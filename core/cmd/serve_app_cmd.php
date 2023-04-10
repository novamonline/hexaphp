<?php

namespace HexaPHP\Core\Cmd;

class ServeAppCmd extends BaseCommand
{
    private string $appName;
    private string $host;
    private int $port;

    // public function __construct(string $appDirectory, string $host = 'localhost', int $port = 8000)
    // {
    //     $this->appDirectory = $appDirectory;
    //     $this->host = $host;
    //     $this->port = $port;
    // }

    public function execute(array $args): void
    {
        var_dump($args);
        $this->appName = $args[0];
        $this->host = $args[1] ?? 'localhost';
        $this->port = $args[2] ?? 8000;
        $appRoot = "./apps/{$this->appName}";
        echo "Serving app from $appRoot...\n";

        // Construct the command to start the web server
        $command = sprintf('php -S %s:%d -t %s', $this->host, $this->port, $appRoot);

        // Start the web server
        echo "Starting web server on http://{$this->host}:{$this->port}...\n";
        passthru($command);
    }
}
