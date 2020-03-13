<?php namespace Oggetto\Test\Model\ResourceModel\News;

/**
 * Class Collection
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'news_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Oggetto\Test\Model\News', 'Oggetto\Test\Model\ResourceModel\News');
    }

}
