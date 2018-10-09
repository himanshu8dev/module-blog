<?php

/**
 * Himanshu Kubavat
 * Magento2 Sample Blog Extension
 */

namespace Himanshu\Blog\Model;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\UrlInterface;
use Magento\MediaStorage\Helper\File\Storage\Database;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use \Himanshu\Blog\Model\Post as PostModel;
use \Himanshu\Blog\Model\ImageResize as ImageResizer;

class ImageUploader {

    /**
     * @var string
     */
    const IMAGE_TMP_PATH = PostModel::MEDIA_TMP_PATH;

    /**
     * @var string
     */
    const IMAGE_PATH = PostModel::MEDIA_PATH;
    
    /**
     * Core file storage database
     *
     * @var \Magento\MediaStorage\Helper\File\Storage\Database
     */
    protected $coreFileStorageDatabase;

    /**
     * Media directory object (writable).
     *
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    protected $mediaDirectory;
    
    /**
     * Uploader factory
     *
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    private $uploaderFactory;

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * Base tmp path
     *
     * @var string
     */
    protected $baseTmpPath;

    /**
     * Base path
     *
     * @var string
     */
    protected $basePath;

    /**
     * Allowed extensions
     *
     * @var string
     */
    protected $allowedExtensions;
    
    /**
     * 
     */
    protected $imageFactory;

    /**
     * @param Database $coreFileStorageDatabase
     * @param Filesystem $filesystem
     * @param UploaderFactory $uploaderFactory
     * @param StoreManagerInterface $storeManager
     * @param LoggerInterface $logger
     * @param AdapterFactory $imageFactory
     * @param $allowedExtensions
     * @param $baseTmpPath
     * @param $basePath
     */
    public function __construct(
        Database $coreFileStorageDatabase,
        Filesystem $filesystem,
        UploaderFactory $uploaderFactory,
        StoreManagerInterface $storeManager, 
        LoggerInterface $logger,
        \Magento\Framework\Image\AdapterFactory $imageFactory,
        $allowedExtensions = [],
        $baseTmpPath,
        $basePath
    ) {
        $this->coreFileStorageDatabase = $coreFileStorageDatabase;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->uploaderFactory = $uploaderFactory;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
        $this->baseTmpPath = $baseTmpPath;
        $this->basePath = $basePath;
        $this->allowedExtensions= $allowedExtensions;
        $this->imageFactory = $imageFactory;    
    }

    /**
     * Retrieve base tmp path
     *
     * @return string
     */
    public function getBaseTmpPath() {
        return $this->baseTmpPath;
    }

    /**
     * Retrieve base path
     *
     * @return string
     */
    public function getBasePath() {
        return $this->basePath;
    }

    /**
     * Retrieve base path
     *
     * @return string[]
     */
    public function getAllowedExtensions() {
        return $this->allowedExtensions;
    }

    /**
     * Retrieve path
     *
     * @param string $path
     * @param string $name
     *
     * @return string
     */
    public function getFilePath($path, $name) {
        return rtrim($path, '/') . '/' . ltrim($name, '/');
    }

    /**
     * Checking file for moving and move it
     *
     * @param string $name
     *
     * @return string
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function moveFileFromTmp($name) {
        $baseTmpPath = $this->getBaseTmpPath();
        $basePath = $this->getBasePath();

        $baseFilePath = $this->getFilePath($basePath, $name);
        $baseTmpFilePath = $this->getFilePath($baseTmpPath, $name);

        try {
            $this->coreFileStorageDatabase->copyFile(
                    $baseTmpFilePath, $baseFilePath
            );
            $this->mediaDirectory->renameFile(
                    $baseTmpFilePath, $baseFilePath
            );
        } catch (\Exception $e) {
            throw new LocalizedException(
            __('Something went wrong while saving the file(s).')
            );
        }
        
        try{
            $this->resize($name);
            $this->resize($name,'large');
        } catch (Exception $ex) {

        }
        
        return $name;
    }

    public function getBaseUrl() {
        return $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
    }

    /**
     * Checking file for save and save it to tmp dir
     *
     * @param string $fileId
     *
     * @return string[]
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function saveFileToTmpDir($fileId) {
        $baseTmpPath = $this->getBaseTmpPath();

        $uploader = $this->uploaderFactory->create(['fileId' => $fileId]);
        $uploader->setAllowedExtensions($this->getAllowedExtensions());
        $uploader->setAllowRenameFiles(true);
        $uploader->setFilesDispersion(false);
        $uploader->setAllowCreateFolders(true);
        
        // exit($uploader->getFileExtension());
        $filename = mt_rand() . time() . "." . $uploader->getFileExtension();
        $result = $uploader->save($this->mediaDirectory->getAbsolutePath($baseTmpPath), $filename);
        if (!$result) {
            throw new LocalizedException(
            __('File can not be saved to the destination folder.')
            );
        }
        
        /**
         * Workaround for prototype 1.7 methods "isJSON", "evalJSON" on Windows OS
         */
        $result['tmp_name'] = str_replace('\\', '/', $result['tmp_name']);
        $result['path'] = str_replace('\\', '/', $result['path']);
        $result['url'] = $this->getBaseUrl() . $this->getFilePath($baseTmpPath, $result['file']);

        if (isset($result['file'])) {
            try {
                $relativePath = rtrim($baseTmpPath, '/') . '/' . ltrim($result['file'], '/');
                $this->coreFileStorageDatabase->saveFile($relativePath);
            } catch (\Exception $e) {
                $this->logger->critical($e);
                throw new LocalizedException(
                __('Something went wrong while saving the file(s).')
                );
            }
        }

        return $result;
    }
    
    /**
     * 
     * @param string $image Image name
     * @param string $imageType Image resize type
     * @param int $width null
     * @param int $height null
     */
    public function resize($image, $imageType = null, $width = null, $height = null) {
        $absolutePath = $this->_getMediaDir(PostModel::MEDIA_PATH) . $image;
        $imgConfig = $this->_getImageResizeConfig($imageType, $width, $height);
        
        $w = $imgConfig['width'];
        $h = $imgConfig['height'];
        $destinationDir = $imgConfig['dest_path'];
        

        $imageResize = $this->imageFactory->create();
        $imageResize->open($absolutePath);
        $imageResize->constrainOnly(TRUE);
        $imageResize->keepTransparency(TRUE);
        $imageResize->keepFrame(FALSE);
        $imageResize->keepAspectRatio(TRUE);
        $imageResize->resize($w, $h);

        // Save image     
        $imageResize->save($destinationDir, $image);

        return $image;
    }

    /**
     * Resize Configuration
     *  
     * @param string $imageType large|null
     * @param int $width null
     * @param int $height null
     * @return array Resize Data
     */
    protected function _getImageResizeConfig($imageType = null, $width = null, $height = null) {
        $destinationDir = $this->_getMediaDir(PostModel::MEDIA_RESIZED_PATH);
        $imgConfig = ['dest_path' => $destinationDir, 'width' => $width, 'height' => $height];

        if (!$width && !$height) {
            switch ($imageType) {
                case "large":
                    $imgConfig['dest_path'] = $this->_getMediaDir(PostModel::MEDIA_RESIZED_1024x480);
                    $imgConfig['width'] = 1024;
                    $imgConfig['height'] = 480;
                    break;

                default: // default thumb image
                    $imgConfig['dest_path'] = $this->_getMediaDir(PostModel::MEDIA_RESIZED_295x280);
                    $imgConfig['width'] = 295;
                    $imgConfig['height'] = 280;
                    break;
            }
        }

        return $imgConfig;
    }

    /**
     * Get Media Directory Path With @param $path 
     */
    protected function _getMediaDir($path) {
        return $this->mediaDirectory->getAbsolutePath($path);
    }
}