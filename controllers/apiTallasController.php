<?php
// controllers/apiTallasController.php
require_once '../config/db.php';
require_once '../models/producto.php'; 

header('Content-Type: application/json');

if (isset($_GET['idPrenda']) && isset($_GET['idColor'])) {
    
    $idPrenda = $_GET['idPrenda'];
    $idColor = $_GET['idColor'];

    $db = new Database();

    try {
        // 1. Instanciamos la base de datos y tu modelo de forma limpia
    
        $productoObj = new Producto($db ->conectar());
        
        // 2. REUTILIZAMOS tu método existente
        $todasLasTallas = $productoObj->obtenerTallas($idPrenda);
        
        // 3. Filtramos solo las tallas del color que nos interesa
        $tallasDelColor = [];
        foreach ($todasLasTallas as $talla) {
            if ($talla['color_id'] == $idColor) {
                $tallasDelColor[] = [
                    "talla" => $talla['talla'],
                    "stock" => $talla['stock']
                ];
            }
        }

        // 4. Devolvemos el resultado al JavaScript
        echo json_encode($tallasDelColor);
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => "Fallo al obtener las tallas"]);
    }
    
    exit;
}

echo json_encode([]);