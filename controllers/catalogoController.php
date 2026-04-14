<?php

session_start();



require_once "models/producto.php";
require_once "models/imagen.php";
require_once "config/db.php";
require_once __DIR__ . '/../models/favorito.php';

$db = new DataBase();
$producto = new Producto($db->conectar());
$imagen = new Imagen($db->conectar());

$esModoSecreto = (isset($_GET['especial']) && $_GET['especial'] == 'herror');

function crearUrl($clave, $valor)
{
    $parametros = [];
    
    if ($clave === 'orden') {
        if (isset($_GET['genero'])) $parametros['genero'] = $_GET['genero'];
        if (isset($_GET['coleccion'])) $parametros['coleccion'] = $_GET['coleccion'];
        if (isset($_GET['tipo'])) $parametros['tipo'] = $_GET['tipo'];
        if (isset($_GET['talla'])) $parametros['talla'] = $_GET['talla'];
        if (isset($_GET['color'])) $parametros['color'] = $_GET['color'];
        if (isset($_GET['rebajas'])) $parametros['rebajas'] = $_GET['rebajas'];
        if (isset($_GET['precioMin'])) {
            $parametros['precioMin'] = $_GET['precioMin'];
            $parametros['precioMax'] = $_GET['precioMax'];
        }
    } 
    else {
        if (isset($_GET['orden'])) $parametros['orden'] = $_GET['orden'];
        
        if ($clave === 'precioMin' || $clave === 'precioMax') {
            if (isset($_GET['precioMin'])) $parametros['precioMin'] = $_GET['precioMin'];
            if (isset($_GET['precioMax'])) $parametros['precioMax'] = $_GET['precioMax'];
        }
    }

    $parametros[$clave] = $valor;
    
    return '?' . http_build_query($parametros);
}

if ($esModoSecreto) {
    $listaProductos = $producto->obtenerColeccionSecreta(); 
    $listaColores = $producto->obtenerColoresColeccionSecreta();
    $listaColecciones = []; 
    $listaTiposProductos = $producto->listarTiposPrendas();
    $mensajeFiltrado = "Colección Exclusiva";

    $precioMin = 0; 
    $precioMax = 1000;
} else {



$ordenActual = isset($_GET["orden"]) ? $_GET["orden"] : null;

if (isset($_GET["coleccion"]) && $_GET["coleccion"] === 'vip') {
    
    if (isset($_SESSION['acceso']) && $_SESSION['acceso'] === true) {
        $listaProductos = $producto->listarProductos(3);
        $mensajeFiltrado = "Colección Exclusiva";
    } else {
        header("Location: index.php?error=acceso_denegado");
        exit();
    }

}elseif (isset($_GET["genero"])) {
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
}elseif (isset($_GET["rebajas"])) {
    $listaProductos = $producto->filtrar('rebajas', 1, null, $ordenActual);
    $mensajeFiltrado = "Productos en Rebajas";
} elseif (isset($_GET["precioMin"]) && isset($_GET["precioMax"])) {
    $listaProductos = $producto->filtrar('precio', $_GET["precioMax"], $_GET["precioMin"], $ordenActual);
    $mensajeFiltrado = "Productos entre " . $_GET["precioMin"] . "€ y " . $_GET["precioMax"] . "€";
} elseif (isset($_GET["orden"])) {
    $listaProductos = $producto->ordenar($_GET["orden"]);
    $mensajeFiltrado = "Ordenado por selección";
} else {
    $listaProductos = $producto->listarProductos(1);
    $mensajeFiltrado = "Todos los productos";
}

$listaCategorias = $producto->listarColecciones();
$listaTiposProductos = $producto->listarTiposPrendas();
$listaColores = $producto->listaColores();

$precioMax = $producto->obtenerPrecioMinMax("MAX");
$precioMin = $producto->obtenerPrecioMinMax("MIN");


$arrayFavoritos = [];

if (isset($_SESSION['usuario_id'])) {
    $favoritoModel = new Favorito($db->conectar());
    $misFavoritos = $favoritoModel->listarFavoritos($_SESSION['usuario_id']);
    
    foreach ($misFavoritos as $fav) {
        $arrayFavoritos[] = $fav['id'] . '-' . $fav['color_id'];
    }
}
}

 ?>