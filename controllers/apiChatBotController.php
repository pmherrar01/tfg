<?php
session_start();
require_once "../config/db.php"; // IMPORTANTE: Necesitamos la conexión
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $json = file_get_contents('php://input');
    $datos = json_decode($json, true);

    if (isset($datos['mensaje']) && !empty($datos['mensaje'])) {
        $mensajeUsuario = trim($datos['mensaje']);
        
        try {
            $db = new Database();
            $conexion = $db->conectar();
            
            $usuarioId = isset($_SESSION["usuario_id"]) ? $_SESSION["usuario_id"] : null;
            
            $sqlLog = "INSERT INTO chat_logs (usuario_id, mensaje) VALUES (:user_id, :mensaje)";
            $stmtLog = $conexion->prepare($sqlLog);
            $stmtLog->execute([
                ':user_id' => $usuarioId,
                ':mensaje' => $mensajeUsuario
            ]);
        } catch (Exception $e) {
        }

        $datosN8n = [
            "mensaje" => $mensajeUsuario,
            "sessionId" => session_id()
        ];

        $urlWebhookChatbot = "http://165.232.96.8:5678/webhook/chatBot"; 

        $curl = curl_init($urlWebhookChatbot);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($datosN8n));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60); 

        $respuesta_n8n = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($http_code === 200) {
            $respuestaDecodificada = json_decode($respuesta_n8n, true);
            $textoRespuesta = "Error leyendo la consulta.";

            if (isset($respuestaDecodificada['output'])) {
                $textoRespuesta = $respuestaDecodificada['output'];
            } else if (isset($respuestaDecodificada['text'])) {
                $textoRespuesta = $respuestaDecodificada['text'];
            }

            echo json_encode(["status" => "success", "respuesta" => $textoRespuesta]);
        } else {
            echo json_encode(["status" => "error", "message" => "Mis circuitos están saturados."]);
        }
    }
}