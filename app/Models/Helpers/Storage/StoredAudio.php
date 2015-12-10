<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-10-26
 * Time: 15:07
 */

namespace Katniss\Models\Helpers;


use Illuminate\Support\Facades\Storage;

class StoredAudio extends StoredFile
{
    const PREFIX = 'audio_';

    public $audioData;

    public function fromUploadedData($data)
    {
        $data = substr($data, strpos($data, ',') + 1);
        $this->audioData = base64_decode($data);
        $this->targetFileName = $this->getGeneratedName('mp3');
        $this->save();

        return true;
    }

    protected function save()
    {
        Storage::disk('tmp')->put($this->targetFileName, $this->audioData);

        parent::save();
    }
}