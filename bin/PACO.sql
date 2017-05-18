-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 13, 2017 at 02:54 AM
-- Server version: 5.5.50-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `PACO`
--

-- --------------------------------------------------------

--
-- Table structure for table `labref`
--

CREATE TABLE IF NOT EXISTS `labref` (
  `id` int(11) NOT NULL,
  `patientID` int(11) NOT NULL,
  `Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `HCT` double DEFAULT NULL,
  `Hemacias` double DEFAULT NULL,
  `HGB` double DEFAULT NULL,
  `Ureia` double DEFAULT NULL,
  `Cr` double DEFAULT NULL,
  `K` double DEFAULT NULL,
  `Na` double DEFAULT NULL,
  `Leuco` double DEFAULT NULL,
  `INR` double DEFAULT NULL,
  `PCR` double DEFAULT NULL,
  `TGO/TGP` varchar(64) DEFAULT NULL,
  `Outros` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `labref`
--

INSERT INTO "labref" ("id", "patientID", "Date", "HCT", "Hemacias", "HGB", "Ureia", "Cr", "K", "Na", "Leuco", "INR", "PCR", "TGO/TGP", "Outros") VALUES
(0, 171, '2016-12-30 14:00:00', 25, 9.2, 10.4, 46, 1.3, 4.2, 141, 13000, 1, 0.3, NULL, 'VDRL: Negativo');

-- --------------------------------------------------------

--
-- Table structure for table "PACO_users"
--

CREATE TABLE IF NOT EXISTS "PACO_users" (
  "id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `userhash` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `PACO_users`
--

INSERT INTO `PACO_users` (`id`, `username`, `email`, `userhash`) VALUES
(1, 'Leon', 'l.nasc@live.com', '$2y$10$9BjGn/hLUOjnl0eKPgZGZuyJYRHDiegqVnFl/TuTsSm1Lbn0X9U3q'),
(3, 'Habib', 'air_booth@hotmail.com', '$2y$10$Aun76RvpOr9PxPNT4bFY4eIwISsYbGtLGdgljD9FdKB0JvoBlBdwy');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE IF NOT EXISTS `patients` (
  `LastActive` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patientID` varchar(11) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
  `userID` int(11) NOT NULL,
  `patientname` varchar(255) NOT NULL,
  `patientage` int(11) NOT NULL,
  `p_status` enum('active','inactive') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `patientID` (`patientID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`LastActive`, `id`, `patientID`, `userID`, `patientname`, `patientage`, `p_status`) VALUES
('2017-05-12 02:46:11', 2, '171222', 1, 'Mustafa Habib', 73, 'active'),
('2017-04-30 04:02:23', 3, '171000', 1, 'Antonia Nuna', 32, 'inactive'),
('2017-04-30 03:59:05', 4, '171005', 1, 'Jose Arantes', 88, 'inactive'),
('2017-04-30 04:02:16', 8, '171', 1, 'Guezia Quedes', 21, 'inactive'),
('2017-04-30 03:40:19', 13, '1', 2, 'Aerovaldo Orelino', 78, 'active'),
('2017-05-12 02:42:39', 14, '31', 1, 'Fausto Silva', 58, 'active'),
('2017-05-09 00:32:15', 16, '0001', 3, 'Emílio Santiago', 78, 'active'),
('2017-05-09 00:34:56', 17, '001', 3, 'PÃ©ricles da Silva', 37, 'active'),
('2017-05-12 02:38:38', 18, 'abba', 1, 'Pepino', 22, 'inactive');

-- --------------------------------------------------------

--
-- Table structure for table `prescriptions`
--

CREATE TABLE IF NOT EXISTS `prescriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `patientID` int(11) NOT NULL,
  `Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `med1` varchar(255) NOT NULL,
  `pos1` enum('1x/d','2x/d','3x/d','4x/d','6x/d','SOS','OUTRO') DEFAULT NULL,
  `med2` varchar(60) NOT NULL,
  `pos2` enum('1x/d','2x/d','3x/d','4x/d','6x/d','SOS','OUTRO') DEFAULT NULL,
  `med3` varchar(60) NOT NULL,
  `pos3` enum('1x/d','2x/d','3x/d','4x/d','6x/d','SOS','OUTRO') DEFAULT NULL,
  `med4` varchar(60) NOT NULL,
  `pos4` enum('1x/d','2x/d','3x/d','4x/d','6x/d','SOS','OUTRO') DEFAULT NULL,
  `med5` varchar(60) NOT NULL,
  `pos5` enum('1x/d','2x/d','3x/d','4x/d','6x/d','SOS','OUTRO') DEFAULT NULL,
  `med6` varchar(60) NOT NULL,
  `pos6` enum('1x/d','2x/d','3x/d','4x/d','6x/d','SOS','OUTRO') DEFAULT NULL,
  `med7` varchar(60) NOT NULL,
  `pos7` enum('1x/d','2x/d','3x/d','4x/d','6x/d','SOS','OUTRO') DEFAULT NULL,
  `med8` varchar(60) NOT NULL,
  `pos8` enum('1x/d','2x/d','3x/d','4x/d','6x/d','SOS','OUTRO') DEFAULT NULL,
  `med9` varchar(60) NOT NULL,
  `pos9` enum('1x/d','2x/d','3x/d','4x/d','6x/d','SOS','OUTRO') DEFAULT NULL,
  `med10` varchar(60) NOT NULL,
  `pos10` enum('1x/d','2x/d','3x/d','4x/d','6x/d','SOS','OUTRO') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=37 ;

--
-- Dumping data for table `prescriptions`
--

INSERT INTO `prescriptions` (`id`, `userID`, `patientID`, `Date`, `med1`, `pos1`, `med2`, `pos2`, `med3`, `pos3`, `med4`, `pos4`, `med5`, `pos5`, `med6`, `pos6`, `med7`, `pos7`, `med8`, `pos8`, `med9`, `pos9`, `med10`, `pos10`) VALUES
(14, 1, 171222, '2017-02-19 17:28:21', '  Lisinopril 5mg VO    ', '', '  ', '', 'Dipirona 500mg VO  ', '3x/d', '         ', '', '         ', '', '         ', '', '         ', '', '         ', '', '         ', '', '         ', ''),
(16, 1, 171222, '2017-02-16 17:21:12', '   Captopril 25mg VO    ', '', ' Carbonato de CÃ¡lcio 500mg VO  ', '', ' Dipirona 1g  IV', 'SOS', '           ', '', '           ', '', '           ', '', '           ', '', '           ', '', '           ', '', '           ', ''),
(17, 1, 171005, '2017-02-17 22:27:12', ' Dipirona 1 G IV  ', '', '     ', '', '     ', '', '     ', '', '     ', '', '     ', '', '     ', '', '     ', '', '     ', '', '     ', ''),
(18, 1, 171005, '2017-02-19 17:31:32', '  ', '', 'Morfina  10mg IV', '3x/d', '        ', '', '        ', '', '        ', '', '        ', '', '        ', '', '        ', '', '        ', '', '        ', ''),
(19, 1, 171032, '2017-02-26 23:47:36', 'Pantoprazol 40mg VO', '1x/d', '  ', '', '  ', '', '  ', '', '  ', '', '  ', '', '  ', '', '  ', '', '  ', '', '  ', ''),
(20, 1, 171032, '2017-02-26 23:48:25', ' Pantoprazol 40mg VO  ', '', ' Dipirona 500mg IV', 'SOS', 'Testosterona 20mcg IV', '', '     ', '', '     ', '', '     ', '', '     ', '', '     ', '', '     ', '', '     ', ''),
(21, 1, 171022, '2017-02-26 23:50:21', 'Ciprofloxacino 500mg IV', '2x/d', 'CodeÃ­na 30mg VO', '2x/d', '  ', '', '  ', '', '  ', '', '  ', '', '  ', '', '  ', '', '  ', '', '  ', ''),
(22, 1, 171022, '2017-02-26 23:51:07', '   ', '', '  ', '', ' Metronidazol   400mg IV', '3x/d', '     ', '', '     ', '', '     ', '', '     ', '', '     ', '', '     ', '', '     ', ''),
(23, 1, 171222, '2017-04-18 14:35:36', '   Lisinopril 5mg VO      ', '', 'Losartana 50mg VO', '2x/d', ' Dipirona 500mg VO    ', '', '            ', '', '            ', '', '            ', '', '            ', '', '            ', '', '            ', '', '            ', ''),
(24, 1, 171222, '2017-04-18 14:35:49', '    Lisinopril 5mg VO        ', '', ' Losartana 50mg VO  ', '', '      ', '', '               ', '', '               ', '', '               ', '', '               ', '', '               ', '', '               ', '', '               ', ''),
(26, 5, 1, '2017-04-21 07:15:12', 'Dipirona 500mg ', '', '  ', '', '  ', '', '  ', '', '  ', '', '  ', '', '  ', '', '  ', '', '  ', '', '  ', ''),
(27, 5, 1, '2017-04-21 07:16:03', 'Dipirona 500mg ', '', '  ', '', '  ', '', '  ', '', '  ', '', '  ', '', '  ', '', '  ', '', '  ', '', '  ', ''),
(31, 1, 171005, '2017-04-30 03:53:04', '    Teste 10mg VO      ', '', '    Teste 10mg VO      ', '', '    Teste 10mg VO      ', '', '    Teste 10mg VO      ', '', '    Teste 10mg VO      ', '', '    Teste 10mg VO      ', '', '    Teste 10mg VO      ', '', '    Teste 10mg VO      ', '', '    Teste 10mg VO      ', '', '    Teste 10mg VO      ', ''),
(32, 1, 171222, '2017-04-30 04:10:33', 'Lisinopril 5mg VO        ', '', 'Losartana 50mg VO  ', '', '           ', '', '                    ', '', '                    ', '', '                    ', '', '                    ', '', '                    ', '', '                    ', '', '                    ', ''),
(33, 1, 31, '2017-05-08 03:16:37', '  Captopril 25mg VO', '', '   ', '', '   ', '', '   ', '', '   ', '', '   ', '', '   ', '', '   ', '', '   ', '', '   ', ''),
(34, 1, 31, '2017-05-08 23:00:18', 'Captopril 25mg VO  ', '', 'Dorzolamida 5% OC', '3x/d', '      ', '', '      ', '', '      ', '', '      ', '', '      ', '', '      ', '', '      ', '', '      ', ''),
(35, 1, 171005, '2017-05-12 00:50:47', '     Teste 10mg VO        ', '', '     Teste 10mg VO        ', '', '     Teste 10mg VO        ', '', '     Teste 10mg VO        ', '', '     Teste 10mg VO        ', '', '     Teste 10mg VO        ', '', '     Teste 10mg VO        ', '', '     Teste 10mg VO        ', '', '     Teste 10mg VO        ', '', '     Teste 10mg VO        ', ''),
(36, 1, 171005, '2017-05-12 00:52:26', '     Teste 10mg VO        ', '', '     Teste 10mg VO        ', '', '     Teste 10mg VO        ', '', '     Teste 10mg VO        ', '', '     Teste 10mg VO        ', '', '     Teste 10mg VO        ', '', '     Teste 10mg VO        ', '', '     Teste 10mg VO        ', '', '     Teste 10mg VO        ', '', '     Teste 10mg VO        ', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
