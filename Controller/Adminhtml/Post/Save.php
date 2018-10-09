<?php

/*
 * Himanshu Kubavat
 * Magento2 Sample Blog Extension
 */

namespace Himanshu\Blog\Controller\Adminhtml\Post;

use Magento\Backend\App\Action;
use Himanshu\Blog\Model\Post;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Description of Save
 *
 * @author himanshu
 */
class Save extends \Magento\Backend\App\Action {

    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Himanshu_Blog::post_update';

    /**
     * @var PostDataProcessor
     */
    protected $dataProcessor;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var \Himanshu\Blog\Model\PostFactory
     */
    private $postFactory;

    /**
     * @var \Himanshu\Blog\Api\PostRepositoryInterface
     */
    private $postRepository;

    /**
     * @param Action\Context $context
     * @param PostDataProcessor $dataProcessor
     * @param DataPersistorInterface $dataPersistor
     * @param \Himanshu\Blog\Model\PostFactory $postFactory
     * @param \Himanshu\Blog\Api\PostRepositoryInterface $postRepository
     */
    public function __construct(
    Action\Context $context, PostDataProcessor $dataProcessor, DataPersistorInterface $dataPersistor, \Himanshu\Blog\Model\PostFactory $postFactory = null, \Himanshu\Blog\Api\PostRepositoryInterface $postRepository = null
    ) {
        $this->dataProcessor = $dataProcessor;
        $this->dataPersistor = $dataPersistor;
        $this->postFactory = $postFactory ?: \Magento\Framework\App\ObjectManager::getInstance()->get(\Himanshu\Blog\Model\PostFactory::class);
        $this->postRepository = $postRepository ?: \Magento\Framework\App\ObjectManager::getInstance()
                        ->get(\Himanshu\Blog\Api\PostRepositoryInterface::class);

        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute() {
        $data = $this->getRequest()->getPostValue();

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $data = $this->dataProcessor->filter($data);

            if (isset($data['status']) && $data['status'] === 'true') {
                $data['status'] = Post::STATUS_ENABLED;
            }

            if (empty($data['id'])) {
                $data['id'] = null;
            }

            /** @var \Himanshu\Blog\Model\Post $model */
            $model = $this->postFactory->create();

            $id = $this->getRequest()->getParam('id');
            if ($id) {
                try {
                    $model = $this->postRepository->getById($id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This post no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $model->setData($data);

            $this->_eventManager->dispatch(
                    'blog_post_prepare_save', ['post' => $model, 'request' => $this->getRequest()]
            );

            if (!$this->dataProcessor->validate($data)) {
                return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
            }

            try {
                $this->postRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the post.'));
                $this->dataPersistor->clear('blog_post');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addExceptionMessage($e->getPrevious() ?: $e);
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the post.'));
            }

            $this->dataPersistor->set('blog_post', $data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

}