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
$root = dirname(__DIR__ . '/../../');
// Get the name of the new package
$packageName = readline('Enter the name of the new package (e.g. my-guzzle-wrapper): ');

// Define the path to the packages directory
$packagesPath = $root. "/" .$packageDir . "/";

// Create the package directory
mkdir($packagesPath . $packageName);

// Create the composer.json file
$composerJson = [
    'name' => $monorepo. '/' . $packageName,
    'description' => 'Description of the ' . $packageName . ' package',
    'version' => '1.0.0',
    'autoload' => [
        'psr-4' => [
            "'$Namespace\\'" . ucfirst($packageName) . '\\' => 'src/'
        ]
    ],
    'require' => [
        // Add any required packages here
    ],
];
file_put_contents($packagesPath . $packageName . '/composer.json', json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

// Create the src directory
mkdir($packagesPath . $packageName . '/src');

// Create an example file
file_put_contents($packagesPath . $packageName . '/src/Example.php', "<?php\n\nnamespace $Namespace\\" . ucfirst($packageName) . ";\n\nclass Example\n{\n    public function hello()\n    {\n        echo 'Hello from the " . $packageName . " package!';\n    }\n}\n");

// Update the root composer.json file to include the new package
$rootComposerJson = json_decode(file_get_contents($root . '/composer.json'), true);
$rootComposerJson['repositories'][] = [
    'type' => 'path',
    'url' => "'$packageDir/'" . $packageName,
];
$rootComposerJson['require'][$packageName] = '^1.0';
file_put_contents($root . '/composer.json', json_encode($rootComposerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

echo 'The ' . $packageName . ' package has been created successfully.' . PHP_EOL;
