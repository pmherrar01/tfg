<?php
require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../models/usuario.php";
require_once __DIR__ . "/../models/pedido.php";
require_once __DIR__ . "/../models/favorito.php";

$favoritoModel = new Favorito($db->conectar());
$listaFavoritos = $favoritoModel->listarFavoritos($_SESSION['usuario_id']);

$db = new Database();
$user = new Usuario($db->conectar());
$pedido = new Pedido($db->conectar());

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

$listaPedidos = $pedido->listarPedidos($_SESSION["usuario_id"]);
$listaFavoritos;

?>