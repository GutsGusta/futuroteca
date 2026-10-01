<?php

session_start();

require_once 'crud.php';

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

$idUsuario = (int) $_SESSION["id_usuario"];

$carrinho = read(
    $pdo,
    "carrinho",
    "id_usuario = $idUsuario"
);

if (!$carrinho) {
    header("Location: carrinho.php");
    exit;
}

$idCarrinho = (int) $carrinho["id_carrinho"];

$sql = "SELECT
            item_carrinho.*,
            produto.titulo,
            produto.preco,
            produto.tipo,
            produto.preco_promocional,
            produto.estoque
        FROM item_carrinho
        INNER JOIN produto
            ON item_carrinho.id_produto = produto.id_produto
        WHERE item_carrinho.id_carrinho = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$idCarrinho]);

$itens = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($itens) == 0) {
    header("Location: carrinho.php");
    exit;
}

$possuiProdutoFisico = false;

foreach ($itens as $item) {

    if ($item["tipo"] == "LIVRO") {
        $possuiProdutoFisico = true;
        break;
    }
}


// CALCULA TOTAL DAS COMPRAS

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


// FINALIZAR

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if ($possuiProdutoFisico) {

        $tipoEntrega = $_POST["tipo_entrega"] ?? "RETIRADA";

        if ($tipoEntrega == "RETIRADA") {

            $frete = 0;

        } else {

            if ($total >= 120) {
                $frete = 0;
            } else {
                $frete = 15;
            }
        }

    } else {

        // Pedido totalmente digital
        $tipoEntrega = "DIGITAL";
        $frete = 0;
    }

    $valorFinal = $total + $frete;

    $pdo->beginTransaction();

    try {

        $idPedido = null;

        // Só cria pedido se houver COMPRA
        if ($total > 0) {

            $idPedido = create(
                $pdo,
                "pedido",
                [
                    "id_usuario" => $idUsuario,
                    "valor_total" => $valorFinal,
                    "tipo_entrega" => $tipoEntrega,
                    "frete" => $frete,
                    "status" => "PENDENTE"
                ]
            );
        }

        foreach ($itens as $item) {

            $idProduto = (int) $item["id_produto"];
            $quantidade = (int) $item["quantidade"];

            if (
                $item["tipo"] == "LIVRO" &&
                $quantidade > $item["estoque"]
            ) {
                throw new Exception(
                    "Estoque insuficiente para " . $item["titulo"]
                );
            }

            if ($item["tipo_operacao"] == "COMPRA") {

                if (
                    !empty($item["preco_promocional"]) &&
                    $item["preco_promocional"] < $item["preco"]
                ) {
                    $precoUnitario = $item["preco_promocional"];
                } else {
                    $precoUnitario = $item["preco"];
                }

                create(
                    $pdo,
                    "item_pedido",
                    [
                        "id_pedido" => $idPedido,
                        "id_produto" => $idProduto,
                        "quantidade" => $quantidade,
                        "preco_unitario" => $precoUnitario
                    ]
                );

            } else {

                // Empréstimos: uma unidade por registro
                for ($i = 0; $i < $quantidade; $i++) {

                    create(
                        $pdo,
                        "emprestimo",
                        [
                            "id_usuario" => $idUsuario,
                            "id_produto" => $idProduto,
                            "data_emprestimo" => date("Y-m-d"),
                            "data_devolucao_prevista" =>
                                date("Y-m-d", strtotime("+14 days")),
                            "status" => "ATIVO"
                        ]
                    );
                }
            }

            if ($item["tipo"] == "LIVRO") {

                $novoEstoque = $item["estoque"] - $quantidade;

                update(
                    $pdo,
                    "produto",
                    ["estoque" => $novoEstoque],
                    "id_produto = $idProduto"
                );
            }
        }

        delete(
            $pdo,
            "item_carrinho",
            "id_carrinho = $idCarrinho"
        );

        $pdo->commit();

        header("Location: index.php");
        exit;

    } catch (Exception $e) {

        $pdo->rollBack();

        die("Erro ao finalizar: " . $e->getMessage());
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Finalizar Compra | Futuroteca</title>
    <link rel="icon" type="x-icon" href="./uploads/logo.png">
    <link rel="stylesheet" href="css/header-footer.css">
    <link rel="stylesheet" href="css/compra.css">
    <link rel="stylesheet" href="css/finalizar-compra.css">
</head>

<body>

    <?php require_once "partials/header.php"; ?>

    <main class="detalhes-container">

        <div class="finalizar-container">

            <h1>Finalizar compra</h1>

            <div class="resumo-compra">

                <div class="resumo-linha">
                    <span>Total dos produtos</span>

                    <strong>
                        R$ <?= number_format($total, 2, ',', '.') ?>
                    </strong>
                </div>

                <?php if ($possuiProdutoFisico && $total >= 120): ?>

                    <div class="frete-gratis">
                        🎉 Sua compra possui frete grátis!
                    </div>

                <?php endif; ?>

            </div>

            <form method="POST" class="form-finalizar">

                <?php if ($possuiProdutoFisico): ?>

                    <div class="campo-entrega">

                        <label for="tipo_entrega">
                            Forma de entrega
                        </label>

                        <select name="tipo_entrega" id="tipo_entrega" required>
                            <option value="RETIRADA">
                                Retirada
                            </option>

                            <option value="ENTREGA">
                                Entrega em casa
                            </option>
                        </select>

                    </div>

                <?php else: ?>

                    <div class="produto-digital">
                        💻 <strong>Compra digital</strong>
                        <p>
                            Este pedido possui apenas produtos digitais.
                            Não é necessário escolher entrega ou retirada.
                        </p>
                    </div>

                <?php endif; ?>

                <button type="submit" class="btn-confirmar">
                    Confirmar compra
                </button>

            </form>

        </div>

    </main>

</body>

</html>