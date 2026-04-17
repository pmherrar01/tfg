<?php
require_once '../config/db.php';
require_once '../includes/auth.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $idUsu = $_SESSION['usuario_id']; 
    $fecha = $_POST['fecha']; 
    $hora = $_POST['hora']; 
    $motivoForm = $_POST['motivo'];
    $comentarios = isset($_POST['comentarios']) ? trim($_POST['comentarios']) : '';

    $motivoFinal = $motivoForm;
    if (!empty($comentarios)) {
        $motivoFinal .= " | Notas: " . $comentarios;
    }

    $fechaCita = $fecha . ' ' . $hora . ':00';

    $db = new Database();
    $coexion = $db->conectar();

    $sqlCheck = "SELECT COUNT(*) as total FROM citas WHERE fecha_cita = :fecha_cita AND estado != 'cancelada'";
    $stmtCheck = $coexion->prepare($sqlCheck);
    $stmtCheck->bindParam(':fecha_cita', $fechaCita);
    $stmtCheck->execute();
    $resultado = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if ($resultado['total'] >= 10) {
        header("Location: ../citas.php?error=aforo_completo");
        exit();
    }

    $sql = "INSERT INTO citas (usuario_id, fecha_cita, motivo, estado) VALUES (:usuario_id, :fecha_cita, :motivo, 'confirmada')";
    $sentencia = $coexion->prepare($sql);
    $sentencia->bindParam(':usuario_id', $idUsu);
    $sentencia->bindParam(':fecha_cita', $fechaCita);
    $sentencia->bindParam(':motivo', $motivoFinal);

    if ($sentencia->execute()) {



        header("Location: ../reservaConfirmada.php");
        exit();
    } else {
        header("Location: ../citas.php?error=error_bd");
        exit();
    }
}
?>