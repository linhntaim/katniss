<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2017-08-22
 * Time: 15:05
 */

namespace Katniss\Everdeen\Exports;


use Katniss\Everdeen\Utils\Storage\StoreFile;

abstract class Export
{
    protected $data;

    /**
     * @var StoreFile
     */
    protected $storeFile;

    protected abstract function getHeader();

    protected abstract function getDataAsFlatArray();

    protected abstract function prepare();

    protected abstract function export();

    public abstract function run();

    public function getUrl()
    {
        return empty($this->storeFile) ? null : $this->storeFile->getUrl();
    }
}