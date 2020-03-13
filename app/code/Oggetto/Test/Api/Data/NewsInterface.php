<?php

namespace Oggetto\Test\Api\Data;

/**
 * News interface.
 * @api
 * @since 100.0.2
 */
interface NewsInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const NEWS_ID       = 'news_id';
    const TITLE         = 'title';
    const CREATION_TIME = 'creation_time';
    const UPDATE_TIME   = 'update_time';
    const DESCRIPTION   = 'description';
    const IS_ACTIVE     = 'is_active';
    const CONTENT       = 'content';
    /**#@-*/

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get title
     *
     * @return string|null
     */
    public function getTitle();

    /**
     * Get creation time
     *
     * @return string|null
     */
    public function getCreationTime();

    /**
     * Get update time
     *
     * @return string|null
     */
    public function getUpdateTime();

    /**
     * Get description
     *
     * @return string|null
     */
    public function getDescription();

    /**
     * Is active
     *
     * @return bool|null
     */
    public function isActive();

    /**
     * Get content
     *
     * @return string|null
     */
    public function getContent();

    /**
     * Set ID
     *
     * @param int $id
     * @return NewsInterface
     */
    public function setId($id);

    /**
     * Set title
     *
     * @param string $title
     * @return NewsInterface
     */
    public function setTitle($title);

    /**
     * Set creation time
     *
     * @param string $creationTime
     * @return NewsInterface
     */
    public function setCreationTime($creationTime);

    /**
     * Set update time
     *
     * @param string $updateTime
     * @return NewsInterface
     */
    public function setUpdateTime($updateTime);

    /**
     * Set description
     *
     * @param string $description
     * @return NewsInterface
     */
    public function setDescription($description);

    /**
     * Set is active
     *
     * @param bool|int $isActive
     * @return NewsInterface
     */
    public function setIsActive($isActive);

    /**
     * Set content
     *
     * @param string $content
     * @return NewsInterface
     */
    public function setContent($content);
}
