SET FOREIGN_KEY_CHECKS = 0;
DROP DATABASE IF EXISTS `agenda`;
CREATE DATABASE IF NOT EXISTS `agenda` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `agenda`;
DROP TABLE IF EXISTS `administradores`;
CREATE TABLE IF NOT EXISTS `administradores` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_admin_usuario` (`usuario_id`),
  CONSTRAINT `fk_admin_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
DROP TABLE IF EXISTS `alunos`;
CREATE TABLE IF NOT EXISTS `alunos` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint NOT NULL,
  `matricula` varchar(100) NOT NULL,
  `turma_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `matricula` (`matricula`),
  KEY `fk_aluno_usuario` (`usuario_id`),
  KEY `fk_aluno_turma` (`turma_id`),
  CONSTRAINT `fk_aluno_turma` FOREIGN KEY (`turma_id`) REFERENCES `turmas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_aluno_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
DROP TABLE IF EXISTS `atendimentos_agendados`;
CREATE TABLE IF NOT EXISTS `atendimentos_agendados` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `evento_id` bigint NOT NULL,
  `aluno_id` bigint NOT NULL,
  `status` enum('solicitado','confirmado','cancelado') DEFAULT 'solicitado',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_atendimento_evento` (`evento_id`),
  KEY `fk_atendimento_aluno` (`aluno_id`),
  CONSTRAINT `fk_atendimento_aluno` FOREIGN KEY (`aluno_id`) REFERENCES `alunos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_atendimento_evento` FOREIGN KEY (`evento_id`) REFERENCES `eventos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
DROP TABLE IF EXISTS `disciplinas`;
CREATE TABLE IF NOT EXISTS `disciplinas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `suap_id` bigint unsigned NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_suap_disciplina` (`suap_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
INSERT INTO `disciplinas` (`id`, `suap_id`, `codigo`, `nome`, `created_at`, `updated_at`) VALUES
	(1, 252849, 'TIN.0030', 'PROJETO PRÁTICO', '2026-07-06 21:21:51', '2026-07-06 21:21:51'),
	(2, 252850, 'TIN.1398', 'GEOGRAFIA', '2026-07-06 21:21:51', '2026-07-06 21:21:51'),
	(3, 252851, 'TIN.1407', 'INGLÊS', '2026-07-06 21:21:51', '2026-07-06 21:21:51'),
	(4, 252852, 'TIN.0012', 'ORGANIZAÇÃO E NORMAS DA QUALIDADE GESTÃO DE ORGANIZAÇÕES E EMPREENDEDORISMO', '2026-07-06 21:21:52', '2026-07-06 21:21:52'),
	(5, 252853, 'TIN.0025', 'PROJETO DE SISTEMAS COM BANCO DE DADOS', '2026-07-06 21:21:52', '2026-07-06 21:21:52'),
	(6, 252854, 'TIN.0029', 'SISTEMAS OPERACIONAIS', '2026-07-06 21:21:52', '2026-07-06 21:21:52'),
	(7, 252855, 'TIN.1382', 'BIOLOGIA', '2026-07-06 21:21:52', '2026-07-06 21:21:52'),
	(8, 252856, 'TIN.1395', 'FILOSOFIA - IV', '2026-07-06 21:21:52', '2026-07-06 21:21:52'),
	(9, 252857, 'TIN.0027', 'FUNDAMENTOS DE ENGENHARIA DE SOFTWARE', '2026-07-06 21:21:52', '2026-07-06 21:21:52'),
	(10, 252858, 'TIN.0013', 'SEGURANÇA DO TRABALHO, MEIO AMBIENTE E SAÚDE', '2026-07-06 21:21:52', '2026-07-06 21:21:52'),
	(11, 252859, 'TIN.1405', 'SOCIOLOGIA', '2026-07-06 21:21:52', '2026-07-06 21:21:52'),
	(12, 252860, 'TIN.0018', 'ESPANHOL', '2026-07-06 21:21:52', '2026-07-06 21:21:52'),
	(13, 252925, 'TIN.1379', 'EDUCAÇÃO FÍSICA', '2026-07-09 19:34:13', '2026-07-09 19:34:13'),
	(14, 252926, 'TIN.1381', 'BIOLOGIA', '2026-07-09 19:45:48', '2026-07-09 19:45:48'),
	(15, 252927, 'TIN.1394', 'FILOSOFIA', '2026-07-09 19:45:49', '2026-07-09 19:45:49'),
	(16, 252928, 'TIN.1385', 'FÍSICA - III', '2026-07-09 19:45:49', '2026-07-09 19:45:49'),
	(17, 252929, 'TIN.1397', 'GEOGRAFIA', '2026-07-09 19:45:49', '2026-07-09 19:45:49'),
	(18, 252930, 'TIN.1401', 'HISTÓRIA', '2026-07-09 19:45:49', '2026-07-09 19:45:49'),
	(19, 252931, 'TIN.1376', 'PORTUGUÊS', '2026-07-09 19:45:49', '2026-07-09 19:45:49'),
	(20, 252932, 'TIN.1388', 'MATEMÁTICA', '2026-07-09 19:45:49', '2026-07-09 19:45:49'),
	(21, 252933, 'TIN.1391', 'QUIMICA III', '2026-07-09 19:45:49', '2026-07-09 19:45:49'),
	(22, 252934, 'TIN.1404', 'SOCIOLOGIA', '2026-07-09 19:45:49', '2026-07-09 19:45:49'),
	(23, 252935, 'TIN.1415', 'GESTÃO E LEGISLAÇÃO AMBIENTAL', '2026-07-09 19:45:49', '2026-07-09 19:45:49'),
	(24, 252936, 'TIN.0039', 'IMPACTOS E MONITORAMENTO AMBIENTAL', '2026-07-09 19:45:49', '2026-07-09 19:45:49'),
	(25, 252937, 'TIN.0040', 'SANEAMENTO AMBIENTAL E SAUDE PÚBLICA', '2026-07-09 19:45:49', '2026-07-09 19:45:49'),
	(26, 252938, 'TIN.0035', 'SOLOS E MEIO AMBIENTE', '2026-07-09 19:45:49', '2026-07-09 19:45:49');
DROP TABLE IF EXISTS `disciplina_professor`;
CREATE TABLE IF NOT EXISTS `disciplina_professor` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `disciplina_id` bigint unsigned NOT NULL,
  `professor_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `turma_codigo` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_disc_prof` (`disciplina_id`,`professor_id`),
  KEY `fk_dp_professor` (`professor_id`),
  CONSTRAINT `fk_dp_disciplina` FOREIGN KEY (`disciplina_id`) REFERENCES `disciplinas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_dp_professor` FOREIGN KEY (`professor_id`) REFERENCES `professores` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
INSERT INTO `disciplina_professor` (`id`, `disciplina_id`, `professor_id`, `created_at`, `updated_at`, `turma_codigo`) VALUES
	(1, 1, 1, NULL, NULL, '20261.4.18.1I'),
	(2, 2, 2, NULL, NULL, '20261.4.18.1I'),
	(3, 3, 3, NULL, NULL, '20261.4.18.1I'),
	(4, 3, 4, NULL, NULL, '20261.4.18.1I'),
	(5, 4, 5, NULL, NULL, '20261.4.18.1I'),
	(6, 5, 6, NULL, NULL, '20261.4.18.1I'),
	(7, 6, 7, NULL, NULL, '20261.4.18.1I'),
	(8, 7, 8, NULL, NULL, '20261.4.18.1I'),
	(9, 8, 9, NULL, NULL, '20261.4.18.1I'),
	(10, 9, 1, NULL, NULL, '20261.4.18.1I'),
	(11, 10, 10, NULL, NULL, '20261.4.18.1I'),
	(12, 11, 11, NULL, NULL, '20261.4.18.1I'),
	(13, 12, 12, NULL, NULL, '20261.4.18.1I'),
	(14, 13, 13, NULL, NULL, '20261.3.28.1I'),
	(15, 14, 8, NULL, NULL, '20261.3.28.1I'),
	(16, 15, 14, NULL, NULL, '20261.3.28.1I'),
	(17, 16, 15, NULL, NULL, '20261.3.28.1I'),
	(18, 17, 16, NULL, NULL, '20261.3.28.1I'),
	(19, 18, 17, NULL, NULL, '20261.3.28.1I'),
	(20, 19, 18, NULL, NULL, '20261.3.28.1I'),
	(21, 20, 19, NULL, NULL, '20261.3.28.1I'),
	(22, 21, 20, NULL, NULL, '20261.3.28.1I'),
	(23, 21, 10, NULL, NULL, '20261.3.28.1I'),
	(24, 22, 11, NULL, NULL, '20261.3.28.1I'),
	(25, 23, 21, NULL, NULL, '20261.3.28.1I'),
	(26, 24, 22, NULL, NULL, '20261.3.28.1I'),
	(27, 25, 22, NULL, NULL, '20261.3.28.1I'),
	(28, 25, 23, NULL, NULL, '20261.3.28.1I'),
	(29, 26, 24, NULL, NULL, '20261.3.28.1I');
DROP TABLE IF EXISTS `eventos`;
CREATE TABLE IF NOT EXISTS `eventos` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `tipo` enum('prova','trabalho','seminario','reuniao','outro') NOT NULL,
  `data_inicio` date NOT NULL,
  `hora_inicio` time DEFAULT NULL,
  `hora_fim` time DEFAULT NULL,
  `descricao` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
INSERT INTO `eventos` (`id`, `titulo`, `tipo`, `data_inicio`, `hora_inicio`, `hora_fim`, `descricao`, `created_at`, `updated_at`) VALUES
	(3, 'aaaaaaaaaaa', 'seminario', '2026-06-20', '12:03:00', '12:03:00', 'aaaaaaaaaaaaa', '2026-06-11 21:36:30', '2026-06-11 22:37:26'),
	(4, 'asd', 'prova', '2026-06-24', '11:01:00', '11:01:00', NULL, '2026-06-11 22:37:19', '2026-06-18 21:32:55'),
	(5, 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'prova', '2026-06-07', NULL, NULL, NULL, '2026-06-11 22:37:34', '2026-06-18 21:33:02'),
	(6, 'Prova de filosofia', 'prova', '2026-06-30', '12:34:00', '13:56:00', 'Levem folha de ofício', '2026-07-13 17:38:53', '2026-07-13 17:38:53');
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1);
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
DROP TABLE IF EXISTS `professores`;
CREATE TABLE IF NOT EXISTS `professores` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `suap_id` bigint unsigned DEFAULT NULL,
  `nome` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
INSERT INTO `professores` (`id`, `suap_id`, `nome`, `created_at`, `updated_at`) VALUES
	(1, NULL, 'Rui Santos Carige Junior', '2026-07-06 21:21:51', '2026-07-06 21:21:51'),
	(2, NULL, 'Romulo Lima Meira', '2026-07-06 21:21:51', '2026-07-06 21:21:51'),
	(3, NULL, 'Adailton di Lauro Dias', '2026-07-06 21:21:51', '2026-07-06 21:21:51'),
	(4, NULL, 'Geferson Silva Souza', '2026-07-06 21:21:51', '2026-07-06 21:21:51'),
	(5, NULL, 'Antonio Fernando Teixeira da Silva', '2026-07-06 21:21:52', '2026-07-06 21:21:52'),
	(6, NULL, 'Neidson Sampaio de Oliveira', '2026-07-06 21:21:52', '2026-07-06 21:21:52'),
	(7, NULL, 'Joao Gabriel Silva Gomes', '2026-07-06 21:21:52', '2026-07-06 21:21:52'),
	(8, NULL, 'Alex Oliveira do Lago', '2026-07-06 21:21:52', '2026-07-06 21:21:52'),
	(9, NULL, 'Azamor Coelho Guedes', '2026-07-06 21:21:52', '2026-07-06 21:21:52'),
	(10, NULL, 'Eider Esdras Silva Oliveira', '2026-07-06 21:21:52', '2026-07-06 21:21:52'),
	(11, NULL, 'Nivaldo Correia da Silva', '2026-07-06 21:21:52', '2026-07-06 21:21:52'),
	(12, NULL, 'Michele Santos Barbosa', '2026-07-06 21:21:52', '2026-07-06 21:21:52'),
	(13, NULL, 'Keila Michelly Canhina Sachimbombo', '2026-07-09 19:34:13', '2026-07-09 19:34:13'),
	(14, NULL, 'Cleiton Gil Barbosa', '2026-07-09 19:45:49', '2026-07-09 19:45:49'),
	(15, NULL, 'Edinelson Pereira dos Santos', '2026-07-09 19:45:49', '2026-07-09 19:45:49'),
	(16, NULL, 'Jeovangela de Matos Rosa Ribeiro', '2026-07-09 19:45:49', '2026-07-09 19:45:49'),
	(17, NULL, 'Ana Paula Batista da Silva Cruz', '2026-07-09 19:45:49', '2026-07-09 19:45:49'),
	(18, NULL, 'Deisiane Alecrim de Mello Oliveira', '2026-07-09 19:45:49', '2026-07-09 19:45:49'),
	(19, NULL, 'Joao Marcos Ribeiro do Carmo', '2026-07-09 19:45:49', '2026-07-09 19:45:49'),
	(20, NULL, 'Leanderson Bispo Pires', '2026-07-09 19:45:49', '2026-07-09 19:45:49'),
	(21, NULL, 'Tayron Juliano Souza', '2026-07-09 19:45:49', '2026-07-09 19:45:49'),
	(22, NULL, 'Chayan Rios Soares Machado', '2026-07-09 19:45:49', '2026-07-09 19:45:49'),
	(23, NULL, 'Nayara de Oliveira Camargo Mascarenhas', '2026-07-09 19:45:49', '2026-07-09 19:45:49'),
	(24, NULL, 'Marcelo Batista Teixeira', '2026-07-09 19:45:49', '2026-07-09 19:45:49');
DROP TABLE IF EXISTS `representantes`;
CREATE TABLE IF NOT EXISTS `representantes` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint NOT NULL,
  `turma_id` bigint NOT NULL,
  `inicio_mandato` date DEFAULT NULL,
  `fim_mandato` date DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_representante_usuario` (`usuario_id`),
  KEY `fk_representante_turma` (`turma_id`),
  CONSTRAINT `fk_representante_turma` FOREIGN KEY (`turma_id`) REFERENCES `turmas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_representante_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
DROP TABLE IF EXISTS `turmas`;
CREATE TABLE IF NOT EXISTS `turmas` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `codigo_acesso` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo_acesso` (`codigo_acesso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
INSERT INTO `turmas` (`id`, `nome`, `codigo_acesso`, `created_at`, `updated_at`) VALUES
	(1, '1181I', '1181I1nf0rm4t1c4!', '2026-06-02 17:24:28', '2026-06-02 17:24:28'),
	(2, '2181I', '2181I1nf0rm4t1c4!', '2026-06-02 17:24:28', '2026-06-02 17:24:28'),
	(3, '3181I', '3181I1nf0rm4t1c4!', '2026-06-02 17:24:28', '2026-06-02 17:24:28'),
	(4, '4181I', '4181I1nf0rm4t1c4!', '2026-06-02 17:24:28', '2026-06-02 17:24:28'),
	(5, '1281I', '1281I4mb13nt3!', '2026-06-02 17:24:28', '2026-06-02 17:24:28'),
	(6, '2281I', '2281I4mb13nt3!', '2026-06-02 17:24:28', '2026-06-02 17:24:28'),
	(7, '3281I', '3281I4mb13nt3!', '2026-06-02 17:24:28', '2026-06-02 17:24:28'),
	(8, '4281I', '4281I4mb13nt3!', '2026-06-02 17:24:28', '2026-06-02 17:24:28'),
	(9, '1182I', '1182I4mb13nt3!', '2026-06-02 17:24:28', '2026-06-02 17:24:28'),
	(10, '2182I', '2182I4mb13nt3!', '2026-06-02 17:24:28', '2026-06-02 17:24:28'),
	(11, '3182I', '3182I4mb13nt3!', '2026-06-02 17:24:28', '2026-06-02 17:24:28'),
	(12, '4182I', '4182I4mb13nt3!', '2026-06-02 17:24:28', '2026-06-02 17:24:28'),
	(13, '1282I', '1282I4mb13nt3!', '2026-06-02 17:24:28', '2026-06-02 17:24:28'),
	(14, '2282I', '2282I4mb13nt3!', '2026-06-02 17:24:28', '2026-06-02 17:24:28'),
	(15, '3282I', '3282I4mb13nt3!', '2026-06-02 17:24:28', '2026-06-02 17:24:28'),
	(16, '4282I', '4282I4mb13nt3!', '2026-06-02 17:24:28', '2026-06-02 17:24:28');
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `matricula` varchar(30) DEFAULT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `senha_suap` longtext,
  `turma_codigo` varchar(50) DEFAULT NULL,
  `role` enum('admin','aluno','professor') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `matricula` (`matricula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
INSERT INTO `usuarios` (`id`, `matricula`, `nome`, `email`, `password`, `senha_suap`, `turma_codigo`, `role`, `created_at`, `updated_at`) VALUES
	(12, '20231180012', 'TAINAH OLIVEIRA', '20231180012@ifba.edu.br', '$2y$12$Hv0kVjzokmnuJXP2R2/ltuc6aOeCZkihis3kfHFzNvNPBTVGhHbYe', 'eyJpdiI6Ildid1VLRTQxdlFaS0kvUWNPb1h1c1E9PSIsInZhbHVlIjoidGk1ckZOdFpPbDExblRiNUNSVWxuZz09IiwibWFjIjoiMzEzNzY4ZGFlNGE3OWNjMGM1ZWUwMjA3NmZjZjkwMjU3YWQzNDg1N2E2YjcxYzhhMmVkZDhiNGJhNzg1YWY2NCIsInRhZyI6IiJ9', '20261.4.18.1I', 'aluno', '2026-07-06 20:19:04', '2026-07-13 17:27:10'),
	(13, '20241280005', 'HEVERTON OLIVEIRA', '20241280005@ifba.edu.br', '$2y$12$lS5r2zqcGPvQjj5CKF1Uq.jMiGpHbwfVgS6DXgsbNrUDspJxrFjce', 'eyJpdiI6IjQzV0xsZkdiOXIxOUt3WmpBS0NDMkE9PSIsInZhbHVlIjoiQ3FTYW83ck0vVXhnb3JTbnBOTXBjdz09IiwibWFjIjoiYzkyZjBlMzc3YTU2ODFkY2Y3NGQzYzA4OTAzY2I3OTA2YmY1YWU2YWExYTc3MWE1MDRlZDlkYTcyZmY4NDRiMyIsInRhZyI6IiJ9', NULL, 'aluno', '2026-07-09 19:33:05', '2026-07-09 22:50:57');
 ALTER TABLE eventos 
ADD COLUMN professor_id bigint unsigned;
 ALTER TABLE eventos 
ADD COLUMN turma_id bigint unsigned;
 ALTER TABLE eventos
ADD CONSTRAINT fk_professores 
FOREIGN KEY (professor_id) 
REFERENCES professores(id) 
ON DELETE CASCADE;
ALTER TABLE eventos
ADD CONSTRAINT fk_turmas 
FOREIGN KEY (turma_id) 
REFERENCES turmas(id) 
ON DELETE CASCADE;
SET FOREIGN_KEY_CHECKS = 1;
