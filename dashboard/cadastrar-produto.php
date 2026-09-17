<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Novo Produto | Futuroteca</title>

    <link rel="stylesheet" href="../css/dashboard.css">
</head>

<body>

    <?php include 'partials/sidebar.php'; ?>

    <main class="conteudo">

        <div class="cabecalho-pagina">
            <div>
                <h1>Novo Produto</h1>
                <p>Cadastre um novo produto na Futuroteca.</p>
            </div>

            <a href="produtos.php" class="btn-voltar">
                ← Voltar
            </a>
        </div>


        <form class="form-produto">

            <div class="campo">
                <label for="titulo">Título</label>

                <input
                    type="text"
                    id="titulo"
                    name="titulo"
                    placeholder="Digite o título"
                    required
                >
            </div>


            <div class="campo">
                <label for="autor">Autor</label>

                <input
                    type="text"
                    id="autor"
                    name="autor"
                    placeholder="Digite o autor"
                >
            </div>


            <div class="linha-form">

                <div class="campo">
                    <label for="categoria">Categoria</label>

                    <select id="categoria" name="categoria" required>
                        <option value="">Selecione</option>
                        <option value="Romance">Romance</option>
                        <option value="Fantasia">Fantasia</option>
                        <option value="Terror">Terror</option>
                        <option value="Ficção">Ficção</option>
                    </select>
                </div>


                <div class="campo">
                    <label for="tipo">Tipo</label>

                    <select id="tipo" name="tipo" required>
                        <option value="">Selecione</option>
                        <option value="LIVRO">Livro</option>
                        <option value="EBOOK">E-book</option>
                        <option value="ARTIGO">Artigo</option>
                    </select>
                </div>

            </div>


            <div class="linha-form">

                <div class="campo">
                    <label for="preco">Preço</label>

                    <input
                        type="number"
                        id="preco"
                        name="preco"
                        step="0.01"
                        min="0"
                        placeholder="0,00"
                        required
                    >
                </div>


                <div class="campo">
                    <label for="estoque">Estoque</label>

                    <input
                        type="number"
                        id="estoque"
                        name="estoque"
                        min="0"
                        value="0"
                        required
                    >
                </div>

            </div>


            <div class="campo">
                <label for="descricao">Descrição</label>

                <textarea
                    id="descricao"
                    name="descricao"
                    rows="5"
                    placeholder="Descrição do produto"
                ></textarea>
            </div>


            <div class="campo">
                <label for="imagem">Imagem</label>

                <input
                    type="file"
                    id="imagem"
                    name="imagem"
                    accept="image/*"
                >
            </div>


            <div class="acoes-form">
                <a href="produtos.php" class="btn-cancelar">
                    Cancelar
                </a>

                <button type="submit" class="btn-salvar">
                    Cadastrar Produto
                </button>
            </div>

        </form>

    </main>

</body>

</html>