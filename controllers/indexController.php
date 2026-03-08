<?php

require_once "./models/producto.php"; 
require_once "./config/db.php";

$db = new Database();
$producto = new Producto($db->conectar());

$novedades = $producto->listarProductos(8);

?>