<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos | Futuroteca</title>

    <link rel="stylesheet" href="../css/dashboard.css">
</head>

<body>

    <?php include 'partials/sidebar.php'; ?>

    <main class="conteudo">

        <div class="cabecalho-pagina">
            <div>
                <h1>Produtos</h1>
                <p>Gerencie os livros, artigos e e-books da Futuroteca.</p>
            </div>

            <button href="cadastrar-produto.php" class="btn-novo-produto">
                + Novo Produto
            </button>
        </div>


        <div class="filtros-produtos">

            <input
                type="text"
                placeholder="Buscar por título ou autor..."
            >

            <select>
                <option value="">Todas as categorias</option>
                <option>Romance</option>
                <option>Fantasia</option>
                <option>Terror</option>
                <option>Ficção</option>
            </select>

            <select>
                <option value="">Todos os tipos</option>
                <option>Livro</option>
                <option>E-book</option>
                <option>Artigo</option>
            </select>

        </div>


        <div class="tabela-produtos">

            <table>

                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Autor</th>
                        <th>Categoria</th>
                        <th>Tipo</th>
                        <th>Preço</th>
                        <th>Estoque</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>

                    <tr>
                        <td>Dom Casmurro</td>
                        <td>Machado de Assis</td>
                        <td>Romance</td>
                        <td>Livro</td>
                        <td>R$ 39,90</td>
                        <td>12</td>

                        <td>
                            <button class="btn-editar">
                                Editar
                            </button>

                            <button class="btn-excluir">
                                Excluir
                            </button>
                        </td>
                    </tr>

                    <tr>
                        <td>1984</td>
                        <td>George Orwell</td>
                        <td>Ficção</td>
                        <td>Livro</td>
                        <td>R$ 49,90</td>
                        <td>8</td>

                        <td>
                            <button class="btn-editar">
                                Editar
                            </button>

                            <button class="btn-excluir">
                                Excluir
                            </button>
                        </td>
                    </tr>

                </tbody>

            </table>

        </div>

    </main>

</body>

</html>