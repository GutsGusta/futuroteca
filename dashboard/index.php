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
            <div class="card-icone">📚</div>

            <div>
                <h2>128</h2>
                <p>Produtos cadastrados</p>
            </div>
        </div>

        <div class="card">
            <div class="card-icone">🛒</div>

            <div>
                <h2>12</h2>
                <p>Pedidos</p>
            </div>
        </div>

        <div class="card">
            <div class="card-icone">🕐</div>

            <div>
                <h2>18</h2>
                <p>Empréstimos ativos</p>
            </div>
        </div>

        <div class="card">
            <div class="card-icone">👥</div>

            <div>
                <h2>46</h2>
                <p>Clientes cadastrados</p>
            </div>
        </div>

    </section>

    <!-- ÚLTIMOS PEDIDOS E EMPRÉSTIMOS -->
<section class="resumo-dashboard">

    <!-- ÚLTIMOS PEDIDOS -->
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
                <tr>
                    <td>#105</td>
                    <td>Ana Silva</td>
                    <td>R$ 89,90</td>
                    <td>
                        <span class="status entregue">Entregue</span>
                    </td>
                </tr>

                <tr>
                    <td>#104</td>
                    <td>Lucas Souza</td>
                    <td>R$ 135,00</td>
                    <td>
                        <span class="status enviado">Enviado</span>
                    </td>
                </tr>

                <tr>
                    <td>#103</td>
                    <td>Pedro Lima</td>
                    <td>R$ 59,90</td>
                    <td>
                        <span class="status pendente">Pendente</span>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>


    <!-- EMPRÉSTIMOS RECENTES -->
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
                <tr>
                    <td>João Silva</td>
                    <td>Dom Casmurro</td>
                    <td>20/09/2026</td>
                    <td>
                        <span class="status ativo">Ativo</span>
                    </td>
                </tr>

                <tr>
                    <td>Maria Souza</td>
                    <td>O Cortiço</td>
                    <td>22/09/2026</td>
                    <td>
                        <span class="status ativo">Ativo</span>
                    </td>
                </tr>

                <tr>
                    <td>Ana Costa</td>
                    <td>1984</td>
                    <td>18/09/2026</td>
                    <td>
                        <span class="status atrasado">Atrasado</span>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>

</section>


<!-- DEVOLUÇÕES PRÓXIMAS -->
<section class="devolucoes">

    <div class="painel-titulo">
        <div>
            <h3>⚠️ Devoluções Próximas</h3>
            <p>Livros com prazo de devolução próximo.</p>
        </div>

        <a href="emprestimos.php">Ver empréstimos →</a>
    </div>

    <div class="lista-devolucoes">

        <div class="devolucao-item">
            <div>
                <strong>Dom Casmurro</strong>
                <p>João Silva</p>
            </div>

            <span>20/09/2026</span>
        </div>

        <div class="devolucao-item">
            <div>
                <strong>O Cortiço</strong>
                <p>Maria Souza</p>
            </div>

            <span>22/09/2026</span>
        </div>

    </div>

</section>

</main>

</body>

</html>