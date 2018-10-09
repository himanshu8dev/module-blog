<?php

/**
 * Himanshu Kubavat
 * Magento2 Sample Blog Extension
 */

namespace Himanshu\Blog\Block\Adminhtml\Post\Edit\Buttons;

use Magento\Backend\Block\Widget\Context;
use Himanshu\Blog\Api\PostRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class Generic Button
 */
class GenericButton {

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var PostRepositoryInterface
     */
    protected $_postRepository;

    /**
     * @param Context $context
     * @param PostRepositoryInterface $postRepository
     */
    public function __construct(
    Context $context, PostRepositoryInterface $postRepository
    ) {
        $this->context = $context;
        $this->_postRepository = $postRepository;
    }

    /**
     * Return Blog post ID
     *
     * @return int|null
     */
    public function getPostId() {
        try {
            return $this->_postRepository->getById(
                            $this->context->getRequest()->getParam('id')
                    )->getId();
        } catch (NoSuchEntityException $e) {
            
        }

        return null;
    }

    /**
     * Generate url by route and parameters
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    public function getUrl($route = '', $params = []) {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }

}
