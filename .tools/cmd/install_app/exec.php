<?php


require_once realpath($GLOBALS['toolsDir'] . '/fileop.php');

$app_name = readline("Enter the name of your app: ");

$src_dir = realpath($GLOBALS['stubsDir'] . '/app');
$dst_dir = realpath($GLOBALS['cmdDir'] . '/install_app/.temp') . DS . $app_name;

// Create the destination directory
if (!file_exists($dst_dir)) {
    mkdir($dst_dir, 0755, true);
}

// Copy the stub files to the destination directory
$callback = function($src, $dst) {
    echo "Created file: " . $dst . PHP_EOL;
};
recursiveCopy($src_dir, $dst_dir, $callback);

// Rename the stub files
$placeholders = [
    "__APP__" => $app_name,
    "__SOME_PLACEHOLDER__" => "some_value",
    // Add more placeholders here
];
recursiveRename($dst_dir, "/\.stub$/", $placeholders, $callback);

// Move the app to the apps directory
$app_dir = realpath($GLOBALS['cmdDir'] . '/install_app/.temp') . DS . $app_name;
$dst_dir = realpath($GLOBALS['cmdDir'] . '/install_app/.temp') . DS . 'apps' . DS . $app_name;

if (!file_exists($dst_dir)) {
    mkdir($dst_dir, 0755, true);
}

recursiveCopy($app_dir, $dst_dir);
recursiveRemove($app_dir);

echo "App created successfully: " . $dst_dir . PHP_EOL;
