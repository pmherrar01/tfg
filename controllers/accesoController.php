<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['codigo']) && $_POST['codigo']) {

require_once __DIR__ . "/../config/db.php";

$db = new Database();
$conexion = $db->conectar();

$codigo =  trim(($_POST["codigo"]));
$sql = "SELECT * from codigos_accesos where codigo = :codigo AND usado = 0";
$sentencia = $conexion->prepare($sql);
$sentencia->execute([":codigo" => $codigo]);

$resultaado = $sentencia->fetch(PDO::FETCH_ASSOC);

if ($resultaado){

    $sql =  "UPDATE codigos_accesos SET usado = 1 WHERE codigo = :codigo";
    $sentencia = $conexion->prepare($sql);
    $sentencia -> execute(["codigo" => $codigo]);

    $_SESSION["acceso"] = true;

    header("Location: ../catalogo.php?coleccion=vip");
    exit();

}else{
    header("Location: ../index.php?acceso=false");
    exit();
}

}else{
    header("Location: ../index.php");
    exit();
}

?>