<?php
require_once 'proteger-admin.php';
require_once '../crud.php';

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: pedidos.php");
    exit;
}

$idPedido = (int) $_POST["id_pedido"];
$status = $_POST["status"];

$statusPermitidos = [
    "PENDENTE",
    "PAGO",
    "ENVIADO",
    "ENTREGUE",
    "CANCELADO"
];

if (!in_array($status, $statusPermitidos)) {
    die("Status inválido.");
}

update(
    $pdo,
    "pedido",
    ["status" => $status],
    "id_pedido = $idPedido"
);

header("Location: pedidos.php");
exit;