<?php

session_start();


require_once __DIR__ . '/../models/favorito.php';
require_once "./models/producto.php";
require_once "./config/db.php";
require_once "./models/usuario.php";

$db = new Database();
$conexion = $db->conectar();
$producto = new Producto($conexion);
$usu = new Usuario($conexion);

$novedades = $producto->listarProductos(1, 8);

$arrayFavoritos = [];

if (isset($_SESSION['usuario_id'])) {
    $favoritoModel = new Favorito($db->conectar());
    $misFavoritos = $favoritoModel->listarFavoritos($_SESSION['usuario_id']);

    foreach ($misFavoritos as $fav) {
        $arrayFavoritos[] = $fav['id'] . '-' . $fav['color_id'];
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = isset($_POST["email"]) ? $_POST["email"] : "";

    if (!$usu->comprobarDescuento($email)) {

        $codigo = $usu->generarCodigoDescuento();
        $usu->crearCodigo($codigo, $email, "descuento");

        $datosn8n = [
            "email" => $email,
            "codigo" => $codigo
        ];

        $urlWebhookDescuento = "http://localhost:5678/webhook-test/solicitarCodigoDescuento";

            $curl = curl_init($urlWebhookDescuento);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($datosn8n));
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_TIMEOUT, 2); 
            
            curl_exec($curl);

            header("Location: index.php?mensaje=codigo_enviado");
            exit;

    }else {
            header("Location: index.php?error=codigo_existente");
            exit;
        }
}
