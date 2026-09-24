<?php

session_start();

require_once 'crud.php';

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $senha = $_POST["senha"];

    // Procura o usuário pelo e-mail
    $usuario = read(
        $pdo,
        "usuario",
        "email = " . $pdo->quote($email)
    );

    // Verifica se encontrou o usuário e se a senha está correta
    if ($usuario && password_verify($senha, $usuario["senha"])) {

        // Guarda informações do usuário na sessão
        $_SESSION["id_usuario"] = $usuario["id_usuario"];
        $_SESSION["nome"] = $usuario["nome"];
        $_SESSION["tipo_usuario"] = $usuario["tipo_usuario"];
        
        // ADMIN vai para o Dashboard
        if ($usuario["tipo_usuario"] == "ADMIN") {

            header("Location: dashboard/index.php");
            exit;

        } else {

            // CLIENTE vai para a página inicial
            header("Location: homepage.php");
            exit;
        }

    } else {

        $mensagem = "E-mail ou senha incorretos.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="./css/login.css">
    <link rel="stylesheet" href="./css/header-footer.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <?php
    require_once "partials/header.php";
    ?>
    <main>
        <h1>Seja Bem-Vindo de Volta!</h1>
        <form class="login-card" method="POST">

            <h1>Realize seu Login</h1>

            <?php if ($mensagem != ""): ?>
                <p class="mensagem-erro">
                    <?= htmlspecialchars($mensagem) ?>
                </p>
            <?php endif; ?>

            <label>
                <p>Email</p>

                <input type="email" name="email" required>
            </label>

            <label>
                <p>Senha</p>

                <input type="password" name="senha" required>
            </label>
<<<<<<< HEAD
            <a href="cadastro.php">Não possui conta? Cadastra-se aqui!</a>
            <button type="submit">Login</button>
=======

            <a href="cadastro.php">
                Não possui conta? Cadastre-se aqui!
            </a>

            <button type="submit">
                Login
            </button>

>>>>>>> 446f373 (Implantação do Back-end no Dashboard e no Login)
        </form>
    </main>
</body>

</html>