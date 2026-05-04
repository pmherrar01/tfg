<?php
session_start();

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../models/usuario.php";
require_once __DIR__ . "/../models/pedido.php";
require_once __DIR__ . "/../models/producto.php";
require_once __DIR__ . "/../models/look.php";

if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../index.php?error=debes_iniciar_sesion");
    exit();
}

$db = new Database();
$conexion = $db->conectar();
$usu = new Usuario($conexion);
$idUsu = $_SESSION["usuario_id"];
$datosUsu = $usu->obtenerDatosUsu($idUsu);
$pedido = new Pedido($conexion);
$producto  = new Producto($conexion);
$listaProductos = $producto->listarInventarioCompleto();
$listaColeciones = $producto->listarColecciones(true);
$listaUsuarios = $usu->listarUsuarios();

if ($datosUsu["rol_id"] != 1) {
    header("Location: ../index.php?error=noAdmin");
    exit();
}

$seccion = isset($_GET['seccion']) ? $_GET['seccion'] : 'pedidos';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HERROR | Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/style.css">
</head>


<body class="admin-body">

    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow d-md-none" style="height: 60px;">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-5 text-white text-uppercase fw-bold" href="#">
            HERROR <span class="fs-6 fw-normal">Admin</span>
        </a>
        <button class="navbar-toggler position-absolute d-md-none collapsed border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Abrir menú" style="right: 15px; top: 12px;">
            <span class="navbar-toggler-icon"></span>
        </button>
    </header>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar-admin collapse shadow">
                <div class="position-sticky pt-3 pt-md-0">

                    <div class="px-4 mb-4 d-none d-md-block">
                        <h4 class="text-uppercase fw-bold tracking-tighter text-white">HERROR <span class="fs-6 fw-normal">Admin</span></h4>
                    </div>

                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link admin-nav-link <?php echo ($seccion == 'pedidos') ? 'active' : ''; ?>" href="admin.php?seccion=pedidos">
                                <i class="bi bi-bag-check"></i> Pedidos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link admin-nav-link <?php echo ($seccion == 'productos') ? 'active' : ''; ?>" href="admin.php?seccion=productos">
                                <i class="bi bi-box-seam"></i> Productos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link admin-nav-link <?php echo ($seccion == 'segundaMano') ? 'active' : ''; ?>" href="admin.php?seccion=segundaMano">
                                <i class="bi bi-box-seam"></i> Segunda mano
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link admin-nav-link <?php echo ($seccion == 'colecciones') ? 'active' : ''; ?>" href="admin.php?seccion=colecciones">
                                <i class="bi bi-collection"></i> Colecciones
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link admin-nav-link <?php echo ($seccion == 'usuarios') ? 'active' : ''; ?>" href="admin.php?seccion=usuarios">
                                <i class="bi bi-people"></i> Usuarios
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link admin-nav-link <?php echo ($seccion == 'looks') ? 'active' : ''; ?>" href="admin.php?seccion=looks">
                                <i class="bi bi-people"></i> Looks
                            </a>
                        </li>
                    </ul>

                    <hr class="mx-3 border-secondary">

                    <ul class="nav flex-column mb-2">
                        <li class="nav-item">
                            <a class="nav-link admin-nav-link text-info" href="../index.php">
                                <i class="bi bi-arrow-left-circle"></i> Volver a la Tienda
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link admin-nav-link text-danger" href="../controllers/usuarioController.php?accion=cerrar">
                                <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 admin-content">

                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                    <h1 class="h2 text-uppercase fw-bold"><?php echo ucfirst($seccion); ?></h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <span class="badge bg-dark p-2">Admin: <?php echo $_SESSION['nombre']; ?></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <?php
                        switch ($seccion) {
                            case 'pedidos':
                                $listaPedidos = $pedido->listarPedidos();
                        ?>
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h3>Gestión de Pedidos</h3>
                                    <span class="badge bg-dark fs-6">Total: <?php echo count($listaPedidos); ?> pedidos</span>
                                </div>

                                <div class="table-responsive bg-white p-3 admin-card d-none d-md-block shadow-sm">
                                    <table class="table admin-table table-hover align-middle">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>ID</th>
                                                <th>Cliente</th>
                                                <th>Fecha</th>
                                                <th>Total</th>
                                                <th>Estado Actual</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($listaPedidos as $p) { ?>
                                                <tr>
                                                    <td class="fw-bold">#<?php echo $p['id']; ?></td>
                                                    <td><?php echo $p['nombre_cliente']; ?></td>
                                                    <td><?php echo date('d/m/Y H:i', strtotime($p['fecha'])); ?></td>
                                                    <td><?php echo number_format($p['total'], 2); ?> €</td>
                                                    <td>
                                                        <form action="../controllers/adminController.php" method="POST" class="d-flex gap-2 m-0">
                                                            <input type="hidden" name="accion" value="cambiarEstadoPedido">
                                                            <input type="hidden" name="idPedido" value="<?php echo $p['id']; ?>">
                                                            <select name="nuevoEstado" class="form-select form-select-sm" style="width: auto;">
                                                                <?php
                                                                $estadosPosibles = ['pendiente', 'pagado', 'enviado', 'entregado', 'cancelado'];
                                                                foreach ($estadosPosibles as $estado) {
                                                                    $seleccionado = ($p['estado'] === $estado) ? 'selected' : '';
                                                                ?>
                                                                    <option value="<?php echo $estado; ?>" <?php echo $seleccionado; ?>><?php echo ucfirst($estado); ?></option>
                                                                <?php } ?>
                                                            </select>
                                                            <button type="submit" class="btn btn-sm btn-dark">Actualizar</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="d-md-none">
                                    <?php foreach ($listaPedidos as $p) {
                                        $colorBorde = 'border-dark';
                                        if ($p['estado'] == 'entregado') $colorBorde = 'border-success';
                                        if ($p['estado'] == 'cancelado') $colorBorde = 'border-danger';
                                        if ($p['estado'] == 'pendiente') $colorBorde = 'border-warning';
                                    ?>
                                        <div class="card mb-3 shadow-sm border-0 border-start border-4 <?php echo $colorBorde; ?>">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                                                    <span class="fw-bold fs-5">Pedido #<?php echo $p['id']; ?></span>
                                                    <span class="fw-bold fs-5"><?php echo number_format($p['total'], 2); ?> €</span>
                                                </div>
                                                <p class="mb-1"><i class="bi bi-person text-muted me-2"></i><strong><?php echo $p['nombre_cliente']; ?></strong></p>
                                                <p class="mb-3 small text-muted"><i class="bi bi-calendar me-2"></i><?php echo date('d/m/Y H:i', strtotime($p['fecha'])); ?></p>

                                                <form action="../controllers/adminController.php" method="POST" class="mt-3 bg-light p-2 rounded">
                                                    <input type="hidden" name="accion" value="cambiarEstadoPedido">
                                                    <input type="hidden" name="idPedido" value="<?php echo $p['id']; ?>">
                                                    <label class="small fw-bold text-uppercase text-muted mb-1 d-block">Cambiar Estado:</label>
                                                    <div class="input-group input-group-sm">
                                                        <select name="nuevoEstado" class="form-select border-dark">
                                                            <?php
                                                            foreach ($estadosPosibles as $estado) {
                                                                $seleccionado = ($p['estado'] === $estado) ? 'selected' : '';
                                                            ?>
                                                                <option value="<?php echo $estado; ?>" <?php echo $seleccionado; ?>><?php echo ucfirst($estado); ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <button type="submit" class="btn btn-dark fw-bold">Guardar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php
                                break;
                            case 'productos':
                                $prod = new Producto($db->conectar());

                                $productosPorPagina = 5;
                                $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
                                if ($paginaActual < 1) {
                                    $paginaActual = 1;
                                }

                                $totalProductos = $prod->contarProductosPorTipo(false);
                                $totalPaginas = ceil($totalProductos / $productosPorPagina);
                                $offset = ($paginaActual - 1) * $productosPorPagina;

                                $listaInventario = $prod->listarProductosPaginados(false, $productosPorPagina, $offset);

                                $productosAgrupados = [];
                                if (!empty($listaInventario)) {
                                    foreach ($listaInventario as $item) {
                                        $pId = $item['prenda_id'];
                                        if (!isset($productosAgrupados[$pId])) {
                                            $productosAgrupados[$pId] = [
                                                'nombre' => $item['nombre'],
                                                'precio' => $item['precio'],
                                                'rebaja' => $item['rebaja'],
                                                'activo' => $item['activo'],
                                                'coleccion_id' => $item['coleccion_id'],
                                                'es_segunda_mano' => $item['es_segunda_mano'],
                                                'nombre_dueno' => $item['nombre_dueno'],
                                                'variantes' => []
                                            ];
                                        }
                                        if ($item['color_id']) {
                                            $productosAgrupados[$pId]['variantes'][] = [
                                                'color_id' => $item['color_id'],
                                                'nombre_color' => $item['nombre_color'],
                                                'talla' => $item['talla'],
                                                'stock' => $item['stock']
                                            ];
                                        }
                                    }
                                }
                        ?>
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div>
                                        <h3 class="fw-bold m-0 text-uppercase">Gestión de Inventario</h3>
                                        <small class="text-muted">Mostrando <?php echo count($productosAgrupados); ?> de <?php echo $totalProductos; ?> productos totales</small>
                                    </div>
                                    <button class="btn btn-admin-black px-3 py-2" type="button" data-bs-toggle="collapse" data-bs-target="#formNuevaPrenda">
                                        <i class="bi bi-plus-lg d-md-none"></i> <span class="d-none d-md-inline"><i class="bi bi-plus-lg me-2"></i> Añadir Prenda</span>
                                    </button>
                                </div>

                                <div class="collapse mb-4 mt-3" id="formNuevaPrenda">
                                    <div class="card card-body admin-card border-0 shadow-sm bg-light">
                                        <h5 class="fw-bold mb-3 text-uppercase"><i class="bi bi-box-seam me-2"></i>Subir Nueva Prenda al Catálogo</h5>
                                        <form action="../controllers/adminController.php" method="POST" enctype="multipart/form-data" class="row g-3">
                                            <input type="hidden" name="accion" value="crearPrenda">

                                            <div class="col-md-4">
                                                <label class="fw-bold small">Nombre de la Prenda:</label>
                                                <input type="text" name="nombre" class="form-control border-dark" placeholder="Ej: Camiseta Oversize Negra" required>
                                            </div>
                                            <div class="col-6 col-md-2">
                                                <label class="fw-bold small">Precio (€):</label>
                                                <input type="number" step="0.01" name="precio" class="form-control border-dark" placeholder="29.99" required>
                                            </div>
                                            <div class="col-6 col-md-3">
                                                <label class="fw-bold small">Tipo de Prenda:</label>
                                                <select name="tipo_id" class="form-select border-dark" required>
                                                    <option value="">-- Seleccionar --</option>
                                                    <?php foreach ($prod->listarTiposPrendas() as $t) { ?>
                                                        <option value="<?php echo $t['id']; ?>"><?php echo $t['nombre']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-12 col-md-3">
                                                <label class="fw-bold small">Colección:</label>
                                                <select name="coleccion_id" class="form-select border-dark">
                                                    <option value="">Ninguna</option>
                                                    <?php foreach ($listaColeciones as $c) { ?>
                                                        <option value="<?php echo $c['id']; ?>"><?php echo $c['nombre']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <div class="col-md-12">
                                                <label class="fw-bold small">Descripción del producto:</label>
                                                <textarea name="descripcion" class="form-control border-dark" rows="2" placeholder="Escribe aquí los detalles del material, estilo..."></textarea>
                                            </div>

                                            <div class="col-6 col-md-2">
                                                <label class="fw-bold small">Género:</label>
                                                <select name="genero" class="form-select border-dark" required>
                                                    <option value="1">Hombre</option>
                                                    <option value="2">Mujer</option>
                                                    <option value="3" selected>Unisex</option>
                                                </select>
                                            </div>
                                            <div class="col-6 col-md-3">
                                                <label class="fw-bold small">Color Principal:</label>
                                                <select name="color_id" class="form-select border-dark" required>
                                                    <option value="">-- Seleccionar --</option>
                                                    <?php foreach ($prod->listaColores() as $c) { ?>
                                                        <option value="<?php echo $c['id']; ?>"><?php echo $c['nombre']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <label class="fw-bold small mb-2">Stock por Tallas (Deja en 0 las que no tengas):</label>
                                                <div class="d-flex flex-wrap gap-2">
                                                    <div class="input-group input-group-sm" style="width: 110px;"><span class="input-group-text bg-dark text-white fw-bold">XS</span><input type="number" name="stock[XS]" class="form-control text-center border-dark" value="0" min="0"></div>
                                                    <div class="input-group input-group-sm" style="width: 110px;"><span class="input-group-text bg-dark text-white fw-bold">S</span><input type="number" name="stock[S]" class="form-control text-center border-dark" value="0" min="0"></div>
                                                    <div class="input-group input-group-sm" style="width: 110px;"><span class="input-group-text bg-dark text-white fw-bold">M</span><input type="number" name="stock[M]" class="form-control text-center border-dark" value="0" min="0"></div>
                                                    <div class="input-group input-group-sm" style="width: 110px;"><span class="input-group-text bg-dark text-white fw-bold">L</span><input type="number" name="stock[L]" class="form-control text-center border-dark" value="0" min="0"></div>
                                                    <div class="input-group input-group-sm" style="width: 110px;"><span class="input-group-text bg-dark text-white fw-bold">XL</span><input type="number" name="stock[XL]" class="form-control text-center border-dark" value="0" min="0"></div>
                                                    <div class="input-group input-group-sm" style="width: 110px;"><span class="input-group-text bg-dark text-white fw-bold">ÚNICA</span><input type="number" name="stock[U]" class="form-control text-center border-dark" value="0" min="0"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 mt-3">
                                                <label class="fw-bold small">Subir Foto:</label>
                                                <input type="file" name="imagenes[]" class="form-control border-dark" accept="image/*" multiple required>
                                            </div>

                                            <div class="col-12 text-end mt-4">
                                                <button type="submit" class="btn btn-dark fw-bold px-4 shadow w-100 w-md-auto"><i class="bi bi-cloud-arrow-up me-2"></i>Publicar Prenda</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <?php
                                $modalesHTML = '';
                                ?>

                                <form action="../controllers/adminController.php" method="POST">
                                    <input type="hidden" name="accion" value="actualizarInventarioMasivo">
                                    <input type="hidden" name="pagina_retorno" value="<?php echo $paginaActual; ?>">

                                    <?php if (empty($productosAgrupados)) { ?>
                                        <div class="alert alert-secondary text-center py-5">No se han encontrado productos en esta página.</div>
                                    <?php } else { ?>
                                        <?php
                                        foreach ($productosAgrupados as $id => $datos) {
                                            $esSegundaMano = ($datos['es_segunda_mano'] == 1);
                                            $borderStyle = $esSegundaMano ? 'border-left: 6px solid #fd7e14;' : 'border-left: 6px solid #0dcaf0;';
                                        ?>
                                            <div class="card mb-4 border-0 shadow-sm admin-card" style="<?php echo $borderStyle; ?>">
                                                <div class="card-header bg-dark text-white py-3">
                                                    <div class="row align-items-center g-3">
                                                        <div class="col-12 col-lg-5">
                                                            <span class="text-secondary fw-bold">#<?php echo $id; ?></span>
                                                            <h5 class="d-inline m-0 fw-bold text-uppercase fs-6 ms-2"><?php echo $datos['nombre']; ?></h5>
                                                            <?php if ($esSegundaMano) { ?>
                                                                <span class="badge bg-warning text-dark fw-bold px-2 ms-2">2ª MANO</span>
                                                                <small class="text-secondary d-block mt-1">Propietario: <b><?php echo ($datos['nombre_dueno'] ?? 'User'); ?></b></small>
                                                            <?php } ?>
                                                        </div>

                                                        <?php if (!$esSegundaMano) { ?>
                                                            <div class="col-6 col-md-3 col-lg-2">
                                                                <label class="d-md-none small text-muted d-block mb-1">Colección</label>
                                                                <select name="coleccion[<?php echo $id; ?>]" class="form-select form-select-sm border-0 bg-light text-dark fw-bold w-100">
                                                                    <option value="">Sin colección</option>
                                                                    <?php
                                                                    foreach ($listaColeciones as $col) {
                                                                        $seleccionado = ($col['id'] == $datos['coleccion_id']) ? 'selected' : '';
                                                                    ?>
                                                                        <option value="<?php echo $col['id']; ?>" <?php echo $seleccionado; ?>><?php echo htmlspecialchars($col['nombre']); ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>

                                                            <div class="col-6 col-md-3 col-lg-2">
                                                                <label class="d-md-none small text-muted d-block mb-1">Precio</label>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="number" step="0.01" name="precio[<?php echo $id; ?>]" value="<?php echo $datos['precio']; ?>" class="form-control text-center fw-bold border-0 bg-light text-dark">
                                                                    <span class="input-group-text bg-light border-0 fw-bold text-dark">€</span>
                                                                </div>
                                                            </div>
                                                        <?php } else { ?>
                                                            <div class="col-12 col-lg-4 text-lg-end">
                                                                <span class="badge bg-light text-dark fs-6"><?php echo $datos['precio']; ?> €</span>
                                                            </div>
                                                        <?php } ?>

                                                        <div class="col-6 col-md-3 col-lg-2">
                                                            <label class="d-md-none small text-muted d-block mb-1">Rebaja</label>
                                                            <div class="input-group input-group-sm">
                                                                <span class="input-group-text bg-secondary text-white border-0 small d-none d-md-block">Rebaja</span>
                                                                <input type="number" name="rebaja[<?php echo $id; ?>]" value="<?php echo $datos['rebaja']; ?>" class="form-control text-center fw-bold" min="0" max="100">
                                                                <span class="input-group-text bg-secondary text-white border-0">%</span>
                                                            </div>
                                                        </div>

                                                        <div class="col-6 col-md-3 col-lg-1">
                                                            <label class="d-md-none small text-muted d-block mb-1">Estado</label>
                                                            <select name="activo[<?php echo $id; ?>]" class="form-select form-select-sm fw-bold border-0 <?php echo ($datos['activo'] == 1 ? 'text-success' : 'text-danger'); ?>">
                                                                <option value="1" <?php echo ($datos['activo'] == 1 ? 'selected' : ''); ?>>ACTIVO</option>
                                                                <option value="0" <?php echo ($datos['activo'] == 0 ? 'selected' : ''); ?>>OCULTO</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="card-body p-0">
                                                    <div class="row g-0 bg-light border-bottom text-secondary small text-uppercase fw-bold py-2 d-none d-md-flex text-center">
                                                        <div class="col-md-4 text-start px-4">Color disponible</div>
                                                        <div class="col-md-4">Talla</div>
                                                        <div class="col-md-4">Stock en Almacén</div>
                                                    </div>
                                                    
                                                    <?php if (empty($datos['variantes'])) { ?>
                                                        <div class="p-4 text-muted text-center">No hay variantes registradas para este producto.</div>
                                                    <?php } else { ?>
                                                        <?php
                                                        foreach ($datos['variantes'] as $v) {
                                                            $claveUnica = $id . '_' . $v['color_id'] . '_' . $v['talla'];
                                                        ?>
                                                            <div class="row g-0 align-items-center py-2 border-bottom px-3 px-md-0 text-md-center">
                                                                <!-- Color -->
                                                                <div class="col-12 col-md-4 text-start px-md-4 mb-2 mb-md-0">
                                                                    <span class="d-inline-block d-md-none text-muted small fw-bold text-uppercase" style="width: 60px;">Color:</span>
                                                                    <span class="fw-bold text-dark"><?php echo $v['nombre_color']; ?></span>
                                                                </div>
                                                                <!-- Talla -->
                                                                <div class="col-6 col-md-4 mb-2 mb-md-0">
                                                                    <span class="d-inline-block d-md-none text-muted small fw-bold text-uppercase" style="width: 60px;">Talla:</span>
                                                                    <span class="badge border border-dark text-dark px-3 py-1 rounded-0"><?php echo ($v['talla'] ?: '-'); ?></span>
                                                                </div>
                                                                <!-- Stock Input -->
                                                                <div class="col-6 col-md-4 d-flex justify-content-end justify-content-md-center">
                                                                    <div class="d-flex align-items-center">
                                                                        <span class="d-inline-block d-md-none text-muted small fw-bold text-uppercase me-2">Stock:</span>
                                                                        <input type="number" name="stock[<?php echo $claveUnica; ?>]" value="<?php echo $v['stock']; ?>" class="form-control form-control-sm text-center border-dark shadow-sm" style="width: 80px;">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </div>

                                                <?php if (!$esSegundaMano) { ?>
                                                    <div class="card-footer bg-white text-end border-0 pb-3 pt-3">
                                                        <button type="button" class="btn btn-sm btn-outline-dark fw-bold w-100 w-md-auto" data-bs-toggle="modal" data-bs-target="#modalColor<?php echo $id; ?>"><i class="bi bi-palette me-2"></i>Añadir Nuevo Color</button>
                                                    </div>
                                                    <?php
                                                    $modalesHTML .= '  <div class="modal fade text-start" id="modalColor' . $id . '" tabindex="-1" aria-hidden="true">';
                                                    $modalesHTML .= '      <div class="modal-dialog modal-lg">';
                                                    $modalesHTML .= '          <div class="modal-content rounded-0 border-dark shadow-lg">';
                                                    $modalesHTML .= '              <div class="modal-header bg-dark text-white border-0"><h5 class="modal-title fw-bold text-uppercase"><i class="bi bi-palette me-2"></i>Añadir Color a: ' . htmlspecialchars($datos['nombre']) . '</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>';
                                                    $modalesHTML .= '              <div class="modal-body p-4">';
                                                    $modalesHTML .= '                  <form action="../controllers/adminController.php" method="POST" enctype="multipart/form-data">';
                                                    $modalesHTML .= '                      <input type="hidden" name="accion" value="anadirColor">';
                                                    $modalesHTML .= '                      <input type="hidden" name="producto_id" value="' . $id . '">';
                                                    $modalesHTML .= '                      <div class="mb-3"><label class="fw-bold small text-uppercase">Elige el nuevo Color:</label><select name="color_id" class="form-select border-dark" required><option value="">-- Seleccionar Color --</option>';
                                                    foreach ($prod->listaColores() as $c) {
                                                        $modalesHTML .= "<option value='{$c['id']}'>{$c['nombre']}</option>";
                                                    }
                                                    $modalesHTML .= '                      </select></div>';
                                                    $modalesHTML .= '                      <div class="mb-3"><label class="fw-bold small text-uppercase">Stock por tallas:</label><div class="d-flex flex-wrap gap-2">';
                                                    foreach (['XS', 'S', 'M', 'L', 'XL', 'Única'] as $t) {
                                                        $modalesHTML .= '                  <div class="input-group input-group-sm" style="width: 110px;"><span class="input-group-text bg-dark text-white fw-bold">' . $t . '</span><input type="number" name="stock[' . $t . ']" class="form-control text-center border-dark" value="0" min="0"></div>';
                                                    }
                                                    $modalesHTML .= '                      </div></div>';
                                                    $modalesHTML .= '                      <div class="mb-4 mt-4"><label class="fw-bold small text-uppercase">Fotos de este nuevo color (Puedes seleccionar varias):</label><input type="file" name="imagenes[]" class="form-control border-dark" accept="image/*" multiple required></div>';
                                                    $modalesHTML .= '                      <button type="submit" class="btn btn-dark w-100 fw-bold py-3 text-uppercase">Guardar Variante</button>';
                                                    $modalesHTML .= '                  </form>';
                                                    $modalesHTML .= '              </div>';
                                                    $modalesHTML .= '          </div>';
                                                    $modalesHTML .= '      </div>';
                                                    $modalesHTML .= '  </div>';
                                                    ?>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>

                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-5 mb-5 pt-3 border-top gap-3">
                                        <nav aria-label="Paginación">
                                            <ul class="pagination mb-0 shadow-sm">
                                                <?php
                                                $disabledPrev = ($paginaActual <= 1) ? 'disabled' : '';
                                                $urlPrev = 'admin.php?seccion=productos&pagina=' . ($paginaActual - 1);
                                                ?>
                                                <li class="page-item <?php echo $disabledPrev; ?>"><a class="page-link text-dark" href="<?php echo $urlPrev; ?>">Anterior</a></li>

                                                <?php
                                                for ($i = 1; $i <= $totalPaginas; $i++) {
                                                    $activa = ($i == $paginaActual) ? 'active bg-dark border-dark text-white' : 'text-dark';
                                                ?>
                                                    <li class="page-item"><a class="page-link <?php echo $activa; ?>" href="admin.php?seccion=productos&pagina=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                                                <?php } ?>

                                                <?php
                                                $disabledNext = ($paginaActual >= $totalPaginas) ? 'disabled' : '';
                                                $urlNext = 'admin.php?seccion=productos&pagina=' . ($paginaActual + 1);
                                                ?>
                                                <li class="page-item <?php echo $disabledNext; ?>"><a class="page-link text-dark" href="<?php echo $urlNext; ?>">Siguiente</a></li>
                                            </ul>
                                        </nav>
                                        
                                        <button type="submit" class="btn btn-admin-black px-5 py-3 shadow-lg fw-bold w-100 w-md-auto position-sticky bottom-0 z-3" style="bottom: 15px;"><i class="bi bi-save me-2"></i> GUARDAR CAMBIOS</button>
                                    </div>
                                </form>

                                <?php echo $modalesHTML; ?>

                            <?php
                                break;
                            case 'segundaMano':
                                $totalSM = $producto->contarProductosPorTipo(true);
                                $listaSM = $producto->listarProductosPaginados(true, 50, 0);
                            ?>
                                <h3 class="fw-bold mb-4 text-uppercase">Revisión de Segunda Mano</h3>

                                <form action="../controllers/adminController.php" method="POST">
                                    <input type="hidden" name="accion" value="actualizarSegundaMano">

                                    <div class="bg-white p-3 admin-card shadow-sm mb-4">
                                        <!-- CABECERA TIPO TABLA (Solo visible en PC) -->
                                        <div class="row d-none d-md-flex bg-dark text-white fw-bold py-2 text-center align-items-center text-uppercase small">
                                            <div class="col-md-4 text-start px-3">ID / Prenda</div>
                                            <div class="col-md-3">Vendedor (Usuario)</div>
                                            <div class="col-md-3">Estado de Revisión</div>
                                            <div class="col-md-2">Precio</div>
                                        </div>

                                        <?php
                                        $agrupadosSM = [];
                                        if (!empty($listaSM)) {
                                            foreach ($listaSM as $item) {
                                                if (!isset($agrupadosSM[$item['prenda_id']])) {
                                                    $agrupadosSM[$item['prenda_id']] = $item;
                                                }
                                            }
                                        }

                                        if (empty($agrupadosSM)) {
                                        ?>
                                            <div class="py-4 text-muted text-center border-bottom">No hay prendas de segunda mano registradas.</div>
                                        <?php } else { ?>
                                            <?php foreach ($agrupadosSM as $id => $p) { 
                                                // Definir color del estado
                                                $colorSelect = ($p['estado_revision'] == 'Aprobado') ? 'text-success' : (($p['estado_revision'] == 'Rechazado') ? 'text-danger' : 'text-warning');
                                            ?>
                                                <div class="row align-items-center py-3 border-bottom px-2 px-md-0">
                                                    
                                                    <div class="col-8 col-md-4 d-flex align-items-center mb-3 mb-md-0 px-md-3">
                                                        <span class="fw-bold text-secondary me-2">#<?php echo $id; ?></span>
                                                        <span class="text-uppercase fw-bold text-truncate"><?php echo htmlspecialchars($p['nombre']); ?></span>
                                                    </div>

                                                    <div class="col-4 col-md-2 text-end text-md-center mb-3 mb-md-0 order-md-last">
                                                        <span class="badge bg-dark fs-6"><?php echo $p['precio']; ?> €</span>
                                                    </div>

                                                    <div class="col-12 col-md-3 mb-3 mb-md-0 text-md-center">
                                                        <label class="d-md-none small text-muted fw-bold text-uppercase mb-1">Vendedor:</label>
                                                        <select name="vendedor[<?php echo $id; ?>]" class="form-select form-select-sm border-0 bg-light fw-bold">
                                                            <?php
                                                            foreach ($listaUsuarios as $u) {
                                                                $sel = ($u['id'] == $p['id_usuario_vendedor']) ? 'selected' : '';
                                                            ?>
                                                                <option value="<?php echo $u['id']; ?>" <?php echo $sel; ?>><?php echo htmlspecialchars($u['nombre']); ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-12 col-md-3 text-md-center">
                                                        <label class="d-md-none small text-muted fw-bold text-uppercase mb-1">Estado de Revisión:</label>
                                                        <select name="revision[<?php echo $id; ?>]" class="form-select form-select-sm fw-bold border-0 bg-light <?php echo $colorSelect; ?>">
                                                            <?php
                                                            $estados = ['Pendiente', 'Aprobado', 'Rechazado'];
                                                            foreach ($estados as $est) {
                                                                $sel = ($est == $p['estado_revision']) ? 'selected' : '';
                                                            ?>
                                                                <option value="<?php echo $est; ?>" <?php echo $sel; ?>><?php echo $est; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>

                                                </div>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>

                                    <div class="text-end mt-4 mb-5">
                                        <button type="submit" class="btn btn-admin-black px-5 py-3 shadow-lg fw-bold w-100 w-md-auto position-sticky bottom-0 z-3" style="bottom: 15px;">
                                            <i class="bi bi-save me-2"></i> Guardar Cambios de Revisión
                                        </button>
                                    </div>
                                </form>
                            <?php
                                break;
                            case 'colecciones':
                                $prod = new Producto($db->conectar());
                                $todasLasColecciones = $prod->listarColecciones(true);
                            ?>
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h3 class="fw-bold m-0 text-uppercase">Gestión de Colecciones</h3>
                                    <button class="btn btn-admin-black" type="button" data-bs-toggle="collapse" data-bs-target="#formNuevaColeccion">
                                        <i class="bi bi-plus-lg me-2"></i> Nueva Colección
                                    </button>
                                </div>

                                <div class="collapse mb-4" id="formNuevaColeccion">
                                    <div class="card card-body admin-card border-0 shadow-sm">
                                        <form action="../controllers/adminController.php" method="POST" class="row g-3">
                                            <input type="hidden" name="accion" value="crearColeccion">
                                            <div class="col-md-4">
                                                <label class="fw-bold mb-1">Nombre:</label>
                                                <input type="text" name="nombre_coleccion" class="form-control" placeholder="Ej: Invierno 2026" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="fw-bold mb-1">Descripción:</label>
                                                <textarea name="descripcion_coleccion" class="form-control" rows="1" placeholder="Breve descripción..."></textarea>
                                            </div>
                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="submit" class="btn btn-dark w-100">Crear</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="table-responsive bg-white p-3 admin-card shadow-sm">
                                    <table class="table admin-table table-hover align-middle">
                                        <thead class="table-dark text-center">
                                            <tr>
                                                <th>ID</th>
                                                <th style="width: 200px;">Nombre</th>
                                                <th>Descripción</th>
                                                <th style="width: 150px;">Estado</th>
                                                <th style="width: 100px;">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($todasLasColecciones as $col) { ?>
                                                <tr>
                                                    <form action="../controllers/adminController.php" method="POST">
                                                        <input type="hidden" name="accion" value="actualizarColeccion">
                                                        <input type="hidden" name="id_coleccion" value="<?php echo $col['id']; ?>">

                                                        <td class="text-center text-secondary fw-bold">#<?php echo $col['id']; ?></td>
                                                        <td><input type="text" name="nombre" value="<?php echo htmlspecialchars($col['nombre']); ?>" class="form-control form-control-sm fw-bold"></td>
                                                        <td><textarea name="descripcion" class="form-control form-control-sm" rows="1"><?php echo htmlspecialchars($col['descripcion'] ?? ''); ?></textarea></td>
                                                        <td>
                                                            <select name="nuevo_estado" class="form-select form-select-sm">
                                                                <option value="1" <?php echo ($col['activa'] == 1 ? 'selected' : ''); ?>>Activa</option>
                                                                <option value="2" <?php echo ($col['activa'] == 2 ? 'selected' : ''); ?>>No Activa</option>
                                                                <option value="3" <?php echo ($col['activa'] == 3 ? 'selected' : ''); ?>>Próximamente</option>
                                                            </select>
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="submit" class="btn btn-sm btn-dark"><i class="bi bi-check-lg"></i></button>
                                                        </td>
                                                    </form>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php
                                break;
                            case 'usuarios':
                                $usuarioObj = new Usuario($db->conectar());
                                $listaUsuarios = $usuarioObj->listarUsuarios();
                            ?>
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h3 class="fw-bold m-0 text-uppercase">Gestión de Usuarios</h3>
                                    <span class="badge bg-secondary fs-6"><?php echo count($listaUsuarios); ?> Registrados</span>
                                </div>

                                <div class="table-responsive bg-white p-3 admin-card shadow-sm">
                                    <table class="table admin-table table-hover align-middle">
                                        <thead class="table-dark text-center">
                                            <tr>
                                                <th>ID</th>
                                                <th>Nombre / Username</th>
                                                <th>Email</th>
                                                <th>Rol Actual</th>
                                                <th style="width: 200px;">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($listaUsuarios)) { ?>
                                                <tr>
                                                    <td colspan="5" class="text-center py-4 text-muted">No hay usuarios registrados.</td>
                                                </tr>
                                            <?php } else { ?>
                                                <?php
                                                foreach ($listaUsuarios as $u) {
                                                    $esAdmin = ($u['rol_id'] == 1);
                                                    $nuevoRol = $esAdmin ? 2 : 1;
                                                ?>
                                                    <tr>
                                                        <td class="text-center text-secondary fw-bold">#<?php echo $u['id']; ?></td>
                                                        <td class="fw-bold"><?php echo htmlspecialchars($u['nombre']); ?></td>
                                                        <td class="text-muted"><?php echo htmlspecialchars($u['email']); ?></td>

                                                        <td class="text-center">
                                                            <?php if ($esAdmin) { ?>
                                                                <span class="badge bg-danger px-3 py-2"><i class="bi bi-star-fill me-1"></i> ADMIN</span>
                                                            <?php } else { ?>
                                                                <span class="badge bg-secondary px-3 py-2">CLIENTE</span>
                                                            <?php } ?>
                                                        </td>

                                                        <td class="text-center">
                                                            <form action="../controllers/adminController.php" method="POST" class="m-0">
                                                                <input type="hidden" name="accion" value="actualizarRol">
                                                                <input type="hidden" name="id_usuario" value="<?php echo $u['id']; ?>">
                                                                <input type="hidden" name="nuevo_rol" value="<?php echo $nuevoRol; ?>">

                                                                <?php if ($esAdmin) { ?>
                                                                    <button type="submit" class="btn btn-sm btn-outline-danger w-100 fw-bold">Quitar Admin</button>
                                                                <?php } else { ?>
                                                                    <button type="submit" class="btn btn-sm btn-dark w-100 fw-bold">Hacer Admin</button>
                                                                <?php } ?>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php
                                break;
                            case 'looks':
                                $lookObj = new Look($db->conectar());
                                $looks = $lookObj->listarLooksAdmin();

                                $sqlCombos = "SELECT p.id as producto_id, p.nombre as producto_nombre, c.id as color_id, c.nombre as color_nombre
                                          FROM productos p
                                          INNER JOIN producto_colores pc ON p.id = pc.producto_id
                                          INNER JOIN colores c ON pc.color_id = c.id
                                          WHERE p.es_segunda_mano = 0
                                          ORDER BY p.nombre ASC, c.nombre ASC";
                                $combinaciones = $db->conectar()->query($sqlCombos)->fetchAll(PDO::FETCH_ASSOC);

                                $looksAgrupados = [];
                                foreach ($looks as $l) {
                                    $looksAgrupados[$l['look_id']]['activo'] = $l['activo'];
                                    if ($l['producto_id']) {
                                        $looksAgrupados[$l['look_id']]['prendas'][] = $l;
                                    }
                                }
                            ?>
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h3 class="fw-bold m-0 text-uppercase">Gestión de Looks</h3>
                                    <button class="btn btn-admin-black" type="button" data-bs-toggle="collapse" data-bs-target="#formNuevoLook"><i class="bi bi-plus-lg me-2"></i> Crear Nuevo Look</button>
                                </div>

                                <div class="collapse mb-4" id="formNuevoLook">
                                    <div class="card card-body admin-card border-0 shadow-sm bg-light">
                                        <form action="../controllers/adminController.php" method="POST">
                                            <input type="hidden" name="accion" value="crear_look">
                                            <h6 class="fw-bold text-uppercase mb-3">Elige hasta 4 prendas para el look:</h6>
                                            <div class="row g-3">
                                                <?php for ($i = 0; $i < 4; $i++) { ?>
                                                    <div class="col-md-6 border-bottom pb-2">
                                                        <span class="badge bg-dark mb-1">Prenda <?php echo ($i + 1); ?></span>
                                                        <select name="prendas[<?php echo $i; ?>]" class="form-select form-select-sm">
                                                            <option value="">-- Seleccionar Prenda y Color --</option>
                                                            <?php
                                                            foreach ($combinaciones as $combo) {
                                                                $valorCompuesto = $combo['producto_id'] . '_' . $combo['color_id'];
                                                                $textoAmostrar = htmlspecialchars($combo['producto_nombre'] . ' - Color: ' . $combo['color_nombre']);
                                                            ?>
                                                                <option value="<?php echo $valorCompuesto; ?>"><?php echo $textoAmostrar; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <div class="text-end mt-3"><button type="submit" class="btn btn-dark fw-bold px-4">Guardar Look</button></div>
                                        </form>
                                    </div>
                                </div>

                                <?php if (empty($looksAgrupados)) { ?>
                                    <div class="alert alert-secondary text-center">No hay looks configurados.</div>
                                <?php } else { ?>
                                    <div class="row">
                                        <?php
                                        foreach ($looksAgrupados as $id => $datos) {
                                            $estadoHTML = ($datos['activo'] == 1) ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-danger">Inactivo</span>';
                                        ?>
                                            <div class="col-md-6 col-lg-6 mb-4">
                                                <div class="card h-100 border-0 shadow-sm admin-card">

                                                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-2">
                                                        <div><span class="fw-bold fs-5">LOOK #<?php echo $id; ?></span> <span class="ms-2"><?php echo $estadoHTML; ?></span></div>
                                                        <div class="d-flex gap-2">
                                                            <button class="btn btn-sm btn-light fw-bold" data-bs-toggle="collapse" data-bs-target="#editLook<?php echo $id; ?>">Editar</button>
                                                            <form action="../controllers/adminController.php" method="POST" onsubmit="return confirm('¿Borrar este look?')">
                                                                <input type="hidden" name="accion" value="eliminarLook">
                                                                <input type="hidden" name="id_look" value="<?php echo $id; ?>">
                                                                <button type="submit" class="btn btn-sm btn-outline-danger border-0"><i class="bi bi-trash"></i></button>
                                                            </form>
                                                        </div>
                                                    </div>

                                                    <div class="card-body">
                                                        <ul class="list-group list-group-flush mb-3">
                                                            <?php
                                                            $prendasActuales = $datos['prendas'] ?? [];
                                                            if (empty($prendasActuales)) {
                                                            ?>
                                                                <li class="list-group-item text-muted">Look vacío</li>
                                                            <?php } ?>
                                                            <?php foreach ($prendasActuales as $prenda) { ?>
                                                                <li class="list-group-item px-0 py-2 border-0">
                                                                    <span class="text-uppercase small fw-bold text-secondary"><i class="bi bi-check2-circle me-1 text-success"></i><?php echo htmlspecialchars($prenda['nombre_producto']); ?> <span class="text-dark">- Color: <?php echo htmlspecialchars($prenda['nombre_color']); ?></span></span>
                                                                </li>
                                                            <?php } ?>
                                                        </ul>

                                                        <div class="collapse mt-3 border-top pt-3" id="editLook<?php echo $id; ?>">
                                                            <form action="../controllers/adminController.php" method="POST">
                                                                <input type="hidden" name="accion" value="editar_look">
                                                                <input type="hidden" name="id_look" value="<?php echo $id; ?>">

                                                                <div class="mb-3 d-flex align-items-center gap-2">
                                                                    <label class="fw-bold small">Estado del Look:</label>
                                                                    <select name="activo" class="form-select form-select-sm w-auto">
                                                                        <option value="1" <?php echo ($datos['activo'] == 1 ? 'selected' : ''); ?>>🟢 Activo</option>
                                                                        <option value="0" <?php echo ($datos['activo'] == 0 ? 'selected' : ''); ?>>🔴 Oculto</option>
                                                                    </select>
                                                                </div>

                                                                <?php
                                                                for ($i = 0; $i < 4; $i++) {
                                                                    $pIdActual = isset($prendasActuales[$i]) ? $prendasActuales[$i]['producto_id'] : '';
                                                                    $cIdActual = isset($prendasActuales[$i]) ? $prendasActuales[$i]['color_id'] : '';
                                                                    $valorCompuestoActual = ($pIdActual && $cIdActual) ? ($pIdActual . '_' . $cIdActual) : '';
                                                                ?>
                                                                    <div class="mb-2">
                                                                        <select name="prendas[<?php echo $i; ?>]" class="form-select form-select-sm">
                                                                            <option value="">- Hueco Vacío -</option>
                                                                            <?php
                                                                            foreach ($combinaciones as $combo) {
                                                                                $valorCompuesto = $combo['producto_id'] . '_' . $combo['color_id'];
                                                                                $textoAmostrar = htmlspecialchars($combo['producto_nombre'] . ' - Color: ' . $combo['color_nombre']);
                                                                                $sel = ($valorCompuesto == $valorCompuestoActual) ? 'selected' : '';
                                                                            ?>
                                                                                <option value="<?php echo $valorCompuesto; ?>" <?php echo $sel; ?>><?php echo $textoAmostrar; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                <?php } ?>
                                                                <button type="submit" class="btn btn-dark btn-sm w-100 mt-2">Guardar Cambios</button>
                                                            </form>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                        <?php
                                break;
                        }
                        ?>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../public/js/global.js"></script>

</body>

</html>