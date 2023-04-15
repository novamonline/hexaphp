<?php

namespace HexaPHP\Libs\Database;
use Illuminate\Database\Capsule\Manager;

class DB extends Manager {

    public function __construct(array $config) {

        // Configure the database connection
        $this->addConnection($config);

        // Set the manager instance as the global instance
        $this->setAsGlobal();

        // Boot Eloquent
        $this->bootEloquent();
    }
}