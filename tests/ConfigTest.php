<?php

namespace Hackphp\Config\Tests;

use Hackphp\Config\Config;
use PHPUnit\Framework\TestCase;
use Hackphp\Config\Parsers\ArrayParser;

class ConfigTest extends TestCase
{
    protected string $configPath;

    protected function setUp(): void
    {
        $this->configPath = __DIR__ . "/config";
    }

    /** @test */
    public function it_can_create_instance_with_default_configs()
    {
        $config = new Config([
            "creator" => "Mohamed Samir",
            "email" => "gm.mohamedsamir@gmail.com"
        ]);

        $creator = $config->get("creator");

        $this->assertEquals("Mohamed Samir", $creator);
    }

    /** @test */
    public function it_loads_and_parse_all_directory_files_successfully()
    {
        $parser = new ArrayParser($this->configPath);

        $config = new Config();
        $config->addParser($parser);
        $config->load();

        $value = $config->get("app.name");
        $this->assertEquals("HackPHP", $value);
    }

    /** @test */
    public function it_loads_the_default_value_if_the_key_not_found()
    {
        $parser = new ArrayParser($this->configPath);

        $config = new Config();
        $config->addParser($parser);
        $config->load();

        $value = $config->get("app.test");
        $this->assertNull($value);

        $value = $config->get("app.test", true);
        $this->assertTrue($value);
    }
}
