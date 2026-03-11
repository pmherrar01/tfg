<?php
require_once 'controllers/carritoController.php';
include './includes/header.php';
?>

<main class="container my-5 py-5 mt-5" style="min-height: 60vh;">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-6 fw-bold text-uppercase" style="letter-spacing: 2px;">Tu Cesta</h1>
            <p class="text-muted">
                <?php 
                    $numArticulos = 0;
                    foreach($carritoDetallado as $it) { $numArticulos += $it['cantidad']; }
                    echo $numArticulos;
                ?> artículo(s) seleccionados
            </p>
        </div>
    </div>

    <?php if (empty($carritoDetallado)): ?>
        <div class="row">
            <div class="col-12 text-center py-5 bg-light border">
                <i class="bi bi-bag-x display-1 text-muted mb-3 d-block"></i>
                <h3 class="fw-bold text-uppercase">Tu cesta está vacía</h3>
                <p class="text-muted mb-4">Parece que aún no has añadido nada.</p>
                <a href="catalogo.php" class="btn btn-dark rounded-0 px-5 py-3 text-uppercase fw-bold ls-1">Explorar Catálogo</a>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-5">
            <div class="col-lg-8">
                <?php foreach ($carritoDetallado as $item): ?>
                    <div class="card border-0 border-bottom rounded-0 mb-3 pb-3">
                        <div class="row g-0 align-items-center">
                            
                            <div class="col-3 col-md-2">
                                <a href="fichaProducto.php?idPrenda=<?php echo $item['idPrenda']; ?>&color=<?php echo $item['color_id']; ?>">
                                    <img src="<?php echo $item['imagen']; ?>" class="img-fluid w-100 object-fit-cover" style="height: 120px;" alt="<?php echo $item['nombre']; ?>">
                                </a>
                            </div>
                            
                            <div class="col-7 col-md-8 px-3">
                                <div class="d-flex flex-column h-100 justify-content-center">
                                    <h5 class="fw-bold text-uppercase fs-6 mb-1">
                                        <a href="fichaProducto.php?idPrenda=<?php echo $item['idPrenda']; ?>&color=<?php echo $item['color_id']; ?>" class="text-decoration-none text-dark">
                                            <?php echo $item['nombre']; ?>
                                        </a>
                                    </h5>
                                    <p class="text-muted small mb-1">Color: <?php echo $item['color_nombre']; ?> | Talla: <?php echo $item['talla']; ?></p>
                                    
                                    <div class="d-flex align-items-center mt-2 gap-3">
                                        
                                        <div class="d-flex align-items-center border border-dark rounded-0">
                                            <a href="controllers/carritoController.php?accion=restar&indice=<?php echo $item['indice']; ?>" class="btn btn-sm btn-light rounded-0 px-2 py-0 border-0" style="background: transparent;">-</a>
                                            <span class="px-3 fw-bold border-start border-end border-dark" style="font-size: 0.9rem;"><?php echo $item['cantidad']; ?></span>
                                            <a href="controllers/carritoController.php?accion=sumar&indice=<?php echo $item['indice']; ?>" class="btn btn-sm btn-light rounded-0 px-2 py-0 border-0" style="background: transparent;">+</a>
                                        </div>

                                        <a href="controllers/carritoController.php?accion=eliminar&indice=<?php echo $item['indice']; ?>" class="text-danger small text-decoration-underline">Eliminar</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-2 col-md-2 text-end">
                                <span class="fw-bold fs-5"><?php echo number_format($item['subtotal'], 2); ?> €</span>
                                <?php if ($item['cantidad'] > 1): ?>
                                    <br><span class="text-muted small">(<?php echo $item['precio']; ?> €/u)</span>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-0 p-4 bg-light">
                    <h4 class="fw-bold text-uppercase mb-4">Resumen</h4>
                    
                    <div class="d-flex justify-content-between mb-3 text-muted">
                        <span>Subtotal</span>
                        <span><?php echo number_format($totalCarrito, 2); ?> €</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 text-muted border-bottom pb-3">
                        <span>Gastos de envío</span>
                        <span>Calculado en el siguiente paso</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-4 mt-3">
                        <span class="fw-bold text-uppercase">Total</span>
                        <span class="fw-bold fs-4"><?php echo number_format($totalCarrito, 2); ?> €</span>
                    </div>

                    <a href="#" class="btn btn-dark rounded-0 py-3 text-uppercase fw-bold w-100 ls-1">Tramitar Pedido</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</main>

<?php include './includes/footer.php'; ?>