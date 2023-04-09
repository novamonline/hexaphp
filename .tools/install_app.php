<?php

/**
 *********************************************************************************************** 
 * 
 ***********************************************************************************************
 *
 */
$Namespace = "HexMonoPHP";
$monorepo = "novamonline";
$packageDir = "libs";
$root = dirname(__DIR__);

// Define the name of the new app
$appName = readline('Enter the name of the new app: ');

// Define the path to the new app directory
$appPath =$root . '/apps/' . $appName;

// Create the new app directory
mkdir($appPath);

// Create the composer.json file
$composerJson = [
    'name' => $monorepo. '/'  . $appName,
    'description' => 'Description of the ' . $appName . ' app',
    'require' => [
        // Add any required packages here
    ],
    'autoload' => [
        'psr-4' => [
            'App\\' => 'src/'
        ]
    ]
];
file_put_contents($appPath . '/composer.json', json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

// Create the src directory
mkdir($appPath . '/src');

// Create default directories
$defaultDirs = ['adapters', 'domain', 'ports'];
foreach($defaultDirs as $dir) {
    mkdir($appPath . '/src/' . $dir);
}

// Create index.php, config.php, loader.php
$defaultFiles = ['index', 'config', 'loader'];
foreach($defaultFiles as $file) {
    file_put_contents($appPath . '/src/' . $file . '.php', "<?php\n\n// Define your " . $file . ".php here\n");
}

echo 'The ' . $appName . ' app has been created successfully.' . PHP_EOL;
