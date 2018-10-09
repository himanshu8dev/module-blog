<?php

/**
 * Himanshu Kubavat
 * Magento2 Sample Blog Extension
 */

namespace Himanshu\Blog\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * Upgrade the Catalog module DB scheme
 */
class UpgradeSchema implements UpgradeSchemaInterface {

    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context) {
        $setup->startSetup();

        // Version 0.0.2 Schema Updation
        if (version_compare($context->getVersion(), '0.0.2', '<')) {
            $this->update0x0x2($setup);
        }

        $setup->endSetup();
    }

    /**
     * Update Schema : Version 0.0.2
     * @param type $setup
     */
    protected function update0x0x2($setup) {
        $postTableName = 'hims_blog_post';
        if ($setup->getConnection()->isTableExists($postTableName) == true) {
            $connection = $setup->getConnection();
            
            /* $connection->addColumn(
              $tableName,
              'updated_at',
              ['type' => Table::TYPE_DATETIME,'nullable' => false, 'default' => '', 'afters' => 'created_at'],
              'Updated At'
            ); */
            
            $connection->changeColumn(
                $postTableName, 
                'status', 
                'status', 
                [
                    'type' => Table::TYPE_BOOLEAN, 
                    'nullable' => false, 
                    'default' => 0, 
                    'comment' => "Post Staus"
                ]
            );
            
            $connection->changeColumn(
                $postTableName, 
                'allowed_comment', 
                'allowed_comment', 
                [
                    'type' => Table::TYPE_BOOLEAN, 
                    'nullable' => false, 
                    'default' => 0, 
                    'comment' => "Allowed Post Comment"
                ]
            );
        }
    }

}
