<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'agregar') {
    
    $idPrenda = $_POST['idPrenda'];
    $talla = $_POST['talla'];
    $color_id = $_POST['color_id'];
    $cantidad = 1;

    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }

    $_SESSION['carrito'][] = [
        'idPrenda' => $idPrenda,
        'talla' => $talla,
        'color_id' => $color_id,
        'cantidad' => $cantidad
    ];

    header("Location: ../fichaProducto.php?idPrenda=" . $idPrenda . "&color=" . $color_id . "&mensaje=carrito_ok");
    exit;
}

header("Location: ../catalogo.php");
exit;

?>