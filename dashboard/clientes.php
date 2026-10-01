<?php
require_once 'proteger-admin.php';
require_once '../crud.php';

$clientes = readAll(
    $pdo,
    "usuario",
    "tipo_usuario = 'CLIENTE'"
);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Clientes | Futuroteca</title>

    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/clientes-adm.css">

</head>

<body>

    <?php include 'partials/sidebar.php'; ?>

    <main class="conteudo">

        <div class="cabecalho-pagina">

            <div>
                <h1>Clientes</h1>
                <p>Clientes cadastrados na Futuroteca.</p>
            </div>

        </div>

        <?php if (isset($_GET["excluido"])): ?>

            <p class="mensagem-sucesso">
                Cliente excluído com sucesso.
            </p>

        <?php endif; ?>


        <?php if (
            isset($_GET["erro"]) &&
            $_GET["erro"] === "cliente_vinculado"
        ): ?>

            <p class="mensagem-erro">
                Não é possível excluir este cliente porque ele possui pedidos ou empréstimos registrados.
            </p>

        <?php endif; ?>

        <div class="tabela-container">

            <table>

                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>CPF</th>
                        <th>Telefone</th>
                        <th>Endereço</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($clientes as $cliente): ?>

                        <tr>

                            <td>
                                <?= htmlspecialchars($cliente["nome"]) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($cliente["email"]) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($cliente["cpf"]) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($cliente["telefone"] ?? "") ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($cliente["endereco"] ?? "") ?>
                            </td>

                            <td>

                                <a href="excluir-cliente.php?id=<?= $cliente["id_usuario"] ?>" class="btn-excluir"
                                    onclick="return confirm('Tem certeza que deseja excluir este cliente?')">
                                    Excluir
                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </main>

</body>

</html>