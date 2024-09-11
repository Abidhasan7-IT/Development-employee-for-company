-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 26, 2024 at 06:27 AM
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
-- Database: `employee_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(15) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `dob` varchar(15) DEFAULT NULL,
  `password` varchar(75) NOT NULL,
  `dp` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `gender`, `dob`, `password`, `dp`) VALUES
(1, 'abid', 'admin@gmail.com', 'Male', '2000-12-31', '1234', '660047e7d79f58.9217565265f1f7061f52a6.31645201a4.jpg'),
(13, 'Abrar', 'abrar@gmail.com', 'Male', '2015-08-25', '1234', '');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `att_id` int(200) NOT NULL,
  `e_id` text NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `date` varchar(100) DEFAULT NULL,
  `time` time DEFAULT current_timestamp(),
  `status` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`att_id`, `e_id`, `name`, `email`, `date`, `time`, `status`) VALUES
(48, 'it1', 'Hasan', 'abidhasanstudent20@gmail.com', '2024-04-26', '10:05:40', 'in'),
(49, 'it1', 'Hasan', 'abidhasanstudent20@gmail.com', '2024-04-26', '11:06:20', 'out'),
(50, 'it1', 'Hasan', 'abidhasanstudent20@gmail.com', '2024-04-26', '12:00:00', 'in'),
(51, 'it1', 'Hasan', 'abidhasanstudent20@gmail.com', '2024-04-26', '14:30:00', 'out'),
(52, 'e1', 'emp1', 'employee1@gmail.com', '2024-04-27', '09:11:42', 'in'),
(53, 'e1', 'emp1', 'employee1@gmail.com', '2024-04-27', '16:28:45', 'out'),
(54, 'it1', 'Hasan', 'abidhasanstudent20@gmail.com', '2024-04-27', '09:46:34', 'in'),
(55, 'it1', 'Hasan', 'abidhasanstudent20@gmail.com', '2024-04-27', '17:57:54', 'out'),
(68, 'e1', 'emp1', 'employee1@gmail.com', '2024-05-01', '09:15:57', 'in'),
(69, 'e1', 'emp1', 'employee1@gmail.com', '2024-05-01', '18:55:55', 'out'),
(77, 'it1', 'Hasan', 'abidhasanstudent20@gmail.com', '2024-05-02', '09:00:11', 'in'),
(78, 'it1', 'Hasan', 'abidhasanstudent20@gmail.com', '2024-05-02', '17:00:00', 'out'),
(82, 'it1', 'Hasan', 'abidhasanstudent20@gmail.com', '2024-05-04', '09:47:40', 'in'),
(83, 'e1', 'emp1', 'employee1@gmail.com', '2024-05-04', '09:00:04', 'in'),
(84, 'it1', 'Hasan', 'abidhasanstudent20@gmail.com', '2024-05-04', '17:07:36', 'out'),
(85, 'e1', 'emp1', 'employee1@gmail.com', '2024-05-04', '17:07:40', 'out'),
(86, 's1', 'abrar ahmed', 'abrar@gmail.com', '2024-05-07', '09:01:42', 'in'),
(87, 's2', 'employee2', 'employee@gmail.com', '2024-05-07', '09:10:46', 'in'),
(88, 's1', 'abrar ahmed', 'abrar@gmail.com', '2024-05-07', '18:52:17', 'out'),
(89, 's2', 'employee2', 'employee@gmail.com', '2024-05-07', '12:52:26', 'out'),
(90, 's2', 'employee2', 'employee@gmail.com', '2024-05-07', '13:22:30', 'in'),
(91, 's2', 'employee2', 'employee@gmail.com', '2024-05-07', '18:52:36', 'out'),
(92, 'it1', 'Hasan', 'abidhasanstudent20@gmail.com', '2024-05-08', '09:08:55', 'in'),
(94, 'it1', 'Hasan', 'abidhasanstudent20@gmail.com', '2024-05-08', '17:09:06', 'out'),
(100, 'e1', 'emp1', 'employee1@gmail.com', '2024-05-09', '12:16:17', 'in'),
(101, 'e2', 'employee3', 'employe3@gmail.com', '2024-05-09', '12:16:35', 'in'),
(102, 'e1', 'emp1', 'employee1@gmail.com', '2024-05-09', '12:16:45', 'out'),
(103, 'e3', 'employee4', 'employe3@gmail.co4', '2024-05-24', '09:24:01', 'in'),
(104, 'it1', 'Hasan', 'abidhasanstudent20@gmail.com', '2024-05-24', '09:11:05', 'in'),
(105, 's1', 'abrar ahmed', 'abrar@gmail.com', '2024-05-24', '09:05:18', 'in'),
(106, 'e1', 'emp1', 'employee1@gmail.com', '2024-05-24', '09:44:23', 'in'),
(107, 'e3', 'employee4', 'employe3@gmail.co4', '2024-05-24', '18:08:46', 'out'),
(108, 'it1', 'Hasan', 'abidhasanstudent20@gmail.com', '2024-05-24', '17:58:53', 'out'),
(109, 'e1', 'emp1', 'employee1@gmail.com', '2024-05-24', '18:09:03', 'out'),
(110, 's1', 'abrar ahmed', 'abrar@gmail.com', '2024-05-24', '18:09:24', 'out');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `id` int(15) NOT NULL,
  `d_name` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`id`, `d_name`) VALUES
(2, 'Marketing'),
(3, 'IT '),
(4, 'HR'),
(5, 'Business');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `id` int(15) NOT NULL,
  `e_id` text NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `dob` varchar(255) DEFAULT NULL,
  `password` varchar(75) NOT NULL,
  `Position` varchar(255) NOT NULL,
  `d_name` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `salary` int(10) NOT NULL,
  `dp` varchar(255) NOT NULL,
  `filename` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `filesize` int(55) DEFAULT NULL,
  `filetype` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `status` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`id`, `e_id`, `name`, `email`, `gender`, `dob`, `password`, `Position`, `d_name`, `salary`, `dp`, `filename`, `filesize`, `filetype`, `status`) VALUES
(13, 'e1', 'emp1', 'employee1@gmail.com', 'Male', '', '', 'employee', 'Marketing', 18000, '', NULL, NULL, NULL, 'active'),
(18, 's1', 'abrar ahmed', 'abrar@gmail.com', 'Male', '', '1234', 'Sr Employee', 'IT', 25000, '', NULL, NULL, NULL, 'active'),
(19, 's2', 'employee2', 'employee@gmail.com', 'Female', '', '1234', 'Sr Employee', 'IT', 25000, '', NULL, NULL, NULL, 'active'),
(20, 'e2', 'employee3', 'employe3@gmail.com', 'Male', '', '', 'employee', 'Business', 22000, '', NULL, NULL, NULL, 'active'),
(21, 'e3', 'employee4', 'employe3@gmail.co4', 'Female', '', '', 'employee', 'HR', 20000, '', NULL, NULL, NULL, 'active'),
(22, 'it1', 'Hasan', 'abidhasanstudent20@gmail.com', 'Male', '12/2/1999', '1234', 'IT Manager', 'IT', 25000, '664222f7251d07.21733309a1.jpg', 'Abid_CV.docx', 76323, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'active'),
(23, 'm1', 'employee5', 'employe5@gmail.com', 'Female', '', '', 'employee', 'Marketing', 18000, '', NULL, NULL, NULL, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `emp_leave`
--

CREATE TABLE `emp_leave` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `LeaveType` varchar(255) DEFAULT NULL,
  `reason` varchar(500) NOT NULL,
  `start_date` varchar(24) NOT NULL,
  `last_date` varchar(20) NOT NULL,
  `email` varchar(30) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emp_leave`
--

INSERT INTO `emp_leave` (`id`, `name`, `LeaveType`, `reason`, `start_date`, `last_date`, `email`, `status`) VALUES
(4, 'abrar ahmed', 'Casual Leave', 'I need leave ', '2024-04-15', '2024-04-16', 'abrar@gmail.com', 'Accepted'),
(20, 'employee2', 'Medical Leave', 'I need leave ', '2024-03-04', '2024-03-05', 'employee@gmail.com', 'Accepted'),
(21, 'employee2', 'Casual Leave', 'I need leave ', '2024-05-11', '2024-05-11', 'employee@gmail.com', 'Accepted'),
(22, 'employee2', 'Personal Time Off', 'I need leave ', '2024-05-15', '2024-05-16', 'employee@gmail.com', 'Accepted'),
(23, 'Hasan', 'Personal Time Off', 'I need leave ', '2024-05-08', '2024-05-09', 'abidhasanstudent20@gmail.com', 'Accepted'),
(24, 'Hasan', 'Casual Leave', 'I need leave ', '2024-05-10', '2024-05-11', 'abidhasanstudent20@gmail.com', 'Accepted');

-- --------------------------------------------------------

--
-- Table structure for table `notice`
--

CREATE TABLE `notice` (
  `id` int(20) NOT NULL,
  `notice` text DEFAULT NULL,
  `currenttime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notice`
--

INSERT INTO `notice` (`id`, `notice`, `currenttime`) VALUES
(6, 'Tomorow we are going to arrange a meeting at 12:00 AM  ', '2024-05-13 14:03:51'),
(10, 'Tomorow we are going to arrange a meeting at 1:00 PM', '2024-05-21 06:01:59');

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `s_id` int(15) NOT NULL,
  `sched_in` varchar(100) DEFAULT NULL,
  `sched_out` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`s_id`, `sched_in`, `sched_out`) VALUES
(10, '09:00 AM', '05:00 PM');

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE `task` (
  `id` int(99) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` varchar(30) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `emp_name` varchar(200) NOT NULL,
  `emp_email` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `sub_date` datetime NOT NULL DEFAULT current_timestamp(),
  `filename` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `filesize` int(55) DEFAULT NULL,
  `filetype` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `task`
--

INSERT INTO `task` (`id`, `name`, `description`, `status`, `start_date`, `end_date`, `emp_name`, `emp_email`, `date_created`, `sub_date`, `filename`, `filesize`, `filetype`) VALUES
(2, 'collect client info', 'collect all client info and do separate file', 'Done', '2024-04-20', '2024-04-21', 'Hasan', 'abidhasanstudent20@gmail.com', '2024-04-20 23:42:05', '2024-04-20 23:53:28', '', 0, ''),
(4, 'please make a account form', 'make a form-\r\ndetails of account', 'Done', '2024-04-22', '2024-04-23', 'Hasan', 'abidhasanstudent20@gmail.com', '2024-04-21 00:06:15', '2024-04-21 00:12:34', '', 0, ''),
(17, 'turnago company website SEO for go top list 10 company', 'do SEO', 'Done', '2024-05-05', '2024-05-06', 'Hasan', 'abidhasanstudent20@gmail.com', '2024-05-05 00:51:16', '2024-05-07 19:58:46', '', 0, ''),
(22, 'software requirement', '', 'Done', '2024-05-11', '2024-05-12', 'Hasan', 'abidhasanstudent20@gmail.com', '2024-05-10 21:55:06', '2024-05-11 12:50:36', 'Requirements.docx', 19255, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');

-- --------------------------------------------------------

--
-- Table structure for table `tblleavetype`
--

CREATE TABLE `tblleavetype` (
  `Id` int(22) NOT NULL,
  `LeaveType` varchar(255) DEFAULT NULL,
  `Description` mediumtext DEFAULT NULL,
  `CreationDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblleavetype`
--

INSERT INTO `tblleavetype` (`Id`, `LeaveType`, `Description`, `CreationDate`) VALUES
(1, 'Casual Leave', 'Provided for urgent or unforeseen matters to the employees.', '2024-03-26 15:58:50'),
(2, 'Medical Leave', 'Related to Health Problems of Employee', '2024-03-26 15:58:50'),
(3, 'Restricted Holiday', 'Holiday that is optional', '2024-03-26 15:58:50'),
(4, 'Paternity Leave', 'To take care of newborns', '2024-03-26 15:58:50'),
(5, 'Bereavement Leave', 'Grieve their loss of losing loved ones', '2024-03-26 15:58:50'),
(6, 'Compensatory Leave', 'For Overtime Workers', '2024-03-29 14:29:19'),
(8, 'Maternity Leave', 'Taking care of newborn, recoveries', '2024-03-30 18:41:03'),
(9, 'Adverse Weather Leave', 'In terms of extreme weather conditions', '2024-03-30 18:42:52'),
(10, 'Voting Leave', 'For official election day', '2024-03-30 18:43:36'),
(12, 'Religious Holidays', 'Based on employees followed religion', '2024-03-30 18:46:41'),
(13, 'Self-Quarantine Leave', 'Related to COVID-19 issues', '2024-03-30 18:47:07'),
(14, 'Personal Time Off', 'To manage some private matters', '2024-03-30 18:47:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`att_id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emp_leave`
--
ALTER TABLE `emp_leave`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notice`
--
ALTER TABLE `notice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`s_id`);

--
-- Indexes for table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblleavetype`
--
ALTER TABLE `tblleavetype`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `att_id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `emp_leave`
--
ALTER TABLE `emp_leave`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `notice`
--
ALTER TABLE `notice`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `s_id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `task`
--
ALTER TABLE `task`
  MODIFY `id` int(99) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `tblleavetype`
--
ALTER TABLE `tblleavetype`
  MODIFY `Id` int(22) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
