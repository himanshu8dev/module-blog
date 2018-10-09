<?php

/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Himanshu\Blog\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function install(
            ModuleDataSetupInterface $setup,
            ModuleContextInterface $context
    ) {
          /**
           * Install messages
           */
          $data = [
            [ 
                'title' => 'My First Blog Post',
                'url_key' => 'my-first-blog-post',
                'short_description' => 'This is example of blog post First',
                'content' => 'This is example of blog post which we are developing for the magento 2 extension.',
                'status' => 1,
                'allowed_comment' => 1
            ],
            [ 
                'title' => 'My Second Blog Post',
                'url_key' => 'my-second-blog-post',
                'short_description' => 'This is example of blog post Second',
                'content' => 'This is example of blog post which we are developing for the magento 2 extension.',
                'status' => 1,
                'allowed_comment' => 0
            ],
            [ 
                'title' => 'My Third Blog Post',
                'url_key' => 'my-third-blog-post',
                'short_description' => 'This is example of blog post Third',
                'content' => 'This is example of blog post which we are developing for the magento 2 extension.',
                'status' => 1,
                'allowed_comment' => 0
            ],
            [ 
                'title' => 'My Fourth Blog Post',
                'url_key' => 'my-fourth-blog-post',
                'short_description' => 'This is example of blog post Fourth',
                'content' => 'This is example of blog post which we are developing for the magento 2 extension.',
                'status' => 0,
                'allowed_comment' => 1
            ]
          ];
          
          foreach ($data as $bind) {
              $setup->getConnection()->insertForce($setup->getTable('hims_blog_post'), $bind);
          }
    }
}