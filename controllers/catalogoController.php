<?php

session_start();

require_once "models/producto.php";
require_once "models/imagen.php";
require_once "config/db.php";

$db = new DataBase();
$producto = new Producto($db->conectar());
$imagen = new Imagen($db->conectar());

function crearUrl($clave, $valor)
{
    $parametros = $_GET;
    $parametros[$clave] = $valor;
    return '?' . http_build_query($parametros);
}

$ordenActual = isset($_GET["orden"]) ? $_GET["orden"] : null;

if (isset($_GET["genero"])) {
    $listaProductos = $producto->filtrar("genero", $_GET["genero"], null, $ordenActual);
    $mensajeFiltrado = $_GET['genero'];
    if ($mensajeFiltrado == "1") {
        $mensajeFiltrado = "Hombre";
    } elseif ($mensajeFiltrado == "2") {
        $mensajeFiltrado = "Mujer";
    } elseif ($mensajeFiltrado == "3") {
        $mensajeFiltrado =  "Unisex";
    }
} elseif (isset($_GET["coleccion"])) {
    $listaProductos = $producto->filtrar('coleccion', $_GET["coleccion"], null, $ordenActual);
    $datosColeccion = $producto->obtenerNombreColeccion($_GET["coleccion"]); 
    $mensajeFiltrado = "Colección: " . $datosColeccion['nombre'];
} elseif (isset($_GET["tipo"])) {
    $listaProductos = $producto->filtrar('tipoPrenda', $_GET["tipo"], null, $ordenActual);
    $datosTiposPrendas = $producto->obtenerTipoPrenda($_GET["tipo"]); 
    $mensajeFiltrado = "Tipo prenda: " . $datosTiposPrendas['nombre'];
} elseif (isset($_GET["talla"])) {
    $listaProductos = $producto->filtrar('talla', $_GET["talla"], null, $ordenActual);
    $mensajeFiltrado = "Talla: " . $_GET["talla"];
} elseif (isset($_GET["color"])) {
    $listaProductos = $producto->filtrar('color', $_GET["color"], null, $ordenActual);
    $mensajeFiltrado = "Color: " . $_GET["color"];
} elseif (isset($_GET["precioMin"]) && isset($_GET["precioMax"])) {
    $listaProductos = $producto->filtrar('precio', $_GET["precioMax"], $_GET["precioMin"], $ordenActual);
    $mensajeFiltrado = "Productos entre " . $_GET["precioMin"] . "€ y " . $_GET["precioMax"] . "€";
} elseif (isset($_GET["orden"])) {
    $listaProductos = $producto->ordenar($_GET["orden"]);
    $mensajeFiltrado = "Ordenado por selección";
} else {
    $listaProductos = $producto->listarProductos();
    $mensajeFiltrado = "Todos los productos";
}

$listaCategorias = $producto->listarColecciones();
$listaTiposProductos = $producto->listarTiposPrendas();
$listaColores = $producto->listaColores();

$precioMax = $producto->obtenerPrecioMinMax("MAX");
$precioMin = $producto->obtenerPrecioMinMax("MIN");

 ?>