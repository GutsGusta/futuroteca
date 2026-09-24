<?php

require_once '../crud.php';

// Verifica se recebeu o ID
if (!isset($_GET["id"])) {
    header("Location: produtos.php");
    exit;
}

$id = (int) $_GET["id"];

// Busca o produto no banco
$produto = read($pdo, "produto", "id_produto = $id");

// Se não encontrar o produto
if (!$produto) {
    header("Location: produtos.php");
    exit;
}


// ATUALIZAR PRODUTO
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Mantém a imagem atual
    $nomeImagem = $produto["imagem"];

    // Se escolher uma nova imagem
    if (isset($_FILES["imagem"]) && $_FILES["imagem"]["error"] == 0) {

        $nomeImagem = time() . "_" . basename($_FILES["imagem"]["name"]);

        $destino = "../uploads/" . $nomeImagem;

        move_uploaded_file(
            $_FILES["imagem"]["tmp_name"],
            $destino
        );
    }


    $dados = [
        "titulo" => $_POST["titulo"],
        "autor" => $_POST["autor"],
        "categoria" => $_POST["categoria"],
        "tipo" => $_POST["tipo"],
        "preco" => $_POST["preco"],
        "estoque" => $_POST["estoque"],
        "descricao" => $_POST["descricao"],
        "imagem" => $nomeImagem
    ];


    update(
        $pdo,
        "produto",
        $dados,
        "id_produto = $id"
    );


    header("Location: produtos.php");
    exit;
}

?>

<!DOCTYPE html>

<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar Produto | Futuroteca</title>

    <link rel="stylesheet" href="../css/dashboard.css">

</head>

<body>

<?php include 'partials/sidebar.php'; ?>


<main class="conteudo">

    <div class="cabecalho-pagina">

        <div>
            <h1>Editar Produto</h1>

            <p>Altere as informações do produto.</p>
        </div>

        <a href="produtos.php" class="btn-voltar">
            ← Voltar
        </a>

    </div>


    <form
        class="form-produto"
        method="POST"
        enctype="multipart/form-data"
    >


        <div class="campo">

            <label for="titulo">
                Título
            </label>

            <input
                type="text"
                id="titulo"
                name="titulo"
                value="<?= htmlspecialchars($produto["titulo"]) ?>"
                required
            >

        </div>


        <div class="campo">

            <label for="autor">
                Autor
            </label>

            <input
                type="text"
                id="autor"
                name="autor"
                value="<?= htmlspecialchars($produto["autor"]) ?>"
            >

        </div>


        <div class="linha-form">


            <div class="campo">

                <label for="categoria">
                    Categoria
                </label>

                <select
                    id="categoria"
                    name="categoria"
                    required
                >

                    <option value="Romance"
                        <?= $produto["categoria"] == "Romance" ? "selected" : "" ?>>
                        Romance
                    </option>

                    <option value="Fantasia"
                        <?= $produto["categoria"] == "Fantasia" ? "selected" : "" ?>>
                        Fantasia
                    </option>

                    <option value="Terror"
                        <?= $produto["categoria"] == "Terror" ? "selected" : "" ?>>
                        Terror
                    </option>

                    <option value="Ficção"
                        <?= $produto["categoria"] == "Ficção" ? "selected" : "" ?>>
                        Ficção
                    </option>

                </select>

            </div>


            <div class="campo">

                <label for="tipo">
                    Tipo
                </label>

                <select
                    id="tipo"
                    name="tipo"
                    required
                >

                    <option value="LIVRO"
                        <?= $produto["tipo"] == "LIVRO" ? "selected" : "" ?>>
                        Livro
                    </option>

                    <option value="EBOOK"
                        <?= $produto["tipo"] == "EBOOK" ? "selected" : "" ?>>
                        E-book
                    </option>

                    <option value="ARTIGO"
                        <?= $produto["tipo"] == "ARTIGO" ? "selected" : "" ?>>
                        Artigo
                    </option>

                </select>

            </div>

        </div>


        <div class="linha-form">


            <div class="campo">

                <label for="preco">
                    Preço
                </label>

                <input
                    type="number"
                    id="preco"
                    name="preco"
                    step="0.01"
                    min="0"
                    value="<?= $produto["preco"] ?>"
                    required
                >

            </div>


            <div class="campo">

                <label for="estoque">
                    Estoque
                </label>

                <input
                    type="number"
                    id="estoque"
                    name="estoque"
                    min="0"
                    value="<?= $produto["estoque"] ?>"
                    required
                >

            </div>

        </div>


        <div class="campo">

            <label for="descricao">
                Descrição
            </label>

            <textarea
                id="descricao"
                name="descricao"
                rows="5"
            ><?= htmlspecialchars($produto["descricao"]) ?></textarea>

        </div>


        <div class="campo">

            <label>
                Imagem atual
            </label>

            <?php if (!empty($produto["imagem"])): ?>

                <img
                    src="../uploads/<?= htmlspecialchars($produto["imagem"]) ?>"
                    alt="Imagem do produto"
                    style="width: 100px; margin-bottom: 10px;"
                >

            <?php endif; ?>


            <label for="imagem">
                Trocar imagem
            </label>

            <input
                type="file"
                id="imagem"
                name="imagem"
                accept="image/*"
            >

        </div>


        <div class="acoes-form">

            <a
                href="produtos.php"
                class="btn-cancelar"
            >
                Cancelar
            </a>


            <button
                type="submit"
                class="btn-salvar"
            >
                Salvar Alterações
            </button>

        </div>


    </form>

</main>

</body>

</html>