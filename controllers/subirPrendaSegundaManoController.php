<?php

session_start();

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/producto.php';

if( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION["usuario_id"])){
    
    $nombrePrenda = isset($_POST["nombrePrenda"]) ? trim($_POST["nombrePrenda"]) : "";
    $precioPrenda = isset($_POST["precioPrenda"]) ? $_POST["precioPrenda"] : -1;
    $tallaPrenda = isset($_POST["tallaPrenda"]) ? $_POST["tallaPrenda"] : "";
    $colorPrenda  = isset($_POST["colorPrenda"]) ? $_POST["colorPrenda"] : "";
    $tipoPrenda = isset($_POST["tipoPrenda"]) ? $_POST["tipoPrenda"] : "";

    $idUsu = $_SESSION["usuario_id"];
    $db = new Database();
    $conexion = $db ->conectar();
    $producto = new Producto($conexion);
    $usu = new Usuario($conexion);

    if(isset($_FILES["foto"]) && $_FILES["foto"]["error"] === 0){

    $nombreFoto = time() . "-" . $_FILES["foto"]["name"];

    $rutaFotos = "../public/img/" . $nombreFoto;

    $rutaFotosBaseDatos = "public/img/" . $nombreFoto;

    if(move_uploaded_file($_FILES["foto"]["tmp_name"], $rutaFotos) ){
        $prendaSubida = $producto -> subirPrendasSegundaMano($nombrePrenda, $precioPrenda, $idUsu, $rutaFotosBaseDatos, $colorPrenda, $tallaPrenda, $tipoPrenda);

        if($prendaSubida){

        $datosPedidoSegundaMano = [
            "nombreUsu" => $usu->obtenerDatosUsu($idUsu)["nombre"],
            "emailUsu" => $usu->obtenerDatosUsu($idUsu)["email"],
            "nombrePrenda" => $nombrePrenda,
            "precio" => $precioPrenda,
            "fecha" => date("d/m/y")
        ];

        $urlWebhookSegundaMano = "http://localhost:5678/webhook-test/subidaPrendaSegundaMano";

        $curl = curl_init($urlWebhookSegundaMano);
            curl_setopt($curl, CURLOPT_POST, true); 
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($datosSegundaMano)); 
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
            curl_setopt($curl, CURLOPT_TIMEOUT, 2); 
            
            curl_exec($curl);

            header("Location: ../prendaSubida.php");
            exit;
        }else{
            header("Location: ../perfil.php?mensaje=subido");
            exit;
        }

    }else{
        echo "error al guardar la foto";
    }

    }

}else{
    header("Location: ../index.php?mensaje=login_requerido");
    exit();
}

?>