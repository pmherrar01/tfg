<?php
// Este archivo lo incluiremos solo en las páginas que queramos proteger
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    // Si no hay sesión, al index con un mensaje de error
    header("Location: index.php?error=acceso_denegado");
    exit;
}