-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 28, 2024 at 01:11 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `User_ID` int(11) NOT NULL,
  `FName` varchar(255) NOT NULL,
  `LName` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Role` enum('Admin','Customer','Organizer') NOT NULL,
  `BirthDate` date NOT NULL,
  `Phone` varchar(15) NOT NULL,
  `Government` varchar(255) NOT NULL,
  `Gender` enum('Male','Female') NOT NULL,
  `image` varchar(255) DEFAULT 'iconuser.png'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`User_ID`, `FName`, `LName`, `Email`, `Password`, `Role`, `BirthDate`, `Phone`, `Government`, `Gender`, `image`) VALUES
(1, 'hana', 'mohamed', 'hana@gmail.com', '123h', 'Admin', '2003-05-10', '01234567890', 'Cairo', 'Female', 'iconuser.png'),
(2, 'sarah', 'wael', 'sarah@gmail.com', '123s', 'Organizer', '2003-08-12', '01234567790', 'Cairo', 'Female', 'organizerslogo.png'),
(3, 'karen', 'george', 'karen@gmail.com', '123k', 'Customer', '2004-02-16', '01244567790', 'Cairo', 'Female', 'iconuser.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`User_ID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `User_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
