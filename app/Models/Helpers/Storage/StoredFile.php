<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-10-26
 * Time: 15:11
 */

namespace Katniss\Models\Helpers;


class StoredFile
{
    const PREFIX = 'file_';

    public $targetFileName;
    public $targetFilePath;
    public $targetFileAsset;

    public static function generateName($prefix, $extension)
    {
        return uniqid($prefix . time() . '_') . '.' . $extension;
    }

    public function getPrefix()
    {
        return $this::PREFIX;
    }

    public function getGeneratedName($extension)
    {
        return self::generateName($this->getPrefix(), $extension);
    }

    protected function save()
    {
        $this->targetFilePath = storage_path('app/tmp/' . $this->targetFileName);
        $this->targetFileAsset = asset('storage/app/tmp/' . $this->targetFileName);
    }
}