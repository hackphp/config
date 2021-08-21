<?php

namespace Hackphp\Config;

use Hackphp\Config\Contracts\Parser;

class Config
{
    /**
     * The parsed config.
     *
     * @var array
     */
    protected array $config;

    /**
     * The parsers classes.
     *
     * @var Parser[]
     */
    protected array $parsers = [];

    /**
     * Create new Config.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * Add Config Parser.
     *
     * @param  Parser $parser
     * @return $this
     */
    public function addParser(Parser $parser)
    {
        if (!in_array($parser, $this->parsers)) {
            $this->parsers[] = $parser;
        }

        return $this;
    }

    /**
     * Load the config loader.
     *
     * @return $this
     */
    public function load()
    {
        foreach ($this->parsers as $parser) {
            $this->config = array_merge($this->config, $parser->parse());
        }

        return $this;
    }

    /**
     * Get the given config value.
     *
     * @param  string $key
     * @param  mixed|null $default
     * @return mixed|null
     */
    public function get($key, $default = null)
    {
        $filtered = $this->config;

        if (isset($filtered[$key])) {
            return $filtered[$key];
        }

        $this->catchSearchFile($filtered, $key);

        return $this->getFilteredValue($filtered, $key, $default);
    }

    /**
     * Catch the file that has the key we search about it.
     *
     * @param  array $filtered
     * @param  string $key
     * @return array
     */
    private function catchSearchFile(array &$filtered, $key)
    {
        return array_reduce(explode(".", $key), function ($carry, $segment) use (&$filtered) {
            if ($carry != null) {
                $key = $carry . "." . $segment;
            } else {
                $key = $segment;
            }

            $values = $this->filterStartsWith($filtered, $key);

            if ($values) {
                $filtered = $values;
            }

            return $key;
        });
    }

    /**
     * Get the array items that it's key starts with the given name.
     *
     * @param  array $haystack
     * @param  string $name
     * @return array
     */
    private function filterStartsWith(array $haystack, string $name)
    {
        $filtered = [];

        foreach ($haystack as $key => $value) {
            if (strpos($key, $name) === 0) {
                $filtered[$key] = $value;
            }
        }

        return $filtered;
    }

    /**
     * Get the given key value from the filtered configs.
     *
     * @param  array $filtered
     * @param  string $key
     * @param  mixed $default
     * @return mixed
     */
    private function getFilteredValue($filtered, $key, $default)
    {
        if (empty($filtered)) {
            return $default;
        }

        $file = array_keys($filtered)[0];
        $filtered = array_values($filtered)[0];

        $key = str_replace($file . ".", "", $key);

        foreach (explode(".", $key) as $segment) {
            $filtered = $filtered[$segment] ?? $default;
        }

        return $filtered ?? $default;
    }
}
