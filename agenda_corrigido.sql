-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 13, 2026 at 06:32 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `agenda`
--

-- --------------------------------------------------------

--
-- Table structure for table `administradores`
--

CREATE TABLE `administradores` (
  `id` bigint NOT NULL,
  `usuario_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `alunos`
--

CREATE TABLE `alunos` (
  `id` bigint NOT NULL,
  `usuario_id` bigint NOT NULL,
  `matricula` varchar(100) NOT NULL,
  `turma_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `atendimentos_agendados`
--

CREATE TABLE `atendimentos_agendados` (
  `id` bigint NOT NULL,
  `evento_id` bigint NOT NULL,
  `aluno_id` bigint NOT NULL,
  `status` enum('solicitado','confirmado','cancelado') DEFAULT 'solicitado',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `disciplinas`
--

CREATE TABLE `disciplinas` (
  `id` bigint UNSIGNED NOT NULL,
  `suap_id` bigint UNSIGNED NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `disciplinas`
--

INSERT INTO `disciplinas` (`id`, `suap_id`, `codigo`, `nome`, `created_at`, `updated_at`) VALUES
(1, 252849, 'TIN.0030', 'PROJETO PRÁTICO', '2026-07-07 00:21:51', '2026-07-07 00:21:51'),
(2, 252850, 'TIN.1398', 'GEOGRAFIA', '2026-07-07 00:21:51', '2026-07-07 00:21:51'),
(3, 252851, 'TIN.1407', 'INGLÊS', '2026-07-07 00:21:51', '2026-07-07 00:21:51'),
(4, 252852, 'TIN.0012', 'ORGANIZAÇÃO E NORMAS DA QUALIDADE GESTÃO DE ORGANIZAÇÕES E EMPREENDEDORISMO', '2026-07-07 00:21:52', '2026-07-07 00:21:52'),
(5, 252853, 'TIN.0025', 'PROJETO DE SISTEMAS COM BANCO DE DADOS', '2026-07-07 00:21:52', '2026-07-07 00:21:52'),
(6, 252854, 'TIN.0029', 'SISTEMAS OPERACIONAIS', '2026-07-07 00:21:52', '2026-07-07 00:21:52'),
(7, 252855, 'TIN.1382', 'BIOLOGIA', '2026-07-07 00:21:52', '2026-07-07 00:21:52'),
(8, 252856, 'TIN.1395', 'FILOSOFIA - IV', '2026-07-07 00:21:52', '2026-07-07 00:21:52'),
(9, 252857, 'TIN.0027', 'FUNDAMENTOS DE ENGENHARIA DE SOFTWARE', '2026-07-07 00:21:52', '2026-07-07 00:21:52'),
(10, 252858, 'TIN.0013', 'SEGURANÇA DO TRABALHO, MEIO AMBIENTE E SAÚDE', '2026-07-07 00:21:52', '2026-07-07 00:21:52'),
(11, 252859, 'TIN.1405', 'SOCIOLOGIA', '2026-07-07 00:21:52', '2026-07-07 00:21:52'),
(12, 252860, 'TIN.0018', 'ESPANHOL', '2026-07-07 00:21:52', '2026-07-07 00:21:52'),
(13, 252925, 'TIN.1379', 'EDUCAÇÃO FÍSICA', '2026-07-09 22:34:13', '2026-07-09 22:34:13'),
(14, 252926, 'TIN.1381', 'BIOLOGIA', '2026-07-09 22:45:48', '2026-07-09 22:45:48'),
(15, 252927, 'TIN.1394', 'FILOSOFIA', '2026-07-09 22:45:49', '2026-07-09 22:45:49'),
(16, 252928, 'TIN.1385', 'FÍSICA - III', '2026-07-09 22:45:49', '2026-07-09 22:45:49'),
(17, 252929, 'TIN.1397', 'GEOGRAFIA', '2026-07-09 22:45:49', '2026-07-09 22:45:49'),
(18, 252930, 'TIN.1401', 'HISTÓRIA', '2026-07-09 22:45:49', '2026-07-09 22:45:49'),
(19, 252931, 'TIN.1376', 'PORTUGUÊS', '2026-07-09 22:45:49', '2026-07-09 22:45:49'),
(20, 252932, 'TIN.1388', 'MATEMÁTICA', '2026-07-09 22:45:49', '2026-07-09 22:45:49'),
(21, 252933, 'TIN.1391', 'QUIMICA III', '2026-07-09 22:45:49', '2026-07-09 22:45:49'),
(22, 252934, 'TIN.1404', 'SOCIOLOGIA', '2026-07-09 22:45:49', '2026-07-09 22:45:49'),
(23, 252935, 'TIN.1415', 'GESTÃO E LEGISLAÇÃO AMBIENTAL', '2026-07-09 22:45:49', '2026-07-09 22:45:49'),
(24, 252936, 'TIN.0039', 'IMPACTOS E MONITORAMENTO AMBIENTAL', '2026-07-09 22:45:49', '2026-07-09 22:45:49'),
(25, 252937, 'TIN.0040', 'SANEAMENTO AMBIENTAL E SAUDE PÚBLICA', '2026-07-09 22:45:49', '2026-07-09 22:45:49'),
(26, 252938, 'TIN.0035', 'SOLOS E MEIO AMBIENTE', '2026-07-09 22:45:49', '2026-07-09 22:45:49'),
(27, 252819, 'TIN.1388', 'MATEMÁTICA', '2026-07-21 17:33:07', '2026-07-21 17:33:07'),
(28, 252823, 'TIN.1376', 'PORTUGUÊS', '2026-07-21 17:33:07', '2026-07-21 17:33:07'),
(29, 252820, 'TIN.0015', 'EDUCAÇÃO AMBIENTAL', '2026-07-21 17:33:07', '2026-07-21 17:33:07'),
(30, 252821, 'TIN.1397', 'GEOGRAFIA', '2026-07-21 17:33:07', '2026-07-21 17:33:07'),
(31, 252822, 'TIN.0024', 'BANCO DE DADOS', '2026-07-21 17:33:07', '2026-07-21 17:33:07'),
(32, 252824, 'TIN.1391', 'QUIMICA III', '2026-07-21 17:33:07', '2026-07-21 17:33:07'),
(33, 252825, 'TIN.0014', 'REDAÇÃO TÉCNICA E CIENTÍFICA', '2026-07-21 17:33:07', '2026-07-21 17:33:07'),
(34, 252826, 'TIN.0028', 'REDES DE COMPUTADORES', '2026-07-21 17:33:07', '2026-07-21 17:33:07'),
(35, 252827, 'TIN.1394', 'FILOSOFIA', '2026-07-21 17:33:07', '2026-07-21 17:33:07'),
(36, 252828, 'TIN.1379', 'EDUCAÇÃO FÍSICA', '2026-07-21 17:33:08', '2026-07-21 17:33:08'),
(37, 252829, 'TIN.1401', 'HISTÓRIA', '2026-07-21 17:33:08', '2026-07-21 17:33:08'),
(38, 252830, 'TIN.1381', 'BIOLOGIA', '2026-07-21 17:33:08', '2026-07-21 17:33:08'),
(39, 252831, 'TIN.1385', 'FÍSICA - III', '2026-07-21 17:33:08', '2026-07-21 17:33:08'),
(40, 252832, 'TIN.0022', 'LINGUAGEM DE PROGRAMAÇÃO II', '2026-07-21 17:33:08', '2026-07-21 17:33:08'),
(41, 252833, 'TIN.1404', 'SOCIOLOGIA', '2026-07-21 17:33:08', '2026-07-21 17:33:08');

-- --------------------------------------------------------

--
-- Table structure for table `disciplina_professor`
--

CREATE TABLE `disciplina_professor` (
  `id` bigint UNSIGNED NOT NULL,
  `disciplina_id` bigint UNSIGNED NOT NULL,
  `professor_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `turma_codigo` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `disciplina_professor`
--

INSERT INTO `disciplina_professor` (`id`, `disciplina_id`, `professor_id`, `created_at`, `updated_at`, `turma_codigo`) VALUES
(56, 1, 51, NULL, NULL, '20261.4.18.1I'),
(57, 2, 52, NULL, NULL, '20261.4.18.1I'),
(58, 3, 53, NULL, NULL, '20261.4.18.1I'),
(59, 3, 54, NULL, NULL, '20261.4.18.1I'),
(60, 4, 55, NULL, NULL, '20261.4.18.1I'),
(61, 5, 56, NULL, NULL, '20261.4.18.1I'),
(62, 6, 57, NULL, NULL, '20261.4.18.1I'),
(63, 7, 58, NULL, NULL, '20261.4.18.1I'),
(64, 8, 59, NULL, NULL, '20261.4.18.1I'),
(65, 9, 51, NULL, NULL, '20261.4.18.1I'),
(66, 10, 60, NULL, NULL, '20261.4.18.1I'),
(67, 11, 61, NULL, NULL, '20261.4.18.1I'),
(68, 12, 62, NULL, NULL, '20261.4.18.1I'),
(69, 27, 63, NULL, NULL, '20261.3.18.1I'),
(70, 28, 64, NULL, NULL, '20261.3.18.1I'),
(71, 29, 60, NULL, NULL, '20261.3.18.1I'),
(72, 30, 65, NULL, NULL, '20261.3.18.1I'),
(73, 31, 51, NULL, NULL, '20261.3.18.1I'),
(74, 31, 66, NULL, NULL, '20261.3.18.1I'),
(75, 32, 67, NULL, NULL, '20261.3.18.1I'),
(76, 32, 60, NULL, NULL, '20261.3.18.1I'),
(77, 33, 62, NULL, NULL, '20261.3.18.1I'),
(78, 34, 57, NULL, NULL, '20261.3.18.1I'),
(79, 35, 68, NULL, NULL, '20261.3.18.1I'),
(80, 36, 69, NULL, NULL, '20261.3.18.1I'),
(81, 37, 70, NULL, NULL, '20261.3.18.1I'),
(82, 38, 58, NULL, NULL, '20261.3.18.1I'),
(83, 39, 71, NULL, NULL, '20261.3.18.1I'),
(84, 40, 72, NULL, NULL, '20261.3.18.1I'),
(85, 41, 61, NULL, NULL, '20261.3.18.1I'),
(86, 1, 69, NULL, NULL, '20261.4.18.1I'),
(87, 1, 73, NULL, NULL, '20261.4.18.1I');

-- --------------------------------------------------------

--
-- Table structure for table `eventos`
--

CREATE TABLE `eventos` (
  `id` bigint NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `tipo` enum('prova','trabalho','seminario','reuniao','outro') NOT NULL,
  `data_inicio` date NOT NULL,
  `hora_inicio` time DEFAULT NULL,
  `hora_fim` time DEFAULT NULL,
  `descricao` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `professor_id` bigint UNSIGNED DEFAULT NULL,
  `turma_id` bigint DEFAULT NULL,
  `disciplina_professor_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `eventos`
--

INSERT INTO `eventos` (`id`, `titulo`, `tipo`, `data_inicio`, `hora_inicio`, `hora_fim`, `descricao`, `created_at`, `updated_at`, `professor_id`, `turma_id`, `disciplina_professor_id`) VALUES
(3, 'aaaaaaaaaaa', 'seminario', '2026-06-20', '12:03:00', '12:03:00', 'aaaaaaaaaaaaa', '2026-06-12 00:36:30', '2026-06-12 01:37:26', NULL, NULL, NULL),
(4, 'asd', 'prova', '2026-06-24', '11:01:00', '11:01:00', NULL, '2026-06-12 01:37:19', '2026-06-19 00:32:55', NULL, NULL, NULL),
(5, 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'prova', '2026-06-07', NULL, NULL, NULL, '2026-06-12 01:37:34', '2026-06-19 00:33:02', NULL, NULL, NULL),
(6, 'Prova de filosofia', 'prova', '2026-06-30', '12:34:00', '13:56:00', 'Levem folha de ofício', '2026-07-13 20:38:53', '2026-07-13 20:38:53', NULL, NULL, NULL),
(7, 'fsdfsdfds', 'prova', '2026-07-28', '04:04:00', '05:05:00', 'dsadads', '2026-08-03 21:12:31', '2026-08-03 21:12:31', NULL, NULL, 56),
(8, 'fsdfsdfds', 'prova', '2026-07-28', '04:04:00', '05:05:00', 'dsadads', '2026-08-03 21:12:32', '2026-08-03 21:12:32', NULL, NULL, 56),
(9, 'sdasdas', 'prova', '2026-08-06', '05:05:00', '06:06:00', 'fasassa', '2026-08-03 23:02:52', '2026-08-03 23:02:52', NULL, NULL, 63),
(10, 'Prova projeto prático', 'prova', '2026-08-19', '10:00:00', '11:01:00', 'Aaasasa', '2026-08-10 21:23:28', '2026-08-10 21:23:28', NULL, NULL, 56),
(11, 'TRABALHO PROJETOpratico', 'trabalho', '2026-08-14', '12:04:00', '12:08:00', 'aaaaaaaaaaaaa', '2026-08-13 21:18:14', '2026-08-13 21:18:14', NULL, NULL, 87);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_07_30_193031_remove_turma_id_from_representantes_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `professores`
--

CREATE TABLE `professores` (
  `id` bigint UNSIGNED NOT NULL,
  `suap_id` bigint UNSIGNED DEFAULT NULL,
  `nome` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `matricula` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `professores`
--

INSERT INTO `professores` (`id`, `suap_id`, `nome`, `created_at`, `updated_at`, `matricula`) VALUES
(51, NULL, 'Rui Santos Carige Junior', '2026-07-16 23:01:34', '2026-07-16 23:01:34', '2880403'),
(52, NULL, 'Romulo Lima Meira', '2026-07-16 23:01:34', '2026-07-16 23:01:34', '2569497'),
(53, NULL, 'Adailton di Lauro Dias', '2026-07-16 23:01:34', '2026-07-16 23:01:34', '1334408'),
(54, NULL, 'Geferson Silva Souza', '2026-07-16 23:01:34', '2026-07-16 23:01:34', '3419691'),
(55, NULL, 'Antonio Fernando Teixeira da Silva', '2026-07-16 23:01:34', '2026-07-16 23:01:34', '3397527'),
(56, NULL, 'Neidson Sampaio de Oliveira', '2026-07-16 23:01:34', '2026-07-16 23:01:34', '3509853'),
(57, NULL, 'Joao Gabriel Silva Gomes', '2026-07-16 23:01:34', '2026-07-16 23:01:34', '2026834'),
(58, NULL, 'Alex Oliveira do Lago', '2026-07-16 23:01:34', '2026-07-16 23:01:34', '3505211'),
(59, NULL, 'Azamor Coelho Guedes', '2026-07-16 23:01:34', '2026-07-16 23:01:34', '1813363'),
(60, NULL, 'Eider Esdras Silva Oliveira', '2026-07-16 23:01:34', '2026-07-16 23:01:34', '1182171'),
(61, NULL, 'Nivaldo Correia da Silva', '2026-07-16 23:01:34', '2026-07-16 23:01:34', '3272172'),
(62, NULL, 'Michele Santos Barbosa', '2026-07-16 23:01:34', '2026-07-16 23:01:34', '3060668'),
(63, NULL, 'Joao Marcos Ribeiro do Carmo', '2026-07-21 17:33:07', '2026-07-21 17:33:07', '1250897'),
(64, NULL, 'Deisiane Alecrim de Mello Oliveira', '2026-07-21 17:33:07', '2026-07-21 17:33:07', '1037266'),
(65, NULL, 'Jeovangela de Matos Rosa Ribeiro', '2026-07-21 17:33:07', '2026-07-21 17:33:07', '2122906'),
(66, NULL, 'Maria Alice Oliveira Costa Leal', '2026-07-21 17:33:07', '2026-07-21 17:33:07', '2161528'),
(67, NULL, 'Leanderson Bispo Pires', '2026-07-21 17:33:07', '2026-07-21 17:33:07', '3489632'),
(68, NULL, 'Cleiton Gil Barbosa', '2026-07-21 17:33:07', '2026-07-21 17:33:07', '1354500'),
(69, NULL, 'Keila Michelly Canhina Sachimbombo', '2026-07-21 17:33:08', '2026-07-21 17:33:08', '1014034'),
(70, NULL, 'Ana Paula Batista da Silva Cruz', '2026-07-21 17:33:08', '2026-07-21 17:33:08', '3390152'),
(71, NULL, 'Edinelson Pereira dos Santos', '2026-07-21 17:33:08', '2026-07-21 17:33:08', '1820809'),
(72, NULL, 'Monck Charles Nunes de Albuquerque', '2026-07-21 17:33:08', '2026-07-21 17:33:08', '3074421'),
(73, NULL, 'Professor Teste', '2026-08-10 22:49:13', '2026-08-10 22:49:13', '99999999999');

-- --------------------------------------------------------

--
-- Table structure for table `representantes`
--

CREATE TABLE `representantes` (
  `id` bigint NOT NULL,
  `usuario_id` bigint NOT NULL,
  `inicio_mandato` date DEFAULT NULL,
  `fim_mandato` date DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `representantes`
--

INSERT INTO `representantes` (`id`, `usuario_id`, `inicio_mandato`, `fim_mandato`, `ativo`, `created_at`, `updated_at`) VALUES
(1, 15, '2026-07-30', NULL, 1, '2026-07-30 22:50:59', '2026-07-30 22:50:59');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('lpwIfEPVDWLCIT0VgAQb1U8IMtQhEHU5DakPtIDH', 21, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJ6WnNEbmFmSW85S0E3YWZQa1VMaUxrVDB2RmZtd2xHWnNkMFBnWTJ5IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9ldmVudG9zP2VuZD0yMDI2LTA5LTA2VDAwJTNBMDAlM0EwMC0wMyUzQTAwJnN0YXJ0PTIwMjYtMDctMjZUMDAlM0EwMCUzQTAwLTAzJTNBMDAiLCJyb3V0ZSI6bnVsbH0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjIxLCJzdWFwX2p3dCI6ImV5SjBlWEFpT2lKS1YxUWlMQ0poYkdjaU9pSklVekkxTmlKOS5leUoxYzJWeVgybGtJam94TVRnME5qSXNJbVZ0WVdsc0lqb2lJaXdpZFhObGNtNWhiV1VpT2lJeU1ESXpNVEU0TURBeE1pSXNJbVY0Y0NJNk1UYzROalEzTWpZNE5Dd2liM0pwWjE5cFlYUWlPakUzT0RZek9EWXlPRFI5LktKYjBKWFJCM3NVZDJMWHBfcUNTWkFaR05NWEdFR0FxQW5iTjJqUWJhT1EifQ==', 1786392396),
('rMMfp3GyMDWvIpuyCP36XqiNLFGLPJrhrNVNvOOC', 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiIwdDdsaGowYVNvUmpYZDFWNGc4Rzc5TTAwS09VSW1SN2NBM05FTFNUIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9ldmVudG9zIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxMiwic3VhcF9qd3QiOiJleUowZVhBaU9pSktWMVFpTENKaGJHY2lPaUpJVXpJMU5pSjkuZXlKMWMyVnlYMmxrSWpveE1UZzBOaklzSW1WdFlXbHNJam9pSWl3aWRYTmxjbTVoYldVaU9pSXlNREl6TVRFNE1EQXhNaUlzSW1WNGNDSTZNVGM0Tmpjek1UWXhNU3dpYjNKcFoxOXBZWFFpT2pFM09EWTJORFV5TVRGOS5Cb3dWdW9NRzhvWDNscWVRbHAzcmp5WVFYYXZoTzgtcEFXdTFWNzI3SzdnIn0=', 1786645227),
('yzrXADkM6ntMOiqmrYCLFVFlPRslf5a1vdYPL3IB', 20, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJKWHg3WWhTWmg2TG05VDRIRWVpVnRVTXpPVHc0UGZqc1JCZWNEUzExIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9ldmVudG9zP2VuZD0yMDI2LTA5LTA2VDAwJTNBMDAlM0EwMC0wMyUzQTAwJnN0YXJ0PTIwMjYtMDctMjZUMDAlM0EwMCUzQTAwLTAzJTNBMDAiLCJyb3V0ZSI6bnVsbH0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjIwLCJzdWFwX2p3dCI6ImV5SjBlWEFpT2lKS1YxUWlMQ0poYkdjaU9pSklVekkxTmlKOS5leUoxYzJWeVgybGtJam94TXpBek9EVXNJbVZ0WVdsc0lqb2lJaXdpZFhObGNtNWhiV1VpT2lJeU1ESTBNVEU0TURBek15SXNJbVY0Y0NJNk1UYzROVGczTkRJM055d2liM0pwWjE5cFlYUWlPakUzT0RVM09EYzROemQ5Ll9talRjaXpHR1pwbi1URnRxOElEMkJ1d25WQlgzR0JJc1p5Vk5VQzY5N00ifQ==', 1785787766);

-- --------------------------------------------------------

--
-- Table structure for table `turmas`
--

CREATE TABLE `turmas` (
  `id` bigint NOT NULL,
  `nome` varchar(100) NOT NULL,
  `codigo_acesso` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `turmas`
--

INSERT INTO `turmas` (`id`, `nome`, `codigo_acesso`, `created_at`, `updated_at`) VALUES
(1, '1181I', '1181I1nf0rm4t1c4!', '2026-06-02 20:24:28', '2026-06-02 20:24:28'),
(2, '2181I', '2181I1nf0rm4t1c4!', '2026-06-02 20:24:28', '2026-06-02 20:24:28'),
(3, '3181I', '3181I1nf0rm4t1c4!', '2026-06-02 20:24:28', '2026-06-02 20:24:28'),
(4, '4181I', '4181I1nf0rm4t1c4!', '2026-06-02 20:24:28', '2026-06-02 20:24:28'),
(5, '1281I', '1281I4mb13nt3!', '2026-06-02 20:24:28', '2026-06-02 20:24:28'),
(6, '2281I', '2281I4mb13nt3!', '2026-06-02 20:24:28', '2026-06-02 20:24:28'),
(7, '3281I', '3281I4mb13nt3!', '2026-06-02 20:24:28', '2026-06-02 20:24:28'),
(8, '4281I', '4281I4mb13nt3!', '2026-06-02 20:24:28', '2026-06-02 20:24:28'),
(9, '1182I', '1182I4mb13nt3!', '2026-06-02 20:24:28', '2026-06-02 20:24:28'),
(10, '2182I', '2182I4mb13nt3!', '2026-06-02 20:24:28', '2026-06-02 20:24:28'),
(11, '3182I', '3182I4mb13nt3!', '2026-06-02 20:24:28', '2026-06-02 20:24:28'),
(12, '4182I', '4182I4mb13nt3!', '2026-06-02 20:24:28', '2026-06-02 20:24:28'),
(13, '1282I', '1282I4mb13nt3!', '2026-06-02 20:24:28', '2026-06-02 20:24:28'),
(14, '2282I', '2282I4mb13nt3!', '2026-06-02 20:24:28', '2026-06-02 20:24:28'),
(15, '3282I', '3282I4mb13nt3!', '2026-06-02 20:24:28', '2026-06-02 20:24:28'),
(16, '4282I', '4282I4mb13nt3!', '2026-06-02 20:24:28', '2026-06-02 20:24:28');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` bigint NOT NULL,
  `matricula` varchar(30) DEFAULT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `senha_suap` longtext,
  `turma_codigo` varchar(50) DEFAULT NULL,
  `role` enum('admin','aluno','professor','representante') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `matricula`, `nome`, `email`, `password`, `senha_suap`, `turma_codigo`, `role`, `created_at`, `updated_at`) VALUES
(12, '20231180012', 'TAINAH OLIVEIRA', '20231180012@ifba.edu.br', '$2y$12$kiKcZ4Hb7lrXeOv.PWJU2O4hVInPoWVXMXHKlUBR8m4wa8t1udyre', 'eyJpdiI6IkZwbElpb0ErVmllRmdhUk5VTDg1NUE9PSIsInZhbHVlIjoiRVh5NUVGT2NlTHhvQ01KN0JtU2VWZz09IiwibWFjIjoiYWI0YTU4NTFkYWU2MjM5NDJhM2Y4Yjc1YTFlZDI0NzM3ZGUxNjY3ZTE0OTBiNzcwNmU4OGQwNmY0YzllY2FlMyIsInRhZyI6IiJ9', '20261.4.18.1I', 'aluno', '2026-07-06 23:19:04', '2026-08-13 21:20:07'),
(13, '20241280005', 'HEVERTON OLIVEIRA', '20241280005@ifba.edu.br', '$2y$12$lS5r2zqcGPvQjj5CKF1Uq.jMiGpHbwfVgS6DXgsbNrUDspJxrFjce', 'eyJpdiI6IjQzV0xsZkdiOXIxOUt3WmpBS0NDMkE9PSIsInZhbHVlIjoiQ3FTYW83ck0vVXhnb3JTbnBOTXBjdz09IiwibWFjIjoiYzkyZjBlMzc3YTU2ODFkY2Y3NGQzYzA4OTAzY2I3OTA2YmY1YWU2YWExYTc3MWE1MDRlZDlkYTcyZmY4NDRiMyIsInRhZyI6IiJ9', NULL, 'aluno', '2026-07-09 22:33:05', '2026-07-10 01:50:57'),
(15, '20231180002', 'MURILO SIRINEU', '20231180002@ifba.edu.br', '$2y$12$mNGV9H.opaWQyAQ3E8YoU.jt044wU/oKV7YZbEO5bQBFUqvHKKrmq', 'eyJpdiI6IjJCbTBhb1lLM0xuYVJtL0d4MFdwREE9PSIsInZhbHVlIjoiTzU4RnAyQlhFb0hJUG5oUU5YSWhQUT09IiwibWFjIjoiMzAxMWExZjVhMjIxZGY3ODQ5NDIzOGY2ZmUzNWJiMGU1NWMxMjM3YmEzMDViYzI1NmE5MDJmMTJmMjE5MTY4OCIsInRhZyI6IiJ9', '20261.4.18.1I', 'aluno', '2026-07-16 19:22:32', '2026-08-10 21:22:36'),
(16, '3074421', 'Monck Albuquerque', 'monckcharles@ifba.edu.br', '$2y$12$J0pAvPdfbWWPEBDAjOBBg.EgiRnosXhhPGdWj/Qo60lB.TRyI65o2', 'eyJpdiI6Ikx0Y095bTErL1RHaDF0dnV6RGNxQWc9PSIsInZhbHVlIjoicWdEU2JFaUg2d00vZ0xXajhaeFZhZz09IiwibWFjIjoiN2UwZGY3ZmMxNTNjMTFlZmU5OGM2MzBlMDQyODU0MDNhM2NkMzAxNDBhMjI0YTFjMzIyZDBjNDg5OTQ2Y2JmZSIsInRhZyI6IiJ9', NULL, 'professor', '2026-07-21 17:26:16', '2026-07-21 17:26:16'),
(17, '20241180004', 'PEDRO PAZ', '20241180004@ifba.edu.br', '$2y$12$UCEeCQBuYgd1vJY836LB9Ovt6YJElQ1Mrcdy8wc/btKQ.FPrafdgu', 'eyJpdiI6IlpLbDdlVkxWRVZNai9QYXhUaGE1ZlE9PSIsInZhbHVlIjoiTGxHK0VyaUVFZFhsSjNZNW9NM1lzdz09IiwibWFjIjoiMzBjYzE1OTc5NTUxOTE1MjcwMjRjODExMDI5ODc4YWMxOTg2ZTdhMzU5NmE3YjFjYTMxNmVlODk5NTFiYWZkOSIsInRhZyI6IiJ9', '20261.3.18.1I', 'aluno', '2026-07-21 17:32:48', '2026-07-21 17:32:58'),
(19, '', 'MURILO SIRINEU', 'senhormu12q@gmail.com', '$2y$12$01Do4yMT1fI1foqQnQg6Bu8MC5Zhtd3z9/0IrowQHLmaQaLb36Jg.', 'eyJpdiI6IkZ1dnlocGkvZk9CQVczRXp6ajFjUFE9PSIsInZhbHVlIjoiSXRVRTZRekt2VTliMUFwZ2w3WDlydz09IiwibWFjIjoiYWE1NzAxOWZhNWY2NjQ2MTlmNzc5YTYwYTM1YWQwYzQzMmJhYTJmOWY2M2M1ZWY3NWQ5OTY0YWM4NDg1MzdhYiIsInRhZyI6IiJ9', '', 'admin', '2026-07-16 19:22:32', '2026-07-30 21:42:43'),
(20, '20241180033', 'RAQUEL CARMO', '20241180033@ifba.edu.br', '$2y$12$Rj61dXiU5I0tKVl0OER4vujApztITowwc0lQyr5n0XZoixQ4mSIn6', 'eyJpdiI6IjVsZWtadGpNR1BTZlBGR09YZjNJaFE9PSIsInZhbHVlIjoiNnNRT1Bwd0hmb3lSQUdLYWo0TVI2TnZsUlRMdXlrRTJpMnFZS2tSa0s2QT0iLCJtYWMiOiJmN2JjMDhiZjk4MGNhZjY1MGM4YThlMDBlMDlmNDhkOWI4MjFlYWM0NjZmYjdlOGVkM2FjYzE3NWZmYTBjMDZjIiwidGFnIjoiIn0=', '20261.3.18.1I', 'aluno', '2026-08-03 23:09:00', '2026-08-03 23:09:07'),
(21, '99999999999', 'Professor Teste', 'professor.teste@ifba.edu.br', '$2y$12$fez51dLGXMtLncnFemrXj.RruKQMnPY7uNPdyKElRFH4MwpC.6bCG', NULL, NULL, 'professor', '2026-08-10 22:46:28', '2026-08-10 22:46:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_admin_usuario` (`usuario_id`);

--
-- Indexes for table `alunos`
--
ALTER TABLE `alunos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `matricula` (`matricula`),
  ADD KEY `fk_aluno_usuario` (`usuario_id`),
  ADD KEY `fk_aluno_turma` (`turma_id`);

--
-- Indexes for table `atendimentos_agendados`
--
ALTER TABLE `atendimentos_agendados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_atendimento_evento` (`evento_id`),
  ADD KEY `fk_atendimento_aluno` (`aluno_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `disciplinas`
--
ALTER TABLE `disciplinas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_suap_disciplina` (`suap_id`);

--
-- Indexes for table `disciplina_professor`
--
ALTER TABLE `disciplina_professor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_disc_prof` (`disciplina_id`,`professor_id`),
  ADD KEY `fk_dp_professor` (`professor_id`);

--
-- Indexes for table `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_professores` (`professor_id`),
  ADD KEY `fk_turmas` (`turma_id`),
  ADD KEY `fk_evento_oferta` (`disciplina_professor_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `professores`
--
ALTER TABLE `professores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `representantes`
--
ALTER TABLE `representantes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_representante_usuario` (`usuario_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `turmas`
--
ALTER TABLE `turmas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_acesso` (`codigo_acesso`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `matricula` (`matricula`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administradores`
--
ALTER TABLE `administradores`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `alunos`
--
ALTER TABLE `alunos`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `atendimentos_agendados`
--
ALTER TABLE `atendimentos_agendados`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `disciplinas`
--
ALTER TABLE `disciplinas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `disciplina_professor`
--
ALTER TABLE `disciplina_professor`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `professores`
--
ALTER TABLE `professores`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `representantes`
--
ALTER TABLE `representantes`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `turmas`
--
ALTER TABLE `turmas`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `administradores`
--
ALTER TABLE `administradores`
  ADD CONSTRAINT `fk_admin_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `alunos`
--
ALTER TABLE `alunos`
  ADD CONSTRAINT `fk_aluno_turma` FOREIGN KEY (`turma_id`) REFERENCES `turmas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_aluno_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `atendimentos_agendados`
--
ALTER TABLE `atendimentos_agendados`
  ADD CONSTRAINT `fk_atendimento_aluno` FOREIGN KEY (`aluno_id`) REFERENCES `alunos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_atendimento_evento` FOREIGN KEY (`evento_id`) REFERENCES `eventos` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `disciplina_professor`
--
ALTER TABLE `disciplina_professor`
  ADD CONSTRAINT `fk_dp_disciplina` FOREIGN KEY (`disciplina_id`) REFERENCES `disciplinas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_dp_professor` FOREIGN KEY (`professor_id`) REFERENCES `professores` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `eventos`
--
ALTER TABLE `eventos`
  ADD CONSTRAINT `fk_evento_oferta` FOREIGN KEY (`disciplina_professor_id`) REFERENCES `disciplina_professor` (`id`),
  ADD CONSTRAINT `fk_professores` FOREIGN KEY (`professor_id`) REFERENCES `professores` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_turmas` FOREIGN KEY (`turma_id`) REFERENCES `turmas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `representantes`
--
ALTER TABLE `representantes`
  ADD CONSTRAINT `fk_representante_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
