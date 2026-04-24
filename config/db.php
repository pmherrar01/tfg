<?php

class Database
{
    private $charset = "utf8mb4";

    public function conectar()
    {
        $config = parse_ini_file(__DIR__ . '/config.ini');

        try {
            $dns = "mysql:host=" . $config['db_host'] . ";dbname=" . $config['db_name'] . ";charset=" . $this->charset;
            
            $pdo = new PDO($dns, $config['db_user'], $config['db_pass']);

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $pdo;

        } catch (PDOException $e) {
            error_log("Error de conexión a la BD: " . $e->getMessage());
            die("Error interno: No se pudo conectar a la base de datos.");
        }
    }
}
?>