<?php
require_once 'proteger-admin.php';
require_once '../crud.php';

$busca = $_GET["busca"] ?? "";
$categoria = $_GET["categoria"] ?? "";
$tipo = $_GET["tipo"] ?? "";

$sql = "SELECT * FROM produto WHERE 1=1";
$params = [];

/* BUSCA POR TÍTULO OU AUTOR */
if (!empty($busca)) {
    $sql .= " AND (titulo LIKE ? OR autor LIKE ?)";
    $params[] = "%" . $busca . "%";
    $params[] = "%" . $busca . "%";
}

/* FILTRO POR CATEGORIA */
if (!empty($categoria)) {
    $sql .= " AND categoria = ?";
    $params[] = $categoria;
}

/* FILTRO POR TIPO */
if (!empty($tipo)) {
    $sql .= " AND tipo = ?";
    $params[] = $tipo;
}

$sql .= " ORDER BY titulo";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
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


        <form method="GET" class="filtros-produtos">

            <input type="text" name="busca" placeholder="Buscar por título ou autor..."
                value="<?= htmlspecialchars($busca) ?>">

            <select name="categoria">

                <option value="">Todas as categorias</option>

                <?php
                $categorias = [
                    "Ficção",
                    "Romance",
                    "Fantasia",
                    "Aventura",
                    "Suspense",
                    "Comédia",
                    "Infantil",
                    "Biografia",
                    "Terror",
                    "Programação",
                    "Artigo Científico",
                    "História",
                    "HQ e Mangás",
                    "Literatura"
                ];
                ?>

                <?php foreach ($categorias as $cat): ?>

                    <option value="<?= htmlspecialchars($cat) ?>" <?= $categoria === $cat ? "selected" : "" ?>>
                        <?= htmlspecialchars($cat) ?>
                    </option>

                <?php endforeach; ?>

            </select>

            <select name="tipo">

                <option value="">Todos os tipos</option>

                <option value="LIVRO" <?= $tipo === "LIVRO" ? "selected" : "" ?>>
                    Livro
                </option>

                <option value="EBOOK" <?= $tipo === "EBOOK" ? "selected" : "" ?>>
                    E-book
                </option>

                <option value="ARTIGO" <?= $tipo === "ARTIGO" ? "selected" : "" ?>>
                    Artigo
                </option>

            </select>

            <button type="submit" class="btn-filtrar">
                Filtrar
            </button>

            <a href="produtos.php" class="btn-limpar">
                Limpar
            </a>

        </form>


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