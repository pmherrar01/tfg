<?php

class favorito
{
    private $idUsu;
    private $idProducto;
    private $fechaAgregado;
    private $conexionDataBase;

    public function __construct($db)
    {
        $this->conexionDataBase = $db;
    }





    /**
     * Get the value of idUsu
     */
    public function getIdUsu()
    {
        return $this->idUsu;
    }

    /**
     * Set the value of idUsu
     *
     * @return  self
     */
    public function setIdUsu($idUsu)
    {
        $this->idUsu = $idUsu;

        return $this;
    }

    /**
     * Get the value of idProducto
     */
    public function getIdProducto()
    {
        return $this->idProducto;
    }

    /**
     * Set the value of idProducto
     *
     * @return  self
     */
    public function setIdProducto($idProducto)
    {
        $this->idProducto = $idProducto;

        return $this;
    }

    /**
     * Get the value of fechaAgregado
     */
    public function getFechaAgregado()
    {
        return $this->fechaAgregado;
    }

    /**
     * Set the value of fechaAgregado
     *
     * @return  self
     */
    public function setFechaAgregado($fechaAgregado)
    {
        $this->fechaAgregado = $fechaAgregado;

        return $this;
    }

    public function esFavorito($idUsu, $idPrenda, $colorPrenda)
    {
        try {
            $sql = "SELECT * from favoritos where usuario_id = :idUsu AND producto_id = :idPrenda AND color_id = :colorPrenda";
            $sentencia = $this->conexionDataBase->prepare($sql);
            $sentencia->execute([
                ":idUsu" => $idUsu,
                ":idPrenda" => $idPrenda,
                ":colorPrenda" => $colorPrenda
            ]);

            $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);

            return (bool)$resultado;
        } catch (PDOException) {
            return false;
        }
    }

    public function agregarFavorito($idUsu, $idPrenda, $colorPrenda)
    {

        try {
            $sql = "INSERT INTO favoritos(usuario_id, producto_id, color_id) VALUES(:idUsu, :idPrenda) AND color_id = :colorPrenda";
            $sentencia = $this->conexionDataBase->prepare($sql);
            $sentencia->execute([
                ":idUsu" => $idUsu,
                ":idPrenda" => $idPrenda,
                ":colorPrenda" => $colorPrenda
            ]);

            return true;
        } catch (PDOException) {
            return false;
        }
    }

    public function eliminarFavoritos($idUsu, $idPrenda, $colorPrenda){

            try {
            $sql = "DELETE FROM favoritos WHERE usuario_id = :idUsu AND producto_id = :idPrenda AND color_id = :colorPrenda";
            $sentencia = $this->conexionDataBase->prepare($sql);
            $sentencia->execute([
                ":idUsu" => $idUsu,
                ":idPrenda" => $idPrenda,
                ":colorPrenda" => $colorPrenda
            ]);

            return true;
        } catch (PDOException) {
            return false;
        }

    }

    public function listarFavoritos($idUsu){
        $sql = "SELECT * from favoritos f  where f.usuario_id = :idUsu
        LEFT JOJN "; //falta por terminar

    }

}
