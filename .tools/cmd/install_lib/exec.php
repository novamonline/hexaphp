<?php

// Get the name of the app from the user
$app_name = readline("Enter the name of your app: ");

// Determine the root directory of the project
$root_dir = getcwd();

// Copy the app stubs to a new directory with the given app name
recursiveCopy("{$root_dir}/.tools/stubs/app", "{$root_dir}/apps/{$app_name}");

// Replace "__APP__" with the app name in the copied stub files and remove the .stub extension
recursiveRename("{$root_dir}/apps/{$app_name}", "/\.stub$/", function($filename, $new_filename) use ($app_name) {
    $content = file_get_contents($filename);
    $content = str_replace("__APP__", $app_name, $content);
    file_put_contents($new_filename, $content);
});

// Copy the lib stubs to a new directory with the given app name
recursiveCopy("{$root_dir}/.tools/stubs/lib", "{$root_dir}/apps/{$app_name}/lib");

// Remove the .stub extension from the lib composer.json file
rename("{$root_dir}/apps/{$app_name}/lib/composer.json.stub", "{$root_dir}/apps/{$app_name}/lib/composer.json");

// Replace "__APP__" with the app name in the Dockerfile stub and remove the .stub extension
$content = file_get_contents("{$root_dir}/.tools/stubs/app/Dockerfile.stub");
$content = str_replace("__APP__", $app_name, $content);
file_put_contents("{$root_dir}/apps/{$app_name}/Dockerfile", $content);
unlink("{$root_dir}/apps/{$app_name}/Dockerfile.stub");

echo "Your app has been created in the {$root_dir}/apps/{$app_name} directory.\n";

/**
 * Recursively copy a directory and its contents.
 *
 * @param string $src The source directory to copy.
 * @param string $dst The destination directory to copy to.
 */
function recursiveCopy($src, $dst) {
    $dir = opendir($src);
    if (!is_dir($dst)) {
        mkdir($dst);
    }
    while (($file = readdir($dir)) !== false) {
        if ($file == "." || $file == "..") {
            continue;
        }
        $src_file = $src . DIRECTORY_SEPARATOR . $file;
        $dst_file = $dst . DIRECTORY_SEPARATOR . $file;
        if (is_dir($src_file)) {
            recursiveCopy($src_file, $dst_file);
        } else {
            copy($src_file, $dst_file);
        }
    }
    closedir($dir);
}

/**
 * Recursively rename files in a directory and its subdirectories.
 *
 * @param string $dir The directory to rename files in.
 * @param string $pattern A regular expression to match filenames to rename.
 * @param callable $callback A function to call for each renamed file.
 */
function recursiveRename($dir, $pattern, $callback) {
    $dir = realpath($dir);
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir), RecursiveIteratorIterator::SELF_FIRST);
    foreach ($iterator as $file) {
        if ($file->isFile() && preg_match($pattern, $file->getFilename())) {
            $new_filename = preg_replace($pattern, "", $file->getPathname());
            $callback($file->getPathname(), $new_filename);
        }
    }
}