<?php
namespace Wilchers\FormBuilder\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Submission extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('wilchers_form_submission', 'submission_id');
    }
}