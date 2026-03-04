<?php

require_once "./models/producto.php";
require_once "./models/imagen.php";
require_once "./config/db.php";

$db = new Database();
$producto = new Producto($db->conectar());
$imagen = new Imagen($db->conectar());

$idPrenda = isset($_GET["idPrenda"]) ? $_GET["idPrenda"] : 0;

$datosPrenda = $producto->obtenerProducto($idPrenda);
$galeria = $imagen->listarImagenes($idPrenda);
$listaTallas = $producto->obtenerTallas($idPrenda);

$cont = 0;

include './includes/header.php';

?>

<main class="container my-5 py-5 mt-5">
    <div class="row">

        <div class="col-md-6 mb-4 mb-md-0">

            <div class="d-flex gap-3 h-100">

                <div class="d-flex flex-column gap-2 overflow-y-auto pe-1" style="width: 85px; max-height: 80vh;">
                    <?php
                    $cont = 0;
                    foreach ($galeria as $img) {
                        $claseActiva = ($cont == 0) ? 'borde-activo' : '';
                    ?>
                        <img src="<?php echo $img['url_imagen']; ?>"
                            class="miniatura-galeria <?php echo $claseActiva; ?>"
                            style="width: 100%; height: 110px; object-fit: cover; cursor: pointer; flex-shrink: 0;"
                            onclick="cambiarFoto(this, '<?php echo $img['url_imagen']; ?>')"
                            alt="Miniatura">
                    <?php
                        $cont++;
                    }
                    ?>
                </div>

                <div class="position-relative flex-grow-1 bg-light overflow-hidden">

                    <img id="imagenPrincipal" src="<?php echo $galeria[0]['url_imagen']; ?>" class="w-100 h-100" style="object-fit: cover; transition: opacity 0.3s ease-in-out;" alt="Prenda principal">

                    <button class="btn position-absolute top-50 start-0 translate-middle-y ms-2 bg-white rounded-circle shadow-sm" style="width: 40px; height: 40px; z-index: 10;" onclick="cambiarConFlechas('prev')">
                        <i class="bi bi-chevron-left"></i>
                    </button>

                    <button class="btn position-absolute top-50 end-0 translate-middle-y me-2 bg-white rounded-circle shadow-sm" style="width: 40px; height: 40px; z-index: 10;" onclick="cambiarConFlechas('next')">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>

            </div>
        </div>

        <div class="col-md-6 ps-md-5 d-flex flex-column justify-content-center">

            <h1 class="display-5 fw-bold text-uppercase mb-2"><?php echo $datosPrenda["nombre"] ?> </h1>
            <p class="fs-3 fw-light mb-4"> <?php echo $datosPrenda["precio"] ?>€</p>

            <div class="mb-5">
                <p class="text-muted text-uppercase" style="letter-spacing: 2px; font-size: 0.85rem;">Descripción</p>
                <p class="fs-6" style="line-height: 1.8;"><?php echo $datosPrenda["descripcion"] ?></p>
            </div>

            <form action="#" method="POST" class="mt-auto">

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label for="talla" class="form-label text-uppercase m-0" style="letter-spacing: 2px; font-size: 0.85rem;">Talla</label>
                        <a href="#" class="text-muted text-decoration-underline" style="font-size: 0.75rem;">Guía de tallas</a>
                    </div>
                    <select class="form-select border-dark rounded-0 py-2" id="talla" name="talla" required>
                        <option value="" selected disabled>Selecciona tu talla</option>
                        <?php

                        foreach ($listaTallas as $talla) {

                            if ($talla["stock"] == 0) {
                                echo "<option value='" . $talla["talla"] . "' disabled>" . $talla["talla"] . " (Agotado)</option>";
                            } elseif ($talla["stock"] <= 10) {
                                echo "<option value='" . $talla["talla"] . "'>" . $talla["talla"] . " - ¡Rápido, quedan pocas!</option>";
                            } else {
                                echo "<option value='" . $talla["talla"] . "'>" . $talla["talla"] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-dark w-100 py-3 rounded-0 text-uppercase fw-bold" style="letter-spacing: 2px; transition: all 0.3s ease;">
                    Añadir al Carrito
                </button>
            </form>

        </div>
    </div>
</main>
<?php

include './includes/footer.php';

?>