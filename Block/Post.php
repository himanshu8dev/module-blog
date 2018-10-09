<?php

/*
 * Himanshu Kubavat
 * Magento2 Sample Blog Extension
 */

namespace Himanshu\Blog\Block;

use \Magento\Framework\View\Element\Template\Context;
use \Magento\Framework\App\Config\ScopeConfigInterface;
use \Magento\Framework\View\Page\Config;
use \Magento\Store\Model\StoreManagerInterface;

class Post extends \Magento\Framework\View\Element\Template {

    protected $_scopeConfig;
    protected $_pageConfig;
    protected $_storeManger;

    public function __construct(
        Context $context, 
        ScopeConfigInterface $scopeConfig, 
        Config $pageConfig, 
        StoreManagerInterface $storeManagerInterface,
        $data = array()
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->_pageConfig = $pageConfig;
        $this->_storeManger = $storeManagerInterface;

        parent::__construct($context, $data);
    }

    /**
     * Prepare global layout
     *
     * @return $this
     */
    protected function _prepareLayout() {
        // Add Body Class
        $this->_pageConfig->addBodyClass('blogpost');

        // Page Browser Title
        $this->_pageConfig->getTitle()->set(__("Blog - Post List"));

        // Page Breadcrumbs
        $breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
        if($breadcrumbs){
            $breadcrumbs->addCrumb('home',array('label' => __('Home'), 'title' => __('Home'),'link' => $this->getHomeUrl()));
            $breadcrumbs->addCrumb('page',array('label' => __('Blog'), 'title' => __('Blog')));
        }
        
        // Page Header
        $pageMainTitle = $this->getLayout()->getBlock('page.main.title');
        if ($pageMainTitle) {
            $pageMainTitle->setPageTitle(__("Blog"));
        }

        return parent::_prepareLayout();
    }
    
    protected function getHomeUrl() {
        return $this->_storeManger->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
    }

    /**
     * 
     * @return string
     */
    public function printMessage() {
        return __("Hey, Welcome!!");
    }

}
