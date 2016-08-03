<?php

namespace Top\Common;

use Symfony\Component\Filesystem\Filesystem;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Imagine\Image\ImageInterface;

class ImageUploadHelper {

    protected $container;

    public function __construct ($container) {
        $this->container = $container;
    }

    public function validate ($file) {
        if (empty($file)) {
            return array(false , '请选择上传的文件!');
        }

        $mineType = $file->getMimeType();
        if (! $this->checkMineType($file)) {
            return array(false , '文件上传失败，只允许上传jpg, gif, png格式的文件!');
        }

        $size = $file->getSize();
        if (! $this->checkFileSize($file)) {
            return array(false , '上传的文件大小超过了最大限制！');
        }

        return array(true , null);
    }


    public function thumbnail ($file, $fileType, $width, $height, $public = true, $mode = ImageInterface::THUMBNAIL_INSET, $quality = 100, $checkSize = false) {
        $filePath = $file->getRealPath();
        $ready = $this->ready($fileType, $public, '.' . $file->guessExtension());
        if (empty($ready) || empty($width) || empty($height)) {
            return false;
        }
        list ($uri, $fullFilePath) = $ready;

        $imagine = new Imagine();
        $image = $imagine->open($filePath);
        $imageSize = $image->getSize();

        $width = $checkSize && $imageSize->getWidth() < $width ? $imageSize->getWidth() : $width;
        $height = $checkSize && $imageSize->getHeight() < $height ? $imageSize->getHeight() : $height;

        $image->thumbnail(new Box($width, $height), $mode)->save($fullFilePath, array('quality' => $quality));

        return array($uri, $fullFilePath);
    }

    public function crop ($file, $fileType, $width, $height, $x = 0, $y = 0, $public = true) {
        $filePath = $file->getRealPath();
        $ready = $this->ready($fileType, $public, '.' . $file->guessExtension());
        if (empty($ready) || empty($width) || empty($height)) {
            return false;
        }
        list ($uri, $fullFilePath) = $ready;

        $imagine = new Imagine();
        $image = $imagine->open($filePath);
        $size = $image->getSize();

        $width = $size->getWidth() > $width ? $width : $size->getWidth();
        $height = $size->getHeight() > $height ? $height : $size->getHeight();

        $image->crop(new Point($x, $y), new Box($width, $height))->save($fullFilePath);

        return array($uri, $fullFilePath);
    }

    public function ratioResizeWithWidth($file, $fileType, $width, $public = true)
    {
        $ready = $this->ready($fileType, $public, '.' . $file->guessExtension());
        if (empty($ready) || empty($width)) {
            return false;
        }
        list ($uri, $fullFilePath) = $ready;
        $imagine = new Imagine();
        $image = $imagine->open($file->getRealPath());

        $size = $image->getSize();
        $ratio = $width/$size->getWidth();
        $height = $size->getHeight()*$ratio;

        $image->resize(new Box($width, $height))->save($fullFilePath);
        return $uri;
    }

    public function getSize($file, $fileType) {
        $ready = $this->ready($fileType, true, $file->guessExtension());
        if (empty($ready)) {
            return false;
        }
        list ($uri, $fullFilePath) = $ready;

        $imagine = new Imagine();
        $image = $imagine->open($file);
        return $image->getSize();
    }

    public function ready ($fileType, $public = true, $extension = '.jpg') {
        if (empty($fileType)) {
            return false;
        }

        $fileUploadPath = $public ? $this->getPublicUploadFilePath() : $this->getPrivateUploadFilePath();
        if (! is_writeable($fileUploadPath)) {
            throw new \Exception("上传目录不可写", 1);
        }

        $filePath = $this->getFilePath($fileType);
        $fileName = $this->getFileNewName($extension);
        $uri = $filePath . '/'. $fileName;

        $targetDir = $fileUploadPath . '/' . $filePath;
        $this->createDir($targetDir);

        $fullFilePath = $targetDir . '/' . $fileName;

        return array($uri , $fullFilePath);
    }

    public function getPublicUploadFilePath(){
        $path = $this->container->getParameter('top.upload.path');
        return realpath($path);
    }

    public function getPrivateUploadFilePath(){
        $path = $this->container->getParameter('top.privateUpload.path');
        return realpath($path);
    }

    public function getFilePath($type) {
        return $type . '/' . date('Y') . '/' . date('m-d');
    }

    public function getFileNewName($extension='.jpg') {
        $name = str_replace('.', '_', substr(uniqid('', true), - 6) . uniqid('', true));
        return  $name . $extension;
    }

    public function getTmpDir() {
        return '/tmp';
    }

    private function checkMineType ($file) {
        $minetype = $file->getMimeType();
        $allowedMineType = array('image/jpeg' , 'image/gif' , 'image/png');
        return in_array($minetype, $allowedMineType);
    }

    private function checkFileSize ($file) {
        $maxSize = $this->container->getParameter('top.upload.maxsize');
        return $file->getSize() < $maxSize;
    }

    private function createDir($dirs, $mode = 0777) {
        $filesystem = new Filesystem();
        try {
            $filesystem->mkdir($dirs);
        } catch (\Exception $e) {
            throw new \Exception('不能创建目录：' . $dirs);
        }
    }

}