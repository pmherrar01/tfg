<?php
class Cita {
    private $conexionDataBase;

    public function __construct($db) {
        $this->conexionDataBase = $db;
    }

    public function obtenerCitasUsuario($idUsu) {
        $sql = "SELECT * FROM citas WHERE usuario_id = :idUsu ORDER BY fecha_cita DESC";
        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia->execute(["idUsu" => $idUsu]);
        
        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    
}
?>