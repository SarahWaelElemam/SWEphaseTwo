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

-- Ensure User table has the following users first (if they don't exist yet)
INSERT INTO `User` (`FName`, `LName`, `Email`, `Password`, `Role`, `BirthDate`, `Phone`, `Government`, `Gender`) 
VALUES 
('hana', 'mohamed', 'hana@gmail.com', '123h', 'Admin', '2003-05-10', '01234567890', 'Cairo', 'Female'),
('sarah', 'wael', 'sarah@gmail.com', '123s', 'Organizer', '2003-08-12', '01234567790', 'Cairo', 'Female'),
('karen', 'george', 'karen@gmail.com', '123k', 'Customer', '2004-02-16', '01244567790', 'Cairo', 'Female');


-- Table 2: Events
CREATE TABLE `Events` (
  `Event_ID` INT(11) NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(255) NOT NULL,
  `Description` TEXT NOT NULL,
  `Category` ENUM('Theatre', 'Concert', 'Exhibition') NOT NULL,
  `status` VARCHAR(255) DEFAULT 'Pending', -- Added a default value and fixed the syntax
  `Created_By` INT(11) NOT NULL,
  `Organizer_Name` VARCHAR(255),
  `Start_Date` DATETIME NOT NULL,
  `End_Date` DATETIME,
  `Venue` VARCHAR(255),
  `Address` TEXT,
  `Venue_Map_Link` TEXT,
  `Venue_Facilities` TEXT,
  `Venue_Profile_Link` TEXT,
  `Created_At` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `Updated_At` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Image` BLOB NOT NULL,
  `Venue_Image` BLOB,
  `Organizer_Logo` BLOB,
  PRIMARY KEY (`Event_ID`),
  FOREIGN KEY (`Created_By`) REFERENCES `User`(`User_ID`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Now insert into Events
INSERT INTO `Events` (
  `Name`, `Description`, `Category`, `Status`, `Created_By`, `Organizer_Name`, `Start_Date`, `End_Date`, `Venue`, 
  `Address`, `Venue_Map_Link`, `Venue_Facilities`, `Venue_Profile_Link`, `Image`, `Venue_Image`, `Organizer_Logo`
) VALUES
('Tech Innovators Summit', 'An event showcasing the latest technology and innovations.', 'Exhibition', 'Upcoming', 1, 'TechWorld', '2025-01-15 09:00:00', '2025-01-15 18:00:00', 'Tech Convention Center', '456 Innovation Drive, Silicon City', 'https://maps.google.com/?q=Tech+Convention+Center', 'Parking, Food Services', 'https://venueprofile.com/tech-convention-center', NULL, NULL, NULL),
('Winter Wonderland', 'A festive winter carnival for families and friends.', 'Festival', 'Upcoming', 2, 'Winter Magic Co.', '2024-12-25 10:00:00', '2024-12-25 22:00:00', 'Central Park', '789 Park Lane, Snowtown', 'https://maps.google.com/?q=Central+Park', 'Bathrooms, Security', 'https://venueprofile.com/central-park', NULL, NULL, NULL),
('Spring Art Gala', 'An exhibition of art from emerging and renowned artists.', 'Exhibition', 'Upcoming', 3, 'ArtWorld', '2024-04-01 12:00:00', '2024-04-02 18:00:00', 'Art District Hall', '321 Gallery Street, Artville', 'https://maps.google.com/?q=Art+District+Hall', 'Parking, Food Services, Bathrooms', 'https://venueprofile.com/art-district-hall', NULL, NULL, NULL),
('Global Business Forum', 'A gathering of industry leaders to discuss global business trends.', 'Conference', 'Upcoming', 1, 'BizConnect', '2025-03-20 08:00:00', '2025-03-20 17:00:00', 'International Trade Center', '654 Commerce Avenue, Business City', 'https://maps.google.com/?q=International+Trade+Center', 'Parking, Security, Food Services', 'https://venueprofile.com/international-trade-center', NULL, NULL, NULL);

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
