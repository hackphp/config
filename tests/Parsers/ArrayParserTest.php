<?php

namespace Hackphp\Config\Tests\Parsers;

use Hackphp\Config\Contracts\Parser;
use Hackphp\Config\Parsers\ArrayParser;
use PHPUnit\Framework\TestCase;

class ArrayParserTest extends TestCase
{
    /** @test */
    public function it_must_be_instance_of_parser_interface()
    {
        $parser = new ArrayParser(configPath());

        $this->assertInstanceOf(Parser::class, $parser);
    }

    /** @test */
    public function it_parses_files_from_the_given_directory()
    {
        $parser = new ArrayParser(configPath("server"));
        $parsed = $parser->parse();

        $expected = [
            "http" => [
                "host" => "127.0.0.1",
                "port" => 8000
            ],
        ];

        $this->assertEquals($expected, $parsed);
    }

    /** @test */
    public function it_can_parse_nested_files()
    {
        $parser = new ArrayParser(configPath());
        $parsed = $parser->parse();

        $expected = [
            "app" => [
                "name" => "HackPHP"
            ],
            "server.http" => [
                "host" => "127.0.0.1",
                "port" => 8000
            ],
        ];

        $this->assertEquals($expected, $parsed);
    }
}
