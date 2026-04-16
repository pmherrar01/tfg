<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $json = file_get_contents('php://input');
    $datos = json_decode($json, true);

    if (isset($datos['email']) && !empty($datos['email'])) {
        $email = $datos['email'];
        $codigoAcceso = "herror"; 

        $urlWebhook = "http://localhost:5678/webhook-test/solicitarCodigo";

        $curl = curl_init($urlWebhook);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode([
            "email" => $email,
            "codigo" => $codigoAcceso
        ]));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        $respuesta = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($http_code === 200) {
            echo json_encode(["status" => "success"]);
        } else {
            // Si falla, mandamos el error rojo con el código exacto
            echo json_encode(["status" => "error", "message" => "Fallo n8n. Código HTTP: " . $http_code . " Respuesta: " . $respuesta]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Email no proporcionado"]);
    }
}