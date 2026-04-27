<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../models/usuario.php";
require_once __DIR__ . "/../models/pedido.php";
require_once __DIR__ . "/../models/producto.php";

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

if ($datosUsu["rol_id"] != 1) {
    header("Location: ../index.php?error=noAdmin");
    exit();
}

$seccion = isset($_GET['seccion']) ? $_GET['seccion'] : 'dashboard';
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
                            <a class="nav-link admin-nav-link <?= ($seccion == 'dashboard') ? 'active' : '' ?>" href="admin.php?seccion=dashboard">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
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
                            <a class="nav-link admin-nav-link <?= ($seccion == 'colecciones') ? 'active' : '' ?>" href="admin.php?seccion=colecciones">
                                <i class="bi bi-collection"></i> Colecciones
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link admin-nav-link <?= ($seccion == 'usuarios') ? 'active' : '' ?>" href="admin.php?seccion=usuarios">
                                <i class="bi bi-people"></i> Usuarios
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
                            case 'dashboard':
                                echo '<div class="alert alert-dark">Bienvenido al panel de control. Selecciona una opción en el menú lateral.</div>';
                                break;
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

                                $totalProductos = $prod->contarProductosTotales();
                                $totalPaginas = ceil($totalProductos / $productosPorPagina);
                                $offset = ($paginaActual - 1) * $productosPorPagina;

                                $listaInventario = $prod->listarProductosConVariantesPaginados($productosPorPagina, $offset);

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
                                echo '  <h3>Gestión de Productos <span class="badge bg-secondary fs-6 ms-2">' . $totalProductos . ' modelos</span></h3>';
                                echo '  <a href="#" class="btn btn-admin-black px-4"><i class="bi bi-plus-lg me-2"></i> Añadir Prenda</a>';
                                echo '</div>';

                                echo '<form action="../controllers/adminController.php" method="POST">';
                                echo '<input type="hidden" name="accion" value="actualizarInventarioMasivo">';
                                echo '<input type="hidden" name="pagina_retorno" value="' . $paginaActual . '">';

                                if (empty($productosAgrupados)) {
                                    echo '<div class="alert alert-secondary text-center py-4">No hay productos en el inventario.</div>';
                                } else {
                                    foreach ($productosAgrupados as $id => $datos) {
                                        $esSegundaMano = ($datos['es_segunda_mano'] == 1);
                                        $borderStyle = $esSegundaMano ? 'border-left: 5px solid #fd7e14;' : '';

                                        echo '<div class="card mb-4 border-0 shadow-sm admin-card" style="' . $borderStyle . '">';

                                        echo '  <div class="card-header bg-dark text-white d-flex flex-wrap justify-content-between align-items-center py-3">';
                                        echo '      <div class="d-flex align-items-center gap-3">';
                                        echo '          <h5 class="m-0 fw-bold text-uppercase fs-6">#' . $id . ' - ' . $datos['nombre'] . '</h5>';

                                        if ($esSegundaMano) {
                                            echo '      <span class="badge bg-warning text-dark fw-bold">SEGUNDA MANO</span>';
                                            echo '      <small class="text-secondary">Subido por: <b>' . ($datos['nombre_dueno'] ?? 'Desconocido') . '</b></small>';
                                            // Para segunda mano, precio estático
                                            echo '      <span class="badge bg-light text-dark">' . $datos['precio'] . ' €</span>';
                                        } else {
                                            echo '      <span class="badge bg-info text-dark fw-bold small">OFFICIAL STORE</span>';
                                            // PARA OFICIALES: Input de precio editable
                                            echo '      <div class="input-group input-group-sm" style="width: 120px;">';
                                            echo '          <input type="number" step="0.01" name="precio[' . $id . ']" value="' . $datos['precio'] . '" class="form-control text-center fw-bold border-0" style="background: #f8f9fa;">';
                                            echo '          <span class="input-group-text bg-light border-0 fw-bold">€</span>';
                                            echo '      </div>';
                                        }
                                        echo '      </div>';

                                        echo '          <span class="badge bg-light text-dark">' . $datos['precio'] . ' €</span>';
                                        echo '      </div>';

                                        echo '      <div class="d-flex gap-3 align-items-center mt-3 mt-md-0">';
                                        echo '          <div class="input-group input-group-sm" style="width: 170px;">';
                                        echo '              <span class="input-group-text bg-light fw-bold border-0">Rebaja</span>';
                                        echo '              <input type="number" name="rebaja[' . $id . ']" value="' . $datos['rebaja'] . '" class="form-control text-center fw-bold" min="0" max="100">';
                                        echo '              <span class="input-group-text bg-light border-0">%</span>';
                                        echo '          </div>';

                                        echo '          <div style="width: 150px;">';
                                        echo '              <select name="activo[' . $id . ']" class="form-select form-select-sm fw-bold border-0 ' . ($datos['activo'] == 1 ? 'text-success' : 'text-danger') . '">';
                                        echo '                  <option value="1" ' . ($datos['activo'] == 1 ? 'selected' : '') . '>Activo</option>';
                                        echo '                  <option value="0" ' . ($datos['activo'] == 0 ? 'selected' : '') . '>No Activo</option>';
                                        echo '              </select>';
                                        echo '          </div>';
                                        echo '      </div>';
                                        echo '  </div>';

                                        echo '  <div class="card-body p-0">';
                                        echo '      <table class="table table-sm table-hover m-0 align-middle text-center">';
                                        echo '          <thead class="table-light">';
                                        echo '              <tr><th class="py-2">Color</th><th>Talla</th><th style="width: 200px;">Stock Actual</th></tr>';
                                        echo '          </thead>';
                                        echo '          <tbody>';

                                        if (empty($datos['variantes'])) {
                                            echo '<tr><td colspan="3" class="text-muted py-3">Sin variantes configuradas</td></tr>';
                                        } else {
                                            foreach ($datos['variantes'] as $v) {
                                                $claveUnica = $id . '_' . $v['color_id'] . '_' . $v['talla'];
                                                echo '<tr>';
                                                echo '  <td class="fw-bold">' . $v['nombre_color'] . '</td>';
                                                echo '  <td><span class="badge border border-dark text-dark px-3 py-1 rounded-0">' . ($v['talla'] ?: 'N/A') . '</span></td>';
                                                echo '  <td class="d-flex justify-content-center">';
                                                echo '      <input type="number" name="stock[' . $claveUnica . ']" value="' . $v['stock'] . '" class="form-control form-control-sm text-center border-dark" style="width: 90px;">';
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

                                echo '<div class="d-flex flex-wrap justify-content-between align-items-center mt-4 pt-3 border-top">';

                                echo '<nav aria-label="Navegación de inventario">';
                                echo '  <ul class="pagination mb-0">';
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

                                echo '  <button type="submit" class="btn btn-admin-black px-5 py-2 mt-3 mt-md-0 fs-6">💾 Guardar Cambios</button>';
                                echo '</div>';

                                echo '</form>';
                                break;
                            case 'colecciones':
                                echo '<h3>Gestión de Colecciones</h3>';
                                break;
                            case 'usuarios':
                                echo '<h3>Gestión de Usuarios y Permisos</h3>';
                                break;
                            default:
                                echo '<div class="alert alert-danger">Sección no encontrada.</div>';
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