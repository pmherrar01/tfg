<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HERROR</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

  <link rel="stylesheet" href="public/css/style.css?v=<?php echo time(); ?>">
</head>

<body>

  <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container-fluid px-4 px-lg-5 position-relative">

      <button class="navbar-toggler border-0 p-0" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal">
        <span class="bi bi-list fs-3"></span>
      </button>

      <div class="collapse navbar-collapse" id="menuPrincipal">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0 mt-3 mt-lg-0">
          <li class="nav-item"><a class="nav-link" href="../catalogo.php">Catalogo</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Hombre</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Mujer</a></li>
          <li class="nav-item"><a class="nav-link text-danger" href="#">Rebajas</a></li>
        </ul>
      </div>

      <a class="navbar-brand position-absolute top-50 start-50 translate-middle m-0 fw-bold fs-4 text-uppercase ls-2" href="index.php">
        HERROR
      </a>

      <div class="d-flex gap-3 align-items-center order-lg-last ms-auto">
        <a href="#" class="text-reset"><i class="bi bi-search"></i></a>

        <?php if (!isset($_SESSION['usuario_id'])): ?>
          <a href="#" class="text-reset" data-bs-toggle="modal" data-bs-target="#modalUsuario">
            <i class="bi bi-person"></i>
          </a>
        <?php else: ?>
          <div class="dropdown">
            <a href="#" class="text-white text-decoration-none dropdown-toggle text-uppercase fw-bold" style="font-size: 0.85rem;" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-person-fill"></i> Hola, <?php echo $_SESSION['nombre']; ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end rounded-0 border-dark mt-3" aria-labelledby="dropdownUser">
              <li><a class="dropdown-item" href="perfil.php">Mi Perfil</a></li>
              <li><a class="dropdown-item" href="#">Mis Pedidos</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item text-danger fw-bold" href="controllers/usuarioController.php?accion=logout">Cerrar Sesión</a></li>
            </ul>
          </div>
        <?php endif; ?>
        <a href="carrito.php" class="text-reset position-relative">
          <i class="bi bi-bag"></i>
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
            <?php
            echo isset($_SESSION['carrito']) ? count($_SESSION['carrito']) : 0;
            ?>
          </span>
        </a>
      </div>

    </div>

  </nav>

  <?php if (!isset($_SESSION['usuario_id'])): ?>
    <div class="modal fade" id="modalUsuario" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" style="max-width: 800px;">
        <div class="modal-content rounded-0 border-0 p-0 overflow-hidden shadow-lg">

          <div class="anim-container">

            <div class="form-box Login">
              <h2>Entrar</h2>
              <form action="controllers/usuarioController.php" method="POST">
                <div class="input-box">
                  <input type="email" name="email" required>
                  <label>Email</label>
                  <i class="bi bi-envelope"></i>
                </div>
                <div class="input-box">
                  <input type="password" name="password" required>
                  <label>Contraseña</label>
                  <i class="bi bi-lock"></i>
                </div>
                <button class="btn-anim" type="submit" name="accion" value="login">Iniciar Sesión</button>
                <div class="regi-link">
                  <p>¿No tienes cuenta? <a href="#" class="SignUpLink">Regístrate</a></p>
                </div>
              </form>
            </div>

            <div class="info-content Login">
              <h2>¡HOLA!</h2>
              <p>Inicia sesión para acceder a tu historial de pedidos, guardar tus favoritos y descubrir las últimas novedades.</p>
            </div>

            <div class="form-box Register">
              <h2>Registro</h2>
              <form action="controllers/usuarioController.php" method="POST">
                <div class="input-box">
                  <input type="text" name="nombre" required>
                  <label>Nombre</label>
                  <i class="bi bi-person"></i>
                </div>
                <div class="input-box">
                  <input type="text" name="apellidos" required>
                  <label>Apellidos</label>
                  <i class="bi bi-people"></i>
                </div>
                <div class="input-box">
                  <input type="email" name="email" required>
                  <label>Email</label>
                  <i class="bi bi-envelope"></i>
                </div>
                <div class="input-box">
                  <input type="password" name="password" required>
                  <label>Contraseña</label>
                  <i class="bi bi-lock"></i>
                </div>
                <button class="btn-anim" type="submit" name="accion" value="registro">Crear Cuenta</button>
                <div class="regi-link">
                  <p>¿Ya tienes cuenta? <a href="#" class="SignInLink">Inicia Sesión</a></p>
                </div>
              </form>
            </div>

            <div class="info-content Register">
              <h2>¡ÚNETE!</h2>
              <p>Crea tu cuenta ahora para disfrutar de una experiencia de compra rápida y gestionar tu armario virtual.</p>
            </div>

          </div>

        </div>
      </div>
    </div>
  <?php endif; ?>