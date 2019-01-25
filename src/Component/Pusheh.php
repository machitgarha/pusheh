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

    public static function removeDir(string $dirPath, bool $recursive = false)
    {
        if ($recursive)
            self::removeDirRecursive();

        if (@rmdir($dirPath))
            return true;

        throw new \Exception("Cannot remove $dirPath directory");
    }

    public static function removeDirRecursive(string $dirPath)
    {
        $dirIt = new \DirectoryIterator($dirPath);
        $content;
        foreach ($dirIt as $content) {
            if ($content->isDot())
                continue;
            if ($content->isDir())
                self::removeDirRecursive($content->getPathname());
            if ($content->isFile())
                unlink($content->getPathname());
        }
        self::removeDir($dirPath);
    }
}