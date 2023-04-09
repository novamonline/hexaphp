<?php 

namespace Core\Cmd;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;


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
}
