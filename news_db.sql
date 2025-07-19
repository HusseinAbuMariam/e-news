
CREATE DATABASE IF NOT EXISTS `news_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `news_db`;

DROP TABLE IF EXISTS `comments`;
DROP TABLE IF EXISTS `articles`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `users`;

-- 3. Create users table
CREATE TABLE `users` (
  `user_id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) UNIQUE NOT NULL,
  `email` VARCHAR(100) UNIQUE NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Create categories table
CREATE TABLE `categories` (
  `category_id` INT AUTO_INCREMENT PRIMARY KEY,
  `category_name` VARCHAR(50) UNIQUE NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Create articles table
CREATE TABLE `articles` (
  `article_id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `content` TEXT NOT NULL,
  `image_url` VARCHAR(255) DEFAULT NULL,
  `category_id` INT NOT NULL,
  `author_id` INT NOT NULL,
  `published_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`category_id`) ON DELETE CASCADE,
  FOREIGN KEY (`author_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Create comments table
CREATE TABLE `comments` (
  `comment_id` INT AUTO_INCREMENT PRIMARY KEY,
  `article_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `comment_text` TEXT NOT NULL,
  `timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`article_id`) REFERENCES `articles`(`article_id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. Insert sample users
INSERT INTO `users` (`username`, `email`, `password`, `role`) VALUES
('admin', 'admin@example.com', '$2y$10$u1hX1yP/7y6UE9oYBuU1zuIj1R1P1cDYXfKWgiI8Zz1V1OlZ7r3mG', 'admin'),
('johndoe', 'john@example.com', '$2y$10$KbQi8QvF0BvTvv5W7jE0IuXWx3pOBz2BP4dkUtAQvFj3vKqR5kY8W', 'user');

-- 8. Insert sample categories
INSERT INTO `categories` (`category_name`) VALUES
('Politics'),
('Technology'),
('Sports'),
('Entertainment');

-- 9. Insert sample articles
INSERT INTO `articles` (`title`, `content`, `image_url`, `category_id`, `author_id`) VALUES
('Global Summit on Climate Change',
 'Delegates from 50 countries convened today in Geneva to address rising global temperatures and propose a binding agreement to reduce carbon emissions by 40% over the next decade. The summit featured keynote speeches from climate scientists, economists, and representatives of island nations at risk.',
 'images/climate.jpg', 1, 1),

('AI Ethics Framework Released',
 'A consortium of leading universities today released a comprehensive AI ethics framework recommending guidelines for transparent algorithm design, data privacy safeguards, and accountability measures for autonomous decision-making systems. Industry leaders praised the initiative as a step toward responsible AI deployment.',
 'images/ai_ethics.jpg', 2, 1),

('Marathon Winners Announced',
 'In yesterday’s city marathon, runners from 30 nations crossed the finish line amidst cheering crowds. The men’s title went to Eliyas Bekele of Ethiopia with a time of 2:08:15, while Kenya’s Florence Kiplagat claimed the women’s crown at 2:22:50.',
 'images/marathon.jpg', 3, 2),

('Indie Film Festival Kicks Off',
 'The annual Indie Film Festival opened its doors with an eclectic lineup of 45 independent films from around the globe. Festival director Lina Rossi emphasized the event’s role in giving a platform to emerging voices in cinema.',
 'images/film_fest.jpg', 4, 2);

-- 10. Insert sample comments
INSERT INTO `comments` (`article_id`, `user_id`, `comment_text`) VALUES
(1, 2, 'This summit is much needed, but binding agreements are hard to enforce.'),
(2, 2, 'Great to see ethics being taken seriously in AI research!'),
(3, 1, 'Congratulations to all participants and winners.'),
(4, 1, 'Looking forward to the documentary premieres at the festival.');
