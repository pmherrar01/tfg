<?php
session_start();

require_once '../config/db.php';
require_once '../models/usuario.php';

$db = new Database();
$usuario = new Usuario($db->conectar());

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if ($_POST['accion'] == 'registro') {

        $nombreUsu = isset($_POST["nombre"]) ? $_POST["nombre"] : 0;
        $apellidosUsu = isset($_POST["apellidos"]) ? $_POST["apellidos"] : 0;
        $correoUsu = isset($_POST["email"]) ? $_POST["email"] : 0;
        $pasUsu = isset($_POST["password"]) ? $_POST["password"] : 0;


        // Llamas a la función registrar
        if ($usuario->registrar()) {
            // Si va bien, lo devuelves al index
            header("Location: ../index.php?mensaje=registro_exito");
            exit;
        } else {
            // Si falla (ej: email repetido)
            header("Location: ../index.php?error=registro_fallo");
            exit;
        }

    } elseif ($_POST['accion'] == 'login') {
        // (De momento déjalo vacío, lo haremos en el siguiente paso)
    }
}