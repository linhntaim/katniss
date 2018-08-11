<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2017-04-29
 * Time: 20:41
 */

namespace Katniss\Everdeen\Imports;


use Katniss\Everdeen\Utils\Storage\StoreFile;

abstract class Import
{
    protected $logs;

    /**
     * @var StoreFile
     */
    protected $file;

    protected $total;
    protected $imported;

    public function __construct($tmpFile)
    {
        $this->logs = [];
        $this->file = new StoreFile($tmpFile);
    }

    public abstract function run();

    protected function addLog($log)
    {
        $this->logs[] = $log;
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function getImported()
    {
        return $this->imported;
    }

    public function getLogs($glue = "\n")
    {
        return empty($glue) ? $this->logs : implode($glue, $this->logs);
    }
}