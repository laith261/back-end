CREATE TABLE `referral` (
  `rid` int(100) NOT NULL,
  `referring` int(11) NOT NULL,
  `referraled` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `users` (
  `uid` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `password` varchar(1000) NOT NULL,
  `refeare_code` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `referral`
  ADD PRIMARY KEY (`rid`),
  ADD KEY `referring` (`referring`),
  ADD KEY `referraled` (`referraled`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `refeare_code` (`refeare_code`);


ALTER TABLE `referral`
  MODIFY `rid` int(100) NOT NULL AUTO_INCREMENT;

ALTER TABLE `users`
  MODIFY `uid` int(100) NOT NULL AUTO_INCREMENT;


ALTER TABLE `referral`
  ADD CONSTRAINT `referral_ibfk_1` FOREIGN KEY (`referring`) REFERENCES `users` (`uid`),
  ADD CONSTRAINT `referral_ibfk_2` FOREIGN KEY (`referraled`) REFERENCES `users` (`uid`);
COMMIT;