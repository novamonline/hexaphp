<?php
namespace HexaPHP\Core\Cmd;

use HexaPHP\Helpers\Str;

class CreateAppCmd extends BaseCommand
{
    protected string $appName;
    protected string $vendorName = "novam";
    public function execute(array $args): void
    {
        echo "Creating a new App...\n";
        $this->appName = $args[0];
        $appPath = "./apps/{$this->appName}";
        $this->createDirectory($appPath);
        
        $stubsDir = "./core/stubs/app";
        $placeholders = $this->getPlaceholders("{$stubsDir}_placeholders.json");

        $this->processStubs($stubsDir, $appPath, function ($content) use($placeholders) {
            return $this->stubReplace($placeholders, $content, "|");
        });

    }
}

