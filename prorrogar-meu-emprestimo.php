<?php

session_start();

require_once 'crud.php';

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: meus-emprestimos.php");
    exit;
}

$idUsuario = (int) $_SESSION["id_usuario"];
$idEmprestimo = (int) $_POST["id_emprestimo"];


/* PROCURA O EMPRÉSTIMO DO USUÁRIO */

$sql = "SELECT *
        FROM emprestimo
        WHERE id_emprestimo = ?
        AND id_usuario = ?
        AND status = 'ATIVO'
        AND prorrogado = 0";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    $idEmprestimo,
    $idUsuario
]);

$emprestimo = $stmt->fetch(PDO::FETCH_ASSOC);


/* VERIFICA SE PODE PRORROGAR */

if (!$emprestimo) {
    header("Location: meus-emprestimos.php");
    exit;
}


/* ADICIONA 7 DIAS */

$novaData = date(
    "Y-m-d",
    strtotime(
        $emprestimo["data_devolucao_prevista"] . " +7 days"
    )
);


/* ATUALIZA O EMPRÉSTIMO */

$sql = "UPDATE emprestimo
        SET data_devolucao_prevista = ?,
            prorrogado = 1
        WHERE id_emprestimo = ?
        AND id_usuario = ?";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    $novaData,
    $idEmprestimo,
    $idUsuario
]);


/* VOLTA PARA A PÁGINA */

header("Location: meus-emprestimos.php");
exit;