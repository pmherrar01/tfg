<?php
require_once '../config/db.php';
require_once '../includes/auth.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $usuario_id = $_SESSION['usuario_id']; 
    $fecha = $_POST['fecha']; 
    $hora = $_POST['hora']; 
    $motivoForm = $_POST['motivo'];
    $comentarios = isset($_POST['comentarios']) ? trim($_POST['comentarios']) : '';

    $motivoFinal = $motivoForm;
    if (!empty($comentarios)) {
        $motivoFinal .= " | Notas: " . $comentarios;
    }

    $fecha_cita_datetime = $fecha . ' ' . $hora . ':00';

    $db = new Database();
    $conn = $db->conectar();

    $sqlCheck = "SELECT COUNT(*) as total FROM citas WHERE fecha_cita = :fecha_cita AND estado != 'cancelada'";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bindParam(':fecha_cita', $fecha_cita_datetime);
    $stmtCheck->execute();
    $resultado = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if ($resultado['total'] >= 10) {
        header("Location: ../citas.php?error=aforo_completo");
        exit();
    }

    $sqlInsert = "INSERT INTO citas (usuario_id, fecha_cita, motivo, estado) VALUES (:usuario_id, :fecha_cita, :motivo, 'confirmada')";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bindParam(':usuario_id', $usuario_id);
    $stmtInsert->bindParam(':fecha_cita', $fecha_cita_datetime);
    $stmtInsert->bindParam(':motivo', $motivoFinal);

    if ($stmtInsert->execute()) {
        header("Location: ../reservaConfirmada.php");
        exit();
    } else {
        header("Location: ../citas.php?error=error_bd");
        exit();
    }
}
?>