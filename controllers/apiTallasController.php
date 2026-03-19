<?php
// controllers/apiTallasController.php
require_once '../config/db.php';
require_once '../models/producto.php'; 

header('Content-Type: application/json');

$db = new Database();



if (isset($_GET['idPrenda']) && isset($_GET['idColor'])) {
    
    $idPrenda = $_GET['idPrenda'];
    $idColor = $_GET['idColor'];

    try {
        // 1. Conéctate a tu base de datos (Usa tu método habitual)
        $conexion = $db->conectar(); 
        
        // 2. Haz la consulta. OJO: Cambia "stock_productos" por el nombre real de tu tabla
        $sql = "SELECT talla, stock FROM stock_productos WHERE producto_id = :idPrenda AND color_id = :idColor";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idPrenda', $idPrenda, PDO::PARAM_INT);
        $stmt->bindParam(':idColor', $idColor, PDO::PARAM_INT);
        $stmt->execute();
        
        $tallas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 3. Devolvemos el array real a JavaScript
        echo json_encode($tallas);
        
    } catch (PDOException $e) {
        // Si la base de datos falla, devolvemos un error limpio para que JS no se rompa
        http_response_code(500);
        echo json_encode(["error" => "Fallo en la base de datos"]);
    }
    
    exit;
}

echo json_encode([]);