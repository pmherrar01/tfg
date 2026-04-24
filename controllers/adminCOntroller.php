<?php
session_start();

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../models/pedido.php";

if (!isset($_SESSION["usuario_id"]) || $_SESSION["rol_id"] != 1) {
    header("Location: ../index.php?error=acceso_denegado");
    exit();
}

$db = new Database();
$conexion = $db->conectar();
$pedido = new Pedido($conexion);

if ($_SERVER["REQUEST_METHOD" === "POST"]) {

    $idPedido = isset($_POST["idPedido"]) ? $_POST["idPedido"] : 0;
    $nuevoEstado = isset($_POST["nuevoEstado"]) ? trim($_POST["nuevoEstado"]) : "";

    $accion = isset($_POST["accion"]) ? $_POST["accion"] : "";

    switch ($accion) {
        case 'cambiarEstadoPedido':
            $pedido->actualizarEstadoPedido($idPedido, $nuevoEstado);
            break;

        default:
            header("Location: ../admin/admin.php?error=accion_desconocida");
            break;
    }
} else {
    header("Location: ../admin/admin.php");
}
exit();
