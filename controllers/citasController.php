<?php
// controllers/citasController.php
require_once '../config/db.php';
require_once '../includes/auth.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $usuario_id = $_SESSION['usuario_id']; // El ID del usuario logueado
    $fecha = $_POST['fecha']; // Ejemplo: 2026-04-15
    $hora = $_POST['hora']; // Ejemplo: 17:00
    $motivoForm = $_POST['motivo'];
    $comentarios = isset($_POST['comentarios']) ? trim($_POST['comentarios']) : '';

    // Como tu base de datos no tiene campo comentarios, lo juntamos con el motivo
    $motivoFinal = $motivoForm;
    if (!empty($comentarios)) {
        $motivoFinal .= " | Notas: " . $comentarios;
    }

    // Juntamos la fecha y la hora para tu columna DATETIME
    $fecha_cita_datetime = $fecha . ' ' . $hora . ':00';

    $db = new Database();
    $conn = $db->conectar();

    // ÚLTIMA COMPROBACIÓN: Por si dos usuarios han intentado reservar a la vez
    $sqlCheck = "SELECT COUNT(*) as total FROM citas WHERE fecha_cita = :fecha_cita AND estado != 'cancelada'";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bindParam(':fecha_cita', $fecha_cita_datetime);
    $stmtCheck->execute();
    $resultado = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if ($resultado['total'] >= 10) {
        // Si justo se llenó, lo mandamos de vuelta con error
        header("Location: ../citas.php?error=aforo_completo");
        exit();
    }

    // SI HAY HUECO, GUARDAMOS LA CITA EN TU TABLA
    $sqlInsert = "INSERT INTO citas (usuario_id, fecha_cita, motivo, estado) VALUES (:usuario_id, :fecha_cita, :motivo, 'confirmada')";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bindParam(':usuario_id', $usuario_id);
    $stmtInsert->bindParam(':fecha_cita', $fecha_cita_datetime);
    $stmtInsert->bindParam(':motivo', $motivoFinal);

    if ($stmtInsert->execute()) {
        // Redirigimos al perfil para que vea su cita guardada
        header("Location: ../reservaConfirmada.php");
        exit();
    } else {
        header("Location: ../citas.php?error=error_bd");
        exit();
    }
}
?>