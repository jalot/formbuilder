<?php
namespace Wilchers\FormBuilder\Controller\Frontend\Form;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Wilchers\FormBuilder\Model\ResourceModel\Form\CollectionFactory;
use Wilchers\FormBuilder\Model\SubmissionFactory;
use Wilchers\FormBuilder\Model\ResourceModel\Submission as SubmissionResource;
use Wilchers\FormBuilder\Model\Mail\Sender;
use Psr\Log\LoggerInterface;

class Post implements HttpPostActionInterface
{
    public function __construct(
        private RequestInterface   $request,
        private Validator          $formKeyValidator,
        private ManagerInterface   $messageManager,
        private RedirectFactory    $redirectFactory,
        private RedirectInterface  $redirect,
        private CollectionFactory  $collectionFactory,
        private SubmissionFactory  $submissionFactory,
        private SubmissionResource $submissionResource,
        private Sender             $sender,
        private LoggerInterface    $logger
    ) {}

    public function execute()
    {
        $result = $this->redirectFactory->create();

        if (!$this->formKeyValidator->validate($this->request)) {
            $this->messageManager->addErrorMessage('Ongeldig verzoek. Probeer opnieuw.');
            return $result->setUrl($this->redirect->getRefererUrl());
        }

        $identifier = $this->request->getParam('identifier', 'contact');
        $coll = $this->collectionFactory->create()
            ->addFieldToFilter('identifier', $identifier)
            ->addFieldToFilter('is_active', 1);

        if (!$coll->getSize()) {
            $this->messageManager->addErrorMessage('Formulier niet gevonden.');
            return $result->setUrl('/');
        }

        $form     = $coll->getFirstItem();
        $post     = $this->request->getPostValue();
        $fields   = $form->getFieldsConfig();
        $labeled  = [];
        $name     = '';
        $email    = '';

        foreach ($fields as $f) {
            $val = $post[$f['name']] ?? null;
            if ($f['type'] === 'checkbox') {
                $val = !empty($post[$f['name']]) ? 'Ja' : 'Nee';
            }
            if (!empty($f['required']) && $f['type'] !== 'checkbox' && empty($val)) {
                $this->messageManager->addErrorMessage(sprintf('Veld "%s" is verplicht.', $f['label']));
                return $result->setUrl($this->redirect->getRefererUrl());
            }
            if ($val === null || $val === '') continue;
            $labeled[$f['label']] = $val;
            if ($f['name'] === 'name')  $name  = $val;
            if ($f['name'] === 'email') $email = $val;
        }

        $timestamp = (new \DateTime())->format('d-m-Y H:i:s');
        $sendCopy  = !empty($post['send_copy']) && (bool)$form->getData('allow_copy');

        $sub = $this->submissionFactory->create();
        $sub->setData([
            'form_id'         => $form->getId(),
            'form_title'      => $form->getTitle(),
            'submitter_name'  => $name,
            'submitter_email' => $email,
            'data'            => json_encode($labeled, JSON_UNESCAPED_UNICODE),
            'ip_address'      => $this->request->getClientIp(),
        ]);
        try { $this->submissionResource->save($sub); }
        catch (\Exception $e) { $this->logger->error('[FormBuilder] save: ' . $e->getMessage()); }

        try {
            $this->sender->send(
                $form->getData('recipient_email'), $form->getTitle(),
                $labeled, $name, $email, $sendCopy,
                $form->getData('success_message') ?? '', $timestamp
            );
        } catch (\Exception $e) { $this->logger->error('[FormBuilder] mail: ' . $e->getMessage()); }

        $this->messageManager->addSuccessMessage($form->getData('success_message') ?: 'Uw formulier is verzonden.');
        return $result->setUrl($this->redirect->getRefererUrl());
    }
}