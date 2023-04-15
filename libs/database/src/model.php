<?php

namespace HexaPHP\Libs\Database;
use HexaPHP\Libs\Database\DB;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel {
    protected $database;

    public function __construct(DB $database) {
        parent::__construct();

        $this->database = $database;

        // Set the database connection for the model
        $this->setConnectionResolver($database->getConnectionResolver());
    }
}
