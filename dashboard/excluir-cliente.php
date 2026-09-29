<?php

require_once 'proteger-admin.php';
require_once '../crud.php';

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: clientes.php");
    exit;
}

$idUsuario = (int) $_GET["id"];

/* Verifica se realmente é um CLIENTE */
$stmt = $pdo->prepare(
    "SELECT id_usuario
     FROM usuario
     WHERE id_usuario = ?
     AND tipo_usuario = 'CLIENTE'"
);

$stmt->execute([$idUsuario]);

$cliente = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cliente) {
    header("Location: clientes.php");
    exit;
}

/* Verifica se possui pedidos */
$stmt = $pdo->prepare(
    "SELECT COUNT(*)
     FROM pedido
     WHERE id_usuario = ?"
);

$stmt->execute([$idUsuario]);

$possuiPedidos = $stmt->fetchColumn() > 0;

/* Verifica se possui empréstimos */
$stmt = $pdo->prepare(
    "SELECT COUNT(*)
     FROM emprestimo
     WHERE id_usuario = ?"
);

$stmt->execute([$idUsuario]);

$possuiEmprestimos = $stmt->fetchColumn() > 0;

if ($possuiPedidos || $possuiEmprestimos) {

    header(
        "Location: clientes.php?erro=cliente_vinculado"
    );

    exit;
}

/* Exclui carrinho do cliente, se existir */
$stmt = $pdo->prepare(
    "SELECT id_carrinho
     FROM carrinho
     WHERE id_usuario = ?"
);

$stmt->execute([$idUsuario]);

$carrinhos = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($carrinhos as $carrinho) {

    $stmtItens = $pdo->prepare(
        "DELETE FROM item_carrinho
         WHERE id_carrinho = ?"
    );

    $stmtItens->execute([
        $carrinho["id_carrinho"]
    ]);
}

$stmt = $pdo->prepare(
    "DELETE FROM carrinho
     WHERE id_usuario = ?"
);

$stmt->execute([$idUsuario]);

/* Finalmente exclui o cliente */
$stmt = $pdo->prepare(
    "DELETE FROM usuario
     WHERE id_usuario = ?
     AND tipo_usuario = 'CLIENTE'"
);

$stmt->execute([$idUsuario]);

header("Location: clientes.php?excluido=1");
exit;