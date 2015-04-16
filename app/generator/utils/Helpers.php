<?php

namespace Generator\Utils;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

final class Helpers
{

    /**
     * Purges directory.
     *
     * @param string
     * @return void
     */
    public static function purge($dir)
    {
        if (!is_dir($dir)) {
            mkdir($dir);
        }
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST) as $entry) {
            if ($entry->isDir()) {
                rmdir($entry);
            } else {
                unlink($entry);
            }
        }
    }
}