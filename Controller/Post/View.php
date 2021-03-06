<?php

/*
 * Himanshu Kubavat
 * Magento2 Sample Blog Extension
 */

namespace Himanshu\Blog\Controller\Post;

class View extends \Magento\Framework\App\Action\Action {

    protected $_pageFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context, 
        \Magento\Framework\View\Result\PageFactory $pageFactory
    ) {
        $this->_pageFactory = $pageFactory;
        return parent::__construct($context);
    }

    public function execute() {
        return $this->_pageFactory->create();
    }

}