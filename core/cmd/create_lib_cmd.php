<?php
namespace Core\Cmd;

class CreateLibCmd extends BaseCommand
{
    protected string $libName;
    protected string $vendorName = "novam";

    public function execute(array $args): void
    {

        $this->libName = $args[0];
        $libPath = "./libs/{$this->libName}";
        $this->createDirectory($libPath);

        $stubsDir = "./core/stubs/lib";
        $placeholders = $this->getPlaceholders("{$stubsDir}_placeholders.json");
        $replacements = array_combine(
            array_keys($placeholders),
            array_map(fn($varName) => $this->$varName, $placeholders)
        );

        $this->processStubs($stubsDir, $libPath, $replacements);

        $this->updateComposerJson(ROOT, $this->libName);
    }

    
}

