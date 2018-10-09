<?php

/**
 * Himanshu Kubavat
 * Magento2 Sample Blog Extension
 */

namespace Himanshu\Blog\Model\ResourceModel\Post;

use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Blog Post Collection
 */
class Collection extends AbstractCollection {

    /**
     * @var string
     */
    protected $_idFieldName = 'id';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'himanshu_blog_post_collection';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'post_grid_collection';
    
    
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct() {
        $this->_init(\Himanshu\Blog\Model\Post::class, \Himanshu\Blog\Model\ResourceModel\Post::class);
    }

}
