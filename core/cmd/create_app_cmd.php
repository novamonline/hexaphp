<?php
namespace HexaPHP\Core\Cmd;

use HexaPHP\Helpers\Str;

class CreateAppCmd extends BaseCommand
{
    protected string $appName;
    protected string $vendorName = "novam";
    public function execute(array $args): void
    {
        $this->appName = $args[0];
        echo "Creating a new app: $this->appName...\n";
        $appPath = "./apps/{$this->appName}";
        $this->createDirectory($appPath);

        $stubsDir = dirname(__DIR__) . "/stubs/app";
        $placeholders = $this->getPlaceholders("{$stubsDir}_placeholders.json");
        $placeholders['__NAME__'] = $this->appName;

        $this->processStubs($stubsDir, $appPath, function ($content) use($placeholders) {
            return $this->stubReplace($placeholders, $content);
        });

    }
}

