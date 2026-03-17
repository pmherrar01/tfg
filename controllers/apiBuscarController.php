<?php

require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../models/producto.php";

$db = new Database();
$producto = new Producto($db->conectar());

$prendaABuscar = isset($_GET["q"]) ? $_GET["q"] : "";

$resultado = $producto->buscarPorNombre($prendaABuscar);

echo json_encode($resultado);

?>