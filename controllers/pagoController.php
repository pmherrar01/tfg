<?php

session_start();

require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../models/pedido.php";
require_once __DIR__ . "/../models/producto.php";

if( $_SERVER["REQUEST_METHOD"] != "POST" && empty($_SESSION["carrito"])){
    header("Location: ./index.php");
    exit;

}

$db = new Database();
$conexion = $db->conectar();
$pedido = new Pedido($conexion);
$producto = new Producto($conexion); 

$idUsu = $_SESSION["usuario_id"];
$totalPedido = isset($_POST["totalPedido"]) ? $_POST["totalPedido"] : 0;


try {
    // 1. Empezamos la transacción (pausamos el guardado definitivo)
    $conexion->beginTransaction();

    // 2. Aquí creas el pedido
     $idPedido = $pedido->crearPedido($idUsu, $totalPedido);


    // 3. Aquí haces tu foreach del carrito
    // foreach(...) { $pedidoModel->crearDetalle... ; $productoModel->restarStock... }

    foreach ($_SESSION["carrito"] as $productoCarrito ) {
        $pedido->crearDetallesPedidos($productoCarrito["id"], $productoCarrito["idPrenda"], $productoCarrito["color_id"], $productoCarrito["talla"], $productoCarrito["cantidad"]);
        $producto->actualizarStock( $productoCarrito["idPrenda"], $productoCarrito["color_id"], $productoCarrito["talla"], $productoCarrito["cantidad"]);
    }

    // 4. Si todo el bucle termina bien, confirmamos y guardamos de verdad:
    $conexion->commit();

    // 5. Borras el carrito y rediriges con éxito
     unset($_SESSION['carrito']);
     header("Location: ../perfil.php?seccion=pedidos");

} catch (Exception $e) {
    // Si cualquier consulta SQL de arriba falla, el código salta directamente aquí.
    
    // 1. Cancelamos todo para no guardar datos a medias
    $conexion->rollBack();
    
    // 2. Redirigimos al usuario con un mensaje de error
     header("Location: ../carrito.php?error=fallo_pago");
}


?>