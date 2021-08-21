<?php

namespace Hackphp\Config\Parsers;

use Hackphp\Config\Contracts\Parser;
use Throwable;

class ArrayParser implements Parser
{
    /**
     * The config files directory path.
     *
     * @var string
     */
    protected string $directory;

    /**
     * Create new ArrayParser
     *
     * @param string $directory The config files directory path.
     */
    public function __construct(string $directory)
    {
        $this->directory = $directory;
    }

    /**
     * @inheritDoc
     */
    public function parse()
    {
        $parsed = [];

        $files = $this->getConfigFiles($this->directory);

        foreach ($files as $namespace => $filePath) {
            try {
                $parsed[$namespace] = require $filePath;
            } catch (Throwable $e) {
                // 
            }
        }

        unset($files);

        return $parsed;
    }

    /**
     * Get the config files.
     *
     * @param  string $dir
     * @return array
     */
    protected function getConfigFiles($dir)
    {
        $files = [];

        foreach (getDirFiles($dir) as $file) {
            $path = $dir . "/" . $file;

            if (is_dir($path)) {
                $files = array_merge($files, $this->getConfigFiles($path));
                continue;
            }

            $configPath = str_replace($this->directory . "/", "", $path);

            $parts = explode("/", $configPath);
            $parts[count($parts) - 1] = str_replace(".php", "", end($parts));

            $files[implode(".", $parts)] = $path;
        }

        return $files;
    }
}
