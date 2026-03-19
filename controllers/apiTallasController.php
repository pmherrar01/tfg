<?php
// controllers/apiTallasController.php
require_once '../config/db.php';
require_once '../models/producto.php'; // Usa tu modelo de productos

header('Content-Type: application/json');

if (isset($_GET['idPrenda']) && isset($_GET['idColor'])) {
    
    $idPrenda = $_GET['idPrenda'];
    $idColor = $_GET['idColor'];

    // AQUÍ: Usa el método de tu clase Producto (o una consulta SQL directa) 
    // que devuelva un array con { "talla": "S", "stock": 5 }
    // Ejemplo: $tallas = $productoObj->obtenerTallasPorColor($idPrenda, $idColor);
    
    // Y lo devolvemos a JavaScript
    echo json_encode($tallas);
    exit;
}

echo json_encode([]);