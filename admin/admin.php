<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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

    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block sidebar-admin collapse shadow">
                <div class="position-sticky">
                    <div class="px-4 mb-4">
                        <h4 class="text-uppercase fw-bold tracking-tighter text-white">HERROR <span class="fs-6 fw-normal">Admin</span></h4>
                    </div>

                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link admin-nav-link <?= ($seccion == 'pedidos') ? 'active' : '' ?>" href="admin.php?seccion=pedidos">
                                <i class="bi bi-bag-check"></i> Pedidos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link admin-nav-link <?= ($seccion == 'productos') ? 'active' : '' ?>" href="admin.php?seccion=productos">
                                <i class="bi bi-box-seam"></i> Productos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link admin-nav-link <?= ($seccion == 'segundaMano') ? 'active' : '' ?>" href="admin.php?seccion=segundaMano">
                                <i class="bi bi-box-seam"></i> Segunda mano
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link admin-nav-link <?= ($seccion == 'colecciones') ? 'active' : '' ?>" href="admin.php?seccion=colecciones">
                                <i class="bi bi-collection"></i> Colecciones
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link admin-nav-link <?= ($seccion == 'usuarios') ? 'active' : '' ?>" href="admin.php?seccion=usuarios">
                                <i class="bi bi-people"></i> Usuarios
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link admin-nav-link <?= ($seccion == 'looks') ? 'active' : '' ?>" href="admin.php?seccion=looks">
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
                    <h1 class="h2 text-uppercase fw-bold"><?= ucfirst($seccion) ?></h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <span class="badge bg-dark p-2">Admin: <?= $_SESSION['nombre'] ?></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <?php
                        switch ($seccion) {
                            case 'pedidos':

                                $listaPedidos = $pedido->listarPedidos();
                                echo '<div class="d-flex justify-content-between align-items-center mb-4">';
                                echo '  <h3>Gestión de Pedidos</h3>';
                                echo '  <span class="badge bg-dark fs-6">Total: ' . count($listaPedidos) . ' pedidos</span>';
                                echo '</div>';
                                echo '<div class="table-responsive bg-white p-3 admin-card">';
                                echo '<table class="table admin-table table-hover align-middle">';
                                echo '<thead>';
                                echo '  <tr>
                                        <th>ID</th>
                                        <th>Cliente</th>
                                        <th>Fecha</th>
                                        <th>Total</th>
                                        <th>Estado Actual</th>
                                    </tr>';
                                echo '</thead>';
                                echo '<tbody>';
                                foreach ($listaPedidos as $p) {
                                    echo '<tr>';
                                    echo '  <td class="fw-bold">#' . $p['id'] . '</td>';
                                    echo '  <td>' . $p['nombre_cliente'] . '</td>';
                                    echo '  <td>' . $p['fecha'] . '</td>';

                                    echo '  <td>' . $p['total'] . ' €</td>';

                                    echo '  <td>';
                                    echo '      <form action="../controllers/adminController.php" method="POST" class="d-flex gap-2">';
                                    echo '          <input type="hidden" name="accion" value="cambiarEstadoPedido">';
                                    echo '          <input type="hidden" name="idPedido" value="' . $p['id'] . '">';
                                    echo '          <select name="nuevoEstado" class="form-select form-select-sm" style="width: auto;">';

                                    $estadosPosibles = ['pendiente', 'pagado', 'enviado', 'entregado', 'cancelado'];
                                    foreach ($estadosPosibles as $estado) {
                                        $seleccionado = ($p['estado'] === $estado) ? 'selected' : '';
                                        echo "          <option value='$estado' $seleccionado>$estado</option>";
                                    }

                                    echo '          </select>';
                                    echo '          <button type="submit" class="btn btn-sm btn-admin-black">Actualizar</button>';
                                    echo '      </form>';
                                    echo '  </td>';
                                    echo '</tr>';
                                }

                                echo '</tbody>';
                                echo '</table>';
                                echo '</div>';
                                break;
                            case 'productos':
                                $prod = new Producto($db->conectar());

                                $productosPorPagina = 5;
                                $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
                                if ($paginaActual < 1) $paginaActual = 1;

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

                                echo '<div class="d-flex justify-content-between align-items-center mb-4">';
                                echo '  <div>';
                                echo '      <h3 class="fw-bold m-0 text-uppercase">Gestión de Inventario</h3>';
                                echo '      <small class="text-muted">Mostrando ' . count($productosAgrupados) . ' de ' . $totalProductos . ' productos totales</small>';
                                echo '  </div>';
                                echo '  <button class="btn btn-admin-black px-4 py-2" type="button" data-bs-toggle="collapse" data-bs-target="#formNuevaPrenda">
                                            <i class="bi bi-plus-lg me-2"></i> Añadir Prenda
                                        </button>';
                                echo '</div>';

                                // --- FORMULARIO DESPLEGABLE CREAR PRENDA ---
                                echo '<div class="collapse mb-4 mt-3" id="formNuevaPrenda">';
                                echo '  <div class="card card-body admin-card border-0 shadow-sm bg-light">';
                                echo '      <h5 class="fw-bold mb-3 text-uppercase"><i class="bi bi-box-seam me-2"></i>Subir Nueva Prenda al Catálogo</h5>';
                                echo '      <form action="../controllers/adminController.php" method="POST" enctype="multipart/form-data" class="row g-3">';
                                echo '          <input type="hidden" name="accion" value="crearPrenda">';

                                echo '          <div class="col-md-4">';
                                echo '              <label class="fw-bold small">Nombre de la Prenda:</label>';
                                echo '              <input type="text" name="nombre" class="form-control border-dark" placeholder="Ej: Camiseta Oversize Negra" required>';
                                echo '          </div>';
                                echo '          <div class="col-md-2">';
                                echo '              <label class="fw-bold small">Precio (€):</label>';
                                echo '              <input type="number" step="0.01" name="precio" class="form-control border-dark" placeholder="29.99" required>';
                                echo '          </div>';
                                echo '          <div class="col-md-3">';
                                echo '              <label class="fw-bold small">Tipo de Prenda:</label>';
                                echo '              <select name="tipo_id" class="form-select border-dark" required>';
                                echo '                  <option value="">-- Seleccionar --</option>';
                                foreach ($prod->listarTiposPrendas() as $t) {
                                    echo "<option value='{$t['id']}'>{$t['nombre']}</option>";
                                }
                                echo '              </select>';
                                echo '          </div>';
                                echo '          <div class="col-md-3">';
                                echo '              <label class="fw-bold small">Colección:</label>';
                                echo '              <select name="coleccion_id" class="form-select border-dark">';
                                echo '                  <option value="">Ninguna</option>';
                                foreach ($listaColeciones as $c) {
                                    echo "<option value='{$c['id']}'>{$c['nombre']}</option>";
                                }
                                echo '              </select>';
                                echo '          </div>';

                                echo '          <div class="col-md-12">';
                                echo '              <label class="fw-bold small">Descripción del producto:</label>';
                                echo '              <textarea name="descripcion" class="form-control border-dark" rows="2" placeholder="Escribe aquí los detalles del material, estilo..."></textarea>';
                                echo '          </div>';

                                echo '          <div class="col-md-2">';
                                echo '              <label class="fw-bold small">Género:</label>';
                                echo '              <select name="genero" class="form-select border-dark" required>';
                                echo '                  <option value="1">Hombre</option>';
                                echo '                  <option value="2">Mujer</option>';
                                echo '                  <option value="3" selected>Unisex</option>';
                                echo '              </select>';
                                echo '          </div>';
                                echo '          <div class="col-md-3">';
                                echo '              <label class="fw-bold small">Color Principal:</label>';
                                echo '              <select name="color_id" class="form-select border-dark" required>';
                                echo '                  <option value="">-- Seleccionar --</option>';
                                foreach ($prod->listaColores() as $c) {
                                    echo "<option value='{$c['id']}'>{$c['nombre']}</option>";
                                }
                                echo '              </select>';
                                echo '          </div>';
                                echo '          <div class="col-md-12 mt-3">';
                                echo '          <label class="fw-bold small mb-2">Stock por Tallas (Deja en 0 las que no tengas):</label>';
                                echo '  <div class="d-flex flex-wrap gap-2">';
                                echo '  <div class="input-group input-group-sm" style="width: 115px;"><span class="input-group-text bg-dark text-white fw-bold">XS</span><input type="number" name="stock[XS]" class="form-control text-center border-dark" value="0" min="0"></div>';
                                echo '  <div class="input-group input-group-sm" style="width: 115px;"><span class="input-group-text bg-dark text-white fw-bold">S</span><input type="number" name="stock[S]" class="form-control text-center border-dark" value="0" min="0"></div>';
                                echo ' <div class="input-group input-group-sm" style="width: 115px;"><span class="input-group-text bg-dark text-white fw-bold">M</span><input type="number" name="stock[M]" class="form-control text-center border-dark" value="0" min="0"></div>';
                                echo '      <div class="input-group input-group-sm" style="width: 115px;"><span class="input-group-text bg-dark text-white fw-bold">L</span><input type="number" name="stock[L]" class="form-control text-center border-dark" value="0" min="0"></div>';
                                echo '    <div class="input-group input-group-sm" style="width: 115px;"><span class="input-group-text bg-dark text-white fw-bold">XL</span><input type="number" name="stock[XL]" class="form-control text-center border-dark" value="0" min="0"></div>';
                                echo '     <div class="input-group input-group-sm" style="width: 115px;"><span class="input-group-text bg-dark text-white fw-bold">ÚNICA</span><input type="number" name="stock[U]" class="form-control text-center border-dark" value="0" min="0"></div>';
                                echo '    </div>';
                                echo '  </div>';
                                echo '          <div class="col-md-3">';
                                echo '              <label class="fw-bold small">Subir Foto:</label>';
                                echo '              <input type="file" name="imagenes[]" class="form-control border-dark" accept="image/*" multiple required>';
                                echo '          </div>';

                                echo '          <div class="col-12 text-end mt-4">';
                                echo '              <button type="submit" class="btn btn-dark fw-bold px-4 shadow"><i class="bi bi-cloud-arrow-up me-2"></i>Guardar y Publicar Prenda</button>';
                                echo '          </div>';
                                echo '      </form>';
                                echo '  </div>';
                                echo '</div>';
                                // ------------------------------------------

                                // Aquí sigue tu formulario masivo que ya tenías:
                                echo '<form action="../controllers/adminController.php" method="POST">';
                                echo '</div>';

                                echo '<form action="../controllers/adminController.php" method="POST">';
                                echo '<input type="hidden" name="accion" value="actualizarInventarioMasivo">';
                                echo '<input type="hidden" name="pagina_retorno" value="' . $paginaActual . '">';

                                if (empty($productosAgrupados)) {
                                    echo '<div class="alert alert-secondary text-center py-5">No se han encontrado productos en esta página.</div>';
                                } else {
                                    foreach ($productosAgrupados as $id => $datos) {
                                        $esSegundaMano = ($datos['es_segunda_mano'] == 1);
                                        $borderStyle = $esSegundaMano ? 'border-left: 6px solid #fd7e14;' : 'border-left: 6px solid #0dcaf0;';

                                        echo '<div class="card mb-4 border-0 shadow-sm admin-card" style="' . $borderStyle . '">';

                                        echo '  <div class="card-header bg-dark text-white d-flex flex-wrap justify-content-between align-items-center py-3">';
                                        echo '      <div class="d-flex align-items-center gap-3">';
                                        echo '          <span class="text-secondary fw-bold">#' . $id . '</span>';
                                        echo '          <h5 class="m-0 fw-bold text-uppercase fs-6">' . $datos['nombre'] . '</h5>';

                                        if ($esSegundaMano) {
                                            echo '      <span class="badge bg-warning text-dark fw-bold px-2">2ª MANO</span>';
                                            echo '      <small class="text-secondary d-none d-md-inline">Propietario: <b>' . ($datos['nombre_dueno'] ?? 'User') . '</b></small>';
                                            echo '      <span class="badge bg-light text-dark fs-6">' . $datos['precio'] . ' €</span>';
                                        } else {
                                            echo '      <div style="width: 140px;" class="ms-1">';
                                            echo '          <select name="coleccion[' . $id . ']" class="form-select form-select-sm border-0 bg-light text-dark fw-bold">';
                                            echo '              <option value="">Sin colección</option>';
                                            foreach ($listaColeciones as $col) {
                                                $seleccionado = ($col['id'] == $datos['coleccion_id']) ? 'selected' : '';
                                                echo '              <option value="' . $col['id'] . '" ' . $seleccionado . '>' . htmlspecialchars($col['nombre']) . '</option>';
                                            }
                                            echo '          </select>';
                                            echo '      </div>';

                                            echo '      <div class="input-group input-group-sm" style="width: 120px;">';
                                            echo '          <input type="number" step="0.01" name="precio[' . $id . ']" value="' . $datos['precio'] . '" class="form-control text-center fw-bold border-0 bg-light text-dark">';
                                            echo '          <span class="input-group-text bg-light border-0 fw-bold text-dark">€</span>';
                                            echo '      </div>';
                                        }
                                        echo '      </div>';

                                        echo '      <div class="d-flex gap-3 align-items-center mt-3 mt-md-0">';
                                        echo '          <div class="input-group input-group-sm" style="width: 160px;">';
                                        echo '              <span class="input-group-text bg-secondary text-white border-0 small">Rebaja</span>';
                                        echo '              <input type="number" name="rebaja[' . $id . ']" value="' . $datos['rebaja'] . '" class="form-control text-center fw-bold" min="0" max="100">';
                                        echo '              <span class="input-group-text bg-secondary text-white border-0">%</span>';
                                        echo '          </div>';
                                        echo '          <div style="width: 140px;">';
                                        echo '              <select name="activo[' . $id . ']" class="form-select form-select-sm fw-bold border-0 ' . ($datos['activo'] == 1 ? 'text-success' : 'text-danger') . '">';
                                        echo '                  <option value="1" ' . ($datos['activo'] == 1 ? 'selected' : '') . '>ACTIVO</option>';
                                        echo '                  <option value="0" ' . ($datos['activo'] == 0 ? 'selected' : '') . '>OCULTO</option>';
                                        echo '              </select>';
                                        echo '          </div>';
                                        echo '      </div>';
                                        echo '  </div>';

                                        echo '  <div class="card-body p-0">';
                                        echo '      <table class="table table-sm table-hover m-0 align-middle text-center">';
                                        echo '          <thead class="table-light text-secondary small text-uppercase">';
                                        echo '              <tr><th class="py-2">Color disponible</th><th>Talla</th><th style="width: 180px;">Stock en Almacén</th></tr>';
                                        echo '          </thead>';
                                        echo '          <tbody>';

                                        if (empty($datos['variantes'])) {
                                            echo '<tr><td colspan="3" class="text-muted py-3">No hay variantes registradas para este producto.</td></tr>';
                                        } else {
                                            foreach ($datos['variantes'] as $v) {
                                                $claveUnica = $id . '_' . $v['color_id'] . '_' . $v['talla'];
                                                echo '<tr>';
                                                echo '  <td class="fw-bold text-dark">' . $v['nombre_color'] . '</td>';
                                                echo '  <td><span class="badge border border-dark text-dark px-3 py-1 rounded-0">' . ($v['talla'] ?: '-') . '</span></td>';
                                                echo '  <td class="d-flex justify-content-center">';
                                                echo '      <input type="number" name="stock[' . $claveUnica . ']" value="' . $v['stock'] . '" class="form-control form-control-sm text-center border-dark shadow-sm" style="width: 80px;">';
                                                echo '  </td>';
                                                echo '</tr>';
                                            }
                                        }
                                        echo '          </tbody>';
                                        echo '      </table>';
                                        echo '  </div>';
                                        echo '</div>';
                                    }
                                }

                                echo '<div class="d-flex flex-wrap justify-content-between align-items-center mt-5 mb-5 pt-3 border-top">';

                                echo '<nav aria-label="Paginación">';
                                echo '  <ul class="pagination mb-0 shadow-sm">';
                                $disabledPrev = ($paginaActual <= 1) ? 'disabled' : '';
                                $urlPrev = 'admin.php?seccion=productos&pagina=' . ($paginaActual - 1);
                                echo '    <li class="page-item ' . $disabledPrev . '"><a class="page-link text-dark" href="' . $urlPrev . '">Anterior</a></li>';

                                for ($i = 1; $i <= $totalPaginas; $i++) {
                                    $activa = ($i == $paginaActual) ? 'active bg-dark border-dark text-white' : 'text-dark';
                                    echo '    <li class="page-item"><a class="page-link ' . $activa . '" href="admin.php?seccion=productos&pagina=' . $i . '">' . $i . '</a></li>';
                                }

                                $disabledNext = ($paginaActual >= $totalPaginas) ? 'disabled' : '';
                                $urlNext = 'admin.php?seccion=productos&pagina=' . ($paginaActual + 1);
                                echo '    <li class="page-item ' . $disabledNext . '"><a class="page-link text-dark" href="' . $urlNext . '">Siguiente</a></li>';
                                echo '  </ul>';
                                echo '</nav>';

                                echo '  <button type="submit" class="btn btn-admin-black px-5 py-3 shadow-lg fw-bold"><i class="bi bi-save me-2"></i> GUARDAR TODOS LOS CAMBIOS</button>';
                                echo '</div>';

                                echo '</form>';
                                break;
                            case 'segundaMano':

                                $totalSM = $producto->contarProductosPorTipo(true);
                                $listaSM = $producto->listarProductosPaginados(true, 50, 0);

                                echo '<h3 class="fw-bold mb-4 text-uppercase">Revisión de Segunda Mano</h3>';

                                echo '<form action="../controllers/adminController.php" method="POST">';
                                echo '<input type="hidden" name="accion" value="actualizarSegundaMano">';

                                echo '<div class="table-responsive bg-white p-3 admin-card shadow-sm">';
                                echo '<table class="table align-middle text-center table-hover">';
                                echo '  <thead class="table-dark">
                                            <tr>
                                                <th>ID</th>
                                                <th>Prenda</th>
                                                <th>Vendedor (Usuario)</th>
                                                <th>Estado de Revisión</th>
                                                <th>Precio</th>
                                            </tr>
                                        </thead>';
                                echo '  <tbody>';

                                $agrupadosSM = [];

                                if (!empty($listaSM)) {
                                    foreach ($listaSM as $item) {
                                        if (!isset($agrupadosSM[$item['prenda_id']])) {
                                            $agrupadosSM[$item['prenda_id']] = $item;
                                        }
                                    }
                                }

                                if (empty($agrupadosSM)) {
                                    echo '<tr><td colspan="5" class="py-4 text-muted">No hay prendas de segunda mano registradas.</td></tr>';
                                } else {
                                    foreach ($agrupadosSM as $id => $p) {
                                        echo '<tr>';
                                        echo '  <td class="fw-bold text-secondary">#' . $id . '</td>';
                                        echo '  <td class="text-uppercase fw-bold">' . htmlspecialchars($p['nombre']) . '</td>';

                                        echo '  <td>';
                                        echo '      <select name="vendedor[' . $id . ']" class="form-select form-select-sm border-0 bg-light fw-bold">';
                                        foreach ($listaUsuarios as $u) {
                                            $sel = ($u['id'] == $p['id_usuario_vendedor']) ? 'selected' : '';
                                            echo '          <option value="' . $u['id'] . '" ' . $sel . '>' . htmlspecialchars($u['nombre']) . '</option>';
                                        }
                                        echo '      </select>';
                                        echo '  </td>';

                                        echo '  <td>';
                                        $colorSelect = ($p['estado_revision'] == 'Aprobado') ? 'text-success' : (($p['estado_revision'] == 'Rechazado') ? 'text-danger' : 'text-warning');

                                        echo '      <select name="revision[' . $id . ']" class="form-select form-select-sm fw-bold border-0 bg-light ' . $colorSelect . '">';
                                        $estados = ['Pendiente', 'Aprobado', 'Rechazado'];
                                        foreach ($estados as $est) {
                                            $sel = ($est == $p['estado_revision']) ? 'selected' : '';
                                            echo '          <option value="' . $est . '" ' . $sel . '>' . $est . '</option>';
                                        }
                                        echo '      </select>';
                                        echo '  </td>';

                                        echo '  <td><span class="badge bg-dark">' . $p['precio'] . ' €</span></td>';
                                        echo '</tr>';
                                    }
                                }

                                echo '  </tbody>';
                                echo '</table>';
                                echo '</div>';

                                echo '<div class="text-end mt-4 mb-5">';
                                echo '  <button type="submit" class="btn btn-admin-black px-5 py-3 shadow-lg fw-bold"><i class="bi bi-save me-2"></i> Guardar Cambios de Revisión</button>';
                                echo '</div>';

                                echo '</form>';
                                break;
                            case 'colecciones':
                                $prod = new Producto($db->conectar());
                                $todasLasColecciones = $prod->listarColecciones(true);

                                echo '<div class="d-flex justify-content-between align-items-center mb-4">';
                                echo '  <h3 class="fw-bold m-0 text-uppercase">Gestión de Colecciones</h3>';
                                echo '  <button class="btn btn-admin-black" type="button" data-bs-toggle="collapse" data-bs-target="#formNuevaColeccion">
                                        <i class="bi bi-plus-lg me-2"></i> Nueva Colección
                                    </button>';
                                echo '</div>';

                                echo '<div class="collapse mb-4" id="formNuevaColeccion">';
                                echo '  <div class="card card-body admin-card border-0 shadow-sm">';
                                echo '      <form action="../controllers/adminController.php" method="POST" class="row g-3">';
                                echo '          <input type="hidden" name="accion" value="crearColeccion">';
                                echo '          <div class="col-md-4">';
                                echo '              <label class="fw-bold mb-1">Nombre:</label>';
                                echo '              <input type="text" name="nombre_coleccion" class="form-control" placeholder="Ej: Invierno 2026" required>';
                                echo '          </div>';
                                echo '          <div class="col-md-6">';
                                echo '              <label class="fw-bold mb-1">Descripción:</label>';
                                echo '              <textarea name="descripcion_coleccion" class="form-control" rows="1" placeholder="Breve descripción..."></textarea>';
                                echo '          </div>';
                                echo '          <div class="col-md-2 d-flex align-items-end">';
                                echo '              <button type="submit" class="btn btn-dark w-100">Crear</button>';
                                echo '          </div>';
                                echo '      </form>';
                                echo '  </div>';
                                echo '</div>';

                                echo '<div class="table-responsive bg-white p-3 admin-card shadow-sm">';
                                echo '<table class="table admin-table table-hover align-middle">';
                                echo '  <thead class="table-dark text-center">
                                        <tr>
                                            <th>ID</th>
                                            <th style="width: 200px;">Nombre</th>
                                            <th>Descripción</th>
                                            <th style="width: 150px;">Estado</th>
                                            <th style="width: 100px;">Acción</th>
                                        </tr>
                                    </thead>';
                                echo '  <tbody>';

                                foreach ($todasLasColecciones as $col) {
                                    echo '<tr>';
                                    echo '  <form action="../controllers/adminController.php" method="POST">';
                                    echo '  <input type="hidden" name="accion" value="actualizarColeccion">';
                                    echo '  <input type="hidden" name="id_coleccion" value="' . $col['id'] . '">';

                                    echo '  <td class="text-center text-secondary fw-bold">#' . $col['id'] . '</td>';

                                    echo '  <td><input type="text" name="nombre" value="' . htmlspecialchars($col['nombre']) . '" class="form-control form-control-sm fw-bold"></td>';

                                    echo '  <td><textarea name="descripcion" class="form-control form-control-sm" rows="1">' . htmlspecialchars($col['descripcion'] ?? '') . '</textarea></td>';

                                    echo '  <td>';
                                    echo '      <select name="nuevo_estado" class="form-select form-select-sm">';
                                    echo '          <option value="1" ' . ($col['activa'] == 1 ? 'selected' : '') . '>Activa</option>';
                                    echo '          <option value="2" ' . ($col['activa'] == 2 ? 'selected' : '') . '>No Activa</option>';
                                    echo '          <option value="3" ' . ($col['activa'] == 3 ? 'selected' : '') . '>Próximamente</option>';
                                    echo '      </select>';
                                    echo '  </td>';

                                    echo '  <td class="text-center">';
                                    echo '      <button type="submit" class="btn btn-sm btn-dark"><i class="bi bi-check-lg"></i></button>';
                                    echo '  </td>';
                                    echo '  </form>';
                                    echo '</tr>';
                                }

                                echo '  </tbody>';
                                echo '</table>';
                                echo '</div>';
                                break;
                            case 'usuarios':
                                $usuarioObj = new Usuario($db->conectar());
                                $listaUsuarios = $usuarioObj->listarUsuarios();

                                echo '<div class="d-flex justify-content-between align-items-center mb-4">';
                                echo '  <h3 class="fw-bold m-0 text-uppercase">Gestión de Usuarios</h3>';
                                echo '  <span class="badge bg-secondary fs-6">' . count($listaUsuarios) . ' Registrados</span>';
                                echo '</div>';

                                echo '<div class="table-responsive bg-white p-3 admin-card shadow-sm">';
                                echo '<table class="table admin-table table-hover align-middle">';
                                echo '  <thead class="table-dark text-center">
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre / Username</th>
                                            <th>Email</th>
                                            <th>Rol Actual</th>
                                            <th style="width: 200px;">Acción</th>
                                        </tr>
                                    </thead>';
                                echo '  <tbody>';

                                if (empty($listaUsuarios)) {
                                    echo '<tr><td colspan="5" class="text-center py-4 text-muted">No hay usuarios registrados.</td></tr>';
                                } else {
                                    foreach ($listaUsuarios as $u) {
                                        $esAdmin = ($u['rol_id'] == 1);

                                        $nuevoRol = $esAdmin ? 2 : 1;

                                        echo '<tr>';
                                        echo '  <td class="text-center text-secondary fw-bold">#' . $u['id'] . '</td>';
                                        echo '  <td class="fw-bold">' . htmlspecialchars($u['nombre']) . '</td>';
                                        echo '  <td class="text-muted">' . htmlspecialchars($u['email']) . '</td>';

                                        echo '  <td class="text-center">';
                                        if ($esAdmin) {
                                            echo '<span class="badge bg-danger px-3 py-2"><i class="bi bi-star-fill me-1"></i> ADMIN</span>';
                                        } else {
                                            echo '<span class="badge bg-secondary px-3 py-2">CLIENTE</span>';
                                        }
                                        echo '  </td>';

                                        echo '  <td class="text-center">';
                                        echo '      <form action="../controllers/adminController.php" method="POST" class="m-0">';
                                        echo '          <input type="hidden" name="accion" value="actualizarRol">';
                                        echo '          <input type="hidden" name="id_usuario" value="' . $u['id'] . '">';
                                        echo '          <input type="hidden" name="nuevo_rol" value="' . $nuevoRol . '">';

                                        if ($esAdmin) {
                                            echo '          <button type="submit" class="btn btn-sm btn-outline-danger w-100 fw-bold">Quitar Admin</button>';
                                        } else {
                                            echo '          <button type="submit" class="btn btn-sm btn-dark w-100 fw-bold">Hacer Admin</button>';
                                        }

                                        echo '      </form>';
                                        echo '  </td>';
                                        echo '</tr>';
                                    }
                                }

                                echo '  </tbody>';
                                echo '</table>';
                                echo '</div>';
                                break;
                            case 'looks':
                                $lookObj = new Look($db->conectar());
                                $looks = $lookObj->listarLooksAdmin();

                                // LA MAGIA: Consultamos las combinaciones reales de Producto + Color
                                $sqlCombos = "SELECT p.id as producto_id, p.nombre as producto_nombre, c.id as color_id, c.nombre as color_nombre
                                          FROM productos p
                                          INNER JOIN producto_colores pc ON p.id = pc.producto_id
                                          INNER JOIN colores c ON pc.color_id = c.id
                                          WHERE p.es_segunda_mano = 0
                                          ORDER BY p.nombre ASC, c.nombre ASC";
                                $combinaciones = $db->conectar()->query($sqlCombos)->fetchAll(PDO::FETCH_ASSOC);

                                // Agrupar prendas por Look
                                $looksAgrupados = [];
                                foreach ($looks as $l) {
                                    $looksAgrupados[$l['look_id']]['activo'] = $l['activo'];
                                    if ($l['producto_id']) {
                                        $looksAgrupados[$l['look_id']]['prendas'][] = $l;
                                    }
                                }

                                echo '<div class="d-flex justify-content-between align-items-center mb-4">';
                                echo '  <h3 class="fw-bold m-0 text-uppercase">Gestión de Looks</h3>';
                                echo '  <button class="btn btn-admin-black" type="button" data-bs-toggle="collapse" data-bs-target="#formNuevoLook"><i class="bi bi-plus-lg me-2"></i> Crear Nuevo Look</button>';
                                echo '</div>';

                                // --- FORMULARIO CREAR LOOK ---
                                echo '<div class="collapse mb-4" id="formNuevoLook">';
                                echo '  <div class="card card-body admin-card border-0 shadow-sm bg-light">';
                                echo '      <form action="../controllers/adminController.php" method="POST">';
                                echo '          <input type="hidden" name="accion" value="crear_look">';
                                echo '          <h6 class="fw-bold text-uppercase mb-3">Elige hasta 4 prendas para el look:</h6>';
                                echo '          <div class="row g-3">';
                                for ($i = 0; $i < 4; $i++) {
                                    echo '          <div class="col-md-6 border-bottom pb-2">';
                                    echo '              <span class="badge bg-dark mb-1">Prenda ' . ($i + 1) . '</span>';
                                    // UN SOLO SELECTOR COMBINADO
                                    echo '              <select name="prendas[' . $i . ']" class="form-select form-select-sm">';
                                    echo '                  <option value="">-- Seleccionar Prenda y Color --</option>';
                                    foreach ($combinaciones as $combo) {
                                        $valorCompuesto = $combo['producto_id'] . '_' . $combo['color_id'];
                                        $textoAmostrar = htmlspecialchars($combo['producto_nombre'] . ' - Color: ' . $combo['color_nombre']);
                                        echo "              <option value='{$valorCompuesto}'>{$textoAmostrar}</option>";
                                    }
                                    echo '              </select>';
                                    echo '          </div>';
                                }
                                echo '          </div>';
                                echo '          <div class="text-end mt-3"><button type="submit" class="btn btn-dark fw-bold px-4">Guardar Look</button></div>';
                                echo '      </form>';
                                echo '  </div>';
                                echo '</div>';

                                // --- LISTADO DE LOOKS (TARJETAS) ---
                                if (empty($looksAgrupados)) {
                                    echo '<div class="alert alert-secondary text-center">No hay looks configurados.</div>';
                                } else {
                                    echo '<div class="row">';
                                    foreach ($looksAgrupados as $id => $datos) {
                                        $estadoHTML = ($datos['activo'] == 1) ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-danger">Inactivo</span>';

                                        echo '<div class="col-md-6 col-lg-6 mb-4">';
                                        echo '  <div class="card h-100 border-0 shadow-sm admin-card">';

                                        echo '      <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-2">';
                                        echo '          <div><span class="fw-bold fs-5">LOOK #' . $id . '</span> <span class="ms-2">' . $estadoHTML . '</span></div>';
                                        echo '          <div class="d-flex gap-2">';
                                        echo '              <button class="btn btn-sm btn-light fw-bold" data-bs-toggle="collapse" data-bs-target="#editLook' . $id . '">Editar</button>';
                                        echo '              <form action="../controllers/adminController.php" method="POST" onsubmit="return confirm(\'¿Borrar este look?\')">';
                                        echo '                  <input type="hidden" name="accion" value="eliminarLook">';
                                        echo '                  <input type="hidden" name="id_look" value="' . $id . '">';
                                        echo '                  <button type="submit" class="btn btn-sm btn-outline-danger border-0"><i class="bi bi-trash"></i></button>';
                                        echo '              </form>';
                                        echo '          </div>';
                                        echo '      </div>';

                                        echo '      <div class="card-body">';
                                        echo '          <ul class="list-group list-group-flush mb-3">';
                                        $prendasActuales = $datos['prendas'] ?? [];
                                        if (empty($prendasActuales)) echo '<li class="list-group-item text-muted">Look vacío</li>';
                                        foreach ($prendasActuales as $prenda) {
                                            echo '          <li class="list-group-item px-0 py-2 border-0">';
                                            // TU LÍNEA DE CÓDIGO APLICADA AQUÍ:
                                            echo '              <span class="text-uppercase small fw-bold text-secondary"><i class="bi bi-check2-circle me-1 text-success"></i>' . htmlspecialchars($prenda['nombre_producto']) . ' <span class="text-dark">- Color: ' . htmlspecialchars($prenda['nombre_color']) . '</span></span>';
                                            echo '          </li>';
                                        }
                                        echo '          </ul>';

                                        // --- FORMULARIO DE EDICIÓN OCULTO ---
                                        echo '          <div class="collapse mt-3 border-top pt-3" id="editLook' . $id . '">';
                                        echo '              <form action="../controllers/adminController.php" method="POST">';
                                        echo '                  <input type="hidden" name="accion" value="editar_look">';
                                        echo '                  <input type="hidden" name="id_look" value="' . $id . '">';

                                        echo '                  <div class="mb-3 d-flex align-items-center gap-2">';
                                        echo '                      <label class="fw-bold small">Estado del Look:</label>';
                                        echo '                      <select name="activo" class="form-select form-select-sm w-auto">';
                                        echo '                          <option value="1" ' . ($datos['activo'] == 1 ? 'selected' : '') . '>🟢 Activo</option>';
                                        echo '                          <option value="0" ' . ($datos['activo'] == 0 ? 'selected' : '') . '>🔴 Oculto</option>';
                                        echo '                      </select>';
                                        echo '                  </div>';

                                        // SELECTORES COMBINADOS PARA EDICIÓN
                                        for ($i = 0; $i < 4; $i++) {
                                            $pIdActual = isset($prendasActuales[$i]) ? $prendasActuales[$i]['producto_id'] : '';
                                            $cIdActual = isset($prendasActuales[$i]) ? $prendasActuales[$i]['color_id'] : '';
                                            $valorCompuestoActual = ($pIdActual && $cIdActual) ? ($pIdActual . '_' . $cIdActual) : '';

                                            echo '              <div class="mb-2">';
                                            echo '                  <select name="prendas[' . $i . ']" class="form-select form-select-sm">';
                                            echo '                      <option value="">- Hueco Vacío -</option>';
                                            foreach ($combinaciones as $combo) {
                                                $valorCompuesto = $combo['producto_id'] . '_' . $combo['color_id'];
                                                $textoAmostrar = htmlspecialchars($combo['producto_nombre'] . ' - Color: ' . $combo['color_nombre']);
                                                $sel = ($valorCompuesto == $valorCompuestoActual) ? 'selected' : '';
                                                echo "                  <option value='{$valorCompuesto}' $sel>{$textoAmostrar}</option>";
                                            }
                                            echo '                  </select>';
                                            echo '              </div>';
                                        }
                                        echo '                  <button type="submit" class="btn btn-dark btn-sm w-100 mt-2">Guardar Cambios</button>';
                                        echo '              </form>';
                                        echo '          </div>'; // Fin collapse editar

                                        echo '      </div>';
                                        echo '  </div>';
                                        echo '</div>';
                                    }
                                    echo '</div>';
                                }
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