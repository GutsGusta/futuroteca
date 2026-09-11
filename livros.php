<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header-footer.css">
    <link rel="stylesheet" href="css/livros.css">
    <title>Livros</title>
</head>
<body>
    <?php
        require_once './partials/header.php';
    ?>
    <main>
        <aside class="barra-lateral">
            <ul class="">
                <li class="">
                    <label><input type="checkbox" name="categoria"> Ficção</label>
                </li>
                <li class="">
                    <label><input type="checkbox" name="categoria"> Romance</label>
                </li>
                <li class="">
                    <label><input type="checkbox" name="categoria"> Fantasia</label>
                </li>
                <li class="">
                    <label><input type="checkbox" name="categoria"> Aventura</label>
                </li>
                <li class="">
                    <label><input type="checkbox" name="categoria"> Suspense</label>
                </li>
                <li class="">
                    <label><input type="checkbox" name="categoria"> Comédia</label>
                </li>
                <li class="">
                    <label><input type="checkbox" name="categoria"> Infantil</label>
                </li>
                <li class="">
                    <label><input type="checkbox" name="categoria" checked> Biografia</label>
                </li>
                <li class="">
                    <label><input type="checkbox" name="categoria"> Terror</label>
                </li>
                <li class="">
                    <label><input type="checkbox" name="categoria"> Programação</label>
                </li>
                <li class="">
                    <label><input type="checkbox" name="categoria"> Artigo Científico</label>
                </li>
                <li class="">
                    <label><input type="checkbox" name="categoria"> História</label>
                </li>
                <li class="">
                    <label><input type="checkbox" name="categoria"> HQ e Mangás</label>
                </li>
                <li class="">
                    <label><input type="checkbox" name="categoria"> Literatura</label>
                </li>
            </ul>
        </aside>
        <div class="card-container">
            <article class="card">
                <img src="uploads/verity.png">
                <h3>Nome do Livro</h3>
                <p>Autor</p>
                <p>Editora</p>
                <p><b>R$ 67,00</b></p>
            </article>
            <article class="card">
                <img src="uploads/verity.png">
                <h3>Nome do Livro</h3>
                <p>Autor</p>
                <p>Editora</p>
                <p><b>R$ 67,00</b></p>
            </article>
        </div>
    </main>
</body>
</html>