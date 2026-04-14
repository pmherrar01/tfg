<?php

require_once __DIR__ . "/../models/usuario.php";

$db = new Database();
$user = new Usuario($db->conectar());

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["accion"]) && $_POST["accion"] === "recuperarPassword"){
  
    $emailCambiarPass = isset($_POST["email"]) ? $_POST["email"] : "";
    
    if($user->buscarUsuPorCorreo($emailCambiarPass)){
        $token;
    }else{
        header("Location:recuperarPassword.php?error=emailNoExiste");
        exit;
    }

}

?>