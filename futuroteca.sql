-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 29/09/2026 às 03:15
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `futuroteca`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `carrinho`
--

CREATE TABLE `carrinho` (
  `id_carrinho` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `carrinho`
--

INSERT INTO `carrinho` (`id_carrinho`, `id_usuario`) VALUES
(1, 1),
(2, 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `emprestimo`
--

CREATE TABLE `emprestimo` (
  `id_emprestimo` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `data_emprestimo` date NOT NULL,
  `data_devolucao_prevista` date NOT NULL,
  `data_devolucao_real` date DEFAULT NULL,
  `status` enum('ATIVO','DEVOLVIDO','ATRASADO') NOT NULL DEFAULT 'ATIVO',
  `prorrogado` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `emprestimo`
--

INSERT INTO `emprestimo` (`id_emprestimo`, `id_usuario`, `id_produto`, `data_emprestimo`, `data_devolucao_prevista`, `data_devolucao_real`, `status`, `prorrogado`) VALUES
(1, 1, 1, '2026-09-27', '2026-10-11', '2026-09-27', 'DEVOLVIDO', 0),
(2, 1, 1, '2026-09-27', '2026-11-15', '2026-09-29', 'DEVOLVIDO', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `item_carrinho`
--

CREATE TABLE `item_carrinho` (
  `id_item_carrinho` int(11) NOT NULL,
  `id_carrinho` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL DEFAULT 1,
  `tipo_operacao` enum('COMPRA','EMPRESTIMO') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `item_pedido`
--

CREATE TABLE `item_pedido` (
  `id_item_pedido` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL DEFAULT 1,
  `preco_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `item_pedido`
--

INSERT INTO `item_pedido` (`id_item_pedido`, `id_pedido`, `id_produto`, `quantidade`, `preco_unitario`) VALUES
(1, 1, 1, 1, 39.90);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedido`
--

CREATE TABLE `pedido` (
  `id_pedido` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `data_pedido` datetime NOT NULL DEFAULT current_timestamp(),
  `valor_total` decimal(10,2) NOT NULL,
  `tipo_entrega` enum('RETIRADA','ENTREGA') NOT NULL,
  `frete` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('PENDENTE','PAGO','ENVIADO','ENTREGUE','CANCELADO') NOT NULL DEFAULT 'PENDENTE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedido`
--

INSERT INTO `pedido` (`id_pedido`, `id_usuario`, `data_pedido`, `valor_total`, `tipo_entrega`, `frete`, `status`) VALUES
(1, 1, '2026-09-27 19:02:50', 39.90, 'RETIRADA', 0.00, 'PAGO');

-- --------------------------------------------------------

--
-- Estrutura para tabela `produto`
--

CREATE TABLE `produto` (
  `id_produto` int(11) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `autor` varchar(100) DEFAULT NULL,
  `categoria` varchar(80) NOT NULL,
  `tipo` enum('LIVRO','EBOOK','ARTIGO') NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `preco_promocional` decimal(10,2) DEFAULT NULL,
  `estoque` int(11) NOT NULL DEFAULT 0,
  `descricao` text DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produto`
--

INSERT INTO `produto` (`id_produto`, `titulo`, `autor`, `categoria`, `tipo`, `preco`, `preco_promocional`, `estoque`, `descricao`, `imagem`) VALUES
(1, 'Dom Casmurro', 'Machado de Assis', 'Ficção', 'LIVRO', 39.90, 29.90, 3, 'Livro para teste', '1790545128_Captura de tela 2026-09-19 003138.png'),
(4, '1984', 'George Orwell', 'Ficção', 'LIVRO', 49.90, 39.90, 12, 'Um clássico da literatura distópica que apresenta uma sociedade marcada pela vigilância e pelo controle.', 'verity.png'),
(5, 'O Pequeno Príncipe', 'Antoine de Saint-Exupéry', 'Infantil', 'LIVRO', 34.90, NULL, 18, 'Uma história clássica sobre amizade, responsabilidade e a maneira como enxergamos o mundo.', 'verity.png'),
(6, 'Harry Potter e a Pedra Filosofal', 'J. K. Rowling', 'Fantasia', 'LIVRO', 59.90, 49.90, 10, 'Primeiro livro da série Harry Potter, acompanhando o início da jornada do jovem bruxo em Hogwarts.', 'verity.png'),
(7, 'O Hobbit', 'J. R. R. Tolkien', 'Fantasia', 'LIVRO', 54.90, NULL, 8, 'Bilbo Bolseiro embarca em uma aventura inesperada ao lado de um grupo de anões.', 'verity.png'),
(8, 'It: A Coisa', 'Stephen King', 'Terror', 'LIVRO', 79.90, 69.90, 7, 'Um grupo de amigos enfrenta uma presença assustadora que ameaça a cidade de Derry.', 'verity.png'),
(9, 'A Menina que Roubava Livros', 'Markus Zusak', 'História', 'LIVRO', 44.90, NULL, 9, 'Uma história ambientada durante a Segunda Guerra Mundial sobre livros, amizade e sobrevivência.', 'verity.png'),
(10, 'JavaScript para Iniciantes', 'Futuroteca', 'Programação', 'EBOOK', 24.90, 19.90, 30, 'Material introdutório sobre lógica, variáveis, funções e os principais fundamentos de JavaScript.', 'verity.png'),
(11, 'Introdução ao Desenvolvimento Web', 'Futuroteca', 'Programação', 'EBOOK', 29.90, NULL, 25, 'Introdução aos conceitos fundamentais de HTML, CSS e JavaScript para desenvolvimento web.', 'verity.png'),
(12, 'Turma da Mônica: Aventuras', 'Mauricio de Sousa', 'HQ e Mangás', 'LIVRO', 24.90, NULL, 15, 'Uma coleção de aventuras da Turma da Mônica para leitores de todas as idades.', 'verity.png'),
(13, 'Orgulho e Preconceito', 'Jane Austen', 'Romance', 'LIVRO', 42.90, 34.90, 11, 'Clássico de Jane Austen sobre relações, diferenças sociais e as primeiras impressões.', 'verity.png'),
(14, 'A Revolução dos Bichos', 'George Orwell', 'Ficção', 'EBOOK', 19.90, NULL, 20, 'Uma fábula política de George Orwell que acompanha uma revolução realizada pelos animais de uma fazenda.', 'verity.png'),
(15, 'Fundamentos de Banco de Dados', 'Futuroteca', 'Artigo Científico', 'ARTIGO', 14.90, NULL, 40, 'Material introdutório sobre bancos de dados relacionais, tabelas, chaves e relacionamentos.', 'verity.png'),
(16, 'Memórias Póstumas de Brás Cubas', 'Machado de Assis', 'Literatura', 'LIVRO', 37.90, NULL, 14, 'Um dos principais clássicos da literatura brasileira, narrado de forma irreverente por Brás Cubas.', 'verity.png'),
(17, 'O Cortiço', 'Aluísio Azevedo', 'Literatura', 'LIVRO', 32.90, 27.90, 12, 'Clássico do naturalismo brasileiro que retrata o cotidiano dos moradores de um cortiço no Rio de Janeiro.', 'verity.png'),
(18, 'Iracema', 'José de Alencar', 'Romance', 'LIVRO', 29.90, NULL, 16, 'Romance clássico brasileiro que acompanha a história de Iracema e Martim.', 'verity.png'),
(19, 'Senhora', 'José de Alencar', 'Romance', 'EBOOK', 18.90, 14.90, 30, 'Romance brasileiro que aborda amor, casamento, dinheiro e as relações sociais do século XIX.', 'verity.png'),
(20, 'Percy Jackson e o Ladrão de Raios', 'Rick Riordan', 'Fantasia', 'LIVRO', 49.90, NULL, 13, 'Percy Jackson descobre sua ligação com a mitologia grega e embarca em uma grande aventura.', 'verity.png'),
(21, 'As Crônicas de Nárnia', 'C. S. Lewis', 'Fantasia', 'LIVRO', 69.90, 59.90, 9, 'Aventuras fantásticas ambientadas no mundo mágico de Nárnia.', 'verity.png'),
(22, 'Jogos Vorazes', 'Suzanne Collins', 'Aventura', 'LIVRO', 46.90, 39.90, 15, 'Katniss Everdeen precisa lutar pela sobrevivência em uma competição transmitida para toda Panem.', 'verity.png'),
(23, 'A Ilha do Tesouro', 'Robert Louis Stevenson', 'Aventura', 'LIVRO', 36.90, NULL, 10, 'Uma clássica aventura envolvendo piratas, mapas secretos e a busca por um tesouro.', 'verity.png'),
(24, 'O Iluminado', 'Stephen King', 'Terror', 'LIVRO', 64.90, 54.90, 8, 'Uma família passa uma temporada isolada em um hotel onde acontecimentos perturbadores começam a ocorrer.', 'verity.png'),
(25, 'Drácula', 'Bram Stoker', 'Terror', 'EBOOK', 22.90, NULL, 25, 'Clássico do terror que apresenta a história do misterioso Conde Drácula.', 'verity.png'),
(26, 'Sherlock Holmes: Um Estudo em Vermelho', 'Arthur Conan Doyle', 'Suspense', 'LIVRO', 38.90, 31.90, 11, 'Primeira aventura de Sherlock Holmes e Dr. Watson investigando um misterioso assassinato.', 'verity.png'),
(27, 'Assassinato no Expresso do Oriente', 'Agatha Christie', 'Suspense', 'LIVRO', 44.90, NULL, 14, 'Hercule Poirot investiga um assassinato ocorrido durante uma viagem no famoso Expresso do Oriente.', 'verity.png'),
(28, 'Diário de um Banana', 'Jeff Kinney', 'Infantil', 'LIVRO', 39.90, 34.90, 20, 'As situações divertidas e confusões do cotidiano de Greg Heffley.', 'verity.png'),
(29, 'Alice no País das Maravilhas', 'Lewis Carroll', 'Infantil', 'EBOOK', 17.90, NULL, 30, 'Alice entra em um mundo fantástico cheio de personagens e acontecimentos inesperados.', 'verity.png'),
(30, 'Steve Jobs', 'Walter Isaacson', 'Biografia', 'LIVRO', 74.90, 64.90, 7, 'Biografia baseada em entrevistas que apresenta diferentes momentos da trajetória de Steve Jobs.', 'verity.png'),
(31, 'HTML e CSS: Primeiros Passos', 'Futuroteca', 'Programação', 'EBOOK', 21.90, NULL, 40, 'Material introdutório para criação e estilização de páginas utilizando HTML e CSS.', 'verity.png'),
(32, 'Introdução ao PHP e MySQL', 'Futuroteca', 'Programação', 'EBOOK', 27.90, 22.90, 35, 'Introdução ao desenvolvimento de aplicações web utilizando PHP e bancos de dados MySQL.', 'verity.png'),
(33, 'Inteligência Artificial na Educação', 'Futuroteca', 'Artigo Científico', 'ARTIGO', 12.90, NULL, 50, 'Material introdutório sobre aplicações, possibilidades e desafios do uso da inteligência artificial na educação.', 'verity.png');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `tipo_usuario` enum('ADMIN','CLIENTE') NOT NULL DEFAULT 'CLIENTE',
  `senha` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nome`, `email`, `cpf`, `telefone`, `endereco`, `tipo_usuario`, `senha`) VALUES
(1, 'Giovanni Rigo Rinaldi', 't@t', '888.888.888.-8', '32423423', 'Continental, 647', 'CLIENTE', '$2y$10$iA6rGeBw0o3IIv8PKAG7BeNCayDMZ5p.ksaMlPdE6twdLcw45f1zW'),
(2, 'Administrador', 'admin@futuroteca.com', '00000000000', NULL, NULL, 'ADMIN', '$2y$10$XvlkEyPpOEZhzZT9Z8Wcc.7TWU4oY1LMioEQida0MAqdYXLRfqW46'),
(3, 'jaoa', 'g@g', '342342342', '21312313', 'dfsdfsdfsf', 'CLIENTE', '$2y$10$C6mMTxiJj6no198C3jirYu62IDRalnONRlfQCDN0YmA3BPUYUIUJa');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `carrinho`
--
ALTER TABLE `carrinho`
  ADD PRIMARY KEY (`id_carrinho`),
  ADD UNIQUE KEY `id_usuario` (`id_usuario`);

--
-- Índices de tabela `emprestimo`
--
ALTER TABLE `emprestimo`
  ADD PRIMARY KEY (`id_emprestimo`),
  ADD KEY `fk_emprestimo_usuario` (`id_usuario`),
  ADD KEY `fk_emprestimo_produto` (`id_produto`);

--
-- Índices de tabela `item_carrinho`
--
ALTER TABLE `item_carrinho`
  ADD PRIMARY KEY (`id_item_carrinho`),
  ADD KEY `fk_item_carrinho_carrinho` (`id_carrinho`),
  ADD KEY `fk_item_carrinho_produto` (`id_produto`);

--
-- Índices de tabela `item_pedido`
--
ALTER TABLE `item_pedido`
  ADD PRIMARY KEY (`id_item_pedido`),
  ADD KEY `fk_item_pedido_pedido` (`id_pedido`),
  ADD KEY `fk_item_pedido_produto` (`id_produto`);

--
-- Índices de tabela `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `fk_pedido_usuario` (`id_usuario`);

--
-- Índices de tabela `produto`
--
ALTER TABLE `produto`
  ADD PRIMARY KEY (`id_produto`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `cpf` (`cpf`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `carrinho`
--
ALTER TABLE `carrinho`
  MODIFY `id_carrinho` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `emprestimo`
--
ALTER TABLE `emprestimo`
  MODIFY `id_emprestimo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `item_carrinho`
--
ALTER TABLE `item_carrinho`
  MODIFY `id_item_carrinho` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `item_pedido`
--
ALTER TABLE `item_pedido`
  MODIFY `id_item_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `produto`
--
ALTER TABLE `produto`
  MODIFY `id_produto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `carrinho`
--
ALTER TABLE `carrinho`
  ADD CONSTRAINT `fk_carrinho_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Restrições para tabelas `emprestimo`
--
ALTER TABLE `emprestimo`
  ADD CONSTRAINT `fk_emprestimo_produto` FOREIGN KEY (`id_produto`) REFERENCES `produto` (`id_produto`),
  ADD CONSTRAINT `fk_emprestimo_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Restrições para tabelas `item_carrinho`
--
ALTER TABLE `item_carrinho`
  ADD CONSTRAINT `fk_item_carrinho_carrinho` FOREIGN KEY (`id_carrinho`) REFERENCES `carrinho` (`id_carrinho`),
  ADD CONSTRAINT `fk_item_carrinho_produto` FOREIGN KEY (`id_produto`) REFERENCES `produto` (`id_produto`);

--
-- Restrições para tabelas `item_pedido`
--
ALTER TABLE `item_pedido`
  ADD CONSTRAINT `fk_item_pedido_pedido` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`),
  ADD CONSTRAINT `fk_item_pedido_produto` FOREIGN KEY (`id_produto`) REFERENCES `produto` (`id_produto`);

--
-- Restrições para tabelas `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `fk_pedido_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
