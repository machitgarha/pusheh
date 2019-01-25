<?php

namespace MAChitgarha\UnitTest\Pusheh;

use PHPUnit\Framework\TestCase;
use MAChitgarha\Component\Pusheh;

class MethodTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        Pusheh::createDirRecursive(__DIR__ . "/dir3/sub");
        $tmpFiles = [
            "something.txt",
            "another.php",
            "love-dashes.c"
        ];
        foreach ($tmpFiles as $tmpFile)
            touch(__DIR__ . "/dir3/$tmpFile");
        foreach ($tmpFiles as $tmpFile)
            touch(__DIR__ . "/dir3/sub/$tmpFile");
    }

    public function testCreateDir()
    {
        $this->assertFalse(Pusheh::createDir("."));
        $this->assertFalse(Pusheh::createDir(".."));
        $this->assertTrue(Pusheh::createDir(__DIR__ . "/dir0"));
        $this->assertTrue(Pusheh::createDir(__DIR__ . "/dir1", 0444));
        $this->assertTrue(Pusheh::createDirRecursive(__DIR__ . "/dir2/sub"));
    }

    /**
     * @depends testCreateDir
     */
    public function testClearDir()
    {
        $this->assertTrue(Pusheh::clearDir(__DIR__ . "/dir3"));
    }

    /**
     * @depends testClearDir
     */
    public function testRemoveDir()
    {
        self::setUpBeforeClass();
        $this->assertFalse(Pusheh::removeDir(__DIR__ . "/dir-1"));
        $this->assertFalse(Pusheh::removeDir(__DIR__ . "/dir-1/deep"));
        foreach (glob(__DIR__ . "/dir*") as $dir)
            $this->assertTrue(Pusheh::removeDirRecursive($dir));
    }
}