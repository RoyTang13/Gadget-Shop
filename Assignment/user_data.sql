INSERT INTO `user` (`userID`, `fname`, `lname`, `email`, `phoneNo`, `userPhoto`, `password`, `reset_code`, `reset_expiry`) VALUES
(1, 'JOHN', 'TANG', 'john124@gmail.com', '012-3332223', '', '$2y$10$3cdDtxVe4SF14KkCDRXqz.PbxaA.fb.PpXkGkKdZ4.uRu.Gvh6sAy', NULL, NULL),
(2, 'JOHN', 'TANG', 'john123@gmail.com', '012-3332222', '', '$2y$10$3cdDtxVe4SF14KkCDRXqz.PbxaA.fb.PpXkGkKdZ4.uRu.Gvh6sAy', NULL, NULL),
(3, 'ROY', 'TANG', 'roy1@gmail.com', '011-1222222', '', '$2y$10$X02OthXz4JWMailohkZ9YOGoLYow3IM89OTAX3nWGy5awOh7v7QL.', NULL, NULL),
(4, 'ROY', 'TANG', 'roy2@gmail.com', '012-3456789', '', '$2y$10$DrSr9rhvOuI43.9DFhifwOCIVH7tWlYAW2iLA2q0PZXzTAV/pYu8u', NULL, NULL),
(5, 'ROY', 'TANG', 'roy3@gmail.com', '012-3456787', '', '$2y$10$J2Vjy9W/Bw.yB7e57MkojeuSYeCAnetBdeTp6Gc9FDIjPx7pQCP9a', NULL, NULL),
(6, 'ROY', 'TANG3', 'roy4@gmail.com', '012-3456786', '', '$2y$10$23VXhUOPMel8Npnqel/7CuvjQqpQNMbmNaPvViXvGcXGK7jDVfeZe', NULL, NULL),
(7, 'ROY5', 'TANG', 'roy5@gmail.com', '012-7772566', '', '$2y$10$KY7OdU8wlLSkNuL/KyJUBOGcH8O44Ugk19ir41/aLi/q4BVBlo76G', NULL, NULL),
(8, 'TANG', 'LE YI', 'leyitang031013@gmail.com', '017-8778729', '', '$2y$10$tubLgmyiCMYxLFdR9l.T5uVdUJkWM/v4xl.ac38jdj6zF1PuKsMcC', '696467', '2025-12-02 00:57:03'),
(9, 'ROY', 'TANG STUDENT', 'tangly-wm24@student.tarc.edu.my', '016-7779988', '', '$2y$10$h8uhhJERMBStUyJSWIqMhOr0SwKRhKGY5LFGzqAxX9sRN2mguRUiu', '924606', '2025-11-24 01:37:26'),
(10, 'TANG', 'LE YI', 'roy6@gmail.com', '012-9999898', '692e97b55ead6.jpg', '$2y$10$m9ZeGmqomlPDmqCvfymvTeqIfWj5cKMh.a0hfarhaPmgq2CBTK/Gm', NULL, NULL),
(11, 'ROY', 'TANG', 'roy7@gmail.com', '017-2229999', '', '$2y$10$1LSrHJoXD9J1l2d2FmtYQO5fCx0ENwuDHQeeVwSwJgcVMw5eRlG0m', NULL, NULL),
(12, 'ROY', 'TANG', 'roy8@gmail.com', '017-8882222', '', '$2y$10$/X4uZWsNNzf.gnHTa.WMk.BK/b.VXEiTaR5yZs1pDIe9NBlM6Dn9q', NULL, NULL),
(13, 'ROY9', '91', 'roy91@gmail.com', '012-7894888', '', '$2y$10$PAAn2cSUu5.9/dJ7GiqO2utwflwTTIi/1sxL44y6dtbXmbqOMgCBa', NULL, NULL),
(14, 'ROY', '10', 'roy10@gmail.com', '017-8889988', '692d8c3176af8.jpg', '$2y$10$wHcw6UjhdBNk6H406/Jut.PQC21jhVjjg3fMQriNu./9eCP13tYqu', NULL, NULL),
(15, 'ROY', '11', 'roy11@gmail.com', '017-7778888', '692ea9b294d57.jpg', '$2y$10$wkX5zSIiEjqAh00JTna/au66VfpaUq6.aohacmuTmIRkzf2/WPmFK', NULL, NULL),
(16, 'ROY', '12', 'roy12@gmail.com', '017-8889999', '', '$2y$10$yO9ZT1bm/kf5mA6quwk9d.FGnllAXEg4/TJv3MIy7nzLtOcrS595.', NULL, NULL),
(17, 'USER', '1', 'user1@gmail.com', '017-8889992', '692dbe3707264.jpg', '$2y$10$TQmnN4uQUUjvCzRBb0Wwx.Ta2r/P4jZSu5.WEwicyRU0BDNWASdMi', NULL, NULL);

--
-- 转储表的索引
--

--
-- 表的索引 `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `user`
--
ALTER TABLE `user`
  MODIFY `userID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
