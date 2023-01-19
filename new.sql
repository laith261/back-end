CREATE TABLE IF NOT EXISTS `refeares` (
  `rid` int(100) NOT NULL AUTO_INCREMENT,
  `r_uid` int(100) NOT NULL,
  `re_uid` int(100) NOT NULL,
  PRIMARY KEY (`rid`),
  KEY `r_uid` (`r_uid`),
  KEY `re_uid` (`re_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `users` (
  `uid` int(100) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `password` varchar(1000) NOT NULL,
  `refeare_code` varchar(100) NOT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `refeare_code` (`refeare_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
ALTER TABLE `refeares`
  ADD CONSTRAINT `refeares_ibfk_1` FOREIGN KEY (`r_uid`) REFERENCES `users` (`uid`),
  ADD CONSTRAINT `refeares_ibfk_2` FOREIGN KEY (`re_uid`) REFERENCES `users` (`uid`);
COMMIT;
