SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- Table 1: User
CREATE TABLE `User` (
  `User_ID` INT(11) NOT NULL AUTO_INCREMENT,
  `FName` VARCHAR(255) NOT NULL,
  `LName` VARCHAR(255) NOT NULL,
  `Email` VARCHAR(255) NOT NULL UNIQUE,
  `Password` VARCHAR(255) NOT NULL,
  `Role` ENUM('Admin', 'Customer', 'Organizer') NOT NULL,
  `BirthDate` DATE NOT NULL,
  `Phone` VARCHAR(15) NOT NULL,
  `Government` VARCHAR(255) NOT NULL,
  `Gender` ENUM('Male', 'Female') NOT NULL,
  PRIMARY KEY (`User_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Insert data into User table
INSERT INTO `User` (`FName`, `LName`, `Email`, `Password`, `Role`, `BirthDate`, `Phone`, `Government`, `Gender`) VALUES
('hana', 'mohamed', 'hana@gmail.com', '123h', 'Admin', '2003-05-10', '01234567890', 'Cairo', 'Female'),
('sarah', 'wael', 'sarah@gmail.com', '123s', 'Organizer', '2003-08-12', '01234567790', 'Cairo', 'Female'),
('karen', 'george', 'karen@gmail.com', '123k', 'Customer', '2004-02-16', '01244567790', 'Cairo', 'Female');

-- Table 2: Events
CREATE TABLE `Events` (
  `Event_ID` INT(11) NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(255) NOT NULL,
  `Location` VARCHAR(255) NOT NULL,
  `Category` VARCHAR(255) NOT NULL,
  `Status` ENUM('Accepted', 'Pending', 'Rejected') NOT NULL,
  `Date` DATE NOT NULL,
  `Time` TIME NOT NULL,
  `Created_By` INT(11) NOT NULL,
  `detailed_loc` VARCHAR(255),
  `image` VarChar(255) NOT NULL,
  PRIMARY KEY (`Event_ID`),
  FOREIGN KEY (`Created_By`) REFERENCES `User`(`User_ID`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Insert data into Events table
INSERT INTO `Events` (`Name`, `Location`, `Category`, `Status`, `Date`, `Time`, `Created_By`, `detailed_loc`, `image`) VALUES
('MEMO', 'Cairo', 'Theatre', 'Accepted', '2024-11-15', '12:00:00', 1, 'Grand Nile Tower', 'event3.png'),
('Cairokee', 'Alexandria', 'Concert', 'Pending', '2024-12-01', '20:00:00', 2, 'Bibliotheca Alexandria', 'event3.png'),
('Amr Diab', 'ElManara', 'Concert', 'Rejected', '2024-12-10', '18:30:00', 3, 'ElManara Conference Center', 'event3.png'),
('elAhly VS elZamalek', 'Cairo', 'Sports', 'Accepted', '2024-12-15', '21:00:00', 1, 'Cairo International Stadium', 'event1.png');


-- Table 3: Tickets
CREATE TABLE `Tickets` (
  `Ticket_ID` INT(11) NOT NULL AUTO_INCREMENT,
  `Event_ID` INT(11) NOT NULL,
  `Price` INT(11) NOT NULL,
  `Status` ENUM('Available', 'Sold', 'Refunded') NOT NULL,
  `Created_At` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Ticket_ID`),
  FOREIGN KEY (`Event_ID`) REFERENCES `Events`(`Event_ID`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Insert data into Tickets table
INSERT INTO `Tickets` (`Event_ID`, `Price`, `Status`) VALUES
(1, 500, 'Available'),
(2, 300, 'Sold'),
(3, 400, 'Refunded'),
(4, 700, 'Available');

-- Table 4: HelpRequest
CREATE TABLE `HelpRequest` (
  `Help_ID` INT(11) NOT NULL AUTO_INCREMENT,
  `User_ID` INT(11) NOT NULL,
  `Status` ENUM('Open', 'Pending', 'Resolved') NOT NULL,
  `Message` VARCHAR(255) NOT NULL,
  `Response` VARCHAR(255) NOT NULL,
  `Created_At` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `Updated_At` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`Help_ID`),
  FOREIGN KEY (`User_ID`) REFERENCES `User`(`User_ID`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Insert data into HelpRequest table
INSERT INTO `HelpRequest` (`User_ID`, `Status`, `Message`, `Response`) VALUES
(3, 'Open', 'nn', 'hh');

-- Table 5: Purchase
CREATE TABLE `Purchase` (
  `Purchase_ID` INT(11) NOT NULL AUTO_INCREMENT,
  `User_ID` INT(11) NOT NULL,
  `Ticket_ID` INT(11) NOT NULL,
  `Status` ENUM('Purchased', 'Refunded') NOT NULL,
  `Purchase_Date` DATE NOT NULL,
  PRIMARY KEY (`Purchase_ID`),
  FOREIGN KEY (`User_ID`) REFERENCES `User`(`User_ID`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (`Ticket_ID`) REFERENCES `Tickets`(`Ticket_ID`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Insert data into Purchase table
INSERT INTO `Purchase` (`User_ID`, `Ticket_ID`, `Status`, `Purchase_Date`) VALUES
(3, 1, 'Purchased', '2024-12-10'),
(3, 2, 'Purchased', '2024-12-10');

COMMIT;
