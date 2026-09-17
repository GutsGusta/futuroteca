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

--livros para adicionar no banco de dados

INSERT INTO produto (titulo, autor, categoria, tipo, preco, estoque, descricao, imagem) VALUES
('Dom Casmurro', 'Machado de Assis', 'Literatura Brasileira', 'LIVRO', 29.90, 15, 'Romance clássico que aborda a dúvida e ciúme de Bentinho quanto à fidelidade de Capitu.', 'https://picsum.photos/seed/livro1/200/300'),
('Grande Sertão: Veredas', 'João Guimarães Rosa', 'Literatura Brasileira', 'LIVRO', 45.00, 10, 'A travessia do jagunço Riobaldo pelos sertões mineiros e suas reflexões existenciais.', 'https://picsum.photos/seed/livro2/200/300'),
('A Hora da Estrela', 'Clarice Lispector', 'Literatura Brasileira', 'EBOOK', 19.90, 30, 'A triste vida da datilógrafa nordestina Macabéa no Rio de Janeiro.', 'https://picsum.photos/seed/livro3/200/300'),
('Vidas Secas', 'Graciliano Ramos', 'Literatura Brasileira', 'LIVRO', 24.90, 12, 'A luta pela sobrevivência de uma família de retirantes no sertão nordestino.', 'https://picsum.photos/seed/livro4/200/300'),
('O Cortiço', 'Aluísio Azevedo', 'Literatura Brasileira', 'LIVRO', 22.50, 18, 'Uma representação naturalista da vida em um habitação coletiva no Rio de Janeiro.', 'https://picsum.photos/seed/livro5/200/300'),
('1984', 'George Orwell', 'Ficção Científica', 'LIVRO', 39.90, 25, 'Uma visão distópica sobre um estado totalitário e a vigilância constante do Grande Irmão.', 'https://picsum.photos/seed/livro6/200/300'),
('Duna', 'Frank Herbert', 'Ficção Científica', 'LIVRO', 69.90, 20, 'Paul Atreides precisa navegar os perigos do planeta desértico Arrakis.', 'https://picsum.photos/seed/livro7/200/300'),
('Admirável Mundo Novo', 'Aldous Huxley', 'Ficção Científica', 'EBOOK', 27.90, 40, 'Sociedade futurista onde os seres humanos são pré-condicionados geneticamente.', 'https://picsum.photos/seed/livro8/200/300'),
('Eu, Robô', 'Isaac Asimov', 'Ficção Científica', 'LIVRO', 34.90, 15, 'Coleção de contos que estabelece e explora as Três Leis da Robótica.', 'https://picsum.photos/seed/livro9/200/300'),
('O Guia do Mochileiro das Galáxias', 'Douglas Adams', 'Ficção Científica', 'EBOOK', 21.90, 50, 'Aventuras cômicas de Arthur Dent pelo espaço após a destruição da Terra.', 'https://picsum.photos/seed/livro10/200/300'),
('O Hobbit', 'J.R.R. Tolkien', 'Fantasia', 'LIVRO', 49.90, 14, 'A jornada inesperada do hobbit Bilbo Bolseiro para recuperar o reino dos anões.', 'https://picsum.photos/seed/livro11/200/300'),
('O Senhor dos Anéis: A Sociedade do Anel', 'J.R.R. Tolkien', 'Fantasia', 'LIVRO', 59.90, 22, 'Frodo Bolseiro inicia a missão de destruir o Um Anel nas chamas da Montanha da Perdição.', 'https://picsum.photos/seed/livro12/200/300'),
('O Nome do Vento', 'Patrick Rothfuss', 'Fantasia', 'LIVRO', 54.90, 8, 'A história de Kvothe, um jovem prodígio que se torna o mago mais notório do mundo.', 'https://picsum.photos/seed/livro13/200/300'),
('A Guerra dos Tronos', 'George R.R. Martin', 'Fantasia', 'EBOOK', 39.90, 35, 'Nobreza e conspirações disputam o Trono de Ferro no continente de Westeros.', 'https://picsum.photos/seed/livro14/200/300'),
('Harry Potter e a Pedra Filosofal', 'J.K. Rowling', 'Fantasia', 'LIVRO', 39.90, 30, 'Um garoto órfão descobre que é um bruxo e ingressa na Escola de Hogwarts.', 'https://picsum.photos/seed/livro15/200/300'),
('Sapiens: Uma Breve História da Humanidade', 'Yuval Noah Harari', 'História', 'LIVRO', 52.00, 19, 'Uma caminhada pela história da humanidade, da Idade da Pedra aos tempos modernos.', 'https://picsum.photos/seed/livro16/200/300'),
('Os Armários Vazios', 'Maria Firmina dos Reis', 'História', 'LIVRO', 28.00, 11, 'Romance histórico e abolicionista pioneiro escrito por uma mulher negra no Brasil.', 'https://picsum.photos/seed/livro17/200/300'),
('Brasil: Uma Biografia', 'Lilia M. Schwarcz e Heloisa M. Starling', 'História', 'EBOOK', 36.90, 25, 'Uma análise abrangente e crítica da formação histórica do Brasil.', 'https://picsum.photos/seed/livro18/200/300'),
('A Era dos Extremos', 'Eric Hobsbawm', 'História', 'LIVRO', 74.90, 7, 'Um panorama crítico do breve século XX, de 1914 até a queda da União Soviética.', 'https://picsum.photos/seed/livro19/200/300'),
('Os Meninos da Rua Paulo', 'Ferenc Molnár', 'História', 'LIVRO', 25.00, 16, 'Lutas de grupos infantis em Budapeste que simbolizam conflitos de época.', 'https://picsum.photos/seed/livro20/200/300'),
('Código Limpo', 'Robert C. Martin', 'Tecnologia', 'LIVRO', 89.90, 12, 'Guia prático de desenvolvimento e boas práticas de programação ágil.', 'https://picsum.photos/seed/livro21/200/300'),
('Entendendo Algoritmos', 'Aditya Y. Bhargava', 'Tecnologia', 'LIVRO', 64.90, 18, 'Um guia ilustrado para programadores e curiosos sobre estrutura de dados e algoritmos.', 'https://picsum.photos/seed/livro22/200/300'),
('Arquitetura Limpa', 'Robert C. Martin', 'Tecnologia', 'EBOOK', 49.90, 28, 'Regras universais de arquitetura de software para maximizar a produtividade.', 'https://picsum.photos/seed/livro23/200/300'),
('O Programador Pragmático', 'Andrew Hunt e David Thomas', 'Tecnologia', 'LIVRO', 85.00, 10, 'Dicas essenciais para o desenvolvimento pessoal e profissional em engenharia de software.', 'https://picsum.photos/seed/livro24/200/300'),
('Computação e Inteligência de Máquinas', 'Alan Turing', 'Tecnologia', 'ARTIGO', 0.00, 100, 'Artigo pioneiro que propõe o famoso Teste de Turing para inteligência artificial.', 'https://picsum.photos/seed/livro25/200/300'),
('A República', 'Platão', 'Filosofia', 'LIVRO', 32.00, 15, 'Diálogo filosófico sobre a justiça, a organização da cidade ideal e a alegoria da caverna.', 'https://picsum.photos/seed/livro26/200/300'),
('Assim Falou Zaratustra', 'Friedrich Nietzsche', 'Filosofia', 'EBOOK', 18.90, 40, 'Obra prima filosófica sobre o conceito do Übermensch e a morte de Deus.', 'https://picsum.photos/seed/livro27/200/300'),
('Meditações', 'Marco Aurélio', 'Filosofia', 'LIVRO', 22.90, 20, 'Escritos pessoais do imperador romano sobre ética stoica e autodomínio.', 'https://picsum.photos/seed/livro28/200/300'),
('O Príncipe', 'Nicolau Maquiavel', 'Filosofia', 'LIVRO', 19.90, 30, 'Tratado de política sobre como conquistar e manter o poder estatal.', 'https://picsum.photos/seed/livro29/200/300'),
('Crítica da Razão Pura', 'Immanuel Kant', 'Filosofia', 'LIVRO', 68.00, 5, 'Exame fundamental sobre os limites e alcances do conhecimento humano.', 'https://picsum.photos/seed/livro30/200/300'),
('O Maior Espetáculo da Terra', 'Richard Dawkins', 'Ciência', 'LIVRO', 48.00, 14, 'As evidências científicas que comprovam a teoria da evolução das espécies.', 'https://picsum.photos/seed/livro31/200/300'),
('Cosmos', 'Carl Sagan', 'Ciência', 'LIVRO', 59.90, 18, 'Uma exploração sobre o universo, a ciência e o futuro da civilização humana.', 'https://picsum.photos/seed/livro32/200/300'),
('Uma Breve História do Tempo', 'Stephen Hawking', 'Ciência', 'EBOOK', 29.90, 32, 'Explicações acessíveis sobre buracos negros, o Big Bang e a física quântica.', 'https://picsum.photos/seed/livro33/200/300'),
('O Gene Egoísta', 'Richard Dawkins', 'Ciência', 'LIVRO', 52.90, 9, 'Visão da evolução focada nos genes como unidade principal de seleção natural.', 'https://picsum.photos/seed/livro34/200/300'),
('A Origem das Espécies', 'Charles Darwin', 'Ciência', 'ARTIGO', 0.00, 100, 'Artigo sintético fundamentando o mecanismo de seleção natural.', 'https://picsum.photos/seed/livro35/200/300'),
('O Homem e seus Símbolos', 'Carl G. Jung', 'Psicologia', 'LIVRO', 62.00, 12, 'Exploração dos arquétipos e do inconsciente por meio dos sonhos.', 'https://picsum.photos/seed/livro36/200/300'),
('O Mal-Estar na Civilização', 'Sigmund Freud', 'Psicologia', 'EBOOK', 21.00, 27, 'Análise psicanalítica da tensão entre os desejos individuais e as regras sociais.', 'https://picsum.photos/seed/livro37/200/300'),
('Em Busca de Sentido', 'Viktor E. Frankl', 'Psicologia', 'LIVRO', 31.90, 22, 'Relato sobre a sobrevivência em campos de concentração e o nascimento da Logoterapia.', 'https://picsum.photos/seed/livro38/200/300'),
('Rápido e Devagar: Duas Formas de Pensar', 'Daniel Kahneman', 'Psicologia', 'LIVRO', 58.00, 15, 'Estudo sobre os dois sistemas que moldam nosso julgamento e tomada de decisão.', 'https://picsum.photos/seed/livro39/200/300'),
('O Poder do Hábito', 'Charles Duhigg', 'Psicologia', 'LIVRO', 42.00, 20, 'Como os hábitos funcionam e como podem ser modificados para transformar nossas vidas.', 'https://picsum.photos/seed/livro40/200/300'),
('Pai Rico, Pai Pobre', 'Robert T. Kiyosaki', 'Negócios e Finanças', 'LIVRO', 39.90, 45, 'Conceitos práticos de educação financeira e independência de investimentos.', 'https://picsum.photos/seed/livro41/200/300'),
('O Investidor Inteligente', 'Benjamin Graham', 'Negócios e Finanças', 'LIVRO', 69.90, 11, 'O guia definitivo sobre investimentos de valor no mercado de ações.', 'https://picsum.photos/seed/livro42/200/300'),
('A Riqueza das Nações', 'Adam Smith', 'Negócios e Finanças', 'EBOOK', 24.90, 30, 'O clássico fundacional da economia moderna e do livre mercado.', 'https://picsum.photos/seed/livro43/200/300'),
('De Zero a Um', 'Peter Thiel', 'Negócios e Finanças', 'LIVRO', 37.90, 17, 'Notas sobre startups e como construir o futuro através da inovação.', 'https://picsum.photos/seed/livro44/200/300'),
('Análise de Mercados Financeiros Contemporâneos', 'John Murphy', 'Negócios e Finanças', 'ARTIGO', 0.00, 100, 'Artigo acadêmico sobre métodos avançados de análise técnica.', 'https://picsum.photos/seed/livro45/200/300'),
('Steve Jobs', 'Walter Isaacson', 'Biografia', 'LIVRO', 59.90, 13, 'A biografia autorizada e detalhada sobre o cofundador visionário da Apple.', 'https://picsum.photos/seed/livro46/200/300'),
('Minha História', 'Michelle Obama', 'Biografia', 'EBOOK', 29.90, 25, 'As memórias íntimas da ex-primeira-dama dos Estados Unidos.', 'https://picsum.photos/seed/livro47/200/300'),
('Diário de Anne Frank', 'Anne Frank', 'Biografia', 'LIVRO', 26.90, 19, 'O relato comovente de uma garota judia escondida durante a Segunda Guerra Mundial.', 'https://picsum.photos/seed/livro48/200/300'),
('Elon Musk', 'Walter Isaacson', 'Biografia', 'LIVRO', 64.90, 10, 'A trajetória do empresário por trás da Tesla, SpaceX e X.', 'https://picsum.photos/seed/livro49/200/300'),
('Longa Caminhada até a Liberdade', 'Nelson Mandela', 'Biografia', 'LIVRO', 49.90, 8, 'A autobiografia emocionante do líder e ativista sul-africano.', 'https://picsum.photos/seed/livro50/200/300');