<?php

/**
 * Himanshu Kubavat
 * Magento2 Sample Blog Extension
 */

namespace Himanshu\Blog\Controller\Adminhtml\Post;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use \Himanshu\Blog\Api\Data\PostInterface;
use \Himanshu\Blog\Api\PostRepositoryInterface as PostRepository;

class InlineEdit extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Himanshu_Blog::post_update';

    /**
     * @var \Himanshu\Blog\Api\PostRepositoryInterface
     */
    protected $postRepository;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $jsonFactory;

    /**
     * @param Context $context
     * @param PostRepository $postRepository
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        PostInterface $postRepository,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->postRepository = $postRepository;
        $this->jsonFactory = $jsonFactory;
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        if ($this->getRequest()->getParam('isAjax')) {
            $postItems = $this->getRequest()->getParam('items', []);
            if (!count($postItems)) {
                $messages[] = __('Please correct the data sent.');
                $error = true;
            } else {
                foreach (array_keys($postItems) as $postId) {
                    /** @var \Himanshu\Blog\Model\Post $post */
                    $post = $this->postRepository->getById($postId);
                    try {
                        $post->setData(array_merge($post->getData(), $postItems[$postId]));
                        $this->postRepository->save($post);
                    } catch (\Exception $e) {
                        $messages[] = $this->getErrorWithPostId(
                            $post,
                            __($e->getMessage())
                        );
                        $error = true;
                    }
                }
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    /**
     * Add Post title to error message
     *
     * @param PostInterface $post
     * @param string $errorText
     * @return string
     */
    protected function getErrorWithPostId(PostInterface $post, $errorText)
    {
        return '[Post ID: ' . $post->getId() . '] ' . $errorText;
    }
}
