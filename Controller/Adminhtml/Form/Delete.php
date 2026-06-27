<?php
namespace Wilchers\FormBuilder\Controller\Adminhtml\Form;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Wilchers\FormBuilder\Model\FormFactory;
use Wilchers\FormBuilder\Model\ResourceModel\Form as FormResource;

class Delete extends Action
{
    const ADMIN_RESOURCE = 'Wilchers_FormBuilder::form';

    public function __construct(Context $context, private FormFactory $formFactory, private FormResource $formResource)
    { parent::__construct($context); }

    public function execute()
    {
        $id = (int)$this->getRequest()->getParam('form_id');
        $redirect = $this->resultRedirectFactory->create();
        if (!$id) return $redirect->setPath('*/*/index');
        $form = $this->formFactory->create();
        $this->formResource->load($form, $id);
        try {
            $this->formResource->delete($form);
            $this->messageManager->addSuccessMessage('Formulier verwijderd.');
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        return $redirect->setPath('*/*/index');
    }
}