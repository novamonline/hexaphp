<?php 

/**
 * Recursively copy files from a directory to another directory, replacing placeholders in the file contents.
 *
 * @param string $src The source directory to copy files from.
 * @param string $dst The destination directory to copy files to.
 * @param array $placeholders An associative array of placeholders and their replacements.
 * @param callable $callback A function to call for each copied file.
 */
function recursiveCopy($src, $dst, $placeholders = [], $callback = null) {
    if (!file_exists($dst)) {
        mkdir($dst, 0755, true);
    }

    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($src));
    foreach ($iterator as $file) {
        if ($file->isDir()) {
            continue;
        }

        $src_path = $file->getPathname();
        $dst_path = str_replace($src, $dst, $src_path);

        $content = file_get_contents($src_path);
        foreach ($placeholders as $placeholder => $replacement) {
            $content = str_replace($placeholder, $replacement, $content);
        }

        file_put_contents($dst_path, $content);
        if ($callback) {
            call_user_func($callback, $src_path, $dst_path);
        }
    }
}

/**
 * Recursively renames files and directories from a given pattern to another pattern.
 *
 * @param string $dir The directory to start renaming from.
 * @param string $from The pattern to replace.
 * @param string $to The pattern to replace with.
 */
function recursiveRename($dir, $from, $to) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir), RecursiveIteratorIterator::SELF_FIRST);
    foreach ($iterator as $file) {
        $old_path = $file->getPathname();
        if ($file->isDir()) {
            $new_path = str_replace($from, $to, $old_path);
            rename($old_path, $new_path);
        } else {
            $old_dirname = dirname($old_path);
            $new_dirname = str_replace($from, $to, $old_dirname);
            if (!file_exists($new_dirname)) {
                mkdir($new_dirname, 0755, true);
            }
            $new_path = $new_dirname . '/' . basename($old_path);
            rename($old_path, $new_path);
        }
    }
}




/**
 * Recursively remove a directory and its contents.
 *
 * @param string $dir The directory to remove.
 */
function recursiveRemove($dir) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);

    foreach ($iterator as $file) {
        if ($file->isDir()) {
            rmdir($file->getPathname());
        } else {
            unlink($file->getPathname());
        }
    }

    rmdir($dir);
}
