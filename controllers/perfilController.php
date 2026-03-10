<?php

session_start();

if(!isset($_SESSION["usuario_id"])){
    header("Location: ../index.php");
    exit;

}

require_once "../html/models/usuario.php";
require_once "../html/config/db.php";

$db = new Database();
$user = new Usuario($db->conectar());

$idUsuarioSession = $_SESSION["usuario_id"];

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $user->setIdUsuario($idUsuarioSession);
    $user->setNombre(isset($_POST["nombre"]) ? trim($_POST["nombre"]) : "");
    $user->setApellidos(isset($_POST["apellidos"]) ? trim($_POST["apellidos"]) : "");
    $user->setTelefono(isset($_POST["telefono"]) ? trim($_POST["telefono"]) : 0);
    $user->setCiudad(isset($_POST["ciudad"]) ? trim($_POST["ciudad"]) : "");
    $user->setCodigoPostal(isset($_POST["codigoPostal"]) ? trim($_POST["codigoPostal"]) : 0);
    $user->setDireccion(isset($_POST["direccion"]) ? trim($_POST["direccion"]) : "");

    if($user->actualizarDatosUsu()){
        $_SESSION["nombre"] = $_POST["nombre"];
        header("Location: ../perfil.php?mensaje=perfil_actualizado");
        exit;
    } else {
        header("Location: ../perfil.php?error=perfil_fallo");
        exit;
    }

}else{
    $datosUsu = $user->obtenerDatosUsu($idUsuarioSession);
}






?>