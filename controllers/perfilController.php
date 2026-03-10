<?php
// 1. Cargamos el guardián de sesión usando __DIR__ para que la ruta sea a prueba de balas
require_once __DIR__ . "/../includes/auth.php";

// 2. Cargamos base de datos y modelo
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../models/usuario.php";

$db = new Database();
$user = new Usuario($db->conectar());

$idUsuarioSession = $_SESSION["usuario_id"];

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $user->setIdUsuario($idUsuarioSession);
    $user->setNombre(!empty($_POST["nombre"]) ? trim($_POST["nombre"]) : "");
    $user->setApellidos(!empty($_POST["apellidos"]) ? trim($_POST["apellidos"]) : "");
    $user->setTelefono(!empty($_POST["telefono"]) ? trim($_POST["telefono"]) : null);
    $user->setCiudad(!empty($_POST["ciudad"]) ? trim($_POST["ciudad"]) : null);
    $user->setCodigoPostal(!empty($_POST["codigoPostal"]) ? trim($_POST["codigoPostal"]) : null);
    $user->setDireccion(!empty($_POST["direccion"]) ? trim($_POST["direccion"]) : null);

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