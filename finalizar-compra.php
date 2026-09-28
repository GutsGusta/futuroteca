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

    $tipoEntrega = $_POST["tipo_entrega"];

    // Retirada não possui frete
    if ($tipoEntrega == "RETIRADA") {

        $frete = 0;

    } else {

        // Frete grátis acima de R$ 120
        if ($total >= 120) {
            $frete = 0;
        } else {
            $frete = 15;
        }
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

            if ($quantidade > $item["estoque"]) {
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

            $novoEstoque = $item["estoque"] - $quantidade;

            update(
                $pdo,
                "produto",
                ["estoque" => $novoEstoque],
                "id_produto = $idProduto"
            );
        }

        delete(
            $pdo,
            "item_carrinho",
            "id_carrinho = $idCarrinho"
        );

        $pdo->commit();

        header("Location: homepage.php");
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

    <link rel="stylesheet" href="css/header-footer.css">
    <link rel="stylesheet" href="css/compra.css">
</head>

<body>

    <?php require_once "partials/header.php"; ?>

    <main class="detalhes-container">

        <div class="detalhes-info">

            <h1>Finalizar compra</h1>

            <p>
                Total dos produtos:
                <strong>
                    R$ <?= number_format($total, 2, ",", ".") ?>
                </strong>
            </p>

            <?php if ($total >= 120): ?>

                <p>🎉 Sua compra possui frete grátis!</p>

            <?php endif; ?>

            <form method="POST">

                <label for="tipo_entrega">
                    Forma de entrega:
                </label>

                <select name="tipo_entrega" id="tipo_entrega" required>

                    <option value="RETIRADA">
                        Retirada
                    </option>

                    <option value="ENTREGA">
                        Entrega
                    </option>

                </select>

                <br><br>

                <button type="submit">
                    Confirmar
                </button>

            </form>

        </div>

    </main>

</body>

</html>