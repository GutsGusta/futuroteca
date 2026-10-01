<?php

session_start();

require_once 'crud.php';

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

$idUsuario = (int) $_SESSION["id_usuario"];

$idPedido = isset($_GET["id"])
    ? (int) $_GET["id"]
    : 0;


$sql = "
    SELECT *
    FROM pedido
    WHERE id_pedido = ?
    AND id_usuario = ?
";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    $idPedido,
    $idUsuario
]);

$pedido = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$pedido) {
    header("Location: meus-pedidos.php");
    exit;
}


$sql = "
    SELECT
        item_pedido.*,
        produto.titulo,
        produto.autor,
        produto.imagem
    FROM item_pedido

    INNER JOIN produto
        ON item_pedido.id_produto = produto.id_produto

    WHERE item_pedido.id_pedido = ?
";

$stmt = $pdo->prepare($sql);

$stmt->execute([$idPedido]);

$itens = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>

<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <link rel="icon" type="x-icon" href="./uploads/logo.png">
    <title>
        Pedido #<?= $pedido["id_pedido"] ?> | Futuroteca
    </title>

    <link
        rel="stylesheet"
        href="css/header-footer.css"
    >

    <link
        rel="stylesheet"
        href="css/detalhes-pedido.css"
    >

</head>


<body>

<?php require_once "partials/header.php"; ?>


<main class="pagina-detalhes-pedido">

    <div class="pedido-container">

        <div class="pedido-header">

            <div>

                <span class="pedido-label">
                    FUTUROTECA
                </span>

                <h1>
                    Pedido #<?= $pedido["id_pedido"] ?>
                </h1>

                <p>
                    Confira as informações e os produtos
                    deste pedido.
                </p>

            </div>


            <span
                class="status-pedido status-<?= strtolower(
                    htmlspecialchars($pedido["status"])
                ) ?>"
            >
                <?= htmlspecialchars($pedido["status"]) ?>
            </span>

        </div>

        <div class="informacoes-pedido">

            <div class="informacao">

                <span class="informacao-label">
                    Data do pedido
                </span>

                <strong>

                    <?= date(
                        "d/m/Y H:i",
                        strtotime($pedido["data_pedido"])
                    ) ?>

                </strong>

            </div>


            <div class="informacao">

                <span class="informacao-label">
                    Tipo de entrega
                </span>

                <strong>

                    <?= htmlspecialchars(
                        $pedido["tipo_entrega"]
                    ) ?>

                </strong>

            </div>


            <div class="informacao">

                <span class="informacao-label">
                    Frete
                </span>

                <strong>

                    <?php if ($pedido["frete"] > 0): ?>

                        R$
                        <?= number_format(
                            $pedido["frete"],
                            2,
                            ",",
                            "."
                        ) ?>

                    <?php else: ?>

                        <span class="frete-gratis">
                            Grátis
                        </span>

                    <?php endif; ?>

                </strong>

            </div>


            <div class="informacao">

                <span class="informacao-label">
                    Total do pedido
                </span>

                <strong class="valor-total">

                    R$
                    <?= number_format(
                        $pedido["valor_total"],
                        2,
                        ",",
                        "."
                    ) ?>

                </strong>

            </div>

        </div>

        <div class="produtos-pedido">

            <div class="secao-titulo">

                <h2>
                    Produtos
                </h2>

                <span>
                    <?= count($itens) ?>
                    <?= count($itens) == 1
                        ? "produto"
                        : "produtos"
                    ?>
                </span>

            </div>


            <?php if (count($itens) == 0): ?>

                <div class="pedido-vazio">

                    <p>
                        Nenhum produto encontrado neste pedido.
                    </p>

                </div>

            <?php else: ?>


                <div class="tabela-produtos-container">

                    <table class="tabela-produtos">

                        <thead>

                            <tr>

                                <th>
                                    Produto
                                </th>

                                <th>
                                    Autor
                                </th>

                                <th>
                                    Quantidade
                                </th>

                                <th>
                                    Preço unitário
                                </th>

                                <th>
                                    Subtotal
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <?php foreach ($itens as $item): ?>

                                <?php

                                $subtotal =
                                    $item["quantidade"] *
                                    $item["preco_unitario"];

                                ?>


                                <tr>

                                    <td class="produto-info">

                                        <div class="produto-imagem">

                                            <img
                                                src="<?= !empty($item["imagem"])
                                                    ? 'uploads/' . htmlspecialchars($item["imagem"])
                                                    : 'uploads/verity.png'
                                                ?>"
                                                alt="<?= htmlspecialchars(
                                                    $item["titulo"]
                                                ) ?>"
                                            >

                                        </div>


                                        <strong>

                                            <?= htmlspecialchars(
                                                $item["titulo"]
                                            ) ?>

                                        </strong>

                                    </td>

                                    <td>

                                        <?= htmlspecialchars(
                                            $item["autor"]
                                        ) ?>

                                    </td>

                                    <td>

                                        <span class="quantidade">

                                            <?= $item["quantidade"] ?>

                                        </span>

                                    </td>

                                    <td class="preco">

                                        R$

                                        <?= number_format(
                                            $item["preco_unitario"],
                                            2,
                                            ",",
                                            "."
                                        ) ?>

                                    </td>

                                    <td class="subtotal">

                                        R$

                                        <?= number_format(
                                            $subtotal,
                                            2,
                                            ",",
                                            "."
                                        ) ?>

                                    </td>

                                </tr>


                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>


            <?php endif; ?>

        </div>

        <div class="resumo-pedido">

            <span>
                Total do pedido
            </span>

            <strong>

                R$
                <?= number_format(
                    $pedido["valor_total"],
                    2,
                    ",",
                    "."
                ) ?>

            </strong>

        </div>

        <a href="meus-pedidos.php"
            class="voltar-pedidos">
            ← Voltar para meus pedidos
        </a>


    </div>

</main>


</body>

</html>