-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 22, 2023 at 12:23 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `imgprocess`
--

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `username` varchar(15) NOT NULL,
  `date_of_birth` date NOT NULL,
  `original_url` varchar(40) NOT NULL,
  `main_url` varchar(40) NOT NULL,
  `thumbnail_url` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`username`, `date_of_birth`, `original_url`, `main_url`, `thumbnail_url`) VALUES
('Bhavesh', '2002-05-18', 'bhav0518/Original/2023-12-22-12-18-42-or', 'bhav0518/Main/2023-12-22-12-18-42-main.j', 'bhav0518/Thumbnail/2023-12-22-12-18-42-thum.jpg'),
('Bhavesh', '2002-05-18', 'bhav0518/Original/2023-12-22-12-20-05-or', 'bhav0518/Main/2023-12-22-12-20-05-main.j', 'bhav0518/Thumbnail/2023-12-22-12-20-05-thum.jpg');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
