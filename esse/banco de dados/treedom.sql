-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 08/06/2025 às 19:05
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
-- Banco de dados: `treedom`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `arvores`
--

CREATE TABLE `arvores` (
  `id` int(11) NOT NULL,
  `localidade` varchar(100) DEFAULT NULL,
  `especie` varchar(50) DEFAULT NULL,
  `nome` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `arvores`
--

INSERT INTO `arvores` (`id`, `localidade`, `especie`, `nome`) VALUES
(6, 'Cerrado', 'Ipê-amarelo', NULL),
(7, 'Mata Atlântica', 'Pau-brasil', NULL),
(8, 'Cerrado/Mata Atlântica', 'Jatobá', NULL),
(9, 'Mata Atlântica', 'Jequitibá-rosa', NULL),
(10, 'Amazônia', 'Copaíba', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `cards`
--

CREATE TABLE `cards` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `raridade` varchar(100) DEFAULT NULL,
  `img` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cards`
--

INSERT INTO `cards` (`id`, `nome`, `raridade`, `img`) VALUES
(1, 'Vinlándia', 'Épica', 'cartas/vinlandia_epico.jpg'),
(2, 'Por do Sol', 'Rara', 'cartas/sol_rara.jpg'),
(3, 'Plantio', 'Comum', 'cartas/plantio_comum.jpg'),
(4, 'Plantação', 'Comum', 'cartas/plantaçao_comum.jpg'),
(5, 'Parque de Bicicletas', 'Épica', 'cartas/parque_epico.jpg'),
(6, 'Minecraft', 'Épica', 'cartas/mine_epico.jpg'),
(7, 'Ficus Benjamina', 'lendaria', 'cartas/ficus_lendario.jpg'),
(8, 'Fazenda', 'Épica', 'cartas/fazenda_epico.jpg'),
(9, 'Campo', 'lendaria', 'cartas/campo_lendario.jpg'),
(10, 'Thorfinn', 'Épica', 'cartas/Thorffin_epico.jpg'),
(11, 'Painel Solar', 'Rara', 'cartas/Painel_raro.jpg');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `especie` varchar(100) DEFAULT NULL,
  `localidade` varchar(100) DEFAULT NULL,
  `data_pedido` date DEFAULT NULL,
  `status` varchar(150) DEFAULT NULL,
  `img` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedidos`
--

INSERT INTO `pedidos` (`id_pedido`, `id_user`, `nome`, `especie`, `localidade`, `data_pedido`, `status`, `img`) VALUES
(1, 1, 'Kit Sementes Raras', 'Pau-brasil', 'Mata Atlântica', '2025-06-07', 'Aguardando processamento', 'NULL'),
(5, 1, 'Kit Sementes Raras', 'Copaíba', 'Amazônia', '2025-06-08', 'Aguardando processamento', 'NULL'),
(6, 1, 'Kit Floresta Diversa', 'Ipê-amarelo', 'Cerrado', '2025-06-08', 'Aguardando processamento', 'NULL');

-- --------------------------------------------------------

--
-- Estrutura para tabela `user_cards`
--

CREATE TABLE `user_cards` (
  `id` int(11) NOT NULL,
  `id_user` int(100) DEFAULT NULL,
  `id_card` int(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `user_cards`
--

INSERT INTO `user_cards` (`id`, `id_user`, `id_card`) VALUES
(1, 1, 5),
(2, 1, 4),
(3, 1, 10),
(4, 1, 9),
(5, 1, 3),
(6, 1, 6),
(7, 1, 3),
(8, 1, 10),
(9, 1, 7),
(10, 1, 4),
(11, 1, 4),
(12, 1, 6),
(13, 1, 10),
(14, 1, 5),
(15, 1, 8),
(16, 1, 3),
(17, 1, 2),
(18, 1, 1),
(19, 1, 7),
(20, 1, 9),
(21, 1, 10),
(22, 1, 5),
(23, 1, 4),
(24, 1, 9),
(25, 1, 10),
(26, 1, 4),
(27, 1, 8),
(28, 1, 3),
(29, 1, 11),
(30, 1, 8),
(31, 1, 3),
(32, 1, 9),
(33, 1, 10),
(34, 1, 1),
(35, 1, 10),
(36, 1, 1),
(37, 1, 2),
(38, 1, 6),
(39, 1, 9),
(40, 1, 7),
(41, 1, 11),
(42, 1, 3),
(43, 1, 5),
(44, 1, 9),
(45, 1, 4),
(46, 1, 8),
(47, 1, 2),
(48, 1, 8),
(49, 1, 3),
(50, 1, 5),
(51, 1, 5),
(52, 1, 10),
(53, 1, 9),
(54, 1, 3),
(55, 1, 6);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `senha` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`id`, `nome`, `email`, `senha`) VALUES
(1, 'Soares\r\n', 'soares@gmail.com', '$2y$10$auV6g2aDDGMNAIjEfm8Bau5VOEHH1vJLkKOS3rtVQU6U.XavU8E2y');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `arvores`
--
ALTER TABLE `arvores`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`);

--
-- Índices de tabela `user_cards`
--
ALTER TABLE `user_cards`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `arvores`
--
ALTER TABLE `arvores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `cards`
--
ALTER TABLE `cards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `user_cards`
--
ALTER TABLE `user_cards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
