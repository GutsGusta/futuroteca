<?php

try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=futuroteca;charset=utf8',
        'root',
        ''
    );

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

} catch (PDOException $e) {

    die("Erro ao conectar ao banco de dados: " . $e->getMessage());

}


/* =========================
   FILTROS RECEBIDOS
========================= */

$categoriasSelecionadas =
    isset($_GET['categoria'])
    ? (array) $_GET['categoria']
    : [];

$autoresSelecionados =
    isset($_GET['autor'])
    ? (array) $_GET['autor']
    : [];

$tiposSelecionados =
    isset($_GET['tipo'])
    ? (array) $_GET['tipo']
    : [];

$precoSelecionado =
    isset($_GET['preco'])
    ? $_GET['preco']
    : "";

$somentePromocoes =
    isset($_GET['promocao']) &&
    $_GET['promocao'] == "1";

/* =========================
   CATEGORIAS
========================= */

$categorias = [
    'Ficção',
    'Romance',
    'Fantasia',
    'Aventura',
    'Suspense',
    'Comédia',
    'Infantil',
    'Biografia',
    'Terror',
    'Programação',
    'Artigo Científico',
    'História',
    'HQ e Mangás',
    'Literatura'
];


/* =========================
   AUTORES DO BANCO
========================= */

$sqlAutores = "SELECT DISTINCT autor
               FROM produto
               WHERE autor IS NOT NULL
               AND autor != ''
               ORDER BY autor";

$stmtAutores = $pdo->prepare($sqlAutores);

$stmtAutores->execute();

$autores = $stmtAutores->fetchAll(PDO::FETCH_COLUMN);


/* =========================
   CONSULTA DOS PRODUTOS
========================= */

$sql = "SELECT * FROM produto";

$condicoes = [];

$params = [];


/* FILTRO POR CATEGORIA */

if (!empty($categoriasSelecionadas)) {

    $marcadores = implode(
        ',',
        array_fill(
            0,
            count($categoriasSelecionadas),
            '?'
        )
    );

    $condicoes[] =
        "categoria IN ($marcadores)";

    foreach ($categoriasSelecionadas as $categoria) {
        $params[] = $categoria;
    }

}


/* FILTRO POR AUTOR */

if (!empty($autoresSelecionados)) {

    $marcadores = implode(
        ',',
        array_fill(
            0,
            count($autoresSelecionados),
            '?'
        )
    );

    $condicoes[] =
        "autor IN ($marcadores)";

    foreach ($autoresSelecionados as $autor) {
        $params[] = $autor;
    }

}


/* FILTRO POR TIPO */

if (!empty($tiposSelecionados)) {

    $marcadores = implode(
        ',',
        array_fill(
            0,
            count($tiposSelecionados),
            '?'
        )
    );

    $condicoes[] =
        "tipo IN ($marcadores)";

    foreach ($tiposSelecionados as $tipo) {
        $params[] = $tipo;
    }

}

/* FILTRO POR PROMOÇÃO */

if ($somentePromocoes) {

    $condicoes[] = "preco_promocional IS NOT NULL
                    AND preco_promocional < preco";

}

/* FILTRO POR PREÇO */

if ($precoSelecionado == "ate30") {

    $condicoes[] = "preco <= 30";

} elseif ($precoSelecionado == "30a60") {

    $condicoes[] =
        "preco > 30 AND preco <= 60";

} elseif ($precoSelecionado == "acima60") {

    $condicoes[] = "preco > 60";

}


/* MONTA O WHERE */

if (!empty($condicoes)) {

    $sql .= " WHERE " .
        implode(" AND ", $condicoes);

}


$sql .= " ORDER BY titulo";


$stmt = $pdo->prepare($sql);

$stmt->execute($params);

$produtos =
    $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>

<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./css/header-footer.css">

    <link rel="stylesheet" href="./css/livros.css">

    <title>Livros | Futuroteca</title>

</head>


<body>


    <?php require_once './partials/header.php'; ?>


    <main class="catalogo">


        <!-- =========================
         SIDEBAR
    ========================== -->

        <aside class="barra-lateral">


            <div class="filtro-cabecalho">

                <h2>Filtros</h2>

                <a href="livros.php">
                    Limpar
                </a>

            </div>


            <form id="filtro-form" method="GET" action="livros.php">


                <!-- GÊNERO -->

                <div class="grupo-filtro">

                    <h3>Gênero</h3>


                    <div class="opcoes-filtro">

                        <?php foreach ($categorias as $categoria): ?>


                            <label class="filtro-item">

                                <input type="checkbox" name="categoria[]" value="<?= htmlspecialchars($categoria) ?>"
                                    <?= in_array(
                                        $categoria,
                                        $categoriasSelecionadas
                                    ) ? 'checked' : '' ?>
                                    onchange="this.form.submit()">

                                <span>
                                    <?= htmlspecialchars($categoria) ?>
                                </span>

                            </label>


                        <?php endforeach; ?>

                    </div>

                </div>


                <!-- AUTOR -->

                <div class="grupo-filtro">

                    <h3>Autor</h3>


                    <div class="opcoes-filtro">

                        <?php foreach ($autores as $autor): ?>


                            <label class="filtro-item">

                                <input type="checkbox" name="autor[]" value="<?= htmlspecialchars($autor) ?>" <?= in_array(
                                      $autor,
                                      $autoresSelecionados
                                  ) ? 'checked' : '' ?> onchange="this.form.submit()">

                                <span>
                                    <?= htmlspecialchars($autor) ?>
                                </span>

                            </label>


                        <?php endforeach; ?>

                    </div>

                </div>


                <!-- TIPO -->

                <div class="grupo-filtro">

                    <h3>Tipo</h3>


                    <div class="opcoes-filtro">

                        <label class="filtro-item">

                            <input type="checkbox" name="tipo[]" value="LIVRO" <?= in_array(
                                'LIVRO',
                                $tiposSelecionados
                            ) ? 'checked' : '' ?> onchange="this.form.submit()">

                            <span>Livro físico</span>

                        </label>


                        <label class="filtro-item">

                            <input type="checkbox" name="tipo[]" value="EBOOK" <?= in_array(
                                'EBOOK',
                                $tiposSelecionados
                            ) ? 'checked' : '' ?> onchange="this.form.submit()">

                            <span>E-book</span>

                        </label>


                        <label class="filtro-item">

                            <input type="checkbox" name="tipo[]" value="ARTIGO" <?= in_array(
                                'ARTIGO',
                                $tiposSelecionados
                            ) ? 'checked' : '' ?> onchange="this.form.submit()">

                            <span>Artigo</span>

                        </label>

                    </div>

                </div>

                <div class="grupo-filtro">

                    <h3>Ofertas</h3>

                    <div class="opcoes-filtro">

                        <label class="filtro-item">

                            <input type="checkbox" name="promocao" value="1" <?= $somentePromocoes ? 'checked' : '' ?>
                                onchange="this.form.submit()">

                            <span>Somente promoções</span>

                        </label>

                    </div>

                </div>


                <!-- PREÇO -->

                <div class="grupo-filtro">

                    <h3>Preço</h3>


                    <div class="opcoes-filtro">


                        <label class="filtro-item">

                            <input type="radio" name="preco" value="ate30" <?= $precoSelecionado == "ate30"
                                ? 'checked'
                                : '' ?> onchange="this.form.submit()">

                            <span>Até R$ 30</span>

                        </label>


                        <label class="filtro-item">

                            <input type="radio" name="preco" value="30a60" <?= $precoSelecionado == "30a60"
                                ? 'checked'
                                : '' ?> onchange="this.form.submit()">

                            <span>R$ 30 a R$ 60</span>

                        </label>


                        <label class="filtro-item">

                            <input type="radio" name="preco" value="acima60" <?= $precoSelecionado == "acima60"
                                ? 'checked'
                                : '' ?> onchange="this.form.submit()">

                            <span>Acima de R$ 60</span>

                        </label>


                    </div>

                </div>


            </form>


        </aside>



        <!-- =========================
         PRODUTOS
    ========================== -->

        <section class="produtos-area">


            <div class="produtos-topo">

                <div>

                    <h1>Livros</h1>

                    <p>
                        Encontre sua próxima leitura
                    </p>

                </div>


                <span>

                    <?= count($produtos) ?>

                    <?= count($produtos) == 1
                        ? 'produto encontrado'
                        : 'produtos encontrados' ?>

                </span>

            </div>



            <div class="card-container">


                <?php if (!empty($produtos)): ?>


                    <?php foreach ($produtos as $produto): ?>


                        <a href="compra.php?id=<?= $produto['id_produto'] ?>" class="card-link">


                            <article class="card">


                                <img src="<?= !empty($produto['imagem'])
                                    ? 'uploads/' . htmlspecialchars($produto['imagem'])
                                    : 'uploads/verity.png' ?>" alt="<?= htmlspecialchars($produto['titulo']) ?>">


                                <div class="card-info">


                                    <span class="card-categoria">

                                        <?= htmlspecialchars(
                                            $produto['categoria']
                                        ) ?>

                                    </span>


                                    <h3>

                                        <?= htmlspecialchars(
                                            $produto['titulo']
                                        ) ?>

                                    </h3>


                                    <p class="card-autor">

                                        <?= htmlspecialchars(
                                            $produto['autor']
                                        ) ?>

                                    </p>


                                    <p class="card-tipo">

                                        <?= htmlspecialchars(
                                            $produto['tipo']
                                        ) ?>

                                    </p>


                                    <?php if (
                                        !empty($produto['preco_promocional']) &&
                                        $produto['preco_promocional'] < $produto['preco']
                                    ): ?>

                                        <div class="card-precos">

                                            <span class="card-preco-antigo">
                                                R$ <?= number_format(
                                                    $produto['preco'],
                                                    2,
                                                    ',',
                                                    '.'
                                                ) ?>
                                            </span>

                                            <span class="card-preco-promocional">
                                                R$ <?= number_format(
                                                    $produto['preco_promocional'],
                                                    2,
                                                    ',',
                                                    '.'
                                                ) ?>
                                            </span>

                                        </div>

                                    <?php else: ?>

                                        <p class="card-preco">
                                            R$ <?= number_format(
                                                $produto['preco'],
                                                2,
                                                ',',
                                                '.'
                                            ) ?>
                                        </p>

                                    <?php endif; ?>


                                </div>


                            </article>


                        </a>


                    <?php endforeach; ?>


                <?php else: ?>


                    <div class="sem-produtos">

                        <h3>
                            Nenhum produto encontrado
                        </h3>

                        <p>
                            Tente alterar os filtros selecionados.
                        </p>

                        <a href="livros.php">
                            Limpar filtros
                        </a>

                    </div>


                <?php endif; ?>


            </div>


        </section>


    </main>


</body>

</html>