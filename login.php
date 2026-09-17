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
        <form class="login-card">      
            <h1>Realize seu Login</h1>
            <label>
                <p>Email</p>
                <input type="email" required>
            </label>
            <label>
                <p>Senha</p>
                <input type="password" required>
            </label>
            <a href="">Não possui conta? Cadastra-se aqui!</a>
            <button type="submit">Login</button>
        </form>
    </main>
</body>
</html>