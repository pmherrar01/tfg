<?php

require_once __DIR__ . "/../models/usuario.php";
require_once __DIR__ . "/../config/db.php";

$db = new Database();
$user = new Usuario($db->conectar());

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["accion"]) && $_POST["accion"] === "recuperarPassword"){
  
    $emailCambiarPass = isset($_POST["email"]) ? $_POST["email"] : "";
    
    if($user->buscarUsuPorCorreo($emailCambiarPass)){
        $token = $user->anadirTokenRecuperarPass($emailCambiarPass);

        $datosParaN8n = json_encode([
            "email" => $emailCambiarPass,
            "token" => $token
        ]);

$urlWebhook = "http://127.0.0.1:5678/webhook-test/recuperar-password-tfg";

        $curl = curl_init($urlWebhook);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $datosParaN8n);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5); 

        // Disparamos la petición a n8n y guardamos la respuesta
        $respuesta = curl_exec($curl);
        
        // CHIVATO DE ERRORES: Si algo falla, que detenga la web y nos lo diga
        if(curl_errno($curl)){
            die('Error catastrófico de cURL: ' . curl_error($curl));
        }
        
        curl_close($curl);

        header("Location: ../recuperarPassword.php?mensaje=correoEnviado");
        exit;
    }else{
        header("Location: ../recuperarPassword.php?error=emailNoExiste");
        exit;
    }

}

?>