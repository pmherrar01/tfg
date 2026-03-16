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
$pedido = new Pedido($db->conectar());
$producto = new Producto($db->conectar()); 

$idUsu = $_SESSION["usuario_id"];
$totalPedido = isset($_POST["totalPedido"]) ? $_POST["totalPedido"] : 0;


try {
    // 1. Empezamos la transacción (pausamos el guardado definitivo)
    $db->conectar()->beginTransaction();

    // 2. Aquí creas el pedido
     $idPedido = $pedido->crearPedido($idUsu, $totalPedido);


    // 3. Aquí haces tu foreach del carrito
    // foreach(...) { $pedidoModel->crearDetalle... ; $productoModel->restarStock... }

    foreach ($_SESSION["carrito"] as $productoCarrito ) {
        $pedido->crearDetallesPedidos($productoCarrito["id"], $productoCarrito["idPrenda"], $productoCarrito["colorId"], $productoCarrito["tallaProducto"], $productoCarrito["cantidad"]);
        $producto->actualizarStock( $productoCarrito["idPrenda"], $productoCarrito["colorId"], $productoCarrito["tallaProducto"], $productoCarrito["cantidad"]);
    }

    // 4. Si todo el bucle termina bien, confirmamos y guardamos de verdad:
    $db->conectar()->commit();

    // 5. Borras el carrito y rediriges con éxito
     unset($_SESSION['carrito']);
     header("Location: perfil.php?seccion=pedidos");

} catch (Exception $e) {
    // Si cualquier consulta SQL de arriba falla, el código salta directamente aquí.
    
    // 1. Cancelamos todo para no guardar datos a medias
    $db->conectar()->rollBack();
    
    // 2. Redirigimos al usuario con un mensaje de error
     header("Location: ../carrito.php?error=fallo_pago");
}


?>