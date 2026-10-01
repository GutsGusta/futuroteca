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
    <link rel="stylesheet" href="css/meus-emprestimos.css">

</head>

<body>
    <?php require_once "partials/header.php"; ?>

    <main class="emprestimos-page">

        <div class="emprestimos-container">

            <div class="emprestimos-cabecalho">
                <div>
                    <span class="emprestimos-tag">MINHA CONTA</span>

                    <h1>Meus Empréstimos</h1>

                    <p>
                        Acompanhe seus livros emprestados e as datas de devolução.
                    </p>
                </div>
            </div>

            <?php if (count($emprestimos) == 0): ?>

                <div class="emprestimos-vazio">

                    <div class="icone-vazio">
                        <span class="material-symbols-outlined">
                            menu_book
                        </span>
                    </div>

                    <h2>Nenhum empréstimo encontrado</h2>

                    <p>
                        Você ainda não realizou nenhum empréstimo.
                    </p>

                    <a href="livros.php" class="btn-ver-livros">
                        Ver livros
                    </a>

                </div>

            <?php else: ?>

                <div class="emprestimos-tabela-container">

                    <table class="tabela-emprestimos">

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

                                    <td class="coluna-livro">
                                        <strong>
                                            <?= htmlspecialchars($emprestimo["titulo"]) ?>
                                        </strong>
                                    </td>

                                    <td class="coluna-autor">
                                        <?= htmlspecialchars($emprestimo["autor"]) ?>
                                    </td>

                                    <td>
                                        <?= date(
                                            "d/m/Y",
                                            strtotime($emprestimo["data_emprestimo"])
                                        ) ?>
                                    </td>

                                    <td class="coluna-devolucao">
                                        <?= date(
                                            "d/m/Y",
                                            strtotime($emprestimo["data_devolucao_prevista"])
                                        ) ?>
                                    </td>

                                    <td>

                                        <span class="status-emprestimo status-<?= strtolower($emprestimo["status"]) ?>">
                                            <?= htmlspecialchars($emprestimo["status"]) ?>
                                        </span>

                                    </td>

                                    <td class="coluna-acao">

                                        <?php if (
                                            $emprestimo["status"] == "ATIVO" &&
                                            $emprestimo["prorrogado"] == 0
                                        ): ?>

                                            <form action="prorrogar-meu-emprestimo.php" method="POST">

                                                <input type="hidden" name="id_emprestimo"
                                                    value="<?= $emprestimo["id_emprestimo"] ?>">

                                                <button type="submit" class="btn-prorrogar"
                                                    onclick="return confirm('Deseja prorrogar este empréstimo por mais 7 dias?')">
                                                    <span class="material-symbols-outlined">
                                                        event_repeat
                                                    </span>

                                                    Prorrogar
                                                </button>

                                            </form>

                                        <?php elseif (
                                            $emprestimo["status"] == "ATIVO" &&
                                            $emprestimo["prorrogado"] == 1
                                        ): ?>

                                            <span class="emprestimo-prorrogado">
                                                Já prorrogado
                                            </span>

                                        <?php else: ?>

                                            <span class="acao-indisponivel">
                                                —
                                            </span>

                                        <?php endif; ?>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            <?php endif; ?>

        </div>

    </main>

</body>

</html>