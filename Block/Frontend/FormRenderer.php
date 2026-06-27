<?php
namespace Wilchers\FormBuilder\Block\Frontend;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Wilchers\FormBuilder\Model\ResourceModel\Form\CollectionFactory;

class FormRenderer extends Template
{
    private $formModel = null;

    public function __construct(Context $context, private CollectionFactory $collectionFactory, array $data = [])
    { parent::__construct($context, $data); }

    public function getForm(): ?\Wilchers\FormBuilder\Model\Form
    {
        if ($this->formModel === null) {
            $id   = $this->getRequest()->getParam('id', 'contact');
            $coll = $this->collectionFactory->create()
                ->addFieldToFilter('identifier', $id)
                ->addFieldToFilter('is_active', 1);
            $this->formModel = $coll->getSize() ? $coll->getFirstItem() : false;
        }
        return $this->formModel ?: null;
    }

    public function getFormAction(): string { return $this->getUrl('formulier/form/post'); }
    public function getIdentifier(): string { return $this->getRequest()->getParam('id', 'contact'); }
}