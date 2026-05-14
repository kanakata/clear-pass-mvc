<?php

namespace App\Models\DatabaseObject;

use PDO;
use PDOException;

class DatabaseObject
{
    private string $dsn;
    private string $password;
    private string $username;
    private  $conn = null;
    public function __construct()
    {
        $this->dsn = $_ENV['DATABASE_DSN'];
        $this->username = $_ENV['DATABASE_USERNAME'];
        $this->password = $_ENV['DATABASE_PASSWORD'];
    }
    protected function databaseConnect()
    {
        try {
            if ($this->conn == null) {
                $this->conn = new PDO($this->dsn, $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            return $this->conn;
        } catch (PDOException $e) {
            $this->errorLoger($e);
        }
    }

    private function errorLoger($e)
    {
        $error = "Error" . $e->getMessage() . " on file: " . $e->getFile() . " on line: " . $e->getLine();
    }
}
