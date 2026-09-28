<?php

require_once '../crud.php';

$clientes = readAll($pdo, "usuario", "tipo_usuario = 'CLIENTE'");
$produtos = readAll($pdo, "produto");

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $idUsuario = (int) $_POST["id_usuario"];
    $idProduto = (int) $_POST["id_produto"];

    $produto = read(
        $pdo,
        "produto",
        "id_produto = $idProduto"
    );

    if (!$produto || $produto["estoque"] <= 0) {

        $mensagem = "Produto sem estoque disponível.";

    } else {

        $dataEmprestimo = date("Y-m-d");

        $dataDevolucao = date(
            "Y-m-d",
            strtotime($dataEmprestimo . " +14 days")
        );

        $dados = [
            "id_usuario" => $idUsuario,
            "id_produto" => $idProduto,
            "data_emprestimo" => $dataEmprestimo,
            "data_devolucao_prevista" => $dataDevolucao,
            "status" => "ATIVO"
        ];

        create($pdo, "emprestimo", $dados);

        $novoEstoque = $produto["estoque"] - 1;

        update(
            $pdo,
            "produto",
            ["estoque" => $novoEstoque],
            "id_produto = $idProduto"
        );

        header("Location: emprestimos.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Novo Empréstimo | Futuroteca</title>

    <link rel="stylesheet" href="../css/dashboard.css">
</head>

<body>

    <?php include 'partials/sidebar.php'; ?>

    <main class="conteudo">

        <div class="titulo-dashboard">
            <h1>Novo Empréstimo</h1>
            <p>Cadastre um novo empréstimo.</p>
        </div>

        <div class="painel-dashboard">

            <?php if (count($clientes) == 0): ?>

                <p>
                    Nenhum cliente cadastrado.
                </p>

            <?php elseif (count($produtos) == 0): ?>

                <p>
                    Nenhum produto cadastrado.
                </p>

            <?php else: ?>

                <?php if ($mensagem != ""): ?>

                    <p>
                        <?= htmlspecialchars($mensagem) ?>
                    </p>

                <?php endif; ?>
                <form method="POST">

                    <label>
                        Cliente
                    </label>

                    <select name="id_usuario" required>

                        <option value="">
                            Selecione um cliente
                        </option>

                        <?php foreach ($clientes as $cliente): ?>

                            <option value="<?= $cliente["id_usuario"] ?>">
                                <?= htmlspecialchars($cliente["nome"]) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                    <br><br>

                    <label>
                        Produto
                    </label>

                    <select name="id_produto" required>

                        <option value="">
                            Selecione um produto
                        </option>

                        <?php foreach ($produtos as $produto): ?>

                            <option value="<?= $produto["id_produto"] ?>">
                                <?= htmlspecialchars($produto["titulo"]) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                    <br><br>

                    <button type="submit">
                        Cadastrar empréstimo
                    </button>

                </form>

            <?php endif; ?>

        </div>

    </main>

</body>

</html>