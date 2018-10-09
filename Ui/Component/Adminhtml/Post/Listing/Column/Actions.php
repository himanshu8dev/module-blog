<?php

/**
 * Himanshu Kubavat
 * Magento2 Sample Blog Extension
 */

namespace Himanshu\Blog\Ui\Component\Adminhtml\Post\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\Escaper;

/**
 * Class PostActions
 */
class Actions extends Column {

    /**
     * Url path
     */
    const URL_PATH_EDIT = 'blog/post/edit';
    const URL_PATH_DELETE = 'blog/post/delete';

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var Escaper
     */
    private $escaper;

    /**
     * Constructor
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
    ContextInterface $context, UiComponentFactory $uiComponentFactory, UrlInterface $urlBuilder, array $components = [], array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource) {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['id'])) {
                    $title = $item['title'];

                    $item[$this->getData('name')] = [
                        'edit' => [
                            'href' => $this->urlBuilder->getUrl(static::URL_PATH_EDIT, ['id' => $item['id']]),
                            'label' => __('Edit')
                        ],
                        /* 'delete' => [
                            'href' => $this->urlBuilder->getUrl(static::URL_PATH_DELETE, ['id' => $item['id']]),
                            'label' => __('Delete'),                            
                            'confirm' => [
                                'title' => __('Delete %1', $title),
                                'message' => __('Are you sure you want to delete a %1 record?', $title)
                            ]
                        ] */
                    ];
                }
            }
        }

        return $dataSource;
    }

}
