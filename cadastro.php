<?php

require_once 'crud.php';

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $cpf = $_POST["cpf"];

    // Verifica se o e-mail já existe
    $emailExistente = read(
        $pdo,
        "usuario",
        "email = " . $pdo->quote($email)
    );

    // Verifica se o CPF já existe
    $cpfExistente = read(
        $pdo,
        "usuario",
        "cpf = " . $pdo->quote($cpf)
    );

    if ($emailExistente) {

        $mensagem = "Este e-mail já está cadastrado.";

    } elseif ($cpfExistente) {

        $mensagem = "Este CPF já está cadastrado.";

    } else {

        $dados = [
            "nome" => $_POST["nome"],
            "email" => $_POST["email"],
            "cpf" => $_POST["cpf"],
            "telefone" => $_POST["telefone"],
            "endereco" => $_POST["endereco"],
            "tipo_usuario" => "CLIENTE",
            "senha" => password_hash($_POST["senha"], PASSWORD_DEFAULT)
        ];

        create($pdo, "usuario", $dados);

        header("Location: login.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/header-footer.css">
    <link rel="stylesheet" href="css/cadastro.css">

    <title>Cadastro</title>
</head>

<body>

<?php require_once "partials/header.php"; ?>

<main>

    <div class="part-pagina1"></div>

    <div class="part-pagina2">

        <h1>Cadastre-se</h1>

        <?php if ($mensagem != ""): ?>

            <p class="mensagem-erro">
                <?= htmlspecialchars($mensagem) ?>
            </p>

        <?php endif; ?>

        <form class="form" method="POST">

            <label>
                <p>Nome Completo</p>

                <input
                    type="text"
                    name="nome"
                    required
                >
            </label>

            <label>
                <p>CPF</p>

                <input
                    type="text"
                    name="cpf"
                    required
                >
            </label>

            <label>
                <p>Email</p>

                <input
                    type="email"
                    name="email"
                    required
                >
            </label>

            <label>
                <p>Telefone</p>

                <input
                    type="text"
                    name="telefone"
                >
            </label>

            <label>
                <p>Endereço</p>

                <input
                    type="text"
                    name="endereco"
                >
            </label>

            <label>
                <p>Senha</p>

                <input
                    type="password"
                    name="senha"
                    required
                >
            </label>

            <a href="login.php">
                Já tem uma conta? Faça login
            </a>

            <button type="submit">
                Cadastrar
            </button>

        </form>

    </div>

</main>

</body>

</html>