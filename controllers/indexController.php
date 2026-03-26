<?php

session_start();


require_once __DIR__ . '/../models/favorito.php';
require_once "./models/producto.php"; 
require_once "./config/db.php";

$db = new Database();
$producto = new Producto($db->conectar());

$novedades = $producto->listarProductos(1, 8);

$arrayFavoritos = [];

if (isset($_SESSION['usuario_id'])) {
    $favoritoModel = new Favorito($db->conectar());
    $misFavoritos = $favoritoModel->listarFavoritos($_SESSION['usuario_id']);
    
    foreach ($misFavoritos as $fav) {
        $arrayFavoritos[] = $fav['id'] . '-' . $fav['color_id'];
    }
}

?>