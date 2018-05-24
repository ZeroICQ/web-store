DROP TABLE IF EXISTS `users`;

create table `users` (
  `id`       int not null auto_increment,
  `login`    varchar(255) not null unique,
  `password` char(60) not null,
  PRIMARY KEY (`id`)
);

CREATE TABLE user_info
(
  id int PRIMARY KEY AUTO_INCREMENT,
  user_id int NOT NULL,
  biography varchar(2048)  NOT NULL DEFAULT '',
  first_name varchar(254)  NOT NULL DEFAULT '',
  second_name varchar(254) NOT NULL DEFAULT '',
  work_place varchar(1024) NOT NULL DEFAULT '',
  CONSTRAINT user_info_users_id_fk FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE
);
CREATE UNIQUE INDEX user_info_id_uindex ON user_info (id);
CREATE UNIQUE INDEX user_info_user_id_uindex ON user_info (user_id);

#test:test
INSERT INTO `users`(`id`, `login`, `password`) VALUES (1, 'test', '$2y$10$yiP9y4y5AE7lqDZCJpyZ8eIEEw/s8G7pt74Xb6EuOLnI8.axOHFLO');
INSERT INTO `user_info`(`user_id`) VALUES (1);

