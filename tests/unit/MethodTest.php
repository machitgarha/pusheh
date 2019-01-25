<?php

/**
 *
 * Unit tests for MAChitgarha\Component\Pusheh class.
 *
 * Go to the project's root and run the tests in this way:
 * phpunit --bootstrap vendor/autoload.php tests/unit
 *
 * @see MAChitgarha\Component\Pusheh
 */
namespace MAChitgarha\UnitTest\Pusheh;

use PHPUnit\Framework\TestCase;
use MAChitgarha\Component\Pusheh;

/**
 * Tests all public methods.
 */
class MethodTest extends TestCase
{
    /** @var string Path to the directory which tests must be done there. */
    protected static $testsPath = __DIR__ . "/../data";

    public static function setUpBeforeClass()
    {
        // Create a directory with some arbitrary files for the tests
        Pusheh::createDirRecursive(self::$testsPath . "/dir3/sub");
        $tmpFiles = [
            "something.txt",
            "another.php",
            "bash-loves-dashes.c"
        ];
        foreach ($tmpFiles as $tmpFile)
            touch(self::$testsPath . "/dir3/$tmpFile");
        foreach ($tmpFiles as $tmpFile)
            touch(self::$testsPath . "/dir3/sub/$tmpFile");
    }

    /**
     * Tests Pusheh::createDir*() methods.
     */
    public function testCreateDir()
    {
        $this->assertFalse(Pusheh::createDir("."));
        $this->assertFalse(Pusheh::createDir(".."));

        $this->assertTrue(Pusheh::createDir(self::$testsPath . "/dir0"));
        $this->assertTrue(Pusheh::createDir(self::$testsPath . "/dir1", 0444));

        $this->assertTrue(Pusheh::createDirRecursive(self::$testsPath . "/dir2/sub"));
    }

    /**
     * Tests Pusheh::clearDir() method.
     * @depends testCreateDir
     */
    public function testClearDir()
    {
        $this->assertTrue(Pusheh::clearDir(self::$testsPath . "/dir3"));

        // Removes the symbolic link with all of its contents, and a test after it
        $this->assertTrue(Pusheh::clearDir(self::$testsPath . "/link"));
        $this->assertFalse(file_exists(self::$testsPath . "/link/file.txt"));
    }

    /**
     * Tests Pusheh::removeDir*() methods.
     * @depends testClearDir
     */
    public function testRemoveDir()
    {
        // Some files has removed by Pusheh::clearDir(), so regenerate them
        self::setUpBeforeClass();
        touch(self::$testsPath . "/linked/test.txt");

        // Test removing non-exist directories
        $this->assertFalse(Pusheh::removeDir(self::$testsPath . "/dir-1"));
        $this->assertFalse(Pusheh::removeDir(self::$testsPath . "/dir-1/deep"));

        // Remove all of the created test directories
        foreach (glob(self::$testsPath . "/dir*") as $dir) {
            $this->assertTrue(Pusheh::removeDirRecursive($dir));
            $this->assertFalse(is_dir($dir));
        }

        // Remove the symbolic link softly, and check it
        Pusheh::removeDirRecursive(self::$testsPath . "/link");
        $this->assertTrue(file_exists(self::$testsPath . "/linked/test.txt"));
    }

    public static function tearDownAfterClass()
    {
        symlink(self::$testsPath . "/linked", self::$testsPath . "/link");
    }
}