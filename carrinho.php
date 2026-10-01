<?php

session_start();

require_once 'crud.php';

// Usuário precisa estar logado
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

$idUsuario = (int) $_SESSION["id_usuario"];

// =====================================================
// ADICIONAR PRODUTO AO CARRINHO
// =====================================================

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

    // Verifica se o produto já está no carrinho
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
            [
                "quantidade" => $novaQuantidade
            ],
            "id_item_carrinho = " .
            (int) $itemExistente["id_item_carrinho"]
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


// =====================================================
// BUSCAR O CARRINHO DO USUÁRIO
// =====================================================

$carrinho = read(
    $pdo,
    "carrinho",
    "id_usuario = $idUsuario"
);

$itens = [];

if ($carrinho) {

    $idCarrinho = (int) $carrinho["id_carrinho"];

    $sql = "
        SELECT
            item_carrinho.*,
            produto.titulo,
            produto.preco,
            produto.preco_promocional,
            produto.estoque,
            produto.imagem
        FROM item_carrinho

        INNER JOIN produto
            ON item_carrinho.id_produto = produto.id_produto

        WHERE item_carrinho.id_carrinho = ?
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([$idCarrinho]);

    $itens = $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// =====================================================
// CALCULAR TOTAL
// =====================================================

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

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Carrinho | Futuroteca</title>

    <link
        rel="stylesheet"
        href="css/header-footer.css"
    >

    <link
        rel="stylesheet"
        href="css/carrinho.css"
    >

</head>


<body>

    <?php require_once "partials/header.php"; ?>


    <main class="pagina-carrinho">

        <div class="carrinho-container">

            <!-- CABEÇALHO -->

            <div class="carrinho-header">

                <div>

                    <span class="carrinho-label">
                        FUTUROTECA
                    </span>

                    <h1>Meu Carrinho</h1>

                    <p>
                        Confira os produtos que você selecionou.
                    </p>

                </div>

                <span class="quantidade-itens">
                    <?= count($itens) ?>
                    <?= count($itens) == 1 ? 'item' : 'itens' ?>
                </span>

            </div>


            <?php if (count($itens) > 0): ?>


                <!-- TABELA -->

                <div class="tabela-container">

                    <table class="tabela-carrinho">

                        <thead>

                            <tr>

                                <th class="col-produto">
                                    Produto
                                </th>

                                <th>
                                    Operação
                                </th>

                                <th>
                                    Quantidade
                                </th>

                                <th>
                                    Preço
                                </th>

                                <th>
                                    Ações
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <?php foreach ($itens as $item): ?>

                                <?php

                                if (
                                    !empty($item["preco_promocional"]) &&
                                    $item["preco_promocional"] < $item["preco"]
                                ) {

                                    $precoItem =
                                        $item["preco_promocional"];

                                    $temPromocao = true;

                                } else {

                                    $precoItem =
                                        $item["preco"];

                                    $temPromocao = false;
                                }

                                ?>


                                <tr>

                                    <!-- PRODUTO -->

                                    <td class="produto-info">

                                        <div class="produto-imagem">

                                            <img
                                                src="<?= !empty($item["imagem"])
                                                    ? 'uploads/' . htmlspecialchars($item["imagem"])
                                                    : 'uploads/verity.png'
                                                ?>"
                                                alt="<?= htmlspecialchars($item["titulo"]) ?>"
                                            >

                                        </div>


                                        <div class="produto-nome">

                                            <strong>
                                                <?= htmlspecialchars($item["titulo"]) ?>
                                            </strong>

                                        </div>

                                    </td>


                                    <!-- OPERAÇÃO -->

                                    <td>

                                        <span
                                            class="badge-operacao
                                            <?= $item["tipo_operacao"] == "COMPRA"
                                                ? "badge-compra"
                                                : "badge-emprestimo"
                                            ?>"
                                        >

                                            <?= htmlspecialchars(
                                                $item["tipo_operacao"]
                                            ) ?>

                                        </span>

                                    </td>


                                    <!-- QUANTIDADE -->

                                    <td>

                                        <span class="quantidade">

                                            <?= $item["quantidade"] ?>

                                        </span>

                                    </td>


                                    <!-- PREÇO -->

                                    <td class="preco">

                                        <?php if ($item["tipo_operacao"] == "COMPRA"): ?>

                                            <?php if ($temPromocao): ?>

                                                <span class="preco-antigo">
                                                    R$
                                                    <?= number_format(
                                                        $item["preco"],
                                                        2,
                                                        ',',
                                                        '.'
                                                    ) ?>
                                                </span>

                                            <?php endif; ?>


                                            <strong>
                                                R$
                                                <?= number_format(
                                                    $precoItem,
                                                    2,
                                                    ',',
                                                    '.'
                                                ) ?>
                                            </strong>

                                        <?php else: ?>

                                            <span class="preco-emprestimo">
                                                Gratuito
                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <!-- REMOVER -->

                                    <td>

                                        <a
                                            href="remover-carrinho.php?id=<?= $item["id_item_carrinho"] ?>"
                                            class="btn-remover"
                                            onclick="return confirm('Remover este produto do carrinho?')"
                                        >
                                            Remover
                                        </a>

                                    </td>

                                </tr>


                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>


                <!-- RESUMO -->

                <div class="carrinho-resumo">

                    <div class="resumo-info">

                        <span>
                            Total da compra
                        </span>

                        <strong>
                            R$
                            <?= number_format(
                                $total,
                                2,
                                ',',
                                '.'
                            ) ?>
                        </strong>

                    </div>


                    <a
                        href="finalizar-compra.php"
                        class="btn-finalizar"
                    >
                        Finalizar compra
                    </a>

                </div>


                <a
                    href="livros.php"
                    class="continuar-comprando"
                >
                    ← Continuar comprando
                </a>


            <?php else: ?>


                <!-- CARRINHO VAZIO -->

                <div class="carrinho-vazio">

                    <div class="icone-carrinho">
                        shopping_bag
                    </div>

                    <h2>
                        Seu carrinho está vazio
                    </h2>

                    <p>
                        Você ainda não adicionou nenhum produto.
                    </p>

                    <a
                        href="livros.php"
                        class="btn-ver-produtos"
                    >
                        Ver produtos
                    </a>

                </div>


            <?php endif; ?>

        </div>

    </main>


</body>

</html>