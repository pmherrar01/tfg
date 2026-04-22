<?php
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../models/look.php";

header('Content-Type: application/json');

$db = new Database();
$look = new Look($db->conectar());

$resultados = $look->obtenerTodosLosLooks();

if (count($resultados) > 0) {
    echo json_encode(["looks_disponibles" => $resultados]);
} else {
    echo json_encode(["mensaje" => "Actualmente no tenemos looks predefinidos publicados."]);
}
?>