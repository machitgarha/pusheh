<?php

namespace MAChitgarha\Component;

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
}