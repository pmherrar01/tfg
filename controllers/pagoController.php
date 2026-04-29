<?php
session_start();
require_once '../config/db.php';
require_once '../models/pedido.php';
require_once '../models/producto.php'; // Necesario para sacar los precios
require_once '../vendor/autoload.php';

if (!isset($_SESSION['usuario_id']) || empty($_SESSION['carrito'])) {
    header("Location: ../carrito.php");
    exit();
}

// ---------------------------------------------------------
// 1. CONFIGURACIÓN DE STRIPE 
// ---------------------------------------------------------
\Stripe\Stripe::setApiKey('sk_test_51TRSRfHJPlhS3OiOmWvQ9M4K1TuNPsHDsBNsV9l99ziXgumDDGjjQtGNQNprptcmSqS0QYrdrGx4AMaOr2HAcy5o006E97tSH6');

$conexion = new Database();
$db = $conexion->conectar();
$pedidoObj = new Pedido($db);
$productoObj = new Producto($db); 
$idUsuario = $_SESSION['usuario_id'];

// =========================================================
// CASO A: EL USUARIO VUELVE DE STRIPE TRAS PAGAR CON ÉXITO
// =========================================================
if (isset($_GET['status']) && $_GET['status'] == 'success') {
    
    $total = $_SESSION['checkout_data']['total'] ?? 0;
    $direccion = $_SESSION['checkout_data']['direccion'] ?? 'Dirección no proporcionada';

    // 1. Crear el pedido principal en la BD
    $idPedido = $pedidoObj->crearPedido($idUsuario, $total, $direccion);

    if ($idPedido) {
        // 2. Extraer correctamente los datos de la sesión para la BD
        foreach ($_SESSION['carrito'] as $item) {
            $idProducto = $item['idPrenda']; // Aquí sacamos el ID limpio
            $idColor    = $item['color_id'];
            $talla      = $item['talla'];
            $cantidad   = $item['cantidad'];

            // Tu función segura que busca el precio ella misma
            $pedidoObj->crearDetallesPedidos($idPedido, $idProducto, $idColor, $talla, $cantidad);
            $productoObj->actualizarStock($idProducto, $idColor, $talla, $cantidad);
        }

        // 3. Limpiar carrito y datos temporales
        unset($_SESSION['carrito']);
        unset($_SESSION['checkout_data']);

        // 4. Redirigir a la página final
        header("Location: ../gracias.php");
        exit();
    } else {
        die("Error al procesar el pedido en la base de datos.");
    }
}

// =========================================================
// CASO B: EL USUARIO ENVÍA EL FORMULARIO PARA IR A PAGAR
// =========================================================
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $_SESSION['checkout_data'] = [
        'total' => $_POST['total'] ?? 0,
        'direccion' => $_POST['direccion'] ?? 'Dirección no proporcionada'
    ];

    $metodo_pago = $_POST['metodo_pago'] ?? 'tarjeta';

    if ($metodo_pago == 'bizum') {
        // Si es Bizum, no vamos a Stripe. Simulamos que el pago se hizo con éxito y redirigimos.
        header("Location: ../controllers/pagoController.php?status=success");
        exit();
    }

    $line_items = [];
    $subtotal = 0;

    // Preparamos los productos para Stripe consultando su info real
    foreach ($_SESSION['carrito'] as $item) {
        
        // Buscamos en la BD el nombre y precio real para Stripe
        $datosProd = $productoObj->obtenerProducto($item['idPrenda']);
        $precioReal = $datosProd['precio'];
        $nombreReal = $datosProd['nombre'];

        $subtotal += ($precioReal * $item['cantidad']);
        
        $line_items[] = [
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => $nombreReal . ' (Talla: ' . $item['talla'] . ')',
                ],
                // Stripe exige céntimos (ej: 25.50€ -> 2550)
                'unit_amount' => round($precioReal * 100), 
            ],
            'quantity' => $item['cantidad'],
        ];
    }

    // Añadimos los gastos de envío si no supera los 50€
    if ($subtotal < 50) {
        $line_items[] = [
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => 'Gastos de envío',
                ],
                'unit_amount' => 499, // 4.99€
            ],
            'quantity' => 1,
        ];
    }

    // Ojo a la ruta, si no estás en /tfg, ajústala
    $dominio = "http://" . $_SERVER['HTTP_HOST'] ; 

    try {
        $checkout_session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $line_items,
            'mode' => 'payment',
            'success_url' => $dominio . '/controllers/pagoController.php?status=success',
            'cancel_url' => $dominio . '/checkout.php',
        ]);

        header("HTTP/1.1 303 See Other");
        header("Location: " . $checkout_session->url);
        exit();

    } catch (Exception $e) {
        die("Error al conectar con Stripe: " . $e->getMessage());
    }
}//
?>