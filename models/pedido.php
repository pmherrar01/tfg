<?php

class Pedido
{
    private $conexionDataBase;
    private $idPedido;
    private $idUsuario;
    private $total;

    public function __construct($db)
    {
        $this->conexionDataBase = $db;
    }

    public function getIdPedido()
    {
        return $this->idPedido;
    }
    public function setIdPedido($idPedido)
    {
        $this->idPedido = $idPedido;
        return $this;
    }
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }
    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;
        return $this;
    }
    public function getTotal()
    {
        return $this->total;
    }
    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }

    public function crearPedido($idUsu, $total, $direccionPedido)
    {
        // ADAPTADO A TU BD: Solo insertamos las columnas que realmente existen en tu tabla 'pedidos'
        $sql = "INSERT INTO pedidos (usuario_id, total, estado, direccion_envio) 
                VALUES (:usuarioId, :total, 'pendiente', :direccion)";

        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia->execute([
            ":usuarioId" => $idUsu,
            ":total" => $total,
            ":direccion" => $direccionPedido
        ]);

        return $this->conexionDataBase->lastInsertId();
    }

    public function crearDetallesPedidos($idPedido, $idPrenda, $idColor, $talla, $cantidad)
    {
        // 1. Sacamos el precio unitario del producto actual
        $sqlPrecio = "SELECT precio FROM productos WHERE id = :idProducto";
        $stmtPrecio = $this->conexionDataBase->prepare($sqlPrecio);
        $stmtPrecio->execute([":idProducto" => $idPrenda]);
        $precioUnitario = $stmtPrecio->fetchColumn();

        // 2. Insertamos la línea en la BD (AHORA SÍ incluye color_id y talla)
        $sql = "INSERT INTO lineas_pedido (pedido_id, producto_id, color_id, talla, cantidad, precio_unitario) 
                VALUES (:idPedido, :idProducto, :idColor, :talla, :cantidad, :precio)";

        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia->execute([
            ":idPedido"   => $idPedido,
            ":idProducto" => $idPrenda,
            ":idColor"    => $idColor,
            ":talla"      => $talla,
            ":cantidad"   => $cantidad,
            ":precio"     => $precioUnitario
        ]);

        return true;
    }

    public function listarPedidos($idUsu)
    {
        $sql = "SELECT * from pedidos where usuario_id = :idUdu order by fecha DESC";
        $sentencia = $this->conexionDataBase->preprare($sql);
        $sentencia->execute([":idUsu" => $idUsu]);

        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerInfoPedido($idPedido)
    {
        $sql = "SELECT lp.*, p.nombre AS producto_nombre, c.nombre AS color_nombre 
                FROM lineas_pedido lp
                INNER JOIN productos p ON lp.producto_id = p.id
                LEFT JOIN colores c ON lp.color_id = c.id
                WHERE lp.pedido_id = :idPedido";
        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia->execute([":idPedido" => $idPedido]);

        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }
}
