DROP TABLE IF EXISTS `users`;

create table `users` (
  `id`       int not null auto_increment,
  `login`    varchar(255) not null unique,
  `password` char(60) not null,
  PRIMARY KEY (`id`)
);
