<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-08-18
 * Time: 19:56
 */

namespace Katniss\Everdeen\Utils\Storage;

use Illuminate\Http\UploadedFile;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Support\Str;

class StoreFile implements IStoreFile
{
    private static $fileSizeType = ['byte', 'bytes', 'KB', 'MB', 'GB'];

    protected static $fileUrl;
    protected static $filePath;
    protected static $collectionPath;
    protected static $tmpPath;
    protected static $userFolderPrefix;

    public static function init()
    {
        self::$fileUrl = _k('file_url');
        self::$filePath = _k('file_path');
        self::$collectionPath = self::toRealPath('collection');
        self::$tmpPath = self::toRealPath('collection/tmp');
        self::$userFolderPrefix = 'user_';
    }

    /**
     * @var string
     */
    protected $prefix;

    /**
     * @var File
     */
    protected $fileInfo;

    /**
     * StoreFile constructor.
     * @param \SplFileInfo|string $sourceFile
     * @param string $prefix
     */
    public function __construct($sourceFile, $prefix = 'file')
    {
        $this->fileInfo = $this->checkSourceFile($sourceFile);
        $this->prefix = $prefix;
    }

    /**
     * @param string|\SplFileInfo $sourceFile
     * @return File
     * @throws KatnissException
     */
    private function checkSourceFile($sourceFile)
    {
        if (is_string($sourceFile)) {
            if (!file_exists($sourceFile)) {
                throw new KatnissException(trans('error.file_not_found'));
            }

            return new File($sourceFile);
        }

        if (!is_a($sourceFile, \SplFileInfo::class)) {
            throw new KatnissException(trans('error.file_not_found'));
        }
        return new File($sourceFile->getRealPath());
    }

    protected function autoFilename()
    {
        $extension = $this->fileInfo->guessExtension();
        if (empty($extension)) {
            $extension = $this->fileInfo->getExtension();
        }
        return self::randomizeFilename($this->prefix, $extension);
    }

    public function moveRelative($targetDirectory, $name = null)
    {
        $this->move(self::toRealPath($targetDirectory), $name);
    }

    public function move($targetDirectory, $name = null)
    {
        self::checkDirectory($targetDirectory);

        if (empty($name)) {
            $name = $this->autoFilename();
        }

        $this->fileInfo = $this->fileInfo->move($targetDirectory, $name);
    }

    public function copyRelative($targetDirectory, $name = null)
    {
        $this->copy(self::toRealPath($targetDirectory), $name);
    }

    public function copy($targetDirectory, $name = null)
    {
        self::checkDirectory($targetDirectory);

        if (empty($name)) {
            $name = $this->autoFilename();
        }

        $this->fileInfo = $this->fileInfo->copy($targetDirectory, $name);
    }

    public function duplicateRelative($targetDirectory, $name = null)
    {
        return $this->duplicate(self::toRealPath($targetDirectory), $name);
    }

    public function duplicate($targetDirectory, $name = null)
    {
        self::checkDirectory($targetDirectory);

        if (empty($name)) {
            $name = $this->autoFilename();
        }

        $targetFileInfo = $this->fileInfo->copy($targetDirectory, $name);

        $clonedStoreFile = clone $this;
        $clonedStoreFile->fileInfo = $targetFileInfo;

        return $clonedStoreFile;
    }

    public function moveToTmp()
    {
        $this->move(self::$tmpPath);
    }

    public function moveToCollection($time = 'now', $name = null)
    {
        $this->move(self::collectionPath($time), $name);
    }

    public function moveToUser($userId, $relativePath = '')
    {
        $this->move(self::userPath($userId, $relativePath));
    }

    public function getUrl()
    {
        return self::toUrl($this->getRelativePath());
    }

    public function getRelativePath()
    {
        return self::toRelativePath($this->fileInfo->getRealPath());
    }

    public function getRealPath()
    {
        return $this->fileInfo->getRealPath();
    }

    public function getFileInfo()
    {
        return $this->fileInfo;
    }

    #region Static
    public static function checkDirectory($directory)
    {
        if (self::containBackDirectory($directory)) {
            throw new KatnissException(trans('error.directory_not_allowed') . ' (' . $directory . ')');
        }
        if (!is_dir($directory)) {
            if (false === @mkdir($directory, 0777, true) && !is_dir($directory)) {
                throw new KatnissException(trans('error.directory_not_found') . ' (' . $directory . ')');
            }
        }
        if (!is_writable($directory)) {
            throw new KatnissException(trans('error.directory_not_writable') . ' (' . $directory . ')');
        }
    }

    public static function randomizeFilename($prefix = null, $extension = null, $needTime = true, $needUnique = true, $moreUnique = true)
    {
        return Str::format('{0}{1}{2}{3}',
            empty($prefix) ? '' : $prefix . '_',
            $needTime ? time() . '_' : '',
            $needUnique ? uniqid('', $moreUnique) : '',
            empty($extension) ? '' : '.' . $extension
        );
    }

    public static function urlSeparator($path)
    {
        return str_replace('\\', '/', $path);
    }

    public static function dirSeparator($path)
    {
        return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
    }

    public static function containBackDirectory($path)
    {
        return Str::startsWith('..\\', $path)
            || Str::contains('\\..\\', $path)
            || Str::startsWith('../', $path)
            || Str::contains('/../', $path);
    }

    public static function concatDirectories()
    {
        $args = func_get_args();
        return implode(DIRECTORY_SEPARATOR, $args);
    }

    public static function concatUrl()
    {
        $args = func_get_args();
        return implode('/', $args);
    }

    public static function delete($url)
    {
        $file = self::concatDirectories(_k('file_path'), str_replace(_k('file_url'), '', $url));
        if (file_exists($file)) {
            return @unlink($file);
        }

        return false;
    }

    public static function collectionPath($time = 'now')
    {
        $date = new \DateTime($time, new \DateTimeZone('UTC'));
        return self::concatDirectories(self::$collectionPath, $date->format('Y'), $date->format('m'), $date->format('d'));
    }

    public static function userFolder($userId)
    {
        return self::$userFolderPrefix . $userId;
    }

    public static function userPath($userId, $relativePath = '')
    {
        if (empty($relativePath)) {
            return self::toRealPath(self::userFolder($userId));
        }
        return self::toRealPath(self::userFolder($userId) . DIRECTORY_SEPARATOR . $relativePath);
    }

    public static function toRealPath($relativePath = '')
    {
        if (empty($relativePath)) return self::$filePath;
        return self::concatDirectories(self::$filePath, $relativePath);
    }

    public static function toRelativePath($realPath)
    {
        return trim(str_replace(self::$filePath, '', $realPath), DIRECTORY_SEPARATOR);
    }

    public static function fileExistsRelative($relativePath)
    {
        return file_exists(self::toRealPath($relativePath));
    }

    public static function toUrl($relativePath)
    {
        return self::urlSeparator(self::concatDirectories(self::$fileUrl, $relativePath));
    }

    public static function toUrlFromRealPath($realPath)
    {
        return self::toUrl(self::toRelativePath($realPath));
    }

    /**
     * Returns the maximum size of an uploaded file as configured in php.ini.
     *
     * @return int The maximum size of an uploaded file in bytes
     */
    public static function maxUploadFileSize()
    {
        return UploadedFile::getMaxFilesize();
    }

    /**
     * @param int $fileSize File size in bytes
     * @return string
     */
    public static function asByte($fileSize)
    {
        return $fileSize . ' byte' . ($fileSize > 1 ? 's' : '');
    }

    /**
     * @param int $fileSize File size in bytes
     * @return string
     */
    public static function asKb($fileSize)
    {
        return round($fileSize / 1024) . 'KB';
    }

    /**
     * @param int $fileSize File size in bytes
     * @return string
     */
    public static function asMb($fileSize)
    {
        return round($fileSize / 1024 / 1024) . 'MB';
    }

    public static function asSize($fileSize, $typeIndex = 1)
    {
        if ($fileSize > 1024) {
            return self::asSize($fileSize / 1024, ++$typeIndex);
        }

        if ($typeIndex == 1 && $fileSize <= 1) {
            $typeIndex = 0;
        }
        return toFormattedNumber($fileSize) . ' ' . self::$fileSizeType[$typeIndex];
    }
    #endregion
}
