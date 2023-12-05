CREATE TABLE `tr_log` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `menu` varchar(200) NOT NULL,
  `action` varchar(10) NOT NULL,
  `form_data` json NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
