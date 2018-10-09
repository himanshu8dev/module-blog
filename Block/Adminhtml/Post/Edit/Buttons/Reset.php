<?php

/**
 * Himanshu Kubavat
 * Magento2 Sample Blog Extension
 */

namespace Himanshu\Blog\Block\Adminhtml\Post\Edit\Buttons;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class Reset Button
 */
class Reset implements ButtonProviderInterface {

    /**
     * @return array
     */
    public function getButtonData() {
        return [
            'label' => __('Reset'),
            'class' => 'reset',
            'on_click' => 'location.reload();',
            'sort_order' => 30
        ];
    }

}
