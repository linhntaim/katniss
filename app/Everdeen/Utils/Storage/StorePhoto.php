<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-08-18
 * Time: 20:53
 */

namespace Katniss\Everdeen\Utils\Storage;

use Intervention\Image\ImageManagerStatic;

class StorePhoto extends StoreFile
{
    /**
     * @var \Intervention\Image\Image
     */
    protected $image;

    /**
     * StorePhoto constructor.
     * @param \SplFileInfo|string $sourceFile
     */
    public function __construct($sourceFile)
    {
        parent::__construct($sourceFile, 'img');

        $this->prepare();
    }

    public function prepare()
    {
        $this->image = ImageManagerStatic::make($this->fileInfo->getRealPath());
    }

    /**
     * @param integer $width
     * @param integer $height
     * @param boolean $aspectRatio
     * @param boolean $upSize
     */
    public function resize($width, $height, $aspectRatio = true, $upSize = false)
    {
        $this->image->resize($width, $height, function ($constraint) use ($aspectRatio, $upSize) {
            if ($aspectRatio) {
                $constraint->aspectRatio();
            }
            if ($upSize) {
                $constraint->upsize();
            }
        });
    }

    /**
     * @param integer $width
     * @param integer $height
     * @param integer|null $x
     * @param integer|null $y
     */
    public function crop($width, $height, $x = null, $y = null)
    {
        $this->image->crop($width, $height, $x, $y);
    }

    /**
     * @param float $angle
     * @param string $bgColor
     */
    public function rotate($angle, $bgColor = '#ffffff')
    {
        $this->image->rotate($angle, $bgColor);
    }

    public function save($quality = null, $path = null)
    {
        $this->image->save($path, $quality);
        $this->prepare();
    }

    public function move($targetDirectory, $name = null)
    {
        parent::move($targetDirectory, $name);
        $this->prepare();
    }

    public function moveRelative($targetDirectory, $name = null)
    {
        parent::moveRelative($targetDirectory, $name);
        $this->prepare();
    }

    public function copy($targetDirectory, $name = null)
    {
        parent::copy($targetDirectory, $name);
        $this->prepare();
    }

    public function copyRelative($targetDirectory, $name = null)
    {
        parent::copyRelative($targetDirectory, $name);
        $this->prepare();
    }

    public function duplicate($targetDirectory, $name = null)
    {
        $clonedStore = parent::duplicate($targetDirectory, $name = null);
        $clonedStore->image = ImageManagerStatic::make($clonedStore->fileInfo->getRealPath());
        return $clonedStore;
    }

    public function duplicateRelative($targetDirectory, $name = null)
    {
        $clonedStore = parent::duplicateRelative($targetDirectory, $name = null);
        $clonedStore->image = ImageManagerStatic::make($clonedStore->fileInfo->getRealPath());
        return $clonedStore;
    }

    public function createThumbnail($width, $height, $separator1 = '_', $separator2 = 'x')
    {
        $thumbnailStoreFile = $this->duplicate($this->fileInfo->getPath(),
            $this->fileInfo->getFilename() . $separator1 . $width . $separator2 . $height . $this->fileInfo->getExtension());
        $thumbnailStoreFile->resize($width, $height);
        $thumbnailStoreFile->save();
        return $thumbnailStoreFile;
    }
}
