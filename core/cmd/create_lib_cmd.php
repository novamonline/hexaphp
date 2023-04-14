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
        echo "Creating a new lib: $this->libName...\n";
        $libPath = "./libs/{$this->libName}";
        $this->createDirectory($libPath);

        $stubsDir = dirname(__DIR__) . "/stubs/lib";
        $placeholders = $this->getPlaceholders("{$stubsDir}_placeholders.json");
        $placeholders['__NAME__'] = $this->libName;
        var_dump($placeholders);

        $this->processStubs($stubsDir, $libPath, function ($content) use($placeholders) {
            return $this->stubReplace($placeholders, $content);
        });

        $this->addToComposerJson(ROOT, $this->libName);
    }

    
}

