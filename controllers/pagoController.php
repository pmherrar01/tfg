<?php

session_start();

require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../config/db.php";

if( $_SERVER["REQUEST_METHOD"] != "POST" && empty($_SESSION["carrito"])){
    header("Location: ./index.php");
    exit;

}

$db = new Database();

$idUsu = $_SESSION["usuario_id"];
$totalPedido = $_POST["totalPedido"];





?>