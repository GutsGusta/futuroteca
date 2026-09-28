<?php
require_once 'proteger-admin.php';
require_once '../crud.php';

if (!isset($_GET["id"])) {
    header("Location: emprestimos.php");
    exit;
}

$idEmprestimo = (int) $_GET["id"];

$emprestimo = read(
    $pdo,
    "emprestimo",
    "id_emprestimo = $idEmprestimo"
);

if (!$emprestimo) {
    header("Location: emprestimos.php");
    exit;
}

if ($emprestimo["status"] != "DEVOLVIDO") {

    $idProduto = (int) $emprestimo["id_produto"];

    $produto = read(
        $pdo,
        "produto",
        "id_produto = $idProduto"
    );

    update(
        $pdo,
        "emprestimo",
        [
            "data_devolucao_real" => date("Y-m-d"),
            "status" => "DEVOLVIDO"
        ],
        "id_emprestimo = $idEmprestimo"
    );

    if ($produto) {

        $novoEstoque = $produto["estoque"] + 1;

        update(
            $pdo,
            "produto",
            ["estoque" => $novoEstoque],
            "id_produto = $idProduto"
        );
    }
}

header("Location: emprestimos.php");
exit;