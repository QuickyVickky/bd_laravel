-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2021 at 03:29 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bigdaddy`
--

-- --------------------------------------------------------

--
-- Table structure for table `access_token`
--

CREATE TABLE `access_token` (
  `id` bigint(20) NOT NULL,
  `usertype` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1-customer, 2- driver',
  `devicetype` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1-web, 2-android, 3-ios',
  `user_id` bigint(20) DEFAULT NULL,
  `token` varchar(2000) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `count_hits` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `access_token`
--

INSERT INTO `access_token` (`id`, `usertype`, `devicetype`, `user_id`, `token`, `created_at`, `updated_at`, `count_hits`) VALUES
(268, 1, 2, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.QmlnZGFkZHlfYXBp.jy_2htoYAvJrFb1Xxom-e9gBYOF7Mydjmo0iLGgxZ4Q', '2021-05-03 10:44:37', '2021-05-06 10:41:56', 17),
(267, 2, 2, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.QmlnZGFkZHlfYXBp.fKTQmF7gVv-0GWxaGlA4bkPNGM2MSpOZcI2GAbLfcas', '2021-05-01 13:01:15', '2021-05-07 10:03:36', 57);

-- --------------------------------------------------------

--
-- Table structure for table `acc_accounts_or_banks`
--

CREATE TABLE `acc_accounts_or_banks` (
  `id` int(11) NOT NULL,
  `account_category_id` int(11) NOT NULL DEFAULT 0 COMMENT 'account_category_id',
  `name` varchar(255) NOT NULL COMMENT 'required',
  `account_id` varchar(55) DEFAULT NULL COMMENT 'optional',
  `description` text DEFAULT NULL COMMENT 'optional',
  `is_active` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-active,1-deactive, 2- deleted',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `admin_id` int(11) NOT NULL DEFAULT 0 COMMENT 'added by admin id',
  `is_editable` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-no,yes-1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `acc_accounts_or_banks`
--

INSERT INTO `acc_accounts_or_banks` (`id`, `account_category_id`, `name`, `account_id`, `description`, `is_active`, `created_at`, `updated_at`, `admin_id`, `is_editable`) VALUES
(1, 3, 'Cash on Hands', NULL, '', 0, '2021-04-01 11:24:57', '2021-04-01 11:25:01', 0, 0),
(15, 3, 'AXIS BANK', NULL, 'patiya branch', 0, '2021-04-01 12:08:49', '2021-04-06 17:59:18', 11, 1);

-- --------------------------------------------------------

--
-- Table structure for table `acc_account_category`
--

CREATE TABLE `acc_account_category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `details` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-active,1-deactive, 2- deleted	',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `level` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-main category, 1- subcategory',
  `path_to` int(11) NOT NULL DEFAULT 0,
  `is_editable` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-no,yes-1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `acc_account_category`
--

INSERT INTO `acc_account_category` (`id`, `name`, `details`, `is_active`, `created_at`, `updated_at`, `level`, `path_to`, `is_editable`) VALUES
(1, 'Assets', 'Assets', 0, '2021-01-25 13:40:44', '2021-01-25 13:40:44', 0, 0, 0),
(2, 'Liabilities & Credit Cards', 'Liabilities & Credit Cards', 1, '2021-01-25 13:40:44', '2021-01-25 13:40:44', 0, 0, 0),
(3, 'Cash and Bank', 'Cash and Bank', 0, '2021-01-25 00:00:00', '2021-01-25 13:41:45', 1, 1, 0),
(4, 'Credit Card', 'Credit Card', 1, '2021-01-25 00:00:00', '2021-01-25 13:41:45', 1, 2, 0),
(5, 'Money in Transit', 'Money in Transit', 1, '2021-01-25 00:00:00', '2021-01-25 13:41:45', 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `acc_transactions`
--

CREATE TABLE `acc_transactions` (
  `id` bigint(20) NOT NULL,
  `transaction_uuid` varchar(36) NOT NULL COMMENT 'transaction unique id',
  `amount` decimal(13,2) NOT NULL DEFAULT 0.00,
  `transaction_date` datetime DEFAULT NULL COMMENT 'transaction_datetime',
  `description` varchar(1000) DEFAULT NULL,
  `transaction_type` varchar(2) NOT NULL DEFAULT 'Dr' COMMENT '	Dr-Debit/Expense,Cr-Credit/Profit	',
  `admin_id` int(11) NOT NULL DEFAULT 0 COMMENT '	0-auto else added by adminid',
  `anybillno` varchar(25) DEFAULT NULL,
  `anybillno_document` varchar(100) DEFAULT NULL,
  `order_id` bigint(20) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-active,1-deactive, 2- deleted',
  `vendor_id` int(11) DEFAULT NULL COMMENT 'optional',
  `user_id` int(11) DEFAULT NULL COMMENT 'customer id',
  `invoice_id` int(11) DEFAULT NULL COMMENT 'invoice_id',
  `accountid_from` int(11) NOT NULL COMMENT 'required accounts_or_banks_id_from',
  `accountid_transferredto` int(11) DEFAULT NULL COMMENT 'optional accounts_or_banks_id_transferredto',
  `transaction_subcategory_id` int(11) NOT NULL COMMENT 'required',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `is_editable` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0-no,1-yes',
  `payment_method` varchar(11) NOT NULL DEFAULT 'CS',
  `notes` varchar(255) DEFAULT NULL,
  `is_reviewed` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-no, 1-yes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `acc_transactions`
--

INSERT INTO `acc_transactions` (`id`, `transaction_uuid`, `amount`, `transaction_date`, `description`, `transaction_type`, `admin_id`, `anybillno`, `anybillno_document`, `order_id`, `is_active`, `vendor_id`, `user_id`, `invoice_id`, `accountid_from`, `accountid_transferredto`, `transaction_subcategory_id`, `created_at`, `updated_at`, `is_editable`, `payment_method`, `notes`, `is_reviewed`) VALUES
(1, '9UVHZ40W0Y606b05b0cebbb3266', '1900.04', '2021-04-07 00:00:00', 'Invoice Payment From #47', 'Cr', 11, 'Invoice #47', NULL, NULL, 0, NULL, 122, 39, 15, NULL, 22, '2021-04-05 18:12:24', '2021-04-05 18:12:24', 1, 'CS', 'Invoice Payment', 0),
(2, '7EJZ9OCBCZ606ea933e70d93218', '2378.00', '2021-04-08 00:00:00', 'Razorpay Payment From #1023', 'Cr', 0, NULL, NULL, 108, 0, NULL, 19, NULL, 15, NULL, 22, '2021-04-08 12:26:51', '2021-04-08 12:26:51', 0, 'CARD', 'Razorpay Payment', 0),
(3, 'Q6R9PF5IJN606eb80db3a864771', '85.80', '2021-04-08 00:00:00', 'Razorpay Payment From #1024', 'Cr', 0, NULL, NULL, 111, 0, NULL, 1, NULL, 15, NULL, 22, '2021-04-08 13:28:38', '2021-04-08 13:30:13', 0, 'CARD', 'Razorpay Payment', 0),
(4, 'Q5Z4NN7LGT606eb9ea35b754159', '616.00', '2021-04-08 00:00:00', 'Razorpay Payment From #1026', 'Cr', 0, NULL, NULL, 112, 0, NULL, 1, NULL, 15, NULL, 22, '2021-04-08 13:38:10', '2021-04-08 13:38:10', 0, 'CARD', 'Razorpay Payment', 0),
(5, 'X2C2RBRJMO606ecbc3358b14753', '671.00', '2021-04-08 00:00:00', 'Razorpay Payment From #1028', 'Cr', 0, NULL, NULL, 114, 0, NULL, 1, NULL, 15, NULL, 22, '2021-04-08 14:54:19', '2021-04-08 14:54:19', 0, 'CARD', 'Razorpay Payment', 0),
(6, 'G3Q1BF1QJV606ecc70ac30b7919', '616.00', '2021-04-08 00:00:00', 'Razorpay Payment From #1029', 'Cr', 0, NULL, NULL, 115, 0, NULL, 1, NULL, 15, NULL, 22, '2021-04-08 14:55:22', '2021-04-08 14:57:12', 0, 'CARD', 'Razorpay Payment', 0),
(7, 'S8MXWFVF1I606ed4ed0f0239430', '616.00', '2021-04-08 00:00:00', 'Razorpay Payment From #1031', 'Cr', 0, NULL, NULL, 106, 0, NULL, 19, NULL, 15, NULL, 22, '2021-04-08 15:33:25', '2021-04-08 15:33:25', 0, 'CARD', 'Razorpay Payment', 0),
(8, '9AWO4OKBD5606ed5f944ee44989', '616.00', '2021-04-08 00:00:00', 'Razorpay Payment From #1032', 'Cr', 0, NULL, NULL, 117, 0, NULL, 1, NULL, 15, NULL, 22, '2021-04-08 15:37:53', '2021-04-08 15:37:53', 0, 'CARD', 'Razorpay Payment', 0),
(9, '3625H0ZLG9606ed682015e82968', '616.00', '2021-04-08 00:00:00', 'Razorpay Payment From #1033', 'Cr', 0, NULL, NULL, 118, 0, NULL, 1, NULL, 15, NULL, 22, '2021-04-08 15:40:10', '2021-04-08 15:40:10', 0, 'CARD', 'Razorpay Payment', 0),
(10, '0CW7A4U1G0606eea3475f099050', '616.00', '2021-04-08 00:00:00', 'Razorpay Payment From #1038', 'Cr', 0, NULL, NULL, 123, 0, NULL, 1, NULL, 15, NULL, 22, '2021-04-08 17:04:12', '2021-04-08 17:04:12', 0, 'CARD', 'Razorpay Payment', 0),
(11, 'PY87DNSEB9606eea9314c796092', '616.00', '2021-04-08 00:00:00', 'Razorpay Payment From #1039', 'Cr', 0, NULL, NULL, 124, 0, NULL, 1, NULL, 15, NULL, 22, '2021-04-08 17:05:47', '2021-04-08 17:05:47', 0, 'CARD', 'Razorpay Payment', 0),
(12, '4KUXIE5QOA606eecc61aaf42062', '168.00', '2021-04-08 00:00:00', 'Razorpay Payment From #1040', 'Cr', 0, NULL, NULL, 126, 0, NULL, 1, NULL, 15, NULL, 22, '2021-04-08 17:15:10', '2021-04-08 17:15:10', 0, 'CARD', 'Razorpay Payment', 0),
(13, '0ISY96L57P606eee493c8f99775', '616.00', '2021-04-08 00:00:00', 'Razorpay Payment From #1041', 'Cr', 0, NULL, NULL, 127, 0, NULL, 1, NULL, 15, NULL, 22, '2021-04-08 17:21:37', '2021-04-08 17:21:37', 0, 'CARD', 'Razorpay Payment', 0),
(14, 'N3BOK4ZC8Y606ef76804ab78409', '66.00', '2021-04-08 00:00:00', 'Razorpay Payment From #1042', 'Cr', 0, NULL, NULL, 128, 0, NULL, 1, NULL, 15, NULL, 22, '2021-04-08 18:00:32', '2021-04-08 18:00:32', 0, 'CARD', 'Razorpay Payment', 0),
(15, 'J0279HJM3M606ef7bd78d8b7542', '616.00', '2021-04-08 00:00:00', 'Razorpay Payment From #1043', 'Cr', 0, NULL, NULL, 129, 0, NULL, 1, NULL, 15, NULL, 22, '2021-04-08 18:01:57', '2021-04-08 18:01:57', 0, 'CARD', 'Razorpay Payment', 0),
(16, 'GF96XY5XDF606efa0cf0e2d7963', '3355.00', '2021-04-08 00:00:00', 'Razorpay Payment From #1044', 'Cr', 0, NULL, NULL, 130, 0, NULL, 1, NULL, 15, NULL, 22, '2021-04-08 18:11:48', '2021-04-08 18:11:48', 0, 'CARD', 'Razorpay Payment', 0),
(17, 'XY30KG89G4606fd7ad6e7a67462', '858.00', '2021-04-09 00:00:00', 'Razorpay Payment From #1045', 'Cr', 0, NULL, NULL, 132, 0, NULL, 1, NULL, 15, NULL, 22, '2021-04-09 09:57:25', '2021-04-09 09:57:25', 0, 'CARD', 'Razorpay Payment', 0),
(18, 'CT7OAZLGZ8606fefda55e565377', '360.00', '2021-04-09 00:00:00', 'Razorpay Payment From #1046', 'Cr', 0, NULL, NULL, 142, 0, NULL, 1, NULL, 15, NULL, 22, '2021-04-09 11:40:34', '2021-04-09 11:40:34', 0, 'CARD', 'Razorpay Payment', 0),
(19, 'YZ4SAY1A31606ff186808d62691', '400.00', '2021-04-09 00:00:00', 'Razorpay Payment From #1047', 'Cr', 0, NULL, NULL, 144, 0, NULL, 1, NULL, 15, NULL, 22, '2021-04-09 11:47:42', '2021-04-09 11:47:42', 0, 'CARD', 'Razorpay Payment', 0),
(20, 'OQC2844YOA606ff97cd9e7f2522', '1936.00', '2021-04-09 00:00:00', 'Razorpay Payment From #1048', 'Cr', 0, NULL, NULL, 147, 0, NULL, 1, NULL, 15, NULL, 22, '2021-04-09 12:21:40', '2021-04-09 12:21:40', 0, 'CARD', 'Razorpay Payment', 0),
(21, 'ILNFFAWT3K606ffb67c886f3544', '242.00', '2021-04-09 00:00:00', 'Razorpay Payment From #1049', 'Cr', 0, NULL, NULL, 149, 0, NULL, 1, NULL, 15, NULL, 22, '2021-04-09 12:29:51', '2021-04-09 12:29:51', 0, 'CARD', 'Razorpay Payment', 0),
(22, '4XAURJFD8A606ffd3cc10ae4049', '2860.00', '2021-04-09 00:00:00', 'Razorpay Payment From #1050', 'Cr', 0, NULL, NULL, 148, 0, NULL, 1, NULL, 15, NULL, 22, '2021-04-09 12:37:40', '2021-04-09 12:37:40', 0, 'CARD', 'Razorpay Payment', 0),
(23, '15JV0S55WD60701073985db4976', '684.00', '2021-04-09 00:00:00', 'Razorpay Payment From #1038', 'Cr', 0, NULL, NULL, 151, 0, NULL, 1, NULL, 15, NULL, 22, '2021-04-09 13:59:39', '2021-04-09 13:59:39', 0, 'CARD', 'Razorpay Payment', 0),
(24, '8K61793T4P60701168d8daa6682', '770.00', '2021-04-09 00:00:00', 'Razorpay Payment From #1039', 'Cr', 0, NULL, NULL, 152, 0, NULL, 1, NULL, 15, NULL, 22, '2021-04-09 14:03:44', '2021-04-09 14:03:44', 0, 'CARD', 'Razorpay Payment', 0),
(25, '9F0O0MDOS06070201e8e71c4016', '840.00', '2021-04-09 00:00:00', 'Razorpay Payment From #1040', 'Cr', 0, NULL, NULL, 153, 0, NULL, 1, NULL, 15, NULL, 22, '2021-04-09 15:06:30', '2021-04-09 15:06:30', 0, 'CARD', 'Razorpay Payment', 0),
(26, 'EZFZ2V4PT4607033d10ad5f7117', '616.00', '2021-04-09 00:00:00', 'Razorpay Payment From #1041', 'Cr', 0, NULL, NULL, 154, 0, NULL, 1, NULL, 15, NULL, 22, '2021-04-09 16:30:33', '2021-04-09 16:30:33', 0, 'CARD', 'Razorpay Payment', 0),
(27, 'R8GSX6XG7Q6070351c173bb2640', '649.00', '2021-04-09 00:00:00', 'Razorpay Payment From #1042', 'Cr', 0, NULL, NULL, 157, 0, NULL, 1, NULL, 15, NULL, 22, '2021-04-09 16:36:04', '2021-04-09 16:36:04', 0, 'CARD', 'Razorpay Payment', 0),
(28, 'KFAHA1T77I6073f6ee115cb6491', '596.00', '2021-04-12 00:00:00', 'Razorpay Payment', 'Cr', 0, NULL, NULL, NULL, 0, NULL, 1, NULL, 15, NULL, 22, '2021-04-12 12:59:50', '2021-04-12 12:59:50', 0, 'CARD', 'Razorpay Payment Wallet', 0),
(29, 'JKY0ODSBTJ6073fd6b07c3f3293', '523.00', '2021-04-12 00:00:00', 'Razorpay Payment', 'Cr', 0, NULL, NULL, NULL, 0, NULL, 1, NULL, 15, NULL, 22, '2021-04-12 13:27:31', '2021-04-12 13:27:31', 0, 'CARD', 'Razorpay Payment Wallet', 0),
(30, 'ZI7YINL4LX6073fe451bfe12885', '152.00', '2021-04-12 00:00:00', 'Razorpay Payment', 'Cr', 0, NULL, NULL, NULL, 0, NULL, 1, NULL, 15, NULL, 22, '2021-04-12 13:31:09', '2021-04-12 13:31:09', 0, 'CARD', 'Razorpay Payment Wallet', 0),
(31, '4XUBPTX3GJ6073fed0882f02727', '269.00', '2021-04-12 00:00:00', 'Razorpay Payment', 'Cr', 0, NULL, NULL, NULL, 0, NULL, 1, NULL, 15, NULL, 22, '2021-04-12 13:33:28', '2021-04-12 13:33:28', 0, 'CARD', 'Razorpay Payment Wallet', 0),
(32, 'ZC0LZMK1LE6073ff5bb8c8f5924', '458.00', '2021-04-12 00:00:00', 'Razorpay Payment', 'Cr', 0, NULL, NULL, NULL, 0, NULL, 1, NULL, 15, NULL, 22, '2021-04-12 13:35:47', '2021-04-12 13:35:47', 0, 'CARD', 'Razorpay Payment Wallet', 0),
(33, '4MZN4RELXV60797e3b808f76974', '616.00', '2021-04-16 00:00:00', 'Razorpay Payment', 'Cr', 0, NULL, NULL, NULL, 0, NULL, 1, NULL, 15, NULL, 22, '2021-04-16 17:38:27', '2021-04-16 17:38:27', 0, 'CARD', 'Razorpay Payment Wallet', 0),
(34, 'WLXREBNQZ160797e4829c812682', '616.00', '2021-04-16 00:00:00', 'Razorpay Payment', 'Cr', 0, NULL, NULL, NULL, 0, NULL, 1, NULL, 15, NULL, 22, '2021-04-16 17:38:40', '2021-04-16 17:38:40', 0, 'CARD', 'Razorpay Payment Wallet', 0),
(35, '1MET0TTH9G6079820dbdc609813', '616.00', '2021-04-16 00:00:00', 'Razorpay Payment', 'Cr', 0, NULL, NULL, NULL, 0, NULL, 1, NULL, 15, NULL, 22, '2021-04-16 17:54:45', '2021-04-16 17:54:45', 0, 'CARD', 'Razorpay Payment Wallet', 0),
(36, 'SRWRU8FIJC607fc685ef3e44402', '1111.00', '2021-04-21 12:00:11', 'jhg', 'Dr', 11, '56', NULL, NULL, 0, 2, NULL, NULL, 15, NULL, 3, '2021-04-21 12:00:29', '2021-04-21 12:00:45', 1, 'P', NULL, 0),
(37, 'VT4KY6BMIO608a6102730b82169', '-294.00', '2021-04-29 00:00:00', 'Invoice Payment From #51', 'Cr', 11, 'Invoice #51', NULL, NULL, 0, NULL, 122, 43, 15, NULL, 22, '2021-04-29 13:02:18', '2021-04-29 13:02:18', 1, 'CS', 'Invoice Payment', 0),
(38, 'JEE459KY4U608a77147e0c86622', '549.00', '2021-04-29 00:00:00', 'Invoice Payment From #52', 'Cr', 11, 'Invoice #52', NULL, NULL, 0, NULL, 1, 44, 15, NULL, 22, '2021-04-29 14:36:28', '2021-04-29 14:36:28', 1, 'NEFT', 'Invoice Payment', 0),
(39, 'FGUB8UAHOQ60923d8a5b2947928', '1111.00', '2021-05-01 12:08:00', NULL, 'Dr', 8, NULL, NULL, NULL, 0, 2, NULL, NULL, 15, NULL, 3, '2021-05-05 12:09:06', '2021-05-05 12:09:35', 1, 'CHQ', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `acc_transaction_subcategory`
--

CREATE TABLE `acc_transaction_subcategory` (
  `id` int(11) NOT NULL,
  `transaction_type` varchar(2) NOT NULL DEFAULT 'Cr' COMMENT 'transaction_typeid',
  `name` varchar(255) DEFAULT NULL,
  `name2` varchar(255) DEFAULT NULL,
  `details` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-active,1-deactive, 2- deleted',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `is_editable` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-no,yes-1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `acc_transaction_subcategory`
--

INSERT INTO `acc_transaction_subcategory` (`id`, `transaction_type`, `name`, `name2`, `details`, `is_active`, `created_at`, `updated_at`, `is_editable`) VALUES
(1, 'Cr', 'Sales', NULL, 'Sales', 0, '2021-01-18 16:30:04', '2021-01-18 16:30:04', 0),
(2, 'Dr', 'Accounting Fees', NULL, 'Accounting Fees', 0, '2021-01-18 16:30:04', '2021-01-18 16:30:04', 0),
(3, 'Dr', 'Advertising & Promotion', NULL, 'Advertising & Promotion', 0, '2021-01-18 16:30:04', '2021-01-18 16:30:04', 0),
(4, 'Dr', 'Bank Service Charges', NULL, 'Bank Service Charges', 0, '2021-01-18 16:30:04', '2021-01-18 16:30:04', 0),
(5, 'Dr', 'Computer – Hardware', NULL, 'Computer – Hardware', 0, '2021-01-18 16:30:04', '2021-01-18 16:30:04', 0),
(6, 'Dr', 'Computer – Hosting', NULL, 'Computer – Hosting', 0, '2021-01-18 16:30:04', '2021-01-18 16:30:04', 0),
(7, 'Dr', 'Computer – Internet', NULL, 'Computer – Internet', 0, '2021-01-18 16:30:04', '2021-01-18 16:30:04', 0),
(8, 'Dr', 'Computer – Software', NULL, 'Computer – Software', 0, '2021-01-18 16:30:04', '2021-01-18 16:30:04', 0),
(9, 'Dr', 'Depreciation Expense', NULL, 'Depreciation Expense', 0, '2021-01-18 16:30:04', '2021-01-18 16:30:04', 0),
(10, 'Dr', 'Dues & Subscriptions', NULL, 'Dues & Subscriptions', 0, '2021-01-18 16:30:04', '2021-01-18 16:30:04', 0),
(11, 'Dr', 'Equipment Lease or Rental', NULL, 'Equipment Lease or Rental', 0, '2021-01-18 16:30:04', '2021-01-18 16:30:04', 0),
(12, 'Dr', 'Insurance – Vehicles', NULL, 'Insurance – Vehicles', 0, '2021-01-18 16:30:04', '2021-01-18 16:30:04', 0),
(13, 'Dr', 'Interest Expense', NULL, 'Interest Expense', 0, '2021-01-18 16:30:04', '2021-01-18 16:30:04', 0),
(14, 'Dr', 'Loss on Foreign Exchange', NULL, 'Loss on Foreign Exchange', 0, '2021-01-18 16:30:04', '2021-01-18 16:30:04', 0),
(15, 'Dr', 'Meals and Entertainment', NULL, 'Meals and Entertainment', 0, '2021-01-18 16:30:04', '2021-01-18 16:30:04', 0),
(16, 'Dr', 'Office Supplies', NULL, 'Office Supplies', 0, '2021-01-18 16:30:04', '2021-01-18 16:30:04', 0),
(17, 'Dr', 'Payroll – Employee Benefits', NULL, 'Payroll – Employee Benefits', 0, '2021-01-18 16:30:04', '2021-01-18 16:30:04', 0),
(22, 'Cr', 'Invoice Payment', 'Invoice Payment', 'Invoice Payment', 0, '2021-02-05 15:06:45', '2021-02-05 15:06:45', 0),
(23, 'Dr', 'Bill Payment', 'Bill Payment', 'Bill Payment', 0, '2021-02-05 15:07:01', '2021-02-05 15:07:01', 0),
(29, 'Cr', 'Transfer From Bank, Credit Card or Loan', 'Transfer From XXXXXX Bank', 'Transfer From XXXXXX Bank', 0, '2021-03-30 15:31:48', '2021-03-30 15:31:48', 0),
(30, 'Dr', 'Transfer To Bank, Credit Card or Loan', 'Transfer To XXXXXX Bank', 'Transfer To XXXXXX Bank', 0, '2021-03-30 15:31:48', '2021-03-30 15:31:48', 0),
(36, 'Dr', 'Vehicle – Repairs & Maintenance', NULL, NULL, 0, '2021-04-01 17:25:30', '2021-04-01 17:25:30', 0),
(37, 'Dr', 'Vehicle – Fuel', NULL, NULL, 0, '2021-04-01 17:25:42', '2021-04-01 17:25:42', 0),
(38, 'Dr', 'Travel Expense', NULL, NULL, 0, '2021-04-01 17:25:56', '2021-04-01 17:25:56', 0),
(39, 'Dr', 'Studio and Location Costs', NULL, NULL, 0, '2021-04-01 17:26:11', '2021-04-01 17:26:11', 0),
(40, 'Dr', 'Rent Expense', NULL, NULL, 0, '2021-04-01 17:26:31', '2021-04-01 17:26:31', 0),
(41, 'Dr', 'Payroll – Salary & Wages', NULL, NULL, 0, '2021-04-01 17:26:57', '2021-04-01 17:26:57', 0),
(42, 'Dr', 'Payroll Employer Taxes', NULL, NULL, 0, '2021-04-01 17:27:11', '2021-04-01 17:27:11', 0),
(43, 'Dr', 'Payroll – Employer\'s Share of Benefits', NULL, NULL, 0, '2021-04-01 17:27:25', '2021-04-01 17:27:25', 0),
(44, 'Dr', 'Office Expenses', NULL, NULL, 0, '2021-04-01 17:28:20', '2021-04-01 17:28:20', 0),
(45, 'Dr', 'Insurance – Worker\'s Compensation', NULL, NULL, 0, '2021-04-01 17:30:24', '2021-04-01 17:30:24', 0),
(46, 'Dr', 'PayPerParcel Driver', NULL, 'PayPerParcel Driver Account', 0, '2021-04-03 18:14:09', '2021-04-03 18:14:09', 0),
(47, 'Dr', 'PayPerTrip Driver', NULL, 'PayPerTrip Driver', 0, '2021-04-03 18:14:24', '2021-04-03 18:14:24', 0),
(48, 'Dr', 'Refund Expenses', NULL, NULL, 0, '2021-04-05 12:38:26', '2021-04-05 12:38:44', 0),
(49, 'Dr', 'IT Expense', NULL, NULL, 0, '2021-04-05 22:47:27', '2021-04-05 22:47:27', 0),
(50, 'Cr', 'Subscriptions Sales', 'Subscriptions Sales', 'Subscriptions Sales', 0, '2021-04-20 17:47:04', '2021-04-20 17:47:04', 0);

-- --------------------------------------------------------

--
-- Table structure for table `acc_vendors`
--

CREATE TABLE `acc_vendors` (
  `id` int(10) UNSIGNED NOT NULL,
  `fullname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'company name',
  `firstname` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'optional',
  `lastname` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'optional',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'optional',
  `mobile` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'optional',
  `vendor_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'constant vendor_type',
  `vendor_about` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-active,1-deactive, 2- deleted',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `admin_id` int(11) NOT NULL DEFAULT 0 COMMENT 'added by admin id',
  `country` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT 'India',
  `state` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pincode` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `landmark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `acc_vendors`
--

INSERT INTO `acc_vendors` (`id`, `fullname`, `firstname`, `lastname`, `email`, `mobile`, `vendor_type`, `vendor_about`, `is_active`, `created_at`, `updated_at`, `admin_id`, `country`, `state`, `city`, `pincode`, `address`, `landmark`) VALUES
(1, 'Shaikh Ezaz', NULL, NULL, NULL, '6354756267', 'Driver', 'Driver', 0, '2021-04-05 22:51:16', '2021-04-05 22:51:16', 8, 'India', 'Gujarat', 'Surat', '394210', 'SY.No.33Plot 8 Room2 Block 39 Hlaf part Oh South Side Tena-25B, Gali No.2 Limbayat,Pratap Nagar Mithi Khadi, Surat.', NULL),
(2, 'Mohammad Fahad Shaikh', NULL, NULL, NULL, '6355301918', 'Driver', 'Driver', 0, '2021-04-05 22:52:21', '2021-04-05 22:52:21', 8, 'India', 'Gujarat', 'Surat', '395005', '3097, Shah Bhagal, Rander Surat', NULL),
(3, 'Nilay Navnitlal Shah', NULL, NULL, NULL, '8401000419', 'Driver', 'Driver', 0, '2021-04-05 22:53:21', '2021-04-05 22:53:21', 8, 'India', 'Gujarat', 'Surat', '390005', 'dumbhal transport imagica surat', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fullname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(111) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` int(11) NOT NULL DEFAULT 1 COMMENT '0-active,1-deactive, 2- deleted',
  `role` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'S' COMMENT '	A-SuperAdmin/MainAdmin, M-manager, S- staff	',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `about` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active_session` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `assigned_role_management_ids` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notassigned_role_management_ids` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ipaddress` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `fullname`, `email`, `mobile`, `password`, `is_active`, `role`, `created_at`, `updated_at`, `about`, `active_session`, `assigned_role_management_ids`, `notassigned_role_management_ids`, `ipaddress`) VALUES
(5, 'Devrishi', 'devrishi@bigdaddylogistics.com', '9999988880', '$2y$10$THaf272F06hHRYs9ONvspOXPnuTX5KYxcVv39iM6i7A999odA6o5m', 0, 'A', '2021-01-06 17:43:05', '2021-03-27 22:05:11', NULL, NULL, '0', '0', '60.254.92.15'),
(1, 'Admin', 'admin@gmail.com', '9797975898', '$2y$10$7VPNpA0XklWbmAuqPSrZy.H8L4WXoQVbV7TG9dYp5pfll7yPQlHAK', 0, 'A', '2020-11-23 11:32:47', '2021-04-09 17:10:28', NULL, '$2y$10$w/k7h11SuUR97UzKSjbKNucS0isodkkmDgIUv1lz2mP2pWLWbYDBS', '0', '0', '192.168.0.111'),
(6, 'Jay', 'jay@bigdaddylogistics.com', '9714152786', '$2y$10$dK17Tu8hym5/W.W3sh5ow.0baLu5YQiMO5cSyuRsYsvmvqcMeVKje', 0, 'A', '2021-01-06 17:43:05', '2021-04-02 23:36:46', NULL, '$2y$10$2j38xBZHRG2SyXBoALtyueD2BATKIWkk5ws20QtysDRn7qM7dYZk.', '0', '0', '103.232.125.233'),
(9, 'Hardik Patel', 'hardik@bigdaddylogistics.com', '9727098439', '$2y$10$zb/87pCioEbVLMoBEG249.NgkGgYzB2taDpc..MU9JxrsfsDhhQK.', 0, 'S', '2021-01-19 12:11:39', '2021-04-21 13:22:03', NULL, '$2y$10$ZNcSjWp0qkOrmjisZ7tGg.vOzaaDhz1IHTNFEuwFR6Dc6dnKJWWry', '16,17,31,32,33,34,38,1,2,4,66,67,3,5,6,7,46,49,53,8,9,36,37,43,44,45,42,10,11,12,23,24,64,26,27,65,41,25,13,54,39,40', '35,62,78,57,58,60,61,79,14,15,22,72,74,73,75,76,77,69,70,71,80,81,83,84,86,50,56,63,47,48,51,52,55,18,19,28,29,30,20,21', '::1'),
(8, 'Technomads Developer', 'developer@mail.com', '9999988123', '$2y$10$1mYBNWf4cbDvPpxXdN76E.NkgcOxw6VDqoz/jNc5NEb0j2lBay82a', 0, 'A', '2021-01-11 17:43:05', '2021-05-07 16:12:30', NULL, '$2y$10$Bq35WYn2jgxN/IWCoIqVEu0OZs2T/4Zw/k4GjwHISmkYm8c9G8SPW', '0', '0', '::1'),
(10, 'Admin1', 'admin1@gmail.com', '9797975898', '$2y$10$7VPNpA0XklWbmAuqPSrZy.H8L4WXoQVbV7TG9dYp5pfll7yPQlHAK', 0, 'A', '2020-11-23 11:32:47', '2021-03-31 22:32:13', NULL, '$2y$10$YrHlZSSVJuzZlr9gcYHSVOrSR0qQBoeK1w.KISXNCkyvp49.s9iYK', '0', '0', '103.232.125.233'),
(11, 'GB TEST', 'gb@gmail.com', '9797975898', '$2y$10$7VPNpA0XklWbmAuqPSrZy.H8L4WXoQVbV7TG9dYp5pfll7yPQlHAK', 0, 'A', '2020-11-23 11:32:47', '2021-05-04 12:44:34', NULL, '$2y$10$vkxKhHadEyHB9s34.77sH.n3rdOivfEFexwxy43VNBottIdo5TXB.', '0', '0', '::1');

-- --------------------------------------------------------

--
-- Table structure for table `api_logs`
--

CREATE TABLE `api_logs` (
  `id` bigint(20) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `api_logs`
--

INSERT INTO `api_logs` (`id`, `created_at`, `data`, `updated_at`) VALUES
(1, '2021-05-06 15:00:46', '{\"to\":\"7678678yutuytyuthjgjhghjgjghf6756tffghmnbmhg876786hgjhgjgj77678ghjgj\",\"notification\":{\"title\":\"New Order\",\"body\":\"Order #1022 has been Assigned to You By Technomads Developer\",\"sound\":true},\"data\":{\"driver_id\":\"6\",\"custom_data\":{\"order_id\":109}}}', '2021-05-06 15:00:46'),
(2, '2021-05-06 15:00:47', '{\"to\":\"7678678yutuytyuthjgjhghjgjghf6756tffghmnbmhg876786hgjhgjgj77678ghjgj\",\"notification\":{\"title\":\"New Order\",\"body\":\"Order #1015 has been Assigned to You By Technomads Developer\",\"sound\":true},\"data\":{\"driver_id\":\"6\",\"custom_data\":{\"order_id\":102}}}', '2021-05-06 15:00:47'),
(3, '2021-05-06 15:00:59', '{\"to\":\"7678678yutuytyuthjgjhghjgjghf6756tffghmnbmhg876786hgjhgjgj77678ghjgj\",\"notification\":{\"title\":\"New Order\",\"body\":\"Order #1013 has been Assigned to You By Technomads Developer\",\"sound\":true},\"data\":{\"driver_id\":\"6\",\"custom_data\":{\"order_id\":100}}}', '2021-05-06 15:00:59'),
(4, '2021-05-06 15:01:02', '{\"to\":\"7678678yutuytyuthjgjhghjgjghf6756tffghmnbmhg876786hgjhgjgj77678ghjgj\",\"notification\":{\"title\":\"New Order\",\"body\":\"Order #1011 has been Assigned to You By Technomads Developer\",\"sound\":true},\"data\":{\"driver_id\":\"6\",\"custom_data\":{\"order_id\":99}}}', '2021-05-06 15:01:02'),
(5, '2021-05-06 15:01:40', '{\"current_latitude\":\"21.15044946348263\",\"current_longitude\":\"72.87329630967\",\"order_id\":\"109\"}', '2021-05-06 15:01:40'),
(6, '2021-05-06 15:01:40', '{\"success\":1,\"data\":{\"destination_addresses\":[\"Simada Naher Junction BRTS, Shiv Darshan Society, Yoginagar Society, Surat, Gujarat 395006, India\"],\"origin_addresses\":[\"Navagam Dindoli, Dindoli, Surat, Gujarat 394210, India\"],\"rows\":[{\"elements\":[{\"distance\":{\"text\":\"8.8 km\",\"value\":11816},\"duration\":{\"text\":\"18 min\",\"value\":352},\"status\":\"OK\"}]}],\"status\":\"OK\"},\"msg\":\"\"}', '2021-05-06 15:01:40'),
(7, '2021-05-06 15:01:48', '{\"success\":1,\"data\":{\"destination_addresses\":[\"Simada Naher Junction BRTS, Shiv Darshan Society, Yoginagar Society, Surat, Gujarat 395006, India\"],\"origin_addresses\":[\"Navagam Dindoli, Dindoli, Surat, Gujarat 394210, India\"],\"rows\":[{\"elements\":[{\"distance\":{\"text\":\"8.8 km\",\"value\":13132},\"duration\":{\"text\":\"18 min\",\"value\":348},\"status\":\"OK\"}]}],\"status\":\"OK\"},\"msg\":\"\"}', '2021-05-06 15:01:48'),
(8, '2021-05-06 15:05:27', '{\"current_latitude\":\"21.15044946348263\",\"current_longitude\":\"72.87329630967\",\"order_id\":\"108\"}', '2021-05-06 15:05:27'),
(9, '2021-05-06 15:05:39', '{\"current_latitude\":\"21.15044946348263\",\"current_longitude\":\"72.87329630967\",\"order_id\":\"102\"}', '2021-05-06 15:05:39'),
(10, '2021-05-06 15:05:39', '{\"success\":1,\"data\":{\"destination_addresses\":[\"Simada Naher Junction BRTS, Shiv Darshan Society, Yoginagar Society, Surat, Gujarat 395006, India\"],\"origin_addresses\":[\"Navagam Dindoli, Dindoli, Surat, Gujarat 394210, India\"],\"rows\":[{\"elements\":[{\"distance\":{\"text\":\"8.8 km\",\"value\":3029},\"duration\":{\"text\":\"18 min\",\"value\":395},\"status\":\"OK\"}]}],\"status\":\"OK\"},\"msg\":\"\"}', '2021-05-06 15:05:39'),
(11, '2021-05-06 15:08:03', '{\"success\":1,\"data\":{\"destination_addresses\":[\"Simada Naher Junction BRTS, Shiv Darshan Society, Yoginagar Society, Surat, Gujarat 395006, India\"],\"origin_addresses\":[\"Navagam Dindoli, Dindoli, Surat, Gujarat 394210, India\"],\"rows\":[{\"elements\":[{\"distance\":{\"text\":\"8.8 km\",\"value\":3097},\"duration\":{\"text\":\"18 min\",\"value\":551},\"status\":\"OK\"}]}],\"status\":\"OK\"},\"msg\":\"\"}', '2021-05-06 15:08:03'),
(12, '2021-05-06 15:22:16', '{\"current_latitude\":\"21.15044946348263\",\"current_longitude\":\"72.87329630967\",\"order_id\":\"100\"}', '2021-05-06 15:22:16'),
(13, '2021-05-06 15:22:16', '{\"success\":1,\"data\":{\"destination_addresses\":[\"Simada Naher Junction BRTS, Shiv Darshan Society, Yoginagar Society, Surat, Gujarat 395006, India\"],\"origin_addresses\":[\"Navagam Dindoli, Dindoli, Surat, Gujarat 394210, India\"],\"rows\":[{\"elements\":[{\"distance\":{\"text\":\"8.8 km\",\"value\":2706},\"duration\":{\"text\":\"18 min\",\"value\":797},\"status\":\"OK\"}]}],\"status\":\"OK\"},\"msg\":\"\"}', '2021-05-06 15:22:16'),
(14, '2021-05-06 15:22:25', '{\"success\":1,\"data\":{\"destination_addresses\":[\"Simada Naher Junction BRTS, Shiv Darshan Society, Yoginagar Society, Surat, Gujarat 395006, India\"],\"origin_addresses\":[\"Navagam Dindoli, Dindoli, Surat, Gujarat 394210, India\"],\"rows\":[{\"elements\":[{\"distance\":{\"text\":\"8.8 km\",\"value\":10504},\"duration\":{\"text\":\"18 min\",\"value\":258},\"status\":\"OK\"}]}],\"status\":\"OK\"},\"msg\":\"\"}', '2021-05-06 15:22:25'),
(15, '2021-05-06 16:03:48', '{\"current_latitude\":\"21.15044946348263\",\"current_longitude\":\"72.87329630967\",\"order_id\":\"99\"}', '2021-05-06 16:03:48'),
(16, '2021-05-06 16:03:48', '{\"success\":1,\"data\":{\"destination_addresses\":[\"Simada Naher Junction BRTS, Shiv Darshan Society, Yoginagar Society, Surat, Gujarat 395006, India\"],\"origin_addresses\":[\"Navagam Dindoli, Dindoli, Surat, Gujarat 394210, India\"],\"rows\":[{\"elements\":[{\"distance\":{\"text\":\"8.8 km\",\"value\":13480},\"duration\":{\"text\":\"18 min\",\"value\":385},\"status\":\"OK\"}]}],\"status\":\"OK\"},\"msg\":\"\"}', '2021-05-06 16:03:48'),
(17, '2021-05-06 16:08:20', '{\"current_latitude\":\"\",\"current_longitude\":\"\",\"order_id\":\"99\",\"undelivered_reason_id\":\"1\"}', '2021-05-06 16:08:20'),
(18, '2021-05-06 16:08:20', '{\"success\":1,\"data\":{\"destination_addresses\":[\"Simada Naher Junction BRTS, Shiv Darshan Society, Yoginagar Society, Surat, Gujarat 395006, India\"],\"origin_addresses\":[\"Navagam Dindoli, Dindoli, Surat, Gujarat 394210, India\"],\"rows\":[{\"elements\":[{\"distance\":{\"text\":\"8.8 km\",\"value\":2734},\"duration\":{\"text\":\"18 min\",\"value\":412},\"status\":\"OK\"}]}],\"status\":\"OK\"},\"msg\":\"\"}', '2021-05-06 16:08:20'),
(19, '2021-05-07 10:03:36', '{\"current_latitude\":\"\",\"current_longitude\":\"\",\"order_id\":\"99\",\"undelivered_reason_id\":\"1\"}', '2021-05-07 10:03:36');

-- --------------------------------------------------------

--
-- Table structure for table `company_configurations`
--

CREATE TABLE `company_configurations` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Company Name',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'optional',
  `mobile` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'optional',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `country` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT 'India',
  `state` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pincode` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `landmark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `about_us` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `privacy_policy` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `terms_condition` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_no_for_driver` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'emergency_no_for_driver'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `company_configurations`
--

INSERT INTO `company_configurations` (`id`, `company_name`, `email`, `mobile`, `created_at`, `updated_at`, `country`, `state`, `city`, `pincode`, `address`, `landmark`, `logo`, `about_us`, `privacy_policy`, `terms_condition`, `emergency_no_for_driver`) VALUES
(1, 'Bigdaddy', 'hello@bigdaddylogistics.com', '+91 7998879900', '2021-02-17 17:43:25', '2021-03-20 15:05:46', 'India', 'Gujarat', 'surat', '394210', 'U-15, Upper Ground Floor, Tower A,\r\nLandmark Empire\'s Textile House, Surat- Kadodara Main Road, Saroli, Surat - 395010 (Gujarat,India)', NULL, NULL, 'Big Daddy TM - \"Local parcel delivery experts\"\r\nWith the vision to bring transparency in the same city transport sector, a young enthusiast from Surat, Gujarat, conceived an online tempo booking platform - ‘Big Daddy TM’. The Founder Mr. Devrishi Arora comes from a family that has more than 60 years of existence and experience in the transportation sector, making him a third generation entrepreneur with in depth knowledge and experience of the industry. He imparts this wisdom - trickled down through generations - onto our digital platform.', 'A basic privacy policy outlines your website’s relationship with users’ personal information.\r\n\r\nTo succeed online and avoid legal turmoil, your website needs a privacy policy agreement. The first step to creating a compliant and comprehensive privacy policy is understanding exactly what that is.\r\n\r\nPrivacy Policy Definition\r\nA privacy policy is a legal document that informs your site’s users about how you collect and handle their personal information. You may also hear privacy policies referred to by the following names:\r\n\r\nPrivacy notice\r\nPrivacy policy statement\r\nPrivacy page\r\nPrivacy clause\r\nPrivacy agreement\r\nA general privacy policy explains a platform’s interactions with the personal information and personally identifiable information (PII) of its users. PII is information that can be used by itself, or combined with other information, to identify an individual.\r\n\r\nSpecific platforms or services may require a unique privacy policy template. Examples include:\r\n\r\napp privacy policies\r\nprivacy policies for Blogger\r\nWordPress privacy policies\r\necommerce privacy policies\r\nsmall business privacy policies\r\nHowever, a standard privacy policy template will likely satisfy user demands and legal requirements for your website.\r\n\r\nStandard Privacy Policy for Website\r\nWe’ll dive into details later on in What to Include in a Boilerplate Privacy Policy, but a basic privacy policy outlines the following:\r\n\r\nWhat information is collected\r\nWhere information is collected from\r\nWhy information is collected\r\nHow information is collected (including through cookies and other tracking technologies)\r\nWho information is shared with or sold to\r\nWhat rights users have over their data\r\nThe site’s contact details\r\nPrivacy policies should be clear, thorough, and easy for internet users to find on any given site.', 'Terms & Conditions \r\n \r\nBigDaddy LR\r\n \r\n\r\nThe company is not responsible for any kind of damage, leakage, theft, breakage, shortage, delays, spoilage by sun rays, flood or by any other means. Sender/client is responsible for proper packaging. No claims will be entertained ever. \r\nPerishable goods like fruits, vegetables, etc. are carried at the client/sender’s risk.  3.Goods insurance will be borne by respected sender or receiver. Company does not cover any insurance.\r\nGoods will be delivered as per our convenience i. e. in one lot or in parts. \r\nIf goods are not delivered because of client’s shortcomings, it will be taken to company godown. Extra charges for storing goods @ Rs. 5/kg per day and double delivery charges for re-delivery of goods will be levied. Also, if goods not claimed from godown, after 7 days, company has right to recover its charges through selling goods directly or through auction. \r\nIf goods are seized by any government officials for any reasons, company is not responsible for any claims or charges. Client/sender is responsible for the same. \r\nIn case of discrepancy in actual and declared weight or dimensions, company will recover the difference in charges at delivery destination plus 25% extra. Similarly, in case of change in delivery address, difference in charges will be applicable. \r\nGoods if stored at company godown for an reason, Company is not responsible for any kind of damage. (Refer point 3)\r\nGoods will only be delivered against company stamp.\r\n10.All disputes arising of this L. R. will be subjected to Surat jurisdiction only.', '7998879900');

-- --------------------------------------------------------

--
-- Table structure for table `deleted_data_logs`
--

CREATE TABLE `deleted_data_logs` (
  `id` bigint(20) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `deleted_data_logs`
--

INSERT INTO `deleted_data_logs` (`id`, `created_at`, `data`, `updated_at`) VALUES
(1, '2021-03-31 13:37:30', '{\"id\":80,\"bigdaddy_lr_number\":1002,\"transporter_lr_number\":\"20326064\",\"user_id\":122,\"pickup_location\":\"Plot No. A\\/60\\/20, Road no.6, Near Ankur Weigh Bridge, Udhna udhyoganagar, Udhna, Surat, Surat-394210\",\"drop_location\":\"SHUBHALAKSHMI POLYESTERS LIMITED\\r\\n418 419, 4th Floor, Jeevandeep Complex, Ring Road, Nr Rajhans Complex, Sagrampura, Surat\",\"pickup_latitude\":\"21.170465801418736\",\"pickup_longitude\":\"72.84854471683502\",\"drop_latitude\":\"21.181385198827172\",\"drop_longitude\":\"72.82501421157855\",\"contact_person_name\":\"Dipak Bhai\",\"contact_person_phone_number\":\"9724749016\",\"transporter_name\":\"Lalji Mulji Transport Co.\",\"contact_person_name_drop\":\"SHUBHALAKSHMI POLYESTERS LIMITED\",\"contact_person_phone_number_drop\":\"2612279364\",\"transporter_name_drop\":\"\",\"goods_height\":\"0.00\",\"goods_width\":\"0.00\",\"goods_length\":\"0.00\",\"final_cost\":\"150.00\",\"total_weight\":\"50.00\",\"total_no_of_parcel\":1,\"customer_estimation_asset_value\":\"23751.00\",\"discount\":\"0.00\",\"min_order_value_charge\":\"0.00\",\"redeliver_charge\":\"0.00\",\"driver_id\":0,\"driver_assigned_datetime\":null,\"pickedup_datetime\":null,\"cancelled_datetime\":null,\"delivered_datetime\":null,\"payment_datetime\":null,\"undelivered_datetime\":null,\"if_undelivered_reason_id\":0,\"if_undelivered_reason_text\":null,\"order_created_by\":6,\"status\":\"P\",\"lr_img\":null,\"pickup_img\":null,\"deliver_img\":null,\"is_active\":0,\"created_at\":\"2021-03-31T07:27:41.000000Z\",\"updated_at\":\"2021-03-31T07:27:41.000000Z\",\"payment_type\":\"C\",\"payment_status\":0,\"transport_cost\":\"0.00\",\"tempo_charge\":\"120.00\",\"service_charge\":\"30.00\",\"other_field_pickup\":null,\"other_field_drop\":null,\"vehicle_id\":0,\"vehicle_no\":null,\"if_cheque_number\":null,\"if_transaction_number\":null,\"payment_comment\":null,\"payment_discount\":\"0.00\",\"invoice_id\":null,\"order_driver_trip_type\":\"PRL\",\"order_driver_trip_amount\":\"0.00\",\"order_parcel\":[{\"id\":281,\"no_of_parcel\":1,\"goods_type_id\":24,\"goods_weight\":\"50.00\",\"total_weight\":\"50.00\",\"order_id\":80,\"is_active\":0,\"created_at\":\"2021-03-31T07:27:41.000000Z\",\"updated_at\":\"2021-03-31T07:27:41.000000Z\",\"tempo_charge\":\"120.00\",\"service_charge\":\"30.00\",\"delivery_charge\":\"150.00\",\"other_text\":null,\"estimation_value\":\"23751.00\",\"goods_type\":{\"id\":24,\"name\":\"Bundle - Big\",\"details\":null,\"img\":null}}],\"order_file\":[],\"invoice\":null,\"order_logs\":[{\"id\":606,\"logs\":\"Order Created By Jay\",\"order_id\":80,\"created_at\":\"2021-03-31T07:27:41.000000Z\"}],\"order_arrange\":[]}', '2021-03-31 13:37:30'),
(2, '2021-04-02 15:03:16', '{\"id\":84,\"bigdaddy_lr_number\":1005,\"transporter_lr_number\":\"20326064\",\"user_id\":122,\"pickup_location\":\"Plot No. A\\/60\\/20, Road no.6, Near Ankur Weigh Bridge, Udhna udhyoganagar, Udhna, Surat, Surat-394210\",\"drop_location\":\"SHUBHALAKSHMI POLYESTERS LIMITED\\r\\n418 419, 4th Floor, Jeevandeep Complex, Ring Road, Nr Rajhans Complex, Sagrampura, Surat\",\"pickup_latitude\":\"21.170465801418736\",\"pickup_longitude\":\"72.84854471683502\",\"drop_latitude\":\"21.181385198827172\",\"drop_longitude\":\"72.82501421157855\",\"contact_person_name\":\"Dipak Bhai\",\"contact_person_phone_number\":\"9724749016\",\"transporter_name\":\"Lalji Mulji Transport Co.\",\"contact_person_name_drop\":\"SHUBHALAKSHMI POLYESTERS LIMITED\",\"contact_person_phone_number_drop\":\"2612279364\",\"transporter_name_drop\":\"\",\"goods_height\":\"0.00\",\"goods_width\":\"0.00\",\"goods_length\":\"0.00\",\"final_cost\":\"150.00\",\"total_weight\":\"50.00\",\"total_no_of_parcel\":1,\"customer_estimation_asset_value\":\"23751.00\",\"discount\":\"0.00\",\"min_order_value_charge\":\"0.00\",\"redeliver_charge\":\"0.00\",\"driver_id\":20,\"driver_assigned_datetime\":\"2021-04-02 15:01:03\",\"pickedup_datetime\":\"2021-04-02 15:01:56\",\"cancelled_datetime\":null,\"delivered_datetime\":\"2021-04-02 15:02:39\",\"payment_datetime\":null,\"undelivered_datetime\":null,\"if_undelivered_reason_id\":0,\"if_undelivered_reason_text\":null,\"order_created_by\":6,\"status\":\"D\",\"lr_img\":null,\"pickup_img\":null,\"deliver_img\":null,\"is_active\":0,\"created_at\":\"2021-04-02T09:28:17.000000Z\",\"updated_at\":\"2021-04-02T09:32:39.000000Z\",\"payment_type\":\"C\",\"payment_status\":0,\"transport_cost\":\"0.00\",\"tempo_charge\":\"120.00\",\"service_charge\":\"30.00\",\"other_field_pickup\":null,\"other_field_drop\":null,\"vehicle_id\":4,\"vehicle_no\":\"GJ07UU1469\",\"if_cheque_number\":null,\"if_transaction_number\":null,\"payment_comment\":null,\"payment_discount\":\"0.00\",\"invoice_id\":null,\"order_driver_trip_type\":\"PRL\",\"order_driver_trip_amount\":\"0.00\",\"order_parcel\":[{\"id\":292,\"no_of_parcel\":1,\"goods_type_id\":24,\"goods_weight\":\"50.00\",\"total_weight\":\"50.00\",\"order_id\":84,\"is_active\":0,\"created_at\":\"2021-04-02T09:28:17.000000Z\",\"updated_at\":\"2021-04-02T09:28:17.000000Z\",\"tempo_charge\":\"120.00\",\"service_charge\":\"30.00\",\"delivery_charge\":\"150.00\",\"other_text\":null,\"estimation_value\":\"23751.00\",\"goods_type\":{\"id\":24,\"name\":\"Bundle - Big\",\"details\":null,\"img\":null}}],\"order_file\":[{\"id\":269,\"order_id\":84,\"img\":\"GP6066e48a8188a1617355914.jpg\",\"img_type\":\"GP\",\"is_active\":0},{\"id\":270,\"order_id\":84,\"img\":\"SGD6066e4b7393691617355959.png\",\"img_type\":\"SGD\",\"is_active\":0}],\"invoice\":null,\"order_logs\":[{\"id\":634,\"logs\":\"Order Created By Jay\",\"order_id\":84,\"created_at\":\"2021-04-02T09:28:17.000000Z\"},{\"id\":635,\"logs\":\"Order Assigned to Pranav Begade By Jay\",\"order_id\":84,\"created_at\":\"2021-04-02T09:31:04.000000Z\"},{\"id\":636,\"logs\":\"Order #1005 Picked Up By Pranav Begade\",\"order_id\":84,\"created_at\":\"2021-04-02T09:31:56.000000Z\"},{\"id\":637,\"logs\":\"Order #1005 Delivered By Pranav Begade\",\"order_id\":84,\"created_at\":\"2021-04-02T09:32:39.000000Z\"}],\"order_arrange\":[]}', '2021-04-02 15:03:16');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(7, '2020_11_19_055346_create_admins_table', 3),
(6, '2020_11_18_115427_create_product_details_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `razorpay_payments`
--

CREATE TABLE `razorpay_payments` (
  `id` bigint(20) NOT NULL,
  `razorpay_order_id` varchar(40) NOT NULL,
  `amount` decimal(13,2) NOT NULL DEFAULT 0.00,
  `order_id` int(11) DEFAULT NULL,
  `payment_status` varchar(15) NOT NULL DEFAULT 'created',
  `is_active` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-active,1-deactive, 2- deleted	',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `razorpay_payment_id` varchar(40) DEFAULT NULL,
  `razorpay_signature` varchar(255) DEFAULT NULL,
  `payment_type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1-order,2-wallet,3-subscription',
  `notes` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `razorpay_payments`
--

INSERT INTO `razorpay_payments` (`id`, `razorpay_order_id`, `amount`, `order_id`, `payment_status`, `is_active`, `created_at`, `updated_at`, `razorpay_payment_id`, `razorpay_signature`, `payment_type`, `notes`) VALUES
(1, 'order_GvyOgVILBo2A0d', '439.70', 95, 'paid', 0, '2021-04-07 14:27:50', '2021-04-07 14:34:54', 'pay_GvyW5OmKl58ve4', 'a5902e693918ea4636f06f2283ad8d8b58676b516075dea9c52f42f1c38386d0', 1, NULL),
(2, 'order_GvyhnNYgvToEIv', '433.80', 97, 'paid', 0, '2021-04-07 14:45:55', '2021-04-07 14:46:16', 'pay_Gvyi2XM8ceNeyl', 'd3df70ce31d58481efd1713167b5a61b9f56a5435a62a73a6f4d405fcaac546d', 1, NULL),
(3, 'order_GvyxfHya4pTSKM', '473.60', 98, 'paid', 0, '2021-04-07 15:00:57', '2021-04-07 15:01:25', 'pay_Gvyy1t2Z2nUBAv', '58bf9f0d8e049e30bdb8ce7a51678a97e2bc45d7379aafce94cb525b7471de21', 1, NULL),
(4, 'order_GvaTor6P9J6rPL', '183.48', 99, 'paid', 0, '2021-04-07 15:12:09', '2021-04-07 15:18:04', 'pay_GvzFWxWvE68xgB', 'dcbf0675bddba776e31b27f86e4fd845dcd97b1da2e65baf8cc647b863e3443d', 1, NULL),
(5, 'order_GvaTor6P9J6rPL', '165.00', 100, 'paid', 0, '2021-04-07 15:40:49', '2021-04-07 15:53:50', 'pay_GvzrSrsnXuDg4Q', '06b1260d7d6ffbddb06c6936d9f56d6c354be1930596920c41782331941a2da9', 1, NULL),
(6, 'order_Gw0kA9kIcbZHgq', '2225.00', 102, 'created', 2, '2021-04-07 16:45:33', '2021-04-07 16:55:34', NULL, NULL, 1, NULL),
(7, 'order_Gw187usjVWPeEv', '66.00', 103, 'created', 2, '2021-04-07 17:08:14', '2021-04-07 17:08:55', NULL, NULL, 1, NULL),
(8, 'order_Gw1RLXx3DaQRjL', '6259.00', 101, 'created', 2, '2021-04-07 17:26:26', '2021-04-07 17:26:53', NULL, NULL, 1, NULL),
(9, 'order_Gw0kA9kIcbZHgq', '814.50', 104, 'created', 0, '2021-04-07 17:37:10', '2021-04-07 17:43:49', NULL, NULL, 1, NULL),
(10, 'order_GvdQp9QFJux0Pe', '225.00', 105, 'paid', 0, '2021-04-07 18:00:35', '2021-04-07 18:12:23', 'pay_Gw2Dn0VPPOpXz9', '5a51c59c240de947ec6c25d8970d349363c83a5aabbc6c700a946f85ed99709f', 1, NULL),
(12, 'order_GwJ0nxDSF8d3hb', '1188.00', 96, 'paid', 0, '2021-04-08 10:37:46', '2021-04-08 10:40:07', 'pay_GwJ3CfGr4PZIkO', 'e4a5d4d48928641de0459ed07fe8145401b46e9a85d67fcbd9bb0698546f2d2f', 1, NULL),
(13, 'order_GwJTYNx2BIVHwN', '753.60', 107, 'created', 2, '2021-04-08 11:04:59', '2021-04-08 13:30:28', NULL, NULL, 1, NULL),
(14, 'order_GwK35P1Kj1uDAe', '1624.48', 110, 'paid', 0, '2021-04-08 11:38:38', '2021-04-08 11:40:32', 'pay_GwK4kyzpWbFwPH', 'a107ec374e717668534b5bd08768f8b4f4c98d200fcd8114408bf9fa79e6c1a5', 1, NULL),
(15, 'order_GwKolpk0Y3AuFK', '253.00', 109, 'paid', 0, '2021-04-08 12:23:46', '2021-04-08 12:25:11', 'pay_GwKq7Uhh4H4LtL', '61a3ef189040c6ec6284b723b9790077a1077109099a15e3ed97dc81fa138fa7', 1, NULL),
(16, 'order_GwKrgwJzNwYqBC', '2378.00', 108, 'paid', 0, '2021-04-08 12:26:32', '2021-04-08 12:26:51', 'pay_GwKrxIK6K4h1RY', '2725455aaa5cb63658d0d8d11496d34c7b4bc5524d73d562f807d72cc86de2ed', 1, NULL),
(20, 'order_GwLwjVlzF8bXKn', '85.80', 111, 'paid', 0, '2021-04-08 13:30:00', '2021-04-08 13:30:13', 'pay_GwLwsio25v54Zu', '97660b52cf525fb58151501b7530eb66d8267e69bec30aff0ef8de9b780fdf37', 1, NULL),
(21, 'order_GwM52HsAQnBDCK', '616.00', 112, 'paid', 0, '2021-04-08 13:37:52', '2021-04-08 13:38:10', 'pay_GwM5HOoi4MTJDv', 'b1134d0ad104ec6e83e78dff9d22a5c75c491691535dac47efe4a435bcefc78a', 1, NULL),
(22, 'order_GwMq20Cn0b30JI', '2520.00', 113, 'created', 0, '2021-04-08 14:22:21', '2021-04-08 14:22:21', NULL, NULL, 1, NULL),
(23, 'order_GwNIf2pf7PQHUn', '671.00', 114, 'paid', 0, '2021-04-08 14:49:27', '2021-04-08 14:54:18', 'pay_GwNNgFRaxls0Wu', '5300c534480a5587e41553cdf4501d44f2b4544b6c95f9c16b417694795d5eef', 1, NULL),
(24, 'order_GwNQcRMmiVmNcm', '616.00', 115, 'paid', 0, '2021-04-08 14:55:06', '2021-04-08 14:57:12', 'pay_GwNQldbuoI3Drb', '213e156d48642a56b7544bd6924bc208d9421f54f3432a4c71cb6b915d01009a', 1, NULL),
(25, 'order_GwNsSxXdTGkdUx', '935.00', 116, 'created', 2, '2021-04-08 15:23:21', '2021-04-08 15:23:53', NULL, NULL, 1, NULL),
(26, 'order_GwO2RNAU8mcM3q', '616.00', 106, 'paid', 0, '2021-04-08 15:32:48', '2021-04-08 15:33:24', 'pay_GwO31GkJTrMlBf', 'a1f183c8bedfee3d3c991045cb578c206d24f9a89a82d123f1496c583640580b', 1, NULL),
(27, 'order_GwO7Y8zLOG5wJR', '176.00', 117, 'created', 0, '2021-04-08 15:37:38', '2021-04-08 15:37:38', NULL, NULL, 1, NULL),
(28, 'order_GwO9I4jwztDXxe', '38.00', 118, 'created', 0, '2021-04-08 15:39:17', '2021-04-08 15:39:17', NULL, NULL, 1, NULL),
(29, 'order_GwPahyeAl1wnww', '616.00', 123, 'created', 0, '2021-04-08 17:03:56', '2021-04-08 17:03:56', NULL, NULL, 1, NULL),
(30, 'order_GwPcVGWJFIrbrS', '253.00', 124, 'created', 0, '2021-04-08 17:05:38', '2021-04-08 17:05:38', NULL, NULL, 1, NULL),
(31, 'order_GwPgyn1b7cfi8F', '616.00', 125, 'created', 0, '2021-04-08 17:09:52', '2021-04-08 17:09:52', NULL, NULL, 1, NULL),
(32, 'order_GwPlmwXnYQn8oy', '168.00', 126, 'paid', 0, '2021-04-08 17:14:25', '2021-04-08 17:15:09', 'pay_GwPmTha5OwQZuE', '7aed785aefbc40c010fc06f5615ae4df60ffb30aa3c6b2ab07c7bff54d98dcb3', 1, NULL),
(33, 'order_GwPsnJQIcqolJJ', '616.00', 127, 'paid', 0, '2021-04-08 17:21:03', '2021-04-08 17:21:37', 'pay_GwPtJiYh3o8jwM', '5ed048ec59397a9d7d263dadf872a8f0da8cf630b4c92f6e8bdfa0fa6132bce1', 1, NULL),
(34, 'order_GwQYH58w7n0Ji4', '66.00', 128, 'paid', 0, '2021-04-08 18:00:19', '2021-04-08 18:00:31', 'pay_GwQYQ8UvNBUysK', '7800b7042579b6752ff378ba2bf2aa8f3b18dc4121113d6b99accd31fc83c571', 1, NULL),
(35, 'order_GwQZO5iujDckPd', '616.00', 129, 'paid', 0, '2021-04-08 18:01:22', '2021-04-08 18:01:57', 'pay_GwQZvHP2JGDFWn', '02d27ee5988dc69234c7016a2cea122076b8f46bce6befbb1621154b381dc3c9', 1, NULL),
(36, 'order_GwQk5I8dsNNnj2', '3355.00', 130, 'paid', 0, '2021-04-08 18:11:30', '2021-04-08 18:11:48', 'pay_GwQkKhTkvA2pNe', '4b0827301ce24c32829e08f9bd7691a3226d0bebe4dfc7fe98edba1c9a338010', 1, NULL),
(37, 'order_GwgqbEF8HaxIjh', '858.00', 132, 'paid', 0, '2021-04-09 09:56:46', '2021-04-09 09:57:24', 'pay_GwgrAq8YTmudfS', '2909c3166c4054e1ff66ebddc2101842607ef35452eeff04f350a51b03109f5d', 1, NULL),
(38, 'order_GvaUrrXS7aZwNl', '649.00', 133, 'created', 0, '2021-04-09 10:01:14', '2021-04-09 10:01:14', NULL, NULL, 1, NULL),
(39, 'order_GwhYfiiqqvnV6t', '902.00', 134, 'created', 0, '2021-04-09 10:38:29', '2021-04-09 10:38:29', NULL, NULL, 1, NULL),
(40, 'order_Gwi6nzmuKib41Z', '539.00', 135, 'created', 0, '2021-04-09 11:10:48', '2021-04-09 11:10:48', NULL, NULL, 1, NULL),
(41, 'order_GwiL8rkq8d0Mob', '1232.00', 139, 'created', 0, '2021-04-09 11:24:23', '2021-04-09 11:24:23', NULL, NULL, 1, NULL),
(42, 'order_GwiQrJmhJfgip7', '840.00', 140, 'created', 0, '2021-04-09 11:29:47', '2021-04-09 11:29:47', NULL, NULL, 1, NULL),
(43, 'order_GwiVZMPQKtzVo2', '480.00', 141, 'created', 0, '2021-04-09 11:34:14', '2021-04-09 11:34:14', NULL, NULL, 1, NULL),
(44, 'order_GwiaGYXVdg6VOJ', '360.00', 142, 'paid', 0, '2021-04-09 11:38:41', '2021-04-09 11:40:33', 'pay_Gwic8HpPqbD24E', '007a1d1814d5ca240b7e945d7efc6f54f9e6d5b9f98d3b5ad854ebf8cbbd781d', 1, NULL),
(45, 'order_GwifBtbCg5Szft', '360.00', 143, 'created', 0, '2021-04-09 11:43:21', '2021-04-09 11:43:21', NULL, NULL, 1, NULL),
(46, 'order_GwijA0RRaAcirX', '400.00', 144, 'paid', 0, '2021-04-09 11:47:06', '2021-04-09 11:47:42', 'pay_Gwije0cmCvonwH', '970b02da91e06c51c10dabab6e0e8c81f026cfcaa1b9017bbfc61e2cbfe6bf1b', 1, NULL),
(47, 'order_GwirfWvsgoxnNz', '600.00', 145, 'created', 0, '2021-04-09 11:55:10', '2021-04-09 11:55:10', NULL, NULL, 1, NULL),
(48, 'order_GwjH41XrNjrxyA', '360.00', 146, 'created', 0, '2021-04-09 12:19:12', '2021-04-09 12:19:12', NULL, NULL, 1, NULL),
(49, 'order_GwjJFTeZ9iHiLq', '1936.00', 147, 'paid', 0, '2021-04-09 12:21:16', '2021-04-09 12:21:40', 'pay_GwjJachK3NCyQn', '29f118ff340e26997731cb15771f0d85c2d73c7c6f4582f78b74add361dacef6', 1, NULL),
(50, 'order_GwjRv1J2zwXLcw', '242.00', 149, 'paid', 0, '2021-04-09 12:29:29', '2021-04-09 12:29:51', 'pay_GwjSEFaDaiZ0Gh', '982f141d716b84ddfaa9b925bcc92edac5a9f7189ce86f743932d6f4c37fdb45', 1, NULL),
(51, 'order_GwjZ1BeIju3mR2', '2860.00', 148, 'paid', 0, '2021-04-09 12:36:12', '2021-04-09 12:37:40', 'pay_GwjaUwQ78uPb48', '2dc87fd57d54aab1b594b0e868329170b4e117522d2c270d2df230179cfabadb', 1, NULL),
(52, 'order_GwjeNdixQW6zdH', '330.00', 150, 'created', 2, '2021-04-09 12:41:17', '2021-04-09 12:48:32', NULL, NULL, 1, NULL),
(53, 'order_GwjvxjBaF7lduC', '649.00', 138, 'created', 0, '2021-04-09 12:57:55', '2021-04-09 12:57:55', NULL, NULL, 1, NULL),
(54, 'order_GwkyKu5n3AarHg', '684.00', 151, 'paid', 0, '2021-04-09 13:58:52', '2021-04-09 13:59:39', 'pay_Gwkz5ao7GR4zIC', '31cc63b8e51a3a06c24b4479b99c87f3c638f2ff895728fa855a1b1e03fc7202', 1, NULL),
(55, 'order_Gwl30EtI37Xts7', '770.00', 152, 'paid', 0, '2021-04-09 14:03:17', '2021-04-09 14:03:44', 'pay_Gwl3Ot8e7fkuo5', 'ed2ed495f12982382faa8d975a9482158916d375de462ae753615abbf9258184', 1, NULL),
(56, 'order_GwlQYbpEEN9cKt', '840.00', 153, 'paid', 0, '2021-04-09 14:25:35', '2021-04-09 15:06:30', 'pay_Gwm7fvkQf6j6Zg', 'e2ecef4d99b9fe8329532710f9d827803b245e9f01d7eef2be2219de75c24994', 1, NULL),
(57, 'order_Gwm6TcrM1FzmhB', '616.00', 154, 'paid', 0, '2021-04-09 15:05:16', '2021-04-09 16:30:32', 'pay_GwnYSXINKFKcNG', 'd9effb5c7c2ccaef454464863d03dc8729d4c6e62dafc8d3bc9e797b36b20a7f', 1, NULL),
(58, 'order_GwnQt71Phm18qR', '649.00', 155, 'created', 2, '2021-04-09 16:23:17', '2021-04-14 11:01:43', NULL, NULL, 1, NULL),
(59, 'order_GwnSIp7ZGo9rEB', '979.00', 156, 'created', 0, '2021-04-09 16:24:37', '2021-04-09 16:24:37', NULL, NULL, 1, NULL),
(60, 'order_Gwndidl9QxOE3N', '649.00', 157, 'paid', 0, '2021-04-09 16:35:26', '2021-04-09 16:36:03', 'pay_GwneIF8KAJZsy6', '2bc1f05c5a4c468122985a1714b22d0d2a1174f9013ecf0fdda3982ef0ac6ec4', 1, NULL),
(61, 'order_GxvNj3JnjDLzrF', '1000.00', NULL, 'created', 0, '2021-04-12 12:48:50', '2021-04-12 12:48:50', NULL, NULL, 1, NULL),
(62, 'order_GxvNlIn9QOt05R', '1000.00', NULL, 'created', 0, '2021-04-12 12:48:52', '2021-04-12 12:48:52', NULL, NULL, 1, NULL),
(63, 'order_GxvPMIl4SlUp5B', '1000.00', NULL, 'created', 0, '2021-04-12 12:50:22', '2021-04-12 12:50:22', NULL, NULL, 1, NULL),
(64, 'order_GxvPNXwNysLuhG', '1000.00', NULL, 'created', 0, '2021-04-12 12:50:23', '2021-04-12 12:50:23', NULL, NULL, 1, NULL),
(65, 'order_GxvPT1sHIMndPq', '269.00', NULL, 'created', 0, '2021-04-12 12:50:28', '2021-04-12 12:50:28', NULL, NULL, 1, NULL),
(66, 'order_GxvPX8kGS4Ox0q', '269.00', NULL, 'created', 0, '2021-04-12 12:50:32', '2021-04-12 12:50:32', NULL, NULL, 1, NULL),
(67, 'order_GxvRGZzhxdpSUG', '596.00', NULL, 'created', 0, '2021-04-12 12:52:11', '2021-04-12 12:52:11', NULL, NULL, 1, NULL),
(68, 'order_GxvRHKrSAzIsqn', '596.00', NULL, 'created', 0, '2021-04-12 12:52:11', '2021-04-12 12:52:11', NULL, NULL, 1, NULL),
(69, 'order_GxvRISCifnAEsk', '596.00', NULL, 'created', 0, '2021-04-12 12:52:12', '2021-04-12 12:52:12', NULL, NULL, 1, NULL),
(70, 'order_GxvTmuQDk7W1sM', '596.00', 0, 'created', 0, '2021-04-12 12:54:34', '2021-04-12 12:54:34', NULL, NULL, 2, NULL),
(71, 'order_GxvWiBW2sCintr', '596.00', 0, 'created', 0, '2021-04-12 12:57:20', '2021-04-12 12:57:20', NULL, NULL, 2, NULL),
(72, 'order_GxvYLMQKVh8jZb', '596.00', 0, 'created', 0, '2021-04-12 12:58:53', '2021-04-12 12:58:53', NULL, NULL, 2, NULL),
(73, 'order_GxvWiBW2sCintr', '596.00', 0, 'paid', 0, '2021-04-12 12:58:59', '2021-04-12 12:58:59', 'pay_GxvYOET0EmfmJc', '135b0d3f479c2566fa631c61e9a7968945b97073b468264ea7e57a4b7245e649', 2, NULL),
(74, 'order_GxvZCwxRbVKlhK', '596.00', 0, 'created', 0, '2021-04-12 12:59:42', '2021-04-12 12:59:42', NULL, NULL, 2, NULL),
(75, 'order_GxvYLMQKVh8jZb', '596.00', 0, 'paid', 0, '2021-04-12 12:59:50', '2021-04-12 12:59:50', 'pay_GxvZHClicnxYc6', '8b01c3eafb721a795d78461d3f75dfc9138ade749b34aa5e153c9e8ab07322c2', 2, NULL),
(76, 'order_Gxw2NzvECMXKjn', '523.00', 0, 'created', 0, '2021-04-12 13:27:19', '2021-04-12 13:27:19', NULL, NULL, 2, NULL),
(77, 'order_Gxw2Rf3vEHx2Ts', '523.00', 0, 'created', 0, '2021-04-12 13:27:22', '2021-04-12 13:27:22', NULL, NULL, 2, NULL),
(78, 'order_Gxw2NzvECMXKjn', '523.00', 0, 'paid', 0, '2021-04-12 13:27:31', '2021-04-12 13:27:31', 'pay_Gxw2VU84Ta3TND', '0768d371f87705817da423ddbe67c0df430d253211566c7e7401eb24a52dcadb', 2, NULL),
(79, 'order_Gxw6DtgK4ntwo9', '152.00', 0, 'created', 0, '2021-04-12 13:30:57', '2021-04-12 13:30:57', NULL, NULL, 2, NULL),
(80, 'order_Gxw6HGf9R6fy7K', '152.00', 0, 'created', 0, '2021-04-12 13:31:00', '2021-04-12 13:31:00', NULL, NULL, 2, NULL),
(81, 'order_Gxw6DtgK4ntwo9', '152.00', 0, 'paid', 0, '2021-04-12 13:31:09', '2021-04-12 13:31:09', 'pay_Gxw6L7xxX6BNcI', 'eb00f241330726e713ee06e326b63a69437de38ecd25dd5bc3709bf91bd8d551', 2, NULL),
(82, 'order_Gxw7N5L2vm6TEb', '126.00', 0, 'created', 0, '2021-04-12 13:32:02', '2021-04-12 13:32:02', NULL, NULL, 2, NULL),
(83, 'order_Gxw8jBuW3SNI1g', '269.00', 0, 'created', 0, '2021-04-12 13:33:19', '2021-04-12 13:33:19', NULL, NULL, 2, NULL),
(84, 'order_Gxw8jBuW3SNI1g', '269.00', 0, 'paid', 0, '2021-04-12 13:33:28', '2021-04-12 13:33:28', 'pay_Gxw8oAYj0WAxJS', '0ef53348cd75dc068f6b5da8b7eda233c9607f535354b7a226b02cb6d25908b1', 2, NULL),
(85, 'order_GxwBBqOVVWjseq', '458.00', 0, 'paid', 0, '2021-04-12 13:35:39', '2021-04-12 13:35:47', 'pay_GxwBGCJcF0moW6', '2c1660a7d97eb93c5a4b473418ea1ac7aa5aefe3d21cc2951a1a812baff31716', 2, NULL),
(86, 'order_Gxwac9vv7iKRXf', '101.00', 0, 'created', 0, '2021-04-12 13:59:43', '2021-04-12 13:59:43', NULL, NULL, 2, NULL),
(87, 'order_GxwagiALv1Sw11', '101.00', 0, 'created', 0, '2021-04-12 13:59:47', '2021-04-12 13:59:47', NULL, NULL, 2, NULL),
(88, 'order_Gxwi39g4uMQPC1', '101.00', 0, 'created', 0, '2021-04-12 14:06:46', '2021-04-12 14:06:46', NULL, NULL, 2, NULL),
(89, 'order_GxwiW8JQCe0bS5', '263.00', 0, 'created', 0, '2021-04-12 14:07:12', '2021-04-12 14:07:12', NULL, NULL, 2, NULL),
(90, 'order_Gxwj5cVRFeu6uR', '5963.00', 0, 'created', 0, '2021-04-12 14:07:45', '2021-04-12 14:07:45', NULL, NULL, 2, NULL),
(91, 'order_GxwjAdo0fdgPLi', '5963.00', 0, 'created', 0, '2021-04-12 14:07:49', '2021-04-12 14:07:49', NULL, NULL, 2, NULL),
(92, 'order_GxwjkGYlLbaFKZ', '456.00', 0, 'created', 0, '2021-04-12 14:08:22', '2021-04-12 14:08:22', NULL, NULL, 2, NULL),
(93, 'order_GxwjlKaNrY4tUf', '456.00', 0, 'created', 0, '2021-04-12 14:08:23', '2021-04-12 14:08:23', NULL, NULL, 2, NULL),
(94, 'order_Gz6eknAdV3Tgnx', '263.00', 0, 'created', 0, '2021-04-15 12:29:35', '2021-04-15 12:29:35', NULL, NULL, 2, NULL),
(95, 'order_Gz6nrm4gpDLiQd', '263.00', 0, 'created', 0, '2021-04-15 12:38:13', '2021-04-15 12:38:13', NULL, NULL, 2, NULL),
(96, 'order_Gz6nwPoQcKAQWn', '263.00', 0, 'created', 0, '2021-04-15 12:38:17', '2021-04-15 12:38:17', NULL, NULL, 2, NULL),
(97, 'order_Gz6o4zLRsDNIKX', '263.00', 0, 'created', 0, '2021-04-15 12:38:25', '2021-04-15 12:38:25', NULL, NULL, 2, NULL),
(98, 'order_Gz6oJaO0IxgtYY', '456.00', 0, 'created', 0, '2021-04-15 12:38:38', '2021-04-15 12:38:38', NULL, NULL, 2, NULL),
(99, 'order_Gz6oT6huKl8dRD', '456.00', 0, 'created', 0, '2021-04-15 12:38:47', '2021-04-15 12:38:47', NULL, NULL, 2, NULL),
(100, 'order_Gz6rBCah1LzCRw', '456.00', 0, 'created', 0, '2021-04-15 12:41:21', '2021-04-15 12:41:21', NULL, NULL, 2, NULL),
(101, 'order_GzAkpHJ2FMMEXd', '110.00', 0, 'created', 0, '2021-04-15 16:30:07', '2021-04-15 16:30:07', NULL, NULL, 3, NULL),
(102, 'order_GzB0PHr7pmmVCf', '110.00', 0, 'created', 0, '2021-04-15 16:44:52', '2021-04-15 16:44:52', NULL, NULL, 3, NULL),
(103, 'order_GzB8t1DL2vcDmo', '110.00', 0, 'created', 0, '2021-04-15 16:52:53', '2021-04-15 16:52:53', NULL, NULL, 3, NULL),
(104, 'order_GzB9cidR1MODog', '110.00', 0, 'created', 0, '2021-04-15 16:53:35', '2021-04-15 16:53:35', NULL, NULL, 3, NULL),
(105, 'order_GzB9kgRlpskzam', '110.00', 0, 'created', 0, '2021-04-15 16:53:42', '2021-04-15 16:53:42', NULL, NULL, 3, NULL),
(106, 'order_GzBG4fUKe9BatE', '110.00', 0, 'created', 0, '2021-04-15 16:59:41', '2021-04-15 16:59:41', NULL, NULL, 3, NULL),
(107, 'order_GzBHMzoYMfjJjD', '110.00', 0, 'created', 0, '2021-04-15 17:00:55', '2021-04-15 17:00:55', NULL, NULL, 3, NULL),
(108, 'order_GzBJERRVHxXwSa', '110.00', 0, 'created', 0, '2021-04-15 17:02:41', '2021-04-15 17:02:41', NULL, NULL, 3, NULL),
(109, 'order_GzBKXbZgc0BgYc', '110.00', 0, 'created', 0, '2021-04-15 17:03:55', '2021-04-15 17:03:55', NULL, NULL, 3, NULL),
(110, 'order_GzCIDxDJFtf6eW', '110.00', 0, 'created', 0, '2021-04-15 18:00:25', '2021-04-15 18:00:25', NULL, NULL, 3, NULL),
(111, 'order_GzTcZy2baRjGW3', '110.00', 0, 'created', 0, '2021-04-16 10:57:28', '2021-04-16 10:57:28', NULL, NULL, 3, NULL),
(112, 'order_GzTdGxCHMCy1tV', '110.00', 0, 'created', 0, '2021-04-16 10:58:08', '2021-04-16 10:58:08', NULL, NULL, 3, NULL),
(113, 'order_GzTlGmtuL12P74', '110.00', 0, 'created', 0, '2021-04-16 11:05:42', '2021-04-16 11:05:42', NULL, NULL, 3, NULL),
(114, 'order_GzUvmf4wWrrFOJ', '456.00', 0, 'created', 0, '2021-04-16 12:14:21', '2021-04-16 12:14:21', NULL, NULL, 2, NULL),
(115, 'order_GzUwQrcaVCBHoM', '456.00', 0, 'created', 0, '2021-04-16 12:14:57', '2021-04-16 12:14:57', NULL, NULL, 2, NULL),
(116, 'order_GzaJcBjZWUcuzI', '456.00', 0, 'created', 0, '2021-04-16 17:30:22', '2021-04-16 17:30:22', NULL, NULL, 2, NULL),
(117, 'order_GzaR2C985Fz4Wd', '456.00', 0, 'created', 0, '2021-04-16 17:37:24', '2021-04-16 17:37:24', NULL, NULL, 2, NULL),
(118, 'order_Gzb2bQxdgTw2dN', '456.00', 0, 'created', 0, '2021-04-16 18:12:58', '2021-04-16 18:12:58', NULL, NULL, 2, NULL),
(119, 'order_GzbSA24jnREhYh', '456.00', 0, 'created', 0, '2021-04-16 18:37:09', '2021-04-16 18:37:09', NULL, NULL, 2, NULL),
(120, 'order_GzbcWpV9N2u2Sv', '456.00', 0, 'created', 0, '2021-04-16 18:46:58', '2021-04-16 18:46:58', NULL, NULL, 2, NULL),
(121, 'order_GzwF9rgCIji6UW', '109.00', 158, 'created', 0, '2021-04-17 14:57:25', '2021-04-17 14:57:25', NULL, NULL, 1, NULL),
(122, 'order_H0fe4bwB9TWPbi', '110.00', 0, 'created', 0, '2021-04-19 11:22:12', '2021-04-19 11:22:12', NULL, NULL, 3, NULL),
(123, 'order_H0hLPya2tDaDZB', '255.00', 169, 'created', 2, '2021-04-19 13:01:55', '2021-04-19 17:32:38', NULL, NULL, 1, NULL),
(124, 'order_H0m153orsoH6yB', '2370.00', 171, 'created', 2, '2021-04-19 17:36:08', '2021-04-19 17:50:50', NULL, NULL, 1, NULL),
(125, 'order_H0mHm99tRnSSGE', '3696.00', 172, 'created', 0, '2021-04-19 17:51:57', '2021-04-19 17:51:57', NULL, NULL, 1, NULL),
(126, 'order_H0mSFYx3CNdH2H', '4920.00', 175, 'created', 2, '2021-04-19 18:01:51', '2021-04-19 18:03:45', NULL, NULL, 1, NULL),
(127, 'order_H0n7humm6qqwIl', '3060.00', 182, 'created', 0, '2021-04-19 18:41:06', '2021-04-19 18:41:06', NULL, NULL, 1, NULL),
(128, 'order_H0nJnocKMcxV8S', '36000.00', 183, 'created', 0, '2021-04-19 18:52:33', '2021-04-19 18:52:33', NULL, NULL, 1, NULL),
(129, 'order_H15QLbYknPjuKG', '1200.00', 0, 'created', 0, '2021-04-20 12:35:14', '2021-04-20 12:35:14', NULL, NULL, 2, NULL),
(130, 'order_H15gbWeu1uJrdO', '2328.00', 184, 'created', 2, '2021-04-20 12:50:37', '2021-04-20 12:53:15', NULL, NULL, 1, NULL),
(131, 'order_H15q2nthcD0NIX', '1968.00', 185, 'created', 2, '2021-04-20 12:59:34', '2021-04-20 17:33:17', NULL, NULL, 1, NULL),
(132, 'order_H17hBt8HGSoZvM', '2916.00', 190, 'created', 0, '2021-04-20 14:48:34', '2021-04-20 14:48:34', NULL, NULL, 1, NULL),
(133, 'order_H1AcQ5BdvDfVjF', '1020.00', 191, 'created', 2, '2021-04-20 17:40:07', '2021-04-20 17:41:06', NULL, NULL, 1, NULL),
(134, 'order_H1AxiZfCnaCjZk', '5740.00', 192, 'created', 2, '2021-04-20 18:00:17', '2021-04-20 18:02:45', NULL, NULL, 1, NULL),
(135, 'order_H1Vn6wDJ0wzIW3', '1200.00', 0, 'created', 0, '2021-04-21 14:22:49', '2021-04-21 14:22:49', NULL, NULL, 2, NULL),
(136, 'order_H1sWdfoSyeE9uG', '890.00', 195, 'created', 0, '2021-04-22 12:30:38', '2021-04-22 12:37:09', NULL, NULL, 1, NULL),
(137, 'order_H1xuVEjRa7QoNA', '1200.00', 0, 'created', 0, '2021-04-22 17:53:13', '2021-04-22 17:53:13', NULL, NULL, 2, NULL),
(138, 'order_H1y6cbvBuhPfi2', '1205.00', 0, 'created', 0, '2021-04-22 18:04:41', '2021-04-22 18:04:41', NULL, NULL, 2, NULL),
(139, 'order_H1y7LFVzDsImXI', '1205.00', 0, 'created', 0, '2021-04-22 18:05:22', '2021-04-22 18:05:22', NULL, NULL, 2, NULL),
(140, 'order_H1y7PVYZ8obrmO', '1205.00', 0, 'created', 0, '2021-04-22 18:05:27', '2021-04-22 18:05:27', NULL, NULL, 2, NULL),
(141, 'order_H1yXZnDZLvn1vA', '1205.00', 0, 'created', 0, '2021-04-22 18:30:12', '2021-04-22 18:30:12', NULL, NULL, 2, NULL),
(142, 'order_H2K80fua13g1s9', '110.00', 0, 'created', 0, '2021-04-23 15:37:16', '2021-04-23 15:37:16', NULL, NULL, 3, NULL),
(143, 'order_H3pITYwXABd5rg', '102.00', 0, 'created', 0, '2021-04-27 10:45:40', '2021-04-27 10:45:40', NULL, NULL, 2, NULL),
(144, 'order_H3ppI9D7tuiOtp', '102.00', 0, 'created', 0, '2021-04-27 11:16:43', '2021-04-27 11:16:43', NULL, NULL, 2, NULL),
(145, 'order_H3ppleaql1I36B', '102.00', 0, 'created', 0, '2021-04-27 11:17:11', '2021-04-27 11:17:11', NULL, NULL, 2, NULL),
(146, 'order_H3prDIzwSCAWbi', '102.00', 0, 'created', 0, '2021-04-27 11:18:33', '2021-04-27 11:18:33', NULL, NULL, 2, NULL),
(147, 'order_H3psNFVqni05Xb', '102.00', 0, 'created', 0, '2021-04-27 11:19:38', '2021-04-27 11:19:38', NULL, NULL, 2, NULL),
(148, 'order_H3tISxmub5NTgx', '102.00', 0, 'created', 0, '2021-04-27 14:40:25', '2021-04-27 14:40:25', NULL, NULL, 2, NULL),
(149, 'order_H3vpFQzqJrg9DV', '102.00', 0, 'created', 0, '2021-04-27 17:08:50', '2021-04-27 17:08:50', NULL, NULL, 2, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_account_category`
--

CREATE TABLE `tbl_account_category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-active,1-deactive, 2- deleted',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `level` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-main category, 1- subcategory',
  `path_to` int(11) NOT NULL DEFAULT 0,
  `type` varchar(2) DEFAULT NULL COMMENT 'Dr-Debit,Cr-Credit, NULL for none',
  `is_editable` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0-no,1-yes'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_account_category`
--

INSERT INTO `tbl_account_category` (`id`, `name`, `image`, `is_active`, `created_at`, `updated_at`, `level`, `path_to`, `type`, `is_editable`) VALUES
(1, 'Salary', NULL, 0, '2021-01-19 15:19:07', '2021-01-19 15:19:07', 0, 0, NULL, 1),
(2, 'Hardik Patel Admin Tool', NULL, 0, '2021-01-19 15:19:37', '2021-01-19 15:19:37', 1, 1, NULL, 1),
(3, 'IT Expense', NULL, 0, '2021-01-19 15:22:22', '2021-02-10 15:10:37', 0, 0, NULL, 1),
(4, 'BDL Mobile Nos.', NULL, 0, '2021-01-19 15:23:06', '2021-01-19 15:23:06', 0, 0, NULL, 1),
(5, 'Domain Charges', NULL, 0, '2021-01-19 15:23:24', '2021-01-19 15:23:24', 0, 0, NULL, 1),
(6, 'GoDaddy', NULL, 0, '2021-01-19 15:23:44', '2021-01-19 15:23:44', 1, 5, NULL, 1),
(38, 'Driver Account', NULL, 0, '2021-03-08 17:03:40', '2021-03-08 17:03:40', 0, 0, NULL, 0),
(8, 'Trademark', NULL, 0, '2021-01-19 15:24:41', '2021-01-19 15:24:41', 0, 0, NULL, 1),
(9, 'Hosting', NULL, 0, '2021-01-19 15:25:07', '2021-01-19 15:25:07', 0, 0, NULL, 1),
(10, 'BDL Tempo Sketch', NULL, 0, '2021-01-19 15:25:30', '2021-01-19 15:25:30', 0, 0, NULL, 1),
(11, 'Stationary & Printing', NULL, 0, '2021-01-19 15:25:59', '2021-01-19 15:25:59', 0, 0, NULL, 1),
(12, 'Murtuza', NULL, 0, '2021-01-19 15:26:18', '2021-01-19 15:26:18', 1, 11, NULL, 1),
(13, 'Lapel Pins', NULL, 0, '2021-01-19 15:27:12', '2021-01-19 15:27:12', 1, 11, NULL, 1),
(14, 'Helpline No.', NULL, 0, '2021-01-19 15:28:26', '2021-01-19 15:28:26', 1, 4, NULL, 1),
(15, 'Payment Installment', NULL, 0, '2021-01-19 15:30:37', '2021-01-19 15:30:37', 1, 3, NULL, 1),
(16, 'Owner Numbers', NULL, 0, '2021-01-19 15:35:58', '2021-01-19 15:35:58', 1, 4, NULL, 1),
(17, 'CS Richa Goyal', NULL, 0, '2021-01-19 15:59:36', '2021-01-19 15:59:36', 1, 8, NULL, 1),
(18, 'Ezerhost', NULL, 0, '2021-01-19 16:02:58', '2021-01-19 16:02:58', 1, 9, NULL, 1),
(19, 'Ajay Sketch Artist', NULL, 0, '2021-01-19 16:11:27', '2021-01-19 16:11:27', 1, 10, NULL, 1),
(20, 'Computer', NULL, 0, '2021-01-19 16:17:15', '2021-01-19 16:17:15', 0, 0, NULL, 1),
(21, 'Nikul', NULL, 0, '2021-01-19 16:17:47', '2021-01-19 16:17:47', 1, 20, NULL, 1),
(22, 'Hiren Tshirts & Caps Printing', NULL, 0, '2021-01-19 16:19:32', '2021-01-19 16:19:32', 1, 11, NULL, 1),
(23, 'Habiba Website Content Writing', NULL, 0, '2021-01-19 16:21:32', '2021-01-19 16:21:32', 1, 3, NULL, 1),
(33, 'Tempo Charges', NULL, 0, '2021-03-02 17:05:07', '2021-03-02 17:05:57', 0, 0, NULL, 1),
(34, '3104 Tempo', NULL, 0, '2021-03-02 17:06:34', '2021-03-02 17:06:34', 1, 33, NULL, 1),
(35, 'Aatish (Animations)', NULL, 0, '2021-03-03 11:38:34', '2021-03-03 11:38:34', 1, 10, NULL, 1),
(36, '4131 Shahrukh', NULL, 0, '2021-03-03 11:40:06', '2021-03-03 11:40:06', 1, 33, NULL, 1),
(37, '3104 Sheikh Kadir', NULL, 0, '2021-03-03 11:41:49', '2021-03-03 11:41:49', 1, 33, NULL, 1),
(39, 'Pay Per Trip', NULL, 0, '2021-03-08 17:04:15', '2021-03-08 17:04:15', 1, 38, NULL, 0),
(40, 'Pay Per Parcel', NULL, 0, '2021-03-08 17:04:39', '2021-03-08 17:04:39', 1, 38, NULL, 0),
(41, 'Office Expenses', NULL, 0, '2021-03-11 11:33:07', '2021-03-11 11:33:07', 0, 0, NULL, 1),
(42, 'Standing Fan', NULL, 0, '2021-03-11 11:33:22', '2021-03-11 11:33:22', 1, 41, NULL, 1),
(43, 'CA MVG Associates', NULL, 0, '2021-03-11 13:24:42', '2021-03-11 13:24:42', 0, 0, NULL, 1),
(44, 'Firm Registration', NULL, 0, '2021-03-11 13:25:17', '2021-03-11 13:25:17', 0, 0, NULL, 1),
(45, 'Professional Fees', NULL, 0, '2021-03-11 13:25:45', '2021-03-11 13:25:45', 1, 43, NULL, 1),
(46, 'Charges - Desai Consultancy', NULL, 0, '2021-03-11 13:26:24', '2021-03-11 13:26:24', 1, 44, NULL, 1),
(47, 'Cash In Hand', NULL, 0, '2021-03-19 09:58:34', '2021-03-19 09:58:34', 0, 0, NULL, 1),
(48, 'Tempo Charges GJ 05 BW 0646', NULL, 0, '2021-03-19 09:58:46', '2021-03-19 09:58:46', 0, 0, NULL, 1),
(49, 'Tempo Charges GJ 05 CT 4131', NULL, 0, '2021-03-19 09:58:58', '2021-03-19 09:58:58', 0, 0, NULL, 1),
(50, 'SSL Certificate GoDaddy', NULL, 0, '2021-03-19 10:00:15', '2021-03-19 10:00:15', 1, 3, NULL, 1),
(51, 'Diesel Expense', NULL, 0, '2021-03-19 10:00:58', '2021-03-19 10:00:58', 1, 48, NULL, 1),
(52, 'Diesel Expense', NULL, 0, '2021-03-19 10:01:12', '2021-03-19 10:01:12', 1, 49, NULL, 1),
(53, 'Parking Charges', NULL, 0, '2021-03-19 17:29:25', '2021-03-19 17:29:25', 1, 48, NULL, 1),
(54, 'Parking Charges', NULL, 0, '2021-03-19 17:29:35', '2021-03-19 17:29:35', 1, 49, NULL, 1),
(55, 'Platform Frame with Wheels', NULL, 0, '2021-03-22 13:07:05', '2021-03-22 13:07:05', 1, 41, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_account_manage`
--

CREATE TABLE `tbl_account_manage` (
  `id` bigint(20) NOT NULL,
  `amount` decimal(11,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `account_datetime` datetime DEFAULT NULL COMMENT 'user datetime',
  `is_active` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-active,1-deactive, 2- deleted',
  `comments` varchar(500) DEFAULT NULL,
  `type` char(2) NOT NULL DEFAULT 'Dr' COMMENT 'Dr-Debit/Expense,Cr-Credit/Profit',
  `account_subcategory_id` varchar(11) NOT NULL DEFAULT '0' COMMENT 'account sub category id',
  `added_by` int(11) NOT NULL DEFAULT 0 COMMENT '0-auto else added by adminid',
  `anybillno` varchar(25) DEFAULT NULL,
  `anybillno_document` varchar(100) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_account_manage`
--

INSERT INTO `tbl_account_manage` (`id`, `amount`, `created_at`, `updated_at`, `account_datetime`, `is_active`, `comments`, `type`, `account_subcategory_id`, `added_by`, `anybillno`, `anybillno_document`, `order_id`) VALUES
(1, '6067.00', '2021-01-19 15:20:20', '2021-02-10 15:01:36', '2021-02-10 00:00:00', 0, NULL, 'Dr', '2', 5, NULL, '', NULL),
(2, '50000.00', '2021-01-19 15:31:01', '2021-01-19 15:31:01', '2020-11-05 00:00:00', 2, NULL, 'Dr', '15', 5, NULL, '', NULL),
(3, '5500.00', '2021-01-19 15:36:22', '2021-01-19 15:36:22', '2020-10-10 00:00:00', 0, NULL, 'Dr', '16', 5, NULL, '', NULL),
(4, '10700.00', '2021-01-19 16:00:25', '2021-01-19 16:00:25', '2020-10-08 00:00:00', 0, NULL, 'Dr', '17', 5, NULL, '', NULL),
(5, '2063.06', '2021-01-19 16:01:12', '2021-01-19 16:01:12', '2020-10-10 00:00:00', 0, NULL, 'Dr', '6', 5, NULL, '', NULL),
(6, '630.12', '2021-01-19 16:06:16', '2021-01-19 16:06:16', '2020-12-08 00:00:00', 0, NULL, 'Dr', '18', 5, NULL, '', NULL),
(7, '1000.00', '2021-01-19 16:12:54', '2021-01-19 16:12:54', '2020-11-26 00:00:00', 0, NULL, 'Dr', '19', 5, NULL, '', NULL),
(8, '1100.00', '2021-01-19 16:13:28', '2021-01-19 16:13:28', '2020-12-26 00:00:00', 0, NULL, 'Dr', '19', 5, NULL, '', NULL),
(9, '5001.00', '2021-01-19 16:14:34', '2021-01-19 16:14:34', '2020-12-02 00:00:00', 0, NULL, 'Dr', '12', 5, NULL, '', NULL),
(10, '16700.00', '2021-01-19 16:15:01', '2021-01-19 16:15:01', '2021-01-02 00:00:00', 0, NULL, 'Dr', '12', 5, NULL, '', NULL),
(11, '1600.00', '2021-01-19 16:15:32', '2021-01-19 16:15:32', '2020-12-15 00:00:00', 0, NULL, 'Dr', '13', 5, NULL, '', NULL),
(12, '5000.00', '2021-01-19 16:16:31', '2021-01-19 16:16:31', '2020-12-17 00:00:00', 0, NULL, 'Dr', '14', 5, NULL, '', NULL),
(13, '20000.00', '2021-01-19 16:18:03', '2021-01-19 16:18:03', '2021-01-18 00:00:00', 0, NULL, 'Dr', '21', 5, NULL, '', NULL),
(14, '5100.00', '2021-01-19 16:18:18', '2021-01-19 16:18:18', '2021-01-19 00:00:00', 0, NULL, 'Dr', '21', 5, NULL, '', NULL),
(15, '5000.00', '2021-01-19 16:19:46', '2021-01-19 16:19:46', '2021-01-01 00:00:00', 0, NULL, 'Dr', '22', 5, NULL, '', NULL),
(16, '3000.00', '2021-01-19 16:21:57', '2021-01-19 16:21:57', '2020-12-23 00:00:00', 0, NULL, 'Dr', '23', 5, NULL, '', NULL),
(18, '50000.00', '2021-02-10 15:12:35', '2021-02-10 15:12:35', '2020-11-05 00:00:00', 0, NULL, 'Dr', '15', 5, NULL, '', NULL),
(19, '100000.00', '2021-02-10 15:13:10', '2021-02-10 15:13:10', '2021-01-30 00:00:00', 0, NULL, 'Dr', '15', 5, NULL, '', NULL),
(20, '10100.00', '2021-02-10 15:14:48', '2021-02-10 15:14:48', '2021-02-10 00:00:00', 0, 'Brochure Designing and Content translation.', 'Dr', '12', 5, NULL, '', NULL),
(21, '15000.00', '2021-02-11 15:19:52', '2021-02-11 15:19:52', '2021-02-11 00:00:00', 0, 'Brochure Printing and 1k for Designing', 'Dr', '12', 5, NULL, '', NULL),
(22, '19000.00', '2021-02-20 12:09:38', '2021-02-20 12:09:38', '2021-02-19 00:00:00', 0, 'HP All in one Printer', 'Dr', '21', 5, NULL, '', NULL),
(23, '14000.00', '2021-03-01 13:49:09', '2021-03-01 13:49:09', '2021-03-01 00:00:00', 0, 'Salary Feb 2021', 'Dr', '2', 5, NULL, '', NULL),
(24, '650.00', '2021-03-02 17:04:48', '2021-03-02 17:04:48', '2021-03-02 00:00:00', 0, 'Toner Cartridge', 'Dr', '21', 5, NULL, '', NULL),
(25, '1000.00', '2021-03-03 11:36:56', '2021-03-03 11:36:56', '2021-03-03 00:00:00', 0, '3 Trips - 2 Adajan 1 Ghod Dhod RD', 'Dr', '34', 5, NULL, '', NULL),
(26, '3500.00', '2021-03-03 11:39:11', '2021-03-03 11:39:11', '2021-03-03 00:00:00', 0, '3 Animations for Posts and 1 for Website', 'Dr', '35', 5, NULL, '', NULL),
(27, '2700.00', '2021-03-11 11:34:45', '2021-03-11 11:34:45', '2021-03-10 00:00:00', 0, 'Mahesh R. Singhala (Company name on Invoice)', 'Dr', '42', 5, '2006', '', NULL),
(28, '6172.00', '2021-03-11 13:27:16', '2021-03-11 13:27:16', '2021-03-11 00:00:00', 0, NULL, 'Dr', '45', 5, 'SALE/20-21/130', '6049cd5c2fa671615449436.pdf', NULL),
(29, '2000.00', '2021-03-11 13:28:05', '2021-03-11 13:28:05', '2021-03-11 00:00:00', 0, NULL, 'Dr', '46', 5, 'SALE/20-21/ 20', '6049cd8dbc6241615449485.pdf', NULL),
(30, '824.82', '2021-03-19 10:01:50', '2021-03-19 10:01:50', '2021-03-16 00:00:00', 0, NULL, 'Dr', '50', 8, NULL, NULL, NULL),
(31, '5000.00', '2021-03-19 10:02:46', '2021-03-19 10:02:46', '2021-03-17 00:00:00', 0, 'For working expenses, getting parcels released from Transport.', 'Dr', '47', 8, NULL, NULL, NULL),
(32, '710.00', '2021-03-19 10:03:31', '2021-03-19 10:03:31', '2021-03-17 00:00:00', 0, 'Diesel expense from 01/03 to 15/03/2021. Paid from Cash in hand.', 'Dr', '51', 8, NULL, NULL, NULL),
(33, '50.00', '2021-03-19 17:30:07', '2021-03-19 17:30:38', '2021-03-19 00:00:00', 0, 'Parking at Millennium Textile Market 2. Paid from Cash in Hand.', 'Dr', '53', 5, NULL, NULL, NULL),
(34, '1600.00', '2021-03-22 13:08:18', '2021-03-22 13:08:18', '2021-03-20 00:00:00', 0, 'Metal frame with wheels to carry Parcels. 800 x 2 nos. Paid from Cash in hand.', 'Dr', '55', 5, NULL, NULL, NULL),
(35, '870.00', '2021-03-27 22:20:44', '2021-03-27 22:20:44', '2021-03-27 00:00:00', 0, 'Diesel expense from 01/03 to 15/03/2021. Paid from Cash in hand.', 'Dr', '52', 5, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_address`
--

CREATE TABLE `tbl_address` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `country` varchar(55) COLLATE utf8mb4_unicode_ci DEFAULT 'India',
  `state` varchar(55) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pincode` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `landmark` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(55) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` varchar(55) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person_number` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transporter_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_type` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'H' COMMENT 'H-Home,W-Work,O-Others',
  `added_by` int(11) NOT NULL DEFAULT 0 COMMENT '0-user self or admin id',
  `updated_by` int(11) NOT NULL DEFAULT 0 COMMENT '0-user self or admin id',
  `is_default` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1-default,0-others',
  `is_active` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-active,1-deactive, 2- deleted',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_address`
--

INSERT INTO `tbl_address` (`id`, `user_id`, `country`, `state`, `city`, `pincode`, `address`, `landmark`, `longitude`, `latitude`, `contact_person_name`, `contact_person_number`, `transporter_name`, `address_type`, `added_by`, `updated_by`, `is_default`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'India', 'Gujarat', 'Surat', '395010', '260(M) , 2ND FLOOR MILLENNIUM TEXTILE MARKET, RING ROAD , SURAT', 'Opp. Universal Textile Market', '72.84154415130615', '21.188213159176268', NULL, NULL, NULL, 'W', 5, 9, 1, 0, '2021-01-19 16:35:28', '2021-01-20 11:55:16'),
(2, 2, 'India', 'GUJARAT', 'SURAT', '395003', 'A-1-BLOCK, 3004, REGENT TEXTILE MARKET, RING ROAD, SURAT', 'NA', '72.8383469581604', '21.188303191073263', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 11:52:53', '2021-01-20 11:52:53'),
(3, 3, 'India', 'Gujarat', 'Surat', '395010', 'SHOP NO 1, GROUND FLOOR, HAPPY PALACE, LH ROAD B/H CHAMUNDA NAGAR, SURAT, Surat, Gujarat, 395010', 'NA', '72.85143613815308', '21.20421796784579', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 12:07:24', '2021-01-20 12:07:24'),
(4, 4, 'India', 'Gujarat', 'Surat', '395010', 'A-92,, KRISHNA RAW HOUSE, PUNAGAM,, SURAT, Surat, Gujarat, 395010', 'NA', '72.76085249682617', '21.127873772045742', NULL, NULL, NULL, 'H', 9, 8, 1, 0, '2021-01-20 12:40:46', '2021-01-20 15:08:22'),
(5, 5, 'India', 'GUJARAT', 'SURAT', '395003', '10/137, SHOP NO.2, SUKH SAGAR APPARTMENT, YAMUNA BAUG, CHAUTA BAZAR, SURAT, Surat, Gujarat, 395003', 'NA', '72.82472267746925', '21.19858897421009', NULL, NULL, NULL, 'H', 9, 9, 1, 0, '2021-01-20 13:00:10', '2021-01-20 13:00:10'),
(6, 6, 'India', 'GUJARAT', 'SURAT', '395006', '95, MOHAN BAUG SHOPPING CENTER, VARACHHA, SURAT, Surat, Gujarat, 395006', 'NA', '72.85629622241211', '21.146446761013088', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 13:04:02', '2021-01-20 13:04:02'),
(7, 7, 'India', 'GUJARAT', 'SURAT', '395009', '17/B, SHOP NO.18, CAPITAL COMPLEX, BESIDE ANAVALI PETROL PUMP, HANI PARK ROAD, ADAJAN , SURAT, Surat, Gujarat, 395009', 'NA', '72.85217634936524', '21.158614008600775', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 13:06:56', '2021-01-20 13:06:56'),
(8, 8, 'India', 'GUJARAT', 'SURAT', '395002', 'SHOP NO.F-10, FIRST, JOLLY ARCADE, GHOD DOD ROAD, SURAT, Surat, Gujarat, 395002', 'NA', '72.82196394702149', '21.1432446876119', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 13:08:44', '2021-01-20 13:08:44'),
(9, 9, 'India', 'GUJARAT', 'SURAT', '395001', 'L 2 LOAR GROUND FLOOR, LALBHAI CONTRACTOR COMPLEX, OPP-PARSI LIBRERY COURT ROAD, NANPURA SURAT, Surat, Gujarat, 395001', 'NA', '72.89062849780274', '21.127873772045742', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 13:23:30', '2021-01-20 13:23:30'),
(10, 10, 'India', 'GYJARAT', 'SURAT', '395005', '116/D NODH NO-0012/179 PAIKI SHOP NO-18, PUNITNAGAR SHOPING CENTER, PUNITNAGAR CO OP HOUSING SOCIETY, RANDER ROAD, PALANPUR PATIYA, Surat, Gujarat, 395005', 'NA', '72.85904280444336', '21.125952295528105', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 13:26:01', '2021-01-20 13:26:01'),
(11, 11, 'India', 'GUJARAT', 'SURAT', '394327', 'SHOP NO 8, THAKORJI COMPLEX, KADODARA, SURAT, Surat, Gujarat, 394327', 'NA', '72.97577254077149', '21.172701151245562', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 13:57:38', '2021-01-20 13:57:38'),
(12, 12, 'India', 'GUJARAT', 'SURAT', '395003', '36-A / SHOP NO. L5 AND L6, ASCON CITY, CITY LIGHT ROAD, SURAT, Surat, Gujarat, 395003', 'NA', '72.88994185229492', '21.13491897289154', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 14:11:14', '2021-01-20 14:11:14'),
(13, 13, 'India', 'GUJARAT', 'SURAT', '380015', 'A 203, DIVINE LIFE AVENUE, RAMDEVNAGAR ROAD, SATELLITE, Ahmedabad, Gujarat, 380015', 'NA', '72.81937607093279', '21.428931352314763', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 14:13:28', '2021-01-20 14:13:28'),
(14, 14, 'India', 'GUJARAT', 'SURAT', '395001', '10/1940 SHOP NO 02, GROUND FLOOR, PADMALAYA BUILDING, SONI FALIA MAIN ROAD GOPIPURA, SURAT, Gujarat, 395001', 'NA', '72.85148970385742', '21.161815749679977', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 14:15:16', '2021-01-27 11:30:54'),
(15, 15, 'India', 'GUJARAT', 'SURAT', '395006', '87, 87, MOHANBAG SHOPPING CENTRE, VARACHHA ROAD,, VARACHHA, Surat, Gujarat, 395006', 'NA', '72.87002913256836', '21.19959106171017', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 14:17:30', '2021-01-20 14:17:30'),
(16, 16, 'India', 'GUJARAT', 'SURAT', '395004', 'A/12 SHOP NO 1, OPP GREEN PARK SOCIETY, SHNEHSAGAR SOCIETY, SINGANPOR ROAD, KATARGAM, Surat, Gujarat, 395004', 'NA', '72.83157698413086', '21.218155048765077', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 14:19:06', '2021-01-20 14:19:06'),
(17, 17, 'India', 'GUJARAT', 'SURAT', '395006', 'PLOT NO.2, GR FLOOR, KHODIYAR COLONY NEAR HAPPY BUNGALOWS, L H ROAD, VARACHHA, Surat, Gujarat, 395006', 'NA', '72.86796919604492', '21.202151750403367', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 14:48:40', '2021-01-20 14:48:40'),
(18, 18, 'India', 'GUJARAT', 'SURAT', '395001', 'L-7, AMIZARA APPARTMENT, NEAR SURAT TENNIS CLUB, ATHWALINES, Surat, Gujarat, 395001', 'NA', '72.8116642644043', '21.175262305811884', NULL, NULL, NULL, 'H', 9, 9, 1, 0, '2021-01-20 14:50:32', '2021-01-20 14:50:32'),
(19, 19, 'India', 'GUJARAT', 'SURAT', '395008', '1A - 730, LOAR GROUND, OPP LALBHAI CONTRACTOR BUILDING, NANPURA, SURAT, Surat, Gujarat, 395008', 'NA', '72.81784407397461', '21.177823416029995', NULL, NULL, NULL, 'W', 9, 9, 0, 0, '2021-01-20 14:53:46', '2021-04-07 11:28:36'),
(20, 20, 'India', 'GUJARAT', 'SURAT', '395006', 'plot no 10, G Floor, Hans Society, Varachha road, Opp.Baroda Pristage, Surat, Gujarat, 395006', 'NA', '72.86453596850586', '21.198950882600844', NULL, NULL, NULL, 'H', 9, 9, 1, 0, '2021-01-20 14:55:24', '2021-01-20 14:55:24'),
(21, 21, 'India', 'GUJARAT', 'SURAT', '395009', '9/1365, NEAR KASHI VISHWANATH TEMPLE, BALAJI ROAD, NEAR KASHI VISHWANATH TEMPLE, BHAGAL, Surat, Gujarat, 395009', 'NA', '72.82745711108399', '21.19190872931089', NULL, NULL, NULL, 'H', 9, 9, 1, 0, '2021-01-20 14:57:03', '2021-01-20 14:57:03'),
(22, 22, 'India', 'GUJARAT', 'SURAT', '394101', 'PLOT NO.60 TO 64,, KHODAL CHHAYA ROW HOUSE, SETELIGHT ROAD,MOTA VARACHHA,, SURAT, Surat, Gujarat, 394101', 'NA', '72.83089033862305', '21.176542866464743', NULL, NULL, NULL, 'H', 9, 9, 1, 0, '2021-01-20 14:58:33', '2021-01-20 14:58:33'),
(23, 23, 'India', 'GUJARAT', 'SURAT', '395002', '10A/137/A/1, Shop U-51-52, Balkrushna Square,, Mota Mandir, Chautabazar, Surat, Gujarat, 395002', 'NA', '72.83638350268555', '21.19190872931089', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 14:59:59', '2021-01-20 14:59:59'),
(24, 24, 'India', 'GUJARAT', 'SURAT', '394221', 'P NO - 400/7, C-1B, GIDC PANDESRA, SURAT, Surat, Gujarat, 394221', 'NA', '72.84668318530274', '21.1297952236628', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 15:02:03', '2021-01-20 15:02:03'),
(25, 25, 'India', 'Gujarat', 'Surat', '395006', '100/2, NAVDURGA NAGAR SOCIETY, NANA VARACHHA MAIN ROAD, KAPODARA, Surat, Gujarat, 395006', 'NA', '72.83569685717774', '21.198310700717236', NULL, NULL, NULL, 'H', 9, 9, 1, 0, '2021-01-20 15:04:08', '2021-01-20 15:04:08'),
(26, 26, 'India', 'Gujarat', 'SURAT', '395007', 'U-23, 1st FLOOR, CENTRAL PLAZA, CITY LIGHT ROAD, SURAT, Surat, Gujarat, 395007', 'NA', '72.77183882495117', '21.15477182789662', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 15:06:22', '2021-01-20 15:06:22'),
(27, 27, 'India', 'Gujarat', 'Surat', '394210', 'PLOT NO 8, HARI ICHHA INDUSTRIAL, UDHANA, SURAT, Surat, Gujarat, 394210', 'NA', '72.84393660327149', '21.145165939958254', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 15:07:43', '2021-01-20 15:07:43'),
(28, 28, 'India', 'Gujarat', 'Surat', '395009', 'u 13, golden park, nr prime market, Surat, Gujarat, 395009', 'NA', '72.77527205249024', '21.2066328487911', NULL, NULL, NULL, 'H', 9, 9, 1, 0, '2021-01-20 15:10:17', '2021-01-20 15:10:17'),
(29, 29, 'India', 'Gujarat', 'Surat', '395004', 'Plot No. 20/21, UMIYA SALES AGENCY, NEAR GAYATRY SHAKTIPITH,VASTA DEVDI ROAD, KATARGAM, Surat, Gujarat, 395004', 'NA', '72.82951704760742', '21.209192916917097', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 15:12:21', '2021-01-20 15:12:21'),
(30, 30, 'India', 'Gujarat', 'Surat', '395010', 'SHOP NO 03, LABHUBA COMPLEX, NEAR STATE BANK OF INDIA, AAIMATA ROAD, PARVAT PATIA, Surat, Gujarat, 395010', 'NA', '72.8885685612793', '21.189347863085708', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 15:13:44', '2021-01-20 15:13:44'),
(31, 31, 'India', 'Gujarat', 'Surat', '395003', '9/1635,, KELAPITH,, GOODLUCK GALI,, SURAT, Surat, Gujarat, 395003', 'NA', '72.83638350268555', '21.19382934985794', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 15:15:05', '2021-01-20 15:15:05'),
(32, 32, 'India', 'Gujarat', 'Surat', '394210', '151, Madhusudan Row House, Nr, Hariom Row House, Godadara, Surat, Gujarat, 394210', 'NA', '72.94006697436524', '21.20279191564029', NULL, NULL, NULL, 'H', 9, 9, 1, 0, '2021-01-20 15:16:34', '2021-01-20 15:16:34'),
(33, 33, 'India', 'Gujarat', 'Surat', '395002', 'SY NO-2, 4658, OPP.KABIR MANDIR, THOBA SHERI, SAGRAMPURA, Surat, Gujarat, 395002', 'NA', '72.81578413745117', '21.186786952488387', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 15:17:57', '2021-01-20 15:17:57'),
(34, 34, 'India', 'Gujarat', 'Surat', '395006', 'SY NO.91, PLOT NO.134, SHOP.F/8, 1ST FLOOR, GANGA PALACE SHOPPING CENTER, BARODA PRISTAGE, VARACHHA ROAD, Surat, Gujarat, 395006', 'NA', '72.86728255053711', '21.201511582391813', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 15:19:17', '2021-01-20 15:19:17'),
(35, 35, 'India', 'Gujarat', 'Valsad', '396001', '92/12/1, AMRUT SHERI, CHHIPWAD, VALSAD, Valsad, Gujarat, 396001', 'NA', '72.93107554432943', '20.59946539319605', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 15:21:07', '2021-01-20 15:21:07'),
(36, 36, 'India', 'Gujarat', 'Surat', '395006', 'SY NO.77/3, 2ND FLOOR, TIRUPATI SOCIETY, VARACHHA ROAD, SURAT, Surat, Gujarat, 395006', 'NA', '72.86796919604492', '21.2066328487911', NULL, NULL, NULL, 'H', 9, 9, 1, 0, '2021-01-20 15:23:29', '2021-01-20 15:23:29'),
(37, 37, 'India', 'Gujarat', 'Surat', '395003', 'SHOP NO.303,, THIRD, INDRALOK MARKET,, KHAPATIYA CHAKLA, CHAUTA BAZAR,, SURAT, Surat, Gujarat, 395003', 'NA', '72.82951704760742', '21.17718314263335', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 15:26:26', '2021-01-20 15:26:26'),
(38, 38, 'India', 'Gujarat', 'Surat', '395003', '10/473, BASEMENT, HEMDEEP APPARTMENT, NAGAR FALIYA, AMBAJI ROAD, Surat, Gujarat, 395003', 'NA', '72.84668318530274', '21.194469551159585', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 15:27:37', '2021-01-20 15:27:37'),
(39, 39, 'India', 'Gujarat', 'Surat', '394210', '17, VINAY NAGAR, UDHNA, SURAT, Surat, Gujarat, 394210', 'NA', '72.84599653979492', '21.14132341035029', NULL, NULL, NULL, 'H', 9, 9, 1, 0, '2021-01-20 15:28:52', '2021-01-20 15:28:52'),
(40, 40, 'India', 'Gujarat', 'Surat', '395003', '4/16, GHANCHI SHERI, OPP.TOWER,BHAGAL, SURAT, Surat, Gujarat, 395003', 'NA', '72.83157698413086', '21.179324045941158', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 15:30:14', '2021-01-20 15:30:14'),
(41, 41, 'India', 'Gujarat', 'Surat', '395003', '944, OPP. TARATIYA HANUMAAN MANDIR, HARIPURA, SURAT, Surat, Gujarat, 395003', 'NA', '72.82059065600586', '21.183585751850057', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 15:31:32', '2021-01-20 15:31:32'),
(42, 42, 'India', 'Gujarat', 'Surat', '395003', '501, 5th floor, MULTZIM APARTMENT, NEAR NARIMAN HOME, SHAHPOR, Surat, Gujarat, 395003', 'NA', '72.82608382006836', '21.19703032862747', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 15:32:48', '2021-01-20 15:32:48'),
(43, 43, 'India', 'Gujarat', 'Surat', '395001', '10/490, PANINI BHIT, NAGAR FALIA, SONI FALIA, Surat, Gujarat, 395001', 'NA', '72.82402388354492', '21.17974421958744', NULL, NULL, NULL, 'H', 9, 9, 1, 0, '2021-01-20 15:34:05', '2021-01-20 15:34:05'),
(44, 44, 'India', 'Gujarat', 'Surat', '395002', 'SHOP NO : 602-603, OPPER GROUND, KOHINOOR TEXTILE MARKET, RING ROAD, SURAT', 'NA', '72.83707014819336', '21.194469551159585', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 15:35:33', '2021-01-20 15:35:33'),
(45, 45, 'India', 'Gujarat', 'Surat', '395002', 'SHOP NO 308, 3RD FLOOR, CITY TEXTILE MARKET, RING ROAD, SURAT, Surat, Gujarat, 395002', 'NA', '72.85492293139649', '21.1483679718299', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 15:38:55', '2021-01-20 15:38:55'),
(46, 46, 'India', 'Gujarat', 'Surat', '395003', '10/350, THAKORDHAWAR COMPLEX, CHAUTA BAZAR, SURAT, Surat, Gujarat, 395003', 'NA', '72.84599653979492', '21.143885107829135', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 15:40:00', '2021-01-20 15:40:00'),
(47, 47, 'India', 'Gujarat', 'Surat', '395004', 'SURVEY NO 412 PLOT NO 95-B, PLOT- NO 8, VASTADEVI ROAD, KATARGAM, Surat, Gujarat, 395004', 'NA', '72.82608382006836', '21.185506480551542', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 15:41:19', '2021-01-20 15:41:19'),
(48, 48, 'India', 'Gujarat', 'Ahmedabad', '380005', '1,, 1ST FLOOR,, NEW PARAS MANI APPARTMENT, TULSI NAGAR, SABARMATI, AHMEDABAD, Ahmedabad, Gujarat, 380005', 'NA', '72.8556095769043', '21.223275737818245', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 15:45:11', '2021-01-20 15:45:11'),
(49, 49, 'India', 'Gujarat', 'Surat', '395003', '10/563, HAWADIA CHAKLA CHAR RASTA, JOITA MAHADEV STREET, GOPIPURA, SURAT, Surat, Gujarat, 395003', 'NA', '72.80479780932617', '21.168219024054675', NULL, NULL, NULL, 'H', 9, 9, 1, 0, '2021-01-20 15:46:30', '2021-01-20 15:46:30'),
(50, 50, 'India', 'Gujarat', 'Surat', '395004', '1-2 ANKUR STEEL, GOPINATH COMPLEX, NR AXIS BANK, CAUSEWAY ROAD, SINGANPORE CHAR RASTA, KATARGAM, SURAT, Surat, Gujarat, 395004', 'NA', '72.81578413745117', '21.220075327984237', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 15:47:55', '2021-01-20 15:47:55'),
(51, 51, 'India', 'Gujarat', 'Surat', '395002', '3/3024, FIRST FLOOR, AKBAR SAHID TEKRA, RUSTAMPURA, SURAT, Surat, Gujarat, 395002', 'NA', '72.8501164128418', '21.14004254500164', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 15:49:43', '2021-01-20 15:49:43'),
(52, 52, 'India', 'Gujarat', 'Surat', '395002', 'G/1, NEW CLOTH MARKET, RINGROAD, SURAT, Surat, Gujarat, 395002', 'NA', '72.85629622241211', '21.182305252185706', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 15:50:51', '2021-01-20 15:50:51'),
(53, 53, 'India', 'Gujarat', 'Surat', '395006', 'PLOT NO.60, NEW BORODA PRASTIGE, L H ROAD, SURAT, Surat, Gujarat, 395006', 'NA', '72.84599653979492', '21.18998808380205', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 15:51:58', '2021-01-20 15:51:58'),
(54, 54, 'India', 'Gujarat', 'Surat', '395010', '141, RADHAMANDIR SOCIETY-3, NR.SAVALIYA CIRCLE, YOGI CHOWK PUNAGAM, Surat, Gujarat, 395010', 'NA', '72.84805647631836', '21.154131454751973', NULL, NULL, NULL, 'H', 9, 9, 1, 0, '2021-01-20 15:53:12', '2021-01-20 15:53:12'),
(55, 55, 'India', 'Gujarat', 'Surat', '395010', 'PLOT NO. 31, SHOP NO. 3, GROUND FLOOR, NARNARAYAN SOCIETY, PARVAT PATIYA ROAD, DUMBHAL, Surat, Gujarat, 395010', 'NA', '72.84530989428711', '21.149008369897786', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 15:54:10', '2021-01-20 15:54:10'),
(56, 56, 'India', 'Gujarat', 'Surat', '395003', '7/2748-2749, BASEMENT, VRAJVATIKA APPARTMENT, CHANDULAL SHETH STREET, SAIYEDPURA, Surat, Gujarat, 395003', 'NA', '72.85835615893555', '21.168219024054675', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 15:55:06', '2021-01-20 15:55:06'),
(57, 57, 'India', 'Gujarat', 'Surat', '395003', '5-121-1022, 1026-10127 SHOP NO 3, SIDDH CHAMBERS, GUJJAR FALIYA HARIPURA, SURAT, Surat, Gujarat, 395003', 'NA', '72.82883040209961', '21.185506480551542', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 15:56:08', '2021-01-20 15:56:08'),
(58, 58, 'India', 'Gujarat', 'Surat', '395006', 'G-15-16,, TORAN SHOPPING CENTER, BARODA PRISTAGE, VARACHHA ROAD, Surat, Gujarat, 395006', 'NA', '72.86041609545899', '21.1483679718299', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 15:57:20', '2021-01-20 15:57:20'),
(59, 59, 'India', 'Gujarat', 'Surat', '395006', 'B-25, Under Ground, Achkan Bazar, Baroda Prestage, Varacha Road, Surat, Surat, Gujarat, 395006', 'NA', '72.86728255053711', '21.200231238045156', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 15:58:57', '2021-01-20 15:58:57'),
(60, 60, 'India', 'Gujarat', 'Surat', '395006', '21/22,, THAKORDWAR SOCIETY,, VARACHHA ROAD,, SURAT, Surat, Gujarat, 395006', 'NA', '72.87414900561524', '21.200231238045156', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 16:00:01', '2021-01-20 16:00:01'),
(61, 61, 'India', 'Gujarat', 'Surat', '395003', '10/603, Ground Floor, Omkar Building, Khapatiya Chakala, Surat, Surat, Gujarat, 395003', 'NA', '72.80754439135742', '21.17013995233537', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 16:01:06', '2021-01-20 16:01:06'),
(62, 62, 'India', 'Gujarat', 'Surat', '394210', 'P 181-182, SUBHASH NAGAR 2, LIMBAYAT, Surat, Gujarat, 394210', 'NA', '72.84119002124024', '21.145806351869997', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 16:01:58', '2021-01-20 16:01:58'),
(63, 63, 'India', 'Gujarat', 'Surat', '395003', 'plot no-, 9,, Dinesh Niwas.b/h trikam nagar,nr chamunda Nagar,, l.h.road, varachha, Surat, Gujarat, 395003', 'NA', '72.85217634936524', '21.149648765196687', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 16:02:56', '2021-01-20 16:02:56'),
(64, 64, 'India', 'Gujarat', 'Surat', '395006', 'PLOT NO 124, GROUND FLOOR, OLD BARODA PRISTAGE, VARACHHA ROAD, VARACHHA, Surat, Gujarat, 395006', 'NA', '72.84393660327149', '21.168219024054675', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 16:04:05', '2021-01-20 16:04:05'),
(65, 65, 'India', 'Gujarat', 'Surat', '395009', 'M-15,, KRISHNA COMPLEX,, GUJARAT GAS CIRCLE,, ADAJAN ROAD,, Surat, Gujarat, 395009', 'NA', '72.83157698413086', '21.150929547487287', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 16:05:24', '2021-01-20 16:05:24'),
(66, 66, 'India', 'Gujarat', 'Surat', '395001', '10 2024, SONI FALIYA, PANI NI BHIT MAIN ROAD, VAGHESHVAR MAINPOL SONIFALIYA, Surat, Gujarat, 395001', 'NA', '72.83501021166992', '21.15541219827168', NULL, NULL, NULL, 'H', 9, 9, 1, 0, '2021-01-20 16:06:22', '2021-01-20 16:06:22'),
(67, 67, 'India', 'Gujarat', 'Surat', '395006', 'PLOT NO 56, VARACHHA ROAD, NEW BARODA PRISTAGE, VARACHHA, SURAT, Surat, Gujarat, 395006', 'NA', '72.85492293139649', '21.152210318701176', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 16:07:14', '2021-01-20 16:07:14'),
(68, 68, 'India', 'Gujarat', 'Surat', '395006', 'PLOT NO-96/2, BARODA PRISTAGE, VARACHHA ROAD, SURAT, Surat, Gujarat, 395006', 'NA', '72.87964216967774', '21.175262305811884', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 16:08:53', '2021-01-20 16:08:53'),
(69, 69, 'India', 'Gujarat', 'Surat', '395006', 'SHOP NO.42,43, NEW B P COMPUND, B/H LABHESHWAR POLICE CHOWKI, L H ROAD, VARACHHA, Surat, Gujarat, 395006', 'NA', '72.94487349291992', '21.1483679718299', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 16:09:44', '2021-01-20 16:09:44'),
(70, 70, 'India', 'Gujarat', 'Surat', '394210', 'A-19, NEAR CHANDRALOK SOCIETY, BAJRANG NAGAR SOCIETY, PARVAT GAM, SURAT, Surat, Gujarat, 39421', 'NA', '72.84256331225586', '21.1432446876119', NULL, NULL, NULL, 'H', 9, 9, 1, 0, '2021-01-20 16:11:08', '2021-01-20 16:11:08'),
(71, 71, 'India', 'Gujarat', 'Surat', '395003', '4/4835 SHOP NO.1, CHIKU APARTMENT, SEFI STREET, ZAMPA BAZAR, Surat, Gujarat, 395003', 'NA', '72.84187666674805', '21.17078025621991', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 16:12:35', '2021-01-20 16:12:35'),
(72, 72, 'India', 'Gujarat', 'Surat', '395003', 'SHOP NO 208,, SECOND FLOOR, INDRALOK,, KHAPATIYA CHAKLA,, H NO 9/1463B, 1437, 1441 TO 1443, NR INDRAPRASTH MKT, Surat, Gujarat, 395003', 'NA', '72.89337507983399', '21.14260426462631', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 16:15:03', '2021-01-20 16:15:03'),
(73, 73, 'India', 'Gujarat', 'Surat', '394210', 'A/24, UDHNA UDHYOG NAGAR, UDHNA, Surat, Gujarat, 394210', 'NA', '72.84324995776367', '21.146446761013088', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 16:16:02', '2021-01-20 16:16:02'),
(74, 74, 'India', 'Gujarat', 'Surat', '395007', '54-58, SAI ASHISH IND SOCIETY, NAVJIVAN MOTORS PVT LTD, DEVCHAND NAGAR, UDHNA-MAGDALLA ROAD, Surat, Gujarat, 395007', 'NA', '72.84119002124024', '21.137480781088602', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 16:17:16', '2021-01-20 16:17:16'),
(75, 75, 'India', 'Gujarat', 'Surat', '395006', 'SHOP 1, B P COMPOUND, NR. PUNAM MECHING BARODA PRISTAGE, VARACHHA, Surat, Gujarat, 395006', 'NA', '72.87483565112305', '21.20343207810253', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 16:18:24', '2021-01-20 16:18:24'),
(76, 76, 'India', 'Gujarat', 'Surat', '395003', '11/1466, 1ST FLOOR, CHINIWALA NI POLE, NANAVAT, SURAT, Surat, Gujarat, 395003', 'NA', '72.85835615893555', '21.17013995233537', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 16:19:32', '2021-01-20 16:19:32'),
(77, 77, 'India', 'Gujarat', 'Surat', '395006', 'shop no 3, BASEMENT, TRISHUL COMPLEX, BARODA PRISTEGE VARACHHA, SURAT, Surat, Gujarat, 395006', 'NA', '72.87689558764649', '21.20535254884047', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 16:20:24', '2021-01-20 16:20:24'),
(78, 78, 'India', 'Gujarat', 'Surat', '395007', '53/84, GAURAV PATH MAIN ROAD, SURAT, Surat, Gujarat, 395007', 'NA', '72.81303755541992', '21.17206085567508', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 16:21:28', '2021-01-20 16:21:28'),
(79, 79, 'India', 'Gujarat', 'Surat', '395002', 'H.NO.10/143-147, SHOP NO.8, 1ST FLOOR TIRUPATI PLAZA MOTA MANDIR ROAD, CHAUTA BAZAR, SURAT, Surat, Gujarat, 395002', 'NA', '72.82539717456055', '21.15797365207468', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 16:23:13', '2021-01-20 16:23:13'),
(80, 80, 'India', 'Gujarat', 'Surat', '395002', 'PLOT NO.4/2328 PAIKI CELLER, SHOP NO 124, NEW CLOTH MARKET, MOTI BEGAMWADI,SALABATPURA, SURAT, Surat, Gujarat, 395002', 'NA', '72.82745711108399', '21.18636679885674', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 16:24:08', '2021-01-20 16:24:08'),
(81, 81, 'India', 'Gujarat', 'Surat', '395009', '6, GANGA JAMNA APPARTMENT, ADAJAN PATIYA, ADAJAN, Surat, Gujarat, 395009', 'NA', '72.84942976733399', '21.17206085567508', NULL, NULL, NULL, 'H', 9, 9, 1, 0, '2021-01-20 16:24:59', '2021-01-20 16:24:59'),
(82, 82, 'India', 'Gujarat', 'Surat', '395006', 'PLOT NO.106, G.F., BARODA PRISTAGE, VARACHHA ROAD, SURAT, Surat, Gujarat, 395006', 'NA', '72.82127730151367', '21.186786952488387', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 16:25:55', '2021-01-20 16:25:55'),
(83, 83, 'India', 'Gujarat', 'Surat', '395007', '56, SHOP NO 4-5, SARDAR SHOPING CENTRE, VARACHHA ROAD, BARODA PRISTAGE, Surat, Gujarat, 395007', 'NA', '72.82677046557617', '21.190848376022462', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 16:27:06', '2021-01-20 16:27:06'),
(84, 84, 'India', 'Gujarat', 'Surat', '395001', '32-A PLOAT NO. 24-25, S.K.INDUSTRIAL SOCIETY-2, BAMROLI ROAD, MAJURA, Surat, Gujarat, 395001', 'NA', '72.86522261401367', '21.156272691601753', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 16:27:56', '2021-01-20 16:27:56'),
(85, 85, 'India', 'Gujarat', 'Surat', '395003', 'SHOP NO 7 - 8, GROUND FLOOR, SHREE BALKRUSHNA SQUARE, MOTA MANDIR, CHAUTA PUL, Surat, Gujarat, 395003', 'NA', '72.8006779362793', '21.171420557333175', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 16:28:54', '2021-01-20 16:28:54'),
(86, 86, 'India', 'Gujarat', 'Surat', '395003', '9/1290, Ground Floor, Venus Appt, Balaji Road, Nr Surat Stri Mandal, Surat, Gujarat, 395003', 'NA', '72.85972944995117', '21.168859336252677', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 16:29:53', '2021-01-20 16:29:53'),
(87, 87, 'India', 'Gujarat', 'Surat', '395006', '18 CTP NO-16 SHOP NO 5-6, 1ST FLOOR SARASWATI CHAMBER, BARODA PRISTAGE, VARACHHA, Surat, Gujarat, 395006', 'NA', '72.80891768237305', '21.17206085567508', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 16:30:54', '2021-01-20 16:30:54'),
(88, 88, 'India', 'Gujarat', 'Surat', '395010', 'FLAT NO 118,PART-D,, FIRST FLOOR,, RESHAM BHAVAN APPT,, B/H,KUBER NAGAR,KARANJ,BOMBAY MARKET ROAD,, SURAT, Surat, Gujarat, 395010', 'NA', '72.85217634936524', '21.184446081333082', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 16:31:53', '2021-01-20 16:31:53'),
(89, 89, 'India', 'Gujarat', 'Surat', '394210', 'SHOP NO 2044, A P MARKET, MAIN ROAD OPP HARI NAGAR, UDHNA, Surat, Gujarat, 394210', 'NA', '72.83638350268555', '21.138761668580884', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 16:32:57', '2021-01-20 16:32:57'),
(90, 90, 'India', 'Gujarat', 'Surat', '395010', 'SHOP NO. 1 AND 2, 9/446, STORE SHERI NAKA, STORE SHERI NAKA, WADIFALIA, Surat, Gujarat, 395010', 'NA', '72.83775679370117', '21.16117540700454', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 16:33:49', '2021-01-20 16:33:49'),
(91, 91, 'India', 'Gujarat', 'Surat', '394107', 'PLOT NO - 89, ADARSHNAGAR-2/B, CHHAPRABHATHA ROAD, SURAT, Surat, Gujarat, 394107', 'NA', '72.81441084643555', '21.215594637604802', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 16:34:51', '2021-01-20 16:34:51'),
(92, 92, 'India', 'Gujarat', 'Surat', '394210', '4, HIRANAND SHOPPING CENTER, UDHNA BHESTAN ROAD, UDHNA, Surat, Gujarat, 394210', 'NA', '72.85286299487305', '21.144525525277942', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 16:35:47', '2021-01-20 16:35:47'),
(93, 93, 'India', 'Gujarat', 'Ahmedabad', '380051', 'Ground Floor, Kataria Arcade, B/h Adani CNG Pump, Off. S.G. Highway, Makarba, Ahmedabad, Gujarat, 380051', 'NA', '72.85423628588867', '21.27213336820644', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 16:38:16', '2021-01-20 16:38:16'),
(94, 94, 'India', 'Gujarat', 'Surat', '395006', 'SY NO.91, PLOT NO.91, B P COMPOUND, BARODA PRISTAGE, VARACHHA MAIN ROAD, SURAT, Surat, Gujarat, 395006', 'NA', '72.87620894213867', '21.198950882600844', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 16:39:28', '2021-01-20 16:39:28'),
(95, 95, 'India', 'Gujarat', 'Surat', '395003', '3/38, GALILYARA SHERI, BHAGAL MAIN ROAD, DHIRAJ MITHAI GALI, Surat, Gujarat, 395003', 'NA', '72.83569685717774', '21.188067413333293', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 16:40:54', '2021-01-20 16:40:54'),
(96, 96, 'India', 'Gujarat', 'Surat', '395006', 'SHOP NO. 110,111,210,211,212, BARODA HOUSE, BARODA PRISTAGE, VARACHHA ROAD, SURAT, Surat, Gujarat, 395006', 'NA', '72.82745711108399', '21.189567939269768', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 16:42:12', '2021-01-20 16:42:12'),
(97, 97, 'India', 'Gujarat', 'Surat', '395003', '9/1301, CELLER BALAJI MARKET, BALAJI ROAD, SURAT, Surat, Gujarat, 395003', 'NA', '72.82608382006836', '21.17013995233537', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 16:43:07', '2021-01-20 16:43:07'),
(98, 98, 'India', 'Gujarat', 'Surat', '395003', '9/1665, KELAPITH, MAIN ROAD, NR VENI SONS, Surat, Gujarat, 395003', 'NA', '72.79655806323242', '21.149648765196687', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 16:44:10', '2021-01-20 16:44:10'),
(99, 99, 'India', 'Gujarat', 'Surat', '395006', 'G-12,, GROUND FLOOR,, ACHKAN BAZAR,, BARODA PRISTAGE, VARACHHA ROAD, SURAT, Surat, Gujarat, 395006', 'NA', '72.86453596850586', '21.197670516059425', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 16:46:10', '2021-01-20 16:46:10'),
(100, 100, 'India', 'Gujarat', 'Surat', '395008', '187, BHAGIRATH SOCEITY 1, MARUTI CHOWK, L H ROAD, SURAT, Surat, Gujarat, 395008', 'NA', '72.85698286791992', '21.184225997523285', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 16:47:08', '2021-01-20 16:47:08'),
(101, 101, 'India', 'Gujarat', 'Banaskantha', '385001', '10/271, URMISH APARTMENT, YAMUNA BAGU KELAPITH, KELAPITH SURAT, Surat, Gujarat, 385001', 'NA', '73.06640974780274', '21.18998808380205', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 17:02:33', '2021-01-20 17:02:33'),
(102, 102, 'India', 'Gujarat', 'Surat', '395003', '10A/137, A2, SHOP NO. - L 16, UNDER GROUND, V.K. CHAMBERS, YAMUNABAGH, CHAUTA BAZAAR, SURAT, Surat, Gujarat, 395003', 'NA', '72.81921736499024', '21.17974421958744', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 17:03:50', '2021-01-20 17:03:50'),
(103, 103, 'India', 'Gujarat', 'Surat', '394210', 'G-63,64, SILICON SHOPPERS, UDHNA ROAD, UDHNA, Surat, Gujarat, 394210', 'NA', '72.84050337573242', '21.143885107829135', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 17:04:47', '2021-01-20 17:04:47'),
(104, 104, 'India', 'Gujarat', 'Surat', '395003', '10/109 1/113 115 TO 131 137 A PAIKI, SHOP NO 12 VRAJRATNA APPARTMENT, CHAUTA BAZAR, SURAT, Surat, Gujarat, 395003', 'NA', '72.81921736499024', '21.188287491423917', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 17:05:51', '2021-01-20 17:05:51'),
(105, 105, 'India', 'Gujarat', 'Surat', '395004', 'PLOT NO.-13, GOPINATH CO-OP. HOU. SOCIETY, OPP. HARIOM MILL, VED ROAD, Surat, Gujarat, 395004', 'NA', '72.82745711108399', '21.213674300085923', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 17:06:40', '2021-01-20 17:06:40'),
(106, 106, 'India', 'Gujarat', 'Surat', '395003', 'SHOP NO - UG -306/307,, SAKAR SHOPPING CENTER, BESIDE MANGALDAS, BHATAR ROAD, SURAT, Surat, Gujarat, 395003', 'NA', '72.81029097338867', '21.158614008600775', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 17:07:32', '2021-01-20 17:07:32'),
(107, 107, 'India', 'Gujarat', 'Surat', '395003', 'SHOP NO 18, L GR FLOOR, SHREE BALKRISHNA SQUARE, MOTA MANDIR, CHOWTA BAZAR, Surat, Gujarat, 395003', 'NA', '72.85286299487305', '21.17013995233537', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 17:08:21', '2021-01-20 17:08:21'),
(108, 108, 'India', 'Gujarat', 'Surat', '395002', '3/2940 SHOP NO 7, GROUND FLOOR, A PAIKI, SUBHAN BADSHAHWADI, CHALAMWAD RUSTOMPURA, Surat, Gujarat, 395002', 'NA', '72.82745711108399', '21.18614671790643', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 17:09:12', '2021-01-20 17:09:12'),
(109, 109, 'India', 'Gujarat', 'Surat', '395009', '1/B, ANKUR SHOPING CENTRE, PALANPUR ROAD, SURAT, Surat, Gujarat, 395009', 'NA', '72.77801863452149', '21.21047368203861', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 17:10:15', '2021-01-20 17:10:15'),
(110, 110, 'India', 'Gujarat', 'Surat', '395009', 'G 5, RIDDHI SHOPERS, PAL ROAD, ADAJAN, Surat, Gujarat, 395009', 'NA', '72.79655806323242', '21.19318914578256', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 17:11:08', '2021-01-20 17:11:08'),
(111, 111, 'India', 'Gujarat', 'Surat', '395001', '164, FIRST FLOOR, MEGHANI TOWER, STATION ROAD, DELHIGATE, Surat, Gujarat, 395001', 'NA', '72.82059065600586', '21.202151750403367', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 17:12:02', '2021-01-20 17:12:02'),
(112, 112, 'India', 'Gujarat', 'Surat', '395003', '3/785, Karva road, Navapura, Surat, Gujarat, 395003', 'NA', '72.8281437565918', '21.16309642671978', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 17:13:05', '2021-01-20 17:13:05'),
(113, 113, 'India', 'Gujarat', 'Surat', '395003', '3, DIWAN PARK APARTMENT, KIRPARAM MEHTA KHANCHO, GOPIPURA, Surat, Gujarat, 395003', 'NA', '72.81303755541992', '21.167578709085653', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 17:14:04', '2021-01-20 17:14:04'),
(114, 114, 'India', 'Gujarat', 'Surat', '395006', '3, SAURABH COMPLEX, NR. BARODA PRISTAGE, VARACHHA ROAD, Surat, Gujarat, 395006', 'NA', '72.86247603198242', '21.204712394702685', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 17:15:05', '2021-01-20 17:15:05'),
(115, 115, 'India', 'Gujarat', 'Surat', '395010', 'BLOCK NO.B/1- B/2, GROUND FLOOR, MARUTI COMPLEX, MODEL TOWN PARVAT PATIA, SURAT, Surat, Gujarat, 395010', 'N', '72.91397444506836', '21.21751495013931', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 17:16:01', '2021-01-20 17:16:01'),
(116, 116, 'India', 'Gujarat', 'Surat', '395006', 'SHOP NO 1, MEERA SHOPPING CENTER, MEERA NAGAR, KHODIYAR NAGAR ROAD, VARACHHA, SURAT, Surat, Gujarat, 395006', 'na', '72.88101546069336', '21.20343207810253', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 17:17:00', '2021-01-20 17:17:00'),
(117, 117, 'India', 'Gujarat', 'Ahmedabad', '380001', '2251/A, MANDVI NI POLE, OPP.CHHIPA MAWJI POLE, MANEKCHOWK, AHMEDABAD, Ahmedabad, Gujarat, 380001', 'NA', '72.91878096362305', '21.229676349213616', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-20 17:18:08', '2021-01-20 17:18:08'),
(118, 118, 'India', 'Gujarat', 'Surat', '395002', 'H-1430-31, MILLENNIUM TEX. MARKET, RING ROAD, SURAT.', 'NA', '72.83295027514649', '21.152210318701176', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-01-21 16:01:21', '2021-01-21 16:01:21'),
(119, 119, 'India', 'Gujarat', 'Surat', '395004', '7/8, Harsh Complex, Katargam Bus Stop, Nr.dholakiya Garden Opp.Bank Of Baroda. Surat 395004', 'Katargam', '72.83333659172058', '21.22209628894929', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-02-15 11:49:43', '2021-02-15 11:49:43'),
(120, 120, 'India', 'Gujarat', 'Surat', '395007', 'SHOP NO. M-27, JOLLY SHOPPING POINT, GHOD DOD\r\nROAD, SURAT, Surat, Gujarat, 395007', 'ghoddod road', '72.80486762523651', '21.17441762402', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-02-15 17:08:28', '2021-02-15 17:08:28'),
(121, 121, 'India', 'Gujarat', 'Surat', '395007', 'Survey No.251, C2,FP NO.126,Jolly Shopping Point,Ghod Dod Road,Surat,Surat,Gujarat, 395007', 'ghoddod road', '72.80486762523651', '21.17441762402', NULL, NULL, NULL, 'W', 9, 9, 0, 0, '2021-02-15 17:15:01', '2021-02-15 17:15:01'),
(122, 121, 'India', 'Gujarat', 'Surat', '395001', 'M - 1,2,3 Jolly Shopping Point, Ghod Dhod Road, Surat', 'Besides G3 Shop', '72.80306115384164', '21.174603719041396', NULL, NULL, NULL, 'W', 5, 5, 1, 0, '2021-02-16 11:44:13', '2021-02-16 11:44:13'),
(123, 122, 'India', 'Gujarat', 'vadodara', '394210', '567, at near nowhere class ok', 'surat', '72.545646546', '12.4645646546', NULL, NULL, NULL, 'H', 9, 9, 1, 0, '2021-02-19 12:04:24', '2021-04-22 12:59:31'),
(124, 122, 'India', 'Gujarat', 'Surat', '394021', 'Lalji Mulji Transport Co, Plot no. 6102, Road no. 61, opp. SBI Bank, G.I.D.C Sachin, Surat.', 'SBI Bank', '72.86286637187004', '21.098006829813485', 'Arjun', '9998012774', 'Lalji Mulji Transport Co.', 'W', 9, 9, 0, 0, '2021-02-19 13:25:43', '2021-03-02 12:42:05'),
(125, 122, 'India', 'Gujarat', 'Surat', '395010', 'Godown No 1, Sehjanand Park, Opposite. Kumbharia Bus Stop, Saroli', 'Kumbharia Bus Stop', '72.89022335929879', '21.190645687601684', 'KrunalBhai', '8200132234', NULL, 'W', 9, 9, 0, 0, '2021-02-19 13:34:56', '2021-03-08 16:46:19'),
(126, 123, 'India', 'GUJARAT', 'SURAT', '394220', 'PLOT NO-273/1,G.I.D.C PANDESARA ,OPP BHAGYA LAXMI MILL,PANDESARA,SURAT,Surat,Gujarat, 394221', 'BHAGYA LAXMI MILL', '72.83433839678764', '21.13802122598861', NULL, NULL, NULL, 'W', 9, 9, 1, 0, '2021-02-19 15:35:19', '2021-02-19 15:35:19'),
(127, 124, 'India', 'Gujarat', 'Surat', '395009', 'SHOP NO.4, DIVYA COMPLEX-A, OPP. ST. MARK SCHOOL, GANGESHWAR TEMPLE, ADAJAN, SURAT, Gujarat', NULL, '72.78982244431973', '21.19593507062176', 'Hiteshbhai', '8866607999', NULL, 'W', 9, 9, 1, 0, '2021-02-22 15:02:01', '2021-03-05 13:10:56'),
(128, 122, 'India', 'Gujarat', 'Surat', '394210', 'Plot No. A/60/20, Road no.6, Near Ankur Weigh Bridge, Udhna udhyoganagar, Udhna, Surat', NULL, '72.84854471683502', '21.170465801418736', 'Dipak Bhai', '9724749016', 'Lalji Mulji Transport Co.', 'W', 9, 9, 0, 0, '2021-02-22 17:03:49', '2021-03-02 12:42:58'),
(129, 125, 'India', 'Gujarat', 'Surat', '395007', 'C-402, Near Umrigar School, Opera Town, Umra, Surat, Gujarat', NULL, '72.78605461120605', '21.17649854133468', 'Hirenbhai', '3824159029', NULL, 'W', 5, 9, 1, 0, '2021-03-01 15:09:53', '2021-03-27 21:41:49'),
(131, 126, 'India', 'Gujarat', 'Surat', '395002', 'Babita narrow Fabrics\r\n2/5149 Rustampura Police Chowki Road, Opp. Bharucha wadi, Baside. Prakash Bakery, Surat', NULL, '72.83234015107155', '21.189207258332004', 'KAMAL', '9737799955', 'Lalji Mulji Transport Co', 'W', 9, 9, 1, 0, '2021-03-08 17:03:58', '2021-03-08 17:03:58'),
(133, 124, 'India', 'Gujarat', 'Surat', '395010', 'Jaipur GoldenTransport Organization\nGodown No. 32, Dumbhal, Near Amaazia Water Park, Parvat Patiya, Surat', NULL, '72.86618828773499', '21.193725010878477', 'DevBhai', '8866554888', 'Jaipur Golden Transport Organization', 'W', 9, 9, 0, 0, '2021-03-10 14:59:48', '2021-03-10 14:59:48'),
(134, 124, 'India', 'Gujarat', 'Surat', '395010', 'X-Press RoadLines\nGodown No, 52-53, Shivam Estate,Shaniya-Hemad Road, Opp. Puna Kumbharya Bus stop, Saroli, Surat', NULL, '72.89088953366634', '21.191230543031466', 'X-Press Roadlines', '2612540354', 'X-Press Roadlines', 'W', 9, 9, 0, 0, '2021-03-10 15:05:09', '2021-03-10 15:05:09'),
(135, 127, 'India', 'Gujarat', 'Surat', '395010', 'SHOP NO. A/114, Landmark Empire, Puna Kumbhariya Road, Magob, Surat, Gujarat, 395010', NULL, '72.87652352177825', '21.190561335302238', 'SHANKESHWAR FAB TEX', '7874280774', 'Shree Prabhat Roadways', 'W', 9, 9, 1, 0, '2021-03-13 15:28:00', '2021-03-13 15:28:00'),
(136, 128, 'India', 'Gujarat', 'Surat', '395002', '583/84/85, Annapurna Textile Market, Ring Road, Surat', NULL, '72.84009929702026', '21.18921831491858', 'SunilBhai', '9818534763', NULL, 'W', 9, 9, 1, 0, '2021-03-15 16:31:59', '2021-03-15 16:31:59'),
(137, 129, 'India', 'Gujarat', 'Surat', '395010', '313 A Building, LandMark Empires, Magob, Surat', NULL, '72.8778735486977', '21.190336431890316', 'Sarang Bhai', '7778041114', NULL, 'W', 9, 9, 1, 0, '2021-03-15 16:49:15', '2021-03-15 16:49:15'),
(138, 129, 'India', 'Gujarat', 'Surat', '395002', '583/84/85, Annapurna Textile Market, Ring Road, Surat', NULL, '72.84009929702026', '21.18921831491858', 'SunilBhai', '9818534763', NULL, 'W', 9, 9, 0, 0, '2021-03-15 16:52:07', '2021-03-15 16:52:07'),
(139, 130, 'India', 'Gujarat', 'Surat', '395002', 'M-2, 723 Kaveri saree, Millenium-2, Near ragukul Market, Surat', NULL, '72.84618502628567', '21.18433086482725', 'Kaveri saree', '9773096780', 'Jamnagar Transport', 'W', 9, 9, 1, 0, '2021-03-19 12:35:18', '2021-03-19 12:35:18'),
(140, 131, 'India', 'Gujarat', 'Surat', '395002', 'Mahalaxmi textile mills, \r\n107 1st floor, Radhey market, Ring road, Surat.', NULL, '72.84442752599716', '21.191101655192504', 'Gaurav Agarwal', '9825788382', NULL, 'W', 9, 9, 1, 0, '2021-03-22 11:25:50', '2021-03-22 11:25:50'),
(141, 19, 'India', 'Gujarat', 'surat', '785699', 'near dee village 123 etc', 't ynrt ytry tr', '18.896656556', '78.896656556', 'gh jgh jgh', '89008990976', 'jJHFJHFJF', 'H', 11, 11, 0, 0, '2021-04-07 11:28:23', '2021-04-07 11:29:09'),
(142, 122, 'India', 'Gujarat', 'vadodara', '394210', '567, at near nowhere class ok', 'surat', '72.545646546', '12.4645646546', NULL, NULL, NULL, 'H', 0, 0, 0, 0, '2021-04-21 12:53:57', '2021-04-21 12:53:57');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin_notifications`
--

CREATE TABLE `tbl_admin_notifications` (
  `id` bigint(20) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `notification_text` varchar(1000) NOT NULL,
  `notification_link` varchar(1000) DEFAULT NULL COMMENT 'direct link of notification',
  `created_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_admin_notifications`
--

INSERT INTO `tbl_admin_notifications` (`id`, `title`, `notification_text`, `notification_link`, `created_at`) VALUES
(1, 'Hello', 'Notifications will be Shown here...', NULL, '2021-03-20 16:09:41'),
(4, 'Emergency for Pranav Begade', 'Pranav Begade Driver has Sent an Emergency Notification.', 'https://bigdaddylogistics.com/adminside/driver-list', '2021-03-24 14:49:46'),
(5, 'Emergency for Pranav Begade', 'Pranav Begade Driver has Sent an Emergency Notification.', 'https://bigdaddylogistics.com/adminside/driver-list', '2021-03-24 14:50:29'),
(6, 'Emergency for Big Daddy', 'Big Daddy Driver has Sent an Emergency Notification.', 'https://bigdaddylogistics.com/adminside/driver-list', '2021-03-24 16:21:44'),
(7, 'Emergency for Big Daddy', 'Big Daddy Driver has Sent an Emergency Notification.', 'https://bigdaddylogistics.com/adminside/driver-list', '2021-03-24 16:22:33'),
(8, 'Emergency for Big Daddy', 'Big Daddy Driver has Sent an Emergency Notification.', 'https://bigdaddylogistics.com/adminside/driver-list', '2021-03-24 16:23:14'),
(9, 'Order Delivered', 'Order #58 has been Delivered By Mohammad Fahad Shaikh.', 'https://bigdaddylogistics.com/adminside/delivered-orders', '2021-03-25 17:50:59'),
(10, 'Order Delivered', 'Order #59 has been Delivered By Mohammad Fahad Shaikh.', 'https://bigdaddylogistics.com/adminside/delivered-orders', '2021-03-25 17:51:34'),
(11, 'Order Delivered', 'Order #57 has been Delivered By Mohammad Fahad Shaikh.', 'https://bigdaddylogistics.com/adminside/delivered-orders', '2021-03-25 18:09:45'),
(12, 'Emergency for Big Daddy', 'Big Daddy Driver has Sent an Emergency Notification.', 'https://bigdaddylogistics.com/adminside/driver-list', '2021-03-25 18:13:35'),
(13, 'Emergency for Big Daddy', 'Big Daddy Driver has Sent an Emergency Notification.', 'https://bigdaddylogistics.com/adminside/driver-list', '2021-03-25 18:15:00'),
(14, 'Emergency for Big Daddy', 'Big Daddy Driver has Sent an Emergency Notification.', 'https://bigdaddylogistics.com/adminside/driver-list', '2021-03-25 18:15:57'),
(15, 'Emergency for Pranav Begade', 'Pranav Begade Driver has Sent an Emergency Notification.', 'https://bigdaddylogistics.com/adminside/driver-list', '2021-03-25 18:18:13'),
(16, 'Emergency for Pranav Begade', 'Pranav Begade Driver has Sent an Emergency Notification.', 'https://bigdaddylogistics.com/adminside/driver-list', '2021-03-25 18:18:38'),
(17, 'Order Delivered', 'Order #63 has been Delivered By Shaikh Ezaz.', 'https://bigdaddylogistics.com/adminside/delivered-orders', '2021-03-30 17:35:57'),
(18, 'Order Delivered', 'Order #64 has been Delivered By Mohammad Fahad Shaikh.', 'https://bigdaddylogistics.com/adminside/delivered-orders', '2021-03-30 17:53:34'),
(19, 'Order Delivered', 'Order #65 has been Delivered By Mohammad Fahad Shaikh.', 'https://bigdaddylogistics.com/adminside/delivered-orders', '2021-03-30 18:21:34'),
(20, 'Order Delivered', 'Order #1002 has been Delivered By Mohammad Fahad Shaikh.', 'https://bigdaddylogistics.com/adminside/delivered-orders', '2021-03-31 17:00:18'),
(21, 'Order Delivered', 'Order #1001 has been Delivered By Shaikh Ezaz.', 'https://bigdaddylogistics.com/adminside/delivered-orders', '2021-03-31 17:21:29'),
(22, 'Order Delivered', 'Order #1004 has been Delivered By Shaikh Ezaz.', 'https://bigdaddylogistics.com/adminside/delivered-orders', '2021-04-02 14:33:11'),
(23, 'Order Delivered', 'Order #1005 has been Delivered By Pranav Begade.', 'https://bigdaddylogistics.com/adminside/delivered-orders', '2021-04-02 15:02:39'),
(24, 'Order Delivered', 'Order #1005 has been Delivered By Shaikh Ezaz.', 'https://bigdaddylogistics.com/adminside/delivered-orders', '2021-04-02 18:36:58'),
(25, 'Order Delivered', 'Order #1006 has been Delivered By Shaikh Ezaz.', 'https://bigdaddylogistics.com/adminside/delivered-orders', '2021-04-05 13:57:12'),
(26, 'Order Delivered', 'Order #1007 has been Delivered By Mohammad Fahad Shaikh.', 'https://bigdaddylogistics.com/adminside/delivered-orders', '2021-04-05 16:07:11'),
(27, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-06 12:25:34'),
(28, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-06 12:37:19'),
(29, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-06 18:04:36'),
(30, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-07 11:31:36'),
(31, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-07 11:43:29'),
(32, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-07 12:59:19'),
(33, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-07 14:20:04'),
(34, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-07 14:41:42'),
(35, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-07 14:45:13'),
(36, 'Order Payment', 'Order Payment Paid Online By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/97', '2021-04-07 14:46:16'),
(37, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-07 15:00:14'),
(38, 'Order Payment', 'Order Payment Paid Online By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/98', '2021-04-07 15:01:26'),
(39, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-07 15:10:01'),
(40, 'Order Payment', 'Order Payment Paid Online By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/99', '2021-04-07 15:18:04'),
(41, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-07 15:40:12'),
(42, 'Order Payment', 'Order Payment Paid Online By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/100', '2021-04-07 15:41:28'),
(43, 'Order Payment', 'Order Payment Paid Online By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/100', '2021-04-07 15:53:50'),
(44, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-07 16:38:15'),
(45, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-07 16:39:50'),
(46, 'Order Placed', 'Order Placed With COD By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/102', '2021-04-07 16:40:47'),
(47, 'Order Placed', 'Order Placed With COD By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/102', '2021-04-07 16:45:44'),
(48, 'Order Placed', 'Order Placed With COD By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/102', '2021-04-07 16:46:03'),
(49, 'Order Placed', 'Order Placed With COD By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/102', '2021-04-07 16:46:06'),
(50, 'Order Placed', 'Order Placed With COD By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/102', '2021-04-07 16:47:43'),
(51, 'Order Placed', 'Order Placed With COD By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/102', '2021-04-07 16:47:47'),
(52, 'Order Placed', 'Order Placed With COD By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/102', '2021-04-07 16:48:09'),
(53, 'Order Placed', 'Order Placed With COD By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/102', '2021-04-07 16:48:11'),
(54, 'Order Placed', 'Order Placed With COD By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/102', '2021-04-07 16:49:08'),
(55, 'Order Placed', 'Order Placed With COD By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/102', '2021-04-07 16:50:30'),
(56, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-07 16:55:32'),
(57, 'Order Placed', 'Order Placed With COD By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/102', '2021-04-07 16:55:35'),
(58, 'Order Placed', 'Order Placed With COD By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/103', '2021-04-07 17:08:55'),
(59, 'Order Placed', 'Order Placed With COD By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/101', '2021-04-07 17:26:53'),
(60, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-07 17:31:58'),
(61, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-07 18:00:20'),
(62, 'Order Payment', 'Order Payment Paid Online By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/105', '2021-04-07 18:11:10'),
(63, 'Order Payment', 'Order Payment Paid Online By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/105', '2021-04-07 18:12:23'),
(64, 'Order Payment', 'Order Payment Paid Online By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/96', '2021-04-08 10:40:07'),
(65, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-08 11:03:44'),
(66, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-08 11:04:23'),
(67, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-08 11:04:35'),
(68, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-08 11:37:05'),
(69, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-08 11:37:59'),
(70, 'Order Payment', 'Order Payment Paid Online By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/110', '2021-04-08 11:40:32'),
(71, 'Order Payment', 'Order Payment Paid Online By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/109', '2021-04-08 12:25:12'),
(72, 'Order Payment', 'Order Payment Paid Online By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/108', '2021-04-08 12:26:51'),
(73, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-08 13:21:26'),
(74, 'Order Payment', 'Order Payment Paid Online By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/111', '2021-04-08 13:22:09'),
(75, 'Order Payment', 'Order Payment Paid Online By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/111', '2021-04-08 13:28:38'),
(76, 'Order Payment', 'Order Payment Paid Online By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/111', '2021-04-08 13:30:13'),
(77, 'Order Placed', 'Order Placed With COD By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/107', '2021-04-08 13:30:28'),
(78, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-08 13:37:22'),
(79, 'Order Payment', 'Order Payment Paid Online By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/112', '2021-04-08 13:38:10'),
(80, 'Order Placed', 'Order Placed With COD By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/107', '2021-04-08 13:40:26'),
(81, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-08 14:17:57'),
(82, 'New LR Uploaded', 'New LR File Uploaded For New Order.', 'http://192.168.0.115/bigdaddy/public/adminside/lr-upload-list', '2021-04-08 14:38:43'),
(83, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-08 14:49:00'),
(84, 'Order Payment', 'Order Payment Paid Online By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/114', '2021-04-08 14:54:19'),
(85, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-08 14:54:52'),
(86, 'Order Payment', 'Order Payment Paid Online By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/115', '2021-04-08 14:55:22'),
(87, 'Order Payment', 'Order Payment Paid Online By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/115', '2021-04-08 14:57:12'),
(88, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-08 15:22:41'),
(89, 'Order Placed', 'Order Placed With COD By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/116', '2021-04-08 15:23:53'),
(90, 'Order Payment', 'Order Payment Paid Online By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/106', '2021-04-08 15:33:25'),
(91, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-08 15:36:59'),
(92, 'Order Payment', 'Order Payment Paid Online By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/117', '2021-04-08 15:37:53'),
(93, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-08 15:38:41'),
(94, 'Order Payment', 'Order Payment Paid Online By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/118', '2021-04-08 15:40:10'),
(95, 'Order Delivered', 'Order #1016 has been Delivered By Shaikh Ezaz.', 'http://192.168.0.115/bigdaddy/public/adminside/delivered-orders', '2021-04-08 16:07:52'),
(96, 'Order Delivered', 'Order #1008 has been Delivered By Shaikh Ezaz.', 'http://192.168.0.115/bigdaddy/public/adminside/delivered-orders', '2021-04-08 16:08:01'),
(97, 'Order Delivered', 'Order #1035 has been Delivered By Shaikh Ezaz.', 'http://192.168.0.115/bigdaddy/public/adminside/delivered-orders', '2021-04-08 16:12:40'),
(98, 'Order Delivered', 'Order #1034 has been Delivered By Shaikh Ezaz.', 'http://192.168.0.115/bigdaddy/public/adminside/delivered-orders', '2021-04-08 16:12:46'),
(99, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-08 17:03:35'),
(100, 'Order Payment', 'Order Payment Paid Online By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/123', '2021-04-08 17:04:12'),
(101, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-08 17:05:20'),
(102, 'Order Payment', 'Order Payment Paid Online By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/124', '2021-04-08 17:05:47'),
(103, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-08 17:09:35'),
(104, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-08 17:13:58'),
(105, 'Order Payment', 'Order Payment Paid Online By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/126', '2021-04-08 17:15:10'),
(106, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-08 17:20:48'),
(107, 'Order Payment', 'Order Payment Paid Online By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/127', '2021-04-08 17:21:37'),
(108, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-08 17:53:43'),
(109, 'Order Payment', 'Order Payment Paid Online By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/128', '2021-04-08 18:00:32'),
(110, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-08 18:01:05'),
(111, 'Order Payment', 'Order Payment Paid Online By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/129', '2021-04-08 18:01:57'),
(112, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-08 18:10:54'),
(113, 'Order Payment', 'Order Payment Paid Online By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/130', '2021-04-08 18:11:48'),
(114, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-08 18:19:19'),
(115, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-09 09:56:06'),
(116, 'Order Payment', 'Order Payment Paid Online By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/132', '2021-04-09 09:57:25'),
(117, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-09 10:00:56'),
(118, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-09 10:38:10'),
(119, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-09 10:40:35'),
(120, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/tobeapproved-orders', '2021-04-09 10:40:37'),
(121, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-09 10:40:39'),
(122, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-09 11:11:04'),
(123, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-09 11:23:42'),
(124, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-09 11:28:44'),
(125, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-09 11:33:51'),
(126, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-09 11:38:23'),
(127, 'Order Payment', 'Order Payment Paid Online By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/142', '2021-04-09 11:40:34'),
(128, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-09 11:43:07'),
(129, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-09 11:46:49'),
(130, 'Order Payment', 'Order Payment Paid Online By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/144', '2021-04-09 11:47:42'),
(131, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-09 11:54:47'),
(132, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-09 12:18:55'),
(133, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-09 12:20:57'),
(134, 'Order Payment', 'Order Payment Paid Online By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/147', '2021-04-09 12:21:40'),
(135, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-09 12:22:30'),
(136, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-09 12:29:06'),
(137, 'Order Payment', 'Order Payment Paid Online By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/149', '2021-04-09 12:29:51'),
(138, 'Order Payment', 'Order Payment Paid Online By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/148', '2021-04-09 12:37:40'),
(139, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-09 12:40:59'),
(140, 'Order Placed', 'Order Placed With COD By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/150', '2021-04-09 12:48:32'),
(141, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-09 13:58:23'),
(142, 'Order Payment', 'Order Payment Paid Online By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/151', '2021-04-09 13:59:39'),
(143, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-09 14:01:25'),
(144, 'Order Payment', 'Order Payment Paid Online By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/152', '2021-04-09 14:03:44'),
(145, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-09 14:16:17'),
(146, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-09 15:04:55'),
(147, 'Order Payment', 'Order Payment Paid Online By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/153', '2021-04-09 15:06:30'),
(148, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-09 16:22:55'),
(149, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-09 16:24:23'),
(150, 'Order Payment', 'Order Payment Paid Online By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/154', '2021-04-09 16:30:33'),
(151, 'New Order', 'New Order Requested By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-09 16:35:12'),
(152, 'Order Payment', 'Order Payment Paid Online By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/157', '2021-04-09 16:36:04'),
(153, 'Order Placed', 'Order Placed With COD By Customer.', 'http://192.168.0.115/bigdaddy/public/adminside/view-order/155', '2021-04-14 11:01:44'),
(154, 'New LR Uploaded', 'New LR File Uploaded For New Order.', 'http://192.168.43.35/bigdaddy/public/adminside/lr-upload-list', '2021-04-15 11:05:13'),
(155, 'New Order', 'New Order Requested By Customer.', 'http://192.168.43.35/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-19 12:25:31'),
(156, 'New Order', 'New Order Requested By Customer.', 'http://192.168.43.35/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-19 12:41:31'),
(157, 'New Order', 'New Order Requested By Customer.', 'http://192.168.43.35/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-19 12:53:29'),
(158, 'New Order', 'New Order Requested By Customer.', 'http://192.168.43.35/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-19 12:56:04'),
(159, 'New Order', 'New Order Requested By Customer.', 'http://192.168.43.35/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-19 12:57:18'),
(160, 'New Order', 'New Order Requested By Customer.', 'http://192.168.43.35/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-19 12:58:55'),
(161, 'New Order', 'New Order Requested By Customer.', 'http://192.168.43.35/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-19 13:01:38'),
(162, 'Order Placed', 'Order Placed With COD By Customer.', 'http://192.168.43.35/bigdaddy/public/adminside/view-order/169', '2021-04-19 17:32:39'),
(163, 'New Order', 'New Order Requested By Customer.', 'http://192.168.43.35/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-19 17:35:28'),
(164, 'Order Placed', 'Order Placed With COD By Customer.', 'http://192.168.43.35/bigdaddy/public/adminside/view-order/171', '2021-04-19 17:50:53'),
(165, 'New Order', 'New Order Requested By Customer.', 'http://192.168.43.35/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-19 17:51:24'),
(166, 'New Order', 'New Order Requested By Customer.', 'http://192.168.43.35/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-19 17:55:26'),
(167, 'New Order', 'New Order Requested By Customer.', 'http://192.168.43.35/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-19 18:01:23'),
(168, 'Order Placed', 'Order Placed With COD By Customer.', 'http://192.168.43.35/bigdaddy/public/adminside/view-order/175', '2021-04-19 18:03:46'),
(169, 'New Order', 'New Order Requested By Customer.', 'http://192.168.43.35/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-19 18:13:28'),
(170, 'New Order', 'New Order Requested By Customer.', 'http://192.168.43.35/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-19 18:17:14'),
(171, 'Order Placed', 'Order Placed With COD By Customer.', 'http://192.168.43.35/bigdaddy/public/adminside/view-order/180', '2021-04-19 18:22:07'),
(172, 'New Order', 'New Order Requested By Customer.', 'http://192.168.43.35/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-19 18:40:20'),
(173, 'New Order', 'New Order Requested By Customer.', 'http://192.168.43.35/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-19 18:52:08'),
(174, 'New Order', 'New Order Requested By Customer.', 'http://192.168.43.35/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-20 12:49:38'),
(175, 'Order Placed', 'Order Placed With COD By Customer.', 'http://192.168.43.35/bigdaddy/public/adminside/view-order/184', '2021-04-20 12:53:15'),
(176, 'New Order', 'New Order Requested By Customer.', 'http://192.168.43.35/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-20 12:59:06'),
(177, 'New Order', 'New Order Requested By Customer.', 'http://192.168.43.35/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-20 14:48:05'),
(178, 'Order Placed', 'Order Placed With Wallet By Customer.', 'http://192.168.43.35/bigdaddy/public/adminside/view-order/185', '2021-04-20 17:33:18'),
(179, 'New Order', 'New Order Requested By Customer.', 'http://192.168.43.35/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-20 17:39:24'),
(180, 'Order Placed', 'Order Placed With Wallet By Customer.', 'http://192.168.43.35/bigdaddy/public/adminside/view-order/191', '2021-04-20 17:41:06'),
(181, 'New Order', 'New Order Requested By Customer.', 'http://192.168.43.35/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-20 17:58:07'),
(182, 'Order Placed', 'Order Placed With Wallet By Customer.', 'http://192.168.43.35/bigdaddy/public/adminside/view-order/192', '2021-04-20 18:02:46'),
(183, 'New Order', 'New Order Requested By Customer.', 'http://192.168.43.35/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-22 12:09:17'),
(184, 'New Order', 'New Order Requested By Customer.', 'http://192.168.43.35/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-22 12:13:05'),
(185, 'New Order', 'New Order Requested By Customer.', 'http://192.168.43.35/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-22 12:27:54'),
(186, 'New Order', 'New Order Requested By Customer.', 'http://192.168.43.35/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-23 11:12:17'),
(187, 'New Order', 'New Order Requested By Customer.', 'http://192.168.43.35/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-23 11:41:43'),
(188, 'New Order', 'New Order Requested By Customer.', 'http://192.168.43.35/bigdaddy/public/adminside/tobeapproved-orders', '2021-04-29 11:19:50'),
(189, 'Order Cancelled', 'Order Cancelled By Customer.', 'http://192.168.43.35/bigdaddy/public/adminside/view-order/200', '2021-04-29 12:32:08'),
(190, 'Order Cancelled', 'Order Cancelled By Customer.', 'http://192.168.43.35/bigdaddy/public/adminside/view-order/203', '2021-04-29 16:23:39'),
(191, 'Order Cancelled', 'Order Cancelled By Customer.', 'http://192.168.43.35/bigdaddy/public/adminside/view-order/204', '2021-04-29 16:49:16'),
(192, 'Order Mismatched', 'Order #1052 Detail Mismatched By Mohammad Fahad Shaikh.', 'http://192.168.43.35/bigdaddy/public/adminside/view-order/198', '2021-05-03 15:33:56'),
(193, 'Order Delivered', 'Order #1062 has been Delivered By Mohammad Fahad Shaikh.', 'http://192.168.43.35/bigdaddy/public/adminside/delivered-orders', '2021-05-03 16:53:31'),
(194, 'Order Delivered', 'Order #1063 has been Delivered By Mohammad Fahad Shaikh.', 'http://192.168.43.35/bigdaddy/public/adminside/delivered-orders', '2021-05-03 16:55:22'),
(195, 'Order Delivered', 'Order #1049 has been Delivered By Mohammad Fahad Shaikh.', 'http://192.168.43.35/bigdaddy/public/adminside/delivered-orders', '2021-05-03 17:09:23'),
(196, 'Order Delivered', 'Order #1048 has been Delivered By Mohammad Fahad Shaikh.', 'http://192.168.43.35/bigdaddy/public/adminside/delivered-orders', '2021-05-03 17:13:09'),
(197, 'New Order', 'New Order Requested By Customer.', 'http://192.168.43.35/bigdaddy/public/adminside/tobeapproved-orders', '2021-05-03 17:26:44'),
(198, 'New Order', 'New Order Requested By Customer.', 'http://192.168.43.35/bigdaddy/public/adminside/tobeapproved-orders', '2021-05-03 17:33:04'),
(199, 'Order Delivered', 'Order #1047 has been Delivered By Mohammad Fahad Shaikh.', 'http://192.168.43.35/bigdaddy/public/adminside/delivered-orders', '2021-05-03 18:03:54'),
(200, 'Order Delivered', 'Order #1045 has been Delivered By Mohammad Fahad Shaikh.', 'http://192.168.43.35/bigdaddy/public/adminside/delivered-orders', '2021-05-03 18:22:32'),
(201, 'Order Delivered', 'Order #1031 has been Delivered By Mohammad Fahad Shaikh.', 'http://192.168.43.35/bigdaddy/public/adminside/delivered-orders', '2021-05-06 13:00:35'),
(202, 'Order Delivered', 'Order #1023 has been Delivered By Mohammad Fahad Shaikh.', 'http://192.168.43.35/bigdaddy/public/adminside/delivered-orders', '2021-05-06 14:20:52'),
(203, 'Order Delivered', 'Order #1066 has been Delivered By Mohammad Fahad Shaikh.', 'http://192.168.43.35/bigdaddy/public/adminside/delivered-orders', '2021-05-06 14:28:43'),
(204, 'Order Delivered', 'Order #1066 has been Delivered By Mohammad Fahad Shaikh.', 'http://192.168.43.35/bigdaddy/public/adminside/delivered-orders', '2021-05-06 14:36:52'),
(205, 'Order Delivered', 'Order #1066 has been Delivered By Mohammad Fahad Shaikh.', 'http://192.168.43.35/bigdaddy/public/adminside/delivered-orders', '2021-05-06 14:38:07'),
(206, 'Order Delivered', 'Order #1065 has been Delivered By Mohammad Fahad Shaikh.', 'http://192.168.43.35/bigdaddy/public/adminside/delivered-orders', '2021-05-06 14:38:55'),
(207, 'Order Delivered', 'Order #1068 has been Delivered By Mohammad Fahad Shaikh.', 'http://192.168.43.35/bigdaddy/public/adminside/delivered-orders', '2021-05-06 14:55:49'),
(208, 'Order Delivered', 'Order #1067 has been Delivered By Mohammad Fahad Shaikh.', 'http://192.168.43.35/bigdaddy/public/adminside/delivered-orders', '2021-05-06 14:56:29'),
(209, 'Order Delivered', 'Order #1022 has been Delivered By Mohammad Fahad Shaikh.', 'http://192.168.43.35/bigdaddy/public/adminside/delivered-orders', '2021-05-06 15:01:48'),
(210, 'Order Delivered', 'Order #1015 has been Delivered By Mohammad Fahad Shaikh.', 'http://192.168.43.35/bigdaddy/public/adminside/delivered-orders', '2021-05-06 15:08:03'),
(211, 'Order Delivered', 'Order #1013 has been Delivered By Mohammad Fahad Shaikh.', 'http://192.168.43.35/bigdaddy/public/adminside/delivered-orders', '2021-05-06 15:22:26');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_coupons`
--

CREATE TABLE `tbl_coupons` (
  `id` int(11) UNSIGNED NOT NULL,
  `coupon_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coupon_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coupon_terms` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coupon_code` varchar(55) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_type` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'P' COMMENT 'F-flat amount discount,\r\nP-percent amount discount',
  `is_active` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-active,1-deactive, 2- deleted',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `start_datetime` datetime DEFAULT NULL,
  `end_datetime` datetime DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0 COMMENT '0-for all else user_id',
  `applied_for` tinyint(1) DEFAULT 0 COMMENT '0-both, 1-online, 2- cod',
  `min_order_value` decimal(13,2) NOT NULL DEFAULT 100.00,
  `discount_value` decimal(9,2) NOT NULL DEFAULT 10.00 COMMENT 'flat/percent',
  `maximum_discount` decimal(9,2) NOT NULL DEFAULT 100.00 COMMENT 'INR',
  `maximum_use_count` int(11) NOT NULL DEFAULT 1 COMMENT 'n time can be used',
  `admin_id` int(11) NOT NULL DEFAULT 0,
  `used_count` int(11) NOT NULL DEFAULT 0 COMMENT 'coupon used count',
  `applied_for_platform` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-both, 1-web, 2- app',
  `maximum_use_count_peruser` int(11) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_coupons`
--

INSERT INTO `tbl_coupons` (`id`, `coupon_title`, `coupon_description`, `coupon_terms`, `coupon_code`, `discount_type`, `is_active`, `created_at`, `updated_at`, `start_datetime`, `end_datetime`, `user_id`, `applied_for`, `min_order_value`, `discount_value`, `maximum_discount`, `maximum_use_count`, `admin_id`, `used_count`, `applied_for_platform`, `maximum_use_count_peruser`) VALUES
(1, 'Summer Offer', 'Get 10% Discount on All Orders', 'Summer Offer is Valid till 25-july-2021.', 'HUY78FTG78', 'P', 0, '2021-04-12 15:01:58', '2021-04-12 16:38:19', '2021-04-12 15:05:18', '2021-07-25 15:05:22', 0, 0, '100.00', '10.00', '100.00', 52, 0, 0, 0, 1),
(2, 'Diwali Offer', 'Diwali Offer Description', 'Diwali Offer Terms', '6GVT9S13KF', 'P', 0, '2021-04-13 11:45:20', '2021-04-13 14:29:53', '2021-04-13 11:44:45', '2021-04-14 12:00:00', 0, 1, '250.00', '12.00', '60.00', 150, 0, 0, 0, 2),
(3, 'Diwali Offer', 'Diwali Offer Description', 'Diwali Offer Description Diwali Offer Description', 'FX8TT2CJ61', 'P', 0, '2021-04-13 11:46:38', '2021-04-13 12:37:40', '2021-04-13 11:46:19', NULL, 15, 1, '120.00', '15.00', '50.00', 14, 0, 0, 0, 1),
(4, 'Diwali 123', 'Diwali 123', 'Diwali Offer Description Diwali Offer Description', 'KEV1YFA4P5', 'F', 1, '2021-04-13 13:31:46', '2021-04-13 15:06:09', '2021-04-14 01:30:00', NULL, 39, 0, '2500.00', '1500.00', '1500.00', 9, 0, 0, 0, 1),
(5, 'Holi Offer', 'Holi Offer Holi Offer', 'Holi Offer Holi Offer 4534', 'J6GP73SQXE', 'P', 0, '2021-04-13 14:11:43', '2021-04-14 14:27:12', '2021-02-01 02:08:00', '2021-04-13 15:39:00', 42, 0, '500.00', '12.00', '120.00', 100000, 0, 0, 0, 1),
(6, 'NEW30', 'NEW30', '1 - This coupon can be used only one time.\r\n2 - Valid from 13-june-2021 to 19-november-2021 .\r\n3 - Valid For Only App downloaded from Playstore/Applestore.', 'NEW30', 'P', 0, '2021-04-13 14:48:58', '2021-04-19 14:53:31', '2021-04-13 14:47:00', NULL, 0, 0, '400.00', '16.00', '120.00', 150, 0, 2, 0, 1),
(7, NULL, NULL, NULL, '8F60HTY2GJ', 'P', 0, '2021-04-19 18:19:59', '2021-04-20 18:00:54', '2021-04-19 18:19:23', NULL, 122, 0, '120.00', '16.00', '150.00', 120, 0, 3, 0, 12),
(8, NULL, NULL, NULL, '9ZP494IBRS', 'F', 0, '2021-04-20 17:59:17', '2021-05-03 15:42:01', '2021-04-20 17:58:29', '2021-08-20 12:00:00', 0, 0, '56.00', '230.00', '230.00', 50000, 0, 2, 0, 26);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_customer_notifications`
--

CREATE TABLE `tbl_customer_notifications` (
  `id` bigint(20) NOT NULL,
  `notification_text` varchar(1000) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0 COMMENT '0-for all else user_id',
  `order_id` int(11) NOT NULL DEFAULT 0 COMMENT '0-for not order notification\r\n',
  `created_at` datetime DEFAULT NULL,
  `classhtml` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_customer_notifications`
--

INSERT INTO `tbl_customer_notifications` (`id`, `notification_text`, `user_id`, `order_id`, `created_at`, `classhtml`) VALUES
(75, 'Your Order #1006 has been Assigned To Driver.', 128, 86, '2021-04-05 11:24:24', 'success'),
(76, 'Your Order #1006 has been Assigned To Driver Shaikh Ezaz.', 128, 86, '2021-04-05 12:36:34', 'success'),
(77, 'Your Order #1006 has been Delivered By Shaikh Ezaz.', 128, 86, '2021-04-05 13:57:12', 'success'),
(78, 'Your Order #1007 has been Assigned To Driver.', 122, 87, '2021-04-05 14:56:42', 'success'),
(79, 'Your Order #1007 has been Assigned To Driver Mohammad Fahad Shaikh.', 122, 87, '2021-04-05 15:11:30', 'success'),
(80, 'Your Order #1007 has been Delivered By Mohammad Fahad Shaikh.', 122, 87, '2021-04-05 16:07:11', 'success'),
(81, 'Your Order #1008 has been Assigned To Driver.', 122, 88, '2021-04-05 18:06:06', 'success'),
(82, 'Your Order #1008 has been Assigned To Driver.', 122, 88, '2021-04-05 18:06:48', 'success'),
(83, 'Your Order #1008 has been Assigned To Driver.', 122, 88, '2021-04-05 18:23:04', 'success'),
(84, 'Your Order # has been Approved. Please Checkout to Confirm Your Order.', 19, 89, '2021-04-06 12:27:40', 'success'),
(85, 'Your Order # has been Approved. Please Checkout to Confirm Your Order.', 19, 90, '2021-04-06 15:24:23', 'success'),
(86, 'Your Order # has been Approved. Please Checkout to Confirm Your Order.', 19, 90, '2021-04-06 15:27:04', 'success'),
(87, 'Your Order # has been Approved. Please Checkout to Confirm Your Order.', 19, 89, '2021-04-06 16:47:57', 'success'),
(88, 'Your Order # has been Approved. Please Checkout to Confirm Your Order.', 19, 92, '2021-04-07 11:32:11', 'success'),
(89, 'Your Order # has been Approved. Please Checkout to Confirm Your Order.', 19, 92, '2021-04-07 12:01:18', 'success'),
(90, 'Your Order # has been Approved. Please Checkout to Confirm Your Order.', 19, 92, '2021-04-07 12:03:59', 'success'),
(91, 'Your Order # has been Approved. Please Checkout to Confirm Your Order.', 19, 92, '2021-04-07 12:20:19', 'success'),
(92, 'Your Order # has been Approved. Please Checkout to Confirm Your Order.', 19, 92, '2021-04-07 12:29:30', 'success'),
(93, 'Your Order # has been Approved. Please Checkout to Confirm Your Order.', 19, 92, '2021-04-07 12:35:27', 'success'),
(94, 'Your Order # has been Approved. Please Checkout to Confirm Your Order.', 19, 94, '2021-04-07 13:28:26', 'success'),
(95, 'Your Order # has been Approved. Please Checkout to Confirm Your Order.', 19, 94, '2021-04-07 13:31:36', 'success'),
(96, 'Your Order # has been Approved. Please Checkout to Confirm Your Order.', 19, 94, '2021-04-07 13:33:32', 'success'),
(97, 'Your Order # has been Approved. Please Checkout to Confirm Your Order.', 19, 95, '2021-04-07 14:21:07', 'success'),
(98, 'Your Order # has been Approved. Please Checkout to Confirm Your Order.', 19, 95, '2021-04-07 14:27:51', 'success'),
(99, 'Your Order # has been Approved. Please Checkout to Confirm Your Order.', 19, 97, '2021-04-07 14:45:56', 'success'),
(100, 'Your Order # has been Approved. Please Checkout to Confirm Your Order.', 19, 98, '2021-04-07 15:00:57', 'success'),
(101, 'Your Order # has been Approved. Please Checkout to Confirm Your Order.', 19, 99, '2021-04-07 15:12:10', 'success'),
(102, 'Your Order # has been Approved. Please Checkout to Confirm Your Order.', 19, 99, '2021-04-07 15:17:11', 'success'),
(103, 'Your Order # has been Approved. Please Checkout to Confirm Your Order.', 19, 100, '2021-04-07 15:40:49', 'success'),
(104, 'Your Order # has been Approved. Please Checkout to Confirm Your Order.', 19, 102, '2021-04-07 16:45:33', 'success'),
(105, 'Your Order # has been Approved. Please Checkout to Confirm Your Order.', 19, 103, '2021-04-07 17:08:14', 'success'),
(106, 'Your Order # has been Approved. Please Checkout to Confirm Your Order.', 19, 101, '2021-04-07 17:26:26', 'success'),
(107, 'Your Order # has been Approved. Please Checkout to Confirm Your Order.', 19, 104, '2021-04-07 17:37:11', 'success'),
(108, 'Your Order # has been Approved. Please Checkout to Confirm Your Order.', 19, 104, '2021-04-07 17:43:49', 'success'),
(109, 'Your Order # has been Approved. Please Checkout to Confirm Your Order.', 19, 105, '2021-04-07 18:00:35', 'success'),
(110, 'Your Order # has been Approved. Please Checkout to Confirm Your Order.', 19, 96, '2021-04-08 10:37:48', 'success'),
(111, 'Your Order # has been Approved. Please Checkout to Confirm Your Order.', 1, 107, '2021-04-08 11:04:59', 'success'),
(112, 'Your Order #1020 has been Assigned To Driver.', 19, 96, '2021-04-08 11:25:29', 'success'),
(113, 'Your Order #1019 has been Assigned To Driver.', 19, 105, '2021-04-08 11:25:30', 'success'),
(114, 'Your Order #1017 has been Assigned To Driver.', 19, 101, '2021-04-08 11:27:49', 'success'),
(115, 'Your Order #1016 has been Assigned To Driver.', 19, 103, '2021-04-08 11:30:43', 'success'),
(116, 'Your Order # has been Approved. Please Checkout to Confirm Your Order.', 1, 110, '2021-04-08 11:38:38', 'success'),
(117, 'Your Order # has been Approved. Please Checkout to Confirm Your Order.', 19, 109, '2021-04-08 12:23:46', 'success'),
(118, 'Your Order # has been Approved. Please Checkout to Confirm Your Order.', 19, 108, '2021-04-08 12:26:32', 'success'),
(119, 'Your Order # has been Approved. Please Checkout to Confirm Your Order.', 1, 111, '2021-04-08 13:21:51', 'success'),
(120, 'Your Order #1024 has been Approved. Please Checkout to Confirm Your Order.', 1, 111, '2021-04-08 13:25:47', 'success'),
(121, 'Your Order # has been Approved. Please Checkout to Confirm Your Order.', 1, 111, '2021-04-08 13:26:56', 'success'),
(122, 'Your Order # has been Approved. Please Checkout to Confirm Your Order.', 1, 111, '2021-04-08 13:28:26', 'success'),
(123, 'Your Order #1024 has been Approved. Please Checkout to Confirm Your Order.', 1, 111, '2021-04-08 13:30:00', 'success'),
(124, 'Your Order # has been Approved. Please Checkout to Confirm Your Order.', 1, 112, '2021-04-08 13:37:52', 'success'),
(125, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 1, 113, '2021-04-08 14:22:21', 'success'),
(126, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 1, 114, '2021-04-08 14:49:27', 'success'),
(127, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 1, 115, '2021-04-08 14:55:06', 'success'),
(128, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 1, 115, '2021-04-08 14:57:00', 'success'),
(129, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 1, 116, '2021-04-08 15:23:21', 'success'),
(130, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 19, 106, '2021-04-08 15:32:48', 'success'),
(131, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 1, 117, '2021-04-08 15:37:38', 'success'),
(132, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 1, 118, '2021-04-08 15:39:17', 'success'),
(133, 'Your Order #1016 has been Assigned To Driver Shaikh Ezaz.', 19, 103, '2021-04-08 16:07:31', 'success'),
(134, 'Your Order #1008 has been Assigned To Driver Shaikh Ezaz.', 122, 88, '2021-04-08 16:07:36', 'success'),
(135, 'Your Order #1016 has been Delivered By Shaikh Ezaz.', 19, 103, '2021-04-08 16:07:52', 'success'),
(136, 'Your Order #1008 has been Delivered By Shaikh Ezaz.', 122, 88, '2021-04-08 16:08:01', 'success'),
(137, 'Your Order #1034 has been Assigned To Driver.', 122, 119, '2021-04-08 16:08:14', 'success'),
(138, 'Your Order #1035 has been Assigned To Driver.', 122, 120, '2021-04-08 16:08:29', 'success'),
(139, 'Your Order #1035 has been Assigned To Driver Shaikh Ezaz.', 122, 120, '2021-04-08 16:08:58', 'success'),
(140, 'Your Order #1034 has been Assigned To Driver Shaikh Ezaz.', 122, 119, '2021-04-08 16:09:48', 'success'),
(141, 'Your Order #1035 has been Delivered By Shaikh Ezaz.', 122, 120, '2021-04-08 16:12:40', 'success'),
(142, 'Your Order #1034 has been Delivered By Shaikh Ezaz.', 122, 119, '2021-04-08 16:12:45', 'success'),
(143, 'Your Order #1036 has been Assigned To Driver.', 122, 121, '2021-04-08 16:13:18', 'success'),
(144, 'Your Order #1037 has been Assigned To Driver.', 122, 122, '2021-04-08 16:13:28', 'success'),
(145, 'Your Order #1037 has been Assigned To Driver.', 122, 122, '2021-04-08 16:31:38', 'success'),
(146, 'Your Order #1036 has been Assigned To Driver.', 122, 121, '2021-04-08 16:31:40', 'success'),
(147, 'Your Order #1036 has been Assigned To Driver.', 122, 121, '2021-04-08 16:33:36', 'success'),
(148, 'Your Order #1037 has been Assigned To Driver.', 122, 122, '2021-04-08 16:33:41', 'success'),
(149, 'Your Order #1036 has been Assigned To Driver.', 122, 121, '2021-04-08 16:34:14', 'success'),
(150, 'Your Order #1037 has been Assigned To Driver.', 122, 122, '2021-04-08 16:34:22', 'success'),
(151, 'Your Order #1037 has been Assigned To Driver.', 122, 122, '2021-04-08 16:34:46', 'success'),
(152, 'Your Order #1036 has been Assigned To Driver.', 122, 121, '2021-04-08 16:34:46', 'success'),
(153, 'Your Order #1037 has been Assigned To Driver.', 122, 122, '2021-04-08 16:36:58', 'success'),
(154, 'Your Order #1036 has been Assigned To Driver.', 122, 121, '2021-04-08 16:36:58', 'success'),
(155, 'Your Order #1036 has been Assigned To Driver.', 122, 121, '2021-04-08 16:38:18', 'success'),
(156, 'Your Order #1037 has been Assigned To Driver.', 122, 122, '2021-04-08 16:38:24', 'success'),
(157, 'Your Order #1020 has been Assigned To Driver.', 19, 96, '2021-04-08 16:44:41', 'success'),
(158, 'Your Order #1019 has been Assigned To Driver.', 19, 105, '2021-04-08 16:46:43', 'success'),
(159, 'Your Order #1017 has been Assigned To Driver.', 19, 101, '2021-04-08 16:46:44', 'success'),
(160, 'Your Order #1036 has been Assigned To Driver.', 122, 121, '2021-04-08 16:51:17', 'success'),
(161, 'Your Order #1037 has been Assigned To Driver.', 122, 122, '2021-04-08 16:51:23', 'success'),
(162, 'Your Order #1036 has been Assigned To Driver.', 122, 121, '2021-04-08 16:52:24', 'success'),
(163, 'Your Order #1037 has been Assigned To Driver.', 122, 122, '2021-04-08 16:52:29', 'success'),
(164, 'Your Order #1020 has been Assigned To Driver.', 19, 96, '2021-04-08 16:53:03', 'success'),
(165, 'Your Order #1019 has been Assigned To Driver.', 19, 105, '2021-04-08 16:53:04', 'success'),
(166, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 1, 123, '2021-04-08 17:03:56', 'success'),
(167, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 1, 124, '2021-04-08 17:05:38', 'success'),
(168, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 1, 125, '2021-04-08 17:09:52', 'success'),
(169, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 1, 126, '2021-04-08 17:14:25', 'success'),
(170, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 1, 127, '2021-04-08 17:21:03', 'success'),
(171, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 1, 128, '2021-04-08 18:00:19', 'success'),
(172, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 1, 129, '2021-04-08 18:01:23', 'success'),
(173, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 1, 130, '2021-04-08 18:11:30', 'success'),
(174, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 1, 132, '2021-04-09 09:56:46', 'success'),
(175, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 1, 133, '2021-04-09 10:01:14', 'success'),
(176, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 1, 134, '2021-04-09 10:38:29', 'success'),
(177, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 1, 135, '2021-04-09 11:10:48', 'success'),
(178, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 1, 139, '2021-04-09 11:24:23', 'success'),
(179, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 1, 140, '2021-04-09 11:29:47', 'success'),
(180, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 1, 141, '2021-04-09 11:34:15', 'success'),
(181, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 1, 142, '2021-04-09 11:38:41', 'success'),
(182, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 1, 143, '2021-04-09 11:43:21', 'success'),
(183, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 1, 144, '2021-04-09 11:47:07', 'success'),
(184, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 1, 145, '2021-04-09 11:55:10', 'success'),
(185, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 1, 146, '2021-04-09 12:19:12', 'success'),
(186, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 1, 147, '2021-04-09 12:21:16', 'success'),
(187, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 1, 149, '2021-04-09 12:29:29', 'success'),
(188, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 1, 148, '2021-04-09 12:36:12', 'success'),
(189, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 1, 150, '2021-04-09 12:41:17', 'success'),
(190, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 1, 138, '2021-04-09 12:57:55', 'success'),
(191, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 1, 151, '2021-04-09 13:58:52', 'success'),
(192, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 1, 152, '2021-04-09 14:03:17', 'success'),
(193, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 1, 153, '2021-04-09 14:25:35', 'success'),
(194, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 1, 154, '2021-04-09 15:05:16', 'success'),
(195, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 1, 155, '2021-04-09 16:23:17', 'success'),
(196, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 1, 156, '2021-04-09 16:24:37', 'success'),
(197, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 1, 157, '2021-04-09 16:35:26', 'success'),
(198, 'Your Order #1042 has been Assigned To Driver.', 1, 157, '2021-04-09 16:47:02', 'success'),
(199, 'Your Order #1041 has been Assigned To Driver.', 1, 154, '2021-04-09 17:10:57', 'success'),
(200, 'Your Order #1040 has been Assigned To Driver.', 1, 153, '2021-04-09 17:14:03', 'success'),
(201, 'Your Order #1043 has been Assigned To Driver.', 1, 155, '2021-04-15 16:42:50', 'success'),
(202, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 1, 158, '2021-04-17 14:57:33', 'success'),
(203, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 122, 169, '2021-04-19 13:01:55', 'success'),
(204, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 122, 171, '2021-04-19 17:36:09', 'success'),
(205, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 122, 172, '2021-04-19 17:51:57', 'success'),
(206, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 122, 175, '2021-04-19 18:01:52', 'success'),
(207, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 122, 182, '2021-04-19 18:41:06', 'success'),
(208, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 122, 183, '2021-04-19 18:52:33', 'success'),
(209, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 122, 184, '2021-04-20 12:50:38', 'success'),
(210, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 122, 185, '2021-04-20 12:59:34', 'success'),
(211, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 122, 190, '2021-04-20 14:48:34', 'success'),
(212, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 122, 191, '2021-04-20 17:40:08', 'success'),
(213, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 122, 192, '2021-04-20 18:00:18', 'success'),
(214, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 122, 195, '2021-04-22 12:28:52', 'success'),
(215, 'Your Order has been Approved. Please Checkout to Confirm Your Order.', 122, 197, '2021-04-23 11:42:04', 'success'),
(216, 'Your Order has Been By Bigdaddy. Please Checkout to Confirm Your Order.', 122, 200, '2021-04-28 18:05:35', 'success'),
(217, 'Your Order has been Cancelled By You.', 122, 200, '2021-04-29 12:32:08', 'danger'),
(218, 'Your Order has Been By Bigdaddy. Please Checkout to Confirm Your Order.', 1, 202, '2021-04-29 16:18:43', 'success'),
(219, 'Your Order has Been By Bigdaddy. Please Checkout to Confirm Your Order.', 122, 203, '2021-04-29 16:20:10', 'success'),
(220, 'Your Order has been Cancelled By You.', 122, 203, '2021-04-29 16:23:39', 'danger'),
(221, 'Your Order has Been Created By Bigdaddy. Please Checkout to Confirm Your Order.', 122, 204, '2021-04-29 16:46:28', 'success'),
(222, 'Your Order has been Cancelled By You.', 122, 204, '2021-04-29 16:49:16', 'danger'),
(223, 'Your Order has Been Created By Bigdaddy. Please Checkout to Confirm Your Order.', 122, 205, '2021-04-30 11:00:58', 'success'),
(224, 'Your Order has Been Created By Bigdaddy. Please Checkout to Confirm Your Order.', 122, 206, '2021-04-30 13:01:56', 'success'),
(225, 'Your Order has Been Created By Bigdaddy. Please Checkout to Confirm Your Order.', 122, 207, '2021-04-30 13:01:56', 'success'),
(226, 'Your Order #1019 has been Assigned To Driver.', 19, 105, '2021-04-30 16:05:47', 'success'),
(227, 'Your Order #1053 has been Assigned To Driver.', 122, 199, '2021-04-30 16:11:35', 'success'),
(228, 'Your Order #1052 has been Assigned To Driver.', 122, 198, '2021-04-30 18:29:18', 'success'),
(229, 'Your Order #1051 has been Assigned To Driver.', 122, 192, '2021-05-01 12:54:19', 'success'),
(230, 'Your Order #1053 has been Assigned To Driver.', 122, 199, '2021-05-03 12:55:56', 'success'),
(231, 'Your Order #1050 has been Assigned To Driver.', 122, 191, '2021-05-03 15:36:25', 'success'),
(232, 'Your Order has Been Created By Bigdaddy. Please Checkout to Confirm Your Order.', 1, 208, '2021-05-03 15:41:07', 'success'),
(233, 'Your Order #1052 is Out For Delivery.', 122, 198, '2021-05-03 15:47:44', 'success'),
(234, 'Your Order #1064 has been Assigned To Driver.', 128, 211, '2021-05-03 15:56:06', 'success'),
(235, 'Your Order #1063 has been Assigned To Driver.', 129, 210, '2021-05-03 15:56:06', 'success'),
(236, 'Your Order #1062 has been Assigned To Driver.', 122, 209, '2021-05-03 15:56:07', 'success'),
(237, 'Your Order #1064 is Out For Delivery.', 128, 211, '2021-05-03 16:34:01', 'success'),
(238, 'Your Order #1062 is Out For Delivery.', 122, 209, '2021-05-03 16:38:47', 'success'),
(239, 'Your Order #1063 is Out For Delivery.', 129, 210, '2021-05-03 16:46:47', 'success'),
(240, 'Your Order #1063 has been Assigned To Driver.', 129, 210, '2021-05-03 16:51:31', 'success'),
(241, 'Your Order #1049 has been Assigned To Driver.', 122, 185, '2021-05-03 16:52:58', 'success'),
(242, 'Your Order #1062 has been Delivered By Mohammad Fahad Shaikh.', 122, 209, '2021-05-03 16:53:31', 'success'),
(243, 'Your Order #1063 is Out For Delivery.', 129, 210, '2021-05-03 16:54:52', 'success'),
(244, 'Your Order #1063 has been Delivered By Mohammad Fahad Shaikh.', 129, 210, '2021-05-03 16:55:21', 'success'),
(245, 'Your Order #1048 has been Assigned To Driver.', 122, 184, '2021-05-03 17:01:05', 'success'),
(246, 'Your Order #1047 has been Assigned To Driver.', 122, 180, '2021-05-03 17:01:06', 'success'),
(247, 'Your Order #1019 has been Assigned To Driver.', 19, 105, '2021-05-03 17:05:19', 'success'),
(248, 'Your Order #1036 has been Assigned To Driver.', 122, 121, '2021-05-03 17:05:52', 'success'),
(249, 'Your Order #1049 is Out For Delivery.', 122, 185, '2021-05-03 17:08:29', 'success'),
(250, 'Your Order #1049 has been Delivered By Mohammad Fahad Shaikh.', 122, 185, '2021-05-03 17:09:22', 'success'),
(251, 'Your Order #1048 is Out For Delivery.', 122, 184, '2021-05-03 17:12:41', 'success'),
(252, 'Your Order #1048 has been Delivered By Mohammad Fahad Shaikh.', 122, 184, '2021-05-03 17:13:09', 'success'),
(253, 'Your Order #1040 has been Assigned To Driver.', 1, 153, '2021-05-03 17:32:57', 'success'),
(254, 'Your Order #1047 is Out For Delivery.', 122, 180, '2021-05-03 17:33:41', 'success'),
(255, 'Your Order #1046 has been Assigned To Driver.', 122, 175, '2021-05-03 17:39:36', 'success'),
(256, 'Your Order #1047 has been Delivered By Mohammad Fahad Shaikh.', 122, 180, '2021-05-03 18:03:53', 'success'),
(257, 'Your Order #1040 is Out For Delivery.', 1, 153, '2021-05-03 18:21:01', 'success'),
(258, 'Your Order #1040 has been Undelivered.', 1, 153, '2021-05-03 18:21:20', 'success'),
(259, 'Your Order #1045 has been Assigned To Driver.', 122, 171, '2021-05-03 18:22:04', 'success'),
(260, 'Your Order #1044 has been Assigned To Driver.', 122, 169, '2021-05-03 18:22:05', 'success'),
(261, 'Your Order #1045 is Out For Delivery.', 122, 171, '2021-05-03 18:22:24', 'success'),
(262, 'Your Order #1045 has been Delivered By Mohammad Fahad Shaikh.', 122, 171, '2021-05-03 18:22:31', 'success'),
(263, 'Your Order #1039 has been Assigned To Driver.', 1, 152, '2021-05-03 18:22:58', 'success'),
(264, 'Your Order #1038 has been Assigned To Driver.', 1, 151, '2021-05-03 18:23:00', 'success'),
(265, 'Your Order #1044 is Out For Delivery.', 122, 169, '2021-05-03 18:23:15', 'success'),
(266, 'Your Order #1044 has been Undelivered.', 122, 169, '2021-05-03 18:23:31', 'success'),
(267, 'Your Order #1039 is Out For Delivery.', 1, 152, '2021-05-03 18:27:44', 'success'),
(268, 'Your Order #1039 has been Undelivered.', 1, 152, '2021-05-03 18:27:53', 'success'),
(269, 'Your Order #1031 has been Assigned To Driver.', 19, 106, '2021-05-06 12:59:11', 'success'),
(270, 'Your Order #1023 has been Assigned To Driver.', 19, 108, '2021-05-06 12:59:15', 'success'),
(271, 'Your Order #1031 is Out For Delivery.', 19, 106, '2021-05-06 12:59:33', 'success'),
(272, 'Your Order #1031 has been Delivered By Mohammad Fahad Shaikh.', 19, 106, '2021-05-06 13:00:31', 'success'),
(273, 'Your Order #1023 is Out For Delivery.', 19, 108, '2021-05-06 14:03:49', 'success'),
(274, 'Your Order #1023 has been Delivered By Mohammad Fahad Shaikh.', 19, 108, '2021-05-06 14:20:51', 'success'),
(275, 'Your Order #1066 has been Assigned To Driver.', 19, 215, '2021-05-06 14:24:50', 'success'),
(276, 'Your Order #1065 has been Assigned To Driver.', 19, 214, '2021-05-06 14:24:52', 'success'),
(277, 'Your Order #1066 is Out For Delivery.', 19, 215, '2021-05-06 14:26:39', 'success'),
(278, 'Your Order #1066 has been Delivered By Mohammad Fahad Shaikh.', 19, 215, '2021-05-06 14:28:41', 'success'),
(279, 'Your Order #1066 has been Delivered By Mohammad Fahad Shaikh.', 19, 215, '2021-05-06 14:36:51', 'success'),
(280, 'Your Order #1066 has been Delivered By Mohammad Fahad Shaikh.', 19, 215, '2021-05-06 14:38:07', 'success'),
(281, 'Your Order #1065 is Out For Delivery.', 19, 214, '2021-05-06 14:38:32', 'success'),
(282, 'Your Order #1065 has been Delivered By Mohammad Fahad Shaikh.', 19, 214, '2021-05-06 14:38:55', 'success'),
(283, 'Your Order #1068 has been Assigned To Driver.', 19, 217, '2021-05-06 14:46:49', 'success'),
(284, 'Your Order #1067 has been Assigned To Driver.', 19, 216, '2021-05-06 14:46:50', 'success'),
(285, 'Your Order #1068 is Out For Delivery.', 19, 217, '2021-05-06 14:47:32', 'success'),
(286, 'Your Order #1068 has been Delivered By Mohammad Fahad Shaikh.', 19, 217, '2021-05-06 14:55:49', 'success'),
(287, 'Your Order #1067 has been Delivered By Mohammad Fahad Shaikh.', 19, 216, '2021-05-06 14:56:29', 'success'),
(288, 'Your Order #1022 has been Assigned To Driver.', 19, 109, '2021-05-06 15:00:46', 'success'),
(289, 'Your Order #1015 has been Assigned To Driver.', 19, 102, '2021-05-06 15:00:47', 'success'),
(290, 'Your Order #1013 has been Assigned To Driver.', 19, 100, '2021-05-06 15:01:02', 'success'),
(291, 'Your Order #1011 has been Assigned To Driver.', 19, 99, '2021-05-06 15:01:03', 'success'),
(292, 'Your Order #1022 is Out For Delivery.', 19, 109, '2021-05-06 15:01:40', 'success'),
(293, 'Your Order #1022 has been Delivered By Mohammad Fahad Shaikh.', 19, 109, '2021-05-06 15:01:48', 'success'),
(294, 'Your Order #1015 is Out For Delivery.', 19, 102, '2021-05-06 15:05:40', 'success'),
(295, 'Your Order #1015 has been Delivered By Mohammad Fahad Shaikh.', 19, 102, '2021-05-06 15:08:03', 'success'),
(296, 'Your Order #1013 is Out For Delivery.', 19, 100, '2021-05-06 15:22:17', 'success'),
(297, 'Your Order #1013 has been Delivered By Mohammad Fahad Shaikh.', 19, 100, '2021-05-06 15:22:26', 'success'),
(298, 'Your Order #1011 is Out For Delivery.', 19, 99, '2021-05-06 16:03:48', 'success'),
(299, 'Your Order #1011 has been Undelivered.', 19, 99, '2021-05-06 16:08:20', 'success');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_customer_uploaded_files`
--

CREATE TABLE `tbl_customer_uploaded_files` (
  `id` bigint(20) NOT NULL,
  `lrfile` varchar(100) DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-active,1-completed,2-deactive',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_customer_uploaded_files`
--

INSERT INTO `tbl_customer_uploaded_files` (`id`, `lrfile`, `user_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'LR_606ec81b107ad1617872923.jpg', 1, 0, '2021-04-08 14:38:43', '2021-04-08 14:38:43'),
(2, 'LR_6077d08f2c5211618464911.jpg', 1, 0, '2021-04-15 11:05:12', '2021-04-15 11:05:12');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_drivers`
--

CREATE TABLE `tbl_drivers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fullname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(111) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'optional',
  `mobile` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pan_card` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` int(11) NOT NULL DEFAULT 1 COMMENT '0-active,1-deactive, 2- deleted',
  `status` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'O',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `country` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'India',
  `state` varchar(55) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(55) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pincode` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_pic` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_latitude` varchar(33) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_longitude` varchar(33) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `aadhar_card_file` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `license_file` varchar(600) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `license_expiry` date DEFAULT NULL,
  `is_salary_based` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-no,1-yes',
  `salary_amount` int(11) NOT NULL DEFAULT 0 COMMENT 'salary_amount',
  `pan_card_file` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_token` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'token of fcm',
  `ipaddress` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vendor_id` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_drivers`
--

INSERT INTO `tbl_drivers` (`id`, `fullname`, `email`, `mobile`, `address`, `pan_card`, `password`, `is_active`, `status`, `created_at`, `updated_at`, `country`, `state`, `city`, `pincode`, `profile_pic`, `current_location`, `current_latitude`, `current_longitude`, `aadhar_card_file`, `license_file`, `license_expiry`, `is_salary_based`, `salary_amount`, `pan_card_file`, `device_token`, `ipaddress`, `vendor_id`) VALUES
(1, 'JINENDRA JAIN', 'ABC@XXX.COM', '2354264825', 'SHOP NO 1, GROUND FLOOR, HAPPY PALACE, LH ROAD B/H CHAMUNDA NAGAR, SURAT,  Gujarat, 395010', 'xy1xaxa2', '$2y$10$car44JlU4r8z6viBXDgV.e1RA4iiVL9AFC2xBfx1x7qYy/9kAWUPS', 2, 'O', '2021-01-25 13:12:53', '2021-01-27 11:43:01', 'India', 'gujrat', 'surat', '395003', 'driverp600e767d1f49f1611560573.png', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 0),
(2, 'UDAYSINGH RAJPUROHIT', 'ABd@XXX.COM', '2530415168', '260(M) , 2ND FLOOR MILLENNIUM TEXTILE MARKET, RING ROAD , SURAT', 'xy1xaxa1', '$2y$10$KzurE2XxxHtOufdCgiFrn.PkbjHxLN1ObC8Jn9ggrUDnS1Ry536va', 2, 'O', '2021-01-25 13:16:41', '2021-01-27 11:25:29', 'India', 'Gujarat', 'Surat', '395010', 'driverp60110051c6ab31611726929.png', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 0),
(3, 'Nilay Navnitlal Shah', NULL, '8401000419', 'dumbhal transport imagica surat', NULL, '$2y$10$PslD1vTnXYu4pRqj31rQ8eF2x9VdvIBx03t54y4y7OyogqrkCmhoO', 1, 'O', '2021-02-15 13:21:09', '2021-04-05 22:53:40', 'India', 'gujrat', 'surat', '390005', 'driverp602a27ed61f5d1613375469.png', NULL, '72.86475395242044', '21.21152561093236', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 3),
(4, 'ShivKaran', NULL, '7802922872', 'Borbhatha, Surat', NULL, '$2y$10$494UGYcE6G26ERe6ujyMO./x.0N2SOAOlB5VPdv0V0oyf3Qp6ImeO', 1, 'O', '2021-02-23 11:33:55', '2021-03-24 13:46:49', 'India', 'Gujrat', 'Surat', '395003', 'driverp60349acae8f821614060234.png', NULL, NULL, NULL, 'ac603f46353e9011614759477.jpg', 'lis605af571819381616573809.jpeg', '2022-03-16', 0, 0, NULL, NULL, NULL, 0),
(5, 'Shoaib Sheikh', NULL, '9723415016', '', NULL, '$2y$10$Ze7juAU.uJDxZZi9UAmi1usjiiqPIqOtEtQKTk/y3ggzkFlX6uWGm', 1, 'O', '2021-02-23 12:02:04', '2021-04-17 11:11:10', 'India', 'Gujarat', 'Surat', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 0),
(6, 'Mohammad Fahad Shaikh', NULL, '6355301918', '3097, Shah Bhagal, Rander Surat', 'CPNPP8759P', '$2y$10$cJ9.2PQ/i42DskgYnHlFfeVOtV0rK.eIpekjEB5lxFjx.a6FC.m/.', 0, 'A', '2021-03-01 12:05:12', '2021-05-06 16:03:48', 'India', 'Gujarat', 'Surat', '395005', 'dp603e1b91d87021614683025.png', NULL, '21.15044946348263', '72.87329630967', NULL, 'lis603e1b91da22f1614683025.jpeg', '2038-12-17', 0, 0, NULL, '7678678yutuytyuthjgjhghjgjghf6756tffghmnbmhg876786hgjhgjgj77678ghjgj', NULL, 2),
(7, 'Shaikh Ezaz', NULL, '6354756267', 'SY.No.33Plot 8 Room2 Block 39 Hlaf part Oh South Side Tena-25B, Gali No.2 Limbayat,Pratap Nagar Mithi Khadi, Surat.', 'MRXPS2209H', '$2y$10$mxX337DoLCTXWi7AgnLfPex32q5VEyiklsJUy53s7gRsnbs27jOm2', 0, 'A', '2021-03-01 12:24:07', '2021-04-09 18:05:08', 'India', 'Gujrat', 'Surat', '394210', 'dp603c98d51db081614584021.png', NULL, '21.2335903', '72.8631777', NULL, NULL, NULL, 1, 30000, 'ac605af3518c9001616573265.jpeg', 'dFyZXZoISc6MC_70SuAfah:APA91bE5BspCTxaC3yfjBMNcqDaoKATL7gZi9EYfj-C2WrtDUHfBjJ1MVZS_TBnz1TqO4KumGLNSp2NYLnTwU_JaexSsR42gN4I1cYzwcVvJeduIE2Z5al7G1238AkKYHSTsNy9g2qiJ', NULL, 1),
(19, 'Big Daddy', NULL, '8866554888', '', 'AHAPA3902E', '$2y$10$AExznsaWU/0hQtVYotVOzO54BSV3qtYBwCty4r./Rwk14xYWCx/J6', 0, 'A', '2021-03-20 13:53:24', '2021-04-01 00:01:51', 'India', 'Gujarat', 'Surat', '395007', NULL, NULL, '0.0', '0.0', NULL, NULL, '2022-03-30', 0, 0, NULL, 'cJ4c2PFXSnOOAeA9FAVVow:APA91bEHCt7GMHIoOzMMx0OfTakttvBkb1LgOA9oJr_r4pKiNtHZnPz2E8-QJUhjkcFwxDDTIReCmJUutcKmxUpZYbj9_XY4OGP5wpN4iMqaKvyIoGMIPdTu1UCHEVVjp1jZPwdl2PKG', NULL, 0),
(20, 'Pranav Begade', NULL, '9725789197', 'surat', NULL, '$2y$10$O3m4IhU8ayPucc2U3Or8deJ2D2Jq.20Mo0bGTuW8JzdsI/MxqdssS', 0, 'A', '2021-03-24 14:47:52', '2021-04-02 22:11:00', 'India', 'gujarat', 'surat', '394105', NULL, NULL, '21.2336054', '72.8632646', NULL, NULL, '2022-04-03', 0, 0, NULL, 'drcajVQrRPq1aQBwEKs0l6:APA91bETXmk4UTLEr2cYv2vHppgvKKW7diQHYNJV5vBj2lN53NhIkc1vp1nCIx0BPKO0s69nlD3ip3vxO_M3nEVC9lCvyls923SsgTr-DQCXsL4VJ4T1ANqe3r-l0BIAtjy18VPrs_XC', NULL, 0),
(21, 'TechNomads testing', NULL, '6354953278', '', NULL, '$2y$10$DppckENXyV8jd/KBfmc3Nu84cLkGoUfg2JDBI5PEObFNRcnECHV6S', 0, 'O', '2021-03-27 19:55:30', '2021-03-30 21:19:19', 'India', 'Gujarat', 'Surat', '394105', NULL, NULL, '0.0', '0.0', NULL, NULL, '2022-04-06', 1, 35000, NULL, 'eg6CGcZQROySN8_5utNNNQ:APA91bHuYjKuT30qDMgfJzk5Dx1MZJEm9wEWo_W0m8AIKRzeyzA6ZObp7mVh_VN4z2sdxqLbCh9md2DV5BeS7OZCLfGTc1KczN_Q5pDeTw2MZ5L97OZbuqHY1pf-mSo4T3r7LV-Wy9T0', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_drivers_files`
--

CREATE TABLE `tbl_drivers_files` (
  `id` int(10) UNSIGNED NOT NULL,
  `img` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'file',
  `driver_id` int(11) NOT NULL,
  `img_type_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'like ''Insurance File Img''',
  `short_helper_name` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` int(11) NOT NULL DEFAULT 0 COMMENT '0-active,1-deactive, 2- deleted',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `if_expiry_date` datetime DEFAULT NULL COMMENT 'if date else null'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_drivers_files`
--

INSERT INTO `tbl_drivers_files` (`id`, `img`, `driver_id`, `img_type_name`, `short_helper_name`, `is_active`, `created_at`, `updated_at`, `if_expiry_date`) VALUES
(1, 'driverac600e767d47d671611560573.jpg', 1, 'Aadhar Card', 'AC', 0, '2021-01-25 13:12:53', '2021-01-25 13:12:53', NULL),
(2, 'driverl600e767d4a1741611560573.jpg', 1, 'License', 'L', 0, '2021-01-25 13:12:53', '2021-01-25 13:12:53', '2022-01-31 00:00:00'),
(3, 'driverrb600e767d4bfbb1611560573.jpg', 1, 'RC book', 'RB', 0, '2021-01-25 13:12:54', '2021-01-25 13:12:54', '2027-01-28 00:00:00'),
(4, 'driverin600e767e4fbb71611560574.jpg', 1, 'Insurance', 'IN', 0, '2021-01-25 13:12:54', '2021-01-25 13:12:54', '2025-01-27 00:00:00'),
(5, 'driverpd600e767e519841611560574.jpg', 1, 'Permit', 'PD', 0, '2021-01-25 13:12:54', '2021-01-25 13:12:54', '2030-01-14 00:00:00'),
(6, 'driverpc600e767e537481611560574.jpg', 1, 'PUC', 'PC', 0, '2021-01-25 13:12:54', '2021-01-25 13:12:54', '2022-01-21 00:00:00'),
(7, 'driverac600e776174a131611560801.jpg', 2, 'Aadhar Card', 'AC', 0, '2021-01-25 13:16:41', '2021-01-25 13:16:41', NULL),
(8, 'driverl600e77617605e1611560801.jpg', 2, 'License', 'L', 0, '2021-01-25 13:16:41', '2021-01-25 13:16:41', '2022-01-31 00:00:00'),
(9, 'driverrb600e7761774191611560801.jpg', 2, 'RC book', 'RB', 0, '2021-01-25 13:16:41', '2021-01-25 13:16:41', '2027-01-28 00:00:00'),
(10, 'driverin600e7761c9b881611560801.jpg', 2, 'Insurance', 'IN', 0, '2021-01-25 13:16:41', '2021-01-25 13:16:41', '2025-01-27 00:00:00'),
(11, 'driverpd600e7761cade41611560801.jpg', 2, 'Permit', 'PD', 0, '2021-01-25 13:16:41', '2021-01-25 13:16:41', '2030-01-14 00:00:00'),
(12, 'driverpc600e7761cbf701611560801.jpg', 2, 'PUC', 'PC', 0, '2021-01-25 13:16:41', '2021-01-25 13:16:41', '2022-01-21 00:00:00'),
(13, 'driverac602a27ed85b381613375469.jpg', 3, 'Aadhar Card', 'AC', 0, '2021-02-15 13:21:09', '2021-02-15 13:21:09', NULL),
(14, 'driverl602a27ed872ee1613375469.jpg', 3, 'License', 'L', 0, '2021-02-15 13:21:09', '2021-02-15 13:21:09', '2022-01-31 00:00:00'),
(16, 'driverin602a27f2895641613375474.jpg', 3, 'Insurance', 'IN', 0, '2021-02-15 13:21:14', '2021-02-15 13:21:14', '2025-01-27 00:00:00'),
(17, 'driverpd602a27f28a6f71613375474.jpg', 3, 'Permit', 'PD', 0, '2021-02-15 13:21:14', '2021-02-15 13:21:14', '2030-01-14 00:00:00'),
(18, 'driverpc602a27f28b8911613375474.jpg', 3, 'PUC', 'PC', 0, '2021-02-15 13:21:14', '2021-02-15 13:21:14', '2022-01-21 00:00:00'),
(20, 'driverl60349acb205621614060235.jpeg', 4, 'License', 'L', 0, '2021-02-23 11:33:55', '2021-02-23 11:33:55', '2038-10-23 00:00:00'),
(21, 'driverrb60349acb210001614060235.jpeg', 4, 'RC book', 'RB', 0, '2021-02-23 11:33:55', '2021-02-23 11:33:55', '2038-02-01 00:00:00'),
(22, 'driverin60349acb219d41614060235.jpeg', 4, 'Insurance', 'IN', 0, '2021-02-23 11:33:55', '2021-02-23 11:33:55', '2021-09-30 00:00:00'),
(23, 'driverpd60349acb226271614060235.jpeg', 4, 'Permit', 'PD', 0, '2021-02-23 11:33:55', '2021-02-23 11:33:55', '2021-09-30 00:00:00'),
(24, 'driverpc60349acb2317c1614060235.jpeg', 4, 'PUC', 'PC', 0, '2021-02-23 11:33:55', '2021-02-23 11:33:55', '2022-01-21 00:00:00'),
(121, 'ot60363501a29581614165249.png', 23, 'Other', 'O', 0, '2021-02-24 22:14:09', '2021-02-24 22:14:09', NULL),
(122, 'ot60363501a2ed21614165249.png', 23, 'Other', 'O', 0, '2021-02-24 22:14:09', '2021-02-24 22:14:09', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_driver_accounts`
--

CREATE TABLE `tbl_driver_accounts` (
  `id` bigint(20) NOT NULL,
  `amount` decimal(13,2) NOT NULL DEFAULT 0.00,
  `driver_id` int(11) NOT NULL DEFAULT 0,
  `type` varchar(5) NOT NULL DEFAULT '''PRL''' COMMENT 'PPT - paypertrip , PPP - payperparcel, PRL - payroll',
  `is_active` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-active,1-deactive, 2- deleted',
  `created_at` datetime DEFAULT NULL,
  `account_datetime` datetime DEFAULT NULL COMMENT 'user datetime',
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_driver_files_type`
--

CREATE TABLE `tbl_driver_files_type` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `short` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `details` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` int(11) NOT NULL DEFAULT 0 COMMENT '0-active,1-deactive, 2- deleted',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `is_exclude` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-no, 1-yes',
  `ask_expiry` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-no, 1-yes',
  `is_multiple` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1-single,0-multiple files'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_driver_files_type`
--

INSERT INTO `tbl_driver_files_type` (`id`, `name`, `short`, `details`, `is_active`, `created_at`, `updated_at`, `is_exclude`, `ask_expiry`, `is_multiple`) VALUES
(5, 'Aadhar Card', 'AC', 'Aadhar Card', 0, '2020-11-20 00:00:00', '2020-11-20 00:00:00', 0, 0, 0),
(6, 'License', 'L', 'License', 0, '2020-11-20 00:00:00', '2020-11-20 00:00:00', 0, 1, 0),
(8, 'RC book', 'RB', 'RC book', 0, '2020-11-20 00:00:00', '2020-11-20 00:00:00', 0, 1, 0),
(9, 'Insurance', 'IN', 'Insurance', 0, '2020-11-20 00:00:00', '2020-11-20 00:00:00', 0, 1, 0),
(10, 'Permit', 'PD', 'Permit Document', 0, '2020-11-20 00:00:00', '2020-11-20 00:00:00', 0, 1, 0),
(11, 'PUC', 'PC', 'Pollution Under Control Certificate', 0, '2020-11-20 00:00:00', '2020-11-20 00:00:00', 0, 1, 0),
(12, 'Other', 'O', 'Other Document', 0, '2020-11-20 00:00:00', '2020-11-20 00:00:00', 1, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_driver_logs`
--

CREATE TABLE `tbl_driver_logs` (
  `id` bigint(20) NOT NULL,
  `logs` text DEFAULT NULL,
  `driver_id` int(11) NOT NULL COMMENT 'driver id',
  `created_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_driver_logs`
--

INSERT INTO `tbl_driver_logs` (`id`, `logs`, `driver_id`, `created_at`) VALUES
(1, 'DeActivated By Hardik Patel', 2, '2021-02-16 13:41:46'),
(2, 'DeActivated By Hardik Patel', 1, '2021-02-16 13:42:08');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_driver_notifications`
--

CREATE TABLE `tbl_driver_notifications` (
  `id` bigint(20) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `notification_text` varchar(1000) NOT NULL,
  `driver_id` int(11) NOT NULL DEFAULT 0 COMMENT '0-for all else driver_id',
  `order_id` int(11) NOT NULL DEFAULT 0 COMMENT '0-for not order notification\r\n',
  `created_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_driver_notifications`
--

INSERT INTO `tbl_driver_notifications` (`id`, `title`, `notification_text`, `driver_id`, `order_id`, `created_at`) VALUES
(78, 'New Order', 'Order #1040 has been Assigned to You By Admin', 7, 153, '2021-04-09 17:14:02'),
(77, 'New Order', 'Order #1041 has been Assigned to You By Admin', 7, 154, '2021-04-09 17:10:57'),
(76, 'New Order', 'Order #1042 has been Assigned to You By GB TEST', 6, 157, '2021-04-09 16:47:01'),
(75, 'New Order', 'Order #1019 has been Assigned to You By GB TEST', 7, 105, '2021-04-08 16:53:03'),
(105, 'New Order', 'Order #1068 has been Assigned to You By Technomads Developer', 6, 217, '2021-05-06 14:46:48'),
(74, 'New Order', 'Order #1020 has been Assigned to You By GB TEST', 7, 96, '2021-04-08 16:53:03'),
(73, 'New Order', 'Order #1037 has been Assigned to You By GB TEST', 7, 122, '2021-04-08 16:52:29'),
(72, 'New Order', 'Order #1036 has been Assigned to You By GB TEST', 7, 121, '2021-04-08 16:52:23'),
(71, 'New Order', 'Order #1037 has been Assigned to You By GB TEST', 7, 122, '2021-04-08 16:51:23'),
(70, 'New Order', 'Order #1036 has been Assigned to You By GB TEST', 7, 121, '2021-04-08 16:51:17'),
(69, 'New Order', 'Order #1017 has been Assigned to You By GB TEST', 7, 101, '2021-04-08 16:46:44'),
(68, 'New Order', 'Order #1019 has been Assigned to You By GB TEST', 7, 105, '2021-04-08 16:46:43'),
(67, 'New Order', 'Order #1020 has been Assigned to You By GB TEST', 7, 96, '2021-04-08 16:44:40'),
(66, 'New Order', 'Order #1037 has been Assigned to You By GB TEST', 7, 122, '2021-04-08 16:38:24'),
(65, 'New Order', 'Order #1036 has been Assigned to You By GB TEST', 7, 121, '2021-04-08 16:38:18'),
(64, 'New Order', 'Order #1036 has been Assigned to You By GB TEST', 7, 121, '2021-04-08 16:36:58'),
(63, 'New Order', 'Order #1037 has been Assigned to You By GB TEST', 7, 122, '2021-04-08 16:36:58'),
(62, 'New Order', 'Order #1036 has been Assigned to You By GB TEST', 7, 121, '2021-04-08 16:34:46'),
(61, 'New Order', 'Order #1037 has been Assigned to You By GB TEST', 7, 122, '2021-04-08 16:34:46'),
(60, 'New Order', 'Order #1037 has been Assigned to You By GB TEST', 7, 122, '2021-04-08 16:34:22'),
(59, 'New Order', 'Order #1036 has been Assigned to You By GB TEST', 7, 121, '2021-04-08 16:34:14'),
(58, 'New Order', 'Order #1037 has been Assigned to You By GB TEST', 7, 122, '2021-04-08 16:33:41'),
(57, 'New Order', 'Order #1036 has been Assigned to You By GB TEST', 7, 121, '2021-04-08 16:33:35'),
(56, 'New Order', 'Order #1036 has been Assigned to You By GB TEST', 7, 121, '2021-04-08 16:31:38'),
(55, 'New Order', 'Order #1037 has been Assigned to You By GB TEST', 7, 122, '2021-04-08 16:31:37'),
(54, 'New Order', 'Order #1037 has been Assigned to You By GB TEST', 7, 122, '2021-04-08 16:13:28'),
(53, 'New Order', 'Order #1036 has been Assigned to You By GB TEST', 7, 121, '2021-04-08 16:13:18'),
(52, 'New Order', 'Order #1035 has been Assigned to You By GB TEST', 7, 120, '2021-04-08 16:08:28'),
(51, 'New Order', 'Order #1034 has been Assigned to You By GB TEST', 7, 119, '2021-04-08 16:08:13'),
(50, 'New Order', 'Order #1016 has been Assigned to You By GB TEST', 7, 103, '2021-04-08 11:30:42'),
(49, 'New Order', 'Order #1017 has been Assigned to You By GB TEST', 6, 101, '2021-04-08 11:27:49'),
(48, 'New Order', 'Order #1019 has been Assigned to You By GB TEST', 6, 105, '2021-04-08 11:25:29'),
(47, 'New Order', 'Order #1020 has been Assigned to You By GB TEST', 6, 96, '2021-04-08 11:25:29'),
(104, 'New Order', 'Order #1065 has been Assigned to You By Technomads Developer', 6, 214, '2021-05-06 14:24:51'),
(103, 'New Order', 'Order #1066 has been Assigned to You By Technomads Developer', 6, 215, '2021-05-06 14:24:50'),
(102, 'New Order', 'Order #1023 has been Assigned to You By Technomads Developer', 6, 108, '2021-05-06 12:59:14'),
(79, 'New Order', 'Order #1043 has been Assigned to You By GB TEST', 6, 155, '2021-04-15 16:42:49'),
(80, 'New Order', 'Order #1019 has been Assigned to You By GB TEST', 7, 105, '2021-04-30 16:05:43'),
(81, 'New Order', 'Order #1053 has been Assigned to You By GB TEST', 6, 199, '2021-04-30 16:11:34'),
(82, 'New Order', 'Order #1052 has been Assigned to You By GB TEST', 6, 198, '2021-04-30 18:29:17'),
(83, 'New Order', 'Order #1051 has been Assigned to You By GB TEST', 6, 192, '2021-05-01 12:54:17'),
(84, 'New Order', 'Order #1053 has been Assigned to You By GB TEST', 19, 199, '2021-05-03 12:55:54'),
(85, 'New Order', 'Order #1050 has been Assigned to You By GB TEST', 6, 191, '2021-05-03 15:36:24'),
(86, 'New Order', 'Order #1064 has been Assigned to You By GB TEST', 6, 211, '2021-05-03 15:56:00'),
(87, 'New Order', 'Order #1063 has been Assigned to You By GB TEST', 6, 210, '2021-05-03 15:56:06'),
(88, 'New Order', 'Order #1062 has been Assigned to You By GB TEST', 6, 209, '2021-05-03 15:56:06'),
(89, 'New Order', 'Order #1063 has been Assigned to You By GB TEST', 6, 210, '2021-05-03 16:51:30'),
(90, 'New Order', 'Order #1049 has been Assigned to You By GB TEST', 6, 185, '2021-05-03 16:52:56'),
(91, 'New Order', 'Order #1048 has been Assigned to You By GB TEST', 6, 184, '2021-05-03 17:01:04'),
(92, 'New Order', 'Order #1047 has been Assigned to You By GB TEST', 6, 180, '2021-05-03 17:01:05'),
(93, 'New Order', 'Order #1019 has been Assigned to You By GB TEST', 7, 105, '2021-05-03 17:05:18'),
(94, 'New Order', 'Order #1036 has been Assigned to You By GB TEST', 5, 121, '2021-05-03 17:05:51'),
(95, 'New Order', 'Order #1040 has been Assigned to You By GB TEST', 6, 153, '2021-05-03 17:32:56'),
(96, 'New Order', 'Order #1046 has been Assigned to You By GB TEST', 5, 175, '2021-05-03 17:39:36'),
(97, 'New Order', 'Order #1045 has been Assigned to You By GB TEST', 6, 171, '2021-05-03 18:22:03'),
(98, 'New Order', 'Order #1044 has been Assigned to You By GB TEST', 6, 169, '2021-05-03 18:22:04'),
(99, 'New Order', 'Order #1039 has been Assigned to You By GB TEST', 6, 152, '2021-05-03 18:22:58'),
(100, 'New Order', 'Order #1038 has been Assigned to You By GB TEST', 6, 151, '2021-05-03 18:22:59'),
(101, 'New Order', 'Order #1031 has been Assigned to You By Technomads Developer', 6, 106, '2021-05-06 12:59:09'),
(106, 'New Order', 'Order #1067 has been Assigned to You By Technomads Developer', 6, 216, '2021-05-06 14:46:50'),
(107, 'New Order', 'Order #1022 has been Assigned to You By Technomads Developer', 6, 109, '2021-05-06 15:00:46'),
(108, 'New Order', 'Order #1015 has been Assigned to You By Technomads Developer', 6, 102, '2021-05-06 15:00:47'),
(109, 'New Order', 'Order #1013 has been Assigned to You By Technomads Developer', 6, 100, '2021-05-06 15:00:59'),
(110, 'New Order', 'Order #1011 has been Assigned to You By Technomads Developer', 6, 99, '2021-05-06 15:01:02');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_driver_order_arrangement`
--

CREATE TABLE `tbl_driver_order_arrangement` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `arrangement_number` int(11) DEFAULT 10000,
  `order_id` int(11) NOT NULL DEFAULT 0,
  `driver_id` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-active,1-deactive, 2- deleted',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `arrangement_type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1-pickup, 2-deliver, 0- undelivered',
  `orderaction_datetime` datetime DEFAULT NULL COMMENT 'estimated order action datetime by google',
  `is_completed` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-no,1-yes',
  `driveraction_datetime` datetime DEFAULT NULL,
  `difference_seconds` int(7) NOT NULL DEFAULT 0 COMMENT 'between driveraction_datetime & orderaction_datetime',
  `between_meters` decimal(8,2) NOT NULL DEFAULT 0.00,
  `between_seconds` int(7) NOT NULL DEFAULT 0,
  `is_early_fulfilled` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0-no,1-yes',
  `origins_latitude` varchar(55) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `origins_longitude` varchar(55) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `destinations_latitude` varchar(55) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `destinations_longitude` varchar(55) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_driver_order_arrangement`
--

INSERT INTO `tbl_driver_order_arrangement` (`id`, `arrangement_number`, `order_id`, `driver_id`, `is_active`, `created_at`, `updated_at`, `arrangement_type`, `orderaction_datetime`, `is_completed`, `driveraction_datetime`, `difference_seconds`, `between_meters`, `between_seconds`, `is_early_fulfilled`, `origins_latitude`, `origins_longitude`, `destinations_latitude`, `destinations_longitude`) VALUES
(1, NULL, 109, 6, 0, '2021-05-06 15:00:46', '2021-05-06 15:01:40', 1, NULL, 1, '2021-05-06 15:01:40', 0, '0.00', 0, 1, NULL, NULL, '21.18494396382123', '72.84413892020893'),
(2, NULL, 109, 6, 0, '2021-05-06 15:00:46', '2021-05-06 15:01:48', 2, '2021-05-06 15:08:11', 1, '2021-05-06 15:01:48', 383, '11816.00', 391, 0, '21.15044946348263', '72.87329630967', '21.163664671740516', '72.83130017898182'),
(3, NULL, 102, 6, 0, '2021-05-06 15:00:47', '2021-05-06 15:05:40', 1, '2021-05-06 15:08:15', 1, '2021-05-06 15:05:40', 155, '13132.00', 387, 0, '21.163664671740516', '72.83130017898182', '21.18494396382123', '72.84413892020893'),
(4, NULL, 102, 6, 0, '2021-05-06 15:00:47', '2021-05-06 15:08:03', 2, '2021-05-06 15:12:59', 1, '2021-05-06 15:08:03', 296, '3029.00', 439, 1, '21.15044946348263', '72.87329630967', '21.163664671740516', '72.83130017898182'),
(5, NULL, 100, 6, 0, '2021-05-06 15:00:59', '2021-05-06 15:22:17', 1, '2021-05-06 15:18:15', 1, '2021-05-06 15:22:17', 242, '3097.00', 612, 0, '21.163664671740516', '72.83130017898182', '21.18494396382123', '72.84413892020893'),
(6, NULL, 100, 6, 0, '2021-05-06 15:00:59', '2021-05-06 15:22:26', 2, '2021-05-06 15:37:02', 1, '2021-05-06 15:22:26', 876, '2706.00', 885, 1, '21.15044946348263', '72.87329630967', '21.163664671740516', '72.83130017898182'),
(7, NULL, 99, 6, 0, '2021-05-06 15:01:02', '2021-05-06 16:03:49', 1, '2021-05-06 15:27:13', 1, '2021-05-06 16:03:49', 2196, '10504.00', 287, 0, '21.163664671740516', '72.83130017898182', '21.18494396382123', '72.84413892020893'),
(8, NULL, 99, 6, 0, '2021-05-06 15:01:02', '2021-05-06 16:08:21', 0, '2021-05-06 16:10:56', 1, '2021-05-06 16:08:21', 155, '13480.00', 428, 1, '21.15044946348263', '72.87329630967', '21.163664671740516', '72.83130017898182');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_goods_type`
--

CREATE TABLE `tbl_goods_type` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `details` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `img` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-active,1-deactive, 2- deleted',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `is_editable` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0-no,1-yes'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_goods_type`
--

INSERT INTO `tbl_goods_type` (`id`, `name`, `details`, `img`, `is_active`, `created_at`, `updated_at`, `is_editable`) VALUES
(1, 'Furniture', NULL, NULL, 0, '2020-12-28 00:00:00', '2020-12-28 00:00:00', 1),
(2, 'Wood/Timber/Plywood', NULL, NULL, 0, '2020-12-28 00:00:00', '2021-02-11 15:21:22', 1),
(3, 'Electrical/Electronics', NULL, NULL, 0, '2020-12-28 00:00:00', '2021-02-11 15:21:08', 1),
(4, 'Chemical/Paints/Oil', NULL, NULL, 0, '2020-12-28 00:00:00', '2021-02-11 15:20:54', 1),
(5, 'Glass', NULL, NULL, 0, '2021-01-13 16:37:35', '2021-01-13 16:38:07', 1),
(6, 'Paper', NULL, NULL, 0, '2021-01-13 16:37:50', '2021-02-11 15:20:28', 1),
(8, 'Others', NULL, NULL, 0, '2021-01-20 11:54:15', '2021-01-20 11:54:15', 0),
(9, 'House Hold', NULL, NULL, 0, '2021-02-06 22:59:34', '2021-02-06 22:59:34', 1),
(10, 'Gifting Articles', NULL, NULL, 0, '2021-02-11 15:22:03', '2021-02-11 15:22:03', 1),
(11, 'Crockery', NULL, NULL, 0, '2021-02-11 15:22:13', '2021-02-11 15:22:13', 1),
(12, 'Utensils', NULL, NULL, 0, '2021-02-11 15:22:20', '2021-02-11 15:22:20', 1),
(13, 'Agricultural', NULL, NULL, 0, '2021-02-11 15:22:41', '2021-02-11 15:22:41', 1),
(14, 'Textiles', NULL, NULL, 0, '2021-02-11 15:23:58', '2021-02-11 15:23:58', 1),
(15, 'Gifting - Mugs/T-Shirts/Cushions', NULL, NULL, 0, '2021-02-11 15:24:53', '2021-02-11 15:24:53', 1),
(16, 'Bundle - Textile Goods', NULL, NULL, 0, '2021-02-16 12:55:40', '2021-02-16 12:55:40', 1),
(17, 'Barrel-Chemicals', NULL, NULL, 0, '2021-02-19 10:43:40', '2021-02-19 10:43:40', 1),
(18, 'Carton - Medium', NULL, NULL, 0, '2021-02-19 10:44:14', '2021-02-19 10:44:14', 1),
(19, 'Others - Motor Pump', NULL, NULL, 0, '2021-02-19 13:17:05', '2021-02-19 13:17:05', 1),
(20, 'Carton-Med-Oil', NULL, NULL, 0, '2021-02-22 17:27:09', '2021-02-22 17:27:09', 1),
(21, 'MediumBox-Fruit Crush', NULL, NULL, 0, '2021-02-23 12:52:20', '2021-02-23 12:52:20', 1),
(22, 'Over- Size-Ctn', NULL, NULL, 0, '2021-02-23 12:53:32', '2021-02-23 12:53:32', 1),
(23, 'Over-Size-Bdl', NULL, NULL, 0, '2021-02-23 14:20:08', '2021-02-23 14:20:08', 1),
(24, 'Bundle - Big', NULL, NULL, 0, '2021-02-24 12:05:44', '2021-02-24 12:05:44', 1),
(25, 'Over-Size-Parcel', NULL, NULL, 0, '2021-03-08 14:42:13', '2021-03-08 14:42:13', 1),
(26, 'Over-Size-PVC', NULL, NULL, 0, '2021-03-08 16:10:09', '2021-03-08 16:10:09', 1),
(27, 'Med-Boxes', NULL, NULL, 0, '2021-03-12 12:31:06', '2021-03-19 12:57:46', 1),
(28, 'Med-Bags', NULL, NULL, 0, '2021-03-12 12:31:32', '2021-03-12 12:31:32', 1),
(29, 'Med- Bundle', NULL, NULL, 0, '2021-03-12 13:23:20', '2021-03-19 12:57:37', 1),
(30, 'Textile Goods - Taka - Bdl', NULL, NULL, 0, '2021-03-19 10:04:47', '2021-03-19 10:04:47', 1),
(31, 'Textile Goods - Taka - Big', NULL, NULL, 0, '2021-03-19 10:04:58', '2021-03-19 10:04:58', 1),
(32, 'Textile Goods -Taka - Med', NULL, NULL, 0, '2021-03-19 10:05:06', '2021-03-31 23:33:15', 1),
(33, 'Carton - Big', NULL, NULL, 0, '2021-03-23 11:34:30', '2021-03-23 11:34:30', 1),
(34, 'Textile Goods - Roll - Med', NULL, NULL, 0, '2021-04-05 17:20:58', '2021-04-05 17:21:21', 1),
(35, 'JHgjhg', NULL, NULL, 0, '2021-04-28 12:00:15', '2021-04-28 12:00:15', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_inquiry`
--

CREATE TABLE `tbl_inquiry` (
  `id` bigint(20) NOT NULL,
  `fullname` varchar(111) DEFAULT NULL,
  `email_address` varchar(111) DEFAULT NULL,
  `mobile` varchar(15) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-pending and active,1-completed,2-deactive',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `devicetype` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1-web, 2-android, 3-ios	',
  `ipaddress` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_inquiry`
--

INSERT INTO `tbl_inquiry` (`id`, `fullname`, `email_address`, `mobile`, `is_active`, `created_at`, `updated_at`, `subject`, `message`, `devicetype`, `ipaddress`) VALUES
(1, 'abc xyz', 'mailthis@mail.ok', '9856897848', 1, '2021-02-15 18:00:31', '2021-02-18 14:25:16', 'Contact Us Inquiry', 'ok no problem ok no problem.', 2, '192.168.0.115'),
(2, 'abc xyz', 'mailthis@mail.ok', '9856897848', 1, '2021-02-15 18:01:28', '2021-02-16 15:50:00', 'Contact Us Inquiry', 'ok no problem ok no problem.', 2, '192.168.0.115'),
(3, 'abc xyz', 'mailthis@mail.ok', '9856897848', 1, '2021-02-15 18:02:14', '2021-02-16 16:27:23', 'Contact Us Inquiry', 'ok no problem ok no problem.', 2, '192.168.0.115'),
(4, 'abc xyz', 'mailthis@mail.ok', '9856897848', 1, '2021-02-15 18:02:39', '2021-02-16 15:48:58', 'Contact Us Inquiry', 'ok no problem ok no problem. ok no problem ok no problem. ok no problem ok no problem. ok no problem ok no problem. ok no problem ok no problem. ok no problem ok no problem. ok no problem ok no problem. ok no problem ok no problem. ok no problem ok no problem. ok no problem ok no problem. ok no problem ok no problem. ok no problem ok no problem.', 2, '192.168.0.115'),
(5, 'fullname', 'required@mail.in', '9798785689', 0, '2021-04-22 15:50:38', '2021-04-22 15:50:38', 'Contact Us Inquiry', 'msg msg msg msg msg msg msg', 1, '192.168.43.35'),
(6, 'fullname', 'required@mail.in', '9798785689', 0, '2021-04-22 15:52:48', '2021-04-22 15:52:48', 'Contact Us Inquiry', 'msg msg msg msg msg msg msg', 1, '192.168.43.35'),
(7, 'fullname', 'required@mail.in', '9798785689', 0, '2021-04-22 19:15:29', '2021-04-22 19:15:29', 'Contact Us Inquiry', 'hello i want to make an order, i dont know ytyty', 1, '192.168.43.35'),
(8, '12', 'required@mail.in', '9798785689', 1, '2021-04-22 19:16:30', '2021-05-04 12:58:12', 'Contact Us Inquiry', 'hello i want to make an order, i dont know ytyty', 1, '192.168.43.35'),
(9, '12', 'required@mail.in', '9798785689', 0, '2021-04-23 10:30:13', '2021-04-23 10:30:13', 'Contact Us Inquiry', 'hello i want to make an order, i dont know ytyty', 1, '192.168.43.35');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_invoice`
--

CREATE TABLE `tbl_invoice` (
  `id` bigint(20) NOT NULL,
  `invoice_file` varchar(1000) NOT NULL COMMENT 'invoice file name',
  `invoice_number` bigint(20) NOT NULL COMMENT 'invoice number by admin',
  `invoice_date` date DEFAULT NULL COMMENT 'invoice_date',
  `is_active` tinyint(1) NOT NULL DEFAULT 0 COMMENT '	0-active,1-deactive, 2- deleted',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_invoice`
--

INSERT INTO `tbl_invoice` (`id`, `invoice_file`, `invoice_number`, `invoice_date`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '35SUNIL_GUPTA_17March2021_0248PM_6051c9652a559682_invoice1.pdf', 35, '2021-03-17', 0, '2021-03-19 09:34:47', '2021-03-19 09:34:47'),
(2, '34SUNIL_MAHENDRABHAI_PATEL_17March2021_1129AM_60519ab90e46e804_invoice1.pdf', 34, '2021-03-17', 0, '2021-03-19 09:34:47', '2021-03-19 09:34:47'),
(3, '27LALJI_MULJI_TRANSPORT_CO_11March2021_1235PM_6049c148de16e955_invoice6.pdf', 27, '2021-03-11', 0, '2021-03-19 09:34:47', '2021-03-19 09:34:47'),
(4, '26KAMAL_CHAMPALAL_GHIYA_09March2021_0112PM_604726d850dd2899_invoice1.pdf', 26, '2021-03-08', 0, '2021-03-19 09:34:47', '2021-03-19 09:34:47'),
(5, '29LALJI_MULJI_TRANSPORT_CO_11March2021_1236PM_6049c184cc2d9671_invoice5.pdf', 29, '2021-03-11', 0, '2021-03-19 09:34:47', '2021-03-19 09:34:47'),
(6, '28LALJI_MULJI_TRANSPORT_CO_11March2021_1236PM_6049c16bb7d9f925_invoice5.pdf', 28, '2021-03-11', 0, '2021-03-19 09:34:47', '2021-03-19 09:34:47'),
(7, '30SANJAY_KUMAR_13March2021_0447PM_604c9f34c59bb809_invoice1.pdf', 30, '2021-03-13', 0, '2021-03-19 09:34:47', '2021-03-19 09:34:47'),
(8, '31Swetaben_Hienbhai_Sheth_15March2021_1143AM_604efb1f517ca705_invoice1.pdf', 31, '2021-03-15', 0, '2021-03-19 09:34:47', '2021-03-19 09:34:47'),
(9, '32SHAILESHBHAI_LAXMANBHAI_BADRESHIYA_15March2021_1203PM_604effaa58ab7242_invoice1.pdf', 32, '2021-03-15', 0, '2021-03-19 09:34:47', '2021-03-19 09:34:47'),
(10, '33sunil_gupta_15March2021_0550PM_604f5113ac502648_invoice1.pdf', 33, '2021-03-15', 0, '2021-03-19 09:34:47', '2021-03-19 09:34:47'),
(14, '25SUNIL_MAHENDRABHAI_PATEL_20March2021_1138AM_605591513693d512_invoice1.pdf', 25, '2021-03-05', 0, '2021-03-20 11:38:18', '2021-03-20 11:38:18'),
(15, '23LALJI_MULJI_TRANSPORT_CO_20March2021_1141AM_60559200a2500897_invoice5.pdf', 23, '2021-03-01', 0, '2021-03-20 11:41:12', '2021-03-20 11:41:12'),
(16, '24LALJI_MULJI_TRANSPORT_CO_20March2021_1142AM_60559245f2722255_invoice5.pdf', 24, '2021-03-01', 0, '2021-03-20 11:42:22', '2021-03-20 11:42:22'),
(17, '22LALJI_MULJI_TRANSPORT_CO_20March2021_1143AM_6055927c88cc3730_invoice5.pdf', 22, '2021-03-01', 0, '2021-03-20 11:43:17', '2021-03-20 11:43:17'),
(18, '21Swetaben_Hienbhai_Sheth_20March2021_1144AM_605592ace0f84222_invoice1.pdf', 21, '2021-03-01', 0, '2021-03-20 11:44:05', '2021-03-20 11:44:05'),
(19, '20SUNIL_MAHENDRABHAI_PATEL_20March2021_1144AM_605592e1549db652_invoice1.pdf', 20, '2021-03-01', 0, '2021-03-20 11:44:57', '2021-03-20 11:44:57'),
(20, '15SUNIL_MAHENDRABHAI_PATEL_20March2021_1145AM_605592fe4def0187_invoice1.pdf', 15, '2021-03-01', 0, '2021-03-20 11:45:26', '2021-03-20 11:45:26'),
(21, '2PRAVINBHAI_HIMMATBHAI_JOGIYA_20March2021_1146AM_6055932cd76ae257_invoice1.pdf', 2, '2021-02-25', 0, '2021-03-20 11:46:13', '2021-03-20 11:46:13'),
(22, '10SUNIL_MAHENDRABHAI_PATEL_20March2021_1146AM_60559351a9477514_invoice1.pdf', 10, '2021-03-01', 0, '2021-03-20 11:46:50', '2021-03-20 11:46:50'),
(23, '1SHAILESHBHAI_LAXMANBHAI_BADRESHIYA_20March2021_1148AM_6055939f7793e777_invoice1.pdf', 1, '2021-02-25', 0, '2021-03-20 11:48:08', '2021-03-20 11:48:08'),
(24, '37_SAJJANKUMAR_SANTLAL_AGARWAL52_March202122114539AM_944_invoice1.pdf', 37, NULL, 0, '2021-03-22 11:45:41', '2021-03-22 11:45:41'),
(26, '38_Swetaben_Hirenbhai_Sheth55_March202123024325PM_431_invoice1.pdf', 38, NULL, 0, '2021-03-23 14:43:25', '2021-03-23 14:43:25'),
(27, '39_FANCY_FABRIC_EXPORT56_March202123043220PM_198_invoice1.pdf', 39, NULL, 0, '2021-03-23 16:32:21', '2021-03-23 16:32:21'),
(28, '40_FANCY_FABRIC_EXPORT64_65_March202130050916PM_811_invoice2.pdf', 40, NULL, 0, '2021-03-30 17:09:16', '2021-03-30 17:09:16'),
(29, '41_VIJAY_GOVINDPRASAD_AGARWAL66_March202131120130PM_266_invoice1.pdf', 41, NULL, 0, '2021-03-31 12:01:30', '2021-03-31 12:01:30'),
(30, '42_Swetaben_Hirenbhai_Sheth1002_March202131035504PM_399_invoice1.pdf', 42, NULL, 0, '2021-03-31 15:55:04', '2021-03-31 15:55:04'),
(32, '43_SUNIL_GUPTA1003_March202131054846PM_960_invoice1.pdf', 43, NULL, 0, '2021-03-31 17:48:46', '2021-03-31 17:48:46'),
(34, '44_FANCY_FABRIC_EXPORT1004_April202102014155PM_696_invoice1.pdf', 44, NULL, 0, '2021-04-02 13:41:55', '2021-04-02 13:41:55'),
(35, '45_Swetaben_Hirenbhai_Sheth1005_April202102050843PM_148_invoice1.pdf', 45, NULL, 0, '2021-04-02 17:08:43', '2021-04-02 17:08:43'),
(38, '46_SUNIL_GUPTA1006_April202105010411PM_782_invoice1.pdf', 46, NULL, 0, '2021-04-05 13:04:12', '2021-04-05 13:04:12'),
(39, '47_LALJI_MULJI_TRANSPORT_CO37_38_39_40_41_44_April202105042551PM_887_invoice6.pdf', 47, NULL, 0, '2021-04-05 16:25:51', '2021-04-05 16:25:51'),
(40, '48_LALJI_MULJI_TRANSPORT_CO45_47_53_54_58_57_April202105042826PM_895_invoice6.pdf', 48, NULL, 0, '2021-04-05 16:28:27', '2021-04-05 16:28:27'),
(41, '49_LALJI_MULJI_TRANSPORT_CO59_60_62_61_63_1007_April202105043002PM_112_invoice6.pdf', 49, NULL, 0, '2021-04-05 16:30:02', '2021-04-05 16:30:02'),
(42, '50_GANESH_DALARAM_CHAUDHARI1020_1019_April202108112608AM_349_invoice2.pdf', 50, NULL, 0, '2021-04-08 11:26:09', '2021-04-08 11:26:09'),
(43, '51_LALJI_MULJI_TRANSPORT_CO1035_April202123065248PM_476_invoice1.pdf', 51, NULL, 0, '2021-04-23 18:52:50', '2021-04-23 18:52:50'),
(44, '52_Ankur_Abhay_Jain1043_April202129023516PM_933_invoice1.pdf', 52, NULL, 0, '2021-04-29 14:35:20', '2021-04-29 14:35:20');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_menu_to_assign`
--

CREATE TABLE `tbl_menu_to_assign` (
  `id` int(10) UNSIGNED NOT NULL,
  `menu` varchar(111) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'URL/Controller',
  `details` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` int(11) NOT NULL DEFAULT 1 COMMENT '0-active,1-deactive, 2- deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_level` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '0-main menu, 1- sub-menu',
  `path_to` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_orders`
--

CREATE TABLE `tbl_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bigdaddy_lr_number` bigint(20) DEFAULT NULL,
  `transporter_lr_number` varchar(55) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'transporter_lr_number',
  `user_id` int(11) NOT NULL DEFAULT 0,
  `pickup_location` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'pickup_address',
  `drop_location` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'drop_address',
  `pickup_latitude` varchar(55) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pickup_longitude` varchar(55) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `drop_latitude` varchar(55) COLLATE utf8mb4_unicode_ci NOT NULL,
  `drop_longitude` varchar(55) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_person_name` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'pickup section',
  `contact_person_phone_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'pickup section',
  `transporter_name` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'pickup section',
  `contact_person_name_drop` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'drop section',
  `contact_person_phone_number_drop` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'drop section',
  `transporter_name_drop` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'drop section',
  `goods_height` decimal(10,2) NOT NULL DEFAULT 0.00,
  `goods_width` decimal(10,2) NOT NULL DEFAULT 0.00,
  `goods_length` decimal(10,2) NOT NULL DEFAULT 0.00,
  `final_cost` decimal(17,2) NOT NULL DEFAULT 599.59 COMMENT 'total delivery charges',
  `total_weight` decimal(17,2) NOT NULL DEFAULT 0.00 COMMENT 'total final weight',
  `total_no_of_parcel` int(11) NOT NULL DEFAULT 1 COMMENT 'total_no_of_parcel',
  `customer_estimation_asset_value` decimal(17,2) NOT NULL DEFAULT 0.00 COMMENT 'customer said that 900 is all my asset value',
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `min_order_value_charge` decimal(10,2) NOT NULL DEFAULT 0.00,
  `redeliver_charge` decimal(10,2) NOT NULL DEFAULT 0.00,
  `driver_id` int(11) NOT NULL DEFAULT 0,
  `driver_assigned_datetime` datetime DEFAULT NULL,
  `pickedup_datetime` datetime DEFAULT NULL,
  `cancelled_datetime` datetime DEFAULT NULL,
  `if_cancelled_reason_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivered_datetime` datetime DEFAULT NULL,
  `payment_datetime` datetime DEFAULT NULL,
  `undelivered_datetime` datetime DEFAULT NULL,
  `if_undelivered_reason_id` int(11) NOT NULL DEFAULT 0 COMMENT '	0-not else reasonfor id',
  `if_undelivered_reason_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_created_by` int(11) NOT NULL DEFAULT 0 COMMENT 'admin id',
  `status` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PU' COMMENT 'order_status_type ',
  `lr_img` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pickup_img` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'from driver',
  `deliver_img` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'from driver',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0-active,1-deactive, 2- deleted',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `payment_type` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'C' COMMENT 'payment_type',
  `payment_status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-Pending, 1-Paid',
  `transport_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tempo_charge` decimal(12,2) NOT NULL DEFAULT 0.00 COMMENT 'tempo charge total',
  `service_charge` decimal(12,2) NOT NULL DEFAULT 0.00 COMMENT 'service charge total',
  `other_field_pickup` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `other_field_drop` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vehicle_id` int(11) NOT NULL DEFAULT 0,
  `vehicle_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'text',
  `if_cheque_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `if_transaction_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_comment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_discount` decimal(11,2) NOT NULL DEFAULT 0.00,
  `invoice_id` bigint(20) DEFAULT NULL COMMENT 'invoice_id',
  `order_driver_trip_type` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PRL' COMMENT 'PPT - paypertrip , PPP - payperparcel, PRL - payroll	',
  `order_driver_trip_amount` decimal(11,2) NOT NULL DEFAULT 0.00,
  `subscription_purchase_id` int(11) DEFAULT NULL,
  `subscription_benefit_amount` decimal(9,2) NOT NULL DEFAULT 0.00,
  `coupon_code_applied` varchar(55) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coupon_code_id` int(9) DEFAULT NULL,
  `coupon_benefit_amount` decimal(9,2) NOT NULL DEFAULT 0.00,
  `wallet_amount_used` decimal(9,2) NOT NULL DEFAULT 0.00,
  `prepaid_amount_used` decimal(9,2) NOT NULL DEFAULT 0.00,
  `cod_amount_used` decimal(9,2) NOT NULL DEFAULT 0.00,
  `total_payable_amount` decimal(17,2) NOT NULL DEFAULT 0.00,
  `payment_type_manual` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'CS'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_orders`
--

INSERT INTO `tbl_orders` (`id`, `bigdaddy_lr_number`, `transporter_lr_number`, `user_id`, `pickup_location`, `drop_location`, `pickup_latitude`, `pickup_longitude`, `drop_latitude`, `drop_longitude`, `contact_person_name`, `contact_person_phone_number`, `transporter_name`, `contact_person_name_drop`, `contact_person_phone_number_drop`, `transporter_name_drop`, `goods_height`, `goods_width`, `goods_length`, `final_cost`, `total_weight`, `total_no_of_parcel`, `customer_estimation_asset_value`, `discount`, `min_order_value_charge`, `redeliver_charge`, `driver_id`, `driver_assigned_datetime`, `pickedup_datetime`, `cancelled_datetime`, `if_cancelled_reason_text`, `delivered_datetime`, `payment_datetime`, `undelivered_datetime`, `if_undelivered_reason_id`, `if_undelivered_reason_text`, `order_created_by`, `status`, `lr_img`, `pickup_img`, `deliver_img`, `is_active`, `created_at`, `updated_at`, `payment_type`, `payment_status`, `transport_cost`, `tempo_charge`, `service_charge`, `other_field_pickup`, `other_field_drop`, `vehicle_id`, `vehicle_no`, `if_cheque_number`, `if_transaction_number`, `payment_comment`, `payment_discount`, `invoice_id`, `order_driver_trip_type`, `order_driver_trip_amount`, `subscription_purchase_id`, `subscription_benefit_amount`, `coupon_code_applied`, `coupon_code_id`, `coupon_benefit_amount`, `wallet_amount_used`, `prepaid_amount_used`, `cod_amount_used`, `total_payable_amount`, `payment_type_manual`) VALUES
(7, 2, '176569', 121, '2,3,4 Royal Township, Saroli', 'Boss Mens Clothing \r\nM - 1,2,3 Jolly Shopping Point, Ghod Dhod Road, Surat', '21.190802804759716', '72.8802564740181', '21.174603719041396', '72.80306115384164', 'Purushottambhai', '2612643040', 'Sri Balaji Transport Lines', 'Pravinbhai Jogiya', '9898265025', '', '0.00', '0.00', '0.00', '200.00', '20.00', 1, '0.00', '0.00', '0.00', '0.00', 3, '2021-02-16 12:49:00', NULL, NULL, NULL, '2021-02-17 14:28:36', NULL, NULL, 0, NULL, 9, 'D', 'lr6035f5dd9060f1614149085.jpeg', 'pickup6035f5dd8f8f31614149085.jpeg', 'deliver6035f5dd8e83d1614149085.jpeg', 0, '2021-02-16 12:48:19', '2021-03-20 11:46:13', 'C', 1, '450.00', '150.00', '50.00', NULL, NULL, 3, 'GJ 05 BY 0992', NULL, NULL, NULL, '0.00', 21, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '200.00', '200.00', 'CS'),
(6, 1, '683457/736616', 119, 'Godown no 18 Dumbhal transport nager surat', 'YogiDarshan Studio\r\n7/8, Harsh Complex, Katargam Bus Stop, Nr.dholakiya Garden Opp.Bank Of Baroda. Surat 395004', '21.194511317668958', '72.86520743742585', '21.22209628894929', '72.83333659172058', 'Transport Manager', '7998879900', 'Gujrat Transport Service', 'Saileshbhai', '9825153439', '', '0.00', '0.00', '0.00', '260.00', '110.00', 3, '0.00', '0.00', '0.00', '0.00', 3, '2021-02-15 13:21:58', NULL, NULL, NULL, '2021-02-15 16:12:14', NULL, NULL, 0, NULL, 9, 'D', '602b91ce545a11613468110.jpg', 'pickup6035f54dc67271614148941.jpeg', 'deliver6035f54dc552f1614148941.jpeg', 0, '2021-02-15 13:15:38', '2021-03-20 11:48:08', 'C', 1, '800.00', '170.00', '90.00', NULL, NULL, 3, 'GJ 05 BY 0992', NULL, NULL, NULL, '0.00', 23, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '260.00', '260.00', 'CS'),
(8, 3, '21459779', 122, 'Lalji Mulji Transport Co , Plot no. 6102 , Road no. 61 , opp. SBI Bank , G.I.D.C Sachin, Surat.', 'PLOT NO-273/1,G I D C Pandesara, OPP BHAGYA LAXMI MILL,PANDESARA,SURAT,Surat,Gujarat, 394221', '21.098006829813485', '72.86286637187004', '21.146731920835446', '72.83443173035576', 'Arjun', '99980 127', 'Lalji Mulji Transport Co.', 'K-fins Pumps Pvt Ltd', '9374921406', '', '0.00', '0.00', '0.00', '80.00', '25.00', 1, '0.00', '0.00', '0.00', '0.00', 3, '2021-02-19 15:43:52', NULL, NULL, NULL, '2021-02-19 15:44:05', '2021-03-04 00:00:00', NULL, 0, NULL, 9, 'D', 'lr6035f78210e681614149506.jpeg', 'pickup6035f782107611614149506.jpeg', 'deliver6035f7820fb941614149506.jpeg', 0, '2021-02-19 14:10:17', '2021-03-20 11:43:17', 'C', 1, '0.00', '50.00', '30.00', NULL, NULL, 3, 'GJ 05 BY 0992', NULL, NULL, NULL, '0.00', 17, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '80.00', '80.00', 'CS'),
(9, 4, '44082878', 122, 'Lalji Mulji Transport Co , Plot no. 6102 , Road no. 61 , opp. SBI Bank , G.I.D.C Sachin, Surat.', 'Vividh Laboratories \r\nB-7,SAIFEE COMPLEX,OPP B R C GATE MAIN ROAD UDHNA,UDHNA,Surat,Gujarat, 394220', '21.09800870662094', '72.86286503076553', '21.157596978308156', '72.8431672316239', 'Arjun', '9374921406', 'Lalji Mulji Transport Co.', 'VIPUL', '9825145901', '', '0.00', '0.00', '0.00', '80.00', '50.00', 1, '0.00', '0.00', '0.00', '0.00', 3, '2021-02-19 16:29:26', NULL, NULL, NULL, '2021-02-19 16:29:36', '2021-03-04 00:00:00', NULL, 0, NULL, 9, 'D', 'lr6035f815081601614149653.jpeg', 'pickup6035f815074a81614149653.jpeg', 'deliver6035f815062341614149653.jpeg', 0, '2021-02-19 16:29:10', '2021-03-20 11:43:17', 'C', 1, '0.00', '50.00', '30.00', NULL, NULL, 3, 'GJ 05 BY 0992', NULL, NULL, NULL, '0.00', 17, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '80.00', '80.00', 'CS'),
(13, 5, '27916501', 122, 'Lalji Mulji Transport Co , Plot no. 6102 , Road no. 61 , opp. SBI Bank , G.I.D.C Sachin, Surat.', 'SHRI MUNIVEER SPINNING MILLS \r\nA1/11,,Sachin Udhyog Nagar,,Hojiwala Estate,Palsana,Vanz,Surat,Gujarat, 394315', '21.09800745541597', '72.86286637187004', '21.086659843988954', '72.90119647979736', 'Arjun', '9998012774', 'Lalji Mulji Transport Co.', 'SHRI MUNIVEER SPINNING MILLS', '9324670412', '', '0.00', '0.00', '0.00', '80.00', '100.00', 2, '0.00', '0.00', '0.00', '0.00', 3, '2021-02-22 15:49:09', NULL, NULL, NULL, '2021-02-22 16:35:03', '2021-03-04 00:00:00', NULL, 0, NULL, 9, 'D', 'lr603383c7b9a831613988807.jpeg', 'pickup60337c909aa091613986960.jpeg', 'deliver60337c9099a941613986960.jpeg', 0, '2021-02-22 14:04:17', '2021-03-20 11:43:17', 'C', 1, '0.00', '60.00', '20.00', NULL, NULL, 3, 'GJ 05 BY 0992', NULL, NULL, NULL, '0.00', 17, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '80.00', '80.00', 'CS'),
(14, 6, '37053808', 122, 'Lalji Mulji Transport Co , Plot no. 6102 , Road no. 61 , opp. SBI Bank , G.I.D.C Sachin, Surat.', 'GOKULANAND PETROFIBRES\r\nPlot No. AA10 , Plot NO. AA36 Of Susml,And Part C OF Block No. 54, Vil. Vanz, Tal. Choryasi,Sachin-Palsana Road,Vanz,Surat,Gujarat, 394230', '21.09800745541597', '72.86286637187004', '21.08926502892692', '72.9132342338562', 'Arjun', '9998012774', 'Lalji Mulji Transport Co.', 'GOKULANAND PETROFIBRES', '9879208090', '', '0.00', '0.00', '0.00', '150.00', '210.00', 1, '40144.00', '0.00', '0.00', '0.00', 3, '2021-02-22 15:49:09', NULL, NULL, NULL, '2021-02-22 16:35:02', '2021-03-04 00:00:00', NULL, 0, NULL, 9, 'D', 'lr603383ee8face1613988846.jpeg', 'pickup60337e87dd2751613987463.jpeg', 'deliver60337e87dcaae1613987463.jpeg', 0, '2021-02-22 14:10:30', '2021-03-20 11:43:17', 'C', 1, '0.00', '100.00', '50.00', NULL, NULL, 3, 'GJ 05 BY 0992', NULL, NULL, NULL, '0.00', 17, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '150.00', '150.00', 'CS'),
(15, 7, '10070308', 122, 'Lalji Mulji Transport Co , Plot no. 6102 , Road no. 61 , opp. SBI Bank , G.I.D.C Sachin, Surat.', 'NATIONAL FIGHTER SPORTS\r\nM-3,Anmol Shoping Point,Opp.Radhe Nagar Society, Dumas Road,Parle Point,Surat,Gujarat, 395007', '21.09800745541597', '72.86286637187004', '21.17060461722949', '72.78851687908173', 'Arjun', '9998012774', 'Lalji Mulji Transport Co.', 'NATIONAL FIGHTER SPORTS', '9825770502', '', '0.00', '0.00', '0.00', '120.00', '320.00', 2, '0.00', '0.00', '0.00', '0.00', 3, '2021-02-22 15:49:09', NULL, NULL, NULL, '2021-02-22 16:35:03', '2021-03-04 00:00:00', NULL, 0, NULL, 9, 'D', 'lr603384da9a34c1613989082.jpeg', 'pickup603384da995b71613989082.jpeg', 'deliver603384da9825e1613989082.jpeg', 0, '2021-02-22 14:19:15', '2021-03-20 11:43:17', 'C', 1, '1298.00', '80.00', '40.00', NULL, NULL, 3, 'GJ 05 BY 0992', NULL, NULL, NULL, '0.00', 17, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '120.00', '120.00', 'CS'),
(16, 8, '35176143', 122, 'lalji Mulji Transports Co\r\nPlot No. A/60/20, Road no.6, Near Ankur Weigh Bridege, Udhna udhyoganagar, Udhna, Surat, Surat-394210', 'SHAH ENGINEERS \r\nRushabh- Near Sita Hospital, Old Sub Jail, Gail, Ring Road, Surat', '21.170463300231756', '72.84854739904404', '21.180517730228612', '72.82680809497833', 'Dipak Bhai', '261-227936', 'Lalji Mulji Transport Co.', 'SHAH ENGINEERS', '9825607213', '', '0.00', '0.00', '0.00', '300.00', '200.00', 10, '27140.00', '0.00', '0.00', '0.00', 3, '2021-02-22 17:57:15', NULL, NULL, NULL, '2021-02-22 17:57:51', '2021-03-04 00:00:00', NULL, 0, NULL, 9, 'D', 'lr60339d8e2b9db1613995406.jpeg', 'pickup60339d8e2b2521613995406.jpeg', 'deliver60339d8e2a6741613995406.jpeg', 0, '2021-02-22 17:23:20', '2021-03-20 11:41:12', 'C', 1, '0.00', '200.00', '100.00', NULL, NULL, 3, 'GJ 05 BY 0992', NULL, NULL, NULL, '0.00', 15, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '300.00', '300.00', 'CS'),
(17, 9, '20702187', 122, 'Plot No. A/60/20, Road no.6, Near Ankur Weigh Bridege, Udhna udhyoganagar, Udhna, Surat, Surat-394210', 'SHREE ENTERPRISE\r\nSMC WD 9 SY 655 Paiki Shop No 1,Ground Floor, Shidhdhi Matani Sheri, Wadi Faliyu, Surat', '21.170465801418736', '72.84853935241699', '21.19562060020591', '72.82889485359192', 'Dipak Bhai', '2612279364', 'Lalji Mulji Transport Co.', 'SHREE ENTERPRISE', '9825803234', '', '0.00', '0.00', '0.00', '200.00', '140.00', 5, '16520.00', '0.00', '0.00', '0.00', 3, '2021-02-22 17:56:47', NULL, NULL, NULL, '2021-02-22 17:58:17', '2021-03-04 00:00:00', NULL, 0, NULL, 9, 'D', 'lr6033a2ca9defa1613996746.jpeg', 'pickup6033a2ca9d1d61613996746.jpeg', 'deliver6033a2ca9c0621613996746.jpeg', 0, '2021-02-22 17:51:59', '2021-03-20 11:41:12', 'C', 1, '0.00', '125.00', '75.00', NULL, NULL, 3, 'GJ 05 BY 0992', NULL, NULL, NULL, '0.00', 15, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '200.00', '200.00', 'CS'),
(18, 10, '572901479', 124, 'Jaipur goldan Transport Godaun Num 32, Dumbhal, Near Amezia Water Paek, Parvat Patiya, Surat', 'Sai Enterprise\r\nSHOP NO.4,,DIVYA COMPLEX-A, OPP SENT MARK SCHOOL,GANGESHWAR TEMPLE, ADAJAN,,SURAT,Surat,Gujarat, 395009', '21.193725010878477', '72.86618828773499', '21.19593507062176', '72.78982244431973', 'DevBhai', '8758885888', 'Jaipur Golden Transport Organisation', 'Sunil Bhai', '9979267853', '', '0.00', '0.00', '0.00', '600.00', '240.00', 4, '19824.00', '0.00', '0.00', '0.00', 5, '2021-02-23 12:04:59', NULL, NULL, NULL, '2021-02-23 16:19:34', '2021-02-23 00:00:00', NULL, 0, NULL, 9, 'D', 'lr6034dda4da93f1614077348.jpeg', 'pickup6034dda4d9fcb1614077348.jpeg', 'deliver6034dda4d90391614077348.jpeg', 0, '2021-02-23 12:00:31', '2021-03-20 11:46:50', 'C', 1, '1660.00', '480.00', '120.00', NULL, NULL, 5, 'GJ 05 AU 3104', NULL, NULL, NULL, '0.00', 22, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '600.00', '600.00', 'CS'),
(19, 11, '32502486', 122, 'Plot No. A/60/20, Road no.6, Near Ankur Weigh Bridege, Udhna udhyoganagar, Udhna, Surat, Surat-394210', 'MR Husefa\r\nPrime corner, Begampura', '21.1704658014187', '72.8485447168350', '21.19513920284538', '72.83529862761497', 'Dipak Bhai', '2612279364', 'Lalji Mulji Transport Co.', 'Mr Husefa', '8511252452', '', '0.00', '0.00', '0.00', '360.00', '81.00', 9, '19790.00', '0.00', '0.00', '0.00', 4, '2021-02-23 13:01:36', NULL, NULL, NULL, '2021-02-23 16:39:58', '2021-03-04 00:00:00', NULL, 0, NULL, 9, 'D', 'lr6034c8db187791614072027.jpeg', 'pickup6034af1c27be21614065436.jpeg', 'deliver6034c851552071614071889.jpeg', 0, '2021-02-23 12:44:40', '2021-03-20 11:41:12', 'C', 1, '0.00', '270.00', '90.00', NULL, NULL, 4, 'GJ07UU1469', NULL, NULL, NULL, '0.00', 15, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '360.00', '360.00', 'CS'),
(20, 12, '23029080', 122, 'Plot No. A/60/20, Road no.6, Near Ankur Weigh Bridege, Udhna udhyoganagar, Udhna, Surat, Surat-394210', 'Avenue Sipermarts Ltd..\r\nRegent Square, Mahadev Road, Adajan, Surat', '21.1704658014187', '72.8485447168350', '21.195503689562557', '72.79269240796566', 'Dipak Bhai', '2612279364', 'Lalji Mulji Transport Co.', 'Avenue Sipermarts Ltd', '9974769720', '', '0.00', '0.00', '0.00', '80.00', '50.00', 1, '5938.00', '0.00', '0.00', '0.00', 4, '2021-02-23 13:21:08', NULL, NULL, NULL, '2021-02-23 16:39:54', '2021-03-04 00:00:00', NULL, 0, NULL, 9, 'D', 'lr6034ca1f4a0b91614072351.jpeg', 'pickup6034ca1f4985e1614072351.jpeg', 'deliver6034ca1f48c851614072351.jpeg', 0, '2021-02-23 13:19:26', '2021-03-20 11:41:12', 'C', 1, '0.00', '50.00', '30.00', NULL, NULL, 4, 'GJ07UU1469', NULL, NULL, NULL, '0.00', 15, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '80.00', '80.00', 'CS'),
(21, 13, '23029076', 122, 'Plot No. A/60/20, Road no.6, Near Ankur Weigh Bridege, Udhna udhyoganagar, Udhna, Surat, Surat-394210', 'Avenue Supermarts Ltd.                                                                                                             \r\nIsana, DMart Brts Road, Bamroli Choriyasi, Surat', '21.1704658014187', '72.8485447168350', '21.148683862622832', '72.81507074832916', 'Dipak Bhai', '2612279364', 'Lalji Mulji Transport Co.', 'Avenue Supermarts Ltd.', '9638447757', '', '0.00', '0.00', '0.00', '100.00', '100.00', 2, '9297.00', '0.00', '0.00', '0.00', 4, '2021-02-23 13:33:28', NULL, NULL, NULL, '2021-02-23 16:39:50', '2021-03-04 00:00:00', NULL, 0, NULL, 9, 'D', 'lr6034d1ce652411614074318.jpeg', 'pickup6034d1ce646141614074318.jpeg', 'deliver6034df2117c6a1614077729.jpeg', 0, '2021-02-23 13:33:08', '2021-03-20 11:41:12', 'C', 1, '0.00', '80.00', '20.00', NULL, NULL, 4, 'GJ07UU1469', NULL, NULL, NULL, '0.00', 15, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '100.00', '100.00', 'CS'),
(22, 14, '21082944', 122, 'Plot No. A/60/20, Road no.6, Near Ankur Weigh Bridege, Udhna udhyoganagar, Udhna, Surat, Surat-394210', 'Bright Electricals\r\nD Mart Apple Farm Punjan Residency Punjan Chouk, Yogi Chouk, Surat', '21.1704658014187', '72.8485447168350', '21.214460135315036', '72.88750648498535', 'Dipak Bhai', '2612279364', 'Lalji Mulji Transport Co.', 'Bright Electricals', '9372886200', '', '0.00', '0.00', '0.00', '630.00', '504.00', 14, '44485.00', '0.00', '0.00', '0.00', 4, '2021-02-23 14:01:45', NULL, NULL, NULL, '2021-02-23 16:39:42', '2021-03-04 00:00:00', NULL, 0, NULL, 9, 'D', 'lr6034cb5c24ab31614072668.jpeg', 'pickup6034cb5c23b3c1614072668.jpeg', 'deliver6034cb5c22cbc1614072668.jpeg', 0, '2021-02-23 14:00:03', '2021-03-20 11:42:22', 'C', 1, '0.00', '490.00', '140.00', NULL, NULL, 4, 'GJ07UU1469', NULL, NULL, NULL, '0.00', 16, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '630.00', '630.00', 'CS'),
(23, 15, '20401', 124, 'X-Press RoadLines\r\nGodown No, 52-53, Shivam Estate,Shaniya-Hemad Road, Opp. Puna Kumbharya Bus stop, Saroli, Surat', 'Sainath Motors\r\nSHOP NO.4,,DIVYA COMPLEX-A, OPP SENT MARK SCHOOL,GANGESHWAR TEMPLE, ADAJAN,,SURAT,Surat,Gujarat, 395009', '21.19110749010127', '72.8909471566789', '21.19593507062176', '72.78982244431973', 'X-Press Roadlines', '2612540354', 'X-Press Roadlines', 'Sunil Bhai', '9979267853', '', '0.00', '0.00', '0.00', '750.00', '200.00', 5, '24000.00', '0.00', '0.00', '0.00', 5, '2021-02-23 15:29:36', NULL, NULL, NULL, '2021-02-23 16:39:20', '2021-02-23 00:00:00', NULL, 0, NULL, 9, 'D', 'lr6034e1b48a5191614078388.jpeg', 'pickup6034e1b489cd11614078388.jpeg', 'deliver6034e1b488f051614078388.jpeg', 0, '2021-02-23 14:44:33', '2021-03-20 11:45:26', 'C', 1, '1600.00', '600.00', '150.00', NULL, NULL, 5, 'GJ 05 AU 3104', NULL, NULL, NULL, '0.00', 20, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '750.00', '750.00', 'CS'),
(24, 16, '15216195', 122, 'Plot No. A/60/20, Road no.6, Near Ankur Weigh Bridge, Udhna udhyoganagar, Udhna, Surat, Surat-394210', 'Kalamandir Jewellers Ltd\r\nPlot No-124, Ghod Dod Rd, Near Indoor Stadium, Athwa, Surat', '21.170465801418736', '72.84854471683502', '21.17441637345993', '72.80195608735085', 'Arjun', '2612279364', 'Lalji Mulji Transport Co.', 'Kalamandir Jewellers', '8460028086', '', '0.00', '0.00', '0.00', '70.00', '50.00', 1, '38270.00', '0.00', '0.00', '0.00', 5, '2021-02-24 12:01:03', NULL, NULL, NULL, '2021-02-24 12:01:19', '2021-03-04 00:00:00', NULL, 0, NULL, 9, 'D', '6035e9a7cf3c71614145959.jpeg', 'pickup6035f365369af1614148453.jpeg', 'deliver6035f3653524e1614148453.jpeg', 0, '2021-02-24 11:22:39', '2021-03-20 11:42:22', 'C', 1, '0.00', '50.00', '20.00', NULL, NULL, 5, 'GJ 05 AU 3104', NULL, NULL, NULL, '0.00', 16, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '70.00', '70.00', 'CS'),
(25, 17, '27636449', 122, 'Plot No. A/60/20, Road no.6, Near Ankur Weigh Bridge, Udhna udhyoganagar, Udhna, Surat, Surat-394210', 'PERFECT INSULATION AND ENGINEERING\r\nPlot No 512 513,Ground Floor,Ahir Was,Opp Khodiyar Nagar,Adajan Gam,Surat,', '21.170465801418736', '72.84854471683502', '21.193434201893428', '72.79256237402349', 'Arjun', '2612279364', 'Lalji Mulji Transport Co.', 'PERFECT INSULATION AND ENGINEERING', '9314019921', '', '0.00', '0.00', '0.00', '120.00', '50.00', 1, '38232.00', '0.00', '0.00', '0.00', 7, '2021-03-01 13:20:16', NULL, NULL, NULL, '2021-03-01 15:17:56', '2021-03-04 00:00:00', NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-01 11:45:25', '2021-03-20 11:42:22', 'C', 1, '800.00', '90.00', '30.00', NULL, NULL, 7, 'GJ 05 BW 0646', NULL, NULL, NULL, '0.00', 16, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '120.00', '120.00', 'CS'),
(26, 20, '572-90179', 124, 'Jaipur GoldenTransport Organization\r\nGodown No. 32, Dumbhal, Near Amaazia Water Park, Parvat Patiya, Surat', 'Sainath Motors\r\nSHOP NO.4, DIVYA COMPLEX-A, OPP SENT MARK SCHOOL,GANGESHWAR TEMPLE, ADAJAN,SURAT,Surat,Gujarat, 395009', '21.193725010878477', '72.86618828773499', '21.19593507062176', '72.78982244431973', 'DevBhai', '8866554888', 'Jaipur Golden Transport Organization', 'Sunil Bhai', '9979267853', '', '0.00', '0.00', '0.00', '320.00', '240.00', 4, '19824.00', '0.00', '0.00', '0.00', 6, '2021-03-01 12:08:48', NULL, NULL, NULL, '2021-03-01 13:20:32', '2021-03-01 00:00:00', NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-01 12:08:20', '2021-03-20 11:44:57', 'C', 1, '1460.00', '200.00', '120.00', NULL, NULL, 6, 'GJ 05 CT 4131', NULL, NULL, NULL, '0.00', 19, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '320.00', '320.00', 'CS'),
(27, 18, '31326744', 122, 'Plot No. A/60/20, Road no.6, Near Ankur Weigh Bridge, Udhna udhyoganagar, Udhna, Surat, Surat-394210', 'EVEREST ELECTRICALS\r\n279/A/1/14,GR/FLOOR,DOCTOR HOUSE,UNAPANI ROAD,SURAT,Surat,Gujarat, 395003', '21.170465801418736', '72.84854471683502', '21.206218447010226', '72.83757984638214', 'Arjun', '2612279364', 'Lalji Mulji Transport Co.', 'EVEREST ELECTRICALS', '9824852915', '', '0.00', '0.00', '0.00', '80.00', '50.00', 1, '36809.00', '0.00', '0.00', '0.00', 7, '2021-03-01 13:20:16', NULL, NULL, NULL, '2021-03-01 15:47:15', '2021-03-04 00:00:00', NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-01 12:41:08', '2021-03-20 11:42:22', 'C', 1, '0.00', '50.00', '30.00', NULL, NULL, 7, 'GJ 05 BW 0646', NULL, NULL, NULL, '0.00', 16, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '80.00', '80.00', 'CS'),
(28, 19, '35327867', 122, 'Plot No. A/60/20, Road no.6, Near Ankur Weigh Bridge, Udhna udhyoganagar, Udhna, Surat, Surat-394210', 'METRO CASH AND CARRY INDIA PVT LTD\r\nBlock no 188 survey no 143,VIP Road,Shagun Villa,VIP Road,ALTHANA,Surat,Gujarat, 395017', '21.170465801418736', '72.84854471683502', '21.14479139185848', '72.80264139175415', 'Arjun', '2612279364', 'Lalji Mulji Transport Co.', 'METRO CASH AND CARRY INDIA PVT LTD', '9874750267', '', '0.00', '0.00', '0.00', '400.00', '120.00', 4, '48121.00', '0.00', '0.00', '0.00', 7, '2021-03-01 13:20:15', NULL, NULL, NULL, '2021-03-01 13:20:50', '2021-03-05 00:00:00', NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-01 12:52:35', '2021-03-20 11:42:22', 'C', 1, '0.00', '280.00', '120.00', NULL, NULL, 7, 'GJ 05 BW 0646', NULL, NULL, NULL, '0.00', 16, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '400.00', '400.00', 'CS'),
(29, 21, '9930977', 125, 'Suraj Carry Corporation\r\nAjanta Timber Plot no. 2\r\nSolapur Compound Road, Near JB Kharwal Mill UDHNA', 'C-402, NEAR UMRIGAR SCHOOL, OPERA TOWN, UMRA, SURAT, Surat, Gujarat, 395007, Surat-395007', '21.126754873497067', '72.85471887069312', '21.17649854133468', '72.78605461120605', 'Rajubhai', '9974088509', 'Suraj Carrying Corporation', 'Hirenbhai', '9824159029', '', '0.00', '0.00', '0.00', '540.00', '150.00', 9, '39082.00', '0.00', '0.00', '0.00', 6, '2021-03-01 15:12:55', NULL, NULL, NULL, '2021-03-02 10:54:12', '2021-03-01 00:00:00', NULL, 0, NULL, 5, 'D', NULL, NULL, NULL, 0, '2021-03-01 15:12:34', '2021-03-20 11:44:05', 'C', 1, '590.00', '360.00', '180.00', NULL, NULL, 6, 'GJ 05 CT 4131', NULL, NULL, NULL, '0.00', 18, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '540.00', '540.00', 'CS'),
(30, 101, '24471192', 122, 'Plot No. A/60/20, Road no.6, Near Ankur Weigh Bridge, Udhna udhyoganagar, Udhna, Surat, Surat-394210', 'S GOYAL SILKFAB PRIVATE LIMITED\r\nC-4247-4251 To 4268-4272, Second Floor, Raghukul Textile Market, Ring Road, Surat', '21.170465801418736', '72.84854471683502', '21.185797282795214', '72.84583568572998', 'Arjun', '2612279364', 'Lalji Mulji Transport Co.', 'S GOYAL SILKFAB PRIVATE LIMITED', '9327338920', '', '0.00', '0.00', '0.00', '200.00', '80.00', 2, '124501.00', '0.00', '0.00', '0.00', 6, '2021-03-02 13:17:41', NULL, NULL, NULL, '2021-03-02 15:59:26', '2021-03-17 00:00:00', NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-02 11:39:16', '2021-03-19 16:08:10', 'C', 1, '873.00', '140.00', '60.00', NULL, NULL, 6, 'GJ 05 CT 4131', '010053 240', NULL, NULL, '0.00', 5, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '200.00', '200.00', 'CHQ'),
(31, 102, '20703215', 122, 'Plot No. A/60/20, Road no.6, Near Ankur Weigh Bridge, Udhna udhyoganagar, Udhna, Surat, Surat-394210', 'WEL COME PHARMACY\r\nShop No 3 4, Second Floor,S M C Complex, NR Ayurvedic Hospital Lal Darwaja Road, Lal Darwaja, Surat', '21.170465801418736', '72.84854471683502', '21.206801498574784', '72.8394872929283', 'Arjun', '2612279364', 'Lalji Mulji Transport Co.', 'WEL COME PHARMACY', '9428747699', '', '0.00', '0.00', '0.00', '160.00', '50.00', 2, '8543.00', '0.00', '0.00', '0.00', 6, '2021-03-02 13:17:50', NULL, NULL, NULL, '2021-03-02 15:59:34', '2021-03-17 00:00:00', NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-02 12:02:58', '2021-03-19 16:08:10', 'C', 1, '0.00', '100.00', '60.00', NULL, NULL, 6, 'GJ 05 CT 4131', '010053 240', NULL, NULL, '0.00', 5, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '160.00', '160.00', 'CHQ'),
(32, 103, '20324797', 122, 'Plot No. A/60/20, Road no.6, Near Ankur Weigh Bridge, Udhna udhyoganagar, Udhna, Surat, Surat-394210', 'AVENUE SUPERMARTS LIMITED\r\nRier Park Soc, Katargam, Ved Road, Singanapoor, Surat', '21.170465801418736', '72.84854471683502', '21.22287867639384', '72.80736400637691', 'Arjun', '2612279364', 'Lalji Mulji Transport Co.', 'AVENUE SUPERMARTS LIMITED', '9638447757', '', '0.00', '0.00', '0.00', '400.40', '210.00', 7, '20788.00', '0.00', '0.00', '0.00', 6, '2021-03-02 13:17:56', NULL, NULL, NULL, '2021-03-02 15:59:39', '2021-03-17 00:00:00', NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-02 12:28:38', '2021-03-19 16:08:10', 'C', 1, '0.00', '281.40', '119.00', NULL, NULL, 6, 'GJ 05 CT 4131', '010053 240', NULL, NULL, '0.00', 5, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '400.40', '400.40', 'CHQ'),
(33, 22, '17210307', 122, 'Lalji Mulji Transport Co, Plot no. 6102, Road no. 61, opp. SBI Bank, G.I.D.C Sachin, Surat., Surat-394021', 'Jain Glass Soluation\r\nAA-15C, Road No.10, Susm Hojiwala Ind. Estate, Sachin-Palsana Road, Sachin, Surat', '21.098006829813485', '72.86286637187004', '21.080936284980382', '72.90591716766357', 'Arjun', '9998012774', 'Lalji Mulji Transport Co.', 'Jain Glass Soluation', '9375725563', '', '0.00', '0.00', '0.00', '200.00', '120.00', 2, '25960.00', '0.00', '0.00', '0.00', 7, '2021-03-02 13:17:03', NULL, NULL, NULL, '2021-03-02 15:59:47', '2021-03-17 00:00:00', NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-02 12:54:44', '2021-03-19 16:08:09', 'C', 1, '0.00', '140.00', '60.00', NULL, NULL, 7, 'GJ 05 BW 0646', '010053 240', NULL, NULL, '0.00', 3, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '200.00', '200.00', 'CHQ'),
(34, 23, '20703303', 122, 'Lalji Mulji Transport Co, Plot no. 6102, Road no. 61, opp. SBI Bank, G.I.D.C Sachin, Surat., Surat-394021', 'COLOURTEX INDUSTRIES PVT. LTD\r\nSurvey No 91, Road No.10, Colourtex Industries Pvt. Ltd., Udhana Navsari Road, Bhestan, Opp Navin Fluorine Industries, Surat', '21.098006829813485', '72.86286637187004', '21.13877737348487', '72.84313201904297', 'Arjun', '9998012774', 'Lalji Mulji Transport Co.', 'COLOURTEX INDUSTRIES PVT. LTD', '7573951459', '', '0.00', '0.00', '0.00', '320.00', '215.00', 4, '60180.00', '0.00', '0.00', '0.00', 7, '2021-03-02 13:16:47', NULL, NULL, NULL, '2021-03-02 16:11:27', '2021-03-17 00:00:00', NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-02 13:09:14', '2021-03-19 16:08:09', 'C', 1, '0.00', '200.00', '120.00', NULL, NULL, 7, 'GJ 05 BW 0646', '010053 240', NULL, NULL, '0.00', 3, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '320.00', '320.00', 'CHQ'),
(35, 24, '35733128', 122, 'Plot No. A/60/20, Road no.6, Near Ankur Weigh Bridge, Udhna udhyoganagar, Udhna, Surat, Surat-394210', 'DHIRAJ SONS SUPER MARKET\r\nParle Point Shop Samarth Sarathi Apt, ParlePoint, Surat', '21.170465801418736', '72.84854471683502', '21.17510292934962', '72.7944928407669', 'Dipak Bhai', '9724749016', 'Lalji Mulji Transport Co.', 'DHIRAJ SONS SUPER MARKET', '9687641013', '', '0.00', '0.00', '0.00', '120.00', '80.00', 1, '26233.00', '0.00', '0.00', '0.00', 6, '2021-03-04 15:51:55', NULL, NULL, NULL, '2021-03-04 17:14:04', '2021-03-17 00:00:00', NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-04 15:17:39', '2021-03-19 16:08:09', 'C', 1, '0.00', '90.00', '30.00', NULL, NULL, 6, 'GJ 05 CT 4131', '010053 240', NULL, NULL, '0.00', 3, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '120.00', '120.00', 'CHQ'),
(36, 25, '35733127', 122, 'Plot No. A/60/20, Road no.6, Near Ankur Weigh Bridge, Udhna udhyoganagar, Udhna, Surat, Surat-394210', 'DHIRAJ SONS SUPER MARKET\r\nRich Mond Plaza, Opp Surya Residency, Vesu, Surat.', '21.170465801418736', '72.84854471683502', '21.14942822580952', '72.78281076265944', 'Dipak Bhai', '9724749016', 'Lalji Mulji Transport Co.', 'DHIRAJ SONS SUPER MARKET', '9687641013', '', '0.00', '0.00', '0.00', '120.00', '80.00', 1, '29301.00', '0.00', '0.00', '0.00', 6, '2021-03-04 15:51:47', NULL, NULL, NULL, '2021-03-04 17:14:15', '2021-03-17 00:00:00', NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-04 15:24:28', '2021-03-19 16:08:09', 'C', 1, '0.00', '90.00', '30.00', NULL, NULL, 6, 'GJ 05 CT 4131', '010053 240', NULL, NULL, '0.00', 3, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '120.00', '120.00', 'CHQ'),
(37, 26, '35733129', 122, 'Plot No. A/60/20, Road no.6, Near Ankur Weigh Bridge, Udhna udhyoganagar, Udhna, Surat, Surat-394210', 'DHIRAJ SONS SUPER MARKET\r\nShop Katargam Shreeji Builiding, Opp Mehta Petrol Pumps Kansanga, surat', '21.170465801418736', '72.84854471683502', '21.221308688235453', '72.83595710992813', 'Dipak Bhai', '9724749016', 'Lalji Mulji Transport Co.', 'DHIRAJ SONS SUPER MARKET', '9978904977', '', '0.00', '0.00', '0.00', '200.00', '150.00', 2, '35658.00', '0.00', '0.00', '0.00', 7, '2021-03-04 15:51:39', NULL, NULL, NULL, '2021-03-04 17:13:58', '2021-03-17 00:00:00', NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-04 15:36:37', '2021-03-19 16:08:09', 'C', 1, '0.00', '140.00', '60.00', NULL, NULL, 7, 'GJ 05 BW 0646', '010053 240', NULL, NULL, '0.00', 3, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '200.00', '200.00', 'CHQ'),
(38, 27, '23029184', 122, 'Plot No. A/60/20, Road no.6, Near Ankur Weigh Bridge, Udhna udhyoganagar, Udhna, Surat, Surat-394210', 'AVENUE SUPERMARTS LIMITED\r\nSarathana, D Mart, kamrej Surat.', '21.170465801418736', '72.84854471683502', '21.231662176544916', '72.90402889251709', 'Dipak Bhai', '9724749016', 'Lalji Mulji Transport Co.', 'AVENUE SUPERMARTS LIMITED', '8320120817', '', '0.00', '0.00', '0.00', '300.00', '50.00', 2, '7557.00', '0.00', '0.00', '0.00', 7, '2021-03-04 15:51:29', NULL, NULL, NULL, '2021-03-05 12:39:23', '2021-03-17 00:00:00', NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-04 15:44:57', '2021-03-19 16:08:09', 'C', 1, '0.00', '240.00', '60.00', NULL, NULL, 7, 'GJ 05 BW 0646', '010053 240', NULL, NULL, '0.00', 3, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '300.00', '300.00', 'CHQ'),
(39, 28, '15601976', 122, 'Plot No. A/60/20, Road no.6, Near Ankur Weigh Bridge, Udhna udhyoganagar, Udhna, Surat, Surat-394210', 'CREATIVE PLASTIC\r\nPLOT NO. 110,GROUND Floor, Maruti Industries Estate-1, Laskana, Taluka - Kamrej,Surat', '21.170465801418736', '72.84854471683502', '21.251917375772752', '72.9316801522666', 'Dipak Bhai', '9724749016', 'Lalji Mulji Transport Co.', 'CREATIVE PLASTIC', '7878435557', '', '0.00', '0.00', '0.00', '150.00', '50.00', 1, '8590.00', '0.00', '0.00', '0.00', 7, '2021-03-04 15:51:20', NULL, NULL, NULL, '2021-03-04 17:15:21', '2021-03-17 00:00:00', NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-04 15:50:27', '2021-03-19 16:08:10', 'C', 1, '0.00', '120.00', '30.00', NULL, NULL, 7, 'GJ 05 BW 0646', '010053 240', NULL, NULL, '0.00', 6, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '150.00', '150.00', 'CHQ'),
(40, 29, '572-901838', 124, 'Jaipur GoldenTransport Organization\r\nGodown No. 32, Dumbhal, Near Amaazia Water Park, Parvat Patiya, Surat', 'Sainath Motors\r\nSHOP NO.4, DIVYA COMPLEX-A, OPP SENT MARK SCHOOL,GANGESHWAR TEMPLE, ADAJAN,SURAT,Surat,Gujarat', '21.193725010878477', '72.86618828773499', '21.19593507062176', '72.78982244431973', 'DevBhai', '8866554888', 'Jaipur Golden Transport Organization', 'Sunil Bhai', '9979267853', '', '0.00', '0.00', '0.00', '150.00', '60.00', 1, '0.00', '0.00', '50.00', '0.00', 7, '2021-03-05 13:07:34', NULL, NULL, NULL, '2021-03-05 15:16:00', '2021-03-04 00:00:00', NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-05 13:07:12', '2021-03-20 11:38:18', 'C', 1, '430.00', '120.00', '30.00', NULL, NULL, 7, 'GJ 05 BW 0646', NULL, NULL, NULL, '0.00', 14, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '200.00', '200.00', 'CS'),
(41, 30, '10070819', 122, 'Lalji Mulji Transport Co, Plot no. 6102, Road no. 61, opp. SBI Bank, G.I.D.C Sachin, Surat., Surat-394021', 'NATIONAL FIGHTER SPORTS\r\nM-3,Anmol Shoping Point, Opp. Radhe Nagar Society, Dumas Road, Parle Point, Surat', '21.098006829813485', '72.86286637187004', '21.17060586782178', '72.78851687908173', 'Arjun', '9998012774', 'Lalji Mulji Transport Co.', 'NATIONAL FIGHTER SPORTS', '9825770502', '', '0.00', '0.00', '0.00', '120.00', '50.00', 1, '17588.00', '0.00', '0.00', '0.00', 7, '2021-03-08 16:24:16', NULL, NULL, NULL, '2021-03-08 17:12:01', '2021-03-17 00:00:00', NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-08 12:18:31', '2021-03-19 16:08:10', 'C', 1, '766.00', '90.00', '30.00', NULL, NULL, 7, 'GJ 05 BW 0646', '010053 240', NULL, NULL, '0.00', 6, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '120.00', '120.00', 'CHQ'),
(42, 32, '31327281', 122, 'Lalji Mulji Transport Co, Plot no. 6102, Road no. 61, opp. SBI Bank, G.I.D.C Sachin, Surat., Surat-394021', 'TULATRANSELECTRICALS.\r\nC1 3511, GIDC, Road No 35, Sachin, Surat, Gujarat, 394230', '21.098006829813485', '72.86286637187004', '21.08932509028879', '72.86005675792694', 'Arjun', '9998012774', 'Lalji Mulji Transport Co.', 'TULATRANSELECTRICALS.', '9825193068', '', '0.00', '0.00', '0.00', '100.00', '50.00', 2, '33512.00', '0.00', '0.00', '0.00', 7, '2021-03-08 17:14:58', NULL, NULL, NULL, '2021-03-08 17:29:01', '2021-03-17 00:00:00', NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-08 13:00:46', '2021-03-19 16:08:10', 'C', 1, '1168.00', '60.00', '40.00', NULL, NULL, 7, 'GJ 05 BW 0646', '010053 240', NULL, NULL, '0.00', 6, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '100.00', '100.00', 'CHQ'),
(43, 33, '22581321', 122, 'Lalji Mulji Transport Co, Plot no. 6102, Road no. 61, opp. SBI Bank, G.I.D.C Sachin, Surat., Surat-394021', 'Waaree ESS Private Limited\r\nShed No.02, Plot No.334, Road No.3, GIDC Sachin, Surat, Gujarat, 394230', '21.098006829813485', '72.86286637187004', '21.09212285373388', '72.85219446159395', 'Arjun', '9998012774', 'Lalji Mulji Transport Co.', 'Waaree ESS Private Limited', '8882095321', '', '0.00', '0.00', '0.00', '250.00', '110.00', 5, '35778.00', '0.00', '0.00', '0.00', 7, '2021-03-08 17:23:52', NULL, NULL, NULL, '2021-03-08 17:29:08', '2021-03-17 00:00:00', NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-08 13:18:51', '2021-03-19 16:08:10', 'C', 1, '0.00', '150.00', '100.00', NULL, NULL, 7, 'GJ 05 BW 0646', '010053 240', NULL, NULL, '0.00', 6, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '250.00', '250.00', 'CHQ'),
(44, 31, '24315469', 122, 'Lalji Mulji Transport Co, Plot no. 6102, Road no. 61, opp. SBI Bank, G.I.D.C Sachin, Surat., Surat-394021', 'Somnath R.M.C Cement Product\r\nHoziwala-Over, Block-201, Vnaz, Sachin, Surat', '21.098006829813485', '72.86286637187004', '21.086439998322174', '72.89531578813194', 'Arjun', '9998012774', 'Lalji Mulji Transport Co.', 'Somnath R.M.C Cement Product', '9825111281', '', '0.00', '0.00', '0.00', '150.00', '50.00', 1, '0.00', '0.00', '0.00', '0.00', 7, '2021-03-08 17:13:06', NULL, NULL, NULL, '2021-03-08 17:28:54', '2021-03-17 00:00:00', NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-08 14:41:12', '2021-03-19 16:08:10', 'C', 1, '0.00', '120.00', '30.00', NULL, NULL, 7, 'GJ 05 BW 0646', '010053 240', NULL, NULL, '0.00', 6, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '150.00', '150.00', 'CHQ'),
(45, 34, '23986441', 122, 'Lalji Mulji Transport Co, Plot no. 6102, Road no. 61, opp. SBI Bank, G.I.D.C Sachin, Surat., Surat-394021', 'SHAIKH AND CO\r\nPLOT NO 948,Ground Floor, SHAIKH AND CO., Road NO 56, GIDC Estate Sachin, Surat, Gujarat, 394230', '21.098006829813485', '72.86286637187004', '21.092744730244082', '72.85998800675623', 'Arjun', '9998012774', 'Lalji Mulji Transport Co.', 'SHAIKH AND CO', '2612397579', '', '0.00', '0.00', '0.00', '210.00', '360.00', 3, '173364.00', '0.00', '0.00', '0.00', 7, '2021-03-08 17:25:35', NULL, NULL, NULL, '2021-03-08 17:29:13', '2021-03-17 00:00:00', NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-08 14:52:54', '2021-03-19 16:08:10', 'C', 1, '3010.00', '120.00', '90.00', NULL, NULL, 7, 'GJ 05 BW 0646', '010053 240', NULL, NULL, '0.00', 5, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '210.00', '210.00', 'CHQ'),
(46, 35, '17210410', 122, 'Plot No. A/60/20, Road no.6, Near Ankur Weigh Bridge, Udhna udhyoganagar, Udhna, Surat, Surat-394210', 'VISHWA IMPEX\r\n144 Sarjan Society, Opp. Sargam Shopping Center, Parley Point 395007, Surat', '21.170465801418736', '72.84854471683502', '21.169549729915065', '72.79071509819458', 'Dipak Bhai', '9724749016', 'Lalji Mulji Transport Co.', 'VISHWA IMPEX', '9825342345', '', '0.00', '0.00', '0.00', '120.00', '50.00', 1, '2879.00', '0.00', '0.00', '0.00', 7, '2021-03-09 11:35:23', NULL, NULL, NULL, '2021-03-09 11:47:03', '2021-03-17 00:00:00', NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-08 15:49:17', '2021-03-19 16:08:10', 'C', 1, '1200.00', '90.00', '30.00', NULL, NULL, 7, 'GJ 05 BW 0646', '010053 240', NULL, NULL, '0.00', 5, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '120.00', '120.00', 'CHQ'),
(48, 37, '18350066', 122, 'Lalji Mulji Transport Co, Plot no. 6102, Road no. 61, opp. SBI Bank, G.I.D.C Sachin, Surat., Surat-394021', 'BLICK SYSTEM INDIA PRIVATE LIMITED\r\nPsp Warehouse, Near. Surat Diamond Bourse, khajod, Surat', '21.098006829813485', '72.86286637187004', '21.119868237827845', '72.78822012319122', 'Arjun', '9998012774', 'Lalji Mulji Transport Co.', 'BLICK SYSTEM INDIA PRIVATE LIMITED', '8007001123', '', '0.00', '0.00', '0.00', '500.04', '760.00', 27, '340076.00', '0.00', '0.00', '0.00', 7, '2021-03-12 13:37:51', NULL, NULL, NULL, '2021-03-12 16:08:46', '2021-04-07 00:00:00', NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-12 12:17:35', '2021-04-05 18:12:24', 'C', 1, '0.00', '270.00', '230.04', NULL, NULL, 7, 'GJ 05 BW 0646', NULL, NULL, NULL, '0.00', 39, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '500.04', '500.04', 'CS'),
(47, 36, '476', 126, 'Godown No 1, Sehjanand Park, Opposite. Kumbharia Bus Stop, Saroli, Surat-395010', 'Babita narrow Fabrics2/5149 Rustampura Police Chowki Road, Opp. Bharucha wadi, Baside. Prakash Bakery, Surat', '21.19098661660373', '72.88688689470291', '21.189207258332004', '72.83234015107155', 'KrunalBhai', '8200132234', 'Lalji Mulji Transport Co', 'KAMAL', '9737799955', 'Lalji Mulji Transport Co', '0.00', '0.00', '0.00', '100.00', '40.00', 1, '9055.00', '0.00', '0.00', '0.00', 7, '2021-03-09 11:46:36', NULL, NULL, NULL, '2021-03-09 11:47:09', '2021-03-09 00:00:00', NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-08 17:09:29', '2021-03-09 17:40:39', 'C', 1, '0.00', '70.00', '30.00', NULL, NULL, 7, 'GJ 05 BW 0646', NULL, NULL, 'COD', '0.00', 4, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '100.00', '100.00', 'CS'),
(50, 39, '36904329', 122, 'Plot No. A/60/20, Road no.6, Near Ankur Weigh Bridge, Udhna udhyoganagar, Udhna, Surat, Surat-394210', 'Orange Megastructure LLP\r\nPlot No-112, Town Planning Scheme No.7, Vesu, Magdalla Circle, Surat, Gujarat, 395007', '21.170465801418736', '72.84854471683502', '21.13609925555691', '72.7512127161026', 'Dipak Bhai', '9724749016', 'Lalji Mulji Transport Co.', 'Orange Megastructure LLP', '7874308905', '', '0.00', '0.00', '0.00', '300.00', '50.00', 1, '20406.00', '0.00', '0.00', '0.00', 6, '2021-03-12 13:35:45', NULL, NULL, NULL, '2021-03-12 16:07:38', '2021-04-07 00:00:00', NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-12 13:21:37', '2021-04-05 18:12:24', 'C', 1, '850.00', '250.00', '50.00', NULL, NULL, 6, 'GJ 05 CT 4131', NULL, NULL, NULL, '0.00', 39, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '300.00', '300.00', 'CS'),
(49, 38, '25970003', 122, 'Lalji Mulji Transport Co, Plot no. 6102, Road no. 61, opp. SBI Bank, G.I.D.C Sachin, Surat., Surat-394021', 'NOVA DYESTUFF IND.PVT.LTD.\r\nPlot No. 251, G.I.D.C. Estate, Pandesara, Surat, Gujarat, 394221', '21.098006829813485', '72.86286637187004', '21.141224037701495', '72.83161997795105', 'Arjun', '9998012774', 'Lalji Mulji Transport Co.', 'NOVA DYESTUFF IND.PVT.LTD.', '9099501689', '', '0.00', '0.00', '0.00', '340.00', '200.00', 8, '102737.00', '0.00', '0.00', '0.00', 7, '2021-03-12 13:37:15', NULL, NULL, NULL, '2021-03-12 16:51:54', '2021-04-07 00:00:00', NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-12 12:30:30', '2021-04-05 18:12:24', 'C', 1, '1463.00', '240.00', '100.00', NULL, NULL, 7, 'GJ 05 BW 0646', NULL, NULL, NULL, '0.00', 39, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '340.00', '340.00', 'CS'),
(51, 40, '35328037', 122, 'Plot No. A/60/20, Road no.6, Near Ankur Weigh Bridge, Udhna udhyoganagar, Udhna, Surat, Surat-394210', 'METRO CASH AND CARRY INDIA PVT LTD\r\nBlock no 188 survey no 143,VIP Road,Shagun Villa,VIP Road,ALTHANA,Surat,Gujarat, 395017', '21.170465801418736', '72.84854471683502', '21.144786388616865', '72.80264139175415', 'Dipak Bhai', '9724749016', 'Lalji Mulji Transport Co.', 'METRO CASH AND CARRY INDIA PVT LTD', '9874750267', '', '0.00', '0.00', '0.00', '400.00', '120.00', 4, '49361.00', '0.00', '0.00', '0.00', 7, '2021-03-13 13:01:57', NULL, NULL, NULL, '2021-03-13 14:16:39', '2021-04-07 00:00:00', NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-13 11:30:35', '2021-04-05 18:12:24', 'C', 1, '0.00', '280.00', '120.00', NULL, NULL, 7, 'GJ 05 BW 0646', NULL, NULL, NULL, '0.00', 39, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '400.00', '400.00', 'CS'),
(52, 41, '27556', 122, 'METRO CASH AND CARRY INDIA PVT LTD\r\nBlock no 188 survey no 143,VIP Road,Shagun Villa,VIP Road,ALTHANA,Surat,Gujarat, 395017', 'Plot No. A/60/20, Road no.6, Near Ankur Weigh Bridge, Udhna udhyoganagar, Udhna, Surat, Surat-394210', '21.144747615187672', '72.80266414808484', '21.170465801418736', '72.84854471683502', 'METRO CASH AND CARRY INDIA PVT LTD', '9874750267', 'Lalji Mulji Transport Co.', 'Dipak Bhai', '9724749016', 'Lalji Mulji Transport Co.', '0.00', '0.00', '0.00', '160.00', '60.00', 2, '0.00', '0.00', '0.00', '0.00', 7, '2021-03-13 14:16:17', NULL, NULL, NULL, '2021-03-13 14:16:43', '2021-04-07 00:00:00', NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-13 11:36:37', '2021-04-05 18:12:24', 'C', 1, '0.00', '100.00', '60.00', NULL, NULL, 7, 'GJ 05 BW 0646', NULL, NULL, NULL, '0.00', 39, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '160.00', '160.00', 'CS'),
(53, 42, 'AGR-984/986', 127, 'Shree Prabhat Roadway Service\r\nNear. Hotal Maharaja, Vedchha Patiya Check Post, Surat', 'SHOP NO. A/114, Landmark Empire, Puna Kumbhariya Road, Magob, Surat, Gujarat, 395010', '21.18645672084212', '72.90420538376499', '21.190561335302238', '72.87652352177825', 'Shree Prabhat Roadway Service', '8077831887', 'Shree Prabhat Roadway Service', 'SHANKESHWAR FAB TEX', '7874280774', '', '0.00', '0.00', '0.00', '200.00', '120.00', 2, '45000.00', '0.00', '0.00', '0.00', 7, '2021-03-13 16:06:54', NULL, NULL, NULL, '2021-03-13 17:06:42', '2021-03-13 00:00:00', NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-13 16:04:59', '2021-03-15 15:07:51', 'C', 1, '1070.00', '140.00', '60.00', NULL, NULL, 7, 'GJ 05 BW 0646', NULL, NULL, 'COD', '0.00', 7, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '200.00', '200.00', 'CS'),
(54, 43, '30562432', 125, 'Lalji Mulji Transport Co.	\r\nPlot No. A/60/20, Road no.6, Near Ankur Weigh Bridge, Udhna udhyoganagar, Udhna, Surat', 'C-402, NEAR UMRIGAR SCHOOL, OPERA TOWN, UMRA, SURAT, Surat, Gujarat, 395007, Surat-395007', '21.170465801418736`', '72.84854471683502', '21.17649854133468', '72.78605461120605', 'Dipak Bhai', '9724749016', 'Lalji Mulji Transport Co.', 'Hirenbhai', '9824159029', '', '0.00', '0.00', '0.00', '600.60', '390.00', 39, '55086.00', '0.00', '0.00', '0.00', 7, '2021-03-15 11:42:55', NULL, NULL, NULL, '2021-03-15 13:32:53', '2021-03-15 00:00:00', NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-15 11:35:25', '2021-03-15 14:02:28', 'C', 1, '2030.00', '390.00', '210.60', NULL, NULL, 7, 'GJ 05 BW 0646', NULL, NULL, 'COD', '0.00', 8, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '600.60', '600.60', 'CS'),
(55, 46, '50073766', 119, 'Om Ravi Transport Co.\r\nGodown no. 15 Dumbhal Transport Nagar Surat', 'YogiDarshan Studio\r\n7/8, Harsh Complex, Katargam Bus Stop, Nr.dholakiya Garden Opp.Bank Of Baroda. Surat 395004', '21.194511317668958', '72.86520743742585', '21.22209628894929', '72.83333659172058', 'Transport Manager', '7998879900', 'Om Ravi Transport Co.', 'Saileshbhai', '9825153439', '', '0.00', '0.00', '0.00', '100.00', '50.00', 1, '1718.00', '100.00', '100.00', '0.00', 6, '2021-03-15 12:02:48', NULL, NULL, NULL, '2021-03-15 18:13:26', '2021-03-15 00:00:00', NULL, 0, NULL, 5, 'D', NULL, NULL, NULL, 0, '2021-03-15 12:02:26', '2021-03-15 18:14:10', 'C', 1, '220.00', '70.00', '30.00', NULL, NULL, 6, 'GJ 05 CT 4131', NULL, NULL, 'COD', '0.00', 9, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '100.00', '100.00', 'CS'),
(56, 44, '27573295', 122, 'Godown No 1, Sehjanand Park, Opposite. Kumbharia Bus Stop, Saroli, Surat-395010', 'GARDEN SILK MILLS LIMITED\r\nTantithaiya, Jolwa, Kadodara, Gujarat', '21.190645687601684', '72.89022335929879', '21.165306105067177', '72.9999862373221', 'KrunalBhai', '8200132234', '', 'GARDEN SILK MILLS LIMITED', '9099001625', 'Lalji Mulji Transport Co.', '0.00', '0.00', '0.00', '200.00', '50.00', 1, '12588.00', '0.00', '0.00', '0.00', 6, '2021-03-15 13:29:55', NULL, NULL, NULL, '2021-03-15 14:00:25', '2021-04-07 00:00:00', NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-15 12:56:07', '2021-04-05 18:12:24', 'C', 1, '0.00', '170.00', '30.00', NULL, NULL, 6, 'GJ 05 CT 4131', NULL, NULL, NULL, '0.00', 39, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '200.00', '200.00', 'CS'),
(57, 45, '27573334', 122, 'Godown No 1, Sehjanand Park, Opposite. Kumbharia Bus Stop, Saroli, Surat-395010', 'PURVI ENGINEERS INDIA PVT LTD\r\nBM 8,10, Sheela Tower, Samarth Park, Adajan Main Road, Surat', '21.190645687601684', '72.89022335929879', '21.190670921553757', '72.796679470592', 'KrunalBhai', '8200132234', '', 'PURVI ENGINEERS INDIA PVT LTD', '8511197740', 'Lalji Mulji Transport Co.', '0.00', '0.00', '0.00', '120.00', '50.00', 1, '28320.00', '0.00', '0.00', '0.00', 6, '2021-03-15 13:30:09', NULL, NULL, NULL, '2021-03-15 16:15:10', NULL, NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-15 13:05:08', '2021-04-05 21:58:27', 'C', 0, '0.00', '90.00', '30.00', NULL, NULL, 6, 'GJ 05 CT 4131', NULL, NULL, NULL, '0.00', 40, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '120.00', '120.00', 'CS'),
(58, 47, '31327568', 122, 'Lalji Mulji Transport Co, Plot no. 6102, Road no. 61, opp. SBI Bank, G.I.D.C Sachin, Surat., Surat-394021', 'MAXHEAL LABORATORIES PRIVATE LIMITED\r\nPlot no.2-7/80-85, SURSEZ, Sachin, Gujarat 394230', '21.098006829813485', '72.86286637187004', '21.086822512973423', '72.87128448486328', 'Arjun', '9998012774', '', 'MAXHEAL LABORATORIES PRIVATE LIMITED', '8690344333', 'Lalji Mulji Transport Co.', '0.00', '0.00', '0.00', '200.00', '120.00', 4, '17550.00', '0.00', '0.00', '0.00', 7, '2021-03-15 16:12:54', NULL, NULL, NULL, '2021-03-15 17:59:21', NULL, NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-15 15:17:12', '2021-04-05 21:58:27', 'C', 0, '985.00', '120.00', '80.00', NULL, NULL, 7, 'GJ 05 BW 0646', NULL, NULL, NULL, '0.00', 40, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '200.00', '200.00', 'CS'),
(60, 48, '48', 128, '313 A Building, LandMark Empires, Magob, Surat', '583/84/85, Annapurna Textile Market, Ring Road, Surat, Surat-395002', '21.190383912968535', '72.87706732749939', '21.18921831491858', '72.84009929702026', 'Sarang', '7778041114', '', 'SunilBhai', '9818534763', '', '0.00', '0.00', '0.00', '200.40', '56.00', 6, '0.00', '0.00', '0.00', '0.00', 6, '2021-03-15 17:49:53', NULL, NULL, NULL, '2021-03-18 11:59:10', '2021-03-17 00:00:00', NULL, 0, NULL, 5, 'D', NULL, NULL, NULL, 0, '2021-03-15 17:49:23', '2021-03-18 11:59:43', 'C', 1, '0.00', '180.00', '20.40', NULL, NULL, 6, 'GJ 05 CT 4131', NULL, NULL, NULL, '0.00', 10, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '200.40', '200.40', 'CS'),
(61, 49, '572-903358', 124, 'Jaipur GoldenTransport Organization\r\nGodown No. 32, Dumbhal, Near Amaazia Water Park, Parvat Patiya, Surat, Surat-395010', 'Sainath Motors\r\nSHOP NO.4, DIVYA COMPLEX-A, OPP SENT MARK SCHOOL,GANGESHWAR TEMPLE, ADAJAN,SURAT,Surat,Gujarat', '21.193725010878477', '72.86618828773499', '21.19593507062176', '72.78982244431973', 'Jaipur GoldenTransport Organization', '0261285001', 'Jaipur Golden Transport Organization', 'Sunil Bhai', '9979267853', '', '0.00', '0.00', '0.00', '810.00', '540.00', 9, '48100.00', '0.00', '0.00', '0.00', 6, '2021-03-17 20:23:49', NULL, NULL, NULL, '2021-03-17 20:24:18', '2021-03-17 00:00:00', NULL, 0, NULL, 8, 'D', NULL, NULL, NULL, 0, '2021-03-17 20:22:12', '2021-03-19 10:35:49', 'C', 1, '2880.00', '540.00', '270.00', NULL, NULL, 6, 'GJ 05 CT 4131', NULL, NULL, 'COD', '0.00', 2, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '810.00', '810.00', 'CS'),
(62, 50, '206-058770', 128, 'Jaipur GoldenTransport Organization\r\nGodown No. 32, Dumbhal, Near Amaazia Water Park, Parvat Patiya, Surat', '583/84/85, Annapurna Textile Market, Ring Road, Surat, Surat-395002', '21.193725010878477', '72.86618828773499', '21.18921831491858', '72.84009929702026', 'DevBhai', '8866554888', 'Jaipur Golden Transport Organization', 'SunilBhai', '9818534763', '', '0.00', '0.00', '0.00', '150.00', '60.00', 1, '0.00', '0.00', '50.00', '0.00', 6, '2021-03-17 20:31:47', NULL, NULL, NULL, '2021-03-17 20:31:56', '2021-03-17 00:00:00', NULL, 0, NULL, 8, 'D', NULL, NULL, NULL, 0, '2021-03-17 20:31:18', '2021-03-19 10:33:36', 'C', 1, '0.00', '120.00', '30.00', NULL, NULL, 6, 'GJ 05 CT 4131', NULL, NULL, 'COD', '0.00', 1, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '200.00', '200.00', 'CS'),
(63, 51, '4403657', 130, 'Jamnagar Transport\r\nUdhna Road No-3 , Near Dharti Namkin, Surat', 'M-2, 723 Kaveri saree, Millenium-2, Near ragukul Market, Surat', '21.172643717477843', '72.84843629092397', '21.18433086482725', '72.84618502628567', 'Imtiyaz Bhai', '9737047149', 'Jamnagar Transport', 'Kaveri saree', '9773096780', '', '0.00', '0.00', '0.00', '500.00', '400.00', 50, '0.00', '0.00', '0.00', '0.00', 7, '2021-03-19 12:44:45', NULL, NULL, NULL, '2021-03-19 16:45:58', '2021-03-19 00:00:00', NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-19 12:41:39', '2021-04-17 10:17:07', 'C', 1, '2760.00', '500.00', '0.00', NULL, NULL, 7, 'GJ 05 BW 0646', NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '500.00', '500.00', 'CS');
INSERT INTO `tbl_orders` (`id`, `bigdaddy_lr_number`, `transporter_lr_number`, `user_id`, `pickup_location`, `drop_location`, `pickup_latitude`, `pickup_longitude`, `drop_latitude`, `drop_longitude`, `contact_person_name`, `contact_person_phone_number`, `transporter_name`, `contact_person_name_drop`, `contact_person_phone_number_drop`, `transporter_name_drop`, `goods_height`, `goods_width`, `goods_length`, `final_cost`, `total_weight`, `total_no_of_parcel`, `customer_estimation_asset_value`, `discount`, `min_order_value_charge`, `redeliver_charge`, `driver_id`, `driver_assigned_datetime`, `pickedup_datetime`, `cancelled_datetime`, `if_cancelled_reason_text`, `delivered_datetime`, `payment_datetime`, `undelivered_datetime`, `if_undelivered_reason_id`, `if_undelivered_reason_text`, `order_created_by`, `status`, `lr_img`, `pickup_img`, `deliver_img`, `is_active`, `created_at`, `updated_at`, `payment_type`, `payment_status`, `transport_cost`, `tempo_charge`, `service_charge`, `other_field_pickup`, `other_field_drop`, `vehicle_id`, `vehicle_no`, `if_cheque_number`, `if_transaction_number`, `payment_comment`, `payment_discount`, `invoice_id`, `order_driver_trip_type`, `order_driver_trip_amount`, `subscription_purchase_id`, `subscription_benefit_amount`, `coupon_code_applied`, `coupon_code_id`, `coupon_benefit_amount`, `wallet_amount_used`, `prepaid_amount_used`, `cod_amount_used`, `total_payable_amount`, `payment_type_manual`) VALUES
(64, 52, '52', 131, 'Mahalaxmi textile mills, \r\n107 1st floor, Radhey market, Ring road, Surat.', 'G/73 royal arcade Sarthana jakatnaka opp deepkamal mall', '21.191101655192504', '72.84442752599716', '21.228639925765886', '72.89795077495071', 'Gaurav Agarwal', '9825788382', '', 'Gopalbhai', '97141 3328', '', '0.00', '0.00', '0.00', '150.00', '50.00', 3, '0.00', '0.00', '50.00', '0.00', 7, '2021-03-22 11:44:24', NULL, NULL, NULL, '2021-03-22 14:26:03', '2021-03-22 00:00:00', NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-22 11:43:58', '2021-03-22 14:26:20', 'C', 1, '0.00', '90.00', '60.00', NULL, NULL, 7, 'GJ 05 BW 0646', NULL, NULL, 'COD', '0.00', 24, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '200.00', '200.00', 'CS'),
(65, 53, '35733538', 122, 'Plot No. A/60/20, Road no.6, Near Ankur Weigh Bridge, Udhna udhyoganagar, Udhna, Surat, Surat-394210', 'Hastee Smart Retail Pvt. Ltd.\r\n KRISHI BHAVAN PUNA KUMBHARIYA ROAD, NEAR KIRAN MOTORS, AAI MATA CIRCLE, Surat, Gujarat 395010', '21.170465801418736', '72.84854471683502', '21.19460785489266', '72.86905499972461', 'Dipak Bhai', '9724749016', 'Lalji Mulji Transport Co.', 'Hastee Mart', '7575072719', '', '0.00', '0.00', '0.00', '100.00', '85.00', 1, '14879.00', '0.00', '0.00', '0.00', 7, '2021-03-23 12:10:05', NULL, NULL, NULL, '2021-03-23 13:16:57', NULL, NULL, 0, NULL, 5, 'D', NULL, NULL, NULL, 0, '2021-03-23 11:37:16', '2021-04-05 21:58:27', 'C', 0, '0.00', '70.00', '30.00', NULL, NULL, 7, 'GJ 05 BW 0646', NULL, NULL, NULL, '0.00', 40, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '100.00', '100.00', 'CS'),
(67, 55, '22609368', 125, 'C-402, Near Umrigar School, Opera Town, Umra, Surat, Gujarat, Surat-395007', 'Om Logistics Ltd.\r\nSaniya Ahmed Road, Shivam Estate, Salasar Residency, Saroli.', '21.17649854133468', '72.78605461120605', '21.17649854133468', '72.78605461120605', 'Hirenbhai', '9824159029', '', 'Om Logistics', '9714502032', 'Om Logistics Ltd.', '0.00', '0.00', '0.00', '420.00', '125.00', 6, '24662.00', '0.00', '0.00', '0.00', 6, '2021-03-23 12:09:33', NULL, NULL, NULL, '2021-03-23 14:38:02', '2021-03-24 00:00:00', NULL, 0, NULL, 5, 'D', NULL, NULL, NULL, 0, '2021-03-23 12:08:53', '2021-03-24 15:56:53', 'C', 1, '0.00', '300.00', '120.00', NULL, NULL, 6, 'GJ 05 CT 4131', NULL, NULL, 'COD', '0.00', 26, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '420.00', '420.00', 'CS'),
(66, 54, '23029481', 122, 'Plot No. A/60/20, Road no.6, Near Ankur Weigh Bridge, Udhna udhyoganagar, Udhna, Surat, Surat-394210', 'Avenue Super Marts Ltd. (D Mart)\r\n108,Vaishnodevi Heights, B/H Subhash Garden\r\nPalanpur Canal Rd, Prabhudarshan Society, Jahangir Pura, Dahin Nagar\r\nSurat, Gujarat 395005\r\nIndia', '21.170465801418736', '72.84854471683502', '21.230082072700238', '72.77830839157104', 'Dipak Bhai', '9724749016', 'Lalji Mulji Transport Co.', 'D Mart', '9974769720', '', '0.00', '0.00', '0.00', '240.00', '50.00', 2, '8997.00', '0.00', '0.00', '0.00', 7, '2021-03-23 12:10:04', NULL, NULL, NULL, '2021-03-23 13:17:07', NULL, NULL, 0, NULL, 5, 'D', NULL, NULL, NULL, 0, '2021-03-23 11:41:34', '2021-04-05 21:58:27', 'C', 0, '0.00', '180.00', '60.00', NULL, NULL, 7, 'GJ 05 BW 0646', NULL, NULL, NULL, '0.00', 40, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '240.00', '240.00', 'CS'),
(68, 56, '56', 129, 'G. Poonawala\r\nOpp. RKLP, RRTM, Nastawala Ki Line me,\r\nBharat Cancer Hospital ki Gali me, Saroli Main Road, Surat', '1006 Raj Textile Tower, Besides Shree Mahavir Textile Market, Surat.', '21.191345345190605', '72.88256439569126', '21.19273469332693', '72.87448167800903', 'Irfan', '6355498396', '', 'Sarangbhai', '9099007849', '', '0.00', '0.00', '0.00', '150.00', '30.00', 1, '5000.00', '0.00', '50.00', '0.00', 7, '2021-03-23 16:32:07', NULL, NULL, NULL, '2021-03-23 17:25:37', '2021-03-23 00:00:00', NULL, 0, NULL, 5, 'D', NULL, NULL, NULL, 0, '2021-03-23 16:31:32', '2021-03-23 18:05:41', 'C', 1, '0.00', '120.00', '30.00', NULL, NULL, 7, 'GJ 05 BW 0646', NULL, NULL, 'COD', '0.00', 27, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '200.00', '200.00', 'CS'),
(69, 58, '27636866', 122, 'Plot No. A/60/20, Road no.6, Near Ankur Weigh Bridge, Udhna udhyoganagar, Udhna, Surat, Surat-394210', 'ATUL FABRICATORS\r\n512-513, First Floor, Ahirwas, Khodiyar Nagar Society, Adajan, Surat, Gujarat, 395009', '21.170465801418736', '72.84854471683502', '21.187620447818812', '72.79068142175674', 'Dipak Bhai', '9724749016', 'Lalji Mulji Transport Co.', 'ATUL FABRICATORS', '9314019921', '', '0.00', '0.00', '0.00', '150.00', '50.00', 1, '38232.00', '0.00', '0.00', '0.00', 6, '2021-03-25 16:38:59', '2021-03-25 16:47:48', NULL, NULL, '2021-03-25 17:50:59', '2021-03-25 17:50:59', NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-25 16:20:22', '2021-04-05 21:58:27', 'C', 0, '850.00', '120.00', '30.00', NULL, NULL, 6, 'GJ 05 CT 4131', NULL, NULL, NULL, '0.00', 40, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '150.00', '150.00', 'CS'),
(70, 59, '27636867', 122, 'Plot No. A/60/20, Road no.6, Near Ankur Weigh Bridge, Udhna udhyoganagar, Udhna, Surat, Surat-394210', 'PERFECT INSULATION AND ENGINEERING\r\n512-513, First Floor, Ahirwas, Khodiyar Nagar Society, Adajan, Surat, Gujarat, 395009', '21.170465801418736', '72.84854471683502', '21.18761253325979', '72.7906723771362', 'Dipak Bhai', '9724749016', 'Lalji Mulji Transport Co.', 'PERFECT INSULATION AND ENGINEERING', '9714500987', '', '0.00', '0.00', '0.00', '150.00', '50.00', 1, '38232.00', '0.00', '0.00', '0.00', 6, '2021-03-25 16:38:57', '2021-03-25 16:48:06', NULL, NULL, '2021-03-25 17:51:33', '2021-03-25 17:51:33', NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-25 16:25:56', '2021-04-05 22:00:02', 'C', 0, '850.00', '120.00', '30.00', NULL, NULL, 6, 'GJ 05 CT 4131', NULL, NULL, NULL, '0.00', 41, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '150.00', '150.00', 'CS'),
(71, 57, '23377696', 122, 'Plot No. A/60/20, Road no.6, Near Ankur Weigh Bridge, Udhna udhyoganagar, Udhna, Surat, Surat-394210', 'AS Technologies \r\nJack and Jones, G-02, Velocity Business Mall, Adajan, Surat', '21.170465801418736', '72.84854471683502', '21.19228703304272', '72.78738485908319', 'Dipak Bhai', '9724749016', 'Lalji Mulji Transport Co.', 'SAiyad', '8169949411', '', '0.00', '0.00', '0.00', '300.00', '100.00', 2, '10000.00', '0.00', '0.00', '0.00', 6, '2021-03-25 16:38:59', '2021-03-25 16:46:50', NULL, NULL, '2021-03-25 18:09:45', '2021-03-25 18:09:45', NULL, 0, NULL, 5, 'D', NULL, NULL, NULL, 0, '2021-03-25 16:38:40', '2021-04-05 21:58:27', 'C', 0, '0.00', '240.00', '60.00', NULL, NULL, 6, 'GJ 05 CT 4131', NULL, NULL, NULL, '0.00', 40, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '300.00', '300.00', 'CS'),
(73, 60, '23029548', 122, 'Plot No. A/60/20, Road no.6, Near Ankur Weigh Bridge, Udhna udhyoganagar, Udhna, Surat, Surat-394210', 'Avenue Super Marts Ltd. (D Mart)\r\n108,Vaishnodevi Heights, B/H Subhash Garden\r\nPalanpur Canal Rd, Prabhudarshan Society, Jahangir Pura, Dahin Nagar\r\nSurat, Gujarat 395005 India', '21.170465801418736', '72.84854471683502', '21.229940150081376', '72.77830035954096', 'Dipak Bhai', '9724749016', 'Lalji Mulji Transport Co.', 'Avenue Super Marts Ltd. (D Mart)', '7738931940', '', '0.00', '0.00', '0.00', '240.00', '50.00', 2, '11157.00', '0.00', '0.00', '0.00', 6, '2021-03-26 17:58:32', NULL, NULL, NULL, '2021-03-26 17:59:13', NULL, NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-26 23:26:49', '2021-04-05 22:00:02', 'C', 0, '0.00', '180.00', '60.00', NULL, NULL, 6, 'GJ 05 CT 4131', NULL, NULL, NULL, '0.00', 41, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '240.00', '240.00', 'CS'),
(74, 62, '21003766', 122, 'Plot No. A/60/20, Road no.6, Near Ankur Weigh Bridge, Udhna udhyoganagar, Udhna, Surat, Surat-394210', 'GIA INDIA LABORATORY PRIVATE LIMITED\r\n2ND AND 3RD FLOOR,F.P No 10/11, RS No 15/1 A and 9/2, Building No A, Swastik Universal, Near Valentine Cinema, Piplod, Surat, Gujarat, 395007', '21.170465801418736', '72.84854471683502', '21.155022667397155', '72.76375472545624', 'Dipak Bhai', '9724749016', 'Lalji Mulji Transport Co.', 'GIA INDIA LABORATORY PRIVATE LIMITED', '9833537200', '', '0.00', '0.00', '0.00', '150.00', '50.00', 1, '31978.00', '0.00', '0.00', '0.00', 7, '2021-03-27 14:45:35', NULL, NULL, NULL, '2021-03-27 16:03:11', NULL, NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-27 19:31:45', '2021-04-05 22:00:02', 'C', 0, '0.00', '120.00', '30.00', NULL, NULL, 7, 'GJ 05 BW 0646', NULL, NULL, NULL, '0.00', 41, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '150.00', '150.00', 'CS'),
(75, 61, '27091284', 122, 'Plot No. A/60/20, Road no.6, Near Ankur Weigh Bridge, Udhna udhyoganagar, Udhna, Surat, Surat-394210', 'ACCUTEX ENGINEERING\r\nA/21/14, Road No.10, udyog Nagar, Udhna Surat 394210', '21.170465801418736', '72.84854471683502', '21.170373696609403', '72.84379991321518', 'Dipak Bhai', '9724749016', 'Lalji Mulji Transport Co.', 'ACCUTEX ENGINEERING', '9227921207', '', '0.00', '0.00', '0.00', '100.00', '50.00', 2, '150000.00', '0.00', '0.00', '0.00', 7, '2021-03-27 14:44:32', NULL, NULL, NULL, '2021-03-27 15:17:15', NULL, NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-27 19:45:52', '2021-04-05 22:00:02', 'C', 0, '0.00', '60.00', '40.00', NULL, NULL, 7, 'GJ 05 BW 0646', NULL, NULL, NULL, '0.00', 41, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '100.00', '100.00', 'CS'),
(76, 63, '20326064', 122, 'Plot No. A/60/20, Road no.6, Near Ankur Weigh Bridge, Udhna udhyoganagar, Udhna, Surat, Surat-394210', 'SHUBHALAKSHMI POLYESTERS LIMITED\r\n418 419, 4th Floor, Jeevandeep Complex, Ring Road, Nr Rajhans Complex, Sagrampura, Surat', '21.170465801418736', '72.84854471683502', '21.181385198827172', '72.82501421157855', 'Dipak Bhai', '9724749016', 'Lalji Mulji Transport Co.', 'SHUBHALAKSHMI POLYESTERS LIMITED', '2612279364', '', '0.00', '0.00', '0.00', '150.00', '50.00', 1, '23751.00', '0.00', '0.00', '0.00', 7, '2021-03-30 11:03:57', '2021-03-30 11:07:27', NULL, NULL, '2021-03-30 17:35:57', NULL, NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-30 16:33:27', '2021-04-05 22:00:02', 'C', 0, '0.00', '120.00', '30.00', NULL, NULL, 7, 'GJ 05 BW 0646', NULL, NULL, NULL, '0.00', 41, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '150.00', '150.00', 'CS'),
(79, 1001, '4403778', 130, 'Jamnagar Transport\r\nUdhna Road No-3 , Near Dharti Namkin, Surat', 'M-2, 723 Kaveri saree, Millenium-2, Near ragukul Market, Surat', '21.172643717477843', '72.84843629092397', '21.18433086482725', '72.84618502628567', 'Imtiyaz Bhai', '9737047149', 'Jamnagar Transport', 'Kaveri saree', '9773096780', '', '0.00', '0.00', '0.00', '500.28', '528.00', 66, '30360.00', '0.00', '0.00', '0.00', 7, '2021-03-31 12:01:13', '2021-03-31 17:19:13', NULL, NULL, '2021-03-31 17:21:29', '2021-03-31 17:21:29', NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-31 17:29:18', '2021-03-31 22:51:29', 'C', 1, '3640.00', '500.28', '0.00', NULL, NULL, 7, 'GJ 05 BW 0646', NULL, NULL, NULL, '0.00', 29, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '500.28', '500.28', 'CS'),
(77, 64, '64', 129, '1006 Raj Textile Tower, Besides Shree Mahavir Textile Market, Surat.', 'G. Poonawala\r\nOpp. RKLP, RRTM, Nastawala Ki Line me,\r\nBharat Cancer Hospital ki Gali me, Saroli Main Road, Surat', '21.19273469332693', '72.87448167800903', '21.191345345190605', '72.88256439569126', 'Sarang Bhai', '9099007849', '', 'Irfan', '6355498396', '', '0.00', '0.00', '0.00', '100.00', '30.00', 1, '5000.00', '0.00', '0.00', '0.00', 6, '2021-03-30 17:07:26', '2021-03-30 17:45:19', NULL, NULL, '2021-03-30 17:53:34', '2021-03-30 17:53:34', NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-30 22:26:33', '2021-03-30 23:23:34', 'C', 1, '0.00', '70.00', '30.00', NULL, NULL, 6, 'GJ 05 CT 4131', NULL, NULL, NULL, '0.00', 28, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '100.00', '100.00', 'CS'),
(78, 65, '65', 129, 'G. Poonawala\r\nOpp. RKLP, RRTM, Nastawala Ki Line me,\r\nBharat Cancer Hospital ki Gali me, Saroli Main Road, Surat', 'Raj Textile Tower\r\n1006 Raj Textile Tower, Besides Shree Mahavir Textile Market, Surat.', '21.191345345190605', '72.88256439569126', '21.19273469332693', '72.87448167800903', 'Irfan', '6355498396', '', 'Sarangbhai', '9099007849', '', '0.00', '0.00', '0.00', '100.00', '30.00', 1, '5000.00', '0.00', '0.00', '0.00', 6, '2021-03-30 17:07:49', '2021-03-30 18:08:25', NULL, NULL, '2021-03-30 18:21:34', '2021-03-30 18:21:34', NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-30 22:31:07', '2021-03-30 23:51:34', 'C', 1, '0.00', '70.00', '30.00', NULL, NULL, 6, 'GJ 05 CT 4131', NULL, NULL, NULL, '0.00', 28, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '100.00', '100.00', 'CS'),
(81, 1002, '27316819KK', 125, 'Lalji Mulji Transport Co.\r\nPlot No. A/60/20, Road no.6, Near Ankur Weigh Bridge, Udhna udhyoganagar, Udhna, Surat', 'Ankit enterprise\r\nNear, St\'Xaviers High School', '21.170465801418736', '72.84854471683502', '21.17649854133468', '72.78605461120605', 'Dipak Bhai', '9724749016', 'Lalji Mulji Transport Co.', 'Hirenbhai', '9824159029', '', '0.00', '0.00', '0.00', '150.00', '50.00', 1, '4118.00', '130.00', '50.00', '0.00', 6, '2021-03-31 15:53:40', '2021-03-31 16:27:15', NULL, NULL, '2021-03-31 17:00:18', '2021-03-31 17:00:18', NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-31 21:10:01', '2021-04-06 12:45:54', 'C', 1, '175.00', '120.00', '30.00', NULL, NULL, 6, 'GJ 05 CT 4131', NULL, NULL, NULL, '0.00', 30, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '70.00', '70.00', 'CS'),
(82, 1003, '1003', 128, 'kuberji Textile Park\r\nA-307, Kuberji  textile park-1, Near japan market\r\nDelhi gate, Surat', '583/84/85, Annapurna Textile Market, Ring Road, Surat, Surat-395002', '21.193725010878477', '72.86618828773499', '21.18921831491858', '72.84009929702026', 'Shubham bahi', '7622922692', '', 'SunilBhai', '9818534763', '', '0.00', '0.00', '0.00', '200.10', '50.00', 3, '0.00', '0.00', '0.00', '0.00', 7, '2021-03-31 17:45:57', NULL, NULL, NULL, '2021-03-31 19:09:40', '2021-03-31 00:00:00', NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-03-31 21:38:18', '2021-04-01 00:39:49', 'C', 1, '0.00', '120.00', '80.10', NULL, NULL, 7, 'GJ 05 BW 0646', NULL, NULL, 'COD', '0.00', 32, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '200.10', '200.10', 'CS'),
(83, 1004, '1004', 129, '313 A Building, LandMark Empires, Magob, Surat, Surat-395010', 'Raj Textile Tower\r\n1006 Raj Textile Tower, Besides Shree Mahavir Textile Market, Surat.', '21.190336431890316', '72.8778735486977', '21.19273469332693', '72.87448167800903', 'Sarang Bhai', '7778041114', '', 'Sarangbhai', '9099007849', '', '0.00', '0.00', '0.00', '200.00', '100.00', 25, '1000.00', '0.00', '0.00', '0.00', 7, '2021-04-02 13:35:05', '2021-04-02 13:59:08', NULL, NULL, '2021-04-02 14:33:11', '2021-04-02 14:33:11', NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-04-02 19:04:24', '2021-04-02 20:03:11', 'C', 1, '0.00', '200.00', '0.00', NULL, NULL, 7, 'GJ 05 BW 0646', NULL, NULL, NULL, '0.00', 34, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '200.00', '200.00', 'CS'),
(85, 1005, '13965', 125, 'Arpit Parcel Service\r\nA-16 Laxmi Narayan Industrial estate, Bhestan-jiav Rd, behind laxmi Narayan Temple, Udhna, Surat. 394210', 'Ankit enterprise\r\nNear, St\'Xaviers High School', '21.156432556915945', '72.84626244919203', '21.17649854133468', '72.78605461120605', 'Arpit Parcel Service', '9825215451', 'Arpit Parcel Service', 'Hirenbhai', '9824159029', '', '0.00', '0.00', '0.00', '200.00', '200.00', 4, '24648.00', '0.00', '0.00', '0.00', 7, '2021-04-02 17:08:25', '2021-04-02 17:48:18', NULL, NULL, '2021-04-02 18:36:58', '2021-04-02 18:36:58', NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-04-02 22:38:14', '2021-04-03 00:06:58', 'C', 1, '620.00', '120.00', '80.00', NULL, NULL, 7, 'GJ 05 BW 0646', NULL, NULL, NULL, '0.00', 35, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '200.00', '200.00', 'CS'),
(86, 1006, '1006', 128, '583/84/85, Annapurna Textile Market, Ring Road, Surat, Surat-395002', 'Raj Textile Tower\r\n1006 Raj Textile Tower, Besides Shree Mahavir Textile Market, Surat.', '21.18921831491858', '72.84009929702026', '21.19273469332693', '72.87448167800903', 'Rajubhai', '9054435985', '', 'Sarang Bhai', '9099007849', '', '0.00', '0.00', '0.00', '1000.80', '276.00', 44, '0.00', '100.00', '0.00', '0.00', 7, '2021-04-05 11:24:23', '2021-04-05 12:36:34', NULL, NULL, '2021-04-05 13:57:12', '2021-04-05 13:57:12', NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-04-02 23:19:02', '2021-04-05 19:27:12', 'C', 1, '0.00', '760.80', '240.00', NULL, NULL, 7, 'GJ 05 BW 0646', NULL, NULL, NULL, '0.00', 38, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '900.80', '900.80', 'CS'),
(87, 1007, '23029678', 122, 'Plot No. A/60/20, Road no.6, Near Ankur Weigh Bridge, Udhna udhyoganagar, Udhna, Surat, Surat-394210', 'AVENUE SUPERMARTS LIMITED\r\nSarathana, D Mart, kamrej Surat.', '21.170465801418736', '72.84854471683502', '21.2316192722628', '72.90405288531144', 'Dipak Bhai', '9724749016', 'Lalji Mulji Transport Co.', 'Avenue Super Marts Ltd. (D Mart)', '9638447757', '', '0.00', '0.00', '0.00', '150.00', '50.00', 1, '7108.00', '0.00', '0.00', '0.00', 6, '2021-04-05 14:56:41', '2021-04-05 15:11:30', NULL, NULL, '2021-04-05 16:07:11', NULL, NULL, 0, NULL, 9, 'D', NULL, NULL, NULL, 0, '2021-04-05 20:24:38', '2021-04-05 22:00:02', 'C', 0, '0.00', '120.00', '30.00', NULL, NULL, 6, 'GJ 05 CT 4131', NULL, NULL, NULL, '0.00', 41, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '150.00', '150.00', 'CS'),
(88, 1008, '23029678jjj', 122, 'Plot No. A/60/20, Road no.6, Near Ankur Weigh Bridge, Udhna udhyoganagar, Udhna, Surat, Surat-394210', 'AVENUE SUPERMARTS LIMITED\r\nSarathana, D Mart, kamrej Surat.', '21.170465801418736', '72.84854471683502', '21.2316192722628', '72.90405288531144', 'Dipak Bhai', '9724749016', 'Lalji Mulji Transport Co.', 'Avenue Super Marts Ltd. (D Mart)', '9638447757', '', '0.00', '0.00', '0.00', '150.00', '50.00', 1, '7108.00', '0.00', '0.00', '0.00', 7, '2021-04-05 18:23:04', '2021-04-08 16:07:36', NULL, NULL, '2021-04-08 16:08:01', NULL, NULL, 0, NULL, 11, 'D', NULL, NULL, NULL, 0, '2021-04-05 18:05:55', '2021-04-08 16:08:01', 'C', 0, '0.00', '120.00', '30.00', NULL, NULL, 7, 'GJ 05 BW 0646', NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '150.00', '150.00', 'CS'),
(89, NULL, '64464', 19, 'Drop  near dee village 123 etc Drop Location', 'Drop  near dee village 123 etc Drop Location', '21.18494396382123', '72.84413892020893', '21.163664671740516', '72.83130017898182', 'Mr Viru Mehta', '7889202021', '', 'Mr Patel Bheem', '7856897845', 'Mr Patel Bheem', '0.00', '0.00', '0.00', '616.00', '620.23', 36, '0.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'OA', NULL, NULL, NULL, 0, '2021-04-06 12:25:32', '2021-04-06 16:47:57', 'C', 0, '5252.06', '616.00', '0.00', NULL, NULL, 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '616.00', '616.00', 'CS'),
(90, NULL, '1212KKK', 19, 'Drop  near dee village 123 etc Drop Location', 'Drop  near dee village 123 etc Drop Location', '21.18494396382123', '72.84413892020893', '21.163664671740516', '72.83130017898182', 'Mr Viru Mehta', '7889202021', '', 'Mr Patel Bheem', '7856897845', 'Mr Patel Bheem', '0.00', '0.00', '0.00', '1007.37', '1204.23', 36, '0.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'OA', NULL, NULL, NULL, 0, '2021-04-06 12:37:19', '2021-04-06 15:27:03', 'C', 0, '5252.06', '910.37', '97.00', NULL, NULL, 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '1007.37', '1007.37', 'CS'),
(91, NULL, 'LPLP909', 19, 'Drop  near dee village 123 etc Drop Location', 'Drop  near dee village 123 etc Drop Location', '21.18494396382123', '72.84413892020893', '21.163664671740516', '72.83130017898182', 'Mr Viru Mehta', '7889202021', '', 'Mr Patel Bheem', '7856897845', 'Mr Patel Bheem', '0.00', '0.00', '0.00', '2244.00', '1204.23', 36, '0.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'OA', NULL, NULL, NULL, 0, '2021-04-06 18:04:36', '2021-04-06 18:08:46', 'C', 0, '5252.06', '1266.00', '978.00', NULL, NULL, 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '2244.00', '2244.00', 'CS'),
(92, NULL, 'KKKKK89', 19, 'Drop  near dee village 123 etc Drop Location', 'Drop  near dee village 123 etc Drop Location', '21.18494396382123', '72.84413892020893', '21.163664671740516', '72.83130017898182', 'Mr Viru Mehta', '7889202021', '', 'Mr Patel Bheem', '7856897845', 'Mr Patel Bheem', '0.00', '0.00', '0.00', '2472.79', '112.00', 36, '0.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'OA', NULL, NULL, NULL, 0, '2021-04-07 11:31:36', '2021-04-07 12:35:25', 'C', 0, '5252.06', '2267.79', '205.00', NULL, NULL, 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '2472.79', '2472.79', 'CS'),
(93, NULL, NULL, 19, 'Drop  near dee village 123 etc Drop Location', 'Drop  near dee village 123 etc Drop Location', '21.18494396382123', '72.84413892020893', '21.163664671740516', '72.83130017898182', 'Mr Viru Mehta', '7889202021', '', 'Mr Patel Bheem', '7856897845', 'Mr Patel Bheem', '0.00', '0.00', '0.00', '0.00', '620.23', 36, '504.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'RO', NULL, NULL, NULL, 0, '2021-04-07 11:43:29', '2021-04-07 11:43:29', 'C', 0, '5252.06', '0.00', '0.00', NULL, NULL, 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 'CS'),
(94, NULL, 'KK89', 19, 'Drop  near dee village 123 etc Drop Location', 'Drop  near dee village 123 etc Drop Location', '21.18494396382123', '72.84413892020893', '21.163664671740516', '72.83130017898182', 'Mr Viru Mehta', '7889202021', '', 'Mr Patel Bheem', '7856897845', 'Mr Patel Bheem', '0.00', '0.00', '0.00', '783.09', '620.23', 36, '0.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'OA', NULL, NULL, NULL, 0, '2021-04-07 12:59:18', '2021-04-07 13:33:29', 'C', 0, '5252.06', '507.79', '275.30', NULL, NULL, 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '783.09', '783.09', 'CS'),
(95, NULL, 'OIUOI', 19, 'Drop  near dee village 123 etc Drop Location', 'Drop  near dee village 123 etc Drop Location', '21.18494396382123', '72.84413892020893', '21.163664671740516', '72.83130017898182', 'Mr Viru Mehta', '7889202021', '', 'Mr Patel Bheem', '7856897845', 'Mr Patel Bheem', '0.00', '0.00', '0.00', '439.70', '620.23', 36, '8493.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'PU', NULL, NULL, NULL, 0, '2021-04-07 14:20:02', '2021-04-07 14:34:54', 'C', 1, '5252.06', '181.70', '258.00', NULL, NULL, 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '439.70', '439.70', 'P'),
(96, 1020, '6089JJJ', 19, 'Drop  near dee village 123 etc Drop Location', 'Drop  near dee village 123 etc Drop Location', '21.18494396382123', '72.84413892020893', '21.163664671740516', '72.83130017898182', 'Mr Viru Mehta', '7889202021', '', 'Mr Patel Bheem', '7856897845', 'Mr Patel Bheem', '0.00', '0.00', '0.00', '1188.00', '564.23', 11, '0.00', '0.00', '0.00', '0.00', 7, '2021-04-08 16:53:03', NULL, NULL, NULL, NULL, '2021-04-08 10:40:07', NULL, 0, NULL, 0, 'A', NULL, NULL, NULL, 0, '2021-04-07 14:41:39', '2021-04-08 16:53:03', 'C', 1, '5252.06', '616.00', '572.00', NULL, NULL, 7, 'GJ 05 BW 0646', NULL, 'pay_GwJ3CfGr4PZIkO', 'RazorPay Order Id: order_GwJ0nxDSF8d3hb', '0.00', 42, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '1188.00', '1188.00', 'P'),
(97, 1009, '97DDDD', 19, 'Drop  near dee village 123 etc Drop Location', 'Drop  near dee village 123 etc Drop Location', '21.18494396382123', '72.84413892020893', '21.163664671740516', '72.83130017898182', 'Mr Viru Mehta', '7889202021', '', 'Mr Patel Bheem', '7856897845', 'Mr Patel Bheem', '0.00', '0.00', '0.00', '433.80', '620.23', 36, '0.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'PU', NULL, NULL, NULL, 0, '2021-04-07 14:45:13', '2021-04-07 14:46:16', 'C', 1, '5252.06', '230.50', '203.30', NULL, NULL, 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '433.80', '433.80', 'P'),
(98, 1010, '98 JJJ', 19, 'Drop  near dee village 123 etc Drop Location', 'Drop  near dee village 123 etc Drop Location', '21.18494396382123', '72.84413892020893', '21.163664671740516', '72.83130017898182', 'Mr Viru Mehta', '7889202021', '', 'Mr Patel Bheem', '7856897845', 'Mr Patel Bheem', '0.00', '0.00', '0.00', '473.60', '620.23', 36, '0.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, '2021-04-07 15:01:25', NULL, 0, NULL, 0, 'PU', NULL, NULL, NULL, 0, '2021-04-07 15:00:13', '2021-04-07 15:01:25', 'C', 1, '5252.06', '281.60', '192.00', NULL, NULL, 0, NULL, NULL, 'pay_Gvyy1t2Z2nUBAv', 'RazorPay Order Id: order_GvyxfHya4pTSKM', '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '473.60', '473.60', 'P'),
(99, 1011, 'UTIUi', 19, 'Drop  near dee village 123 etc Drop Location', 'Drop  near dee village 123 etc Drop Location', '21.18494396382123', '72.84413892020893', '21.163664671740516', '72.83130017898182', 'Mr Viru Mehta', '7889202021', '', 'Mr Patel Bheem', '7856897845', 'Mr Patel Bheem', '0.00', '0.00', '0.00', '183.48', '564.23', 11, '0.00', '0.00', '0.00', '0.00', 6, '2021-05-06 15:01:02', '2021-05-06 16:03:48', NULL, NULL, NULL, '2021-04-07 15:18:04', '2021-05-06 16:08:20', 1, 'Shop Closed', 0, 'U', NULL, NULL, NULL, 0, '2021-04-07 15:09:59', '2021-05-06 16:08:20', 'C', 1, '5252.06', '88.88', '94.60', NULL, NULL, 6, 'GJ 05 CT 4131', NULL, 'pay_GvzFWxWvE68xgB', 'RazorPay Order Id: order_GvzEogOt130uAH', '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '183.48', '183.48', 'P'),
(100, 1013, 'fgd345', 19, 'Drop  near dee village 123 etc Drop Location', 'Drop  near dee village 123 etc Drop Location', '21.18494396382123', '72.84413892020893', '21.163664671740516', '72.83130017898182', 'Mr Viru Mehta', '7889202021', '', 'Mr Patel Bheem', '7856897845', 'Mr Patel Bheem', '0.00', '0.00', '0.00', '165.00', '564.23', 11, '0.00', '0.00', '0.00', '0.00', 6, '2021-05-06 15:00:59', '2021-05-06 15:22:16', NULL, NULL, '2021-05-06 15:22:25', '2021-04-07 15:53:50', NULL, 0, NULL, 0, 'D', NULL, NULL, NULL, 0, '2021-04-07 15:40:11', '2021-05-06 15:22:25', 'C', 1, '5252.06', '66.00', '99.00', NULL, NULL, 6, 'GJ 05 CT 4131', NULL, 'pay_GvzrSrsnXuDg4Q', 'RazorPay Order Id: order_GvaTor6P9J6rPL', '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '165.00', '165.00', 'P'),
(101, 1017, 'fgjvjj', 19, 'Drop  near dee village 123 etc Drop Location', 'Drop  near dee village 123 etc Drop Location', '21.18494396382123', '72.84413892020893', '21.163664671740516', '72.83130017898182', 'Mr Viru Mehta', '7889202021', '', 'Mr Patel Bheem', '7856897845', 'Mr Patel Bheem', '0.00', '0.00', '0.00', '6259.00', '620.23', 36, '0.00', '0.00', '0.00', '0.00', 7, '2021-04-08 16:46:44', NULL, NULL, NULL, NULL, '2021-04-07 17:26:53', NULL, 0, NULL, 0, 'A', NULL, NULL, NULL, 0, '2021-04-07 16:38:15', '2021-04-08 16:46:44', 'C', 0, '5252.06', '6259.00', '0.00', NULL, NULL, 7, 'GJ 05 BW 0646', NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '6259.00', '6259.00', 'CS'),
(102, 1015, '435345', 19, 'Drop  near dee village 123 etc Drop Location', 'Drop  near dee village 123 etc Drop Location', '21.18494396382123', '72.84413892020893', '21.163664671740516', '72.83130017898182', 'Mr Viru Mehta', '7889202021', '', 'Mr Patel Bheem', '7856897845', 'Mr Patel Bheem', '0.00', '0.00', '0.00', '2225.00', '620.23', 36, '0.00', '0.00', '0.00', '0.00', 6, '2021-05-06 15:00:47', '2021-05-06 15:05:39', NULL, NULL, '2021-05-06 15:08:03', '2021-04-07 16:55:34', NULL, 0, NULL, 0, 'D', NULL, NULL, NULL, 0, '2021-04-07 16:39:50', '2021-05-06 15:08:03', 'C', 0, '5252.06', '2225.00', '0.00', NULL, NULL, 6, 'GJ 05 CT 4131', NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '2225.00', '2225.00', 'CS'),
(103, 1016, 'dfhfg', 19, 'Drop  near dee village 123 etc Drop Location', 'Drop  near dee village 123 etc Drop Location', '21.18494396382123', '72.84413892020893', '21.163664671740516', '72.83130017898182', 'Mr Viru Mehta', '7889202021', '', 'Mr Patel Bheem', '7856897845', 'Mr Patel Bheem', '0.00', '0.00', '0.00', '66.00', '620.23', 36, '0.00', '0.00', '0.00', '0.00', 7, '2021-04-08 11:30:42', '2021-04-08 16:07:31', NULL, NULL, '2021-04-08 16:07:52', '2021-04-07 17:08:55', NULL, 0, NULL, 0, 'D', NULL, NULL, NULL, 0, '2021-04-07 16:55:32', '2021-04-08 16:07:52', 'C', 1, '5252.06', '66.00', '0.00', NULL, NULL, 7, 'GJ 05 BW 0646', NULL, NULL, NULL, '0.00', NULL, 'PPT', '89.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '66.00', '66.00', 'CS'),
(104, NULL, 'kk768678', 19, 'Drop  near dee village 123 etc Drop Location', 'Drop  near dee village 123 etc Drop Location', '21.18494396382123', '72.84413892020893', '21.163664671740516', '72.83130017898182', 'Mr Viru Mehta', '7889202021', '', 'Mr Patel Bheem', '7856897845', 'Mr Patel Bheem', '0.00', '0.00', '0.00', '814.50', '620.23', 36, '0.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'OA', NULL, NULL, NULL, 0, '2021-04-07 17:31:57', '2021-04-07 17:43:49', 'C', 0, '5252.06', '242.00', '572.50', NULL, NULL, 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '814.50', '814.50', 'CS'),
(105, 1019, 'fgdh', 19, 'Drop  near dee village 123 etc Drop Location', 'Drop  near dee village 123 etc Drop Location', '21.18494396382123', '72.84413892020893', '21.163664671740516', '72.83130017898182', 'Mr Viru Mehta', '7889202021', '', 'Mr Patel Bheem', '7856897845', 'Mr Patel Bheem', '0.00', '0.00', '0.00', '225.00', '620.23', 36, '0.00', '0.00', '0.00', '0.00', 7, '2021-05-03 17:05:18', NULL, NULL, NULL, NULL, '2021-04-07 18:12:23', NULL, 0, NULL, 0, 'A', NULL, NULL, NULL, 0, '2021-04-07 18:00:20', '2021-05-03 17:05:18', 'C', 1, '5252.06', '225.00', '0.00', NULL, NULL, 7, 'GJ 05 BW 0646', NULL, 'pay_Gw2Dn0VPPOpXz9', 'RazorPay Order Id: order_GvdQp9QFJux0Pe', '0.00', 42, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '225.00', '225.00', 'P'),
(106, 1031, '202105060001', 19, 'Drop  near dee village 123 etc Drop Location', 'Drop  near dee village 123 etc Drop Location', '21.240633519741884', '72.94027070057486', '21.001530528414694', '72.99730648820949', 'Mr Viru Mehta', '7889202021', '', 'Mr Patel Bheem', '7856897845', 'Mr Patel Bheem', '0.00', '0.00', '0.00', '616.00', '564.23', 11, '0.00', '0.00', '0.00', '0.00', 6, '2021-05-06 12:59:09', '2021-05-06 12:59:30', NULL, NULL, '2021-05-06 13:00:31', '2021-04-08 15:33:24', NULL, 0, NULL, 0, 'D', NULL, NULL, NULL, 0, '2021-04-08 11:03:44', '2021-05-06 13:00:31', 'C', 1, '5252.06', '616.00', '0.00', NULL, NULL, 6, 'GJ 05 CT 4131', NULL, 'pay_GwO31GkJTrMlBf', 'RazorPay Order Id: order_GwO2RNAU8mcM3q', '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '616.00', '616.00', 'P'),
(157, 1042, ' fgh', 1, 'Drop  near dee village 123 etc Drop Location', 'Drop  near dee village 123 etc Drop Location', '21.18494396382123', '72.84413892020893', '21.163664671740516', '72.83130017898182', 'Mr Viru Mehta', '7889202021', '', 'Mr Patel Bheem', '7856897845', 'Mr Patel Bheem', '0.00', '0.00', '0.00', '649.00', '620.23', 36, '0.00', '0.00', '0.00', '0.00', 6, '2021-04-09 16:47:01', NULL, NULL, NULL, NULL, '2021-04-09 16:36:03', NULL, 0, NULL, 0, 'A', NULL, NULL, NULL, 0, '2021-04-09 16:35:11', '2021-04-09 16:47:01', 'C', 1, '5252.06', '649.00', '0.00', NULL, NULL, 6, 'GJ 05 CT 4131', NULL, 'pay_GwneIF8KAJZsy6', 'RazorPay Order Id: order_Gwndidl9QxOE3N', '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '649.00', '649.00', 'P'),
(108, 1023, '202105060002', 19, 'Drop  near dee village 123 etc Drop Location', 'Drop  near dee village 123 etc Drop Location', '21.18233095641593', '72.91969299316406', '20.99255580736955', '73.13120236223293', 'Mr Viru Mehta', '7889202021', '', 'Mr Patel Bheem', '7856897845', 'Mr Patel Bheem', '0.00', '0.00', '0.00', '2378.00', '620.23', 36, '0.00', '0.00', '0.00', '0.00', 6, '2021-05-06 12:59:14', '2021-05-06 14:03:44', NULL, NULL, NULL, '2021-04-08 12:26:51', NULL, 0, NULL, 0, 'PP', NULL, NULL, NULL, 0, '2021-04-08 11:04:35', '2021-05-06 14:03:44', 'C', 1, '5252.06', '2378.00', '0.00', NULL, NULL, 6, 'GJ 05 CT 4131', NULL, 'pay_GwKrxIK6K4h1RY', 'RazorPay Order Id: order_GwKrgwJzNwYqBC', '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '2378.00', '2378.00', 'P'),
(109, 1022, 'oppo8', 19, 'Drop  near dee village 123 etc Drop Location', 'Drop  near dee village 123 etc Drop Location', '21.18494396382123', '72.84413892020893', '21.163664671740516', '72.83130017898182', 'Mr Viru Mehta', '7889202021', '', 'Mr Patel Bheem', '7856897845', 'Mr Patel Bheem', '0.00', '0.00', '0.00', '253.00', '620.23', 36, '0.00', '0.00', '0.00', '0.00', 6, '2021-05-06 15:00:46', '2021-05-06 15:01:40', NULL, NULL, '2021-05-06 15:01:48', '2021-04-08 12:25:11', NULL, 0, NULL, 0, 'D', NULL, NULL, NULL, 0, '2021-04-08 11:37:04', '2021-05-06 15:01:48', 'C', 1, '5252.06', '253.00', '0.00', NULL, NULL, 6, 'GJ 05 CT 4131', NULL, 'pay_GwKq7Uhh4H4LtL', 'RazorPay Order Id: order_GwKolpk0Y3AuFK', '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '253.00', '253.00', 'P'),
(151, 1038, NULL, 1, 'fgf', 'fgf', '21.184943963821', '72.844138920209', '21.163664671741', '72.831300178982', 'denisha patel', '09987675655', 'denisha', 'fdfd', '9854125632', 'fdfd', '0.00', '0.00', '0.00', '684.00', '132.00', 12, '0.00', '0.00', '0.00', '0.00', 6, '2021-05-03 18:22:59', NULL, NULL, NULL, NULL, '2021-04-09 13:59:39', NULL, 0, NULL, 0, 'A', NULL, NULL, NULL, 0, '2021-04-09 13:58:23', '2021-05-03 18:22:59', 'C', 1, '200.00', '144.00', '540.00', NULL, NULL, 6, 'GJ 05 CT 4131', NULL, 'pay_Gwkz5ao7GR4zIC', 'RazorPay Order Id: order_GwkyKu5n3AarHg', '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '684.00', '684.00', 'P'),
(152, 1039, NULL, 1, 'cdd', 'cdd', '21.184943963821', '72.844138920209', '21.163664671741', '72.831300178982', 'asd', '09978004699', 'denisha patel', 'sdsjg', '9754824563', 'sdsjg', '0.00', '0.00', '0.00', '770.00', '132.00', 11, '0.00', '0.00', '0.00', '0.00', 6, '2021-05-03 18:22:58', '2021-05-03 18:27:42', NULL, NULL, NULL, '2021-04-09 14:03:44', '2021-05-03 18:27:51', 1, 'Shop Closed', 0, 'U', NULL, NULL, NULL, 0, '2021-04-09 14:01:25', '2021-05-03 18:27:51', 'C', 1, '120.00', '550.00', '220.00', NULL, NULL, 6, 'GJ 05 CT 4131', NULL, 'pay_Gwl3Ot8e7fkuo5', 'RazorPay Order Id: order_Gwl30EtI37Xts7', '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '770.00', '770.00', 'P'),
(153, 1040, NULL, 1, 'surat', 'surat', '21.184943963821', '72.844138920209', '21.163664671741', '72.831300178982', 'denisha', '09987675655', 'denisha', 'fsddfds', '0000000000', 'fsddfds', '0.00', '0.00', '0.00', '840.00', '144.00', 12, '0.00', '0.00', '0.00', '0.00', 6, '2021-05-03 17:32:56', '2021-05-03 18:20:59', NULL, NULL, NULL, '2021-04-09 15:06:30', '2021-05-03 18:21:20', 1, 'Shop Closed', 0, 'U', NULL, NULL, NULL, 0, '2021-04-09 14:16:16', '2021-05-03 18:21:20', 'C', 1, '200.00', '240.00', '600.00', NULL, NULL, 6, 'GJ 05 CT 4131', NULL, 'pay_Gwm7fvkQf6j6Zg', 'RazorPay Order Id: order_GwlQYbpEEN9cKt', '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '840.00', '840.00', 'P'),
(154, 1041, NULL, 1, 'Drop  near dee village 123 etc Drop Location', 'Drop  near dee village 123 etc Drop Location', '21.18494396382123', '72.84413892020893', '21.163664671740516', '72.83130017898182', 'Mr Viru Mehta', '7889202021', '', 'Mr Patel Bheem', '7856897845', 'Mr Patel Bheem', '0.00', '0.00', '0.00', '616.00', '564.23', 11, '0.00', '0.00', '0.00', '0.00', 7, '2021-04-09 17:10:57', NULL, NULL, NULL, NULL, '2021-04-09 16:30:32', NULL, 0, NULL, 0, 'A', NULL, NULL, NULL, 0, '2021-04-09 15:04:55', '2021-04-09 17:10:57', 'C', 1, '5252.06', '616.00', '0.00', NULL, NULL, 7, 'GJ 05 BW 0646', NULL, 'pay_GwnYSXINKFKcNG', 'RazorPay Order Id: order_Gwm6TcrM1FzmhB', '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '616.00', '616.00', 'P'),
(155, 1043, 'rg hgf hf', 1, 'Drop  near dee village 123 etc Drop Location', 'Drop  near dee village 123 etc Drop Location', '21.18494396382123', '72.84413892020893', '21.163664671740516', '72.83130017898182', 'Mr Viru Mehta', '7889202021', '', 'Mr Patel Bheem', '7856897845', 'Mr Patel Bheem', '0.00', '0.00', '0.00', '649.00', '564.23', 11, '0.00', '0.00', '0.00', '0.00', 6, '2021-04-15 16:42:49', NULL, NULL, NULL, NULL, '2021-04-29 00:00:00', NULL, 0, NULL, 0, 'A', NULL, NULL, NULL, 0, '2021-04-09 16:22:55', '2021-04-29 14:36:28', 'C', 1, '5252.06', '649.00', '0.00', NULL, NULL, 6, 'GJ 05 CT 4131', NULL, NULL, NULL, '100.00', 44, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '649.00', '649.00', 'NEFT'),
(156, NULL, NULL, 1, 'Drop  near dee village 123 etc Drop Location', 'Drop  near dee village 123 etc Drop Location', '21.18494396382123', '72.84413892020893', '21.163664671740516', '72.83130017898182', 'Mr Viru Mehta', '7889202021', '', 'Mr Patel Bheem', '7856897845', 'Mr Patel Bheem', '0.00', '0.00', '0.00', '979.00', '564.23', 11, '0.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'OA', NULL, NULL, NULL, 0, '2021-04-09 16:24:23', '2021-04-09 16:24:37', 'C', 0, '5252.06', '979.00', '0.00', NULL, NULL, 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '979.00', '979.00', 'CS'),
(119, 1034, '23029678jjj', 122, 'Plot No. A/60/20, Road no.6, Near Ankur Weigh Bridge, Udhna udhyoganagar, Udhna, Surat, Surat-394210', 'AVENUE SUPERMARTS LIMITED\r\nSarathana, D Mart, kamrej Surat.', '21.170465801418736', '72.84854471683502', '21.2316192722628', '72.90405288531144', 'Dipak Bhai', '9724749016', 'Lalji Mulji Transport Co.', 'Avenue Super Marts Ltd. (D Mart)', '9638447757', '', '0.00', '0.00', '0.00', '150.00', '50.00', 1, '7108.00', '0.00', '0.00', '0.00', 7, '2021-04-08 16:08:13', '2021-04-08 16:09:48', NULL, NULL, '2021-04-08 16:12:45', NULL, NULL, 0, NULL, 11, 'D', NULL, NULL, NULL, 0, '2021-04-08 16:06:08', '2021-04-08 16:12:45', 'C', 0, '0.00', '120.00', '30.00', NULL, NULL, 7, 'GJ 05 BW 0646', NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '150.00', '150.00', 'CS'),
(120, 1035, '23029678jjj', 122, 'Plot No. A/60/20, Road no.6, Near Ankur Weigh Bridge, Udhna udhyoganagar, Udhna, Surat, Surat-394210', 'AVENUE SUPERMARTS LIMITED\r\nSarathana, D Mart, kamrej Surat.', '21.170465801418736', '72.84854471683502', '21.2316192722628', '72.90405288531144', 'Dipak Bhai', '9724749016', 'Lalji Mulji Transport Co.', 'Avenue Super Marts Ltd. (D Mart)', '9638447757', '', '0.00', '0.00', '0.00', '260.00', '50.00', 1, '7108.00', '0.00', '0.00', '0.00', 7, '2021-04-08 16:08:28', '2021-04-08 16:08:58', NULL, NULL, '2021-04-08 16:12:40', '2021-04-29 00:00:00', NULL, 0, NULL, 11, 'D', NULL, NULL, NULL, 0, '2021-04-08 16:06:29', '2021-04-29 13:02:18', 'C', 1, '0.00', '110.00', '150.00', NULL, NULL, 7, 'GJ 05 BW 0646', NULL, '546456', NULL, '554.00', 43, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '-294.00', '-294.00', 'CS'),
(121, 1036, '23029678jjj', 122, 'Plot No. A/60/20, Road no.6, Near Ankur Weigh Bridge, Udhna udhyoganagar, Udhna, Surat, Surat-394210', 'AVENUE SUPERMARTS LIMITED\r\nSarathana, D Mart, kamrej Surat.', '21.170465801418736', '72.84854471683502', '21.2316192722628', '72.90405288531144', 'Dipak Bhai', '9724749016', 'Lalji Mulji Transport Co.', 'Avenue Super Marts Ltd. (D Mart)', '9638447757', '', '0.00', '0.00', '0.00', '260.00', '50.00', 1, '7108.00', '0.00', '0.00', '0.00', 5, '2021-05-03 17:05:51', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 11, 'A', NULL, NULL, NULL, 0, '2021-04-08 16:12:57', '2021-05-03 17:05:51', 'C', 0, '0.00', '110.00', '150.00', NULL, NULL, 5, 'GJ 05 AU 3104', NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '260.00', '260.00', 'CS'),
(122, 1037, '23029678jjj', 122, 'Plot No. A/60/20, Road no.6, Near Ankur Weigh Bridge, Udhna udhyoganagar, Udhna, Surat, Surat-394210', 'AVENUE SUPERMARTS LIMITED\r\nSarathana, D Mart, kamrej Surat.', '21.170465801418736', '72.84854471683502', '21.2316192722628', '72.90405288531144', 'Dipak Bhai', '9724749016', 'Lalji Mulji Transport Co.', 'Avenue Super Marts Ltd. (D Mart)', '9638447757', '', '0.00', '0.00', '0.00', '260.00', '50.00', 1, '7108.00', '0.00', '0.00', '0.00', 7, '2021-04-08 16:52:29', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 11, 'A', NULL, NULL, NULL, 0, '2021-04-08 16:13:07', '2021-04-08 16:52:29', 'C', 0, '0.00', '110.00', '150.00', NULL, NULL, 7, 'GJ 05 BW 0646', NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '260.00', '260.00', 'CS'),
(158, NULL, 'kghkj', 1, 'Drop  near dee village 123 etc Drop Location', 'Drop  near dee village 123 etc Drop Location', '21.18494396382123', '72.84413892020893', '21.163664671740516', '72.83130017898182', 'Mr Viru Mehta', '7889202021', '', 'Mr Patel Bheem', '7856897845', 'Mr Patel Bheem', '0.00', '0.00', '0.00', '2530.00', '45.00', 5, '456.00', '2066.14', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'OA', NULL, NULL, NULL, 0, '2021-04-07 11:43:29', '2021-04-17 18:38:10', 'C', 0, '5252.06', '1250.00', '1280.00', NULL, NULL, 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', 1, '19.62', 'NEW30', 6, '92.09', '0.00', '0.00', '463.86', '463.86', 'CS'),
(159, NULL, NULL, 122, 'XYZ road at the rate 456', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', NULL, 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '124000.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'RO', NULL, NULL, NULL, 0, '2021-04-19 12:24:21', '2021-04-19 12:24:21', 'C', 0, '12500.00', '0.00', '0.00', NULL, NULL, 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 'CS'),
(160, NULL, NULL, 122, 'XYZ road at the rate 456', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', NULL, 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '0.00', '150.00', 3, '124000.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'RO', NULL, NULL, NULL, 0, '2021-04-19 12:25:27', '2021-04-19 12:25:27', 'C', 0, '12500.00', '0.00', '0.00', NULL, NULL, 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 'CS'),
(161, NULL, NULL, 122, 'XYZ road at the rate 456', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', NULL, 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '124000.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'RO', NULL, NULL, NULL, 0, '2021-04-19 12:28:49', '2021-04-19 12:28:49', 'C', 0, '12500.00', '0.00', '0.00', NULL, NULL, 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 'CS'),
(162, NULL, NULL, 122, 'XYZ road at the rate 456', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', NULL, 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '124000.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'RO', NULL, NULL, NULL, 0, '2021-04-19 12:29:55', '2021-04-19 12:29:55', 'C', 0, '12500.00', '0.00', '0.00', NULL, NULL, 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 'CS'),
(163, NULL, NULL, 122, 'XYZ road at the rate 456', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', NULL, 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '124000.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'RO', NULL, NULL, NULL, 0, '2021-04-19 12:31:15', '2021-04-19 12:31:15', 'C', 0, '12500.00', '0.00', '0.00', NULL, NULL, 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 'CS'),
(164, NULL, NULL, 122, 'XYZ road at the rate 456', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', NULL, 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '124000.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'RO', NULL, NULL, NULL, 0, '2021-04-19 12:31:35', '2021-04-19 12:31:35', 'C', 0, '12500.00', '0.00', '0.00', NULL, NULL, 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 'CS'),
(165, NULL, NULL, 122, 'XYZ road at the rate 456', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '0.00', '150.00', 3, '124000.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'RO', NULL, NULL, NULL, 0, '2021-04-19 12:41:30', '2021-04-19 12:41:30', 'C', 0, '12500.00', '0.00', '0.00', 'hj', 'ko', 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 'CS'),
(166, NULL, NULL, 122, 'XYZ road at the rate 456', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '0.00', '150.00', 3, '124000.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'RO', NULL, NULL, NULL, 0, '2021-04-19 12:53:29', '2021-04-19 12:53:29', 'C', 0, '12500.00', '0.00', '0.00', 'hj', 'ko', 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 'CS'),
(167, NULL, NULL, 122, 'XYZ road at the rate 456', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '0.00', '150.00', 3, '124000.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'RO', NULL, NULL, NULL, 0, '2021-04-19 12:56:04', '2021-04-19 12:56:04', 'C', 0, '12500.00', '0.00', '0.00', 'hj', 'ko', 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 'CS');
INSERT INTO `tbl_orders` (`id`, `bigdaddy_lr_number`, `transporter_lr_number`, `user_id`, `pickup_location`, `drop_location`, `pickup_latitude`, `pickup_longitude`, `drop_latitude`, `drop_longitude`, `contact_person_name`, `contact_person_phone_number`, `transporter_name`, `contact_person_name_drop`, `contact_person_phone_number_drop`, `transporter_name_drop`, `goods_height`, `goods_width`, `goods_length`, `final_cost`, `total_weight`, `total_no_of_parcel`, `customer_estimation_asset_value`, `discount`, `min_order_value_charge`, `redeliver_charge`, `driver_id`, `driver_assigned_datetime`, `pickedup_datetime`, `cancelled_datetime`, `if_cancelled_reason_text`, `delivered_datetime`, `payment_datetime`, `undelivered_datetime`, `if_undelivered_reason_id`, `if_undelivered_reason_text`, `order_created_by`, `status`, `lr_img`, `pickup_img`, `deliver_img`, `is_active`, `created_at`, `updated_at`, `payment_type`, `payment_status`, `transport_cost`, `tempo_charge`, `service_charge`, `other_field_pickup`, `other_field_drop`, `vehicle_id`, `vehicle_no`, `if_cheque_number`, `if_transaction_number`, `payment_comment`, `payment_discount`, `invoice_id`, `order_driver_trip_type`, `order_driver_trip_amount`, `subscription_purchase_id`, `subscription_benefit_amount`, `coupon_code_applied`, `coupon_code_id`, `coupon_benefit_amount`, `wallet_amount_used`, `prepaid_amount_used`, `cod_amount_used`, `total_payable_amount`, `payment_type_manual`) VALUES
(168, NULL, NULL, 122, 'XYZ road at the rate 456', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '0.00', '150.00', 3, '124000.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'RO', NULL, NULL, NULL, 0, '2021-04-19 12:57:18', '2021-04-19 12:57:18', 'C', 0, '12500.00', '0.00', '0.00', 'hj', 'ko', 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 'CS'),
(169, 1044, 'NMN', 122, 'XYZ road at the rate 456', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '765.00', '150.00', 9, '2389.00', '170.69', '0.00', '0.00', 6, '2021-05-03 18:22:04', '2021-05-03 18:23:14', NULL, NULL, NULL, NULL, '2021-05-03 18:23:28', 1, 'Shop Closed', 0, 'U', NULL, NULL, NULL, 0, '2021-04-19 12:58:55', '2021-05-03 18:23:28', 'C', 0, '12500.00', '234.00', '531.00', 'hj', 'ko', 6, 'GJ 05 CT 4131', NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', 2, '100.00', 'NEW30', 6, '70.69', '0.00', '0.00', '594.31', '594.31', 'CS'),
(170, NULL, NULL, 122, 'XYZ road at the rate 456', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '0.00', '150.00', 3, '124000.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'RO', NULL, NULL, NULL, 0, '2021-04-19 13:01:38', '2021-04-19 13:01:38', 'C', 0, '12500.00', '0.00', '0.00', 'hj', 'ko', 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 'CS'),
(171, 1045, 'NNNNNN1', 122, 'XYZ road at the rate 456', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '2370.00', '150.00', 6, '4564.00', '0.00', '0.00', '0.00', 6, '2021-05-03 18:22:03', '2021-05-03 18:22:22', NULL, NULL, '2021-05-03 18:22:30', NULL, NULL, 0, NULL, 0, 'D', NULL, NULL, NULL, 0, '2021-04-19 17:35:27', '2021-05-03 18:22:30', 'C', 0, '12500.00', '954.00', '1416.00', 'hj', 'ko', 6, 'GJ 05 CT 4131', NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '111.00', '0.00', '2370.00', '2370.00', 'CS'),
(172, NULL, 'axc', 122, 'XYZ road at the rate 456', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '3696.00', '600.00', 12, '0.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'OA', NULL, NULL, NULL, 0, '2021-04-19 17:51:23', '2021-04-19 17:53:54', 'C', 0, '12500.00', '2436.00', '1260.00', 'hj', 'ko', 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '3696.00', '3696.00', 'CS'),
(173, NULL, 'dfdghf', 122, 'XYZ road at the rate 456', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '0.00', '150.00', 6, '0.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'OA', NULL, NULL, NULL, 0, '2021-04-19 17:55:25', '2021-04-19 17:55:25', 'C', 0, '12500.00', '0.00', '0.00', 'hj', 'ko', 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 'CS'),
(174, NULL, NULL, 122, 'XYZ road at the rate 456', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '0.00', '150.00', 6, '124000.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'RO', NULL, NULL, NULL, 0, '2021-04-19 17:57:09', '2021-04-19 17:57:09', 'C', 0, '12500.00', '0.00', '0.00', 'hj', 'ko', 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 'CS'),
(175, 1046, 'cdddddd', 122, 'XYZ road at the rate 456', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '4920.00', '150.00', 6, '0.00', '0.00', '0.00', '0.00', 5, '2021-05-03 17:39:36', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'A', NULL, NULL, NULL, 0, '2021-04-19 18:01:22', '2021-05-03 17:39:36', 'C', 0, '12500.00', '3924.00', '996.00', 'hj', 'ko', 5, 'GJ 05 AU 3104', NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '49.00', '0.00', '4920.00', '4920.00', 'CS'),
(176, NULL, NULL, 122, 'XYZ road at the rate 456', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '0.00', '150.00', 6, '124000.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'RO', NULL, NULL, NULL, 0, '2021-04-19 18:04:45', '2021-04-19 18:04:45', 'C', 0, '12500.00', '0.00', '0.00', 'hj', 'ko', 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 'CS'),
(177, NULL, NULL, 122, 'XYZ road at the rate 456', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '0.00', '150.00', 6, '124000.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'RO', NULL, NULL, NULL, 0, '2021-04-19 18:05:51', '2021-04-19 18:05:51', 'C', 0, '12500.00', '0.00', '0.00', 'hj', 'ko', 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 'CS'),
(179, NULL, NULL, 122, 'XYZ road at the rate 456', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '0.00', '150.00', 6, '124000.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'RO', NULL, NULL, NULL, 0, '2021-04-19 18:08:33', '2021-04-19 18:08:33', 'C', 0, '12500.00', '0.00', '0.00', 'hj', 'ko', 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 'CS'),
(180, 1047, 'nmnmnmnmnm1', 122, 'XYZ road at the rate 456', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '10284.00', '150.00', 6, '0.00', '282.00', '0.00', '0.00', 6, '2021-05-03 17:01:05', '2021-05-03 17:33:40', NULL, NULL, '2021-05-03 18:03:51', NULL, NULL, 0, NULL, 0, 'D', NULL, NULL, NULL, 0, '2021-04-19 18:13:28', '2021-05-03 18:03:51', 'C', 0, '12500.00', '2736.00', '7548.00', 'hj', 'ko', 6, 'GJ 05 CT 4131', NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', 2, '100.00', '8F60HTY2GJ', 7, '150.00', '112.00', '0.00', '10002.00', '10002.00', 'CS'),
(181, NULL, NULL, 122, 'XYZ road at the rate 456', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '0.00', '150.00', 6, '124000.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'RO', NULL, NULL, NULL, 0, '2021-04-19 18:17:14', '2021-04-19 18:17:14', 'C', 0, '12500.00', '0.00', '0.00', 'hj', 'ko', 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 'CS'),
(182, NULL, 'opopop11', 122, 'XYZ road at the rate 456', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '840.00', '150.00', 6, '0.00', '736.34', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'OA', NULL, NULL, NULL, 0, '2021-04-19 18:40:20', '2021-04-19 18:51:10', 'C', 0, '12500.00', '720.00', '120.00', 'hj', 'ko', 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', '8F60HTY2GJ', 7, '19.75', '0.00', '0.00', '103.66', '103.66', 'CS'),
(183, NULL, NULL, 122, 'XYZ road at the rate 456', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '36000.00', '150.00', 6, '0.00', '850.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'OA', NULL, NULL, NULL, 0, '2021-04-19 18:52:08', '2021-04-19 18:57:56', 'C', 0, '12500.00', '6000.00', '30000.00', 'hj', 'ko', 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', 2, '100.00', '8F60HTY2GJ', 7, '150.00', '0.00', '0.00', '35150.00', '35150.00', 'CS'),
(184, 1048, 'fg1111', 122, 'XYZ road at the rate 456', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '2328.00', '150.00', 6, '0.00', '0.00', '0.00', '0.00', 6, '2021-05-03 17:01:04', '2021-05-03 17:12:40', NULL, NULL, '2021-05-03 17:13:07', NULL, NULL, 0, NULL, 0, 'D', NULL, NULL, NULL, 0, '2021-04-20 12:49:38', '2021-05-03 17:13:07', 'C', 0, '12500.00', '1578.00', '750.00', 'hj', 'ko', 6, 'GJ 05 CT 4131', NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '37.00', '0.00', '2328.00', '2328.00', 'CS'),
(185, 1049, NULL, 122, 'XYZ road at the rate 456', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '1968.00', '150.00', 6, '0.00', '0.00', '0.00', '0.00', 6, '2021-05-03 16:52:56', '2021-05-03 17:08:27', NULL, NULL, '2021-05-03 17:09:21', NULL, NULL, 0, NULL, 0, 'D', NULL, NULL, NULL, 0, '2021-04-20 12:59:06', '2021-05-03 17:09:21', 'W', 1, '12500.00', '750.00', '1218.00', 'hj', 'ko', 6, 'GJ 05 CT 4131', NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '1968.00', '0.00', '1968.00', '1968.00', 'CARD'),
(186, NULL, NULL, 122, 'XYZ road at the rate 456', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '124000.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'RO', NULL, NULL, NULL, 0, '2021-04-20 13:05:26', '2021-04-20 13:05:26', 'C', 0, '12500.00', '0.00', '0.00', 'hj', 'ko', 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 'CS'),
(187, NULL, NULL, 122, 'XYZ road at the rate 456', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '124000.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'RO', NULL, NULL, NULL, 0, '2021-04-20 13:06:41', '2021-04-20 13:06:41', 'C', 0, '12500.00', '0.00', '0.00', 'hj', 'ko', 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 'CS'),
(188, NULL, NULL, 122, 'XYZ road at the rate 456', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '124000.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'RO', NULL, NULL, NULL, 0, '2021-04-20 13:07:43', '2021-04-20 13:07:43', 'C', 0, '12500.00', '0.00', '0.00', 'hj', 'ko', 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 'CS'),
(189, NULL, NULL, 122, 'XYZ road at the rate 456', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '0.00', '0.00', 0, '124000.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'RO', NULL, NULL, NULL, 0, '2021-04-20 14:45:56', '2021-04-20 14:45:56', 'C', 0, '12500.00', '0.00', '0.00', 'hj', 'ko', 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 'CS'),
(190, NULL, NULL, 122, 'XYZ road at the rate 456', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '2916.00', '150.00', 6, '0.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'OA', NULL, NULL, NULL, 0, '2021-04-20 14:48:05', '2021-04-20 14:48:30', 'C', 0, '12500.00', '1500.00', '1416.00', 'hj', 'ko', 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '2916.00', '2916.00', 'CS'),
(191, 1050, '999999', 122, 'XYZ road at the rate 456', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '1020.00', '150.00', 6, '0.00', '0.00', '0.00', '0.00', 6, '2021-05-03 15:36:24', NULL, NULL, NULL, NULL, '2021-04-20 17:41:05', NULL, 0, NULL, 0, 'A', NULL, NULL, NULL, 0, '2021-04-20 17:39:24', '2021-05-03 15:36:24', 'W', 1, '12500.00', '900.00', '120.00', 'hj', 'ko', 6, 'GJ 05 CT 4131', NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '1020.00', '0.00', '1020.00', '1020.00', 'CARD'),
(192, 1051, 'tr120', 122, 'XYZ road at the rate 456', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '5740.00', '46.00', 18, '13.00', '700.00', '0.00', '0.00', 6, '2021-05-01 12:54:16', NULL, NULL, NULL, NULL, '2021-04-20 18:02:45', NULL, 0, NULL, 0, 'A', NULL, NULL, NULL, 0, '2021-04-20 17:58:07', '2021-05-01 12:54:17', 'W', 1, '12500.00', '2820.00', '2920.00', 'hj', 'ko', 6, 'GJ 05 CT 4131', NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', 2, '100.00', '9ZP494IBRS', 8, '600.00', '5040.00', '0.00', '5040.00', '5040.00', 'W'),
(193, NULL, NULL, 122, 'XYZ road at the rate 456', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '0.00', '150.00', 6, '124000.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'RO', NULL, NULL, NULL, 0, '2021-04-22 12:09:16', '2021-04-22 12:09:17', 'C', 0, '12500.00', '0.00', '0.00', 'hj', 'ko', 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 'CS'),
(194, NULL, NULL, 122, 'XYZ road at the rate 456', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '0.00', '150.00', 6, '124000.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'RO', NULL, NULL, NULL, 0, '2021-04-22 12:13:05', '2021-04-22 12:13:05', 'C', 0, '12500.00', '0.00', '0.00', 'hj', 'ko', 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 'CS'),
(195, NULL, 'nnnn195', 122, 'XYZ road at the rate 456', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '927.00', '375.00', 3, '0.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'OA', NULL, NULL, NULL, 0, '2021-04-22 12:27:54', '2021-05-03 10:39:18', 'C', 0, '12500.00', '609.00', '318.00', 'hj', 'ko', 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '750.87', '927.00', 'CS'),
(196, NULL, NULL, 122, 'ABC road at the rate 123', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '0.00', '150.00', 6, '124000.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'RO', NULL, NULL, NULL, 0, '2021-04-23 11:12:17', '2021-04-23 11:12:17', 'C', 0, '12500.00', '0.00', '0.00', 'hj', 'ko', 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 'CS'),
(197, NULL, NULL, 122, 'ABC road at the rate 123', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '1188.00', '150.00', 6, '0.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'OA', NULL, NULL, NULL, 0, '2021-04-23 11:41:43', '2021-04-23 11:42:03', 'C', 0, '12500.00', '996.00', '192.00', 'hj', 'ko', 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '1188.00', '1188.00', 'CS'),
(198, 1052, 'jkj8989', 122, 'ABC road at the rate 123', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '1188.00', '150.00', 6, '0.00', '0.00', '0.00', '0.00', 6, '2021-04-30 18:29:17', '2021-05-03 15:47:41', NULL, NULL, NULL, NULL, NULL, 0, NULL, 11, 'PP', NULL, NULL, NULL, 0, '2021-04-23 17:04:51', '2021-05-03 15:47:41', 'C', 0, '12500.00', '996.00', '192.00', 'hj', 'ko', 6, 'GJ 05 CT 4131', NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '1188.00', '1188.00', 'CS'),
(199, 1053, 'kl00909', 122, 'ABC road at the rate 123', 'XYZ road at the rate 456', '21.177567957913165', '72.83403589729055', '21.187057974171225', '72.9691510999815', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '930.00', '120.00', 6, '0.00', '0.00', '0.00', '0.00', 19, '2021-05-03 12:55:54', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 11, 'A', NULL, NULL, NULL, 0, '2021-04-28 18:01:48', '2021-05-03 12:55:54', 'C', 0, '12500.00', '720.00', '210.00', 'hj', 'ko', 8, 'GJ 05 RA 8833', NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '599.59', '930.00', 'CS'),
(200, 1054, 'mk999999', 122, 'ABC road at the rate 123', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '930.00', '120.00', 6, '0.00', '0.00', '0.00', '0.00', 0, NULL, NULL, '2021-04-29 12:32:07', NULL, NULL, NULL, NULL, 0, NULL, 11, 'CU', NULL, NULL, NULL, 0, '2021-04-28 18:05:31', '2021-04-29 12:32:07', 'C', 0, '12500.00', '720.00', '210.00', 'hj', 'ko', 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '930.00', '930.00', 'CS'),
(201, NULL, NULL, 122, 'ABC road at the rate 123', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '0.00', '150.00', 6, '124000.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'RO', NULL, NULL, NULL, 0, '2021-04-29 11:19:50', '2021-04-29 11:19:50', 'C', 0, '12500.00', '0.00', '0.00', 'hj', 'ko', 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 'CS'),
(202, 1055, 'kghkjiiuyiy', 1, 'Drop  near dee village 123 etc Drop Location', 'Drop  near dee village 123 etc Drop Location', '21.18494396382123', '72.84413892020893', '21.163664671740516', '72.83130017898182', 'Mr Viru Mehta', '7889202021', '', 'Mr Patel Bheem', '7856897845', 'Mr Patel Bheem', '0.00', '0.00', '0.00', '2530.00', '45.00', 5, '456.00', '2066.14', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 11, 'OA', NULL, NULL, NULL, 0, '2021-04-29 16:18:42', '2021-04-29 16:18:43', 'C', 0, '5252.06', '1250.00', '1280.00', NULL, NULL, 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '463.86', 'CS'),
(203, 1056, 'ml4545', 122, 'ABC road at the rate 123', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '0.00', '150.00', 6, '0.00', '0.00', '0.00', '0.00', 0, NULL, NULL, '2021-04-29 16:23:38', 'This kjgkh kjjkh kjbj', NULL, NULL, NULL, 0, NULL, 11, 'CU', NULL, NULL, NULL, 0, '2021-04-29 16:20:10', '2021-04-29 16:23:38', 'C', 0, '12500.00', '0.00', '0.00', 'hj', 'ko', 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 'CS'),
(204, 1057, 'mk9099', 122, 'ABC road at the rate 123', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '780.00', '150.00', 6, '0.00', '0.00', '0.00', '0.00', 0, NULL, NULL, '2021-04-29 16:49:16', 'This kjgkh kjjkh kjbj', NULL, NULL, NULL, 0, NULL, 11, 'CU', NULL, NULL, NULL, 0, '2021-04-29 16:46:27', '2021-04-29 16:49:16', 'C', 0, '12500.00', '720.00', '60.00', 'hj', 'ko', 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '780.00', 'CS'),
(205, 1058, 'mk9099', 122, 'ABC road at the rate 123', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '780.00', '150.00', 6, '0.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 11, 'OA', NULL, NULL, NULL, 0, '2021-04-30 11:00:54', '2021-04-30 11:00:58', 'C', 0, '12500.00', '720.00', '60.00', 'hj', 'ko', 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '780.00', 'CS'),
(206, 1059, 'mk9099', 122, 'ABC road at the rate 123', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '780.00', '150.00', 6, '0.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 11, 'OA', NULL, NULL, NULL, 0, '2021-04-30 13:01:55', '2021-04-30 13:01:56', 'C', 0, '12500.00', '720.00', '60.00', 'hj', 'ko', 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '780.00', 'CS'),
(207, 1060, 'mk9099', 122, 'ABC road at the rate 123', 'XYZ road at the rate 456', '21.150064540787493', '72.87235686589077', '21.151048452046176', '72.87431082240718', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '780.00', '150.00', 6, '0.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 11, 'OA', NULL, NULL, NULL, 0, '2021-04-30 13:01:55', '2021-04-30 13:01:56', 'C', 0, '12500.00', '720.00', '60.00', 'hj', 'ko', 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '780.00', 'CS'),
(208, 1061, 'kghkjiiuyiy', 1, 'Drop  near dee village 123 etc Drop Location', 'Drop  near dee village 123 etc Drop Location', '21.18494396382123', '72.84413892020893', '21.163664671740516', '72.83130017898182', 'Mr Viru Mehta', '7889202021', '', 'Mr Patel Bheem', '7856897845', 'Mr Patel Bheem', '0.00', '0.00', '0.00', '8096.00', '720.00', 16, '456.00', '2296.14', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 11, 'OA', NULL, NULL, NULL, 0, '2021-05-03 15:41:07', '2021-05-03 15:42:01', 'C', 0, '5252.06', '4000.00', '4096.00', NULL, NULL, 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', '9ZP494IBRS', 8, '230.00', '0.00', '0.00', '0.00', '5799.86', 'CS'),
(209, 1062, 'order1', 122, 'ABC road at the rate 123', 'XYZ road at the rate 456', '21.19882882681874', '72.86086042498113', '21.170966037963556', '72.83300399780273', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '780.00', '150.00', 6, '0.00', '0.00', '0.00', '0.00', 6, '2021-05-03 15:56:06', '2021-05-03 16:38:45', NULL, NULL, '2021-05-03 16:53:27', NULL, NULL, 0, NULL, 11, 'D', NULL, NULL, NULL, 0, '2021-05-03 15:53:37', '2021-05-03 16:53:27', 'C', 0, '12500.00', '720.00', '60.00', 'hj', 'ko', 6, 'GJ 05 CT 4131', NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '780.00', 'CS'),
(210, 1063, 'order2', 129, '313 A Building, LandMark Empires, Magob, Surat, Surat-395010', 'Raj Textile Tower\r\n1006 Raj Textile Tower, Besides Shree Mahavir Textile Market, Surat.', '21.190407023014174', '72.8655004428855', '21.148954030319178', '72.87510395050049', 'Sarang Bhai', '7778041114', '', 'Sarangbhai', '9099007849', '', '0.00', '0.00', '0.00', '200.00', '100.00', 25, '1000.00', '0.00', '0.00', '0.00', 6, '2021-05-03 16:51:30', '2021-05-03 16:54:50', NULL, NULL, '2021-05-03 16:55:20', NULL, NULL, 0, NULL, 11, 'D', NULL, NULL, NULL, 0, '2021-05-03 15:54:31', '2021-05-03 16:55:20', 'C', 0, '0.00', '200.00', '0.00', NULL, NULL, 6, 'GJ 05 CT 4131', NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '200.00', 'CS'),
(211, 1064, 'order3', 128, '583/84/85, Annapurna Textile Market, Ring Road, Surat, Surat-395002', 'Raj Textile Tower\r\n1006 Raj Textile Tower, Besides Shree Mahavir Textile Market, Surat.', '21.21994100335654', '72.88450241088867', '21.152403771628475', '72.87289251642031', 'Rajubhai', '9054435985', '', 'Sarang Bhai', '9099007849', '', '0.00', '0.00', '0.00', '1000.80', '276.00', 44, '0.00', '100.00', '0.00', '0.00', 6, '2021-05-03 15:56:00', '2021-05-03 16:33:59', NULL, NULL, '2021-05-03 16:48:17', NULL, NULL, 0, NULL, 11, 'D', NULL, NULL, NULL, 0, '2021-05-03 15:55:43', '2021-05-03 16:48:17', 'C', 0, '0.00', '760.80', '240.00', NULL, NULL, 6, 'GJ 05 CT 4131', NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '900.80', 'CS'),
(212, NULL, NULL, 1, 'ABC road at the rate 123', 'XYZ road at the rate 456', '21.150064540787', '72.872356865891', '21.151048452046', '72.874310822407', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '0.00', '150.00', 6, '124000.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'RO', NULL, NULL, NULL, 0, '2021-05-03 17:26:44', '2021-05-03 17:26:44', 'C', 0, '12500.00', '0.00', '0.00', 'hj', 'ko', 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 'CS'),
(213, NULL, NULL, 1, 'ABC road at the rate 123', 'XYZ road at the rate 456', '21.150064540787', '72.872356865891', '21.151048452046', '72.874310822407', 'Mr ABC XYZ', '9797589878', '', 'Mr JHGH JHGJHG', '9878568959', 'Mr JHGH JHGJHG', '0.00', '0.00', '0.00', '0.00', '150.00', 6, '124000.00', '0.00', '0.00', '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'RO', NULL, NULL, NULL, 0, '2021-05-03 17:33:04', '2021-05-03 17:33:04', 'C', 0, '12500.00', '0.00', '0.00', 'hj', 'ko', 0, NULL, NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 'CS'),
(214, 1065, '2021050600003', 19, 'Drop  near dee village 123 etc Drop Location', 'Drop  near dee village 123 etc Drop Location', '21.17896959261843', '72.82150268554688', '21.097120494329605', '72.86989560885084', 'Mr Viru Mehta', '7889202021', '', 'Mr Patel Bheem', '7856897845', 'Mr Patel Bheem', '0.00', '0.00', '0.00', '2860.00', '564.23', 11, '0.00', '0.00', '0.00', '0.00', 6, '2021-05-06 14:24:51', '2021-05-06 14:38:31', NULL, NULL, '2021-05-06 14:38:55', NULL, NULL, 0, NULL, 8, 'D', NULL, NULL, NULL, 0, '2021-05-06 14:23:34', '2021-05-06 14:38:55', 'C', 0, '5252.06', '2860.00', '0.00', NULL, NULL, 6, 'GJ 05 CT 4131', NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '2860.00', 'CS'),
(215, 1066, '2021050600004', 19, 'Drop  near dee village 123 etc Drop Location', 'Drop  near dee village 123 etc Drop Location', '21.171177211875417', '72.96190003407095', '21.122743113870463', '72.97357908053053', 'Mr Viru Mehta', '7889202021', '', 'Mr Patel Bheem', '7856897845', 'Mr Patel Bheem', '0.00', '0.00', '0.00', '2600.00', '122.50', 10, '0.00', '0.00', '0.00', '0.00', 6, '2021-05-06 14:24:50', '2021-05-06 14:26:37', NULL, NULL, '2021-05-06 14:38:07', NULL, NULL, 0, NULL, 8, 'D', NULL, NULL, NULL, 0, '2021-05-06 14:24:18', '2021-05-06 14:38:07', 'C', 0, '5252.06', '2600.00', '0.00', NULL, NULL, 6, 'GJ 05 CT 4131', NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '2600.00', 'CS'),
(216, 1067, '2021050600005', 19, 'Drop  near dee village 123 etc Drop Location', 'Drop  near dee village 123 etc Drop Location', '21.171177211875417', '72.96190003407095', '21.122743113870463', '72.97357908053053', 'Mr Viru Mehta', '7889202021', '', 'Mr Patel Bheem', '7856897845', 'Mr Patel Bheem', '0.00', '0.00', '0.00', '2600.00', '122.50', 10, '0.00', '0.00', '0.00', '0.00', 6, '2021-05-06 14:46:50', NULL, NULL, NULL, '2021-05-06 14:56:28', NULL, NULL, 0, NULL, 8, 'D', NULL, NULL, NULL, 0, '2021-05-06 14:46:02', '2021-05-06 14:56:28', 'C', 0, '5252.06', '2600.00', '0.00', NULL, NULL, 6, 'GJ 05 CT 4131', NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '2600.00', 'CS'),
(217, 1068, '2021050600006', 19, 'Drop  near dee village 123 etc Drop Location', 'Drop  near dee village 123 etc Drop Location', '21.171177211875417', '72.96190003407095', '21.122743113870463', '72.97357908053053', 'Mr Viru Mehta', '7889202021', '', 'Mr Patel Bheem', '7856897845', 'Mr Patel Bheem', '0.00', '0.00', '0.00', '897.00', '281.75', 23, '0.00', '0.00', '0.00', '0.00', 6, '2021-05-06 14:46:48', '2021-05-06 14:47:32', NULL, NULL, '2021-05-06 14:55:48', NULL, NULL, 0, NULL, 8, 'D', NULL, NULL, NULL, 0, '2021-05-06 14:46:35', '2021-05-06 14:55:48', 'C', 0, '5252.06', '598.00', '299.00', NULL, NULL, 6, 'GJ 05 CT 4131', NULL, NULL, NULL, '0.00', NULL, 'PRL', '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '897.00', 'CS');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_files`
--

CREATE TABLE `tbl_order_files` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` int(11) NOT NULL DEFAULT 0,
  `img` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'file',
  `img_type` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'LRP-LR Pickup Image,\r\nLRD- LR Drop Image,\r\nGP- Goods Pickup Image,\r\nGD- Goods Drop Image,',
  `is_active` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-active,1-deactive, 2- deleted',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_order_files`
--

INSERT INTO `tbl_order_files` (`id`, `order_id`, `img`, `img_type`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 24, '6035e9a7cf3c71614145959.jpeg', 'LRP', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(2, 24, 'pickup6035f365369af1614148453.jpeg', 'GP', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(3, 24, 'deliver6035f3653524e1614148453.jpeg', 'GD', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(4, 23, 'deliver6034e1b488f051614078388.jpeg', 'GD', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(5, 23, 'lr6034e1b48a5191614078388.jpeg', 'LRP', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(6, 23, 'pickup6034e1b489cd11614078388.jpeg', 'GP', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(7, 22, 'deliver6034cb5c22cbc1614072668.jpeg', 'GD', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(8, 22, 'lr6034cb5c24ab31614072668.jpeg', 'LRP', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(9, 22, 'pickup6034cb5c23b3c1614072668.jpeg', 'GP', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(10, 21, 'deliver6034df2117c6a1614077729.jpeg', 'GD', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(11, 21, 'lr6034d1ce652411614074318.jpeg', 'LRP', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(12, 21, 'pickup6034d1ce646141614074318.jpeg', 'GP', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(13, 20, 'deliver6034ca1f48c851614072351.jpeg', 'GD', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(14, 20, 'lr6034ca1f4a0b91614072351.jpeg', 'LRP', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(15, 20, 'pickup6034ca1f4985e1614072351.jpeg', 'GP', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(16, 19, 'deliver6034c851552071614071889.jpeg', 'GD', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(17, 19, 'lr6034c8db187791614072027.jpeg', 'LRP', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(18, 19, 'pickup6034af1c27be21614065436.jpeg', 'GP', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(19, 18, 'deliver6034dda4d90391614077348.jpeg', 'GD', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(20, 18, 'lr6034dda4da93f1614077348.jpeg', 'LRP', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(21, 18, 'pickup6034dda4d9fcb1614077348.jpeg', 'GP', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(22, 17, 'deliver6033a2ca9c0621613996746.jpeg', 'GD', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(23, 17, 'lr6033a2ca9defa1613996746.jpeg', 'LRP', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(24, 17, 'pickup6033a2ca9d1d61613996746.jpeg', 'GP', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(25, 16, 'deliver60339d8e2a6741613995406.jpeg', 'GD', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(26, 16, 'lr60339d8e2b9db1613995406.jpeg', 'LRP', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(27, 16, 'pickup60339d8e2b2521613995406.jpeg', 'GP', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(28, 15, 'deliver603384da9825e1613989082.jpeg', 'GD', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(29, 15, 'lr603384da9a34c1613989082.jpeg', 'LRP', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(30, 15, 'pickup603384da995b71613989082.jpeg', 'GP', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(31, 14, 'lr603383ee8face1613988846.jpeg', 'LRP', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(32, 14, 'pickup60337e87dd2751613987463.jpeg', 'GP', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(33, 14, 'deliver60337e87dcaae1613987463.jpeg', 'GD', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(34, 13, 'lr603383c7b9a831613988807.jpeg', 'LRP', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(35, 13, 'pickup60337c909aa091613986960.jpeg', 'GP', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(36, 13, 'deliver60337c9099a941613986960.jpeg', 'GD', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(37, 9, 'lr6035f815081601614149653.jpeg', 'LRP', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(38, 9, 'pickup6035f815074a81614149653.jpeg', 'GP', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(39, 9, 'deliver6035f815062341614149653.jpeg', 'GD', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(40, 8, 'lr6035f78210e681614149506.jpeg', 'LRP', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(41, 8, 'pickup6035f782107611614149506.jpeg', 'GP', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(42, 8, 'deliver6035f7820fb941614149506.jpeg', 'GD', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(43, 7, 'lr6035f5dd9060f1614149085.jpeg', 'LRP', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(44, 7, 'pickup6035f5dd8f8f31614149085.jpeg', 'GP', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(45, 7, 'deliver6035f5dd8e83d1614149085.jpeg', 'GD', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(46, 6, '602b91ce545a11613468110.jpg', 'LRP', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(47, 6, 'pickup6035f54dc67271614148941.jpeg', 'GP', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(48, 6, 'deliver6035f54dc552f1614148941.jpeg', 'GD', 0, '2021-02-26 10:51:32', '2021-02-26 10:51:32'),
(49, 25, 'LRP603c867d8377f1614579325.jpeg', 'LRP', 0, '2021-03-01 11:45:25', '2021-03-01 11:45:25'),
(50, 25, 'GP603c867d8471c1614579325.jpeg', 'GP', 0, '2021-03-01 11:45:25', '2021-03-01 11:45:25'),
(51, 26, 'LRP603c8bdc6f6831614580700.jpeg', 'LRP', 0, '2021-03-01 12:08:20', '2021-03-01 12:08:20'),
(52, 26, 'GP603c8bdc70ca51614580700.jpeg', 'GP', 0, '2021-03-01 12:08:20', '2021-03-01 12:08:20'),
(53, 27, 'LRP603c938c6bf0c1614582668.jpeg', 'LRP', 0, '2021-03-01 12:41:08', '2021-03-01 12:41:08'),
(54, 27, 'GP603c938c6d8f71614582668.jpeg', 'GP', 0, '2021-03-01 12:41:08', '2021-03-01 12:41:08'),
(55, 28, 'LRP603c963b96c341614583355.jpeg', 'LRP', 0, '2021-03-01 12:52:35', '2021-03-01 12:52:35'),
(56, 28, 'LRD603c963b975eb1614583355.jpeg', 'LRD', 0, '2021-03-01 12:52:35', '2021-03-01 12:52:35'),
(57, 28, 'GP603c963b97d171614583355.jpeg', 'GP', 0, '2021-03-01 12:52:35', '2021-03-01 12:52:35'),
(58, 28, 'GD603c963b984071614583355.jpeg', 'GD', 0, '2021-03-01 12:52:35', '2021-03-01 12:52:35'),
(59, 26, 'LRD603c9a8adcac91614584458.jpeg', 'LRD', 0, '2021-03-01 13:10:58', '2021-03-01 13:10:58'),
(60, 26, 'LRD603c9a8add3751614584458.jpeg', 'LRD', 0, '2021-03-01 13:10:58', '2021-03-01 13:10:58'),
(61, 26, 'GD603c9a8addc6e1614584458.jpeg', 'GD', 0, '2021-03-01 13:10:58', '2021-03-01 13:10:58'),
(62, 25, 'LRD603cb4ac2c6781614591148.jpeg', 'LRD', 0, '2021-03-01 15:02:28', '2021-03-01 15:02:28'),
(63, 25, 'GD603cb4ac2cf831614591148.jpeg', 'GD', 0, '2021-03-01 15:02:28', '2021-03-01 15:02:28'),
(64, 29, 'LRP603dc8e4331cd1614661860.jpeg', 'LRP', 0, '2021-03-02 10:41:00', '2021-03-02 10:41:00'),
(65, 29, 'LRP603dc8e433a481614661860.jpeg', 'LRP', 0, '2021-03-02 10:41:00', '2021-03-02 10:41:00'),
(66, 29, 'LRD603dc8e4340261614661860.jpeg', 'LRD', 0, '2021-03-02 10:41:00', '2021-03-02 10:41:00'),
(67, 29, 'LRD603dc8e43485e1614661860.jpeg', 'LRD', 0, '2021-03-02 10:41:00', '2021-03-02 10:41:00'),
(68, 29, 'GP603dc8e434e841614661860.jpeg', 'GP', 0, '2021-03-02 10:41:00', '2021-03-02 10:41:00'),
(69, 29, 'GD603dc8e4354f21614661860.jpeg', 'GD', 0, '2021-03-02 10:41:00', '2021-03-02 10:41:00'),
(70, 30, 'LRP603dd68ceebcd1614665356.jpeg', 'LRP', 0, '2021-03-02 11:39:16', '2021-03-02 11:39:16'),
(71, 31, 'LRP603ddc1ac01a01614666778.jpeg', 'LRP', 0, '2021-03-02 12:02:58', '2021-03-02 12:02:58'),
(72, 32, 'LRP603de21ebf5691614668318.jpeg', 'LRP', 0, '2021-03-02 12:28:38', '2021-03-02 12:28:38'),
(73, 33, 'LRP603de83c0bf871614669884.jpeg', 'LRP', 0, '2021-03-02 12:54:44', '2021-03-02 12:54:44'),
(74, 33, 'GP603de83c0ca101614669884.jpeg', 'GP', 0, '2021-03-02 12:54:44', '2021-03-02 12:54:44'),
(75, 34, 'LRP603deba27696c1614670754.jpeg', 'LRP', 0, '2021-03-02 13:09:14', '2021-03-02 13:09:14'),
(76, 34, 'GP603deba2776b11614670754.jpeg', 'GP', 0, '2021-03-02 13:09:14', '2021-03-02 13:09:14'),
(77, 30, 'LRD603df96c16d671614674284.jpeg', 'LRD', 0, '2021-03-02 14:08:04', '2021-03-02 14:08:04'),
(78, 30, 'GP603df96c17b501614674284.jpeg', 'GP', 0, '2021-03-02 14:08:04', '2021-03-02 14:08:04'),
(79, 30, 'GD603df96c186321614674284.jpeg', 'GD', 0, '2021-03-02 14:08:04', '2021-03-02 14:08:04'),
(80, 33, 'LRD603dfa91119781614674577.jpeg', 'LRD', 0, '2021-03-02 14:12:57', '2021-03-02 14:12:57'),
(81, 33, 'GD603dfa91123021614674577.jpeg', 'GD', 0, '2021-03-02 14:12:57', '2021-03-02 14:12:57'),
(82, 31, 'LRD603e0017290021614675991.jpeg', 'LRD', 0, '2021-03-02 14:36:31', '2021-03-02 14:36:31'),
(83, 31, 'GP603e0017298661614675991.jpeg', 'GP', 0, '2021-03-02 14:36:31', '2021-03-02 14:36:31'),
(84, 31, 'GD603e001729edc1614675991.jpeg', 'GD', 0, '2021-03-02 14:36:31', '2021-03-02 14:36:31'),
(85, 32, 'LRD603e0e4c7f7381614679628.jpeg', 'LRD', 0, '2021-03-02 15:37:08', '2021-03-02 15:37:08'),
(86, 32, 'GP603e0e4c804411614679628.jpeg', 'GP', 0, '2021-03-02 15:37:08', '2021-03-02 15:37:08'),
(87, 32, 'GD603e0e4cb53c51614679628.jpeg', 'GD', 0, '2021-03-02 15:37:08', '2021-03-02 15:37:08'),
(88, 34, 'LRD603e1637ecf761614681655.jpeg', 'LRD', 0, '2021-03-02 16:10:55', '2021-03-02 16:10:55'),
(89, 35, 'LRP6040acbb12cee1614851259.jpeg', 'LRP', 0, '2021-03-04 15:17:39', '2021-03-04 15:17:39'),
(90, 36, 'LRP6040ae54c85561614851668.jpeg', 'LRP', 0, '2021-03-04 15:24:28', '2021-03-04 15:24:28'),
(91, 37, 'LRP6040b12d29d5a1614852397.jpeg', 'LRP', 0, '2021-03-04 15:36:37', '2021-03-04 15:36:37'),
(92, 38, 'LRP6040b32174a3e1614852897.jpeg', 'LRP', 0, '2021-03-04 15:44:57', '2021-03-04 15:44:57'),
(93, 39, 'LRP6040b46b4583e1614853227.jpeg', 'LRP', 0, '2021-03-04 15:50:27', '2021-03-04 15:50:27'),
(94, 39, 'LRD6041d8b5a26a41614928053.jpeg', 'LRD', 0, '2021-03-05 12:37:33', '2021-03-05 12:37:33'),
(95, 39, 'GP6041d8b5a41521614928053.jpeg', 'GP', 0, '2021-03-05 12:37:33', '2021-03-05 12:37:33'),
(96, 39, 'GD6041d8b5a4edc1614928053.jpeg', 'GD', 0, '2021-03-05 12:37:33', '2021-03-05 12:37:33'),
(97, 38, 'LRD6041d94ba61721614928203.jpeg', 'LRD', 0, '2021-03-05 12:40:03', '2021-03-05 12:40:03'),
(98, 38, 'GP6041d94ba85661614928203.jpeg', 'GP', 0, '2021-03-05 12:40:03', '2021-03-05 12:40:03'),
(99, 38, 'GD6041d94ba8e9c1614928203.jpeg', 'GD', 0, '2021-03-05 12:40:03', '2021-03-05 12:40:03'),
(100, 37, 'LRD6041d9885a9a11614928264.jpeg', 'LRD', 0, '2021-03-05 12:41:04', '2021-03-05 12:41:04'),
(101, 37, 'GP6041d9885b1981614928264.jpeg', 'GP', 0, '2021-03-05 12:41:04', '2021-03-05 12:41:04'),
(102, 37, 'GD6041d9885b8de1614928264.jpeg', 'GD', 0, '2021-03-05 12:41:04', '2021-03-05 12:41:04'),
(103, 36, 'LRD6041d9b71be7f1614928311.jpeg', 'LRD', 0, '2021-03-05 12:41:51', '2021-03-05 12:41:51'),
(104, 36, 'GP6041d9b71c7e21614928311.jpeg', 'GP', 0, '2021-03-05 12:41:51', '2021-03-05 12:41:51'),
(105, 36, 'GD6041d9b71cf031614928311.jpeg', 'GD', 0, '2021-03-05 12:41:51', '2021-03-05 12:41:51'),
(106, 35, 'LRD6041d9f366aa41614928371.jpeg', 'LRD', 0, '2021-03-05 12:42:51', '2021-03-05 12:42:51'),
(107, 35, 'GP6041d9f3672351614928371.jpeg', 'GP', 0, '2021-03-05 12:42:51', '2021-03-05 12:42:51'),
(108, 35, 'GD6041d9f3679851614928371.jpeg', 'GD', 0, '2021-03-05 12:42:51', '2021-03-05 12:42:51'),
(109, 40, 'LRP6041dfa8eaae71614929832.jpeg', 'LRP', 0, '2021-03-05 13:07:12', '2021-03-05 13:07:12'),
(110, 40, 'LRD6041fd999025a1614937497.jpeg', 'LRD', 0, '2021-03-05 15:14:57', '2021-03-05 15:14:57'),
(111, 40, 'GP6041fd9990a691614937497.jpeg', 'GP', 0, '2021-03-05 15:14:57', '2021-03-05 15:14:57'),
(112, 40, 'GD6041fd99911321614937497.jpeg', 'GD', 0, '2021-03-05 15:14:57', '2021-03-05 15:14:57'),
(113, 41, 'LRP6045fdb188dba1615199665.jpeg', 'LRP', 0, '2021-03-08 16:04:25', '2021-03-08 16:04:25'),
(114, 41, 'LRD6045fdb18989b1615199665.jpeg', 'LRD', 0, '2021-03-08 16:04:25', '2021-03-08 16:04:25'),
(115, 41, 'GP6045fdb18a2781615199665.jpeg', 'GP', 0, '2021-03-08 16:04:25', '2021-03-08 16:04:25'),
(116, 41, 'GD6045fdb18ade41615199665.jpeg', 'GD', 0, '2021-03-08 16:04:25', '2021-03-08 16:04:25'),
(117, 44, 'LRP6045fe36c75e21615199798.jpeg', 'LRP', 0, '2021-03-08 16:06:38', '2021-03-08 16:06:38'),
(118, 44, 'LRD6045fe36c7e031615199798.jpeg', 'LRD', 0, '2021-03-08 16:06:38', '2021-03-08 16:06:38'),
(119, 44, 'GP6045fe36c84c91615199798.jpeg', 'GP', 0, '2021-03-08 16:06:38', '2021-03-08 16:06:38'),
(120, 44, 'GD6045fe36c8d011615199798.jpeg', 'GD', 0, '2021-03-08 16:06:38', '2021-03-08 16:06:38'),
(121, 43, 'LRP6045ff771fc8b1615200119.jpeg', 'LRP', 0, '2021-03-08 16:11:59', '2021-03-08 16:11:59'),
(122, 43, 'LRD6045ff77206f11615200119.jpeg', 'LRD', 0, '2021-03-08 16:11:59', '2021-03-08 16:11:59'),
(123, 43, 'GP6045ff7720fed1615200119.jpeg', 'GP', 0, '2021-03-08 16:11:59', '2021-03-08 16:11:59'),
(124, 43, 'GD6045ff77219341615200119.jpeg', 'GD', 0, '2021-03-08 16:11:59', '2021-03-08 16:11:59'),
(125, 45, 'LRP6046019ed2b7a1615200670.jpeg', 'LRP', 0, '2021-03-08 16:21:10', '2021-03-08 16:21:10'),
(126, 45, 'LRD6046019ed3aa91615200670.jpeg', 'LRD', 0, '2021-03-08 16:21:10', '2021-03-08 16:21:10'),
(127, 45, 'GP6046019ed42881615200670.jpeg', 'GP', 0, '2021-03-08 16:21:10', '2021-03-08 16:21:10'),
(128, 45, 'GD6046019ed4af31615200670.jpeg', 'GD', 0, '2021-03-08 16:21:10', '2021-03-08 16:21:10'),
(129, 42, 'LRP604606357656c1615201845.jpeg', 'LRP', 0, '2021-03-08 16:40:45', '2021-03-08 16:40:45'),
(130, 42, 'LRD60460635776a31615201845.jpeg', 'LRD', 0, '2021-03-08 16:40:45', '2021-03-08 16:40:45'),
(131, 42, 'GP60460635782481615201845.jpeg', 'GP', 0, '2021-03-08 16:40:45', '2021-03-08 16:40:45'),
(132, 42, 'GD60460635789f91615201845.jpeg', 'GD', 0, '2021-03-08 16:40:45', '2021-03-08 16:40:45'),
(133, 46, 'LRP60470ff0a4c521615269872.jpeg', 'LRP', 0, '2021-03-09 11:34:32', '2021-03-09 11:34:32'),
(134, 46, 'LRD60470ff0a52fd1615269872.jpeg', 'LRD', 0, '2021-03-09 11:34:32', '2021-03-09 11:34:32'),
(135, 46, 'GP60470ff0a58e11615269872.jpeg', 'GP', 0, '2021-03-09 11:34:32', '2021-03-09 11:34:32'),
(136, 46, 'GD60470ff0a5fa01615269872.jpeg', 'GD', 0, '2021-03-09 11:34:32', '2021-03-09 11:34:32'),
(137, 47, 'LRP604712ad591e91615270573.jpeg', 'LRP', 0, '2021-03-09 11:46:13', '2021-03-09 11:46:13'),
(138, 47, 'LRD604712ad599d01615270573.jpeg', 'LRD', 0, '2021-03-09 11:46:13', '2021-03-09 11:46:13'),
(139, 47, 'GP604712ad59fcb1615270573.jpeg', 'GP', 0, '2021-03-09 11:46:13', '2021-03-09 11:46:13'),
(140, 47, 'GD604712ad5a7221615270573.jpeg', 'GD', 0, '2021-03-09 11:46:13', '2021-03-09 11:46:13'),
(141, 48, 'LRP604b42914bfe81615544977.jpeg', 'LRP', 0, '2021-03-12 15:59:37', '2021-03-12 15:59:37'),
(142, 48, 'LRD604b42914c93e1615544977.jpeg', 'LRD', 0, '2021-03-12 15:59:37', '2021-03-12 15:59:37'),
(143, 48, 'GP604b42914cfb81615544977.jpeg', 'GP', 0, '2021-03-12 15:59:37', '2021-03-12 15:59:37'),
(144, 48, 'GD604b42914d6fc1615544977.jpeg', 'GD', 0, '2021-03-12 15:59:37', '2021-03-12 15:59:37'),
(145, 50, 'LRP604b42fa647581615545082.jpeg', 'LRP', 0, '2021-03-12 16:01:22', '2021-03-12 16:01:22'),
(146, 50, 'LRD604b42fa651871615545082.jpeg', 'LRD', 0, '2021-03-12 16:01:22', '2021-03-12 16:01:22'),
(147, 50, 'GP604b42fa658ff1615545082.jpeg', 'GP', 0, '2021-03-12 16:01:22', '2021-03-12 16:01:22'),
(148, 50, 'GD604b42fa661971615545082.jpeg', 'GD', 0, '2021-03-12 16:01:22', '2021-03-12 16:01:22'),
(149, 49, 'LRP604b4e9a5d09c1615548058.jpeg', 'LRP', 0, '2021-03-12 16:50:58', '2021-03-12 16:50:58'),
(150, 49, 'LRD604b4e9a5ded51615548058.jpeg', 'LRD', 0, '2021-03-12 16:50:58', '2021-03-12 16:50:58'),
(151, 49, 'GP604b4e9a5ea4b1615548058.jpeg', 'GP', 0, '2021-03-12 16:50:58', '2021-03-12 16:50:58'),
(152, 49, 'GD604b4e9a5f5511615548058.jpeg', 'GD', 0, '2021-03-12 16:50:58', '2021-03-12 16:50:58'),
(153, 51, 'LRP604c6a57be32e1615620695.jpeg', 'LRP', 0, '2021-03-13 13:01:35', '2021-03-13 13:01:35'),
(154, 51, 'LRD604c6a57bf0c11615620695.jpeg', 'LRD', 0, '2021-03-13 13:01:35', '2021-03-13 13:01:35'),
(155, 51, 'GP604c6a57bfdae1615620695.jpeg', 'GP', 0, '2021-03-13 13:01:35', '2021-03-13 13:01:35'),
(156, 51, 'GD604c6a57c09481615620695.jpeg', 'GD', 0, '2021-03-13 13:01:35', '2021-03-13 13:01:35'),
(157, 52, 'LRP604c7bc3dae9c1615625155.jpeg', 'LRP', 0, '2021-03-13 14:15:55', '2021-03-13 14:15:55'),
(158, 52, 'LRD604c7bc3dbeec1615625155.jpeg', 'LRD', 0, '2021-03-13 14:15:55', '2021-03-13 14:15:55'),
(159, 52, 'GP604c7bc3dcd881615625155.jpeg', 'GP', 0, '2021-03-13 14:15:55', '2021-03-13 14:15:55'),
(160, 52, 'GD604c7bc3dfc781615625155.jpeg', 'GD', 0, '2021-03-13 14:15:55', '2021-03-13 14:15:55'),
(161, 53, 'LRP604c9be582df81615633381.jpeg', 'LRP', 0, '2021-03-13 16:33:01', '2021-03-13 16:33:01'),
(162, 53, 'LRP604c9be5836771615633381.jpeg', 'LRP', 0, '2021-03-13 16:33:01', '2021-03-13 16:33:01'),
(163, 53, 'GP604c9be583d301615633381.jpeg', 'GP', 0, '2021-03-13 16:33:01', '2021-03-13 16:33:01'),
(164, 53, 'LRD604ca3adda0ab1615635373.jpeg', 'LRD', 0, '2021-03-13 17:06:13', '2021-03-13 17:06:13'),
(165, 53, 'GD604ca3adda8791615635373.jpeg', 'GD', 0, '2021-03-13 17:06:13', '2021-03-13 17:06:13'),
(166, 54, 'LRP604f145aa2e0e1615795290.jpeg', 'LRP', 0, '2021-03-15 13:31:30', '2021-03-15 13:31:30'),
(167, 54, 'LRD604f145aa39291615795290.jpeg', 'LRD', 0, '2021-03-15 13:31:30', '2021-03-15 13:31:30'),
(168, 54, 'GP604f145aa42871615795290.jpeg', 'GP', 0, '2021-03-15 13:31:30', '2021-03-15 13:31:30'),
(169, 54, 'GD604f145aa4a581615795290.jpeg', 'GD', 0, '2021-03-15 13:31:30', '2021-03-15 13:31:30'),
(170, 56, 'LRP604f1afd4e19b1615796989.jpeg', 'LRP', 0, '2021-03-15 13:59:49', '2021-03-15 13:59:49'),
(175, 56, 'LRD604f2b46cda7b1615801158.jpeg', 'LRD', 0, '2021-03-15 15:09:18', '2021-03-15 15:09:18'),
(172, 56, 'GP604f1afd4f0901615796989.jpeg', 'GP', 0, '2021-03-15 13:59:49', '2021-03-15 13:59:49'),
(173, 56, 'GD604f1afd4f74c1615796989.jpeg', 'GD', 0, '2021-03-15 13:59:49', '2021-03-15 13:59:49'),
(174, 53, 'LRD604f2aef8ade81615801071.jpeg', 'LRD', 0, '2021-03-15 15:07:51', '2021-03-15 15:07:51'),
(176, 57, 'LRP604f399e1337f1615804830.jpeg', 'LRP', 0, '2021-03-15 16:10:30', '2021-03-15 16:10:30'),
(177, 57, 'LRD604f399e140e81615804830.jpeg', 'LRD', 0, '2021-03-15 16:10:30', '2021-03-15 16:10:30'),
(178, 57, 'GP604f399e14ad81615804830.jpeg', 'GP', 0, '2021-03-15 16:10:30', '2021-03-15 16:10:30'),
(179, 57, 'GD604f399e1570c1615804830.jpeg', 'GD', 0, '2021-03-15 16:10:30', '2021-03-15 16:10:30'),
(180, 58, 'LRP604f5301d41021615811329.jpeg', 'LRP', 0, '2021-03-15 17:58:49', '2021-03-15 17:58:49'),
(181, 58, 'LRD604f5301d4b041615811329.jpeg', 'LRD', 0, '2021-03-15 17:58:49', '2021-03-15 17:58:49'),
(182, 58, 'GP604f5301d52df1615811329.jpeg', 'GP', 0, '2021-03-15 17:58:49', '2021-03-15 17:58:49'),
(183, 58, 'GD604f5301d5b9a1615811329.jpeg', 'GD', 0, '2021-03-15 17:58:49', '2021-03-15 17:58:49'),
(184, 55, 'LRP604f5653d537d1615812179.jpeg', 'LRP', 0, '2021-03-15 18:12:59', '2021-03-15 18:12:59'),
(185, 55, 'LRD604f5653d5fce1615812179.jpeg', 'LRD', 0, '2021-03-15 18:12:59', '2021-03-15 18:12:59'),
(186, 55, 'GP604f5653d661d1615812179.jpeg', 'GP', 0, '2021-03-15 18:12:59', '2021-03-15 18:12:59'),
(187, 61, 'LRP605217e31799c1615992803.jpeg', 'LRP', 0, '2021-03-17 20:23:23', '2021-03-17 20:23:23'),
(188, 61, 'LRD605217e317fd01615992803.jpeg', 'LRD', 0, '2021-03-17 20:23:23', '2021-03-17 20:23:23'),
(189, 61, 'GP605217e3183381615992803.jpeg', 'GP', 0, '2021-03-17 20:23:23', '2021-03-17 20:23:23'),
(190, 61, 'GD605217e31873a1615992803.jpeg', 'GD', 0, '2021-03-17 20:23:23', '2021-03-17 20:23:23'),
(191, 62, 'LRP605219be06dbe1615993278.jpeg', 'LRP', 0, '2021-03-17 20:31:18', '2021-03-17 20:31:18'),
(192, 62, 'LRD605219be0766d1615993278.jpeg', 'LRD', 0, '2021-03-17 20:31:18', '2021-03-17 20:31:18'),
(193, 62, 'GP605219be07ccd1615993278.jpeg', 'GP', 0, '2021-03-17 20:31:18', '2021-03-17 20:31:18'),
(194, 62, 'GD605219be0842d1615993278.jpeg', 'GD', 0, '2021-03-17 20:31:18', '2021-03-17 20:31:18'),
(195, 63, 'LRP605484a77a3eb1616151719.jpeg', 'LRP', 0, '2021-03-19 16:31:59', '2021-03-19 16:31:59'),
(196, 63, 'LRD605484a77a8d61616151719.jpeg', 'LRD', 0, '2021-03-19 16:31:59', '2021-03-19 16:31:59'),
(197, 63, 'GP605484a77aca11616151719.jpeg', 'GP', 0, '2021-03-19 16:31:59', '2021-03-19 16:31:59'),
(198, 63, 'GD605484a77b05c1616151719.jpeg', 'GD', 0, '2021-03-19 16:31:59', '2021-03-19 16:31:59'),
(199, 64, 'LRP60585b9506b841616403349.jpeg', 'LRP', 0, '2021-03-22 14:25:49', '2021-03-22 14:25:49'),
(200, 64, 'LRD60585b95071ee1616403349.jpeg', 'LRD', 0, '2021-03-22 14:25:49', '2021-03-22 14:25:49'),
(201, 64, 'GP60585b95076b81616403349.jpeg', 'GP', 0, '2021-03-22 14:25:49', '2021-03-22 14:25:49'),
(202, 64, 'GD60585b9507b181616403349.jpeg', 'GD', 0, '2021-03-22 14:25:49', '2021-03-22 14:25:49'),
(203, 66, 'LRP60598ece3b9351616481998.jpeg', 'LRP', 0, '2021-03-23 12:16:38', '2021-03-23 12:16:38'),
(204, 66, 'LRD60598ece3c6401616481998.jpeg', 'LRD', 0, '2021-03-23 12:16:38', '2021-03-23 12:16:38'),
(205, 66, 'GP60598ece3cd8b1616481998.jpeg', 'GP', 0, '2021-03-23 12:16:38', '2021-03-23 12:16:38'),
(206, 66, 'GD60598ece3d5141616481998.jpeg', 'GD', 0, '2021-03-23 12:16:38', '2021-03-23 12:16:38'),
(207, 65, 'LRP60599cd2477c51616485586.jpeg', 'LRP', 0, '2021-03-23 13:16:26', '2021-03-23 13:16:26'),
(208, 65, 'LRD60599cd2485141616485586.jpeg', 'LRD', 0, '2021-03-23 13:16:26', '2021-03-23 13:16:26'),
(209, 65, 'GP60599cd248d2e1616485586.jpeg', 'GP', 0, '2021-03-23 13:16:26', '2021-03-23 13:16:26'),
(210, 65, 'GD60599cd2496f61616485586.jpeg', 'GD', 0, '2021-03-23 13:16:26', '2021-03-23 13:16:26'),
(211, 67, 'LRP6059ae5b494441616490075.jpeg', 'LRP', 0, '2021-03-23 14:31:15', '2021-03-23 14:31:15'),
(212, 67, 'LRD6059ae5b49d071616490075.jpeg', 'LRD', 0, '2021-03-23 14:31:15', '2021-03-23 14:31:15'),
(213, 67, 'GP6059ae5b4a4211616490075.jpeg', 'GP', 0, '2021-03-23 14:31:15', '2021-03-23 14:31:15'),
(214, 67, 'GD6059ae5b4adf01616490075.jpeg', 'GD', 0, '2021-03-23 14:31:15', '2021-03-23 14:31:15'),
(215, 68, 'LRP6059d71bb662a1616500507.jpeg', 'LRP', 0, '2021-03-23 17:25:07', '2021-03-23 17:25:07'),
(216, 68, 'GP6059d71bb6fe91616500507.jpeg', 'GP', 0, '2021-03-23 17:25:07', '2021-03-23 17:25:07'),
(217, 68, 'GD6059d71bb874c1616500507.jpeg', 'GD', 0, '2021-03-23 17:25:07', '2021-03-23 17:25:07'),
(218, 71, 'GP605c7122004331616671010.jpg', 'GP', 0, '2021-03-25 16:46:50', '2021-03-25 16:46:50'),
(219, 69, 'GP605c715c868791616671068.jpg', 'GP', 0, '2021-03-25 16:47:48', '2021-03-25 16:47:48'),
(220, 70, 'GP605c716e6079d1616671086.jpg', 'GP', 0, '2021-03-25 16:48:06', '2021-03-25 16:48:06'),
(221, 70, 'LRP605c76fbba2df1616672507.jpeg', 'LRP', 0, '2021-03-25 17:11:47', '2021-03-25 17:11:47'),
(222, 69, 'LRP605c777657aba1616672630.jpeg', 'LRP', 0, '2021-03-25 17:13:50', '2021-03-25 17:13:50'),
(223, 71, 'LRP605c779848a5e1616672664.jpeg', 'LRP', 0, '2021-03-25 17:14:24', '2021-03-25 17:14:24'),
(224, 69, 'GD605c802b0eb3d1616674859.jpg', 'GD', 0, '2021-03-25 17:50:59', '2021-03-25 17:50:59'),
(225, 69, 'SGD605c802b0e3cc1616674859.png', 'SGD', 0, '2021-03-25 17:50:59', '2021-03-25 17:50:59'),
(226, 70, 'GD605c804d8339d1616674893.jpg', 'GD', 0, '2021-03-25 17:51:33', '2021-03-25 17:51:33'),
(227, 70, 'SGD605c804d82c9e1616674893.png', 'SGD', 0, '2021-03-25 17:51:33', '2021-03-25 17:51:33'),
(228, 71, 'GD605c84910fc921616675985.jpg', 'GD', 0, '2021-03-25 18:09:45', '2021-03-25 18:09:45'),
(229, 71, 'SGD605c84910f5181616675985.png', 'SGD', 0, '2021-03-25 18:09:45', '2021-03-25 18:09:45'),
(230, 73, 'LRP605dd309e47f61616761609.jpeg', 'LRP', 0, '2021-03-26 17:56:49', '2021-03-26 17:56:49'),
(231, 73, 'LRD605dd309e4b601616761609.jpeg', 'LRD', 0, '2021-03-26 17:56:49', '2021-03-26 17:56:49'),
(232, 73, 'GP605dd309e4c1d1616761609.jpeg', 'GP', 0, '2021-03-26 17:56:49', '2021-03-26 17:56:49'),
(233, 73, 'GD605dd309e4d0d1616761609.jpeg', 'GD', 0, '2021-03-26 17:56:49', '2021-03-26 17:56:49'),
(234, 75, 'LRP605eff0fbffe31616838415.jpeg', 'LRP', 0, '2021-03-27 15:16:55', '2021-03-27 15:16:55'),
(235, 75, 'LRD605eff0fc051c1616838415.jpeg', 'LRD', 0, '2021-03-27 15:16:55', '2021-03-27 15:16:55'),
(236, 75, 'GP605eff0fc06041616838415.jpeg', 'GP', 0, '2021-03-27 15:16:55', '2021-03-27 15:16:55'),
(237, 75, 'GD605eff0fc08a41616838415.jpeg', 'GD', 0, '2021-03-27 15:16:55', '2021-03-27 15:16:55'),
(238, 74, 'LRP605effd59f9751616838613.jpeg', 'LRP', 0, '2021-03-27 15:20:13', '2021-03-27 15:20:13'),
(239, 74, 'GP605effd59fd1f1616838613.jpeg', 'GP', 0, '2021-03-27 15:20:13', '2021-03-27 15:20:13'),
(240, 74, 'LRD605f09cff41111616841167.jpeg', 'LRD', 0, '2021-03-27 16:02:48', '2021-03-27 16:02:48'),
(241, 74, 'GD605f09d00004b1616841168.jpeg', 'GD', 0, '2021-03-27 16:02:48', '2021-03-27 16:02:48'),
(242, 76, 'GP6062b916e778f1617082646.jpg', 'GP', 0, '2021-03-30 11:07:27', '2021-03-30 11:07:27'),
(243, 76, 'GD6063142481a6c1617105956.jpg', 'GD', 0, '2021-03-30 17:35:57', '2021-03-30 17:35:57'),
(244, 76, 'GD6063142481e4f1617105956.jpg', 'GD', 0, '2021-03-30 17:35:57', '2021-03-30 17:35:57'),
(245, 76, 'SGD60631424819821617105956.png', 'SGD', 0, '2021-03-30 17:35:57', '2021-03-30 17:35:57'),
(246, 77, 'GP60631657851cc1617106519.jpg', 'GP', 0, '2021-03-30 17:45:19', '2021-03-30 17:45:19'),
(247, 77, 'GP606316578560b1617106519.jpg', 'GP', 0, '2021-03-30 17:45:19', '2021-03-30 17:45:19'),
(248, 77, 'GD60631846c34591617107014.jpg', 'GD', 0, '2021-03-30 17:53:34', '2021-03-30 17:53:34'),
(249, 77, 'SGD60631846c338d1617107014.png', 'SGD', 0, '2021-03-30 17:53:34', '2021-03-30 17:53:34'),
(250, 78, 'GP60631bc15c4c41617107905.jpg', 'GP', 0, '2021-03-30 18:08:25', '2021-03-30 18:08:25'),
(251, 78, 'GD60631ed666bf31617108694.jpg', 'GD', 0, '2021-03-30 18:21:34', '2021-03-30 18:21:34'),
(252, 78, 'GD60631ed666ce81617108694.jpg', 'GD', 0, '2021-03-30 18:21:34', '2021-03-30 18:21:34'),
(253, 78, 'SGD60631ed666b021617108694.png', 'SGD', 0, '2021-03-30 18:21:34', '2021-03-30 18:21:34'),
(254, 81, 'GP6064558bd8d381617188235.jpg', 'GP', 0, '2021-03-31 16:27:15', '2021-03-31 16:27:15'),
(255, 81, 'GD60645d4aca7631617190218.jpg', 'GD', 0, '2021-03-31 17:00:18', '2021-03-31 17:00:18'),
(256, 81, 'GD60645d4aca8831617190218.jpg', 'GD', 0, '2021-03-31 17:00:18', '2021-03-31 17:00:18'),
(257, 81, 'SGD60645d4aca6611617190218.png', 'SGD', 0, '2021-03-31 17:00:18', '2021-03-31 17:00:18'),
(258, 79, 'GP606461b9274f91617191353.jpg', 'GP', 0, '2021-03-31 17:19:13', '2021-03-31 17:19:13'),
(259, 79, 'GP606461b927e6c1617191353.jpg', 'GP', 0, '2021-03-31 17:19:13', '2021-03-31 17:19:13'),
(260, 79, 'GD60646241afd4c1617191489.jpg', 'GD', 0, '2021-03-31 17:21:29', '2021-03-31 17:21:29'),
(261, 79, 'GD60646241aff301617191489.jpg', 'GD', 0, '2021-03-31 17:21:29', '2021-03-31 17:21:29'),
(262, 79, 'SGD60646241afc631617191489.png', 'SGD', 0, '2021-03-31 17:21:29', '2021-03-31 17:21:29'),
(263, 82, 'LRP60647b719c1c71617197937.jpeg', 'LRP', 0, '2021-03-31 19:08:57', '2021-03-31 19:08:57'),
(264, 82, 'LRD60647b719c2ef1617197937.jpeg', 'LRD', 0, '2021-03-31 19:08:57', '2021-03-31 19:08:57'),
(265, 82, 'GP60647b719c3d81617197937.jpeg', 'GP', 0, '2021-03-31 19:08:57', '2021-03-31 19:08:57'),
(266, 82, 'GD60647b719c5291617197937.jpeg', 'GD', 0, '2021-03-31 19:08:57', '2021-03-31 19:08:57'),
(267, 83, 'GP6066d5d38f3d71617352147.jpg', 'GP', 0, '2021-04-02 13:59:08', '2021-04-02 13:59:08'),
(268, 83, 'SGD6066ddcf2db9e1617354191.png', 'SGD', 0, '2021-04-02 14:33:11', '2021-04-02 14:33:11'),
(272, 85, 'GD606716f29dfda1617368818.jpg', 'GD', 0, '2021-04-02 18:36:58', '2021-04-02 18:36:58'),
(271, 85, 'GP60670b893894f1617365897.jpg', 'GP', 0, '2021-04-02 17:48:18', '2021-04-02 17:48:18'),
(273, 85, 'SGD606716f29dec91617368818.png', 'SGD', 0, '2021-04-02 18:36:58', '2021-04-02 18:36:58'),
(274, 86, 'GP606ab6f9572e81617606393.jpg', 'GP', 0, '2021-04-05 12:36:34', '2021-04-05 12:36:34'),
(275, 86, 'GD606ac9e09fe161617611232.jpg', 'GD', 0, '2021-04-05 13:57:12', '2021-04-05 13:57:12'),
(276, 86, 'GD606ac9e0a01991617611232.jpg', 'GD', 0, '2021-04-05 13:57:12', '2021-04-05 13:57:12'),
(277, 86, 'SGD606ac9e09f9421617611232.png', 'SGD', 0, '2021-04-05 13:57:12', '2021-04-05 13:57:12'),
(278, 87, 'GP606adb4a3c3321617615690.jpg', 'GP', 0, '2021-04-05 15:11:30', '2021-04-05 15:11:30'),
(279, 87, 'GD606ae85722e161617619031.jpg', 'GD', 0, '2021-04-05 16:07:11', '2021-04-05 16:07:11'),
(280, 87, 'GD606ae85722f941617619031.jpg', 'GD', 0, '2021-04-05 16:07:11', '2021-04-05 16:07:11'),
(281, 87, 'SGD606ae85722d2c1617619031.png', 'SGD', 0, '2021-04-05 16:07:11', '2021-04-05 16:07:11'),
(282, 103, 'SGD606edd00a947d1617878272.png', 'SGD', 0, '2021-04-08 16:07:52', '2021-04-08 16:07:52'),
(283, 88, 'SGD606edd094322a1617878281.png', 'SGD', 0, '2021-04-08 16:08:01', '2021-04-08 16:08:01'),
(284, 120, 'GP606edd42d191f1617878338.jpg', 'GP', 0, '2021-04-08 16:08:58', '2021-04-08 16:08:58'),
(285, 120, 'SGD606ede20740d41617878560.png', 'SGD', 0, '2021-04-08 16:12:40', '2021-04-08 16:12:40'),
(286, 119, 'SGD606ede25f25681617878565.png', 'SGD', 0, '2021-04-08 16:12:45', '2021-04-08 16:12:45'),
(287, 201, 'LRP608a48fe7bb031619675390.jpeg', 'LRP', 0, '2021-04-29 11:19:50', '2021-04-29 11:19:50'),
(288, 212, 'LRP608fe4fc504641620043004.jpeg', 'LRP', 0, '2021-05-03 17:26:44', '2021-05-03 17:26:44'),
(289, 213, 'LRP608fe678714651620043384.jpeg', 'LRP', 0, '2021-05-03 17:33:04', '2021-05-03 17:33:04');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_logs`
--

CREATE TABLE `tbl_order_logs` (
  `id` bigint(20) NOT NULL,
  `logs` text DEFAULT NULL,
  `order_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 for user or 0 for admin'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_order_logs`
--

INSERT INTO `tbl_order_logs` (`id`, `logs`, `order_id`, `created_at`, `type`) VALUES
(366, 'New Invoice #29 Created By Devrishi', 31, '2021-03-11 12:36:45', 0),
(365, 'New Invoice #29 Created By Devrishi', 30, '2021-03-11 12:36:45', 0),
(364, 'New Invoice #28 Created By Devrishi', 44, '2021-03-11 12:36:20', 0),
(350, 'New Invoice #102 Created By Technomads Developer', 31, '2021-03-09 12:28:30', 0),
(363, 'New Invoice #28 Created By Devrishi', 43, '2021-03-11 12:36:20', 0),
(349, 'Order Delivered By Hardik Patel', 47, '2021-03-09 11:47:09', 0),
(361, 'New Invoice #28 Created By Devrishi', 41, '2021-03-11 12:36:20', 0),
(362, 'New Invoice #28 Created By Devrishi', 42, '2021-03-11 12:36:20', 0),
(360, 'New Invoice #28 Created By Devrishi', 39, '2021-03-11 12:36:20', 0),
(354, 'New Invoice #27 Created By Devrishi', 33, '2021-03-11 12:35:46', 0),
(355, 'New Invoice #27 Created By Devrishi', 34, '2021-03-11 12:35:46', 0),
(356, 'New Invoice #27 Created By Devrishi', 35, '2021-03-11 12:35:46', 0),
(357, 'New Invoice #27 Created By Devrishi', 36, '2021-03-11 12:35:46', 0),
(358, 'New Invoice #27 Created By Devrishi', 37, '2021-03-11 12:35:46', 0),
(359, 'New Invoice #27 Created By Devrishi', 38, '2021-03-11 12:35:46', 0),
(348, 'Order Delivered By Hardik Patel', 46, '2021-03-09 11:47:03', 0),
(353, 'Payment Paid is Marked By Hardik Patel', 47, '2021-03-09 17:40:39', 0),
(352, 'Order Details Edited By Devrishi', 8, '2021-03-09 15:27:19', 0),
(344, 'Order Delivered By Hardik Patel', 43, '2021-03-08 17:29:08', 0),
(345, 'Order Delivered By Hardik Patel', 45, '2021-03-08 17:29:13', 0),
(346, 'Order Details Edited By Hardik Patel', 46, '2021-03-09 11:34:32', 0),
(347, 'Order Details Edited By Hardik Patel', 47, '2021-03-09 11:46:13', 0),
(372, 'Order Details Edited By Hardik Patel', 48, '2021-03-12 12:46:05', 0),
(371, 'Order Created By Hardik Patel', 49, '2021-03-12 12:30:30', 0),
(370, 'Order Created By Hardik Patel', 48, '2021-03-12 12:17:35', 0),
(351, 'New Invoice #26 Created By Hardik Patel', 47, '2021-03-09 13:12:17', 0),
(342, 'Order Delivered By Hardik Patel', 44, '2021-03-08 17:28:54', 0),
(343, 'Order Delivered By Hardik Patel', 42, '2021-03-08 17:29:01', 0),
(36, 'Order Created By Hardik Patel', 6, '2021-02-15 13:15:38', 0),
(37, 'Order Assigned to UDAYSINGH RAJPUROHIT By Hardik Patel', 6, '2021-02-15 13:17:43', 0),
(38, 'Order Assigned to Nilay Navnitlal Shah By Hardik Patel', 6, '2021-02-15 13:21:58', 0),
(39, 'Order Details Edited By Hardik Patel', 6, '2021-02-15 13:28:45', 0),
(40, 'Order Details Edited By Jay', 6, '2021-02-15 13:28:59', 0),
(41, 'Order Delivered By Hardik Patel [Payment Paid]', 6, '2021-02-15 16:12:14', 0),
(42, 'Order Created By Hardik Patel', 7, '2021-02-16 12:48:19', 0),
(43, 'Order Assigned to Nilay Navnitlal Shah By Hardik Patel', 7, '2021-02-16 12:49:00', 0),
(44, 'Order Details Edited By Hardik Patel', 7, '2021-02-16 12:56:07', 0),
(45, 'Order Delivered By Hardik Patel [Payment Paid]', 7, '2021-02-17 14:28:37', 0),
(46, 'Order Created By Hardik Patel', 8, '2021-02-19 14:10:17', 0),
(47, 'Order Details Edited By Hardik Patel', 8, '2021-02-19 14:14:18', 0),
(48, 'Order Details Edited By Hardik Patel', 8, '2021-02-19 14:50:45', 0),
(49, 'Order Details Edited By Hardik Patel', 8, '2021-02-19 15:00:31', 0),
(50, 'Order Details Edited By Hardik Patel', 8, '2021-02-19 15:01:26', 0),
(51, 'Order Details Edited By Hardik Patel', 8, '2021-02-19 15:01:39', 0),
(52, 'Order Details Edited By Hardik Patel', 8, '2021-02-19 15:02:33', 0),
(53, 'Order Details Edited By Hardik Patel', 8, '2021-02-19 15:05:08', 0),
(54, 'Order Details Edited By Hardik Patel', 8, '2021-02-19 15:39:00', 0),
(55, 'Order Details Edited By Hardik Patel', 8, '2021-02-19 15:43:16', 0),
(56, 'Order Assigned to Nilay Navnitlal Shah By Hardik Patel', 8, '2021-02-19 15:43:52', 0),
(57, 'Order Delivered By Hardik Patel [Payment Pending]', 8, '2021-02-19 15:44:05', 0),
(58, 'Order Created By Hardik Patel', 9, '2021-02-19 16:29:10', 0),
(59, 'Order Assigned to Nilay Navnitlal Shah By Hardik Patel', 9, '2021-02-19 16:29:26', 0),
(60, 'Order Delivered By Hardik Patel [Payment Pending]', 9, '2021-02-19 16:29:36', 0),
(61, 'Order Created By Hardik Patel', 10, '2021-02-19 16:50:29', 0),
(62, 'Order Details Edited By Hardik Patel', 10, '2021-02-19 16:54:02', 0),
(63, 'Order Details Edited By Hardik Patel', 10, '2021-02-19 17:03:52', 0),
(64, 'Order Created By Technomads Developer', 11, '2021-02-19 20:01:41', 0),
(65, 'Order Created By Technomads Developer', 12, '2021-02-19 20:03:28', 0),
(66, 'Order Created By Hardik Patel', 13, '2021-02-22 14:04:17', 0),
(67, 'Order Created By Hardik Patel', 14, '2021-02-22 14:10:30', 0),
(68, 'Order Details Edited By Hardik Patel', 13, '2021-02-22 14:11:11', 0),
(69, 'Order Created By Hardik Patel', 15, '2021-02-22 14:19:15', 0),
(70, 'Order Details Edited By Hardik Patel', 13, '2021-02-22 15:12:40', 0),
(71, 'Order Details Edited By Hardik Patel', 13, '2021-02-22 15:13:07', 0),
(72, 'Order Details Edited By Hardik Patel', 14, '2021-02-22 15:21:03', 0),
(73, 'Order Details Edited By Hardik Patel', 13, '2021-02-22 15:43:27', 0),
(74, 'Order Details Edited By Hardik Patel', 14, '2021-02-22 15:44:06', 0),
(75, 'Order Details Edited By Hardik Patel', 15, '2021-02-22 15:48:02', 0),
(76, 'Order Assigned to Nilay Navnitlal Shah By Hardik Patel', 15, '2021-02-22 15:49:09', 0),
(77, 'Order Assigned to Nilay Navnitlal Shah By Hardik Patel', 14, '2021-02-22 15:49:09', 0),
(78, 'Order Assigned to Nilay Navnitlal Shah By Hardik Patel', 13, '2021-02-22 15:49:09', 0),
(79, 'Order Delivered By Hardik Patel', 15, '2021-02-22 16:34:40', 0),
(80, 'Order Delivered By Hardik Patel', 14, '2021-02-22 16:34:53', 0),
(81, 'Order Delivered By Hardik Patel', 15, '2021-02-22 16:34:53', 0),
(82, 'Order Delivered By Hardik Patel', 14, '2021-02-22 16:35:02', 0),
(83, 'Order Delivered By Hardik Patel', 13, '2021-02-22 16:35:03', 0),
(84, 'Order Delivered By Hardik Patel', 15, '2021-02-22 16:35:03', 0),
(85, 'Order Details Edited By Technomads Developer', 15, '2021-02-22 17:17:50', 0),
(86, 'Order Details Edited By Technomads Developer', 15, '2021-02-22 17:18:21', 0),
(87, 'Order Created By Hardik Patel', 16, '2021-02-22 17:23:20', 0),
(88, 'Order Details Edited By Hardik Patel', 16, '2021-02-22 17:26:11', 0),
(89, 'Order Details Edited By Technomads Developer', 14, '2021-02-22 17:26:49', 0),
(90, 'Order Details Edited By Hardik Patel', 16, '2021-02-22 17:33:26', 0),
(91, 'Order Details Edited By Hardik Patel', 16, '2021-02-22 17:35:29', 0),
(92, 'Order Created By Hardik Patel', 17, '2021-02-22 17:51:59', 0),
(93, 'Order Details Edited By Hardik Patel', 17, '2021-02-22 17:55:46', 0),
(94, 'Order Assigned to Nilay Navnitlal Shah By Hardik Patel', 17, '2021-02-22 17:56:39', 0),
(95, 'Order Assigned to Nilay Navnitlal Shah By Hardik Patel', 17, '2021-02-22 17:56:47', 0),
(96, 'Order Assigned to Nilay Navnitlal Shah By Hardik Patel', 16, '2021-02-22 17:57:10', 0),
(97, 'Order Assigned to Nilay Navnitlal Shah By Hardik Patel', 16, '2021-02-22 17:57:15', 0),
(98, 'Order Delivered By Hardik Patel', 16, '2021-02-22 17:57:51', 0),
(99, 'Order Delivered By Hardik Patel', 17, '2021-02-22 17:58:17', 0),
(100, 'Order Created By Hardik Patel', 18, '2021-02-23 12:00:31', 0),
(101, 'Order Details Edited By Jay', 18, '2021-02-23 12:03:16', 0),
(102, 'Order Details Edited By Hardik Patel', 18, '2021-02-23 12:04:27', 0),
(103, 'Order Assigned to Shoaib Sheikh By Jay', 18, '2021-02-23 12:04:59', 0),
(104, 'Order Created By Hardik Patel', 19, '2021-02-23 12:44:40', 0),
(105, 'Order Details Edited By Hardik Patel', 18, '2021-02-23 12:45:33', 0),
(106, 'Order Details Edited By Hardik Patel', 19, '2021-02-23 13:00:36', 0),
(107, 'Order Assigned to ShivKaran By Hardik Patel', 19, '2021-02-23 13:01:36', 0),
(108, 'Order Created By Hardik Patel', 20, '2021-02-23 13:19:26', 0),
(109, 'Order Details Edited By Hardik Patel', 19, '2021-02-23 13:20:06', 0),
(110, 'Order Assigned to ShivKaran By Hardik Patel', 20, '2021-02-23 13:21:08', 0),
(111, 'Order Created By Hardik Patel', 21, '2021-02-23 13:33:08', 0),
(112, 'Order Assigned to ShivKaran By Hardik Patel', 21, '2021-02-23 13:33:28', 0),
(113, 'Order Details Edited By Hardik Patel', 18, '2021-02-23 13:34:59', 0),
(114, 'Order Details Edited By Hardik Patel', 19, '2021-02-23 13:39:16', 0),
(115, 'Order Details Edited By Hardik Patel', 20, '2021-02-23 13:39:52', 0),
(116, 'Order Created By Hardik Patel', 22, '2021-02-23 14:00:03', 0),
(117, 'Order Assigned to ShivKaran By Hardik Patel', 22, '2021-02-23 14:01:45', 0),
(118, 'Order Created By Hardik Patel', 23, '2021-02-23 14:44:33', 0),
(119, 'Order Details Edited By Hardik Patel', 19, '2021-02-23 14:48:09', 0),
(120, 'Order Details Edited By Hardik Patel', 19, '2021-02-23 14:50:27', 0),
(121, 'Order Details Edited By Hardik Patel', 20, '2021-02-23 14:55:51', 0),
(122, 'Order Details Edited By Hardik Patel', 22, '2021-02-23 15:01:08', 0),
(123, 'Order Details Edited By Hardik Patel', 23, '2021-02-23 15:23:17', 0),
(124, 'Order Details Edited By Hardik Patel', 21, '2021-02-23 15:28:38', 0),
(125, 'Order Assigned to Shoaib Sheikh By Hardik Patel', 23, '2021-02-23 15:29:36', 0),
(126, 'Order Details Edited By Hardik Patel', 18, '2021-02-23 16:19:08', 0),
(127, 'Order Delivered By Hardik Patel', 18, '2021-02-23 16:19:34', 0),
(128, 'Order Details Edited By Hardik Patel', 21, '2021-02-23 16:25:29', 0),
(129, 'Order Details Edited By Hardik Patel', 23, '2021-02-23 16:36:28', 0),
(130, 'Order Delivered By Devrishi', 23, '2021-02-23 16:39:20', 0),
(131, 'Order Delivered By Devrishi', 22, '2021-02-23 16:39:42', 0),
(132, 'Order Delivered By Devrishi', 21, '2021-02-23 16:39:50', 0),
(133, 'Order Delivered By Devrishi', 20, '2021-02-23 16:39:54', 0),
(134, 'Order Delivered By Devrishi', 19, '2021-02-23 16:39:58', 0),
(135, 'Payment Paid is Marked By Devrishi', 23, '2021-02-23 16:40:22', 0),
(136, 'Payment Paid is Marked By Devrishi', 23, '2021-02-23 16:40:30', 0),
(137, 'Payment Paid is Marked By Devrishi', 18, '2021-02-23 16:40:30', 0),
(138, 'Order Details Edited By Devrishi', 23, '2021-02-23 17:00:59', 0),
(139, 'Order Created By Hardik Patel', 24, '2021-02-24 11:22:39', 0),
(140, 'Order Assigned to Shoaib Sheikh By Devrishi', 24, '2021-02-24 12:01:03', 0),
(141, 'Order Delivered By Devrishi', 24, '2021-02-24 12:01:19', 0),
(142, 'Order Details Edited By Devrishi', 24, '2021-02-24 12:04:13', 0),
(143, 'Order Details Edited By Devrishi', 24, '2021-02-24 12:06:15', 0),
(144, 'Order Details Edited By Devrishi', 6, '2021-02-24 12:12:21', 0),
(145, 'Order Details Edited By Devrishi', 7, '2021-02-24 12:14:45', 0),
(146, 'Order Details Edited By Devrishi', 7, '2021-02-24 12:15:42', 0),
(147, 'Order Details Edited By Devrishi', 6, '2021-02-24 12:17:10', 0),
(148, 'Order Details Edited By Devrishi', 8, '2021-02-24 12:21:46', 0),
(149, 'Order Details Edited By Devrishi', 9, '2021-02-24 12:24:13', 0),
(150, 'Order Details Edited By Devrishi', 13, '2021-02-24 12:25:12', 0),
(151, 'Order Details Edited By Devrishi', 14, '2021-02-24 12:25:59', 0),
(152, 'Order Details Edited By Devrishi', 15, '2021-02-24 12:26:36', 0),
(153, 'Order Details Edited By Devrishi', 16, '2021-02-24 12:27:51', 0),
(154, 'Order Details Edited By Devrishi', 17, '2021-02-24 12:30:04', 0),
(155, 'Order Details Edited By Devrishi', 18, '2021-02-24 12:31:07', 0),
(156, 'Order Details Edited By Devrishi', 19, '2021-02-24 12:33:41', 0),
(157, 'Order Details Edited By Devrishi', 20, '2021-02-24 12:34:24', 0),
(158, 'Order Details Edited By Devrishi', 21, '2021-02-24 12:38:34', 0),
(159, 'Order Details Edited By Devrishi', 22, '2021-02-24 12:40:02', 0),
(160, 'Order Details Edited By Devrishi', 23, '2021-02-24 12:41:11', 0),
(161, 'Order Details Edited By Devrishi', 24, '2021-02-24 12:42:03', 0),
(162, 'Order Details Edited By Devrishi', 7, '2021-02-25 13:05:06', 0),
(163, 'Order Created By Hardik Patel', 25, '2021-03-01 11:45:25', 0),
(164, 'Order Created By Hardik Patel', 26, '2021-03-01 12:08:20', 0),
(165, 'Order Assigned to Mohammad Fahad Shaikh By Jay', 26, '2021-03-01 12:08:48', 0),
(166, 'Order Details Edited By Jay', 26, '2021-03-01 12:10:59', 0),
(167, 'Order Details Edited By Jay', 26, '2021-03-01 12:11:03', 0),
(168, 'Order Created By Hardik Patel', 27, '2021-03-01 12:41:08', 0),
(169, 'Order Created By Hardik Patel', 28, '2021-03-01 12:52:35', 0),
(170, 'Order Details Edited By Hardik Patel', 26, '2021-03-01 13:10:58', 0),
(171, 'Order Assigned to Shaikh Ezaz By Devrishi', 28, '2021-03-01 13:20:15', 0),
(172, 'Order Assigned to Shaikh Ezaz By Devrishi', 27, '2021-03-01 13:20:16', 0),
(173, 'Order Assigned to Shaikh Ezaz By Devrishi', 25, '2021-03-01 13:20:16', 0),
(174, 'Order Delivered By Devrishi', 26, '2021-03-01 13:20:32', 0),
(175, 'Order Delivered By Devrishi', 28, '2021-03-01 13:20:50', 0),
(176, 'Order Details Edited By Devrishi', 26, '2021-03-01 14:02:47', 0),
(177, 'Order Details Edited By Hardik Patel', 25, '2021-03-01 15:02:28', 0),
(178, 'Order Created By Devrishi', 29, '2021-03-01 15:12:34', 0),
(179, 'Order Assigned to Mohammad Fahad Shaikh By Devrishi', 29, '2021-03-01 15:12:55', 0),
(180, 'Order Details Edited By Devrishi', 29, '2021-03-01 15:14:47', 0),
(181, 'Payment Paid is Marked By Devrishi', 26, '2021-03-01 15:17:42', 0),
(182, 'Order Delivered By Devrishi', 25, '2021-03-01 15:17:56', 0),
(183, 'Order Delivered By Devrishi', 27, '2021-03-01 15:47:15', 0),
(184, 'Order Details Edited By Hardik Patel', 29, '2021-03-01 16:22:45', 0),
(185, 'Order Details Edited By Hardik Patel', 29, '2021-03-02 10:41:00', 0),
(186, 'Order Delivered By Hardik Patel', 29, '2021-03-02 10:54:12', 0),
(187, 'Order Created By Hardik Patel', 30, '2021-03-02 11:39:16', 0),
(188, 'Order Created By Hardik Patel', 31, '2021-03-02 12:02:58', 0),
(189, 'Order Created By Hardik Patel', 32, '2021-03-02 12:28:38', 0),
(190, 'Order Created By Hardik Patel', 33, '2021-03-02 12:54:44', 0),
(191, 'Order Created By Hardik Patel', 34, '2021-03-02 13:09:14', 0),
(192, 'Order Assigned to Shaikh Ezaz By Hardik Patel', 34, '2021-03-02 13:16:47', 0),
(193, 'Order Assigned to Shaikh Ezaz By Hardik Patel', 33, '2021-03-02 13:17:03', 0),
(194, 'Order Assigned to Mohammad Fahad Shaikh By Hardik Patel', 30, '2021-03-02 13:17:41', 0),
(195, 'Order Assigned to Mohammad Fahad Shaikh By Hardik Patel', 31, '2021-03-02 13:17:50', 0),
(196, 'Order Assigned to Mohammad Fahad Shaikh By Hardik Patel', 32, '2021-03-02 13:17:56', 0),
(197, 'Order Details Edited By Devrishi', 13, '2021-03-02 14:02:44', 0),
(198, 'Order Details Edited By Devrishi', 15, '2021-03-02 14:04:25', 0),
(199, 'Order Details Edited By Devrishi', 16, '2021-03-02 14:06:00', 0),
(200, 'Order Details Edited By Devrishi', 17, '2021-03-02 14:07:04', 0),
(201, 'Order Details Edited By Hardik Patel', 30, '2021-03-02 14:08:04', 0),
(202, 'Order Details Edited By Devrishi', 19, '2021-03-02 14:09:14', 0),
(203, 'Order Details Edited By Devrishi', 21, '2021-03-02 14:10:41', 0),
(204, 'Order Details Edited By Hardik Patel', 33, '2021-03-02 14:12:57', 0),
(205, 'Order Details Edited By Devrishi', 22, '2021-03-02 14:16:39', 0),
(206, 'Order Details Edited By Devrishi', 24, '2021-03-02 14:17:32', 0),
(207, 'Order Details Edited By Devrishi', 25, '2021-03-02 14:20:27', 0),
(208, 'Order Details Edited By Hardik Patel', 31, '2021-03-02 14:36:31', 0),
(209, 'Order Details Edited By Hardik Patel', 32, '2021-03-02 15:37:08', 0),
(210, 'Order Delivered By Hardik Patel', 30, '2021-03-02 15:59:26', 0),
(211, 'Order Delivered By Hardik Patel', 31, '2021-03-02 15:59:34', 0),
(212, 'Order Delivered By Hardik Patel', 32, '2021-03-02 15:59:39', 0),
(213, 'Order Delivered By Hardik Patel', 33, '2021-03-02 15:59:47', 0),
(214, 'Payment Paid is Marked By Devrishi', 29, '2021-03-02 16:02:12', 0),
(215, 'Order Details Edited By Hardik Patel', 34, '2021-03-02 16:10:55', 0),
(216, 'Order Delivered By Hardik Patel', 34, '2021-03-02 16:11:27', 0),
(217, 'Order Created By Hardik Patel', 35, '2021-03-04 15:17:39', 0),
(218, 'Order Created By Hardik Patel', 36, '2021-03-04 15:24:28', 0),
(219, 'Order Created By Hardik Patel', 37, '2021-03-04 15:36:37', 0),
(220, 'Order Created By Hardik Patel', 38, '2021-03-04 15:44:57', 0),
(221, 'Order Created By Hardik Patel', 39, '2021-03-04 15:50:27', 0),
(222, 'Order Assigned to Shaikh Ezaz By Hardik Patel', 39, '2021-03-04 15:51:20', 0),
(223, 'Order Assigned to Shaikh Ezaz By Hardik Patel', 38, '2021-03-04 15:51:29', 0),
(224, 'Order Assigned to Shaikh Ezaz By Hardik Patel', 37, '2021-03-04 15:51:39', 0),
(225, 'Order Assigned to Mohammad Fahad Shaikh By Hardik Patel', 36, '2021-03-04 15:51:47', 0),
(226, 'Order Assigned to Mohammad Fahad Shaikh By Hardik Patel', 35, '2021-03-04 15:51:55', 0),
(227, 'Order Delivered By Hardik Patel', 37, '2021-03-04 17:13:58', 0),
(228, 'Order Delivered By Hardik Patel', 35, '2021-03-04 17:14:04', 0),
(229, 'Order Delivered By Hardik Patel', 36, '2021-03-04 17:14:15', 0),
(230, 'Order Delivered By Hardik Patel', 39, '2021-03-04 17:15:21', 0),
(231, 'Order Details Edited By Devrishi', 39, '2021-03-05 12:37:33', 0),
(232, 'Order Delivered By Devrishi', 38, '2021-03-05 12:39:23', 0),
(233, 'Order Details Edited By Devrishi', 38, '2021-03-05 12:40:03', 0),
(234, 'Order Details Edited By Devrishi', 37, '2021-03-05 12:41:04', 0),
(235, 'Order Details Edited By Devrishi', 36, '2021-03-05 12:41:51', 0),
(236, 'Order Details Edited By Devrishi', 35, '2021-03-05 12:42:51', 0),
(237, 'Order Created By Hardik Patel', 40, '2021-03-05 13:07:12', 0),
(238, 'Order Assigned to Shaikh Ezaz By Hardik Patel', 40, '2021-03-05 13:07:34', 0),
(239, 'Order Details Edited By Hardik Patel', 40, '2021-03-05 13:09:21', 0),
(240, 'Order Details Edited By Hardik Patel', 40, '2021-03-05 13:18:26', 0),
(241, 'Order Details Edited By Hardik Patel', 40, '2021-03-05 15:14:57', 0),
(242, 'Order Delivered By Hardik Patel', 40, '2021-03-05 15:16:00', 0),
(243, 'Payment Paid is Marked By Hardik Patel', 40, '2021-03-05 15:18:18', 0),
(244, 'Payment Paid is Marked By Hardik Patel', 40, '2021-03-05 15:19:34', 0),
(245, 'Payment Paid is Marked By Hardik Patel', 8, '2021-03-05 15:19:35', 0),
(246, 'Payment Paid is Marked By Hardik Patel', 8, '2021-03-05 15:19:43', 0),
(247, 'Payment Paid is Marked By Hardik Patel', 40, '2021-03-05 15:19:43', 0),
(248, 'Payment Paid is Marked By Hardik Patel', 9, '2021-03-05 15:19:43', 0),
(249, 'Payment Paid is Marked By Hardik Patel', 8, '2021-03-05 15:19:56', 0),
(250, 'Payment Paid is Marked By Hardik Patel', 40, '2021-03-05 15:19:56', 0),
(251, 'Payment Paid is Marked By Hardik Patel', 9, '2021-03-05 15:19:56', 0),
(252, 'Payment Paid is Marked By Hardik Patel', 13, '2021-03-05 15:19:57', 0),
(253, 'Payment Paid is Marked By Hardik Patel', 14, '2021-03-05 15:21:04', 0),
(254, 'Payment Paid is Marked By Hardik Patel', 15, '2021-03-05 15:21:14', 0),
(255, 'Payment Paid is Marked By Hardik Patel', 14, '2021-03-05 15:21:14', 0),
(256, 'Payment Paid is Marked By Hardik Patel', 14, '2021-03-05 15:21:25', 0),
(257, 'Payment Paid is Marked By Hardik Patel', 15, '2021-03-05 15:21:25', 0),
(258, 'Payment Paid is Marked By Hardik Patel', 16, '2021-03-05 15:21:25', 0),
(259, 'Payment Paid is Marked By Hardik Patel', 15, '2021-03-05 15:21:34', 0),
(260, 'Payment Paid is Marked By Hardik Patel', 16, '2021-03-05 15:21:34', 0),
(261, 'Payment Paid is Marked By Hardik Patel', 17, '2021-03-05 15:21:34', 0),
(262, 'Payment Paid is Marked By Hardik Patel', 14, '2021-03-05 15:21:34', 0),
(263, 'Payment Paid is Marked By Hardik Patel', 15, '2021-03-05 15:21:51', 0),
(264, 'Payment Paid is Marked By Hardik Patel', 16, '2021-03-05 15:21:51', 0),
(265, 'Payment Paid is Marked By Hardik Patel', 14, '2021-03-05 15:21:51', 0),
(266, 'Payment Paid is Marked By Hardik Patel', 17, '2021-03-05 15:21:51', 0),
(267, 'Payment Paid is Marked By Hardik Patel', 19, '2021-03-05 15:21:52', 0),
(268, 'Payment Paid is Marked By Hardik Patel', 16, '2021-03-05 15:22:03', 0),
(269, 'Payment Paid is Marked By Hardik Patel', 14, '2021-03-05 15:22:03', 0),
(270, 'Payment Paid is Marked By Hardik Patel', 19, '2021-03-05 15:22:03', 0),
(271, 'Payment Paid is Marked By Hardik Patel', 17, '2021-03-05 15:22:03', 0),
(272, 'Payment Paid is Marked By Hardik Patel', 20, '2021-03-05 15:22:04', 0),
(273, 'Payment Paid is Marked By Hardik Patel', 15, '2021-03-05 15:22:04', 0),
(274, 'Payment Paid is Marked By Hardik Patel', 14, '2021-03-05 15:22:20', 0),
(275, 'Payment Paid is Marked By Hardik Patel', 15, '2021-03-05 15:22:20', 0),
(276, 'Payment Paid is Marked By Hardik Patel', 17, '2021-03-05 15:22:21', 0),
(277, 'Payment Paid is Marked By Hardik Patel', 19, '2021-03-05 15:22:21', 0),
(278, 'Payment Paid is Marked By Hardik Patel', 16, '2021-03-05 15:22:21', 0),
(279, 'Payment Paid is Marked By Hardik Patel', 21, '2021-03-05 15:22:21', 0),
(280, 'Payment Paid is Marked By Hardik Patel', 20, '2021-03-05 15:22:21', 0),
(281, 'Payment Paid is Marked By Hardik Patel', 16, '2021-03-05 15:22:43', 0),
(282, 'Payment Paid is Marked By Hardik Patel', 14, '2021-03-05 15:22:43', 0),
(283, 'Payment Paid is Marked By Hardik Patel', 17, '2021-03-05 15:22:44', 0),
(284, 'Payment Paid is Marked By Hardik Patel', 20, '2021-03-05 15:22:44', 0),
(285, 'Payment Paid is Marked By Hardik Patel', 15, '2021-03-05 15:22:44', 0),
(286, 'Payment Paid is Marked By Hardik Patel', 21, '2021-03-05 15:22:44', 0),
(287, 'Payment Paid is Marked By Hardik Patel', 19, '2021-03-05 15:22:45', 0),
(288, 'Payment Paid is Marked By Hardik Patel', 22, '2021-03-05 15:22:46', 0),
(289, 'Payment Paid is Marked By Hardik Patel', 14, '2021-03-05 15:23:05', 0),
(290, 'Payment Paid is Marked By Hardik Patel', 21, '2021-03-05 15:23:05', 0),
(291, 'Payment Paid is Marked By Hardik Patel', 16, '2021-03-05 15:23:05', 0),
(292, 'Payment Paid is Marked By Hardik Patel', 17, '2021-03-05 15:23:05', 0),
(293, 'Payment Paid is Marked By Hardik Patel', 19, '2021-03-05 15:23:05', 0),
(294, 'Payment Paid is Marked By Hardik Patel', 20, '2021-03-05 15:23:05', 0),
(295, 'Payment Paid is Marked By Hardik Patel', 15, '2021-03-05 15:23:05', 0),
(296, 'Payment Paid is Marked By Hardik Patel', 24, '2021-03-05 15:23:06', 0),
(297, 'Payment Paid is Marked By Hardik Patel', 22, '2021-03-05 15:23:11', 0),
(298, 'Payment Paid is Marked By Hardik Patel', 19, '2021-03-05 15:23:56', 0),
(299, 'Payment Paid is Marked By Hardik Patel', 14, '2021-03-05 15:23:56', 0),
(300, 'Payment Paid is Marked By Hardik Patel', 15, '2021-03-05 15:23:56', 0),
(301, 'Payment Paid is Marked By Hardik Patel', 17, '2021-03-05 15:23:56', 0),
(302, 'Payment Paid is Marked By Hardik Patel', 16, '2021-03-05 15:23:56', 0),
(303, 'Payment Paid is Marked By Hardik Patel', 20, '2021-03-05 15:24:01', 0),
(304, 'Payment Paid is Marked By Hardik Patel', 25, '2021-03-05 15:24:01', 0),
(305, 'Payment Paid is Marked By Hardik Patel', 21, '2021-03-05 15:24:01', 0),
(306, 'Payment Paid is Marked By Hardik Patel', 22, '2021-03-05 15:24:01', 0),
(307, 'Payment Paid is Marked By Hardik Patel', 24, '2021-03-05 15:24:02', 0),
(308, 'Payment Paid is Marked By Hardik Patel', 17, '2021-03-05 15:25:42', 0),
(309, 'Payment Paid is Marked By Hardik Patel', 19, '2021-03-05 15:25:42', 0),
(310, 'Payment Paid is Marked By Hardik Patel', 15, '2021-03-05 15:25:42', 0),
(311, 'Payment Paid is Marked By Hardik Patel', 16, '2021-03-05 15:25:42', 0),
(312, 'Payment Paid is Marked By Hardik Patel', 14, '2021-03-05 15:25:42', 0),
(313, 'Payment Paid is Marked By Hardik Patel', 24, '2021-03-05 15:25:46', 0),
(314, 'Payment Paid is Marked By Hardik Patel', 22, '2021-03-05 15:25:47', 0),
(315, 'Payment Paid is Marked By Hardik Patel', 27, '2021-03-05 15:25:47', 0),
(316, 'Payment Paid is Marked By Hardik Patel', 21, '2021-03-05 15:25:49', 0),
(317, 'Payment Paid is Marked By Hardik Patel', 20, '2021-03-05 15:25:49', 0),
(318, 'Payment Paid is Marked By Hardik Patel', 25, '2021-03-05 15:25:50', 0),
(319, 'Payment Paid is Marked By Hardik Patel', 28, '2021-03-05 15:29:23', 0),
(320, 'New Invoice #103 Created By Technomads Developer', 32, '2021-03-06 18:44:52', 0),
(321, 'Order Created By Hardik Patel', 41, '2021-03-08 12:18:31', 0),
(322, 'Order Details Edited By Hardik Patel', 41, '2021-03-08 12:19:27', 0),
(323, 'Order Created By Hardik Patel', 42, '2021-03-08 13:00:46', 0),
(324, 'Order Created By Hardik Patel', 43, '2021-03-08 13:18:51', 0),
(325, 'Order Details Edited By Hardik Patel', 41, '2021-03-08 14:34:56', 0),
(326, 'Order Created By Hardik Patel', 44, '2021-03-08 14:41:12', 0),
(327, 'Order Created By Hardik Patel', 45, '2021-03-08 14:52:54', 0),
(328, 'Order Details Edited By Hardik Patel', 42, '2021-03-08 15:29:56', 0),
(329, 'Order Details Edited By Hardik Patel', 44, '2021-03-08 15:32:22', 0),
(330, 'Order Details Edited By Hardik Patel', 43, '2021-03-08 15:36:09', 0),
(331, 'Order Details Edited By Hardik Patel', 45, '2021-03-08 15:38:36', 0),
(332, 'Order Created By Hardik Patel', 46, '2021-03-08 15:49:17', 0),
(333, 'Order Details Edited By Hardik Patel', 41, '2021-03-08 16:04:25', 0),
(334, 'Order Details Edited By Hardik Patel', 44, '2021-03-08 16:06:38', 0),
(335, 'Order Details Edited By Hardik Patel', 43, '2021-03-08 16:11:59', 0),
(336, 'Order Details Edited By Hardik Patel', 43, '2021-03-08 16:14:52', 0),
(337, 'Order Details Edited By Hardik Patel', 45, '2021-03-08 16:21:10', 0),
(338, 'Order Details Edited By Hardik Patel', 46, '2021-03-08 16:23:51', 0),
(339, 'Order Details Edited By Hardik Patel', 42, '2021-03-08 16:40:45', 0),
(340, 'Order Created By Hardik Patel', 47, '2021-03-08 17:09:29', 0),
(341, 'Order Delivered By Hardik Patel', 41, '2021-03-08 17:12:01', 0),
(367, 'New Invoice #29 Created By Devrishi', 32, '2021-03-11 12:36:45', 0),
(368, 'New Invoice #29 Created By Devrishi', 45, '2021-03-11 12:36:45', 0),
(369, 'New Invoice #29 Created By Devrishi', 46, '2021-03-11 12:36:45', 0),
(373, 'Order Details Edited By Hardik Patel', 49, '2021-03-12 12:48:22', 0),
(374, 'Order Created By Hardik Patel', 50, '2021-03-12 13:21:37', 0),
(375, 'Order Details Edited By Devrishi', 48, '2021-03-12 13:34:00', 0),
(376, 'Order Details Edited By Devrishi', 49, '2021-03-12 13:34:44', 0),
(377, 'Order Details Edited By Devrishi', 50, '2021-03-12 13:35:16', 0),
(378, 'Order Details Edited By Hardik Patel', 48, '2021-03-12 15:59:37', 0),
(379, 'Order Details Edited By Hardik Patel', 50, '2021-03-12 16:01:22', 0),
(380, 'Order Delivered By Hardik Patel', 50, '2021-03-12 16:07:38', 0),
(381, 'Order Delivered By Hardik Patel', 48, '2021-03-12 16:08:46', 0),
(382, 'Order Details Edited By Hardik Patel', 49, '2021-03-12 16:50:58', 0),
(383, 'Order Delivered By Hardik Patel', 49, '2021-03-12 16:51:54', 0),
(384, 'Order Created By Hardik Patel', 51, '2021-03-13 11:30:35', 0),
(385, 'Order Created By Hardik Patel', 52, '2021-03-13 11:36:37', 0),
(386, 'Order Details Edited By Hardik Patel', 51, '2021-03-13 13:01:35', 0),
(387, 'Order Assigned to Shaikh Ezaz By Hardik Patel', 51, '2021-03-13 13:01:57', 0),
(388, 'Order Details Edited By Hardik Patel', 52, '2021-03-13 13:22:49', 0),
(389, 'Order Details Edited By Hardik Patel', 52, '2021-03-13 14:15:55', 0),
(390, 'Order Assigned to Shaikh Ezaz By Hardik Patel', 52, '2021-03-13 14:16:17', 0),
(391, 'Order Delivered By Hardik Patel', 51, '2021-03-13 14:16:39', 0),
(392, 'Order Delivered By Hardik Patel', 52, '2021-03-13 14:16:43', 0),
(393, 'Order Created By Hardik Patel', 53, '2021-03-13 16:04:59', 0),
(394, 'Order Assigned to Shaikh Ezaz By Hardik Patel', 53, '2021-03-13 16:06:54', 0),
(395, 'Order Details Edited By Hardik Patel', 53, '2021-03-13 16:33:01', 0),
(396, 'New Invoice #30 Created By Hardik Patel', 53, '2021-03-13 16:37:25', 0),
(397, 'Order Details Edited By Hardik Patel', 53, '2021-03-13 16:46:18', 0),
(398, 'New Invoice #30 Created By Hardik Patel', 53, '2021-03-13 16:47:09', 0),
(399, 'Order Details Edited By Hardik Patel', 53, '2021-03-13 17:06:13', 0),
(400, 'Order Delivered By Hardik Patel', 53, '2021-03-13 17:06:42', 0),
(401, 'Payment Paid is Marked By Hardik Patel', 53, '2021-03-13 17:21:48', 0),
(402, 'Order Created By Hardik Patel', 54, '2021-03-15 11:35:25', 0),
(403, 'Order Assigned to Shaikh Ezaz By Hardik Patel', 54, '2021-03-15 11:42:50', 0),
(404, 'Order Assigned to Shaikh Ezaz By Hardik Patel', 54, '2021-03-15 11:42:55', 0),
(405, 'New Invoice #31 Created By Hardik Patel', 54, '2021-03-15 11:43:53', 0),
(406, 'Order Created By Devrishi', 55, '2021-03-15 12:02:26', 0),
(407, 'Order Assigned to Mohammad Fahad Shaikh By Devrishi', 55, '2021-03-15 12:02:45', 0),
(408, 'Order Assigned to Mohammad Fahad Shaikh By Devrishi', 55, '2021-03-15 12:02:48', 0),
(409, 'New Invoice #32 Created By Devrishi', 55, '2021-03-15 12:03:14', 0),
(410, 'Order Created By Hardik Patel', 56, '2021-03-15 12:56:07', 0),
(411, 'Order Created By Hardik Patel', 57, '2021-03-15 13:05:08', 0),
(412, 'Order Assigned to Mohammad Fahad Shaikh By Hardik Patel', 56, '2021-03-15 13:29:55', 0),
(413, 'Order Assigned to Mohammad Fahad Shaikh By Hardik Patel', 57, '2021-03-15 13:30:09', 0),
(414, 'Order Details Edited By Hardik Patel', 54, '2021-03-15 13:31:30', 0),
(415, 'Order Delivered By Hardik Patel', 54, '2021-03-15 13:32:53', 0),
(416, 'Order Details Edited By Hardik Patel', 56, '2021-03-15 13:59:49', 0),
(417, 'Order Delivered By Hardik Patel', 56, '2021-03-15 14:00:25', 0),
(418, 'Payment Paid is Marked By Hardik Patel', 54, '2021-03-15 14:02:28', 0),
(419, 'Order Details Edited By Devrishi', 53, '2021-03-15 15:07:51', 0),
(420, 'Order Details Edited By Devrishi', 56, '2021-03-15 15:09:18', 0),
(421, 'Order Created By Hardik Patel', 58, '2021-03-15 15:17:12', 0),
(422, 'Order Details Edited By Hardik Patel', 57, '2021-03-15 16:10:30', 0),
(423, 'Order Assigned to Shaikh Ezaz By Hardik Patel', 58, '2021-03-15 16:12:54', 0),
(424, 'Order Delivered By Hardik Patel', 57, '2021-03-15 16:15:10', 0),
(426, 'Order Created By Devrishi', 60, '2021-03-15 17:49:23', 0),
(427, 'Order Assigned to Mohammad Fahad Shaikh By Devrishi', 60, '2021-03-15 17:49:53', 0),
(428, 'New Invoice #33 Created By Devrishi', 60, '2021-03-15 17:50:36', 0),
(429, 'Order Details Edited By Hardik Patel', 58, '2021-03-15 17:58:49', 0),
(430, 'Order Delivered By Hardik Patel', 58, '2021-03-15 17:59:21', 0),
(431, 'Order Details Edited By Hardik Patel', 55, '2021-03-15 18:12:59', 0),
(432, 'Order Delivered By Hardik Patel', 55, '2021-03-15 18:13:26', 0),
(433, 'Payment Paid is Marked By Hardik Patel', 55, '2021-03-15 18:14:10', 0),
(434, 'Order Details Edited By Hardik Patel', 60, '2021-03-15 18:15:53', 0),
(435, 'Order Created By Technomads Developer', 61, '2021-03-17 20:22:12', 0),
(436, 'Order Details Edited By Technomads Developer', 61, '2021-03-17 20:23:23', 0),
(437, 'Order Assigned to Mohammad Fahad Shaikh By Technomads Developer', 61, '2021-03-17 20:23:49', 0),
(438, 'Order Delivered By Technomads Developer', 61, '2021-03-17 20:24:18', 0),
(439, 'Payment Paid is Marked By Technomads Developer', 61, '2021-03-17 20:24:40', 0),
(440, 'Order Created By Technomads Developer', 62, '2021-03-17 20:31:18', 0),
(441, 'Order Assigned to Mohammad Fahad Shaikh By Technomads Developer', 62, '2021-03-17 20:31:47', 0),
(442, 'Order Delivered By Technomads Developer', 62, '2021-03-17 20:31:56', 0),
(443, 'Payment Paid is Marked By Technomads Developer', 62, '2021-03-17 20:32:20', 0),
(444, 'Order Delivered By Devrishi', 60, '2021-03-18 11:59:10', 0),
(445, 'Payment Paid is Marked By Devrishi', 60, '2021-03-18 11:59:43', 0),
(446, 'Order Details Edited By Technomads Developer', 62, '2021-03-19 10:33:36', 0),
(447, 'Order Details Edited By Technomads Developer', 61, '2021-03-19 10:35:49', 0),
(448, 'Order Created By Hardik Patel', 63, '2021-03-19 12:41:39', 0),
(449, 'Order Details Edited By Hardik Patel', 63, '2021-03-19 12:44:11', 0),
(450, 'Order Assigned to Shaikh Ezaz By Hardik Patel', 63, '2021-03-19 12:44:45', 0),
(451, 'New Invoice #36 Created By Hardik Patel', 63, '2021-03-19 12:46:23', 0),
(452, 'Order Details Edited By Hardik Patel', 63, '2021-03-19 12:54:45', 0),
(453, 'Invoice Deleted By Hardik Patel', 63, '2021-03-19 12:55:04', 0),
(454, 'New Invoice #36 Created By Hardik Patel', 63, '2021-03-19 12:55:13', 0),
(455, 'Order Details Edited By Hardik Patel', 63, '2021-03-19 12:58:16', 0),
(456, 'Invoice Deleted By Hardik Patel', 63, '2021-03-19 12:58:47', 0),
(457, 'New Invoice #36 Created By Hardik Patel', 63, '2021-03-19 12:58:53', 0),
(458, 'Payment Paid is Marked By Devrishi', 39, '2021-03-19 16:06:07', 0),
(459, 'Payment Paid is Marked By Devrishi', 41, '2021-03-19 16:06:07', 0),
(460, 'Payment Paid is Marked By Devrishi', 42, '2021-03-19 16:06:07', 0),
(461, 'Payment Paid is Marked By Devrishi', 43, '2021-03-19 16:06:07', 0),
(462, 'Payment Paid is Marked By Devrishi', 44, '2021-03-19 16:06:07', 0),
(463, 'Payment Paid is Marked By Devrishi', 33, '2021-03-19 16:07:50', 0),
(464, 'Payment Paid is Marked By Devrishi', 34, '2021-03-19 16:07:50', 0),
(465, 'Payment Paid is Marked By Devrishi', 35, '2021-03-19 16:07:50', 0),
(466, 'Payment Paid is Marked By Devrishi', 36, '2021-03-19 16:07:50', 0),
(467, 'Payment Paid is Marked By Devrishi', 37, '2021-03-19 16:07:50', 0),
(468, 'Payment Paid is Marked By Devrishi', 38, '2021-03-19 16:07:50', 0),
(469, 'Payment Paid is Marked By Devrishi', 39, '2021-03-19 16:07:50', 0),
(470, 'Payment Paid is Marked By Devrishi', 41, '2021-03-19 16:07:50', 0),
(471, 'Payment Paid is Marked By Devrishi', 42, '2021-03-19 16:07:50', 0),
(472, 'Payment Paid is Marked By Devrishi', 43, '2021-03-19 16:07:51', 0),
(473, 'Payment Paid is Marked By Devrishi', 44, '2021-03-19 16:07:51', 0),
(474, 'Payment Paid is Marked By Devrishi', 33, '2021-03-19 16:08:09', 0),
(475, 'Payment Paid is Marked By Devrishi', 34, '2021-03-19 16:08:09', 0),
(476, 'Payment Paid is Marked By Devrishi', 35, '2021-03-19 16:08:09', 0),
(477, 'Payment Paid is Marked By Devrishi', 36, '2021-03-19 16:08:09', 0),
(478, 'Payment Paid is Marked By Devrishi', 37, '2021-03-19 16:08:09', 0),
(479, 'Payment Paid is Marked By Devrishi', 38, '2021-03-19 16:08:09', 0),
(480, 'Payment Paid is Marked By Devrishi', 39, '2021-03-19 16:08:10', 0),
(481, 'Payment Paid is Marked By Devrishi', 41, '2021-03-19 16:08:10', 0),
(482, 'Payment Paid is Marked By Devrishi', 42, '2021-03-19 16:08:10', 0),
(483, 'Payment Paid is Marked By Devrishi', 43, '2021-03-19 16:08:10', 0),
(484, 'Payment Paid is Marked By Devrishi', 44, '2021-03-19 16:08:10', 0),
(485, 'Payment Paid is Marked By Devrishi', 30, '2021-03-19 16:08:10', 0),
(486, 'Payment Paid is Marked By Devrishi', 31, '2021-03-19 16:08:10', 0),
(487, 'Payment Paid is Marked By Devrishi', 32, '2021-03-19 16:08:10', 0),
(488, 'Payment Paid is Marked By Devrishi', 45, '2021-03-19 16:08:10', 0),
(489, 'Payment Paid is Marked By Devrishi', 46, '2021-03-19 16:08:10', 0),
(490, 'Order Details Edited By Hardik Patel', 63, '2021-03-19 16:31:59', 0),
(491, 'Order Delivered By Devrishi', 63, '2021-03-19 16:45:58', 0),
(492, 'Payment Paid is Marked By Devrishi', 63, '2021-03-19 16:46:16', 0),
(493, 'New Invoice #25 Created By Technomads Developer', 40, '2021-03-20 11:38:18', 0),
(494, 'New Invoice #23 Created By Technomads Developer', 16, '2021-03-20 11:41:12', 0),
(495, 'New Invoice #23 Created By Technomads Developer', 17, '2021-03-20 11:41:12', 0),
(496, 'New Invoice #23 Created By Technomads Developer', 19, '2021-03-20 11:41:12', 0),
(497, 'New Invoice #23 Created By Technomads Developer', 20, '2021-03-20 11:41:12', 0),
(498, 'New Invoice #23 Created By Technomads Developer', 21, '2021-03-20 11:41:12', 0),
(499, 'New Invoice #24 Created By Technomads Developer', 22, '2021-03-20 11:42:22', 0),
(500, 'New Invoice #24 Created By Technomads Developer', 24, '2021-03-20 11:42:22', 0),
(501, 'New Invoice #24 Created By Technomads Developer', 25, '2021-03-20 11:42:22', 0),
(502, 'New Invoice #24 Created By Technomads Developer', 27, '2021-03-20 11:42:22', 0),
(503, 'New Invoice #24 Created By Technomads Developer', 28, '2021-03-20 11:42:22', 0),
(504, 'New Invoice #22 Created By Technomads Developer', 8, '2021-03-20 11:43:17', 0),
(505, 'New Invoice #22 Created By Technomads Developer', 9, '2021-03-20 11:43:17', 0),
(506, 'New Invoice #22 Created By Technomads Developer', 13, '2021-03-20 11:43:17', 0),
(507, 'New Invoice #22 Created By Technomads Developer', 14, '2021-03-20 11:43:17', 0),
(508, 'New Invoice #22 Created By Technomads Developer', 15, '2021-03-20 11:43:17', 0),
(509, 'New Invoice #21 Created By Technomads Developer', 29, '2021-03-20 11:44:05', 0),
(510, 'New Invoice #20 Created By Technomads Developer', 26, '2021-03-20 11:44:57', 0),
(511, 'New Invoice #15 Created By Technomads Developer', 23, '2021-03-20 11:45:26', 0),
(512, 'New Invoice #2 Created By Technomads Developer', 7, '2021-03-20 11:46:13', 0),
(513, 'New Invoice #10 Created By Technomads Developer', 18, '2021-03-20 11:46:50', 0),
(514, 'New Invoice #1 Created By Technomads Developer', 6, '2021-03-20 11:48:08', 0),
(515, 'Order Created By Hardik Patel', 64, '2021-03-22 11:43:58', 0),
(516, 'Order Assigned to Shaikh Ezaz By Hardik Patel', 64, '2021-03-22 11:44:20', 0),
(517, 'Order Assigned to Shaikh Ezaz By Hardik Patel', 64, '2021-03-22 11:44:24', 0),
(518, 'New Invoice #37 Created By Hardik Patel', 64, '2021-03-22 11:45:41', 0),
(519, 'Order Details Edited By Hardik Patel', 64, '2021-03-22 14:25:49', 0),
(520, 'Order Delivered By Hardik Patel', 64, '2021-03-22 14:26:03', 0),
(521, 'Payment Paid is Marked By Hardik Patel', 64, '2021-03-22 14:26:20', 0),
(522, 'Order Created By Devrishi', 65, '2021-03-23 11:37:16', 0),
(523, 'Order Created By Devrishi', 66, '2021-03-23 11:41:34', 0),
(524, 'Order Details Edited By Devrishi', 65, '2021-03-23 11:42:19', 0),
(525, 'Order Details Edited By Devrishi', 65, '2021-03-23 11:42:27', 0),
(526, 'Order Created By Devrishi', 67, '2021-03-23 12:08:53', 0),
(527, 'Order Assigned to Mohammad Fahad Shaikh By Devrishi', 67, '2021-03-23 12:09:34', 0),
(528, 'Order Assigned to Shaikh Ezaz By Devrishi', 66, '2021-03-23 12:10:05', 0),
(529, 'Order Assigned to Shaikh Ezaz By Devrishi', 65, '2021-03-23 12:10:06', 0),
(530, 'Order Details Edited By Hardik Patel', 66, '2021-03-23 12:16:38', 0),
(531, 'New Invoice #38 Created By Devrishi', 67, '2021-03-23 12:30:29', 0),
(532, 'Invoice Deleted By Devrishi', 67, '2021-03-23 12:31:18', 0),
(533, 'Order Details Edited By Hardik Patel', 65, '2021-03-23 13:16:26', 0),
(534, 'Order Delivered By Hardik Patel', 65, '2021-03-23 13:16:57', 0),
(535, 'Order Delivered By Hardik Patel', 66, '2021-03-23 13:17:07', 0),
(536, 'Order Details Edited By Hardik Patel', 67, '2021-03-23 14:31:15', 0),
(537, 'Order Delivered By Hardik Patel', 67, '2021-03-23 14:38:02', 0),
(538, 'New Invoice #38 Created By Devrishi', 67, '2021-03-23 14:43:25', 0),
(539, 'Order Created By Devrishi', 68, '2021-03-23 16:31:32', 0),
(540, 'Order Assigned to Shaikh Ezaz By Devrishi', 68, '2021-03-23 16:32:08', 0),
(541, 'New Invoice #39 Created By Devrishi', 68, '2021-03-23 16:32:21', 0),
(542, 'Order Details Edited By Hardik Patel', 68, '2021-03-23 17:25:07', 0),
(543, 'Order Delivered By Hardik Patel', 68, '2021-03-23 17:25:37', 0),
(544, 'Payment Paid is Marked By Hardik Patel', 68, '2021-03-23 18:05:41', 0),
(545, 'Payment Paid is Marked By Hardik Patel', 67, '2021-03-24 15:56:53', 0),
(546, 'Order Created By Hardik Patel', 69, '2021-03-25 16:20:22', 0),
(547, 'Order Details Edited By Hardik Patel', 69, '2021-03-25 16:21:32', 0),
(548, 'Order Created By Hardik Patel', 70, '2021-03-25 16:25:56', 0),
(549, 'Order Created By Devrishi', 71, '2021-03-25 16:38:40', 0),
(550, 'Order Assigned to Mohammad Fahad Shaikh By Devrishi', 70, '2021-03-25 16:38:58', 0),
(551, 'Order Assigned to Mohammad Fahad Shaikh By Devrishi', 69, '2021-03-25 16:38:59', 0),
(552, 'Order Assigned to Mohammad Fahad Shaikh By Devrishi', 71, '2021-03-25 16:39:00', 0),
(553, 'Order Details Edited By Devrishi', 71, '2021-03-25 16:41:31', 0),
(554, 'Order #57 Picked Up By Mohammad Fahad Shaikh', 71, '2021-03-25 16:46:50', 0),
(555, 'Order #58 Picked Up By Mohammad Fahad Shaikh', 69, '2021-03-25 16:47:48', 0),
(556, 'Order #59 Picked Up By Mohammad Fahad Shaikh', 70, '2021-03-25 16:48:06', 0),
(557, 'Order Details Edited By Devrishi', 70, '2021-03-25 17:11:47', 0),
(558, 'Order Details Edited By Devrishi', 70, '2021-03-25 17:13:22', 0),
(559, 'Order Details Edited By Devrishi', 69, '2021-03-25 17:13:50', 0),
(560, 'Order Details Edited By Devrishi', 71, '2021-03-25 17:14:24', 0),
(561, 'Order #58 Delivered By Mohammad Fahad Shaikh', 69, '2021-03-25 17:50:59', 0),
(562, 'Order #59 Delivered By Mohammad Fahad Shaikh', 70, '2021-03-25 17:51:33', 0),
(563, 'Order #57 Delivered By Mohammad Fahad Shaikh', 71, '2021-03-25 18:09:45', 0),
(565, 'Order Created By Hardik Patel', 73, '2021-03-26 17:56:49', 0),
(566, 'Order Details Edited By Hardik Patel', 73, '2021-03-26 17:58:12', 0),
(567, 'Order Assigned to Mohammad Fahad Shaikh By Hardik Patel', 73, '2021-03-26 17:58:32', 0),
(568, 'Order Delivered By Hardik Patel', 73, '2021-03-26 17:59:13', 0),
(569, 'Order Created By Hardik Patel', 74, '2021-03-27 14:01:45', 0),
(570, 'Order Details Edited By Hardik Patel', 74, '2021-03-27 14:02:24', 0),
(571, 'Order Details Edited By Hardik Patel', 74, '2021-03-27 14:06:42', 0),
(572, 'Order Created By Hardik Patel', 75, '2021-03-27 14:15:52', 0),
(573, 'Order Details Edited By Hardik Patel', 74, '2021-03-27 14:26:04', 0),
(574, 'Order Details Edited By Hardik Patel', 75, '2021-03-27 14:35:21', 0),
(575, 'Order Details Edited By Hardik Patel', 74, '2021-03-27 14:35:54', 0),
(576, 'Order Details Edited By Hardik Patel', 75, '2021-03-27 14:36:33', 0),
(577, 'Order Details Edited By Hardik Patel', 75, '2021-03-27 14:43:46', 0),
(578, 'Order Assigned to Shaikh Ezaz By Hardik Patel', 75, '2021-03-27 14:44:32', 0),
(579, 'Order Assigned to Shaikh Ezaz By Hardik Patel', 74, '2021-03-27 14:45:35', 0),
(580, 'Order Details Edited By Hardik Patel', 75, '2021-03-27 15:16:55', 0),
(581, 'Order Delivered By Hardik Patel', 75, '2021-03-27 15:17:15', 0),
(582, 'Order Details Edited By Hardik Patel', 74, '2021-03-27 15:20:13', 0),
(583, 'Order Details Edited By Hardik Patel', 74, '2021-03-27 16:02:48', 0),
(584, 'Order Delivered By Hardik Patel', 74, '2021-03-27 16:03:11', 0),
(585, 'Order Created By Hardik Patel', 76, '2021-03-30 11:03:27', 0),
(586, 'Order Assigned to Shaikh Ezaz By Hardik Patel', 76, '2021-03-30 11:03:57', 0),
(587, 'Order #63 Picked Up By Shaikh Ezaz', 76, '2021-03-30 11:07:27', 0),
(588, 'Order Created By Hardik Patel', 77, '2021-03-30 16:56:33', 0),
(589, 'Order Created By Hardik Patel', 78, '2021-03-30 17:01:08', 0),
(590, 'Order Details Edited By Hardik Patel', 77, '2021-03-30 17:04:36', 0),
(591, 'Order Details Edited By Hardik Patel', 78, '2021-03-30 17:05:42', 0),
(592, 'Order Details Edited By Hardik Patel', 77, '2021-03-30 17:06:13', 0),
(593, 'Order Assigned to Mohammad Fahad Shaikh By Hardik Patel', 77, '2021-03-30 17:07:26', 0),
(594, 'Order Assigned to Mohammad Fahad Shaikh By Hardik Patel', 78, '2021-03-30 17:07:49', 0),
(595, 'New Invoice #40 Created By Hardik Patel', 77, '2021-03-30 17:09:16', 0),
(596, 'New Invoice #40 Created By Hardik Patel', 78, '2021-03-30 17:09:16', 0),
(597, 'Order #63 Delivered By Shaikh Ezaz', 76, '2021-03-30 17:35:57', 0),
(598, 'Order #64 Picked Up By Mohammad Fahad Shaikh', 77, '2021-03-30 17:45:19', 0),
(599, 'Order #64 Delivered By Mohammad Fahad Shaikh', 77, '2021-03-30 17:53:34', 0),
(600, 'Order #65 Picked Up By Mohammad Fahad Shaikh', 78, '2021-03-30 18:08:25', 0),
(601, 'Order #65 Delivered By Mohammad Fahad Shaikh', 78, '2021-03-30 18:21:34', 0),
(602, 'Order Created By Hardik Patel', 79, '2021-03-31 11:59:18', 0),
(603, 'Order Assigned to Shaikh Ezaz By Hardik Patel', 79, '2021-03-31 12:01:13', 0),
(604, 'New Invoice #41 Created By Hardik Patel', 79, '2021-03-31 12:01:30', 0),
(605, 'Order Details Edited By Hardik Patel', 79, '2021-03-31 12:16:58', 0),
(607, 'Order Created By Hardik Patel', 81, '2021-03-31 15:40:01', 0),
(608, 'Order Details Edited By Hardik Patel', 81, '2021-03-31 15:50:30', 0),
(609, 'Order Assigned to Mohammad Fahad Shaikh By Hardik Patel', 81, '2021-03-31 15:53:40', 0),
(610, 'New Invoice #42 Created By Hardik Patel', 81, '2021-03-31 15:55:04', 0),
(611, 'Order Created By Hardik Patel', 82, '2021-03-31 16:08:18', 0),
(612, 'Order #1002 Picked Up By Mohammad Fahad Shaikh', 81, '2021-03-31 16:27:15', 0),
(613, 'Order #1002 Delivered By Mohammad Fahad Shaikh', 81, '2021-03-31 17:00:18', 0),
(614, 'Order #1001 Picked Up By Shaikh Ezaz', 79, '2021-03-31 17:19:13', 0),
(615, 'Order #1001 Delivered By Shaikh Ezaz', 79, '2021-03-31 17:21:29', 0),
(616, 'Order Details Edited By Hardik Patel', 82, '2021-03-31 17:45:39', 0),
(617, 'Order Assigned to Shaikh Ezaz By Hardik Patel', 82, '2021-03-31 17:45:57', 0),
(618, 'New Invoice #43 Created By Hardik Patel', 82, '2021-03-31 17:47:01', 0),
(619, 'Order Details Edited By Hardik Patel', 82, '2021-03-31 17:48:26', 0),
(620, 'Invoice Deleted By Hardik Patel', 82, '2021-03-31 17:48:42', 0),
(621, 'New Invoice #43 Created By Hardik Patel', 82, '2021-03-31 17:48:46', 0),
(622, 'Order Details Edited By Hardik Patel', 82, '2021-03-31 18:24:47', 0),
(623, 'Order Details Edited By Hardik Patel', 82, '2021-03-31 19:08:57', 0),
(624, 'Order Delivered By Hardik Patel', 82, '2021-03-31 19:09:40', 0),
(625, 'Payment Paid is Marked By Hardik Patel', 82, '2021-03-31 19:09:49', 0),
(626, 'Order Created By Hardik Patel', 83, '2021-04-02 13:34:24', 0),
(627, 'Order Assigned to Shaikh Ezaz By Hardik Patel', 83, '2021-04-02 13:35:05', 0),
(628, 'New Invoice #44 Created By Hardik Patel', 83, '2021-04-02 13:35:24', 0),
(629, 'Invoice Deleted By Hardik Patel', 83, '2021-04-02 13:38:41', 0),
(630, 'Order Details Edited By Hardik Patel', 83, '2021-04-02 13:41:16', 0),
(631, 'New Invoice #44 Created By Hardik Patel', 83, '2021-04-02 13:41:55', 0),
(632, 'Order #1004 Picked Up By Shaikh Ezaz', 83, '2021-04-02 13:59:08', 0),
(633, 'Order #1004 Delivered By Shaikh Ezaz', 83, '2021-04-02 14:33:11', 0),
(640, 'New Invoice #45 Created By Hardik Patel', 85, '2021-04-02 17:08:43', 0),
(639, 'Order Assigned to Shaikh Ezaz By Hardik Patel', 85, '2021-04-02 17:08:25', 0),
(638, 'Order Created By Hardik Patel', 85, '2021-04-02 17:08:14', 0),
(641, 'Order #1005 Picked Up By Shaikh Ezaz', 85, '2021-04-02 17:48:18', 0),
(642, 'Order Created By Hardik Patel', 86, '2021-04-02 17:49:02', 0),
(643, 'Order Assigned to Mohammad Fahad Shaikh By Hardik Patel', 86, '2021-04-02 17:49:43', 0),
(644, 'New Invoice #46 Created By Hardik Patel', 86, '2021-04-02 17:50:10', 0),
(645, 'Order #1005 Delivered By Shaikh Ezaz', 85, '2021-04-02 18:36:58', 0),
(646, 'Invoice Deleted By Hardik Patel', 86, '2021-04-03 17:41:16', 0),
(647, 'Order Details Edited By Hardik Patel', 86, '2021-04-05 10:43:50', 0),
(648, 'Order Assigned to Shaikh Ezaz By Hardik Patel', 86, '2021-04-05 11:24:24', 0),
(649, 'Order Details Edited By Hardik Patel', 86, '2021-04-05 11:26:02', 0),
(650, 'Order Details Edited By Hardik Patel', 86, '2021-04-05 11:31:11', 0),
(651, 'Order Details Edited By Hardik Patel', 86, '2021-04-05 11:59:30', 0),
(652, 'New Invoice #46 Created By Hardik Patel', 86, '2021-04-05 12:00:14', 0),
(653, 'Invoice Deleted By Hardik Patel', 86, '2021-04-05 12:06:27', 0),
(654, 'Order Details Edited By Hardik Patel', 86, '2021-04-05 12:07:53', 0),
(655, 'Order #1006 Picked Up By Shaikh Ezaz', 86, '2021-04-05 12:36:34', 0),
(656, 'New Invoice #46 Created By Hardik Patel', 86, '2021-04-05 13:04:12', 0),
(657, 'Order #1006 Delivered By Shaikh Ezaz', 86, '2021-04-05 13:57:12', 0),
(658, 'Order Created By Hardik Patel', 87, '2021-04-05 14:54:38', 0),
(659, 'Order Details Edited By Hardik Patel', 87, '2021-04-05 14:56:12', 0),
(660, 'Order Assigned to Mohammad Fahad Shaikh By Hardik Patel', 87, '2021-04-05 14:56:42', 0),
(661, 'Order #1007 Picked Up By Mohammad Fahad Shaikh', 87, '2021-04-05 15:11:30', 0),
(662, 'Order #1007 Delivered By Mohammad Fahad Shaikh', 87, '2021-04-05 16:07:11', 0),
(663, 'New Invoice #47 Created By Hardik Patel', 48, '2021-04-05 16:25:51', 0),
(664, 'New Invoice #47 Created By Hardik Patel', 49, '2021-04-05 16:25:51', 0),
(665, 'New Invoice #47 Created By Hardik Patel', 50, '2021-04-05 16:25:51', 0),
(666, 'New Invoice #47 Created By Hardik Patel', 51, '2021-04-05 16:25:51', 0),
(667, 'New Invoice #47 Created By Hardik Patel', 52, '2021-04-05 16:25:51', 0),
(668, 'New Invoice #47 Created By Hardik Patel', 56, '2021-04-05 16:25:51', 0),
(669, 'New Invoice #48 Created By Hardik Patel', 57, '2021-04-05 16:28:27', 0),
(670, 'New Invoice #48 Created By Hardik Patel', 58, '2021-04-05 16:28:27', 0),
(671, 'New Invoice #48 Created By Hardik Patel', 65, '2021-04-05 16:28:27', 0),
(672, 'New Invoice #48 Created By Hardik Patel', 66, '2021-04-05 16:28:27', 0),
(673, 'New Invoice #48 Created By Hardik Patel', 69, '2021-04-05 16:28:27', 0),
(674, 'New Invoice #48 Created By Hardik Patel', 71, '2021-04-05 16:28:27', 0),
(675, 'New Invoice #49 Created By Hardik Patel', 70, '2021-04-05 16:30:02', 0),
(676, 'New Invoice #49 Created By Hardik Patel', 73, '2021-04-05 16:30:02', 0),
(677, 'New Invoice #49 Created By Hardik Patel', 74, '2021-04-05 16:30:02', 0),
(678, 'New Invoice #49 Created By Hardik Patel', 75, '2021-04-05 16:30:02', 0),
(679, 'New Invoice #49 Created By Hardik Patel', 76, '2021-04-05 16:30:02', 0),
(680, 'New Invoice #49 Created By Hardik Patel', 87, '2021-04-05 16:30:02', 0),
(681, 'Order Created By GB TEST', 88, '2021-04-05 18:05:55', 0),
(682, 'Order Assigned to Mohammad Fahad Shaikh By GB TEST', 88, '2021-04-05 18:06:06', 0),
(683, 'Order Assigned to Mohammad Fahad Shaikh By GB TEST', 88, '2021-04-05 18:06:48', 0),
(684, 'Payment Paid is Marked By GB TEST', 48, '2021-04-05 18:12:24', 0),
(685, 'Payment Paid is Marked By GB TEST', 50, '2021-04-05 18:12:24', 0),
(686, 'Payment Paid is Marked By GB TEST', 49, '2021-04-05 18:12:24', 0),
(687, 'Payment Paid is Marked By GB TEST', 51, '2021-04-05 18:12:24', 0),
(688, 'Payment Paid is Marked By GB TEST', 52, '2021-04-05 18:12:24', 0),
(689, 'Payment Paid is Marked By GB TEST', 56, '2021-04-05 18:12:24', 0),
(690, 'Order Assigned to Shaikh Ezaz By GB TEST', 88, '2021-04-05 18:23:04', 0),
(691, 'Order Requested.', 89, '2021-04-06 12:25:32', 0),
(692, 'Order Approved.', 89, '2021-04-06 12:27:40', 0),
(693, 'Order Details Edited By GB TEST', 89, '2021-04-06 12:29:20', 0),
(694, 'Order Requested.', 90, '2021-04-06 12:37:19', 0),
(695, 'Order Approved.', 90, '2021-04-06 15:24:23', 0),
(696, 'Order Approved.', 90, '2021-04-06 15:27:04', 0),
(697, 'Order Approved.', 89, '2021-04-06 16:47:57', 0),
(698, 'Order Requested.', 91, '2021-04-06 18:04:36', 0),
(699, 'Order Details Edited By GB TEST', 91, '2021-04-06 18:05:21', 0),
(700, 'Order Details Edited By GB TEST', 91, '2021-04-06 18:07:52', 0),
(701, 'Order Details Edited By GB TEST', 91, '2021-04-06 18:08:29', 0),
(702, 'Order Requested.', 92, '2021-04-07 11:31:36', 0),
(703, 'Order Approved.', 92, '2021-04-07 11:32:11', 0),
(704, 'Order Requested.', 93, '2021-04-07 11:43:29', 0),
(705, 'Order Approved.', 92, '2021-04-07 12:01:18', 0),
(706, 'Order Approved.', 92, '2021-04-07 12:03:59', 0);
INSERT INTO `tbl_order_logs` (`id`, `logs`, `order_id`, `created_at`, `type`) VALUES
(707, 'Order Details Edited By GB TEST', 92, '2021-04-07 12:19:33', 0),
(708, 'Order Approved.', 92, '2021-04-07 12:20:19', 0),
(709, 'Order Approved.', 92, '2021-04-07 12:29:30', 0),
(710, 'Order Approved.', 92, '2021-04-07 12:35:27', 0),
(711, 'Order Requested.', 94, '2021-04-07 12:59:18', 0),
(712, 'Order Approved.', 94, '2021-04-07 13:28:26', 0),
(713, 'Order Approved.', 94, '2021-04-07 13:31:36', 0),
(714, 'Order Approved.', 94, '2021-04-07 13:33:32', 0),
(715, 'Order Requested.', 95, '2021-04-07 14:20:02', 0),
(716, 'Order Approved.', 95, '2021-04-07 14:21:07', 0),
(717, 'Order Details Edited By GB TEST', 95, '2021-04-07 14:27:29', 0),
(718, 'Order Approved.', 95, '2021-04-07 14:27:51', 0),
(719, 'Order Requested.', 96, '2021-04-07 14:41:39', 0),
(720, 'Order Requested.', 97, '2021-04-07 14:45:13', 0),
(721, 'Order Approved.', 97, '2021-04-07 14:45:56', 0),
(722, 'Order Requested.', 98, '2021-04-07 15:00:13', 0),
(723, 'Order Approved.', 98, '2021-04-07 15:00:57', 0),
(724, 'Order Requested.', 99, '2021-04-07 15:09:59', 0),
(725, 'Order Approved.', 99, '2021-04-07 15:12:10', 0),
(726, 'Order Approved.', 99, '2021-04-07 15:17:11', 0),
(727, 'Order Payment Paid Online By Customer, Amount INR [18348]', 99, '2021-04-07 15:18:04', 0),
(728, 'Order Requested.', 100, '2021-04-07 15:40:11', 0),
(729, 'Order Approved.', 100, '2021-04-07 15:40:49', 0),
(730, 'Order Payment Paid Online By Customer, Amount INR [165]', 100, '2021-04-07 15:41:28', 0),
(731, 'Order Payment Paid Online By Customer, Amount INR [150]', 100, '2021-04-07 15:53:50', 0),
(732, 'Order Requested.', 101, '2021-04-07 16:38:15', 0),
(733, 'Order Requested.', 102, '2021-04-07 16:39:50', 0),
(734, 'Order Placed With COD By Customer.', 102, '2021-04-07 16:40:47', 0),
(735, 'Order Approved.', 102, '2021-04-07 16:45:33', 0),
(736, 'Order Placed With COD By Customer.', 102, '2021-04-07 16:45:44', 0),
(737, 'Order Placed With COD By Customer.', 102, '2021-04-07 16:46:03', 0),
(738, 'Order Placed With COD By Customer.', 102, '2021-04-07 16:46:06', 0),
(739, 'Order Placed With COD By Customer.', 102, '2021-04-07 16:47:43', 0),
(740, 'Order Placed With COD By Customer.', 102, '2021-04-07 16:47:47', 0),
(741, 'Order Placed With COD By Customer.', 102, '2021-04-07 16:48:09', 0),
(742, 'Order Placed With COD By Customer.', 102, '2021-04-07 16:48:11', 0),
(743, 'Order Placed With COD By Customer.', 102, '2021-04-07 16:49:07', 0),
(744, 'Order Placed With COD By Customer.', 102, '2021-04-07 16:50:30', 0),
(745, 'Order Requested.', 103, '2021-04-07 16:55:32', 0),
(746, 'Order Placed With COD By Customer.', 102, '2021-04-07 16:55:34', 0),
(747, 'Order Approved.', 103, '2021-04-07 17:08:14', 0),
(748, 'Order Placed With COD By Customer.', 103, '2021-04-07 17:08:55', 0),
(749, 'Order Approved.', 101, '2021-04-07 17:26:26', 0),
(750, 'Order Placed With COD By Customer.', 101, '2021-04-07 17:26:53', 0),
(751, 'Order Requested.', 104, '2021-04-07 17:31:57', 0),
(752, 'Order Approved.', 104, '2021-04-07 17:37:11', 0),
(753, 'Order Approved.', 104, '2021-04-07 17:43:49', 0),
(754, 'Order Requested.', 105, '2021-04-07 18:00:20', 0),
(755, 'Order Approved.', 105, '2021-04-07 18:00:35', 0),
(756, 'Order Payment Paid Online By Customer, Amount INR [12.56]', 105, '2021-04-07 18:11:10', 0),
(757, 'Order Payment Paid Online By Customer, Amount INR [12.56]', 105, '2021-04-07 18:12:23', 0),
(758, 'Order Approved.', 96, '2021-04-08 10:37:48', 0),
(759, 'Order Payment Paid Online By Customer, Amount INR [1188]', 96, '2021-04-08 10:40:07', 0),
(760, 'Order Requested.', 106, '2021-04-08 11:03:44', 0),
(761, 'Order Requested.', 107, '2021-04-08 11:04:23', 0),
(762, 'Order Requested.', 108, '2021-04-08 11:04:35', 0),
(763, 'Order Approved.', 107, '2021-04-08 11:04:59', 0),
(764, 'Order Assigned to Mohammad Fahad Shaikh By GB TEST', 96, '2021-04-08 11:25:29', 0),
(765, 'Order Assigned to Mohammad Fahad Shaikh By GB TEST', 105, '2021-04-08 11:25:30', 0),
(766, 'New Invoice #50 Created By GB TEST', 96, '2021-04-08 11:26:09', 0),
(767, 'New Invoice #50 Created By GB TEST', 105, '2021-04-08 11:26:09', 0),
(768, 'Order Assigned to Mohammad Fahad Shaikh By GB TEST', 101, '2021-04-08 11:27:49', 0),
(769, 'Order Assigned to Shaikh Ezaz By GB TEST', 103, '2021-04-08 11:30:43', 0),
(770, 'Order Requested.', 109, '2021-04-08 11:37:04', 0),
(771, 'Order Requested.', 110, '2021-04-08 11:37:59', 0),
(772, 'Order Approved.', 110, '2021-04-08 11:38:38', 0),
(773, 'Order Payment Paid Online By Customer, Amount INR [1624.48]', 110, '2021-04-08 11:40:32', 0),
(774, 'Order Approved.', 109, '2021-04-08 12:23:46', 0),
(775, 'Order Payment Paid Online By Customer, Amount INR [253]', 109, '2021-04-08 12:25:11', 0),
(776, 'Order Approved.', 108, '2021-04-08 12:26:32', 0),
(777, 'Order Payment Paid Online By Customer, Amount INR [2378]', 108, '2021-04-08 12:26:51', 0),
(778, 'Order Requested.', 111, '2021-04-08 13:21:26', 0),
(779, 'Order Approved.', 111, '2021-04-08 13:21:51', 0),
(780, 'Order Payment Paid Online By Customer, Amount INR [616]', 111, '2021-04-08 13:22:09', 0),
(781, 'Order Approved.', 111, '2021-04-08 13:25:47', 0),
(782, 'Order Approved.', 111, '2021-04-08 13:26:56', 0),
(783, 'Order Approved.', 111, '2021-04-08 13:28:26', 0),
(784, 'Order Payment Paid Online By Customer, Amount INR [85.8]', 111, '2021-04-08 13:28:38', 0),
(785, 'Order Approved.', 111, '2021-04-08 13:30:00', 0),
(786, 'Order Payment Paid Online By Customer, Amount INR [85.8]', 111, '2021-04-08 13:30:13', 0),
(787, 'Order Placed With COD By Customer.', 107, '2021-04-08 13:30:28', 0),
(788, 'Order Requested.', 112, '2021-04-08 13:37:22', 0),
(789, 'Order Approved.', 112, '2021-04-08 13:37:52', 0),
(790, 'Order Payment Paid Online By Customer, Amount INR [616]', 112, '2021-04-08 13:38:10', 0),
(791, 'Order Placed With COD By Customer.', 107, '2021-04-08 13:40:26', 0),
(792, 'Order Delivered By GB TEST', 107, '2021-04-08 14:13:34', 0),
(793, 'Order Requested.', 113, '2021-04-08 14:17:57', 0),
(794, 'Order Requested.', 113, '2021-04-08 14:17:57', 0),
(795, 'Order Approved.', 113, '2021-04-08 14:22:21', 0),
(796, 'Order Approved.', 113, '2021-04-08 14:22:21', 0),
(797, 'Order Placed With COD.', 107, '2021-04-08 13:30:28', 1),
(798, 'Order Delivered.', 107, '2021-04-08 13:40:33', 1),
(799, 'Order Requested.', 114, '2021-04-08 14:49:00', 0),
(800, 'Order Requested.', 114, '2021-04-08 14:49:00', 1),
(801, 'Order Approved.', 114, '2021-04-08 14:49:27', 0),
(802, 'Order Approved.', 114, '2021-04-08 14:49:27', 1),
(803, 'Order Payment Paid Online By Customer, Amount INR [671]', 114, '2021-04-08 14:54:18', 0),
(804, 'Order Successfully Placed.', 114, '2021-04-08 14:54:18', 1),
(805, 'Order Requested.', 115, '2021-04-08 14:54:52', 0),
(806, 'Order Requested.', 115, '2021-04-08 14:54:52', 1),
(807, 'Order Approved.', 115, '2021-04-08 14:55:06', 0),
(808, 'Order Approved.', 115, '2021-04-08 14:55:06', 1),
(809, 'Order Payment Paid Online By Customer, Amount INR [616]', 115, '2021-04-08 14:55:22', 0),
(810, 'Order Successfully Placed.', 115, '2021-04-08 14:55:22', 1),
(811, 'Order Approved.', 115, '2021-04-08 14:57:00', 0),
(812, 'Order Approved.', 115, '2021-04-08 14:57:00', 1),
(813, 'Order Payment Paid Online By Customer, Amount INR [616]', 115, '2021-04-08 14:57:12', 0),
(814, 'Order Successfully Placed.', 115, '2021-04-08 14:57:12', 1),
(815, 'Order Requested.', 116, '2021-04-08 15:22:41', 0),
(816, 'Order Requested.', 116, '2021-04-08 15:22:41', 1),
(817, 'Order Approved.', 116, '2021-04-08 15:23:21', 0),
(818, 'Order Approved.', 116, '2021-04-08 15:23:21', 1),
(819, 'Order Placed With COD By Customer.', 116, '2021-04-08 15:23:53', 0),
(820, 'Order Successfully Placed With COD.', 116, '2021-04-08 15:23:53', 1),
(821, 'Order Approved.', 106, '2021-04-08 15:32:48', 0),
(822, 'Order Approved.', 106, '2021-04-08 15:32:48', 1),
(823, 'Order Payment Paid Online By Customer, Amount INR [616]', 106, '2021-04-08 15:33:24', 0),
(824, 'Order Successfully Placed.', 106, '2021-04-08 15:33:24', 1),
(825, 'Order Requested.', 117, '2021-04-08 15:36:59', 0),
(826, 'Order Requested.', 117, '2021-04-08 15:36:59', 1),
(827, 'Order Approved.', 117, '2021-04-08 15:37:38', 0),
(828, 'Order Approved.', 117, '2021-04-08 15:37:38', 1),
(829, 'Order Payment Paid Online By Customer, Amount INR [616]', 117, '2021-04-08 15:37:53', 0),
(830, 'Order Successfully Placed.', 117, '2021-04-08 15:37:53', 1),
(831, 'Order Requested.', 118, '2021-04-08 15:38:41', 0),
(832, 'Order Requested.', 118, '2021-04-08 15:38:41', 1),
(833, 'Order Approved.', 118, '2021-04-08 15:39:17', 0),
(834, 'Order Approved.', 118, '2021-04-08 15:39:17', 1),
(835, 'Order Payment Paid Online By Customer, Amount INR [616]', 118, '2021-04-08 15:40:09', 0),
(836, 'Order Successfully Placed.', 118, '2021-04-08 15:40:09', 1),
(837, 'Order Created By GB TEST', 119, '2021-04-08 16:06:08', 0),
(838, 'Order Created.', 119, '2021-04-08 16:06:08', 1),
(839, 'Order Created By GB TEST', 120, '2021-04-08 16:06:29', 0),
(840, 'Order Created.', 120, '2021-04-08 16:06:29', 1),
(841, 'Order #1016 Picked Up By Shaikh Ezaz', 103, '2021-04-08 16:07:31', 0),
(842, 'Order Picked Up By Driver.', 103, '2021-04-08 16:07:31', 1),
(843, 'Order #1008 Picked Up By Shaikh Ezaz', 88, '2021-04-08 16:07:36', 0),
(844, 'Order Picked Up By Driver.', 88, '2021-04-08 16:07:36', 1),
(845, 'Order #1016 Delivered By Shaikh Ezaz', 103, '2021-04-08 16:07:52', 0),
(846, 'Order Delivered By Driver.', 103, '2021-04-08 16:07:52', 1),
(847, 'Order #1008 Delivered By Shaikh Ezaz', 88, '2021-04-08 16:08:01', 0),
(848, 'Order Delivered By Driver.', 88, '2021-04-08 16:08:01', 1),
(849, 'Order Assigned to Shaikh Ezaz By GB TEST', 119, '2021-04-08 16:08:14', 0),
(850, 'Order Assigned to Driver.', 119, '2021-04-08 16:08:14', 1),
(851, 'Order Assigned to Shaikh Ezaz By GB TEST', 120, '2021-04-08 16:08:29', 0),
(852, 'Order Assigned to Driver.', 120, '2021-04-08 16:08:29', 1),
(853, 'Order #1035 Picked Up By Shaikh Ezaz', 120, '2021-04-08 16:08:58', 0),
(854, 'Order Picked Up By Driver.', 120, '2021-04-08 16:08:58', 1),
(855, 'Order #1034 Picked Up By Shaikh Ezaz', 119, '2021-04-08 16:09:48', 0),
(856, 'Order Picked Up By Driver.', 119, '2021-04-08 16:09:48', 1),
(857, 'Order #1035 Delivered By Shaikh Ezaz', 120, '2021-04-08 16:12:40', 0),
(858, 'Order Delivered By Driver.', 120, '2021-04-08 16:12:40', 1),
(859, 'Order #1034 Delivered By Shaikh Ezaz', 119, '2021-04-08 16:12:45', 0),
(860, 'Order Delivered By Driver.', 119, '2021-04-08 16:12:45', 1),
(861, 'Order Created By GB TEST', 121, '2021-04-08 16:12:57', 0),
(862, 'Order Created.', 121, '2021-04-08 16:12:57', 1),
(863, 'Order Created By GB TEST', 122, '2021-04-08 16:13:07', 0),
(864, 'Order Created.', 122, '2021-04-08 16:13:07', 1),
(865, 'Order Assigned to Shaikh Ezaz By GB TEST', 121, '2021-04-08 16:13:18', 0),
(866, 'Order Assigned to Driver.', 121, '2021-04-08 16:13:18', 1),
(867, 'Order Assigned to Shaikh Ezaz By GB TEST', 122, '2021-04-08 16:13:28', 0),
(868, 'Order Assigned to Driver.', 122, '2021-04-08 16:13:28', 1),
(869, 'Order Assigned to Shaikh Ezaz By GB TEST', 122, '2021-04-08 16:31:38', 0),
(870, 'Order Assigned to Driver.', 122, '2021-04-08 16:31:38', 1),
(871, 'Order Assigned to Shaikh Ezaz By GB TEST', 121, '2021-04-08 16:31:40', 0),
(872, 'Order Assigned to Driver.', 121, '2021-04-08 16:31:40', 1),
(873, 'Order Assigned to Shaikh Ezaz By GB TEST', 121, '2021-04-08 16:33:36', 0),
(874, 'Order Assigned to Driver.', 121, '2021-04-08 16:33:36', 1),
(875, 'Order Assigned to Shaikh Ezaz By GB TEST', 122, '2021-04-08 16:33:41', 0),
(876, 'Order Assigned to Driver.', 122, '2021-04-08 16:33:41', 1),
(877, 'Order Assigned to Shaikh Ezaz By GB TEST', 121, '2021-04-08 16:34:14', 0),
(878, 'Order Assigned to Driver.', 121, '2021-04-08 16:34:14', 1),
(879, 'Order Assigned to Shaikh Ezaz By GB TEST', 122, '2021-04-08 16:34:22', 0),
(880, 'Order Assigned to Driver.', 122, '2021-04-08 16:34:22', 1),
(881, 'Order Assigned to Shaikh Ezaz By GB TEST', 122, '2021-04-08 16:34:46', 0),
(882, 'Order Assigned to Driver.', 122, '2021-04-08 16:34:46', 1),
(883, 'Order Assigned to Shaikh Ezaz By GB TEST', 121, '2021-04-08 16:34:46', 0),
(884, 'Order Assigned to Driver.', 121, '2021-04-08 16:34:46', 1),
(885, 'Order Assigned to Shaikh Ezaz By GB TEST', 122, '2021-04-08 16:36:58', 0),
(886, 'Order Assigned to Driver.', 122, '2021-04-08 16:36:58', 1),
(887, 'Order Assigned to Shaikh Ezaz By GB TEST', 121, '2021-04-08 16:36:58', 0),
(888, 'Order Assigned to Driver.', 121, '2021-04-08 16:36:58', 1),
(889, 'Order Assigned to Shaikh Ezaz By GB TEST', 121, '2021-04-08 16:38:18', 0),
(890, 'Order Assigned to Driver.', 121, '2021-04-08 16:38:18', 1),
(891, 'Order Assigned to Shaikh Ezaz By GB TEST', 122, '2021-04-08 16:38:24', 0),
(892, 'Order Assigned to Driver.', 122, '2021-04-08 16:38:24', 1),
(893, 'Order Assigned to Shaikh Ezaz By GB TEST', 96, '2021-04-08 16:44:41', 0),
(894, 'Order Assigned to Driver.', 96, '2021-04-08 16:44:41', 1),
(895, 'Order Assigned to Shaikh Ezaz By GB TEST', 105, '2021-04-08 16:46:43', 0),
(896, 'Order Assigned to Driver.', 105, '2021-04-08 16:46:43', 1),
(897, 'Order Assigned to Shaikh Ezaz By GB TEST', 101, '2021-04-08 16:46:44', 0),
(898, 'Order Assigned to Driver.', 101, '2021-04-08 16:46:44', 1),
(899, 'Order Assigned to Shaikh Ezaz By GB TEST', 121, '2021-04-08 16:51:17', 0),
(900, 'Order Assigned to Driver.', 121, '2021-04-08 16:51:17', 1),
(901, 'Order Assigned to Shaikh Ezaz By GB TEST', 122, '2021-04-08 16:51:23', 0),
(902, 'Order Assigned to Driver.', 122, '2021-04-08 16:51:23', 1),
(903, 'Order Assigned to Shaikh Ezaz By GB TEST', 121, '2021-04-08 16:52:24', 0),
(904, 'Order Assigned to Driver.', 121, '2021-04-08 16:52:24', 1),
(905, 'Order Assigned to Shaikh Ezaz By GB TEST', 122, '2021-04-08 16:52:29', 0),
(906, 'Order Assigned to Driver.', 122, '2021-04-08 16:52:29', 1),
(907, 'Order Assigned to Shaikh Ezaz By GB TEST', 96, '2021-04-08 16:53:03', 0),
(908, 'Order Assigned to Driver.', 96, '2021-04-08 16:53:03', 1),
(909, 'Order Assigned to Shaikh Ezaz By GB TEST', 105, '2021-04-08 16:53:04', 0),
(910, 'Order Assigned to Driver.', 105, '2021-04-08 16:53:04', 1),
(911, 'Order Requested.', 123, '2021-04-08 17:03:35', 0),
(912, 'Order Requested.', 123, '2021-04-08 17:03:35', 1),
(913, 'Order Approved.', 123, '2021-04-08 17:03:56', 0),
(914, 'Order Approved.', 123, '2021-04-08 17:03:56', 1),
(915, 'Order Payment Paid Online By Customer, Amount INR [616]', 123, '2021-04-08 17:04:12', 0),
(916, 'Order Successfully Placed.', 123, '2021-04-08 17:04:12', 1),
(917, 'Order Requested.', 124, '2021-04-08 17:05:20', 0),
(918, 'Order Requested.', 124, '2021-04-08 17:05:20', 1),
(919, 'Order Approved.', 124, '2021-04-08 17:05:38', 0),
(920, 'Order Approved.', 124, '2021-04-08 17:05:38', 1),
(921, 'Order Payment Paid Online By Customer, Amount INR [616]', 124, '2021-04-08 17:05:46', 0),
(922, 'Order Successfully Placed.', 124, '2021-04-08 17:05:46', 1),
(923, 'Order Requested.', 125, '2021-04-08 17:09:35', 0),
(924, 'Order Requested.', 125, '2021-04-08 17:09:35', 1),
(925, 'Order Approved.', 125, '2021-04-08 17:09:52', 0),
(926, 'Order Approved.', 125, '2021-04-08 17:09:52', 1),
(927, 'Order Requested.', 126, '2021-04-08 17:13:58', 0),
(928, 'Order Requested.', 126, '2021-04-08 17:13:58', 1),
(929, 'Order Approved.', 126, '2021-04-08 17:14:25', 0),
(930, 'Order Approved.', 126, '2021-04-08 17:14:25', 1),
(931, 'Order Payment Paid Online By Customer, Amount INR [168]', 126, '2021-04-08 17:15:09', 0),
(932, 'Order Successfully Placed.', 126, '2021-04-08 17:15:09', 1),
(933, 'Order Requested.', 127, '2021-04-08 17:20:48', 0),
(934, 'Order Requested.', 127, '2021-04-08 17:20:48', 1),
(935, 'Order Approved.', 127, '2021-04-08 17:21:03', 0),
(936, 'Order Approved.', 127, '2021-04-08 17:21:03', 1),
(937, 'Order Payment Paid Online By Customer, Amount INR [616]', 127, '2021-04-08 17:21:37', 0),
(938, 'Order Successfully Placed.', 127, '2021-04-08 17:21:37', 1),
(939, 'Order Requested.', 128, '2021-04-08 17:53:43', 0),
(940, 'Order Requested.', 128, '2021-04-08 17:53:43', 1),
(941, 'Order Approved.', 128, '2021-04-08 18:00:19', 0),
(942, 'Order Approved.', 128, '2021-04-08 18:00:19', 1),
(943, 'Order Payment Paid Online By Customer, Amount INR [66]', 128, '2021-04-08 18:00:31', 0),
(944, 'Order Successfully Placed.', 128, '2021-04-08 18:00:31', 1),
(945, 'Order Requested.', 129, '2021-04-08 18:01:04', 0),
(946, 'Order Requested.', 129, '2021-04-08 18:01:04', 1),
(947, 'Order Approved.', 129, '2021-04-08 18:01:23', 0),
(948, 'Order Approved.', 129, '2021-04-08 18:01:23', 1),
(949, 'Order Payment Paid Online By Customer, Amount INR [616]', 129, '2021-04-08 18:01:57', 0),
(950, 'Order Successfully Placed.', 129, '2021-04-08 18:01:57', 1),
(951, 'Order Requested.', 130, '2021-04-08 18:10:54', 0),
(952, 'Order Requested.', 130, '2021-04-08 18:10:54', 1),
(953, 'Order Approved.', 130, '2021-04-08 18:11:30', 0),
(954, 'Order Approved.', 130, '2021-04-08 18:11:30', 1),
(955, 'Order Payment Paid Online By Customer, Amount INR [3355]', 130, '2021-04-08 18:11:48', 0),
(956, 'Order Successfully Placed.', 130, '2021-04-08 18:11:48', 1),
(957, 'Order Requested.', 131, '2021-04-08 18:19:19', 0),
(958, 'Order Requested.', 131, '2021-04-08 18:19:19', 1),
(959, 'Order Requested.', 132, '2021-04-09 09:56:04', 0),
(960, 'Order Requested.', 132, '2021-04-09 09:56:04', 1),
(961, 'Order Approved.', 132, '2021-04-09 09:56:46', 0),
(962, 'Order Approved.', 132, '2021-04-09 09:56:46', 1),
(963, 'Order Payment Paid Online By Customer, Amount INR [858]', 132, '2021-04-09 09:57:24', 0),
(964, 'Order Successfully Placed.', 132, '2021-04-09 09:57:24', 1),
(965, 'Order Requested.', 133, '2021-04-09 10:00:56', 0),
(966, 'Order Requested.', 133, '2021-04-09 10:00:56', 1),
(967, 'Order Approved.', 133, '2021-04-09 10:01:14', 0),
(968, 'Order Approved.', 133, '2021-04-09 10:01:14', 1),
(969, 'Order Requested.', 134, '2021-04-09 10:38:10', 0),
(970, 'Order Requested.', 134, '2021-04-09 10:38:10', 1),
(971, 'Order Approved.', 134, '2021-04-09 10:38:29', 0),
(972, 'Order Approved.', 134, '2021-04-09 10:38:29', 1),
(973, 'Order Requested.', 135, '2021-04-09 10:40:35', 0),
(974, 'Order Requested.', 135, '2021-04-09 10:40:35', 1),
(975, 'Order Requested.', 136, '2021-04-09 10:40:37', 0),
(976, 'Order Requested.', 136, '2021-04-09 10:40:37', 1),
(977, 'Order Requested.', 137, '2021-04-09 10:40:38', 0),
(978, 'Order Requested.', 137, '2021-04-09 10:40:38', 1),
(979, 'Order Approved.', 135, '2021-04-09 11:10:48', 0),
(980, 'Order Approved.', 135, '2021-04-09 11:10:48', 1),
(981, 'Order Requested.', 138, '2021-04-09 11:11:04', 0),
(982, 'Order Requested.', 138, '2021-04-09 11:11:04', 1),
(983, 'Order Requested.', 139, '2021-04-09 11:23:42', 0),
(984, 'Order Requested.', 139, '2021-04-09 11:23:42', 1),
(985, 'Order Approved.', 139, '2021-04-09 11:24:23', 0),
(986, 'Order Approved.', 139, '2021-04-09 11:24:23', 1),
(987, 'Order Requested.', 140, '2021-04-09 11:28:44', 0),
(988, 'Order Requested.', 140, '2021-04-09 11:28:44', 1),
(989, 'Order Approved.', 140, '2021-04-09 11:29:47', 0),
(990, 'Order Approved.', 140, '2021-04-09 11:29:47', 1),
(991, 'Order Requested.', 141, '2021-04-09 11:33:50', 0),
(992, 'Order Requested.', 141, '2021-04-09 11:33:50', 1),
(993, 'Order Approved.', 141, '2021-04-09 11:34:15', 0),
(994, 'Order Approved.', 141, '2021-04-09 11:34:15', 1),
(995, 'Order Requested.', 142, '2021-04-09 11:38:23', 0),
(996, 'Order Requested.', 142, '2021-04-09 11:38:23', 1),
(997, 'Order Approved.', 142, '2021-04-09 11:38:41', 0),
(998, 'Order Approved.', 142, '2021-04-09 11:38:41', 1),
(999, 'Order Payment Paid Online By Customer, Amount INR [360]', 142, '2021-04-09 11:40:33', 0),
(1000, 'Order Successfully Placed.', 142, '2021-04-09 11:40:33', 1),
(1001, 'Order Requested.', 143, '2021-04-09 11:43:07', 0),
(1002, 'Order Requested.', 143, '2021-04-09 11:43:07', 1),
(1003, 'Order Approved.', 143, '2021-04-09 11:43:21', 0),
(1004, 'Order Approved.', 143, '2021-04-09 11:43:21', 1),
(1005, 'Order Requested.', 144, '2021-04-09 11:46:49', 0),
(1006, 'Order Requested.', 144, '2021-04-09 11:46:49', 1),
(1007, 'Order Approved.', 144, '2021-04-09 11:47:07', 0),
(1008, 'Order Approved.', 144, '2021-04-09 11:47:07', 1),
(1009, 'Order Payment Paid Online By Customer, Amount INR [400]', 144, '2021-04-09 11:47:42', 0),
(1010, 'Order Successfully Placed.', 144, '2021-04-09 11:47:42', 1),
(1011, 'Order Requested.', 145, '2021-04-09 11:54:47', 0),
(1012, 'Order Requested.', 145, '2021-04-09 11:54:47', 1),
(1013, 'Order Approved.', 145, '2021-04-09 11:55:10', 0),
(1014, 'Order Approved.', 145, '2021-04-09 11:55:10', 1),
(1015, 'Order Requested.', 146, '2021-04-09 12:18:55', 0),
(1016, 'Order Requested.', 146, '2021-04-09 12:18:55', 1),
(1017, 'Order Approved.', 146, '2021-04-09 12:19:12', 0),
(1018, 'Order Approved.', 146, '2021-04-09 12:19:12', 1),
(1019, 'Order Requested.', 147, '2021-04-09 12:20:57', 0),
(1020, 'Order Requested.', 147, '2021-04-09 12:20:57', 1),
(1021, 'Order Approved.', 147, '2021-04-09 12:21:16', 0),
(1022, 'Order Approved.', 147, '2021-04-09 12:21:16', 1),
(1023, 'Order Payment Paid Online By Customer, Amount INR [1936]', 147, '2021-04-09 12:21:40', 0),
(1024, 'Order Successfully Placed.', 147, '2021-04-09 12:21:40', 1),
(1025, 'Order Requested.', 148, '2021-04-09 12:22:30', 0),
(1026, 'Order Requested.', 148, '2021-04-09 12:22:30', 1),
(1027, 'Order Requested.', 149, '2021-04-09 12:29:06', 0),
(1028, 'Order Requested.', 149, '2021-04-09 12:29:06', 1),
(1029, 'Order Approved.', 149, '2021-04-09 12:29:29', 0),
(1030, 'Order Approved.', 149, '2021-04-09 12:29:29', 1),
(1031, 'Order Payment Paid Online By Customer, Amount INR [242]', 149, '2021-04-09 12:29:51', 0),
(1032, 'Order Successfully Placed.', 149, '2021-04-09 12:29:51', 1),
(1033, 'Order Approved.', 148, '2021-04-09 12:36:12', 0),
(1034, 'Order Approved.', 148, '2021-04-09 12:36:12', 1),
(1035, 'Order Payment Paid Online By Customer, Amount INR [2860]', 148, '2021-04-09 12:37:40', 0),
(1036, 'Order Successfully Placed.', 148, '2021-04-09 12:37:40', 1),
(1037, 'Order Requested.', 150, '2021-04-09 12:40:59', 0),
(1038, 'Order Requested.', 150, '2021-04-09 12:40:59', 1),
(1039, 'Order Approved.', 150, '2021-04-09 12:41:17', 0),
(1040, 'Order Approved.', 150, '2021-04-09 12:41:17', 1),
(1041, 'Order Placed With COD By Customer.', 150, '2021-04-09 12:48:32', 0),
(1042, 'Order Successfully Placed With COD.', 150, '2021-04-09 12:48:32', 1),
(1043, 'Order Approved.', 138, '2021-04-09 12:57:55', 0),
(1044, 'Order Approved.', 138, '2021-04-09 12:57:55', 1),
(1045, 'Order Requested.', 151, '2021-04-09 13:58:23', 0),
(1046, 'Order Requested.', 151, '2021-04-09 13:58:23', 1),
(1047, 'Order Approved.', 151, '2021-04-09 13:58:52', 0),
(1048, 'Order Approved.', 151, '2021-04-09 13:58:52', 1),
(1049, 'Order Payment Paid Online By Customer, Amount INR [684]', 151, '2021-04-09 13:59:39', 0),
(1050, 'Order Successfully Placed.', 151, '2021-04-09 13:59:39', 1),
(1051, 'Order Requested.', 152, '2021-04-09 14:01:25', 0),
(1052, 'Order Requested.', 152, '2021-04-09 14:01:25', 1),
(1053, 'Order Approved.', 152, '2021-04-09 14:03:17', 0),
(1054, 'Order Approved.', 152, '2021-04-09 14:03:17', 1),
(1055, 'Order Payment Paid Online By Customer, Amount INR [770]', 152, '2021-04-09 14:03:44', 0),
(1056, 'Order Successfully Placed.', 152, '2021-04-09 14:03:44', 1),
(1057, 'Order Requested.', 153, '2021-04-09 14:16:16', 0),
(1058, 'Order Requested.', 153, '2021-04-09 14:16:16', 1),
(1059, 'Order Approved.', 153, '2021-04-09 14:25:35', 0),
(1060, 'Order Approved.', 153, '2021-04-09 14:25:35', 1),
(1061, 'Order Requested.', 154, '2021-04-09 15:04:55', 0),
(1062, 'Order Requested.', 154, '2021-04-09 15:04:55', 1),
(1063, 'Order Approved.', 154, '2021-04-09 15:05:16', 0),
(1064, 'Order Approved.', 154, '2021-04-09 15:05:16', 1),
(1065, 'Order Payment Paid Online By Customer, Amount INR [840]', 153, '2021-04-09 15:06:30', 0),
(1066, 'Order Successfully Placed.', 153, '2021-04-09 15:06:30', 1),
(1067, 'Order Requested.', 155, '2021-04-09 16:22:55', 0),
(1068, 'Order Requested.', 155, '2021-04-09 16:22:55', 1),
(1069, 'Order Approved.', 155, '2021-04-09 16:23:17', 0),
(1070, 'Order Approved.', 155, '2021-04-09 16:23:17', 1),
(1071, 'Order Requested.', 156, '2021-04-09 16:24:23', 0),
(1072, 'Order Requested.', 156, '2021-04-09 16:24:23', 1),
(1073, 'Order Approved.', 156, '2021-04-09 16:24:37', 0),
(1074, 'Order Approved.', 156, '2021-04-09 16:24:37', 1),
(1075, 'Order Payment Paid Online By Customer, Amount INR [616]', 154, '2021-04-09 16:30:32', 0),
(1076, 'Order Successfully Placed.', 154, '2021-04-09 16:30:32', 1),
(1077, 'Order Requested.', 157, '2021-04-09 16:35:11', 0),
(1078, 'Order Requested.', 157, '2021-04-09 16:35:11', 1),
(1079, 'Order Approved.', 157, '2021-04-09 16:35:26', 0),
(1080, 'Order Approved.', 157, '2021-04-09 16:35:26', 1),
(1081, 'Order Payment Paid Online By Customer, Amount INR [649]', 157, '2021-04-09 16:36:03', 0),
(1082, 'Order Successfully Placed.', 157, '2021-04-09 16:36:03', 1),
(1083, 'Order Assigned to Mohammad Fahad Shaikh By GB TEST', 157, '2021-04-09 16:47:02', 0),
(1084, 'Order Assigned to Driver.', 157, '2021-04-09 16:47:02', 1),
(1085, 'Order Assigned to Shaikh Ezaz By Admin', 154, '2021-04-09 17:10:57', 0),
(1086, 'Order Assigned to Driver.', 154, '2021-04-09 17:10:57', 1),
(1087, 'Order Assigned to Shaikh Ezaz By Admin', 153, '2021-04-09 17:14:03', 0),
(1088, 'Order Assigned to Driver.', 153, '2021-04-09 17:14:03', 1),
(1089, 'Order Placed With COD By Customer.', 155, '2021-04-14 11:01:43', 0),
(1090, 'Order Successfully Placed With COD.', 155, '2021-04-14 11:01:43', 1),
(1091, 'Order Details Edited By GB TEST', 155, '2021-04-14 14:19:09', 0),
(1092, 'Order Assigned to Mohammad Fahad Shaikh By GB TEST', 155, '2021-04-15 16:42:50', 0),
(1093, 'Order Assigned to Driver.', 155, '2021-04-15 16:42:50', 1),
(1094, 'Invoice Deleted By GB TEST', 63, '2021-04-17 10:17:07', 0),
(1095, 'Order Approved.', 158, '2021-04-17 14:57:33', 0),
(1096, 'Order Approved.', 158, '2021-04-17 14:57:33', 1),
(1097, 'Order Details Edited By GB TEST', 158, '2021-04-17 17:39:08', 0),
(1098, 'Order Requested.', 160, '2021-04-19 12:25:27', 0),
(1099, 'Order Requested.', 160, '2021-04-19 12:25:27', 1),
(1100, 'Order Requested.', 165, '2021-04-19 12:41:30', 0),
(1101, 'Order Requested.', 165, '2021-04-19 12:41:30', 1),
(1102, 'Order Requested.', 166, '2021-04-19 12:53:29', 0),
(1103, 'Order Requested.', 166, '2021-04-19 12:53:29', 1),
(1104, 'Order Requested.', 167, '2021-04-19 12:56:04', 0),
(1105, 'Order Requested.', 167, '2021-04-19 12:56:04', 1),
(1106, 'Order Requested.', 168, '2021-04-19 12:57:18', 0),
(1107, 'Order Requested.', 168, '2021-04-19 12:57:18', 1),
(1108, 'Order Requested.', 169, '2021-04-19 12:58:55', 0),
(1109, 'Order Requested.', 169, '2021-04-19 12:58:55', 1),
(1110, 'Order Requested.', 170, '2021-04-19 13:01:38', 0),
(1111, 'Order Requested.', 170, '2021-04-19 13:01:38', 1),
(1112, 'Order Approved.', 169, '2021-04-19 13:01:55', 0),
(1113, 'Order Approved.', 169, '2021-04-19 13:01:55', 1),
(1114, 'Order Details Edited By GB TEST', 169, '2021-04-19 13:03:04', 0),
(1115, 'Order Details Edited By GB TEST', 169, '2021-04-19 13:13:11', 0),
(1116, 'Order Placed With COD By Customer.', 169, '2021-04-19 17:32:38', 0),
(1117, 'Order Successfully Placed With COD.', 169, '2021-04-19 17:32:38', 1),
(1118, 'Order Requested.', 171, '2021-04-19 17:35:28', 0),
(1119, 'Order Requested.', 171, '2021-04-19 17:35:28', 1),
(1120, 'Order Approved.', 171, '2021-04-19 17:36:09', 0),
(1121, 'Order Approved.', 171, '2021-04-19 17:36:09', 1),
(1122, 'Order Details Edited By GB TEST', 171, '2021-04-19 17:46:13', 0),
(1123, 'Order Placed With COD By Customer.', 171, '2021-04-19 17:50:50', 0),
(1124, 'Order Successfully Placed With COD.', 171, '2021-04-19 17:50:50', 1),
(1125, 'Order Requested.', 172, '2021-04-19 17:51:23', 0),
(1126, 'Order Requested.', 172, '2021-04-19 17:51:23', 1),
(1127, 'Order Approved.', 172, '2021-04-19 17:51:57', 0),
(1128, 'Order Approved.', 172, '2021-04-19 17:51:57', 1),
(1129, 'Order Details Edited By GB TEST', 172, '2021-04-19 17:53:54', 0),
(1130, 'Order Requested.', 173, '2021-04-19 17:55:25', 0),
(1131, 'Order Requested.', 173, '2021-04-19 17:55:25', 1),
(1132, 'Order Requested.', 174, '2021-04-19 17:57:09', 0),
(1133, 'Order Requested.', 174, '2021-04-19 17:57:09', 1),
(1134, 'Order Requested.', 175, '2021-04-19 18:01:22', 0),
(1135, 'Order Requested.', 175, '2021-04-19 18:01:22', 1),
(1136, 'Order Approved.', 175, '2021-04-19 18:01:52', 0),
(1137, 'Order Approved.', 175, '2021-04-19 18:01:52', 1),
(1138, 'Order Placed With COD By Customer.', 175, '2021-04-19 18:03:45', 0),
(1139, 'Order Successfully Placed With COD.', 175, '2021-04-19 18:03:45', 1),
(1140, 'Order Requested.', 176, '2021-04-19 18:04:45', 0),
(1141, 'Order Requested.', 176, '2021-04-19 18:04:45', 1),
(1142, 'Order Requested.', 177, '2021-04-19 18:05:51', 0),
(1143, 'Order Requested.', 177, '2021-04-19 18:05:51', 1),
(1144, 'Order Requested.', 178, '2021-04-19 18:07:11', 0),
(1145, 'Order Requested.', 178, '2021-04-19 18:07:11', 1),
(1146, 'Order Requested.', 179, '2021-04-19 18:08:33', 0),
(1147, 'Order Requested.', 179, '2021-04-19 18:08:33', 1),
(1148, 'Order Requested.', 180, '2021-04-19 18:13:28', 0),
(1149, 'Order Requested.', 180, '2021-04-19 18:13:28', 1),
(1150, 'Order Details Edited By GB TEST', 180, '2021-04-19 18:15:52', 0),
(1151, 'Order Requested.', 181, '2021-04-19 18:17:14', 0),
(1152, 'Order Requested.', 181, '2021-04-19 18:17:14', 1),
(1153, 'Order Placed With COD By Customer.', 180, '2021-04-19 18:22:06', 0),
(1154, 'Order Successfully Placed With COD.', 180, '2021-04-19 18:22:06', 1),
(1155, 'Order Requested.', 182, '2021-04-19 18:40:20', 0),
(1156, 'Order Requested.', 182, '2021-04-19 18:40:20', 1),
(1157, 'Order Approved.', 182, '2021-04-19 18:41:06', 0),
(1158, 'Order Approved.', 182, '2021-04-19 18:41:06', 1),
(1159, 'Order Details Edited By GB TEST', 182, '2021-04-19 18:44:30', 0),
(1160, 'Order Requested.', 183, '2021-04-19 18:52:08', 0),
(1161, 'Order Requested.', 183, '2021-04-19 18:52:08', 1),
(1162, 'Order Approved.', 183, '2021-04-19 18:52:33', 0),
(1163, 'Order Approved.', 183, '2021-04-19 18:52:33', 1),
(1164, 'Order Requested.', 184, '2021-04-20 12:49:38', 0),
(1165, 'Order Requested.', 184, '2021-04-20 12:49:38', 1),
(1166, 'Order Approved.', 184, '2021-04-20 12:50:38', 0),
(1167, 'Order Approved.', 184, '2021-04-20 12:50:38', 1),
(1168, 'Order Placed With COD By Customer.', 184, '2021-04-20 12:53:15', 0),
(1169, 'Order Successfully Placed With COD.', 184, '2021-04-20 12:53:15', 1),
(1170, 'Order Requested.', 185, '2021-04-20 12:59:06', 0),
(1171, 'Order Requested.', 185, '2021-04-20 12:59:06', 1),
(1172, 'Order Approved.', 185, '2021-04-20 12:59:34', 0),
(1173, 'Order Approved.', 185, '2021-04-20 12:59:34', 1),
(1174, 'Order Requested.', 190, '2021-04-20 14:48:05', 0),
(1175, 'Order Requested.', 190, '2021-04-20 14:48:05', 1),
(1176, 'Order Approved.', 190, '2021-04-20 14:48:34', 0),
(1177, 'Order Approved.', 190, '2021-04-20 14:48:34', 1),
(1178, 'Order Placed With Wallet By Customer.', 185, '2021-04-20 17:33:17', 0),
(1179, 'Order Successfully Placed With Wallet.', 185, '2021-04-20 17:33:17', 1),
(1180, 'Order Requested.', 191, '2021-04-20 17:39:24', 0),
(1181, 'Order Requested.', 191, '2021-04-20 17:39:24', 1),
(1182, 'Order Approved.', 191, '2021-04-20 17:40:08', 0),
(1183, 'Order Approved.', 191, '2021-04-20 17:40:08', 1),
(1184, 'Order Placed With Wallet By Customer.', 191, '2021-04-20 17:41:06', 0),
(1185, 'Order Successfully Placed With Wallet.', 191, '2021-04-20 17:41:06', 1),
(1186, 'Order Requested.', 192, '2021-04-20 17:58:07', 0),
(1187, 'Order Requested.', 192, '2021-04-20 17:58:07', 1),
(1188, 'Order Approved.', 192, '2021-04-20 18:00:18', 0),
(1189, 'Order Approved.', 192, '2021-04-20 18:00:18', 1),
(1190, 'Order Placed With Wallet By Customer.', 192, '2021-04-20 18:02:45', 0),
(1191, 'Order Successfully Placed With Wallet.', 192, '2021-04-20 18:02:45', 1),
(1192, 'Order Requested.', 193, '2021-04-22 12:09:17', 0),
(1193, 'Order Requested.', 193, '2021-04-22 12:09:17', 1),
(1194, 'Order Requested.', 194, '2021-04-22 12:13:05', 0),
(1195, 'Order Requested.', 194, '2021-04-22 12:13:05', 1),
(1196, 'Order Requested.', 195, '2021-04-22 12:27:54', 0),
(1197, 'Order Requested.', 195, '2021-04-22 12:27:54', 1),
(1198, 'Order Approved.', 195, '2021-04-22 12:28:52', 0),
(1199, 'Order Approved.', 195, '2021-04-22 12:28:52', 1),
(1200, 'Order Requested.', 196, '2021-04-23 11:12:17', 0),
(1201, 'Order Requested.', 196, '2021-04-23 11:12:17', 1),
(1202, 'Order Requested.', 197, '2021-04-23 11:41:43', 0),
(1203, 'Order Requested.', 197, '2021-04-23 11:41:43', 1),
(1204, 'Order Approved.', 197, '2021-04-23 11:42:04', 0),
(1205, 'Order Approved.', 197, '2021-04-23 11:42:04', 1),
(1206, 'Order Created By GB TEST', 198, '2021-04-23 17:04:51', 0),
(1207, 'Order Created.', 198, '2021-04-23 17:04:51', 1),
(1208, 'New Invoice #51 Created By GB TEST', 120, '2021-04-23 18:52:51', 0),
(1209, 'Order Created By GB TEST', 200, '2021-04-28 18:05:35', 0),
(1210, 'Order Created By Bigdaddy.', 200, '2021-04-28 18:05:35', 1),
(1211, 'Order Requested.', 201, '2021-04-29 11:19:50', 0),
(1212, 'Order Requested.', 201, '2021-04-29 11:19:50', 1),
(1213, 'Order Cancelled By Customer.', 200, '2021-04-29 12:32:08', 0),
(1214, 'Order Cancelled.', 200, '2021-04-29 12:32:08', 1),
(1215, 'Payment Paid is Marked By GB TEST', 120, '2021-04-29 13:02:18', 0),
(1216, 'New Invoice #52 Created By GB TEST', 155, '2021-04-29 14:35:20', 0),
(1217, 'Payment Paid is Marked By GB TEST', 155, '2021-04-29 14:36:28', 0),
(1218, 'Order Created By GB TEST', 202, '2021-04-29 16:18:43', 0),
(1219, 'Order Created By Bigdaddy.', 202, '2021-04-29 16:18:43', 1),
(1220, 'Order Created By GB TEST', 203, '2021-04-29 16:20:10', 0),
(1221, 'Order Created By Bigdaddy.', 203, '2021-04-29 16:20:10', 1),
(1222, 'Order Cancelled By Customer.', 203, '2021-04-29 16:23:38', 0),
(1223, 'Order Cancelled.', 203, '2021-04-29 16:23:38', 1),
(1224, 'Order Created By GB TEST', 204, '2021-04-29 16:46:28', 0),
(1225, 'Order Created By Bigdaddy.', 204, '2021-04-29 16:46:28', 1),
(1226, 'Order Cancelled By Customer.', 204, '2021-04-29 16:49:16', 0),
(1227, 'Order Cancelled.', 204, '2021-04-29 16:49:16', 1),
(1228, 'Order Created By GB TEST', 205, '2021-04-30 11:00:58', 0),
(1229, 'Order Created By Bigdaddy.', 205, '2021-04-30 11:00:58', 1),
(1230, 'Order Created By GB TEST', 206, '2021-04-30 13:01:56', 0),
(1231, 'Order Created By Bigdaddy.', 206, '2021-04-30 13:01:56', 1),
(1232, 'Order Created By GB TEST', 207, '2021-04-30 13:01:56', 0),
(1233, 'Order Created By Bigdaddy.', 207, '2021-04-30 13:01:56', 1),
(1234, 'Order Assigned to Shaikh Ezaz By GB TEST', 105, '2021-04-30 16:05:47', 0),
(1235, 'Order Assigned to Driver.', 105, '2021-04-30 16:05:47', 1),
(1236, 'Order Assigned to Mohammad Fahad Shaikh By GB TEST', 199, '2021-04-30 16:11:35', 0),
(1237, 'Order Assigned to Driver.', 199, '2021-04-30 16:11:35', 1),
(1238, 'Order Assigned to Mohammad Fahad Shaikh By GB TEST', 198, '2021-04-30 18:29:18', 0),
(1239, 'Order Assigned to Driver.', 198, '2021-04-30 18:29:18', 1),
(1240, 'Order Assigned to Mohammad Fahad Shaikh By GB TEST', 192, '2021-05-01 12:54:19', 0),
(1241, 'Order Assigned to Driver.', 192, '2021-05-01 12:54:19', 1),
(1242, 'Order Details Edited By GB TEST', 199, '2021-05-01 13:07:53', 0),
(1243, 'Order Assigned to Big Daddy By GB TEST', 199, '2021-05-03 12:55:56', 0),
(1244, 'Order Assigned to Driver.', 199, '2021-05-03 12:55:56', 1),
(1245, 'Order #1052 Detail Mismatched By Mohammad Fahad Shaikh', 198, '2021-05-03 15:33:52', 0),
(1246, 'Order Assigned to Mohammad Fahad Shaikh By GB TEST', 191, '2021-05-03 15:36:25', 0),
(1247, 'Order Assigned to Driver.', 191, '2021-05-03 15:36:25', 1),
(1248, 'Order Created By GB TEST', 208, '2021-05-03 15:41:07', 0),
(1249, 'Order Created By Bigdaddy.', 208, '2021-05-03 15:41:07', 1),
(1250, 'Order #1052 Picked Up By Mohammad Fahad Shaikh', 198, '2021-05-03 15:47:44', 0),
(1251, 'Order Picked Up By Driver.', 198, '2021-05-03 15:47:44', 1),
(1252, 'Order Created By GB TEST', 209, '2021-05-03 15:53:37', 0),
(1253, 'Order Created By Bigdaddy.', 209, '2021-05-03 15:53:37', 1),
(1254, 'Order Created By GB TEST', 210, '2021-05-03 15:54:31', 0),
(1255, 'Order Created By Bigdaddy.', 210, '2021-05-03 15:54:31', 1),
(1256, 'Order Created By GB TEST', 211, '2021-05-03 15:55:43', 0),
(1257, 'Order Created By Bigdaddy.', 211, '2021-05-03 15:55:43', 1),
(1258, 'Order Assigned to Mohammad Fahad Shaikh By GB TEST', 211, '2021-05-03 15:56:06', 0),
(1259, 'Order Assigned to Driver.', 211, '2021-05-03 15:56:06', 1),
(1260, 'Order Assigned to Mohammad Fahad Shaikh By GB TEST', 210, '2021-05-03 15:56:06', 0),
(1261, 'Order Assigned to Driver.', 210, '2021-05-03 15:56:06', 1),
(1262, 'Order Assigned to Mohammad Fahad Shaikh By GB TEST', 209, '2021-05-03 15:56:07', 0),
(1263, 'Order Assigned to Driver.', 209, '2021-05-03 15:56:07', 1),
(1264, 'Order #1064 Picked Up By Mohammad Fahad Shaikh', 211, '2021-05-03 16:34:01', 0),
(1265, 'Order Picked Up By Driver.', 211, '2021-05-03 16:34:01', 1),
(1266, 'Order #1062 Picked Up By Mohammad Fahad Shaikh', 209, '2021-05-03 16:38:47', 0),
(1267, 'Order Picked Up By Driver.', 209, '2021-05-03 16:38:47', 1),
(1268, 'Order #1063 Picked Up By Mohammad Fahad Shaikh', 210, '2021-05-03 16:46:47', 0),
(1269, 'Order Picked Up By Driver.', 210, '2021-05-03 16:46:47', 1),
(1270, 'Order Assigned to Mohammad Fahad Shaikh By GB TEST', 210, '2021-05-03 16:51:31', 0),
(1271, 'Order Assigned to Driver.', 210, '2021-05-03 16:51:31', 1),
(1272, 'Order Assigned to Mohammad Fahad Shaikh By GB TEST', 185, '2021-05-03 16:52:58', 0),
(1273, 'Order Assigned to Driver.', 185, '2021-05-03 16:52:58', 1),
(1274, 'Order #1062 Delivered By Mohammad Fahad Shaikh', 209, '2021-05-03 16:53:31', 0),
(1275, 'Order Delivered By Driver.', 209, '2021-05-03 16:53:31', 1),
(1276, 'Order #1063 Picked Up By Mohammad Fahad Shaikh', 210, '2021-05-03 16:54:52', 0),
(1277, 'Order Picked Up By Driver.', 210, '2021-05-03 16:54:52', 1),
(1278, 'Order #1063 Delivered By Mohammad Fahad Shaikh', 210, '2021-05-03 16:55:21', 0),
(1279, 'Order Delivered By Driver.', 210, '2021-05-03 16:55:21', 1),
(1280, 'Order Assigned to Mohammad Fahad Shaikh By GB TEST', 184, '2021-05-03 17:01:05', 0),
(1281, 'Order Assigned to Driver.', 184, '2021-05-03 17:01:05', 1),
(1282, 'Order Assigned to Mohammad Fahad Shaikh By GB TEST', 180, '2021-05-03 17:01:06', 0),
(1283, 'Order Assigned to Driver.', 180, '2021-05-03 17:01:06', 1),
(1284, 'Order Assigned to Shaikh Ezaz By GB TEST', 105, '2021-05-03 17:05:19', 0),
(1285, 'Order Assigned to Driver.', 105, '2021-05-03 17:05:19', 1),
(1286, 'Order Assigned to Shoaib Sheikh By GB TEST', 121, '2021-05-03 17:05:52', 0),
(1287, 'Order Assigned to Driver.', 121, '2021-05-03 17:05:52', 1),
(1288, 'Order #1049 Picked Up By Mohammad Fahad Shaikh', 185, '2021-05-03 17:08:29', 0),
(1289, 'Order Picked Up By Driver.', 185, '2021-05-03 17:08:29', 1),
(1290, 'Order #1049 Delivered By Mohammad Fahad Shaikh', 185, '2021-05-03 17:09:22', 0),
(1291, 'Order Delivered By Driver.', 185, '2021-05-03 17:09:22', 1),
(1292, 'Order #1048 Picked Up By Mohammad Fahad Shaikh', 184, '2021-05-03 17:12:41', 0),
(1293, 'Order Picked Up By Driver.', 184, '2021-05-03 17:12:41', 1),
(1294, 'Order #1048 Delivered By Mohammad Fahad Shaikh', 184, '2021-05-03 17:13:09', 0),
(1295, 'Order Delivered By Driver.', 184, '2021-05-03 17:13:09', 1),
(1296, 'Order Requested.', 212, '2021-05-03 17:26:44', 0),
(1297, 'Order Requested.', 212, '2021-05-03 17:26:44', 1),
(1298, 'Order Assigned to Mohammad Fahad Shaikh By GB TEST', 153, '2021-05-03 17:32:57', 0),
(1299, 'Order Assigned to Driver.', 153, '2021-05-03 17:32:57', 1),
(1300, 'Order Requested.', 213, '2021-05-03 17:33:04', 0),
(1301, 'Order Requested.', 213, '2021-05-03 17:33:04', 1),
(1302, 'Order #1047 Picked Up By Mohammad Fahad Shaikh', 180, '2021-05-03 17:33:41', 0),
(1303, 'Order Picked Up By Driver.', 180, '2021-05-03 17:33:41', 1),
(1304, 'Order Assigned to Shoaib Sheikh By GB TEST', 175, '2021-05-03 17:39:36', 0),
(1305, 'Order Assigned to Driver.', 175, '2021-05-03 17:39:36', 1),
(1306, 'Order #1047 Delivered By Mohammad Fahad Shaikh', 180, '2021-05-03 18:03:53', 0),
(1307, 'Order Delivered By Driver.', 180, '2021-05-03 18:03:53', 1),
(1308, 'Order #1040 Picked Up By Mohammad Fahad Shaikh', 153, '2021-05-03 18:21:01', 0),
(1309, 'Order Picked Up By Driver.', 153, '2021-05-03 18:21:01', 1),
(1310, 'Order #1040 Undelivered By Mohammad Fahad Shaikh [Shop Closed]', 153, '2021-05-03 18:21:20', 0),
(1311, 'Order Undelivered Due to Some Reason.', 153, '2021-05-03 18:21:20', 1),
(1312, 'Order Assigned to Mohammad Fahad Shaikh By GB TEST', 171, '2021-05-03 18:22:04', 0),
(1313, 'Order Assigned to Driver.', 171, '2021-05-03 18:22:04', 1),
(1314, 'Order Assigned to Mohammad Fahad Shaikh By GB TEST', 169, '2021-05-03 18:22:05', 0),
(1315, 'Order Assigned to Driver.', 169, '2021-05-03 18:22:05', 1),
(1316, 'Order #1045 Picked Up By Mohammad Fahad Shaikh', 171, '2021-05-03 18:22:24', 0),
(1317, 'Order Picked Up By Driver.', 171, '2021-05-03 18:22:24', 1),
(1318, 'Order #1045 Delivered By Mohammad Fahad Shaikh', 171, '2021-05-03 18:22:31', 0),
(1319, 'Order Delivered By Driver.', 171, '2021-05-03 18:22:31', 1),
(1320, 'Order Assigned to Mohammad Fahad Shaikh By GB TEST', 152, '2021-05-03 18:22:58', 0),
(1321, 'Order Assigned to Driver.', 152, '2021-05-03 18:22:58', 1),
(1322, 'Order Assigned to Mohammad Fahad Shaikh By GB TEST', 151, '2021-05-03 18:23:00', 0),
(1323, 'Order Assigned to Driver.', 151, '2021-05-03 18:23:00', 1),
(1324, 'Order #1044 Picked Up By Mohammad Fahad Shaikh', 169, '2021-05-03 18:23:15', 0),
(1325, 'Order Picked Up By Driver.', 169, '2021-05-03 18:23:15', 1),
(1326, 'Order #1044 Undelivered By Mohammad Fahad Shaikh [Shop Closed]', 169, '2021-05-03 18:23:31', 0),
(1327, 'Order Undelivered Due to Some Reason.', 169, '2021-05-03 18:23:31', 1),
(1328, 'Order #1039 Picked Up By Mohammad Fahad Shaikh', 152, '2021-05-03 18:27:44', 0),
(1329, 'Order Picked Up By Driver.', 152, '2021-05-03 18:27:44', 1),
(1330, 'Order #1039 Undelivered By Mohammad Fahad Shaikh [Shop Closed]', 152, '2021-05-03 18:27:53', 0),
(1331, 'Order Undelivered Due to Some Reason.', 152, '2021-05-03 18:27:53', 1),
(1332, 'Order Details Edited By Technomads Developer', 106, '2021-05-06 12:57:41', 0),
(1333, 'Order Details Edited By Technomads Developer', 108, '2021-05-06 12:58:47', 0),
(1334, 'Order Details Edited By Technomads Developer', 108, '2021-05-06 12:58:48', 0),
(1335, 'Order Assigned to Mohammad Fahad Shaikh By Technomads Developer', 106, '2021-05-06 12:59:11', 0),
(1336, 'Order Assigned to Driver.', 106, '2021-05-06 12:59:11', 1),
(1337, 'Order Assigned to Mohammad Fahad Shaikh By Technomads Developer', 108, '2021-05-06 12:59:15', 0),
(1338, 'Order Assigned to Driver.', 108, '2021-05-06 12:59:15', 1),
(1339, 'Order #1031 Picked Up By Mohammad Fahad Shaikh', 106, '2021-05-06 12:59:33', 0),
(1340, 'Order Picked Up By Driver.', 106, '2021-05-06 12:59:33', 1),
(1341, 'Order #1031 Delivered By Mohammad Fahad Shaikh', 106, '2021-05-06 13:00:31', 0),
(1342, 'Order Delivered By Driver.', 106, '2021-05-06 13:00:31', 1),
(1343, 'Order #1023 Picked Up By Mohammad Fahad Shaikh', 108, '2021-05-06 14:03:49', 0),
(1344, 'Order Picked Up By Driver.', 108, '2021-05-06 14:03:49', 1),
(1345, 'Order #1023 Delivered By Mohammad Fahad Shaikh', 108, '2021-05-06 14:20:51', 0),
(1346, 'Order Delivered By Driver.', 108, '2021-05-06 14:20:51', 1),
(1347, 'Order Created By Technomads Developer', 214, '2021-05-06 14:23:34', 0),
(1348, 'Order Created By Bigdaddy.', 214, '2021-05-06 14:23:34', 1),
(1349, 'Order Created By Technomads Developer', 215, '2021-05-06 14:24:18', 0),
(1350, 'Order Created By Bigdaddy.', 215, '2021-05-06 14:24:18', 1),
(1351, 'Order Assigned to Mohammad Fahad Shaikh By Technomads Developer', 215, '2021-05-06 14:24:50', 0),
(1352, 'Order Assigned to Driver.', 215, '2021-05-06 14:24:50', 1),
(1353, 'Order Assigned to Mohammad Fahad Shaikh By Technomads Developer', 214, '2021-05-06 14:24:52', 0),
(1354, 'Order Assigned to Driver.', 214, '2021-05-06 14:24:52', 1),
(1355, 'Order #1066 Picked Up By Mohammad Fahad Shaikh', 215, '2021-05-06 14:26:39', 0),
(1356, 'Order Picked Up By Driver.', 215, '2021-05-06 14:26:39', 1),
(1357, 'Order #1066 Delivered By Mohammad Fahad Shaikh', 215, '2021-05-06 14:28:41', 0),
(1358, 'Order Delivered By Driver.', 215, '2021-05-06 14:28:41', 1),
(1359, 'Order #1066 Delivered By Mohammad Fahad Shaikh', 215, '2021-05-06 14:36:51', 0),
(1360, 'Order Delivered By Driver.', 215, '2021-05-06 14:36:51', 1),
(1361, 'Order #1066 Delivered By Mohammad Fahad Shaikh', 215, '2021-05-06 14:38:07', 0),
(1362, 'Order Delivered By Driver.', 215, '2021-05-06 14:38:07', 1),
(1363, 'Order #1065 Picked Up By Mohammad Fahad Shaikh', 214, '2021-05-06 14:38:32', 0),
(1364, 'Order Picked Up By Driver.', 214, '2021-05-06 14:38:32', 1),
(1365, 'Order #1065 Delivered By Mohammad Fahad Shaikh', 214, '2021-05-06 14:38:55', 0),
(1366, 'Order Delivered By Driver.', 214, '2021-05-06 14:38:55', 1),
(1367, 'Order Created By Technomads Developer', 216, '2021-05-06 14:46:02', 0),
(1368, 'Order Created By Bigdaddy.', 216, '2021-05-06 14:46:02', 1),
(1369, 'Order Created By Technomads Developer', 217, '2021-05-06 14:46:35', 0),
(1370, 'Order Created By Bigdaddy.', 217, '2021-05-06 14:46:35', 1),
(1371, 'Order Assigned to Mohammad Fahad Shaikh By Technomads Developer', 217, '2021-05-06 14:46:49', 0),
(1372, 'Order Assigned to Driver.', 217, '2021-05-06 14:46:49', 1),
(1373, 'Order Assigned to Mohammad Fahad Shaikh By Technomads Developer', 216, '2021-05-06 14:46:50', 0),
(1374, 'Order Assigned to Driver.', 216, '2021-05-06 14:46:50', 1),
(1375, 'Order #1068 Picked Up By Mohammad Fahad Shaikh', 217, '2021-05-06 14:47:32', 0),
(1376, 'Order Picked Up By Driver.', 217, '2021-05-06 14:47:32', 1),
(1377, 'Order #1068 Delivered By Mohammad Fahad Shaikh', 217, '2021-05-06 14:55:49', 0),
(1378, 'Order Delivered By Driver.', 217, '2021-05-06 14:55:49', 1),
(1379, 'Order #1067 Delivered By Mohammad Fahad Shaikh', 216, '2021-05-06 14:56:29', 0),
(1380, 'Order Delivered By Driver.', 216, '2021-05-06 14:56:29', 1),
(1381, 'Order Assigned to Mohammad Fahad Shaikh By Technomads Developer', 109, '2021-05-06 15:00:46', 0),
(1382, 'Order Assigned to Driver.', 109, '2021-05-06 15:00:46', 1),
(1383, 'Order Assigned to Mohammad Fahad Shaikh By Technomads Developer', 102, '2021-05-06 15:00:47', 0),
(1384, 'Order Assigned to Driver.', 102, '2021-05-06 15:00:47', 1),
(1385, 'Order Assigned to Mohammad Fahad Shaikh By Technomads Developer', 100, '2021-05-06 15:01:02', 0),
(1386, 'Order Assigned to Driver.', 100, '2021-05-06 15:01:02', 1),
(1387, 'Order Assigned to Mohammad Fahad Shaikh By Technomads Developer', 99, '2021-05-06 15:01:03', 0),
(1388, 'Order Assigned to Driver.', 99, '2021-05-06 15:01:03', 1),
(1389, 'Order #1022 Picked Up By Mohammad Fahad Shaikh', 109, '2021-05-06 15:01:40', 0),
(1390, 'Order Picked Up By Driver.', 109, '2021-05-06 15:01:40', 1),
(1391, 'Order #1022 Delivered By Mohammad Fahad Shaikh', 109, '2021-05-06 15:01:48', 0),
(1392, 'Order Delivered By Driver.', 109, '2021-05-06 15:01:48', 1),
(1393, 'Order #1015 Picked Up By Mohammad Fahad Shaikh', 102, '2021-05-06 15:05:40', 0),
(1394, 'Order Picked Up By Driver.', 102, '2021-05-06 15:05:40', 1),
(1395, 'Order #1015 Delivered By Mohammad Fahad Shaikh', 102, '2021-05-06 15:08:03', 0),
(1396, 'Order Delivered By Driver.', 102, '2021-05-06 15:08:03', 1),
(1397, 'Order #1013 Picked Up By Mohammad Fahad Shaikh', 100, '2021-05-06 15:22:17', 0),
(1398, 'Order Picked Up By Driver.', 100, '2021-05-06 15:22:17', 1),
(1399, 'Order #1013 Delivered By Mohammad Fahad Shaikh', 100, '2021-05-06 15:22:26', 0),
(1400, 'Order Delivered By Driver.', 100, '2021-05-06 15:22:26', 1),
(1401, 'Order #1011 Picked Up By Mohammad Fahad Shaikh', 99, '2021-05-06 16:03:48', 0),
(1402, 'Order Picked Up By Driver.', 99, '2021-05-06 16:03:48', 1),
(1403, 'Order #1011 Undelivered By Mohammad Fahad Shaikh [Shop Closed]', 99, '2021-05-06 16:08:20', 0),
(1404, 'Order Undelivered Due to Some Reason.', 99, '2021-05-06 16:08:20', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_parcel_details`
--

CREATE TABLE `tbl_order_parcel_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `no_of_parcel` int(11) NOT NULL DEFAULT 1,
  `goods_type_id` int(11) NOT NULL DEFAULT 0,
  `goods_weight` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_weight` decimal(15,2) NOT NULL DEFAULT 0.00,
  `order_id` int(11) NOT NULL DEFAULT 0 COMMENT 'tbl_order id',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0-active,1-deactive, 2- deleted',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `tempo_charge` decimal(12,2) NOT NULL DEFAULT 0.00,
  `service_charge` decimal(12,2) NOT NULL DEFAULT 0.00,
  `delivery_charge` decimal(15,2) NOT NULL DEFAULT 0.00 COMMENT 'tempo_charge+service_charge',
  `other_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estimation_value` decimal(15,2) NOT NULL DEFAULT 0.00
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_order_parcel_details`
--

INSERT INTO `tbl_order_parcel_details` (`id`, `no_of_parcel`, `goods_type_id`, `goods_weight`, `total_weight`, `order_id`, `is_active`, `created_at`, `updated_at`, `tempo_charge`, `service_charge`, `delivery_charge`, `other_text`, `estimation_value`) VALUES
(216, 39, 18, '10.00', '390.00', 54, 0, '2021-03-15 13:31:30', '2021-03-15 13:31:30', '10.00', '5.40', '600.60', NULL, '55086.00'),
(215, 1, 27, '50.00', '50.00', 57, 2, '2021-03-15 13:05:08', '2021-03-15 13:05:08', '90.00', '30.00', '120.00', NULL, '28320.00'),
(214, 1, 27, '50.00', '50.00', 56, 2, '2021-03-15 12:56:07', '2021-03-15 12:56:07', '170.00', '30.00', '200.00', NULL, '12588.00'),
(207, 2, 18, '30.00', '60.00', 52, 0, '2021-03-13 14:15:55', '2021-03-13 14:15:55', '50.00', '30.00', '160.00', NULL, '0.00'),
(206, 2, 18, '30.00', '60.00', 52, 2, '2021-03-13 13:22:49', '2021-03-13 13:22:49', '50.00', '30.00', '160.00', NULL, '0.00'),
(205, 4, 18, '30.00', '120.00', 51, 0, '2021-03-13 13:01:35', '2021-03-13 13:01:35', '70.00', '30.00', '400.00', NULL, '49361.00'),
(204, 4, 18, '30.00', '120.00', 52, 2, '2021-03-13 11:36:37', '2021-03-13 11:36:37', '70.00', '30.00', '400.00', NULL, '49361.00'),
(203, 4, 18, '30.00', '120.00', 51, 2, '2021-03-13 11:30:35', '2021-03-13 11:30:35', '70.00', '30.00', '400.00', NULL, '49361.00'),
(200, 27, 27, '28.15', '760.00', 48, 0, '2021-03-12 15:59:37', '2021-03-12 15:59:37', '10.00', '8.52', '500.04', NULL, '340076.00'),
(201, 1, 16, '50.00', '50.00', 50, 0, '2021-03-12 16:01:22', '2021-03-12 16:01:22', '250.00', '50.00', '300.00', NULL, '20406.00'),
(202, 8, 28, '25.00', '200.00', 49, 0, '2021-03-12 16:50:58', '2021-03-12 16:50:58', '30.00', '12.50', '340.00', NULL, '102737.00'),
(213, 1, 24, '50.00', '50.00', 55, 2, '2021-03-15 12:02:26', '2021-03-15 12:02:26', '70.00', '30.00', '100.00', NULL, '1718.00'),
(212, 39, 18, '10.00', '390.00', 54, 2, '2021-03-15 11:35:25', '2021-03-15 11:35:25', '10.00', '5.40', '600.60', NULL, '55086.00'),
(211, 2, 16, '60.00', '120.00', 53, 2, '2021-03-13 17:06:13', '2021-03-13 17:06:13', '70.00', '30.00', '200.00', NULL, '45000.00'),
(210, 2, 16, '60.00', '120.00', 53, 2, '2021-03-13 16:46:18', '2021-03-13 16:46:18', '70.00', '30.00', '200.00', NULL, '45000.00'),
(209, 2, 16, '60.00', '120.00', 53, 2, '2021-03-13 16:33:01', '2021-03-13 16:33:01', '70.00', '30.00', '200.00', NULL, '45000.00'),
(208, 2, 16, '60.00', '120.00', 53, 2, '2021-03-13 16:04:59', '2021-03-13 16:04:59', '70.00', '30.00', '200.00', NULL, '45000.00'),
(199, 1, 16, '50.00', '50.00', 50, 2, '2021-03-12 13:35:16', '2021-03-12 13:35:16', '250.00', '50.00', '300.00', NULL, '20406.00'),
(198, 8, 28, '25.00', '200.00', 49, 2, '2021-03-12 13:34:44', '2021-03-12 13:34:44', '30.00', '12.50', '340.00', NULL, '102737.00'),
(197, 27, 27, '28.15', '760.00', 48, 2, '2021-03-12 13:34:00', '2021-03-12 13:34:00', '10.00', '8.52', '500.04', NULL, '340076.00'),
(196, 1, 16, '50.00', '50.00', 50, 2, '2021-03-12 13:21:37', '2021-03-12 13:21:37', '170.00', '30.00', '200.00', NULL, '20406.00'),
(195, 8, 28, '25.00', '200.00', 49, 2, '2021-03-12 12:48:22', '2021-03-12 12:48:22', '20.00', '10.00', '240.00', NULL, '102737.00'),
(191, 1, 19, '25.00', '25.00', 8, 0, '2021-03-09 15:27:19', '2021-03-09 15:27:19', '50.00', '30.00', '80.00', NULL, '0.00'),
(193, 8, 23, '25.00', '200.00', 49, 2, '2021-03-12 12:30:30', '2021-03-12 12:30:30', '20.00', '10.00', '240.00', NULL, '102737.00'),
(194, 27, 27, '28.15', '760.00', 48, 2, '2021-03-12 12:46:05', '2021-03-12 12:46:05', '9.82', '5.00', '400.14', NULL, '340076.00'),
(190, 1, 23, '40.00', '40.00', 47, 0, '2021-03-09 11:46:13', '2021-03-09 11:46:13', '70.00', '30.00', '100.00', NULL, '9055.00'),
(189, 1, 22, '50.00', '50.00', 46, 0, '2021-03-09 11:34:32', '2021-03-09 11:34:32', '90.00', '30.00', '120.00', NULL, '2879.00'),
(192, 27, 18, '28.15', '760.00', 48, 2, '2021-03-12 12:17:35', '2021-03-12 12:17:35', '90.00', '30.00', '3240.00', NULL, '340076.00'),
(31, 2, 10, '30.00', '60.00', 6, 2, '2021-02-15 13:15:38', '2021-02-15 13:15:38', '50.00', '30.00', '160.00', NULL, '0.00'),
(32, 1, 15, '50.00', '50.00', 6, 2, '2021-02-15 13:15:38', '2021-02-15 13:15:38', '70.00', '30.00', '100.00', NULL, '0.00'),
(33, 2, 10, '30.00', '60.00', 6, 2, '2021-02-15 13:28:45', '2021-02-15 13:28:45', '50.00', '30.00', '160.00', NULL, '0.00'),
(34, 1, 15, '50.00', '50.00', 6, 2, '2021-02-15 13:28:45', '2021-02-15 13:28:45', '70.00', '30.00', '100.00', NULL, '0.00'),
(35, 2, 10, '30.00', '60.00', 6, 2, '2021-02-15 13:28:59', '2021-02-15 13:28:59', '50.00', '30.00', '160.00', NULL, '0.00'),
(36, 1, 15, '50.00', '50.00', 6, 2, '2021-02-15 13:28:59', '2021-02-15 13:28:59', '70.00', '30.00', '100.00', NULL, '0.00'),
(37, 1, 8, '1.00', '1.00', 7, 2, '2021-02-16 12:48:19', '2021-02-16 12:48:19', '150.00', '50.00', '200.00', NULL, '0.00'),
(38, 1, 16, '1.00', '1.00', 7, 2, '2021-02-16 12:56:07', '2021-02-16 12:56:07', '150.00', '50.00', '200.00', NULL, '0.00'),
(39, 1, 19, '25.00', '25.00', 8, 2, '2021-02-19 14:10:17', '2021-02-19 14:10:17', '50.00', '30.00', '80.00', NULL, '0.00'),
(40, 1, 19, '25.00', '25.00', 8, 2, '2021-02-19 14:14:18', '2021-02-19 14:14:18', '50.00', '30.00', '80.00', NULL, '0.00'),
(41, 1, 19, '25.00', '25.00', 8, 2, '2021-02-19 14:50:45', '2021-02-19 14:50:45', '50.00', '30.00', '80.00', NULL, '0.00'),
(42, 1, 19, '25.00', '25.00', 8, 2, '2021-02-19 15:00:31', '2021-02-19 15:00:31', '50.00', '30.00', '80.00', NULL, '0.00'),
(43, 1, 19, '25.00', '25.00', 8, 2, '2021-02-19 15:01:26', '2021-02-19 15:01:26', '50.00', '30.00', '80.00', NULL, '0.00'),
(44, 1, 19, '25.00', '25.00', 8, 2, '2021-02-19 15:01:39', '2021-02-19 15:01:39', '50.00', '30.00', '80.00', NULL, '0.00'),
(45, 1, 19, '25.00', '25.00', 8, 2, '2021-02-19 15:02:33', '2021-02-19 15:02:33', '50.00', '30.00', '80.00', NULL, '0.00'),
(46, 1, 19, '25.00', '25.00', 8, 2, '2021-02-19 15:05:08', '2021-02-19 15:05:08', '50.00', '30.00', '80.00', NULL, '0.00'),
(47, 1, 19, '25.00', '25.00', 8, 2, '2021-02-19 15:39:00', '2021-02-19 15:39:00', '50.00', '30.00', '80.00', NULL, '0.00'),
(48, 1, 17, '50.00', '50.00', 8, 2, '2021-02-19 15:39:00', '2021-02-19 15:39:00', '50.00', '30.00', '80.00', NULL, '0.00'),
(49, 1, 19, '25.00', '25.00', 8, 2, '2021-02-19 15:43:16', '2021-02-19 15:43:16', '50.00', '30.00', '80.00', NULL, '0.00'),
(50, 1, 17, '50.00', '50.00', 9, 2, '2021-02-19 16:29:10', '2021-02-19 16:29:10', '50.00', '30.00', '80.00', NULL, '0.00'),
(58, 2, 18, '50.00', '100.00', 13, 2, '2021-02-22 14:11:11', '2021-02-22 14:11:11', '50.00', '30.00', '160.00', NULL, '0.00'),
(56, 2, 18, '50.00', '100.00', 13, 2, '2021-02-22 14:04:17', '2021-02-22 14:04:17', '50.00', '30.00', '160.00', NULL, '0.00'),
(57, 1, 17, '210.00', '210.00', 14, 2, '2021-02-22 14:10:30', '2021-02-22 14:10:30', '100.00', '50.00', '150.00', NULL, '0.00'),
(59, 2, 16, '160.00', '320.00', 15, 2, '2021-02-22 14:19:15', '2021-02-22 14:19:15', '80.00', '40.00', '240.00', NULL, '0.00'),
(60, 2, 18, '50.00', '100.00', 13, 2, '2021-02-22 15:12:40', '2021-02-22 15:12:40', '50.00', '30.00', '160.00', NULL, '0.00'),
(61, 2, 18, '50.00', '100.00', 13, 2, '2021-02-22 15:13:07', '2021-02-22 15:13:07', '50.00', '30.00', '160.00', NULL, '0.00'),
(62, 1, 17, '210.00', '210.00', 14, 2, '2021-02-22 15:21:03', '2021-02-22 15:21:03', '100.00', '50.00', '150.00', NULL, '0.00'),
(63, 2, 18, '50.00', '100.00', 13, 2, '2021-02-22 15:43:27', '2021-02-22 15:43:27', '50.00', '30.00', '160.00', NULL, '0.00'),
(64, 1, 17, '210.00', '210.00', 14, 2, '2021-02-22 15:44:06', '2021-02-22 15:44:06', '100.00', '50.00', '150.00', NULL, '0.00'),
(65, 2, 16, '160.00', '320.00', 15, 2, '2021-02-22 15:48:02', '2021-02-22 15:48:02', '80.00', '40.00', '240.00', NULL, '0.00'),
(66, 2, 16, '160.00', '320.00', 15, 2, '2021-02-22 17:17:50', '2021-02-22 17:17:50', '80.00', '40.00', '240.00', NULL, '0.00'),
(67, 2, 16, '160.00', '320.00', 15, 2, '2021-02-22 17:18:21', '2021-02-22 17:18:21', '80.00', '40.00', '240.00', NULL, '0.00'),
(68, 10, 18, '200.00', '2000.00', 16, 2, '2021-02-22 17:23:20', '2021-02-22 17:23:20', '40.00', '20.00', '600.00', NULL, '27140.00'),
(69, 10, 18, '200.00', '2000.00', 16, 2, '2021-02-22 17:26:11', '2021-02-22 17:26:11', '40.00', '20.00', '600.00', NULL, '27140.00'),
(70, 1, 17, '210.00', '210.00', 14, 2, '2021-02-22 17:26:49', '2021-02-22 17:26:49', '100.00', '50.00', '150.00', NULL, '40144.00'),
(71, 10, 18, '200.00', '2000.00', 16, 2, '2021-02-22 17:33:26', '2021-02-22 17:33:26', '40.00', '20.00', '600.00', NULL, '27140.00'),
(72, 10, 18, '200.00', '2000.00', 16, 2, '2021-02-22 17:35:29', '2021-02-22 17:35:29', '40.00', '20.00', '600.00', NULL, '27140.00'),
(73, 5, 18, '140.00', '700.00', 17, 2, '2021-02-22 17:51:59', '2021-02-22 17:51:59', '50.00', '30.00', '400.00', NULL, '16520.00'),
(74, 5, 18, '140.00', '700.00', 17, 2, '2021-02-22 17:55:46', '2021-02-22 17:55:46', '50.00', '30.00', '400.00', NULL, '16520.00'),
(75, 4, 8, '240.00', '960.00', 18, 2, '2021-02-23 12:00:31', '2021-02-23 12:00:31', '120.00', '30.00', '600.00', 'Over- Size-Ctn', '19824.00'),
(76, 4, 8, '60.00', '240.00', 18, 2, '2021-02-23 12:03:16', '2021-02-23 12:03:16', '120.00', '30.00', '600.00', 'Over- Size-Ctn', '19824.00'),
(77, 4, 8, '60.00', '240.00', 18, 2, '2021-02-23 12:04:27', '2021-02-23 12:04:27', '120.00', '30.00', '600.00', 'Over- Size-Ctn', '19824.00'),
(78, 9, 13, '80.00', '720.00', 19, 2, '2021-02-23 12:44:40', '2021-02-23 12:44:40', '40.00', '20.00', '540.00', NULL, '19790.00'),
(79, 4, 8, '60.00', '240.00', 18, 2, '2021-02-23 12:45:33', '2021-02-23 12:45:33', '120.00', '30.00', '600.00', 'Over- Size-Ctn', '19824.00'),
(80, 9, 21, '80.00', '720.00', 19, 2, '2021-02-23 13:00:36', '2021-02-23 13:00:36', '40.00', '20.00', '540.00', NULL, '19790.00'),
(81, 1, 18, '50.00', '50.00', 20, 2, '2021-02-23 13:19:26', '2021-02-23 13:19:26', '50.00', '30.00', '80.00', NULL, '5938.00'),
(82, 9, 21, '80.00', '720.00', 19, 2, '2021-02-23 13:20:06', '2021-02-23 13:20:06', '40.00', '20.00', '540.00', NULL, '19790.00'),
(83, 2, 18, '50.00', '100.00', 21, 2, '2021-02-23 13:33:08', '2021-02-23 13:33:08', '50.00', '30.00', '160.00', NULL, '9297.00'),
(84, 4, 8, '60.00', '240.00', 18, 2, '2021-02-23 13:34:59', '2021-02-23 13:34:59', '120.00', '30.00', '600.00', 'Over- Size-Ctn', '19824.00'),
(85, 9, 21, '80.00', '720.00', 19, 2, '2021-02-23 13:39:16', '2021-02-23 13:39:16', '40.00', '20.00', '540.00', NULL, '19790.00'),
(86, 1, 18, '50.00', '50.00', 20, 2, '2021-02-23 13:39:52', '2021-02-23 13:39:52', '50.00', '30.00', '80.00', NULL, '5938.00'),
(87, 14, 16, '510.00', '7140.00', 22, 2, '2021-02-23 14:00:03', '2021-02-23 14:00:03', '40.00', '15.00', '770.00', NULL, '44485.00'),
(88, 5, 23, '40.00', '200.00', 23, 2, '2021-02-23 14:44:33', '2021-02-23 14:44:33', '50.00', '30.00', '400.00', NULL, '0.00'),
(89, 9, 21, '80.00', '720.00', 19, 2, '2021-02-23 14:48:09', '2021-02-23 14:48:09', '40.00', '20.00', '540.00', NULL, '19790.00'),
(90, 9, 21, '80.00', '720.00', 19, 2, '2021-02-23 14:50:27', '2021-02-23 14:50:27', '40.00', '20.00', '540.00', NULL, '19790.00'),
(91, 1, 18, '50.00', '50.00', 20, 2, '2021-02-23 14:55:51', '2021-02-23 14:55:51', '50.00', '30.00', '80.00', NULL, '5938.00'),
(92, 14, 16, '510.00', '7140.00', 22, 2, '2021-02-23 15:01:08', '2021-02-23 15:01:08', '40.00', '15.00', '770.00', NULL, '44485.00'),
(93, 5, 23, '40.00', '200.00', 23, 2, '2021-02-23 15:23:17', '2021-02-23 15:23:17', '120.00', '30.00', '750.00', NULL, '24000.00'),
(94, 2, 18, '50.00', '100.00', 21, 2, '2021-02-23 15:28:38', '2021-02-23 15:28:38', '50.00', '30.00', '160.00', NULL, '9297.00'),
(95, 4, 8, '60.00', '240.00', 18, 2, '2021-02-23 16:19:08', '2021-02-23 16:19:08', '120.00', '30.00', '600.00', 'Over- Size-Ctn', '19824.00'),
(96, 2, 18, '50.00', '100.00', 21, 2, '2021-02-23 16:25:29', '2021-02-23 16:25:29', '50.00', '30.00', '160.00', NULL, '9297.00'),
(97, 5, 23, '40.00', '200.00', 23, 2, '2021-02-23 16:36:28', '2021-02-23 16:36:28', '120.00', '30.00', '750.00', NULL, '24000.00'),
(98, 5, 23, '40.00', '200.00', 23, 2, '2021-02-23 17:00:59', '2021-02-23 17:00:59', '120.00', '30.00', '750.00', NULL, '24000.00'),
(99, 1, 23, '50.00', '50.00', 24, 2, '2021-02-24 11:22:39', '2021-02-24 11:22:39', '70.00', '30.00', '100.00', NULL, '38270.00'),
(100, 1, 23, '50.00', '50.00', 24, 2, '2021-02-24 12:04:13', '2021-02-24 12:04:13', '70.00', '30.00', '100.00', NULL, '38270.00'),
(101, 1, 24, '50.00', '50.00', 24, 2, '2021-02-24 12:06:15', '2021-02-24 12:06:15', '70.00', '30.00', '100.00', NULL, '38270.00'),
(102, 2, 10, '30.00', '60.00', 6, 2, '2021-02-24 12:12:21', '2021-02-24 12:12:21', '50.00', '30.00', '160.00', NULL, '0.00'),
(103, 1, 15, '50.00', '50.00', 6, 2, '2021-02-24 12:12:21', '2021-02-24 12:12:21', '70.00', '30.00', '100.00', NULL, '0.00'),
(104, 1, 16, '1.00', '1.00', 7, 2, '2021-02-24 12:14:45', '2021-02-24 12:14:45', '150.00', '50.00', '200.00', NULL, '0.00'),
(105, 1, 16, '1.00', '1.00', 7, 2, '2021-02-24 12:15:42', '2021-02-24 12:15:42', '150.00', '50.00', '200.00', NULL, '0.00'),
(106, 2, 10, '30.00', '60.00', 6, 0, '2021-02-24 12:17:10', '2021-02-24 12:17:10', '50.00', '30.00', '160.00', NULL, '0.00'),
(107, 1, 15, '50.00', '50.00', 6, 0, '2021-02-24 12:17:10', '2021-02-24 12:17:10', '70.00', '30.00', '100.00', NULL, '0.00'),
(108, 1, 19, '25.00', '25.00', 8, 2, '2021-02-24 12:21:46', '2021-02-24 12:21:46', '50.00', '30.00', '80.00', NULL, '0.00'),
(109, 1, 17, '50.00', '50.00', 9, 0, '2021-02-24 12:24:13', '2021-02-24 12:24:13', '50.00', '30.00', '80.00', NULL, '0.00'),
(110, 2, 18, '50.00', '100.00', 13, 2, '2021-02-24 12:25:12', '2021-02-24 12:25:12', '50.00', '30.00', '160.00', NULL, '0.00'),
(111, 1, 17, '210.00', '210.00', 14, 0, '2021-02-24 12:25:59', '2021-02-24 12:25:59', '100.00', '50.00', '150.00', NULL, '40144.00'),
(112, 2, 16, '160.00', '320.00', 15, 2, '2021-02-24 12:26:36', '2021-02-24 12:26:36', '80.00', '40.00', '240.00', NULL, '0.00'),
(113, 10, 18, '20.00', '200.00', 16, 2, '2021-02-24 12:27:51', '2021-02-24 12:27:51', '40.00', '20.00', '600.00', NULL, '27140.00'),
(114, 5, 18, '28.00', '140.00', 17, 2, '2021-02-24 12:30:04', '2021-02-24 12:30:04', '50.00', '30.00', '400.00', NULL, '16520.00'),
(115, 4, 8, '60.00', '240.00', 18, 0, '2021-02-24 12:31:07', '2021-02-24 12:31:07', '120.00', '30.00', '600.00', 'Over- Size-Ctn', '19824.00'),
(116, 9, 21, '9.00', '81.00', 19, 2, '2021-02-24 12:33:41', '2021-02-24 12:33:41', '40.00', '20.00', '540.00', NULL, '19790.00'),
(117, 1, 18, '50.00', '50.00', 20, 0, '2021-02-24 12:34:24', '2021-02-24 12:34:24', '50.00', '30.00', '80.00', NULL, '5938.00'),
(118, 2, 18, '50.00', '100.00', 21, 2, '2021-02-24 12:38:34', '2021-02-24 12:38:34', '50.00', '30.00', '160.00', NULL, '9297.00'),
(119, 14, 16, '36.00', '504.00', 22, 2, '2021-02-24 12:40:02', '2021-02-24 12:40:02', '40.00', '15.00', '770.00', NULL, '44485.00'),
(120, 5, 23, '40.00', '200.00', 23, 0, '2021-02-24 12:41:11', '2021-02-24 12:41:11', '120.00', '30.00', '750.00', NULL, '24000.00'),
(121, 1, 24, '50.00', '50.00', 24, 2, '2021-02-24 12:42:03', '2021-02-24 12:42:03', '70.00', '30.00', '100.00', NULL, '38270.00'),
(122, 1, 16, '20.00', '20.00', 7, 0, '2021-02-25 13:05:06', '2021-02-25 13:05:06', '150.00', '50.00', '200.00', NULL, '0.00'),
(123, 1, 18, '50.00', '50.00', 25, 2, '2021-03-01 11:45:25', '2021-03-01 11:45:25', '70.00', '30.00', '100.00', NULL, '38232.00'),
(124, 4, 18, '60.00', '240.00', 26, 2, '2021-03-01 12:08:20', '2021-03-01 12:08:20', '50.00', '30.00', '320.00', NULL, '19824.00'),
(125, 4, 18, '60.00', '240.00', 26, 2, '2021-03-01 12:10:59', '2021-03-01 12:10:59', '50.00', '30.00', '320.00', NULL, '19824.00'),
(126, 4, 18, '60.00', '240.00', 26, 2, '2021-03-01 12:11:03', '2021-03-01 12:11:03', '50.00', '30.00', '320.00', NULL, '19824.00'),
(127, 1, 22, '50.00', '50.00', 27, 0, '2021-03-01 12:41:08', '2021-03-01 12:41:08', '50.00', '30.00', '80.00', NULL, '36809.00'),
(128, 4, 22, '30.00', '120.00', 28, 0, '2021-03-01 12:52:35', '2021-03-01 12:52:35', '70.00', '30.00', '400.00', NULL, '48121.00'),
(129, 4, 18, '60.00', '240.00', 26, 2, '2021-03-01 13:10:58', '2021-03-01 13:10:58', '50.00', '30.00', '320.00', NULL, '19824.00'),
(130, 4, 18, '60.00', '240.00', 26, 0, '2021-03-01 14:02:47', '2021-03-01 14:02:47', '50.00', '30.00', '320.00', NULL, '19824.00'),
(131, 1, 18, '50.00', '50.00', 25, 2, '2021-03-01 15:02:28', '2021-03-01 15:02:28', '70.00', '30.00', '100.00', NULL, '38232.00'),
(132, 9, 1, '16.67', '150.00', 29, 2, '2021-03-01 15:12:34', '2021-03-01 15:12:34', '40.00', '20.00', '540.00', NULL, '39082.00'),
(133, 9, 18, '16.67', '150.00', 29, 2, '2021-03-01 15:14:47', '2021-03-01 15:14:47', '40.00', '20.00', '540.00', NULL, '39082.00'),
(134, 9, 18, '16.67', '150.00', 29, 2, '2021-03-01 16:22:45', '2021-03-01 16:22:45', '40.00', '20.00', '540.00', NULL, '39082.00'),
(135, 9, 18, '16.67', '150.00', 29, 0, '2021-03-02 10:41:00', '2021-03-02 10:41:00', '40.00', '20.00', '540.00', NULL, '39082.00'),
(136, 2, 16, '40.00', '80.00', 30, 2, '2021-03-02 11:39:16', '2021-03-02 11:39:16', '70.00', '30.00', '200.00', NULL, '124501.00'),
(137, 2, 18, '25.00', '50.00', 31, 2, '2021-03-02 12:02:58', '2021-03-02 12:02:58', '50.00', '30.00', '160.00', NULL, '8543.00'),
(138, 7, 18, '30.00', '210.00', 32, 2, '2021-03-02 12:28:38', '2021-03-02 12:28:38', '40.20', '17.00', '400.40', NULL, '20788.00'),
(139, 2, 24, '60.00', '120.00', 33, 2, '2021-03-02 12:54:44', '2021-03-02 12:54:44', '70.00', '30.00', '200.00', NULL, '25960.00'),
(140, 4, 17, '53.75', '215.00', 34, 2, '2021-03-02 13:09:14', '2021-03-02 13:09:14', '50.00', '30.00', '320.00', NULL, '60180.00'),
(141, 2, 18, '50.00', '100.00', 13, 0, '2021-03-02 14:02:44', '2021-03-02 14:02:44', '30.00', '10.00', '80.00', NULL, '0.00'),
(142, 2, 16, '160.00', '320.00', 15, 0, '2021-03-02 14:04:25', '2021-03-02 14:04:25', '40.00', '20.00', '120.00', NULL, '0.00'),
(143, 10, 18, '20.00', '200.00', 16, 0, '2021-03-02 14:06:00', '2021-03-02 14:06:00', '20.00', '10.00', '300.00', NULL, '27140.00'),
(144, 5, 18, '28.00', '140.00', 17, 0, '2021-03-02 14:07:04', '2021-03-02 14:07:04', '25.00', '15.00', '200.00', NULL, '16520.00'),
(145, 2, 16, '40.00', '80.00', 30, 0, '2021-03-02 14:08:04', '2021-03-02 14:08:04', '70.00', '30.00', '200.00', NULL, '124501.00'),
(146, 9, 21, '9.00', '81.00', 19, 0, '2021-03-02 14:09:14', '2021-03-02 14:09:14', '30.00', '10.00', '360.00', NULL, '19790.00'),
(147, 2, 18, '50.00', '100.00', 21, 0, '2021-03-02 14:10:41', '2021-03-02 14:10:41', '40.00', '10.00', '100.00', NULL, '9297.00'),
(148, 2, 24, '60.00', '120.00', 33, 0, '2021-03-02 14:12:57', '2021-03-02 14:12:57', '70.00', '30.00', '200.00', NULL, '25960.00'),
(149, 14, 16, '36.00', '504.00', 22, 0, '2021-03-02 14:16:39', '2021-03-02 14:16:39', '35.00', '10.00', '630.00', NULL, '44485.00'),
(150, 1, 24, '50.00', '50.00', 24, 0, '2021-03-02 14:17:32', '2021-03-02 14:17:32', '50.00', '20.00', '70.00', NULL, '38270.00'),
(151, 1, 18, '50.00', '50.00', 25, 0, '2021-03-02 14:20:27', '2021-03-02 14:20:27', '90.00', '30.00', '120.00', NULL, '38232.00'),
(152, 2, 18, '25.00', '50.00', 31, 0, '2021-03-02 14:36:31', '2021-03-02 14:36:31', '50.00', '30.00', '160.00', NULL, '8543.00'),
(153, 7, 18, '30.00', '210.00', 32, 0, '2021-03-02 15:37:08', '2021-03-02 15:37:08', '40.20', '17.00', '400.40', NULL, '20788.00'),
(154, 4, 17, '53.75', '215.00', 34, 0, '2021-03-02 16:10:55', '2021-03-02 16:10:55', '50.00', '30.00', '320.00', NULL, '60180.00'),
(155, 1, 18, '80.00', '80.00', 35, 2, '2021-03-04 15:17:39', '2021-03-04 15:17:39', '90.00', '30.00', '120.00', NULL, '26233.00'),
(156, 1, 18, '80.00', '80.00', 36, 2, '2021-03-04 15:24:28', '2021-03-04 15:24:28', '90.00', '30.00', '120.00', NULL, '29301.00'),
(157, 2, 18, '75.00', '150.00', 37, 2, '2021-03-04 15:36:37', '2021-03-04 15:36:37', '70.00', '30.00', '200.00', NULL, '35658.00'),
(158, 2, 18, '25.00', '50.00', 38, 2, '2021-03-04 15:44:57', '2021-03-04 15:44:57', '120.00', '30.00', '300.00', NULL, '7557.00'),
(159, 1, 18, '50.00', '50.00', 39, 2, '2021-03-04 15:50:27', '2021-03-04 15:50:27', '120.00', '30.00', '150.00', NULL, '8590.00'),
(160, 1, 18, '50.00', '50.00', 39, 0, '2021-03-05 12:37:33', '2021-03-05 12:37:33', '120.00', '30.00', '150.00', NULL, '8590.00'),
(161, 2, 18, '25.00', '50.00', 38, 0, '2021-03-05 12:40:03', '2021-03-05 12:40:03', '120.00', '30.00', '300.00', NULL, '7557.00'),
(162, 2, 18, '75.00', '150.00', 37, 0, '2021-03-05 12:41:04', '2021-03-05 12:41:04', '70.00', '30.00', '200.00', NULL, '35658.00'),
(163, 1, 18, '80.00', '80.00', 36, 0, '2021-03-05 12:41:51', '2021-03-05 12:41:51', '90.00', '30.00', '120.00', NULL, '29301.00'),
(164, 1, 18, '80.00', '80.00', 35, 0, '2021-03-05 12:42:51', '2021-03-05 12:42:51', '90.00', '30.00', '120.00', NULL, '26233.00'),
(165, 1, 23, '60.00', '60.00', 40, 2, '2021-03-05 13:07:12', '2021-03-05 13:07:12', '120.00', '30.00', '150.00', NULL, '0.00'),
(166, 1, 23, '60.00', '60.00', 40, 2, '2021-03-05 13:09:21', '2021-03-05 13:09:21', '120.00', '30.00', '150.00', NULL, '0.00'),
(167, 1, 23, '60.00', '60.00', 40, 2, '2021-03-05 13:18:26', '2021-03-05 13:18:26', '120.00', '30.00', '150.00', NULL, '0.00'),
(168, 1, 23, '60.00', '60.00', 40, 0, '2021-03-05 15:14:57', '2021-03-05 15:14:57', '120.00', '30.00', '150.00', NULL, '0.00'),
(169, 1, 24, '50.00', '50.00', 41, 2, '2021-03-08 12:18:31', '2021-03-08 12:18:31', '120.00', '30.00', '150.00', NULL, '17588.00'),
(170, 1, 24, '50.00', '50.00', 41, 2, '2021-03-08 12:19:27', '2021-03-08 12:19:27', '120.00', '30.00', '150.00', NULL, '17588.00'),
(171, 2, 22, '25.00', '50.00', 42, 2, '2021-03-08 13:00:46', '2021-03-08 13:00:46', '50.00', '30.00', '160.00', NULL, '33512.00'),
(172, 5, 18, '22.00', '110.00', 43, 2, '2021-03-08 13:18:51', '2021-03-08 13:18:51', '50.00', '30.00', '400.00', NULL, '33512.00'),
(173, 1, 24, '50.00', '50.00', 41, 2, '2021-03-08 14:34:56', '2021-03-08 14:34:56', '90.00', '30.00', '120.00', NULL, '17588.00'),
(174, 1, 22, '50.00', '50.00', 44, 2, '2021-03-08 14:41:12', '2021-03-08 14:41:12', '120.00', '30.00', '150.00', NULL, '0.00'),
(175, 3, 23, '120.00', '360.00', 45, 2, '2021-03-08 14:52:54', '2021-03-08 14:52:54', '90.00', '30.00', '360.00', NULL, '173364.00'),
(176, 2, 22, '25.00', '50.00', 42, 2, '2021-03-08 15:29:56', '2021-03-08 15:29:56', '30.00', '20.00', '100.00', NULL, '33512.00'),
(177, 1, 25, '50.00', '50.00', 44, 2, '2021-03-08 15:32:22', '2021-03-08 15:32:22', '120.00', '30.00', '150.00', NULL, '0.00'),
(178, 5, 18, '22.00', '110.00', 43, 2, '2021-03-08 15:36:09', '2021-03-08 15:36:09', '30.00', '20.00', '250.00', NULL, '35778.00'),
(179, 3, 23, '120.00', '360.00', 45, 2, '2021-03-08 15:38:36', '2021-03-08 15:38:36', '40.00', '30.00', '210.00', NULL, '173364.00'),
(180, 1, 23, '50.00', '50.00', 46, 2, '2021-03-08 15:49:17', '2021-03-08 15:49:17', '70.00', '30.00', '100.00', NULL, '2879.00'),
(181, 1, 24, '50.00', '50.00', 41, 0, '2021-03-08 16:04:25', '2021-03-08 16:04:25', '90.00', '30.00', '120.00', NULL, '17588.00'),
(182, 1, 25, '50.00', '50.00', 44, 0, '2021-03-08 16:06:38', '2021-03-08 16:06:38', '120.00', '30.00', '150.00', NULL, '0.00'),
(183, 5, 26, '22.00', '110.00', 43, 2, '2021-03-08 16:11:59', '2021-03-08 16:11:59', '30.00', '20.00', '250.00', NULL, '35778.00'),
(184, 5, 18, '22.00', '110.00', 43, 0, '2021-03-08 16:14:52', '2021-03-08 16:14:52', '30.00', '20.00', '250.00', NULL, '35778.00'),
(185, 3, 23, '120.00', '360.00', 45, 0, '2021-03-08 16:21:10', '2021-03-08 16:21:10', '40.00', '30.00', '210.00', NULL, '173364.00'),
(186, 1, 22, '50.00', '50.00', 46, 2, '2021-03-08 16:23:51', '2021-03-08 16:23:51', '90.00', '30.00', '120.00', NULL, '2879.00'),
(187, 2, 18, '25.00', '50.00', 42, 0, '2021-03-08 16:40:45', '2021-03-08 16:40:45', '30.00', '20.00', '100.00', NULL, '33512.00'),
(188, 1, 23, '40.00', '40.00', 47, 2, '2021-03-08 17:09:29', '2021-03-08 17:09:29', '70.00', '30.00', '100.00', NULL, '9055.00'),
(217, 1, 27, '50.00', '50.00', 56, 2, '2021-03-15 13:59:49', '2021-03-15 13:59:49', '170.00', '30.00', '200.00', NULL, '12588.00'),
(218, 2, 16, '60.00', '120.00', 53, 0, '2021-03-15 15:07:51', '2021-03-15 15:07:51', '70.00', '30.00', '200.00', NULL, '45000.00'),
(219, 1, 27, '50.00', '50.00', 56, 0, '2021-03-15 15:09:18', '2021-03-15 15:09:18', '170.00', '30.00', '200.00', NULL, '12588.00'),
(220, 4, 29, '30.00', '120.00', 58, 2, '2021-03-15 15:17:12', '2021-03-15 15:17:12', '30.00', '20.00', '200.00', NULL, '17550.00'),
(221, 1, 27, '50.00', '50.00', 57, 0, '2021-03-15 16:10:30', '2021-03-15 16:10:30', '90.00', '30.00', '120.00', NULL, '28320.00'),
(223, 6, 16, '0.09', '0.56', 60, 2, '2021-03-15 17:49:23', '2021-03-15 17:49:23', '30.00', '3.40', '200.40', NULL, '0.00'),
(224, 4, 29, '30.00', '120.00', 58, 0, '2021-03-15 17:58:49', '2021-03-15 17:58:49', '30.00', '20.00', '200.00', NULL, '17550.00'),
(225, 1, 24, '50.00', '50.00', 55, 0, '2021-03-15 18:12:59', '2021-03-15 18:12:59', '70.00', '30.00', '100.00', NULL, '1718.00'),
(226, 6, 16, '9.33', '56.00', 60, 0, '2021-03-15 18:15:53', '2021-03-15 18:15:53', '30.00', '3.40', '200.40', NULL, '0.00'),
(227, 9, 25, '60.00', '540.00', 61, 2, '2021-03-17 20:22:12', '2021-03-17 20:22:12', '60.00', '30.00', '810.00', NULL, '48100.00'),
(228, 9, 25, '60.00', '540.00', 61, 2, '2021-03-17 20:23:23', '2021-03-17 20:23:23', '60.00', '30.00', '810.00', NULL, '48100.00'),
(229, 1, 16, '60.00', '60.00', 62, 2, '2021-03-17 20:31:18', '2021-03-17 20:31:18', '120.00', '30.00', '150.00', NULL, '0.00'),
(230, 1, 22, '60.00', '60.00', 62, 0, '2021-03-19 10:33:36', '2021-03-19 10:33:36', '120.00', '30.00', '150.00', NULL, '0.00'),
(231, 9, 25, '60.00', '540.00', 61, 0, '2021-03-19 10:35:49', '2021-03-19 10:35:49', '60.00', '30.00', '810.00', NULL, '48100.00'),
(232, 50, 18, '8.00', '400.00', 63, 2, '2021-03-19 12:41:39', '2021-03-19 12:41:39', '10.00', '0.00', '500.00', NULL, '2760.00'),
(233, 50, 18, '8.00', '400.00', 63, 2, '2021-03-19 12:44:11', '2021-03-19 12:44:11', '10.00', '0.00', '500.00', NULL, '0.00'),
(234, 50, 29, '8.00', '400.00', 63, 2, '2021-03-19 12:54:45', '2021-03-19 12:54:45', '10.00', '0.00', '500.00', NULL, '0.00'),
(235, 50, 29, '8.00', '400.00', 63, 2, '2021-03-19 12:58:16', '2021-03-19 12:58:16', '10.00', '0.00', '500.00', NULL, '0.00'),
(236, 50, 29, '8.00', '400.00', 63, 0, '2021-03-19 16:31:59', '2021-03-19 16:31:59', '10.00', '0.00', '500.00', NULL, '0.00'),
(237, 3, 32, '16.67', '50.00', 64, 2, '2021-03-22 11:43:58', '2021-03-22 11:43:58', '30.00', '20.00', '150.00', NULL, '0.00'),
(238, 3, 32, '16.67', '50.00', 64, 0, '2021-03-22 14:25:49', '2021-03-22 14:25:49', '30.00', '20.00', '150.00', NULL, '0.00'),
(239, 1, 33, '85.00', '85.00', 65, 2, '2021-03-23 11:37:16', '2021-03-23 11:37:16', '70.00', '30.00', '100.00', NULL, '14879.00'),
(240, 2, 18, '25.00', '50.00', 66, 2, '2021-03-23 11:41:34', '2021-03-23 11:41:34', '90.00', '30.00', '240.00', NULL, '8997.00'),
(241, 1, 33, '85.00', '85.00', 65, 2, '2021-03-23 11:42:19', '2021-03-23 11:42:19', '70.00', '30.00', '100.00', NULL, '14879.00'),
(242, 1, 33, '85.00', '85.00', 65, 2, '2021-03-23 11:42:27', '2021-03-23 11:42:27', '70.00', '30.00', '100.00', NULL, '14879.00'),
(243, 6, 18, '20.83', '125.00', 67, 2, '2021-03-23 12:08:53', '2021-03-23 12:08:53', '50.00', '20.00', '420.00', NULL, '0.00'),
(244, 2, 18, '25.00', '50.00', 66, 0, '2021-03-23 12:16:38', '2021-03-23 12:16:38', '90.00', '30.00', '240.00', NULL, '8997.00'),
(245, 1, 33, '85.00', '85.00', 65, 0, '2021-03-23 13:16:26', '2021-03-23 13:16:26', '70.00', '30.00', '100.00', NULL, '14879.00'),
(246, 6, 18, '20.83', '125.00', 67, 0, '2021-03-23 14:31:15', '2021-03-23 14:31:15', '50.00', '20.00', '420.00', NULL, '24662.00'),
(247, 1, 8, '30.00', '30.00', 68, 2, '2021-03-23 16:31:32', '2021-03-23 16:31:32', '120.00', '30.00', '150.00', 'Textile - Ghoda', '5000.00'),
(248, 1, 8, '30.00', '30.00', 68, 0, '2021-03-23 17:25:07', '2021-03-23 17:25:07', '120.00', '30.00', '150.00', 'Textile - Ghoda', '5000.00'),
(249, 1, 18, '50.00', '50.00', 69, 2, '2021-03-25 16:20:22', '2021-03-25 16:20:22', '90.00', '30.00', '120.00', NULL, '38232.00'),
(250, 1, 18, '50.00', '50.00', 69, 2, '2021-03-25 16:21:32', '2021-03-25 16:21:32', '90.00', '30.00', '120.00', NULL, '38232.00'),
(251, 1, 18, '50.00', '50.00', 70, 2, '2021-03-25 16:25:56', '2021-03-25 16:25:56', '90.00', '30.00', '120.00', NULL, '38232.00'),
(252, 2, 29, '50.00', '100.00', 71, 2, '2021-03-25 16:38:40', '2021-03-25 16:38:40', '90.00', '30.00', '240.00', NULL, '10000.00'),
(253, 2, 29, '50.00', '100.00', 71, 2, '2021-03-25 16:41:31', '2021-03-25 16:41:31', '90.00', '30.00', '240.00', NULL, '10000.00'),
(254, 1, 18, '50.00', '50.00', 70, 2, '2021-03-25 17:11:47', '2021-03-25 17:11:47', '90.00', '30.00', '120.00', NULL, '38232.00'),
(255, 1, 18, '50.00', '50.00', 70, 0, '2021-03-25 17:13:22', '2021-03-25 17:13:22', '120.00', '30.00', '150.00', NULL, '38232.00'),
(256, 1, 18, '50.00', '50.00', 69, 0, '2021-03-25 17:13:50', '2021-03-25 17:13:50', '120.00', '30.00', '150.00', NULL, '38232.00'),
(257, 2, 29, '50.00', '100.00', 71, 0, '2021-03-25 17:14:24', '2021-03-25 17:14:24', '120.00', '30.00', '300.00', NULL, '10000.00'),
(259, 2, 18, '25.00', '50.00', 73, 2, '2021-03-26 23:26:49', '2021-03-26 23:26:49', '90.00', '30.00', '240.00', NULL, '11157.00'),
(260, 2, 18, '25.00', '50.00', 73, 0, '2021-03-26 23:28:12', '2021-03-26 23:28:12', '90.00', '30.00', '240.00', NULL, '11157.00'),
(261, 1, 18, '50.00', '50.00', 74, 2, '2021-03-27 19:31:45', '2021-03-27 19:31:45', '90.00', '30.00', '120.00', NULL, '31978.00'),
(262, 1, 18, '50.00', '50.00', 74, 2, '2021-03-27 19:32:24', '2021-03-27 19:32:24', '120.00', '30.00', '150.00', NULL, '31978.00'),
(263, 1, 18, '50.00', '50.00', 74, 2, '2021-03-27 19:36:42', '2021-03-27 19:36:42', '120.00', '30.00', '150.00', NULL, '31978.00'),
(264, 2, 19, '25.00', '50.00', 75, 2, '2021-03-27 19:45:52', '2021-03-27 19:45:52', '120.00', '30.00', '300.00', NULL, '150000.00'),
(265, 1, 18, '50.00', '50.00', 74, 2, '2021-03-27 19:56:04', '2021-03-27 19:56:04', '120.00', '30.00', '150.00', NULL, '31978.00'),
(266, 2, 19, '25.00', '50.00', 75, 2, '2021-03-27 20:05:21', '2021-03-27 20:05:21', '120.00', '30.00', '300.00', NULL, '150000.00'),
(267, 1, 18, '50.00', '50.00', 74, 2, '2021-03-27 20:05:54', '2021-03-27 20:05:54', '120.00', '30.00', '150.00', NULL, '31978.00'),
(268, 2, 19, '25.00', '50.00', 75, 2, '2021-03-27 20:06:33', '2021-03-27 20:06:33', '120.00', '30.00', '300.00', NULL, '150000.00'),
(269, 2, 19, '25.00', '50.00', 75, 2, '2021-03-27 20:13:46', '2021-03-27 20:13:46', '30.00', '20.00', '100.00', NULL, '150000.00'),
(270, 2, 19, '25.00', '50.00', 75, 0, '2021-03-27 20:46:55', '2021-03-27 20:46:55', '30.00', '20.00', '100.00', NULL, '150000.00'),
(271, 1, 18, '50.00', '50.00', 74, 2, '2021-03-27 20:50:13', '2021-03-27 20:50:13', '120.00', '30.00', '150.00', NULL, '31978.00'),
(272, 1, 18, '50.00', '50.00', 74, 0, '2021-03-27 21:32:48', '2021-03-27 21:32:48', '120.00', '30.00', '150.00', NULL, '31978.00'),
(273, 1, 24, '50.00', '50.00', 76, 0, '2021-03-30 16:33:27', '2021-03-30 16:33:27', '120.00', '30.00', '150.00', NULL, '23751.00'),
(274, 1, 8, '30.00', '30.00', 77, 2, '2021-03-30 22:26:33', '2021-03-30 22:26:33', '70.00', '30.00', '100.00', 'Textile - Ghoda', '5000.00'),
(275, 1, 8, '30.00', '30.00', 78, 2, '2021-03-30 22:31:08', '2021-03-30 22:31:08', '70.00', '30.00', '100.00', 'Textile - Ghoda', '5000.00'),
(276, 1, 8, '30.00', '30.00', 77, 2, '2021-03-30 22:34:36', '2021-03-30 22:34:36', '70.00', '30.00', '100.00', 'Textile - Ghoda', '5000.00'),
(277, 1, 8, '30.00', '30.00', 78, 0, '2021-03-30 22:35:42', '2021-03-30 22:35:42', '70.00', '30.00', '100.00', 'Textile - Ghoda', '5000.00'),
(278, 1, 8, '30.00', '30.00', 77, 0, '2021-03-30 22:36:13', '2021-03-30 22:36:13', '70.00', '30.00', '100.00', 'Textile - Ghoda', '5000.00'),
(279, 66, 29, '8.00', '528.00', 79, 2, '2021-03-31 17:29:18', '2021-03-31 17:29:18', '7.58', '0.00', '500.28', NULL, '30360.00'),
(280, 66, 29, '8.00', '528.00', 79, 0, '2021-03-31 17:46:58', '2021-03-31 17:46:58', '7.58', '0.00', '500.28', NULL, '30360.00'),
(282, 1, 18, '50.00', '50.00', 81, 2, '2021-03-31 21:10:01', '2021-03-31 21:10:01', '70.00', '30.00', '100.00', NULL, '4118.00'),
(283, 1, 18, '50.00', '50.00', 81, 2, '2021-03-31 21:20:30', '2021-03-31 21:20:30', '120.00', '30.00', '150.00', NULL, '4118.00'),
(284, 3, 32, '16.67', '50.00', 82, 2, '2021-03-31 21:38:18', '2021-03-31 21:38:18', '40.00', '26.70', '200.10', NULL, '0.00'),
(285, 3, 32, '16.67', '50.00', 82, 2, '2021-03-31 23:15:39', '2021-03-31 23:15:39', '40.00', '26.70', '200.10', NULL, '0.00'),
(286, 3, 32, '16.67', '50.00', 82, 2, '2021-03-31 23:18:26', '2021-03-31 23:18:26', '40.00', '26.70', '200.10', NULL, '0.00'),
(287, 3, 32, '16.67', '50.00', 82, 2, '2021-03-31 23:54:47', '2021-03-31 23:54:47', '40.00', '26.70', '200.10', NULL, '0.00'),
(288, 3, 32, '16.67', '50.00', 82, 0, '2021-04-01 00:38:57', '2021-04-01 00:38:57', '40.00', '26.70', '200.10', NULL, '0.00'),
(289, 25, 16, '4.00', '100.00', 83, 2, '2021-04-02 19:04:24', '2021-04-02 19:04:24', '8.00', '0.00', '200.00', NULL, '1000.00'),
(290, 8, 32, '6.25', '50.00', 83, 0, '2021-04-02 19:11:16', '2021-04-02 19:11:16', '8.00', '0.00', '64.00', NULL, '800.00'),
(291, 17, 8, '2.94', '50.00', 83, 0, '2021-04-02 19:11:16', '2021-04-02 19:11:16', '8.00', '0.00', '136.00', 'Empty Carton', '200.00'),
(293, 4, 18, '50.00', '200.00', 85, 0, '2021-04-02 22:38:14', '2021-04-02 22:38:14', '30.00', '20.00', '200.00', NULL, '24648.00'),
(294, 4, 32, '25.00', '100.00', 86, 2, '2021-04-02 23:19:02', '2021-04-02 23:19:02', '50.00', '20.00', '280.00', NULL, '0.00'),
(295, 6, 32, '30.00', '180.00', 86, 2, '2021-04-05 16:13:50', '2021-04-05 16:13:50', '70.00', '30.00', '600.00', NULL, '0.00'),
(296, 8, 32, '30.00', '240.00', 86, 2, '2021-04-05 16:56:02', '2021-04-05 16:56:02', '70.00', '30.00', '800.00', NULL, '0.00'),
(297, 8, 32, '30.00', '240.00', 86, 2, '2021-04-05 17:01:11', '2021-04-05 17:01:11', '70.00', '30.00', '800.00', NULL, '0.00'),
(298, 8, 32, '30.00', '240.00', 86, 2, '2021-04-05 17:29:30', '2021-04-05 17:29:30', '60.00', '30.00', '720.00', NULL, '0.00'),
(299, 36, 34, '1.00', '36.00', 86, 2, '2021-04-05 17:29:30', '2021-04-05 17:29:30', '7.80', '0.00', '280.80', NULL, '0.00'),
(300, 8, 32, '30.00', '240.00', 86, 0, '2021-04-05 17:37:53', '2021-04-05 17:37:53', '60.00', '30.00', '720.00', NULL, '0.00'),
(301, 36, 34, '1.00', '36.00', 86, 0, '2021-04-05 17:37:53', '2021-04-05 17:37:53', '7.80', '0.00', '280.80', NULL, '0.00'),
(302, 1, 18, '50.00', '50.00', 87, 2, '2021-04-05 20:24:38', '2021-04-05 20:24:38', '120.00', '30.00', '150.00', NULL, '7108.00'),
(303, 1, 18, '50.00', '50.00', 87, 0, '2021-04-05 20:26:12', '2021-04-05 20:26:12', '120.00', '30.00', '150.00', NULL, '7108.00'),
(304, 1, 18, '50.00', '50.00', 88, 0, '2021-04-05 18:05:55', '2021-04-05 18:05:55', '120.00', '30.00', '150.00', NULL, '7108.00'),
(305, 11, 8, '12.25', '564.23', 89, 2, '2021-04-06 12:25:32', '2021-04-06 12:25:32', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(306, 25, 2, '25.60', '56.00', 89, 2, '2021-04-06 12:25:32', '2021-04-06 12:25:32', '0.00', '0.00', '0.00', NULL, '0.00'),
(307, 11, 8, '12.25', '564.23', 89, 2, '2021-04-06 12:27:39', '2021-04-06 12:27:39', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(308, 25, 2, '25.60', '56.00', 89, 2, '2021-04-06 12:27:39', '2021-04-06 12:27:39', '0.00', '0.00', '0.00', NULL, '0.00'),
(309, 11, 8, '12.25', '564.23', 89, 2, '2021-04-06 12:29:20', '2021-04-06 12:29:20', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(310, 25, 2, '25.60', '56.00', 89, 2, '2021-04-06 12:29:20', '2021-04-06 12:29:20', '0.00', '0.00', '0.00', NULL, '0.00'),
(311, 11, 8, '12.25', '564.23', 90, 2, '2021-04-06 12:37:19', '2021-04-06 12:37:19', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(312, 25, 2, '25.60', '56.00', 90, 2, '2021-04-06 12:37:19', '2021-04-06 12:37:19', '0.00', '0.00', '0.00', NULL, '0.00'),
(313, 1, 18, '50.00', '50.00', 81, 2, '2021-04-06 12:45:45', '2021-04-06 12:45:45', '120.00', '30.00', '150.00', NULL, '4118.00'),
(314, 1, 18, '50.00', '50.00', 81, 0, '2021-04-06 12:45:54', '2021-04-06 12:45:54', '120.00', '30.00', '150.00', NULL, '4118.00'),
(315, 11, 8, '12.25', '564.23', 90, 2, '2021-04-06 15:24:23', '2021-04-06 15:24:23', '52.00', '2.00', '594.00', 'EmailBox', '0.00'),
(316, 25, 2, '25.60', '640.00', 90, 2, '2021-04-06 15:24:23', '2021-04-06 15:24:23', '26.00', '3.00', '725.00', NULL, '0.00'),
(317, 11, 8, '12.25', '564.23', 90, 0, '2021-04-06 15:27:03', '2021-04-06 15:27:03', '23.67', '2.00', '282.37', 'EmailBox', '0.00'),
(318, 25, 2, '25.60', '640.00', 90, 0, '2021-04-06 15:27:03', '2021-04-06 15:27:03', '26.00', '3.00', '725.00', NULL, '0.00'),
(319, 11, 8, '12.25', '564.23', 89, 0, '2021-04-06 16:47:57', '2021-04-06 16:47:57', '56.00', '0.00', '616.00', 'EmailBox', '0.00'),
(320, 25, 2, '25.60', '56.00', 89, 0, '2021-04-06 16:47:57', '2021-04-06 16:47:57', '0.00', '0.00', '0.00', NULL, '0.00'),
(321, 11, 8, '12.25', '564.23', 91, 2, '2021-04-06 18:04:36', '2021-04-06 18:04:36', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(322, 25, 2, '25.60', '56.00', 91, 2, '2021-04-06 18:04:36', '2021-04-06 18:04:36', '0.00', '0.00', '0.00', NULL, '0.00'),
(323, 11, 8, '12.25', '564.23', 91, 2, '2021-04-06 18:05:04', '2021-04-06 18:05:04', '56.00', '23.00', '869.00', 'EmailBox', '0.00'),
(324, 25, 2, '25.60', '640.00', 91, 2, '2021-04-06 18:05:04', '2021-04-06 18:05:04', '26.00', '29.00', '1375.00', NULL, '0.00'),
(325, 11, 8, '12.25', '564.23', 91, 2, '2021-04-06 18:05:21', '2021-04-06 18:05:21', '56.00', '23.00', '869.00', 'EmailBox', '0.00'),
(326, 25, 2, '25.60', '640.00', 91, 2, '2021-04-06 18:05:21', '2021-04-06 18:05:21', '26.00', '29.00', '1375.00', NULL, '0.00'),
(327, 11, 8, '12.25', '564.23', 91, 2, '2021-04-06 18:06:28', '2021-04-06 18:06:28', '56.00', '23.00', '869.00', 'EmailBox', '0.00'),
(328, 25, 2, '25.60', '640.00', 91, 2, '2021-04-06 18:06:28', '2021-04-06 18:06:28', '26.00', '29.00', '1375.00', NULL, '0.00'),
(329, 11, 8, '12.25', '564.23', 91, 2, '2021-04-06 18:07:52', '2021-04-06 18:07:52', '56.00', '23.00', '869.00', 'EmailBox', '0.00'),
(330, 25, 2, '25.60', '640.00', 91, 2, '2021-04-06 18:07:52', '2021-04-06 18:07:52', '26.00', '29.00', '1375.00', NULL, '0.00'),
(331, 11, 8, '12.25', '564.23', 91, 2, '2021-04-06 18:08:10', '2021-04-06 18:08:10', '56.00', '23.00', '869.00', 'EmailBox', '0.00'),
(332, 25, 2, '25.60', '640.00', 91, 2, '2021-04-06 18:08:10', '2021-04-06 18:08:10', '26.00', '29.00', '1375.00', NULL, '0.00'),
(333, 11, 8, '12.25', '564.23', 91, 2, '2021-04-06 18:08:29', '2021-04-06 18:08:29', '56.00', '23.00', '869.00', 'EmailBox', '0.00'),
(334, 25, 2, '25.60', '640.00', 91, 2, '2021-04-06 18:08:29', '2021-04-06 18:08:29', '26.00', '29.00', '1375.00', NULL, '0.00'),
(335, 11, 8, '12.25', '564.23', 91, 0, '2021-04-06 18:08:46', '2021-04-06 18:08:46', '56.00', '23.00', '869.00', 'EmailBox', '0.00'),
(336, 25, 2, '25.60', '640.00', 91, 0, '2021-04-06 18:08:46', '2021-04-06 18:08:46', '26.00', '29.00', '1375.00', NULL, '0.00'),
(337, 11, 8, '12.25', '564.23', 92, 2, '2021-04-07 11:31:36', '2021-04-07 11:31:36', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(338, 25, 2, '25.60', '56.00', 92, 2, '2021-04-07 11:31:36', '2021-04-07 11:31:36', '0.00', '0.00', '0.00', NULL, '0.00'),
(339, 11, 8, '12.25', '564.23', 92, 2, '2021-04-07 11:32:10', '2021-04-07 11:32:10', '3.00', '5.00', '88.00', 'EmailBox', '0.00'),
(340, 25, 2, '25.60', '56.00', 92, 2, '2021-04-07 11:32:10', '2021-04-07 11:32:10', '9.00', '6.00', '375.00', NULL, '0.00'),
(341, 11, 8, '12.25', '564.23', 93, 0, '2021-04-07 11:43:29', '2021-04-07 11:43:29', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(342, 25, 2, '25.60', '56.00', 93, 0, '2021-04-07 11:43:29', '2021-04-07 11:43:29', '0.00', '0.00', '0.00', NULL, '0.00'),
(343, 11, 8, '12.25', '564.23', 92, 2, '2021-04-07 12:01:18', '2021-04-07 12:01:18', '3.00', '5.00', '88.00', 'EmailBox', '0.00'),
(344, 25, 2, '25.60', '56.00', 92, 2, '2021-04-07 12:01:18', '2021-04-07 12:01:18', '9.00', '6.00', '375.00', NULL, '0.00'),
(345, 11, 8, '12.25', '564.23', 92, 2, '2021-04-07 12:03:58', '2021-04-07 12:03:58', '3.00', '5.00', '88.00', 'EmailBox', '0.00'),
(346, 25, 2, '25.60', '56.00', 92, 2, '2021-04-07 12:03:58', '2021-04-07 12:03:58', '9.00', '6.00', '375.00', NULL, '0.00'),
(347, 11, 8, '5.09', '56.00', 92, 2, '2021-04-07 12:19:13', '2021-04-07 12:19:13', '6.00', '5.00', '121.00', 'EmailBox', '0.00'),
(348, 25, 2, '25.60', '56.00', 92, 2, '2021-04-07 12:19:13', '2021-04-07 12:19:13', '9.00', '6.00', '375.00', NULL, '0.00'),
(349, 11, 8, '5.09', '56.00', 92, 2, '2021-04-07 12:19:33', '2021-04-07 12:19:33', '6.00', '5.00', '121.00', 'EmailBox', '0.00'),
(350, 25, 2, '25.60', '56.00', 92, 2, '2021-04-07 12:19:33', '2021-04-07 12:19:33', '9.00', '6.00', '375.00', NULL, '0.00'),
(351, 11, 8, '5.09', '56.00', 92, 2, '2021-04-07 12:20:18', '2021-04-07 12:20:18', '6.00', '5.00', '121.00', 'EmailBox', '0.00'),
(352, 25, 2, '25.60', '56.00', 92, 2, '2021-04-07 12:20:18', '2021-04-07 12:20:18', '9.00', '6.00', '375.00', NULL, '0.00'),
(353, 11, 8, '5.09', '56.00', 92, 2, '2021-04-07 12:29:24', '2021-04-07 12:29:24', '78.89', '5.00', '922.79', 'EmailBox', '0.00'),
(354, 25, 2, '25.60', '56.00', 92, 2, '2021-04-07 12:29:24', '2021-04-07 12:29:24', '56.00', '6.00', '1550.00', NULL, '0.00'),
(355, 11, 8, '5.09', '56.00', 92, 0, '2021-04-07 12:35:25', '2021-04-07 12:35:25', '78.89', '5.00', '922.79', 'EmailBox', '0.00'),
(356, 25, 2, '25.60', '56.00', 92, 0, '2021-04-07 12:35:25', '2021-04-07 12:35:25', '56.00', '6.00', '1550.00', NULL, '0.00'),
(357, 11, 8, '12.25', '564.23', 94, 2, '2021-04-07 12:59:18', '2021-04-07 12:59:18', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(358, 25, 2, '25.60', '56.00', 94, 2, '2021-04-07 12:59:18', '2021-04-07 12:59:18', '0.00', '0.00', '0.00', NULL, '0.00'),
(359, 11, 8, '12.25', '564.23', 94, 2, '2021-04-07 13:28:25', '2021-04-07 13:28:25', '23.89', '9.80', '370.59', 'EmailBox', '0.00'),
(360, 25, 2, '25.60', '56.00', 94, 2, '2021-04-07 13:28:25', '2021-04-07 13:28:25', '9.80', '6.70', '412.50', NULL, '0.00'),
(361, 11, 8, '12.25', '564.23', 94, 2, '2021-04-07 13:31:33', '2021-04-07 13:31:33', '23.89', '9.80', '370.59', 'EmailBox', '0.00'),
(362, 25, 2, '25.60', '56.00', 94, 2, '2021-04-07 13:31:33', '2021-04-07 13:31:33', '9.80', '6.70', '412.50', NULL, '0.00'),
(363, 11, 8, '12.25', '564.23', 94, 0, '2021-04-07 13:33:29', '2021-04-07 13:33:29', '23.89', '9.80', '370.59', 'EmailBox', '0.00'),
(364, 25, 2, '25.60', '56.00', 94, 0, '2021-04-07 13:33:29', '2021-04-07 13:33:29', '9.80', '6.70', '412.50', NULL, '0.00'),
(365, 11, 8, '12.25', '564.23', 95, 2, '2021-04-07 14:20:02', '2021-04-07 14:20:02', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(366, 25, 2, '25.60', '56.00', 95, 2, '2021-04-07 14:20:02', '2021-04-07 14:20:02', '0.00', '0.00', '0.00', NULL, '0.00'),
(367, 11, 8, '12.25', '564.23', 95, 2, '2021-04-07 14:21:05', '2021-04-07 14:21:05', '6.00', '3.00', '99.00', 'EmailBox', '596.00'),
(368, 25, 11, '25.60', '56.00', 95, 2, '2021-04-07 14:21:05', '2021-04-07 14:21:05', '3.00', '9.00', '300.00', NULL, '7897.00'),
(369, 11, 8, '12.25', '564.23', 95, 2, '2021-04-07 14:25:37', '2021-04-07 14:25:37', '9.70', '3.00', '139.70', 'EmailBox', '596.00'),
(370, 25, 11, '25.60', '56.00', 95, 2, '2021-04-07 14:25:37', '2021-04-07 14:25:37', '3.00', '9.00', '300.00', NULL, '7897.00'),
(371, 11, 8, '12.25', '564.23', 95, 2, '2021-04-07 14:27:29', '2021-04-07 14:27:29', '9.70', '3.00', '139.70', 'EmailBox', '596.00'),
(372, 25, 11, '25.60', '56.00', 95, 2, '2021-04-07 14:27:29', '2021-04-07 14:27:29', '3.00', '9.00', '300.00', NULL, '7897.00'),
(373, 11, 8, '12.25', '564.23', 95, 0, '2021-04-07 14:27:49', '2021-04-07 14:27:49', '9.70', '3.00', '139.70', 'EmailBox', '596.00'),
(374, 25, 11, '25.60', '56.00', 95, 0, '2021-04-07 14:27:49', '2021-04-07 14:27:49', '3.00', '9.00', '300.00', NULL, '7897.00'),
(375, 11, 8, '12.25', '564.23', 96, 2, '2021-04-07 14:41:39', '2021-04-07 14:41:39', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(376, 25, 2, '25.60', '56.00', 96, 2, '2021-04-07 14:41:39', '2021-04-07 14:41:39', '0.00', '0.00', '0.00', NULL, '0.00'),
(377, 11, 8, '12.25', '564.23', 97, 2, '2021-04-07 14:45:13', '2021-04-07 14:45:13', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(378, 25, 2, '25.60', '56.00', 97, 2, '2021-04-07 14:45:13', '2021-04-07 14:45:13', '0.00', '0.00', '0.00', NULL, '0.00'),
(379, 11, 8, '12.25', '564.23', 97, 0, '2021-04-07 14:45:54', '2021-04-07 14:45:54', '8.00', '7.80', '173.80', 'EmailBox', '0.00'),
(380, 25, 2, '25.60', '56.00', 97, 0, '2021-04-07 14:45:54', '2021-04-07 14:45:54', '5.70', '4.70', '260.00', NULL, '0.00'),
(381, 11, 8, '12.25', '564.23', 98, 2, '2021-04-07 15:00:13', '2021-04-07 15:00:13', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(382, 25, 2, '25.60', '56.00', 98, 2, '2021-04-07 15:00:13', '2021-04-07 15:00:13', '0.00', '0.00', '0.00', NULL, '0.00'),
(383, 11, 8, '12.25', '564.23', 98, 0, '2021-04-07 15:00:56', '2021-04-07 15:00:56', '5.60', '4.50', '111.10', 'EmailBox', '0.00'),
(384, 25, 2, '25.60', '56.00', 98, 0, '2021-04-07 15:00:56', '2021-04-07 15:00:56', '8.80', '5.70', '362.50', NULL, '0.00'),
(385, 11, 8, '12.25', '564.23', 99, 2, '2021-04-07 15:09:59', '2021-04-07 15:09:59', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(386, 25, 2, '25.60', '56.00', 99, 2, '2021-04-07 15:09:59', '2021-04-07 15:09:59', '0.00', '0.00', '0.00', NULL, '0.00'),
(387, 11, 8, '12.25', '564.23', 99, 2, '2021-04-07 15:12:08', '2021-04-07 15:12:08', '8.08', '8.60', '183.48', 'EmailBox', '0.00'),
(388, 11, 8, '12.25', '564.23', 99, 0, '2021-04-07 15:17:10', '2021-04-07 15:17:10', '8.08', '8.60', '183.48', 'EmailBox', '0.00'),
(389, 11, 8, '12.25', '564.23', 100, 2, '2021-04-07 15:40:11', '2021-04-07 15:40:11', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(390, 25, 2, '25.60', '56.00', 100, 2, '2021-04-07 15:40:11', '2021-04-07 15:40:11', '0.00', '0.00', '0.00', NULL, '0.00'),
(391, 11, 8, '12.25', '564.23', 100, 0, '2021-04-07 15:40:48', '2021-04-07 15:40:48', '6.00', '9.00', '165.00', 'EmailBox', '0.00'),
(392, 11, 8, '12.25', '564.23', 101, 2, '2021-04-07 16:38:15', '2021-04-07 16:38:15', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(393, 25, 2, '25.60', '56.00', 101, 2, '2021-04-07 16:38:15', '2021-04-07 16:38:15', '0.00', '0.00', '0.00', NULL, '0.00'),
(394, 11, 8, '12.25', '564.23', 102, 2, '2021-04-07 16:39:50', '2021-04-07 16:39:50', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(395, 25, 2, '25.60', '56.00', 102, 2, '2021-04-07 16:39:50', '2021-04-07 16:39:50', '0.00', '0.00', '0.00', NULL, '0.00'),
(396, 11, 8, '12.25', '564.23', 102, 0, '2021-04-07 16:45:32', '2021-04-07 16:45:32', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(397, 25, 2, '25.60', '56.00', 102, 0, '2021-04-07 16:45:32', '2021-04-07 16:45:32', '89.00', '0.00', '2225.00', NULL, '0.00'),
(398, 11, 8, '12.25', '564.23', 103, 2, '2021-04-07 16:55:32', '2021-04-07 16:55:32', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(399, 25, 2, '25.60', '56.00', 103, 2, '2021-04-07 16:55:32', '2021-04-07 16:55:32', '0.00', '0.00', '0.00', NULL, '0.00'),
(400, 11, 8, '12.25', '564.23', 103, 0, '2021-04-07 17:08:14', '2021-04-07 17:08:14', '6.00', '0.00', '66.00', 'EmailBox', '0.00'),
(401, 25, 2, '25.60', '56.00', 103, 0, '2021-04-07 17:08:14', '2021-04-07 17:08:14', '0.00', '0.00', '0.00', NULL, '0.00'),
(402, 11, 8, '12.25', '564.23', 101, 0, '2021-04-07 17:26:25', '2021-04-07 17:26:25', '569.00', '0.00', '6259.00', 'EmailBox', '0.00'),
(403, 25, 2, '25.60', '56.00', 101, 0, '2021-04-07 17:26:25', '2021-04-07 17:26:25', '0.00', '0.00', '0.00', NULL, '0.00'),
(404, 11, 8, '12.25', '564.23', 104, 2, '2021-04-07 17:31:57', '2021-04-07 17:31:57', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(405, 25, 2, '25.60', '56.00', 104, 2, '2021-04-07 17:31:57', '2021-04-07 17:31:57', '0.00', '0.00', '0.00', NULL, '0.00'),
(406, 11, 8, '12.25', '564.23', 104, 2, '2021-04-07 17:37:10', '2021-04-07 17:37:10', '22.00', '0.00', '242.00', 'EmailBox', '0.00'),
(407, 25, 2, '25.60', '56.00', 104, 2, '2021-04-07 17:37:10', '2021-04-07 17:37:10', '0.00', '22.90', '572.50', NULL, '0.00'),
(408, 11, 8, '12.25', '564.23', 104, 0, '2021-04-07 17:43:49', '2021-04-07 17:43:49', '22.00', '0.00', '242.00', 'EmailBox', '0.00'),
(409, 25, 2, '25.60', '56.00', 104, 0, '2021-04-07 17:43:49', '2021-04-07 17:43:49', '0.00', '22.90', '572.50', NULL, '0.00'),
(410, 11, 8, '12.25', '564.23', 105, 2, '2021-04-07 18:00:20', '2021-04-07 18:00:20', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(411, 25, 2, '25.60', '56.00', 105, 2, '2021-04-07 18:00:20', '2021-04-07 18:00:20', '0.00', '0.00', '0.00', NULL, '0.00'),
(412, 11, 8, '12.25', '564.23', 105, 0, '2021-04-07 18:00:35', '2021-04-07 18:00:35', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(413, 25, 2, '25.60', '56.00', 105, 0, '2021-04-07 18:00:35', '2021-04-07 18:00:35', '9.00', '0.00', '225.00', NULL, '0.00'),
(414, 11, 8, '12.25', '564.23', 96, 2, '2021-04-08 10:36:04', '2021-04-08 10:36:04', '56.00', '52.00', '1188.00', 'EmailBox', '0.00'),
(415, 11, 8, '12.25', '564.23', 96, 0, '2021-04-08 10:37:46', '2021-04-08 10:37:46', '56.00', '52.00', '1188.00', 'EmailBox', '0.00'),
(416, 11, 8, '12.25', '564.23', 106, 2, '2021-04-08 11:03:44', '2021-04-08 11:03:44', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(417, 25, 2, '25.60', '56.00', 106, 2, '2021-04-08 11:03:44', '2021-04-08 11:03:44', '0.00', '0.00', '0.00', NULL, '0.00'),
(418, 12, 8, '32.00', '384.00', 107, 2, '2021-04-08 11:04:23', '2021-04-08 11:04:23', '0.00', '0.00', '0.00', 'test test test', '0.00'),
(419, 11, 8, '12.25', '564.23', 108, 2, '2021-04-08 11:04:35', '2021-04-08 11:04:35', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(420, 25, 2, '25.60', '56.00', 108, 2, '2021-04-08 11:04:35', '2021-04-08 11:04:35', '0.00', '0.00', '0.00', NULL, '0.00'),
(421, 12, 8, '32.00', '384.00', 107, 0, '2021-04-08 11:04:59', '2021-04-08 11:04:59', '56.00', '6.80', '753.60', 'test test test', '0.00'),
(422, 11, 8, '12.25', '564.23', 109, 2, '2021-04-08 11:37:04', '2021-04-08 11:37:04', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(423, 25, 2, '25.60', '56.00', 109, 2, '2021-04-08 11:37:04', '2021-04-08 11:37:04', '0.00', '0.00', '0.00', NULL, '0.00'),
(424, 11, 8, '12.25', '564.23', 110, 2, '2021-04-08 11:37:59', '2021-04-08 11:37:59', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(425, 25, 2, '25.60', '56.00', 110, 2, '2021-04-08 11:37:59', '2021-04-08 11:37:59', '0.00', '0.00', '0.00', NULL, '0.00'),
(426, 11, 8, '12.25', '564.23', 110, 0, '2021-04-08 11:38:37', '2021-04-08 11:38:37', '56.78', '90.90', '1624.48', 'EmailBox', '0.00'),
(427, 11, 8, '12.25', '564.23', 109, 0, '2021-04-08 12:23:46', '2021-04-08 12:23:46', '23.00', '0.00', '253.00', 'EmailBox', '0.00');
INSERT INTO `tbl_order_parcel_details` (`id`, `no_of_parcel`, `goods_type_id`, `goods_weight`, `total_weight`, `order_id`, `is_active`, `created_at`, `updated_at`, `tempo_charge`, `service_charge`, `delivery_charge`, `other_text`, `estimation_value`) VALUES
(428, 25, 2, '25.60', '56.00', 109, 0, '2021-04-08 12:23:46', '2021-04-08 12:23:46', '0.00', '0.00', '0.00', NULL, '0.00'),
(429, 11, 8, '12.25', '564.23', 108, 2, '2021-04-08 12:26:32', '2021-04-08 12:26:32', '23.00', '0.00', '253.00', 'EmailBox', '0.00'),
(430, 25, 2, '25.60', '56.00', 108, 2, '2021-04-08 12:26:32', '2021-04-08 12:26:32', '85.00', '0.00', '2125.00', NULL, '0.00'),
(431, 11, 8, '12.25', '564.23', 111, 2, '2021-04-08 13:21:26', '2021-04-08 13:21:26', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(432, 25, 2, '25.60', '56.00', 111, 2, '2021-04-08 13:21:26', '2021-04-08 13:21:26', '0.00', '0.00', '0.00', NULL, '0.00'),
(433, 11, 8, '12.25', '564.23', 111, 2, '2021-04-08 13:21:50', '2021-04-08 13:21:50', '56.00', '0.00', '616.00', 'EmailBox', '0.00'),
(434, 11, 8, '12.25', '564.23', 111, 2, '2021-04-08 13:25:46', '2021-04-08 13:25:46', '56.00', '0.00', '616.00', 'EmailBox', '0.00'),
(435, 11, 8, '12.25', '564.23', 111, 2, '2021-04-08 13:26:56', '2021-04-08 13:26:56', '56.00', '0.00', '616.00', 'EmailBox', '0.00'),
(436, 11, 8, '12.25', '564.23', 111, 2, '2021-04-08 13:28:25', '2021-04-08 13:28:25', '7.80', '0.00', '85.80', 'EmailBox', '0.00'),
(437, 11, 8, '12.25', '564.23', 111, 0, '2021-04-08 13:30:00', '2021-04-08 13:30:00', '7.80', '0.00', '85.80', 'EmailBox', '0.00'),
(438, 11, 8, '12.25', '564.23', 112, 2, '2021-04-08 13:37:22', '2021-04-08 13:37:22', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(439, 25, 2, '25.60', '56.00', 112, 2, '2021-04-08 13:37:22', '2021-04-08 13:37:22', '0.00', '0.00', '0.00', NULL, '0.00'),
(440, 11, 8, '12.25', '564.23', 112, 0, '2021-04-08 13:37:51', '2021-04-08 13:37:51', '56.00', '0.00', '616.00', 'EmailBox', '0.00'),
(441, 25, 2, '25.60', '56.00', 112, 0, '2021-04-08 13:37:51', '2021-04-08 13:37:51', '0.00', '0.00', '0.00', NULL, '0.00'),
(442, 12, 8, '11.00', '132.00', 113, 2, '2021-04-08 14:17:57', '2021-04-08 14:17:57', '0.00', '0.00', '0.00', 'test test test', '0.00'),
(443, 12, 8, '11.00', '132.00', 113, 0, '2021-04-08 14:22:21', '2021-04-08 14:22:21', '200.00', '10.00', '2520.00', 'test test test', '0.00'),
(444, 11, 8, '12.25', '564.23', 114, 2, '2021-04-08 14:49:00', '2021-04-08 14:49:00', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(445, 25, 2, '25.60', '56.00', 114, 2, '2021-04-08 14:49:00', '2021-04-08 14:49:00', '0.00', '0.00', '0.00', NULL, '0.00'),
(446, 11, 8, '12.25', '564.23', 114, 0, '2021-04-08 14:49:27', '2021-04-08 14:49:27', '26.00', '35.00', '671.00', 'EmailBox', '0.00'),
(447, 11, 8, '12.25', '564.23', 115, 2, '2021-04-08 14:54:52', '2021-04-08 14:54:52', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(448, 25, 2, '25.60', '56.00', 115, 2, '2021-04-08 14:54:52', '2021-04-08 14:54:52', '0.00', '0.00', '0.00', NULL, '0.00'),
(449, 11, 8, '12.25', '564.23', 115, 2, '2021-04-08 14:55:05', '2021-04-08 14:55:05', '56.00', '0.00', '616.00', 'EmailBox', '0.00'),
(450, 25, 2, '25.60', '56.00', 115, 2, '2021-04-08 14:55:05', '2021-04-08 14:55:05', '0.00', '0.00', '0.00', NULL, '0.00'),
(451, 11, 8, '12.25', '564.23', 115, 0, '2021-04-08 14:56:59', '2021-04-08 14:56:59', '56.00', '0.00', '616.00', 'EmailBox', '0.00'),
(452, 25, 2, '25.60', '56.00', 115, 0, '2021-04-08 14:56:59', '2021-04-08 14:56:59', '0.00', '0.00', '0.00', NULL, '0.00'),
(453, 11, 8, '12.25', '564.23', 116, 2, '2021-04-08 15:22:41', '2021-04-08 15:22:41', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(454, 25, 2, '25.60', '56.00', 116, 2, '2021-04-08 15:22:41', '2021-04-08 15:22:41', '0.00', '0.00', '0.00', NULL, '0.00'),
(455, 11, 8, '12.25', '564.23', 116, 0, '2021-04-08 15:23:21', '2021-04-08 15:23:21', '59.00', '26.00', '935.00', 'EmailBox', '0.00'),
(456, 11, 8, '12.25', '564.23', 106, 2, '2021-04-08 15:32:47', '2021-04-08 15:32:47', '56.00', '0.00', '616.00', 'EmailBox', '0.00'),
(457, 11, 8, '12.25', '564.23', 117, 2, '2021-04-08 15:36:59', '2021-04-08 15:36:59', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(458, 25, 2, '25.60', '56.00', 117, 2, '2021-04-08 15:36:59', '2021-04-08 15:36:59', '0.00', '0.00', '0.00', NULL, '0.00'),
(459, 11, 8, '12.25', '564.23', 117, 0, '2021-04-08 15:37:37', '2021-04-08 15:37:37', '16.00', '0.00', '176.00', 'EmailBox', '0.00'),
(460, 11, 8, '12.25', '564.23', 118, 2, '2021-04-08 15:38:41', '2021-04-08 15:38:41', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(461, 25, 2, '25.60', '56.00', 118, 2, '2021-04-08 15:38:41', '2021-04-08 15:38:41', '0.00', '0.00', '0.00', NULL, '0.00'),
(462, 1, 15, '10.00', '10.00', 118, 0, '2021-04-08 15:39:16', '2021-04-08 15:39:16', '12.00', '26.00', '38.00', NULL, '12500.00'),
(463, 1, 18, '50.00', '50.00', 119, 0, '2021-04-08 16:06:08', '2021-04-08 16:06:08', '120.00', '30.00', '150.00', NULL, '7108.00'),
(464, 1, 18, '50.00', '50.00', 120, 0, '2021-04-08 16:06:29', '2021-04-08 16:06:29', '110.00', '150.00', '260.00', NULL, '7108.00'),
(465, 1, 18, '50.00', '50.00', 121, 0, '2021-04-08 16:12:57', '2021-04-08 16:12:57', '110.00', '150.00', '260.00', NULL, '7108.00'),
(466, 1, 18, '50.00', '50.00', 122, 0, '2021-04-08 16:13:07', '2021-04-08 16:13:07', '110.00', '150.00', '260.00', NULL, '7108.00'),
(467, 11, 8, '12.25', '564.23', 123, 2, '2021-04-08 17:03:35', '2021-04-08 17:03:35', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(468, 25, 2, '25.60', '56.00', 123, 2, '2021-04-08 17:03:35', '2021-04-08 17:03:35', '0.00', '0.00', '0.00', NULL, '0.00'),
(469, 11, 8, '12.25', '564.23', 123, 0, '2021-04-08 17:03:55', '2021-04-08 17:03:55', '56.00', '0.00', '616.00', 'EmailBox', '0.00'),
(470, 11, 8, '12.25', '564.23', 124, 2, '2021-04-08 17:05:20', '2021-04-08 17:05:20', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(471, 25, 2, '25.60', '56.00', 124, 2, '2021-04-08 17:05:20', '2021-04-08 17:05:20', '0.00', '0.00', '0.00', NULL, '0.00'),
(472, 11, 8, '12.25', '564.23', 124, 0, '2021-04-08 17:05:37', '2021-04-08 17:05:37', '23.00', '0.00', '253.00', 'EmailBox', '0.00'),
(473, 11, 8, '12.25', '564.23', 125, 2, '2021-04-08 17:09:35', '2021-04-08 17:09:35', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(474, 25, 2, '25.60', '56.00', 125, 2, '2021-04-08 17:09:35', '2021-04-08 17:09:35', '0.00', '0.00', '0.00', NULL, '0.00'),
(475, 11, 8, '12.25', '564.23', 125, 0, '2021-04-08 17:09:52', '2021-04-08 17:09:52', '56.00', '0.00', '616.00', 'EmailBox', '0.00'),
(476, 11, 8, '12.25', '564.23', 126, 2, '2021-04-08 17:13:58', '2021-04-08 17:13:58', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(477, 25, 2, '25.60', '56.00', 126, 2, '2021-04-08 17:13:58', '2021-04-08 17:13:58', '0.00', '0.00', '0.00', NULL, '0.00'),
(478, 3, 14, '5.00', '55.00', 126, 0, '2021-04-08 17:14:25', '2021-04-08 17:14:25', '56.00', '0.00', '168.00', NULL, '0.00'),
(479, 11, 8, '12.25', '564.23', 127, 2, '2021-04-08 17:20:48', '2021-04-08 17:20:48', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(480, 25, 2, '25.60', '56.00', 127, 2, '2021-04-08 17:20:48', '2021-04-08 17:20:48', '0.00', '0.00', '0.00', NULL, '0.00'),
(481, 11, 8, '12.25', '564.23', 127, 0, '2021-04-08 17:21:03', '2021-04-08 17:21:03', '56.00', '0.00', '616.00', 'EmailBox', '0.00'),
(482, 25, 2, '25.60', '56.00', 127, 0, '2021-04-08 17:21:03', '2021-04-08 17:21:03', '0.00', '0.00', '0.00', NULL, '0.00'),
(483, 11, 8, '12.25', '564.23', 128, 2, '2021-04-08 17:53:43', '2021-04-08 17:53:43', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(484, 25, 2, '25.60', '56.00', 128, 2, '2021-04-08 17:53:43', '2021-04-08 17:53:43', '0.00', '0.00', '0.00', NULL, '0.00'),
(485, 11, 8, '12.25', '564.23', 128, 0, '2021-04-08 18:00:19', '2021-04-08 18:00:19', '6.00', '0.00', '66.00', 'EmailBox', '0.00'),
(486, 25, 2, '25.60', '56.00', 128, 0, '2021-04-08 18:00:19', '2021-04-08 18:00:19', '0.00', '0.00', '0.00', NULL, '0.00'),
(487, 11, 8, '12.25', '564.23', 129, 2, '2021-04-08 18:01:04', '2021-04-08 18:01:04', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(488, 25, 2, '25.60', '56.00', 129, 2, '2021-04-08 18:01:04', '2021-04-08 18:01:04', '0.00', '0.00', '0.00', NULL, '0.00'),
(489, 11, 8, '12.25', '564.23', 129, 0, '2021-04-08 18:01:22', '2021-04-08 18:01:22', '56.00', '0.00', '616.00', 'EmailBox', '0.00'),
(490, 11, 8, '12.25', '564.23', 130, 2, '2021-04-08 18:10:54', '2021-04-08 18:10:54', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(491, 25, 2, '25.60', '56.00', 130, 2, '2021-04-08 18:10:54', '2021-04-08 18:10:54', '0.00', '0.00', '0.00', NULL, '0.00'),
(492, 11, 8, '12.25', '564.23', 130, 0, '2021-04-08 18:11:30', '2021-04-08 18:11:30', '305.00', '0.00', '3355.00', 'EmailBox', '0.00'),
(493, 11, 8, '12.25', '564.23', 131, 0, '2021-04-08 18:19:19', '2021-04-08 18:19:19', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(494, 25, 2, '25.60', '56.00', 131, 0, '2021-04-08 18:19:19', '2021-04-08 18:19:19', '0.00', '0.00', '0.00', NULL, '0.00'),
(495, 11, 8, '12.25', '564.23', 132, 2, '2021-04-09 09:56:04', '2021-04-09 09:56:04', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(496, 25, 2, '25.60', '56.00', 132, 2, '2021-04-09 09:56:04', '2021-04-09 09:56:04', '0.00', '0.00', '0.00', NULL, '0.00'),
(497, 11, 8, '12.25', '564.23', 132, 0, '2021-04-09 09:56:45', '2021-04-09 09:56:45', '52.00', '26.00', '858.00', 'EmailBox', '0.00'),
(498, 11, 8, '12.25', '564.23', 133, 2, '2021-04-09 10:00:56', '2021-04-09 10:00:56', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(499, 25, 2, '25.60', '56.00', 133, 2, '2021-04-09 10:00:56', '2021-04-09 10:00:56', '0.00', '0.00', '0.00', NULL, '0.00'),
(500, 11, 8, '12.25', '564.23', 133, 0, '2021-04-09 10:01:14', '2021-04-09 10:01:14', '59.00', '0.00', '649.00', 'EmailBox', '0.00'),
(501, 11, 8, '12.25', '564.23', 134, 2, '2021-04-09 10:38:10', '2021-04-09 10:38:10', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(502, 25, 2, '25.60', '56.00', 134, 2, '2021-04-09 10:38:10', '2021-04-09 10:38:10', '0.00', '0.00', '0.00', NULL, '0.00'),
(503, 11, 8, '12.25', '564.23', 134, 0, '2021-04-09 10:38:29', '2021-04-09 10:38:29', '59.00', '23.00', '902.00', 'EmailBox', '0.00'),
(504, 11, 8, '12.25', '564.23', 135, 2, '2021-04-09 10:40:35', '2021-04-09 10:40:35', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(505, 25, 2, '25.60', '56.00', 135, 2, '2021-04-09 10:40:35', '2021-04-09 10:40:35', '0.00', '0.00', '0.00', NULL, '0.00'),
(506, 11, 8, '12.25', '564.23', 136, 0, '2021-04-09 10:40:37', '2021-04-09 10:40:37', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(507, 25, 2, '25.60', '56.00', 136, 0, '2021-04-09 10:40:37', '2021-04-09 10:40:37', '0.00', '0.00', '0.00', NULL, '0.00'),
(508, 11, 8, '12.25', '564.23', 137, 0, '2021-04-09 10:40:38', '2021-04-09 10:40:38', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(509, 25, 2, '25.60', '56.00', 137, 0, '2021-04-09 10:40:38', '2021-04-09 10:40:38', '0.00', '0.00', '0.00', NULL, '0.00'),
(510, 11, 8, '12.25', '564.23', 135, 0, '2021-04-09 11:10:47', '2021-04-09 11:10:47', '26.00', '23.00', '539.00', 'EmailBox', '0.00'),
(511, 11, 8, '12.25', '564.23', 138, 2, '2021-04-09 11:11:04', '2021-04-09 11:11:04', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(512, 25, 2, '25.60', '56.00', 138, 2, '2021-04-09 11:11:04', '2021-04-09 11:11:04', '0.00', '0.00', '0.00', NULL, '0.00'),
(513, 11, 8, '12.25', '564.23', 139, 2, '2021-04-09 11:23:42', '2021-04-09 11:23:42', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(514, 25, 2, '25.60', '56.00', 139, 2, '2021-04-09 11:23:42', '2021-04-09 11:23:42', '0.00', '0.00', '0.00', NULL, '0.00'),
(515, 11, 8, '12.25', '564.23', 139, 0, '2021-04-09 11:24:11', '2021-04-09 11:24:11', '59.00', '53.00', '1232.00', 'EmailBox', '0.00'),
(516, 12, 8, '52.00', '624.00', 140, 2, '2021-04-09 11:28:44', '2021-04-09 11:28:44', '0.00', '0.00', '0.00', 'test test test', '0.00'),
(517, 12, 8, '52.00', '624.00', 140, 0, '2021-04-09 11:29:46', '2021-04-09 11:29:46', '30.00', '40.00', '840.00', 'test test test', '0.00'),
(518, 12, 17, '11.00', '132.00', 141, 2, '2021-04-09 11:33:50', '2021-04-09 11:33:50', '0.00', '0.00', '0.00', NULL, '0.00'),
(519, 12, 17, '11.00', '132.00', 141, 0, '2021-04-09 11:34:14', '2021-04-09 11:34:14', '10.00', '30.00', '480.00', NULL, '0.00'),
(520, 12, 24, '11.00', '132.00', 142, 2, '2021-04-09 11:38:23', '2021-04-09 11:38:23', '0.00', '0.00', '0.00', NULL, '0.00'),
(521, 12, 24, '11.00', '132.00', 142, 0, '2021-04-09 11:38:41', '2021-04-09 11:38:41', '10.00', '20.00', '360.00', NULL, '0.00'),
(522, 12, 13, '54.00', '648.00', 143, 2, '2021-04-09 11:43:07', '2021-04-09 11:43:07', '0.00', '0.00', '0.00', NULL, '0.00'),
(523, 12, 13, '54.00', '648.00', 143, 0, '2021-04-09 11:43:20', '2021-04-09 11:43:20', '10.00', '20.00', '360.00', NULL, '0.00'),
(524, 10, 13, '11.00', '110.00', 144, 2, '2021-04-09 11:46:49', '2021-04-09 11:46:49', '0.00', '0.00', '0.00', NULL, '0.00'),
(525, 10, 13, '11.00', '110.00', 144, 0, '2021-04-09 11:47:06', '2021-04-09 11:47:06', '10.00', '30.00', '400.00', NULL, '0.00'),
(526, 12, 8, '12.00', '144.00', 145, 2, '2021-04-09 11:54:47', '2021-04-09 11:54:47', '0.00', '0.00', '0.00', 'test test test', '0.00'),
(527, 12, 8, '12.00', '144.00', 145, 0, '2021-04-09 11:55:09', '2021-04-09 11:55:09', '10.00', '40.00', '600.00', 'test test test', '0.00'),
(528, 12, 17, '12.00', '144.00', 146, 2, '2021-04-09 12:18:55', '2021-04-09 12:18:55', '0.00', '0.00', '0.00', NULL, '0.00'),
(529, 12, 17, '12.00', '144.00', 146, 0, '2021-04-09 12:19:12', '2021-04-09 12:19:12', '10.00', '20.00', '360.00', NULL, '0.00'),
(530, 11, 17, '20.00', '220.00', 147, 2, '2021-04-09 12:20:57', '2021-04-09 12:20:57', '0.00', '0.00', '0.00', NULL, '0.00'),
(531, 11, 17, '20.00', '220.00', 147, 0, '2021-04-09 12:21:16', '2021-04-09 12:21:16', '98.00', '78.00', '1936.00', NULL, '0.00'),
(532, 11, 8, '12.25', '564.23', 148, 2, '2021-04-09 12:22:30', '2021-04-09 12:22:30', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(533, 25, 2, '25.60', '56.00', 148, 2, '2021-04-09 12:22:30', '2021-04-09 12:22:30', '0.00', '0.00', '0.00', NULL, '0.00'),
(534, 11, 17, '11.00', '121.00', 149, 2, '2021-04-09 12:29:06', '2021-04-09 12:29:06', '0.00', '0.00', '0.00', NULL, '0.00'),
(535, 11, 17, '11.00', '121.00', 149, 0, '2021-04-09 12:29:28', '2021-04-09 12:29:28', '12.00', '10.00', '242.00', NULL, '0.00'),
(536, 11, 8, '12.25', '564.23', 148, 0, '2021-04-09 12:36:12', '2021-04-09 12:36:12', '260.00', '0.00', '2860.00', 'EmailBox', '0.00'),
(537, 11, 17, '32.00', '352.00', 150, 2, '2021-04-09 12:40:59', '2021-04-09 12:40:59', '0.00', '0.00', '0.00', NULL, '0.00'),
(538, 11, 17, '32.00', '352.00', 150, 0, '2021-04-09 12:41:16', '2021-04-09 12:41:16', '10.00', '20.00', '330.00', NULL, '0.00'),
(539, 11, 8, '12.25', '564.23', 138, 0, '2021-04-09 12:57:55', '2021-04-09 12:57:55', '59.00', '0.00', '649.00', 'EmailBox', '0.00'),
(540, 12, 17, '11.00', '132.00', 151, 2, '2021-04-09 13:58:23', '2021-04-09 13:58:23', '0.00', '0.00', '0.00', NULL, '0.00'),
(541, 12, 17, '11.00', '132.00', 151, 0, '2021-04-09 13:58:51', '2021-04-09 13:58:51', '12.00', '45.00', '684.00', NULL, '0.00'),
(542, 11, 24, '12.00', '132.00', 152, 2, '2021-04-09 14:01:25', '2021-04-09 14:01:25', '0.00', '0.00', '0.00', NULL, '0.00'),
(543, 11, 24, '12.00', '132.00', 152, 0, '2021-04-09 14:03:16', '2021-04-09 14:03:16', '50.00', '20.00', '770.00', NULL, '0.00'),
(544, 12, 17, '12.00', '144.00', 153, 2, '2021-04-09 14:16:16', '2021-04-09 14:16:16', '0.00', '0.00', '0.00', NULL, '0.00'),
(545, 12, 17, '12.00', '144.00', 153, 0, '2021-04-09 14:25:34', '2021-04-09 14:25:34', '20.00', '50.00', '840.00', NULL, '0.00'),
(546, 11, 8, '12.25', '564.23', 154, 2, '2021-04-09 15:04:55', '2021-04-09 15:04:55', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(547, 25, 2, '25.60', '56.00', 154, 2, '2021-04-09 15:04:55', '2021-04-09 15:04:55', '0.00', '0.00', '0.00', NULL, '0.00'),
(548, 11, 8, '12.25', '564.23', 154, 0, '2021-04-09 15:05:15', '2021-04-09 15:05:15', '56.00', '0.00', '616.00', 'EmailBox', '0.00'),
(549, 11, 8, '12.25', '564.23', 155, 2, '2021-04-09 16:22:55', '2021-04-09 16:22:55', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(550, 25, 2, '25.60', '56.00', 155, 2, '2021-04-09 16:22:55', '2021-04-09 16:22:55', '0.00', '0.00', '0.00', NULL, '0.00'),
(551, 11, 8, '12.25', '564.23', 155, 2, '2021-04-09 16:23:16', '2021-04-09 16:23:16', '59.00', '0.00', '649.00', 'EmailBox', '0.00'),
(552, 11, 8, '12.25', '564.23', 156, 2, '2021-04-09 16:24:23', '2021-04-09 16:24:23', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(553, 25, 2, '25.60', '56.00', 156, 2, '2021-04-09 16:24:23', '2021-04-09 16:24:23', '0.00', '0.00', '0.00', NULL, '0.00'),
(554, 11, 8, '12.25', '564.23', 156, 0, '2021-04-09 16:24:37', '2021-04-09 16:24:37', '89.00', '0.00', '979.00', 'EmailBox', '0.00'),
(555, 11, 8, '12.25', '564.23', 157, 2, '2021-04-09 16:35:11', '2021-04-09 16:35:11', '0.00', '0.00', '0.00', 'EmailBox', '0.00'),
(556, 25, 2, '25.60', '56.00', 157, 2, '2021-04-09 16:35:11', '2021-04-09 16:35:11', '0.00', '0.00', '0.00', NULL, '0.00'),
(557, 11, 8, '12.25', '564.23', 157, 0, '2021-04-09 16:35:25', '2021-04-09 16:35:25', '59.00', '0.00', '649.00', 'EmailBox', '0.00'),
(558, 25, 2, '25.60', '56.00', 157, 0, '2021-04-09 16:35:25', '2021-04-09 16:35:25', '0.00', '0.00', '0.00', NULL, '0.00'),
(559, 11, 8, '12.25', '564.23', 155, 0, '2021-04-14 14:19:09', '2021-04-14 14:19:09', '59.00', '0.00', '649.00', 'EmailBox', '0.00'),
(560, 1, 18, '45.00', '45.00', 158, 2, '2021-04-17 14:57:23', '2021-04-17 14:57:23', '55.00', '54.00', '109.00', NULL, '456.00'),
(561, 5, 18, '45.00', '45.00', 158, 0, '2021-04-17 17:39:08', '2021-04-17 17:39:08', '250.00', '256.00', '2530.00', NULL, '456.00'),
(562, 3, 2, '50.00', '150.00', 160, 0, '2021-04-19 12:25:27', '2021-04-19 12:25:27', '0.00', '0.00', '0.00', NULL, '0.00'),
(563, 3, 2, '50.00', '150.00', 165, 0, '2021-04-19 12:41:30', '2021-04-19 12:41:30', '0.00', '0.00', '0.00', NULL, '0.00'),
(564, 3, 2, '50.00', '150.00', 166, 0, '2021-04-19 12:53:29', '2021-04-19 12:53:29', '0.00', '0.00', '0.00', NULL, '0.00'),
(565, 3, 0, '50.00', '150.00', 167, 0, '2021-04-19 12:56:04', '2021-04-19 12:56:04', '0.00', '0.00', '0.00', NULL, '0.00'),
(566, 3, 0, '50.00', '150.00', 168, 0, '2021-04-19 12:57:18', '2021-04-19 12:57:18', '0.00', '0.00', '0.00', NULL, '0.00'),
(567, 3, 1, '50.00', '150.00', 169, 2, '2021-04-19 12:58:55', '2021-04-19 12:58:55', '0.00', '0.00', '0.00', NULL, '0.00'),
(568, 3, 1, '50.00', '150.00', 170, 0, '2021-04-19 13:01:38', '2021-04-19 13:01:38', '0.00', '0.00', '0.00', NULL, '0.00'),
(569, 3, 1, '50.00', '150.00', 169, 2, '2021-04-19 13:01:53', '2021-04-19 13:01:53', '26.00', '59.00', '255.00', NULL, '2389.00'),
(570, 9, 1, '50.00', '150.00', 169, 2, '2021-04-19 13:03:04', '2021-04-19 13:03:04', '26.00', '59.00', '765.00', NULL, '2389.00'),
(571, 9, 1, '50.00', '150.00', 169, 2, '2021-04-19 13:12:56', '2021-04-19 13:12:56', '26.00', '59.00', '765.00', NULL, '2389.00'),
(572, 9, 1, '50.00', '150.00', 169, 0, '2021-04-19 13:13:11', '2021-04-19 13:13:11', '26.00', '59.00', '765.00', NULL, '2389.00'),
(573, 6, 1, '50.00', '150.00', 171, 2, '2021-04-19 17:35:28', '2021-04-19 17:35:28', '0.00', '0.00', '0.00', NULL, '0.00'),
(574, 6, 1, '50.00', '150.00', 171, 2, '2021-04-19 17:36:06', '2021-04-19 17:36:06', '159.00', '236.00', '2370.00', NULL, '4564.00'),
(575, 6, 1, '50.00', '150.00', 171, 2, '2021-04-19 17:45:29', '2021-04-19 17:45:29', '159.00', '236.00', '2370.00', NULL, '4564.00'),
(576, 6, 1, '50.00', '150.00', 171, 0, '2021-04-19 17:46:13', '2021-04-19 17:46:13', '159.00', '236.00', '2370.00', NULL, '4564.00'),
(577, 6, 1, '50.00', '150.00', 172, 2, '2021-04-19 17:51:23', '2021-04-19 17:51:23', '0.00', '0.00', '0.00', NULL, '0.00'),
(578, 12, 1, '50.00', '600.00', 172, 2, '2021-04-19 17:51:51', '2021-04-19 17:51:51', '203.00', '105.00', '3696.00', NULL, '0.00'),
(579, 12, 1, '50.00', '600.00', 172, 0, '2021-04-19 17:53:54', '2021-04-19 17:53:54', '203.00', '105.00', '3696.00', NULL, '0.00'),
(580, 6, 1, '50.00', '150.00', 173, 2, '2021-04-19 17:55:25', '2021-04-19 17:55:25', '0.00', '0.00', '0.00', NULL, '0.00'),
(581, 6, 1, '50.00', '150.00', 173, 2, '2021-04-19 17:56:16', '2021-04-19 17:56:16', '6465.00', '132.00', '39582.00', NULL, '0.00'),
(582, 6, 1, '50.00', '150.00', 173, 0, '2021-04-19 17:56:51', '2021-04-19 17:56:51', '6465.00', '132.00', '39582.00', NULL, '0.00'),
(583, 6, 1, '50.00', '150.00', 174, 0, '2021-04-19 17:57:09', '2021-04-19 17:57:09', '0.00', '0.00', '0.00', NULL, '0.00'),
(584, 6, 1, '50.00', '150.00', 175, 2, '2021-04-19 18:01:22', '2021-04-19 18:01:22', '0.00', '0.00', '0.00', NULL, '0.00'),
(585, 6, 1, '50.00', '150.00', 175, 0, '2021-04-19 18:01:51', '2021-04-19 18:01:51', '654.00', '166.00', '4920.00', NULL, '0.00'),
(586, 6, 1, '50.00', '150.00', 176, 0, '2021-04-19 18:04:45', '2021-04-19 18:04:45', '0.00', '0.00', '0.00', NULL, '0.00'),
(587, 6, 1, '50.00', '150.00', 177, 0, '2021-04-19 18:05:51', '2021-04-19 18:05:51', '0.00', '0.00', '0.00', NULL, '0.00'),
(588, 6, 1, '50.00', '150.00', 178, 0, '2021-04-19 18:07:11', '2021-04-19 18:07:11', '0.00', '0.00', '0.00', NULL, '0.00'),
(589, 6, 1, '50.00', '150.00', 179, 0, '2021-04-19 18:08:33', '2021-04-19 18:08:33', '0.00', '0.00', '0.00', NULL, '0.00'),
(590, 6, 1, '50.00', '150.00', 180, 2, '2021-04-19 18:13:28', '2021-04-19 18:13:28', '0.00', '0.00', '0.00', NULL, '0.00'),
(591, 6, 1, '50.00', '150.00', 180, 2, '2021-04-19 18:14:59', '2021-04-19 18:14:59', '456.00', '1258.00', '10284.00', NULL, '0.00'),
(592, 6, 1, '50.00', '150.00', 180, 0, '2021-04-19 18:15:52', '2021-04-19 18:15:52', '456.00', '1258.00', '10284.00', NULL, '0.00'),
(593, 6, 1, '50.00', '150.00', 181, 0, '2021-04-19 18:17:14', '2021-04-19 18:17:14', '0.00', '0.00', '0.00', NULL, '0.00'),
(594, 6, 1, '50.00', '150.00', 182, 2, '2021-04-19 18:40:20', '2021-04-19 18:40:20', '0.00', '0.00', '0.00', NULL, '0.00'),
(595, 6, 1, '50.00', '150.00', 182, 2, '2021-04-19 18:41:05', '2021-04-19 18:41:05', '498.00', '12.00', '3060.00', NULL, '0.00'),
(596, 6, 1, '50.00', '150.00', 182, 0, '2021-04-19 18:44:30', '2021-04-19 18:44:30', '120.00', '20.00', '840.00', NULL, '0.00'),
(597, 6, 1, '50.00', '150.00', 183, 2, '2021-04-19 18:52:08', '2021-04-19 18:52:08', '0.00', '0.00', '0.00', NULL, '0.00'),
(598, 6, 1, '50.00', '150.00', 183, 0, '2021-04-19 18:52:32', '2021-04-19 18:52:32', '1000.00', '5000.00', '36000.00', NULL, '0.00'),
(599, 6, 1, '50.00', '150.00', 184, 2, '2021-04-20 12:49:38', '2021-04-20 12:49:38', '0.00', '0.00', '0.00', NULL, '0.00'),
(600, 6, 1, '50.00', '150.00', 184, 0, '2021-04-20 12:50:37', '2021-04-20 12:50:37', '263.00', '125.00', '2328.00', NULL, '0.00'),
(601, 6, 1, '50.00', '150.00', 185, 2, '2021-04-20 12:59:06', '2021-04-20 12:59:06', '0.00', '0.00', '0.00', NULL, '0.00'),
(602, 6, 1, '50.00', '150.00', 185, 0, '2021-04-20 12:59:33', '2021-04-20 12:59:33', '125.00', '203.00', '1968.00', NULL, '0.00'),
(603, 6, 1, '125.00', '150.00', 190, 2, '2021-04-20 14:48:05', '2021-04-20 14:48:05', '0.00', '0.00', '0.00', NULL, '0.00'),
(604, 6, 1, '125.00', '150.00', 190, 0, '2021-04-20 14:48:30', '2021-04-20 14:48:30', '250.00', '236.00', '2916.00', NULL, '0.00'),
(605, 6, 1, '125.00', '150.00', 191, 2, '2021-04-20 17:39:24', '2021-04-20 17:39:24', '0.00', '0.00', '0.00', NULL, '0.00'),
(606, 6, 1, '125.00', '150.00', 191, 0, '2021-04-20 17:40:03', '2021-04-20 17:40:03', '150.00', '20.00', '1020.00', NULL, '0.00'),
(607, 6, 1, '125.00', '150.00', 192, 2, '2021-04-20 17:58:07', '2021-04-20 17:58:07', '0.00', '0.00', '0.00', NULL, '0.00'),
(608, 2, 1, '15.00', '30.00', 192, 0, '2021-04-20 18:00:16', '2021-04-20 18:00:16', '450.00', '260.00', '1420.00', NULL, '0.00'),
(609, 16, 19, '1.00', '16.00', 192, 0, '2021-04-20 18:00:16', '2021-04-20 18:00:16', '120.00', '150.00', '4320.00', NULL, '13.00'),
(610, 6, 1, '125.00', '150.00', 193, 0, '2021-04-22 12:09:17', '2021-04-22 12:09:17', '0.00', '0.00', '0.00', NULL, '0.00'),
(611, 6, 1, '125.00', '150.00', 194, 0, '2021-04-22 12:13:05', '2021-04-22 12:13:05', '0.00', '0.00', '0.00', NULL, '0.00'),
(612, 6, 1, '125.00', '150.00', 195, 2, '2021-04-22 12:27:54', '2021-04-22 12:27:54', '0.00', '0.00', '0.00', NULL, '0.00'),
(613, 3, 1, '125.00', '375.00', 195, 0, '2021-04-22 12:28:52', '2021-04-22 12:28:52', '203.00', '106.00', '927.00', NULL, '0.00'),
(614, 6, 1, '125.00', '150.00', 196, 0, '2021-04-23 11:12:17', '2021-04-23 11:12:17', '0.00', '0.00', '0.00', NULL, '0.00'),
(615, 6, 1, '125.00', '150.00', 197, 2, '2021-04-23 11:41:43', '2021-04-23 11:41:43', '0.00', '0.00', '0.00', NULL, '0.00'),
(616, 6, 1, '125.00', '150.00', 197, 0, '2021-04-23 11:42:03', '2021-04-23 11:42:03', '166.00', '32.00', '1188.00', NULL, '0.00'),
(617, 6, 1, '125.00', '150.00', 198, 0, '2021-04-23 17:04:51', '2021-04-23 17:04:51', '166.00', '32.00', '1188.00', NULL, '0.00'),
(618, 6, 1, '20.00', '120.00', 199, 2, '2021-04-28 18:01:48', '2021-04-28 18:01:48', '120.00', '35.00', '930.00', NULL, '0.00'),
(619, 6, 1, '20.00', '120.00', 200, 0, '2021-04-28 18:05:31', '2021-04-28 18:05:31', '120.00', '35.00', '930.00', NULL, '0.00'),
(620, 6, 1, '125.00', '150.00', 201, 0, '2021-04-29 11:19:50', '2021-04-29 11:19:50', '0.00', '0.00', '0.00', NULL, '0.00'),
(621, 5, 18, '45.00', '45.00', 202, 0, '2021-04-29 16:18:42', '2021-04-29 16:18:42', '250.00', '256.00', '2530.00', NULL, '456.00'),
(622, 6, 1, '125.00', '150.00', 203, 0, '2021-04-29 16:20:10', '2021-04-29 16:20:10', '0.00', '0.00', '0.00', NULL, '0.00'),
(623, 6, 1, '125.00', '150.00', 204, 0, '2021-04-29 16:46:27', '2021-04-29 16:46:27', '120.00', '10.00', '780.00', NULL, '0.00'),
(624, 6, 1, '125.00', '150.00', 205, 0, '2021-04-30 11:00:54', '2021-04-30 11:00:54', '120.00', '10.00', '780.00', NULL, '0.00'),
(625, 6, 1, '125.00', '150.00', 206, 0, '2021-04-30 13:01:55', '2021-04-30 13:01:55', '120.00', '10.00', '780.00', NULL, '0.00'),
(626, 6, 1, '125.00', '150.00', 207, 0, '2021-04-30 13:01:55', '2021-04-30 13:01:55', '120.00', '10.00', '780.00', NULL, '0.00'),
(627, 6, 1, '20.00', '120.00', 199, 0, '2021-05-01 13:07:53', '2021-05-01 13:07:53', '120.00', '35.00', '930.00', NULL, '0.00'),
(628, 16, 18, '45.00', '720.00', 208, 0, '2021-05-03 15:41:07', '2021-05-03 15:41:07', '250.00', '256.00', '8096.00', NULL, '456.00'),
(629, 6, 1, '125.00', '150.00', 209, 0, '2021-05-03 15:53:37', '2021-05-03 15:53:37', '120.00', '10.00', '780.00', NULL, '0.00'),
(630, 8, 32, '6.25', '50.00', 210, 0, '2021-05-03 15:54:31', '2021-05-03 15:54:31', '8.00', '0.00', '64.00', NULL, '800.00'),
(631, 17, 8, '2.94', '50.00', 210, 0, '2021-05-03 15:54:31', '2021-05-03 15:54:31', '8.00', '0.00', '136.00', 'Empty Carton', '200.00'),
(632, 8, 32, '30.00', '240.00', 211, 0, '2021-05-03 15:55:43', '2021-05-03 15:55:43', '60.00', '30.00', '720.00', NULL, '0.00'),
(633, 36, 34, '1.00', '36.00', 211, 0, '2021-05-03 15:55:43', '2021-05-03 15:55:43', '7.80', '0.00', '280.80', NULL, '0.00'),
(634, 6, 1, '125.00', '150.00', 212, 0, '2021-05-03 17:26:44', '2021-05-03 17:26:44', '0.00', '0.00', '0.00', NULL, '0.00'),
(635, 6, 1, '125.00', '150.00', 213, 0, '2021-05-03 17:33:04', '2021-05-03 17:33:04', '0.00', '0.00', '0.00', NULL, '0.00'),
(636, 11, 8, '12.25', '564.23', 106, 0, '2021-05-06 12:57:41', '2021-05-06 12:57:41', '56.00', '0.00', '616.00', 'EmailBox', '0.00'),
(637, 11, 8, '12.25', '564.23', 108, 2, '2021-05-06 12:58:47', '2021-05-06 12:58:47', '23.00', '0.00', '253.00', 'EmailBox', '0.00'),
(638, 25, 2, '25.60', '56.00', 108, 2, '2021-05-06 12:58:47', '2021-05-06 12:58:47', '85.00', '0.00', '2125.00', NULL, '0.00'),
(639, 11, 8, '12.25', '564.23', 108, 0, '2021-05-06 12:58:48', '2021-05-06 12:58:48', '23.00', '0.00', '253.00', 'EmailBox', '0.00'),
(640, 25, 2, '25.60', '56.00', 108, 0, '2021-05-06 12:58:48', '2021-05-06 12:58:48', '85.00', '0.00', '2125.00', NULL, '0.00'),
(641, 11, 8, '12.25', '564.23', 214, 0, '2021-05-06 14:23:34', '2021-05-06 14:23:34', '260.00', '0.00', '2860.00', 'EmailBox', '0.00'),
(642, 10, 8, '12.25', '122.50', 215, 0, '2021-05-06 14:24:18', '2021-05-06 14:24:18', '260.00', '0.00', '2600.00', 'EmailBox', '0.00'),
(643, 10, 8, '12.25', '122.50', 216, 0, '2021-05-06 14:46:02', '2021-05-06 14:46:02', '260.00', '0.00', '2600.00', 'EmailBox', '0.00'),
(644, 23, 8, '12.25', '281.75', 217, 0, '2021-05-06 14:46:35', '2021-05-06 14:46:35', '26.00', '13.00', '897.00', 'EmailBox', '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_otp`
--

CREATE TABLE `tbl_otp` (
  `id` bigint(20) NOT NULL,
  `otp` varchar(10) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `for_whom` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1-customer, 2- driver	',
  `for_what` varchar(2) NOT NULL DEFAULT 'L' COMMENT 'L-Login,R-Signup,F-forgot-password',
  `is_active` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-active, 1-deactive',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `device_type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1-web, 2-android, 3-ios	'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_otp`
--

INSERT INTO `tbl_otp` (`id`, `otp`, `user_id`, `for_whom`, `for_what`, `is_active`, `created_at`, `updated_at`, `device_type`) VALUES
(1, '1111', 2, 1, 'L', 1, '2021-04-30 18:26:08', '2021-04-30 18:26:30', 2),
(2, '1111', 2, 1, 'L', 1, '2021-04-30 18:26:09', '2021-04-30 18:26:30', 2),
(3, '1111', 2, 1, 'L', 1, '2021-04-30 18:26:11', '2021-04-30 18:26:30', 2),
(4, '1111', 2, 1, 'L', 1, '2021-04-30 18:26:34', '2021-04-30 18:26:56', 2),
(5, '1111', 2, 1, 'L', 1, '2021-04-30 18:26:40', '2021-04-30 18:26:56', 2),
(6, '1111', 2, 1, 'L', 1, '2021-04-30 18:26:41', '2021-04-30 18:26:56', 2),
(7, '1111', 2, 1, 'L', 1, '2021-04-30 18:27:00', '2021-04-30 18:27:16', 2),
(8, '1111', 2, 1, 'L', 1, '2021-04-30 18:27:02', '2021-04-30 18:27:16', 2),
(9, '1111', 2, 1, 'L', 1, '2021-04-30 18:27:03', '2021-04-30 18:27:16', 2),
(10, '1111', 2, 1, 'L', 1, '2021-04-30 18:27:22', '2021-05-03 10:44:37', 2),
(11, '1111', 6, 2, 'L', 1, '2021-05-01 12:57:53', '2021-05-01 13:01:15', 2),
(12, '1111', 2, 1, 'L', 1, '2021-05-03 10:44:18', '2021-05-03 10:44:37', 2),
(13, '1111', 71, 1, 'F', 1, '2021-05-05 11:18:09', '2021-05-05 11:19:32', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_reasonfor`
--

CREATE TABLE `tbl_reasonfor` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `details` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'for what',
  `is_active` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-active,1-deactive, 2- deleted',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_reasonfor`
--

INSERT INTO `tbl_reasonfor` (`id`, `name`, `details`, `type`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Shop Closed', 'The Delivered Address is Closed.', 'undelivered_reason', 0, '2021-03-03 18:26:19', '2021-03-03 18:26:19'),
(2, 'Payment Pending', 'Payment Pending.', 'undelivered_reason', 0, '2021-03-03 18:26:19', '2021-03-03 18:26:19'),
(3, 'Order Amount Costly', 'Order Amount is Very Costly.', 'ordercancelled_reason', 0, '2021-04-29 18:26:19', '2021-04-29 18:26:19'),
(4, 'Others', 'Others', 'ordercancelled_reason', 0, '2021-04-29 18:26:19', '2021-04-29 18:26:19'),
(5, 'Order Confirm Confusion', 'Having Confusion in Order Confirm', 'ordercancelled_reason', 0, '2021-04-29 18:26:19', '2021-04-29 18:26:19');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_reviews`
--

CREATE TABLE `tbl_reviews` (
  `id` bigint(20) NOT NULL,
  `driver_star` varchar(2) NOT NULL DEFAULT '5' COMMENT 'to driver review star',
  `bigdaddy_service_star` varchar(2) NOT NULL DEFAULT '5',
  `order_id` int(11) NOT NULL DEFAULT 0,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-active,1-deactive',
  `subject` varchar(255) DEFAULT NULL COMMENT 'headline',
  `message` text DEFAULT NULL COMMENT 'review',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_reviews`
--

INSERT INTO `tbl_reviews` (`id`, `driver_star`, `bigdaddy_service_star`, `order_id`, `user_id`, `is_active`, `subject`, `message`, `created_at`, `updated_at`) VALUES
(1, '4', '3', 107, 1, 0, 'gfg jhjh', 'hfgjh jhghj jhfjh', '2021-04-08 15:20:28', '2021-04-08 15:20:28'),
(2, '5', '4', 169, 122, 0, 'hvhjf ', 'kghkg hmh', '2021-04-20 11:56:13', '2021-04-20 11:56:13');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_role_management`
--

CREATE TABLE `tbl_role_management` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `main_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'routes ',
  `details` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-active,1-deactive, 2- deleted',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `level` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-for single and 1- for multiple',
  `path_to` int(11) NOT NULL DEFAULT 0 COMMENT '0 or id',
  `any_svg` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `any_html` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `any_order` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `remove_class` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `has_submenu` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-yes, 1-single',
  `one_url` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_role_management`
--

INSERT INTO `tbl_role_management` (`id`, `name`, `main_url`, `details`, `is_active`, `created_at`, `updated_at`, `level`, `path_to`, `any_svg`, `any_html`, `any_order`, `remove_class`, `has_submenu`, `one_url`) VALUES
(1, 'Order', NULL, NULL, 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 0, 0, '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"15.713\" height=\"20\" viewBox=\"0 0 15.713 20\"> <g id=\"clipboard\" transform=\"translate(-47.037)\"> <g id=\"Group_3440\" data-name=\"Group 3440\" transform=\"translate(47.037 0)\"> <g id=\"Group_3439\" data-name=\"Group 3439\" transform=\"translate(0 0)\"> <path id=\"Path_10847\" data-name=\"Path 10847\" d=\"M60.75,2.621H58.941V1.811c0-.262-.286-.381-.548-.381h-1.4A2.13,2.13,0,0,0,54.87,0,2.166,2.166,0,0,0,52.751,1.43H51.37c-.262,0-.524.119-.524.381v.809H49.037a2.024,2.024,0,0,0-2,1.928V18.191a1.915,1.915,0,0,0,2,1.809H60.75a1.915,1.915,0,0,0,2-1.809V4.549A2.024,2.024,0,0,0,60.75,2.621ZM51.8,2.382h1.309a.524.524,0,0,0,.452-.429A1.381,1.381,0,0,1,54.87.883a1.357,1.357,0,0,1,1.286,1.071.524.524,0,0,0,.476.429h1.357v1.9H51.8Zm10,15.808a.966.966,0,0,1-1.048.857H49.037a.966.966,0,0,1-1.048-.857V4.549a1.071,1.071,0,0,1,1.048-.976h1.809V4.787a.5.5,0,0,0,.524.452h7.023a.524.524,0,0,0,.548-.452V3.573H60.75a1.071,1.071,0,0,1,1.048.976V18.191Z\" transform=\"translate(-47.037 0)\"/> <path id=\"Path_10848\" data-name=\"Path 10848\" d=\"M103.019,230.46a.476.476,0,0,0-.667-.024l-1.524,1.452-.643-.667a.476.476,0,0,0-.667-.024.5.5,0,0,0,0,.69l.976,1a.428.428,0,0,0,.333.143.476.476,0,0,0,.333-.143l1.857-1.762a.452.452,0,0,0,.027-.639Z\" transform=\"translate(-96.995 -219.816)\"/> <path id=\"Path_10849\" data-name=\"Path 10849\" d=\"M204.5,256.034h-5.476a.476.476,0,0,0,0,.952H204.5a.476.476,0,0,0,0-.952Z\" transform=\"translate(-191.644 -244.367)\"/> <path id=\"Path_10850\" data-name=\"Path 10850\" d=\"M103.019,146.868a.476.476,0,0,0-.667-.024l-1.524,1.452-.643-.667a.476.476,0,0,0-.667-.024.5.5,0,0,0,0,.69l.976,1a.428.428,0,0,0,.333.143.476.476,0,0,0,.333-.143l1.857-1.762a.452.452,0,0,0,.027-.639Z\" transform=\"translate(-96.995 -140.034)\"/> <path id=\"Path_10851\" data-name=\"Path 10851\" d=\"M204.5,172.442h-5.476a.476.476,0,0,0,0,.952H204.5a.476.476,0,0,0,0-.952Z\" transform=\"translate(-191.644 -164.584)\"/> <path id=\"Path_10852\" data-name=\"Path 10852\" d=\"M103.019,314.051a.476.476,0,0,0-.667-.024l-1.524,1.452-.643-.667a.476.476,0,0,0-.667-.024.5.5,0,0,0,0,.69l.976,1a.428.428,0,0,0,.333.143.476.476,0,0,0,.333-.143l1.857-1.762a.452.452,0,0,0,.027-.639Z\" transform=\"translate(-96.995 -299.598)\"/> <path id=\"Path_10853\" data-name=\"Path 10853\" d=\"M204.5,339.626h-5.476a.476.476,0,1,0,0,.952H204.5a.476.476,0,1,0,0-.952Z\" transform=\"translate(-191.644 -324.149)\"/> </g> </g> </g> </svg>', NULL, 'AC', NULL, 0, NULL),
(2, 'Add Order', 'order-add', NULL, 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 1, 1, NULL, NULL, '1', NULL, 0, NULL),
(3, 'To Be Assigned', 'tobeassigned-orders', NULL, 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 1, 1, NULL, NULL, '3', NULL, 0, NULL),
(4, 'Orders', 'order-list', NULL, 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 1, 1, NULL, NULL, '2', NULL, 0, 'view-order,edit-order'),
(5, 'Assigned', 'assigned-orders', NULL, 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 1, 1, NULL, NULL, '4', NULL, 0, NULL),
(6, 'Delivered Orders', 'delivered-orders', NULL, 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 1, 1, NULL, NULL, '5', NULL, 0, NULL),
(7, 'Cancelled Orders', 'cancelled-orders', NULL, 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 1, 1, NULL, NULL, '6', NULL, 0, NULL),
(8, 'Customer', NULL, NULL, 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 0, 0, '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"20.001\" height=\"20\" viewBox=\"0 0 20.001 20\"> <g id=\"customer\" transform=\"translate(0 -0.013)\"> <g id=\"Group_3436\" data-name=\"Group 3436\" transform=\"translate(0 9.23)\"> <g id=\"Group_3435\" data-name=\"Group 3435\" transform=\"translate(0)\"> <path id=\"Path_10845\" data-name=\"Path 10845\" d=\"M19.375,236.609a1.757,1.757,0,0,0-2.419.146L15.234,238.6a1.761,1.761,0,0,0-1.679-1.237H10.481c-.233,0-.311-.089-.716-.4a4.11,4.11,0,0,0-5.4.013l-1.13.994a1.751,1.751,0,0,0-1.611-.02l-1.3.648a.586.586,0,0,0-.262.786l3.516,7.031a.586.586,0,0,0,.786.262l1.3-.648a1.756,1.756,0,0,0,.971-1.634h6.924a4.121,4.121,0,0,0,3.281-1.641l2.813-3.751A1.753,1.753,0,0,0,19.375,236.609Zm-14.24,8.375-.771.386-2.992-5.983L2.144,239a.586.586,0,0,1,.786.262L5.4,244.2A.586.586,0,0,1,5.135,244.984Zm13.576-6.68L15.9,242.054a2.943,2.943,0,0,1-2.344,1.172H6.222l-2.2-4.392,1.109-.976a2.937,2.937,0,0,1,3.871,0,2.074,2.074,0,0,0,1.475.68h3.073a.586.586,0,0,1,0,1.172H10.562a.586.586,0,1,0,0,1.172h3.385a1.763,1.763,0,0,0,1.286-.56l2.58-2.769a.586.586,0,0,1,.9.75Z\" transform=\"translate(0 -235.959)\"/> </g> </g> <g id=\"Group_3438\" data-name=\"Group 3438\" transform=\"translate(7.07 0.013)\"> <g id=\"Group_3437\" data-name=\"Group 3437\" transform=\"translate(0 0)\"> <path id=\"Path_10846\" data-name=\"Path 10846\" d=\"M186.244,4.011a2.334,2.334,0,0,0,.648-1.616A2.376,2.376,0,0,0,184.549.013,2.414,2.414,0,0,0,182.166,2.4a2.3,2.3,0,0,0,.67,1.62,3.575,3.575,0,0,0-1.842,3.106v.586a.586.586,0,0,0,.586.586h5.9a.586.586,0,0,0,.586-.586V7.122A3.572,3.572,0,0,0,186.244,4.011Zm-1.7-2.827A1.208,1.208,0,0,1,185.72,2.4a1.173,1.173,0,0,1-1.172,1.172A1.208,1.208,0,0,1,183.338,2.4,1.243,1.243,0,0,1,184.549,1.185Zm-2.383,5.937a2.414,2.414,0,0,1,2.383-2.383,2.366,2.366,0,0,1,2.344,2.383Z\" transform=\"translate(-180.994 -0.013)\"/> </g> </g> </g> </svg>', NULL, 'AD', NULL, 0, NULL),
(9, 'Customer', 'customer-list', NULL, 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 1, 8, NULL, NULL, '1', NULL, 0, 'view-customer'),
(10, 'Add Customer', 'customer-add', NULL, 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 1, 8, NULL, NULL, '2', NULL, 0, NULL),
(11, 'Driver', NULL, NULL, 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 0, 0, '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"20.3\" height=\"20.3\" viewBox=\"0 0 20.3 20.3\"> <g id=\"chauffer\" transform=\"translate(0.15 0.15)\"> <path id=\"Path_10854\" data-name=\"Path 10854\" d=\"M10,0A10,10,0,1,0,20,10,10.011,10.011,0,0,0,10,0Zm0,19.412A9.412,9.412,0,1,1,19.412,10,9.422,9.422,0,0,1,10,19.412Z\" stroke=\"#000\" stroke-width=\"0.3\"/> <path id=\"Path_10855\" data-name=\"Path 10855\" d=\"M157.383,154.85a2.533,2.533,0,1,0-2.533,2.533A2.536,2.536,0,0,0,157.383,154.85ZM154.85,156.8a1.945,1.945,0,1,1,1.945-1.945A1.947,1.947,0,0,1,154.85,156.8Z\" transform=\"translate(-144.85 -144.85)\" stroke=\"#000\" stroke-width=\"0.3\"/> <path id=\"Path_10856\" data-name=\"Path 10856\" d=\"M223.46,237.283h0a5.773,5.773,0,0,0-7.229,6.229h0a.294.294,0,0,0,.292.261l.034,0a8.367,8.367,0,0,0,7.111-6.127A.294.294,0,0,0,223.46,237.283Zm-2.775,4.055a7.72,7.72,0,0,1-3.9,1.808,5.184,5.184,0,0,1,6.225-5.363A7.744,7.744,0,0,1,220.685,241.338Z\" transform=\"translate(-205.595 -225.466)\" stroke=\"#000\" stroke-width=\"0.3\"/> <path id=\"Path_10857\" data-name=\"Path 10857\" d=\"M39.327,237.281h0a.294.294,0,0,0-.208.36,8.368,8.368,0,0,0,7.111,6.127l.034,0a.294.294,0,0,0,.292-.261h0a5.773,5.773,0,0,0-7.229-6.229ZM46,243.144a7.781,7.781,0,0,1-6.225-5.363A5.184,5.184,0,0,1,46,243.144Z\" transform=\"translate(-37.191 -225.464)\" stroke=\"#000\" stroke-width=\"0.3\"/> <path id=\"Path_10858\" data-name=\"Path 10858\" d=\"M47.717,35.878a8.359,8.359,0,0,0-14.229,5.829.294.294,0,0,0,.521.191A10.154,10.154,0,0,1,41.847,38.5,10.154,10.154,0,0,1,49.684,41.9a.294.294,0,0,0,.521-.191A8.307,8.307,0,0,0,47.717,35.878Zm-5.871,2.037a10.982,10.982,0,0,0-7.715,2.978,7.772,7.772,0,0,1,15.431,0A10.983,10.983,0,0,0,41.847,37.915Z\" transform=\"translate(-31.847 -31.828)\" stroke=\"#000\" stroke-width=\"0.3\"/> </g> </svg>', NULL, 'AE', NULL, 0, NULL),
(12, 'Driver', 'driver-list', NULL, 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 1, 11, NULL, NULL, '1', NULL, 0, 'view-driver'),
(13, 'Add Driver', 'driver-add', NULL, 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 1, 11, NULL, NULL, '2', NULL, 0, NULL),
(14, 'Admins', NULL, NULL, 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 0, 0, '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"18.638\" height=\"20\" viewBox=\"0 0 18.638 20\"> <g id=\"user_2_\" data-name=\"user (2)\" transform=\"translate(-17.44)\"> <g id=\"Group_3615\" data-name=\"Group 3615\" transform=\"translate(17.44 11.265)\"> <g id=\"Group_3614\" data-name=\"Group 3614\"> <path id=\"Path_11614\" data-name=\"Path 11614\" d=\"M26.759,288.389c-6.009,0-9.319,2.843-9.319,8a.73.73,0,0,0,.73.73H35.348a.73.73,0,0,0,.73-.73C36.078,291.232,32.768,288.389,26.759,288.389Zm-7.832,7.275c.287-3.86,2.918-5.815,7.832-5.815s7.545,1.955,7.833,5.815Z\" transform=\"translate(-17.44 -288.389)\"/> </g> </g> <g id=\"Group_3617\" data-name=\"Group 3617\" transform=\"translate(21.917)\"> <g id=\"Group_3616\" data-name=\"Group 3616\"> <path id=\"Path_11615\" data-name=\"Path 11615\" d=\"M136.891,0a4.784,4.784,0,0,0-4.842,4.939,4.858,4.858,0,1,0,9.684,0A4.784,4.784,0,0,0,136.891,0Zm0,8.735a3.611,3.611,0,0,1-3.382-3.8,3.319,3.319,0,0,1,3.382-3.479,3.356,3.356,0,0,1,3.382,3.479A3.611,3.611,0,0,1,136.891,8.735Z\" transform=\"translate(-132.049)\"/> </g> </g> </g> </svg>', NULL, 'AF', NULL, 0, NULL),
(15, 'List', 'admins', NULL, 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 1, 14, NULL, NULL, '1', NULL, 0, 'view-admin'),
(16, 'Dashboard', 'dashboard', NULL, 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 0, 0, '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"19.998\" height=\"20\" viewBox=\"0 0 19.998 20\"> <path id=\"home_2_\" data-name=\"home (2)\" d=\"M19.961,8.7l0,0L11.8.539a1.841,1.841,0,0,0-2.6,0L1.043,8.693,1.035,8.7A1.84,1.84,0,0,0,2.26,11.838l.057,0h.325v6A2.157,2.157,0,0,0,4.8,20H7.988a.586.586,0,0,0,.586-.586V14.706a.984.984,0,0,1,.983-.983H11.44a.984.984,0,0,1,.983.983v4.707a.586.586,0,0,0,.586.586H16.2a2.157,2.157,0,0,0,2.155-2.155v-6h.3a1.842,1.842,0,0,0,1.3-3.142Zm-.83,1.774a.665.665,0,0,1-.473.2H17.77a.586.586,0,0,0-.586.586v6.59a.984.984,0,0,1-.983.983H13.6V14.706a2.157,2.157,0,0,0-2.155-2.155H9.558A2.158,2.158,0,0,0,7.4,14.706v4.121H4.8a.984.984,0,0,1-.983-.983v-6.59a.586.586,0,0,0-.586-.586h-.9a.669.669,0,0,1-.461-1.142h0l8.158-8.158a.669.669,0,0,1,.946,0l8.156,8.156,0,0a.671.671,0,0,1,0,.946Zm0,0\" transform=\"translate(-0.5 0)\"/> </svg>', NULL, 'AA', NULL, 1, NULL),
(17, 'Dashboard', 'dashboard', NULL, 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 1, 16, NULL, NULL, '0', NULL, 0, NULL),
(18, 'Account old', NULL, NULL, 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 0, 0, '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"19.998\" height=\"20\" viewBox=\"0 0 19.998 20\"> <g id=\"expensive\" transform=\"translate(-0.02)\"> <g id=\"Group_3619\" data-name=\"Group 3619\" transform=\"translate(0.019 0)\"> <g id=\"Group_3618\" data-name=\"Group 3618\" transform=\"translate(0 0)\"> <path id=\"Path_11616\" data-name=\"Path 11616\" d=\"M63.006,313.186a5.835,5.835,0,0,1-1.534-1.106,5.723,5.723,0,0,1-.864-1.1.37.37,0,1,0-.642.37l.007.011a6.465,6.465,0,0,0,.975,1.242,6.568,6.568,0,0,0,1.729,1.246.37.37,0,0,0,.329-.664Z\" transform=\"translate(-57.144 -296.408)\"/> <path id=\"Path_11617\" data-name=\"Path 11617\" d=\"M138.239,152.187a1.481,1.481,0,0,1-1.481-1.481h-.741a2.224,2.224,0,0,0,1.852,2.189v.774h.741v-.774a2.222,2.222,0,0,0-.37-4.411A1.481,1.481,0,1,1,139.72,147h.741a2.224,2.224,0,0,0-1.852-2.189v-.774h-.741v.774a2.222,2.222,0,0,0,.37,4.411,1.481,1.481,0,1,1,0,2.963Z\" transform=\"translate(-129.721 -137.372)\"/> <path id=\"Path_11618\" data-name=\"Path 11618\" d=\"M320.557,72.313a.371.371,0,1,0-.472.573,7.865,7.865,0,0,1,1.07,1.071.37.37,0,1,0,.573-.47A8.618,8.618,0,0,0,320.557,72.313Z\" transform=\"translate(-305.139 -68.885)\"/> <path id=\"Path_11619\" data-name=\"Path 11619\" d=\"M41.995,105.951a6.666,6.666,0,0,0-1.7,6.555.37.37,0,0,0,.713-.2,5.923,5.923,0,0,1,9.881-5.821q.211.211.4.436a5.9,5.9,0,0,1-.2,7.735l-.017.017-.007.007-.013.013c-.05.057-.1.114-.162.172a5.925,5.925,0,0,1-5.337,1.621.37.37,0,1,0-.144.727,6.666,6.666,0,0,0,6.007-1.823c.058-.058.111-.116.165-.174l.027-.027a6.666,6.666,0,0,0-9.613-9.234Z\" transform=\"translate(-38.184 -99.189)\"/> <path id=\"Path_11620\" data-name=\"Path 11620\" d=\"M285.841,146.224Z\" transform=\"translate(-272.61 -139.455)\"/> <path id=\"Path_11621\" data-name=\"Path 11621\" d=\"M11.5,0A8.518,8.518,0,0,0,4.777,3.288,8.321,8.321,0,0,0,3.3,4.764,8.518,8.518,0,1,0,15.256,16.718a8.494,8.494,0,0,0,1.481-1.485A8.516,8.516,0,0,0,11.5,0Zm3.767,15.384A7.78,7.78,0,1,1,3.791,5.32a7.934,7.934,0,0,1,.844-.568A7.783,7.783,0,0,1,15.269,15.384Zm2.125-9.647a.37.37,0,0,0-.656.343l.016.027a7.769,7.769,0,0,1-.095,7.934A8.51,8.51,0,0,0,5.974,3.362a7.688,7.688,0,0,1,4.044-1.138,7.766,7.766,0,0,1,3.923,1.06.37.37,0,0,0,.373-.639A8.522,8.522,0,0,0,7.44,1.883a7.777,7.777,0,0,1,10.7,10.682A8.516,8.516,0,0,0,17.394,5.738Z\" transform=\"translate(-0.019 0)\"/> </g> </g> </g> </svg>', NULL, 'AL', NULL, 0, NULL),
(19, 'Details', 'account-list', NULL, 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 1, 18, NULL, NULL, '0', NULL, 0, NULL),
(20, 'Add Expenses', 'account-add', NULL, 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 1, 18, NULL, NULL, '2', NULL, 0, NULL),
(21, 'Category', 'account-category', NULL, 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 1, 18, NULL, NULL, '3', NULL, 0, NULL),
(22, 'Add', 'admins-add', NULL, 0, '2021-01-11 00:00:00', '2021-01-11 00:00:00', 1, 14, NULL, NULL, '2', NULL, 0, NULL),
(23, 'Assign', NULL, 'assign driver btn', 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 2, 12, NULL, NULL, '1', 'roleclass_assign_btn_driver', 0, NULL),
(24, 'View', NULL, 'view driver btn', 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 2, 12, NULL, NULL, '2', 'roleclass_view_btn_driver', 0, NULL),
(25, 'Active/Deactive', NULL, 'driver active / deactive change', 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 2, 12, NULL, NULL, '3', 'roleclass_status_btn_driver', 0, NULL),
(26, 'View', NULL, 'view driver tab', 0, '2021-01-12 00:00:00', '2021-01-12 00:00:00', 3, 24, NULL, NULL, '2', 'roleclass_view_liandultab_driver', 0, NULL),
(27, 'Images', NULL, 'view driver images document tab', 0, '2021-01-12 00:00:00', '2021-01-12 00:00:00', 3, 24, NULL, NULL, '3', 'roleclass_images_liandultab_driver', 0, NULL),
(28, 'View', NULL, 'view account details in popup', 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 2, 19, NULL, NULL, '1', 'roleclass_view_btn_accountdetails', 0, NULL),
(29, 'Delete', NULL, 'delete account details in popup', 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 2, 19, NULL, NULL, '2', 'roleclass_delete_btn_accountdetails', 0, NULL),
(30, 'Edit', NULL, 'edit account details in popup', 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 2, 19, NULL, NULL, '3', 'roleclass_edit_btn_accountdetails', 0, NULL),
(31, 'TodayReport', '', 'Today Report Count', 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 2, 17, NULL, NULL, '1', 'roleclass_view_todayeportsection_dashboard', 0, NULL),
(32, 'CustomerReport', NULL, 'All Customer Count Report', 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 2, 17, NULL, NULL, '1', 'roleclass_view_customereportsection_dashboard', 0, NULL),
(33, 'SummaryReport', NULL, 'All Summary Sales Report', 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 2, 17, NULL, NULL, '1', 'roleclass_view_summaryreportsection_dashboard', 0, NULL),
(34, 'Last7DaysReport', NULL, 'Last 7 Days Report', 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 2, 17, NULL, NULL, '1', 'roleclass_view_last7dayscountreportsection_dashboard', 0, NULL),
(35, 'RevenueMonthlyReport', NULL, 'This Year Monthly Report', 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 2, 17, NULL, NULL, '1', 'roleclass_view_thisyearmonthlyreportsection_dashboard', 0, NULL),
(38, 'Add Order', 'order-add', NULL, 0, '2021-01-13 00:00:00', '2021-01-13 00:00:00', 0, 0, '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"20\" height=\"20\" viewBox=\"0 0 20 20\"> <path id=\"Path_11621\" data-name=\"Path 11621\" d=\"M4.111,3H21.889A1.111,1.111,0,0,1,23,4.111V21.889A1.111,1.111,0,0,1,21.889,23H4.111A1.111,1.111,0,0,1,3,21.889V4.111A1.111,1.111,0,0,1,4.111,3ZM5.222,5.222V20.778H20.778V5.222Zm6.667,6.667V7.444h2.222v4.444h4.444v2.222H14.111v4.444H11.889V14.111H7.444V11.889Z\" transform=\"translate(-3 -3)\"/> </svg>', NULL, 'AB', NULL, 1, NULL),
(36, 'Active/Deactive', '', 'customer active / deactive change', 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 2, 9, NULL, NULL, '1', 'roleclass_status_btn_customer', 0, NULL),
(37, 'View', '', 'View Customer Details', 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 2, 9, NULL, NULL, '1', 'roleclass_view_btn_customer', 0, NULL),
(39, 'Custom Fields', NULL, NULL, 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 0, 0, '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"20.5\" height=\"20.5\" viewBox=\"0 0 20.5 20.5\"> <g id=\"files\" transform=\"translate(0.25 0.25)\"> <g id=\"Group_3729\" data-name=\"Group 3729\"> <g id=\"Group_3728\" data-name=\"Group 3728\"> <path id=\"Path_11622\" data-name=\"Path 11622\" d=\"M19.667,19.333H18.333V10a1,1,0,0,0-1-1V7a1,1,0,0,0-1-1V4a1,1,0,0,0-1-1V1a1,1,0,0,0-1-1H5.667a1,1,0,0,0-1,1V3a1,1,0,0,0-1,1V6a1,1,0,0,0-1,1V9a1,1,0,0,0-1,1v9.333H.333a.333.333,0,0,0,0,.667H19.667a.333.333,0,0,0,0-.667ZM5.333,1A.334.334,0,0,1,5.667.667h8.667A.334.334,0,0,1,14.667,1V3H9.633A1.667,1.667,0,0,0,6.367,3H5.333V1ZM8.939,3H7.061A.994.994,0,0,1,8.939,3ZM4.333,4a.334.334,0,0,1,.333-.333H15.333A.334.334,0,0,1,15.667,4V6H8.633A1.667,1.667,0,0,0,5.367,6H4.333V4ZM7.939,6H6.061A.994.994,0,0,1,7.939,6ZM3.333,7a.334.334,0,0,1,.333-.333H16.333A.334.334,0,0,1,16.667,7V9H7.633A1.667,1.667,0,0,0,4.367,9H3.333ZM6.939,9H5.061A.994.994,0,0,1,6.939,9ZM17.667,19.333H2.333V10a.334.334,0,0,1,.333-.333H17.333a.334.334,0,0,1,.333.333v9.333Z\" stroke=\"#000\" stroke-width=\"0.5\"/> <path id=\"Path_11623\" data-name=\"Path 11623\" d=\"M95.867,285.6a2,2,0,1,0-2-2A2,2,0,0,0,95.867,285.6Zm0-3.333a1.333,1.333,0,1,1-1.333,1.333A1.333,1.333,0,0,1,95.867,282.267Z\" transform=\"translate(-90.2 -270.6)\" stroke=\"#000\" stroke-width=\"0.5\"/> <path id=\"Path_11624\" data-name=\"Path 11624\" d=\"M229.2,281.6h-7a.333.333,0,0,0,0,.667h7a.333.333,0,1,0,0-.667Z\" transform=\"translate(-213.2 -270.6)\" stroke=\"#000\" stroke-width=\"0.5\"/> <path id=\"Path_11625\" data-name=\"Path 11625\" d=\"M229.2,324.267h-7a.333.333,0,1,0,0,.667h7a.333.333,0,0,0,0-.667Z\" transform=\"translate(-213.2 -311.6)\" stroke=\"#000\" stroke-width=\"0.5\"/> <path id=\"Path_11626\" data-name=\"Path 11626\" d=\"M229.2,366.933h-7a.333.333,0,0,0,0,.667h7a.333.333,0,0,0,0-.667Z\" transform=\"translate(-213.2 -352.6)\" stroke=\"#000\" stroke-width=\"0.5\"/> <path id=\"Path_11627\" data-name=\"Path 11627\" d=\"M106.2,409.6h-12a.333.333,0,1,0,0,.667h12a.333.333,0,1,0,0-.667Z\" transform=\"translate(-90.2 -393.6)\" stroke=\"#000\" stroke-width=\"0.5\"/> <path id=\"Path_11628\" data-name=\"Path 11628\" d=\"M106.2,452.267h-12a.333.333,0,1,0,0,.667h12a.333.333,0,1,0,0-.667Z\" transform=\"translate(-90.2 -434.6)\" stroke=\"#000\" stroke-width=\"0.5\"/> </g> </g> </g> </svg>', NULL, 'AH', NULL, 0, NULL),
(40, 'Goods Type', 'goods-type', NULL, 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 1, 39, NULL, NULL, '1', NULL, 0, NULL),
(41, 'Logs', NULL, 'view driver logs tab', 0, '2021-01-16 00:00:00', '2021-01-16 00:00:00', 3, 24, NULL, NULL, '6', 'roleclass_logs_liandultab_driver', 0, NULL),
(42, 'Logs', NULL, 'view customer logs tab', 0, '2021-01-16 00:00:00', '2021-01-16 00:00:00', 3, 37, NULL, NULL, '4', 'roleclass_logs_liandultab_customer', 0, NULL),
(43, 'View', NULL, 'view customer data tab', 0, '2021-01-16 00:00:00', '2021-01-16 00:00:00', 3, 37, NULL, NULL, '1', 'roleclass_view_liandultab_customer', 0, NULL),
(44, 'Order', NULL, 'view customer order tab', 0, '2021-01-16 00:00:00', '2021-01-16 00:00:00', 3, 37, NULL, NULL, '2', 'roleclass_order_liandultab_customer', 0, NULL),
(45, 'Address', NULL, 'view customer address tab', 0, '2021-01-16 00:00:00', '2021-01-16 00:00:00', 3, 37, NULL, NULL, '3', 'roleclass_addresses_liandultab_customer', 0, NULL),
(46, 'Undelivered Orders', 'undelivered-orders', NULL, 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 1, 1, NULL, NULL, '7', NULL, 0, NULL),
(47, 'Inquiry', NULL, NULL, 0, '2021-02-16 00:00:00', '2021-01-07 00:00:00', 0, 0, '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"15.751\" height=\"20\" viewBox=\"0 0 15.751 20\"> <g id=\"commentator\" transform=\"translate(-54.385)\"> <g id=\"Group_425\" data-name=\"Group 425\" transform=\"translate(54.385 0)\"> <path id=\"Path_171\" data-name=\"Path 171\" d=\"M224.622,115.118c.271,0,.544-.012.816-.03a.293.293,0,1,0-.038-.585,4.016,4.016,0,0,1-3.335-.832.293.293,0,0,0-.428.4A3.855,3.855,0,0,0,224.622,115.118Z\" transform=\"translate(-215.028 -109.141)\"/> <path id=\"Path_172\" data-name=\"Path 172\" d=\"M208.785,203.006a.293.293,0,0,0-.293.293v.155a.293.293,0,0,0,.586,0V203.3A.293.293,0,0,0,208.785,203.006Z\" transform=\"translate(-202.472 -195.076)\"/> <path id=\"Path_173\" data-name=\"Path 173\" d=\"M288.772,203.748a.293.293,0,0,0,.293-.293V203.3a.293.293,0,0,0-.586,0v.155A.293.293,0,0,0,288.772,203.748Z\" transform=\"translate(-279.335 -195.077)\"/> <path id=\"Path_174\" data-name=\"Path 174\" d=\"M70.128,16.4a2.655,2.655,0,0,0-2.073-2.438l-3.607-1.281v-.817a5.523,5.523,0,0,0,.56-.45h.571a1.767,1.767,0,0,0,1.831-1.9,1.616,1.616,0,0,0,1.184-1.556V7.313a1.612,1.612,0,0,0-.711-1.336V5.259a5.053,5.053,0,0,0-1.66-3.767A5.833,5.833,0,0,0,62.26,0a5.393,5.393,0,0,0-5.624,5.259v.718a1.612,1.612,0,0,0-.711,1.336v.647a1.616,1.616,0,0,0,1.614,1.614h.7A4.5,4.5,0,0,0,59.5,11.406a5.516,5.516,0,0,0,.573.461v.816l-2.338.83a.293.293,0,1,0,.2.552l.705-.25,1.639,2.3a.293.293,0,0,0,.206.121l.033,0a.293.293,0,0,0,.195-.074l.27-.241.515.65-.851,2.839h-2.73v-.743a.293.293,0,0,0-.586,0v.743H54.972l.006-3.011a2.144,2.144,0,0,1,1.684-1.887.293.293,0,0,0-.2-.552A2.655,2.655,0,0,0,54.392,16.4l-.007,3.305a.293.293,0,0,0,.293.294H69.843a.293.293,0,0,0,.293-.294Zm-4.817-2.79-1.352,1.9L62.715,14.4,64.21,13.22Zm-3.051.4-1.6-1.266v-.529a3.474,3.474,0,0,0,1.6.4h.031a3.49,3.49,0,0,0,1.571-.4v.529Zm3.319-3.184h-.015a3.811,3.811,0,0,0,.722-1.258h.532C66.741,10.408,66.326,10.831,65.579,10.831ZM67.455,6.4a1.028,1.028,0,0,1,.554.911v.647a1.029,1.029,0,0,1-1.027,1.028h-.637V6.5a2.8,2.8,0,0,0,.262-.211h.375a1.02,1.02,0,0,1,.468.113l0,0ZM58.176,8.987h-.637a1.029,1.029,0,0,1-1.028-1.028V7.313a1.029,1.029,0,0,1,1.028-1.027h.637Zm.17-3.287c.009-.164.025-.325.049-.482A3.651,3.651,0,0,1,60.039,2.6a4.211,4.211,0,0,1,3.4-.448.293.293,0,0,0,.164-.562,4.8,4.8,0,0,0-3.882.516A4.222,4.222,0,0,0,57.815,5.13c-.028.186-.046.377-.055.57h-.221a1.616,1.616,0,0,0-.317.031V5.259A4.814,4.814,0,0,1,62.26.586a5.248,5.248,0,0,1,3.568,1.338A4.474,4.474,0,0,1,67.3,5.259v.472a1.614,1.614,0,0,0-.316-.031h-.206a4.386,4.386,0,0,0-1.808-3.46.293.293,0,1,0-.339.478A3.772,3.772,0,0,1,66.2,5.869a4.011,4.011,0,0,1-2.9.771,3.851,3.851,0,0,1-2.869-1.383c-.213-.291-.4-.512-.657-.495s-.386.249-.521.495A.7.7,0,0,1,58.6,5.7ZM59.9,10.975a3.54,3.54,0,0,1-1.135-1.719V6.276a1.293,1.293,0,0,0,1-.738c.015-.027.037-.067.059-.106.033.04.076.095.131.171a4.428,4.428,0,0,0,3.3,1.622q.224.017.446.017a5.082,5.082,0,0,0,2.061-.412V9.256a3.362,3.362,0,0,1-.986,1.575H63.748a.293.293,0,0,0,0,.586h.321a3.207,3.207,0,0,1-1.805.617h-.007A3.642,3.642,0,0,1,59.9,10.975Zm.665,4.534-1.352-1.9,1.1-.391L61.8,14.4Zm.854.024.844-.753.845.753-.544.687-.6,0Zm.866,3.88H61.253l.782-2.608h.451l.781,2.607Zm4.913,0v-.743a.293.293,0,1,0-.586,0v.743H63.88l-.851-2.839.515-.65.27.241a.293.293,0,0,0,.195.074l.033,0a.293.293,0,0,0,.206-.121l1.639-2.3,1.974.7A2.144,2.144,0,0,1,69.543,16.4l.007,3.011Z\" transform=\"translate(-54.385 0)\"/> <path id=\"Path_175\" data-name=\"Path 175\" d=\"M237.394,258.744a.293.293,0,0,0-.279-.515,1.039,1.039,0,0,1-.695,0,.293.293,0,0,0-.287.511,1.45,1.45,0,0,0,.65.135A1.309,1.309,0,0,0,237.394,258.744Z\" transform=\"translate(-228.89 -248.107)\"/> </g> </g> </svg>', NULL, 'AI', NULL, 0, NULL),
(48, 'List', 'inquiry-list', NULL, 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 1, 47, NULL, NULL, '1', NULL, 0, NULL),
(49, 'LR Uploads', 'lr-upload-list', NULL, 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 1, 1, NULL, NULL, '8', NULL, 0, NULL),
(50, 'Company', 'company-detail', NULL, 0, '2021-02-17 00:00:00', '2021-01-07 00:00:00', 1, 39, NULL, NULL, '2', NULL, 0, NULL),
(51, 'Sales Executive', NULL, NULL, 0, '2021-02-16 00:00:00', '2021-01-07 00:00:00', 0, 0, '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"20\" height=\"20\" viewBox=\"0 0 20 20\"> <g id=\"Group_424\" data-name=\"Group 424\" transform=\"translate(-1948 -2300)\"> <path id=\"Path_154\" data-name=\"Path 154\" d=\"M138,184h-.667c0,.059-.127.167-.333.167s-.333-.108-.333-.167H136a1.017,1.017,0,0,0,2,0Z\" transform=\"translate(1817 2123)\"/> <rect id=\"Rectangle_77\" data-name=\"Rectangle 77\" width=\"0.667\" height=\"0.667\" transform=\"translate(1952.333 2304.667)\"/> <rect id=\"Rectangle_78\" data-name=\"Rectangle 78\" width=\"0.667\" height=\"0.667\" transform=\"translate(1955 2304.667)\"/> <path id=\"Path_155\" data-name=\"Path 155\" d=\"M35.333,30.667a4.672,4.672,0,0,1-4.667,4.667,4.721,4.721,0,0,1-.694-.051,2.646,2.646,0,0,0-.211-.719A4.006,4.006,0,1,0,27.42,33h-.8a4.667,4.667,0,1,1,8.668-2.956l.661-.088a5.331,5.331,0,0,0-9.328-2.766l-2.354-1.1-.533-1.6.394-.2A1.576,1.576,0,0,0,25,22.881v-.548h.5a1.168,1.168,0,0,0,1.167-1.167V20.5A1.168,1.168,0,0,0,25.5,19.333H25V17.167A1.168,1.168,0,0,0,23.833,16H20.25a1.585,1.585,0,0,0-1.583,1.583v.083h.667v-.083a.918.918,0,0,1,.917-.917h2.969L22.7,18.383a1.325,1.325,0,0,1-1.277.95h-2.01a.083.083,0,0,1-.083-.083v-.917h-.667v.917a.754.754,0,0,0,0,.083H18.5A1.168,1.168,0,0,0,17.333,20.5v.667A1.168,1.168,0,0,0,18.5,22.333H19v.561a1.575,1.575,0,0,0,.875,1.416l.384.192-.53,1.59-2.993,1.4A1.174,1.174,0,0,0,16,28.572v6.261A1.168,1.168,0,0,0,17.167,36h12.5a.332.332,0,0,0,.192-.061A5.337,5.337,0,0,0,36,30.667Zm-4.667-3.333a3.333,3.333,0,1,1-3.333,3.333A3.333,3.333,0,0,1,30.667,27.333ZM25.5,20a.5.5,0,0,1,.5.5v.667a.5.5,0,0,1-.5.5H25V20Zm-1.167-2.833v1.5H23.312q.016-.045.03-.092l.57-1.9A.5.5,0,0,1,24.333,17.167Zm-5.833,4.5a.5.5,0,0,1-.5-.5V20.5a.5.5,0,0,1,.5-.5H19v1.667Zm1.167,1.227V20h1.76a1.989,1.989,0,0,0,1.494-.667h1.412v3.548a.912.912,0,0,1-.5.818l-1.607.814a.5.5,0,0,1-.449,0l-1.6-.8A.912.912,0,0,1,19.667,22.894Zm1.809,2.217a1.172,1.172,0,0,0,1.049,0l.612-.31.5,1.5-.135.224a1.75,1.75,0,0,1-3,0l-.135-.224.5-1.49ZM17.167,35.333a.5.5,0,0,1-.5-.5V28.572a.5.5,0,0,1,.32-.467l.021-.009,2.86-1.334.059.1a2.417,2.417,0,0,0,4.145,0l.059-.1,2.083.971A5.332,5.332,0,0,0,25.87,33H19v.667h4.667v1.667Zm7.167-1.667h1v1.667h-1ZM26,35.333V33.667h1.333a2,2,0,0,1,1.972,1.667Z\" transform=\"translate(1932 2284)\"/> <path id=\"Path_156\" data-name=\"Path 156\" d=\"M329.333,309.333H330v-.667h.167a1.167,1.167,0,0,0,0-2.333h-1a.5.5,0,0,1,0-1h2.167v-.667H330V304h-.667v.667h-.167a1.167,1.167,0,0,0,0,2.333h1a.5.5,0,0,1,0,1H328v.667h1.333Z\" transform=\"translate(1633 2008)\"/> </g> </svg>', NULL, 'AJ', NULL, 0, NULL),
(52, 'Customers', 'se-customer-list', NULL, 0, '2021-02-18 00:00:00', '2021-01-07 00:00:00', 1, 51, NULL, NULL, '1', NULL, 0, NULL),
(53, 'To Be Approved', 'tobeapproved-orders', NULL, 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 1, 1, NULL, NULL, '9', NULL, 0, NULL),
(54, 'Vehicle', 'vehicle-list', NULL, 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 1, 11, NULL, NULL, '3', NULL, 0, 'add-vehicle'),
(55, 'List', 'sales-executive-list', NULL, 0, '2021-02-18 00:00:00', '2021-01-07 00:00:00', 1, 51, NULL, NULL, '2', NULL, 0, NULL),
(56, 'Feedback', 'feedback-list', 'feedback by customers', 0, '2021-02-26 00:00:00', '2021-01-07 00:00:00', 1, 39, NULL, NULL, '3', NULL, 0, NULL),
(58, 'List', 'invoice-list', NULL, 0, '2021-03-09 00:00:00', '2021-03-09 00:00:00', 1, 57, NULL, NULL, '1', NULL, 0, NULL),
(57, 'Invoice', NULL, NULL, 0, '2021-03-09 00:00:00', '2021-03-09 00:00:00', 0, 0, '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"22\" height=\"22\" viewBox=\"0 0 22 22\"> <g id=\"Group_5312\" data-name=\"Group 5312\" transform=\"translate(-9951 -13787)\"> <g id=\"invoice_7_\" data-name=\"invoice (7)\" transform=\"translate(9903.472 13788)\"> <g id=\"Group_5305\" data-name=\"Group 5305\" transform=\"translate(50.529)\"> <g id=\"Group_5304\" data-name=\"Group 5304\" transform=\"translate(0)\"> <path id=\"Path_15635\" data-name=\"Path 15635\" d=\"M65.249.517h0a.922.922,0,0,0-1,.152l-1.3,1.151L61.177.239a.935.935,0,0,0-1.248,0l-1.767,1.58L56.395.239a.936.936,0,0,0-1.249,0L53.377,1.821,52.076.67a.93.93,0,0,0-1.548.7V18.629a.931.931,0,0,0,1.55.7l1.3-1.151,1.768,1.582a.935.935,0,0,0,1.248,0l1.767-1.581,1.767,1.58a.936.936,0,0,0,1.249,0l1.769-1.582,1.3,1.151a.93.93,0,0,0,1.548-.7V1.371A.922.922,0,0,0,65.249.517ZM63.565,17.478a.935.935,0,0,0-1.244,0l-1.768,1.582-1.767-1.58a.936.936,0,0,0-1.249,0l-1.767,1.58L54,17.481a.93.93,0,0,0-1.24,0l-1.3,1.151v-2.5l0-14.76,1.3,1.151a.935.935,0,0,0,1.244,0L55.771.937l1.767,1.58a.936.936,0,0,0,1.249,0L60.554.937l1.769,1.582a.93.93,0,0,0,1.24,0l1.3-1.151V13.234l0,5.394Z\" transform=\"translate(-50.529)\"/> </g> </g> <g id=\"Group_5307\" data-name=\"Group 5307\" transform=\"translate(53.255 9.532)\"> <g id=\"Group_5306\" data-name=\"Group 5306\" transform=\"translate(0)\"> <path id=\"Path_15636\" data-name=\"Path 15636\" d=\"M118.083,203.527H109.2a.468.468,0,0,0,0,.937h8.879a.468.468,0,0,0,0-.937Z\" transform=\"translate(-108.736 -203.527)\"/> </g> </g> <g id=\"Group_5309\" data-name=\"Group 5309\" transform=\"translate(53.255 6.722)\"> <g id=\"Group_5308\" data-name=\"Group 5308\" transform=\"translate(0)\"> <path id=\"Path_15637\" data-name=\"Path 15637\" d=\"M113.868,143.527H109.2a.468.468,0,1,0,0,.937h4.664a.468.468,0,0,0,0-.937Z\" transform=\"translate(-108.736 -143.527)\"/> </g> </g> <g id=\"Group_5311\" data-name=\"Group 5311\" transform=\"translate(53.255 12.342)\"> <g id=\"Group_5310\" data-name=\"Group 5310\" transform=\"translate(0)\"> <path id=\"Path_15638\" data-name=\"Path 15638\" d=\"M118.083,263.527H109.2a.468.468,0,0,0,0,.937h8.879a.468.468,0,0,0,0-.937Z\" transform=\"translate(-108.736 -263.527)\"/> </g> </g> </g> <g id=\"Rectangle_2680\" data-name=\"Rectangle 2680\" transform=\"translate(9952 13788)\" fill=\"none\" stroke=\"#707070\" stroke-width=\"1\" opacity=\"0\"> <rect width=\"20\" height=\"20\" stroke=\"none\"/> <rect x=\"-0.5\" y=\"-0.5\" width=\"21\" height=\"21\" fill=\"none\"/> </g> </g> </svg>', NULL, 'AD1', NULL, 0, NULL),
(60, 'Delete', NULL, 'view delete btn', 0, '2021-03-10 00:00:00', '2021-03-10 00:00:00', 2, 58, NULL, NULL, '2', 'roleclass_delete_btn_invoice', 0, NULL),
(61, 'Payment', NULL, 'view payment btn', 0, '2021-03-10 00:00:00', '2021-03-10 00:00:00', 2, 58, NULL, NULL, '2', 'roleclass_payment_btn_invoice', 0, NULL),
(62, 'Invoice', NULL, 'view customer invoice tab', 0, '2021-03-17 00:00:00', '2021-03-17 00:00:00', 3, 37, NULL, NULL, '5', 'roleclass_invoice_liandultab_customer', 0, NULL),
(63, 'Notification', 'all-notifications', NULL, 0, '2021-03-20 00:00:00', '2021-03-20 00:00:00', 1, 39, NULL, NULL, '4', NULL, 0, NULL),
(64, 'Assigned', NULL, 'view driver assigned tab', 0, '2021-01-16 00:00:00', '2021-01-16 00:00:00', 3, 24, NULL, NULL, '1', 'roleclass_assigned_liandultab_driver', 0, NULL),
(65, 'Delivered', NULL, 'view driver delivered tab', 0, '2021-01-16 00:00:00', '2021-01-16 00:00:00', 3, 24, NULL, NULL, '4', 'roleclass_delivered_liandultab_driver', 0, NULL),
(66, 'view order', 'view-order', 'View Order Details', 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 2, 4, NULL, NULL, '1', NULL, 0, NULL),
(67, 'edit order', 'edit-order', 'Edit Order Details', 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 2, 4, NULL, NULL, '2', NULL, 0, NULL),
(69, 'Vendor List', 'vendor-list', NULL, 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 1, 72, NULL, NULL, '3', NULL, 0, NULL),
(78, 'Transaction', NULL, 'view customer payment transaction tab', 0, '2021-04-05 00:00:00', '2021-04-05 00:00:00', 3, 37, NULL, NULL, '6', 'roleclass_transaction_liandultab_customer', 0, NULL),
(79, 'Payroll', NULL, 'view driver Payroll tab', 0, '2021-04-05 00:00:00', '2021-04-05 00:00:00', 3, 24, NULL, NULL, '5', 'roleclass_payroll_liandultab_driver', 0, NULL),
(70, 'Bank Accounts', 'accountsnbanks-list', NULL, 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 1, 72, NULL, NULL, '4', NULL, 0, NULL),
(71, 'Category', 'transaction-category-list', NULL, 0, '2021-04-01 00:00:00', '2021-04-01 00:00:00', 1, 72, NULL, NULL, '5', NULL, 0, NULL),
(74, 'Add Expense', 'add-transaction', 'Add Transaction', 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 1, 72, NULL, NULL, '1', NULL, 0, NULL),
(73, 'Transaction List', 'transaction-list', 'Transaction List', 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 1, 72, NULL, NULL, '2', NULL, 0, 'edit-transaction'),
(72, 'Accounts', NULL, NULL, 0, '2021-01-07 00:00:00', '2021-01-07 00:00:00', 0, 0, '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"19.998\" height=\"20\" viewBox=\"0 0 19.998 20\"> <g id=\"expensive\" transform=\"translate(-0.02)\"> <g id=\"Group_3619\" data-name=\"Group 3619\" transform=\"translate(0.019 0)\"> <g id=\"Group_3618\" data-name=\"Group 3618\" transform=\"translate(0 0)\"> <path id=\"Path_11616\" data-name=\"Path 11616\" d=\"M63.006,313.186a5.835,5.835,0,0,1-1.534-1.106,5.723,5.723,0,0,1-.864-1.1.37.37,0,1,0-.642.37l.007.011a6.465,6.465,0,0,0,.975,1.242,6.568,6.568,0,0,0,1.729,1.246.37.37,0,0,0,.329-.664Z\" transform=\"translate(-57.144 -296.408)\"/> <path id=\"Path_11617\" data-name=\"Path 11617\" d=\"M138.239,152.187a1.481,1.481,0,0,1-1.481-1.481h-.741a2.224,2.224,0,0,0,1.852,2.189v.774h.741v-.774a2.222,2.222,0,0,0-.37-4.411A1.481,1.481,0,1,1,139.72,147h.741a2.224,2.224,0,0,0-1.852-2.189v-.774h-.741v.774a2.222,2.222,0,0,0,.37,4.411,1.481,1.481,0,1,1,0,2.963Z\" transform=\"translate(-129.721 -137.372)\"/> <path id=\"Path_11618\" data-name=\"Path 11618\" d=\"M320.557,72.313a.371.371,0,1,0-.472.573,7.865,7.865,0,0,1,1.07,1.071.37.37,0,1,0,.573-.47A8.618,8.618,0,0,0,320.557,72.313Z\" transform=\"translate(-305.139 -68.885)\"/> <path id=\"Path_11619\" data-name=\"Path 11619\" d=\"M41.995,105.951a6.666,6.666,0,0,0-1.7,6.555.37.37,0,0,0,.713-.2,5.923,5.923,0,0,1,9.881-5.821q.211.211.4.436a5.9,5.9,0,0,1-.2,7.735l-.017.017-.007.007-.013.013c-.05.057-.1.114-.162.172a5.925,5.925,0,0,1-5.337,1.621.37.37,0,1,0-.144.727,6.666,6.666,0,0,0,6.007-1.823c.058-.058.111-.116.165-.174l.027-.027a6.666,6.666,0,0,0-9.613-9.234Z\" transform=\"translate(-38.184 -99.189)\"/> <path id=\"Path_11620\" data-name=\"Path 11620\" d=\"M285.841,146.224Z\" transform=\"translate(-272.61 -139.455)\"/> <path id=\"Path_11621\" data-name=\"Path 11621\" d=\"M11.5,0A8.518,8.518,0,0,0,4.777,3.288,8.321,8.321,0,0,0,3.3,4.764,8.518,8.518,0,1,0,15.256,16.718a8.494,8.494,0,0,0,1.481-1.485A8.516,8.516,0,0,0,11.5,0Zm3.767,15.384A7.78,7.78,0,1,1,3.791,5.32a7.934,7.934,0,0,1,.844-.568A7.783,7.783,0,0,1,15.269,15.384Zm2.125-9.647a.37.37,0,0,0-.656.343l.016.027a7.769,7.769,0,0,1-.095,7.934A8.51,8.51,0,0,0,5.974,3.362a7.688,7.688,0,0,1,4.044-1.138,7.766,7.766,0,0,1,3.923,1.06.37.37,0,0,0,.373-.639A8.522,8.522,0,0,0,7.44,1.883a7.777,7.777,0,0,1,10.7,10.682A8.516,8.516,0,0,0,17.394,5.738Z\" transform=\"translate(-0.019 0)\"/> </g> </g> </g> </svg>', NULL, 'AG', NULL, 0, NULL),
(75, 'View', NULL, 'view transaction details in popup', 0, '2021-04-02 00:00:00', '2021-04-02 00:00:00', 2, 73, NULL, NULL, '1', 'roleclass_view_btn_transactiondetails', 0, NULL),
(76, 'Delete', NULL, 'delete transaction details in popup', 0, '2021-04-02 00:00:00', '2021-04-02 00:00:00', 2, 73, NULL, NULL, '2', 'roleclass_delete_btn_transactiondetails', 0, NULL),
(77, 'Edit', NULL, 'edit transaction details in popup', 0, '2021-04-02 00:00:00', '2021-04-02 00:00:00', 2, 73, NULL, NULL, '3', 'roleclass_edit_btn_transactiondetails', 0, NULL),
(80, 'Offers', NULL, NULL, 0, '2021-04-12 00:00:00', '2021-04-12 00:00:00', 0, 0, '\r\n<svg id=\"Capa_1\" enable-background=\"new 0 0 512 512\" height=\"512\" viewBox=\"0 0 512 512\" width=\"512\" xmlns=\"http://www.w3.org/2000/svg\"><g><path d=\"m203.556 345.012 70.71-212.133c2.619-7.859-1.628-16.354-9.487-18.974-7.858-2.619-16.354 1.628-18.974 9.487l-70.71 212.133c-2.619 7.859 1.628 16.354 9.487 18.974 1.573.524 3.173.773 4.745.773 6.28.001 12.133-3.974 14.229-10.26z\"/><path d=\"m309.533 279.203c24.813 0 45-20.187 45-45s-20.187-45-45-45-45 20.187-45 45 20.187 45 45 45zm0-60c8.271 0 15 6.729 15 15s-6.729 15-15 15-15-6.729-15-15 6.729-15 15-15z\"/><path d=\"m139.827 189.203c-24.813 0-45 20.187-45 45s20.187 45 45 45 45-20.187 45-45-20.186-45-45-45zm0 60c-8.271 0-15-6.729-15-15s6.729-15 15-15 15 6.729 15 15-6.728 15-15 15z\"/><path d=\"m509 186-52.307-69.743 2.041-14.283c.667-4.674-.904-9.39-4.243-12.728l-31.82-31.82 31.819-31.82c5.858-5.857 5.858-15.355 0-21.213-5.857-5.857-15.355-5.857-21.213 0l-31.819 31.82-31.82-31.82c-3.338-3.339-8.054-4.905-12.728-4.243l-148.493 21.213c-3.213.459-6.19 1.948-8.485 4.243l-183.848 183.848c-21.445 21.444-21.445 56.338 0 77.782l155.563 155.564c3.182 3.182 6.666 5.881 10.353 8.118v6.082c0 30.327 24.673 55 55 55h220c30.327 0 55-24.673 55-55v-262c0-3.245-1.053-6.404-3-9zm-471.703 80.023c-9.748-9.748-9.748-25.608 0-35.356l180.312-180.312 136.118-19.445 26.517 26.517-21.213 21.213-10.607-10.607c-5.857-5.857-15.355-5.857-21.213 0s-5.858 15.355 0 21.213l42.427 42.427c2.929 2.929 6.768 4.394 10.606 4.394s7.678-1.465 10.606-4.394c5.858-5.857 5.858-15.355 0-21.213l-10.607-10.607 21.213-21.213 26.517 26.517-19.446 136.118-180.311 180.312c-4.722 4.722-11 7.322-17.678 7.322s-12.956-2.601-17.678-7.322zm444.703 190.977c0 13.785-11.215 25-25 25h-220c-13.164 0-23.976-10.228-24.925-23.154 13.567-.376 27.022-5.714 37.353-16.046l183.848-183.848c2.295-2.295 3.784-5.272 4.243-8.485l13.173-92.21 31.308 41.743z\"/></g></svg>', NULL, 'AG', NULL, 0, NULL),
(82, 'Coupon Used', 'subscription-list', NULL, 1, '2021-04-12 00:00:00', '2021-04-12 00:00:00', 1, 80, NULL, NULL, '2', NULL, 0, 'subscription-view,subscription-add'),
(84, 'Subcription', 'subscription-list', NULL, 0, '2021-04-14 00:00:00', '2021-04-14 00:00:00', 1, 80, NULL, NULL, '1', NULL, 0, 'subscription-view,subscription-add'),
(81, 'Coupon', 'coupon-list', NULL, 0, '2021-04-12 00:00:00', '2021-04-12 00:00:00', 1, 80, NULL, NULL, '1', NULL, 0, 'coupon-view,coupon-add'),
(83, 'Subcription', NULL, NULL, 1, '2021-04-14 00:00:00', '2021-04-14 00:00:00', 0, 0, '<svg id=\"Capa_1\" enable-background=\"new 0 0 512 512\" height=\"512\" viewBox=\"0 0 512 512\" width=\"512\" xmlns=\"http://www.w3.org/2000/svg\"><g><path d=\"m172.818 350.271c-3.998 1.114-6.336 5.258-5.222 9.256l22.144 79.471c1.757 6.306 7.336 10.543 13.882 10.543s12.125-4.237 13.882-10.543l22.144-79.471c1.114-3.998-1.223-8.142-5.222-9.256-4-1.117-8.142 1.224-9.256 5.222l-21.549 77.332-21.548-77.332c-1.114-3.998-5.256-6.335-9.255-5.222z\"/><path d=\"m255.629 357.557v84.468c0 4.15 3.364 7.491 7.515 7.491s7.515-3.388 7.515-7.539v-84.42c0-4.15-3.365-7.515-7.515-7.515s-7.515 3.365-7.515 7.515z\"/><path d=\"m297.543 349.919c-4.15 0-7.515 3.365-7.515 7.515v84.591c0 4.15 3.365 7.515 7.515 7.515s7.515-3.365 7.515-7.515v-28.706h7.922c17.48 0 31.7-14.221 31.7-31.701 0-17.479-14.221-31.7-31.7-31.7h-15.437zm32.108 31.7c0 9.193-7.478 16.671-16.671 16.671h-7.922v-33.342h7.922c9.192 0 16.671 7.479 16.671 16.671z\"/><path d=\"m309.812 226.032-24.101-5.42c-.745-.167-1.395-.64-1.785-1.297l-12.602-21.246c-3.242-5.465-8.97-8.727-15.324-8.727s-12.082 3.263-15.324 8.727l-12.602 21.247c-.389.657-1.04 1.129-1.785 1.297l-24.1 5.42c-6.199 1.394-11.072 5.834-13.036 11.877-1.963 6.043-.631 12.499 3.565 17.271l16.312 18.55c.504.573.753 1.339.682 2.099l-2.293 24.595c-.59 6.326 2.127 12.333 7.267 16.068s11.693 4.462 17.527 1.947l22.683-9.782c.701-.303 1.506-.302 2.207 0l22.683 9.782c5.758 2.482 12.456 1.738 17.527-1.947 5.14-3.735 7.857-9.742 7.267-16.068l-2.293-24.595c-.071-.76.178-1.526.682-2.099l16.312-18.55c4.196-4.771 5.528-11.228 3.565-17.271-1.962-6.044-6.836-10.484-13.034-11.878zm-1.816 19.223-16.312 18.55c-3.224 3.666-4.813 8.557-4.36 13.419l2.293 24.595c.195 2.081-1.954 3.65-3.879 2.818l-22.683-9.781c-4.46-1.924-9.65-1.923-14.11 0l-22.683 9.781c-1.924.832-4.073-.737-3.879-2.818l2.293-24.595c.453-4.862-1.136-9.753-4.36-13.419l-16.312-18.55c-1.385-1.575-.556-4.102 1.481-4.56l24.1-5.42c4.764-1.071 8.925-4.094 11.415-8.293l12.602-21.246c1.067-1.8 3.726-1.801 4.795 0l12.601 21.245c2.491 4.201 6.652 7.224 11.415 8.294l24.1 5.42c2.038.458 2.869 2.985 1.483 4.56z\"/><path d=\"m371.056 102.19h-55.005l10.734-21.606c1.846-3.717.33-8.227-3.386-10.073-3.715-1.848-8.227-.331-10.073 3.386l-14.056 28.292h-27.71l-7.168-14.428 36.134-72.732h42.047l-15.848 31.899c-1.846 3.717-.33 8.227 3.387 10.073 3.715 1.847 8.227.331 10.073-3.386l21.243-42.757c2.438-4.911-1.258-10.858-6.731-10.858h-58.829c-2.853 0-5.46 1.616-6.73 4.171l-33.138 66.701-33.138-66.701c-1.27-2.555-3.877-4.171-6.73-4.171h-58.829c-5.473 0-9.17 5.947-6.73 10.858l45.375 91.332h-55.004c-16.882 0-30.616 13.734-30.616 30.616v62.421c0 4.15 3.365 7.515 7.515 7.515s7.515-3.365 7.515-7.515v-62.421c0-8.594 6.992-15.587 15.587-15.587h62.471l11.046 22.233h-5.376c-4.15 0-7.515 3.364-7.515 7.515 0 4.15 3.364 7.515 7.515 7.515h93.83c4.15 0 7.515-3.365 7.515-7.515s-3.364-7.515-7.515-7.515h-12.843l-11.046-22.233h92.029c8.594 0 15.587 6.992 15.587 15.587v258.234c0 4.15 3.364 7.515 7.515 7.515 4.15 0 7.515-3.365 7.515-7.515v-258.234c-.001-16.882-13.736-30.616-30.617-30.616zm-139.813 37.262-61.815-124.423h42.047l61.815 124.423z\"/><path d=\"m394.157 413.584c-4.15 0-7.515 3.364-7.515 7.515v60.285c0 8.595-6.992 15.587-15.587 15.587h-230.111c-8.594 0-15.587-6.992-15.587-15.587v-256.098c0-4.15-3.364-7.515-7.515-7.515s-7.515 3.365-7.515 7.515v256.098c0 16.882 13.734 30.616 30.616 30.616h230.112c16.882 0 30.616-13.734 30.616-30.616v-60.285c.001-4.151-3.364-7.515-7.514-7.515z\"/></g></svg>', NULL, 'AG', NULL, 0, NULL),
(86, 'Subcription', 'subscription-purchase-list', NULL, 0, '2021-04-14 00:00:00', '2021-04-14 00:00:00', 1, 87, NULL, NULL, '2', NULL, 0, 'subscription-purchase-add'),
(88, 'Wallet', 'wallet-transaction-list', NULL, 0, '2021-04-14 00:00:00', '2021-04-14 00:00:00', 1, 87, NULL, NULL, '1', NULL, 0, 'wallet-credit-add'),
(87, 'Transaction', NULL, NULL, 0, '2021-04-21 00:00:00', '2021-04-21 00:00:00', 0, 0, '<svg id=\"Capa_1\" enable-background=\"new 0 0 512 512\" height=\"512\" viewBox=\"0 0 512 512\" width=\"512\" xmlns=\"http://www.w3.org/2000/svg\"><g><path d=\"m172.818 350.271c-3.998 1.114-6.336 5.258-5.222 9.256l22.144 79.471c1.757 6.306 7.336 10.543 13.882 10.543s12.125-4.237 13.882-10.543l22.144-79.471c1.114-3.998-1.223-8.142-5.222-9.256-4-1.117-8.142 1.224-9.256 5.222l-21.549 77.332-21.548-77.332c-1.114-3.998-5.256-6.335-9.255-5.222z\"/><path d=\"m255.629 357.557v84.468c0 4.15 3.364 7.491 7.515 7.491s7.515-3.388 7.515-7.539v-84.42c0-4.15-3.365-7.515-7.515-7.515s-7.515 3.365-7.515 7.515z\"/><path d=\"m297.543 349.919c-4.15 0-7.515 3.365-7.515 7.515v84.591c0 4.15 3.365 7.515 7.515 7.515s7.515-3.365 7.515-7.515v-28.706h7.922c17.48 0 31.7-14.221 31.7-31.701 0-17.479-14.221-31.7-31.7-31.7h-15.437zm32.108 31.7c0 9.193-7.478 16.671-16.671 16.671h-7.922v-33.342h7.922c9.192 0 16.671 7.479 16.671 16.671z\"/><path d=\"m309.812 226.032-24.101-5.42c-.745-.167-1.395-.64-1.785-1.297l-12.602-21.246c-3.242-5.465-8.97-8.727-15.324-8.727s-12.082 3.263-15.324 8.727l-12.602 21.247c-.389.657-1.04 1.129-1.785 1.297l-24.1 5.42c-6.199 1.394-11.072 5.834-13.036 11.877-1.963 6.043-.631 12.499 3.565 17.271l16.312 18.55c.504.573.753 1.339.682 2.099l-2.293 24.595c-.59 6.326 2.127 12.333 7.267 16.068s11.693 4.462 17.527 1.947l22.683-9.782c.701-.303 1.506-.302 2.207 0l22.683 9.782c5.758 2.482 12.456 1.738 17.527-1.947 5.14-3.735 7.857-9.742 7.267-16.068l-2.293-24.595c-.071-.76.178-1.526.682-2.099l16.312-18.55c4.196-4.771 5.528-11.228 3.565-17.271-1.962-6.044-6.836-10.484-13.034-11.878zm-1.816 19.223-16.312 18.55c-3.224 3.666-4.813 8.557-4.36 13.419l2.293 24.595c.195 2.081-1.954 3.65-3.879 2.818l-22.683-9.781c-4.46-1.924-9.65-1.923-14.11 0l-22.683 9.781c-1.924.832-4.073-.737-3.879-2.818l2.293-24.595c.453-4.862-1.136-9.753-4.36-13.419l-16.312-18.55c-1.385-1.575-.556-4.102 1.481-4.56l24.1-5.42c4.764-1.071 8.925-4.094 11.415-8.293l12.602-21.246c1.067-1.8 3.726-1.801 4.795 0l12.601 21.245c2.491 4.201 6.652 7.224 11.415 8.294l24.1 5.42c2.038.458 2.869 2.985 1.483 4.56z\"/><path d=\"m371.056 102.19h-55.005l10.734-21.606c1.846-3.717.33-8.227-3.386-10.073-3.715-1.848-8.227-.331-10.073 3.386l-14.056 28.292h-27.71l-7.168-14.428 36.134-72.732h42.047l-15.848 31.899c-1.846 3.717-.33 8.227 3.387 10.073 3.715 1.847 8.227.331 10.073-3.386l21.243-42.757c2.438-4.911-1.258-10.858-6.731-10.858h-58.829c-2.853 0-5.46 1.616-6.73 4.171l-33.138 66.701-33.138-66.701c-1.27-2.555-3.877-4.171-6.73-4.171h-58.829c-5.473 0-9.17 5.947-6.73 10.858l45.375 91.332h-55.004c-16.882 0-30.616 13.734-30.616 30.616v62.421c0 4.15 3.365 7.515 7.515 7.515s7.515-3.365 7.515-7.515v-62.421c0-8.594 6.992-15.587 15.587-15.587h62.471l11.046 22.233h-5.376c-4.15 0-7.515 3.364-7.515 7.515 0 4.15 3.364 7.515 7.515 7.515h93.83c4.15 0 7.515-3.365 7.515-7.515s-3.364-7.515-7.515-7.515h-12.843l-11.046-22.233h92.029c8.594 0 15.587 6.992 15.587 15.587v258.234c0 4.15 3.364 7.515 7.515 7.515 4.15 0 7.515-3.365 7.515-7.515v-258.234c-.001-16.882-13.736-30.616-30.617-30.616zm-139.813 37.262-61.815-124.423h42.047l61.815 124.423z\"/><path d=\"m394.157 413.584c-4.15 0-7.515 3.364-7.515 7.515v60.285c0 8.595-6.992 15.587-15.587 15.587h-230.111c-8.594 0-15.587-6.992-15.587-15.587v-256.098c0-4.15-3.364-7.515-7.515-7.515s-7.515 3.365-7.515 7.515v256.098c0 16.882 13.734 30.616 30.616 30.616h230.112c16.882 0 30.616-13.734 30.616-30.616v-60.285c.001-4.151-3.364-7.515-7.514-7.515z\"/></g></svg>', NULL, 'AGA', NULL, 0, NULL),
(90, 'Subscription Features', 'subscription-feature-list', NULL, 0, '2021-04-28 00:00:00', '2021-04-28 00:00:00', 1, 39, NULL, NULL, '5', NULL, 0, NULL),
(91, 'WalletCredit', 'walletcredit-list', NULL, 0, '2021-04-28 00:00:00', '2021-04-28 00:00:00', 1, 39, NULL, NULL, '6', NULL, 0, NULL),
(92, 'Tracking', 'orderlist-tracking', NULL, 0, '2021-05-01 00:00:00', '2021-05-01 00:00:00', 1, 1, NULL, NULL, '91', NULL, 0, ''),
(93, 'TimingReports', NULL, 'view driver TimingReports tab', 0, '2021-05-06 00:00:00', '2021-05-06 00:00:00', 3, 24, NULL, NULL, '7', 'roleclass_timingreports_liandultab_driver', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_salesexecutive`
--

CREATE TABLE `tbl_salesexecutive` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fullname` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'required',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'optional',
  `mobile` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'optional',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0-active,1-deactive, 2- deleted',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_salesexecutive`
--

INSERT INTO `tbl_salesexecutive` (`id`, `fullname`, `email`, `mobile`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Hardik Patel', NULL, NULL, 0, '2021-03-25 09:40:48', '2021-03-25 09:40:48');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_salesexecutive_cutomerlist`
--

CREATE TABLE `tbl_salesexecutive_cutomerlist` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL DEFAULT 0 COMMENT 'adminId of sales executive\r\n',
  `is_active` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-active,1-deactive, 2- deleted',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_short_helper`
--

CREATE TABLE `tbl_short_helper` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `short` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `details` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'for what',
  `is_active` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-active,1-deactive, 2- deleted',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `classhtml` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'span class'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_short_helper`
--

INSERT INTO `tbl_short_helper` (`id`, `name`, `short`, `details`, `type`, `is_active`, `created_at`, `updated_at`, `classhtml`) VALUES
(2, 'Home', 'H', 'Home Address', 'address_type', 0, '2020-11-20 00:00:00', '2020-11-20 00:00:00', NULL),
(1, 'Work', 'W', 'Work/Office Address', 'address_type', 0, '2020-11-20 00:00:00', '2020-11-20 00:00:00', NULL),
(3, 'Other', 'O', 'Other Address', 'address_type', 0, '2020-11-20 00:00:00', '2020-11-20 00:00:00', NULL),
(13, 'New', 'P', 'Order Placed By Admin', 'order_status_type', 0, '2020-11-20 00:00:00', '2020-11-20 00:00:00', 'info'),
(14, 'Placed', 'PU', 'Order Placed By Customer', 'order_status_type', 0, '2020-11-20 00:00:00', '2020-11-20 00:00:00', 'info'),
(15, 'Assigned', 'A', 'Order Assigned to Driver', 'order_status_type', 0, '2020-11-20 00:00:00', '2020-11-20 00:00:00', 'primary'),
(16, 'Delivered', 'D', 'Order Delivered to Customer', 'order_status_type', 0, '2020-11-20 00:00:00', '2020-11-20 00:00:00', 'success'),
(17, 'Cancelled', 'C', 'Order Cancelled By Admin', 'order_status_type', 0, '2020-11-20 00:00:00', '2020-11-20 00:00:00', 'danger'),
(18, 'Cancelled', 'CU', 'Order Cancelled By Customer', 'order_status_type', 0, '2020-11-20 00:00:00', '2020-11-20 00:00:00', 'danger'),
(19, 'COD', 'C', 'Cash On Delivery', 'payment_type', 0, '2020-11-20 00:00:00', '2020-11-20 00:00:00', NULL),
(20, 'Prepaid', 'P', 'Prepaid Order', 'payment_type', 0, '2020-11-20 00:00:00', '2020-11-20 00:00:00', NULL),
(21, 'Pending', '0', 'Pending', 'payment_status', 0, '2020-11-20 00:00:00', '2020-11-20 00:00:00', 'danger'),
(22, 'Paid', '1', 'Paid', 'payment_status', 0, '2020-11-20 00:00:00', '2020-11-20 00:00:00', 'info'),
(23, 'Available', 'A', 'Available', 'driver_status', 0, '2021-01-04 00:00:00', '2021-01-04 00:00:00', 'success'),
(24, 'Transit', 'T', 'Transit', 'driver_status', 2, '2021-01-04 00:00:00', '2021-01-04 00:00:00', 'info'),
(25, 'Breakdown', 'B', 'Breakdown', 'driver_status', 0, '2021-01-04 00:00:00', '2021-01-04 00:00:00', 'danger'),
(26, 'OffWork', 'O', 'OffWork', 'driver_status', 0, '2021-01-04 00:00:00', '2021-01-04 00:00:00', 'warning'),
(27, 'Transporter', 'Transporter', 'Transporter', 'customer_type', 0, '2021-01-05 00:00:00', '2021-01-05 00:00:00', NULL),
(28, 'Business', 'Business', 'Business', 'customer_type', 0, '2021-01-05 00:00:00', '2021-01-05 00:00:00', NULL),
(29, 'Individual', 'Individual', 'Individual', 'customer_type', 0, '2021-01-05 00:00:00', '2021-01-05 00:00:00', NULL),
(30, 'SuperAdmin', 'A', 'SuperAdmin/MainAdmin', 'admins_type', 0, '2021-01-07 00:00:00', '2021-01-05 00:00:00', NULL),
(31, 'Staff', 'S', 'Staff', 'admins_type', 0, '2021-01-07 00:00:00', '2021-01-05 00:00:00', NULL),
(32, 'Expenses', 'Dr', 'Expenses/Debit ', 'account_type', 0, '2021-01-07 00:00:00', '2021-01-05 00:00:00', 'danger'),
(33, 'Income', 'Cr', 'Profits/Credit ', 'account_type', 0, '2021-01-07 00:00:00', '2021-01-05 00:00:00', 'success'),
(34, 'Manager', 'M', 'Manager', 'admins_type', 0, '2021-01-07 00:00:00', '2021-01-05 00:00:00', NULL),
(35, 'Yes', '1', 'Is exempted from GST ? ', 'customer_gst_exempted_type', 0, '2021-01-07 00:00:00', '2021-01-05 00:00:00', NULL),
(36, 'No', '0', 'Is exempted from GST ? ', 'customer_gst_exempted_type', 0, '2021-01-07 00:00:00', '2021-01-05 00:00:00', NULL),
(37, 'Undelivered', 'U', 'Order Undelivered By Driver', 'order_status_type', 0, '2021-01-16 00:00:00', '2020-11-20 00:00:00', 'warning'),
(39, 'Wallet', 'W', 'Wallet Order', 'payment_type', 0, '2021-02-17 00:00:00', '2020-11-20 00:00:00', NULL),
(40, 'Order-Request', 'RO', 'Order Requested By Customer', 'order_status_type', 0, '2021-02-19 00:00:00', '2021-02-19 00:00:00', 'success'),
(41, 'Approved', 'OA', 'Order Approved By Admin', 'order_status_type', 0, '2021-02-19 00:00:00', '2021-02-19 00:00:00', 'success'),
(42, 'Pickup', 'PP', 'Order PickedUp By Driver', 'order_status_type', 0, '2021-02-20 00:00:00', '2021-02-20 00:00:00', 'info'),
(43, 'Cheque', 'CHQ', 'Admin Manually', 'payment_type_manual', 0, '2020-11-20 00:00:00', '2020-11-20 00:00:00', NULL),
(44, 'Cash', 'CS', 'Admin Manually', 'payment_type_manual', 0, '2020-11-20 00:00:00', '2020-11-20 00:00:00', NULL),
(45, 'RTGS', 'RTGS', 'Admin Manually', 'payment_type_manual', 0, '2020-11-20 00:00:00', '2020-11-20 00:00:00', NULL),
(46, 'NEFT', 'NEFT', 'Admin Manually', 'payment_type_manual', 0, '2020-11-20 00:00:00', '2020-11-20 00:00:00', NULL),
(47, 'CARD', 'CARD', 'Admin Manually', 'payment_type_manual', 0, '2020-11-20 00:00:00', '2020-11-20 00:00:00', NULL),
(48, 'LR Image Pickup', 'LRP', 'LR Image Pickup', 'order_file_type', 0, '2021-01-16 00:00:00', '2020-11-20 00:00:00', NULL),
(49, 'LR Image Drop', 'LRD', 'LR Image Drop', 'order_file_type', 0, '2021-01-16 00:00:00', '2020-11-20 00:00:00', NULL),
(50, 'Goods Image Drop', 'GD', 'Goods Image Drop', 'order_file_type', 0, '2021-01-16 00:00:00', '2020-11-20 00:00:00', NULL),
(51, 'Goods Image Pickup', 'GP', 'Goods Image Pickup', 'order_file_type', 0, '2021-01-16 00:00:00', '2020-11-20 00:00:00', NULL),
(52, 'Signature Image Pickup', 'SGP', 'Signature Image Pickup', 'order_file_type', 0, '2021-01-16 00:00:00', '2020-11-20 00:00:00', NULL),
(53, 'Signature Image Drop', 'SGD', 'Signature Image Drop', 'order_file_type', 0, '2021-01-16 00:00:00', '2020-11-20 00:00:00', NULL),
(55, 'Wallet Payment', 'W', 'Admin Manually', 'payment_type_manual', 0, '2021-04-20 00:00:00', '2021-04-20 00:00:00', NULL),
(56, 'Online Payment', 'P', 'Admin Manually', 'payment_type_manual', 0, '2021-04-20 00:00:00', '2021-04-20 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_subscriptions`
--

CREATE TABLE `tbl_subscriptions` (
  `id` int(11) UNSIGNED NOT NULL,
  `subscription_shortname` varchar(111) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subscription_value` decimal(9,2) NOT NULL DEFAULT 150.00 COMMENT 'subcription amount to purchase',
  `subscription_validity_months` smallint(5) NOT NULL DEFAULT 1,
  `subscription_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subscription_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subscription_terms` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_type` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'P' COMMENT 'F-flat amount discount,\r\nP-percent amount discount',
  `is_active` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-active,1-deactive, 2- deleted',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `min_order_value` decimal(13,2) NOT NULL DEFAULT 100.00,
  `discount_value_min` decimal(9,2) NOT NULL DEFAULT 2.00 COMMENT 'flat/percent',
  `discount_value_max` decimal(9,2) NOT NULL DEFAULT 5.00,
  `maximum_discount_perorder` decimal(9,2) NOT NULL DEFAULT 100.00 COMMENT 'INR',
  `admin_id` int(11) NOT NULL DEFAULT 0,
  `maximum_discount_amount` decimal(9,2) NOT NULL DEFAULT 250.00 COMMENT 'overall maximum',
  `subscription_feature_ids` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_default_bestvalue` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-no,1-yes'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_subscriptions`
--

INSERT INTO `tbl_subscriptions` (`id`, `subscription_shortname`, `subscription_value`, `subscription_validity_months`, `subscription_title`, `subscription_description`, `subscription_terms`, `discount_type`, `is_active`, `created_at`, `updated_at`, `min_order_value`, `discount_value_min`, `discount_value_max`, `maximum_discount_perorder`, `admin_id`, `maximum_discount_amount`, `subscription_feature_ids`, `is_default_bestvalue`) VALUES
(1, 'BigDaddy Silver', '110.00', 1, 'BigDaddy Silver Membership', 'BigDaddy Silver Membership', 'BigDaddy Silver Membership', 'P', 0, '2021-04-13 17:44:36', '2021-04-28 17:14:57', '200.00', '7.00', '12.00', '98.00', 0, '500.00', '1,2,6', 0),
(2, 'SuperGold', '499.00', 6, 'SuperGold', 'SuperGold', 'SuperGold Terms', 'F', 0, '2021-04-14 12:11:56', '2021-04-28 17:14:57', '200.00', '10.00', '20.00', '150.00', 0, '1001.00', '1,4', 0),
(3, 'SilverPlus', '120.00', 4, 'SilverPlus Title SilverPlus', 'SilverPlus Title SilverPlus SilverPlus Title SilverPlus SilverPlus Title SilverPlus', 'SilverPlus Terms SilverPlus Title SilverPlus', 'F', 0, '2021-04-14 13:47:11', '2021-04-28 17:14:57', '2.00', '12.00', '22.00', '70.00', 0, '999.00', '1,2', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_subscription_features`
--

CREATE TABLE `tbl_subscription_features` (
  `id` int(11) UNSIGNED NOT NULL,
  `subscription_feature` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-active,1-deactive, 2- deleted',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_subscription_features`
--

INSERT INTO `tbl_subscription_features` (`id`, `subscription_feature`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Feature 1', 0, '2021-04-14 16:21:41', '2021-04-14 16:21:41'),
(2, 'Feature 2', 0, '2021-04-14 16:21:41', '2021-04-14 16:21:41'),
(3, 'Feature 3', 0, '2021-04-14 16:21:41', '2021-04-14 16:21:41'),
(4, 'Feature 4', 0, '2021-04-14 16:21:41', '2021-04-14 16:21:41'),
(5, 'Feature 5', 0, '2021-04-14 16:21:41', '2021-04-14 16:21:41'),
(6, 'Feature 6', 0, '2021-04-14 16:21:41', '2021-04-14 16:21:41'),
(7, 'Feature 7', 0, '2021-04-14 16:21:41', '2021-04-14 16:21:41'),
(8, 'Feature 8', 0, '2021-04-14 16:21:41', '2021-04-14 16:21:41'),
(9, 'dsh fg hggh', 0, '2021-04-28 15:38:22', '2021-04-28 16:49:10');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_subscription_purchase`
--

CREATE TABLE `tbl_subscription_purchase` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `subscription_id` int(9) NOT NULL DEFAULT 0,
  `subscription_shortname` varchar(111) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subscription_value` decimal(9,2) NOT NULL DEFAULT 150.00 COMMENT 'subcription amount to purchase',
  `subscription_validity_months` smallint(5) NOT NULL DEFAULT 1,
  `subscription_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subscription_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subscription_terms` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_type` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'P' COMMENT 'F-flat amount discount,\r\nP-percent amount discount',
  `is_active` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-active,1-deactive, 2- deleted',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `min_order_value` decimal(13,2) NOT NULL DEFAULT 100.00,
  `discount_value_min` decimal(9,2) NOT NULL DEFAULT 2.00 COMMENT 'flat/percent',
  `discount_value_max` decimal(9,2) NOT NULL DEFAULT 5.00,
  `maximum_discount_perorder` decimal(9,2) NOT NULL DEFAULT 100.00 COMMENT 'INR',
  `admin_id` int(11) NOT NULL DEFAULT 0 COMMENT 'added by adminid else 0',
  `maximum_discount_amount` decimal(9,2) NOT NULL DEFAULT 250.00 COMMENT 'overall maximum',
  `subscription_feature_ids` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purchase_datetime` datetime NOT NULL,
  `amount_used` decimal(9,2) NOT NULL DEFAULT 0.00,
  `expired_datetime` datetime NOT NULL,
  `transaction_number` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_manually_added` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-no, 1- yes',
  `notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_subscription_purchase`
--

INSERT INTO `tbl_subscription_purchase` (`id`, `user_id`, `subscription_id`, `subscription_shortname`, `subscription_value`, `subscription_validity_months`, `subscription_title`, `subscription_description`, `subscription_terms`, `discount_type`, `is_active`, `created_at`, `updated_at`, `min_order_value`, `discount_value_min`, `discount_value_max`, `maximum_discount_perorder`, `admin_id`, `maximum_discount_amount`, `subscription_feature_ids`, `purchase_datetime`, `amount_used`, `expired_datetime`, `transaction_number`, `is_manually_added`, `notes`) VALUES
(1, 1, 3, 'SilverPlus', '120.00', 4, 'SilverPlus Title SilverPlus', 'SilverPlus Title SilverPlus SilverPlus Title SilverPlus SilverPlus Title SilverPlus', 'SilverPlus Terms SilverPlus Title SilverPlus', 'F', 0, '2021-04-22 16:49:01', '2021-04-22 16:49:01', '210.00', '12.00', '22.00', '70.00', 11, '999.00', '1,2', '2021-04-22 16:49:01', '999.00', '2021-08-22 00:00:00', 'ADD_16190903418119', 1, 'kkk kukgyg iuiuyi'),
(2, 1, 3, 'SilverPlus', '120.00', 4, 'SilverPlus Title SilverPlus', 'SilverPlus Title SilverPlus SilverPlus Title SilverPlus SilverPlus Title SilverPlus', 'SilverPlus Terms SilverPlus Title SilverPlus', 'F', 0, '2021-04-22 16:51:16', '2021-04-22 16:51:16', '210.00', '12.00', '22.00', '70.00', 11, '999.00', '1,2', '2021-04-22 16:51:16', '0.00', '2021-08-22 00:00:00', 'ADD_16190904769881', 1, 'p'),
(3, 39, 1, 'BigDaddy Silver', '110.00', 1, 'BigDaddy Silver Membership', 'BigDaddy Silver Membership', 'BigDaddy Silver Membership', 'P', 0, '2021-04-22 16:57:14', '2021-04-22 16:57:14', '200.00', '7.00', '12.00', '98.00', 11, '500.00', '1,2,6', '2021-04-22 16:57:14', '0.00', '2021-05-22 00:00:00', 'ADD_16190908343068', 1, NULL),
(4, 27, 3, 'SilverPlus', '120.00', 4, 'SilverPlus Title SilverPlus', 'SilverPlus Title SilverPlus SilverPlus Title SilverPlus SilverPlus Title SilverPlus', 'SilverPlus Terms SilverPlus Title SilverPlus', 'F', 0, '2021-04-22 16:57:25', '2021-04-22 16:57:25', '210.00', '12.00', '22.00', '70.00', 11, '999.00', '1,2', '2021-04-22 16:57:25', '0.00', '2021-08-22 00:00:00', 'ADD_16190908459777', 1, 'fhfg hfgh');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `firstname` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fullname` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transporter_name` varchar(155) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `GST_number` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_type` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Transporter',
  `email` varchar(111) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `added_by` int(11) NOT NULL DEFAULT 0 COMMENT '0-user self or admin id',
  `updated_by` int(11) NOT NULL DEFAULT 0 COMMENT '0-user self or admin id',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0-active,1-deactive, 2- deleted',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `pan_no` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ownership` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_pic` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_gst_exempted_type` varchar(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '0-no, 1-yes customer_gst_exempted_type',
  `wallet_credit` decimal(11,2) NOT NULL DEFAULT 0.00,
  `ipaddress` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_token` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_paymentbill_type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-to pay, 1- tobebilled'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `firstname`, `lastname`, `fullname`, `business_name`, `transporter_name`, `GST_number`, `customer_type`, `email`, `mobile`, `added_by`, `updated_by`, `password`, `is_active`, `created_at`, `updated_at`, `pan_no`, `business_type`, `ownership`, `profile_pic`, `customer_gst_exempted_type`, `wallet_credit`, `ipaddress`, `device_token`, `user_paymentbill_type`) VALUES
(1, 'Ankur', 'Abhay', 'Ankur Abhay Jain', 'MAAHI SAREES', NULL, '24ANIPJ6293R1Z7', 'Business', NULL, '9099920409', 5, 9, '$2y$10$0WDzx/J7xr/tstEmcJ2s3eDCXmJFSUOm5nUN52X8uf4I4oKr2dOwa', 0, '2021-01-19 16:35:28', '2021-04-21 18:31:31', NULL, 'Wholesale Business', 'Proprietorship', NULL, NULL, '1900.00', NULL, NULL, 0),
(2, 'SEEMA', 'SAREES', 'SEEMA SAREES', 'SHYLAA A UNIT OF SEEMA SAREES', NULL, '24ADJFS9514K1ZN', 'Business', 'seemasarees@yahoo.com', '9375165330', 9, 11, '$2y$10$qLp1tVk/BSq3rTicgZit5OcW2cS0a3z.cevYhNy8MgEkMePH6HRBu', 0, '2021-01-20 11:52:53', '2021-04-19 11:05:07', NULL, 'Recipient of Goods or Services , Retail Business , Wholesale Business ,Factory / Manufacturing , Office / Sale Office , Supplier of Services', 'Partnership', NULL, NULL, '0.00', NULL, NULL, 0),
(3, 'DEVA', 'RAM', 'DEVA RAM HOPU RAM JI KUMAWAT', 'MAHALAXMI FASHION', NULL, '24AMWPK5723E1ZW', 'Business', 'ABC@XXX.COM', '9426869694', 9, 9, '$2y$10$f87y98pK9XOWUvEzPsJwsuJUwJrXQBw4WSaWbiiL9csSQsnOmAbw6', 0, '2021-01-20 12:07:24', '2021-01-20 12:07:24', NULL, 'Office / Sale Office', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(4, 'VISHALKUMAR', 'MATHURBHAI', 'VISHALKUMAR MATHURBHAI CHANIYARA', 'AMBIKA STEEL', NULL, '24ANIPC8379D1Z0', 'Business', 'XYZ@XXX.COM', '9601500070', 9, 9, '$2y$10$UBJMkEdRT6iFk7v0Hq2gWuL58Y5rZFCqN9ihBKO4lIypUaR1ZhnWa', 0, '2021-01-20 12:40:46', '2021-01-20 12:40:46', NULL, 'Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(5, 'CHOGARAM', 'KOLAJI', 'CHOGARAM KOLAJI CHOUDHARY', 'BAJRANG CUTLERY', NULL, '24AFPPC6497M1ZS', 'Business', 'ACC@XXX.COM', '9909845986', 9, 9, '$2y$10$Stj2IzciP2szAnmb6uUyBepLr55MyFqGUbMMGbsxBMfYiM67fjrM.', 0, '2021-01-20 13:00:10', '2021-01-20 13:00:10', NULL, 'Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(6, 'SURESHKUMAR', 'AMULAKHRAI', 'SURESHKUMAR AMULAKHRAI SANGHVI', 'SURESH ENTERPRISE', NULL, '24ACTPS6793D1ZY', 'Business', 'AAA@XYZ.COM', '9879921358', 9, 9, '$2y$10$JIWZGGDnJGOSlX9tP3xT6./jVWLDp2n/UqdxLaYfAAnnne2On.Zn6', 0, '2021-01-20 13:04:02', '2021-01-20 13:04:02', NULL, 'Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(7, 'PARESH', 'R', 'PARESH R TAILOR HUF', 'PARESH ENGINEERING WORKS', NULL, '24AAQHP1647G1Z0', 'Business', 'BBB@XYZ.COM', '9374720106', 9, 9, '$2y$10$RItmXTtDTz5pSwawsJAqoOowg12VS4knz6fST93Gzch8teyMYMdI.', 0, '2021-01-20 13:06:56', '2021-01-20 13:06:56', NULL, ' Supplier of Services', 'Hindu Undivided Family', NULL, NULL, '0.00', NULL, NULL, 0),
(8, 'LALITKUMAR', 'PARASMAL', 'LALITKUMAR PARASMAL JAIN', 'ADINATH TRADING CO', NULL, '24ABNPJ1808M1ZI', 'Business', 'CCC@XYZ.COM', '9638755848', 9, 9, '$2y$10$JoSzIva3HWYPnDVHq72mwO.iuABX7knaB/EiTOJOVq.8i/WKZ7bCK', 0, '2021-01-20 13:08:44', '2021-01-20 13:08:44', NULL, 'Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(9, 'DEVANGBHAI', 'VISHNUBHAI', 'DEVANGBHAI VISHNUBHAI PATEL', 'PRINT POINT PAPER GALLARY', NULL, '24AFFPP0241J1ZP', 'Business', 'DDD@XYZ.COM', '9033824971', 9, 9, '$2y$10$ZfjJ4jHRUEQ9poua8VRwu.bT027HsvD0Bq5ZKD0LXK.df3g1V2g.K', 0, '2021-01-20 13:23:30', '2021-01-20 13:23:30', NULL, 'RETAIL BUSINESS', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(10, 'DINESHKUMAR', 'JAWAHARLAL', 'DINESHKUMAR JAWAHARLAL NISHAD', 'GURU KRUPA AGARBATTI AND POOJA CENTER', NULL, '24AJDPN3634K1Z7', 'Business', 'EEE@XYZ.COM', '7405437039', 9, 9, '$2y$10$rrHHgD3ZYRlSrrD.T4yvju/agV2IB0zdPrGkTA/2ZHpJxe9NWoEEi', 0, '2021-01-20 13:26:01', '2021-01-20 13:26:01', NULL, 'RETAIL BUSINESS', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(11, 'RAMESHBHAI', 'PRAKASHBHAI', 'RAMESHBHAI PRAKASHBHAI CHOUDHARI', 'RAJARAM BANGLES', NULL, '24AQFPC5261Q1ZN', 'Business', NULL, '9687127565', 9, 9, '$2y$10$AwJFvUF45Il0CTKmqCjZju7ZVjXHzvWWv.LuZBlIWCvdUA.z.mD.O', 0, '2021-01-20 13:57:38', '2021-01-20 13:57:38', NULL, 'Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(12, 'MOHAMMEDALI', 'SAQUIB', 'MOHAMMEDALI SAQUIB HAMIDANI', 'NEW ARIFE LAMOULDE', NULL, '24ACVPH6353E2ZG', 'Business', NULL, '9054222228', 9, 9, '$2y$10$8GY4Hyx5na57B1mbDLSF6uyqLDMpYY0PI1Hzakcojnowh3Gb76rlK', 0, '2021-01-20 14:11:14', '2021-01-20 14:11:14', NULL, 'Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(13, 'MINESHKUMAR', 'DAHYABHAI', 'MINESHKUMAR DAHYABHAI PANDYA', 'PANDYA CORPORATION', NULL, '24ANNPP2905P1ZH', 'Business', NULL, NULL, 9, 9, '$2y$10$PnmWIm3lGmaW0dmnPr5EOeLe0Fdd0IbUVvpziRiKrm3ZbsraG1Cia', 0, '2021-01-20 14:13:28', '2021-01-20 14:13:28', NULL, 'Works Contract', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(14, 'JINENDRA', 'JAIN', 'JINENDRA JAIN', 'SHREEJI GIFT', NULL, '24AATPJ3462G1ZL', 'Business', NULL, '9377397642', 9, 9, '$2y$10$T3a8Hg8xE.SbHvj9gS41fud.yZfEVKTdse9cYeg7SY/EphE679uuS', 0, '2021-01-20 14:15:16', '2021-01-20 14:15:16', NULL, 'Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(15, 'NARESHKUMAR', 'PARSHOTAMBHAI', 'NARESHKUMAR PARSHOTAMBHAI DHANDHUKIA', 'GAYATRI SALES', NULL, '24AFFPD4795R1ZU', 'Business', NULL, '9429487271', 9, 9, '$2y$10$5LfuhnvipKsqoSssfU.llOlSRdQ/je1GPc8EuKhtjpUI4UFGoeDwe', 0, '2021-01-20 14:17:30', '2021-01-20 14:17:30', NULL, 'RETAIL BUSINESS', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(16, 'VIPULBHAI', 'NAVINBHAI', 'VIPULBHAI NAVINBHAI CHAMPANERA', 'SHREE VISHWAKARMA BORING WORKS', NULL, '24AFRPC1399G1ZC', 'Business', NULL, '9925144518', 9, 9, '$2y$10$Zi2Edv2ByzLNAXm1Md28c.qyn6CFjHgCwCzchW8KkIG454aO.6a6i', 0, '2021-01-20 14:19:06', '2021-01-20 14:19:06', NULL, 'Retail Business , Wholesale Business, Service Provision', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(17, 'ASHISHKUMAR', 'DHARAMVEERBHAI', 'ASHISHKUMAR DHARAMVEERBHAI MEHTA', 'GAUTAM SALES AGENCY', NULL, '24ACZPM1617Q1ZU', 'Business', NULL, '9825082660', 9, 9, '$2y$10$Z7afMdofRGEfXcXEfi6dGuL9wffykwmIN6EUp7wCkrJY37pRkMA3.', 0, '2021-01-20 14:48:40', '2021-01-20 14:48:40', NULL, 'Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(18, 'JIGNESH', 'YOGESHBHAI', 'JIGNESH YOGESHBHAI DALAL', 'JAGZ', NULL, '24AASPD5714G1ZT', 'Business', NULL, '8758788577', 9, 9, '$2y$10$CF7o7ShfEmdy21ApZWAyJ.3j6WyXVNViev9Jt3yAby2dErXf5Ofru', 0, '2021-01-20 14:50:32', '2021-01-20 14:50:32', NULL, 'Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(19, 'GANESH', 'DALARAM', 'GANESH DALARAM CHAUDHARI', 'GAJANAN COMPUTERS', NULL, '24AHJPC2119A1Z8', 'Business', NULL, '9979110307', 9, 9, '$2y$10$3N13sboRzp53t1ZQMyhQ8OA2s9CK9Fm8i3SpFXlNXeo6UxfOkkE.W', 0, '2021-01-20 14:53:46', '2021-01-20 14:53:46', NULL, 'Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(20, 'ASHISHKUMAR', 'NATVARLAL', 'ASHISHKUMAR NATVARLAL ACHARYA', 'MAHALAXMI HANDLOOM', NULL, '24BGPPA8922F1ZG', 'Business', NULL, '9275110701', 9, 9, '$2y$10$Q7YksUoXPXRl/buB6YfyouYM.QdYKuBl1ur3U1pqkU4sIWa8zgMLC', 0, '2021-01-20 14:55:24', '2021-01-20 14:55:24', NULL, 'Factory / Manufacturing , Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(21, 'AMBICA', 'TRADING', 'AMBICA TRADING', 'AMBICA TRADING', NULL, '24ABCFA9795M1ZT', 'Business', NULL, '9879156525', 9, 9, '$2y$10$1edWOLUsMkvAbP.wk.Y4V.8HKkzVZubIivmig/4uRTceFuJ1B0Sim', 0, '2021-01-20 14:57:03', '2021-01-20 14:57:03', NULL, 'Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(22, 'DIPAKKUMAR', 'RAVJIBHAI', 'DIPAKKUMAR RAVJIBHAI GONDALIYA', 'VRAJ DIGITAL TILES', NULL, '24ARSPG9119K1ZB', 'Business', NULL, '8000007072', 9, 9, '$2y$10$7zF9.JMe2KmLfgO8VP2Te.HkVFl5mmPKyTpSUh5tLkfw29eITHGF.', 0, '2021-01-20 14:58:33', '2021-01-20 14:58:33', NULL, 'Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(23, 'GULSHERKHAN', 'ABDULVAHABKHAN', 'GULSHERKHAN ABDULVAHABKHAN PATHAN', 'RAJA JEWELLERS', NULL, '24AFLPP1928E1ZI', 'Business', NULL, '9979202067', 9, 9, '$2y$10$2FMorNoGcxsWz0058puBO.D0qB9c3CSsPYMlJA6PG78UXyLML8O3y', 0, '2021-01-20 14:59:59', '2021-01-20 14:59:59', NULL, 'RETAIL BUSINESS', 'Proprietorship', NULL, '0', '0.00', NULL, NULL, 0),
(24, 'PRIME', 'DENIM', 'PRIME DENIM LLP', 'PRIME DENIM LLP', NULL, '24AATFP3407H1Z5', 'Business', NULL, '9328162000', 9, 9, '$2y$10$DwzEcsx2WsXRqGTKSUWvrOxDnbqZa0JdA6L7Z5/4LP68V.8nh/Bj.', 0, '2021-01-20 15:02:03', '2021-01-20 15:02:03', NULL, ' Wholesale Business, Factory / Manufacturing', 'Limited Liability Partnership', NULL, NULL, '0.00', NULL, NULL, 0),
(25, 'LALIT', 'VANARSIBHAI', 'LALIT VANARSIBHAI PATEL', 'PATEL MOTORS', NULL, '24CELPP8445K1ZV', 'Business', NULL, '9825129121', 9, 9, '$2y$10$4UqAPkLuz3t4kINC5aO/7exuFS59gWjBh8JurDNsZ7WZHGiDvU6a2', 0, '2021-01-20 15:04:08', '2021-01-20 15:04:08', NULL, 'Retail Business , Wholesale Business , Office / Sale Office ,Recipient of Goods or Service ,Supplier of Services', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(26, 'LEELA', 'FURNISHING', 'LEELA FURNISHING', 'LEELA FURNISHING', NULL, '24AADFF0877D1ZR', 'Business', NULL, '9771475881', 9, 9, '$2y$10$odorTa/2A8QFqixTibsnM.h89ctOvbssJAaeh7bkcWc0u96OHl8rO', 0, '2021-01-20 15:06:22', '2021-01-20 15:06:22', NULL, 'Retail Business , Wholesale Business , import , export', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(27, 'SITARAM', 'BANSILAL', 'SITARAM BANSILAL RATHI', 'SHREE INDRA HOSIERY AND HANDLOOM', NULL, '24AAWPR5987R1Z5', 'Business', NULL, '9327443879', 9, 9, '$2y$10$j3yvu0gJjz6EFuDkiIL7XeVmfnOtbbJ4NHyvWNunnnFvNVe76gBzm', 0, '2021-01-20 15:07:43', '2021-01-20 15:07:43', NULL, 'Office / Sale Office, wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(28, 'GAURANG', 'AJAYKUMAR', 'GAURANG AJAYKUMAR MODI', 'JIYA S THE KURTIS STUDIO', NULL, '24AMNPM6922G1ZW', 'Business', NULL, '9429878852', 9, 9, '$2y$10$6cXWVTH0k0qMSNHJbLEkOuqyv4gpC0RCsJgWVYW2NlXubtdCL8.NO', 0, '2021-01-20 15:10:17', '2021-01-20 15:10:17', NULL, 'Retail Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(29, 'SHREE', 'UMIYA', 'SHREE UMIYA SALES AGENCY', 'SHREE UMIYA SALES AGENCY', NULL, '24ACIFS2942M1ZS', 'Business', NULL, '9825137240', 9, 9, '$2y$10$yYDL7RrKXoeujWYlaBQ30OSS6lQKyL2gXosaajpi9wdQuat93f7XC', 0, '2021-01-20 15:12:21', '2021-01-20 15:12:21', NULL, 'Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(30, 'URESHKUMAR', 'TALARAMJI', 'URESHKUMAR TALARAMJI CHAUDHARY', 'SHREE MAHADEV HOSIERY', NULL, '24ALPPC8562K1ZP', 'Business', NULL, '9374547141', 9, 9, '$2y$10$IXdPVT6LFT6HhKsyaA7AlOzPj0uvzPsXoC/UOF3PTYbf86llwI00W', 0, '2021-01-20 15:13:44', '2021-01-20 15:13:44', NULL, 'Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(31, 'SHREE', 'GAJANAN', 'SHREE GAJANAN STORES', 'SHRI GAJANAN STORE', NULL, '24AAJFS8383B1Z3', 'Business', NULL, '9825690590', 9, 9, '$2y$10$1BnqZrEpTBlbp2hL4pNmGe0FXxHmOVQtX/fG1lk7MhREovGp4wxZO', 0, '2021-01-20 15:15:05', '2021-01-20 15:15:05', NULL, 'Retail Business , Factory / Manufacturing', 'Partnership', NULL, NULL, '0.00', NULL, NULL, 0),
(32, 'MEENABEN', 'GOPALBHAI', 'MEENABEN GOPALBHAI MER', 'DECENT MULTI PRINT', NULL, '24DEEPM1286K1ZB', 'Business', NULL, '9624625999', 9, 9, '$2y$10$8IVsN1bmIH2xN3O1oP8fv.8wxKva.D9fxlu/1t8UarDBh99FB6kwG', 0, '2021-01-20 15:16:34', '2021-01-20 15:16:34', NULL, 'Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(33, 'ANKUR', 'SURESH', 'ANKUR SURESH JAIN', 'HMMM ENTERPRISE', NULL, '24AMVPJ1022A1ZM', 'Business', NULL, '9327667610', 9, 9, '$2y$10$UP9qdCpynN730yNj8XQoCOsYe.tQq2U.T.yKFtLkp8LOTf5MPPKN6', 0, '2021-01-20 15:17:57', '2021-01-20 15:17:57', NULL, 'Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(34, 'HARISINGH', 'HADMATSINGH', 'HARISINGH HADMATSINGH CHAUHAN', 'ASHAPURI TRADING CO', NULL, '24AHRPC3838L1Z1', 'Business', NULL, '9825053529', 9, 9, '$2y$10$LX5Rk7HuFBcBh6QcKAmGUuTK9/yGjsQtKVJU76gB5PgJ8leSpBal6', 0, '2021-01-20 15:19:17', '2021-01-20 15:19:17', NULL, 'Office / Sale Office, wholesale Business, retail business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(35, 'KIRAN', 'RAMESHBHAI', 'KIRAN RAMESHBHAI DOSHI', 'VISHWAS FASHIONS', NULL, '24BJIPD6869C1Z9', 'Business', NULL, '9376838407', 9, 9, '$2y$10$FZvuT0wJm8lg9d2YOHSJH.b.gYjz91d5sLeCDOENfSGbM/TEg1vli', 0, '2021-01-20 15:21:07', '2021-01-20 15:21:07', NULL, 'Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(36, 'ANKITKUMAR', 'LALJEEBHAI', 'ANKITKUMAR LALJEEBHAI KANSAGRA', 'CREAMSON NUTRI FOODS', NULL, '24DVOPK7638L1ZU', 'Business', NULL, '6351348640', 9, 9, '$2y$10$WkLoX6cloopL3X0whE5t6.tyobnxHH/EI7gEifopIFwWulGdlExjm', 0, '2021-01-20 15:23:29', '2021-01-20 15:23:29', NULL, 'Retail Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(37, 'RAKESHKUMAR', 'DALSUKHBHAI', 'RAKESHKUMAR DALSUKHBHAI SHAH', 'SHANKESHWAR CREATION(CHAUTA', NULL, '24BLWPS6729Q1ZS', 'Business', NULL, '9909447666', 9, 9, '$2y$10$8UEcadbrSFiqHcQp1rzeWel1w7c/EidMVlDSk05OLQrUSF5Hs9miW', 0, '2021-01-20 15:26:26', '2021-01-20 15:26:26', NULL, 'Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(38, 'HARSHITKUMAR', 'TARUNKUMAR', 'HARSHITKUMAR TARUNKUMAR SHAH', 'MANSI TRADERS', NULL, '24BBBPS5827A1ZY', 'Business', NULL, '8866623497', 9, 9, '$2y$10$FJElTyw8Vdi6NALIvscGI.S2LHzMNJwpB9OLChDtjZ/gZ6zE9vjp6', 0, '2021-01-20 15:27:37', '2021-01-20 15:27:37', NULL, 'Office / Sale Office, wholesale Business, retail business  , supply of service', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(39, 'MAUSAMI', 'KISHORBHAI', 'MAUSAMI KISHORBHAI JAIN', 'CHOGMAL CHAMPALAL & CO', NULL, '24ARJPJ1480D1Z4', 'Business', NULL, '9328918157', 9, 9, '$2y$10$b6zF5MD1HlLNQ50gZhA8XewGyV1dbJadUbH4UZfpcFHn7ya/Hq7BG', 0, '2021-01-20 15:28:52', '2021-01-20 15:28:52', NULL, 'Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(40, 'SANGHVI', 'VINODKUMAR', 'SANGHVI VINODKUMAR KISHANRAJ', 'ABHISHEK AGENCY', NULL, '24AAGHS3295D1Z5', 'Business', NULL, '9427824870', 9, 9, '$2y$10$jzPfxZtgX5hV3ec.UiXzaegQtX7YoBVglJHeWnhgOmHolqX2Xf/W2', 0, '2021-01-20 15:30:14', '2021-01-20 15:30:14', NULL, 'Retail Business , Wholesale Business', 'Hindu Undivided Family', NULL, NULL, '0.00', NULL, NULL, 0),
(41, 'ALL', 'INDIA', 'ALL INDIA SALES CORPORATION', 'ALL INDIA SALES CORPORATION', NULL, '24AAHFA2840B1Z5', 'Business', NULL, '9879556869', 9, 9, '$2y$10$vUWo.pl9cQv/.wvXZurtUeg3LXo0kD7MjYWmjuvkTv2DFWuOVd2Ma', 0, '2021-01-20 15:31:32', '2021-01-20 15:31:32', NULL, 'Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(42, 'MOHAMEDAFZAL', 'MOHAMEDFAROOK', 'MOHAMEDAFZAL MOHAMEDFAROOK KHIMANI', 'AASHI CHEM', NULL, '24CBFPK4710H1ZZ', 'Business', NULL, '9925840972', 9, 9, '$2y$10$9P3HO41A.CHw2ffqkeCoEe2tIXcjSydk3T7ykBOgE13OLKEa1BTD.', 0, '2021-01-20 15:32:48', '2021-01-20 15:32:48', NULL, 'Wholesale Business, export', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(43, 'NILESHKUMAR', 'GYANCHAND', 'NILESHKUMAR GYANCHAND CHHATANI', 'MAHADEV AGENCY', NULL, '24AICPC6920D1ZX', 'Business', NULL, '9428149698', 9, 9, '$2y$10$67odVIy9btAexe9j0g8Fg.VW5THCk.vr7L6hbHWmJ.iFubAMakIca', 0, '2021-01-20 15:34:05', '2021-01-20 15:34:05', NULL, 'Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(44, 'ANKUSH', 'KUMAR', 'ANKUSH KUMAR MOHATA', 'PRIYA COLLECTION', NULL, '24AXKPM7266M1ZT', 'Business', NULL, NULL, 9, 9, '$2y$10$xgyp4FXm/.tcA9ydzrAqgeqbaVLYEDY5qXsZHXQR57WBOA3f7x.b2', 0, '2021-01-20 15:35:33', '2021-01-20 15:35:33', NULL, 'Wholesale Business , office/ sales', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(45, 'KENVY', 'LADIES', 'KENVY LADIES WEAR SURAT', 'KENVY LADIES WEAR SURAT', NULL, '24AAVFK6165B1Z7', 'Business', NULL, NULL, 9, 9, '$2y$10$F4Y.ydkQ5//ZvA5jtqsl9em0REzxt/yJQuvm7EP6R7JbBdWuwUpo6', 0, '2021-01-20 15:38:55', '2021-01-20 15:38:55', NULL, 'Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(46, 'SHIVCHAND', 'MEVALAL', 'SHIVCHAND MEVALAL GUPTA', 'GUPTA TRADING CO', NULL, '24ABGPG4760Q1ZB', 'Business', NULL, NULL, 9, 9, '$2y$10$0akVdk15U//0rXIiwZvT7eYr.XCvTtyVU4evEcMZWK4P.d24Qdy9W', 0, '2021-01-20 15:40:00', '2021-01-20 15:40:00', NULL, 'Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(47, 'RAMESHCHANDRA', 'GOVINDBHAI', 'RAMESHCHANDRA GOVINDBHAI TAILOR', 'ATUL MACHINERY STORES', NULL, '24AAWPT1376Q1ZM', 'Business', NULL, NULL, 9, 9, '$2y$10$GIUQhAa3oV5ErjZhKqkgb.5cnGFXkNbKYiUdDO7c7b3Is8QQJxFg2', 0, '2021-01-20 15:41:19', '2021-01-20 15:41:19', NULL, 'Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(48, 'RAJBHADURSINGH', 'RAMSINGH', 'RAJBHADURSINGH RAMSINGH RAJPU', 'RADIANT SERVICES', NULL, '24AEPPR9339J1ZQ', 'Business', NULL, NULL, 9, 9, '$2y$10$wK9kse.dK277yBeEf16oOeeQrfpKihu5GV1MALgbAnhLYgio6rJ6m', 0, '2021-01-20 15:45:11', '2021-01-20 15:45:11', NULL, 'Service Provision', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(49, 'LILABEN', 'TEJARAMBHAI', 'LILABEN TEJARAMBHAI CHAUDHARI', 'LALITA FANCY', NULL, '24AUOPC3723E1Z0', 'Business', NULL, NULL, 9, 9, '$2y$10$6ILd.0Hvfd8lnxH5vIOOpe6q9CeMSZ75SF.gi0symRRWeH3r6ZhsO', 0, '2021-01-20 15:46:30', '2021-01-20 15:46:30', NULL, 'Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(50, 'MANOJKUMAR', 'JAMNADAS', 'MANOJKUMAR JAMNADAS DANGI', 'ANKUR STEEL', NULL, '24APRPK7877H1Z6', 'Business', NULL, NULL, 9, 9, '$2y$10$ZqFR.hl5qxHK7xeyJOuiluilBzPd9cc48zOnx65CWfQggj2x0/vqy', 0, '2021-01-20 15:47:55', '2021-01-20 15:47:55', NULL, 'Recipient of Goods or Services , retail', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(51, 'HATIM', 'IBRAHIMBHAI', 'HATIM IBRAHIMBHAI GOTIWALA', 'HATIM TRADERS', NULL, '24APXPG7675K1Z1', 'Business', NULL, NULL, 9, 9, '$2y$10$u3E4BO7T1lAzUzIXnBpXLuHZCio3gZQae0nZggbTVTcFQqEIun6KS', 0, '2021-01-20 15:49:43', '2021-01-20 15:49:43', NULL, 'Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(52, 'SANJAYKUMAR', 'LADULAL', 'SANJAYKUMAR LADULAL LADDHA', 'VIDISHA FASHION', NULL, '24ACTPL6685K1ZR', 'Business', NULL, NULL, 9, 9, '$2y$10$EfJYQ82qxJIBDcu7YUyzH.H9mGmpUpXv6kAJ44SMQHThmAuwoizq6', 0, '2021-01-20 15:50:51', '2021-01-20 15:50:51', NULL, 'Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(53, 'NAVAKAR', 'STEELS', 'NAVAKAR STEELS', 'NAVKAR STEEL', NULL, '24AACFN8819J1Z1', 'Business', NULL, NULL, 9, 9, '$2y$10$WCi9I77o6atKCSjdiF6UQOuP.cAPX8OLaAEsTWMRf4O5t1u./rav2', 0, '2021-01-20 15:51:58', '2021-01-20 15:51:58', NULL, 'Retail Business , other', 'Partnership', NULL, NULL, '0.00', NULL, NULL, 0),
(54, 'MOTI', 'MULTI', 'MOTI MULTI PLAST', 'MOTI MULTI PLAST', NULL, '24ABMFM1080B1ZO', 'Business', NULL, NULL, 9, 9, '$2y$10$RCnqNP6bgrfLgh4qJruEFu02mBXAnTa9q96EuwODEHcLlV4mPOM3.', 0, '2021-01-20 15:53:12', '2021-01-20 15:53:12', NULL, 'Office / Sale Office', 'Partnership', NULL, NULL, '0.00', NULL, NULL, 0),
(55, 'UDAYSINGH', 'RAJPUROHIT', 'UDAYSINGH RAJPUROHIT', 'MAJISHA TRADING', NULL, '24BIVPR5165F1ZR', 'Business', NULL, NULL, 9, 9, '$2y$10$6OHHaAbhuve.SOh.qLkN1OORLnyx3rWBMSd4AtKXas2CxyQc3SPuG', 0, '2021-01-20 15:54:10', '2021-01-20 15:54:10', NULL, 'Retail Business , Wholesale Business , other', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(56, 'FENIL', 'MANOJKUMAR', 'FENIL MANOJKUMAR FANCY', 'SHREYANSH PLASTIC', NULL, '24ABQPF3255M1ZE', 'Business', NULL, NULL, 9, 9, '$2y$10$MHJdALEnUK4uN65oFNppZue2s9SDoFEiW63KCRcFWRwkAqsBjpLpa', 0, '2021-01-20 15:55:06', '2021-01-20 15:55:06', NULL, 'Retail Business , Wholesale Business , Warehouse / Depot', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(57, 'BABULAL', 'PARSURAMBHAI', 'BABULAL PARSURAMBHAI DAVE', 'CLASSIC STEEL', NULL, '24AJBPD5442M1ZD', 'Business', NULL, NULL, 9, 9, '$2y$10$rGMFZ6u4pIVUSq1CvlU6N.Lr3GHzS3TySyqpDvF5aZY7TMnwCjSNO', 0, '2021-01-20 15:56:08', '2021-01-20 15:56:08', NULL, 'Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(58, 'RAJENDRAKUMAR', 'KESHAVLAL', 'RAJENDRAKUMAR KESHAVLAL DOSHI', 'RIDHI HOSIERY', NULL, '24ACEPD4300L1Z4', 'Business', NULL, NULL, 9, 9, '$2y$10$r0dBpUOFRpZ9yqQutHQ2DeyMetAgurcpoxXcvhopdfErZzDA56voC', 0, '2021-01-20 15:57:20', '2021-01-20 15:57:20', NULL, 'Retail Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(59, 'Gheva', 'Ram', 'Gheva Ram Prajapat', 'SANDHYA NOVELTY', NULL, '24BPPPP8089F1Z8', 'Business', NULL, NULL, 9, 9, '$2y$10$VPQvHz9jILtjHRHl1VTfs.8bLdUm3HQvR0s3Gt.iJoTIjWy4y5XjK', 0, '2021-01-20 15:58:57', '2021-01-20 15:58:57', NULL, 'Retail Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(60, 'BHARATBHAI', 'DHIRUBHAI', 'BHARATBHAI DHIRUBHAI PATEL', 'PARAM PACKAGING', NULL, '24ADSPP6012E1ZM', 'Business', NULL, NULL, 9, 9, '$2y$10$lwDShZCtFHBhGRsgwQTLq.fO0oW9UhlDrElG.GvbC4Po9SzNsXTOq', 0, '2021-01-20 16:00:01', '2021-01-20 16:00:01', NULL, ' Retail Business , Factory / Manufacturing ,Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(61, 'babulal', '', 'babulal', 'ARBUDA AGENCY', NULL, '24BPZPB0308H1Z2', 'Business', NULL, NULL, 9, 9, '$2y$10$G8kb5wetjsx3rovLn5.n9O2nF0P64qE0WkgmQS/P7xJDNELQljBwK', 0, '2021-01-20 16:01:06', '2021-01-20 16:01:06', NULL, 'Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(62, 'HIMATSINGH', 'GANESHLAL', 'HIMATSINGH GANESHLAL JAIN', 'ANAND TRADERS', NULL, '24AAYPJ7048P2ZQ', 'Business', NULL, NULL, 9, 9, '$2y$10$ePVS8w1FdIH2tDqACyuYZ.dly.kSGpD9xSTaqf6uVt/FOyx.Kl9Ty', 0, '2021-01-20 16:01:58', '2021-01-20 16:01:58', NULL, 'Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(63, 'NARESH', 'JASHWANTBHAI', 'NARESH JASHWANTBHAI TANNA', 'AARTI AGARBATTI', NULL, '24ABZPT8245Q1ZB', 'Business', NULL, NULL, 9, 9, '$2y$10$cG8CDG2NrfQ3C/q.A7Quburt2Sn8ltLLJU8Jt9YLfHxl8ExoD/FbG', 0, '2021-01-20 16:02:56', '2021-01-20 16:02:56', NULL, 'Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(64, 'KALPESHKUMAR', 'MATHURBHAI', 'KALPESHKUMAR MATHURBHAI RAKHOLIYA', 'SHIV HOSIERY', NULL, '24APKPR7722P1Z5', 'Business', NULL, NULL, 9, 9, '$2y$10$FNA7jl0JGTrCRi6cM08DluVLQkslzYurV.J2iAxlNPSSUngX/lWbC', 0, '2021-01-20 16:04:05', '2021-01-20 16:04:05', NULL, 'Retail Business , Wholesale Business', 'Retail Business , Wholesale Business', NULL, NULL, '0.00', NULL, NULL, 0),
(65, 'GAYATRI', 'SUDARSHANDEV', 'GAYATRI SUDARSHANDEV PATEL', 'NIDDHI GARMENTS', NULL, '24AZOPP6521G1Z7', 'Business', NULL, NULL, 9, 9, '$2y$10$0ZOgYtkPnpyN4r9Y096uQ.pw/ZtXztX755tske6sOM0Lycz4DLxOG', 0, '2021-01-20 16:05:24', '2021-01-20 16:05:24', NULL, 'Factory / Manufacturing  Retail Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(66, 'AJAYKUMAR', 'JAYENDRABHAI', 'AJAYKUMAR JAYENDRABHAI JADAV', 'HIGH TECH INDUSTRIAL HOSES', NULL, '24ACLPJ3346H1ZO', 'Business', NULL, NULL, 9, 9, '$2y$10$rwLWVRxX/vjESSa0Ww05o.ngkUH0HcNFNJS/tiwCdTSuyPV8ZFguW', 0, '2021-01-20 16:06:22', '2021-01-20 16:06:22', NULL, 'Factory / Manufacturing  Retail Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(67, 'RAJURAM', 'VARADARAM', 'RAJURAM VARADARAM', 'MAHALAXMI PLASTIC', NULL, '24AOPPV5565A1ZO', 'Business', NULL, NULL, 9, 9, '$2y$10$g7.ma2/KWijusnXK/6w3BeGcJseS8GDfR9JrXydKlIRiv7cX6N3P.', 0, '2021-01-20 16:07:14', '2021-01-20 16:07:14', NULL, 'Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(68, 'NIMESHKUMAR', 'NARESHBHAI', 'NIMESHKUMAR NARESHBHAI GORAKHIA', 'TRIVENI STEEL CENTER', NULL, '24ACOPG9158M1ZZ', 'Business', NULL, NULL, 9, 9, '$2y$10$AYR6qbkfl5jXHWuNrOB7..sdsDn.vtuQgV4qQmo2CMCDAv0dgYJSi', 0, '2021-01-20 16:08:53', '2021-01-20 16:08:53', NULL, 'Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(69, 'SURESHBHAI', 'BHIKKABHAI', 'SURESHBHAI BHIKKABHAI DESAI', 'PARICHAY CREATION SRT', NULL, '24AHYPD9403D1ZD', 'Business', NULL, NULL, 9, 9, '$2y$10$Vyy97KgOyvTzlMcCu2uBneXYeYD.L.fIpoYuZeGSwiiUEZnI.76Lm', 0, '2021-01-20 16:09:44', '2021-01-20 16:09:44', NULL, 'Factory / Manufacturing, Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(70, 'GANPATSINGH', 'BHURSINGH', 'GANPATSINGH BHURSINGH RATHORE', 'BHAGAWATI TRADING SRT', NULL, '24ARSPR8753J1ZW', 'Business', NULL, NULL, 9, 9, '$2y$10$hmSsIOiLLn2KXrh8SbsKe.W.IDP.6MwCa2YYeLOj0vFH/HdhSK8bm', 0, '2021-01-20 16:11:08', '2021-01-20 16:11:08', NULL, 'Retail Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(71, 'ZOEBBHAI', 'TAIYABBHAI', 'ZOEBBHAI TAIYABBHAI PASSWALA', 'NATIONAL RACSING TRADERS', NULL, '24ABNPP1549F1ZL', 'Business', 'gautam.technomads@gmail.com', NULL, 9, 8, '$2y$10$Rwo.QjUYKaAFLfGLShgVI.XwThz6O4oQtfQbQkC0//BtSKXCNW4/y', 0, '2021-01-20 16:12:35', '2021-05-05 11:19:32', NULL, 'Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(72, 'SURESH', 'KUMAR', 'SURESH KUMAR MOHANLALJI PARMAR', 'SATGURU TRADERS', NULL, '24DIZPP4626P1Z6', 'Business', NULL, NULL, 9, 9, '$2y$10$DC.4lr4thWPOTXly/5ZReOzkWBAoDVu6iECJobqZAPD63gV0Dh8Me', 0, '2021-01-20 16:15:03', '2021-01-20 16:15:03', NULL, 'Retail Business , Wholesale Business , Office / Sale Office ,  Recipient of Goods or Services', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(73, 'ASIM', 'PLAST', 'ASIM PLAST PRIVATE LIMITED', 'ASIM PLAST PVT LTD', NULL, '24AACCA2311B1ZQ', 'Business', NULL, NULL, 9, 9, '$2y$10$2Zy0x59otjFjCaV7aHLLBekY8OmX9HWLmho.uzs2BcjIT/0so1IG2', 0, '2021-01-20 16:16:02', '2021-01-20 16:16:02', NULL, 'Retail Business , Factory / Manufacturing', 'Private Limited Company', NULL, NULL, '0.00', NULL, NULL, 0),
(74, 'NAVJIVAN', 'MOTORS', 'NAVJIVAN MOTORS PRIVATE LIMITED', 'NAVJIVAN MOTOR PVT LTD', NULL, '24AABCN7197D1ZG', 'Business', NULL, NULL, 9, 9, '$2y$10$Qp/BB6TMQ5PWRiS..VjgQuDg3OBeGfXl93h.448hG2X8h6cyhgIN2', 0, '2021-01-20 16:17:16', '2021-01-20 16:17:16', NULL, 'Retail Business , Office / Sale  Services', 'Private Limited Company', NULL, NULL, '0.00', NULL, NULL, 0),
(75, 'DHARMENDRA', 'ISHWERLAL', 'DHARMENDRA ISHWERLAL GORAKHIYA', 'RAJESHREE STEEL CENTER', NULL, '24ABCPG0556E1ZA', 'Business', NULL, NULL, 9, 9, '$2y$10$ErHnteZxJ8GQoPvCM1BxNu5Mr6.K3QTJj0aBDxZi4.FPb/DzSu5H.', 0, '2021-01-20 16:18:24', '2021-01-20 16:18:24', NULL, 'Factory / Manufacturing, Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(76, 'NAYSAR', 'NARESH', 'NAYSAR NARESH MARFATIA', 'N.N.TRADERS', NULL, '24AMVPM1759P1Z4', 'Business', NULL, NULL, 9, 9, '$2y$10$IY.LF0GCdY7nKpJqpTyHaeQ12Q1PgxgZrpn9Z45.9gEhjUbIOGvku', 0, '2021-01-20 16:19:32', '2021-01-20 16:19:32', NULL, 'Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(77, 'ARESHBHAI', 'LABHUBHAI', 'ARESHBHAI LABHUBHAI KUMBHANI', 'SHREE KHODIYAR CUTLERY STORES', NULL, '24AUNPK2679Q1ZR', 'Business', NULL, NULL, 9, 9, '$2y$10$ZoF/Gdp9wh9n8DCLF8M7w.9j7uSH.S3q.sj9O5aP7SwP/XZTEaOZO', 0, '2021-01-20 16:20:24', '2021-01-20 16:20:24', NULL, 'Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(78, 'VIJAY', 'SALES', 'VIJAY SALES (INDIA) PRIVATE LIMITED', 'VIJAY SALES', NULL, '24AAHCV3778L1ZQ', 'Business', NULL, NULL, 9, 9, '$2y$10$oG89lI21P0DJzuvtdmdHdeCSk1dyn8JqzjXHUMC0cApqqlvr6h7l6', 0, '2021-01-20 16:21:28', '2021-01-20 16:21:28', NULL, 'Retail Business , Wholesale Business', 'Private Limited Company', NULL, NULL, '0.00', NULL, NULL, 0),
(79, 'YASHWANTSINGH', 'GUMAN', 'YASHWANTSINGH GUMAN RAJPUROHIT', 'PUROHIT COSMETIC.SRT', NULL, '24BEFPR4582A1ZN', 'Business', NULL, NULL, 9, 9, '$2y$10$LxCF5F7O7Irpd9BHUjJvDuFnk3XvgLLitmhrhVFwlcDqDLYPkM4..', 0, '2021-01-20 16:23:13', '2021-01-20 16:23:13', NULL, 'Retail Business , Wholesale Business , Office / Sale Office ,  Recipient of Goods or Services', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(80, 'YOGESH', 'S', 'YOGESH S PURANIK', 'SHRADHDHA TEXTILES', NULL, '24AQMPP2036M1ZK', 'Business', NULL, NULL, 9, 9, '$2y$10$KECrvaFLkL19lFVJFqv6cePE7itUNMgco2iU/VlThBq4KtRcncJV2', 0, '2021-01-20 16:24:08', '2021-04-22 10:30:45', NULL, 'Wholesale Business', 'Proprietorship', NULL, NULL, '123.00', NULL, NULL, 0),
(81, 'ANILKUMAR', 'TULSIDAS', 'ANILKUMAR TULSIDAS GANDHI', 'NEW RAMDEV STEEL', NULL, '24ABTPG2049K1ZG', 'Business', NULL, NULL, 9, 9, '$2y$10$gSuL1MQh/RaBhinkDSLj/e6uVruAaFbBgkKe5Eii84PzdCAhUHljy', 0, '2021-01-20 16:24:59', '2021-01-20 16:24:59', NULL, 'Retail Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(82, 'PAPPU', 'TARACHAND', 'PAPPU TARACHAND MORYA', 'POOJA FOOTWEAR', NULL, '24DGXPM8965A1ZT', 'Business', NULL, NULL, 9, 9, '$2y$10$rPCRLvfwiyReD8zKwHJGieJZ5n72R.sqcLEz3y5zB3kb3.JfF0rby', 0, '2021-01-20 16:25:55', '2021-01-20 16:25:55', NULL, 'Retail Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(83, 'MAHENDRA', 'KUMAR', 'MAHENDRA KUMAR', 'ARBUDA TRADERS', NULL, '24CNOPK0467J1ZQ', 'Business', NULL, NULL, 9, 9, '$2y$10$grK4iSp5uOLeVVNMbd2bGuabwb9fZqdjDQHSPLjRCytcMR2sZO0OW', 0, '2021-01-20 16:27:06', '2021-01-20 16:27:06', NULL, 'Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(84, 'SIGNATURE', 'DESIGN', 'SIGNATURE DESIGN', 'SIGNATURE DESIGN', NULL, '24ADBFS1634E1ZJ', 'Business', NULL, NULL, 9, 9, '$2y$10$4jjkkrx51zvv/si9aVx17uP/F5fLs7CAP3UoLSesUeYAFc22ESelS', 0, '2021-01-20 16:27:56', '2021-01-20 16:27:56', NULL, 'Warehouse / Depot ,RETAIL BUSINESS', 'PARTNERSHIP', NULL, NULL, '0.00', NULL, NULL, 0),
(85, 'DIPARAM', 'VAGTARAMJI', 'DIPARAM VAGTARAMJI CHAUDHARY', 'SANGITA GIFT AND TOYS', NULL, '24AUDPC9567H1ZJ', 'Business', NULL, NULL, 9, 9, '$2y$10$YF3l1rhrRytJnbqHDZcW0eFTuu7wMC4hPt3UjCgxGmYUH1UO9xHaq', 0, '2021-01-20 16:28:54', '2021-01-20 16:28:54', NULL, 'Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(86, 'NANDLAL', 'JAMATMAL', 'NANDLAL JAMATMAL RELWANI', 'KAILASH TRADERS', NULL, '24ABMPR0178H1ZH', 'Business', NULL, NULL, 9, 9, '$2y$10$VaZA6ZexwiIH40DkTModFef0f8cPMbFDUj2.ZHo883zTtFB7FKUM2', 0, '2021-01-20 16:29:53', '2021-01-20 16:29:53', NULL, 'Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(87, 'KALPANABEN', 'JATINBHAI', 'KALPANABEN JATINBHAI NATHWANI', 'PRAMUKH READYMADE STORES', NULL, '24ACTPN3893P1ZJ', 'Business', NULL, NULL, 9, 9, '$2y$10$pSCzuDd9FYwCsU/muqgMnOcEv27mIEsu4NGi62Vh2BOBskmM.AuLW', 0, '2021-01-20 16:30:54', '2021-01-20 16:30:54', NULL, 'Factory / Manufacturing , Retail Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(88, 'MANISH', 'PRAKASHCHANDRA', 'MANISH PRAKASHCHANDRA KEWEDIA', 'S.M.ENTERPRISE', NULL, '24ALRPK6029P1ZF', 'Business', NULL, NULL, 9, 9, '$2y$10$LBkVSfbm8uzskzzS/d7zfuajnXIUL7Cf7QxYCkeiq89MrOw.jULP2', 0, '2021-01-20 16:31:53', '2021-01-20 16:31:53', NULL, 'Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(89, 'MANOJKUMAR', 'SHANKARLAL', 'MANOJKUMAR SHANKARLAL', 'OM SAI FASHION', NULL, '24BQYPS2965R1ZG', 'Business', NULL, NULL, 9, 9, '$2y$10$/iss3tfqGzQ5TATcSKkaYOy.t2Dr8yLmiB2d.hP6gZmDF75T713hC', 0, '2021-01-20 16:32:57', '2021-01-20 16:32:57', NULL, 'Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(90, 'ASHOK', 'SINGH', 'ASHOK SINGH RAJPUROHIT', 'ROHINI NX', NULL, '24ALVPR2756G1ZL', 'Business', NULL, NULL, 9, 9, '$2y$10$thRCTQYa6jz1bvyl6D7bYuD5FUPLJXy4mWd7SMIQrQ.7E0MU0xe0y', 0, '2021-01-20 16:33:49', '2021-01-20 16:33:49', NULL, 'Retail Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(91, 'RENISH', 'MANOJBHAI', 'RENISH MANOJBHAI RADADIYA', 'BALAJI CREATION', NULL, '24DNZPR6165L1ZW', 'Business', NULL, NULL, 9, 9, '$2y$10$EpW0d8IEUKRHbVTplW9aOexJ6pTa8ayfMjoIO3mIQcsTcjwmt/5oi', 0, '2021-01-20 16:34:51', '2021-01-20 16:34:51', NULL, 'Factory / Manufacturing ,OTHER', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(92, 'RAMESHBHAI', 'HARIRAMBHAI', 'RAMESHBHAI HARIRAMBHAI CHHURA', 'R.K.CYCLE', NULL, '24AAYPC8536B1ZO', 'Business', NULL, NULL, 9, 9, '$2y$10$.nESNXHnDzzcrnlk3EQ.n.ZDmH8lW36A5uTw3enOEidxjfJ7g5Z.C', 0, '2021-01-20 16:35:47', '2021-01-20 16:35:47', NULL, 'Retail Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(93, 'KATARIA', 'AUTOMOBILES', 'KATARIA AUTOMOBILES PRIVATE LIMITED', 'KATARIA AUTOMOBILES PVT LTD', NULL, '24AAACK6221C1Z7', 'Business', NULL, NULL, 9, 9, '$2y$10$llCcBGsmJ61f0qBOz9ffRuHD7IumyJOsg6VBr2zUkw2Jf8fNJjZAa', 0, '2021-01-20 16:38:16', '2021-01-20 16:38:16', NULL, 'Office / Sale Office,  Retail Business, Service Provision, Recipient of Goods or Services, Supplier of Services, Warehouse / Depot, Leasing Business', 'Private Limited Company', NULL, NULL, '0.00', NULL, NULL, 0),
(94, 'ANSHUL', 'KALWADIYA', 'ANSHUL KALWADIYA', 'SHRI GAYATRI HANDLOOM', NULL, '24DFWPK1969C1Z4', 'Business', NULL, NULL, 9, 9, '$2y$10$5JF4TQibQnwpRITvThr7PeUSB2EgfOEtmX7KIfzYnOGPGaPNrEdU.', 0, '2021-01-20 16:39:28', '2021-01-20 16:39:28', NULL, 'Retail Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(95, 'ATUL', 'KANAIYALAL', 'ATUL KANAIYALAL DOSHI', 'ADINATH TRADERS', NULL, '24AAYPD4212B1Z6', 'Business', NULL, NULL, 9, 9, '$2y$10$92f71EU44s9hFZqRL3GFe.Eu7pCgtl8B/zZ73nZjpUd71kwQi2B3C', 0, '2021-01-20 16:40:54', '2021-01-20 16:40:54', NULL, 'Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(96, 'JABRARAM', 'LUHAR', 'JABRARAM LUHAR', 'SAGAR BENGAL HOUSE', NULL, '24AJMPL5210A1ZT', 'Business', NULL, NULL, 9, 9, '$2y$10$brX67t8ulYT1i0FVJxKaR.7CmlGZz5RiEqrnwnlnfLDqBaihRARAG', 0, '2021-01-20 16:42:12', '2021-01-20 16:42:12', NULL, 'Retail Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(97, 'THE', 'CARD', 'THE CARD COMPANY', 'THE CARD COMPANY', NULL, '24AAMFT2897C1ZY', 'Business', NULL, NULL, 9, 9, '$2y$10$HeeBt6XoIS9QIeTsQjz9GOVh8szE718MJoce2P5jvGRHpx6csIrzu', 0, '2021-01-20 16:43:07', '2021-01-20 16:43:07', NULL, 'Retail Business', 'Partnership', NULL, NULL, '0.00', NULL, NULL, 0),
(98, 'KISHOREBHAI', 'KANJIBHAI', 'KISHOREBHAI KANJIBHAI THAKKAR', 'AMIT STEEL CENTER', NULL, '24AAWPT1313P1Z3', 'Business', NULL, NULL, 9, 9, '$2y$10$Gmmg17d08w83enFXIgCdoOnyuJq2ULYAkRW9njVSVeQXWtHNQWSzK', 0, '2021-01-20 16:44:10', '2021-01-20 16:44:10', NULL, 'Retail Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(99, 'BHAVESHKUMAR', 'JAYANTILAL', 'BHAVESHKUMAR JAYANTILAL SHAH', 'VARDHMAN HOSIERY TRADERS', NULL, '24AMKPS8173L1ZB', 'Business', NULL, NULL, 9, 9, '$2y$10$xkpep9okfaXKYeDVYHt0TeWpCeIz.mHbho0CxXSlQvAmYD3CYkFOa', 0, '2021-01-20 16:46:10', '2021-01-20 16:46:10', NULL, 'Retail Business , Wholesale Business ,Office / Sale Office', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(100, 'DHIRAJ', 'SHANTILAL', 'DHIRAJ SHANTILAL JAIN', 'ARIHANT STEEL HOUSE', NULL, '24AFUPJ7773E1Z0', 'Business', NULL, NULL, 9, 9, '$2y$10$2CAKWb6q8aFI.H5//.eX.uBz8B2UOzRPIq1llbrHkbO4X7NV4DBGS', 0, '2021-01-20 16:47:08', '2021-01-20 16:47:08', NULL, 'Retail Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(101, 'VIJAYKUMAR', 'CHIMANLAL', 'VIJAYKUMAR CHIMANLAL CHELARAMANI', 'VIJAY HOSIERY', NULL, '24AASPC6655R1ZX', 'Business', NULL, NULL, 9, 9, '$2y$10$7/bYICOg2UsDqsqy3ax0/uuQzVmLo2ThtUMxnMFCFxXI1R.ZzMDWS', 0, '2021-01-20 17:02:33', '2021-01-20 17:02:33', NULL, 'Retail Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(102, 'KAMAL', 'GOPALDAS', 'KAMAL GOPALDAS CHANDWANI', 'SRI GANESH HOSIERY', NULL, '24AFHPC9804N1Z9', 'Business', NULL, NULL, 9, 9, '$2y$10$BBZwLkilUrRoTt5q8h.Ln.QAWYUg8Rzvh/WEXNAwbWvTfFJmfVwkO', 0, '2021-01-20 17:03:50', '2021-01-20 17:03:50', NULL, 'Recipient of Goods or Services , Wholesale Business`', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(103, 'MUKESH', 'KUMAR', 'MUKESH KUMAR MEHAR', 'KAMLA SALES', NULL, '24AIIPM8841R1ZG', 'Business', NULL, NULL, 9, 9, '$2y$10$SMLbALpJ/X2inpqYDgDemu/sf/XNtvqa9sWdJzvN6y4MRC/PTFni.', 0, '2021-01-20 17:04:47', '2021-01-20 17:04:47', NULL, 'Retail Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(104, 'BURHAN', 'SAIFUDDIN', 'BURHAN SAIFUDDIN RATLAMWALA', 'BURHANI ENTERPRISE', NULL, '24AAYPR5043G1ZB', 'Business', NULL, NULL, 9, 9, '$2y$10$biH8MJt4IX6BosQhcDhcx.YPOVl3rCTfzl4jVgc1Hj8UfpT8grXza', 0, '2021-01-20 17:05:51', '2021-01-20 17:05:51', NULL, 'Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(105, 'PANKAJ', 'LALJIBHAI', 'PANKAJ LALJIBHAI KAKADIYA', 'YAMUNA KRUPA ENTERPRISE', NULL, '24ARFPK3014N2ZV', 'Business', NULL, NULL, 9, 9, '$2y$10$rOfkaAyV3Ari8fkVtTlODOfBLeXQ.X48K8YuyJfltjZ8bFJFnL4Ja', 0, '2021-01-20 17:06:40', '2021-01-20 17:06:40', NULL, 'Office / Sale Office,  Retail Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(106, 'KORAT', 'PRASHANT', 'KORAT PRASHANT RAJESHBHAI', '# VILLAIN', NULL, '24HJTPK0855C1Z3', 'Business', NULL, NULL, 9, 9, '$2y$10$T3cy2fDefjIy0WYSZGQ8MudgxEdcqekpfilqa93uI6g3KIO7CbvJi', 0, '2021-01-20 17:07:32', '2021-01-20 17:07:32', NULL, 'Office / Sale Office,  Retail Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(107, 'KISTURARAM', 'CHOUDHARY', 'KISTURARAM CHOUDHARY', 'LAXMI HOSIERY', NULL, '24AKMPR1961K1ZQ', 'Business', NULL, NULL, 9, 9, '$2y$10$5e6fga2tSNTzaw8yPvqW..f8Kej1RcPUYiujGu11GAPsk2n7yvoKG', 0, '2021-01-20 17:08:21', '2021-01-20 17:08:21', NULL, 'Retail Business , Wholesale Business , Warehouse / Depot', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(108, 'MANSURALI', 'NANABHAI', 'MANSURALI NANABHAI POONAWALA', 'M G POONAWALA TRADING CO', NULL, '24ABNPP0996N1ZV', 'Business', NULL, NULL, 9, 9, '$2y$10$5dkptrrHn5AjwOidSttVNetHAPG2Flqwrmqhj0VxqAWOiAgT6O6F.', 0, '2021-01-20 17:09:12', '2021-01-20 17:09:12', NULL, 'Retail Business , Wholesale Business ,Factory / Manufacturing', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(109, 'HITESH', 'JETHANAND', 'HITESH JETHANAND VATWANI', 'SHIVAM FASHION POINT', NULL, '24AJQPV7110B1ZA', 'Business', NULL, NULL, 9, 9, '$2y$10$PdlsmDYSHdHGdVmibw5eXOeOUGdr6kbSLttL6D/GcA9YZ3EuBhu/O', 0, '2021-01-20 17:10:15', '2021-01-20 17:10:15', NULL, 'Retail Business, Recipient of Goods or Services', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(110, 'VIJAY', 'LADHURAM', 'VIJAY LADHURAM KALAL', 'J & K FASHION', NULL, '24AIZPK3175B1Z5', 'Business', NULL, NULL, 9, 9, '$2y$10$Z0A690XjjLe9PmCUZAchWe3HhvjU3dbu1TsSMXif/xILo6VjKwoQC', 0, '2021-01-20 17:11:08', '2021-01-20 17:11:08', NULL, 'Retail Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(111, 'STAR', 'ENTERPRISE', 'STAR ENTERPRISE', 'STAR ENTERPRISE', NULL, '24ABSFS0792G1ZT', 'Business', NULL, NULL, 9, 9, '$2y$10$2MS.doT6mo30m1e20QlW4OA3aX/0tvyecxpmKTNzK3WmTrY9OlGJK', 0, '2021-01-20 17:12:02', '2021-01-20 17:12:02', NULL, 'Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(112, 'ARVIND', 'CHIMANLAL', 'ARVIND CHIMANLAL SARAIYA', 'ARVIND CHIMANLAL SARAIYA', NULL, '24AGSPS1535L1ZW', 'Business', NULL, NULL, 9, 9, '$2y$10$GOdrDHqLaNoUOAhSFRixHuq9cCuOfUSE10cjJhA1nbTKVBPcO/Ope', 0, '2021-01-20 17:13:05', '2021-01-20 17:13:05', NULL, 'Retail Business  OTHER', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(113, 'KASHYAP', 'RAJESH', 'KASHYAP RAJESH SEJPAL', 'ANANT TRADING/ BABA BABY', NULL, '24DHTPS4601C1ZC', 'Business', NULL, NULL, 9, 9, '$2y$10$Q7xlQODoTj70gLP4UtQRM./Nf6kQGv53vdLQaOaJkv23lLEFofyP6', 0, '2021-01-20 17:14:04', '2021-01-20 17:14:04', NULL, 'Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(114, 'CHINTANKUMAR', 'BHARATKUMAR', 'CHINTANKUMAR BHARATKUMAR DOSHI', 'NAVPAD RUMAL HOUSE', NULL, '24AFEPD0874Q1Z9', 'Business', NULL, NULL, 9, 9, '$2y$10$Ge56nPjgFOySbKS/28eOpeqQEFDAY6iXx9SJrMjKV21oI7BzxcYxe', 0, '2021-01-20 17:15:05', '2021-01-20 17:15:05', NULL, 'Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(115, 'BHAWANI', 'SHANKAR', 'BHAWANI SHANKAR RATHI', 'ATTITUDE FASHION HUB', NULL, '24AZDPR7234B1ZM', 'Business', NULL, NULL, 9, 9, '$2y$10$PI7PdhuMUPB98FVUXO8/HumCIUDE6kSy8Dgf32RBw8h7qHGSX1dGK', 0, '2021-01-20 17:16:01', '2021-01-20 17:16:01', NULL, 'Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(116, 'PRAVINKUMAR', 'SHAMJIBHAI', 'PRAVINKUMAR SHAMJIBHAI KOTADIYA', 'SURBHI METAL SURAT', NULL, '24ALWPK5306Q1ZE', 'Business', NULL, NULL, 9, 9, '$2y$10$vsN4xBjqvuL2jNTjcBJ6ZuROLxw39LGMcwRp1zk9ePISZoNp0y5/m', 0, '2021-01-20 17:17:00', '2021-01-20 17:17:00', NULL, 'Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(117, 'HARDIK', 'RADHESHYAMBHAI', 'HARDIK RADHESHYAMBHAI MUNDRA', 'HARDIK ALLUMINIUM', NULL, '24AQEPM3736H1ZX', 'Business', NULL, NULL, 9, 9, '$2y$10$7l8sWUfA3zAUoRS37EdlgehQfrd7cSqT8sUtBYFR3xwKnHQQ6mQoC', 0, '2021-01-20 17:18:08', '2021-01-20 17:18:08', NULL, 'Retail Business , Wholesale Business', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(118, 'HINABEN', 'DINESHBHAI', 'HINABEN DINESHBHAI BHIMANI', 'KINNARI TEXTILES', NULL, '24ADOPB5068J1ZF', 'Business', NULL, '9725179851', 9, 9, '$2y$10$gbNj0kgpnjOQTdkYr778fuFWo1i50UzTDp05beYAntRRBlTZZXvBG', 0, '2021-01-21 16:01:21', '2021-01-21 16:01:21', NULL, 'Retail Business , Wholesale Business ,Factory / Manufacturing', 'Proprietorship', NULL, NULL, '0.00', NULL, NULL, 0),
(119, 'SHAILESHBHAI', 'LAXMANBHAI', 'SHAILESHBHAI LAXMANBHAI BADRESHIYA', 'YOGIDARSHAN STUDIO', NULL, '24AXAPP8083D1ZI', 'Business', 'yogidarshan963@yahoo.com', '9825153439', 9, 9, '$2y$10$MUGGxhef5pxCIUn.aduaC.H2km/vKq2YNZZRyGlGL6tTijSiM5SZm', 0, '2021-02-15 11:49:43', '2021-02-15 11:49:43', NULL, 'Office / Sale Office', 'Proprietorship', NULL, '0', '0.00', NULL, NULL, 0),
(120, 'MAMTA', 'HIREN', 'MAMTA HIREN JOGIYA', 'MACHINGGO.COM', NULL, '24AHZPJ2345R1ZI', 'Business', NULL, '98982 6502', 9, 9, '$2y$10$bGQhA290v3/.e35IHZJA9OWGnU.gKbh5BP0U7KcI5KLMEN5xcDT.q', 0, '2021-02-15 17:08:28', '2021-02-15 17:08:28', NULL, 'Office / Sale Office', 'Proprietorship', NULL, '0', '0.00', NULL, NULL, 0),
(121, 'PRAVINBHAI', 'HIMMATBHAI', 'PRAVINBHAI HIMMATBHAI JOGIYA', 'BOSS MENS CLOTHING', NULL, '24AEOPJ8837J1ZY', 'Business', NULL, '9898265025', 9, 9, '$2y$10$I36KaqGr2lvDXfTTIiQ6hO.N1Uf1OeI0vmHpXwBRP.uaMd5ZgQCUe', 0, '2021-02-15 17:15:01', '2021-02-15 17:15:01', NULL, 'Warehouse / Depot ,RETAIL BUSINESS', 'Proprietorship', NULL, '0', '0.00', NULL, NULL, 0),
(122, 'LALJI', 'MULJI', 'LALJI MULJI TRANSPORT CO', 'LALJI MULJI TRANSPORT CO', 'Lalji Mulji Transport Co.', '24AAAFL2431J1ZP', 'Transporter', NULL, '9724749016', 9, 9, '$2y$10$DJqYCfHgnX/u1Z71/q23euQvxf9Ir.myrZOgtOVotCnEYr1xElBy6', 0, '2021-02-19 12:04:24', '2021-04-20 18:02:45', NULL, 'Supplier of Services', 'Partnership', NULL, '0', '43940.00', NULL, NULL, 1),
(123, 'K', 'FINS', 'K FINS PUMPS PVT.LTD.', 'K FINS PUMPS PVT.LTD.', NULL, '24AAECK0342C1Z9', 'Business', NULL, '9374921406', 9, 9, '$2y$10$cqPKOciCtCCDFU/1n2le3uhjPV70QdXNe6A/Mia9HX3NVf3/C5TZ.', 0, '2021-02-19 15:35:19', '2021-02-19 15:35:19', NULL, 'Factory / ManufacturingOffice / Sale Office', 'Private Limited Company', NULL, '0', '0.00', NULL, NULL, 0),
(124, 'SUNIL', 'MAHENDRABHAI', 'SUNIL MAHENDRABHAI PATEL', 'SAI ENTERPRISE', NULL, '24AXRPP6749D1Z0', 'Business', NULL, '9979267853', 9, 9, '$2y$10$MuYnip92etABdI0JBmMMbe2nTsznBYeVNdRNyv/jZeNXvDgtwPMYS', 0, '2021-02-22 15:02:01', '2021-02-22 15:02:01', NULL, 'Retail Business, Others', 'Proprietorship', NULL, '0', '0.00', NULL, NULL, 0),
(125, 'Swetaben', 'Hirenbhai', 'Swetaben Hirenbhai Sheth', 'Ankit Enterprise', NULL, '24AZLPS1069B1ZG', 'Business', NULL, '9824159029', 5, 5, '$2y$10$aaQ5yVJgtqWimxnDUJ/zHOO0oj4sbl.MoDw4AQ9UEFLCq7lWFLXxC', 0, '2021-03-01 15:05:10', '2021-03-23 12:11:54', NULL, 'Wholesale', 'Proprietorship', NULL, '0', '0.00', NULL, NULL, 0),
(126, 'KAMAL', 'CHAMPALAL', 'KAMAL CHAMPALAL GHIYA', 'BABITA NARROW FABRICS', NULL, '24ABAPG3940N1ZR', 'Business', NULL, '9737799955', 9, 9, '$2y$10$alKjj/Y4BGOGqmEgdwX8v.SU6/Nu6wXsbmpvErRx/4oG4tP48JZXe', 0, '2021-03-08 17:03:58', '2021-03-08 17:03:58', NULL, 'Office / Sale OfficeWholesale Business', 'Proprietorship', NULL, '0', '0.00', NULL, NULL, 0),
(127, 'SANJAY', 'KUMAR', 'SANJAY KUMAR', 'SHANKESHWAR FAB TEX', NULL, '24ANAPK2836K1Z3', 'Business', NULL, '9375906912', 9, 9, '$2y$10$tYOD43ulGFc9FouYPVWpqO0fHgYAHs.INuRiTLevVVIXO7ogVWr.e', 0, '2021-03-13 15:28:00', '2021-03-13 15:28:00', NULL, 'Office / Sale OfficeWholesale Business', 'Proprietorship', NULL, '0', '0.00', NULL, NULL, 0),
(128, 'SUNIL', 'GUPTA', 'SUNIL GUPTA', 'SHUBHAM CREATION', NULL, '24AJCPG8962E1ZB', 'Business', NULL, '9818534763', 9, 5, '$2y$10$fUP9/.jgoX1OCxrKBGQivOwNP8Jg9p0QXQZ.KeHJH3uO/OwrXSjyi', 0, '2021-03-15 16:31:59', '2021-03-15 17:54:38', NULL, 'Wholesale BusinessRetail BusinessOffice / Sale Office', 'Proprietorship', NULL, '0', '0.00', NULL, NULL, 0),
(129, 'FANCY', 'FABRIC', 'FANCY FABRIC EXPORT', 'FANCY FABRIC EXPORT', NULL, '24AAHFF1209K1ZO', 'Business', NULL, '7778041114', 9, 9, '$2y$10$GMdTayIvwhlrAaOZ9ofzyuRluIFwHCN.bML62J6KdDIJB//y0F2t2', 0, '2021-03-15 16:49:15', '2021-03-15 16:49:15', NULL, 'Retail BusinessWholesale BusinessRecipient of Goods or ServicesExportImportSupplier of Services', 'Partnership', NULL, '0', '0.00', NULL, NULL, 0),
(130, 'VIJAY', 'GOVINDPRASAD', 'VIJAY GOVINDPRASAD AGARWAL', 'KAVERI SAREES', NULL, '27ADYPA9158L1ZP', 'Business', NULL, '9773096780', 9, 9, '$2y$10$5k68KBJoPrOFvIrJ9wbFFO8LvLweP5p02Ys88fTg/cuoDVG0i7JvG', 0, '2021-03-19 12:35:18', '2021-03-19 12:35:18', NULL, 'Wholesale Business', 'Proprietorship', NULL, '0', '0.00', NULL, NULL, 0),
(131, 'SAJJANKUMAR', 'SANTLAL', 'SAJJANKUMAR SANTLAL AGARWAL', 'MAHALAXMI TEXTILE MILLS', NULL, '24AABPA2758A1ZJ', 'Business', NULL, '9913332274', 9, 9, '$2y$10$BdEX64bvhE.zHOkrJsBy0O0.9N/IZiH6dHcUM4vQkANv94nc3wVhq', 0, '2021-03-22 11:25:50', '2021-03-22 11:25:50', NULL, 'Office / Sale OfficeRetail BusinessWholesale Business', 'Proprietorship', NULL, '0', '0.00', NULL, NULL, 0),
(132, NULL, NULL, 'ABCD JHGJHGJ', 'ABCD JHGJHGJ', NULL, 'ABCDEFGHIJ12345', 'Business', 'xyzabc@mail.if', '9375165210', 0, 0, '$2y$10$StiCzcvzZJXHxHPDx3s0TOdy9.FWrAYM46ji66Kn9qD6Pr8Hd60Qq', 0, '2021-04-19 10:52:57', '2021-04-19 10:52:57', '', NULL, NULL, NULL, '0', '0.00', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users_logs`
--

CREATE TABLE `tbl_users_logs` (
  `id` bigint(20) NOT NULL,
  `logs` text DEFAULT NULL,
  `user_id` int(11) NOT NULL COMMENT 'customer id',
  `created_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_vehicles`
--

CREATE TABLE `tbl_vehicles` (
  `id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL DEFAULT 0,
  `vehicle_no` varchar(20) NOT NULL COMMENT 'GJ05 MM 4545',
  `is_active` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-active,1-deactive',
  `about` varchar(255) DEFAULT NULL,
  `driver_assigned_datetime` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `vehicle_img` varchar(100) DEFAULT NULL,
  `rc_book_file` varchar(600) DEFAULT NULL,
  `insurance_file` varchar(600) DEFAULT NULL,
  `permit_file` varchar(600) DEFAULT NULL,
  `puc_file` varchar(600) DEFAULT NULL,
  `insurance_expiry` date DEFAULT NULL,
  `permit_expiry` date DEFAULT NULL,
  `puc_expiry` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_vehicles`
--

INSERT INTO `tbl_vehicles` (`id`, `driver_id`, `vehicle_no`, `is_active`, `about`, `driver_assigned_datetime`, `created_at`, `updated_at`, `vehicle_img`, `rc_book_file`, `insurance_file`, `permit_file`, `puc_file`, `insurance_expiry`, `permit_expiry`, `puc_expiry`) VALUES
(1, 0, 'GJ-05-DR-2452', 2, NULL, '2021-02-26 11:19:48', '2021-02-26 11:19:48', '2021-02-26 11:19:48', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 0, 'Gj-05-dr-2453', 2, NULL, '2021-02-26 11:23:38', '2021-02-26 11:23:38', '2021-02-26 11:23:38', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 3, 'GJ 05 BY 0992', 0, NULL, '2021-02-26 11:33:35', '2021-02-26 11:23:50', '2021-02-26 11:33:35', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 20, 'GJ07UU1469', 0, NULL, '2021-04-02 15:00:50', '2021-02-26 11:24:00', '2021-04-02 15:00:50', NULL, 'rc605af47cb70041616573564.jpeg', 'ins605af47cb759b1616573564.jpeg', 'per605af47cb82a51616573564.jpeg', NULL, '2021-09-30', '2021-05-01', NULL),
(5, 5, 'GJ 05 AU 3104', 0, NULL, '2021-02-26 11:33:02', '2021-02-26 11:24:08', '2021-02-26 11:33:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 6, 'GJ 05 CT 4131', 0, NULL, '2021-03-09 10:30:57', '2021-03-01 12:04:01', '2021-03-24 13:40:00', NULL, 'rc605af3d8051b31616573400.jpeg', NULL, NULL, 'puc605af3d805a021616573400.jpeg', NULL, NULL, '2021-10-28'),
(7, 7, 'GJ 05 BW 0646', 0, NULL, '2021-03-01 13:19:58', '2021-03-01 13:19:09', '2021-03-24 13:34:43', NULL, 'rc605af29bcead71616573083.jpeg', 'ins605af29bcf3081616573083.jpeg', 'per605af29bcf7d41616573083.jpeg', 'puc605af29bcfba91616573083.jpeg', '2021-04-22', '2021-05-01', '2021-10-28'),
(8, 19, 'GJ 05 RA 8833', 0, 'Honda City', '2021-03-22 16:59:44', '2021-03-20 13:52:02', '2021-03-22 16:59:44', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_vehicle_files`
--

CREATE TABLE `tbl_vehicle_files` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vehicle_id` int(11) NOT NULL DEFAULT 0,
  `img` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'file',
  `img_type` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-active,1-deactive, 2- deleted',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_wallet_creditlist`
--

CREATE TABLE `tbl_wallet_creditlist` (
  `id` bigint(20) NOT NULL,
  `wallet_amount` decimal(11,2) NOT NULL DEFAULT 0.00 COMMENT 'Rupees',
  `wallet_credit` decimal(11,2) NOT NULL DEFAULT 0.00 COMMENT 'credits',
  `is_active` tinyint(1) NOT NULL DEFAULT 0 COMMENT '	0-active,1-deactive, 2- deleted',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_wallet_creditlist`
--

INSERT INTO `tbl_wallet_creditlist` (`id`, `wallet_amount`, `wallet_credit`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '1000.00', '1110.00', 0, '2021-04-09 11:36:10', '2021-04-09 11:36:10'),
(2, '9999.00', '12050.00', 0, '2021-04-09 11:36:10', '2021-04-28 16:39:28'),
(3, '5000.00', '5500.00', 0, '2021-04-09 11:36:10', '2021-04-09 11:36:10'),
(4, '500.00', '592.00', 0, '2021-04-27 11:36:10', '2021-04-28 16:33:32'),
(5, '111.00', '125.00', 0, '2021-04-28 16:27:49', '2021-04-28 16:39:19');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_wallet_transaction`
--

CREATE TABLE `tbl_wallet_transaction` (
  `id` bigint(20) NOT NULL,
  `transaction_number` varchar(40) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `order_id` int(11) NOT NULL DEFAULT 0,
  `transaction_datetime` datetime NOT NULL,
  `transaction_amount` decimal(11,2) NOT NULL DEFAULT 0.00 COMMENT 'Rupees',
  `transaction_credit` decimal(11,2) NOT NULL DEFAULT 0.00 COMMENT 'transaction_credit used',
  `transaction_type` varchar(2) NOT NULL DEFAULT 'Dr' COMMENT 'Dr,Cr',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `is_manually_added` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-no, 1- yes',
  `admin_id` int(11) NOT NULL DEFAULT 0 COMMENT 'added by adminid else 0',
  `is_active` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-active,1-deactive, 2- deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_wallet_transaction`
--

INSERT INTO `tbl_wallet_transaction` (`id`, `transaction_number`, `user_id`, `order_id`, `transaction_datetime`, `transaction_amount`, `transaction_credit`, `transaction_type`, `created_at`, `updated_at`, `notes`, `is_manually_added`, `admin_id`, `is_active`) VALUES
(1, 'pay_GxvZHClicnxYc6', 1, 0, '2021-04-12 12:59:50', '596.00', '596.00', 'Cr', '2021-04-12 12:59:50', '2021-04-12 12:59:50', 'RazorPay Order Id: order_GxvYLMQKVh8jZb', 0, 0, 0),
(2, 'pay_Gxw2VU84Ta3TND', 1, 0, '2021-04-12 13:27:31', '523.00', '523.00', 'Cr', '2021-04-12 13:27:31', '2021-04-12 13:27:31', 'RazorPay Order Id: order_Gxw2NzvECMXKjn', 0, 0, 0),
(3, 'pay_Gxw6L7xxX6BNcI', 1, 0, '2021-04-07 13:31:09', '152.00', '152.00', 'Cr', '2021-04-12 13:31:09', '2021-04-12 13:31:09', 'RazorPay Order Id: order_Gxw6DtgK4ntwo9', 0, 0, 0),
(4, 'pay_Gxw8oAYj0WAxJS', 1, 0, '2021-04-12 13:33:28', '269.00', '269.00', 'Cr', '2021-04-12 13:33:28', '2021-04-12 13:33:28', 'RazorPay Order Id: order_Gxw8jBuW3SNI1g', 0, 0, 0),
(5, 'pay_GxwBGCJcF0moW6', 1, 0, '2021-04-12 13:35:47', '458.00', '458.00', 'Cr', '2021-04-12 13:35:47', '2021-04-12 13:35:47', 'RazorPay Order Id: order_GxwBBqOVVWjseq', 0, 0, 0),
(6, 'pay_GwO31GkJTrMlBf', 1, 0, '2021-04-16 17:38:27', '616.00', '616.00', 'Cr', '2021-04-16 17:38:27', '2021-04-16 17:38:27', 'RazorPay Order Id: order_GwO2RNAU8mcM3q', 1, 0, 0),
(7, 'pay_GwO31GkJTrMlBf', 1, 0, '2021-04-16 17:38:40', '616.00', '616.00', 'Cr', '2021-04-16 17:38:40', '2021-04-16 17:38:40', 'RazorPay Order Id: order_GwO2RNAU8mcM3q', 1, 0, 0),
(8, 'pay_GwO31GkJTrMlBf', 1, 0, '2021-04-16 17:54:45', '616.00', '616.00', 'Cr', '2021-04-16 17:54:45', '2021-04-16 17:54:45', 'RazorPay Order Id: order_GwO2RNAU8mcM3q', 1, 0, 0),
(9, '171', 122, 171, '2021-04-19 17:50:50', '111.00', '111.00', 'Dr', '2021-04-19 17:50:50', '2021-04-19 17:50:50', 'Used With Wallet Order', 0, 0, 0),
(10, '175', 122, 175, '2021-04-19 18:03:45', '49.00', '49.00', 'Dr', '2021-04-19 18:03:45', '2021-04-19 18:03:45', 'Used With Wallet Order', 0, 0, 0),
(11, '180', 122, 180, '2021-04-19 18:22:06', '112.00', '112.00', 'Dr', '2021-04-19 18:22:06', '2021-04-19 18:22:06', 'Used With Wallet Order', 0, 0, 0),
(12, '184', 122, 184, '2021-04-20 12:53:14', '37.00', '37.00', 'Dr', '2021-04-20 12:53:14', '2021-04-20 12:53:14', 'Used With Wallet Order', 0, 0, 0),
(13, 'ORDERID_185', 122, 185, '2021-04-20 17:33:17', '1968.00', '1968.00', 'Dr', '2021-04-20 17:33:17', '2021-04-20 17:33:17', 'Used With Wallet Order', 0, 0, 0),
(14, 'ORDERID_191', 122, 191, '2021-04-20 17:41:05', '1020.00', '1020.00', 'Dr', '2021-04-20 17:41:05', '2021-04-20 17:41:05', 'Used With Wallet Order', 0, 0, 0),
(15, 'ORDERID_192', 122, 192, '2021-04-20 18:02:45', '5040.00', '5040.00', 'Dr', '2021-04-20 18:02:45', '2021-04-20 18:02:45', 'Used With Wallet Order', 0, 0, 0),
(16, 'ADD_16190100916068', 1, 0, '2021-04-21 18:31:31', '0.00', '52.00', 'Cr', '2021-04-21 18:31:31', '2021-04-21 18:31:31', 'Added By Admin', 1, 11, 0),
(17, 'ADD_16190676455488', 80, 0, '2021-04-22 10:30:45', '0.00', '123.00', 'Cr', '2021-04-22 10:30:45', '2021-04-22 10:30:45', 'Added By Admin', 1, 11, 0);

-- --------------------------------------------------------

--
-- Table structure for table `temp_token`
--

CREATE TABLE `temp_token` (
  `id` bigint(20) NOT NULL,
  `usertype` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1-customer, 2- driver',
  `devicetype` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1-web, 2-android, 3-ios',
  `user_id` bigint(20) NOT NULL,
  `token` varchar(1000) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `temp_token`
--

INSERT INTO `temp_token` (`id`, `usertype`, `devicetype`, `user_id`, `token`, `created_at`) VALUES
(195, 1, 2, 122, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJkZXZpY2V0eXBlIjoyLCJjdXJyZW50ZGF0ZXRpbWUiOjE2MTk4NDk1MTQsInVzZXJ0eXBlIjoxLCJ1c2VyX2lkIjoxMjIsInVuaXFpZCI6IjYwOGNmMTJhOGE5NmQifQ.H_euNrQvBoOwlJuNBmbNSROixDcbOUNDElGLSiOjx1w', '2021-05-01 11:41:54');

-- --------------------------------------------------------

--
-- Table structure for table `testonly`
--

CREATE TABLE `testonly` (
  `id` bigint(20) NOT NULL,
  `data` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access_token`
--
ALTER TABLE `access_token`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_accounts_or_banks`
--
ALTER TABLE `acc_accounts_or_banks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_account_category`
--
ALTER TABLE `acc_account_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_transactions`
--
ALTER TABLE `acc_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_transaction_subcategory`
--
ALTER TABLE `acc_transaction_subcategory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_vendors`
--
ALTER TABLE `acc_vendors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `api_logs`
--
ALTER TABLE `api_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company_configurations`
--
ALTER TABLE `company_configurations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deleted_data_logs`
--
ALTER TABLE `deleted_data_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`(250));

--
-- Indexes for table `razorpay_payments`
--
ALTER TABLE `razorpay_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_account_category`
--
ALTER TABLE `tbl_account_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_account_manage`
--
ALTER TABLE `tbl_account_manage`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_address`
--
ALTER TABLE `tbl_address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_admin_notifications`
--
ALTER TABLE `tbl_admin_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_coupons`
--
ALTER TABLE `tbl_coupons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_customer_notifications`
--
ALTER TABLE `tbl_customer_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_customer_uploaded_files`
--
ALTER TABLE `tbl_customer_uploaded_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_drivers`
--
ALTER TABLE `tbl_drivers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_drivers_files`
--
ALTER TABLE `tbl_drivers_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_driver_accounts`
--
ALTER TABLE `tbl_driver_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_driver_files_type`
--
ALTER TABLE `tbl_driver_files_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_driver_logs`
--
ALTER TABLE `tbl_driver_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_driver_notifications`
--
ALTER TABLE `tbl_driver_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_driver_order_arrangement`
--
ALTER TABLE `tbl_driver_order_arrangement`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_goods_type`
--
ALTER TABLE `tbl_goods_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_inquiry`
--
ALTER TABLE `tbl_inquiry`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_invoice`
--
ALTER TABLE `tbl_invoice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_menu_to_assign`
--
ALTER TABLE `tbl_menu_to_assign`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_orders`
--
ALTER TABLE `tbl_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_order_files`
--
ALTER TABLE `tbl_order_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_order_logs`
--
ALTER TABLE `tbl_order_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_order_parcel_details`
--
ALTER TABLE `tbl_order_parcel_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_otp`
--
ALTER TABLE `tbl_otp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_reasonfor`
--
ALTER TABLE `tbl_reasonfor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_reviews`
--
ALTER TABLE `tbl_reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_role_management`
--
ALTER TABLE `tbl_role_management`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_salesexecutive`
--
ALTER TABLE `tbl_salesexecutive`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_salesexecutive_cutomerlist`
--
ALTER TABLE `tbl_salesexecutive_cutomerlist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_short_helper`
--
ALTER TABLE `tbl_short_helper`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_subscriptions`
--
ALTER TABLE `tbl_subscriptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_subscription_features`
--
ALTER TABLE `tbl_subscription_features`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_subscription_purchase`
--
ALTER TABLE `tbl_subscription_purchase`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_users_logs`
--
ALTER TABLE `tbl_users_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_vehicles`
--
ALTER TABLE `tbl_vehicles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_vehicle_files`
--
ALTER TABLE `tbl_vehicle_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_wallet_creditlist`
--
ALTER TABLE `tbl_wallet_creditlist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_wallet_transaction`
--
ALTER TABLE `tbl_wallet_transaction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `temp_token`
--
ALTER TABLE `temp_token`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testonly`
--
ALTER TABLE `testonly`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access_token`
--
ALTER TABLE `access_token`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=269;

--
-- AUTO_INCREMENT for table `acc_accounts_or_banks`
--
ALTER TABLE `acc_accounts_or_banks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `acc_account_category`
--
ALTER TABLE `acc_account_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `acc_transactions`
--
ALTER TABLE `acc_transactions`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `acc_transaction_subcategory`
--
ALTER TABLE `acc_transaction_subcategory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `acc_vendors`
--
ALTER TABLE `acc_vendors`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `api_logs`
--
ALTER TABLE `api_logs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `company_configurations`
--
ALTER TABLE `company_configurations`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `deleted_data_logs`
--
ALTER TABLE `deleted_data_logs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `razorpay_payments`
--
ALTER TABLE `razorpay_payments`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=150;

--
-- AUTO_INCREMENT for table `tbl_account_category`
--
ALTER TABLE `tbl_account_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `tbl_account_manage`
--
ALTER TABLE `tbl_account_manage`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `tbl_address`
--
ALTER TABLE `tbl_address`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;

--
-- AUTO_INCREMENT for table `tbl_admin_notifications`
--
ALTER TABLE `tbl_admin_notifications`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=212;

--
-- AUTO_INCREMENT for table `tbl_coupons`
--
ALTER TABLE `tbl_coupons`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_customer_notifications`
--
ALTER TABLE `tbl_customer_notifications`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=300;

--
-- AUTO_INCREMENT for table `tbl_customer_uploaded_files`
--
ALTER TABLE `tbl_customer_uploaded_files`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_drivers`
--
ALTER TABLE `tbl_drivers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tbl_drivers_files`
--
ALTER TABLE `tbl_drivers_files`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT for table `tbl_driver_accounts`
--
ALTER TABLE `tbl_driver_accounts`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_driver_files_type`
--
ALTER TABLE `tbl_driver_files_type`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_driver_logs`
--
ALTER TABLE `tbl_driver_logs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_driver_notifications`
--
ALTER TABLE `tbl_driver_notifications`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `tbl_driver_order_arrangement`
--
ALTER TABLE `tbl_driver_order_arrangement`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_goods_type`
--
ALTER TABLE `tbl_goods_type`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `tbl_inquiry`
--
ALTER TABLE `tbl_inquiry`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_invoice`
--
ALTER TABLE `tbl_invoice`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `tbl_menu_to_assign`
--
ALTER TABLE `tbl_menu_to_assign`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_orders`
--
ALTER TABLE `tbl_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=218;

--
-- AUTO_INCREMENT for table `tbl_order_files`
--
ALTER TABLE `tbl_order_files`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=290;

--
-- AUTO_INCREMENT for table `tbl_order_logs`
--
ALTER TABLE `tbl_order_logs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1405;

--
-- AUTO_INCREMENT for table `tbl_order_parcel_details`
--
ALTER TABLE `tbl_order_parcel_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=645;

--
-- AUTO_INCREMENT for table `tbl_otp`
--
ALTER TABLE `tbl_otp`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_reasonfor`
--
ALTER TABLE `tbl_reasonfor`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_reviews`
--
ALTER TABLE `tbl_reviews`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_role_management`
--
ALTER TABLE `tbl_role_management`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `tbl_salesexecutive`
--
ALTER TABLE `tbl_salesexecutive`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_salesexecutive_cutomerlist`
--
ALTER TABLE `tbl_salesexecutive_cutomerlist`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_short_helper`
--
ALTER TABLE `tbl_short_helper`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `tbl_subscriptions`
--
ALTER TABLE `tbl_subscriptions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_subscription_features`
--
ALTER TABLE `tbl_subscription_features`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_subscription_purchase`
--
ALTER TABLE `tbl_subscription_purchase`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- AUTO_INCREMENT for table `tbl_users_logs`
--
ALTER TABLE `tbl_users_logs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_vehicles`
--
ALTER TABLE `tbl_vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_vehicle_files`
--
ALTER TABLE `tbl_vehicle_files`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_wallet_creditlist`
--
ALTER TABLE `tbl_wallet_creditlist`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_wallet_transaction`
--
ALTER TABLE `tbl_wallet_transaction`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `temp_token`
--
ALTER TABLE `temp_token`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=196;

--
-- AUTO_INCREMENT for table `testonly`
--
ALTER TABLE `testonly`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
