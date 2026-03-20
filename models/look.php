<?php

class Look{
    private $id;
    private $activo;
    private $fecha;
    private $conexionDataBase;
    private $aPrendas = [];

    public function __construct($db) {
        $this->conexionDataBase = $db;
    }

    


    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of activo
     */ 
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * Set the value of activo
     *
     * @return  self
     */ 
    public function setActivo($activo)
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * Get the value of fecha
     */ 
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set the value of fecha
     *
     * @return  self
     */ 
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get the value of aPrendas
     */ 
    public function getAPrendas()
    {
        return $this->aPrendas;
    }

    /**
     * Set the value of aPrendas
     *
     * @return  self
     */ 
    public function setAPrendas($aPrendas)
    {
        $this->aPrendas = $aPrendas;

        return $this;
    }

        public function comprobarLook($idPrenda, $idColor)
    {

    try {
        $sql = "SELECT COUNT(*) as total
                FROM look_prendas lp
                INNER JOIN looks l ON lp.look_id = l.id
                WHERE lp.producto_id = :idPrenda 
                AND lp.color_id = :idColor 
                AND l.activo = 1";

        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia -> execute([
            ":idPrenda" => $idPrenda,
            ":idColor" => $idColor
        ]);

        $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);

        return $resultado['total'] > 0;

    } catch (PDOException) {
        return false;
    }

    }

    public function mostrarLook($idPrenda, $idColor){
  

        try {
      $sql = "SELECT p.*, c.id as color_id, c.nombre as color_nombre, MIN(i.url_imagen) as url_imagen
                FROM look_prendas lp
                INNER JOIN looks l ON lp.look_id = l.id
                INNER JOIN productos p ON lp.producto_id = p.id
                INNER JOIN colores c ON lp.color_id = c.id
                LEFT JOIN imagenes_productos i ON p.id = i.producto_id AND i.color_id = c.id AND i.es_principal = 1
                    WHERE lp.look_id IN (
                    SELECT look_id 
                    FROM look_prendas 
                    WHERE producto_id = :idPrenda AND color_id = :idColor
                    )   
                AND NOT (lp.producto_id = :idPrenda AND lp.color_id = :idColor)
                AND p.activo = 1
                AND l.activo = 1
                GROUP BY p.id, c.id";

        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia -> execute([
            ":idPrenda" => $idPrenda,
            ":idColor" => $idColor
        ]);

        return $sentencia->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return [];
        }
    }

}

?>