CREATE TABLE IF NOT EXISTS `users` (
    `id`       INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `email`    VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_user_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;