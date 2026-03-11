<?php

require_once 'controllers/carritoController.php';
include './includes/header.php';
?>

<main class="container my-5 py-5 mt-5">
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="display-5 fw-bold text-uppercase" style="letter-spacing: 4px;">Tu Carrito</h1>
            <p class="text-muted">Revisa tus artículos antes de finalizar la compra</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12 text-center">
            <h3>Contenido de la Sesión (Solo para pruebas):</h3>
            <pre class="bg-light p-4 text-start border d-inline-block">
                <?php 
                    // Esto imprimirá en bruto lo que hay dentro de tu sesión
                    print_r(isset($_SESSION['carrito']) ? $_SESSION['carrito'] : 'El carrito está vacío'); 
                ?>
            </pre>
        </div>
    </div>
</main>

<?php include './includes/footer.php'; ?>