<?php

class Database
{

    private $host = 'localhost';
    private $user = 'admin_tfg';
    private $password = 'vkyLDWJhls1l3mz';
    private $dbname = 'db_tfg';
    private $charset = "utf8mb4";


    public function conectar()
    {

        try {
            $dns = "mysql:host=" . $this->host . ";dbname=" . $this->dbname . ";charset=" . $this->charset;
            $pdo = new PDO($dns, $this->user, $this->password);

            $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

            return $pdo;
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }
}
