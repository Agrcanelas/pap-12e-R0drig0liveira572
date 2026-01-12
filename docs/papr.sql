-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 12-Jan-2026 às 12:02
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `papr`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `data` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nome`, `data`) VALUES
(1, 'Educação', '2026-01-12 09:51:59'),
(2, 'Tecnologia', '2026-01-12 09:51:59'),
(3, 'Saúde', '2026-01-12 09:51:59'),
(4, 'Desporto', '2026-01-12 09:51:59'),
(5, 'Arte', '2026-01-12 09:51:59'),
(6, 'Culinária', '2026-01-12 09:51:59'),
(7, 'Música', '2026-01-12 09:51:59'),
(8, 'Construção', '2026-01-12 09:51:59'),
(9, 'Jardinagem', '2026-01-12 09:51:59'),
(10, 'Idiomas', '2026-01-12 09:51:59');

-- --------------------------------------------------------

--
-- Estrutura da tabela `servicos`
--

CREATE TABLE `servicos` (
  `id_servico` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `categoria` int(11) DEFAULT NULL,
  `horas` int(11) DEFAULT NULL,
  `data` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `servicos`
--

INSERT INTO `servicos` (`id_servico`, `nome`, `descricao`, `categoria`, `horas`, `data`) VALUES
(1, 'Aulas de Matemática', 'Explicações escolares', 1, 2, '2026-01-12 09:53:39'),
(2, 'Reparação de Computadores', 'Manutenção técnica', 2, 3, '2026-01-12 09:53:39'),
(3, 'Acompanhamento Sénior', 'Apoio domiciliário', 3, 4, '2026-01-12 09:53:39'),
(4, 'Treino Personalizado', 'Exercício físico', 4, 2, '2026-01-12 09:53:39'),
(5, 'Pintura Artística', 'Aulas práticas', 5, 3, '2026-01-12 09:53:39'),
(6, 'Cozinha Caseira', 'Receitas tradicionais', 6, 2, '2026-01-12 09:53:39'),
(7, 'Aulas de Guitarra', 'Iniciação musical', 7, 3, '2026-01-12 09:53:39'),
(8, 'Pequenas Obras', 'Reparações domésticas', 8, 5, '2026-01-12 09:53:39'),
(9, 'Manutenção de Jardins', 'Limpeza e corte', 9, 4, '2026-01-12 09:53:39'),
(10, 'Aulas de Inglês', 'Conversação', 10, 2, '2026-01-12 09:53:39');

-- --------------------------------------------------------

--
-- Estrutura da tabela `transacoes`
--

CREATE TABLE `transacoes` (
  `id_transacao` int(11) NOT NULL,
  `id_prestador` int(11) DEFAULT NULL,
  `id_receptor` int(11) DEFAULT NULL,
  `id_servico` int(11) DEFAULT NULL,
  `horas_trocadas` int(11) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `data` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `transacoes`
--

INSERT INTO `transacoes` (`id_transacao`, `id_prestador`, `id_receptor`, `id_servico`, `horas_trocadas`, `estado`, `data`) VALUES
(31, 1, 2, 1, 2, 'concluída', '2026-01-12 09:54:03'),
(32, 3, 4, 2, 3, 'concluída', '2026-01-12 09:54:03'),
(33, 5, 6, 3, 4, 'pendente', '2026-01-12 09:54:03'),
(34, 7, 8, 4, 2, 'concluída', '2026-01-12 09:54:03'),
(35, 9, 10, 5, 3, 'cancelada', '2026-01-12 09:54:03'),
(36, 2, 1, 6, 2, 'concluída', '2026-01-12 09:54:03'),
(37, 4, 3, 7, 3, 'pendente', '2026-01-12 09:54:03'),
(38, 6, 5, 8, 5, 'concluída', '2026-01-12 09:54:03'),
(39, 8, 7, 9, 4, 'concluída', '2026-01-12 09:54:03'),
(40, 10, 9, 10, 2, 'pendente', '2026-01-12 09:54:03');

-- --------------------------------------------------------

--
-- Estrutura da tabela `utilizadores`
--

CREATE TABLE `utilizadores` (
  `id_utilizador` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `creditos` int(11) DEFAULT 0,
  `perfil` int(11) DEFAULT NULL,
  `data` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `utilizadores`
--

INSERT INTO `utilizadores` (`id_utilizador`, `nome`, `email`, `password`, `foto`, `creditos`, `perfil`, `data`) VALUES
(1, 'Ana Silva', 'ana@email.com', '123', NULL, 10, 1, '2026-01-12 09:51:03'),
(2, 'Bruno Costa', 'bruno@email.com', '123', 'bruno.jpg', 5, 2, '2026-01-12 09:51:03'),
(3, 'Carla Mendes', 'carla@email.com', '123', NULL, 8, 1, '2026-01-12 09:51:03'),
(4, 'Daniel Rocha', 'daniel@email.com', '123', 'daniel.jpg', 12, 2, '2026-01-12 09:51:03'),
(5, 'Eva Sousa', 'eva@email.com', '123', NULL, 15, 1, '2026-01-12 09:51:03'),
(6, 'Filipe Pires', 'filipe@email.com', '123', 'filipe.jpg', 7, 2, '2026-01-12 09:51:03'),
(7, 'Gina Lopes', 'gina@email.com', '123', NULL, 9, 1, '2026-01-12 09:51:03'),
(8, 'Hugo Teixeira', 'hugo@email.com', '123', 'hugo.jpg', 6, 2, '2026-01-12 09:51:03'),
(9, 'Inês Martins', 'ines@email.com', '123', NULL, 11, 1, '2026-01-12 09:51:03'),
(10, 'João Ribeiro', 'joao@email.com', '123', 'joao.jpg', 4, 2, '2026-01-12 09:51:03');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Índices para tabela `servicos`
--
ALTER TABLE `servicos`
  ADD PRIMARY KEY (`id_servico`),
  ADD KEY `categoria` (`categoria`);

--
-- Índices para tabela `transacoes`
--
ALTER TABLE `transacoes`
  ADD PRIMARY KEY (`id_transacao`),
  ADD KEY `id_prestador` (`id_prestador`),
  ADD KEY `id_receptor` (`id_receptor`),
  ADD KEY `id_servico` (`id_servico`);

--
-- Índices para tabela `utilizadores`
--
ALTER TABLE `utilizadores`
  ADD PRIMARY KEY (`id_utilizador`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `servicos`
--
ALTER TABLE `servicos`
  MODIFY `id_servico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `transacoes`
--
ALTER TABLE `transacoes`
  MODIFY `id_transacao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de tabela `utilizadores`
--
ALTER TABLE `utilizadores`
  MODIFY `id_utilizador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `servicos`
--
ALTER TABLE `servicos`
  ADD CONSTRAINT `servicos_ibfk_1` FOREIGN KEY (`categoria`) REFERENCES `categorias` (`id_categoria`);

--
-- Limitadores para a tabela `transacoes`
--
ALTER TABLE `transacoes`
  ADD CONSTRAINT `transacoes_ibfk_1` FOREIGN KEY (`id_prestador`) REFERENCES `utilizadores` (`id_utilizador`),
  ADD CONSTRAINT `transacoes_ibfk_2` FOREIGN KEY (`id_receptor`) REFERENCES `utilizadores` (`id_utilizador`),
  ADD CONSTRAINT `transacoes_ibfk_3` FOREIGN KEY (`id_servico`) REFERENCES `servicos` (`id_servico`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
