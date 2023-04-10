<?php 

namespace HexaPHP\Core\Cmd;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use HexaPHP\Helpers\Str;


class StubProcessor
{
    public static function processStubs(string $sourceDir, string $targetDir, array $replacements): void
    {
        $sourceDir = rtrim($sourceDir, '/');
        $targetDir = rtrim($targetDir, '/');
        $dirIterator = new RecursiveDirectoryIterator($sourceDir);
        $iterator = new RecursiveIteratorIterator($dirIterator, RecursiveIteratorIterator::SELF_FIRST);

        foreach ($iterator as $item) {
            $relativePath = substr($item->getPathname(), strlen($sourceDir) + 1);
            $newPath = $targetDir . '/' . str_replace(array_keys($replacements), array_values($replacements), $relativePath);

            if ($item->isDir()) {
                if (!file_exists($newPath)) {
                    mkdir($newPath, 0755, true);
                }
            } else {
                $content = file_get_contents($item->getPathname());
                $content = str_replace(array_keys($replacements), array_values($replacements), $content);
                file_put_contents($newPath, $content);
            }
        }
    }

    public function replace(array $placeholders, string $content): string
    {
        $delim = '|';

        return preg_replace_callback('/\{\{([\w|]+)\}\}/', function ($matches) use ($delim, $placeholders) {
            [$varName, $func] = array_pad(explode($delim, $matches[1], 2), 2, null);
            if (isset($placeholders[$varName])) {
                $value = $placeholders[$varName];
                if ($func) {
                    if (method_exists(Str::class, $func)) {
                        $value = Str::$func($value);
                    } else {
                        throw new \RuntimeException("Invalid function: {$func}");
                    }
                }
                return $value;
            }
            throw new \RuntimeException("Invalid placeholder: {$varName}");
        }, $content);
    }
}
