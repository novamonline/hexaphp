<?php

namespace HexaPHP\Core\Cmd;

class ServeAppCmd extends BaseCommand
{
    private string $appName;
    private string $serverName;
    private string $host;
    private int $port = 8000;

    public function execute(array $args): void
    {
        $this->appName = array_shift($args);
        $this->serverName = array_shift($args) ?? 'localhost';
        
        $appRoot = "./apps/{$this->appName}/public";

        if (in_array('--docker', $args)) {
            $this->useDocker($appRoot);
        } else {
            $this->useLocal($appRoot);
        }
    }

    private function useLocal(string $appRoot){
       
        echo "Serving app from $appRoot...\n";
        if (strpos($this->serverName, ':') !== false) {
            $parts = explode(':', $this->serverName);
            [$this->host, $this->port] = array_pad($parts, 2, null);
        } else if(ctype_digit($this->serverName)) {
            $this->host = 'localhost';
            $this->port = $this->serverName;
        } else {
            $this->host = $this->serverName;
        }

        // Construct the command to start the web server
        $command = sprintf('php -S %s:%d -t %s', $this->host, $this->port, $appRoot);

        // Start the web server
        echo "Starting web server on http://{$this->host}:{$this->port}...\n";
        passthru($command);
    }

    private function useDocker(string $appRoot){
        echo "Running docker from $appRoot...\n";
        $bldCMD = sprintf('docker build -t %s:latest -f %s/Dockerfile %s', $this->appName, $appRoot, $appRoot);
        $runCMD = sprintf('docker run -it --rm -v $(pwd):/app -w /app -p %s:8000 %s php -S 0.0.0.0:8000', $this->port, $this->appName);
        passthru($bldCMD);
        passthru($runCMD);
    }
}
