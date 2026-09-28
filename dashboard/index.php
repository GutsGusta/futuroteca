<?php

require_once 'proteger-admin.php';
require_once '../crud.php';

$produtos = readAll($pdo, "produto");
$totalProdutos = count($produtos);

$sqlUltimosPedidos = "SELECT
                        pedido.*,
                        usuario.nome AS nome_cliente
                      FROM pedido
                      INNER JOIN usuario
                        ON pedido.id_usuario = usuario.id_usuario
                      ORDER BY pedido.id_pedido DESC
                      LIMIT 3";

$stmtUltimosPedidos = $pdo->query($sqlUltimosPedidos);

$ultimosPedidos = $stmtUltimosPedidos->fetchAll(PDO::FETCH_ASSOC);

$clientes = readAll($pdo, "usuario", "tipo_usuario = 'CLIENTE'");
$totalClientes = count($clientes);

$pedidos = readAll($pdo, "pedido");
$totalPedidos = count($pedidos);

$emprestimosAtivos = readAll($pdo, "emprestimo", "status = 'ATIVO'");
$totalEmprestimosAtivos = count($emprestimosAtivos);

$sqlEmprestimosRecentes = "SELECT
                            emprestimo.*,
                            usuario.nome AS nome_cliente,
                            produto.titulo AS nome_produto
                           FROM emprestimo
                           INNER JOIN usuario
                            ON emprestimo.id_usuario = usuario.id_usuario
                           INNER JOIN produto
                            ON emprestimo.id_produto = produto.id_produto
                           ORDER BY emprestimo.id_emprestimo DESC
                           LIMIT 3";

$stmtEmprestimosRecentes = $pdo->query($sqlEmprestimosRecentes);

$emprestimosRecentes = $stmtEmprestimosRecentes->fetchAll(PDO::FETCH_ASSOC);

$sqlDevolucoesProximas = "SELECT
                            emprestimo.*,
                            usuario.nome AS nome_cliente,
                            produto.titulo AS nome_produto
                          FROM emprestimo
                          INNER JOIN usuario
                            ON emprestimo.id_usuario = usuario.id_usuario
                          INNER JOIN produto
                            ON emprestimo.id_produto = produto.id_produto
                          WHERE emprestimo.status = 'ATIVO'
                          ORDER BY emprestimo.data_devolucao_prevista ASC
                          LIMIT 3";

$stmtDevolucoesProximas = $pdo->query($sqlDevolucoesProximas);

$devolucoesProximas = $stmtDevolucoesProximas->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard | Futuroteca</title>

    <link rel="stylesheet" href="../css/dashboard.css">
</head>

<body>

    <?php include 'partials/sidebar.php'; ?>

    <main class="conteudo">

        <div class="titulo-dashboard">
            <h1>Dashboard</h1>
            <p>Bem-vindo(a) ao painel administrativo da Futuroteca!</p>
        </div>

        <section class="cards-dashboard">

            <div class="card">
                <div class="card-icone"></div>

                <div>
                    <h2><?= $totalProdutos ?></h2>
                    <p>Produtos cadastrados</p>
                </div>
            </div>

            <div class="card">
                <div class="card-icone"></div>

                <div>
                    <h2><?= $totalPedidos ?></h2>
                    <p>Pedidos</p>
                </div>
            </div>

            <div class="card">
                <div class="card-icone"></div>

                <div>
                    <h2><?= $totalEmprestimosAtivos ?></h2>
                    <p>Empréstimos ativos</p>
                </div>
            </div>

            <div class="card">
                <div class="card-icone"></div>

                <div>
                    <h2><?= $totalClientes ?></h2>
                    <p>Clientes cadastrados</p>
                </div>
            </div>

        </section>

        <section class="resumo-dashboard">

            <div class="painel-dashboard">

                <div class="painel-titulo">
                    <h3>Últimos Pedidos</h3>
                    <a href="pedidos.php">Ver todos →</a>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Pedido</th>
                            <th>Cliente</th>
                            <th>Valor</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (count($ultimosPedidos) == 0): ?>

                            <tr>
                                <td colspan="4">
                                    Nenhum pedido realizado.
                                </td>
                            </tr>

                        <?php else: ?>

                            <?php foreach ($ultimosPedidos as $pedido): ?>

                                <tr>

                                    <td>
                                        #
                                        <?= $pedido["id_pedido"] ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($pedido["nome_cliente"]) ?>
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
                                        <span class="status <?= strtolower($pedido["status"]) ?>">
                                            <?= htmlspecialchars($pedido["status"]) ?>
                                        </span>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php endif; ?>

                    </tbody>
                </table>

            </div>

            <div class="painel-dashboard">

                <div class="painel-titulo">
                    <h3>Empréstimos Recentes</h3>
                    <a href="emprestimos.php">Ver todos →</a>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Livro</th>
                            <th>Devolução</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (count($emprestimosRecentes) == 0): ?>

                            <tr>
                                <td colspan="4">
                                    Nenhum empréstimo realizado.
                                </td>
                            </tr>

                        <?php else: ?>

                            <?php foreach ($emprestimosRecentes as $emprestimo): ?>

                                <tr>

                                    <td>
                                        <?= htmlspecialchars($emprestimo["nome_cliente"]) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($emprestimo["nome_produto"]) ?>
                                    </td>

                                    <td>
                                        <?= date(
                                            "d/m/Y",
                                            strtotime($emprestimo["data_devolucao_prevista"])
                                        ) ?>
                                    </td>

                                    <td>
                                        <span class="status <?= strtolower($emprestimo["status"]) ?>">
                                            <?= htmlspecialchars($emprestimo["status"]) ?>
                                        </span>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php endif; ?>

                    </tbody>
                </table>

            </div>

        </section>

        <section class="devolucoes">

            <div class="painel-titulo">
                <div>
                    <h3> Devoluções Próximas</h3>
                    <p>Livros com prazo de devolução próximo.</p>
                </div>

                <a href="emprestimos.php">Ver empréstimos →</a>
            </div>

            <div class="lista-devolucoes">

                <?php if (count($devolucoesProximas) == 0): ?>

                    <div class="devolucao-item">
                        <p>Nenhuma devolução próxima.</p>
                    </div>

                <?php else: ?>

                    <?php foreach ($devolucoesProximas as $devolucao): ?>

                        <div class="devolucao-item">

                            <div>
                                <strong>
                                    <?= htmlspecialchars($devolucao["nome_produto"]) ?>
                                </strong>

                                <p>
                                    <?= htmlspecialchars($devolucao["nome_cliente"]) ?>
                                </p>
                            </div>

                            <span>
                                <?= date(
                                    "d/m/Y",
                                    strtotime($devolucao["data_devolucao_prevista"])
                                ) ?>
                            </span>

                        </div>

                    <?php endforeach; ?>

                <?php endif; ?>

            </div>

        </section>

    </main>

</body>

</html>