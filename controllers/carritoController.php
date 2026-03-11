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
    $color_id = $_POST['color_id'];
    $cantidad = 1;

    // PROTECCIÓN 1: Evitamos el Error 500 si no mandan talla
    if (!isset($_POST['talla']) || empty($_POST['talla'])) {
        header("Location: ../fichaProducto.php?idPrenda=" . $idPrenda . "&color=" . $color_id . "&error=falta_talla");
        exit;
    }
    
    $talla = $_POST['talla'];

    // PROTECCIÓN 2: Comprobamos el stock en la base de datos
    $stmt = $conexion->prepare("SELECT stock FROM producto_tallas WHERE producto_id = ? AND color_id = ? AND talla = ?");
    $stmt->execute([$idPrenda, $color_id, $talla]);
    $resultadoStock = $stmt->fetch(PDO::FETCH_ASSOC);
    $stockMaximo = $resultadoStock ? $resultadoStock['stock'] : 0;

    // Si el stock directamente es 0 (Agotado)
    if ($stockMaximo < 1) {
        header("Location: ../fichaProducto.php?idPrenda=" . $idPrenda . "&color=" . $color_id . "&error=no_stock");
        exit;
    }

    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }

    $productoEncontrado = false;
    foreach ($_SESSION['carrito'] as &$item) {
        if ($item['idPrenda'] == $idPrenda && $item['talla'] == $talla && $item['color_id'] == $color_id) {
            
            // Si al sumar 1 superamos el stock disponible, error
            if ($item['cantidad'] + $cantidad > $stockMaximo) {
                header("Location: ../fichaProducto.php?idPrenda=" . $idPrenda . "&color=" . $color_id . "&error=no_stock");
                exit;
            }
            
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

// --- LÓGICA DE MODIFICAR CARRITO (+, -, Eliminar) ---
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['accion']) && isset($_GET['indice'])) {
    $indice = (int)$_GET['indice'];

    if (isset($_SESSION['carrito'][$indice])) {
        if ($_GET['accion'] == 'sumar') {
            
            $item = $_SESSION['carrito'][$indice];
            
            $stmt = $conexion->prepare("SELECT stock FROM producto_tallas WHERE producto_id = ? AND color_id = ? AND talla = ?");
            $stmt->execute([$item['idPrenda'], $item['color_id'], $item['talla']]);
            $resultadoStock = $stmt->fetch(PDO::FETCH_ASSOC);
            $stockMaximo = $resultadoStock ? $resultadoStock['stock'] : 0;

            if ($item['cantidad'] < $stockMaximo) {
                $_SESSION['carrito'][$indice]['cantidad']++;
            } else {
                header("Location: ../carrito.php?error=no_stock");
                exit;
            }

        } elseif ($_GET['accion'] == 'restar') {
            $_SESSION['carrito'][$indice]['cantidad']--;
            if ($_SESSION['carrito'][$indice]['cantidad'] <= 0) {
                unset($_SESSION['carrito'][$indice]);
            }
        } elseif ($_GET['accion'] == 'eliminar') {
            unset($_SESSION['carrito'][$indice]);
        }

        $_SESSION['carrito'] = array_values($_SESSION['carrito']);
    }

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
        'color_id' => $item['color_id'],
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