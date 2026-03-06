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



    /**
     * Get the value of idColecion
     */
    public function getIdColecion()
    {
        return $this->idColecion;
    }

    /**
     * Set the value of idColecion
     *
     * @return  self
     */
    public function setIdColecion($idColecion)
    {
        $this->idColecion = $idColecion;

        return $this;
    }

    /**
     * Get the value of idTipoProducto
     */
    public function getIdTipoProducto()
    {
        return $this->idTipoProducto;
    }

    /**
     * Set the value of idTipoProducto
     *
     * @return  self
     */
    public function setIdTipoProducto($idTipoProducto)
    {
        $this->idTipoProducto = $idTipoProducto;

        return $this;
    }

    /**
     * Get the value of nombreProducto
     */
    public function getNombreProducto()
    {
        return $this->nombreProducto;
    }

    /**
     * Set the value of nombreProducto
     *
     * @return  self
     */
    public function setNombreProducto($nombreProducto)
    {
        $this->nombreProducto = $nombreProducto;

        return $this;
    }

    /**
     * Get the value of descripcionProducto
     */
    public function getDescripcionProducto()
    {
        return $this->descripcionProducto;
    }

    /**
     * Set the value of descripcionProducto
     *
     * @return  self
     */
    public function setDescripcionProducto($descripcionProducto)
    {
        $this->descripcionProducto = $descripcionProducto;

        return $this;
    }

    /**
     * Get the value of precioProducto
     */
    public function getPrecioProducto()
    {
        return $this->precioProducto;
    }

    /**
     * Set the value of precioProducto
     *
     * @return  self
     */
    public function setPrecioProducto($precioProducto)
    {
        $this->precioProducto = $precioProducto;

        return $this;
    }

    /**
     * Get the value of stock
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Set the value of stock
     *
     * @return  self
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get the value of colorProducto
     */
    public function getColorProducto()
    {
        return $this->colorProducto;
    }

    /**
     * Set the value of colorProducto
     *
     * @return  self
     */
    public function setColorProducto($colorProducto)
    {
        $this->colorProducto = $colorProducto;

        return $this;
    }

    /**
     * Get the value of destacado
     */
    public function getDestacado()
    {
        return $this->destacado;
    }

    /**
     * Set the value of destacado
     *
     * @return  self
     */
    public function setDestacado($destacado)
    {
        $this->destacado = $destacado;

        return $this;
    }

    /**
     * Get the value of fechaProducto
     */
    public function getFechaProducto()
    {
        return $this->fechaProducto;
    }

    /**
     * Set the value of fechaProducto
     *
     * @return  self
     */
    public function setFechaProducto($fechaProducto)
    {
        $this->fechaProducto = $fechaProducto;

        return $this;
    }


    /**
     * Get the value of tallaProducto
     */
    public function getTallaProducto()
    {
        return $this->tallaProducto;
    }

    /**
     * Set the value of tallaProducto
     *
     * @return  self
     */
    public function setTallaProducto($tallaProducto)
    {
        $this->tallaProducto = $tallaProducto;

        return $this;
    }

    public function obtenerTallas($idPrenda)
    {
        $sql = "SELECT talla, stock FROM producto_tallas WHERE producto_id = :idPrenda";

        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia->execute([":idPrenda" => $idPrenda]);

        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }


    public function listarProductos($limite = null)
    {
        // Hemos quitado p.color y p.talla porque ya no existen en esta tabla
        $sql = "SELECT p.id, p.coleccion_id, p.tipo_id, p.nombre, p.descripcion, 
                       p.precio, p.stock, p.destacado, p.creado_en, i.url_imagen
                FROM productos p
                LEFT JOIN imagenes_productos i ON p.id = i.producto_id AND i.es_principal = 1
                WHERE p.activo = 1
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
        $sql = "SELECT * FROM productos
                WHERE id = :idPrenda";


        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia->execute([":idPrenda" => $id]);
        return $sentencia->fetch(PDO::FETCH_ASSOC);
    }

    private function filtrarGenero($genero)
    {

        switch ($genero) {

            case '1':
                $generoAFiltrar = 1;
                break;

            case '2':
                $generoAFiltrar = 2;
                break;

            default:
                $generoAFiltrar = 3;
                break;
        }

        $sql = "SELECT * from productos p LEFT JOIN imagenes_productos i ON p.id = i.producto_id AND i.es_principal = 1 where genero = :generoAFiltrar";
        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia->execute([":generoAFiltrar" => $generoAFiltrar]);

        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    private function filtrarColeccion($idColeccion)
    {
        $sql = "SELECT * from productos p LEFT JOIN imagenes_productos i ON p.id = i.producto_id AND i.es_principal = 1 where coleccion_id = :idColeccion";
        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia->execute([":idColeccion" => $idColeccion]);

        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    private function filtrarTipoPrenda($prendaAFiltrar)
    {
        $sql = "SELECT * from productos p LEFT JOIN imagenes_productos i ON p.id = i.producto_id AND i.es_principal = 1 where tipo_id = :prendaAFiltrar";
        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia->execute([":prendaAFiltrar" => $prendaAFiltrar]);

        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    private function filtrarPorTalla($talla)
    {
        // Adaptado a tus tablas reales: imagenes_productos y producto_id
        $sql = "SELECT p.*, i.url_imagen 
                FROM productos p
                LEFT JOIN imagenes_productos i ON p.id = i.producto_id AND i.es_principal = 1
                INNER JOIN producto_tallas pt ON p.id = pt.producto_id
                WHERE pt.talla = :talla AND pt.stock > 0";

        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia->execute([":talla" => $talla]);

        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    private function filtrarColor($color)
    {
        // 1. Añadimos i.url_imagen al SELECT
        $sql = "SELECT p.*, MIN(i.url_imagen) as url_imagen 
                FROM productos p
                INNER JOIN producto_colores pc ON p.id = pc.producto_id
                INNER JOIN colores c ON pc.color_id = c.id
                -- 2. Le decimos que intente coger la foto que coincida con ese color exacto
                LEFT JOIN imagenes_productos i ON p.id = i.producto_id AND i.es_principal = 1 AND i.color_id = c.id
                WHERE c.nombre = :color 
                -- 3. Agrupamos por el ID del producto para evitar que se repita
                GROUP BY p.id";

        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia->execute([":color" => $color]);

        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    private function filtrarPorPrecio($max, $min)
    {
        $sql = "SELECT * from productos p LEFT JOIN imagenes_productos i ON p.id = i.producto_id AND i.es_principal = 1  where precio BETWEEN :min AND :max GROUP BY p.id";
        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia->execute([":min" => $min, ":max" => $max]);

        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    public function filtrar($filtrado, $valor, $valor2 = null)
    {
        switch ($filtrado) {
            case 'genero':
                return $this->filtrarGenero($valor);
                break;
            case 'coleccion':
                return $this->filtrarColeccion($valor);
                break;
            case 'tipoPrenda':
                return $this->filtrarTipoPrenda($valor);
                break;
            case 'color':
                return $this->filtrarColor($valor);
                break;
            case 'talla':
                return $this->filtrarPorTalla($valor);
                break;
            case 'precio':
                return $this->filtrarPorPrecio($valor, $valor2);
                break;

            default:
                # code...
                break;
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

        // Lo pasamos a mayúsculas por si acaso alguien escribe "min" o "Min"
        if (strtoupper($valor) === "MIN") {
            $funcionSql = "MIN";
        }

        try {
            $sql = "SELECT {$funcionSql}(precio) as precio_limite FROM productos WHERE activo = 1";

            $sentencia = $this->conexionDataBase->prepare($sql);
            $sentencia->execute();

            $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);

            // SALVACAÍDAS: Si el resultado es nulo, le asignamos 0. Y forzamos que sea un número (float).
            $precio = isset($resultado['precio_limite']) ? $resultado['precio_limite'] : 0;

            return round((float)$precio, 2);
        } catch (PDOException $e) {
            // Si la base de datos falla, devolvemos 0 para que no se rompa la web
            return 0;
        }
    }

    private function ordenarPrecioAsc()
    {
        $sql = "SELECT * from productos WHERE activo = 1 order by precio ASC";
        $sentencia  = $this->conexionDataBase->prepare($sql);
        $sentencia->execute();

        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }


    private function ordenarPrecioDesc()
    {
        $sql = "SELECT * from productos WHERE activo = 1 order by precio DESC";
        $sentencia  = $this->conexionDataBase->prepare($sql);
        $sentencia->execute();

        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    private function ordenarFechaDesc()
    {
        $sql = "SELECT * from productos WHERE activo = 1 order by creado_en DESC";
        $sentencia  = $this->conexionDataBase->prepare($sql);
        $sentencia->execute();

        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    private function ordenarFechaAsc()
    {
        $sql = "SELECT * from productos WHERE activo = 1 order by creado_en ASC";
        $sentencia  = $this->conexionDataBase->prepare($sql);
        $sentencia->execute();

        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    private function ordenarNombreAsc()
    {
        $sql = "SELECT * from productos WHERE activo = 1 order by nombre ASC";
        $sentencia  = $this->conexionDataBase->prepare($sql);
        $sentencia->execute();

        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    private function ordenarNombreDesc()
    {
        $sql = "SELECT * from productos WHERE activo = 1 order by nombre DESC";
        $sentencia  = $this->conexionDataBase->prepare($sql);
        $sentencia->execute();

        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ordenar($accion){
        switch ($accion) {
            case 'nombreAsc':
                return $this->ordenarNombreAsc();
                break;
                case 'nombreDesc':
                    return $this->ordenarNombreDesc();
                break;
                case 'precioAsc':
                return $this->ordenarPrecioAsc();
                break;
                case 'precioDesc':
                return $this->ordenarPrecioDesc();
                break;
                case 'fechaAsc':
                return $this->ordenarFechaAsc();
                break;
                case 'fechaDesc':
                return $this->ordenarPrecioDesc();
                break;
            
            default:
                # code...
                break;
        }
    }

}
