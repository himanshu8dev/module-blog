<?php

/*
 * Himanshu Kubavat
 * Magento2 Sample Blog Extension
 */

namespace Himanshu\Blog\Controller\Adminhtml\Post;

use \Magento\Backend\App\Action;

class Delete extends Action {

    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Himanshu_Blog::post_delete';

    /**
     * Delete action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute() {
        $id = $this->getRequest()->getParam('id');

        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $model = $this->_objectManager->create(\Himanshu\Blog\Model\Post::class);

                $model->load($id);
                $title = $model->getTitle();

                $model->delete();

                $this->messageManager->addSuccess(__('The post has been deleted.'));
                $this->_eventManager->dispatch('adminhtml_blogpost_on_delete', ['title' => $title, 'status' => 'success']);
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $title = '';
                $this->_eventManager->dispatch('adminhtml_blogpost_on_delete', ['title' => $title, 'status' => 'fail']);
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }

        $this->messageManager->addError(__('We can\'t find a post to delete.'));

        return $resultRedirect->setPath('*/*/');
    }

}