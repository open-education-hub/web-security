CREATE TABLE `users` (
  `id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `username` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `university` varchar(128),
  `faculty` varchar(128),
  `email` varchar(128),
  `score` int(11) UNSIGNED DEFAULT 0
);
