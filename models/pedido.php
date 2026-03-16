<?php

class Pedido{
    private $conexionDataBase;
    private $idPedido;
    private $idUsuario;
    private $total;

    public function __construct($db) {
        $this->conexionDataBase = $db;
    }

    


    /**
     * Get the value of idPedido
     */ 
    public function getIdPedido()
    {
        return $this->idPedido;
    }

    /**
     * Set the value of idPedido
     *
     * @return  self
     */ 
    public function setIdPedido($idPedido)
    {
        $this->idPedido = $idPedido;

        return $this;
    }

    /**
     * Get the value of idUsuario
     */ 
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    /**
     * Set the value of idUsuario
     *
     * @return  self
     */ 
    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;

        return $this;
    }

    /**
     * Get the value of total
     */ 
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set the value of total
     *
     * @return  self
     */ 
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    public function crearPedido($idUsu, $total){

    try {
        $sql = "INSERT INTO pedidos (usuario_id, total, estado, fecha) VALUES (:usuarioId, :total, 'Pendiente', NOW())";

        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia -> execute([
            ":usuarioId" => $idUsu,
            ":total" => $total
            ]);

            return $this->conexionDataBase->lastInsertId();

    } catch (PDOException) {
        return false;
    }

            
    }


    public function crearDetallesPedidos($idPedido, $idPrenda, $idColor, $talla, $cantidad){

    try {
        $sql = "INSERT INTO detalles_pedido (pedido_id, producto_id, color_id, talla, cantidad) VALUES (:idPedido, :idProducto, :idColor, :talla, :cantidad)";
        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia->execute([
            ":idPedido" => $idPedido,
            ":idProducto" => $idPrenda,
            ":idColor" => $idColor,
            ":talla" => $talla,
            ":cantidad" => $cantidad
        ]);

        return true;
    } catch (PDOException) {
        return false;
    }



    }

}

?>