-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 24, 2023 at 02:56 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `studentattdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `password`, `email`) VALUES
(1, 'Besa ', 'besa', 'besaemmanuel99@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `aid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `ispresent` tinyint(4) NOT NULL COMMENT '1 Represents Present',
  `uid` int(11) NOT NULL,
  `grade` int(255) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `id` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `time` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6),
  `session` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`aid`, `sid`, `ispresent`, `uid`, `grade`, `comment`, `id`, `date`, `time`, `session`) VALUES
(1333, 4, 1, 6, 0, '', 2, 1700607600, '2023-11-24 01:35:22.573146', NULL),
(1332, 3, 1, 6, 0, '', 2, 1700607600, '2023-11-24 01:35:22.572740', NULL),
(1331, 2, 1, 6, 0, '', 2, 1700607600, '2023-11-24 01:35:22.572373', NULL),
(1330, 1, 1, 6, 3, 'Good', 2, 1700607600, '2023-11-24 01:35:22.571859', NULL),
(1328, 3, 1, 6, 0, '', 2, 1700694000, '2023-11-24 01:35:04.539079', NULL),
(1329, 4, 1, 6, 0, '', 2, 1700694000, '2023-11-24 01:35:04.539351', NULL),
(1327, 2, 1, 6, 0, '', 2, 1700694000, '2023-11-24 01:35:04.538877', NULL),
(1325, 4, 1, 6, 0, '', 2, 1700780400, '2023-11-24 01:34:51.666301', NULL),
(1326, 1, 1, 6, 4, '', 2, 1700694000, '2023-11-24 01:35:04.538356', NULL),
(1324, 3, 1, 6, 0, '', 2, 1700780400, '2023-11-24 01:34:51.666061', NULL),
(1323, 2, 1, 6, 0, '', 2, 1700780400, '2023-11-24 01:34:51.665852', NULL),
(1322, 1, 1, 6, 2, '', 2, 1700780400, '2023-11-24 01:34:51.665544', NULL),
(1321, 4, 1, 6, 0, '', 3, 1700607600, '2023-11-24 01:34:24.686032', NULL),
(1320, 3, 1, 6, 0, '', 3, 1700607600, '2023-11-24 01:34:24.685548', NULL),
(1319, 2, 1, 6, 0, '', 3, 1700607600, '2023-11-24 01:34:24.685068', NULL),
(1318, 1, 1, 6, 5, 'Great', 3, 1700607600, '2023-11-24 01:34:24.684288', NULL),
(1317, 4, 1, 6, 0, '', 3, 1700694000, '2023-11-24 01:34:05.437832', NULL),
(1315, 2, 1, 6, 0, '', 3, 1700694000, '2023-11-24 01:34:05.437108', NULL),
(1316, 3, 1, 6, 0, '', 3, 1700694000, '2023-11-24 01:34:05.437390', NULL),
(1314, 1, 1, 6, 2, 'Great', 3, 1700694000, '2023-11-24 01:34:05.436808', NULL),
(1313, 4, 1, 6, 0, '', 3, 1700780400, '2023-11-24 01:33:39.891137', NULL),
(1312, 3, 1, 6, 0, '', 3, 1700780400, '2023-11-24 01:33:39.890829', NULL),
(1311, 2, 1, 6, 4, '', 3, 1700780400, '2023-11-24 01:33:39.890351', NULL),
(1310, 1, 1, 6, 4, 'Great', 3, 1700780400, '2023-11-24 01:33:39.889668', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `email_log`
--

CREATE TABLE `email_log` (
  `id` int(11) NOT NULL,
  `sid` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `email` int(11) DEFAULT 0,
  `send_count` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `email_log`
--

INSERT INTO `email_log` (`id`, `sid`, `subject_id`, `email`, `send_count`) VALUES
(2, NULL, NULL, 0, 4),
(3, NULL, NULL, 0, 12);

-- --------------------------------------------------------

--
-- Table structure for table `notification_log`
--

CREATE TABLE `notification_log` (
  `nid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `sid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `rollno` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`sid`, `name`, `rollno`, `password`, `email`) VALUES
(1, 'Emmanuel Besa', '10', 'besa', 'besaemmanuel99@gmail.com'),
(2, 'Boyd Gondwe', '11', '$2y$10$Eeqgte806yQJ1ftByrvJsOp/LGwpJPRBNExmmnZvREDvXJQlLLkvO', '1804685@northrise.net'),
(3, 'Chita Besa', '12', 'besa', '1804685@northrise.net'),
(4, 'Ernest Lubinda', '13', 'besa', 'besaemmanuel67@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `student_subject`
--

CREATE TABLE `student_subject` (
  `ss_id` int(25) NOT NULL,
  `sid` int(11) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `student_subject`
--

INSERT INTO `student_subject` (`ss_id`, `sid`, `id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(6, 2, 1),
(7, 2, 2),
(8, 2, 3),
(11, 3, 1),
(12, 3, 2),
(13, 3, 3),
(16, 4, 1),
(17, 4, 2),
(18, 4, 3),
(21, 5, 1),
(22, 5, 2),
(23, 5, 3),
(26, 6, 1),
(27, 6, 2),
(28, 6, 3),
(31, 7, 1),
(32, 7, 2),
(33, 7, 3),
(36, 8, 1),
(37, 8, 2),
(38, 8, 3),
(41, 9, 1),
(42, 9, 2),
(43, 9, 3),
(46, 10, 1),
(47, 10, 2),
(48, 10, 3),
(51, 11, 1),
(52, 11, 2),
(53, 11, 3),
(56, 12, 1),
(57, 12, 2),
(58, 12, 3),
(61, 13, 1),
(62, 13, 2),
(63, 13, 3),
(66, 14, 1),
(67, 14, 2),
(68, 14, 3),
(71, 15, 1),
(72, 15, 2),
(73, 15, 3),
(76, 16, 1),
(77, 16, 2),
(78, 16, 3),
(81, 17, 1),
(82, 17, 2),
(83, 17, 3),
(86, 18, 1),
(87, 18, 2),
(88, 18, 3),
(91, 19, 1),
(92, 19, 2),
(93, 19, 3),
(96, 34, 1),
(97, 34, 2),
(99, 35, 1),
(101, 35, 2),
(102, 36, 1),
(104, 36, 3),
(105, 36, 2),
(106, 37, 1),
(107, 37, 2),
(110, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`id`, `name`) VALUES
(1, 'Suturing '),
(2, 'Incisions'),
(3, 'Delivery');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `uid` int(11) NOT NULL,
  `uname` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`uid`, `uname`, `password`, `email`, `status`, `created`) VALUES
(1, 'anthony', 'password', 'mranthony@gmail.com', 1, 1489060137),
(2, 'karen', 'password', 'karenhughes@gmail.com', 1, 1489060137),
(3, 'collin', 'password', 'colling@gmail.com', 1, 1489060137),
(4, 'jesse', 'password', 'jessebnes@gmail.com', 1, 1489060137),
(6, 'elijah', 'password', 'Elijah58@gmail.com', 1, 1489060137);

-- --------------------------------------------------------

--
-- Table structure for table `user_subject`
--

CREATE TABLE `user_subject` (
  `us_id` int(255) NOT NULL,
  `uid` int(255) NOT NULL,
  `id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_subject`
--

INSERT INTO `user_subject` (`us_id`, `uid`, `id`) VALUES
(6, 6, 2),
(7, 6, 3),
(8, 6, 1);

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
  ADD PRIMARY KEY (`aid`);

--
-- Indexes for table `email_log`
--
ALTER TABLE `email_log`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_student_subject` (`sid`,`subject_id`);

--
-- Indexes for table `notification_log`
--
ALTER TABLE `notification_log`
  ADD PRIMARY KEY (`nid`),
  ADD UNIQUE KEY `unique_notification` (`sid`,`id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`sid`);

--
-- Indexes for table `student_subject`
--
ALTER TABLE `student_subject`
  ADD PRIMARY KEY (`ss_id`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `user_subject`
--
ALTER TABLE `user_subject`
  ADD PRIMARY KEY (`us_id`),
  ADD KEY `uid` (`uid`),
  ADD KEY `id` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `aid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1334;

--
-- AUTO_INCREMENT for table `email_log`
--
ALTER TABLE `email_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notification_log`
--
ALTER TABLE `notification_log`
  MODIFY `nid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `sid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `student_subject`
--
ALTER TABLE `student_subject`
  MODIFY `ss_id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `user_subject`
--
ALTER TABLE `user_subject`
  MODIFY `us_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
