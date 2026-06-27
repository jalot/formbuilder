<?php
namespace Wilchers\FormBuilder\Controller\Adminhtml\Form;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Wilchers\FormBuilder\Model\FormFactory;
use Wilchers\FormBuilder\Model\ResourceModel\Form as FormResource;

class Save extends Action
{
    const ADMIN_RESOURCE = 'Wilchers_FormBuilder::form';

    public function __construct(Context $context, private FormFactory $formFactory, private FormResource $formResource)
    { parent::__construct($context); }

    public function execute()
    {
        $redirect = $this->resultRedirectFactory->create();
        $data     = $this->getRequest()->getPostValue();
        if (!$data) return $redirect->setPath('*/*/index');

        $id   = (int)($data['form_id'] ?? 0);
        $form = $this->formFactory->create();
        if ($id) $this->formResource->load($form, $id);

        $fields = [];
        foreach ($data['fields'] ?? [] as $f) {
            if (!empty($f['label'])) {
                $fields[] = [
                    'name'     => preg_replace('/[^a-z0-9_]/', '_', strtolower($f['name'] ?? $f['label'])),
                    'label'    => trim($f['label']),
                    'type'     => $f['type'] ?? 'text',
                    'required' => !empty($f['required']),
                ];
            }
        }

        $form->setData([
            'title'           => $data['title'],
            'identifier'      => preg_replace('/[^a-z0-9_-]/', '-', strtolower($data['identifier'])),
            'recipient_email' => $data['recipient_email'],
            'allow_copy'      => (int)($data['allow_copy'] ?? 1),
            'success_message' => $data['success_message'],
            'is_active'       => (int)($data['is_active'] ?? 1),
        ]);
        $form->setFieldsConfig($fields);
        if ($id) $form->setId($id);

        try {
            $this->formResource->save($form);
            $this->messageManager->addSuccessMessage('Formulier opgeslagen.');
            return $redirect->setPath('*/*/edit', ['form_id' => $form->getId()]);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage('Fout: ' . $e->getMessage());
            return $redirect->setPath('*/*/edit', ['form_id' => $id]);
        }
    }
}