<?php
session_start();

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/producto.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION["usuario_id"])) {
    
    $db = new Database();
    $conexion = $db->conectar();
    $producto = new Producto($conexion);

    $idUsu = $_SESSION["usuario_id"];
    $idPrenda = $_POST['idPrenda'];
    $nombrePrenda = trim($_POST['nombrePrenda']);
    $precioPrenda = $_POST['precioPrenda'];
    $tallaPrenda = $_POST['tallaPrenda'];
    $colorPrenda = $_POST['colorPrenda'];
    $tipoPrenda = $_POST['tipoPrenda'];

    $actualizado = $producto->actualizarDatosPrendaSegundaMano($idPrenda, $nombrePrenda, $precioPrenda, $tipoPrenda, $idUsu, $colorPrenda, $tallaPrenda);

    if ($actualizado) {
        
        if (isset($_POST['fotosABorrar']) && is_array($_POST['fotosABorrar'])) {
            foreach ($_POST['fotosABorrar'] as $idFotoBorrar) {
                $producto->borrarImagenPrenda($idFotoBorrar); 
            }
        }

        if (isset($_FILES['fotosNuevas']) && !empty($_FILES['fotosNuevas']['name'])) {
            
            $totalFotos = count($_FILES['fotosNuevas']['name']);
            
            for ($i = 0; $i < $totalFotos; $i++) {
                if ($_FILES['fotosNuevas']['error'][$i] === 0) {
                    
                    $nombreFoto = time() . "-" . $i . "-" . $_FILES['fotosNuevas']['name'][$i];
                    $rutaMover = "../public/img/" . $nombreFoto;
                    $rutaBaseDatos = "public/img/" . $nombreFoto;

                    if (move_uploaded_file($_FILES['fotosNuevas']['tmp_name'][$i], $rutaMover)) {
                        $producto->anadirImagenPrenda($idPrenda, $colorPrenda, $rutaBaseDatos);
                    }
                }
            }
        }

        header("Location: ../perfil.php?seccion=prendas&mensaje=prenda_actualizada");
        exit;

    } else {
        header("Location: ../perfil.php?seccion=prendas&error=error_actualizar");
        exit;
    }

} else {
    header("Location: ../index.php?mensaje=login_requerido");
    exit;
}