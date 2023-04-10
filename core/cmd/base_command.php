<?php 
namespace HexaPHP\Core\Cmd;
use RuntimeException;
use HexaPHP\Helpers\Str;

abstract class BaseCommand
{
    protected string $vendorName;
    abstract public function execute(array $args): void;

    protected function createDirectory($path)
    {
        if (!is_dir($path)) {
            echo "Creating directory: {$path}\n";
            mkdir($path, 0777, true);
        } else {
            echo "Directory already exists: {$path}\n";
        }
    }

    protected function createFilesFromStubs($stubsDir, $destinationDir)
    {
        $files = scandir($stubsDir);

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $stubPath = "{$stubsDir}/{$file}";
            $destinationPath = "{$destinationDir}/" . str_replace('.stub', '', $file);

            if (is_dir($stubPath)) {
                $this->createDirectory($destinationPath);
                $this->createFilesFromStubs($stubPath, $destinationPath);
            } else {
                $content = file_get_contents($stubPath);
                $content = str_replace('__APP__', basename($destinationDir), $content);
                file_put_contents($destinationPath, $content);
            }
        }
    }

    protected function getPlaceholders(string $jsonFilePath): array
    {
        if (!file_exists($jsonFilePath)) {
            throw new RuntimeException("JSON file not found: {$jsonFilePath}");
        }

        $content = file_get_contents($jsonFilePath);
        $placeholders = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException("Invalid JSON in file: {$jsonFilePath}");
        }

        return $placeholders;
    }

    protected function processFileName(string $fileName, array $replacements): string
    {
        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $fileName
        );
    }

    protected function processStubs(string $stubsDir, string $destinationDir, callable $callback): void
    {
        $files = scandir($stubsDir);

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $stubPath = "{$stubsDir}/{$file}";
            $destinationPath = "{$destinationDir}/" . str_replace('.stub', '', $file);

            if (is_dir($stubPath)) {
                $this->createDirectory($destinationPath);
                $this->processStubs($stubPath, $destinationPath, $callback);
            } else {
                $content = file_get_contents($stubPath);

                // Call the $callback function to replace the placeholders in the stub content
                $content = $callback($content);

                file_put_contents($destinationPath, $content);
            }
        }
    }


    protected function updateComposerJson(string $baseDir, string $libName): void
    {
        $composerJsonPath = $baseDir . 'composer.json';
        $composerJsonContent = file_get_contents($composerJsonPath);
        $composerJson = json_decode($composerJsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException("Invalid JSON in file: {$composerJsonPath}");
        }

        $newRepository = [
            'type' => 'path',
            'url' => "libs/{$libName}"
        ];

        $composerJson['repositories'][] = $newRepository;
        $composerJson['require']["{$this->vendorName}/{$libName}"] = "*";

        $updatedComposerJsonContent = json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        file_put_contents($composerJsonPath, $updatedComposerJsonContent);

        echo "composer.json has been updated to include the new library.\n";
    }

    public function stubReplace(array $placeholders, string $content, $delim = "|"): string
    {
        return preg_replace_callback('/\{\{([\w|]+)\}\}/', function ($matches) use ($delim, $placeholders) {
            [$varName, $func] = array_pad(explode($delim, $matches[1], 2), 2, null);
            if (isset($placeholders[$varName])) {
                $value = $placeholders[$varName];
                if ($func) {
                    if (method_exists(Str::class, $func)) {
                        $value = Str::$func($value);
                    } else {
                        throw new RuntimeException("Invalid function: {$func}");
                    }
                }
                return $value;
            }
            throw new RuntimeException("Invalid placeholder: {$varName}");

        }, $content);
    }
}