<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Oggetto\Test\Model\Product;

use Magento\UrlRewrite\Model\UrlFinderInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Product Url model
 *
 * @api
 * @since 100.0.2
 */
class Url extends \Magento\Framework\DataObject
{
    /**
     * URL instance
     *
     * @var \Magento\Framework\UrlFactory
     */
    protected $urlFactory;

    /**
     * @var \Magento\Framework\Filter\FilterManager
     */
    protected $filter;

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\Session\SidResolverInterface
     */
    protected $sidResolver;

    /**
     * @var \Magento\UrlRewrite\Model\UrlFinderInterface
     */
    protected $urlFinder;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param \Magento\Framework\UrlFactory $urlFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Filter\FilterManager $filter
     * @param \Magento\Framework\Session\SidResolverInterface $sidResolver
     * @param UrlFinderInterface $urlFinder
     * @param array $data
     * @param ScopeConfigInterface|null $scopeConfig
     */
    public function __construct(
        \Magento\Framework\UrlFactory $urlFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Filter\FilterManager $filter,
        \Magento\Framework\Session\SidResolverInterface $sidResolver,
        UrlFinderInterface $urlFinder,
        array $data = [],
        ScopeConfigInterface $scopeConfig = null
    ) {
        parent::__construct($data);
        $this->urlFactory = $urlFactory;
        $this->storeManager = $storeManager;
        $this->filter = $filter;
        $this->sidResolver = $sidResolver;
        $this->urlFinder = $urlFinder;
        $this->scopeConfig = $scopeConfig ?:
            \Magento\Framework\App\ObjectManager::getInstance()->get(ScopeConfigInterface::class);
    }

    /**
     * Retrieve URL in current store
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param array $params the URL route params
     * @return string
     */
    public function getUrlInStore(\Magento\Catalog\Model\Product $product, $params = [])
    {
        $params['_scope_to_url'] = true;
        return $this->getUrl($product, $params);
    }

    /**
     * Retrieve Product URL
     *
     * @param  \Magento\Catalog\Model\Product $product
     * @param  bool $useSid forced SID mode
     * @return string
     */
    public function getProductUrl($product, $useSid = null)
    {
        if ($useSid === null) {
            $useSid = $this->sidResolver->getUseSessionInUrl();
        }

        $params = [];
        if (!$useSid) {
            $params['_nosid'] = true;
        }

        return $this->getUrl($product, $params);
    }

    /**
     * Format Key for URL
     *
     * @param string $str
     * @return string
     */
    public function formatUrlKey($str)
    {
        return $this->filter->translitUrl($str);
    }

    /**
     * Retrieve Product URL using UrlDataObject
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param array $params
     * @return string
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function getUrl(\Oggetto\Test\Model\News $product, $params = [])
    {
        $routeParams = $params;
        $routePath = 'test/index/view';
        $routeParams['id'] = $product->getId();

        $url = $this->urlFactory->create();
        return $url->getUrl($routePath, $routeParams);
    }
}
