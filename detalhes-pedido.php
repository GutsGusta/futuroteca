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


/* BUSCA O PEDIDO
   E CONFIRMA QUE ELE PERTENCE AO USUÁRIO LOGADO */

$sql = "SELECT *
        FROM pedido
        WHERE id_pedido = ?
        AND id_usuario = ?";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    $idPedido,
    $idUsuario
]);

$pedido = $stmt->fetch(PDO::FETCH_ASSOC);


/* SE O PEDIDO NÃO EXISTIR */

if (!$pedido) {
    header("Location: meus-pedidos.php");
    exit;
}


/* BUSCA OS PRODUTOS DO PEDIDO */

$sql = "SELECT
            item_pedido.*,
            produto.titulo,
            produto.autor
        FROM item_pedido
        INNER JOIN produto
            ON item_pedido.id_produto = produto.id_produto
        WHERE item_pedido.id_pedido = ?";

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
        href="css/compra.css"
    >

</head>

<body>

<?php require_once "partials/header.php"; ?>


<main class="detalhes-container">

    <div class="detalhes-info">

        <h1>
            Pedido #<?= $pedido["id_pedido"] ?>
        </h1>


        <p>
            <strong>Data:</strong>

            <?= date(
                "d/m/Y H:i",
                strtotime($pedido["data_pedido"])
            ) ?>
        </p>


        <p>
            <strong>Status:</strong>

            <?= htmlspecialchars($pedido["status"]) ?>
        </p>


        <p>
            <strong>Tipo de entrega:</strong>

            <?= htmlspecialchars($pedido["tipo_entrega"]) ?>
        </p>


        <p>
            <strong>Frete:</strong>

            R$ <?= number_format(
                $pedido["frete"],
                2,
                ",",
                "."
            ) ?>
        </p>


        <h2>Produtos</h2>


        <?php if (count($itens) == 0): ?>

            <p>
                Nenhum produto encontrado neste pedido.
            </p>

        <?php else: ?>

            <table>

                <thead>

                    <tr>

                        <th>Produto</th>

                        <th>Autor</th>

                        <th>Quantidade</th>

                        <th>Preço unitário</th>

                        <th>Subtotal</th>

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

                            <td>
                                <?= htmlspecialchars(
                                    $item["titulo"]
                                ) ?>
                            </td>


                            <td>
                                <?= htmlspecialchars(
                                    $item["autor"]
                                ) ?>
                            </td>


                            <td>
                                <?= $item["quantidade"] ?>
                            </td>


                            <td>

                                R$ <?= number_format(
                                    $item["preco_unitario"],
                                    2,
                                    ",",
                                    "."
                                ) ?>

                            </td>


                            <td>

                                R$ <?= number_format(
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

        <?php endif; ?>


        <h2>

            Total:
            R$ <?= number_format(
                $pedido["valor_total"],
                2,
                ",",
                "."
            ) ?>

        </h2>


        <br>


        <a href="meus-pedidos.php">
            ← Voltar para meus pedidos
        </a>

    </div>

</main>


</body>

</html>