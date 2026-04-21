<?php
session_start();
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $json = file_get_contents('php://input');
    $datos = json_decode($json, true);

    if (isset($datos['mensaje']) && !empty($datos['mensaje'])) {
        $mensajeUsuario = trim($datos['mensaje']);

        $datosN8n = [
            "mensaje" => $mensajeUsuario,
            "sessionId" => session_id()
        ];

        $urlWebhookChatbot = "http://localhost:5678/webhook/chatBot";

        $curl = curl_init($urlWebhookChatbot);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($datosN8n));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 15);

        $respuesta_n8n = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($http_code === 200) {
            $respuestaDecodificada = json_decode($respuesta_n8n, true);
            $textoRespuesta = "Error leyendo la consulta.";

            if (isset($respuestaDecodificada['output'])) {
                $textoRespuesta = $respuestaDecodificada['output'];
            } else if (isset($respuestaDecodificada['text'])) {
                $textoRespuesta = $respuestaDecodificada['text'];
            } else if (is_string($respuestaDecodificada)) {
                $textoRespuesta = $respuestaDecodificada;
            }

            echo json_encode(["status" => "success", "respuesta" => $textoRespuesta]);
        } else {
            echo json_encode(["status" => "error", "message" => "Mis circuitos están saturados."]);
        }
    }
}
