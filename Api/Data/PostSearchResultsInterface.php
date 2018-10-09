<?php
/**
 * Himanshu Kubavat
 * Magento2 Sample Blog Extension
 */
namespace Himanshu\Blog\Api\Data;
use Magento\Framework\Api\SearchResultsInterface;
/**
 * Interface for blog post search results.
 * @api
 */
interface PostSearchResultsInterface extends SearchResultsInterface {
    /**
     * Get blocks list.
     *
     * @return \Himanshu\Blog\Api\Data\PostInterface[]
     */
    public function getItems();
    /**
     * Set blocks list.
     *
     * @param \Himanshu\Blog\Api\Data\PostInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}