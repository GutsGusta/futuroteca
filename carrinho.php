<?php

session_start();

require_once 'crud.php';

// Usuário precisa estar logado
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

$idUsuario = (int) $_SESSION["id_usuario"];
$mensagem = "";

// ADICIONAR PRODUTO AO CARRINHO
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $idProduto = (int) $_POST["id_produto"];
    $quantidade = (int) $_POST["quantidade"];
    $tipoOperacao = $_POST["tipo_operacao"];

    if ($quantidade < 1) {
        $quantidade = 1;
    }

    // Busca o produto
    $produto = read(
        $pdo,
        "produto",
        "id_produto = $idProduto"
    );

    if (!$produto) {
        die("Produto não encontrado.");
    }

    if ($quantidade > $produto["estoque"]) {
        die("Quantidade maior que o estoque disponível.");
    }

    // Procura o carrinho do usuário
    $carrinho = read(
        $pdo,
        "carrinho",
        "id_usuario = $idUsuario"
    );

    // Se ainda não existir, cria
    if (!$carrinho) {

        $idCarrinho = create(
            $pdo,
            "carrinho",
            [
                "id_usuario" => $idUsuario
            ]
        );

    } else {

        $idCarrinho = $carrinho["id_carrinho"];
    }

    // Verifica se esse produto/operação já está no carrinho
    $itemExistente = read(
        $pdo,
        "item_carrinho",
        "id_carrinho = $idCarrinho
        AND id_produto = $idProduto
        AND tipo_operacao = " . $pdo->quote($tipoOperacao)
    );

    if ($itemExistente) {

        $novaQuantidade =
            $itemExistente["quantidade"] + $quantidade;

        if ($novaQuantidade > $produto["estoque"]) {
            $novaQuantidade = $produto["estoque"];
        }

        update(
            $pdo,
            "item_carrinho",
            ["quantidade" => $novaQuantidade],
            "id_item_carrinho = " . (int) $itemExistente["id_item_carrinho"]
        );

    } else {

        create(
            $pdo,
            "item_carrinho",
            [
                "id_carrinho" => $idCarrinho,
                "id_produto" => $idProduto,
                "quantidade" => $quantidade,
                "tipo_operacao" => $tipoOperacao
            ]
        );
    }

    header("Location: carrinho.php");
    exit;
}


// BUSCAR O CARRINHO DO USUÁRIO

$carrinho = read(
    $pdo,
    "carrinho",
    "id_usuario = $idUsuario"
);

$itens = [];

if ($carrinho) {

    $idCarrinho = (int) $carrinho["id_carrinho"];

    $sql = "SELECT
            item_carrinho.*,
            produto.titulo,
            produto.preco,
            produto.preco_promocional,
            produto.estoque
            FROM item_carrinho
            INNER JOIN produto
                ON item_carrinho.id_produto = produto.id_produto
            WHERE item_carrinho.id_carrinho = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$idCarrinho]);

    $itens = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$total = 0;

foreach ($itens as $item) {

    if ($item["tipo_operacao"] == "COMPRA") {

        if (
            !empty($item["preco_promocional"]) &&
            $item["preco_promocional"] < $item["preco"]
        ) {
            $precoFinal = $item["preco_promocional"];
        } else {
            $precoFinal = $item["preco"];
        }

        $total += $precoFinal * $item["quantidade"];
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Carrinho | Futuroteca</title>

    <link rel="stylesheet" href="css/header-footer.css">
    <link rel="stylesheet" href="css/compra.css">

</head>

<body>

    <?php require_once "partials/header.php"; ?>

    <main class="detalhes-container">

        <div class="detalhes-info">

            <h1>Meu Carrinho</h1>

            <?php if (count($itens) == 0): ?>

                <p>Seu carrinho está vazio.</p>

                <a href="livros.php">
                    Ver produtos
                </a>

            <?php else: ?>

                <table>

                    <thead>

                        <tr>
                            <th>Produto</th>
                            <th>Operação</th>
                            <th>Quantidade</th>
                            <th>Preço</th>
                            <th>Ações</th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($itens as $item): ?>

                            <tr>

                                <td>
                                    <?= htmlspecialchars($item["titulo"]) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($item["tipo_operacao"]) ?>
                                </td>

                                <td>
                                    <?= $item["quantidade"] ?>
                                </td>

                                <td>

                                    <?php if ($item["tipo_operacao"] == "COMPRA"): ?>

                                        <?php
                                        if (
                                            !empty($item["preco_promocional"]) &&
                                            $item["preco_promocional"] < $item["preco"]
                                        ) {
                                            $precoItem = $item["preco_promocional"];
                                        } else {
                                            $precoItem = $item["preco"];
                                        }
                                        ?>

                                        R$ <?= number_format(
                                            $precoItem * $item["quantidade"],
                                            2,
                                            ",",
                                            "."
                                        ) ?>

                                    <?php else: ?>

                                        Empréstimo

                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

                <h2>
                    Total: R$ <?= number_format($total, 2, ",", ".") ?>
                </h2>

                <?php if ($total > 0): ?>

                    <a href="finalizar-compra.php">
                        Finalizar compra
                    </a>

                <?php endif; ?>

            <?php endif; ?>

        </div>

        <td>
            <a href="remover-carrinho.php?id=<?= $item["id_item_carrinho"] ?>"
                onclick="return confirm('Remover este produto do carrinho?')">
                Remover
            </a>
        </td>

    </main>

</body>

</html>