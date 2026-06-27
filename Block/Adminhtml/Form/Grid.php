<?php
namespace Wilchers\FormBuilder\Block\Adminhtml\Form;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Wilchers\FormBuilder\Model\ResourceModel\Form\CollectionFactory;

class Grid extends Template
{
    public function __construct(Context $context, private CollectionFactory $collectionFactory, array $data = [])
    { parent::__construct($context, $data); }

    public function getForms() { return $this->collectionFactory->create()->setOrder('form_id','DESC'); }
    public function getNewUrl()    { return $this->getUrl('wilchers_formbuilder/form/edit'); }
    public function getEditUrl($id){ return $this->getUrl('wilchers_formbuilder/form/edit', ['form_id'=>$id]); }
    public function getDeleteUrl($id){ return $this->getUrl('wilchers_formbuilder/form/delete', ['form_id'=>$id]); }
}