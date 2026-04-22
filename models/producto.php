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
    private $esSegundaMano;
    private $idUsuarioVendedor;
    private $estadoRevision;

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



    /**
     * Get the value of esSegundaMano
     */
    public function getEsSegundaMano()
    {
        return $this->esSegundaMano;
    }

    /**
     * Set the value of esSegundaMano
     *
     * @return  self
     */
    public function setEsSegundaMano($esSegundaMano)
    {
        $this->esSegundaMano = $esSegundaMano;

        return $this;
    }

    /**
     * Get the value of estadoRevision
     */
    public function getEstadoRevision()
    {
        return $this->estadoRevision;
    }

    /**
     * Set the value of estadoRevision
     *
     * @return  self
     */
    public function setEstadoRevision($estadoRevision)
    {
        $this->estadoRevision = $estadoRevision;

        return $this;
    }

    /**
     * Get the value of idUsuarioVendedor
     */
    public function getIdUsuarioVendedor()
    {
        return $this->idUsuarioVendedor;
    }

    /**
     * Set the value of idUsuarioVendedor
     *
     * @return  self
     */
    public function setIdUsuarioVendedor($idUsuarioVendedor)
    {
        $this->idUsuarioVendedor = $idUsuarioVendedor;

        return $this;
    }

    public function obtenerTallas($idPrenda)
    {
        $sql = "SELECT color_id, talla, stock FROM producto_tallas WHERE producto_id = :idPrenda";
        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia->execute([":idPrenda" => $idPrenda]);
        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarProductos($estadoActivo, $limite = null)
    {
        $sql = "SELECT p.*, c.id as color_id, c.nombre as color_nombre, MIN(i.url_imagen) as url_imagen
                FROM productos p
                INNER JOIN producto_colores pc ON p.id = pc.producto_id
                INNER JOIN colores c ON pc.color_id = c.id
                INNER JOIN colecciones col ON p.coleccion_id = col.id 
                LEFT JOIN imagenes_productos i ON p.id = i.producto_id AND i.color_id = c.id AND i.es_principal = 1
                WHERE p.activo = :estadoActivo AND col.activa = 1
                GROUP BY p.id, c.id
                ORDER BY p.creado_en DESC";

        if ($limite != null) {
            if (filter_var($limite, FILTER_VALIDATE_INT)) {
                $sql .= " LIMIT " . $limite;
            } else {
                $sql .= " LIMIT 20"; 
            }
        }

        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia->execute([":estadoActivo" => $estadoActivo]);
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
            case 'precioAsc':
                $ordenSql = " ORDER BY p.precio ASC";
                break;
            case 'precioDesc':
                $ordenSql = " ORDER BY p.precio DESC";
                break;
            case 'nombreAsc':
                $ordenSql = " ORDER BY p.nombre ASC";
                break;
            case 'nombreDesc':
                $ordenSql = " ORDER BY p.nombre DESC";
                break;
            case 'fechaDesc':
                $ordenSql = " ORDER BY p.creado_en DESC";
                break;
            case 'fechaAsc':
                $ordenSql = " ORDER BY p.creado_en ASC";
                break;
        }
        return $ordenSql;
    }

    private function filtrarGenero($genero, $orden)
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
        $sql = "SELECT p.*, c.id as color_id, c.nombre as color_nombre, MIN(i.url_imagen) as url_imagen 
                FROM productos p 
                INNER JOIN producto_colores pc ON p.id = pc.producto_id
                INNER JOIN colores c ON pc.color_id = c.id
                INNER JOIN colecciones col ON p.coleccion_id = col.id 
                LEFT JOIN imagenes_productos i ON p.id = i.producto_id AND i.color_id = c.id AND i.es_principal = 1 
                WHERE p.genero = :filtro AND p.activo = 1 AND col.activa = 1 
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
                INNER JOIN colecciones col ON p.coleccion_id = col.id 
                LEFT JOIN imagenes_productos i ON p.id = i.producto_id AND i.color_id = c.id AND i.es_principal = 1 
                WHERE p.coleccion_id = :filtro AND p.activo = 1 AND col.activa = 1 
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
                INNER JOIN colecciones col ON p.coleccion_id = col.id 
                LEFT JOIN imagenes_productos i ON p.id = i.producto_id AND i.color_id = c.id AND i.es_principal = 1 
                WHERE p.tipo_id = :filtro AND p.activo = 1 AND col.activa = 1 
                GROUP BY p.id, c.id" . $this->obtenerSqlOrden($orden);
        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia->execute([":filtro" => $tipo]);
        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    private function filtrarPorTalla($talla, $orden)
    {
        $sql = "SELECT p.*, c.id as color_id, c.nombre as color_nombre, MIN(i.url_imagen) as url_imagen 
                FROM productos p 
                INNER JOIN producto_tallas pt ON p.id = pt.producto_id 
                INNER JOIN colores c ON pt.color_id = c.id
                INNER JOIN colecciones col ON p.coleccion_id = col.id 
                LEFT JOIN imagenes_productos i ON p.id = i.producto_id AND i.color_id = c.id AND i.es_principal = 1 
                WHERE pt.talla = :talla AND pt.stock > 0 AND p.activo = 1 AND col.activa = 1 
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
                INNER JOIN colecciones col ON p.coleccion_id = col.id 
                LEFT JOIN imagenes_productos i ON p.id = i.producto_id AND i.color_id = c.id AND i.es_principal = 1 
                WHERE c.nombre = :color AND p.activo = 1 AND col.activa = 1 
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
                INNER JOIN colecciones col ON p.coleccion_id = col.id 
                LEFT JOIN imagenes_productos i ON p.id = i.producto_id AND i.color_id = c.id AND i.es_principal = 1 
                WHERE p.precio BETWEEN :min AND :max AND p.activo = 1 AND col.activa = 1 
                GROUP BY p.id, c.id" . $this->obtenerSqlOrden($orden);
        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia->execute([":min" => $min, ":max" => $max]);
        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    public function filtrar($filtrado, $valor, $valor2 = null, $orden = null)
    {
        switch ($filtrado) {
            case 'genero':
                return $this->filtrarGenero($valor, $orden);
            case 'coleccion':
                return $this->filtrarColeccion($valor, $orden);
            case 'tipoPrenda':
                return $this->filtrarTipoPrenda($valor, $orden);
            case 'color':
                return $this->filtrarColor($valor, $orden);
            case 'talla':
                return $this->filtrarPorTalla($valor, $orden);
            case 'precio':
                return $this->filtrarPorPrecio($valor, $valor2, $orden);
            case 'rebajas':
                return $this->filtrarRebajas($orden);
            default:
                return $this->listarProductos(1);
        }
    }

    public function listaColores()
    {
        $sql = "SELECT id, nombre, valor_hexadecimal from colores";
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
            // Añadimos el INNER JOIN aquí también
            $sql = "SELECT {$funcionSql}(p.precio) as precio_limite 
                    FROM productos p 
                    INNER JOIN colecciones col ON p.coleccion_id = col.id 
                    WHERE p.activo = 1 AND col.activa = 1";
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
                INNER JOIN colecciones col ON p.coleccion_id = col.id 
                LEFT JOIN imagenes_productos i ON p.id = i.producto_id AND i.color_id = c.id AND i.es_principal = 1 
                WHERE p.activo = 1 AND col.activa = 1 
                GROUP BY p.id, c.id" . $ordenSql;
        $sentencia  = $this->conexionDataBase->prepare($sql);
        $sentencia->execute();
        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizarStock($idPrenda, $idColor, $talla, $cantidad)
    {

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
        } catch (PDOException $e) {
            $errores = [$e];
            return false;
        }
    }


    public function buscarPorNombre($nombreABuscar)
    {
        $sql = "SELECT p.id, p.nombre, p.precio, c.id AS color_id, c.nombre AS color_nombre, MIN(i.url_imagen) AS url_imagen
                FROM productos p
                INNER JOIN producto_colores pc ON p.id = pc.producto_id
                INNER JOIN colores c ON pc.color_id = c.id
                INNER JOIN colecciones col ON p.coleccion_id = col.id 
                LEFT JOIN imagenes_productos i ON p.id = i.producto_id AND i.color_id = c.id AND i.es_principal = 1
                WHERE p.activo = 1 AND col.activa = 1 AND p.nombre LIKE :nombreABuscar
                GROUP BY p.id, c.id
                LIMIT 6";

        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia->execute([":nombreABuscar" => "%" . $nombreABuscar . "%"]);

        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

public function buscarPorNombreChatBot($nombreABuscar)
    {
        $sql = "SELECT p.id, p.nombre, p.descripcion, p.precio, c.nombre AS color_nombre, 
                       MIN(i.url_imagen) AS url_imagen,
                       GROUP_CONCAT(CONCAT(pt.talla, ': ', pt.stock, ' unidades') SEPARATOR ' | ') AS tallas_stock
                FROM productos p
                INNER JOIN producto_colores pc ON p.id = pc.producto_id
                INNER JOIN colores c ON pc.color_id = c.id
                INNER JOIN colecciones col ON p.coleccion_id = col.id 
                INNER JOIN producto_tallas pt ON p.id = pt.producto_id AND c.id = pt.color_id
                LEFT JOIN imagenes_productos i ON p.id = i.producto_id AND i.color_id = c.id AND i.es_principal = 1
                WHERE p.activo = 1 AND col.activa = 1 AND p.nombre LIKE :nombreABuscar AND pt.stock > 0
                GROUP BY p.id, c.id
                LIMIT 6";

        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia->execute([":nombreABuscar" => "%" . $nombreABuscar . "%"]);

        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    public function subirPrendasSegundaMano($nombrePrenda, $precioPrenda, $idUsu, $imgPrenda, $idColor, $tallaPrenda, $idTipoPrenda)
    {
        try {
            $this->conexionDataBase->beginTransaction();

            $sql = "INSERT INTO productos (nombre, precio, tipo_id, activo, es_segunda_mano, id_usuario_vendedor, estado_revision) 
            VALUES (:nombre, :precio, :idTipoPrenda, 0, 1, :idUsuario, 'Pendiente')";
            $sentencia = $this->conexionDataBase->prepare($sql);
            $sentencia->execute([
                ":nombre" => $nombrePrenda,
                ":precio" => $precioPrenda,
                ":idTipoPrenda" => $idTipoPrenda,
                ":idUsuario" => $idUsu
            ]);

            $idProductoInsertado = $this->conexionDataBase->lastInsertId();

            $sqlColor = "INSERT INTO producto_colores (producto_id, color_id) VALUES (:idProducto, :idColor)";
            $sentenciaColor = $this->conexionDataBase->prepare($sqlColor);
            $sentenciaColor->execute([
                ":idProducto" => $idProductoInsertado,
                ":idColor" => $idColor
            ]);

            $sqlImagen = "INSERT INTO imagenes_productos (producto_id, color_id, url_imagen, es_principal) 
                  VALUES (:idProducto, :idColor, :imagenUrl, 1)";
            $sentenciaImg = $this->conexionDataBase->prepare($sqlImagen);
            $sentenciaImg->execute([
                ":idProducto" => $idProductoInsertado,
                ":idColor" => $idColor,
                ":imagenUrl" => $imgPrenda
            ]);

            $sqlTalla =  "INSERT INTO producto_tallas (producto_id, color_id, talla, stock) 
                 VALUES (:idProducto, :idColor, :talla, 1)";
            $sentenciaTalla = $this->conexionDataBase->prepare($sqlTalla);
            $sentenciaTalla->execute([
                ":idProducto" => $idProductoInsertado,
                ":idColor" => $idColor,
                ":talla" => $tallaPrenda
            ]);

            $this->conexionDataBase->commit();
            return true;
        } catch (Exception $e) {
            header("Location: ../segundaMano.php");
            return false;
        }
    }

    public function listarTodasTallas()
    {
        $sql = "SELECT DISTINCT talla from producto_tallas ";
        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia->execute();

        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerMisPrendasSegundaMano($idUsuario)
    {
        $sql = "SELECT p.*, pc.color_id, pt.talla, MIN(i.url_imagen) as url_imagen 
                FROM productos p 
                LEFT JOIN producto_colores pc ON p.id = pc.producto_id
                LEFT JOIN producto_tallas pt ON p.id = pt.producto_id
                LEFT JOIN imagenes_productos i ON p.id = i.producto_id AND i.es_principal = 1
                WHERE p.id_usuario_vendedor = :idUsuario 
                GROUP BY p.id, pc.color_id, pt.talla
                ORDER BY p.creado_en DESC";

        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia->execute([":idUsuario" => $idUsuario]);
        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerImagenesProducto($idProducto)
    {
        $sql = "SELECT id, url_imagen FROM imagenes_productos WHERE producto_id = :id";
        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia->execute([':id' => $idProducto]);
        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizarDatosPrendaSegundaMano($idPrenda, $nombrePrenda, $precioPrenda, $tipoPrenda, $idUsu, $idColor, $talla)
    {

        try {
            $this->conexionDataBase->beginTransaction();

            $sql = "UPDATE productos SET nombre = :nombrePrenda, precio = :precioPrenda, tipo_id = :idTipoPrenda, estado_revision = 'Pendiente' WHERE id = :idPrenda AND id_usuario_vendedor = :idUsu";
            $sentencia = $this->conexionDataBase->prepare($sql);
            $sentencia->execute([
                ":nombrePrenda" => $nombrePrenda,
                ":precioPrenda" => $precioPrenda,
                ":idTipoPrenda" => $tipoPrenda,
                ":idPrenda" => $idPrenda,
                ":idUsu" => $idUsu
            ]);

            $sqlColor = "UPDATE producto_colores SET color_id = :idColor WHERE producto_id = :idPrenda";
            $sentenciaColor = $this->conexionDataBase->prepare($sqlColor);
            $sentenciaColor->execute([
                ":idColor" => $idColor,
                ":idPrenda" => $idPrenda
            ]);

            $sqlTalla = "UPDATE producto_tallas SET talla = :talla, color_id = :idColor WHERE producto_id = :idPrenda";

            $sentenciaTalla = $this->conexionDataBase->prepare($sqlTalla);
            $sentenciaTalla->execute([
                ":talla" => $talla,
                ":idColor" => $idColor,
                ":idPrenda" => $idPrenda
            ]);

            $this->conexionDataBase->commit();

            return true;
        } catch (PDOException) {
            header("Location: perfil.php?seccion=prendas");
            return false;
        }
    }

    public function borrarImagenPrenda($idImagen)
    {
        $sql = "DELETE FROM imagenes_productos WHERE id = :idImagen";
        $sentencia = $this->conexionDataBase->prepare($sql);
        return $sentencia->execute([":idImagen" => $idImagen]);
    }

    public function anadirImagenPrenda($idPrenda, $idColor, $urlImagen)
    {
        $sqlCheck = "SELECT count(*) FROM imagenes_productos WHERE producto_id = :id AND es_principal = 1";
        $sentenciaCheck = $this->conexionDataBase->prepare($sqlCheck);
        $sentenciaCheck->execute([":id" => $idPrenda]);
        $esPrincipal = ($sentenciaCheck->fetchColumn() == 0) ? 1 : 0;

        $sql = "INSERT INTO imagenes_productos (producto_id, color_id, url_imagen, es_principal) VALUES (:idPrenda, :idColor, :urlImagen, :esPrincipal)";
        $sentencia = $this->conexionDataBase->prepare($sql);
        return $sentencia->execute([
            ":idPrenda" => $idPrenda,
            ":idColor" => $idColor,
            ":urlImagen" => $urlImagen,
            ":esPrincipal" => $esPrincipal
        ]);
    }

    public function obtenerCatalogoSegundaMano($idUsuarioActual = null)
    {
        $sql = "SELECT p.*, pc.color_id, c.nombre as color_nombre, pt.talla, MIN(i.url_imagen) as url_imagen 
                FROM productos p 
                LEFT JOIN producto_colores pc ON p.id = pc.producto_id
                LEFT JOIN colores c ON pc.color_id = c.id
                LEFT JOIN producto_tallas pt ON p.id = pt.producto_id
                LEFT JOIN imagenes_productos i ON p.id = i.producto_id AND i.es_principal = 1
                WHERE p.es_segunda_mano = 1 AND p.estado_revision = 'Aprobado' AND pt.stock > 0";

        if ($idUsuarioActual !== null) {
            $sql .= " AND p.id_usuario_vendedor != :idUsuario ";
        }

        $sql .= " GROUP BY p.id, pc.color_id, pt.talla, c.nombre ORDER BY p.creado_en DESC";

        $sentencia = $this->conexionDataBase->prepare($sql);

        if ($idUsuarioActual !== null) {
            $sentencia->execute([":idUsuario" => $idUsuarioActual]);
        } else {
            $sentencia->execute();
        }

        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }


    private function filtrarRebajas($orden)
    {
        $sql = "SELECT p.*, c.id as color_id, c.nombre as color_nombre, MIN(i.url_imagen) as url_imagen 
                FROM productos p 
                INNER JOIN producto_colores pc ON p.id = pc.producto_id
                INNER JOIN colores c ON pc.color_id = c.id
                INNER JOIN colecciones col ON p.coleccion_id = col.id 
                LEFT JOIN imagenes_productos i ON p.id = i.producto_id AND i.color_id = c.id AND i.es_principal = 1 
                WHERE p.rebaja > 0 AND p.activo = 1 AND col.activa = 1 
                GROUP BY p.id, c.id" . $this->obtenerSqlOrden($orden);
        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia->execute();
        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    public function verificarCodigoDescuento($codigo, $email)
    {
        try {
            $sql = "SELECT * FROM codigos_accesos WHERE codigo = :codigo AND email = :email AND tipo = 'descuento' AND usado = 0";
            $sentencia = $this->conexionDataBase->prepare($sql);
            $sentencia->execute([
                ':codigo' => $codigo,
                ':email' => $email
            ]);
            return $sentencia->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en verificarCodigoDescuento: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerColeccionSecreta()
    {
        $sql = "SELECT p.*, c.id as color_id, c.nombre as color_nombre, MIN(i.url_imagen) as url_imagen
                FROM productos p
                INNER JOIN producto_colores pc ON p.id = pc.producto_id
                INNER JOIN colores c ON pc.color_id = c.id
                INNER JOIN colecciones col ON p.coleccion_id = col.id 
                LEFT JOIN imagenes_productos i ON p.id = i.producto_id AND i.color_id = c.id AND i.es_principal = 1
                WHERE p.activo = 1 AND col.activa = 3
                GROUP BY p.id, c.id
                ORDER BY p.creado_en DESC";

        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia->execute();
        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerColoresColeccionSecreta()
    {
        $sql = "SELECT DISTINCT c.* FROM colores c 
            INNER JOIN producto_colores pc ON c.id = pc.color_id 
            INNER JOIN productos p ON pc.producto_id = p.id 
            INNER JOIN colecciones col ON p.coleccion_id = col.id 
            WHERE p.activo = 1 AND col.activa = 3";

        $sentencia = $this->conexionDataBase->prepare($sql);
        $sentencia->execute();
        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }
}
