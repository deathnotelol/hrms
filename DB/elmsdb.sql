-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 10, 2024 at 11:43 AM
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
-- Database: `elmsdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `UserName` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `updationDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `UserName`, `Password`, `updationDate`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', '2024-09-25 04:11:02');

-- --------------------------------------------------------

--
-- Table structure for table `employee_attendance`
--

CREATE TABLE `employee_attendance` (
  `id` int(11) NOT NULL,
  `eid` int(11) NOT NULL,
  `attendance_date` date NOT NULL,
  `day_of_week` varchar(20) NOT NULL,
  `shift` varchar(20) NOT NULL,
  `attendance` tinyint(1) NOT NULL,
  `createddate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_attendance`
--

INSERT INTO `employee_attendance` (`id`, `eid`, `attendance_date`, `day_of_week`, `shift`, `attendance`, `createddate`) VALUES
(1, 19, '2024-10-06', 'Sunday', 'morning', 1, '2024-10-06 02:30:00'),
(2, 19, '2024-10-06', 'Sunday', 'evening', 1, '2024-10-06 14:04:31');

-- --------------------------------------------------------

--
-- Table structure for table `employee_signatures`
--

CREATE TABLE `employee_signatures` (
  `EmpId` int(11) NOT NULL,
  `eid` int(11) NOT NULL,
  `signature` varchar(255) NOT NULL,
  `createddate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_signatures`
--

INSERT INTO `employee_signatures` (`EmpId`, `eid`, `signature`, `createddate`) VALUES
(10001, 19, 'signatures/signature_10001_1728205413.png', '2024-10-06 09:03:33');

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

CREATE TABLE `positions` (
  `id` int(11) NOT NULL,
  `position_name` varchar(255) NOT NULL,
  `salary` decimal(10,2) NOT NULL,
  `CreateDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`id`, `position_name`, `salary`, `CreateDate`) VALUES
(1, 'Software Engineer', 5000.00, '2024-10-02 15:14:46'),
(2, 'Project Manager', 7000.00, '2024-10-02 15:14:46'),
(3, 'System Analyst', 5500.00, '2024-10-02 15:14:46'),
(4, 'Developer', 4500.00, '2024-10-02 15:14:46'),
(6, 'dfjefef', 555555.00, '2024-10-10 04:19:45');

-- --------------------------------------------------------

--
-- Table structure for table `tbldepartments`
--

CREATE TABLE `tbldepartments` (
  `id` int(11) NOT NULL,
  `DepartmentName` varchar(150) DEFAULT NULL,
  `DepartmentShortName` varchar(100) DEFAULT NULL,
  `DepartmentCode` varchar(50) DEFAULT NULL,
  `CreationDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbldepartments`
--

INSERT INTO `tbldepartments` (`id`, `DepartmentName`, `DepartmentShortName`, `DepartmentCode`, `CreationDate`) VALUES
(1, 'Human Resource', 'HR', 'HR01', '2023-08-31 14:50:20'),
(2, 'Information Technology', 'IT', 'IT01', '2023-08-31 14:50:56'),
(3, 'Accounts', 'Accounts', 'ACCNT01', '2023-08-31 14:51:26'),
(4, 'ADMIN', 'Admin', 'ADMN01', '2023-09-01 11:35:50'),
(6, 'Cyber Security Department', 'CSD', 'CS001', '2024-09-29 14:59:44');

-- --------------------------------------------------------

--
-- Table structure for table `tblemployees`
--

CREATE TABLE `tblemployees` (
  `id` int(11) NOT NULL,
  `EmpId` varchar(100) NOT NULL,
  `FirstName` varchar(150) DEFAULT NULL,
  `LastName` varchar(150) DEFAULT NULL,
  `EmailId` varchar(200) DEFAULT NULL,
  `Password` varchar(180) DEFAULT NULL,
  `Gender` varchar(100) DEFAULT NULL,
  `Dob` varchar(100) DEFAULT NULL,
  `Department` varchar(255) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `City` varchar(200) DEFAULT NULL,
  `Country` varchar(150) DEFAULT NULL,
  `PositionID` int(11) NOT NULL,
  `Phonenumber` char(11) DEFAULT NULL,
  `Status` int(1) DEFAULT NULL,
  `RegDate` timestamp NULL DEFAULT current_timestamp(),
  `ProfileImage` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tblemployees`
--

INSERT INTO `tblemployees` (`id`, `EmpId`, `FirstName`, `LastName`, `EmailId`, `Password`, `Gender`, `Dob`, `Department`, `Address`, `City`, `Country`, `PositionID`, `Phonenumber`, `Status`, `RegDate`, `ProfileImage`) VALUES
(19, '10001', 'Aye', 'Aye', 'ayeaye@gmail.com', '6ad14ba9986e3615423dfca256d04e3f', 'Female', '13 September, 1995', 'Accounts', 'localhost', 'Yangon', 'Myanmar', 4, '09789456123', 1, '2024-09-29 13:18:42', '../assets/images/upload/images (4).jpg'),
(20, '10002', 'Mya', 'Mya', 'myamya@gmail.com', '6ad14ba9986e3615423dfca256d04e3f', 'Female', '11 September, 1990', 'Human Resource', 'BLDG-2, RM-5, EAST YANKIN', 'Mandalay', 'Myanmar', 2, '09456123789', 1, '2024-09-29 13:20:13', '../assets/images/upload/007.jpg'),
(21, '10003', 'War', 'War', 'warwar@gmail.com', '6ad14ba9986e3615423dfca256d04e3f', 'Female', '11 September, 1996', 'Human Resource', 'BLDG-2, RM-5, EAST YANKIN', 'Yangon', 'Myanmar', 3, '09123456789', 1, '2024-09-29 13:21:38', '../assets/images/upload/images (3).jpg'),
(26, '10009', 'Hla', 'Hla', 'hlahla@gmail.com', '6ad14ba9986e3615423dfca256d04e3f', 'Female', '18 October, 2005', 'Human Resource', 'BLDG-2, RM-5, EAST YANKIN', 'Yangon', 'Myanmar', 1, '09454545454', 1, '2024-10-10 08:47:25', '../assets/images/upload/download.jpg'),
(27, '10010', 'Mar', 'Mar', 'marmar@gmail.com', '6ad14ba9986e3615423dfca256d04e3f', 'Female', '12 October, 2005', 'Information Technology', 'BLDG-2, RM-5, EAST YANKIN', 'Yangon', 'Myanmar', 3, '09444545454', 1, '2024-10-10 08:50:59', '../assets/images/upload/007.jpg'),
(28, '10011', 'Nyo', 'Nyo', 'nyonyo@gmail.com', '6ad14ba9986e3615423dfca256d04e3f', 'Female', '17 November, 2004', 'Human Resource', 'BLDG-2, RM-5, EAST YANKIN', 'Yangon', 'Singapore', 4, '09454545545', 1, '2024-10-10 09:01:14', '../assets/images/upload/images (1).jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tblleaves`
--

CREATE TABLE `tblleaves` (
  `id` int(11) NOT NULL,
  `LeaveType` varchar(110) DEFAULT NULL,
  `FromDate` date DEFAULT NULL,
  `ToDate` date DEFAULT NULL,
  `duration` int(11) NOT NULL,
  `Description` mediumtext DEFAULT NULL,
  `PostingDate` date DEFAULT NULL,
  `AdminRemark` longtext DEFAULT NULL,
  `AdminRemarkDate` varchar(120) DEFAULT NULL,
  `Status` int(1) DEFAULT NULL,
  `IsRead` int(1) DEFAULT NULL,
  `empid` int(11) DEFAULT NULL,
  `Deduction` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tblleaves`
--

INSERT INTO `tblleaves` (`id`, `LeaveType`, `FromDate`, `ToDate`, `duration`, `Description`, `PostingDate`, `AdminRemark`, `AdminRemarkDate`, `Status`, `IsRead`, `empid`, `Deduction`) VALUES
(1, 'Casual Leaves', '2024-10-11', '2024-10-12', 2, 'Hello', '2024-10-10', 'Done', '2024-10-10 14:36:38 ', 1, 1, 19, 0.00),
(2, 'Earned Leaves', '2024-10-11', '2024-10-15', 5, 'Hello', '2024-10-10', 'Hello', '2024-10-10 14:36:21 ', 1, 1, 19, 450.00),
(3, 'Sick Leaves', '2024-10-11', '2024-10-16', 6, 'Hello', '2024-10-10', 'Hello Done', '2024-10-10 14:35:53 ', 1, 1, 19, 810.00),
(4, 'RH (Restricted Leaves)', '2024-10-11', '2024-10-20', 10, 'Hi', '2024-10-10', 'Hi', '2024-10-10 14:35:11 ', 1, 1, 19, 1800.00),
(5, 'Casual Leaves', '2024-10-11', '2024-10-13', 3, 'Hello', '2024-10-10', 'Hi ', '2024-10-10 14:40:41 ', 1, 1, 20, 0.00),
(6, 'Earned Leaves', '2024-10-11', '2024-10-15', 5, 'Hello', '2024-10-10', 'Hello', '2024-10-10 14:40:52 ', 1, 1, 20, 700.00),
(7, 'Sick Leaves', '2024-10-12', '2024-10-17', 6, 'Hi', '2024-10-10', 'Done', '2024-10-10 14:41:02 ', 1, 1, 20, 1260.00),
(8, 'RH (Restricted Leaves)', '2024-10-11', '2024-10-20', 10, 'Hello ', '2024-10-10', 'Hi', '2024-10-10 14:41:12 ', 1, 1, 20, 2800.00),
(9, 'Casual Leaves', '2024-10-11', '2024-10-13', 3, 'dffef', '2024-10-10', 'dfefefefe', '2024-10-10 14:47:00 ', 1, 1, 20, 0.00),
(10, 'Earned Leaves', '2024-10-11', '2024-10-15', 5, 'effefefef', '2024-10-10', 'dfeef', '2024-10-10 14:49:37 ', 1, 1, 20, 700.00),
(11, 'Earned Leaves', '2024-10-17', '2024-10-21', 5, 'Hello', '2024-10-10', 'Done', '2024-10-10 15:37:24 ', 1, 1, 20, 700.00);

-- --------------------------------------------------------

--
-- Table structure for table `tblleavetype`
--

CREATE TABLE `tblleavetype` (
  `id` int(11) NOT NULL,
  `LeaveType` varchar(200) DEFAULT NULL,
  `Description` longtext DEFAULT NULL,
  `CreationDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tblleavetype`
--

INSERT INTO `tblleavetype` (`id`, `LeaveType`, `Description`, `CreationDate`) VALUES
(1, 'Casual Leaves', 'Casual Leaves', '2023-08-31 14:52:22'),
(2, 'Earned Leaves', 'Earned Leaves', '2023-08-31 14:52:49'),
(3, 'Sick Leaves', 'Sick Leaves', '2023-08-31 14:53:15'),
(4, 'RH (Restricted Leaves)', 'Restricted Leaves', '2023-09-01 11:37:06');

-- --------------------------------------------------------

--
-- Table structure for table `tblsalaries`
--

CREATE TABLE `tblsalaries` (
  `id` int(11) NOT NULL,
  `EmpId` int(11) NOT NULL,
  `PaymentMonth` varchar(20) NOT NULL,
  `PaymentDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `PaymentMethod` varchar(50) NOT NULL,
  `Salary` decimal(10,2) NOT NULL,
  `Allowance` decimal(10,2) NOT NULL,
  `Deduction` decimal(10,2) NOT NULL,
  `NetSalary` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tblsalaries`
--

INSERT INTO `tblsalaries` (`id`, `EmpId`, `PaymentMonth`, `PaymentDate`, `PaymentMethod`, `Salary`, `Allowance`, `Deduction`, `NetSalary`) VALUES
(1, 10001, 'September', '2024-10-06 09:12:37', 'Bank Transfer', 4500.00, 200.00, 270.00, 4430.00),
(2, 10002, 'September', '2024-10-06 09:30:33', 'Bank Transfer', 7000.00, 0.00, 1470.00, 5530.00),
(3, 10003, 'September', '2024-10-06 09:31:59', 'Bank Transfer', 5500.00, 0.00, 0.00, 5500.00),
(4, 10003, 'October', '2024-10-10 04:37:42', 'Visa', 5500.00, 0.00, 0.00, 5500.00),
(5, 10005, 'October', '2024-10-10 04:38:11', 'Bank Transfer', 5500.00, 0.00, 0.00, 5500.00),
(6, 10001, 'February', '2024-10-10 07:15:28', 'Bank Transfer', 4500.00, 200.00, 270.00, 4430.00),
(7, 10009, 'September', '2024-10-10 08:51:56', 'Bank Transfer', 5000.00, 0.00, 0.00, 5000.00),
(8, 10010, 'September', '2024-10-10 08:52:11', 'Bank Transfer', 5500.00, 0.00, 0.00, 5500.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_attendance`
--
ALTER TABLE `employee_attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_eid` (`eid`),
  ADD KEY `idx_attendance` (`attendance`);

--
-- Indexes for table `employee_signatures`
--
ALTER TABLE `employee_signatures`
  ADD PRIMARY KEY (`EmpId`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbldepartments`
--
ALTER TABLE `tbldepartments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblemployees`
--
ALTER TABLE `tblemployees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblleaves`
--
ALTER TABLE `tblleaves`
  ADD PRIMARY KEY (`id`),
  ADD KEY `UserEmail` (`empid`);

--
-- Indexes for table `tblleavetype`
--
ALTER TABLE `tblleavetype`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblsalaries`
--
ALTER TABLE `tblsalaries`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employee_attendance`
--
ALTER TABLE `employee_attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbldepartments`
--
ALTER TABLE `tbldepartments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tblemployees`
--
ALTER TABLE `tblemployees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `tblleaves`
--
ALTER TABLE `tblleaves`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tblleavetype`
--
ALTER TABLE `tblleavetype`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tblsalaries`
--
ALTER TABLE `tblsalaries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
