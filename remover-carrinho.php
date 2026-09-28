<?php

session_start();

require_once 'crud.php';

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET["id"])) {
    header("Location: carrinho.php");
    exit;
}

$idItem = (int) $_GET["id"];
$idUsuario = (int) $_SESSION["id_usuario"];

$sql = "DELETE item_carrinho
        FROM item_carrinho
        INNER JOIN carrinho
            ON item_carrinho.id_carrinho = carrinho.id_carrinho
        WHERE item_carrinho.id_item_carrinho = ?
        AND carrinho.id_usuario = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$idItem, $idUsuario]);

header("Location: carrinho.php");
exit;