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
            $precios = isset($_POST['precio']) ? $_POST['precio'] : []; // NUEVO: Array de precios
            $pagRetorno = isset($_POST['pagina_retorno']) ? $_POST['pagina_retorno'] : 1;

            $prodObj = new Producto($conexion);

            // Procesamos datos de la PRENDA (Rebaja, Estado y ahora Precio)
            foreach ($rebajas as $idPrenda => $valorRebaja) {
                $estadoActivo = $estados[$idPrenda];
                // Miramos si para este ID ha llegado un precio (solo llegará para oficiales)
                $precioActualizado = isset($precios[$idPrenda]) ? $precios[$idPrenda] : null;
                
                $prodObj->actualizarDatosBasicosPrenda($idPrenda, $valorRebaja, $estadoActivo, $precioActualizado);
            }

            // Procesamos STOCKS (Esto sigue igual)
            foreach ($stocks as $clave => $cantidad) {
                list($idP, $idC, $talla) = explode('_', $clave);
                $prodObj->actualizarStockEspecifico($idP, $idC, $talla, $cantidad);
            }

            header("Location: ../admin/admin.php?seccion=productos&pagina=$pagRetorno&mensaje=inventario_actualizado");
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
