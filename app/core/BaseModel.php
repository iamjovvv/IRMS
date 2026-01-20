<?php

require_once BASE_PATH . '/app/config/database.php';

abstract class BaseModel
{
    protected PDO $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }
}

?>