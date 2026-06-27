<?php
namespace Wilchers\FormBuilder\Model\ResourceModel\Form;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            \Wilchers\FormBuilder\Model\Form::class,
            \Wilchers\FormBuilder\Model\ResourceModel\Form::class
        );
    }
}