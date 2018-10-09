<?php

/**
 * Himanshu Kubavat
 * Magento2 Sample Blog Extension
 */

namespace Himanshu\Blog\Model;


use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject\IdentityInterface;
use \Himanshu\Blog\Api\Data\PostInterface;

/**
 * Blog Post model
 *
 * @method array getStoreId()
 */
class Post extends AbstractModel implements PostInterface, IdentityInterface {

    /**
     * Blog post cache tag
     */
    const CACHE_TAG = 'himanshu_blog_p';
    
    protected $_cacheTag = self::CACHE_TAG;
    
    /**
     * Post's statuses
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    
    /**
     * Blog Post Module's Media Directories
     */
    const MEDIA_MAIN_PATH = 'himanshu/';
    const MEDIA_TMP_PATH = self::MEDIA_MAIN_PATH . 'blog/tmp_post/';
    const MEDIA_PATH = self::MEDIA_MAIN_PATH . 'blog/post/';
    const MEDIA_RESIZED_PATH = self::MEDIA_PATH . 'resized/';
    const MEDIA_RESIZED_295x280 = self::MEDIA_RESIZED_PATH . '295x280/';
    const MEDIA_RESIZED_1024x480 = self::MEDIA_RESIZED_PATH . '1024x480/';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'himanshu_blog_post';

    /**
     * @return void
     */
    protected function _construct() {
        $this->_init(\Himanshu\Blog\Model\ResourceModel\Post::class);
    }

    /**
     * Prevent posts recursion
     *
     * @return AbstractModel
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function beforeSave() {
        $needle = 'post_id="' . $this->getId() . '"';

        if ($this->hasDataChanges()) {
            $this->setUpdateTime(null);
        }

        if (false == strstr($this->getContent(), $needle)) {
            return parent::beforeSave();
        }
        throw new \Magento\Framework\Exception\LocalizedException(
        __('Make sure that post content does not reference the post itself.')
        );
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities() {
        return [self::CACHE_TAG . '_' . $this->getId(), self::CACHE_TAG . '_' . $this->getIdentifier()];
    }

    /**
     * Retrieve Post ID
     *
     * @return int
     */
    public function getId() {
        return $this->getData(self::POST_ID);
    }

    /**
     * Retrieve Post Title
     *
     * @return string
     */
    public function getTitle() {
        return $this->getData(self::TITLE);
    }

    /**
     * Retrieve Post Url
     *
     * @return string
     */
    public function getUrlKey() {
        return $this->getData(self::URL_KEY);
    }

    /**
     * Retrieve Post Short Description
     *
     * @return string
     */
    public function getShortDescription() {
        return $this->getData(self::SHORT_DESCRIPTION);
    }

    /**
     * Retrieve Post Content
     *
     * @return string
     */
    public function getContent() {
        return $this->getData(self::CONTENT);
    }

    /**
     * Retrieve Post Tags
     *
     * @return string
     */
    public function getTags() {
        return $this->getData(self::TAGS);
    }

    /**
     * Retrieve Post Status
     *
     * @return boolean|int
     */
    public function getStatus() {
        return $this->getData(self::STATUS);
    }

    /**
     * Retrieve Post Image
     *
     * @return string
     */
    public function getFeaturedImage() {
        return $this->getData(self::FEATURED_IMAGE);
    }

    /**
     * Retrieve Post Meta Title
     *
     * @return string
     */
    public function getMetaTitle() {
        return $this->getData(self::META_TITLE);
    }

    /**
     * Retrieve Post Meta Keyword
     *
     * @return string
     */
    public function getMetaKeyword() {
        return $this->getData(self::META_KEYWORD);
    }

    /**
     * Retrieve Post Meta Description
     *
     * @return string
     */
    public function getMetaDescription() {
        return $this->getData(self::META_DESCRIPTION);
    }

    /**
     * Retrieve Post Allowed Comment
     *
     * @return string
     */
    public function getAllowedComment() {
        return $this->getData(self::ALLOWED_COMMENT);
    }

    /**
     * Retrieve Post Created Time
     *
     * @return string
     */
    public function getCreatedAt() {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * Retrieve Post Updated Time
     *
     * @return string
     */
    public function getUpdatedAt() {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * Set ID
     *
     * @param int $id
     * @return PostInterface
     */
    public function setId($id) {
        return $this->setData(self::POST_ID, $id);
    }

    /**
     * Set Post Title
     *
     * @param string $title
     * @return PostInterface
     */
    public function setTitle($title) {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * Set Post Url
     *
     * @param string $urlKey
     * @return PostInterface
     */
    public function setUrlKey($urlKey) {
        return $this->setData(self::URL_KEY, $urlKey);
    }

    /**
     * Set Post Short Description
     *
     * @param string $shortDescription
     * @return PostInterface
     */
    public function setShortDescription($shortDescription) {
        return $this->setData(self::SHORT_DESCRIPTION, $shortDescription);
    }

    /**
     * Set Post Content
     *
     * @param string $content
     * @return PostInterface
     */
    public function setContent($content) {
        return $this->setData(self::CONTENT, $content);
    }

    /**
     * Set Post Tags
     *
     * @param string $tags
     * @return PostInterface
     */
    public function setTags($tags) {
        return $this->setData(self::TAGS, $tags);
    }

    /**
     * Set Post Status
     *
     * @param boolean|int $status
     * @return PostInterface
     */
    public function setStatus($status) {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * Set Post Image
     *
     * @param string $featuredImage
     * @return PostInterface
     */
    public function setFeaturedImage($featuredImage) {
        return $this->setData(self::FEATURED_IMAGE, $featuredImage);
    }

    /**
     * Set Post Meta Title
     *
     * @param string $metaTitle
     * @return PostInterface
     */
    public function setMetaTitle($metaTitle) {
        return $this->setData(self::META_TITLE, $metaTitle);
    }

    /**
     * Set Post Meta Keyword
     *
     * @param string $metaKeyword
     * @return PostInterface
     */
    public function setMetaKeyword($metaKeyword) {
        return $this->setData(self::META_KEYWORD, $metaKeyword);
    }

    /**
     * Retrieve Post Meta Description
     *
     * @param string $metaDescription
     * @return PostInterface
     */
    public function setMetaDescription($metaDescription) {
        return $this->setData(self::META_DESCRIPTION, $metaDescription);
    }

    /**
     * Set Post Allowed Comment
     *
     * @param boolean|int $allowedComment
     * @return PostInterface
     */
    public function setAllowedComment($allowedComment) {
        return $this->setData(self::ALLOWED_COMMENT, $allowedComment);
    }

    /**
     * Set Post Created Time
     *
     * @param string|datetime $createdAt
     * @return PostInterface
     */
    public function setCreatedAt($createdAt) {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Set Post Updated Time
     *
     * @param string|datetime $updatedAt
     * @return PostInterface
     */
    public function setUpdatedAt($updatedAt) {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }

    /**
     * Prepare post's statuses.
     *
     * @return array
     */
    public function getPostStatuses() {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }
    
    /**
     * Get Media Directories
     */
    public static function getPostMedia($imgType = null) {
        $dir = self::MEDIA_PATH;

        switch ($imgType) {
            case "thumb":
                $dir = self::MEDIA_RESIZED_295x280;
                break;

            case "large":
                $dir = self::MEDIA_RESIZED_1024x480;
                break;

            default:
                break;
        }
        return $dir;
    }
}
