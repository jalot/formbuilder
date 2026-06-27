<?php
namespace Wilchers\FormBuilder\Block\Adminhtml\Submission;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Wilchers\FormBuilder\Model\ResourceModel\Submission\CollectionFactory;

class Grid extends Template
{
    public function __construct(Context $context, private CollectionFactory $collectionFactory, array $data = [])
    { parent::__construct($context, $data); }

    public function getSubmissions() { return $this->collectionFactory->create()->setOrder('submission_id','DESC'); }
}