<?php
require_once 'proteger-admin.php';
require_once '../crud.php';

$hoje = date("Y-m-d");

$busca = $_GET["busca"] ?? "";
$status = $_GET["status"] ?? "";

$sql = "SELECT 
            emprestimo.*,
            usuario.nome AS nome_cliente,
            produto.titulo AS nome_produto
        FROM emprestimo
        INNER JOIN usuario 
            ON emprestimo.id_usuario = usuario.id_usuario
        INNER JOIN produto 
            ON emprestimo.id_produto = produto.id_produto
        WHERE 1=1";

$params = [];

/* BUSCA POR CLIENTE OU PRODUTO */
if (!empty($busca)) {
    $sql .= " AND (
                usuario.nome LIKE ?
                OR produto.titulo LIKE ?
              )";

    $params[] = "%" . $busca . "%";
    $params[] = "%" . $busca . "%";
}

/* FILTRO POR STATUS */
if (!empty($status)) {
    $sql .= " AND emprestimo.status = ?";
    $params[] = $status;
}

$sql .= " ORDER BY emprestimo.id_emprestimo DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

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

        <form method="GET" class="filtros-produtos">

            <input type="text" name="busca" placeholder="Buscar por cliente ou produto..."
                value="<?= htmlspecialchars($busca) ?>">

            <select name="status">

                <option value="">
                    Todos os status
                </option>

                <option value="ATIVO" <?= $status === "ATIVO" ? "selected" : "" ?>>
                    Ativo
                </option>

                <option value="ATRASADO" <?= $status === "ATRASADO" ? "selected" : "" ?>>
                    Atrasado
                </option>

                <option value="DEVOLVIDO" <?= $status === "DEVOLVIDO" ? "selected" : "" ?>>
                    Devolvido
                </option>

            </select>

            <button type="submit" class="btn-filtrar">
                Filtrar
            </button>

            <a href="emprestimos.php" class="btn-limpar">
                Limpar
            </a>

        </form>

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

                                       <button> <a href="prorrogar-emprestimo.php?id=<?= $emprestimo["id_emprestimo"] ?>"
                                            onclick="return confirm('Deseja prorrogar este empréstimo por mais 7 dias?')">
                                            Prorrogar
                                        </a>
                                        </button>

                                        |

                                        <button><a href="devolver-emprestimo.php?id=<?= $emprestimo["id_emprestimo"] ?>"
                                            onclick="return confirm('Confirmar devolução deste empréstimo?')">
                                            Devolver
                                        </a>
                                        </button>

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