-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 25, 2025 at 03:21 PM
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
-- Database: `milma_ypo`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dairy_id` bigint(20) UNSIGNED NOT NULL,
  `fund_id` bigint(20) UNSIGNED DEFAULT NULL,
  `opening_balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `main_balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `agencies`
--

CREATE TABLE `agencies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dairy_id` bigint(20) UNSIGNED DEFAULT NULL,
  `agency_code` varchar(191) DEFAULT NULL,
  `name` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `contact_no` varchar(191) DEFAULT NULL,
  `address` varchar(191) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `aggency_bills`
--

CREATE TABLE `aggency_bills` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `aggency_sale_id` bigint(20) UNSIGNED DEFAULT NULL,
  `asset_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `discount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `gst_percent` decimal(5,2) NOT NULL DEFAULT 0.00 COMMENT 'GST percentage',
  `gst_amount` decimal(12,2) NOT NULL DEFAULT 0.00 COMMENT 'GST amount for this item',
  `tax_type` varchar(191) DEFAULT NULL,
  `total` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `aggency_bill_units`
--

CREATE TABLE `aggency_bill_units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `aggency_bill_id` bigint(20) UNSIGNED NOT NULL,
  `serial_no` varchar(191) DEFAULT NULL,
  `brand` varchar(191) DEFAULT NULL,
  `model` varchar(191) DEFAULT NULL,
  `warranty` varchar(191) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `aggency_sales`
--

CREATE TABLE `aggency_sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `agency_id` bigint(20) UNSIGNED DEFAULT NULL,
  `dairy_idx` bigint(20) DEFAULT NULL,
  `dairy_id` bigint(20) UNSIGNED DEFAULT NULL,
  `invoice_id` varchar(191) DEFAULT NULL,
  `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_items_id` bigint(20) UNSIGNED DEFAULT NULL,
  `dairy_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `brand` varchar(255) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `serial_no` varchar(255) DEFAULT NULL,
  `warranty` varchar(255) DEFAULT NULL,
  `purchase_value` decimal(15,2) NOT NULL DEFAULT 0.00,
  `purchase_date` date DEFAULT NULL,
  `sold_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `invoice_refno` varchar(255) DEFAULT NULL,
  `status` enum('available','sold','damaged','maintenance') NOT NULL DEFAULT 'available',
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dairies`
--

CREATE TABLE `dairies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `location` varchar(255) NOT NULL,
  `admin_userid` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deliveries`
--

CREATE TABLE `deliveries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `delivery_no` varchar(191) NOT NULL,
  `invoice_id` varchar(191) NOT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_items`
--

CREATE TABLE `delivery_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `delivery_id` bigint(20) UNSIGNED NOT NULL,
  `invoice_item_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `delivered_quantity` int(11) NOT NULL DEFAULT 0,
  `warranty` varchar(191) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rate` decimal(15,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `dairy_id` bigint(20) UNSIGNED DEFAULT NULL,
  `expense_item` varchar(255) DEFAULT NULL,
  `is_head_office` tinyint(1) NOT NULL DEFAULT 0,
  `fund_id` bigint(20) UNSIGNED DEFAULT NULL,
  `expensecategory_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense_categories`
--

CREATE TABLE `expense_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense_items`
--

CREATE TABLE `expense_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_code` varchar(191) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fund_allocations`
--

CREATE TABLE `fund_allocations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dairy_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `allocation_date` date NOT NULL,
  `financial_year` varchar(255) NOT NULL,
  `remarks` text DEFAULT NULL,
  `status` enum('approved','pending','rejected') NOT NULL DEFAULT 'approved',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `head_office_fund`
--

CREATE TABLE `head_office_fund` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `financial_year` varchar(191) NOT NULL,
  `balance_amount` decimal(14,2) DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` varchar(20) NOT NULL,
  `dairy_id` bigint(20) UNSIGNED NOT NULL,
  `invoice_no` varchar(191) DEFAULT NULL,
  `delivered_date` date DEFAULT NULL,
  `vendor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `discount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','delivered','cancelled','partially_delivered') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` varchar(20) NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `delivered_quantity` int(11) DEFAULT NULL,
  `pending_quantity` int(2) DEFAULT NULL,
  `unit_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `gst_percent` decimal(5,2) DEFAULT 0.00 COMMENT 'GST percentage',
  `tax_type` enum('inclusive','exclusive') NOT NULL DEFAULT 'exclusive',
  `gst_amount` decimal(12,2) NOT NULL DEFAULT 0.00 COMMENT 'GST amount for this item',
  `discount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `taxable_value` decimal(12,2) NOT NULL DEFAULT 0.00 COMMENT 'Amount before GST and after discount',
  `total` decimal(12,2) NOT NULL DEFAULT 0.00 COMMENT 'Final total including GST',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_10_07_044205_create_vendors_table', 1),
(5, '2025_10_07_065719_create_products_table', 1),
(6, '2025_10_14_131215_create_dairies_table', 1),
(7, '2025_10_16_133140_create_expense_categories_table', 1),
(8, '2025_10_17_070054_create_fund_allocations_table', 1),
(9, '2025_10_17_105117_create_invoice_table', 1),
(10, '2025_10_17_105215_create_expenses_table', 1),
(11, '2025_10_17_105319_create_transaction_ledger_table', 1),
(12, '2025_10_17_105352_create_accounts_table', 1),
(13, '2025_10_17_105520_create_invoice_items_table', 1),
(14, '2025_10_17_105534_create_assets_table', 1),
(15, '2025_10_18_125812_create_roles_table', 1),
(16, '2025_10_18_125830_create_permissions_table', 1),
(17, '2025_10_18_125843_create_role_user_table', 1),
(18, '2025_10_18_125852_create_regions_table', 1),
(19, '2025_10_18_125936_create_permission_role_pivot_table', 1),
(20, '2025_10_18_130004_create_usertable_update', 1),
(21, '2025_10_21_140412_add_soft_deletes_to_tables', 2),
(22, '2025_10_22_070601_add_category_and_item_code_to_products_table', 3),
(23, '2025_10_22_080634_update_invoice_items_table_change_product_name_to_product_id', 4),
(24, '2025_10_22_081210_add_tax_type_to_invoice_items_table', 4),
(25, '2025_10_23_061519_update_products_and_expenses_tables', 5),
(26, '2025_10_23_071835_update_assets_table', 6),
(27, '2025_10_23_085824_update_status_enum_in_assets_table', 7),
(28, '2025_10_24_094352_add_quantity_to_assets_table', 8),
(29, '2025_10_24_102626_create_expense_items_table', 9),
(30, '2025_10_24_172045_add_description_to_expense_items_table', 10),
(31, '2025_10_27_132613_modify_expenses_table', 11),
(32, '2025_10_29_161229_make_dairy_id_nullable_in_expenses_table', 12),
(33, '2025_10_29_100713_create_aggency_sales_table', 13),
(34, '2025_10_29_101417_create_aggency_bills_table', 13),
(35, '2025_10_29_130119_add_invoice_items_id_to_assets_table', 13),
(36, '2025_10_29_165317_rename_product_id_to_asset_id_in_aggency_bills_table', 13),
(37, '2025_10_29_165723_remove_asset_id_from_aggency_sales_table', 13),
(38, '2025_11_03_123517_update_status_enum_in_invoices_table', 14),
(39, '2025_11_05_140113_add_dairy_id_to_aggency_sales_table', 15),
(40, '2025_11_07_190511_create_head_offices_table', 15),
(41, '2025_11_10_103722_add_date_and_invoice_no_to_invoices_table', 16),
(42, '2025_11_13_122732_create_agencies_table', 17),
(43, '2025_11_13_125733_add_dairy_id_to_agencies_table', 17),
(44, '2025_11_14_094126_add_agency_code_to_agencies_table', 17),
(45, '2025_11_14_114741_update_aggency_sales_table', 17),
(46, '2025_11_18_162741_create_deliveries_table', 18),
(47, '2025_11_18_163024_create_deliveryitems_table', 18),
(48, '2025_11_21_192750_create_aggency_bill_units_table', 19);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `title`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'user_access', '2025-10-18 07:52:44', '2025-10-18 07:52:44', NULL),
(2, 'user_create', '2025-10-18 07:52:44', '2025-10-18 07:52:44', NULL),
(3, 'user_edit', '2025-10-18 07:52:44', '2025-10-18 07:52:44', NULL),
(4, 'user_show', '2025-10-18 07:52:44', '2025-10-18 07:52:44', NULL),
(5, 'user_delete', '2025-10-18 07:52:44', '2025-10-18 07:52:44', NULL),
(6, 'permission_access', NULL, NULL, NULL),
(7, 'permission_edit', NULL, NULL, NULL),
(8, 'permission_show', NULL, NULL, NULL),
(9, 'permission_delete', NULL, NULL, NULL),
(10, 'permission_create', NULL, NULL, NULL),
(11, 'role_create', '2025-10-18 08:46:40', '2025-10-18 08:46:40', NULL),
(12, 'role_edit', '2025-10-18 08:47:05', '2025-10-18 08:47:05', NULL),
(13, 'role_show', NULL, NULL, NULL),
(14, 'role_delete', NULL, NULL, NULL),
(15, 'role_access', NULL, NULL, NULL),
(16, 'profile_password_edit', NULL, NULL, NULL),
(17, 'region_create', NULL, NULL, NULL),
(18, 'region_edit', NULL, NULL, NULL),
(19, 'region_show', NULL, NULL, NULL),
(20, 'region_delete', NULL, NULL, NULL),
(21, 'region_access', NULL, NULL, NULL),
(22, 'vendor_access', '2025-10-06 18:24:46', '2025-10-06 18:24:46', NULL),
(23, 'vendor_create', '2025-10-06 18:25:19', '2025-10-06 18:25:19', NULL),
(24, 'vendor_edit', '2025-10-06 18:25:47', '2025-10-06 18:25:47', NULL),
(25, 'vendor_delete', '2025-10-06 18:26:14', '2025-10-06 18:26:14', NULL),
(26, 'vendor_show', '2025-10-06 18:41:38', '2025-10-06 18:41:38', NULL),
(27, 'dairy_create', '2025-10-14 17:55:31', '2025-10-14 17:55:31', NULL),
(28, 'dairy_edit', '2025-10-14 17:55:48', '2025-10-14 17:55:48', NULL),
(29, 'dairy_show', '2025-10-14 17:56:05', '2025-10-14 17:56:05', NULL),
(30, 'dairy_access', '2025-10-14 17:57:03', '2025-10-14 17:57:03', NULL),
(31, 'dairy_delete', '2025-10-14 18:01:24', '2025-10-14 18:01:24', NULL),
(32, 'product_access', '2025-10-14 20:14:37', '2025-10-14 20:14:37', NULL),
(33, 'product_create', '2025-10-14 20:15:05', '2025-10-14 20:15:05', NULL),
(34, 'product_show', '2025-10-14 20:15:32', '2025-10-14 20:15:32', NULL),
(35, 'product_edit', '2025-10-14 20:16:01', '2025-10-14 20:16:01', NULL),
(36, 'product_delete', '2025-10-14 20:17:09', '2025-10-14 20:17:09', NULL),
(37, 'dairy_create', '2025-10-21 07:51:57', '2025-10-21 07:51:57', NULL),
(38, 'dairy_access', '2025-10-21 07:52:14', '2025-10-21 07:52:14', NULL),
(39, 'dairy_edit', '2025-10-21 07:53:39', '2025-10-21 07:53:39', NULL),
(40, 'dairy_show', '2025-10-21 07:54:05', '2025-10-21 07:54:05', NULL),
(41, 'expensecategory_create', '2025-10-21 07:55:22', '2025-10-21 07:55:22', NULL),
(42, 'expensecategory_edit', '2025-10-21 07:56:05', '2025-10-21 07:56:05', NULL),
(43, 'expensecategory_access', '2025-10-21 07:56:21', '2025-10-21 07:56:21', NULL),
(44, 'expensecategory_show', '2025-10-21 07:56:39', '2025-10-21 07:56:39', NULL),
(45, 'expensecategory_delete', '2025-10-21 07:57:24', '2025-10-21 07:57:24', NULL),
(46, 'dairy_delete', '2025-10-21 07:57:45', '2025-10-21 07:57:45', NULL),
(47, 'fundallocation_access', '2025-10-21 08:22:45', '2025-10-21 08:22:45', NULL),
(48, 'fundallocation_create', '2025-10-21 08:23:09', '2025-10-21 08:23:09', NULL),
(49, 'fundallocation_show', '2025-10-21 08:23:27', '2025-10-21 08:23:27', NULL),
(50, 'invoice_access', '2025-10-24 10:46:06', '2025-10-24 10:46:06', NULL),
(51, 'expenseitem_access', '2025-10-24 10:46:49', '2025-10-24 10:46:49', NULL),
(52, 'expenseitem_create', '2025-10-24 10:47:06', '2025-10-24 10:47:06', NULL),
(53, 'expenseitem_edit', '2025-10-24 10:47:27', '2025-10-24 10:47:27', NULL),
(54, 'expenseitem_delete', '2025-10-24 10:47:46', '2025-10-24 10:47:46', NULL),
(55, 'invoice_create', '2025-10-24 10:48:59', '2025-10-24 10:48:59', NULL),
(56, 'invoice_cancel', '2025-10-24 10:49:21', '2025-10-24 10:49:21', NULL),
(57, 'expense_access', '2025-10-28 04:46:11', '2025-10-28 04:46:11', NULL),
(58, 'expense_edit', '2025-10-28 04:46:37', '2025-10-28 04:46:37', NULL),
(59, 'expense_show', '2025-10-28 04:47:04', '2025-10-28 04:47:04', NULL),
(60, 'transaction_access', '2025-11-03 04:58:47', '2025-11-03 04:58:47', NULL),
(61, 'asset_access', '2025-11-03 07:29:39', '2025-11-03 07:29:39', NULL),
(62, 'user_manage_access', '2025-11-07 12:14:02', '2025-11-07 12:14:02', NULL),
(63, 'agency_sale_access', '2025-11-07 12:14:39', '2025-11-19 11:16:50', NULL),
(64, 'agency_access', '2025-11-19 11:21:02', '2025-11-19 11:21:02', NULL),
(65, 'agency_create', '2025-11-19 11:21:34', '2025-11-19 11:21:34', NULL),
(66, 'agency_delete', '2025-11-19 11:31:24', '2025-11-19 11:31:24', NULL),
(67, 'agency_edit', '2025-11-19 11:32:02', '2025-11-19 11:32:02', NULL),
(68, 'fundallocation_edit', '2025-11-19 11:43:59', '2025-11-19 11:43:59', NULL),
(69, 'fundallocation_adjust', '2025-11-19 11:45:35', '2025-11-19 11:45:35', NULL),
(70, 'expense_access', '2025-11-19 11:46:37', '2025-11-19 12:32:35', '2025-11-19 12:32:35'),
(71, 'expense_create', '2025-11-19 11:47:16', '2025-11-19 11:47:16', NULL),
(72, 'expense_edit', '2025-11-19 11:48:48', '2025-11-19 12:32:54', '2025-11-19 12:32:54'),
(73, 'expense_report_access', '2025-11-19 11:58:43', '2025-11-19 11:58:43', NULL),
(74, 'headofficefund_access', '2025-11-19 11:59:52', '2025-11-19 11:59:52', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

CREATE TABLE `permission_role` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permission_role`
--

INSERT INTO `permission_role` (`id`, `role_id`, `permission_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(2, 1, 2, NULL, NULL),
(3, 1, 3, NULL, NULL),
(4, 1, 4, NULL, NULL),
(5, 1, 6, NULL, NULL),
(6, 1, 7, NULL, NULL),
(7, 1, 8, NULL, NULL),
(8, 1, 9, NULL, NULL),
(9, 1, 10, NULL, NULL),
(10, 1, 11, NULL, NULL),
(11, 1, 15, NULL, NULL),
(12, 1, 12, NULL, NULL),
(13, 1, 5, NULL, NULL),
(14, 1, 13, NULL, NULL),
(15, 1, 14, NULL, NULL),
(16, 1, 16, NULL, NULL),
(17, 1, 17, NULL, NULL),
(18, 1, 18, NULL, NULL),
(19, 1, 19, NULL, NULL),
(20, 1, 20, NULL, NULL),
(21, 1, 21, NULL, NULL),
(22, 1, 22, NULL, NULL),
(23, 1, 23, NULL, NULL),
(24, 1, 24, NULL, NULL),
(25, 1, 25, NULL, NULL),
(26, 1, 26, NULL, NULL),
(27, 1, 27, NULL, NULL),
(28, 1, 28, NULL, NULL),
(29, 1, 29, NULL, NULL),
(30, 1, 30, NULL, NULL),
(31, 1, 31, NULL, NULL),
(32, 1, 32, NULL, NULL),
(33, 1, 33, NULL, NULL),
(34, 1, 34, NULL, NULL),
(35, 1, 35, NULL, NULL),
(36, 1, 36, NULL, NULL),
(37, 1, 37, NULL, NULL),
(38, 1, 38, NULL, NULL),
(39, 1, 39, NULL, NULL),
(40, 1, 40, NULL, NULL),
(41, 1, 41, NULL, NULL),
(42, 1, 42, NULL, NULL),
(43, 1, 43, NULL, NULL),
(44, 1, 44, NULL, NULL),
(45, 1, 45, NULL, NULL),
(46, 1, 46, NULL, NULL),
(47, 1, 47, NULL, NULL),
(48, 1, 48, NULL, NULL),
(49, 1, 49, NULL, NULL),
(65, 2, 16, NULL, NULL),
(71, 2, 22, NULL, NULL),
(75, 2, 26, NULL, NULL),
(81, 2, 32, NULL, NULL),
(83, 2, 34, NULL, NULL),
(92, 2, 43, NULL, NULL),
(93, 2, 44, NULL, NULL),
(99, 1, 50, NULL, NULL),
(100, 1, 51, NULL, NULL),
(101, 1, 52, NULL, NULL),
(102, 1, 53, NULL, NULL),
(103, 1, 54, NULL, NULL),
(104, 1, 55, NULL, NULL),
(105, 1, 56, NULL, NULL),
(106, 1, 57, NULL, NULL),
(107, 1, 58, NULL, NULL),
(108, 1, 59, NULL, NULL),
(109, 1, 60, NULL, NULL),
(110, 2, 23, NULL, NULL),
(111, 2, 24, NULL, NULL),
(125, 2, 51, NULL, NULL),
(131, 2, 57, NULL, NULL),
(132, 2, 58, NULL, NULL),
(133, 2, 59, NULL, NULL),
(134, 2, 60, NULL, NULL),
(135, 2, 61, NULL, NULL),
(137, 2, 63, NULL, NULL),
(138, 1, 61, NULL, NULL),
(139, 1, 62, NULL, NULL),
(140, 1, 63, NULL, NULL),
(141, 1, 64, NULL, NULL),
(142, 1, 65, NULL, NULL),
(143, 1, 66, NULL, NULL),
(144, 1, 67, NULL, NULL),
(145, 1, 68, NULL, NULL),
(146, 1, 69, NULL, NULL),
(147, 1, 70, NULL, NULL),
(148, 1, 71, NULL, NULL),
(149, 1, 72, NULL, NULL),
(150, 1, 73, NULL, NULL),
(151, 1, 74, NULL, NULL),
(152, 2, 42, NULL, NULL),
(153, 2, 50, NULL, NULL),
(154, 2, 52, NULL, NULL),
(155, 2, 53, NULL, NULL),
(156, 2, 54, NULL, NULL),
(157, 2, 55, NULL, NULL),
(158, 2, 64, NULL, NULL),
(159, 2, 65, NULL, NULL),
(160, 2, 66, NULL, NULL),
(161, 2, 67, NULL, NULL),
(162, 2, 71, NULL, NULL),
(163, 2, 73, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `productname` varchar(255) NOT NULL,
  `item_code` varchar(255) NOT NULL,
  `img` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `vendor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `regions`
--

CREATE TABLE `regions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `regions`
--

INSERT INTO `regions` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Trivandrum', '2025-10-18 07:52:44', '2025-10-18 07:52:44', NULL),
(2, 'Kollam', '2025-10-18 07:52:44', '2025-10-18 07:52:44', NULL),
(3, 'Pathanamthitta', '2025-10-18 07:52:44', '2025-10-18 07:52:44', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `title`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'SuperAdmin', '2025-10-18 07:52:44', '2025-10-18 07:52:44', NULL),
(2, 'DairyAdmin', '2025-10-18 07:52:44', '2025-10-21 08:39:49', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_user`
--

INSERT INTO `role_user` (`id`, `user_id`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(3, 2, 2, NULL, NULL),
(4, 3, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('27QWyInANXra6zit8sK60mrUFELxAbgBsWgA0NvR', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiYjVUcEJDaFAzWG96UGNjSzJvNDhCNW1WVzBxeUtqdGJnUnJxTHJNMSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9pbnZvaWNlcyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czo0OiJhdXRoIjthOjE6e3M6MjE6InBhc3N3b3JkX2NvbmZpcm1lZF9hdCI7aToxNzYzNzExNTk4O319', 1763711614),
('4q5UTYX0p9aynsWFCB5bnAePEM8B3pwYXmeZ4Zbb', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiN29HcElQTFB2NzhMQ2F4enFENEl1WmVZY09iUUdVN29iQ3R1ekNNNCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQyOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vZGFpcmllcy80L2VkaXQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6NDoiYXV0aCI7YToxOntzOjIxOiJwYXNzd29yZF9jb25maXJtZWRfYXQiO2k6MTc2Mzk4MTU4Nzt9fQ==', 1763985029),
('bDLhoZUB5qe7O6yR5w6pv94qCkgv4uO724NvW4po', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiYkVPRW5qNkxYaDZoWVZ0RmVGQ3BzcjRkQVY4STZHTFRiSE1ldmJUaCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9kYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6NDoiYXV0aCI7YToxOntzOjIxOiJwYXNzd29yZF9jb25maXJtZWRfYXQiO2k6MTc2NDAzNjAxNTt9fQ==', 1764036016),
('GJXBAugQTcoETowxwM8ywtyzdPAsCqwweO7w4M3Z', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiYWNqZ0RVSGVJb2FpUUJPM2VwUlJJTkc2ekIyS0xrNzB1TnY2N1NqUCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQwOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vYWdnZW5jeS1zYWxlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjQ6ImF1dGgiO2E6MTp7czoyMToicGFzc3dvcmRfY29uZmlybWVkX2F0IjtpOjE3NjM3Mjg0Mjg7fX0=', 1763737512),
('iIqt7Q2imiLmqle3wzuwNWhfmQ38sGpDEJVGN0to', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoieHRXYTZxV2pWNVNZV2Z5bXlmQkw4ejM0WHBTbVNFNFF0SEtxdkxDbyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM3OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjQ6ImF1dGgiO2E6MTp7czoyMToicGFzc3dvcmRfY29uZmlybWVkX2F0IjtpOjE3NjQwNDUzMTA7fX0=', 1764049090),
('LPEdzhH0ybNpa1EozyGaixrpiruoAia4f9CzmgUw', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoielNsWEJUT0lMUjR1UFd2MEI0ZG9BMFZSZUFYWlJkWnhLaEYyeXk5aSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9hZ2dlbmN5LXNhbGUvc2hvdy8xMSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czo0OiJhdXRoIjthOjE6e3M6MjE6InBhc3N3b3JkX2NvbmZpcm1lZF9hdCI7aToxNzYzOTU1ODk3O319', 1763964957),
('zObLExR5emqEyu7ezeEG5zIkSrOuAjU6soug1klA', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoicVZScFg0YmlaVjU1Tzg4clFQMG9zMTkwbUpORUM2bW5iRXFUSzZFYSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM3OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjQ6ImF1dGgiO2E6MTp7czoyMToicGFzc3dvcmRfY29uZmlybWVkX2F0IjtpOjE3NjQwNzA5NjE7fX0=', 1764080502);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dairy_id` bigint(20) UNSIGNED NOT NULL,
  `fund_allocation_id` bigint(20) UNSIGNED DEFAULT NULL,
  `expense_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `expense_id` int(11) DEFAULT NULL,
  `type` enum('credit','debit','hold','refund') NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `reference_no` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('pending','completed','cancelled') NOT NULL DEFAULT 'completed',
  `transaction_date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_blocked` tinyint(1) NOT NULL DEFAULT 0,
  `region_id` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `is_blocked`, `region_id`, `deleted_at`) VALUES
(1, 'Admin User', 'admin@milma.com', NULL, '$2y$12$4EBLuDg69wAO5kfZwwqjUOrknKTn0qdk/KhXFd8osYEe3wrA9oDvO', NULL, '2025-10-18 07:52:43', '2025-10-18 07:52:43', 0, NULL, NULL),
(2, 'testsree', 'sree@milma.com', NULL, '$2y$12$MLuxtD/7n3Ozf6L4LjEB1.yX1QEXYU/bf8laQf2wnlIhlWL3hvaxS', NULL, '2025-10-22 06:28:07', '2025-10-29 03:47:48', 0, NULL, NULL),
(3, 'kollam', 'kollam@milma.com', NULL, '$2y$12$VQo/a/fgrQk0m9bepQeVYO8RhqS7kGGH2e8sz609kI9F9vAyxd67W', NULL, '2025-11-03 05:41:56', '2025-11-03 05:41:56', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accounts_dairy_id_foreign` (`dairy_id`),
  ADD KEY `accounts_fund_id_foreign` (`fund_id`);

--
-- Indexes for table `agencies`
--
ALTER TABLE `agencies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `agencies_dairy_id_foreign` (`dairy_id`);

--
-- Indexes for table `aggency_bills`
--
ALTER TABLE `aggency_bills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aggency_bills_aggency_sale_id_foreign` (`aggency_sale_id`),
  ADD KEY `aggency_bills_asset_id_foreign` (`asset_id`);

--
-- Indexes for table `aggency_bill_units`
--
ALTER TABLE `aggency_bill_units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aggency_sales`
--
ALTER TABLE `aggency_sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aggency_sales_dairy_id_foreign` (`dairy_id`),
  ADD KEY `aggency_sales_agency_id_foreign` (`agency_id`);

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assets_dairy_id_foreign` (`dairy_id`),
  ADD KEY `assets_product_id_foreign` (`product_id`),
  ADD KEY `assets_invoice_items_id_foreign` (`invoice_items_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `dairies`
--
ALTER TABLE `dairies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `deliveries_delivery_no_unique` (`delivery_no`);

--
-- Indexes for table `delivery_items`
--
ALTER TABLE `delivery_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `delivery_items_delivery_id_foreign` (`delivery_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expenses_dairy_id_foreign` (`dairy_id`),
  ADD KEY `expenses_fund_id_foreign` (`fund_id`),
  ADD KEY `expenses_expensecategory_id_foreign` (`expensecategory_id`),
  ADD KEY `expenses_product_id_foreign` (`product_id`);

--
-- Indexes for table `expense_categories`
--
ALTER TABLE `expense_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expense_items`
--
ALTER TABLE `expense_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `expense_items_item_code_unique` (`item_code`),
  ADD KEY `expense_items_category_id_foreign` (`category_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `fund_allocations`
--
ALTER TABLE `fund_allocations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fund_allocations_dairy_id_foreign` (`dairy_id`);

--
-- Indexes for table `head_office_fund`
--
ALTER TABLE `head_office_fund`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoices_dairy_id_foreign` (`dairy_id`),
  ADD KEY `invoices_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_items_invoice_id_foreign` (`invoice_id`),
  ADD KEY `invoice_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permission_role_role_id_foreign` (`role_id`),
  ADD KEY `permission_role_permission_id_foreign` (`permission_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_vendor_id_foreign` (`vendor_id`),
  ADD KEY `products_category_id_foreign` (`category_id`);

--
-- Indexes for table `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_user_user_id_foreign` (`user_id`),
  ADD KEY `role_user_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_dairy_id_foreign` (`dairy_id`),
  ADD KEY `transactions_fund_allocation_id_foreign` (`fund_allocation_id`),
  ADD KEY `transactions_expense_category_id_foreign` (`expense_category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_region_id_foreign` (`region_id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vendors_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `agencies`
--
ALTER TABLE `agencies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `aggency_bills`
--
ALTER TABLE `aggency_bills`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `aggency_bill_units`
--
ALTER TABLE `aggency_bill_units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `aggency_sales`
--
ALTER TABLE `aggency_sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dairies`
--
ALTER TABLE `dairies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deliveries`
--
ALTER TABLE `deliveries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `delivery_items`
--
ALTER TABLE `delivery_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expense_categories`
--
ALTER TABLE `expense_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expense_items`
--
ALTER TABLE `expense_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fund_allocations`
--
ALTER TABLE `fund_allocations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `head_office_fund`
--
ALTER TABLE `head_office_fund`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `permission_role`
--
ALTER TABLE `permission_role`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=164;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `regions`
--
ALTER TABLE `regions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `role_user`
--
ALTER TABLE `role_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `accounts_dairy_id_foreign` FOREIGN KEY (`dairy_id`) REFERENCES `dairies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `accounts_fund_id_foreign` FOREIGN KEY (`fund_id`) REFERENCES `fund_allocations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `agencies`
--
ALTER TABLE `agencies`
  ADD CONSTRAINT `agencies_dairy_id_foreign` FOREIGN KEY (`dairy_id`) REFERENCES `dairies` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `aggency_bills`
--
ALTER TABLE `aggency_bills`
  ADD CONSTRAINT `aggency_bills_aggency_sale_id_foreign` FOREIGN KEY (`aggency_sale_id`) REFERENCES `aggency_sales` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `aggency_bills_asset_id_foreign` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `aggency_sales`
--
ALTER TABLE `aggency_sales`
  ADD CONSTRAINT `aggency_sales_agency_id_foreign` FOREIGN KEY (`agency_id`) REFERENCES `agencies` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `aggency_sales_dairy_id_foreign` FOREIGN KEY (`dairy_id`) REFERENCES `dairies` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `assets`
--
ALTER TABLE `assets`
  ADD CONSTRAINT `assets_dairy_id_foreign` FOREIGN KEY (`dairy_id`) REFERENCES `dairies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assets_invoice_items_id_foreign` FOREIGN KEY (`invoice_items_id`) REFERENCES `invoice_items` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `assets_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `delivery_items`
--
ALTER TABLE `delivery_items`
  ADD CONSTRAINT `delivery_items_delivery_id_foreign` FOREIGN KEY (`delivery_id`) REFERENCES `deliveries` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_dairy_id_foreign` FOREIGN KEY (`dairy_id`) REFERENCES `dairies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expenses_expensecategory_id_foreign` FOREIGN KEY (`expensecategory_id`) REFERENCES `expense_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expenses_fund_id_foreign` FOREIGN KEY (`fund_id`) REFERENCES `fund_allocations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `expense_items`
--
ALTER TABLE `expense_items`
  ADD CONSTRAINT `expense_items_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `expense_categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `fund_allocations`
--
ALTER TABLE `fund_allocations`
  ADD CONSTRAINT `fund_allocations_dairy_id_foreign` FOREIGN KEY (`dairy_id`) REFERENCES `dairies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_dairy_id_foreign` FOREIGN KEY (`dairy_id`) REFERENCES `dairies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoices_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoice_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `expense_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_user`
--
ALTER TABLE `role_user`
  ADD CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_dairy_id_foreign` FOREIGN KEY (`dairy_id`) REFERENCES `dairies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_expense_category_id_foreign` FOREIGN KEY (`expense_category_id`) REFERENCES `expense_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transactions_fund_allocation_id_foreign` FOREIGN KEY (`fund_allocation_id`) REFERENCES `fund_allocations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_region_id_foreign` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
