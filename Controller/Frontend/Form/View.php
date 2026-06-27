<?php
namespace Wilchers\FormBuilder\Controller\Frontend\Form;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Result\PageFactory;
use Wilchers\FormBuilder\Model\ResourceModel\Form\CollectionFactory;

class View implements HttpGetActionInterface
{
    public function __construct(
        private RequestInterface  $request,
        private PageFactory       $pageFactory,
        private CollectionFactory $collectionFactory
    ) {}

    public function execute()
    {
        $id   = $this->request->getParam('id', 'contact');
        $coll = $this->collectionFactory->create()
            ->addFieldToFilter('identifier', $id)
            ->addFieldToFilter('is_active', 1);

        $page = $this->pageFactory->create();
        if ($coll->getSize()) {
            $page->getConfig()->getTitle()->set($coll->getFirstItem()->getTitle());
        }
        return $page;
    }
}