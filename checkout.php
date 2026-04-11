<?php
require_once 'controllers/checkoutController.php';

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
            <h4 class="fw-bold text-uppercase mb-4 border-bottom pb-2 mt-5">2. Método de Pago</h4>
            
            <div class="card border border-dark border-2 rounded-0 p-3 bg-white mb-3" id="caja_tarjeta">
                <div class="form-check d-flex align-items-center m-0">
                    <input class="form-check-input me-3" type="radio" name="metodo_pago" id="pago_tarjeta" value="tarjeta" checked style="transform: scale(1.2);" onchange="cambiarMetodoPago()">
                    <label class="form-check-label fw-bold w-100 d-flex justify-content-between align-items-center" for="pago_tarjeta" style="cursor: pointer;">
                        <span>Tarjeta de Crédito / Débito</span>
                        <i class="bi bi-credit-card-2-back fs-4"></i>
                    </label>
                </div>
<div id="form_tarjeta" class="mt-3 pt-3 border-top">
                    
                    <div class="tarjeta-contenedor d-flex justify-content-center mb-4">
                        <div class="tarjeta-credito" id="tarjeta-credito">
                            <div class="tarjeta-cara tarjeta-frente p-3 text-white">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <i class="bi bi-sim text-warning fs-2"></i>
                                    <i class="bi bi-wifi fs-4"></i>
                                </div>
                                <h4 id="tarjeta-numero-visual" class="mb-4" style="letter-spacing: 2px;">#### #### #### ####</h4>
                                <div class="d-flex justify-content-between text-uppercase" style="font-size: 0.8rem;">
                                    <div>
                                        <small class="text-white-50 d-block">Titular</small>
                                        <span id="tarjeta-nombre-visual">NOMBRE APELLIDOS</span>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-white-50 d-block">Expira</small>
                                        <span id="tarjeta-mes-visual">MM</span>/<span id="tarjeta-anio-visual">AA</span>
                                    </div>
                                </div>
                            </div>
                            <div class="tarjeta-cara tarjeta-dorso">
                                <div class="banda-magnetica mt-4"></div>
                                <div class="p-3">
                                    <div class="bg-white text-dark text-end p-2 mb-2 rounded" style="height: 40px; line-height: 1.5;">
                                        <span id="tarjeta-cvv-visual" class="fw-bold"></span>
                                    </div>
                                    <p class="text-white-50 small m-0" style="font-size: 0.6rem;">Código de seguridad de 3 dígitos (CVV)</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold text-uppercase">Número de Tarjeta</label>
                            <input type="text" class="form-control rounded-0" id="input-numero" name="num_tarjeta" maxlength="19" placeholder="0000 0000 0000 0000" autocomplete="off">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-uppercase">Titular de la Tarjeta</label>
                            <input type="text" class="form-control rounded-0 text-uppercase" id="input-nombre" name="titular_tarjeta" maxlength="30" placeholder="Ej: PABLO PÉREZ" autocomplete="off">
                        </div>
                        <div class="col-4">
                            <label class="form-label small fw-bold text-uppercase">Mes</label>
                            <select class="form-select rounded-0" id="input-mes" name="mes_tarjeta">
                                <option value="MM" selected disabled>Mes</option>
                                <?php for($i=1; $i<=12; $i++){ printf('<option value="%02d">%02d</option>', $i, $i); } ?>
                            </select>
                        </div>
                        <div class="col-4">
                            <label class="form-label small fw-bold text-uppercase">Año</label>
                            <select class="form-select rounded-0" id="input-anio" name="anio_tarjeta">
                                <option value="AA" selected disabled>Año</option>
                                <?php $anioActual = date("y"); for($i=0; $i<10; $i++){ $a = $anioActual+$i; echo "<option value='$a'>20$a</option>"; } ?>
                            </select>
                        </div>
                        <div class="col-4">
                            <label class="form-label small fw-bold text-uppercase">CVV</label>
                            <input type="text" class="form-control rounded-0" id="input-cvv" name="cvv_tarjeta" maxlength="3" placeholder="123" autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border border-secondary border-1 rounded-0 p-3 bg-light mb-3" id="caja_bizum" style="opacity: 0.7;">
                <div class="form-check d-flex align-items-center m-0">
                    <input class="form-check-input me-3" type="radio" name="metodo_pago" id="pago_bizum" value="bizum" style="transform: scale(1.2);" onchange="cambiarMetodoPago()">
                    <label class="form-check-label fw-bold w-100 d-flex justify-content-between align-items-center" for="pago_bizum" style="cursor: pointer;">
                        <span>Pago con Bizum</span>
                        <i class="bi bi-phone fs-4"></i>
                    </label>
                </div>
                <div id="form_bizum" class="mt-3 pt-3 border-top" style="display: none;">
                    <p class="text-muted small mb-0"><i class="bi bi-shield-check text-success me-1"></i> Serás redirigido a la pasarela segura de Bizum para confirmar el pago con tu número de teléfono.</p>
                </div>
            </div>

            <p class="text-muted small mt-3"><i class="bi bi-shield-lock me-1"></i> Pago 100% seguro y encriptado.</p>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-0 p-4 bg-light sticky-top" style="top: 100px;">
                <h4 class="fw-bold text-uppercase mb-4">Resumen del Pedido</h4>

                <div class="border-bottom pb-3 mb-3" style="max-height: 250px; overflow-y: auto;">
                    <?php
                    $subtotalCheckout = 0;
                    if (isset($_SESSION['carrito'])):
                        foreach ($_SESSION['carrito'] as $item):
                            $producto = $productoModel->obtenerProducto($item['idPrenda']);
                            $subtotalItem = $producto['precio'] * $item['cantidad'];
                            $subtotalCheckout += $subtotalItem;
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

                <div class="d-flex justify-content-between mb-2 text-muted">
                    <span>Subtotal</span>
                    <span><?php echo number_format($subtotalCheckout, 2); ?> €</span>
                </div>

                <?php 
                // CÁLCULO DEL DESCUENTO
                $descuentoCheckout = 0;
                if (isset($_SESSION['descuento'])) {
                    $porcentaje = $_SESSION['descuento']['porcentaje'];
                    $descuentoCheckout = $subtotalCheckout * ($porcentaje / 100);
                }
                
                if ($descuentoCheckout > 0): ?>
                    <div class="d-flex justify-content-between mb-2 text-danger fw-bold">
                        <span>Descuento (<?= $_SESSION['descuento']['porcentaje'] ?>%)</span>
                        <span>-<?= number_format($descuentoCheckout, 2) ?> €</span>
                    </div>
                <?php endif; ?>

                <?php 
                // CÁLCULO FINAL
                $envio = (($subtotalCheckout - $descuentoCheckout) > 50) ? 0 : 4.99;
                $totalFinalCheckout = ($subtotalCheckout - $descuentoCheckout) + $envio;
                ?>

                <div class="d-flex justify-content-between mb-3 text-muted border-bottom pb-3">
                    <span>Gastos de envío</span>
                    <?php if ($envio == 0): ?>
                        <span class="text-success fw-bold">GRATIS</span>
                    <?php else: ?>
                        <span class="fw-bold"><?php echo number_format($envio, 2); ?> €</span>
                    <?php endif; ?>
                </div>

                <div class="d-flex justify-content-between mb-4 mt-3">
                    <span class="fw-bold text-uppercase fs-5">Total a Pagar</span>
                    <span class="fw-bold fs-3"><?php echo number_format($totalFinalCheckout, 2); ?> €</span>
                </div>

                <form action="controllers/pagoController.php" method="POST">
                    <input type="hidden" name="totalPedido" value="<?php echo $totalFinalCheckout; ?>">

                    <?php
                    $btnDisabled = (empty($datosComprador['direccion']) || empty($datosComprador['ciudad'])) ? 'disabled' : '';
                    ?>

                    <button type="submit" class="btn btn-dark rounded-0 py-3 text-uppercase fw-bold w-100 ls-1" <?php echo $btnDisabled; ?>>
                        Confirmar y Pagar
                    </button>

                    <?php if ($btnDisabled): ?>
                        <div class="text-danger small mt-2 text-center fw-bold">
                            Debes completar tu dirección de envío en el perfil para poder pagar.
                        </div>
                    <?php endif; ?>
                </form>

            </div>
        </div>
    </div>
</main>

<script src="public/js/checkout.js"></script>

<?php include './includes/footer.php'; ?>