<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=futuroteca;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}


$id_produto = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $pdo->prepare("SELECT * FROM produto WHERE id_produto = ?");
$stmt->execute([$id_produto]);
$produto = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$produto) {
    header("Location: livros.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/header-footer.css">
    <link rel="stylesheet" href="./css/compra.css">
    <title><?= htmlspecialchars($produto['titulo']) ?> - Futuroteca</title>
</head>
<body>
<?php require_once './partials/header.php'; ?>

<main class="detalhes-container">
    <div class="detalhes-imagem">
        <img src="<?= htmlspecialchars($produto['imagem'] ?: 'uploads/verity.png') ?>" alt="<?= htmlspecialchars($produto['titulo']) ?>">
    </div>

    <div class="detalhes-info">
        <span class="badge-tipo"><?= htmlspecialchars($produto['tipo']) ?></span>
        <h1><?= htmlspecialchars($produto['titulo']) ?></h1>
        <p class="autor">Por <strong><?= htmlspecialchars($produto['autor']) ?></strong></p>
        <p class="categoria">Categoria: <span><?= htmlspecialchars($produto['categoria']) ?></span></p>

        <div class="preco-box">
            <span class="preco-label">Preço:</span>
            <span class="preco-valor">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></span>
        </div>

        <p class="estoque <?= $produto['estoque'] > 0 ? 'em-estoque' : 'sem-estoque' ?>">
            <?= $produto['estoque'] > 0 ? "Disponível em estoque ({$produto['estoque']} un.)" : "Indisponível no momento" ?>
        </p>

        <div class="descricao">
            <h3>Descrição</h3>
            <p><?= nl2br(htmlspecialchars($produto['descricao'] ?: 'Nenhuma descrição informada.')) ?></p>
        </div>

        <form action="carrinho.php" method="POST" class="compra-form">
            <input type="hidden" name="id_produto" value="<?= $produto['id_produto'] ?>">
            <div class="qtd-selector">
                <label for="quantidade">Qtd:</label>
                <input type="number" id="quantidade" name="quantidade" value="1" min="1" max="<?= $produto['estoque'] ?>">
            </div>
            <button type="submit" class="btn-comprar" <?= $produto['estoque'] <= 0 ? 'disabled' : '' ?>>
                Adicionar ao Carrinho
            </button>
        </form>
    </div>
</main>
</body>
</html>
