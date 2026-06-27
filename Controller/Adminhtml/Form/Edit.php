<?php
namespace Wilchers\FormBuilder\Controller\Adminhtml\Form;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry;
use Wilchers\FormBuilder\Model\FormFactory;
use Wilchers\FormBuilder\Model\ResourceModel\Form as FormResource;

class Edit extends Action
{
    const ADMIN_RESOURCE = 'Wilchers_FormBuilder::form';

    public function __construct(
        Context        $context,
        private PageFactory  $pageFactory,
        private Registry     $registry,
        private FormFactory  $formFactory,
        private FormResource $formResource
    ) { parent::__construct($context); }

    public function execute()
    {
        $id   = (int)$this->getRequest()->getParam('form_id');
        $form = $this->formFactory->create();
        if ($id) {
            $this->formResource->load($form, $id);
            if (!$form->getId()) {
                $this->messageManager->addErrorMessage('Formulier niet gevonden.');
                return $this->resultRedirectFactory->create()->setPath('*/*/index');
            }
        }
        $this->registry->register('current_form', $form);
        $page = $this->pageFactory->create();
        $page->getConfig()->getTitle()->prepend($id ? 'Bewerken: ' . $form->getTitle() : 'Nieuw formulier');
        return $page;
    }
}