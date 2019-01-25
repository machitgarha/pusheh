<?php

namespace MAChitgarha\Component;

use Webmozart\PathUtil\Path;

class Pusheh
{
    public static function createDir(string $dirPath, int $mode = 0777, bool $recursive = true)
    {
        if (is_dir($dirPath))
            return false;
        
        if (@mkdir($dirPath, $mode, $recursive))
            return true;

        throw new \Exception("Cannot create $dirPath directory");
    }

    public static function createDirRecursive(string $dirPath, int $mode = 0777)
    {
        self::createDir($dirPath, $mode, true);
    }

    public static function clearDir(string $dirPath)
    {
        if (!is_dir($dirPath))
            throw new \Exception("Directory $dirPath does not exist");

        $dirIt = new \DirectoryIterator($dirPath);
        foreach ($dirIt as $content) {
            if ($content->isDot())
                continue;
            if ($content->isDir())
                self::removeDirRecursive($content->getPathname());
            if ($content->isFile())
                unlink($content->getPathname());
        }

        return true;
    }

    public static function removeDir(string $dirPath, bool $recursive = false)
    {
        if ($recursive)
            self::removeDirRecursive($dirPath);
        
        if (!is_dir($dirPath))
            return false;

        if (@rmdir($dirPath))
            return true;

        throw new \Exception("Cannot remove $dirPath directory");
    }

    public static function removeDirRecursive(string $dirPath)
    {
        try {
            self::clearDir($dirPath);
        } catch (\Exception $e) {
            return false;
        }

        return self::removeDir($dirPath);
    }
}