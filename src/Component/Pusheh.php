<?php
/**
 * Pusheh class file.
 *
 * @author Mohammad Amin Chitgarha <machitgarha@outlook.com>
 * @see https://github.com/MAChitgarha/Pusheh
 */

namespace MAChitgarha\Component;

/**
 * Directory tools.
 *
 * @todo Add a new method to get directory size.
 * @todo Add Github Wiki and Packagist link.
 * @todo Add safe remove option to prevent removing /* things.
 */
class Pusheh
{
    /**
     * Creates a directory.
     *
     * @param string $dirPath Directory path.
     * @param integer $mode Directory access mode. {@see chmod()}
     * @param bool $recursive Create directories nested and one by one. {@see self::createDirRecursive}
     * @return bool Whether did directory exist or it has been created.
     * @throws \Exception When the directory cannot be created.
     */
    public static function createDir(string $dirPath, int $mode = 0777, bool $recursive = false)
    {
        if (is_dir($dirPath)) {
            return false;
        }

        if (@mkdir($dirPath, $mode, $recursive)) {
            return true;
        }

        throw new \Exception("Cannot create $dirPath directory");
    }

    /**
     * Creates a directory recursively.
     *
     * Creates nested directories, one by one, to reach the last one. In other words, creates every non-exist directory to reach the last one.
     *
     * @param string $dirPath Directory path.
     * @param integer $mode Directory access mode. {@see chmod()}
     * @return bool Whether did directory exist or it has been created.
     * @throws \Exception When the directory cannot be created.
     */
    public static function createDirRecursive(string $dirPath, int $mode = 0777)
    {
        return self::createDir($dirPath, $mode, true);
    }

    /**
     * Clears contents of a directory, i.e. empty the directory.
     *
     * @param string $dirPath Directory path.
     * @param bool $softLinks Doesn't allow symbolic links to remove the main directory contents. This doesn't affect on the symbolic links inside the directory.
     * @return bool Returns true when the directory cleared successfully, or false if the directory is a link and soft links is on.
     * @throws \Exception When the specified path is not a directory.
     * @throws \Exception If one of the directory contents cannot be removed.
     */
    public static function clearDir(string $dirPath, bool $softLinks = false)
    {
        if (is_link($dirPath) && $softLinks) {
            return false;
        }

        if (!is_dir($dirPath)) {
            throw new \Exception("Directory $dirPath does not exist");
        }

        // Iterate over the directory
        $dirIt = new \DirectoryIterator($dirPath);
        foreach ($dirIt as $content) {
            if ($content->isDot()) {
                continue;
            }
            if ($content->isFile() || $content->isLink()) {
                unlink($content->getPathname());
            }
            // Remove directories using recursion
            if ($content->isDir()) {
                self::removeDirRecursive($content->getPathname());
            }
        }

        return true;
    }

    /**
     * Removes a directory.
     *
     * @param string $dirPath Directory path.
     * @param boolean $recursive To remove the directory, if it's not empty. {@see self::removeDirRecursive}
     * @return bool Whether the directory removed, or the directory doesn't exit.
     * @throws \Exception If the directory cannot be removed.
     */
    public static function removeDir(string $dirPath, bool $recursive = false)
    {
        if ($recursive) {
            return self::removeDirRecursive($dirPath);
        }
        
        if (!is_dir($dirPath)) {
            return false;
        }

        if (@rmdir($dirPath)) {
            return true;
        }

        throw new \Exception("Cannot remove $dirPath directory");
    }

    /**
     * Removes a directory recursively.
     *
     * Removes a directory, even if is not empty (i.e. clear the directory before removing it).
     *
     * @param string $dirPath Directory path.
     * @param bool $softLinks Whether if it is a symbolic link, remove itself or remove the linked directory contents, also.
     * @return bool Whether did directory exist or it has been created.
     * @throws \Exception When the directory cannot be created.
     */
    public static function removeDirRecursive(string $dirPath, bool $softLinks = true)
    {
        if (is_link($dirPath) && $softLinks) {
            return unlink($dirPath);
        }

        try {
            self::clearDir($dirPath, $softLinks);
        } catch (\Exception $e) {
            return false;
        }

        return self::removeDir($dirPath);
    }
}
