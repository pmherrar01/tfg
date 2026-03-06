<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "./models/producto.php";
require_once "./models/imagen.php";
require_once "./config/db.php";

$db = new DataBase();
$producto = new Producto($db->conectar());
$imagen = new Imagen($db->conectar());

if (isset($_GET["genero"])) {
    $listaProductos = $producto->filtrar("genero", $_GET["genero"]);
    $mensajeFiltrado = $_GET['genero'];

    if ($mensajeFiltrado == "1") {
        $mensajeFiltrado = "Hombre";
    } elseif ($mensajeFiltrado == "2") {
        $mensajeFiltrado = "Mujer";
    } elseif ($mensajeFiltrado == "3") {
        $mensajeFiltrado =  "Unisex";
    }
} elseif (isset($_GET["coleccion"])) {

    $listaProductos = $producto->filtrar('coleccion', $_GET["coleccion"]);
    $datosColeccion = $producto->obtenerNombreColeccion($_GET["coleccion"]);
    $mensajeFiltrado = "Colección: " . $datosColeccion['nombre'];
} elseif (isset($_GET["tipo"])) {
    $listaProductos = $producto->filtrar('tipoPrenda', $_GET["tipo"]);
    $datosTiposPrendas = $producto->obtenerTipoPrenda($_GET["tipo"]);
    $mensajeFiltrado = "Tipo prenda: " . $datosTiposPrendas['nombre'];
} elseif (isset($_GET["talla"])) {
    $listaProductos = $producto->filtrar('talla', $_GET["talla"]);
    $mensajeFiltrado = "Talla: " . $_GET["talla"];
} elseif (isset($_GET["color"])) {
    $listaProductos = $producto->filtrar('color', $_GET["color"]);
    $mensajeFiltrado = "Color: " . $_GET["color"];
} elseif (isset($_GET["precioMin"]) && isset($_GET["precioMax"])) {
    $listaProductos = $producto->filtrar('precio', $_GET["precioMax"], $_GET["precioMin"]);
    $mensajeFiltrado = "Productos cuyo precio estan entre " . $_GET["precioMin"] . " y " . $_GET["precioMax"];
} else {
    $listaProductos = $producto->listarProductos();
    $mensajeFiltrado = "Todos los productos";
}


if(isset($_GET["orden"])){
    $listaProductos = $producto->ordenar($_GET["orden"]);

}elseif (isset($_GET["orden"])) {
 $listaProductos = $producto->ordenar($_GET["orden"]);
}elseif (isset($_GET["orden"])) {
 $listaProductos = $producto->ordenar($_GET["orden"]);
}elseif (isset($_GET["orden"])) {
 $listaProductos = $producto->ordenar($_GET["orden"]);
}elseif (isset($_GET["orden"])) {
 $listaProductos = $producto->ordenar($_GET["orden"]);
}elseif (isset($_GET["orden"])) {
 $listaProductos = $producto->ordenar($_GET["orden"]);
}else {
    $listaProductos = $producto->listarProductos();
    $mensajeFiltrado = "Todos los productos";
}


$listaCategorias = $producto->listarColecciones();
$listaTiposProductos = $producto->listarTiposPrendas();
$listaColores = $producto->listaColores();

$precioMax = $producto->obtenerPrecioMinMax("MAX");
$precioMin = $producto->obtenerPrecioMinMax("MIN");


include './includes/header.php';
?>

<main class="container my-5 py-5 mt-5">

    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="display-5 fw-bold text-uppercase" style="letter-spacing: 4px;">Catálogo</h1>
            <h2><?php echo $mensajeFiltrado ?></h2>
            <p class="text-muted">Descubre todas nuestras colecciones</p>
        </div>
    </div>

    <div class="row">
        <aside class="col-lg-3 d-none d-lg-block mb-4">
            <div class="sticky-top" style="top: 100px; z-index: 1;">

                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                    <h5 class="fw-bold text-uppercase m-0">Filtros</h5>
                    <a href="catalogo.php" class="text-muted small text-decoration-underline">Limpiar</a>
                </div>

                <div class="accordion accordion-flush" id="acordeonFiltros">

                    <div class="accordion accordion-flush" id="acordeonFiltros">

                        <div class="accordion-item bg-transparent border-bottom">
                            <h2 class="accordion-header">
                                <button class="accordion-button bg-transparent px-0 fw-bold text-uppercase shadow-none" style="font-size: 0.9rem;" type="button" data-bs-toggle="collapse" data-bs-target="#filtroOrdenar">
                                    Ordenar por
                                </button>
                            </h2>
                            <div id="filtroOrdenar" class="accordion-collapse collapse show" data-bs-parent="#acordeonFiltros">
                                <div class="accordion-body px-0 py-2">
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-2"><a href="?orden=fechaDesc" class="text-muted nav-filtro">Fecha: más reciente - más antiguo</a></li>
                                        <li class="mb-2"><a href="?orden=fechaAsc" class="text-muted nav-filtro">Fecha: más antiguo - más reciente</a></li>
                                        <li class="mb-2"><a href="?orden=precioAsc" class="text-muted nav-filtro">Precio: Menor a Mayor</a></li>
                                        <li class="mb-2"><a href="?orden=precioDesc" class="text-muted nav-filtro">Precio: Mayor a Menor</a></li>
                                        <li class="mb-2"><a href="?orden=nombreAsc" class="text-muted nav-filtro">Alfabéticamente: A - Z</a></li>
                                        <li class="mb-2"><a href="?orden=nombreDesc" class="text-muted nav-filtro">Alfabéticamente: Z - A</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item bg-transparent border-bottom">
                           

                                <div class="accordion-item bg-transparent border-bottom">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button bg-transparent px-0 fw-bold text-uppercase shadow-none" style="font-size: 0.9rem;" type="button" data-bs-toggle="collapse" data-bs-target="#filtroGenero">
                                            Género
                                        </button>
                                    </h2>
                                    <div id="filtroGenero" class="accordion-collapse collapse show" data-bs-parent="#acordeonFiltros">
                                        <div class="accordion-body px-0 py-2">
                                            <ul class="list-unstyled mb-0">
                                                <li class="mb-2"><a href="?genero=1" class="text-muted nav-filtro">Hombre</a></li>
                                                <li class="mb-2"><a href="?genero=2" class="text-muted nav-filtro">Mujer</a></li>
                                                <li class="mb-2"><a href="?genero=3" class="text-muted nav-filtro">Unisex</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item bg-transparent border-bottom">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed bg-transparent px-0 fw-bold text-uppercase shadow-none" style="font-size: 0.9rem;" type="button" data-bs-toggle="collapse" data-bs-target="#filtroColeccion">
                                            Colección
                                        </button>
                                    </h2>
                                    <div id="filtroColeccion" class="accordion-collapse collapse" data-bs-parent="#acordeonFiltros">
                                        <div class="accordion-body px-0 py-2">
                                            <ul class="list-unstyled mb-0">
                                                <?php
                                                foreach ($listaCategorias as $categoria) {
                                                ?>
                                                    <li class="mb-2"><a href="?coleccion=<?php echo $categoria['id']; ?>" class="text-muted nav-filtro"><?php echo $categoria["nombre"] ?></a></li>
                                                <?php
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item bg-transparent border-bottom">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed bg-transparent px-0 fw-bold text-uppercase shadow-none" style="font-size: 0.9rem;" type="button" data-bs-toggle="collapse" data-bs-target="#filtroCategoria">
                                            Tipo de prenda
                                        </button>
                                    </h2>
                                    <div id="filtroCategoria" class="accordion-collapse collapse" data-bs-parent="#acordeonFiltros">
                                        <div class="accordion-body px-0 py-2">
                                            <ul class="list-unstyled mb-0">
                                                <?php
                                                foreach ($listaTiposProductos as $producto) {
                                                ?>
                                                    <li class="mb-2"><a href="?tipo=<?php echo $producto["id"] ?>" class="text-muted nav-filtro"><?php echo $producto["nombre"] ?></a></li>
                                                <?php
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item bg-transparent border-bottom">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed bg-transparent px-0 fw-bold text-uppercase shadow-none" style="font-size: 0.9rem;" type="button" data-bs-toggle="collapse" data-bs-target="#filtroTalla">
                                            Talla
                                        </button>
                                    </h2>
                                    <div id="filtroTalla" class="accordion-collapse collapse" data-bs-parent="#acordeonFiltros">
                                        <div class="accordion-body px-0 py-2">
                                            <div class="d-flex flex-wrap gap-2">
                                                <a href="?talla=S" class="border text-muted text-decoration-none px-3 py-1 nav-filtro">S</a>
                                                <a href="?talla=M" class="border text-muted text-decoration-none px-3 py-1 nav-filtro">M</a>
                                                <a href="?talla=L" class="border text-muted text-decoration-none px-3 py-1 nav-filtro">L</a>
                                                <a href="?talla=XL" class="border text-muted text-decoration-none px-3 py-1 nav-filtro">XL</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item bg-transparent border-bottom">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed bg-transparent px-0 fw-bold text-uppercase shadow-none" style="font-size: 0.9rem;" type="button" data-bs-toggle="collapse" data-bs-target="#filtroColor">
                                            Color
                                        </button>
                                    </h2>
                                    <div id="filtroColor" class="accordion-collapse collapse" data-bs-parent="#acordeonFiltros">
                                        <div class="accordion-body px-0 py-2">
                                            <div class="d-flex flex-wrap gap-2">

                                                <?php

                                                foreach ($listaColores as $color) {



                                                ?>
                                                    <a href="?color=<?php echo $color["nombre"] ?>" class="color-swatch border border-dark" style="background-color: <?php echo $color["valor_hexadecimal"] ?>;" title="<?php echo $color["nombre"] ?>"></a>

                                                <?php
                                                }
                                                ?>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item bg-transparent border-bottom">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed bg-transparent px-0 fw-bold text-uppercase shadow-none" style="font-size: 0.9rem;" type="button" data-bs-toggle="collapse" data-bs-target="#filtroPrecio">
                                            Precio
                                        </button>
                                    </h2>
                                    <div id="filtroPrecio" class="accordion-collapse collapse" data-bs-parent="#acordeonFiltros">
                                        <div class="accordion-body px-0 py-3">
                                            <div class="range-slider-container position-relative mb-3 mt-4">
                                                <div class="slider-track"></div>
                                                <input type="range" min="<?php echo $precioMin; ?>" max="<?php echo $precioMax; ?>" value="<?php echo $precioMin; ?>" id="slider-min" class="custom-range">
                                                <input type="range" min="<?php echo $precioMin; ?>" max="<?php echo $precioMax; ?>" value="<?php echo $precioMax; ?>" id="slider-max" class="custom-range">
                                            </div>

                                            <div class="d-flex justify-content-between text-muted small fw-bold mb-3">
                                                <span>Min: <span id="precio-min-val"><?php echo $precioMin ?></span>€</span>
                                                <span>Max: <span id="precio-max-val"><?php echo $precioMax ?></span>€</span>
                                            </div>

                                            <button class="btn btn-dark w-100 btn-sm text-uppercase" onclick="aplicarFiltroPrecio()">Aplicar Filtro</button>

                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
        </aside>

        <section class="col-lg-9">

            <div class="row g-4">

                <?php
                if (!empty($listaProductos)) {
                    foreach ($listaProductos as $prenda) {
                        $listaImagenes = $imagen->listarImagenes($prenda["id"]);
                        $fotoHover = count($listaImagenes) > 1 ? $listaImagenes[1]["url_imagen"] : $prenda["url_imagen"];
                ?>

                        <div class="col-6 col-md-4">
                            <div class="card product-card border-0 bg-transparent h-100 position-relative">

                                <button type="button" class="btn btn-favorito position-absolute top-0 end-0 m-2" style="z-index: 10;" onclick="this.querySelector('i').classList.toggle('bi-heart'); this.querySelector('i').classList.toggle('bi-heart-fill');">
                                    <i class="bi bi-heart"></i>
                                </button>
                                <a href="fichaProducto.php?idPrenda=<?php echo $prenda["id"] ?>">
                                    <div class="img-wrapper position-relative">
                                        <img src="<?php echo $prenda["url_imagen"]; ?>" class="card-img-top img-principal" alt="Prenda">

                                        <img src="<?php echo $fotoHover; ?>" class="card-img-top img-hover position-absolute top-0 start-0 w-100 h-100" alt="Prenda Hover">
                                    </div>

                                    <div class="card-body text-center px-0">
                                        <h5 class="card-title text-uppercase fw-bold fs-6 mt-2 mb-1"><?php echo $prenda["nombre"] ?></h5>
                                        <p class="card-text"><?php echo $prenda["precio"] ?> €</p>
                                    </div>
                                </a>
                            </div>
                        </div>

                <?php
                    }
                } else {
                    echo "<p class='text-center'>No hay productos disponibles en este momento.</p>";
                }
                ?>

            </div>

        </section>
    </div>
</main>

<?php include './includes/footer.php'; ?>