<?php

/*
 * Himanshu Kubavat
 * Magento2 Sample Blog Extension
 */

namespace Himanshu\Blog\Controller\Adminhtml\Post;

class NewAction extends \Magento\Backend\App\Action {

    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Himanshu_Blog::post_new';

    /**
     * @var \Magento\Backend\Model\View\Result\ForwardFactory
     */
    protected $forwardPostFactory;

    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $forwardPostFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Backend\Model\View\Result\ForwardFactory $forwardPostFactory
    ) {
        parent::__construct($context);
        $this->forwardPostFactory = $forwardPostFactory;
    }

    /**
     * Load the page defined in view/adminhtml/layout/blog_post_index.xml
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute() {
        $forwardPost = $this->forwardPostFactory->create();
        $forwardPost->forward('edit');
        
        return $forwardPost;
    }

}
