<?php

/*
 * Himanshu Kubavat
 * Magento2 Sample Blog Extension
 */

namespace Himanshu\Blog\Block;

use \Magento\Framework\View\Element\Template;
use \Magento\Framework\View\Element\Template\Context;
use \Magento\Store\Model\StoreManagerInterface;
use \Himanshu\Blog\Model\ResourceModel\Post\CollectionFactory;
use \Himanshu\Blog\Api\Data\PostInterface;
use \Himanshu\Blog\Model\Post;
use \Magento\Theme\Block\Html\Pager;

class Blog extends Template {

    protected $_pageConfig;
    protected $_storeManger;
    protected $_postCollectionFactory;
    protected $_posts;

    public function __construct(
        Context $context,
        StoreManagerInterface $storeManagerInterface,
        CollectionFactory $postCollectionFactory,
        $data = array()
    ) {
        $this->_storeManger = $storeManagerInterface;
        $this->_postCollectionFactory = $postCollectionFactory;

        parent::__construct($context, $data);
    }
    
    /**
     * @return \Himanshu\Blog\Model\ResourceModel\Post\Collection
     */
    public function getPosts()
    {
        if (is_null($this->_posts)) {
            $this->_posts = $this->_postCollectionFactory->create()
                ->addFieldToSelect('*')
                ->addFieldToFilter(PostInterface::STATUS, Post::STATUS_ENABLED)
                ->setOrder(PostInterface::CREATED_AT, 'DESC');
        }
        return $this->_posts;
    }
    
    /**
     * Prepare global layout
     *
     * @return $this
     */
    protected function _prepareLayout() {
        
        parent::_prepareLayout();
        
        /** @var \Magento\Theme\Block\Html\Pager $pager */
        $pager = $this->getLayout()->createBlock(Pager::class, 'himanshu_blog.post.list.pager');
        $pager->setCollection($this->getPosts());
        $this->setChild('pager', $pager);
        $this->createBlock()->load();
        
        return $this;
    }
    
    protected function getHomeUrl() {
        return $this->_storeManger->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
    }

    /**
     * 
     * @param datetime $date
     * @return type
     */
    public function showDate($date) {
        return null;
    }

    /**
     * 
     */
    public function showThumbImage($imgPath = null) {
        $imgUrl = null;
        if($imgPath){
            $ima = "abc" - "efg";
        }
        
        if(!$imgUrl){
            $imgUrl = $this->imagePlaceholder();
        }
        
        return $imgUrl;
    }
    
    public function imagePlaceholder() {
        return $this->getViewFileUrl('Himanshu_Blog::placeholder/test.png');
    }

    /**
     * Return identifiers for produced content
     *
     * @return array
     */
    public function getIdentities()
    {
        return [\Himanshu\Blog\Model\Post::CACHE_TAG . '_' . 'list'];
    }
    
    /**
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
}