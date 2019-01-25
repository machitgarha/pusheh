<?php

namespace MAChitgarha\UnitTest\Pusheh;

use PHPUnit\Framework\TestCase;
use MAChitgarha\Component\Pusheh;
use Webmozart\PathUtil\Path;

class ExceptionTest extends TestCase
{
    protected static $testDirPath = __DIR__ . "/test";
    protected static $testSubDirPath;

    public static function setUpBeforeClass()
    {
        mkdir(self::$testDirPath, 0000);

        self::$testSubDirPath = Path::join(self::$testDirPath, "sub");
    }

    public function setUp()
    {
        $this->expectException(\Exception::class);
    }

    public function testCreateDir()
    {
        Pusheh::createDir(self::$testSubDirPath);
    }

    public function testClearDir()
    {
        Pusheh::clearDir(self::$testSubDirPath);
    }

    public static function tearDownAfterClass()
    {
        Pusheh::removeDir(self::$testDirPath);
    }
}