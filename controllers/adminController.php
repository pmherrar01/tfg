<?php
session_start();

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../models/pedido.php";
require_once __DIR__ . "/../models/producto.php";

if (!isset($_SESSION["usuario_id"]) || $_SESSION["rol_id"] != 1) {
    header("Location: ../index.php?error=acceso_denegado");
    exit();
}

$db = new Database();
$conexion = $db->conectar();
$pedido = new Pedido($conexion);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $idPedido = isset($_POST["idPedido"]) ? $_POST["idPedido"] : 0;
    $nuevoEstado = isset($_POST["nuevoEstado"]) ? trim($_POST["nuevoEstado"]) : "";

    $accion = isset($_POST["accion"]) ? $_POST["accion"] : "";

    switch ($accion) {
        case 'cambiarEstadoPedido':
            $pedido->actualizarEstadoPedido($idPedido, $nuevoEstado);
            header("Location: ../admin/admin.php?seccion=pedidos&mensaje=estado_actualizado");
            break;
        case 'actualizarInventarioMasivo':
            $stocks = isset($_POST['stock']) ? $_POST['stock'] : [];
            $rebajas = isset($_POST['rebaja']) ? $_POST['rebaja'] : [];
            $estados = isset($_POST['activo']) ? $_POST['activo'] : [];
            $precios = isset($_POST['precio']) ? $_POST['precio'] : []; 
            $colecciones = isset($_POST['coleccion']) ? $_POST['coleccion'] : []; 

            $pagRetorno = isset($_POST['pagina_retorno']) ? $_POST['pagina_retorno'] : 1;

            $prodObj = new Producto($conexion);

            foreach ($rebajas as $idPrenda => $valorRebaja) {
                $estadoActivo = $estados[$idPrenda];
                $precioActualizado = isset($precios[$idPrenda]) ? $precios[$idPrenda] : null;
                $coleccionActualizada = isset($colecciones[$idPrenda]) ? $colecciones[$idPrenda] : null; 
                
                $prodObj->actualizarDatosBasicosPrenda($idPrenda, $valorRebaja, $estadoActivo, $precioActualizado, $coleccionActualizada);
            }

            foreach ($stocks as $clave => $cantidad) {
                list($idP, $idC, $talla) = explode('_', $clave);
                $prodObj->actualizarStockEspecifico($idP, $idC, $talla, $cantidad);
            }

            header("Location: ../admin/admin.php?seccion=productos&pagina=$pagRetorno&mensaje=inventario_actualizado");
            exit();
            
        case 'crearColeccion':
            $nombre = isset($_POST['nombre_coleccion']) ? trim($_POST['nombre_coleccion']) : "";
            $descripcion = isset($_POST['descripcion_coleccion']) ? trim($_POST['descripcion_coleccion']) : "";
            
            if (!empty($nombre)) {
                $prodObj = new Producto($conexion);
                $prodObj->crearColeccion($nombre, $descripcion);
                header("Location: ../admin/admin.php?seccion=colecciones&mensaje=coleccion_creada");
            } else {
                header("Location: ../admin/admin.php?seccion=colecciones&error=nombre_vacio");
            }
            exit();
            

        case 'actualizarColeccion':
            $idCol = isset($_POST['id_coleccion']) ? $_POST['id_coleccion'] : 0;
            $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : "";
            $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : "";
            $nuevoEstado = isset($_POST['nuevo_estado']) ? $_POST['nuevo_estado'] : 2;
            
            $prodObj = new Producto($conexion);
            $prodObj->actualizarEstadoColeccion($idCol, $nombre, $descripcion, $nuevoEstado);
            
            header("Location: ../admin/admin.php?seccion=colecciones&mensaje=coleccion_actualizada");
            exit();

            case 'actualizarSegundaMano':
            $revisiones = isset($_POST['revision']) ? $_POST['revision'] : [];
            $vendedores = isset($_POST['vendedor']) ? $_POST['vendedor'] : [];
            
            $prodObj = new Producto($conexion);
            
            // Recorremos de forma segura
            foreach ($revisiones as $idPrenda => $estado) {
                // Comprobamos que exista el vendedor para esa prenda, si no, lo saltamos
                if (isset($vendedores[$idPrenda])) {
                    $idVendedor = $vendedores[$idPrenda];
                    $prodObj->actualizarRevisionSegundaMano($idPrenda, $estado, $idVendedor);
                }
            }
            
            header("Location: ../admin/admin.php?seccion=segundaMano&mensaje=revision_completada");
            exit();
            break;
            

        default:
            header("Location: ../admin/admin.php?error=accion_desconocida");
            break;
    }
} else {
    header("Location: ../admin/admin.php");
}
exit();
