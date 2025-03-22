DROP DATABASE IF EXISTS chatsync;
CREATE DATABASE chatsync;

USE chatsync;

CREATE TABLE `users` (
  `id` BIGINT(20) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `username` VARCHAR(255) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `profile_image` VARCHAR(255)
);

INSERT INTO `users` VALUES (-1 , 'CHAT GPT-ai' , 'chat**GPT--Hass-increp' , 'gpt.png');
INSERT INTO `users` VALUES (-10 , 'Public Chat' , 'chat**PUBLIC-d--Hass-increp' , 'logo.png');


CREATE TABLE `requests` (
  `id` BIGINT(20) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `sent_from` BIGINT(20) NOT NULL,
  `sent_to` BIGINT(20) NOT NULL,
  `status` VARCHAR(255),
  FOREIGN KEY (`sent_from`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`sent_to`) REFERENCES `users`(`id`) ON DELETE CASCADE
);

CREATE TABLE `friends` (
  `id` BIGINT(20) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `friend_id` BIGINT(20) NOT NULL,
  `ur_id` BIGINT(20) NOT NULL,
  FOREIGN KEY (`friend_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`ur_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
);

CREATE TABLE `messages` (
  `id` BIGINT(20) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `sent_from` BIGINT(20) NOT NULL,
  `sent_to` BIGINT(20) NOT NULL,
  `content` VARCHAR(255),
  `type` ENUM('txt' , 'img' , 'audio' , 'video' , 'ai'),
  `state` VARCHAR(20),
  FOREIGN KEY (`sent_from`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`sent_to`) REFERENCES `users`(`id`) ON DELETE CASCADE
);

