<?php

/**
 * Unit tests for MAChitgarha\Component\JSON class.
 *
 * Go to the project's root and run the tests in this way:
 * phpunit --bootstrap vendor/autoload.php tests/unit
 * Using the --repeat option is recommended.
 *
 * @see MAChitgarha\Component\JSON
 */
namespace MAChitgarha\UnitTest\Pusheh;

use PHPUnit\Framework\TestCase;
use MAChitgarha\Component\Pusheh;
use Webmozart\PathUtil\Path;

/**
 * Except \Exception in all of the cases.
 */
class ExceptionTest extends TestCase
{
    /** @var string Path to a test directory. */
    protected static $testDirPath = __DIR__ . "/../data/test";

    /** @var string Path to a sub-directory of the test directory. */
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

    /**
     * Tests when a directory cannot be created.
     */
    public function testCreateDir()
    {
        Pusheh::createDir(self::$testSubDirPath);
    }

    /**
     * Tests when a directory cannot be cleared.
     */
    public function testClearDir()
    {
        Pusheh::clearDir(self::$testSubDirPath);
    }

    public static function tearDownAfterClass()
    {
        Pusheh::removeDir(self::$testDirPath);
    }
}