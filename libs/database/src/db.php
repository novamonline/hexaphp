<?php
class Database extends PDO
{
    public function __construct($dsn, $username = null, $password = null, $options = [])
    {
        parent::__construct($dsn, $username, $password, $options);
    }
}
