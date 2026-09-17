<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/descricao.css">
    <link rel="stylesheet" href="./css/header-footer.css">
    <title>Descrição Produto</title>
</head>
<body>
    <?php
    require_once "partials/header.php"
    ?>
    <h1 class="titulo"> Verity </h1>
    <img class="img-livro" src="./uploads/verity.png">
    <div class="desc-livro">
    <p> Edição Português | por Colleen Hoover (Autor), Thaís Britto (Tradutor)</p>
    <p> Verity Crawford é a autora best-seller por trás de uma série de sucesso. Ela está no auge de sua carreira, aclamada pela crítica e pelo público, no entanto, um súbito e terrível acidente acaba interrompendo suas atividades, deixando-a sem condições de concluir a história...  </p>
    </div>


    <div class="box-compra">
        <button class="escolha-compra"> Comprar</button>
        <p> 39,31</p>
        <p> Chega em ...</p>
        <p class="em-estoque"> Em estoque</p>

        <label>Quantidade:</label>

        <select name="" >
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
        </select>

        <button class="escolha-carrinho">Adicionar ao carrinho</button>
        <button class="botao-comprar">Comprar</button>
    </div>
</body>
</html>