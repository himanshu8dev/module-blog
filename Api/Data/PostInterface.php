<?php

/**
 * Himanshu Kubavat
 * Magento2 Sample Blog Extension
 */

namespace Himanshu\Blog\Api\Data;

/**
 * Blog Post interface.
 * @api
 */
interface PostInterface {

    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const POST_ID = 'id';
    const TITLE = 'title';
    const URL_KEY = 'url_key';
    const SHORT_DESCRIPTION = 'short_description';
    const CONTENT = 'content';
    const TAGS = 'tags';
    const STATUS = 'status';
    const FEATURED_IMAGE = 'featured_image';
    const META_TITLE = 'meta_title';
    const META_KEYWORD = 'meta_keyword';
    const META_DESCRIPTION = 'meta_description';
    const ALLOWED_COMMENT = 'allowed_comment';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get Title
     *
     * @return string|null
     */
    public function getTitle();

    /**
     * Get URL
     *
     * @return string
     */
    public function getUrlKey();

    /**
     * Get Short Description
     *
     * @return string|null
     */
    public function getShortDescription();

    /**
     * Get Content
     *
     * @return string|null
     */
    public function getContent();

    /**
     * Get Tags
     *
     * @return string|null
     */
    public function getTags();

    /**
     * Get Status
     *
     * @return string|null
     */
    public function getStatus();

    /**
     * Get Featured Image
     *
     * @return string|null
     */
    public function getFeaturedImage();

    /**
     * Get Meta Title
     *
     * @return string|null
     */
    public function getMetaTitle();

    /**
     * Get Meta Keyword
     *
     * @return string|null
     */
    public function getMetaKeyword();

    /**
     * Get Meta Description
     *
     * @return string|null
     */
    public function getMetaDescription();

    /**
     * Get Allowed Comment Permission
     *
     * @return string|null
     */
    public function getAllowedComment();

    /**
     * Get Creation Time
     *
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Get Update Time
     *
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * Set ID
     *
     * @param int $id
     * @return PostInterface
     */
    public function setId($id);

    /**
     * Set Title
     *
     * @param string $title
     * @return PostInterface
     */
    public function setTitle($title);

    /**
     * Set URL
     *
     * @param string $urlKey
     * @return PostInterface
     */
    public function setUrlKey($urlKey);

    /**
     * Set Short Description
     *
     * @param string $shortDescription
     * @return PostInterface
     */
    public function setShortDescription($shortDescription);

    /**
     * Set Content
     *
     * @param string $content
     * @return PostInterface
     */
    public function setContent($content);

    /**
     * Set Tags
     * 
     * @param string $tags
     * @return PostInterface
     */
    public function setTags($tags);

    /**
     * Set Status
     *
     * @param boolean|int $status
     * @return PostInterface
     */
    public function setStatus($status);

    /**
     * Set Featured Image
     *
     * @param string $featuredImage
     * @return PostInterface
     */
    public function setFeaturedImage($featuredImage);

    /**
     * Set Meta Title
     *
     * @param string $metaTitle
     * @return PostInterface
     */
    public function setMetaTitle($metaTitle);

    /**
     * Set Meta Keyword
     *
     * @param string $metaKeyword
     * @return PostInterface
     */
    public function setMetaKeyword($metaKeyword);

    /**
     * Set Meta Description
     *
     * @param string $metaDescription
     * @return PostInterface
     */
    public function setMetaDescription($metaDescription);

    /**
     * Set Allowed Comment Permission
     *
     * @param boolean|int $allowedComment
     * @return PostInterface
     */
    public function setAllowedComment($allowedComment);

    /**
     * Set Creation Time
     *
     * @param string|datetime $createdAt
     * @return PostInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * Set Update Time
     *
     * @param string|datetime $updatedAt
     * @return PostInterface
     */
    public function setUpdatedAt($updatedAt);
}
