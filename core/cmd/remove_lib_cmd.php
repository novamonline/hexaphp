<?php
namespace HexaPHP\Core\Cmd;
use HexaPHP\Helpers\Str;

class RemoveLibCmd extends BaseCommand
{
    protected string $libName;
    protected string $vendorName = "novam";

    public function execute(array $args): void
    {
        $this->libName = $args[0];
        $basePath = getcwd() . '/';
        $libPath = $basePath . "libs/{$this->libName}";

        if (is_dir($libPath)) {
            $this->removeDirectory($libPath);
        }        
        $this->removeFromComposerJson(ROOT, $this->libName);
    }

     
}

