<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-10-26
 * Time: 15:07
 */

namespace Katniss\Everdeen\Utils\Storage;

class StoreAudio extends StoreFile
{
    public function __construct($sourceFile)
    {
        parent::__construct($sourceFile, '_audios');
    }
}