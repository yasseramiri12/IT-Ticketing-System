-- IT Ticketing System Database Schema
-- Last updated: 2026-01-21

-- Users table
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `role` enum('admin','client') NOT NULL DEFAULT 'client',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default admin user (username: admin, password: admin)
-- And default client user for testing (username: yasser, password: password123)
REPLACE INTO `users` (`id`, `user`, `password`, `role`, `email`) VALUES
(1, 'admin', 'admin', 'admin', 'admin@example.com'),
(2, 'yasser', 'password123', 'client', 'yasser@example.com');

-- IT Tickets table (used for submission)
CREATE TABLE IF NOT EXISTS `it_ticket` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id client` int(11) DEFAULT NULL,
  `created by` varchar(255) DEFAULT NULL,
  `created for` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `issue type` varchar(255) DEFAULT NULL,
  `issue description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Manage Tickets table (used for admin dashboard and tracking)
CREATE TABLE IF NOT EXISTS `manage tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id ticket` int(11) DEFAULT NULL,
  `created by` varchar(100) DEFAULT NULL,
  `created for` varchar(100) DEFAULT NULL,
  `issue type` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `progress` varchar(50) DEFAULT 'In Progress',
  `ticket urgency` varchar(50) DEFAULT 'medium',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
