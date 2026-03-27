<?php

require_once 'controllers/segundaManoController.php';

include './includes/header.php';

?>

<main class="container my-5 py-5 min-vh-100">

    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold text-uppercase" style="letter-spacing: 2px;">Segunda Mano</h1>
        <p class="text-muted fs-5">Dale una segunda vida a tu ropa o encuentra joyas vintage únicas.</p>
    </div>

    <div class="text-center mb-5">
        <button type="button" class="btn btn-principal btn-lg text-uppercase fw-bold px-5 py-3 rounded-0" data-bs-toggle="modal" data-bs-target="#modalSubirPrenda" style="letter-spacing: 1px;">
            Vender mi prenda <i class="bi bi-tag-fill ms-2"></i>
        </button>
    </div>

    <div class="modal fade" id="modalSubirPrenda" tabindex="-1" aria-labelledby="modalSubirPrendaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-0 border-0 shadow">

                <div class="modal-header border-bottom-0 pb-0 mt-3 px-4">
                    <h5 class="modal-title fw-bold text-uppercase fs-4" id="modalSubirPrendaLabel">Detalles de la prenda</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body px-4 pb-4">
                    <form action="controllers/subirPrendaSegundaManoController.php" method="POST" enctype="multipart/form-data">

                        <div class="mb-3">
                            <label for="nombrePrenda" class="form-label fw-bold small text-uppercase text-muted">Título</label>
                            <input type="text" class="form-control rounded-0 p-2" id="nombrePrenda" name="nombrePrenda" required>
                        </div>

                        <div class="row">
                            <div class="col-6 mb-3">
                                <label for="precioPrenda" class="form-label fw-bold small text-uppercase text-muted">Precio (€)</label>
                                <input type="number" step="0.01" class="form-control rounded-0 p-2" id="precioPrenda" name="precioPrenda" placeholder="0.00" required>
                            </div>

                            <div class="col-6 mb-3">
                                <label for="tallaPrenda" class="form-label fw-bold small text-uppercase text-muted">Talla</label>
                                <select class="form-select rounded-0 p-2" id="tallaPrenda" name="tallaPrenda" required>
                                    <option value="" selected disabled>Elegir...</option>
                                    <?php foreach ($listaTallas as $talla) { ?>
                                        <option value="<?php echo $talla['talla']; ?>"><?php echo $talla['talla']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="colorPrenda" class="form-label fw-bold small text-uppercase text-muted">Color</label>
                            <select class="form-select rounded-0 p-2" id="colorPrenda" name="colorPrenda" required>
                                <option value="" selected disabled>Elegir...</option>
                                <?php foreach ($listaColores as $color) { ?>

                                    <option value="<?php echo $color['id']; ?>"><?php echo $color['nombre']; ?></option>

                                <?php } ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="colorPrenda" class="form-label fw-bold small text-uppercase text-muted">Tipo prenda</label>
                            <select class="form-select rounded-0 p-2" id="tipoPrenda" name="tipoPrenda" required>
                                <option value="" selected disabled>Elegir...</option>
                                <?php foreach ($listaTipoPrenda as $tipoPrendda) { ?>

                                    <option value="<?php echo $tipoPrendda['id']; ?>"><?php echo $tipoPrendda['nombre']; ?></option>

                                <?php } ?>
                            </select>
                        </div>

                        <div class="mb-4 mt-2">
                            <label for="fotoPrenda" class="form-label fw-bold small text-uppercase text-muted">Foto del artículo</label>
                            <input class="form-control rounded-0" type="file" id="fotoPrenda" name="foto" accept="image/*" required>
                            <div class="form-text" style="font-size: 0.75rem;">Sube una foto clara y con buena iluminación.</div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-principal rounded-0 py-3 text-uppercase fw-bold" style="letter-spacing: 1px;">
                                Subir prenda
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>


</main>

<?php
include './includes/footer.php';
?>