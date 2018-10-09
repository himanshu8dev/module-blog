<?php

/*
 * Himanshu Kubavat
 * Magento2 Sample Blog Extension
 */

namespace Himanshu\Blog\Ui\Component\Adminhtml\Post\Form;

use Himanshu\Blog\Model\ResourceModel\Post\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Store\Model\StoreManagerInterface;
use \Himanshu\Blog\Model\Post as PostModel;

/**
 * Class DataProvider
 */
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider {

    /**
     * @var \Himanshu\Blog\Model\ResourceModel\Post\Collection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $postCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param StoreManagerInterface $storeManager
     * @param array $meta
     * @param array $data
     */
    public function __construct(
    $name, $primaryFieldName, $requestFieldName, CollectionFactory $postCollectionFactory, DataPersistorInterface $dataPersistor, StoreManagerInterface $storeManager, array $meta = [], array $data = []
    ) {
        $this->collection = $postCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->storeManager = $storeManager;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->meta = $this->prepareMeta($this->meta);
    }

    /**
     * Prepares Meta
     *
     * @param array $meta
     * @return array
     */
    public function prepareMeta(array $meta) {
        return $meta;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData() {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();

        /** @var $post \Himanshu\Blog\Model\Post */
        foreach ($items as $post) {
            $this->loadedData[$post->getId()] = $post->getData();

            $this->setPostFeaturedImage($this->loadedData[$post->getId()]);
        }

        $data = $this->dataPersistor->get('blog_post');
        if (!empty($data)) {
            $post = $this->collection->getNewEmptyItem();
            $post->setData($data);
            $this->loadedData[$post->getId()] = $post->getData();
            $this->dataPersistor->clear('blog_post');
        }

        return $this->loadedData;
    }

    public function setPostFeaturedImage(&$post = array()) {
        if (isset($post['featured_image']) && $post['featured_image']) {
            $image[] = [
                "name" => basename($post['featured_image']),
                "url" => $this->getMediaUrl() . $post['featured_image']
            ];
            $post['featured_image'] = $image;
        }
    }

    protected function getMediaUrl() {
        return $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . PostModel::MEDIA_PATH;
    }

}
