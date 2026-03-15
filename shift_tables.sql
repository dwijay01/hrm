CREATE TABLE IF NOT EXISTS `shift_master` (
  `shift_id` int(11) NOT NULL AUTO_INCREMENT,
  `shift_name` varchar(100) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `tolerance_minutes` int(11) DEFAULT '0',
  `status` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`shift_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `employee_shift_roster` (
  `roster_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` varchar(50) NOT NULL,
  `shift_id` int(11) NOT NULL,
  `roster_date` date NOT NULL,
  `assigned_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`roster_id`),
  UNIQUE KEY `emp_date_unique` (`employee_id`, `roster_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
