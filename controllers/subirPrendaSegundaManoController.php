<?php

session_start();

if( $_SERVER['REQUEST_METHOD'] == 'POST' &&$_SESSION["usuario_id"]){
    
    $nombrePrenda = isset($_POST["nombrePrenda"]) ? trim($_POST["nombrePrenda"]) : "";
    $precioPrenda = isset($_POST["precioPrenda"]) ? $_POST["precioPrenda"] : -1;
    $tallaPrenda = isset($_POST["tallaPrenda"]) ? $_POST["tallaPrenda"] : "";

    $idUsu = $_SESSION["usuario_id"];
    $db = new Database();
    $producto = new Producto($db->conectar());

    if(isset($_FILES["foto"]) && $_FILES["foto"]["error"] === 0){

    $nombreFoto = time() . "-" . $_FILES["foto"]["name"];

    $rutaFotos = "../public/img/" . $nombreFoto;

    $rutaFotosBaseDatos = "public/img/" . $nombreFoto;

    if(move_uploaded_file($_FILES["foto"]["tmp_name"], $rutaFotos) ){

    }else{
        echo "error al guardar la foto";
    }

    }

}else{
    header("Location: index.php?mensaje=login_requerido");
    exit();
}

?>