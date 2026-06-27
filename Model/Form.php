<?php
namespace Wilchers\FormBuilder\Model;

use Magento\Framework\Model\AbstractModel;

class Form extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(\Wilchers\FormBuilder\Model\ResourceModel\Form::class);
    }

    public function getFieldsConfig(): array
    {
        $json = $this->getData('fields_config');
        return $json ? (json_decode($json, true) ?: []) : [];
    }

    public function setFieldsConfig(array $fields): self
    {
        return $this->setData('fields_config', json_encode($fields, JSON_UNESCAPED_UNICODE));
    }
}