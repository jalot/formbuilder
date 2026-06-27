<?php
namespace Wilchers\FormBuilder\Model\ResourceModel\Submission;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            \Wilchers\FormBuilder\Model\Submission::class,
            \Wilchers\FormBuilder\Model\ResourceModel\Submission::class
        );
    }
}