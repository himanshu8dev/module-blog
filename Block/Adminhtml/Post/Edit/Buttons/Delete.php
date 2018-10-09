<?php

/**
 * Himanshu Kubavat
 * Magento2 Sample Blog Extension
 */

namespace Himanshu\Blog\Block\Adminhtml\Post\Edit\Buttons;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class Delete Button
 */
class Delete extends GenericButton implements ButtonProviderInterface {

    /**
     * @return array
     */
    public function getButtonData() {
        $data = [];
        if ($this->getPostId()) {
            $data = [
                'label' => __('Delete Post'),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\'' . __(
                        'Are you sure you want to do this?'
                ) . '\', \'' . $this->getDeleteUrl() . '\')',
                'sort_order' => 20,
            ];
        }
        return $data;
    }

    /**
     * @return string
     */
    public function getDeleteUrl() {
        return $this->getUrl('*/*/delete', ['id' => $this->getPostId()]);
    }

}
