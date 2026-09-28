<?php

require_once '../crud.php';

$hoje = date("Y-m-d");

$sqlAtrasados = "UPDATE emprestimo
                 SET status = 'ATRASADO'
                 WHERE data_devolucao_prevista < ?
                 AND status = 'ATIVO'";

$stmtAtrasados = $pdo->prepare($sqlAtrasados);
$stmtAtrasados->execute([$hoje]);

$sql = "SELECT 
            emprestimo.*,
            usuario.nome AS nome_cliente,
            produto.titulo AS nome_produto
        FROM emprestimo
        INNER JOIN usuario 
            ON emprestimo.id_usuario = usuario.id_usuario
        INNER JOIN produto 
            ON emprestimo.id_produto = produto.id_produto
        ORDER BY emprestimo.id_emprestimo DESC";

$stmt = $pdo->query($sql);
$emprestimos = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Empréstimos | Futuroteca</title>

    <link rel="stylesheet" href="../css/dashboard.css">
</head>

<body>

    <?php include 'partials/sidebar.php'; ?>

    <main class="conteudo">

        <div class="titulo-dashboard">
            <h1>Empréstimos</h1>
            <p>Gerencie os empréstimos realizados na Futuroteca.</p>
        </div>

        <div class="painel-dashboard">

            <div class="painel-titulo">
                <h3>Empréstimos cadastrados</h3>

                <a href="cadastrar-emprestimo.php">
                    Novo empréstimo
                </a>
            </div>

            <table>

                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Produto</th>
                        <th>Empréstimo</th>
                        <th>Devolução prevista</th>
                        <th>Devolução real</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (count($emprestimos) == 0): ?>

                        <tr>
                            <td colspan="7">
                                Nenhum empréstimo cadastrado.
                            </td>
                        </tr>

                    <?php else: ?>

                        <?php foreach ($emprestimos as $emprestimo): ?>

                            <tr>

                                <td>
                                    <?= htmlspecialchars($emprestimo["nome_cliente"]) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($emprestimo["nome_produto"]) ?>
                                </td>

                                <td>
                                    <?= date("d/m/Y", strtotime($emprestimo["data_emprestimo"])) ?>
                                </td>

                                <td>
                                    <?= date("d/m/Y", strtotime($emprestimo["data_devolucao_prevista"])) ?>
                                </td>

                                <td>
                                    <?php if ($emprestimo["data_devolucao_real"]): ?>

                                        <?= date("d/m/Y", strtotime($emprestimo["data_devolucao_real"])) ?>

                                    <?php else: ?>

                                        -

                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($emprestimo["status"]) ?>
                                </td>

                                <td>

                                    <?php if ($emprestimo["status"] == "ATIVO" || $emprestimo["status"] == "ATRASADO"): ?>

                                        <a href="prorrogar-emprestimo.php?id=<?= $emprestimo["id_emprestimo"] ?>"
                                            onclick="return confirm('Deseja prorrogar este empréstimo por mais 7 dias?')">
                                            Prorrogar
                                        </a>

                                        |

                                        <a href="devolver-emprestimo.php?id=<?= $emprestimo["id_emprestimo"] ?>"
                                            onclick="return confirm('Confirmar devolução deste empréstimo?')">
                                            Devolver
                                        </a>

                                    <?php else: ?>

                                        -

                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </main>

</body>

</html>