<?php

session_start();

require_once 'crud.php';

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

$idUsuario = (int) $_SESSION["id_usuario"];

$sql = "SELECT
            emprestimo.*,
            produto.titulo,
            produto.autor
        FROM emprestimo
        INNER JOIN produto
            ON emprestimo.id_produto = produto.id_produto
        WHERE emprestimo.id_usuario = ?
        ORDER BY emprestimo.id_emprestimo DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$idUsuario]);

$emprestimos = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Meus Empréstimos | Futuroteca</title>
    <link rel="icon" type="x-icon" href="./uploads/logo.png">
    <link rel="stylesheet" href="css/header-footer.css">
    <link rel="stylesheet" href="css/compra.css">

</head>

<body>

    <?php require_once "partials/header.php"; ?>

    <main class="detalhes-container">

        <div class="detalhes-info">

            <h1>Meus Empréstimos</h1>

            <?php if (count($emprestimos) == 0): ?>

                <p>Você ainda não realizou nenhum empréstimo.</p>

            <?php else: ?>

                <table>

                    <thead>

                        <tr>
                            <th>Livro</th>
                            <th>Autor</th>
                            <th>Empréstimo</th>
                            <th>Devolução</th>
                            <th>Status</th>
                            <th>Ação</th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($emprestimos as $emprestimo): ?>

                            <tr>

                                <td>
                                    <?= htmlspecialchars($emprestimo["titulo"]) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($emprestimo["autor"]) ?>
                                </td>

                                <td>
                                    <?= date(
                                        "d/m/Y",
                                        strtotime($emprestimo["data_emprestimo"])
                                    ) ?>
                                </td>

                                <td>
                                    <?= date(
                                        "d/m/Y",
                                        strtotime($emprestimo["data_devolucao_prevista"])
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($emprestimo["status"]) ?>
                                </td>

                                <td>

                                    <?php if (
                                        $emprestimo["status"] == "ATIVO" &&
                                        $emprestimo["prorrogado"] == 0
                                    ): ?>

                                        <form action="prorrogar-meu-emprestimo.php" method="POST">

                                            <input type="hidden" name="id_emprestimo" value="<?= $emprestimo["id_emprestimo"] ?>">

                                            <button type="submit">
                                                Prorrogar +7 dias
                                            </button>

                                        </form>

                                    <?php elseif (
                                        $emprestimo["status"] == "ATIVO" &&
                                        $emprestimo["prorrogado"] == 1
                                    ): ?>

                                        Já prorrogado

                                    <?php else: ?>

                                        -

                                    <?php endif; ?>

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