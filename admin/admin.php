<?php
session_start();

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../models/usuario.php";

if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../index.php?error=debes_iniciar_sesion");
    exit();
}

$db = new Database();
$usu = new Usuario($db->conectar());
$idUsu = $_SESSION["usuario_id"];
$datosUsu = $usu->obtenerDatosUsu($idUsu);

if($datosUsu["rol_id"] != 1){
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="public/css/style.css">
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
                        <a class="nav-link admin-nav-link text-info" href="index.php">
                            <i class="bi bi-arrow-left-circle"></i> Volver a la Tienda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link admin-nav-link text-danger" href="controllers/usuarioController.php?accion=cerrar">
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
                            // Aquí llamaremos a tu lógica de pedidos
                            echo '<h3>Gestión de Pedidos</h3>';
                            break;
                        case 'productos':
                            // Aquí la gestión de stock y rebajas
                            echo '<h3>Gestión de Productos e Inventario</h3>';
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
<script src="public/js/global.js"></script>

</body>
</html>