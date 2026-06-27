<?php
namespace Wilchers\FormBuilder\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Form extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('wilchers_form', 'form_id');
    }
}