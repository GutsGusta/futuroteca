<?php

require_once 'crud.php';

$produtos = readAll($pdo, "produto");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="./css/homepage.css">
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/header-footer.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Audiowide&family=Exo+2:wght@400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php require_once './partials/header.php'; ?>

    <div class="container-banners">
        <div class="banner-imagens">
            <img src="uploads/banner1.png" alt="Banner 1" class="banner-ativo">
            <img src="https://placehold.co/1905x504?text=Banner2" alt="Banner 2" class="banner-inativo">
            <img src="https://placehold.co/1905x504?text=Banner3" alt="Banner 3" class="banner-inativo">
        </div>
        <button class="banner-seta banner-anterior">
            <span class="material-symbols-outlined">chevron_left</span>
        </button>

        <button class="banner-seta banner-proximo">
            <span class="material-symbols-outlined">chevron_right</span>
        </button>
        <div class="banner-passador">
            <!-- AQUI DEVERÁ TER UM CODIGO QUE CONTROLA OS BANNERS E ADICIONA A CLASSE BANNER-ATIVO E BANNER-INATIVO
             NA DIV ACIMA, AQUI DEVEÁ TER UM CODIGO QUE MOSTRE CADA BOTÃO DE ACORDO COM A QUANTIDADE DE BANNERS E 
             COLOCAR A CLASSE ATIVA/INATIVA DEPENDENDO DE QUAL BANNER ESTÁ ATIVO -->
            <span class="dot dot-ativo"></span>
            <span class="dot"></span>
            <span class="dot"></span>
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
                <a class="mais-vendidos-item" href="#">
                    <?php if (count($produtos) == 0): ?>

                        <p>Nenhum produto cadastrado.</p>

                    <?php else: ?>

                        <?php foreach ($produtos as $produto): ?>

                            <a class="mais-vendidos-item" href="compra.php?id=<?= $produto["id_produto"] ?>"
                                >

                                <img src="<?= !empty($produto["imagem"])
                                    ? 'uploads/' . htmlspecialchars($produto["imagem"])
                                    : 'uploads/verity.png' ?>"
                                alt="
                        <?= htmlspecialchars($produto["titulo"]) ?>"
                                >

                                <div class="mais-vendidos-info">

                                    <h3 class="mais-vendidos-titulo">
                                        <?= htmlspecialchars($produto["titulo"]) ?>
                                    </h3>

                                    <h4 class="mais-vendidos-autor">
                                        <?= htmlspecialchars($produto["autor"]) ?>
                                    </h4>

                                    <h5 class="mais-vendidos-preco">
                                        R$
                                        <?= number_format(
                                            $produto["preco"],
                                            2,
                                            ",",
                                            "."
                                        ) ?>
                                    </h5>

                                </div>

                            </a>

                        <?php endforeach; ?>

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