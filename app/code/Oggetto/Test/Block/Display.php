<?php

namespace Oggetto\Test\Block;

use Magento\Framework\View\Element\Template\Context;
use Oggetto\Test\Model\ResourceModel\News\CollectionFactory;

class Display extends \Magento\Framework\View\Element\Template
{
    protected $collectionFactory;

    protected $helperData;

    protected $countOfPosts;

    protected $_isExpanded = true;

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

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->pageConfig->getTitle()->set(__('News'));

        if ($this->getNewsCollection()) {
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'test.news.pager'
            )->setLimit($this->countOfPosts)->setShowPerPage(false)->setCollection(
                $this->getNewsCollection()
            );
            $this->setChild('pager', $pager);
            $this->getNewsCollection()->load();
        }

        $select = $this->getLayout()->createBlock(
            '\Magento\Framework\View\Element\Html\Select',
            'test.news.select'
        )->setId('sorter')->setName('sorter')->setClass('sorter-options')->setTitle('title')->setOptions([
            "asc" => "Сначала старые",
            "desc" => "Сначала новые"
        ]);
        $this->setChild('select', $select);

        return $this;
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    public function getSelectHtml()
    {
        return $this->getChildHtml('select');
    }

    public function getPagerUrl($params = [])
    {
        $urlParams = [];
        $urlParams['_current'] = true;
        $urlParams['_escape'] = false;
        $urlParams['_use_rewrite'] = true;
        $urlParams['_query'] = $params;
        return $this->getUrl('*/*/*', $urlParams);
    }

    public function getWidgetOptionsJson(array $customOptions = [])
    {
        $options = [
            'select' => '[name="sorter"]',
            'url' => $this->getPagerUrl(),
            'sort' => 'sort',
            'default' => $this->getSelected()
        ];
        $options = array_replace_recursive($options, $customOptions);
        return json_encode(['productListToolbarForm' => $options]);
    }

    private function getSelected()
    {
        return  ($this->getRequest()->getParam('sort')) ? $this->getRequest()->getParam('sort') : 'desc';
    }

    public function getNewsCollection()
    {
        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
        $sort = ($this->getRequest()->getParam('sort')) ? $this->getRequest()->getParam('sort') : 'desc';

        $newsCollection = $this->collectionFactory->create();
        $newsCollection->addFieldToFilter('is_active', ['eq' => true]);
        $newsCollection->setOrder('creation_time', $sort);
        $newsCollection->setPageSize($this->countOfPosts);
        $newsCollection->setCurPage($page);
        return $newsCollection;
    }

    public function isExpanded()
    {
        return $this->_isExpanded;
    }
}
