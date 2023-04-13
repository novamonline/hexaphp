<?php
namespace HexaPHP\Core\Cmd;
use HexaPHP\Helpers\Str;

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
        
        $this->processStubs($stubsDir, $libPath, function ($content) use($placeholders) {
            return $this->stubReplace($placeholders, $content, "|");
        });

        $this->addToComposerJson(ROOT, $this->libName);
    }

    
}

