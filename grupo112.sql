-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 26-Jan-2025 às 00:48
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
-- Banco de dados: `grupo112`
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
(18, 'cliente', 'Coimbra', '3030-004', 'Centro', 'cliente@cliente.pt', 123123345, 987125622, 1),
(19, 'Diogo Mesquita', 'rua ', '3040-702', 'Centro', 'dmmesquita31@gmail.com', 965467197, 123123123, 1),
(20, 'cliente1', 'rua', '3040-702', 'Centro', 'cliente1@cliente.com', 123123123, 123123123, 1),
(21, 'alexandra', 'rua da capela 22', '3040-702', 'Centro', 'dmmesquitafifa@gmail.com', 963863363, 123123123, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `contactos`
--

CREATE TABLE `contactos` (
  `id_contacto` int(11) NOT NULL,
  `tipo_contacto` varchar(255) NOT NULL,
  `informacao` varchar(255) NOT NULL,
  `ordem` int(11) NOT NULL,
  `visivel` int(11) NOT NULL DEFAULT 1,
  `map_link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `contactos`
--

INSERT INTO `contactos` (`id_contacto`, `tipo_contacto`, `informacao`, `ordem`, `visivel`, `map_link`) VALUES
(1, 'email', 'dmmesquita0331@gmail.com', 1, 1, '0');

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
(14, 18, 'estabelecimento do cliente', 'rua do estabelecimento do cliente', 'Ratos, Baratas, Formigas', 'Março, Junho, Setembro, Dezembro', '2024-12-29', 'teste teste cliente', 'Renovavel', 1000.00, '2024-12-29 11:28:27', 'Sábado', 1),
(15, 20, 'cliente1-1', 'rua rua', 'Ratos, Baratas, Formigas', 'Janeiro, Abril, Julho, Outubro', '2025-01-01', 'rarara', 'Renovavel', 100.00, '2025-01-01 13:12:04', 'Segunda', 1),
(16, 20, 'cliente1-2', 'rua 2', 'Ratos', 'Janeiro, Maio, Setembro', '2025-01-01', '12231', 'Renovavel', 120.00, '2025-01-01 13:21:02', 'Segunda', 1),
(17, 21, 'celium', 'rua da igreja', 'Ratos, Baratas, Formigas', 'Janeiro, Março, Maio, Julho, Setembro, Novembro', '2025-01-02', 'ola adeus', 'Renovavel', 150.00, '2025-01-02 15:05:24', 'Sábado', 1);

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
(3, 'Diogo Mesquita', 'teste', NULL, 1, 1);

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
(5, 'Roedores', 'Os ratos e ratazanas são roedores com mais de três mil espécies, sendo essencial identificá-los para um controlo eficaz. As três espécies mais comuns em pragas são a Ratazana Comum (Rattus norvegicus), o Rato Negro (Rattus rattus) e o Rato Doméstico (Mus ', '/web/uploads/roedores.jpg', 1, 1),
(6, 'Baratas', 'As baratas são insetos nocivos à saúde humana, conhecidos por propagar diversas doenças. Em Portugal, existem três espécies principais:\r\nBarata Alemã: Castanha, com 12-15 mm de comprimento.\r\nBarata Oriental: Preta, com 20-30 mm de comprimento.\r\nBarata Ame', '/web/uploads/baratas.jpg', 2, 1),
(7, 'Formigas', 'As formigas podem tornar-se num incómodo, em particular quando entram na sua casa. O problema é que não sabemos por onde andaram para se alimentar, e como tal, não as queremos a passear nos nossos armários onde temos os nossos alimentos guardados. As form', '/web/uploads/formigas.jpg', 3, 1),
(8, 'Moscas', 'Os insetos voadores podem ser bastante irritantes e representam riscos à saúde devido às doenças que podem transmitir. Para controlar infestações, é possível adotar medidas simples e sem custos, como:\r\nManter portas e janelas fechadas.\r\nArmazenar alimento', '/web/uploads/moscas.jpg', 4, 1),
(9, 'Aves', 'As aves podem ser uma praga significativa, causando diversos problemas:\r\nDanos à propriedade: Estragam telhas, bloqueiam caleiras e corroem materiais com seus dejetos.\r\nAgressividade: Podem atacar pessoas, especialmente durante a época de reprodução.\r\nTra', '/web/uploads/aves.jpg', 5, 1),
(10, 'Térmitas', 'As térmitas são insetos que se alimentam de madeira e materiais celulósicos, representando uma séria ameaça para estruturas de edifícios. Existem duas espécies principais associadas a danos em construções:\r\nTérmitas da madeira húmida:\r\nPreferem madeira em', '/web/uploads/termitas.png', 6, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `relatorios`
--

CREATE TABLE `relatorios` (
  `id_relatorio` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_estabelecimento` int(11) NOT NULL,
  `id_tecnico` int(11) NOT NULL,
  `data_finalizacao` datetime NOT NULL,
  `arquivo_relatorio` varchar(255) DEFAULT NULL,
  `enviado_email` tinyint(1) DEFAULT 0,
  `atualizado_em` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `criado_em` datetime DEFAULT current_timestamp(),
  `id_agendamento` int(11) DEFAULT NULL,
  `id_visita` int(11) DEFAULT NULL,
  `descricao` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `relatorios`
--

INSERT INTO `relatorios` (`id_relatorio`, `id_cliente`, `id_estabelecimento`, `id_tecnico`, `data_finalizacao`, `arquivo_relatorio`, `enviado_email`, `atualizado_em`, `criado_em`, `id_agendamento`, `id_visita`, `descricao`) VALUES
(18, 20, 15, 22, '0000-00-00 00:00:00', NULL, 0, '2025-01-25 22:03:17', '2025-01-25 22:03:17', 0, 17, 'teste de cortesia'),
(19, 20, 16, 22, '0000-00-00 00:00:00', NULL, 0, '2025-01-25 22:17:55', '2025-01-25 22:17:55', 0, 18, 'teste extra');

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
(10, 'Desinfestação', 'O objetivo é a eliminação de agentes patogénicos. \r\nOs nossos tratamentos de desinfestação estão desenhados de forma específica para cada tipo de Praga. \r\nPor isso, podemos oferecer uma máxima eficácia e garantia de um serviço profissional.', '/web/uploads/desinfestacao.jpg', 1, 1),
(11, 'Desratização', 'A desratização ou controlo de roedores é o processo que consiste em eliminar ratos e ratazanas e que implica um conhecimento profundo do seu habitat, comportamento e biologia, para um efetivo controlo da Praga.', '/web/uploads/desratizacao.jpg', 2, 1),
(12, 'Desbaratização', 'A desbaratização é um termo frequentemente utilizado para nos referirmos a tratamentos para o controlo de Baratas.', '/web/uploads/desbaratizacao.jpg', 3, 1),
(13, 'Tratamento de Madeiras', 'Os insetos que infestam a madeira são muito perigosas e os danos são muito difíceis de detetar, antes de o dano ser já muito elevado/visível.', '/web/uploads/tratamentomadeiras.jpg', 4, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tecnicos`
--

CREATE TABLE `tecnicos` (
  `id_tecnico` int(11) NOT NULL,
  `nome_tecnico` varchar(255) NOT NULL,
  `email_tecnico` varchar(255) NOT NULL,
  `palavra_passe_tecnico` varchar(255) NOT NULL,
  `visivel` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tecnicos`
--

INSERT INTO `tecnicos` (`id_tecnico`, `nome_tecnico`, `email_tecnico`, `palavra_passe_tecnico`, `visivel`) VALUES
(20, 'Diogo', 'tecnico@tecnico.pt', '$2y$10$cJXq/sqY8zREH5GbykV/Kel/hWALtqvdPJnUuCHVZDc1eeU4BaLO.', 1),
(22, 'Alberto', 'testetecnico@tecnico.pt', '$2y$10$922sEzzwrsLY/KI7jmcDpuqKmA.a9YFoWmky7oKK0RKVqGG0oG.Um', 0);

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
(15, 'cliente', 'cliente@cliente.pt', '$2y$10$hdIDKkQJA0rFhhCtimAK2.uDInnogMTdZd1weaA2k8LCh5Cc7ntVS', 'cliente', '2024-12-29 11:27:43', NULL, NULL),
(16, 'Diogo Mesquita', 'dmmesquita31@gmail.com', '$2y$10$EVINhlPlhD7VaYrgkwpIT.Pn0cBeE2GH8xbYdBuHae.bNVC8W/haG', 'cliente', '2024-12-29 15:28:27', 'dec8507c6d26da2d25cd5794fbfab76199cd49a04fc4a1c1bac4c0072a270a47', '2025-01-23 16:46:23'),
(17, 'cliente1', 'cliente1@cliente.com', '$2y$10$9tn9GbavTOIVKb0pwpmLSuaTFhPV.xc7mUbnQbkHy8rFQzdtIIjZm', 'cliente', '2025-01-01 13:11:28', NULL, NULL),
(20, 'tecnico', 'tecnico@tecnico.pt', '$2y$10$cJXq/sqY8zREH5GbykV/Kel/hWALtqvdPJnUuCHVZDc1eeU4BaLO.', 'tecnico', '2025-01-02 11:08:02', NULL, NULL),
(21, 'alexandra', 'alexandra@mesquita.pt', '$2y$10$Ovepx8354e9.8uEoBazmTOD.9xO4zX82qwNU/xwQ3IPAXqhaovxRW', 'cliente', '2025-01-02 15:04:55', NULL, NULL),
(22, 'testetecnico', 'testetecnico@tecnico.pt', '$2y$10$922sEzzwrsLY/KI7jmcDpuqKmA.a9YFoWmky7oKK0RKVqGG0oG.Um', 'tecnico', '2025-01-25 20:11:33', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `visitas`
--

CREATE TABLE `visitas` (
  `id_visita` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_contrato` int(11) NOT NULL,
  `tipo_visita` varchar(50) NOT NULL,
  `data_visita` date NOT NULL,
  `hora_visita` time NOT NULL,
  `id_tecnico` int(11) DEFAULT NULL,
  `observacoes` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Índices para tabela `relatorios`
--
ALTER TABLE `relatorios`
  ADD PRIMARY KEY (`id_relatorio`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_estabelecimento` (`id_estabelecimento`),
  ADD KEY `id_tecnico` (`id_tecnico`);

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
-- Índices para tabela `visitas`
--
ALTER TABLE `visitas`
  ADD PRIMARY KEY (`id_visita`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_tecnico` (`id_tecnico`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  MODIFY `id_agendamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de tabela `alertas`
--
ALTER TABLE `alertas`
  MODIFY `id_alerta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de tabela `contactos`
--
ALTER TABLE `contactos`
  MODIFY `id_contacto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `contratos`
--
ALTER TABLE `contratos`
  MODIFY `id_contrato` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de tabela `equipa`
--
ALTER TABLE `equipa`
  MODIFY `id_membro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `pragas`
--
ALTER TABLE `pragas`
  MODIFY `id_praga` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `relatorios`
--
ALTER TABLE `relatorios`
  MODIFY `id_relatorio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de tabela `servicos`
--
ALTER TABLE `servicos`
  MODIFY `id_servico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de tabela `tecnicos`
--
ALTER TABLE `tecnicos`
  MODIFY `id_tecnico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de tabela `utilizadores`
--
ALTER TABLE `utilizadores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de tabela `visitas`
--
ALTER TABLE `visitas`
  MODIFY `id_visita` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

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

--
-- Limitadores para a tabela `relatorios`
--
ALTER TABLE `relatorios`
  ADD CONSTRAINT `relatorios_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`),
  ADD CONSTRAINT `relatorios_ibfk_2` FOREIGN KEY (`id_estabelecimento`) REFERENCES `contratos` (`id_contrato`),
  ADD CONSTRAINT `relatorios_ibfk_3` FOREIGN KEY (`id_tecnico`) REFERENCES `tecnicos` (`id_tecnico`);

--
-- Limitadores para a tabela `visitas`
--
ALTER TABLE `visitas`
  ADD CONSTRAINT `visitas_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`),
  ADD CONSTRAINT `visitas_ibfk_2` FOREIGN KEY (`id_tecnico`) REFERENCES `tecnicos` (`id_tecnico`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
