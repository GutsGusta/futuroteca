<?php

session_start();

require_once 'crud.php';

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

$idUsuario = (int) $_SESSION["id_usuario"];


/* BUSCA SOMENTE OS PEDIDOS DO USUÁRIO LOGADO */

$sql = "
    SELECT *
    FROM pedido
    WHERE id_usuario = ?
    ORDER BY id_pedido DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$idUsuario]);

$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>

<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Meus Pedidos | Futuroteca</title>

    <link rel="stylesheet" href="css/header-footer.css">
    <link rel="stylesheet" href="css/compra.css">
    <link rel="stylesheet" href="css/meus-pedidos.css">

</head>


<body>

<?php require_once "partials/header.php"; ?>


<main class="pagina-pedidos">

    <div class="pedidos-container">


        <!-- CABEÇALHO -->

        <div class="pedidos-header">

            <div>

                <span class="pedidos-label">
                    FUTUROTECA
                </span>

                <h1>Meus Pedidos</h1>

                <p>
                    Acompanhe suas compras e empréstimos.
                </p>

            </div>


            <span class="quantidade-pedidos">

                <?= count($pedidos) ?>

                <?= count($pedidos) == 1
                    ? "pedido"
                    : "pedidos"
                ?>

            </span>

        </div>


        <?php if (count($pedidos) == 0): ?>


            <!-- NENHUM PEDIDO -->

            <div class="pedidos-vazio">

                <div class="icone-pedidos">
                    shopping_bag
                </div>

                <h2>
                    Você ainda não realizou nenhum pedido
                </h2>

                <p>
                    Quando você realizar uma compra ou empréstimo,
                    seus pedidos aparecerão aqui.
                </p>

                <a
                    href="livros.php"
                    class="btn-ver-produtos"
                >
                    Explorar produtos
                </a>

            </div>


        <?php else: ?>


            <!-- TABELA -->

            <div class="tabela-pedidos-container">

                <table class="tabela-pedidos">

                    <thead>

                        <tr>

                            <th>
                                Pedido
                            </th>

                            <th>
                                Data
                            </th>

                            <th>
                                Total
                            </th>

                            <th>
                                Entrega
                            </th>

                            <th>
                                Frete
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Detalhes
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php foreach ($pedidos as $pedido): ?>

                            <tr>

                                <!-- PEDIDO -->

                                <td class="pedido-id">

                                    #<?= $pedido["id_pedido"] ?>

                                </td>


                                <!-- DATA -->

                                <td class="pedido-data">

                                    <?= date(
                                        "d/m/Y H:i",
                                        strtotime($pedido["data_pedido"])
                                    ) ?>

                                </td>


                                <!-- TOTAL -->

                                <td class="pedido-total">

                                    R$

                                    <?= number_format(
                                        $pedido["valor_total"],
                                        2,
                                        ",",
                                        "."
                                    ) ?>

                                </td>


                                <!-- ENTREGA -->

                                <td>

                                    <span class="tipo-entrega">

                                        <?= htmlspecialchars(
                                            $pedido["tipo_entrega"]
                                        ) ?>

                                    </span>

                                </td>


                                <!-- FRETE -->

                                <td class="pedido-frete">

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

                                </td>


                                <!-- STATUS -->

                                <td>

                                    <span
                                        class="status-pedido status-<?= strtolower(
                                            htmlspecialchars(
                                                $pedido["status"]
                                            )
                                        ) ?>"
                                    >

                                        <?= htmlspecialchars(
                                            $pedido["status"]
                                        ) ?>

                                    </span>

                                </td>


                                <!-- DETALHES -->

                                <td>

                                    <a
                                        href="detalhes-pedido.php?id=<?= $pedido["id_pedido"] ?>"
                                        class="btn-detalhes"
                                    >
                                        Ver pedido
                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>


            <!-- VOLTAR PARA PRODUTOS -->

            <a
                href="livros.php"
                class="continuar-comprando"
            >
                ← Continuar comprando
            </a>


        <?php endif; ?>


    </div>

</main>


</body>

</html>