<?php
try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=futuroteca;charset=utf8',
        'root',
        ''
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}

$sql = "SELECT * FROM produto LIMIT 5";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="./css/homepage.css">
    <link rel="stylesheet" href="./css/reset.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Audiowide&family=Exo+2:wght@400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOMEPAGE</title>
</head>

<body>

    <div class="container-banners">

        <input type="radio" name="banner" id="banner-1" checked>
        <input type="radio" name="banner" id="banner-2">
        <input type="radio" name="banner" id="banner-3">


        <div class="banner-imagens">

            <img src="../futuroteca/uploads/banner1.png" alt="Banner 1" class="banner banner-1">
            <img src="https://placehold.co/1905x504?text=Banner2" alt="Banner 2" class="banner banner-2">
            <img src="https://placehold.co/1905x504?text=Banner3" alt="Banner 3" class="banner banner-3">

        </div>

        <label for="banner-3" class="banner-seta anterior banner-1-anterior">
            <span class="material-symbols-outlined">
                chevron_left
            </span>
        </label>

        <label for="banner-2" class="banner-seta proximo banner-1-proximo">
            <span class="material-symbols-outlined">
                chevron_right
            </span>
        </label>

        <label for="banner-1" class="banner-seta anterior banner-2-anterior">
            <span class="material-symbols-outlined">
                chevron_left
            </span>
        </label>

        <label for="banner-3" class="banner-seta proximo banner-2-proximo">
            <span class="material-symbols-outlined">
                chevron_right
            </span>
        </label>

        <label for="banner-2" class="banner-seta anterior banner-3-anterior">
            <span class="material-symbols-outlined">
                chevron_left
            </span>
        </label>

        <label for="banner-1" class="banner-seta proximo banner-3-proximo">
            <span class="material-symbols-outlined">
                chevron_right
            </span>
        </label>

        <div class="banner-passador">
            <label for="banner-1" class="dot"></label>
            <label for="banner-2" class="dot"></label>
            <label for="banner-3" class="dot"></label>
        </div>

    </div>

    <main>
        <section class="secao">
            <h2 class="secao-titulo">Categorias em destaque</h2>

            <div class="categoria-lista" role="tablist">

                <button class="categoria-botao" role="tab">
                    <span class="material-symbols-outlined">favorite</span>
                    <p>Romance</p>
                </button>

                <button class="categoria-botao" role="tab">
                    <span class="material-symbols-outlined">auto_awesome</span>
                    <p>Fantasia</p>
                </button>

                <button class="categoria-botao" role="tab">
                    <span class="material-symbols-outlined">rocket_launch</span>
                    <p>Ficção</p>
                </button>

                <button class="categoria-botao" role="tab">
                    <span class="material-symbols-outlined">toys</span>
                    <p>Infantil</p>
                </button>

                <button class="categoria-botao" role="tab">
                    <span class="material-symbols-outlined">skull</span>
                    <p>Terror</p>
                </button>

                <button class="categoria-botao" role="tab">
                    <span class="material-symbols-outlined">sell</span>
                    <p>Ofertas</p>
                </button>

            </div>
        </section>

        <section class="mais-vendidos">
            <h2 class="secao-titulo">Os mais vendidos</h2>

            <div class="mais-vendidos-lista">
                <?php if (!empty($produtos)): ?>
                    <?php foreach ($produtos as $produto): ?>
                        <a class="mais-vendidos-item" href="compra.php?id=<?= $produto['id_produto'] ?>"> <img
                                src="<?= htmlspecialchars($produto['imagem'] ?: 'uploads/verity.png') ?>"
                                alt="<?= htmlspecialchars($produto['titulo']) ?>">
                            <div class="mais-vendidos-info">
                                <h3 class="mais-vendidos-titulo"> <?= htmlspecialchars($produto['titulo']) ?> </h3>
                                <h4 class="mais-vendidos-autor"> <?= htmlspecialchars($produto['autor']) ?> </h4>
                                <p class="mais-vendidos-categoria"> <?= htmlspecialchars($produto['categoria']) ?> </p>
                                <h5 class="mais-vendidos-preco"> R$ <?= number_format($produto['preco'], 2, ',', '.') ?> </h5>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Nenhum livro encontrado.</p>
                <?php endif; ?>
            </div>

            <div class="mais-vendidos-passador">
                <!-- AQUI TERA UM CODIGO PARA PASSAR DE PÁGINA AONDE CADA PAGINA TERÁ 10 MAIS-VENDIDO-ITEM -->
                <button class="mais-vendidos-seta">
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>

                <button class="mais-vendidos-seta">
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>
            </div>
        </section>

    </main>
</body>

</html>