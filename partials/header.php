<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />

<header class="site-header">

    <div class="header-principal">

        <a href="index.php" class="logo-header">
            <img src="uploads/logo.png" alt="Futuroteca">
        </a>

        <form action="livros.php" method="GET" class="header-busca">

            <input type="text" name="busca" placeholder="Busque por título ou autor..." autocomplete="off">

            <button type="submit" aria-label="Buscar">
                <span class="material-symbols-outlined">
                    search
                </span>
            </button>

        </form>

        <div class="header-acoes">

            <a href="carrinho.php" class="icone-header" title="Carrinho">
                <img src="uploads/carrinho.png" alt="Carrinho">
            </a>

            <?php if (isset($_SESSION["id_usuario"])): ?>

                <div class="usuario-header">

                    <span class="material-symbols-outlined">
                        account_circle
                    </span>

                    <div class="usuario-texto">
                        <small>Olá,</small>

                        <strong>
                            <?= htmlspecialchars($_SESSION["nome"]) ?>
                        </strong>
                    </div>

                    <div class="menu-usuario">
                        <a href="meus-pedidos.php">Meus pedidos</a>
                        <a href="meus-emprestimos.php">Meus empréstimos</a>

                        <?php if ($_SESSION["tipo_usuario"] === "ADMIN"): ?>
                            <a href="dashboard/index.php">Dashboard</a>
                        <?php endif; ?>

                        <a href="logout.php">Sair</a>
                    </div>

                </div>

            <?php else: ?>

                <a href="login.php" class="login-header">
                    <span class="material-symbols-outlined">
                        account_circle
                    </span>

                    <div>
                        <small>Olá!</small>
                        <strong>Entrar</strong>
                    </div>
                </a>

            <?php endif; ?>

        </div>

    </div>

    <nav class="header-nav">

        <a href="livros.php" class="categorias-link">
            <span class="material-symbols-outlined">menu</span>
            Categorias
        </a>

        <div class="nav-bar">
            <a href="index.php#mais-vendidos">Mais Vendidos</a>
            <a href="index.php#autores">Autores</a>
            <a href="index.php#promocoes">Promoções</a>
            <a href="index.php#ebooks">E-books</a>
        </div>

    </nav>

</header>