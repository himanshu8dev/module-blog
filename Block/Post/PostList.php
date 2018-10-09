<?php 

namespace Himanshu\Blog\Block\Post;

use Magento\Framework\UrlFactory;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Theme\Block\Html\Pager;
use Himanshu\Blog\Model\Post;
use Himanshu\Blog\Model\ResourceModel\Post\CollectionFactory as PostCollectionFactory;
use \Magento\Framework\UrlInterface;
use \Magento\Store\Model\StoreManagerInterface;

class PostList extends Template
{
    /**
     * @var PostCollectionFactory
     */
    protected $postCollectionFactory;
    /**
     * @var UrlFactory
     */
    protected $urlFactory;

    /**
     * @var \Himanshu\Blog\Model\ResourceModel\Post\Collection
     */
    protected $posts;
    
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param Context $context
     * @param PostCollectionFactory $postCollectionFactory
     * @param UrlFactory $urlFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        PostCollectionFactory $postCollectionFactory,
        UrlFactory $urlFactory,
        StoreManagerInterface $storeManager,
        array $data = []
    ) {
        $this->postCollectionFactory = $postCollectionFactory;
        $this->urlFactory = $urlFactory;
        $this->storeManager = $storeManager;
        parent::__construct($context, $data);
    }

    /**
     * @return \Himanshu\Blog\Model\ResourceModel\Post\Collection
     */
    public function getPosts()
    {
        if (is_null($this->posts)) {
            $this->posts = $this->postCollectionFactory->create()
                ->addFieldToSelect('*')
                ->addFieldToFilter(Post::STATUS, Post::STATUS_ENABLED)
                ->setOrder(Post::CREATED_AT, 'DESC');
        }
        return $this->posts;
    }

    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        // Page Breadcrumbs
        $breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
        if($breadcrumbs) {
            $breadcrumbs->addCrumb('home',array('label' => __('Home'), 'title' => __('Home'),'link' => $this->_homeUrl()));
            $breadcrumbs->addCrumb('page',array('label' => __('Blog'), 'title' => __('Blog')));
        }
        
        // Page Header
        $pageMainTitle = $this->getLayout()->getBlock('page.main.title');
        if ($pageMainTitle) {
            $pageMainTitle->setPageTitle(__("Blog"));
        }
        
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock(Pager::class, 'himanshu_blog.post.list.pager');
        $pager->setCollection($this->getPosts());
        $this->setChild('pager', $pager);
        $this->getPosts()->load();
        return $this;
    }

    /**
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
    
    /**
     * 
     */
    public function showThumbImage($image = null, $imageSize = 'thumb') {
        $imgUrl = null;
        
        if ($image) {
            $imgUrl = $this->_mediaUrl() . Post::getPostMedia($imageSize) . $image;
        }

        if (!$imgUrl) {
            $imgUrl = $this->imagePlaceholder();
        }

        return $imgUrl;
    }

    public function imagePlaceholder() {
        return $this->getViewFileUrl('Himanshu_Blog::images/grey-placeholder.png');
    }
    
    public function showFormatedDate($date = null) {
        return "";
    }
    
    public function getPostURL($id) {
        return $this->_homeUrl().DIRECTORY_SEPARATOR."blog/post/view/id/".$id;
    }
    
    protected function _homeUrl() {
        return $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_WEB);
    }
    
    protected function _mediaUrl() {
        return $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
    }
    
}
