<?php

/**
 * Himanshu Kubavat
 * Magento2 Sample Blog Extension
 */

namespace Himanshu\Blog\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use \Himanshu\Blog\Setup\InstallSchema;

/**
 * Blog Post ResourceModel
 */
class Post extends AbstractDb {

    public function __construct(
        Context $context
    ) {
        parent::__construct($context);
    }

    protected function _construct() {
        $this->_init(InstallSchema::TBL_POST, 'id');
    }

}
