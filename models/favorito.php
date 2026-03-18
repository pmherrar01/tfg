<?php

class favorito{
    private $idUsu;
    private $idProducto;
    private $fechaAgregado;
    private $conexionDataBase;

    public function __construct($db) {
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

    public function esFavorito($idUsu, $idPrenda){
        try {
            $sql = "SELECT * from favoritos where usuario_id = :idUsu AND producto_id = :idPrenda";
            $sentencia = $this->conexionDataBase->prepare($sql);
            $sentencia->execute([
                ":idUsu" => $idUsu,
                ":idPrenda" => $idPrenda
            ]);
            
            $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);

            return(bool)$resultado;

        } catch (PDOException) {
            return false;
        }
    }

}

?>