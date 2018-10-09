<?php

/*
 * Himanshu Kubavat
 * Magento2 Sample Blog Extension
 */

namespace Himanshu\Blog\Controller\Adminhtml\Post;

use \Magento\Backend\App\Action;
use \Magento\Backend\App\Action\Context;
use \Magento\Framework\View\Result\PageFactory;
use \Magento\Framework\Registry;
use \Himanshu\Blog\Api\PostRepositoryInterface;

class Edit extends Action {

    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Himanshu_Blog::post_update';

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Himanshu\Blog\Api\PostRepositoryInterface
     */
    protected $_postRepositoryInterface;

    /**
     * Constructor
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Registry $registry
     * @param PostRepositoryInterface $postRepositoryInterface
     */
    public function __construct(
        Context $context, PageFactory $resultPageFactory, Registry $registry, PostRepositoryInterface $postRepositoryInterface
    ) {
        parent::__construct($context);
        $this->_coreRegistry = $registry;
        $this->_resultPageFactory = $resultPageFactory;
        $this->_postRepositoryInterface = $postRepositoryInterface;
    }

    protected function _initAction() {
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('Himanshu_Blog::post_new');
        $resultPage->addBreadcrumb(__('Blog'), __('Blog'));
        $resultPage->addBreadcrumb(__('Manage Posts'), __('Manage Posts'));

        return $resultPage;
    }

    /**
     * Load the page defined in view/adminhtml/layout/blog_post_index.xml
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute() {

        // GET ID of current Post
        $id = $this->getRequest()->getParam('id');

        // SET Post registry
        $postModel = $this->setPostRegistryData($id);

        /* Set Post page Variable */
        $resultPage = $this->_initAction();
        
        if($id){
            $resultPage->setActiveMenu('Himanshu_Blog::post_update');
        }
        
        $resultPage->addBreadcrumb($id ? __('Edit Post') : __('New Post'), $id ? __('Edit Post') : __('New Post'));
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Post'));
        $resultPage->getConfig()->getTitle()->prepend($postModel->getId() ? $postModel->getTitle() : __('New Post'));

        return $resultPage;
    }

    protected function setPostRegistryData($id) {
        $model = $this->_objectManager->create(\Himanshu\Blog\Model\Post::class);
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This post no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->_coreRegistry->register('blog_post', $model);
        return $model;
    }

}
