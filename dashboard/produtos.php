<?php

require_once '../crud.php';

$produtos = readAll($pdo, "produto");

?>

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

            <a href="cadastrar-produto.php" class="btn-novo-produto">
                + Novo Produto
            </a>
        </div>


        <div class="filtros-produtos">

            <input type="text" placeholder="Buscar por título ou autor...">

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

                    <?php foreach ($produtos as $produto): ?>

                        <tr>
                            <td>
                                <?= htmlspecialchars($produto["titulo"]) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($produto["autor"]) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($produto["categoria"]) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($produto["tipo"]) ?>
                            </td>

                            <td>
                                R$ <?= number_format($produto["preco"], 2, ",", ".") ?>
                            </td>

                            <td>
                                <?= $produto["estoque"] ?>
                            </td>

                            <td>
                                <a href="editar-produto.php?id=<?= $produto['id_produto'] ?>" class="btn-editar">
                                    Editar
                                </a>

                                <a href="excluir-produto.php?id=<?= $produto['id_produto'] ?>" class="btn-excluir"
                                    onclick="return confirm('Tem certeza que deseja excluir este produto?')">
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