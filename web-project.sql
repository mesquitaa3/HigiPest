-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 29-Dez-2024 às 12:30
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
(18, 'cliente', 'Coimbra', '3030-004', 'Centro', 'cliente@cliente.pt', 123123345, 987125622, 1);

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
(14, 18, 'estabelecimento do cliente', 'rua do estabelecimento do cliente', 'Ratos, Baratas, Formigas', 'Março, Junho, Setembro, Dezembro', '2024-12-29', 'teste teste cliente', 'Renovavel', 1000.00, '2024-12-29 11:28:27', 'Sábado', 1);

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
(11, 'admin', 'admin@admin.pt', '$2y$10$NEmvraNoNb3KuaU8O2qCjelPDbTBDqTLe9HBbw.bzVZ7LYkAN9mlC', 'administrador', '2024-12-28 12:16:28', NULL, NULL),
(15, 'cliente', 'cliente@cliente.pt', '$2y$10$hdIDKkQJA0rFhhCtimAK2.uDInnogMTdZd1weaA2k8LCh5Cc7ntVS', 'cliente', '2024-12-29 11:27:43', NULL, NULL);

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
  MODIFY `id_agendamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de tabela `alertas`
--
ALTER TABLE `alertas`
  MODIFY `id_alerta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de tabela `contactos`
--
ALTER TABLE `contactos`
  MODIFY `id_contacto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `contratos`
--
ALTER TABLE `contratos`
  MODIFY `id_contrato` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
