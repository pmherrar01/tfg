<?php

class Producto
{
    private $conexionDataBase;
    private $idColecion;
    private $idTipoProducto;
    private $nombreProducto;
    private $descripcionProducto;
    private $precioProducto;
    private $stock;
    private $colorProducto;
    private $destacado;
    private $fechaProducto;
    private $tallaProducto;

    public function __construct($db)
    {
        $this->conexionDataBase = $db;
    }

    // ... (Tus Getters y Setters se mantienen igual) ...
    public function getIdColecion() { return $this->idColecion; }
    public function setIdColecion($idColecion) { $this->idColecion = $idColecion; return $this; }
    public function getIdTipoProducto() { return $this->idTipoProducto; }
    public function setIdTipoProducto($idTipoProducto) { $this->idTipoProducto = $idTipoProducto; return $this; }
    public function getNombreProducto() { return $this->nombreProducto; }
    public function setNombreProducto($nombreProducto) { $this->nombreProducto = $nombreProducto; return $this; }
    public function getDescripcionProducto() { return $this->descripcionProducto; }
    public function setDescripcionProducto($descripcionProducto) { $this->descripcionProducto = $descripcionProducto; return $this; }
    public function getPrecioProducto() { return $this->precioProducto; }
    public function setPrecioProducto($precioProducto) { $this->precioProducto = $precioProducto; return $this; }
    public function getStock() { return $this->stock; }
    public function setStock($stock) { $this->stock = $stock; return $this; }
    public function getColorProducto() { return $this->colorProducto; }
    public function setColorProducto($colorProducto) { $this->colorProducto = $colorProducto; return $this; }
    public function getDestacado() { return $this->destacado; }
    public function setDestacado($destacado) { $this->destacado = $destacado; return $this; }
    public function getFechaProducto() { return $this->fechaProducto; }
    public function setFechaProducto($fechaProducto) { $this->fechaProducto = $fechaProducto; return $this; }
    public function getTallaProducto() { return $this->tallaProducto; }
    public function setTallaProducto($tallaProducto) { $this->tallaProducto = $tallaProducto; return $this; }

    public function obtenerTallas($idPrenda)
    {
        $sql = "SELECT color_id, talla, stock FROM producto_tallas WHERE producto_id = :idPrenda";
        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia->execute([":idPrenda" => $idPrenda]);
        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    // CORREGIDO: Añadidos los INNER JOIN para poder agrupar por c.id
    public function listarProductos($limite = null)
    {
        $sql = "SELECT p.*, c.id as color_id, c.nombre as color_nombre, MIN(i.url_imagen) as url_imagen
                FROM productos p
                INNER JOIN producto_colores pc ON p.id = pc.producto_id
                INNER JOIN colores c ON pc.color_id = c.id
                LEFT JOIN imagenes_productos i ON p.id = i.producto_id AND i.color_id = c.id AND i.es_principal = 1
                WHERE p.activo = 1
                GROUP BY p.id, c.id
                ORDER BY p.creado_en DESC";

        if ($limite != null) {
            $sql .= " LIMIT " . $limite;
        }

        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia->execute();
        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarTiposPrendas()
    {
        $sql = "SELECT id, nombre from tipos_producto";
        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia->execute();
        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerNombreColeccion($idColeccion)
    {
        $sql = "SELECT nombre FROM colecciones WHERE id = :idColeccion";
        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia->execute([":idColeccion" => $idColeccion]);
        return $sentencia->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerTipoPrenda($idTipoPrenda)
    {
        $sql = "SELECT nombre FROM tipos_producto WHERE id = :idTipoPrenda";
        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia->execute([":idTipoPrenda" => $idTipoPrenda]);
        return $sentencia->fetch(PDO::FETCH_ASSOC);
    }

    public function listarColecciones()
    {
        $sql = "SELECT id, nombre from colecciones where activa = 1";
        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia->execute();
        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerProducto($id)
    {
        $sql = "SELECT * FROM productos WHERE id = :idPrenda";
        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia->execute([":idPrenda" => $id]);
        return $sentencia->fetch(PDO::FETCH_ASSOC);
    }

    private function obtenerSqlOrden($accion)
    {
        $ordenSql = " ORDER BY p.creado_en DESC";
        switch ($accion) {
            case 'precioAsc': $ordenSql = " ORDER BY p.precio ASC"; break;
            case 'precioDesc': $ordenSql = " ORDER BY p.precio DESC"; break;
            case 'nombreAsc': $ordenSql = " ORDER BY p.nombre ASC"; break;
            case 'nombreDesc': $ordenSql = " ORDER BY p.nombre DESC"; break;
            case 'fechaDesc': $ordenSql = " ORDER BY p.creado_en DESC"; break;
            case 'fechaAsc': $ordenSql = " ORDER BY p.creado_en ASC"; break;
        }
        return $ordenSql;
    }

    // CORREGIDOS TODOS LOS FILTROS PARA UNIR LAS TABLAS DE COLORES
    private function filtrarGenero($genero, $orden)
    {
        switch ($genero) {
            case '1': $generoAFiltrar = 1; break;
            case '2': $generoAFiltrar = 2; break;
            default: $generoAFiltrar = 3; break;
        }
        $sql = "SELECT p.*, c.id as color_id, c.nombre as color_nombre, MIN(i.url_imagen) as url_imagen 
                FROM productos p 
                INNER JOIN producto_colores pc ON p.id = pc.producto_id
                INNER JOIN colores c ON pc.color_id = c.id
                LEFT JOIN imagenes_productos i ON p.id = i.producto_id AND i.color_id = c.id AND i.es_principal = 1 
                WHERE p.genero = :filtro AND p.activo = 1 
                GROUP BY p.id, c.id" . $this->obtenerSqlOrden($orden);
        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia->execute([":filtro" => $generoAFiltrar]);
        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    private function filtrarColeccion($idColeccion, $orden)
    {
        $sql = "SELECT p.*, c.id as color_id, c.nombre as color_nombre, MIN(i.url_imagen) as url_imagen 
                FROM productos p 
                INNER JOIN producto_colores pc ON p.id = pc.producto_id
                INNER JOIN colores c ON pc.color_id = c.id
                LEFT JOIN imagenes_productos i ON p.id = i.producto_id AND i.color_id = c.id AND i.es_principal = 1 
                WHERE p.coleccion_id = :filtro AND p.activo = 1 
                GROUP BY p.id, c.id" . $this->obtenerSqlOrden($orden);
        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia->execute([":filtro" => $idColeccion]);
        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    private function filtrarTipoPrenda($tipo, $orden)
    {
        $sql = "SELECT p.*, c.id as color_id, c.nombre as color_nombre, MIN(i.url_imagen) as url_imagen 
                FROM productos p 
                INNER JOIN producto_colores pc ON p.id = pc.producto_id
                INNER JOIN colores c ON pc.color_id = c.id
                LEFT JOIN imagenes_productos i ON p.id = i.producto_id AND i.color_id = c.id AND i.es_principal = 1 
                WHERE p.tipo_id = :filtro AND p.activo = 1 
                GROUP BY p.id, c.id" . $this->obtenerSqlOrden($orden);
        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia->execute([":filtro" => $tipo]);
        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    private function filtrarPorTalla($talla, $orden)
    {
        // En este unimos la tabla de TALLAS para saber qué colores tienen stock en esa talla concreta
        $sql = "SELECT p.*, c.id as color_id, c.nombre as color_nombre, MIN(i.url_imagen) as url_imagen 
                FROM productos p 
                INNER JOIN producto_tallas pt ON p.id = pt.producto_id 
                INNER JOIN colores c ON pt.color_id = c.id
                LEFT JOIN imagenes_productos i ON p.id = i.producto_id AND i.color_id = c.id AND i.es_principal = 1 
                WHERE pt.talla = :talla AND pt.stock > 0 AND p.activo = 1 
                GROUP BY p.id, c.id" . $this->obtenerSqlOrden($orden);
        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia->execute([":talla" => $talla]);
        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    private function filtrarColor($color, $orden)
    {
        $sql = "SELECT p.*, c.id as color_id, c.nombre as color_nombre, MIN(i.url_imagen) as url_imagen 
                FROM productos p 
                INNER JOIN producto_colores pc ON p.id = pc.producto_id 
                INNER JOIN colores c ON pc.color_id = c.id 
                LEFT JOIN imagenes_productos i ON p.id = i.producto_id AND i.color_id = c.id AND i.es_principal = 1 
                WHERE c.nombre = :color AND p.activo = 1 
                GROUP BY p.id, c.id" . $this->obtenerSqlOrden($orden);
        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia->execute([":color" => $color]);
        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    private function filtrarPorPrecio($max, $min, $orden)
    {
        $sql = "SELECT p.*, c.id as color_id, c.nombre as color_nombre, MIN(i.url_imagen) as url_imagen 
                FROM productos p 
                INNER JOIN producto_colores pc ON p.id = pc.producto_id
                INNER JOIN colores c ON pc.color_id = c.id
                LEFT JOIN imagenes_productos i ON p.id = i.producto_id AND i.color_id = c.id AND i.es_principal = 1 
                WHERE p.precio BETWEEN :min AND :max AND p.activo = 1 
                GROUP BY p.id, c.id" . $this->obtenerSqlOrden($orden);
        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia->execute([":min" => $min, ":max" => $max]);
        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    public function filtrar($filtrado, $valor, $valor2 = null, $orden = null)
    {
        switch ($filtrado) {
            case 'genero': return $this->filtrarGenero($valor, $orden);
            case 'coleccion': return $this->filtrarColeccion($valor, $orden);
            case 'tipoPrenda': return $this->filtrarTipoPrenda($valor, $orden);
            case 'color': return $this->filtrarColor($valor, $orden);
            case 'talla': return $this->filtrarPorTalla($valor, $orden);
            case 'precio': return $this->filtrarPorPrecio($valor, $valor2, $orden);
            default: return $this->listarProductos();
        }
    }

    public function listaColores()
    {
        $sql = "SELECT nombre, valor_hexadecimal from colores";
        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia->execute();
        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerColoresPorProducto($idProducto)
    {
        try {
            $sql = "SELECT c.id, c.nombre, c.valor_hexadecimal 
                    FROM colores c
                    INNER JOIN producto_colores pc ON c.id = pc.color_id
                    WHERE pc.producto_id = :idProducto";
            $sentencia = $this->conexionDataBase->prepare($sql);
            $sentencia->execute([":idProducto" => $idProducto]);
            return $sentencia->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function obtenerPrecioMinMax($valor)
    {
        $funcionSql = "MAX";
        if (strtoupper($valor) === "MIN") {
            $funcionSql = "MIN";
        }
        try {
            $sql = "SELECT {$funcionSql}(precio) as precio_limite FROM productos WHERE activo = 1";
            $sentencia = $this->conexionDataBase->prepare($sql);
            $sentencia->execute();
            $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);
            $precio = isset($resultado['precio_limite']) ? $resultado['precio_limite'] : 0;
            return round((float)$precio, 2);
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function ordenar($accion)
    {
        $ordenSql = $this->obtenerSqlOrden($accion);
        $sql = "SELECT p.*, c.id as color_id, c.nombre as color_nombre, MIN(i.url_imagen) as url_imagen 
                FROM productos p 
                INNER JOIN producto_colores pc ON p.id = pc.producto_id
                INNER JOIN colores c ON pc.color_id = c.id
                LEFT JOIN imagenes_productos i ON p.id = i.producto_id AND i.color_id = c.id AND i.es_principal = 1 
                WHERE p.activo = 1 
                GROUP BY p.id, c.id" . $ordenSql;
        $sentencia  = $this->conexionDataBase->prepare($sql);
        $sentencia->execute();
        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizarStock($idPrenda, $idColor, $talla, $cantidad){

    try {
        $sql = "UPDATE producto_tallas SET stock = stock - :cantidad WHERE producto_id = :idPrenda AND color_id = :idColor AND talla = :talla";
        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia->execute([
            ":cantidad" => $cantidad,
            ":idPrenda" => $idPrenda,
            ":idColor" => $idColor,
            ":talla" => $talla
        ]); 
        return true;
    } catch (PDOException) {
        return false;
    }

    }


    public function buscarPorNombre($nombreABuscar){
        $sql = "SELECT p.*, MIN(i.url_imagen) AS url_imagen
        from productos p
        LEFT JOIN imagenes_productos i ON p.id = i.producto_id AND i.es_principal = 1
        WHERE p.activo = 1 AND p.nombre LIKE :nombreABuscar
        GROUP BY p.id
        LIMIT 5";

        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia->execute([":nombreABuscar" => "%" . $nombreABuscar . "%"]);

        return $sentencia->fetchAll(PDO::FETCH_ASSOC);

    }

}
?>