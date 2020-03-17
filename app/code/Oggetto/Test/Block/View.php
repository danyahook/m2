<?php

namespace Oggetto\Test\Block;

use Magento\Framework\View\Element\Template\Context;
use Oggetto\Test\Model\ResourceModel\News\CollectionFactory;

class View extends \Magento\Framework\View\Element\Template
{
    protected $collectionFactory;

    protected $helperData;

    protected $countOfPosts;

    public function __construct(
        Context $context,
        CollectionFactory $collectionFactory,
        \Oggetto\Test\Helper\Data $helperData
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->helperData = $helperData;
        $this->countOfPosts = $this->helperData->getGeneralConfig('ranges');
        parent::__construct($context);
    }

    public function getNewsCollection()
    {
        $pageId = $this->getRequest()->getParam('id');

        $newsCollection = $this->collectionFactory->create();
        $newsCollection->addFieldToFilter('news_id', ['eq' => $pageId]);
        return $newsCollection;
    }
}
