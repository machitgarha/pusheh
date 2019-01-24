<?php

namespace MAChitgarha\Component;

class Pusheh
{
    public static function makeDirRecursive(string $dirPath, int $mode = 0777)
    {
        if (is_dir($dirPath))
            return false;
        
        if (@mkdir($dirPath, $mode))
            return true;

        throw new \Exception("Cannot create $dirPath directory recursively");
    }
}