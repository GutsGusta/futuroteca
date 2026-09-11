CREATE DATABASE futuroteca;
USE futuroteca;

CREATE TABLE usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    telefone VARCHAR(20),
    endereco VARCHAR(255),
    tipo_usuario ENUM('ADMIN', 'CLIENTE') NOT NULL DEFAULT 'CLIENTE',
    senha VARCHAR(255) NOT NULL
);

CREATE TABLE produto (
    id_produto INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    autor VARCHAR(100),
    categoria VARCHAR(80) NOT NULL,
    tipo ENUM('LIVRO', 'EBOOK', 'ARTIGO') NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    estoque INT NOT NULL DEFAULT 0,
    descricao TEXT,
    imagem VARCHAR(255)
);

CREATE TABLE emprestimo (
    id_emprestimo INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_produto INT NOT NULL,
    data_emprestimo DATE NOT NULL,
    data_devolucao_prevista DATE NOT NULL,
    data_devolucao_real DATE,
    status ENUM('ATIVO', 'DEVOLVIDO', 'ATRASADO') NOT NULL DEFAULT 'ATIVO',

    CONSTRAINT fk_emprestimo_usuario
        FOREIGN KEY (id_usuario)
        REFERENCES usuario(id_usuario),

    CONSTRAINT fk_emprestimo_produto
        FOREIGN KEY (id_produto)
        REFERENCES produto(id_produto)
);

CREATE TABLE carrinho (
    id_carrinho INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL UNIQUE,

    CONSTRAINT fk_carrinho_usuario
        FOREIGN KEY (id_usuario)
        REFERENCES usuario(id_usuario)
);

CREATE TABLE item_carrinho (
    id_item_carrinho INT AUTO_INCREMENT PRIMARY KEY,
    id_carrinho INT NOT NULL,
    id_produto INT NOT NULL,
    quantidade INT NOT NULL DEFAULT 1,
    tipo_operacao ENUM('COMPRA', 'EMPRESTIMO') NOT NULL,

    CONSTRAINT fk_item_carrinho_carrinho
        FOREIGN KEY (id_carrinho)
        REFERENCES carrinho(id_carrinho),

    CONSTRAINT fk_item_carrinho_produto
        FOREIGN KEY (id_produto)
        REFERENCES produto(id_produto)
);

CREATE TABLE pedido (
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    data_pedido DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    valor_total DECIMAL(10,2) NOT NULL,
    tipo_entrega ENUM('RETIRADA', 'ENTREGA') NOT NULL,
    frete DECIMAL(10,2) NOT NULL DEFAULT 0,
    status ENUM(
        'PENDENTE',
        'PAGO',
        'ENVIADO',
        'ENTREGUE',
        'CANCELADO'
    ) NOT NULL DEFAULT 'PENDENTE',

    CONSTRAINT fk_pedido_usuario
        FOREIGN KEY (id_usuario)
        REFERENCES usuario(id_usuario)
);

CREATE TABLE item_pedido (
    id_item_pedido INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT NOT NULL,
    id_produto INT NOT NULL,
    quantidade INT NOT NULL DEFAULT 1,
    preco_unitario DECIMAL(10,2) NOT NULL,

    CONSTRAINT fk_item_pedido_pedido
        FOREIGN KEY (id_pedido)
        REFERENCES pedido(id_pedido),

    CONSTRAINT fk_item_pedido_produto
        FOREIGN KEY (id_produto)
        REFERENCES produto(id_produto)
);