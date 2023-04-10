<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Model;

class Database
{
    private static $instance;

    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        $capsule = new Capsule;

        $capsule->addConnection([
            'driver' => 'mysql', // Replace with your database driver (e.g., 'pgsql', 'sqlite', etc.)
            'host' => 'localhost', // Replace with your database host
            'database' => 'your_database_name', // Replace with your database name
            'username' => 'your_database_username', // Replace with your database username
            'password' => 'your_database_password', // Replace with your database password
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ]);

        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }
}
