<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header-footer.css">
    <link rel="stylesheet" href="css/cadastro.css">
    <title>Cadastro</title>
</head>
<?php
    require_once "partials/header.php";
?>
<body>
    <main>
        <div class="part-pagina1"></div>
        <div class="part-pagina2">
            <h1>Cadastre-se</h1>
            <form class="form">
                <label>
                    <p>Nome Completo</p>
                    <input type="text">
                </label>
                <label>
                    <p>CPF</p>
                    <input type="text">
                </label>
                <label>
                    <p>Email</p>
                    <input type="email">
                </label>
                <label>
                    <p>Telefone</p>
                    <input type="text">
                </label>
                <label>
                    <p>Endereço</p>
                    <input type="text">
                </label>
                <label>
                    <p>Senha</p>
                    <input type="password">
                </label>
                <a href="">Já tem uma conta? Faça login</a>
                <button type="submit">Cadastrar</button>
            </form>
        </div>
    </main>
</body>
</html>