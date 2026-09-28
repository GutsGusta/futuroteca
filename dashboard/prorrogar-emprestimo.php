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

if ($emprestimo["status"] == "ATIVO") {

    $novaData = date(
        "Y-m-d",
        strtotime($emprestimo["data_devolucao_prevista"] . " +7 days")
    );

    update(
        $pdo,
        "emprestimo",
        ["data_devolucao_prevista" => $novaData],
        "id_emprestimo = $idEmprestimo"
    );
}

header("Location: emprestimos.php");
exit;