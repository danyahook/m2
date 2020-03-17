<?php

namespace Oggetto\Test\Model;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Store\Model\StoreManagerInterface;
use Oggetto\Test\Api\Data\NewsInterface;
use Oggetto\Test\Model\News\FileInfo;
use Oggetto\Test\Model\Product\Url;

/**
 * Class News
 */
class News extends \Magento\Framework\Model\AbstractModel implements NewsInterface, IdentityInterface
{
    const CACHE_TAG = 'mage_news';

    /**#@+
     * News statuses
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    /**#@-*/
    protected $_cacheTag = self::CACHE_TAG;

    /**
     * Store manager
     *
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    protected $_urlModel;
    /**
     * Construct.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Oggetto\Test\Model\ResourceModel\News');
        $this->_urlModel = ObjectManager::getInstance()->get(Product\Url::class);
    }

    /**
     * Prevent news recursion
     *
     * @return AbstractModel
     * @throws LocalizedException
     */
    public function beforeSave()
    {
        if ($this->hasDataChanges()) {
            $this->setUpdateTime(null);
        }

        $needle = 'news_id="' . $this->getId() . '"';
        if (false == strstr($this->getContent(), (string) $needle)) {
            return parent::beforeSave();
        }
        throw new LocalizedException(
            __('Make sure that static block content does not reference the block itself.')
        );
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Retrieve news id
     *
     * @return int
     */
    public function getId()
    {
        return $this->getData(self::NEWS_ID);
    }

    /**
     * Retrieve news title
     *
     * @return int
     */
    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    /**
     * Retrieve news creation time
     *
     * @return int
     */
    public function getCreationTime()
    {
        return $this->getData(self::CREATION_TIME);
    }

    /**
     * Retrieve news update time
     *
     * @return int
     */
    public function getUpdateTime()
    {
        return $this->getData(self::UPDATE_TIME);
    }

    /**
     * Retrieve news description
     *
     * @return int
     */
    public function getDescription()
    {
        return $this->getData(self::DESCRIPTION);
    }

    /**
     * Is active
     *
     * @return bool
     */
    public function isActive()
    {
        return (bool)$this->getData(self::IS_ACTIVE);
    }

    /**
     * Retrieve news content
     *
     * @return int
     */
    public function getContent()
    {
        return $this->getData(self::CONTENT);
    }

    /**
     * Set ID
     *
     * @param int $id
     * @return NewsInterface
     */
    public function setId($id)
    {
        return $this->setData(self::NEWS_ID, $id);
    }

    /**
     * Set title
     *
     * @param string $title
     * @return NewsInterface
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * Set creation time
     *
     * @param string $creationTime
     * @return NewsInterface
     */
    public function setCreationTime($creationTime)
    {
        return $this->setData(self::CREATION_TIME, $creationTime);
    }

    /**
     * Set update time
     *
     * @param string $updateTime
     * @return NewsInterface
     */
    public function setUpdateTime($updateTime)
    {
        return $this->setData(self::UPDATE_TIME, $updateTime);
    }

    /**
     * Set description
     *
     * @param string $description
     * @return NewsInterface
     */
    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * Set is active
     *
     * @param bool|int $isActive
     * @return NewsInterface
     */
    public function setIsActive($isActive)
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

    /**
     * Set content
     *
     * @param string $content
     * @return NewsInterface
     */
    public function setContent($content)
    {
        return $this->setData(self::CONTENT, $content);
    }

    /**
     * Retrieve the Image URL
     *
     * @param string $imageName
     * @return bool|string
     * @throws LocalizedException
     */
    public function getImageUrl($imageName = null)
    {
        $url = '';
        $image = $imageName;
        if (!$image) {
            $image = $this->getData('image');
        }
        if ($image) {
            if (is_string($image)) {
                $url = $this->_getStoreManager()->getStore()->getBaseUrl(
                    \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                ) . FileInfo::ENTITY_MEDIA_PATH . '/' . $image;
            } else {
                throw new LocalizedException(
                    __('Something went wrong while getting the image url.')
                );
            }
        }
        return $url;
    }

    public function getNewsUrl()
    {
        return $this->_urlModel->getUrl($this);
    }

    /**
     * Get StoreManagerInterface instance
     *
     * @return StoreManagerInterface
     */
    private function _getStoreManager()
    {
        if ($this->_storeManager === null) {
            $this->_storeManager = ObjectManager::getInstance()->get(StoreManagerInterface::class);
        }
        return $this->_storeManager;
    }
}
