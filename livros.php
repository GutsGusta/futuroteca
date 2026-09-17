<?php

try {
    $pdo = new PDO('mysql:host=localhost;dbname=futuroteca;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}


$categorias_selecionadas = isset($_GET['categoria']) ? (array)$_GET['categoria'] : [];


$sql = "SELECT * FROM produto";
$params = [];

if (!empty($categorias_selecionadas)) {
    $in = str_repeat('?,', count($categorias_selecionadas) - 1) . '?';
    $sql .= " WHERE categoria IN ($in)";
    $params = $categorias_selecionadas;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);


$categorias = [
    'Ficção', 'Romance', 'Fantasia', 'Aventura', 'Suspense', 
    'Comédia', 'Infantil', 'Biografia', 'Terror', 'Programação', 
    'Artigo Científico', 'História', 'HQ e Mangás', 'Literatura'
];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/header-footer.css">
    <link rel="stylesheet" href="./css/livros.css">
    <title>Livros</title>
</head>
<body>

<main>
    <aside class="barra-lateral">
        <form id="filtro-form" method="GET" action="livros.php">
            <ul class="livros-generos">
                <?php foreach ($categorias as $cat): ?>
                    <?php $checked = in_array($cat, $categorias_selecionadas) ? 'checked' : ''; ?>
                    <li class="livros-indv">
                        <label>
                            <input 
                                type="checkbox" 
                                name="categoria[]" 
                                value="<?= htmlspecialchars($cat) ?>" 
                                <?= $checked ?>
                                onchange="document.getElementById('filtro-form').submit();"
                            > 
                            <?= htmlspecialchars($cat) ?>
                        </label>
                    </li>
                <?php endforeach; ?>
            </ul>
        </form>
    </aside>

    <div class="card-container">
        <?php if (!empty($produtos)): ?>
            <?php foreach ($produtos as $produto): ?>
                <a href="compra.php?id=<?= $produto['id_produto'] ?>" class="card-link">
                <article class="card">
                    <img src="<?= htmlspecialchars($produto['imagem'] ?: 'uploads/verity.png') ?>" alt="<?= htmlspecialchars($produto['titulo']) ?>">
                    <h3><?= htmlspecialchars($produto['titulo']) ?></h3>
                    <p><?= htmlspecialchars($produto['autor']) ?></p>
                    <p><?= htmlspecialchars($produto['categoria']) ?></p>
                    <p><b>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></b></p>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nenhum livro encontrado para as categorias selecionadas.</p>
        <?php endif; ?>
    </div>
</main>
</body>
</html>