<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/producto.php';
require_once __DIR__ . '/../models/imagen.php';

$db = new Database();
$conexion = $db->conectar();
$productoModel = new Producto($conexion);
$imagenModel = new Imagen($conexion);

// --- 1. LÓGICA DE AÑADIR AL CARRITO (POST) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'agregar') {
    
    $idPrenda = $_POST['idPrenda'];
    $talla = $_POST['talla'];
    $color_id = $_POST['color_id'];
    $cantidad = 1;

    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }

    $productoEncontrado = false;
    foreach ($_SESSION['carrito'] as &$item) {
        if ($item['idPrenda'] == $idPrenda && $item['talla'] == $talla && $item['color_id'] == $color_id) {
            $item['cantidad'] += $cantidad;
            $productoEncontrado = true;
            break;
        }
    }

    if (!$productoEncontrado) {
        $_SESSION['carrito'][] = [
            'idPrenda' => $idPrenda,
            'talla' => $talla,
            'color_id' => $color_id,
            'cantidad' => $cantidad
        ];
    }

    header("Location: ../fichaProducto.php?idPrenda=" . $idPrenda . "&color=" . $color_id . "&mensaje=carrito_ok");
    exit;
}

// --- NUEVO: LÓGICA DE MODIFICAR CARRITO (+, -, Eliminar) ---
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['accion']) && isset($_GET['indice'])) {
    $indice = (int)$_GET['indice'];

    if (isset($_SESSION['carrito'][$indice])) {
        if ($_GET['accion'] == 'sumar') {
            $_SESSION['carrito'][$indice]['cantidad']++;
        } elseif ($_GET['accion'] == 'restar') {
            $_SESSION['carrito'][$indice]['cantidad']--;
            // Si la cantidad llega a 0, lo eliminamos automáticamente
            if ($_SESSION['carrito'][$indice]['cantidad'] <= 0) {
                unset($_SESSION['carrito'][$indice]);
            }
        } elseif ($_GET['accion'] == 'eliminar') {
            unset($_SESSION['carrito'][$indice]);
        }

        // Reorganizamos la lista para que no queden huecos vacíos
        $_SESSION['carrito'] = array_values($_SESSION['carrito']);
    }

    // Recargamos el carrito
    header("Location: ../carrito.php");
    exit;
}

// --- 2. LÓGICA DE MOSTRAR EL CARRITO (GET normal) ---
$carritoActual = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
$carritoDetallado = [];
$totalCarrito = 0;

foreach ($carritoActual as $indice => $item) {
    $datosProd = $productoModel->obtenerProducto($item['idPrenda']);
    
    $imagenesColor = $imagenModel->listarImagenesPorColor($item['idPrenda'], $item['color_id']);
    $foto = !empty($imagenesColor) ? $imagenesColor[0]['url_imagen'] : 'public/img/fondo.jpg';
    
    $coloresProducto = $productoModel->obtenerColoresPorProducto($item['idPrenda']);
    $nombreColor = "Color";
    foreach($coloresProducto as $cp) {
        if($cp['id'] == $item['color_id']) {
            $nombreColor = $cp['nombre'];
            break;
        }
    }

    $subtotal = $datosProd['precio'] * $item['cantidad'];
    $totalCarrito += $subtotal;

    $carritoDetallado[] = [
        'indice' => $indice, 
        'idPrenda' => $item['idPrenda'],
        'color_id' => $item['color_id'], // <-- AQUÍ PASAMOS EL COLOR A LA VISTA
        'nombre' => $datosProd['nombre'],
        'precio' => $datosProd['precio'],
        'talla' => $item['talla'],
        'color_nombre' => $nombreColor,
        'cantidad' => $item['cantidad'],
        'imagen' => $foto,
        'subtotal' => $subtotal
    ];
}
?>