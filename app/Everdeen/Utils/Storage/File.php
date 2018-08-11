<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2017-05-23
 * Time: 14:54
 */

namespace Katniss\Everdeen\Utils\Storage;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File as BaseFile;

class File extends BaseFile
{
    protected function getTargetFile($directory, $name = null)
    {
        return new File(parent::getTargetFile($directory, $name)->getPathname(), false);
    }

    public function copy($directory, $name)
    {
        $target = $this->getTargetFile($directory, $name);

        if (!@copy($this->getPathname(), $target)) {
            $error = error_get_last();
            throw new FileException(sprintf('Could not move the file "%s" to "%s" (%s)', $this->getPathname(), $target, strip_tags($error['message'])));
        }

        @chmod($target, 0666 & ~umask());

        return $target;
    }
}