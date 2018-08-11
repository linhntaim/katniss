<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-10-26
 * Time: 15:07
 */

namespace Katniss\Everdeen\Utils\Storage;

use Illuminate\Support\Facades\Storage;

class StoreEncodedAudio extends StoreAudio
{
    public function __construct($sourceString, $extension)
    {
        parent::__construct(
            $this->storeFileFromSourceString($sourceString, $extension)
        );
    }

    protected function storeFileFromSourceString($sourceString, $extension)
    {
        $audio = self::decodeAudioString($sourceString);
        $audioFileName = self::randomizeFilename(null, $extension);
        Storage::disk('files')->put('tmp/' . $audioFileName, $audio);
        return _k('file_path') . '/tmp/' . $audioFileName;
    }

    public static function decodeAudioString($dataString)
    {
        $dataString = substr($dataString, strpos($dataString, ',') + 1);
        return base64_decode($dataString);
    }
}
