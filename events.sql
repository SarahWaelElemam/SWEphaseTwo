-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 28, 2024 at 01:10 PM
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
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `Event_ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Location` varchar(255) NOT NULL,
  `Category` enum('Concert','Theater','Sport','Show') DEFAULT 'Concert',
  `Status` enum('Accepted','Pending','Rejected') NOT NULL,
  `Date` date NOT NULL,
  `Time` time NOT NULL,
  `Created_By` int(11) NOT NULL,
  `detailed_loc` varchar(255) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `price_range1` int(11) DEFAULT NULL,
  `price_range2` int(11) DEFAULT NULL,
  `availabe_tick` int(11) DEFAULT NULL,
  `sold_tick` int(11) DEFAULT NULL,
  `total_tick` int(11) DEFAULT NULL,
  `about` varchar(255) DEFAULT NULL,
  `venue_loc` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`Event_ID`, `Name`, `Location`, `Category`, `Status`, `Date`, `Time`, `Created_By`, `detailed_loc`, `image`, `price_range1`, `price_range2`, `availabe_tick`, `sold_tick`, `total_tick`, `about`, `venue_loc`) VALUES
(2, 'Cairokee', 'Alexandria', 'Concert', 'Accepted', '2024-12-01', '20:00:00', 2, 'Bibliotheca Alexandria', 'event3.png', 600, 1000, NULL, NULL, NULL, 'ana mo4 anany sebtk t3y4 b3dy el 7ayah', 'https://www.google.com/travel/hotels/s/mWyMWGKYDHqTDKsJ8'),
(3, 'Amr Diab', 'ElManara', 'Concert', 'Rejected', '2024-12-10', '18:30:00', 3, 'ElManara Conference Center', 'event3.png', 1000, 12000, NULL, NULL, NULL, NULL, NULL),
(4, 'elAhly VS elZamalek', 'Cairo', 'Sport', 'Accepted', '2024-12-31', '21:00:00', 1, 'Cairo International Stadium', 'event1.png', 100, 500, NULL, NULL, NULL, 'fmf', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`Event_ID`),
  ADD KEY `Created_By` (`Created_By`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `Event_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`Created_By`) REFERENCES `user` (`User_ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
