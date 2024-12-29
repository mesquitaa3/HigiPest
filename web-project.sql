-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 29-Dez-2024 às 12:13
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
-- Banco de dados: `web-project`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `agendamentos`
--

CREATE TABLE `agendamentos` (
  `id_agendamento` int(11) NOT NULL,
  `id_contrato` int(11) NOT NULL,
  `data_agendada` date NOT NULL,
  `observacoes` text DEFAULT NULL,
  `hora_servico` time NOT NULL,
  `tecnico` int(11) NOT NULL,
  `pragas_tratadas` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `agendamentos`
--

INSERT INTO `agendamentos` (`id_agendamento`, `id_contrato`, `data_agendada`, `observacoes`, `hora_servico`, `tecnico`, `pragas_tratadas`) VALUES
(11, 6, '2024-12-22', 'teste v1', '17:30:00', 7, 'Ratos, Formigas'),
(13, 6, '2024-12-22', 'teste', '10:00:00', 7, 'Ratos'),
(14, 6, '2024-12-22', NULL, '00:00:00', 7, NULL),
(15, 6, '2024-12-22', 'teste v1912', '19:15:00', 7, 'Ratos'),
(16, 7, '2024-12-22', 'teste bla bla', '10:00:00', 7, 'Ratos');

-- --------------------------------------------------------

--
-- Estrutura da tabela `alertas`
--

CREATE TABLE `alertas` (
  `id_alerta` int(11) NOT NULL,
  `id_utilizador` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mensagem` text NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `nome_cliente` varchar(255) NOT NULL,
  `morada_cliente` varchar(255) NOT NULL,
  `codigopostal_cliente` varchar(255) NOT NULL,
  `zona_cliente` varchar(255) NOT NULL,
  `email_cliente` varchar(255) NOT NULL,
  `telemovel_cliente` bigint(20) NOT NULL,
  `nif_cliente` int(11) NOT NULL,
  `visivel` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nome_cliente`, `morada_cliente`, `codigopostal_cliente`, `zona_cliente`, `email_cliente`, `telemovel_cliente`, `nif_cliente`, `visivel`) VALUES
(1, 'ronaldo1', 'cidade do ronaldo', '1000-001', 'Sul', 'ronaldo@gmail.com', 919191919, 0, 1),
(2, 'teste2', 'rua da capela', '3040-702', 'HIGIPREV - Lousã', 'dmmesquita31@gmail.com', 965467197, 0, 1),
(4, 'Diogo Mesquita', 'rua dali', '3030-888', 'Centro', 'dmmesquitafifa@gmail.com', 918274981, 0, 1),
(5, 'José Mesquita', 'Rua da Capela Nº 22', '3040-702', 'Centro', 'jmesquita1972@gmail.com', 911734128, 0, 1),
(6, 'Tomás Nunes', 'Loreto', '3030-001', 'Centro', 'tomas.nunes@gmail.com', 919191123, 0, 1),
(13, 'teste3', 'rua do teste3', '3030-303', 'Centro', 'teste3@cliente.pt', 965467197, 0, 1),
(14, 'teste4', 'rua do teste4', '3030-004', 'Centro', 'teste4@cliente.pt', 123123123, 0, 1),
(15, 'teste5', 'rua do teste5', '3030-005', 'Centro', 'teste5@cliente.pt', 123123321, 123123123, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `contactos`
--

CREATE TABLE `contactos` (
  `id_contacto` int(11) NOT NULL,
  `tipo_contacto` varchar(255) NOT NULL,
  `informacao` varchar(255) NOT NULL,
  `ordem` int(11) NOT NULL,
  `visivel` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `contactos`
--

INSERT INTO `contactos` (`id_contacto`, `tipo_contacto`, `informacao`, `ordem`, `visivel`) VALUES
(1, 'Email', 'dmmesquita0331@gmail.com', 1, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `contratos`
--

CREATE TABLE `contratos` (
  `id_contrato` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `estabelecimento_contrato` varchar(255) NOT NULL,
  `morada_contrato` varchar(255) NOT NULL,
  `pragas_contrato` varchar(255) DEFAULT NULL,
  `meses_contrato` varchar(255) DEFAULT NULL,
  `data_inicio_contrato` date NOT NULL,
  `observacoes_contrato` text DEFAULT NULL,
  `tipo_contrato` enum('Unico','Renovavel') NOT NULL,
  `valor_contrato` decimal(10,2) DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `dia_descanso` varchar(255) NOT NULL,
  `visivel` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `contratos`
--

INSERT INTO `contratos` (`id_contrato`, `id_cliente`, `estabelecimento_contrato`, `morada_contrato`, `pragas_contrato`, `meses_contrato`, `data_inicio_contrato`, `observacoes_contrato`, `tipo_contrato`, `valor_contrato`, `data_criacao`, `dia_descanso`, `visivel`) VALUES
(6, 4, 'Higipragas', 'Rua da Capela Nº22 ', 'Ratos, Baratas, Formigas', 'Março, Junho, Setembro, Dezembro', '2024-12-21', 'teste teste', 'Renovavel', 0.00, '2024-12-21 23:10:51', '', 1),
(7, 6, 'Worten', 'Forum ', 'Ratos, Baratas', 'Abril, Agosto, Dezembro', '2024-12-22', 'ola tomas', 'Renovavel', 100.00, '2024-12-22 15:30:11', '', 1),
(8, 14, 'Teste 4 - Estabelecimento', 'Rua do Estabelecimento do teste 4', 'Ratos, Formigas', 'Janeiro, Abril, Julho, Outubro', '2025-01-28', 'teste 4 allalalalalalalalla ', 'Renovavel', 100.00, '2024-12-28 11:28:58', '', 1),
(12, 15, 'teste 5 asdasdasd', 'tasdasdasdasd', 'Formigas', 'Abril, Agosto, Dezembro', '2024-12-28', 'teste asdasdasd', 'Renovavel', 120.00, '2024-12-28 20:39:04', 'segunda', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `equipa`
--

CREATE TABLE `equipa` (
  `id_membro` int(11) NOT NULL,
  `nome_membro` varchar(255) NOT NULL,
  `funcao` varchar(255) NOT NULL,
  `img` varchar(255) DEFAULT NULL,
  `ordem` int(11) NOT NULL,
  `visivel` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `equipa`
--

INSERT INTO `equipa` (`id_membro`, `nome_membro`, `funcao`, `img`, `ordem`, `visivel`) VALUES
(3, 'Diogo Mesquita', 'Gerente', NULL, 1, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `pragas`
--

CREATE TABLE `pragas` (
  `id_praga` int(11) NOT NULL,
  `praga` varchar(255) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `img` varchar(255) DEFAULT NULL,
  `ordem` int(11) NOT NULL,
  `visivel` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `pragas`
--

INSERT INTO `pragas` (`id_praga`, `praga`, `descricao`, `img`, `ordem`, `visivel`) VALUES
(1, 'ratos', 'rato', '', 1, 1),
(2, 'ratos com patas', 'ratos', '/web/uploads/rato.jpg', 1, 1),
(3, 'baratas', 'baratas', NULL, 2, 1),
(4, 'moscas', 'moscas', NULL, 3, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `servicos`
--

CREATE TABLE `servicos` (
  `id_servico` int(11) NOT NULL,
  `servico` varchar(255) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `img` varchar(255) DEFAULT NULL,
  `ordem` int(11) NOT NULL,
  `visivel` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `servicos`
--

INSERT INTO `servicos` (`id_servico`, `servico`, `descricao`, `img`, `ordem`, `visivel`) VALUES
(1, 'ratos', 'ratooos', '/web/uploads/images.jpg', 1, 0),
(2, 'TESTE2', 'texto texto', '', 2, 0),
(3, 'teste3', 'aaa', '/web/uploads/logout.png', 1, 0),
(5, 'teste4', 'aaaasdasd', '/web/uploads/rodents.png', 6, 0),
(6, 'teste5', 'skkaakak', '/web/uploads/logout.png', 3, 0),
(8, 'cramn', 'cramn', '/web/uploads/rato.jpg', 98, 0),
(9, 'rui', 'rui', '/web/uploads/rato.jpg', 99, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tecnicos`
--

CREATE TABLE `tecnicos` (
  `id_tecnico` int(11) NOT NULL,
  `nome_tecnico` varchar(255) NOT NULL,
  `email_tecnico` varchar(255) NOT NULL,
  `palavra_passe_tecnico` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `utilizadores`
--

CREATE TABLE `utilizadores` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `palavra_passe` varchar(255) NOT NULL,
  `cargo` enum('cliente','administrador','tecnico') NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `utilizadores`
--

INSERT INTO `utilizadores` (`id`, `nome`, `email`, `palavra_passe`, `cargo`, `data_criacao`, `reset_token`, `token_expiry`) VALUES
(3, 'teste', 'dmmesquita31@gmail.com', '$2y$10$L/O1WDRVv9TkgXsovrXeeO300MmAwhKM/G4KWCsrLF02.iN8G4zDm', 'administrador', '2024-11-22 11:02:52', 'd9e71644258982e622a140bf9d6b5f2f4e00f99ce908d9020dfa98fe9874b3a7', '2024-12-10 18:37:10'),
(7, 'Diogo Mesquita', 'dmmesquita0331@gmail.com', '$2y$10$PfjVtsLgf1QqDOwQl7C6FeT8zvjXorgaG4dX6mMO5BqXDkdIuGELm', 'tecnico', '2024-12-22 15:56:46', NULL, NULL),
(8, 'testecliente', 'testecliente@cliente.pt', '$2y$10$TYUV60jHLiupTZqLfJqJ6OfjnZpJoweIYRcsWOKM41UW/QE1lqp/W', 'cliente', '2024-12-27 22:42:15', NULL, NULL),
(9, 'teste3', 'teste3@cliente.pt', '$2y$10$7vt0TpCfSCMi4HvSZFQqiOf2tNxrReu5VZ2O92HJaDbTbI9nd/qji', 'cliente', '2024-12-28 11:21:17', NULL, NULL),
(10, 'teste4', 'teste4@cliente.pt', '$2y$10$iXi0luD7uF8iV9K9qm2B8OiRpIOjgY2ek2VKQm9IMZY/djen6ZzJ.', 'cliente', '2024-12-28 11:27:25', NULL, NULL),
(11, 'admin', 'admin@admin.pt', '$2y$10$NEmvraNoNb3KuaU8O2qCjelPDbTBDqTLe9HBbw.bzVZ7LYkAN9mlC', 'administrador', '2024-12-28 12:16:28', NULL, NULL),
(12, 'teste5', 'teste5@cliente.pt', '$2y$10$Yo8GqXugHCcWP5K1Tr2rCulvfmNP6PswUXS3iljMyX0mPqi8LxD3O', 'cliente', '2024-12-28 20:27:05', NULL, NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  ADD PRIMARY KEY (`id_agendamento`),
  ADD KEY `id_contrato` (`id_contrato`);

--
-- Índices para tabela `alertas`
--
ALTER TABLE `alertas`
  ADD PRIMARY KEY (`id_alerta`),
  ADD KEY `id_utilizador` (`id_utilizador`);

--
-- Índices para tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Índices para tabela `contactos`
--
ALTER TABLE `contactos`
  ADD PRIMARY KEY (`id_contacto`);

--
-- Índices para tabela `contratos`
--
ALTER TABLE `contratos`
  ADD PRIMARY KEY (`id_contrato`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Índices para tabela `equipa`
--
ALTER TABLE `equipa`
  ADD PRIMARY KEY (`id_membro`);

--
-- Índices para tabela `pragas`
--
ALTER TABLE `pragas`
  ADD PRIMARY KEY (`id_praga`);

--
-- Índices para tabela `servicos`
--
ALTER TABLE `servicos`
  ADD PRIMARY KEY (`id_servico`);

--
-- Índices para tabela `tecnicos`
--
ALTER TABLE `tecnicos`
  ADD PRIMARY KEY (`id_tecnico`),
  ADD UNIQUE KEY `email_tecnico` (`email_tecnico`);

--
-- Índices para tabela `utilizadores`
--
ALTER TABLE `utilizadores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  MODIFY `id_agendamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de tabela `alertas`
--
ALTER TABLE `alertas`
  MODIFY `id_alerta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `contactos`
--
ALTER TABLE `contactos`
  MODIFY `id_contacto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `contratos`
--
ALTER TABLE `contratos`
  MODIFY `id_contrato` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `equipa`
--
ALTER TABLE `equipa`
  MODIFY `id_membro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `pragas`
--
ALTER TABLE `pragas`
  MODIFY `id_praga` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `servicos`
--
ALTER TABLE `servicos`
  MODIFY `id_servico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `tecnicos`
--
ALTER TABLE `tecnicos`
  MODIFY `id_tecnico` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `utilizadores`
--
ALTER TABLE `utilizadores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  ADD CONSTRAINT `agendamentos_ibfk_1` FOREIGN KEY (`id_contrato`) REFERENCES `contratos` (`id_contrato`);

--
-- Limitadores para a tabela `alertas`
--
ALTER TABLE `alertas`
  ADD CONSTRAINT `alertas_ibfk_1` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizadores` (`id`);

--
-- Limitadores para a tabela `contratos`
--
ALTER TABLE `contratos`
  ADD CONSTRAINT `contratos_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
