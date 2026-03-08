<?php

require_once "./models/producto.php";
require_once "./models/imagen.php";
require_once "./config/db.php";

$db = new Database();
$producto = new Producto($db->conectar());
$imagen = new Imagen($db->conectar());

$idPrenda = isset($_GET["idPrenda"]) ? $_GET["idPrenda"] : 0;

$datosPrenda = $producto->obtenerProducto($idPrenda);

if (!$datosPrenda) {
    header("Location: catalogo.php");
    exit;
}

$galeria = $imagen->listarImagenes($idPrenda);
$listaTallas = $producto->obtenerTallas($idPrenda);

// NUEVO: Obtenemos los colores de esta prenda
$coloresProducto = $producto->obtenerColoresPorProducto($idPrenda);

$cont = 0;

include './includes/header.php';

?>