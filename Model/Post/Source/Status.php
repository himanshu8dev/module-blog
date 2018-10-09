<?php

/**
 * Himanshu Kubavat
 * Magento2 Sample Blog Extension
 */

namespace Himanshu\Blog\Model\Post\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class IsActive
 */
class Status implements OptionSourceInterface {

    /**
     * @var \Himanshu\Blog\Model\Post
     */
    protected $blogPost;

    /**
     * Constructor
     *
     * @param \Himanshu\Blog\Model\Post $blogPost
     */
    public function __construct(\Himanshu\Blog\Model\Post $blogPost) {
        $this->blogPost = $blogPost;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray() {
        $availableOptions = $this->blogPost->getPostStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }

}
