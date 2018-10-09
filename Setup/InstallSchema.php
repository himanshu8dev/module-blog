<?php

/**
 * Himanshu Kubavat
 * Magento2 Sample Blog Extension
 */

namespace Himanshu\Blog\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use \Himanshu\Blog\Model\Post as PostModel;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface {
    
    /**
     * @var Magento\Framework\App\Filesystem\DirectoryList
     */
    private $_directoryList;
    
    /**
     * @var Magento\Framework\Filesystem\Io\File 
     */
    private $_ioFile;


    /**
     * @param \Magento\Framework\Filesystem\Io\File $ioFile
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     */
    public function __construct(
        \Magento\Framework\Filesystem\Io\File $ioFile,
        DirectoryList $directoryList
    ) {
        $this->_ioFile = $ioFile;
        $this->_directoryList = $directoryList;
    }
    
    /**
     * Define Table Constant
     */
    CONST TBL_POST = "hims_blog_post";
    CONST TBL_COMMENT = "hims_blog_comment";
    CONST TBL_TAG = "hims_blog_tag";
    
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(
        SchemaSetupInterface $setup, 
        ModuleContextInterface $context
    ) {
        
        /*
         * Create Module Media Directory
         */
        $this->_createMediaDir();

        /**
         * Create table 'hims_blog_post'
         */
        $postTable = $setup->getConnection()->newTable($setup->getTable(SELF::TBL_POST))
                ->addColumn('id', Table::TYPE_INTEGER, 13, ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true], 'Post ID')
                ->addColumn('title', Table::TYPE_TEXT, 120, ['nullable' => false], 'Post Title')
                ->addColumn('url_key', Table::TYPE_TEXT, 155, ['nullable' => false], 'Post URL Key')
                ->addColumn('short_description', Table::TYPE_TEXT, 512, [], 'Post Short Description')
                ->addColumn('content', Table::TYPE_TEXT, '64k', ['nullable' => false], 'Post Post Content')
                ->addColumn('tags', Table::TYPE_TEXT, 360, [], 'Post Tags')
                ->addColumn('status', Table::TYPE_INTEGER, 1, ['nullable' => false,'default' => 0], 'Post Status')
                ->addColumn('featured_image', Table::TYPE_TEXT, 255, [], 'Post Featured Image')
                ->addColumn('meta_title', Table::TYPE_TEXT, 255, ['nullable' => true], 'Post Meta Title')
                ->addColumn('meta_keyword', Table::TYPE_TEXT, 255, [], 'Post Meta Keyword')
                ->addColumn('meta_description', Table::TYPE_TEXT, 360, [], 'Post Meta Descriotion')
                ->addColumn('allowed_comment', Table::TYPE_INTEGER, 1, ['default' => 0], 'Post Allowed Comment')
                ->addColumn('created_at', Table::TYPE_TIMESTAMP, null, ['nullable' => false, 'default' => Table::TIMESTAMP_INIT], 'Post Created At')
                ->addColumn('updated_at', Table::TYPE_TIMESTAMP, null, ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE], 'Post Updated At')
                ->setComment('Post Table');
        $setup->getConnection()->createTable($postTable);

        /**
         * Create table 'hims_blog_comment'
         */
        $postCommentTable = $setup->getConnection()->newTable($setup->getTable(SELF::TBL_COMMENT))
                ->addColumn('id', Table::TYPE_INTEGER, 21, ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true], 'Post Comment ID')
                ->addColumn('post_id', Table::TYPE_INTEGER, 13, ['unsigned' => true, 'nullable' => false], 'Post ID')
                ->addColumn('customer_id', Table::TYPE_INTEGER, null, ['unsigned' => true, 'nullable' => false, 'default' => 0], 'Customer ID')
                ->addColumn('name', Table::TYPE_TEXT, 255, ['nullable' => false], 'Name')
                ->addColumn('email', Table::TYPE_TEXT, 255, [], 'Email')
                ->addColumn('comment', Table::TYPE_TEXT, 512, ['nullable' => false], 'Comment')
                ->addColumn('is_approved', Table::TYPE_INTEGER, 1, ['nullable' => false,'default' => 0], 'Is Approved')
                ->addColumn('created_at', Table::TYPE_TIMESTAMP, null, ['nullable' => false, 'default' => Table::TIMESTAMP_INIT], 'Comment Time')
                ->addIndex(
                    $setup->getIdxName(
                        SELF::TBL_COMMENT, 
                        ['post_id'],
                        AdapterInterface::INDEX_TYPE_UNIQUE
                    ),
                    ['post_id'],
                    AdapterInterface::INDEX_TYPE_UNIQUE
                )
                /*
                ->addIndex(
                    $setup->getIdxName(
                        SELF::TBL_COMMENT, 
                        ['post_id', 'customer_id'],
                        AdapterInterface::INDEX_TYPE_UNIQUE
                    ),
                    ['post_id', 'customer_id'],
                    AdapterInterface::INDEX_TYPE_UNIQUE
                ) */
                ->addForeignKey(
                        $setup->getFkName(
                                SELF::TBL_COMMENT, 'post_id', SELF::TBL_POST, 'id'
                        ), 'post_id', $setup->getTable(SELF::TBL_POST), 'id', Table::ACTION_CASCADE
                )
                /* ->addForeignKey(// Add foreign key for table entity
                        $setup->getFkName(
                                SELF::TBL_COMMENT, // New table
                                'customer_id', // Column in New Table
                                'customer_entity', // Reference Table
                                'entity_id' // Column in Reference table
                        ), 'customer_id', // New table column
                        $setup->getTable('customer_entity'), // Reference Table
                        'entity_id', // Reference Table Column
                        // When the parent is deleted, delete the row with foreign key
                        Table::ACTION_CASCADE
                ) */
                ->setComment('Post Comment Table');
        $setup->getConnection()->createTable($postCommentTable);

        /**
         * Create table 'hims_blog_tag'
         */
        $postTagTable = $setup->getConnection()->newTable($setup->getTable(SELF::TBL_TAG))
                ->addColumn('id', Table::TYPE_BIGINT, null, ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true], 'Tag ID')
                ->addColumn('name', Table::TYPE_TEXT, 255, ['nullable => false'], 'Tag Name')
                ->addColumn('url_key', Table::TYPE_TEXT, 255, [], 'Tag URL Key')
                ->addColumn('created_at', Table::TYPE_TIMESTAMP, null, ['nullable' => false, 'default' => Table::TIMESTAMP_INIT], 'Tag Created At')
                ->setComment('Post Tags Table');
        $setup->getConnection()->createTable($postTagTable);
    }
    
    /**
     * Creating Media Directories
     */
    protected function _createMediaDir() {
        $mainMedia = $this->_directoryList->getPath(DirectoryList::MEDIA) . DIRECTORY_SEPARATOR . PostModel::MEDIA_MAIN_PATH;
        $media = $this->_directoryList->getPath(DirectoryList::MEDIA) . DIRECTORY_SEPARATOR . PostModel::MEDIA_PATH;
        $mediaTemp = $this->_directoryList->getPath(DirectoryList::MEDIA) . DIRECTORY_SEPARATOR . PostModel::MEDIA_TMP_PATH;
        $mediaResized295x480 = $this->_directoryList->getPath(DirectoryList::MEDIA) . DIRECTORY_SEPARATOR . PostModel::MEDIA_RESIZED_295x280;
        $mediaResized1024x480 = $this->_directoryList->getPath(DirectoryList::MEDIA) . DIRECTORY_SEPARATOR . PostModel::MEDIA_RESIZED_1024x480;
        
        $directories = [$media, $mediaTemp, $mediaResized295x480, $mediaResized1024x480];

        try {
            $ioAdapter = $this->_ioFile;
            foreach ($directories as $dir) {
                $ioAdapter->checkAndCreateFolder($dir);
            }
            $ioAdapter::chmodRecursive($mainMedia, 0777);
            $ioAdapter->close();
        } catch (Exception $ex) {
            
        }

        return true;
    }

}
