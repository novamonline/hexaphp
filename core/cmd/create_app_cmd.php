<?php
namespace Core\Cmd;

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
        $replacements = array_combine(
            array_keys($placeholders), 
            array_map(fn($varName) => $this->$varName, $placeholders)
        );

       
        $this->processStubs($stubsDir, $appPath, $replacements);
    }
}

