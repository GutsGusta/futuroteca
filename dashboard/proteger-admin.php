<?php
require_once 'proteger-admin.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (
    !isset($_SESSION["id_usuario"]) ||
    !isset($_SESSION["tipo_usuario"]) ||
    $_SESSION["tipo_usuario"] !== "ADMIN"
) {
    header("Location: ../login.php");
    exit;
}