DROP TABLE IF EXISTS `users`;

create table `users` (
  `id`       int not null auto_increment,
  `login`    varchar(255) not null unique,
  `password` char(60) not null,
  PRIMARY KEY (`id`)
);

#test:test
INSERT INTO `users`(`login`, `password`) VALUES ('test', '$2y$10$yiP9y4y5AE7lqDZCJpyZ8eIEEw/s8G7pt74Xb6EuOLnI8.axOHFLO');
