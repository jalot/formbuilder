<?php
namespace Wilchers\FormBuilder\Model;

use Magento\Framework\Model\AbstractModel;

class Submission extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(\Wilchers\FormBuilder\Model\ResourceModel\Submission::class);
    }

    public function getDataArray(): array
    {
        $json = $this->getData('data');
        return $json ? (json_decode($json, true) ?: []) : [];
    }
}