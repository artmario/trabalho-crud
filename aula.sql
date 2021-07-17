DROP TABLE IF EXISTS `produtos`;
CREATE TABLE `produtos` (
  `id` int NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

