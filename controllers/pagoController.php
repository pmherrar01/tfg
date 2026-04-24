<?php

session_start();

require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../models/pedido.php";
require_once __DIR__ . "/../models/producto.php";
require_once __DIR__ . "/../models/usuario.php";

if ($_SERVER["REQUEST_METHOD"] != "POST" && empty($_SESSION["carrito"])) {
    header("Location: ./index.php");
    exit;
}

$db = new Database();
$conexion = $db->conectar();
$pedido = new Pedido($conexion);
$producto = new Producto($conexion);
$usu = new Usuario($conexion);

$idUsu = $_SESSION["usuario_id"];
$totalPedido = isset($_POST["totalPedido"]) ? $_POST["totalPedido"] : 0;
$datosUsu = $usu->obtenerDatosUsu($idUsu);

$direccionUsu = $datosUsu['direccion'] . ", " . $datosUsu['codigo_postal'] . " - " . $datosUsu['ciudad'];


try {
    $conexion->beginTransaction();

    $idPedido = $pedido->crearPedido($idUsu, $totalPedido, $direccionUsu);



    foreach ($_SESSION["carrito"] as $productoCarrito) {
        $pedido->crearDetallesPedidos($idPedido, $productoCarrito["idPrenda"], $productoCarrito["color_id"], $productoCarrito["talla"], $productoCarrito["cantidad"]);
        $producto->actualizarStock($productoCarrito["idPrenda"], $productoCarrito["color_id"], $productoCarrito["talla"], $productoCarrito["cantidad"]);
    }

    $conexion->commit();

    $datosPedido = [
        "nombreUsu" => $datosUsu['nombre'],
        "email"     => $datosUsu['email'],
        "idPedido"  => $idPedido,
        "total"     => $totalPedido,
        "direccion" => $direccionUsu,
        "articulos" => count($_SESSION["carrito"]) 
    ];

    $config = parse_ini_file(__DIR__ . '/../config/config.ini');
    $urlWebhook = $config['base_url'] . $config['confirmacionCompra'];

    $curl = curl_init($urlWebhook);
    curl_setopt($curl, CURLOPT_POST, true); 
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($datosPedido)); 
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($curl, CURLOPT_TIMEOUT, 2);
    
    curl_exec($curl);

    unset($_SESSION['carrito']);
    header("Location: ../gracias.php");
} catch (Exception $e) {

$conexion->rollBack();

    header("Location: ../gracias.php");
}
