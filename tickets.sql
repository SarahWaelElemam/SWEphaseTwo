-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 28, 2024 at 01:14 PM
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
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `Ticket_ID` int(11) NOT NULL,
  `Event_ID` int(11) NOT NULL,
  `Price` int(11) NOT NULL,
  `Status` enum('Available','Sold','Refunded') NOT NULL,
  `Created_At` datetime DEFAULT current_timestamp(),
  `type` enum('VIP','Gold','Silver','Platinum') DEFAULT 'Silver'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`Ticket_ID`, `Event_ID`, `Price`, `Status`, `Created_At`, `type`) VALUES
(2, 2, 1000, 'Sold', '2024-12-25 16:41:49', 'Platinum'),
(3, 2, 1000, 'Sold', '2024-12-25 16:41:49', 'Gold'),
(4, 4, 400, 'Available', '2024-12-25 16:41:49', 'Silver'),
(5, 2, 400, 'Available', '2024-12-25 16:41:45', 'VIP'),
(6, 2, 400, 'Available', '2024-12-25 16:41:45', 'Silver'),
(7, 2, 400, 'Available', '2024-12-25 16:41:45', 'Silver');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`Ticket_ID`),
  ADD KEY `Event_ID` (`Event_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `Ticket_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`Event_ID`) REFERENCES `events` (`Event_ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
