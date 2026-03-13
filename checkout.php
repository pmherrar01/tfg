<?php
// 1. Cargamos el controlador que has programado tú (el cerebro)
require_once 'controllers/checkoutController.php';

// 2. Cargamos la cabecera
include './includes/header.php';
?>

<main class="container my-5 py-5 mt-5" style="min-height: 60vh;">
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="display-6 fw-bold text-uppercase" style="letter-spacing: 2px;">Confirmar Pedido</h1>
            <p class="text-muted">Revisa tus datos de envío y finaliza la compra</p>
        </div>
    </div>

    <div class="row g-5">
        <div class="col-lg-7">
            <h4 class="fw-bold text-uppercase mb-4 border-bottom pb-2">1. Dirección de Envío</h4>

            <?php
            // COMPROBACIÓN VITAL: ¿Están vacíos la dirección o la ciudad?
            if (empty($datosComprador['direccion']) || empty($datosComprador['ciudad'])):
            ?>
                <div class="border border-dark border-3 p-4 mb-4 bg-transparent">
                    <h5 class="fw-bold text-uppercase mb-1" style="letter-spacing: 1px;">Dirección no válida</h5>
                    <p class="text-uppercase small fw-bold text-muted mb-4" style="letter-spacing: 0.5px;">Acción requerida: Faltan datos postales para poder procesar el envío.</p>
                    <a href="perfil.php" class="btn btn-outline-dark rounded-0 px-4 py-3 text-uppercase fw-bold border-2 w-100" style="letter-spacing: 2px;">
                        Actualizar Perfil
                    </a>
                </div>

            <?php else: ?>
                <div class="card border-0 bg-light rounded-0 p-4 shadow-sm">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h5 class="fw-bold m-0 text-uppercase"><?php echo $datosComprador['nombre'] . ' ' . $datosComprador['apellidos']; ?></h5>
                        <a href="perfil.php" class="text-muted small text-decoration-underline"><i class="bi bi-pencil me-1"></i>Editar</a>
                    </div>

                    <p class="mb-1 text-muted"><i class="bi bi-geo-alt me-2"></i><strong>Dirección:</strong> <?php echo $datosComprador['direccion']; ?></p>
                    <p class="mb-1 text-muted"><i class="bi bi-building me-2"></i><strong>Ciudad:</strong> <?php echo $datosComprador['ciudad']; ?></p>
                    <p class="mb-1 text-muted"><i class="bi bi-mailbox me-2"></i><strong>C.P.:</strong> <?php echo $datosComprador['codigo_postal'] ?? 'No especificado'; ?></p>
                    <p class="mb-0 text-muted"><i class="bi bi-telephone me-2"></i><strong>Teléfono:</strong> <?php echo $datosComprador['telefono'] ?? 'No especificado'; ?></p>
                </div>
            <?php endif; ?>

            <h4 class="fw-bold text-uppercase mb-4 border-bottom pb-2 mt-5">2. Método de Pago</h4>
            <div class="card border border-dark border-2 rounded-0 p-3 bg-white mb-3">
                <div class="form-check d-flex align-items-center">
                    <input class="form-check-input me-3" type="radio" name="metodo_pago" id="pago_tarjeta" checked style="transform: scale(1.2);">
                    <label class="form-check-label fw-bold w-100 d-flex justify-content-between align-items-center" for="pago_tarjeta">
                        <span>Tarjeta de Crédito / Débito</span>
                        <i class="bi bi-credit-card-2-back fs-4"></i>
                    </label>
                </div>
            </div>
            <p class="text-muted small"><i class="bi bi-shield-lock me-1"></i> Pago seguro.</p>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-0 p-4 bg-light sticky-top" style="top: 100px;">
                <h4 class="fw-bold text-uppercase mb-4">Resumen del Pedido</h4>

                <div class="border-bottom pb-3 mb-3" style="max-height: 250px; overflow-y: auto;">
                    <?php
                    $totalCheckout = 0;
                    if (isset($_SESSION['carrito'])):
                        // Recorremos el carrito para mostrar los datos rápidos (asumo que tienes el modelo instanciado en el controlador)
                        foreach ($_SESSION['carrito'] as $item):
                            $producto = $productoModel->obtenerProducto($item['idPrenda']);
                            $subtotalItem = $producto['precio'] * $item['cantidad'];
                            $totalCheckout += $subtotalItem;
                    ?>
                            <div class="d-flex justify-content-between mb-2 small">
                                <span class="text-truncate pe-2" style="max-width: 250px;">
                                    <?php echo $item['cantidad']; ?>x <?php echo $producto['nombre']; ?> (Talla: <?php echo $item['talla']; ?>)
                                </span>
                                <span class="text-nowrap fw-bold"><?php echo number_format($subtotalItem, 2); ?> €</span>
                            </div>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </div>

                <div class="d-flex justify-content-between mb-3 text-muted">
                    <span>Subtotal</span>
                    <span><?php echo number_format($totalCheckout, 2); ?> €</span>
                </div>
                <div class="d-flex justify-content-between mb-3 text-muted border-bottom pb-3">
                    <span>Gastos de envío</span>
                    <span class="text-success fw-bold">GRATIS</span>
                </div>

                <div class="d-flex justify-content-between mb-4 mt-3">
                    <span class="fw-bold text-uppercase fs-5">Total a Pagar</span>
                    <span class="fw-bold fs-3"><?php echo number_format($totalCheckout, 2); ?> €</span>
                </div>

                <form action="controllers/pagoController.php" method="POST">
                    <input type="hidden" name="totalPedido" value="<?php echo $totalCheckout; ?>">

                    <?php
                    $btnDisabled = (empty($datosComprador['direccion']) || empty($datosComprador['ciudad'])) ? 'disabled' : '';
                    ?>

                    <button type="submit" class="btn btn-dark rounded-0 py-3 text-uppercase fw-bold w-100 ls-1" <?php echo $btnDisabled; ?>>
                        Confirmar y Pagar
                    </button>

                    <?php if ($btnDisabled): ?>
                        <div class="text-danger small mt-2 text-center fw-bold">
                            Debes completar tu dirección de envío para poder pagar.
                        </div>
                    <?php endif; ?>
                </form>

            </div>
        </div>
    </div>
</main>

<?php include './includes/footer.php'; ?>