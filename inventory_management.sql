-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 07, 2023 at 11:31 AM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventory_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `roles` enum('ADMIN','EDITOR','REVIEWER') NOT NULL,
  `password` varchar(100) NOT NULL,
  `date_updated` datetime NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `mobile`, `roles`, `password`, `date_updated`, `date_created`) VALUES
(1, 'Amit Chaudhary', 'amit@gmail.com', '9794978416', 'ADMIN', '12345', '2023-07-16 00:00:00', '2023-07-23 19:16:58');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` varchar(10) NOT NULL,
  `name` varchar(30) NOT NULL,
  `address` varchar(100) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `gstin` varchar(30) NOT NULL,
  `state` enum('Andaman and Nicobar Islands (35)','Andhra Pradesh (37)','Arunachal Pradesh (12)','Assam (18)','Bihar (10)','Centre Jurisdiction (99)','Chandigarh (04)','Chhattisgarh (22)','Dadra and Nagar Haveli and Daman and Diu (26)','Delhi (07)','Goa (30)','Gujarat (24)','Haryana (06)','Himachal Pradesh (02)','Jammu and Kashmir (01)','Jharkhand (20)','Karnataka (29)','Kerala (32)','Ladakh (Newly Added) (38)','Lakshadweep (31)','Madhya Pradesh (23)','Maharashtra (27)','Manipur (14)','Meghalaya (17)','Mizoram (15)','Nagaland (13)','Odisha (21)','Other Territory (97)','Puducherry (34)','Punjab (03)','Rajasthan (08)','Sikkim (11)','Tamil Nadu (33)','Telangana (36)','Tripura (16)','Uttar Pradesh (09)','Uttarakhand (05)','West Bengal (19)') NOT NULL,
  `place_of_supply` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `customer_id`, `name`, `address`, `phone`, `gstin`, `state`, `place_of_supply`) VALUES
(2, 'PbvUy-', 'adssa', '2312312313', 'Delhi (07)', '123123123', 'Delhi (07)', 'Delhi (07)'),
(8, 'vTYZ5g', 'swdsdfsfd', '0', 'Assam (18)', '', 'Assam (18)', 'Assam (18)');

-- --------------------------------------------------------

--
-- Table structure for table `expense`
--

CREATE TABLE `expense` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `expense_id` varchar(15) NOT NULL,
  `date` date NOT NULL,
  `expense_purpose` varchar(15) NOT NULL,
  `amount` int(11) NOT NULL,
  `payment_mode` enum('BANK TRANSFER','CASH','CREDIT CARD') NOT NULL,
  `remarks` varchar(50) NOT NULL,
  `created_by` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int(11) NOT NULL,
  `date_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `expense`
--

INSERT INTO `expense` (`id`, `expense_id`, `date`, `expense_purpose`, `amount`, `payment_mode`, `remarks`, `created_by`, `date_created`, `updated_by`, `date_updated`) VALUES
(29, 'u71nxh', '2023-09-16', 'QQQQQQQQ', 21, 'CASH', 'SASASAS', 1, '0000-00-00 00:00:00', 0, '2023-09-30 04:48:10'),
(30, 'eQwAkB', '2023-09-16', 'dfgd', 234, 'BANK TRANSFER', 'sdadasd', 1, '0000-00-00 00:00:00', 0, '2023-09-16 18:31:45'),
(31, 'PCRAiH', '2023-09-16', 'sdfsdf', 2323, 'BANK TRANSFER', 'dasdas', 1, '0000-00-00 00:00:00', 0, '2023-09-16 18:33:14'),
(32, 'mrgLY5', '2023-09-16', 'AAAAAAAAAAAA', 211, 'BANK TRANSFER', 'asdasd', 1, '0000-00-00 00:00:00', 0, '2023-09-30 04:48:00'),
(33, '6OXC-B', '2023-09-23', 'DEBUGGASHBJ AKJ', 112, 'CASH', '', 1, '0000-00-00 00:00:00', 0, '2023-09-23 07:56:19'),
(34, 'GTZVC-', '2023-09-23', 'ASAS QWQW', 3113, 'BANK TRANSFER', '', 1, '0000-00-00 00:00:00', 0, '2023-09-23 07:56:33'),
(36, 'gJf8Mb', '2023-09-26', 'dfsfdsf', 234234, 'CASH', 'dfsdfsdfds', 1, '0000-00-00 00:00:00', 0, '2023-09-28 15:51:04'),
(37, 'kwC28z', '2023-09-22', 'Add Expense', 13121, 'CREDIT CARD', 'asdasdasdsa', 1, '0000-00-00 00:00:00', 0, '2023-09-29 15:18:34'),
(38, '7U7cjE', '2023-10-07', 'zXZX', 123, 'CREDIT CARD', '123', 1, '0000-00-00 00:00:00', 0, '2023-10-07 08:28:12'),
(39, '9cdKEG', '2023-10-07', 'asdasda', 123, 'CASH', '12312312', 1, '0000-00-00 00:00:00', 0, '2023-10-07 08:28:19'),
(40, 'jdotWY', '2023-10-07', 'asdasdasd', 31221, 'CREDIT CARD', 'sadasdas', 1, '0000-00-00 00:00:00', 0, '2023-10-07 08:28:25'),
(41, 'kSPhrQ', '2023-10-07', 'SDsadasd', 123, 'CREDIT CARD', '123123', 1, '0000-00-00 00:00:00', 0, '2023-10-07 08:28:32');

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_type` enum('Regular','Direct Invoice') NOT NULL,
  `invoice_number` varchar(25) NOT NULL,
  `invoice_date` date NOT NULL,
  `dispatch_mode` varchar(25) NOT NULL,
  `reverse_charge` float NOT NULL,
  `vehcile_no` varchar(20) NOT NULL,
  `due_date` date NOT NULL,
  `customer_po_date` varchar(50) NOT NULL,
  `taxable_amount` float NOT NULL,
  `cgst` float NOT NULL,
  `sgst` float NOT NULL,
  `igst` float NOT NULL,
  `total_tax` float NOT NULL,
  `total_amount_after_tax` float NOT NULL,
  `total_discount` float NOT NULL,
  `shipping_address` varchar(10) NOT NULL,
  `billing_address` varchar(10) NOT NULL,
  `created_by` int(11) NOT NULL,
  `date_created` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `date_updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`id`, `invoice_type`, `invoice_number`, `invoice_date`, `dispatch_mode`, `reverse_charge`, `vehcile_no`, `due_date`, `customer_po_date`, `taxable_amount`, `cgst`, `sgst`, `igst`, `total_tax`, `total_amount_after_tax`, `total_discount`, `shipping_address`, `billing_address`, `created_by`, `date_created`, `updated_by`, `date_updated`) VALUES
(2, 'Regular', 'BISS/01/22', '2023-10-07', 'ASAS1231', 213, 'BISS123', '2023-10-07', 'ASAS123123', 100, 0, 0, 10, 110, 100, 0, 'PbvUy-', 'PbvUy-', 1, '2023-10-07 08:02:37', NULL, '2023-10-07 09:27:04'),
(4, 'Direct Invoice', 'asdasdasd', '2023-10-07', 'asdas', 123123, 'sdadasdsa', '2023-10-07', 'asdasdasd', 90, 4.5, 4.5, 0, 99, 90, 10, 'vTYZ5g', 'vTYZ5g', 1, '2023-10-07 08:25:50', NULL, '2023-10-07 08:25:50');

-- --------------------------------------------------------

--
-- Table structure for table `payment_against_puchase`
--

CREATE TABLE `payment_against_puchase` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_number` varchar(25) NOT NULL,
  `amount_paid` float NOT NULL,
  `date_paid` date NOT NULL,
  `created_by` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payment_against_puchase`
--

INSERT INTO `payment_against_puchase` (`id`, `invoice_number`, `amount_paid`, `date_paid`, `created_by`, `date_created`) VALUES
(1, 'sdsadasd', 150, '2023-09-12', 1, '2023-09-12 18:08:31'),
(2, 'sdsadasd', 11, '2023-09-13', 1, '2023-09-13 15:09:23'),
(3, 'ASAS123', 350, '2023-09-14', 1, '2023-09-14 00:58:33'),
(4, 'sdsadasdasd', 15, '2023-09-14', 1, '2023-09-14 13:12:38'),
(5, 'AWSWS12w3', 336, '2023-09-14', 1, '2023-09-14 13:19:37'),
(6, 'asdasdsad', 175, '2023-09-14', 1, '2023-09-14 13:28:53'),
(7, 'asdasd', 175, '2023-09-14', 1, '2023-09-14 13:34:20'),
(8, 'ASAS12', 175, '2023-09-14', 1, '2023-09-14 13:39:14'),
(9, 'SAS12312', 15, '2023-09-14', 1, '2023-09-14 13:41:59'),
(10, 'fghbjn', 15, '2023-09-14', 1, '2023-09-14 13:54:36'),
(11, 'EW789', 12, '2023-09-14', 1, '2023-09-14 13:57:15'),
(12, 'ASAWQW123', 1344, '2023-09-14', 1, '2023-09-14 14:07:07'),
(13, 'ASAS121', 180, '2023-09-14', 1, '2023-09-14 14:48:39'),
(14, 'QMN312', 31, '2023-09-14', 1, '2023-09-14 14:53:01'),
(15, 'QWQWQW121212', 483, '2023-09-14', 1, '2023-09-14 15:22:05'),
(16, 'QWAS123', 1000, '2023-09-14', 1, '2023-09-14 15:30:27'),
(17, 'QWAS123', 500, '2023-09-14', 1, '2023-09-14 15:30:37'),
(18, 'QWAS123', 19, '2023-09-14', 1, '2023-09-14 15:30:56'),
(19, '12wdasdasd', 400, '2023-09-14', 1, '2023-09-14 15:38:32'),
(20, '12wdasdasd', 500, '2023-09-14', 1, '2023-09-14 15:38:43'),
(21, '12wdasdasd', 800, '2023-09-14', 1, '2023-09-14 15:38:47'),
(22, '12wdasdasd', 60, '2023-09-14', 1, '2023-09-14 15:38:55'),
(23, '12wdasdasd', 700, '2023-09-14', 1, '2023-09-14 15:38:59'),
(24, '12wdasdasd', 60, '2023-09-14', 1, '2023-09-14 15:39:04'),
(25, '12wdasdasd', 20, '2023-09-14', 1, '2023-09-14 15:39:08'),
(26, '12wdasdasd', 600, '2023-09-14', 1, '2023-09-14 15:39:13'),
(27, '12wdasdasd', 900, '2023-09-14', 1, '2023-09-14 15:39:17'),
(28, '12wdasdasd', 700, '2023-09-14', 1, '2023-09-14 15:39:21'),
(29, '12wdasdasd', 500, '2023-09-14', 1, '2023-09-14 15:39:24'),
(30, '12wdasdasd', 700, '2023-09-14', 1, '2023-09-14 15:39:28'),
(31, '12wdasdasd', 6000, '2023-09-14', 1, '2023-09-14 15:39:34'),
(32, '12wdasdasd', 190, '2023-09-14', 1, '2023-09-14 15:39:40'),
(35, 'ASAS1222', 1505, '2023-09-14', 1, '2023-09-14 16:06:41'),
(36, 'ASAS1231312', 500, '2023-09-14', 1, '2023-09-14 16:31:29'),
(37, 'ASAS1231312', 400, '2023-09-14', 1, '2023-09-14 16:31:36'),
(38, 'ASAS1231312', 42, '2023-09-14', 1, '2023-09-14 16:31:47'),
(39, 'ASA1212', 200, '2023-09-14', 1, '2023-09-14 16:33:38'),
(40, 'ASA1212', 2500, '2023-09-14', 1, '2023-09-14 16:33:52'),
(41, 'ASA1212', 900, '2023-09-15', 1, '2023-09-14 16:34:04'),
(42, 'ASA1212', 16, '2023-09-15', 1, '2023-09-14 16:34:45'),
(43, 'A111111123', 3517, '2023-09-14', 1, '2023-09-14 16:47:51'),
(44, '12wdasdasd', 2802, '2023-09-16', 1, '2023-09-16 05:49:44'),
(45, 'GAH61AH61212G', 8758, '2023-09-16', 1, '2023-09-16 06:28:36'),
(46, 'GA567665', 3321, '2023-09-16', 1, '2023-09-16 06:44:38'),
(47, 'ASQ123222', 1757, '2023-09-16', 1, '2023-09-16 07:09:31'),
(48, 'zadasdasd123', 1932, '2023-09-16', 1, '2023-09-16 07:27:12'),
(49, 'ASASAQW123123', 161, '2023-09-16', 1, '2023-09-16 07:51:53'),
(50, 'ASASASASAS', 111, '2023-09-16', 1, '2023-09-16 08:01:41'),
(51, 'LLKJJ12312', 4043, '2023-09-16', 1, '2023-09-16 08:10:04'),
(52, 'ASA123123', 2425, '2023-09-16', 1, '2023-09-16 08:37:32'),
(53, 'ASAS1221', 143, '2023-09-16', 1, '2023-09-16 09:04:58'),
(54, 'AAAAAAAAAAQQ', 100, '2023-09-16', 1, '2023-09-16 19:29:31'),
(55, 'sdfsdfsdfsdfsdf', 38, '2023-09-29', 1, '2023-09-29 14:24:32'),
(56, 'sdfsdfsdfsdfsdf', 0, '0000-00-00', 1, '2023-10-07 08:27:51');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `hsn_sac` varchar(50) NOT NULL,
  `per_piece_price` float NOT NULL,
  `quantity` int(11) NOT NULL,
  `taxrate` float NOT NULL,
  `created_by` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(11) DEFAULT NULL,
  `date_updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `hsn_sac`, `per_piece_price`, `quantity`, `taxrate`, `created_by`, `date_created`, `updated_by`, `date_updated`) VALUES
(1, 'asdasdasd', '12QWQW', 12, 68, 12, 1, '2023-09-12 18:08:31', 1, '2023-09-24 16:47:35'),
(2, 'ASASQWWQ', '12QQA123', 13, 132, 12, 1, '2023-09-14 00:58:33', 1, '2023-09-24 16:47:44'),
(3, 'ASASA', 'QWQW', 12, 1203, 12, 1, '2023-09-14 13:19:37', 1, '2023-09-16 08:10:04'),
(4, 'Nameen', 'WSA123', 100, 288, 10, 1, '2023-09-14 14:07:07', 1, '2023-09-30 11:02:45'),
(5, 'Kukure', 'QWQW', 15, 45, 27, 1, '2023-09-14 14:53:01', 1, '2023-09-29 14:24:32'),
(6, 'Biscuits', '21WQ', 12, 125, 12, 1, '2023-09-14 14:53:01', 1, '2023-09-16 09:04:58'),
(7, 'ASASASAS', '121QWQW', 12, 56, 12, 1, '2023-09-14 15:49:14', 1, '2023-09-24 16:47:26'),
(8, 'QQQQQQQQQ', '32AS', 16, 73, 15, 1, '2023-09-14 16:31:03', 1, '2023-09-25 14:13:23');

-- --------------------------------------------------------

--
-- Table structure for table `products_against_invoice`
--

CREATE TABLE `products_against_invoice` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_number` varchar(25) NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL,
  `per_piece_price` int(11) NOT NULL,
  `discount` float NOT NULL,
  `profit` float NOT NULL,
  `taxablevalue` float NOT NULL,
  `cgst` float NOT NULL,
  `sgst` float NOT NULL,
  `igst` float NOT NULL,
  `total` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products_against_invoice`
--

INSERT INTO `products_against_invoice` (`id`, `invoice_number`, `product_name`, `quantity`, `per_piece_price`, `discount`, `profit`, `taxablevalue`, `cgst`, `sgst`, `igst`, `total`) VALUES
(1, 'WESXW-01', 'ASASASAS', 12, 21, 12, 12, 12, 12, 12, 12, 12);

-- --------------------------------------------------------

--
-- Table structure for table `products_against_purchase`
--

CREATE TABLE `products_against_purchase` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_number` varchar(25) NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `per_piece_price` float NOT NULL,
  `quantity` int(11) NOT NULL,
  `taxrate` float NOT NULL,
  `csgt` float NOT NULL,
  `sgst` float NOT NULL,
  `igst` float NOT NULL,
  `total_tax` float NOT NULL,
  `total` float NOT NULL,
  `credit_note` varchar(50) DEFAULT NULL,
  `credit_note_date` date DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(11) DEFAULT NULL,
  `date_updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products_against_purchase`
--

INSERT INTO `products_against_purchase` (`id`, `invoice_number`, `product_name`, `per_piece_price`, `quantity`, `taxrate`, `csgt`, `sgst`, `igst`, `total_tax`, `total`, `credit_note`, `credit_note_date`, `created_by`, `date_created`, `updated_by`, `date_updated`) VALUES
(1, 'sdsadasd', 'asdasdasd', 12, 12, 12, 0, 0, 12, 17, 161, NULL, NULL, 1, '2023-09-12 18:08:31', NULL, NULL),
(2, 'ASAS123', 'asdasdasd', 12, 12, 12, 0, 0, 12, 17, 161, NULL, NULL, 1, '2023-09-14 00:58:33', NULL, NULL),
(3, 'ASAS123', 'ASASQWWQ', 13, 13, 12, 0, 0, 12, 20, 189, NULL, NULL, 1, '2023-09-14 00:58:33', NULL, NULL),
(4, 'sdsadasdasd', 'ASASQWWQ', 13, 1, 12, 0, 0, 12, 2, 15, NULL, NULL, 1, '2023-09-14 13:12:38', NULL, NULL),
(5, 'AWSWS12w3', 'ASASQWWQ', 13, 12, 12, 0, 0, 12, 19, 175, NULL, NULL, 1, '2023-09-14 13:19:37', NULL, NULL),
(6, 'AWSWS12w3', 'ASASA', 12, 12, 12, 0, 0, 12, 17, 161, NULL, NULL, 1, '2023-09-14 13:19:37', NULL, NULL),
(7, 'asdasdsad', 'ASASQWWQ', 13, 12, 12, 0, 0, 12, 19, 175, NULL, NULL, 1, '2023-09-14 13:28:53', NULL, NULL),
(8, 'asdasd', 'ASASQWWQ', 13, 12, 12, 0, 0, 12, 19, 175, NULL, NULL, 1, '2023-09-14 13:34:20', NULL, NULL),
(9, 'ASAS12', 'ASASQWWQ', 13, 12, 12, 0, 0, 12, 19, 175, NULL, NULL, 1, '2023-09-14 13:39:14', NULL, NULL),
(10, 'SAS12312', 'ASASQWWQ', 13, 1, 12, 0, 0, 12, 2, 15, NULL, NULL, 1, '2023-09-14 13:41:59', NULL, NULL),
(11, 'fghbjn', 'ASASQWWQ', 13, 1, 12, 0, 0, 12, 2, 15, NULL, NULL, 1, '2023-09-14 13:54:36', NULL, NULL),
(12, 'EW789', 'ASASA', 12, 1, 0, 0, 0, 0, 0, 12, NULL, NULL, 1, '2023-09-14 13:57:15', NULL, NULL),
(13, 'ASAWQW123', 'Nameen', 100, 12, 12, 0, 0, 12, 144, 1344, NULL, NULL, 1, '2023-09-14 14:07:07', NULL, NULL),
(14, 'QAS3131', 'Nameen', 100, 1, 12, 0, 0, 12, 12, 112, 'CREDIT-NOTE-1', '2023-09-16', 1, '2023-09-14 14:36:52', 1, '2023-09-16 17:51:16'),
(15, 'QAS3131', 'ASASA', 12, 21, 13, 0, 0, 13, 33, 285, 'CREDIT-NOTE-1', '2023-09-16', 1, '2023-09-14 14:36:52', 1, '2023-09-16 17:51:16'),
(16, 'QAS3131', 'asdasdasd', 12, 21, 12, 0, 0, 12, 30, 282, 'CREDIT-NOTE-1', '2023-09-16', 1, '2023-09-14 14:36:52', 1, '2023-09-16 17:51:16'),
(17, 'QWAS123', 'Nameen', 100, 12, 12, 0, 0, 12, 144, 1344, NULL, NULL, 1, '2023-09-14 14:46:22', NULL, NULL),
(18, 'QWAS123', 'ASASQWWQ', 13, 12, 12, 0, 0, 12, 19, 175, 'ASASQWWQ', '2023-09-14', 1, '2023-09-14 14:46:22', 1, '2023-09-14 15:31:06'),
(19, 'ASAS121', 'asdasdasd', 12, 1, 12, 0, 0, 12, 1, 13, 'asdasdasd', '2023-09-14', 1, '2023-09-14 14:48:39', 1, '2023-09-14 15:20:13'),
(20, 'ASAS121', 'ASASQWWQ', 13, 21, 12, 0, 0, 12, 33, 306, NULL, NULL, 1, '2023-09-14 14:48:39', NULL, NULL),
(21, 'ASAS121', 'ASASA', 12, 12, 12, 0, 0, 12, 17, 161, NULL, NULL, 1, '2023-09-14 14:48:39', NULL, NULL),
(22, 'QMN312', 'ASASA', 12, 12, 12, 0, 0, 12, 17, 161, 'ASASA', '2023-09-14', 1, '2023-09-14 14:53:01', 1, '2023-09-14 14:53:13'),
(23, 'QMN312', 'Kukure', 11, 17, 31, 0, 0, 31, 58, 245, 'Biscuits', '2023-09-14', 1, '2023-09-14 14:53:01', 1, '2023-09-14 15:21:24'),
(24, 'QMN312', 'Biscuits', 12, 12, 12, 0, 0, 12, 17, 161, 'Biscuits', '2023-09-14', 1, '2023-09-14 14:53:01', 1, '2023-09-14 15:21:24'),
(25, 'QWQWQW121212', 'Biscuits', 12, 12, 12, 0, 0, 12, 17, 161, NULL, NULL, 1, '2023-09-14 15:22:05', NULL, NULL),
(26, 'QWQWQW121212', 'Kukure', 11, 12, 12, 0, 0, 12, 16, 148, NULL, NULL, 1, '2023-09-14 15:22:05', NULL, NULL),
(27, 'QWQWQW121212', 'Biscuits', 12, 12, 21, 0, 0, 21, 30, 174, NULL, NULL, 1, '2023-09-14 15:22:05', NULL, NULL),
(28, '12wdasdasd', 'ASASA', 12, 1111, 12, 0, 0, 12, 1600, 14932, 'sdasdasd', '2023-09-14', 1, '2023-09-14 15:38:32', 1, '2023-09-16 06:16:08'),
(29, 'G123QWG', 'ASASQWWQ', 13, 12, 12, 6, 6, 0, 19, 175, 'ASASASAS', '2023-09-14', 1, '2023-09-14 15:49:14', 1, '2023-09-14 16:02:25'),
(30, 'G123QWG', 'Biscuits', 12, 12, 21, 10.5, 10.5, 0, 30, 174, NULL, NULL, 1, '2023-09-14 15:49:14', NULL, NULL),
(31, 'G123QWG', 'ASASASAS', 12, 12, 12, 6, 6, 0, 17, 161, 'ASASASAS', '2023-09-14', 1, '2023-09-14 15:49:14', 1, '2023-09-14 16:02:25'),
(32, 'ASAS1222', 'ASASASAS', 12, 12, 12, 0, 0, 12, 17, 161, NULL, NULL, 1, '2023-09-14 16:04:07', NULL, NULL),
(33, 'ASAS1222', 'Nameen', 100, 12, 12, 0, 0, 12, 144, 1344, NULL, NULL, 1, '2023-09-14 16:04:07', NULL, NULL),
(34, 'ASAS1231312', 'ASASASAS', 12, 12, 12, 0, 0, 12, 17, 161, NULL, NULL, 1, '2023-09-14 16:31:03', NULL, NULL),
(35, 'ASAS1231312', 'QQQQQQQQQ', 16, 37, 32, 0, 0, 32, 189, 781, NULL, NULL, 1, '2023-09-14 16:31:03', NULL, NULL),
(36, 'ASA1212', 'QQQQQQQQQ', 16, 21, 11, 5.5, 5.5, 0, 37, 373, 'QQQQQQQQQ', '2023-09-14', 1, '2023-09-14 16:33:25', 1, '2023-09-14 16:35:01'),
(37, 'ASA1212', 'Nameen', 100, 22, 21, 10.5, 10.5, 0, 462, 2662, NULL, NULL, 1, '2023-09-14 16:33:25', NULL, NULL),
(38, 'ASA1212', 'Biscuits', 12, 40, 21, 10.5, 10.5, 0, 101, 581, 'Biscuits', '2023-09-09', 1, '2023-09-14 16:33:25', 1, '2023-09-14 16:35:11'),
(39, 'A111111123', 'QQQQQQQQQ', 16, 12, 12, 0, 0, 12, 23, 215, 'QQQQQQQQQ', '2023-09-14', 1, '2023-09-14 16:47:51', 1, '2023-09-14 16:48:06'),
(40, 'A111111123', 'ASASA', 12, 12, 12, 0, 0, 12, 17, 161, 'asdasdasd', '2023-09-14', 1, '2023-09-14 16:47:51', 1, '2023-09-14 16:51:17'),
(41, 'A111111123', 'ASASQWWQ', 13, 12, 12, 0, 0, 12, 19, 175, 'ASASQWWQ', '2023-09-16', 1, '2023-09-14 16:47:51', 1, '2023-09-16 04:20:09'),
(42, 'A111111123', 'Nameen', 100, 22, 22, 0, 0, 22, 484, 2684, NULL, NULL, 1, '2023-09-14 16:47:51', NULL, NULL),
(43, 'A111111123', 'asdasdasd', 12, 21, 12, 0, 0, 12, 30, 282, 'asdasdasd', '2023-09-14', 1, '2023-09-14 16:47:51', 1, '2023-09-14 16:51:17'),
(44, 'GAH61AH61212G', 'ASASASAS', 12, 9, 9, 0, 0, 9, 10, 118, 'asdasd', '2023-09-16', 1, '2023-09-16 06:28:36', 1, '2023-09-16 06:29:51'),
(45, 'GAH61AH61212G', 'Nameen', 100, 80, 8, 0, 0, 8, 640, 8640, 'asdasd', '2023-09-15', 1, '2023-09-16 06:28:36', 1, '2023-09-16 06:33:58'),
(47, 'GA567665', 'Nameen', 100, 31, 3, 1.5, 1.5, 0, 93, 3193, 'kkk', '2023-09-13', 1, '2023-09-16 06:44:38', 1, '2023-09-16 06:49:12'),
(48, 'ASQ123222', 'Kukure', 11, 12, 21, 0, 0, 21, 28, 160, NULL, NULL, 1, '2023-09-16 07:09:31', NULL, NULL),
(49, 'ASQ123222', 'Nameen', 100, 12, 21, 0, 0, 21, 252, 1452, NULL, NULL, 1, '2023-09-16 07:09:31', NULL, NULL),
(50, 'ASQ123222', 'Biscuits', 12, 11, 10, 0, 0, 10, 13, 145, 'Biscuits', '2023-09-16', 1, '2023-09-16 07:09:31', 1, '2023-09-16 07:10:20'),
(51, 'zadasdasd123', 'Biscuits', 12, 12, 12, 6, 6, 0, 17, 161, 'Biscuits', '2023-09-16', 1, '2023-09-16 07:27:12', 1, '2023-09-16 07:49:48'),
(52, 'zadasdasd123', 'Nameen', 100, 12, 12, 6, 6, 0, 144, 1344, NULL, NULL, 1, '2023-09-16 07:27:12', NULL, NULL),
(53, 'zadasdasd123', 'ASASA', 12, 21, 12, 6, 6, 0, 30, 282, NULL, NULL, 1, '2023-09-16 07:27:12', NULL, NULL),
(54, 'zadasdasd123', 'ASASASAS', 12, 11, 10, 5, 5, 0, 13, 145, NULL, NULL, 1, '2023-09-16 07:27:12', NULL, NULL),
(55, 'ASASAQW123123', 'QQQQQQQQQ', 16, 1, 12, 6, 6, 0, 2, 18, 'SSS1234', '2023-09-16', 1, '2023-09-16 07:51:53', 1, '2023-09-16 07:54:40'),
(56, 'ASASAQW123123', 'ASASQWWQ', 13, 1, 21, 10.5, 10.5, 0, 3, 16, 'ASA1223', '2023-09-16', 1, '2023-09-16 07:51:53', 1, '2023-09-16 08:17:32'),
(57, 'ASASAQW123123', 'Kukure', 11, 1, 11, 5.5, 5.5, 0, 1, 12, NULL, NULL, 1, '2023-09-16 07:51:53', NULL, NULL),
(58, 'ASASAQW123123', 'Nameen', 100, 1, 15, 7.5, 7.5, 0, 15, 115, 'ASA1223', '2023-09-16', 1, '2023-09-16 07:51:53', 1, '2023-09-16 08:17:32'),
(59, 'ASASASASAS', 'Nameen', 100, 1, 11, 0, 0, 11, 11, 111, 'ASAS21212', '2023-09-16', 1, '2023-09-16 08:01:41', 1, '2023-09-16 08:01:53'),
(60, 'LLKJJ12312', 'Nameen', 100, 15, 1, 0.5, 0.5, 0, 15, 1515, 'Nameen', '2023-09-16', 1, '2023-09-16 08:10:04', 1, '2023-09-16 08:10:13'),
(61, 'LLKJJ12312', 'Biscuits', 12, 12, 12, 6, 6, 0, 17, 161, 'NEW', '2023-09-16', 1, '2023-09-16 08:10:04', 1, '2023-09-16 08:10:22'),
(62, 'LLKJJ12312', 'Nameen', 100, 21, 12, 6, 6, 0, 252, 2352, 'Nameen', '2023-09-16', 1, '2023-09-16 08:10:04', 1, '2023-09-16 08:10:13'),
(63, 'LLKJJ12312', 'ASASA', 12, 1, 21, 10.5, 10.5, 0, 3, 15, 'NEW', '2023-09-16', 1, '2023-09-16 08:10:04', 1, '2023-09-16 08:10:22'),
(64, 'ASA123123', 'asdasdasd', 12, 1, 12, 6, 6, 0, 1, 13, 'asdasdasd', '2023-09-16', 1, '2023-09-16 08:37:32', 1, '2023-09-16 08:37:44'),
(65, 'ASA123123', 'ASASQWWQ', 13, 1, 12, 6, 6, 0, 2, 15, 'ASASQWWQ', '2023-09-16', 1, '2023-09-16 08:37:32', 1, '2023-09-16 08:42:38'),
(66, 'ASA123123', 'Nameen', 100, 21, 12, 6, 6, 0, 252, 2352, NULL, NULL, 1, '2023-09-16 08:37:32', NULL, NULL),
(67, 'ASA123123', 'Kukure', 11, 1, 21, 10.5, 10.5, 0, 2, 13, 'Kukure', '2023-09-16', 1, '2023-09-16 08:37:32', 1, '2023-09-16 08:38:13'),
(68, 'ASA123123', 'QQQQQQQQQ', 16, 1, 21, 10.5, 10.5, 0, 3, 19, 'QQQQQQQQQ', '2023-09-16', 1, '2023-09-16 08:37:32', 1, '2023-09-16 08:49:49'),
(69, 'ASA123123', 'Biscuits', 12, 1, 12, 6, 6, 0, 1, 13, NULL, NULL, 1, '2023-09-16 08:37:32', NULL, NULL),
(70, 'ASAS1221', 'Nameen', 100, 1, 11, 5.5, 5.5, 0, 11, 111, 'asdasdsad', '2023-09-16', 1, '2023-09-16 09:04:58', 1, '2023-09-16 09:42:05'),
(71, 'ASAS1221', 'Biscuits', 12, 1, 14, 7, 7, 0, 2, 14, 'Biscuits', '2023-09-16', 1, '2023-09-16 09:04:58', 1, '2023-09-16 09:05:30'),
(72, 'ASAS1221', 'QQQQQQQQQ', 16, 1, 15, 7.5, 7.5, 0, 2, 18, '2023-09-16-CREDIT_NOTE', '2023-09-16', 1, '2023-09-16 09:04:58', 1, '2023-09-16 17:50:02'),
(73, 'AAAAAAAAAAQQ', 'Nameen', 100, 12, 11, 5.5, 5.5, 0, 132, 1332, 'ASAS', '2023-09-16', 1, '2023-09-16 19:25:05', 1, '2023-09-16 19:31:06'),
(74, 'sdfsdfsdfsdfsdf', 'Kukure', 15, 2, 27, 0, 0, 27, 8, 38, 'KURKURE38', '2023-10-01', 1, '2023-09-29 14:24:32', 1, '2023-10-01 15:24:55');

-- --------------------------------------------------------

--
-- Table structure for table `purchase`
--

CREATE TABLE `purchase` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sold_by` varchar(10) NOT NULL,
  `cateogry` enum('For Company Use','For Selling','','') NOT NULL,
  `invoice_date` date NOT NULL,
  `invoice_number` varchar(25) NOT NULL,
  `tax_type` enum('IGST','CGST/SGST','','') NOT NULL,
  `cgst` float NOT NULL,
  `sgst` float NOT NULL,
  `igst` float NOT NULL,
  `tax_amount` float NOT NULL,
  `taxable_amount` float NOT NULL,
  `total_amount_after_tax` float NOT NULL,
  `transport_charges` float NOT NULL,
  `payment_status` enum('Full Credit','Full Paid','Partial Paid') NOT NULL,
  `created_by` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(11) NOT NULL,
  `date_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `purchase`
--

INSERT INTO `purchase` (`id`, `sold_by`, `cateogry`, `invoice_date`, `invoice_number`, `tax_type`, `cgst`, `sgst`, `igst`, `tax_amount`, `taxable_amount`, `total_amount_after_tax`, `transport_charges`, `payment_status`, `created_by`, `date_created`, `updated_by`, `date_updated`) VALUES
(1, 'JBTtIt', 'For Company Use', '2023-09-12', 'sdsadasd', 'IGST', 8.5, 8.5, 17, 17, 144, 161, 150, 'Full Paid', 1, '2023-09-12 18:08:31', 0, '2023-09-13 15:09:23'),
(2, 'JBTtIt', 'For Company Use', '2023-09-14', 'ASAS123', 'IGST', 18.5, 18.5, 37, 37, 313, 350, 34324, 'Full Paid', 1, '2023-09-14 00:58:33', 0, '2023-09-14 00:58:33'),
(3, 'JBTtIt', 'For Company Use', '2023-09-14', 'sdsadasdasd', 'IGST', 1, 1, 2, 2, 13, 15, 0, 'Full Paid', 1, '2023-09-14 13:12:38', 0, '2023-09-14 13:12:38'),
(4, 'JBTtIt', 'For Company Use', '2023-09-14', 'AWSWS12w3', 'IGST', 18, 18, 36, 36, 300, 336, 0, 'Full Paid', 1, '2023-09-14 13:19:37', 0, '2023-09-14 13:19:37'),
(5, 'JBTtIt', 'For Company Use', '2023-09-14', 'asdasdsad', 'IGST', 9.5, 9.5, 19, 19, 156, 175, 0, 'Full Paid', 1, '2023-09-14 13:28:53', 0, '2023-09-14 13:28:53'),
(6, 'JBTtIt', 'For Company Use', '2023-09-14', 'asdasd', 'IGST', 9.5, 9.5, 19, 19, 156, 175, 0, 'Full Paid', 1, '2023-09-14 13:34:19', 0, '2023-09-14 13:34:19'),
(7, 'JBTtIt', 'For Company Use', '2023-09-14', 'ASAS12', 'IGST', 9.5, 9.5, 19, 19, 156, 175, 0, 'Full Paid', 1, '2023-09-14 13:39:14', 0, '2023-09-14 13:39:14'),
(8, 'JBTtIt', 'For Company Use', '2023-09-14', 'SAS12312', 'IGST', 1, 1, 2, 2, 13, 15, 0, 'Full Paid', 1, '2023-09-14 13:41:59', 0, '2023-09-14 13:41:59'),
(9, 'JBTtIt', 'For Company Use', '2023-09-14', 'fghbjn', 'IGST', 1, 1, 2, 2, 13, 15, 67, 'Full Paid', 1, '2023-09-14 13:54:36', 0, '2023-09-14 13:54:36'),
(10, 'JBTtIt', 'For Company Use', '2023-09-14', 'EW789', 'IGST', 0, 0, 0, 0, 12, 12, 0, 'Full Paid', 1, '2023-09-14 13:57:15', 0, '2023-09-14 13:57:15'),
(11, 'poq-ds', 'For Company Use', '2023-09-14', 'ASAWQW123', 'IGST', 72, 72, 144, 144, 1200, 1344, 0, 'Full Paid', 1, '2023-09-14 14:07:07', 0, '2023-09-14 14:07:07'),
(12, 'xhiJaj', 'For Selling', '2023-09-14', 'QAS3131', 'IGST', 37.5, 37.5, 75, 75, 604, 679, 120, 'Full Credit', 1, '2023-09-14 14:36:52', 0, '2023-09-14 14:36:52'),
(13, 'poq-ds', 'For Selling', '2023-09-14', 'QWAS123', 'IGST', 81.5, 81.5, 163, 163, 1356, 1519, 200, 'Full Paid', 1, '2023-09-14 14:46:22', 0, '2023-09-14 15:30:56'),
(14, 'qD6pLQ', 'For Selling', '2023-09-14', 'ASAS121', 'IGST', 25.5, 25.5, 51, 51, 429, 480, 300, 'Partial Paid', 1, '2023-09-14 14:48:39', 0, '2023-09-14 14:48:39'),
(15, 'qD6pLQ', 'For Selling', '2023-09-14', 'QMN312', 'IGST', 46, 46, 92, 92, 475, 567, 0, 'Partial Paid', 1, '2023-09-14 14:53:01', 0, '2023-09-14 14:53:01'),
(18, 'poq-ds', 'For Company Use', '2023-09-14', 'QWQWQW121212', 'IGST', 31.5, 31.5, 63, 63, 420, 483, 600, 'Full Paid', 1, '2023-09-14 15:22:05', 0, '2023-09-14 15:22:05'),
(20, 'poq-ds', 'For Company Use', '2023-09-14', '12wdasdasd', 'IGST', 800, 800, 1600, 1600, 13332, 14932, 0, 'Full Paid', 1, '2023-09-14 15:38:32', 0, '2023-09-16 05:49:44'),
(21, 'qD6pLP', 'For Selling', '2023-09-14', 'G123QWG', 'CGST/SGST', 33, 33, 66, 66, 444, 510, 800, 'Full Paid', 1, '2023-09-14 15:49:14', 0, '2023-09-14 16:02:36'),
(22, 'JBTtIt', 'For Company Use', '2023-09-14', 'ASAS1222', 'IGST', 80.5, 80.5, 161, 161, 1344, 1505, 122, 'Full Paid', 1, '2023-09-14 16:04:07', 0, '2023-09-14 16:06:41'),
(23, 'xhiJaj', 'For Selling', '2023-09-14', 'ASAS1231312', 'IGST', 103, 103, 206, 206, 736, 942, 80, 'Full Paid', 1, '2023-09-14 16:31:03', 0, '2023-09-14 16:31:47'),
(24, 'qD6pLP', 'For Company Use', '2023-09-14', 'ASA1212', 'CGST/SGST', 300, 300, 600, 600, 3016, 3616, 50, 'Full Paid', 1, '2023-09-14 16:33:25', 0, '2023-09-14 16:34:45'),
(25, 'JBTtIt', 'For Selling', '2023-09-14', 'A111111123', 'IGST', 286.5, 286.5, 573, 573, 2944, 3517, 0, 'Full Paid', 1, '2023-09-14 16:47:51', 0, '2023-09-14 16:47:51'),
(26, 'JBTtIt', 'For Company Use', '2023-09-16', 'GAH61AH61212G', 'IGST', 325, 325, 650, 650, 8108, 8758, 0, 'Full Paid', 1, '2023-09-16 06:28:36', 0, '2023-09-16 06:28:36'),
(27, 'qD6pLP', 'For Selling', '2023-09-01', 'GA567665', 'CGST/SGST', 52, 52, 104, 104, 3217, 3321, 0, 'Full Paid', 1, '2023-09-16 06:44:38', 0, '2023-09-16 06:45:13'),
(28, 'poq-ds', 'For Selling', '2023-08-28', 'ASQ123222', 'IGST', 146.5, 146.5, 293, 293, 1464, 1757, 0, 'Full Paid', 1, '2023-09-16 07:09:31', 0, '2023-09-16 07:09:55'),
(29, 'qD6pLP', 'For Selling', '2023-09-16', 'zadasdasd123', 'CGST/SGST', 102, 102, 204, 204, 1728, 1932, 0, 'Full Paid', 1, '2023-09-16 07:27:12', 0, '2023-09-16 07:27:12'),
(30, 'qD6pLP', 'For Selling', '2023-09-16', 'ASASAQW123123', 'CGST/SGST', 10.5, 10.5, 21, 21, 140, 161, 0, 'Full Paid', 1, '2023-09-16 07:51:53', 0, '2023-09-16 07:51:53'),
(31, 'JBTtIt', 'For Selling', '2023-09-16', 'ASASASASAS', 'IGST', 5.5, 5.5, 11, 11, 100, 111, 0, 'Full Paid', 1, '2023-09-16 08:01:41', 0, '2023-09-16 08:01:41'),
(32, 'qD6pLP', 'For Selling', '2023-09-16', 'LLKJJ12312', 'CGST/SGST', 143.5, 143.5, 287, 287, 3756, 4043, 0, 'Full Paid', 1, '2023-09-16 08:10:04', 0, '2023-09-16 08:10:04'),
(33, 'qD6pLP', 'For Selling', '2023-09-16', 'ASA123123', 'CGST/SGST', 130.5, 130.5, 261, 261, 2164, 2425, 0, 'Full Paid', 1, '2023-09-16 08:37:32', 0, '2023-09-16 08:37:32'),
(34, 'qD6pLP', 'For Selling', '2023-09-16', 'ASAS1221', 'CGST/SGST', 7.5, 7.5, 15, 15, 128, 143, 0, 'Full Paid', 1, '2023-09-16 09:04:58', 0, '2023-09-16 09:04:58'),
(35, 'qD6pLP', 'For Company Use', '2023-09-16', 'AAAAAAAAAAQQ', 'CGST/SGST', 66, 66, 132, 132, 1200, 1332, 0, 'Partial Paid', 1, '2023-09-16 19:25:05', 0, '2023-09-16 19:29:31'),
(36, 'xhiJaj', 'For Company Use', '2023-09-29', 'sdfsdfsdfsdfsdf', 'IGST', 4, 4, 8, 8, 30, 38, 0, 'Full Paid', 1, '2023-09-29 14:24:32', 0, '2023-09-29 14:24:32');

-- --------------------------------------------------------

--
-- Table structure for table `vendor`
--

CREATE TABLE `vendor` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vendor_id` varchar(10) NOT NULL,
  `vendor_name` varchar(100) NOT NULL,
  `address` varchar(150) NOT NULL,
  `gst_number` varchar(30) NOT NULL,
  `pan_card` varchar(10) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_by` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(11) NOT NULL,
  `date_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vendor`
--

INSERT INTO `vendor` (`id`, `vendor_id`, `vendor_name`, `address`, `gst_number`, `pan_card`, `mobile`, `email`, `created_by`, `date_created`, `updated_by`, `date_updated`) VALUES
(21, '1oNtX_', 'Hind Gurjana Veg Restaurant', 'Ramawadh Nagar Phase 2 Banpokhar Mandir Road GKP', 'KJ1111111111QWADDS', 'HYTP12SS90', '9794978416', 'ashutoshsingh5192344@gmail.com', 1, '2023-09-16 09:40:24', 1, '2023-09-28 15:50:31'),
(18, 'eDpOwC', 'dfgf', 'gdfgd', 'dfgdf', 'fgdf123127', '0', '', 1, '2023-09-16 03:59:43', 1, '2023-09-16 03:59:43'),
(11, 'IP5Hgo', 'asda', 'sdsad', 'adasd', 'asda', '123123', 'dsdsadasd', 1, '2023-09-09 14:21:32', 1, '2023-09-09 14:21:32'),
(5, 'JBTtIt', 'Hind Gurjana Veg Restaurantsa', 'Ramawadh Nagar Phase 2 Banpokhar Mandir Road Gorakhpur', 'KJHS90JA1GFASNMZO', 'HYTP12290A', '3333333339', 'ashutoshsingh5192344@gmail.com', 1, '2023-08-26 12:17:59', 1, '2023-09-09 14:15:49'),
(19, 'kS6_jV', 'asd', 'Ramawadh Nagar Phase 2 Banpokhar Mandir Road GKP', 'l', 'ff33333333', '9794978416', 'ashutoshsingh5192344@gmail.com', 1, '2023-09-16 04:09:56', 1, '2023-09-16 09:32:30'),
(13, 'm1WzzI', 'awdsa', 'dasda', 'dasda', 'sdasd', '123', 'asdasda', 1, '2023-09-09 14:29:25', 1, '2023-09-09 14:29:25'),
(17, 'oePL-a', 'sdasdasa', 'dasdasd', 'asdasdas', 'dsadasdasd', '1231231231', '', 1, '2023-09-16 03:52:22', 1, '2023-09-16 03:52:22'),
(4, 'poq-ds', 'Desi Dabha', '417 F Azad Nagar, Rustampur Gorakhpur', 'K12S90JAJ999JGFASNMZO', 'HYTPW0490A', '9794978414', 'acutush@3ds.com', 1, '2023-08-26 05:07:51', 1, '2023-09-03 06:17:18'),
(1, 'qD6pLP', 'Honda Motor Services', 'Ramawadh Nagar Phase 2, Banpokhar Mandir Road Gorakhpur', '18HS90JAJ999JGFASNMZO', 'HYTPS0490A', '9794978416', 'ashutoshsingh5192344@gmail.com', 1, '2023-08-20 08:24:36', 1, '2023-09-03 02:23:17'),
(3, 'qD6pLQ', 'Hind Gurjana Veg Restaur an', 'Ramawadh Nagar Phase 2 Banpokhar Mandir Road GKP', 'KJHS90JAJ91QWADDS', 'FSAPS0490A', '9794978417', 'ashutoshsingh5192344@gmail.com', 1, '2023-08-20 08:26:22', 1, '2023-09-03 02:23:47'),
(20, 'WOrTR4', 'asdasdas', 'asdasdsdasdas', 'asdasd', 'asdasdasas', '1231231232', '', 1, '2023-09-16 04:15:52', 1, '2023-09-16 04:19:26'),
(14, 'xhiJaj', 'Hind Gurjana Veg Restaurant', 'Ramawadh Nagar Phase 2 Banpokhar Mandir Road GKP', 'KJHS90JAJ91QWADDS234', 'HYTPS1490A', '969573845', 'ashutoshsingh5192344@gmail.com', 1, '2023-09-09 14:33:04', 1, '2023-09-09 14:33:04'),
(15, 'YwG_G_', 'sadasasd', 'asda', 'dads', 'asdasasda', '', 'ashutoshsingh5192344@gmail.com', 1, '2023-09-09 14:34:18', 1, '2023-09-09 14:34:18'),
(16, 'ZjGgUv', 'dsfsdfsdf', 'sdfsdfsdf', 'sdfsdf', 'sdfsdfsdfe', '9443434343', 'dfgdfg', 1, '2023-09-09 14:35:27', 1, '2023-09-09 14:35:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`email`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `mobile` (`mobile`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `customer_id` (`customer_id`);

--
-- Indexes for table `expense`
--
ALTER TABLE `expense`
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `expenseId` (`expense_id`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `invoice_number` (`invoice_number`),
  ADD UNIQUE KEY `shipping_address` (`shipping_address`,`billing_address`),
  ADD KEY `FK_customer.customer_id__invoice.billing_address` (`billing_address`);

--
-- Indexes for table `payment_against_puchase`
--
ALTER TABLE `payment_against_puchase`
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_payment_against_puchase__purchase` (`invoice_number`) USING BTREE;

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `product_name` (`product_name`);

--
-- Indexes for table `products_against_invoice`
--
ALTER TABLE `products_against_invoice`
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_products_against_invoice__invoice` (`invoice_number`),
  ADD KEY `FK_products_against_invoice__products` (`product_name`);

--
-- Indexes for table `products_against_purchase`
--
ALTER TABLE `products_against_purchase`
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_products_against_purchase__purchase` (`invoice_number`),
  ADD KEY `FK_products_against_purchase__products` (`product_name`);

--
-- Indexes for table `purchase`
--
ALTER TABLE `purchase`
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `invoice_number` (`invoice_number`),
  ADD KEY `FK_purchase_vendor` (`sold_by`) USING BTREE;

--
-- Indexes for table `vendor`
--
ALTER TABLE `vendor`
  ADD PRIMARY KEY (`vendor_id`),
  ADD UNIQUE KEY `gst_number` (`gst_number`),
  ADD UNIQUE KEY `pan_card` (`pan_card`),
  ADD UNIQUE KEY `id` (`id`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `expense`
--
ALTER TABLE `expense`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payment_against_puchase`
--
ALTER TABLE `payment_against_puchase`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `products_against_invoice`
--
ALTER TABLE `products_against_invoice`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products_against_purchase`
--
ALTER TABLE `products_against_purchase`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `purchase`
--
ALTER TABLE `purchase`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `vendor`
--
ALTER TABLE `vendor`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `FK_customer.customer_id__invoice.billing_address` FOREIGN KEY (`billing_address`) REFERENCES `customers` (`customer_id`),
  ADD CONSTRAINT `FK_customer.customer_id__invoice.shipping_address` FOREIGN KEY (`shipping_address`) REFERENCES `customers` (`customer_id`);

--
-- Constraints for table `payment_against_puchase`
--
ALTER TABLE `payment_against_puchase`
  ADD CONSTRAINT `FK_Purchase_Payment_History_Purchase` FOREIGN KEY (`invoice_number`) REFERENCES `purchase` (`invoice_number`);

--
-- Constraints for table `products_against_invoice`
--
ALTER TABLE `products_against_invoice`
  ADD CONSTRAINT `FK_products_against_invoice__invoice` FOREIGN KEY (`invoice_number`) REFERENCES `invoice` (`invoice_number`),
  ADD CONSTRAINT `FK_products_against_invoice__products` FOREIGN KEY (`product_name`) REFERENCES `products` (`product_name`);

--
-- Constraints for table `products_against_purchase`
--
ALTER TABLE `products_against_purchase`
  ADD CONSTRAINT `FK_products_against_purchase__products` FOREIGN KEY (`product_name`) REFERENCES `products` (`product_name`),
  ADD CONSTRAINT `FK_products_against_purchase__purchase` FOREIGN KEY (`invoice_number`) REFERENCES `purchase` (`invoice_number`);

--
-- Constraints for table `purchase`
--
ALTER TABLE `purchase`
  ADD CONSTRAINT `FK_AddPurchase_Vendor` FOREIGN KEY (`sold_by`) REFERENCES `vendor` (`vendor_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
