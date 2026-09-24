<?php

require_once '../crud.php';

// Verifica se recebeu o ID
if (!isset($_GET["id"])) {
    header("Location: produtos.php");
    exit;
}

$id = (int) $_GET["id"];

// Busca o produto antes de excluir
$produto = read(
    $pdo,
    "produto",
    "id_produto = $id"
);

// Se o produto não existir
if (!$produto) {
    header("Location: produtos.php");
    exit;
}

// Exclui o produto do banco
delete(
    $pdo,
    "produto",
    "id_produto = $id"
);

// Exclui também a imagem da pasta uploads
if (!empty($produto["imagem"])) {

    $caminhoImagem = "../uploads/" . $produto["imagem"];

    if (file_exists($caminhoImagem)) {
        unlink($caminhoImagem);
    }
}

// Volta para produtos
header("Location: produtos.php");
exit;

?>