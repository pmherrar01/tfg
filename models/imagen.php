<?php

class Imagen{

    private $idImagen;
    private $idProducto;
    private $urlImagen;
    private $esPrincipal;
    private $conexionDataBase;

    public function __construct($db) {
        $this->conexionDataBase = $db;
    }
    

    /**
     * Get the value of idImagen
     */ 
    public function getIdImagen()
    {
        return $this->idImagen;
    }

    /**
     * Set the value of idImagen
     *
     * @return  self
     */ 
    public function setIdImagen($idImagen)
    {
        $this->idImagen = $idImagen;

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
     * Get the value of urlImagen
     */ 
    public function getUrlImagen()
    {
        return $this->urlImagen;
    }

    /**
     * Set the value of urlImagen
     *
     * @return  self
     */ 
    public function setUrlImagen($urlImagen)
    {
        $this->urlImagen = $urlImagen;

        return $this;
    }

    /**
     * Get the value of esPrincipal
     */ 
    public function getEsPrincipal()
    {
        return $this->esPrincipal;
    }

    /**
     * Set the value of esPrincipal
     *
     * @return  self
     */ 
    public function setEsPrincipal($esPrincipal)
    {
        $this->esPrincipal = $esPrincipal;

        return $this;
    }


    public function listarImagenes($idPrenda)
    {
        $sql = "Select url_imagen from imagenes_productos where producto_id = :idProducto ORDER BY es_principal DESC";

        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia->execute([":idProducto" => $idPrenda]);

        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>