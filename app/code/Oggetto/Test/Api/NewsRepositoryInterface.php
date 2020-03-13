<?php

namespace Oggetto\Test\Api;

/**
 * News CRUD interface.
 * @api
 * @since 100.0.2
 */
interface NewsRepositoryInterface
{
    /**
     * Save news.
     *
     * @param \Oggetto\Test\Api\Data\NewsInterface $news
     * @return \Oggetto\Test\Api\Data\NewsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(Data\NewsInterface $news);

    /**
     * Retrieve news.
     *
     * @param int $newsId
     * @return \Oggetto\Test\Api\Data\NewsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($newsId);

    /**
     * Delete news.
     *
     * @param \Oggetto\Test\Api\Data\NewsInterface $news
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(Data\NewsInterface $news);

    /**
     * Delete news by ID.
     *
     * @param int $newsId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($newsId);
}
