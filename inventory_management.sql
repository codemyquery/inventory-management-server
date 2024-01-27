-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 27, 2024 at 06:52 PM
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
(1, 'aynqSi', 'Singh', '', '9794978416', 'ASARRRR4556GH', 'Assam (18)', 'Assam (18)'),
(4, 'iJz-nH', 'Ashtosh', '', '9794978416', 'GHGTY5677', 'Assam (18)', 'Assa'),
(5, 'X_KBgY', 'Jhon Samuel', '', '9794978416', 'ASARRRR4556GH', 'Assam (18)', 'Assam (18)'),
(6, 'iaJ8UA', 'Suryansh Singh', 'Ramawadh Nagar Phase 2, Banpokhar Mandir Road Gorakhpur', '9794978416', '18AATYR4556GH', 'Assam (18)', 'Assam (18)');

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
(1, '51rGet', '2024-01-27', 'asdasdas', 21312, 'CASH', 'asdasdas', 1, '0000-00-00 00:00:00', 0, '2024-01-27 10:49:20'),
(2, 'i_JsD-', '2024-01-27', 'asdasd', 111, 'BANK TRANSFER', '', 1, '0000-00-00 00:00:00', 0, '2024-01-27 10:49:28'),
(3, 'WgJig0', '2024-01-27', 'asdasd', 23121, 'BANK TRANSFER', 'asdasdasd', 1, '0000-00-00 00:00:00', 0, '2024-01-27 12:57:28');

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
(3, 'Regular', 'BISS/2023-2024/2', '2024-01-27', 'DTDC', 150, 'UP 52 DP7313', '2024-01-27', 'Gorakhpur', 96.8, 5.81, 5.81, 0, 108.42, 96.8, 13.2, 'iJz-nH', 'iJz-nH', 1, '2024-01-27 14:41:25', NULL, '2024-01-27 14:41:25'),
(4, 'Regular', 'BISS/2023-2024/3', '2024-01-27', 'BLUE DART', 120, 'UP 52 DP7313', '2024-01-27', 'ASAS123 123', 99, 5.94, 5.94, 0, 110.88, 99, 11, 'X_KBgY', 'X_KBgY', 1, '2024-01-27 16:37:01', NULL, '2024-01-27 16:37:01'),
(5, 'Regular', 'BISS/2023-2024/4', '2024-01-27', 'BLUE DART', 120, 'UP 52 DP7313', '2024-01-27', 'ASAS123 123', 891, 53.46, 53.46, 0, 997.92, 891, 99, 'iaJ8UA', 'iaJ8UA', 1, '2024-01-27 17:16:03', NULL, '2024-01-27 17:16:03');

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
(1, 'GAH656123Q', 1120, '2024-01-27', 1, '2024-01-27 12:05:43');

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
  `date_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `hsn_sac`, `per_piece_price`, `quantity`, `taxrate`, `created_by`, `date_created`, `updated_by`, `date_updated`) VALUES
(1, 'Keyboard', 'SDA123AA', 100, 10, 12, 1, '2024-01-27 12:05:43', NULL, '2024-01-27 12:05:43');

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
(3, 'BISS/2023-2024/2', 'Keyboard', 1, 110, 12, 0, 96.8, 6, 6, 0, 108.42),
(4, 'BISS/2023-2024/3', 'Keyboard', 1, 110, 10, 0, 99, 6, 6, 0, 110.88),
(5, 'BISS/2023-2024/4', 'Keyboard', 9, 110, 10, 90, 891, 6, 6, 0, 997.92);

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
(1, 'GAH656123Q', 'Keyboard', 100, 10, 12, 0, 0, 12, 120, 1120, NULL, NULL, 1, '2024-01-27 12:05:43', NULL, NULL);

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
(1, '8k80Ix', 'For Company Use', '2024-01-27', 'GAH656123Q', 'IGST', 60, 60, 120, 120, 1000, 1120, 200, 'Full Paid', 1, '2024-01-27 12:05:43', 0, '2024-01-27 12:05:43');

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
(1, '8k80Ix', 'Hind Gurjana Veg Restaurant', 'Ramawadh Nagar Phase 2 Banpokhar Mandir Road GKP', 'KJHS90JAJ999JGFASNMZO', 'HYTPS0490A', '9794978415', 'ashutoshsingh5192344@gmail.com', 1, '2024-01-27 10:51:01', 1, '2024-01-27 10:51:01'),
(3, 'N0slMm', 'Honda Motor Services', 'Post Shawreji Tehsil Salempur', 'KJHS90JAJ91QWADDS', 'HYTP122AAA', '9999678965', 'ashutoshsingh5192344@gmail.com', 1, '2024-01-27 12:59:16', 1, '2024-01-27 13:02:09');

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `expense`
--
ALTER TABLE `expense`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payment_against_puchase`
--
ALTER TABLE `payment_against_puchase`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products_against_invoice`
--
ALTER TABLE `products_against_invoice`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products_against_purchase`
--
ALTER TABLE `products_against_purchase`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `purchase`
--
ALTER TABLE `purchase`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vendor`
--
ALTER TABLE `vendor`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
