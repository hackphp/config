# HackPHP Config
HackPHP Config Access.

## Usage

```bash
composer require hackphp/config "1.0"
```

```php
use Hackphp\Config\Config;
use Hackphp\Config\Parsers\ArrayParser;

$config = new Config([
    "app" => [
        "name" => "HackPHP"
    ],
    "server" => [
        "host" => "127.0.0.1",
        "port" => 8000
    ],
]);

$configRootDir = __DIR__ . "/config";
$config->addParser(new ArrayParser($configRootDir));
$config->load();

echo $config->get("server.host");
// output: 127.0.0.1

echo $config->get("file.key", "default value");
// output: default value
```