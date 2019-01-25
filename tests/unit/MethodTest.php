<?php

namespace MAChitgarha\UnitTest\Pusheh;

use PHPUnit\Framework\TestCase;
use MAChitgarha\Component\Pusheh;

class MethodTest extends TestCase
{
    protected static $testsDir = __DIR__ . "/../data";

    public static function setUpBeforeClass()
    {
        Pusheh::createDirRecursive(self::$testsDir . "/dir3/sub");

        $tmpFiles = [
            "something.txt",
            "another.php",
            "bash-loves-dashes.c"
        ];
        foreach ($tmpFiles as $tmpFile)
            touch(self::$testsDir . "/dir3/$tmpFile");
        foreach ($tmpFiles as $tmpFile)
            touch(self::$testsDir . "/dir3/sub/$tmpFile");
    }

    public function testCreateDir()
    {
        $this->assertFalse(Pusheh::createDir("."));
        $this->assertFalse(Pusheh::createDir(".."));
        $this->assertTrue(Pusheh::createDir(self::$testsDir . "/dir0"));
        $this->assertTrue(Pusheh::createDir(self::$testsDir . "/dir1", 0444));
        $this->assertTrue(Pusheh::createDirRecursive(self::$testsDir . "/dir2/sub"));
    }

    /**
     * @depends testCreateDir
     */
    public function testClearDir()
    {
        $this->assertTrue(Pusheh::clearDir(self::$testsDir . "/dir3"));
        $this->assertTrue(Pusheh::clearDir(self::$testsDir . "/link"));
        $this->assertFalse(file_exists(self::$testsDir . "/link/file.txt"));
    }

    /**
     * @depends testClearDir
     */
    public function testRemoveDir()
    {
        self::setUpBeforeClass();
        touch(self::$testsDir . "/linked/test.txt");

        $this->assertFalse(Pusheh::removeDir(self::$testsDir . "/dir-1"));
        $this->assertFalse(Pusheh::removeDir(self::$testsDir . "/dir-1/deep"));
        foreach (glob(self::$testsDir . "/dir*") as $dir)
            $this->assertTrue(Pusheh::removeDirRecursive($dir));
        Pusheh::removeDirRecursive(self::$testsDir . "/link");
        $this->assertTrue(file_exists(self::$testsDir . "/linked/test.txt"));
    }

    public static function tearDownAfterClass()
    {
        symlink(self::$testsDir . "/linked", self::$testsDir . "/link");
    }
}