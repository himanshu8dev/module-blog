<?php

/**
 * Himanshu Kubavat
 * Magento2 Sample Blog Extension
 */

namespace Himanshu\Blog\Model\ResourceModel\Post\Grid;

use \Himanshu\Blog\Model\ResourceModel\Post\Collection as PostCollection;
use \Magento\Framework\Api\Search\SearchResultInterface;

class Collection extends PostCollection implements SearchResultInterface {

    protected $_idFieldName = 'id';

    /**
     *
     * @param \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory            
     * @param \Psr\Log\LoggerInterface $logger            
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy            
     * @param \Magento\Framework\Event\ManagerInterface $eventManager            
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager            
     * @param mixed|null $mainTable            
     * @param \Magento\Framework\Model\ResourceModel\Db\AbstractDb $eventPrefix            
     * @param mixed $eventObject            
     * @param mixed $resourceModel            
     * @param string $model            
     * @param null $connection            
     * @param \Magento\Framework\Model\ResourceModel\Db\AbstractDb|null $resource
     *            @SuppressWarnings(PHPMD.ExcessiveParameterList)
     *            
     */
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory, 
        \Psr\Log\LoggerInterface $logger, 
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy, 
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager, 
        $mainTable, 
        $eventPrefix, 
        $eventObject, 
        $resourceModel, 
        $model = \Magento\Framework\View\Element\UiComponent\DataProvider\Document::class,
        $connection = null, 
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
        $this->logger = $logger;
        $this->_eventPrefix = $eventPrefix;
        $this->_eventObject = $eventObject;
        $this->_init($model, $resourceModel);
        $this->setMainTable($mainTable);
        $this->storeManager = $storeManager;
    }

    /**
     *
     * @return \Magento\Framework\Api\Search\AggregationInterface
     */
    public function getAggregations() {
        return $this->aggregations;
    }

    /**
     *
     * @param \Magento\Framework\Api\Search\AggregationInterface $aggregations            
     * @return void
     */
    public function setAggregations($aggregations) {
        $this->aggregations = $aggregations;
    }

    /**
     *
     * @return \Magento\Framework\Api\Search\SearchCriteriaInterface|null
     */
    public function getSearchCriteria() {
        return $this->searchCriteria;
    }

    /**
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria            
     * @return $this @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function setSearchCriteria(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria) {
        $this->searchCriteria = $searchCriteria;
        return $this;
    }

    /**
     * Get total count.
     *
     * @return int
     */
    public function getTotalCount() {
        return $this->getSize();
    }

    /**
     * Set total count.
     *
     * @param int $totalCount            
     * @return $this @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function setTotalCount($totalCount) {
        return $this;
    }

    /**
     * Set items list.
     *
     * @param \Magento\Framework\Api\ExtensibleDataInterface[] $items            
     * @return $this @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function setItems(array $items = null) {
        return $this;
    }

    /**
     * This is the function that will add the filter
     */
    protected function _beforeLoad() {
        parent::_beforeLoad();
        return $this;
    }

}