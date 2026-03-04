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
        <a href="#" class="text-reset"><i class="bi bi-person"></i></a>
        <a href="#" class="text-reset position-relative">
            <i class="bi bi-bag"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">0</span>
        </a>
    </div>

  </div>
</nav>