SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `atendimentos_agendados`;
DROP TABLE IF EXISTS `professor_turma`;
DROP TABLE IF EXISTS `representantes`;
DROP TABLE IF EXISTS `administradores`;
DROP TABLE IF EXISTS `professores`;
DROP TABLE IF EXISTS `alunos`;
DROP TABLE IF EXISTS `eventos`;
DROP TABLE IF EXISTS `turmas`;
DROP TABLE IF EXISTS `usuarios`;

SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE `usuarios` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `matricula` varchar(30) DEFAULT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','aluno','professor') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `matricula` (`matricula`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE `turmas` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `codigo_acesso` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo_acesso` (`codigo_acesso`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE `eventos` (
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

ALTER TABLE eventos

ADD CONSTRAINT fk_eventos_professor
FOREIGN KEY (id_prof)
REFERENCES professores(id)
ON DELETE CASCADE,

ADD CONSTRAINT fk_eventos_turma
FOREIGN KEY (id_turma)
REFERENCES turmas(id)
ON DELETE CASCADE,

ADD CONSTRAINT fk_eventos_disciplina
FOREIGN KEY (id_disciplina)
REFERENCES disciplinas(id)
ON DELETE SET NULL;



CREATE TABLE `alunos` (
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE `professores` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_professor_usuario` (`usuario_id`),
  CONSTRAINT `fk_professor_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE `administradores` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_admin_usuario` (`usuario_id`),
  CONSTRAINT `fk_admin_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE `representantes` (
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


CREATE TABLE `professor_turma` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `professor_id` bigint NOT NULL,
  `turma_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_professor_turma_professor` (`professor_id`),
  KEY `fk_professor_turma_turma` (`turma_id`),
  CONSTRAINT `fk_professor_turma_professor` FOREIGN KEY (`professor_id`) REFERENCES `professores` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_professor_turma_turma` FOREIGN KEY (`turma_id`) REFERENCES `turmas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE `atendimentos_agendados` (
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



SET FOREIGN_KEY_CHECKS=0;

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'20231180012','TAINAH OLIVEIRA','20231180012@ifba.edu.br','$2y$12$LzK7uw9NOTmThNaU3o2JQuKZaBDlH8OKUu0phiW.PLdjdQ9qq1mFe','admin','2026-06-15 20:23:06','2026-06-18 17:16:19'),(4,'20231180002','MURILO SIRINEU','20231180002@ifba.edu.br','$2y$12$TJcWxYGxOL5JYZHLOUJzQefLAA8pb.dBy06zYVsskJlETIgWf/8z6','admin','2026-06-15 20:36:50','2026-06-18 17:20:46'),(5,'3074421','Monck Albuquerque','monckcharles@ifba.edu.br','$2y$12$ABgWJeQMcdkrqPzbAn7RlORg8hMdj/XxLPnGUOATkdhcCg8Eu2bF2','admin','2026-06-18 20:05:02','2026-06-18 17:18:37');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;


LOCK TABLES `turmas` WRITE;
/*!40000 ALTER TABLE `turmas` DISABLE KEYS */;
INSERT INTO `turmas` VALUES (1,'1181I','1181I1nf0rm4t1c4!','2026-06-02 17:24:28','2026-06-02 17:24:28'),(2,'2181I','2181I1nf0rm4t1c4!','2026-06-02 17:24:28','2026-06-02 17:24:28'),(3,'3181I','3181I1nf0rm4t1c4!','2026-06-02 17:24:28','2026-06-02 17:24:28'),(4,'4181I','4181I1nf0rm4t1c4!','2026-06-02 17:24:28','2026-06-02 17:24:28'),(5,'1281I','1281I4mb13nt3!','2026-06-02 17:24:28','2026-06-02 17:24:28'),(6,'2281I','2281I4mb13nt3!','2026-06-02 17:24:28','2026-06-02 17:24:28'),(7,'3281I','3281I4mb13nt3!','2026-06-02 17:24:28','2026-06-02 17:24:28'),(8,'4281I','4281I4mb13nt3!','2026-06-02 17:24:28','2026-06-02 17:24:28'),(9,'1182I','1182I4mb13nt3!','2026-06-02 17:24:28','2026-06-02 17:24:28'),(10,'2182I','2182I4mb13nt3!','2026-06-02 17:24:28','2026-06-02 17:24:28'),(11,'3182I','3182I4mb13nt3!','2026-06-02 17:24:28','2026-06-02 17:24:28'),(12,'4182I','4182I4mb13nt3!','2026-06-02 17:24:28','2026-06-02 17:24:28'),(13,'1282I','1282I4mb13nt3!','2026-06-02 17:24:28','2026-06-02 17:24:28'),(14,'2282I','2282I4mb13nt3!','2026-06-02 17:24:28','2026-06-02 17:24:28'),(15,'3282I','3282I4mb13nt3!','2026-06-02 17:24:28','2026-06-02 17:24:28'),(16,'4282I','4282I4mb13nt3!','2026-06-02 17:24:28','2026-06-02 17:24:28');
/*!40000 ALTER TABLE `turmas` ENABLE KEYS */;
UNLOCK TABLES;


LOCK TABLES `eventos` WRITE;
/*!40000 ALTER TABLE `eventos` DISABLE KEYS */;
INSERT INTO `eventos` VALUES (3,'aaaaaaaaaaa','seminario','2026-06-20','12:03:00','12:03:00','aaaaaaaaaaaaa','2026-06-11 21:36:30','2026-06-11 22:37:26'),(4,'asd','prova','2026-06-19','11:01:00','11:01:00',NULL,'2026-06-11 22:37:19','2026-06-11 22:58:27'),(5,'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa','prova','2026-06-13',NULL,NULL,NULL,'2026-06-11 22:37:34','2026-06-11 22:37:44');
/*!40000 ALTER TABLE `eventos` ENABLE KEYS */;
UNLOCK TABLES;


LOCK TABLES `alunos` WRITE;
/*!40000 ALTER TABLE `alunos` DISABLE KEYS */;
INSERT INTO `alunos` VALUES (1,1,'20231180012',4,'2026-06-08 14:59:53','2026-06-08 14:59:53');
/*!40000 ALTER TABLE `alunos` ENABLE KEYS */;
UNLOCK TABLES;


LOCK TABLES `administradores` WRITE;
/*!40000 ALTER TABLE `administradores` DISABLE KEYS */;
INSERT INTO `administradores` VALUES (1,4,'2026-06-08 14:09:54','2026-06-15 18:42:49');
/*!40000 ALTER TABLE `administradores` ENABLE KEYS */;
UNLOCK TABLES;


SET FOREIGN_KEY_CHECKS=1;
