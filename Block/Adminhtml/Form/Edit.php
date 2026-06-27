<?php
namespace Wilchers\FormBuilder\Block\Adminhtml\Form;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;

class Edit extends Template
{
    public function __construct(Context $context, private Registry $registry, array $data = [])
    { parent::__construct($context, $data); }

    public function getForm() { return $this->registry->registry('current_form'); }
    public function getSaveUrl(){ return $this->getUrl('wilchers_formbuilder/form/save'); }
    public function getBackUrl(){ return $this->getUrl('wilchers_formbuilder/form/index'); }

    public function getFieldTypes(): array {
        return ['text'=>'Tekst','email'=>'E-mail','tel'=>'Telefoon','date'=>'Datum','textarea'=>'Tekstvak','checkbox'=>'Checkbox','select'=>'Keuzelijst'];
    }
}