<?php

session_start();


require_once __DIR__ . '/../models/favorito.php';
require_once "./models/producto.php"; 
require_once "./config/db.php";

$db = new Database();
$producto = new Producto($db->conectar());

$novedades = $producto->listarProductos(8);

$arrayFavoritos = [];

if (isset($_SESSION['usuario_id'])) {
    // Si está logueado, sacamos sus favoritos
    $favoritoModel = new Favorito($db->conectar());
    $misFavoritos = $favoritoModel->listarFavoritos($_SESSION['usuario_id']);
    
    // Convertimos la lista en un formato fácil de comprobar: "idPrenda-idColor" (Ej: "1-3")
    foreach ($misFavoritos as $fav) {
        $arrayFavoritos[] = $fav['id'] . '-' . $fav['color_id'];
    }
}

?>