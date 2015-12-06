<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-09-25
 * Time: 22:36
 */

namespace Katniss\Models\Helpers;

use Symfony\Component\HttpFoundation\File\File;
use Illuminate\Support\Facades\Storage;

class StoredPhoto
{
    const PREFIX = 'photo_';

    protected $image;
    protected $imageFilePath;
    protected $imageFileExt;
    protected $imageFileSize;
    protected $imageType;
    protected $imageWidth;
    protected $imageHeight;

    public $targetFileName;
    public $targetFilePath;
    public $targetFileAsset;
    protected $targetQuality;
    protected $targetWidth;
    protected $targetHeight;

    /**
     * @var array
     */
    protected $result;

    public function __construct()
    {

    }

    public static function getPrefix()
    {
        return StoredPhoto::PREFIX;
    }

    public static function moveImage($src, $toDisk, $basename = null)
    {
        $imageFileName = strtok(str_replace(appHomeUrl() . '/storage/app/tmp/', '', $src), '?');
        if (startWith($imageFileName, self::getPrefix())) {
            $storage = Storage::disk('local');
            if ($storage->exists('tmp/' . $imageFileName)) {
                if (!empty($basename)) {
                    $exts = explode('.', $imageFileName);
                    $fileName = $basename . '.' . array_pop($exts);
                } else {
                    $fileName = $imageFileName;
                }
                $storage->move('tmp/' . $imageFileName, $toDisk . '/' . $fileName);
                return $fileName;
            }
        }

        return false;
    }

    public static function removeImage($src)
    {
        $imageFileName = strtok(str_replace(appHomeUrl() . '/storage/app/tmp/', '', $src), '?');
        if (startWith($imageFileName, self::getPrefix())) {
            $storage = Storage::disk('tmp');
            if ($storage->exists($imageFileName)) {
                $storage->delete($imageFileName);
                return true;
            }
        }

        return false;
    }

    public function fromFile($src, $target_quality = 90)
    {
        $imageFileName = strtok(str_replace(appHomeUrl() . '/storage/app/tmp/', '', $src), '?');
        if (startWith($imageFileName, self::getPrefix())) {
            $file = new File(storage_path('app/tmp/' . $imageFileName));
            if ($file) {
                $this->targetFileAsset = asset('storage/app/tmp/' . $imageFileName);
                $this->imageType = $file->getMimeType();
                $this->imageFilePath = $file->getRealPath();
                switch (strtolower($this->imageType)) {
                    case 'image/png':
                        $this->imageFileExt = 'png';
                        $this->image = imagecreatefrompng($this->imageFilePath);
                        break;
                    case 'image/gif':
                        $this->imageFileExt = 'gif';
                        $this->image = imagecreatefromgif($this->imageFilePath);
                        break;
                    case 'image/jpeg':
                    case 'image/pjpeg':
                        $this->imageFileExt = 'jpg';
                        $this->image = imagecreatefromjpeg($this->imageFilePath);
                        break;
                    default:
                        $this->saveResult([
                            'success' => false,
                            'message' => 'Image type was not supported'
                        ]);
                        return false;
                }
                $this->imageFileSize = $file->getSize();
                $this->targetQuality = $target_quality;

                list($imageWidth, $imageHeight) = getimagesize($this->imageFilePath);
                $this->imageWidth = $imageWidth;
                $this->imageHeight = $imageHeight;

                $this->targetFileName = $imageFileName;
                $this->targetFilePath = $this->imageFilePath;

                return true;
            }
        }

        $this->saveResult([
            'success' => false,
            'message' => 'Image was not existed'
        ]);
        return false;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $client_image
     * @param int $target_quality
     */
    public function fromUploadedFile($client_image, $target_quality = 90)
    {
        if (!$client_image->isValid()) {
            $this->saveResult([
                'success' => false,
                'message' => 'Image was not existed'
            ]);
            return false;
        }

        $this->imageType = $client_image->getMimeType();
        $this->imageFilePath = $client_image->getRealPath();
        switch (strtolower($this->imageType)) {
            case 'image/png':
                $this->imageFileExt = 'png';
                $this->image = imagecreatefrompng($this->imageFilePath);
                break;
            case 'image/gif':
                $this->imageFileExt = 'gif';
                $this->image = imagecreatefromgif($this->imageFilePath);
                break;
            case 'image/jpeg':
            case 'image/pjpeg':
                $this->imageFileExt = 'jpg';
                $this->image = imagecreatefromjpeg($this->imageFilePath);
                break;
            default:
                $this->saveResult([
                    'success' => false,
                    'message' => 'Image type was not supported'
                ]);
                return false;
        }
        $this->imageFileSize = $client_image->getSize();
        $this->targetQuality = $target_quality;

        list($imageWidth, $imageHeight) = getimagesize($this->imageFilePath);
        $this->imageWidth = $imageWidth;
        $this->imageHeight = $imageHeight;

        $this->targetFileName = uniqid(self::getPrefix() . time() . '_') . '.' . $this->imageFileExt;
        $this->save();

        return true;
    }

    /**
     * @return array
     */
    public function getCurrentResult()
    {
        return $this->result;
    }

    /**
     * @param array $info
     */
    protected function saveResult(array $info)
    {
        $this->result = $info;
    }

    protected function save()
    {
        $storage = Storage::disk('tmp');
        $storage->put($this->targetFileName, file_get_contents($this->imageFilePath));
        $this->targetFilePath = storage_path('app/tmp/' . $this->targetFileName);
        $this->targetFileAsset = asset('storage/app/tmp/' . $this->targetFileName);
    }

    public function resize($container_width, $container_height)
    {
        if ($container_width > 0 && $container_height > 0) {

            $imageWidth = $this->imageWidth;
            $imageHeight = $this->imageHeight;
            $imageSizeRatio = $imageWidth / $imageHeight;

            if ($imageWidth > $container_width) {
                $imageWidth = $container_width;
                $imageHeight = $imageWidth / $imageSizeRatio;
            }
            if ($imageHeight > $container_height) {
                $imageHeight = $container_height;
                $imageWidth = $imageHeight / $imageSizeRatio;
            }
            if ($imageWidth < $container_width) {
                $imageWidth = $container_width;
                $imageHeight = $imageWidth / $imageSizeRatio;
            }
            if ($imageHeight < $container_height) {
                $imageHeight = $container_height;
                $imageWidth = $imageHeight * $imageSizeRatio;
            }

            $this->targetWidth = ceil($imageWidth);
            $this->targetHeight = ceil($imageHeight);

            $imageCanvas = imagecreatetruecolor($this->targetWidth, $this->targetHeight);
            if (imagecopyresized($imageCanvas, $this->image, 0, 0, 0, 0, $this->targetWidth, $this->targetHeight, $this->imageWidth, $this->imageHeight)) {
                switch (strtolower($this->imageType)) {
                    case 'image/png':
                        imagepng($imageCanvas, $this->targetFilePath);
                        break;
                    case 'image/gif':
                        imagegif($imageCanvas, $this->targetFilePath);
                        break;
                    case 'image/jpeg':
                    case 'image/pjpeg':
                        imagejpeg($imageCanvas, $this->targetFilePath, $this->targetQuality);
                        break;
                    default:
                        return false;
                }
                if (is_resource($imageCanvas)) {
                    imagedestroy($imageCanvas);
                }

                $this->saveResult([
                    'success' => true,
                    'msg' => 'true',
                    'width' => $this->targetWidth,
                    'height' => $this->targetHeight,
                    'imgSrc' => $this->targetFileAsset,
                    'thumSrc' => 'none',
                ]);

                return true;
            }
        }

        $this->saveResult([
            'success' => false,
            'message' => 'Resize image failed'
        ]);

        return false;
    }

    public function crop($crop_width, $crop_height, $x_offset, $y_offset)
    {
        if ($x_offset >= 0 && $x_offset <= $this->imageWidth && $y_offset >= 0 && $y_offset <= $this->imageHeight) {
            if ($x_offset + $crop_width <= $this->imageWidth && $y_offset + $crop_height <= $this->imageHeight) {

                $this->targetWidth = ceil($crop_width);
                $this->targetHeight = ceil($crop_height);
                $x_offset = ceil($x_offset);
                $y_offset = ceil($y_offset);

                $imageCanvas = imagecreatetruecolor($this->targetWidth, $this->targetHeight);
                if (imagecopyresampled($imageCanvas, $this->image, 0, 0, $x_offset, $y_offset, $this->targetWidth, $this->targetHeight, $this->targetWidth, $this->targetHeight)) {
                    switch (strtolower($this->imageType)) {
                        case 'image/png':
                            imagepng($imageCanvas, $this->targetFilePath);
                            break;
                        case 'image/gif':
                            imagegif($imageCanvas, $this->targetFilePath);
                            break;
                        case 'image/jpeg':
                        case 'image/pjpeg':
                            imagejpeg($imageCanvas, $this->targetFilePath, $this->targetQuality);
                            break;
                        default:
                            return false;
                    }
                    if (is_resource($imageCanvas)) {
                        imagedestroy($imageCanvas);
                    }

                    $this->saveResult([
                        'success' => true,
                        'msg' => 'true',
                        'width' => $this->targetWidth,
                        'height' => $this->targetHeight,
                        'imgSrc' => $this->targetFileAsset
                    ]);

                    return true;
                }
            }
        }

        return false;
    }

    public function move($toDisk, $basename = null)
    {
        $storage = Storage::disk('local');
        if ($storage->exists('tmp/' . $this->targetFileName)) {
            if (!empty($basename)) {
                $fileName = $basename . '.' . $this->imageFileExt;
            } else {
                $fileName = $this->targetFileName;
            }
            $storage->move('tmp/' . $this->targetFileName, $toDisk . '/' . $fileName);

            $this->targetFileName = $fileName;
            $this->targetFilePath = storage_path('app/' . $toDisk . '/' . $this->targetFileName);
            $this->targetFileAsset = asset('storage/app/' . $toDisk . '/' . $this->targetFileName);

            return true;
        }

        return false;
    }
}