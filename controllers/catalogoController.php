<?php

session_start();

require_once "models/producto.php";
require_once "models/imagen.php";
require_once "config/db.php";
require_once __DIR__ . '/../models/favorito.php';

$db = new DataBase();
$producto = new Producto($db->conectar());
$imagen = new Imagen($db->conectar());

// Detectamos si estamos en la colección VIP
$esModoSecreto = (isset($_GET['especial']) && $_GET['especial'] == 'herror');

// Función creadora de URLs inteligente (conserva el modo secreto y filtros previos)
function crearUrl($clave, $valor) {
    $parametros = $_GET;
    $parametros[$clave] = $valor;
    if (isset($parametros['pagina'])) {
        unset($parametros['pagina']);
    }
    return '?' . http_build_query($parametros);
}

$ordenActual = isset($_GET["orden"]) ? $_GET["orden"] : null;

// LÓGICA DE FILTRADO UNIFICADA (Pasamos el flag $esModoSecreto a todas las consultas)
if (isset($_GET["genero"])) {
    $listaProductos = $producto->filtrar("genero", $_GET["genero"], null, $ordenActual, $esModoSecreto);
    $mensajeFiltrado = "Género: " . ($_GET['genero'] == "1" ? "Hombre" : ($_GET['genero'] == "2" ? "Mujer" : "Unisex"));
} elseif (isset($_GET["coleccion"]) && !$esModoSecreto) {
    $listaProductos = $producto->filtrar('coleccion', $_GET["coleccion"], null, $ordenActual, $esModoSecreto);
    $datosColeccion = $producto->obtenerNombreColeccion($_GET["coleccion"]); 
    $mensajeFiltrado = "Colección: " . $datosColeccion['nombre'];
} elseif (isset($_GET["tipo"])) {
    $listaProductos = $producto->filtrar('tipoPrenda', $_GET["tipo"], null, $ordenActual, $esModoSecreto);
    $datosTiposPrendas = $producto->obtenerTipoPrenda($_GET["tipo"]); 
    $mensajeFiltrado = "Tipo prenda: " . $datosTiposPrendas['nombre'];
} elseif (isset($_GET["talla"])) {
    $listaProductos = $producto->filtrar('talla', $_GET["talla"], null, $ordenActual, $esModoSecreto);
    $mensajeFiltrado = "Talla: " . $_GET["talla"];
} elseif (isset($_GET["color"])) {
    $listaProductos = $producto->filtrar('color', $_GET["color"], null, $ordenActual, $esModoSecreto);
    $mensajeFiltrado = "Color: " . $_GET["color"];
} elseif (isset($_GET["rebajas"]) && !$esModoSecreto) {
    $listaProductos = $producto->filtrar('rebajas', 1, null, $ordenActual, $esModoSecreto);
    $mensajeFiltrado = "Productos en Rebajas";
} elseif (isset($_GET["precioMin"]) && isset($_GET["precioMax"])) {
    $listaProductos = $producto->filtrar('precio', $_GET["precioMax"], $_GET["precioMin"], $ordenActual, $esModoSecreto);
    $mensajeFiltrado = "Productos entre " . $_GET["precioMin"] . "€ y " . $_GET["precioMax"] . "€";
} elseif (isset($_GET["orden"])) {
    $listaProductos = $producto->ordenar($_GET["orden"], $esModoSecreto);
    $mensajeFiltrado = "Ordenado por selección";
} else {
    // Si no hay filtros, cargamos todo según el modo en el que estemos
    if ($esModoSecreto) {
        $listaProductos = $producto->obtenerColeccionSecreta(); 
        $mensajeFiltrado = "Colección Exclusiva";
    } else {
        $listaProductos = $producto->listarProductos(1);
        $mensajeFiltrado = "Todos los productos";
    }
}

// OBTENER LISTADOS PARA LOS MENÚS DESPLEGABLES DEL ASIDE
if ($esModoSecreto) {
    $listaCategorias = []; 
    $listaColores = $producto->obtenerColoresColeccionSecreta();
} else {
    $listaCategorias = $producto->listarColecciones();
    $listaColores = $producto->listaColores();
}

$listaTiposProductos = $producto->listarTiposPrendas();

// Extraemos los precios dinámicos según el modo en el que estemos
$precioMax = $producto->obtenerPrecioMinMax("MAX", $esModoSecreto);
$precioMin = $producto->obtenerPrecioMinMax("MIN", $esModoSecreto);

// FAVORITOS
$arrayFavoritos = [];
if (isset($_SESSION['usuario_id'])) {
    $favoritoModel = new Favorito($db->conectar());
    $misFavoritos = $favoritoModel->listarFavoritos($_SESSION['usuario_id']);
    
    foreach ($misFavoritos as $fav) {
        $arrayFavoritos[] = $fav['id'] . '-' . $fav['color_id'];
    }
}

?>