<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-08-18
 * Time: 20:53
 */

namespace Katniss\Models\Helpers\Storage;

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
        parent::__construct($sourceFile, '_photos');

        $this->prefix = 'img';
        $this->image = ImageManagerStatic::make($this->targetFileInfo->getRealPath());
    }

    /**
     * @param integer $width
     * @param integer $height
     */
    public function resize($width, $height)
    {
        $this->image->resize($width, $height);
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

    public function save($quality = null)
    {
        $this->image->save(null, $quality);
    }

    public function duplicate($targetDirectory, $name = null, $autoExtension = true)
    {
        $name = $this->tmpFilename($name, $autoExtension);

        $targetFileInfo = clone $this->targetFileInfo;
        $image = clone $this->image;
        $this->targetFileInfo = $this->copy($targetDirectory, $name);
        $this->image = ImageManagerStatic::make($this->targetFileInfo->getRealPath());

        $clonedStore = clone $this;
        $this->targetFileInfo = $targetFileInfo;
        $this->image = $image;
        return $clonedStore;
    }
}