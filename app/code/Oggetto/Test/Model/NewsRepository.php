<?php

namespace Oggetto\Test\Model;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Oggetto\Test\Api\Data;
use Oggetto\Test\Api\Data\NewsInterface;
use Oggetto\Test\Api\NewsRepositoryInterface;
use Oggetto\Test\Model\ResourceModel\News as ResourceNews;

/**
 * Class NewsRepository
 */
class NewsRepository implements NewsRepositoryInterface
{
    /**
     * @var ResourceNews
     */
    protected $resource;

    /**
     * @var NewsFactory
     */
    protected $newsFactory;

    /**
     * @param ResourceNews $resource
     * @param NewsFactory $newsFactory
     */
    public function __construct(
        ResourceNews $resource,
        NewsFactory $newsFactory
    ) {
        $this->resource = $resource;
        $this->newsFactory = $newsFactory;
    }

    /**
     * Save News data
     *
     * @param NewsInterface $news
     * @return NewsInterface
     * @throws CouldNotSaveException
     */
    public function save(NewsInterface $news)
    {
        try {
            $this->resource->save($news);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $news;
    }

    /**
     * Load News data by given News Identity
     *
     * @param string $newsId
     * @return News
     * @throws NoSuchEntityException
     */
    public function getById($newsId)
    {
        $news = $this->newsFactory->create();
        $this->resource->load($news, $newsId);
        if (!$news->getId()) {
            throw new NoSuchEntityException(__('The news with the "%1" ID doesn\'t exist.', $newsId));
        }
        return $news;
    }

    /**
     * Delete News
     *
     * @param NewsInterface $news
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(NewsInterface $news)
    {
        try {
            $this->resource->delete($news);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Delete News by given News Identity
     *
     * @param string $newsId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($newsId)
    {
        return $this->delete($this->getById($newsId));
    }
}
