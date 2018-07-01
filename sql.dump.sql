-- Adminer 4.6.2 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `employee`;
CREATE TABLE `employee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `employee` (`id`, `name`) VALUES
  (101,	'Kis Béla'),
  (102,	'Nagy sándor'),
  (103,	'Farkas Béla'),
  (104,	'Virág Hajnalka'),
  (105,	'Piros Ilona');

DROP TABLE IF EXISTS `worktime`;
CREATE TABLE `worktime` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `employee_id` int(11) NOT NULL,
  `start_time` time NOT NULL DEFAULT '00:00:00',
  `end_time` time NOT NULL DEFAULT '00:00:00',
  `total_work_time` time NOT NULL DEFAULT '00:00:00',
  `sunday_bonus` time NOT NULL DEFAULT '00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `date` (`date`,`employee_id`),
  KEY `employee_id` (`employee_id`),
  CONSTRAINT `worktime_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `worktime` (`id`, `date`, `employee_id`, `start_time`, `end_time`, `total_work_time`, `sunday_bonus`) VALUES
  (3,	'2018-06-27',	102,	'12:00:00',	'21:36:00',	'09:36:00',	'00:00:00'),
  (5,	'2018-06-29',	102,	'08:30:00',	'16:00:00',	'07:30:00',	'00:00:00'),
  (6,	'2017-01-05',	101,	'09:00:00',	'13:33:00',	'04:33:00',	'00:00:00'),
  (7,	'2018-06-25',	103,	'15:19:00',	'23:25:00',	'08:06:00',	'00:00:00'),
  (8,	'2018-06-17',	104,	'12:00:00',	'18:00:00',	'06:00:00',	'06:00:00'),
  (9,	'2018-06-17',	105,	'13:10:00',	'21:45:00',	'08:35:00',	'08:35:00'),
  (10,	'2018-06-21',	101,	'07:00:00',	'18:49:00',	'11:49:00',	'00:00:00'),
  (11,	'2018-06-30',	105,	'10:35:00',	'22:55:00',	'12:20:00',	'00:00:00'),
  (12,	'2018-06-12',	101,	'06:57:00',	'15:00:00',	'08:03:00',	'00:00:00'),
  (13,	'2018-06-01',	104,	'09:00:00',	'17:30:00',	'08:30:00',	'00:00:00'),
  (14,	'2018-07-22',	101,	'00:00:00',	'18:04:00',	'18:04:00',	'18:04:00'),
  (15,	'2018-06-27',	101,	'10:45:00',	'23:08:00',	'12:23:00',	'00:00:00'),
  (16,	'2018-06-04',	102,	'00:00:00',	'11:23:00',	'11:23:00',	'00:00:00'),
  (17,	'2018-07-18',	101,	'18:24:00',	'18:50:00',	'00:26:00',	'00:00:00');

-- 2018-07-01 21:01:00