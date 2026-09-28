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

$sql = "SELECT
            produto.*,
            COALESCE(SUM(item_pedido.quantidade), 0) AS total_vendido
        FROM produto
        LEFT JOIN item_pedido
            ON produto.id_produto = item_pedido.id_produto
        GROUP BY produto.id_produto
        ORDER BY total_vendido DESC, produto.id_produto DESC
        LIMIT 5";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sqlAutores = "SELECT DISTINCT autor
               FROM produto
               WHERE autor IS NOT NULL
               AND autor != ''
               ORDER BY autor
               LIMIT 6";

$stmtAutores = $pdo->prepare($sqlAutores);
$stmtAutores->execute();

$autores = $stmtAutores->fetchAll(PDO::FETCH_ASSOC);

// E-BOOKS
$sqlEbooks = "SELECT *
              FROM produto
              WHERE tipo = 'EBOOK'
              ORDER BY id_produto DESC
              LIMIT 5";

$stmtEbooks = $pdo->prepare($sqlEbooks);
$stmtEbooks->execute();

// PROMOÇÕES
$sqlPromocoes = "SELECT *
                 FROM produto
                 WHERE preco_promocional IS NOT NULL
                 AND preco_promocional < preco
                 ORDER BY id_produto DESC
                 LIMIT 5";

$stmtPromocoes = $pdo->prepare($sqlPromocoes);
$stmtPromocoes->execute();

$promocoes = $stmtPromocoes->fetchAll(PDO::FETCH_ASSOC);

$ebooks = $stmtEbooks->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <link rel="stylesheet" href="./css/homepage.css">
    <link rel="stylesheet" href="./css/reset.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Audiowide&family=Exo+2:wght@400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="./css/header-footer.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOMEPAGE</title>
</head>

<body>
    <?php require_once './partials/header.php'; ?>

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

            <h2 class="secao-titulo">
                Categorias em destaque
            </h2>

            <div class="categoria-lista">

                <a href="livros.php?categoria=Romance" class="categoria-botao">
                    <span class="material-symbols-outlined">
                        favorite
                    </span>
                    <p>Romance</p>
                </a>

                <a href="livros.php?categoria=Fantasia" class="categoria-botao">
                    <span class="material-symbols-outlined">
                        auto_awesome
                    </span>
                    <p>Fantasia</p>
                </a>

                <a href="livros.php?categoria=Ficção" class="categoria-botao">
                    <span class="material-symbols-outlined">
                        rocket_launch
                    </span>
                    <p>Ficção</p>
                </a>

                <a href="livros.php?categoria=Infantil" class="categoria-botao">
                    <span class="material-symbols-outlined">
                        toys
                    </span>
                    <p>Infantil</p>
                </a>

                <a href="livros.php?categoria=Terror" class="categoria-botao">
                    <span class="material-symbols-outlined">
                        skull
                    </span>
                    <p>Terror</p>
                </a>

                <a href="livros.php?promocao=1" class="categoria-botao">
                    <span class="material-symbols-outlined">
                        sell
                    </span>
                    <p>Ofertas</p>
                </a>

            </div>

        </section>

        <section class="mais-vendidos" id="mais-vendidos">
            <h2 class="secao-titulo">Os mais vendidos</h2>

            <div class="mais-vendidos-lista">
                <?php if (!empty($produtos)): ?>
                    <?php foreach ($produtos as $produto): ?>
                        <a class="mais-vendidos-item" href="compra.php?id=<?= $produto['id_produto'] ?>"> <img src="<?= !empty($produto['imagem'])
                              ? 'uploads/' . htmlspecialchars($produto['imagem'])
                              : 'uploads/verity.png' ?>" alt="<?= htmlspecialchars($produto['titulo']) ?>">
                            <div class="mais-vendidos-info">
                                <h3 class="mais-vendidos-titulo"> <?= htmlspecialchars($produto['titulo']) ?> </h3>
                                <h4 class="mais-vendidos-autor"> <?= htmlspecialchars($produto['autor']) ?> </h4>
                                <p class="mais-vendidos-categoria"> <?= htmlspecialchars($produto['categoria']) ?> </p>
                                <?php if (
                                    !empty($produto['preco_promocional']) &&
                                    $produto['preco_promocional'] < $produto['preco']
                                ): ?>

                                    <div class="precos-promocao">

                                        <span class="preco-antigo">
                                            R$ <?= number_format(
                                                $produto['preco'],
                                                2,
                                                ',',
                                                '.'
                                            ) ?>
                                        </span>

                                        <span class="preco-promocional">
                                            R$ <?= number_format(
                                                $produto['preco_promocional'],
                                                2,
                                                ',',
                                                '.'
                                            ) ?>
                                        </span>

                                    </div>

                                <?php else: ?>

                                    <h5 class="mais-vendidos-preco">
                                        R$ <?= number_format(
                                            $produto['preco'],
                                            2,
                                            ',',
                                            '.'
                                        ) ?>
                                    </h5>

                                <?php endif; ?>
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

        <section class="secao autores" id="autores">

            <h2 class="secao-titulo">
                Autores
            </h2>

            <div class="autores-lista">

                <?php if (!empty($autores)): ?>

                    <?php foreach ($autores as $autor): ?>

                        <a href="livros.php?autor=<?= urlencode($autor['autor']) ?>" class="autor-item">

                            <span class="material-symbols-outlined">
                                person
                            </span>

                            <p>
                                <?= htmlspecialchars($autor['autor']) ?>
                            </p>

                        </a>

                    <?php endforeach; ?>

                <?php else: ?>

                    <p>Nenhum autor encontrado.</p>

                <?php endif; ?>

            </div>

        </section>

        <section class="mais-vendidos" id="promocoes">

            <div class="secao-cabecalho">

                <h2 class="secao-titulo">
                    Promoções
                </h2>

                <a href="livros.php?promocao=1" class="ver-todos">
                    Ver todos →
                </a>

            </div>

            <div class="mais-vendidos-lista">

                <?php if (!empty($promocoes)): ?>

                    <?php foreach ($promocoes as $produto): ?>

                        <a class="mais-vendidos-item" href="compra.php?id=<?= $produto['id_produto'] ?>">

                            <img src="<?= !empty($produto['imagem'])
                                ? 'uploads/' . htmlspecialchars($produto['imagem'])
                                : 'uploads/verity.png' ?>" alt="<?= htmlspecialchars($produto['titulo']) ?>">

                            <div class="mais-vendidos-info">

                                <h3 class="mais-vendidos-titulo">
                                    <?= htmlspecialchars($produto['titulo']) ?>
                                </h3>

                                <h4 class="mais-vendidos-autor">
                                    <?= htmlspecialchars($produto['autor']) ?>
                                </h4>

                                <p class="mais-vendidos-categoria">
                                    <?= htmlspecialchars($produto['categoria']) ?>
                                </p>

                                <div class="precos-promocao">

                                    <span class="preco-antigo">
                                        R$ <?= number_format(
                                            $produto['preco'],
                                            2,
                                            ',',
                                            '.'
                                        ) ?>
                                    </span>

                                    <span class="preco-promocional">
                                        R$ <?= number_format(
                                            $produto['preco_promocional'],
                                            2,
                                            ',',
                                            '.'
                                        ) ?>
                                    </span>

                                </div>

                            </div>

                        </a>

                    <?php endforeach; ?>

                <?php else: ?>

                    <p>Nenhum produto em promoção.</p>

                <?php endif; ?>

            </div>

        </section>

        <section class="mais-vendidos" id="ebooks">

            <div class="secao-cabecalho">

                <h2 class="secao-titulo">
                    E-books
                </h2>

                <a href="livros.php?tipo=EBOOK" class="ver-todos">
                    Ver todos →
                </a>

            </div>

            <div class="mais-vendidos-lista">

                <?php if (!empty($ebooks)): ?>

                    <?php foreach ($ebooks as $ebook): ?>

                        <a class="mais-vendidos-item" href="compra.php?id=<?= $ebook['id_produto'] ?>">

                            <img src="<?= !empty($ebook['imagem'])
                                ? 'uploads/' . htmlspecialchars($ebook['imagem'])
                                : 'uploads/verity.png' ?>" alt="<?= htmlspecialchars($ebook['titulo']) ?>">

                            <div class="mais-vendidos-info">

                                <h3 class="mais-vendidos-titulo">
                                    <?= htmlspecialchars($ebook['titulo']) ?>
                                </h3>

                                <h4 class="mais-vendidos-autor">
                                    <?= htmlspecialchars($ebook['autor']) ?>
                                </h4>

                                <p class="mais-vendidos-categoria">
                                    <?= htmlspecialchars($ebook['categoria']) ?>
                                </p>

                                <h5 class="mais-vendidos-preco">
                                    R$ <?= number_format(
                                        $ebook['preco'],
                                        2,
                                        ',',
                                        '.'
                                    ) ?>
                                </h5>

                            </div>

                        </a>

                    <?php endforeach; ?>

                <?php else: ?>

                    <p>Nenhum e-book cadastrado.</p>

                <?php endif; ?>

            </div>

        </section>

    </main>
</body>

</html>