<?php

session_start();

require_once 'crud.php';

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

$idUsuario = (int) $_SESSION["id_usuario"];


/* BUSCA SOMENTE OS PEDIDOS DO USUÁRIO LOGADO */

$sql = "SELECT *
        FROM pedido
        WHERE id_usuario = ?
        ORDER BY id_pedido DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$idUsuario]);

$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Meus Pedidos | Futuroteca</title>

    <link rel="stylesheet" href="css/header-footer.css">
    <link rel="stylesheet" href="css/compra.css">

</head>

<body>

<?php require_once "partials/header.php"; ?>

<main class="detalhes-container">

    <div class="detalhes-info">

        <h1>Meus Pedidos</h1>

        <?php if (count($pedidos) == 0): ?>

            <p>Você ainda não realizou nenhuma compra.</p>

        <?php else: ?>

            <table>

                <thead>

                    <tr>
                        <th>Pedido</th>
                        <th>Data</th>
                        <th>Total</th>
                        <th>Entrega</th>
                        <th>Frete</th>
                        <th>Status</th>
                        <th>Detalhes</th>
                    </tr>

                </thead>

                <tbody>

                    <?php foreach ($pedidos as $pedido): ?>

                        <tr>

                            <td>
                                #<?= $pedido["id_pedido"] ?>
                            </td>

                            <td>
                                <?= date(
                                    "d/m/Y H:i",
                                    strtotime($pedido["data_pedido"])
                                ) ?>
                            </td>

                            <td>
                                R$
                                <?= number_format(
                                    $pedido["valor_total"],
                                    2,
                                    ",",
                                    "."
                                ) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($pedido["tipo_entrega"]) ?>
                            </td>

                            <td>
                                R$
                                <?= number_format(
                                    $pedido["frete"],
                                    2,
                                    ",",
                                    "."
                                ) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($pedido["status"]) ?>
                            </td>

                            <td>

                                <a href="detalhes-pedido.php?id=<?= $pedido["id_pedido"] ?>">
                                    Ver pedido
                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        <?php endif; ?>

    </div>

</main>

</body>

</html>