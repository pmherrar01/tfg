<?php

require_once 'includes/auth.php';
require_once 'controllers/perfilController.php';

$seccion = isset($_GET['seccion']) ? $_GET['seccion'] : 'datos';

include './includes/header.php';
?>

<main class="container my-5 py-5 mt-5">
    <div class="row">

        <aside class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm rounded-0">
                <div class="card-body p-0">
                    <div class="p-4 border-bottom bg-light">
                        <h5 class="fw-bold mb-1 text-uppercase">Mi Cuenta</h5>
                        <p class="text-muted small mb-0"><?php echo isset($datosUsu['email']) ? $datosUsu['email'] : ''; ?></p>
                    </div>
                    <div class="list-group list-group-flush rounded-0">
                        <a href="perfil.php?seccion=datos" class="list-group-item list-group-item-action p-3 fw-bold <?php echo $seccion == 'datos' ? 'bg-dark text-white' : 'text-muted'; ?>">Mis Datos</a>
                        <a href="perfil.php?seccion=pedidos" class="list-group-item list-group-item-action p-3 fw-bold <?php echo $seccion == 'pedidos' ? 'bg-dark text-white' : 'text-muted'; ?>">Mis Pedidos</a>
                        <a href="perfil.php?seccion=favoritos" class="list-group-item list-group-item-action p-3 fw-bold <?php echo $seccion == 'favoritos' ? 'bg-dark text-white' : 'text-muted'; ?>">Mis Favoritos</a>
                        <a href="perfil.php?seccion=puntos" class="list-group-item list-group-item-action p-3 fw-bold d-flex justify-content-between align-items-center <?php echo $seccion == 'puntos' ? 'bg-dark text-white' : 'text-muted'; ?>">
                            Puntos de Fidelidad
                            <span class="badge bg-success rounded-pill"><?php echo isset($datosUsu['puntos_fidelidad']) ? $datosUsu['puntos_fidelidad'] : '0'; ?> pts</span>
                        </a>
                        <a href="controllers/usuarioController.php?accion=logout" class="list-group-item list-group-item-action p-3 text-danger fw-bold mt-2 border-top">Cerrar Sesión</a>
                    </div>
                </div>
            </div>
        </aside>

        <section class="col-lg-9">

            <?php if ($seccion == 'datos') { ?>

                <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'perfil_actualizado'): ?>
                    <div class="alert alert-success rounded-0 text-uppercase fw-bold text-center mb-4">
                        <i class="bi bi-check-circle-fill me-2"></i> Datos actualizados correctamente
                    </div>
                <?php endif; ?>

                <div class="card border-0 shadow-sm rounded-0 p-4">
                    <h3 class="fw-bold text-uppercase mb-4">Datos Personales</h3>
                    <form action="controllers/perfilController.php" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small fw-bold text-uppercase">Nombre</label>
                                <input type="text" class="form-control rounded-0" name="nombre" value="<?php echo isset($datosUsu['nombre']) ? $datosUsu['nombre'] : ''; ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small fw-bold text-uppercase">Apellidos</label>
                                <input type="text" class="form-control rounded-0" name="apellidos" value="<?php echo isset($datosUsu['apellidos']) ? $datosUsu['apellidos'] : ''; ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small fw-bold text-uppercase">Email (No se puede cambiar)</label>
                                <input type="email" class="form-control rounded-0 text-muted" value="<?php echo isset($datosUsu['email']) ? $datosUsu['email'] : ''; ?>" readonly>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label text-muted small fw-bold text-uppercase">Teléfono</label>
                                <input type="text" class="form-control rounded-0" name="telefono" value="<?php echo isset($datosUsu['telefono']) ? $datosUsu['telefono'] : ''; ?>">
                            </div>
                        </div>

                        <h3 class="fw-bold text-uppercase mb-4 mt-3 border-top pt-4">Dirección de Envío</h3>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label text-muted small fw-bold text-uppercase">Dirección Completa (Calle, número, piso)</label>
                                <input type="text" class="form-control rounded-0" name="direccion" value="<?php echo isset($datosUsu['direccion']) ? $datosUsu['direccion'] : ''; ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label text-muted small fw-bold text-uppercase">Ciudad</label>
                                <input type="text" class="form-control rounded-0" name="ciudad" value="<?php echo isset($datosUsu['ciudad']) ? $datosUsu['ciudad'] : ''; ?>">
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label text-muted small fw-bold text-uppercase">Código Postal</label>
                                <input type="text" class="form-control rounded-0" name="codigoPostal" value="<?php echo isset($datosUsu['codigo_postal']) ? $datosUsu['codigo_postal'] : ''; ?>">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-dark rounded-0 px-5 py-3 text-uppercase fw-bold ls-1 w-100">Guardar Cambios</button>
                    </form>
                </div>


            <?php } elseif ($seccion == 'pedidos') { ?>

                <?php if (empty($listaPedidos)) { ?>
                    <div class="card border-0 shadow-sm rounded-0 p-5 text-center h-100 d-flex justify-content-center align-items-center">
                        <div>
                            <i class="bi bi-box-seam display-1 text-muted mb-3 d-block"></i>
                            <h3 class="fw-bold text-uppercase">Mis Pedidos</h3>
                            <p class="text-muted fs-5">Todavía no has realizado ningún pedido.<br>¡Ve al catálogo a cazar tu próxima prenda favorita!</p>
                            <a href="catalogo.php" class="btn btn-outline-dark rounded-0 px-5 py-2 text-uppercase fw-bold mt-3">Ir al Catálogo</a>
                        </div>
                    </div>

                <?php } else { ?>
                    <h3 class="fw-bold text-uppercase mb-4">Historial de Pedidos</h3>

                    <?php foreach ($listaPedidos as $pedidoTicket) { ?>

                        <div class="card border-dark border-2 rounded-0 mb-4 bg-transparent">
                            <div class="card-header border-bottom border-dark border-2 bg-transparent p-3 d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-uppercase fs-5" style="letter-spacing: 1px;">
                                    Pedido #<?php echo str_pad($pedidoTicket['id'], 5, "0", STR_PAD_LEFT); ?>
                                </span>
                                <span class="fw-bold text-muted">
                                    <?php echo date('d / m / Y', strtotime($pedidoTicket['fecha'])); ?>
                                </span>
                            </div>

                            <div class="card-body p-4">
                                <div class="row mb-4">
                                    <div class="col-6">
                                        <p class="mb-1 text-muted small text-uppercase fw-bold">Estado</p>
                                        <p class="mb-0 fw-bold fs-5 text-uppercase <?php echo ($pedidoTicket['estado'] == 'pendiente') ? 'text-warning' : 'text-success'; ?>">
                                            <?php echo $pedidoTicket['estado']; ?>
                                        </p>
                                    </div>
                                    <div class="col-6 text-end">
                                        <p class="mb-1 text-muted small text-uppercase fw-bold">Total Pagado</p>
                                        <p class="mb-0 fw-bold fs-4"><?php echo number_format($pedidoTicket['total'], 2); ?> €</p>
                                    </div>
                                </div>

                                <h6 class="fw-bold text-uppercase mb-3 border-top border-dark border-2 pt-4">Artículos del pedido:</h6>

                                <ul class="list-unstyled mb-0">
                                    <?php
                                    $lineas = $pedido->obtenerInfoPedido($pedidoTicket["id"]);

                                    if (!empty($lineas)) {
                                        foreach ($lineas as $linea) {
                                    ?>
                                            <li class="d-flex justify-content-between align-items-center text-muted small mb-3 text-uppercase fw-bold">

                                                <div class="d-flex align-items-center">

                                                    <?php
                                                    $fotoMuestra = !empty($linea['url_imagen']) ? $linea['url_imagen'] : 'public/img/fondo.jpg';
                                                    ?>
                                                    <img src="<?php echo $fotoMuestra; ?>"
                                                        alt="<?php echo $linea['producto_nombre']; ?>"
                                                        class="me-3 border border-dark border-1"
                                                        style="width: 55px; height: 55px; object-fit: cover;">

                                                    <span>
                                                        <span class="text-dark me-2"><?php echo $linea['cantidad']; ?>x</span>
                                                        <?php echo $linea['producto_nombre']; ?>
                                                        <span class="d-block mt-1" style="font-size: 0.85rem;">
                                                            (Talla: <?php echo $linea['talla'] ?? 'N/A'; ?> - Color: <?php echo $linea['color_nombre'] ?? 'N/A'; ?>)
                                                        </span>
                                                    </span>
                                                </div>

                                                <span class="text-dark fs-6"><?php echo number_format($linea['precio_unitario'] * $linea['cantidad'], 2); ?> €</span>

                                            </li>
                                    <?php
                                        };
                                    };
                                    ?>
                                </ul>

                            </div>
                        </div>
                    <?php };  ?>
                <?php };  ?>


            <?php } elseif ($seccion == 'puntos') { ?>

                <div class="card border-0 shadow-sm rounded-0 p-5 text-center h-100 d-flex justify-content-center align-items-center bg-light">
                    <div>
                        <i class="bi bi-star-fill text-warning display-1 mb-3 d-block"></i>
                        <h3 class="fw-bold text-uppercase">Tu Saldo Actual</h3>
                        <h1 class="display-2 fw-bold my-3 text-dark"><?php echo isset($datosUsu['puntos_fidelidad']) ? $datosUsu['puntos_fidelidad'] : '0'; ?> <span class="fs-4 text-muted">pts</span></h1>
                        <p class="text-muted fs-5">Acumula puntos con cada compra y canjéalos por descuentos exclusivos en futuros pedidos.</p>
                    </div>
                </div>

            <?php } elseif ($seccion == 'favoritos') { ?>

                <h3 class="fw-bold text-uppercase mb-4">Mis Favoritos</h3>

                <?php if (empty($listaFavoritos)) { ?>
                    <div class="card border-0 shadow-sm rounded-0 p-5 text-center h-100 d-flex justify-content-center align-items-center bg-light">
                        <div>
                            <i class="bi bi-heart text-muted display-1 mb-3 d-block"></i>
                            <h4 class="fw-bold text-uppercase">Tu lista está vacía</h4>
                            <p class="text-muted">Aún no has guardado ninguna prenda. ¡Descubre nuestro catálogo!</p>
                            <a href="catalogo.php" class="btn btn-outline-dark rounded-0 px-5 py-2 text-uppercase fw-bold mt-3">Ir al Catálogo</a>
                        </div>
                    </div>
                <?php } else { ?>
                    
                    <div class="row g-4">
                        
                        <?php foreach ($listaFavoritos as $prenda) { ?>
                            <div class="col-6 col-md-4 col-lg-3 mb-4">
                                <div class="card product-card border-0 bg-transparent h-100 position-relative d-flex flex-column">

                                    <a href="fichaProducto.php?idPrenda=<?= $prenda['id'] ?>&color=<?= $prenda['color_id'] ?>" class="text-decoration-none text-dark d-block flex-grow-1">

                                        <div class="img-wrapper position-relative overflow-hidden">
                                            <img src="<?= !empty($prenda['url_imagen']) ? $prenda['url_imagen'] : 'public/img/fondo.jpg' ?>" class="card-img-top rounded-0" alt="<?= $prenda['nombre'] ?>" style="height: 380px; object-fit: cover;">

                                            <div id="overlay-tallas-<?= $prenda['id'] ?>" class="overlay-tallas d-none position-absolute bottom-0 start-0 w-100 bg-white bg-opacity-75 p-2 text-center" style="z-index: 20;" onclick="event.preventDefault();">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="small fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 0.7rem;">Talla</span>
                                                    <button type="button" class="btn-close" style="font-size: 0.6rem;" onclick="cerrarOverlayTallas(event, <?= $prenda['id'] ?>)"></button>
                                                </div>
                                                <div id="contenedor-botones-<?= $prenda['id'] ?>" class="d-flex justify-content-center flex-wrap gap-1">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card-body text-center px-0 pb-1 mt-2">
                                            <h5 class="card-title text-uppercase fw-bold fs-6 mb-1 text-truncate"><?= $prenda['nombre'] ?></h5>
                                            <p class="card-text mb-2"><?= $prenda['precio'] ?> €</p>
                                        </div>
                                    </a>

                                    <div class="d-flex align-items-center justify-content-between gap-1 mt-auto px-1 pt-2">
                                        <button type="button" class="btn btn-principal rounded-0 flex-grow-1 text-uppercase fw-bold py-1 px-0"
                                            style="height: 40px; font-size: 0.75rem; letter-spacing: 1px;"
                                            onclick="abrirOverlayTallas(event, <?= $prenda['id'] ?>, <?= $prenda['color_id'] ?>)">
                                            Añadir
                                        </button>

                                        <button type="button" class="btn btn-toggle-favorito btn-favorito-custom btn-favorito-std d-flex justify-content-center align-items-center rounded-0 m-0"
                                            style="height: 40px; width: 40px; padding: 0;"
                                            data-id="<?= $prenda['id'] ?>"
                                            data-color="<?= $prenda['color_id'] ?>">
                                            <i class="bi bi-heart-fill"></i>
                                        </button>
                                    </div>

                                </div>
                            </div>
                        <?php } ?>
                    
                    </div> <?php }  ?>

            <?php }; ?>
    </div>


</section>
</div>
</main>

<?php include './includes/footer.php'; ?>