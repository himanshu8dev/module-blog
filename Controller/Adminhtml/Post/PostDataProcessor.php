<?php

/*
 * Himanshu Kubavat
 * Magento2 Sample Blog Extension
 */

namespace Himanshu\Blog\Controller\Adminhtml\Post;

/**
 * Class PostDataProcessor
 */
class PostDataProcessor {

    /**
     * @var \Magento\Framework\Stdlib\DateTime\Filter\Date
     */
    protected $dateFilter;

    /**
     * @var \Magento\Framework\View\Model\Layout\Update\ValidatorFactory
     */
    protected $validatorFactory;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;
    
    /**
     * @var \Himanshu\Blog\Model\ImageUploader
     */
    protected $_imgUploader;

    /**
     * @param \Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Magento\Framework\View\Model\Layout\Update\ValidatorFactory $validatorFactory
     * @param \Himanshu\Blog\Model\ImageUploader $imgUploader
     */
    public function __construct(
        \Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\View\Model\Layout\Update\ValidatorFactory $validatorFactory,
        \Himanshu\Blog\Model\ImageUploader $imgUploader
    ) {
        $this->dateFilter = $dateFilter;
        $this->messageManager = $messageManager;
        $this->validatorFactory = $validatorFactory;
        $this->_imgUploader = $imgUploader;
    }

    /**
     * Filtering posted data. Converting localized data if needed
     *
     * @param array $data
     * @return array
     */
    public function filter($data) {
        $filterRules = [];
        
        /* For Image uploading */
        $this->_setImageUpload($data);
        
        return (new \Zend_Filter_Input($filterRules, [], $data))->getUnescaped();
    }

    
    protected function _setImageUpload(&$data) {
        
        if (isset($data['featured_image'][0]['file']) && $data['featured_image'][0]['file']) {
            $data['featured_image'] = $this->_imgUploader->moveFileFromTmp($data['featured_image'][0]['file']);
        } elseif(isset($data['featured_image'][0]['name'])) {
            $data['featured_image'] = $data['featured_image'][0]['name'];
        } else {
            $data['featured_image'] = null;
        }

        return $data;
    }

    /**
     * Validate post data
     *
     * @param array $data
     * @return bool Return FALSE if some item is invalid
     */
    public function validate($data)
    {
        return true;
    }

    /**
     * Check if required fields is not empty
     *
     * @param array $data
     * @return bool
     */
    public function validateRequireEntry(array $data) {
        $requiredFields = [
            'title' => __('Post Title'),
            'content' => __('Content'),
            'status' => __('Status')
        ];
        
        $errorNo = true;
        
        foreach ($data as $field => $value) {
            if (in_array($field, array_keys($requiredFields)) && $value == '') {
                $errorNo = false;
                $this->messageManager->addError(
                        __('To apply changes you should fill in hidden required "%1" field', $requiredFields[$field])
                );
            }
        }
        return $errorNo;
    }

}