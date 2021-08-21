<?php

if (!function_exists("configPath")) {
    /**
     * Get the tests config root directory.
     *
     * @return string
     */
    function configPath($path = null)
    {
        $dir = __DIR__ . "/config";

        if ($path) {
            $dir .= "/" . $path;
        }
        
        return $dir;
    }
}

if (!function_exists("getDirFiles")) {
    /**
     * Get the directory filles and folders.
     *
     * @param  string $dir
     * @return array
     */
    function getDirFiles($dir)
    {
        $files = scandir($dir);
        unset($files[0], $files[1]);

        return $files;
    }
}
