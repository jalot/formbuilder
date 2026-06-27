<?php
namespace Wilchers\FormBuilder\Controller\Adminhtml\Form;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    const ADMIN_RESOURCE = 'Wilchers_FormBuilder::form';

    public function __construct(Context $context, private PageFactory $pageFactory)
    { parent::__construct($context); }

    public function execute()
    {
        $page = $this->pageFactory->create();
        $page->getConfig()->getTitle()->prepend('Formulieren');
        return $page;
    }
}