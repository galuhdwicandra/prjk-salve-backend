-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 03, 2026 at 03:59 PM
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
-- Database: `db-web-salve`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounting_accounts`
--

CREATE TABLE `accounting_accounts` (
  `id` char(36) NOT NULL,
  `branch_id` char(36) DEFAULT NULL,
  `parent_id` char(36) DEFAULT NULL,
  `code` varchar(32) NOT NULL,
  `name` varchar(150) NOT NULL,
  `type` enum('ASSET','LIABILITY','EQUITY','REVENUE','EXPENSE') NOT NULL,
  `normal_balance` enum('DEBIT','CREDIT') NOT NULL,
  `is_cash_account` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accounting_accounts`
--

INSERT INTO `accounting_accounts` (`id`, `branch_id`, `parent_id`, `code`, `name`, `type`, `normal_balance`, `is_cash_account`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
('117a5586-2362-42d9-bec6-bb9d4d13cf1e', NULL, NULL, '1020', 'Bank / Transfer', 'ASSET', 'DEBIT', 1, 1, 12, '2026-05-25 07:36:01', '2026-05-25 07:36:01'),
('15fef3e3-21f9-48ff-a136-c546047b2690', NULL, NULL, '2000', 'Liabilitas', 'LIABILITY', 'CREDIT', 0, 1, 20, '2026-05-25 07:36:01', '2026-05-25 07:36:01'),
('1e9976c4-5742-46ad-aad6-d2c0900a91d8', NULL, NULL, '5090', 'Beban Lain-lain', 'EXPENSE', 'DEBIT', 0, 1, 59, '2026-05-25 07:36:01', '2026-05-25 07:36:01'),
('272f026b-f271-4244-a35e-0dad45dc3023', NULL, NULL, '4010', 'Pendapatan Laundry', 'REVENUE', 'CREDIT', 0, 1, 41, '2026-05-25 07:36:01', '2026-05-25 07:36:01'),
('3177eeae-9904-47a8-a1e2-10aff6561ae1', NULL, NULL, '1010', 'Kas Cabang', 'ASSET', 'DEBIT', 1, 1, 11, '2026-05-25 07:36:01', '2026-05-25 07:36:01'),
('3d1b751e-3f0d-46ec-b0a9-7266cf5597f8', NULL, NULL, '5010', 'Beban Operasional', 'EXPENSE', 'DEBIT', 0, 1, 51, '2026-05-25 07:36:01', '2026-05-25 07:36:01'),
('4656b0b3-3cc5-44f3-a709-843be023453d', NULL, NULL, '1030', 'QRIS', 'ASSET', 'DEBIT', 1, 1, 13, '2026-05-25 07:36:01', '2026-05-25 07:36:01'),
('4abbea14-3ba8-4fb9-95f5-0f8c7d3a51cd', NULL, NULL, '5000', 'Beban', 'EXPENSE', 'DEBIT', 0, 1, 50, '2026-05-25 07:36:01', '2026-05-25 07:36:01'),
('5056f8b6-aa17-479c-a8e3-11f811cab1f3', NULL, NULL, '5030', 'Beban Listrik dan Air', 'EXPENSE', 'DEBIT', 0, 1, 53, '2026-05-25 07:36:01', '2026-05-25 07:36:01'),
('589bf859-8acf-4c68-be53-c50612da3435', NULL, NULL, '3010', 'Modal Pemilik', 'EQUITY', 'CREDIT', 0, 1, 31, '2026-05-25 07:36:01', '2026-05-25 07:36:01'),
('62610b80-9694-4b9c-b677-66da3f0b1b41', NULL, NULL, '2010', 'Uang Muka Pelanggan', 'LIABILITY', 'CREDIT', 0, 1, 21, '2026-05-25 09:16:00', '2026-05-25 09:16:00'),
('799907c0-077d-486b-9e6c-ae415188fe24', NULL, NULL, '1000', 'Aset', 'ASSET', 'DEBIT', 0, 1, 10, '2026-05-25 07:36:01', '2026-05-25 07:36:01'),
('7a9919c8-6b9a-4d19-a6e8-39d1ac0d1a30', NULL, NULL, '4090', 'Diskon Penjualan', 'REVENUE', 'DEBIT', 0, 1, 49, '2026-05-25 07:36:01', '2026-05-25 07:36:01'),
('ab21d984-3271-417a-81a6-1372ca192d3a', NULL, NULL, '3020', 'Prive / Penarikan Pemilik', 'EQUITY', 'DEBIT', 0, 1, 32, '2026-05-25 07:36:01', '2026-05-25 07:36:01'),
('d7bca330-774d-4a80-8958-ab4152972dd6', NULL, NULL, '3000', 'Ekuitas', 'EQUITY', 'CREDIT', 0, 1, 30, '2026-05-25 07:36:01', '2026-05-25 07:36:01'),
('ddcdbac8-7b25-4a9e-9909-dcd35dab5dda', NULL, NULL, '1040', 'Piutang Usaha', 'ASSET', 'DEBIT', 0, 1, 14, '2026-05-25 07:36:01', '2026-05-25 07:36:01'),
('e246a9b7-3a07-4c06-b89a-252f3154553f', NULL, NULL, '5040', 'Beban Transport', 'EXPENSE', 'DEBIT', 0, 1, 54, '2026-05-25 07:36:01', '2026-05-25 07:36:01'),
('f490c2fe-f40f-48da-8bb0-0fffb4e191f0', NULL, NULL, '4000', 'Pendapatan', 'REVENUE', 'CREDIT', 0, 1, 40, '2026-05-25 07:36:01', '2026-05-25 07:36:01'),
('f904d28f-6d6a-44a4-8fb4-4ba62733e715', NULL, NULL, '5020', 'Beban Bahan Cuci', 'EXPENSE', 'DEBIT', 0, 1, 52, '2026-05-25 07:36:01', '2026-05-25 07:36:01');

-- --------------------------------------------------------

--
-- Table structure for table `accounting_account_mappings`
--

CREATE TABLE `accounting_account_mappings` (
  `id` char(36) NOT NULL,
  `branch_id` char(36) DEFAULT NULL,
  `event_key` varchar(80) NOT NULL,
  `payment_method` varchar(30) DEFAULT NULL,
  `expense_category` varchar(100) DEFAULT NULL,
  `debit_account_id` char(36) NOT NULL,
  `credit_account_id` char(36) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accounting_account_mappings`
--

INSERT INTO `accounting_account_mappings` (`id`, `branch_id`, `event_key`, `payment_method`, `expense_category`, `debit_account_id`, `credit_account_id`, `is_active`, `created_at`, `updated_at`) VALUES
('03822e41-570d-459d-86e8-044c492d6c17', NULL, 'RECEIVABLE_SETTLED_CASH', 'CASH', NULL, '3177eeae-9904-47a8-a1e2-10aff6561ae1', 'ddcdbac8-7b25-4a9e-9909-dcd35dab5dda', 1, '2026-05-25 07:36:01', '2026-05-25 07:36:01'),
('16746105-7f37-487f-828f-95c26e6ca8f3', NULL, 'ORDER_DISCOUNT', NULL, NULL, '7a9919c8-6b9a-4d19-a6e8-39d1ac0d1a30', '272f026b-f271-4244-a35e-0dad45dc3023', 1, '2026-05-25 07:36:01', '2026-05-25 07:36:01'),
('20be91b4-0b89-452f-9f58-3ac7c9dbc8cf', NULL, 'CASH_ADJUSTMENT_IN', NULL, NULL, '3177eeae-9904-47a8-a1e2-10aff6561ae1', '589bf859-8acf-4c68-be53-c50612da3435', 1, '2026-05-25 07:36:01', '2026-05-25 07:36:01'),
('32f10b47-7f8d-44ca-be0c-4b2e472af536', NULL, 'CASH_ADJUSTMENT_OUT', NULL, NULL, '1e9976c4-5742-46ad-aad6-d2c0900a91d8', '3177eeae-9904-47a8-a1e2-10aff6561ae1', 1, '2026-05-25 07:36:01', '2026-05-25 07:36:01'),
('33f00c08-c352-45f1-b564-376f51c4a8a8', NULL, 'CASH_OPENING_FLOAT', NULL, NULL, '3177eeae-9904-47a8-a1e2-10aff6561ae1', '589bf859-8acf-4c68-be53-c50612da3435', 1, '2026-05-25 07:36:01', '2026-05-25 07:36:01'),
('379b611c-f3ea-4245-a54b-d7e59fd56ff3', NULL, 'CASH_WITHDRAWAL', NULL, NULL, 'ab21d984-3271-417a-81a6-1372ca192d3a', '3177eeae-9904-47a8-a1e2-10aff6561ae1', 1, '2026-05-25 07:36:01', '2026-05-25 07:36:01'),
('38e99453-0e58-46b1-81fa-b1317b5d16de', NULL, 'EXPENSE_CASH_BOX', NULL, NULL, '3d1b751e-3f0d-46ec-b0a9-7266cf5597f8', '3177eeae-9904-47a8-a1e2-10aff6561ae1', 1, '2026-05-25 07:36:01', '2026-05-25 07:36:01'),
('39d424fb-7202-4980-abee-40575e253d77', NULL, 'EXPENSE_NON_CASH', NULL, NULL, '3d1b751e-3f0d-46ec-b0a9-7266cf5597f8', '117a5586-2362-42d9-bec6-bb9d4d13cf1e', 1, '2026-05-25 07:36:01', '2026-05-25 07:36:01'),
('4417b1c9-60de-4ab5-9aab-dbf70c91774e', NULL, 'ORDER_PAID_QRIS', 'QRIS', NULL, '4656b0b3-3cc5-44f3-a709-843be023453d', '272f026b-f271-4244-a35e-0dad45dc3023', 1, '2026-05-25 07:36:01', '2026-05-25 07:36:01'),
('59482b0f-a6d7-4d47-9c64-7c90e3d37f6e', NULL, 'ORDER_PAID_DP', 'DP', NULL, '3177eeae-9904-47a8-a1e2-10aff6561ae1', '62610b80-9694-4b9c-b677-66da3f0b1b41', 1, '2026-05-25 09:16:00', '2026-05-25 09:16:00'),
('5b695ab8-b66a-426d-ab40-bd53ef535b6a', NULL, 'ORDER_RECEIVABLE_CREATED', NULL, NULL, 'ddcdbac8-7b25-4a9e-9909-dcd35dab5dda', '272f026b-f271-4244-a35e-0dad45dc3023', 1, '2026-05-25 07:36:01', '2026-05-25 07:36:01'),
('cc1ec523-92c0-46b8-8705-5d4857bb1773', NULL, 'ORDER_PAID_TRANSFER', 'TRANSFER', NULL, '117a5586-2362-42d9-bec6-bb9d4d13cf1e', '272f026b-f271-4244-a35e-0dad45dc3023', 1, '2026-05-25 07:36:01', '2026-05-25 07:36:01'),
('f0910fd6-f344-425a-88e3-e91a2de26094', NULL, 'ORDER_PAID_CASH', 'CASH', NULL, '3177eeae-9904-47a8-a1e2-10aff6561ae1', '272f026b-f271-4244-a35e-0dad45dc3023', 1, '2026-05-25 07:36:01', '2026-05-25 07:36:01');

-- --------------------------------------------------------

--
-- Table structure for table `accounting_journal_entries`
--

CREATE TABLE `accounting_journal_entries` (
  `id` char(36) NOT NULL,
  `branch_id` char(36) NOT NULL,
  `mapping_id` char(36) DEFAULT NULL,
  `journal_no` varchar(50) NOT NULL,
  `journal_date` date NOT NULL,
  `source_type` varchar(80) DEFAULT NULL,
  `source_id` char(36) DEFAULT NULL,
  `source_no` varchar(100) DEFAULT NULL,
  `status` enum('DRAFT','POSTED','VOID') NOT NULL DEFAULT 'POSTED',
  `description` text DEFAULT NULL,
  `total_debit` decimal(14,2) NOT NULL DEFAULT 0.00,
  `total_credit` decimal(14,2) NOT NULL DEFAULT 0.00,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `posted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `posted_at` timestamp NULL DEFAULT NULL,
  `voided_by` bigint(20) UNSIGNED DEFAULT NULL,
  `voided_at` timestamp NULL DEFAULT NULL,
  `void_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accounting_journal_entries`
--

INSERT INTO `accounting_journal_entries` (`id`, `branch_id`, `mapping_id`, `journal_no`, `journal_date`, `source_type`, `source_id`, `source_no`, `status`, `description`, `total_debit`, `total_credit`, `created_by`, `posted_by`, `posted_at`, `voided_by`, `voided_at`, `void_reason`, `created_at`, `updated_at`) VALUES
('02a71f8d-0ffd-4fab-903c-3f57ce23a025', '9617c6c0-85c2-463e-9885-31e0698f5d77', '33f00c08-c352-45f1-b564-376f51c4a8a8', 'JRN-20260708-0001', '2026-07-08', 'cash_mutation', '019f41da-c0e1-7019-a683-faa86509aee5', NULL, 'POSTED', 'Modal awal kas', 502500.00, 502500.00, 1, 1, '2026-07-08 13:11:23', NULL, NULL, NULL, '2026-07-08 13:11:23', '2026-07-08 13:11:23'),
('276dcf2c-ccad-4324-8c86-e3e8e1a5a960', '9617c6c0-85c2-463e-9885-31e0698f5d77', '03822e41-570d-459d-86e8-044c492d6c17', 'JRN-20260525-0008', '2026-05-25', 'payment', '019e5e6d-08e3-7210-b3a2-78cc4fbb69fe', 'INV-25-05-0011', 'POSTED', 'Posting otomatis pelunasan piutang tunai', 25000.00, 25000.00, 2, 2, '2026-05-25 09:17:44', NULL, NULL, NULL, '2026-05-25 09:17:44', '2026-05-25 09:17:44'),
('372633a6-ed08-4f97-9fbc-cd847646681e', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'f0910fd6-f344-425a-88e3-e91a2de26094', 'JRN-20260708-0002', '2026-07-08', 'payment', '019f41da-e0d1-704d-8b63-c0234dcdef88', 'INV-08-07-0001', 'POSTED', 'Posting otomatis pembayaran order', 110000.00, 110000.00, 3, 3, '2026-07-08 13:11:31', NULL, NULL, NULL, '2026-07-08 13:11:31', '2026-07-08 13:11:31'),
('6e603f9b-01e0-4376-b705-3e4ebd7017ba', '9617c6c0-85c2-463e-9885-31e0698f5d77', '03822e41-570d-459d-86e8-044c492d6c17', 'JRN-20260525-0011', '2026-05-25', 'payment', '019e5e77-3979-7088-9a50-4f763fca6b75', 'INV-25-05-0012', 'POSTED', 'Posting otomatis pelunasan piutang tunai', 68750.00, 68750.00, 2, 2, '2026-05-25 09:28:52', NULL, NULL, NULL, '2026-05-25 09:28:52', '2026-05-25 09:28:52'),
('87ae4753-5c1f-463b-8d39-1e1d38fa46e0', '9617c6c0-85c2-463e-9885-31e0698f5d77', NULL, 'JRN-20260525-0001', '2026-05-25', 'manual', NULL, NULL, 'VOID', NULL, 50000.00, 50000.00, 2, NULL, NULL, 1, '2026-05-25 08:46:20', 'nayoba', '2026-05-25 08:38:31', '2026-05-25 08:46:20'),
('9929e313-065f-4a1a-ab0a-b9c253258a4d', '9617c6c0-85c2-463e-9885-31e0698f5d77', '59482b0f-a6d7-4d47-9c64-7c90e3d37f6e', 'JRN-20260525-0010', '2026-05-25', 'payment', '019e5e76-6776-7106-adb1-f9b3a09ddc22', 'INV-25-05-0012', 'POSTED', 'Posting otomatis pembayaran order', 25000.00, 25000.00, 2, 2, '2026-05-25 09:27:58', NULL, NULL, NULL, '2026-05-25 09:27:58', '2026-05-25 09:27:58'),
('9db474ed-79c4-4835-a0c4-4fb9bfddd16f', '9617c6c0-85c2-463e-9885-31e0698f5d77', '03822e41-570d-459d-86e8-044c492d6c17', 'JRN-20260525-0009', '2026-05-25', 'payment', '019e5e6f-36df-712b-805f-7d1051742cb4', 'INV-18-04-0013', 'POSTED', 'Posting otomatis pelunasan piutang tunai', 93750.00, 93750.00, 2, 2, '2026-05-25 09:20:07', NULL, NULL, NULL, '2026-05-25 09:20:07', '2026-05-25 09:20:07'),
('9e2841bd-9869-4025-8260-d572829a55ba', '9617c6c0-85c2-463e-9885-31e0698f5d77', '33f00c08-c352-45f1-b564-376f51c4a8a8', 'JRN-20260525-0002', '2026-05-25', 'cash_mutation', '019e5e58-54ac-739d-8128-90da743660fa', NULL, 'POSTED', 'Modal awal kas', 50000.00, 50000.00, 1, 1, '2026-05-25 08:55:07', NULL, NULL, NULL, '2026-05-25 08:55:07', '2026-05-25 08:55:07'),
('a9e33137-1afc-4a3b-afb3-629a26f394d4', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'f0910fd6-f344-425a-88e3-e91a2de26094', 'JRN-20260525-0005', '2026-05-25', 'payment', '019e5e67-1267-738c-a6ca-21044ee70e36', 'INV-25-05-0008', 'POSTED', 'Posting otomatis pembayaran order', 125000.00, 125000.00, 2, 2, '2026-05-25 09:11:13', NULL, NULL, NULL, '2026-05-25 09:11:13', '2026-05-25 09:11:13'),
('aea79db5-b7e5-42e4-ad44-231b9dde447e', '9617c6c0-85c2-463e-9885-31e0698f5d77', '03822e41-570d-459d-86e8-044c492d6c17', 'JRN-20260525-0004', '2026-05-25', 'payment', '019e5e5b-0258-73ff-8970-8f5d24dfb397', 'INV-25-05-0006', 'POSTED', 'Posting otomatis pelunasan piutang tunai', 45000.00, 45000.00, 2, 2, '2026-05-25 08:58:03', NULL, NULL, NULL, '2026-05-25 08:58:03', '2026-05-25 08:58:03'),
('aebf3e04-8674-4cce-9c65-88e3653dad68', '9617c6c0-85c2-463e-9885-31e0698f5d77', '03822e41-570d-459d-86e8-044c492d6c17', 'JRN-20260525-0003', '2026-05-25', 'payment', '019e5e59-ce64-71e8-a6be-e39f58cae540', 'INV-19-04-0014', 'POSTED', 'Posting otomatis pelunasan piutang tunai', 45000.00, 45000.00, 2, 2, '2026-05-25 08:56:44', NULL, NULL, NULL, '2026-05-25 08:56:44', '2026-05-25 08:56:44'),
('f1274abc-07ea-4555-a804-ddbaa32d2027', '9617c6c0-85c2-463e-9885-31e0698f5d77', '59482b0f-a6d7-4d47-9c64-7c90e3d37f6e', 'JRN-20260525-0007', '2026-05-25', 'payment', '019e5e6c-6ab7-70cb-925f-15609b28ab0e', 'INV-25-05-0011', 'POSTED', 'Posting otomatis pembayaran order', 20000.00, 20000.00, 2, 2, '2026-05-25 09:17:04', NULL, NULL, NULL, '2026-05-25 09:17:04', '2026-05-25 09:17:04'),
('f73557f9-5d8e-4efe-bc51-507d117a111e', '9617c6c0-85c2-463e-9885-31e0698f5d77', '4417b1c9-60de-4ab5-9aab-dbf70c91774e', 'JRN-20260525-0006', '2026-05-25', 'payment', '019e5e68-a48c-7322-8f93-4767212c3f6f', 'INV-25-05-0010', 'POSTED', 'Posting otomatis pembayaran order', 50000.00, 50000.00, 2, 2, '2026-05-25 09:12:56', NULL, NULL, NULL, '2026-05-25 09:12:56', '2026-05-25 09:12:56');

-- --------------------------------------------------------

--
-- Table structure for table `accounting_journal_lines`
--

CREATE TABLE `accounting_journal_lines` (
  `id` char(36) NOT NULL,
  `journal_entry_id` char(36) NOT NULL,
  `account_id` char(36) NOT NULL,
  `description` text DEFAULT NULL,
  `debit` decimal(14,2) NOT NULL DEFAULT 0.00,
  `credit` decimal(14,2) NOT NULL DEFAULT 0.00,
  `line_order` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accounting_journal_lines`
--

INSERT INTO `accounting_journal_lines` (`id`, `journal_entry_id`, `account_id`, `description`, `debit`, `credit`, `line_order`, `created_at`, `updated_at`) VALUES
('04b98d3e-18cd-46e7-9e0e-e74f5df663dd', 'a9e33137-1afc-4a3b-afb3-629a26f394d4', '272f026b-f271-4244-a35e-0dad45dc3023', 'Posting otomatis pembayaran order', 0.00, 125000.00, 2, '2026-05-25 09:11:13', '2026-05-25 09:11:13'),
('18ec34c1-15d0-49e9-8c28-d741a0d14c07', 'aea79db5-b7e5-42e4-ad44-231b9dde447e', 'ddcdbac8-7b25-4a9e-9909-dcd35dab5dda', 'Posting otomatis pelunasan piutang tunai', 0.00, 45000.00, 2, '2026-05-25 08:58:03', '2026-05-25 08:58:03'),
('2aeb15de-34ac-4e4e-942c-1dfa5d7f8383', '6e603f9b-01e0-4376-b705-3e4ebd7017ba', 'ddcdbac8-7b25-4a9e-9909-dcd35dab5dda', 'Posting otomatis pelunasan piutang tunai', 0.00, 68750.00, 2, '2026-05-25 09:28:52', '2026-05-25 09:28:52'),
('2f67f9af-30ee-4aee-97cb-93d5fa8ec26f', '87ae4753-5c1f-463b-8d39-1e1d38fa46e0', '799907c0-077d-486b-9e6c-ae415188fe24', NULL, 50000.00, 0.00, 1, '2026-05-25 08:38:31', '2026-05-25 08:38:31'),
('436d1bcd-6c4d-4cd1-a253-3f5d2009ad8f', 'f73557f9-5d8e-4efe-bc51-507d117a111e', '4656b0b3-3cc5-44f3-a709-843be023453d', 'Posting otomatis pembayaran order', 50000.00, 0.00, 1, '2026-05-25 09:12:56', '2026-05-25 09:12:56'),
('452599b8-7cbd-44a0-a447-262aa658930c', 'f73557f9-5d8e-4efe-bc51-507d117a111e', '272f026b-f271-4244-a35e-0dad45dc3023', 'Posting otomatis pembayaran order', 0.00, 50000.00, 2, '2026-05-25 09:12:56', '2026-05-25 09:12:56'),
('4ad471a6-1dc5-4203-9b06-4b7604dca46a', 'aea79db5-b7e5-42e4-ad44-231b9dde447e', '3177eeae-9904-47a8-a1e2-10aff6561ae1', 'Posting otomatis pelunasan piutang tunai', 45000.00, 0.00, 1, '2026-05-25 08:58:03', '2026-05-25 08:58:03'),
('51508549-b56b-4423-a3fb-1f66e6290ae1', 'aebf3e04-8674-4cce-9c65-88e3653dad68', 'ddcdbac8-7b25-4a9e-9909-dcd35dab5dda', 'Posting otomatis pelunasan piutang tunai', 0.00, 45000.00, 2, '2026-05-25 08:56:44', '2026-05-25 08:56:44'),
('581c9e19-fbfd-40cf-9f45-177e3e31c56e', '02a71f8d-0ffd-4fab-903c-3f57ce23a025', '589bf859-8acf-4c68-be53-c50612da3435', 'Modal awal kas', 0.00, 502500.00, 2, '2026-07-08 13:11:23', '2026-07-08 13:11:23'),
('66b62831-1745-4059-82b7-20fa5e29b891', '9e2841bd-9869-4025-8260-d572829a55ba', '3177eeae-9904-47a8-a1e2-10aff6561ae1', 'Modal awal kas', 50000.00, 0.00, 1, '2026-05-25 08:55:07', '2026-05-25 08:55:07'),
('69dd72ae-0c0c-408d-a587-9706d266e66f', '02a71f8d-0ffd-4fab-903c-3f57ce23a025', '3177eeae-9904-47a8-a1e2-10aff6561ae1', 'Modal awal kas', 502500.00, 0.00, 1, '2026-07-08 13:11:23', '2026-07-08 13:11:23'),
('80ed498a-77c5-4dc9-964c-49b3be9052d8', '372633a6-ed08-4f97-9fbc-cd847646681e', '3177eeae-9904-47a8-a1e2-10aff6561ae1', 'Posting otomatis pembayaran order', 110000.00, 0.00, 1, '2026-07-08 13:11:31', '2026-07-08 13:11:31'),
('868263e9-14cc-4fca-9edf-69b41e3b6c8c', '9e2841bd-9869-4025-8260-d572829a55ba', '589bf859-8acf-4c68-be53-c50612da3435', 'Modal awal kas', 0.00, 50000.00, 2, '2026-05-25 08:55:07', '2026-05-25 08:55:07'),
('8e5bb8df-56f1-4df7-8043-1189e012f4ba', 'f1274abc-07ea-4555-a804-ddbaa32d2027', '3177eeae-9904-47a8-a1e2-10aff6561ae1', 'Posting otomatis pembayaran order', 20000.00, 0.00, 1, '2026-05-25 09:17:04', '2026-05-25 09:17:04'),
('a309eb8d-fcb0-4484-a4fb-acb7fefef11e', '87ae4753-5c1f-463b-8d39-1e1d38fa46e0', '3177eeae-9904-47a8-a1e2-10aff6561ae1', NULL, 0.00, 50000.00, 2, '2026-05-25 08:38:31', '2026-05-25 08:38:31'),
('adcb694e-ed0a-48d7-a245-8941567f9d16', '6e603f9b-01e0-4376-b705-3e4ebd7017ba', '3177eeae-9904-47a8-a1e2-10aff6561ae1', 'Posting otomatis pelunasan piutang tunai', 68750.00, 0.00, 1, '2026-05-25 09:28:52', '2026-05-25 09:28:52'),
('b95e12a8-6b16-4432-8ca6-420caad13c14', '9929e313-065f-4a1a-ab0a-b9c253258a4d', '62610b80-9694-4b9c-b677-66da3f0b1b41', 'Posting otomatis pembayaran order', 0.00, 25000.00, 2, '2026-05-25 09:27:58', '2026-05-25 09:27:58'),
('ca7421b3-d262-4f12-b337-d983a2ec2a78', '276dcf2c-ccad-4324-8c86-e3e8e1a5a960', 'ddcdbac8-7b25-4a9e-9909-dcd35dab5dda', 'Posting otomatis pelunasan piutang tunai', 0.00, 25000.00, 2, '2026-05-25 09:17:44', '2026-05-25 09:17:44'),
('cbe83e7f-de72-4885-8821-620518ed8f16', 'a9e33137-1afc-4a3b-afb3-629a26f394d4', '3177eeae-9904-47a8-a1e2-10aff6561ae1', 'Posting otomatis pembayaran order', 125000.00, 0.00, 1, '2026-05-25 09:11:13', '2026-05-25 09:11:13'),
('d8f585a2-293d-4142-a083-3473e6a80abe', '9db474ed-79c4-4835-a0c4-4fb9bfddd16f', 'ddcdbac8-7b25-4a9e-9909-dcd35dab5dda', 'Posting otomatis pelunasan piutang tunai', 0.00, 93750.00, 2, '2026-05-25 09:20:07', '2026-05-25 09:20:07'),
('de4a560b-0b9f-4a12-a070-9332cd5f94d6', 'aebf3e04-8674-4cce-9c65-88e3653dad68', '3177eeae-9904-47a8-a1e2-10aff6561ae1', 'Posting otomatis pelunasan piutang tunai', 45000.00, 0.00, 1, '2026-05-25 08:56:44', '2026-05-25 08:56:44'),
('df4d7090-4faa-4bfd-894c-9ebbab140da1', '9db474ed-79c4-4835-a0c4-4fb9bfddd16f', '3177eeae-9904-47a8-a1e2-10aff6561ae1', 'Posting otomatis pelunasan piutang tunai', 93750.00, 0.00, 1, '2026-05-25 09:20:07', '2026-05-25 09:20:07'),
('e768e832-e11a-43a5-a174-1318a4a5018f', '372633a6-ed08-4f97-9fbc-cd847646681e', '272f026b-f271-4244-a35e-0dad45dc3023', 'Posting otomatis pembayaran order', 0.00, 110000.00, 2, '2026-07-08 13:11:31', '2026-07-08 13:11:31'),
('eb2b61d1-ea35-413d-b782-6a0479d8e149', '276dcf2c-ccad-4324-8c86-e3e8e1a5a960', '3177eeae-9904-47a8-a1e2-10aff6561ae1', 'Posting otomatis pelunasan piutang tunai', 25000.00, 0.00, 1, '2026-05-25 09:17:44', '2026-05-25 09:17:44'),
('f249e5b6-3f70-49dd-aff6-0af0c5d10ae5', 'f1274abc-07ea-4555-a804-ddbaa32d2027', '62610b80-9694-4b9c-b677-66da3f0b1b41', 'Posting otomatis pembayaran order', 0.00, 20000.00, 2, '2026-05-25 09:17:04', '2026-05-25 09:17:04'),
('f918d68b-4699-4582-a6db-a1c8fbb0beef', '9929e313-065f-4a1a-ab0a-b9c253258a4d', '3177eeae-9904-47a8-a1e2-10aff6561ae1', 'Posting otomatis pembayaran order', 25000.00, 0.00, 1, '2026-05-25 09:27:58', '2026-05-25 09:27:58');

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` char(36) NOT NULL,
  `code` varchar(32) NOT NULL,
  `name` varchar(150) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `invoice_prefix` varchar(8) NOT NULL DEFAULT 'SLV',
  `reset_policy` enum('monthly','never') NOT NULL DEFAULT 'monthly',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `code`, `name`, `address`, `invoice_prefix`, `reset_policy`, `created_at`, `updated_at`) VALUES
('76674448-57af-46dd-ab26-c8c9a1e2292d', 'CBG-002', 'Cabang Ketiga', 'Permata Biru Block Ar.06', 'SLV', 'never', '2026-04-15 06:20:23', '2026-04-15 06:20:37'),
('9617c6c0-85c2-463e-9885-31e0698f5d77', 'CBG-001', 'Cabang Utama', 'Alamat Cabang Utama', 'SLV', 'monthly', '2026-03-29 00:44:53', '2026-03-29 00:44:53');

-- --------------------------------------------------------

--
-- Table structure for table `branch_user`
--

CREATE TABLE `branch_user` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` char(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branch_user`
--

INSERT INTO `branch_user` (`user_id`, `branch_id`) VALUES
(2, '9617c6c0-85c2-463e-9885-31e0698f5d77'),
(3, '9617c6c0-85c2-463e-9885-31e0698f5d77'),
(4, '9617c6c0-85c2-463e-9885-31e0698f5d77'),
(5, '9617c6c0-85c2-463e-9885-31e0698f5d77'),
(6, '9617c6c0-85c2-463e-9885-31e0698f5d77');

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
-- Table structure for table `cash_mutations`
--

CREATE TABLE `cash_mutations` (
  `id` char(36) NOT NULL,
  `cash_session_id` char(36) NOT NULL,
  `branch_id` char(36) NOT NULL,
  `type` enum('OPENING_FLOAT','SALE_CASH','RECEIVABLE_CASH_SETTLEMENT','EXPENSE_CASH','WITHDRAWAL','ADJUSTMENT_IN','ADJUSTMENT_OUT') NOT NULL,
  `direction` enum('IN','OUT') NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `source_type` varchar(50) DEFAULT NULL,
  `source_id` char(36) DEFAULT NULL,
  `reference_no` varchar(100) DEFAULT NULL,
  `note` varchar(200) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `effective_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cash_mutations`
--

INSERT INTO `cash_mutations` (`id`, `cash_session_id`, `branch_id`, `type`, `direction`, `amount`, `source_type`, `source_id`, `reference_no`, `note`, `created_by`, `effective_at`, `created_at`, `updated_at`) VALUES
('019d95cf-b2a7-73a6-96b0-4051303f9f3d', '019d95cf-b29f-7113-8e0f-ea3282047dd5', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'OPENING_FLOAT', 'IN', 0.00, 'cash_session', '019d95cf-b29f-7113-8e0f-ea3282047dd5', NULL, 'Modal awal kas', 1, '2026-04-16 10:21:50', '2026-04-16 10:21:50', '2026-04-16 10:21:50'),
('019d95d0-9b9e-734b-a8bd-001ef43c77f2', '019d95cf-b29f-7113-8e0f-ea3282047dd5', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'WITHDRAWAL', 'OUT', 50000.00, 'withdrawal', '443bbeff-704e-4951-85a2-2925bb30f163', NULL, 'Penarikan kas harian', 1, '2026-04-16 10:22:49', '2026-04-16 10:22:49', '2026-04-16 10:22:49'),
('019d9601-86e3-72f2-b924-358d9687f270', '019d95cf-b29f-7113-8e0f-ea3282047dd5', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'RECEIVABLE_CASH_SETTLEMENT', 'IN', 150000.00, 'payment', '019d9601-86b5-73f5-9df8-0689561b59e1', 'INV-15-04-0006', 'Pelunasan piutang tunai', 3, '2026-04-15 21:16:00', '2026-04-16 11:16:15', '2026-04-16 11:16:15'),
('019d9629-0c70-73c2-a979-9b7990471517', '019d9629-0c65-72f7-aab0-d17cc950896a', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'OPENING_FLOAT', 'IN', 200000.00, 'cash_session', '019d9629-0c65-72f7-aab0-d17cc950896a', NULL, 'Modal awal kas', 1, '2026-04-19 13:35:55', '2026-04-16 11:59:25', '2026-04-19 13:35:55'),
('019d996c-6c03-72ef-bd29-9ab8d40ebd53', '019d9629-0c65-72f7-aab0-d17cc950896a', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'SALE_CASH', 'IN', 100000.00, 'payment', '019d996c-6b66-7334-989e-f5f3a8ff11d0', 'INV-17-04-0009', 'Pembayaran order tunai', 3, '2026-04-17 03:11:00', '2026-04-17 03:11:52', '2026-04-17 03:11:52'),
('019dbe3e-7cc1-732b-a7d7-30ec6439ace6', '019dbe3e-7cbf-732b-9c6a-a3572fc5e7e0', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'OPENING_FLOAT', 'IN', 50000.00, 'cash_session', '019dbe3e-7cbf-732b-9c6a-a3572fc5e7e0', NULL, 'Modal awal kas', 1, '2026-04-24 06:47:39', '2026-04-24 06:47:39', '2026-04-24 06:47:39'),
('019dc308-d49a-7211-9ac8-5612ef583d4b', '019dc308-d475-7179-9c80-079af874a280', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'OPENING_FLOAT', 'IN', 50000.00, 'cash_session', '019dc308-d475-7179-9c80-079af874a280', NULL, 'Modal awal kas', 2, '2026-04-25 05:07:09', '2026-04-25 05:07:09', '2026-04-25 05:07:09'),
('019de857-6455-7313-a440-087f26279870', '019de857-6453-7109-ab51-982d492958a7', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'OPENING_FLOAT', 'IN', 50000.00, 'cash_session', '019de857-6453-7109-ab51-982d492958a7', NULL, 'Modal awal kas', 1, '2026-05-02 10:58:54', '2026-05-02 10:58:54', '2026-05-02 10:58:54'),
('019e05e2-f0d9-728d-9be2-0cace429bf98', '019e05e2-f0d6-7168-9b1c-48fa4c732902', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'OPENING_FLOAT', 'IN', 50000.00, 'cash_session', '019e05e2-f0d6-7168-9b1c-48fa4c732902', NULL, 'Modal awal kas', 1, '2026-05-08 04:40:19', '2026-05-08 04:40:19', '2026-05-08 04:40:19'),
('019e5a2a-0bf0-7280-a39c-30185e689bed', '019e5a2a-0bec-72b0-929b-117773256c1e', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'OPENING_FLOAT', 'IN', 50000.00, 'cash_session', '019e5a2a-0bec-72b0-929b-117773256c1e', NULL, 'Modal awal kas', 1, '2026-05-24 13:26:05', '2026-05-24 13:26:05', '2026-05-24 13:26:05'),
('019e5e58-54ac-739d-8128-90da743660fa', '019e5e58-54a9-7100-8535-6f1fa9e77d02', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'OPENING_FLOAT', 'IN', 50000.00, 'cash_session', '019e5e58-54a9-7100-8535-6f1fa9e77d02', NULL, 'Modal awal kas', 1, '2026-05-25 08:55:07', '2026-05-25 08:55:07', '2026-05-25 08:55:07'),
('019e5e59-ce76-7286-9881-194bfc70d0df', '019e5e58-54a9-7100-8535-6f1fa9e77d02', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'SALE_CASH', 'IN', 45000.00, 'payment', '019e5e59-ce64-71e8-a6be-e39f58cae540', 'INV-19-04-0014', 'Pembayaran order tunai', 2, '2026-05-25 08:56:00', '2026-05-25 08:56:44', '2026-05-25 08:56:44'),
('019e5e5b-0277-7341-b4a9-a8c7b769f20d', '019e5e58-54a9-7100-8535-6f1fa9e77d02', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'SALE_CASH', 'IN', 45000.00, 'payment', '019e5e5b-0258-73ff-8970-8f5d24dfb397', 'INV-25-05-0006', 'Pembayaran order tunai', 2, '2026-05-25 08:58:00', '2026-05-25 08:58:03', '2026-05-25 08:58:03'),
('019e5e5d-6e46-710c-a93a-4b1dc653ef30', '019e5e58-54a9-7100-8535-6f1fa9e77d02', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'SALE_CASH', 'IN', 50000.00, 'payment', '019e5e5d-6d9b-73c4-abf6-cd746dda9c91', 'INV-25-05-0007', 'Pembayaran order tunai', 2, '2026-05-25 09:00:00', '2026-05-25 09:00:41', '2026-05-25 09:00:41'),
('019e5e67-12c0-707a-9793-b6fce009b657', '019e5e58-54a9-7100-8535-6f1fa9e77d02', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'SALE_CASH', 'IN', 125000.00, 'payment', '019e5e67-1267-738c-a6ca-21044ee70e36', 'INV-25-05-0008', 'Pembayaran order tunai', 2, '2026-05-25 09:11:00', '2026-05-25 09:11:13', '2026-05-25 09:11:13'),
('019e5e6d-08ef-705d-a664-9baf0ec65999', '019e5e58-54a9-7100-8535-6f1fa9e77d02', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'RECEIVABLE_CASH_SETTLEMENT', 'IN', 25000.00, 'payment', '019e5e6d-08e3-7210-b3a2-78cc4fbb69fe', 'INV-25-05-0011', 'Pelunasan piutang tunai', 2, '2026-05-25 09:17:00', '2026-05-25 09:17:44', '2026-05-25 09:17:44'),
('019e5e6f-36f7-7282-aff7-491b87363a6a', '019e5e58-54a9-7100-8535-6f1fa9e77d02', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'SALE_CASH', 'IN', 93750.00, 'payment', '019e5e6f-36df-712b-805f-7d1051742cb4', 'INV-18-04-0013', 'Pembayaran order tunai', 2, '2026-05-25 09:20:00', '2026-05-25 09:20:07', '2026-05-25 09:20:07'),
('019e5e77-3990-7360-943e-21edd0487de2', '019e5e58-54a9-7100-8535-6f1fa9e77d02', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'RECEIVABLE_CASH_SETTLEMENT', 'IN', 68750.00, 'payment', '019e5e77-3979-7088-9a50-4f763fca6b75', 'INV-25-05-0012', 'Pelunasan piutang tunai', 2, '2026-05-25 09:28:00', '2026-05-25 09:28:52', '2026-05-25 09:28:52'),
('019f41da-c0e1-7019-a683-faa86509aee5', '019f41da-c0de-70ff-a028-36c776c61120', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'OPENING_FLOAT', 'IN', 502500.00, 'cash_session', '019f41da-c0de-70ff-a028-36c776c61120', NULL, 'Modal awal kas', 1, '2026-07-08 13:11:23', '2026-07-08 13:11:23', '2026-07-08 13:11:23'),
('019f41da-e101-70f3-8646-5ba145b233c4', '019f41da-c0de-70ff-a028-36c776c61120', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'SALE_CASH', 'IN', 110000.00, 'payment', '019f41da-e0d1-704d-8b63-c0234dcdef88', 'INV-08-07-0001', 'Pembayaran order tunai', 3, '2026-07-08 13:11:00', '2026-07-08 13:11:31', '2026-07-08 13:11:31');

-- --------------------------------------------------------

--
-- Table structure for table `cash_sessions`
--

CREATE TABLE `cash_sessions` (
  `id` char(36) NOT NULL,
  `branch_id` char(36) NOT NULL,
  `business_date` date NOT NULL,
  `status` enum('OPEN','CLOSED') NOT NULL DEFAULT 'OPEN',
  `opened_by` bigint(20) UNSIGNED NOT NULL,
  `opened_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `opening_cash` decimal(12,2) NOT NULL DEFAULT 0.00,
  `closed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `closing_cash_system` decimal(12,2) DEFAULT NULL,
  `closing_cash_counted` decimal(12,2) DEFAULT NULL,
  `difference_amount` decimal(12,2) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cash_sessions`
--

INSERT INTO `cash_sessions` (`id`, `branch_id`, `business_date`, `status`, `opened_by`, `opened_at`, `opening_cash`, `closed_by`, `closed_at`, `closing_cash_system`, `closing_cash_counted`, `difference_amount`, `notes`, `created_at`, `updated_at`) VALUES
('019d95cf-b29f-7113-8e0f-ea3282047dd5', '9617c6c0-85c2-463e-9885-31e0698f5d77', '2026-04-16', 'CLOSED', 1, '2026-04-16 11:16:57', 0.00, 1, '2026-04-16 11:16:57', 100000.00, 0.00, -100000.00, NULL, '2026-04-16 10:21:50', '2026-04-16 11:16:57'),
('019d9629-0c65-72f7-aab0-d17cc950896a', '9617c6c0-85c2-463e-9885-31e0698f5d77', '2026-04-17', 'CLOSED', 1, '2026-04-24 06:47:27', 200000.00, 1, '2026-04-24 06:47:27', 300000.00, 300000.00, 0.00, NULL, '2026-04-16 11:59:25', '2026-04-24 06:47:27'),
('019dbe3e-7cbf-732b-9c6a-a3572fc5e7e0', '9617c6c0-85c2-463e-9885-31e0698f5d77', '2026-04-24', 'CLOSED', 1, '2026-04-25 05:07:19', 50000.00, 2, '2026-04-25 05:07:19', 50000.00, 50000.00, 0.00, NULL, '2026-04-24 06:47:39', '2026-04-25 05:07:19'),
('019dc308-d475-7179-9c80-079af874a280', '9617c6c0-85c2-463e-9885-31e0698f5d77', '2026-04-25', 'CLOSED', 2, '2026-05-02 10:58:48', 50000.00, 1, '2026-05-02 10:58:48', 50000.00, 50000.00, 0.00, NULL, '2026-04-25 05:07:09', '2026-05-02 10:58:48'),
('019de857-6453-7109-ab51-982d492958a7', '9617c6c0-85c2-463e-9885-31e0698f5d77', '2026-05-02', 'CLOSED', 1, '2026-05-08 04:40:10', 50000.00, 1, '2026-05-08 04:40:10', 50000.00, 50000.00, 0.00, NULL, '2026-05-02 10:58:54', '2026-05-08 04:40:10'),
('019e05e2-f0d6-7168-9b1c-48fa4c732902', '9617c6c0-85c2-463e-9885-31e0698f5d77', '2026-05-08', 'CLOSED', 1, '2026-05-24 13:25:59', 50000.00, 1, '2026-05-24 13:25:59', 50000.00, 50000.00, 0.00, NULL, '2026-05-08 04:40:19', '2026-05-24 13:25:59'),
('019e5a2a-0bec-72b0-929b-117773256c1e', '9617c6c0-85c2-463e-9885-31e0698f5d77', '2026-05-24', 'CLOSED', 1, '2026-05-25 08:41:10', 50000.00, 2, '2026-05-25 08:41:10', 50000.00, 50000.00, 0.00, NULL, '2026-05-24 13:26:05', '2026-05-25 08:41:10'),
('019e5e58-54a9-7100-8535-6f1fa9e77d02', '9617c6c0-85c2-463e-9885-31e0698f5d77', '2026-05-25', 'CLOSED', 1, '2026-07-08 13:11:02', 50000.00, 1, '2026-07-08 13:11:02', 502500.00, 502500.00, 0.00, NULL, '2026-05-25 08:55:07', '2026-07-08 13:11:02'),
('019f41da-c0de-70ff-a028-36c776c61120', '9617c6c0-85c2-463e-9885-31e0698f5d77', '2026-07-08', 'OPEN', 1, '2026-07-08 13:11:23', 502500.00, NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-08 13:11:23', '2026-07-08 13:11:23');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` char(36) NOT NULL,
  `branch_id` char(36) NOT NULL,
  `name` varchar(150) NOT NULL,
  `whatsapp` varchar(32) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `branch_id`, `name`, `whatsapp`, `address`, `notes`, `tags`, `created_at`, `updated_at`) VALUES
('3730104d-ef9b-41cd-b89b-94061d32b82b', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'jajang', '08123545678', 'komplek cileunyi', NULL, '[]', '2026-04-18 09:25:47', '2026-04-18 09:25:47'),
('7e470177-a56f-4309-8bd3-48be37a4c5b9', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'galuh', '082119664428', 'permata biru blok ar,06', NULL, '[\"VIP\"]', '2026-04-15 04:01:02', '2026-04-16 06:48:33');

-- --------------------------------------------------------

--
-- Table structure for table `deliveries`
--

CREATE TABLE `deliveries` (
  `id` char(36) NOT NULL,
  `order_id` char(36) NOT NULL,
  `type` varchar(20) NOT NULL,
  `zone_id` char(36) DEFAULT NULL,
  `fee` decimal(12,2) NOT NULL DEFAULT 0.00,
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `auto_assigned` tinyint(1) NOT NULL DEFAULT 0,
  `status` varchar(32) NOT NULL DEFAULT 'CREATED',
  `handover_photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `deliveries`
--

INSERT INTO `deliveries` (`id`, `order_id`, `type`, `zone_id`, `fee`, `assigned_to`, `auto_assigned`, `status`, `handover_photo`, `created_at`, `updated_at`) VALUES
('019d9989-be5a-7350-92e6-afc53124ec5e', '019d996c-6759-72a6-80f5-b7a96613fba9', 'delivery', NULL, 2000.00, 5, 1, 'COMPLETED', NULL, '2026-04-17 03:43:54', '2026-04-17 03:45:43');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_events`
--

CREATE TABLE `delivery_events` (
  `id` char(36) NOT NULL,
  `delivery_id` char(36) NOT NULL,
  `status` varchar(32) NOT NULL,
  `note` varchar(200) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `delivery_events`
--

INSERT INTO `delivery_events` (`id`, `delivery_id`, `status`, `note`, `created_at`, `updated_at`) VALUES
('019d9989-be81-72c2-a237-8e45182bc9d6', '019d9989-be5a-7350-92e6-afc53124ec5e', 'CREATED', 'Delivery created', '2026-04-17 03:43:54', '2026-04-17 03:43:54'),
('019d9989-be94-73f8-a4d2-34fbd81e88a3', '019d9989-be5a-7350-92e6-afc53124ec5e', 'ASSIGNED', 'Auto-assigned courier #5', '2026-04-17 03:43:54', '2026-04-17 03:43:54'),
('019d9989-f7b3-70bd-8fe9-2ac7173e23ca', '019d9989-be5a-7350-92e6-afc53124ec5e', 'ON_THE_WAY', NULL, '2026-04-17 03:44:09', '2026-04-17 03:44:09'),
('019d998b-2e9f-733e-8093-4ca28d6bfec1', '019d9989-be5a-7350-92e6-afc53124ec5e', 'PICKED', NULL, '2026-04-17 03:45:28', '2026-04-17 03:45:28'),
('019d998b-6965-7304-9cf9-190cf020343c', '019d9989-be5a-7350-92e6-afc53124ec5e', 'COMPLETED', NULL, '2026-04-17 03:45:43', '2026-04-17 03:45:43');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` char(36) NOT NULL,
  `branch_id` char(36) NOT NULL,
  `category` varchar(100) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_source` enum('NON_CASH','CASH_BOX') NOT NULL DEFAULT 'NON_CASH',
  `note` text DEFAULT NULL,
  `proof_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
-- Table structure for table `invoice_counters`
--

CREATE TABLE `invoice_counters` (
  `id` char(36) NOT NULL,
  `branch_id` char(36) NOT NULL,
  `prefix` varchar(8) NOT NULL DEFAULT 'SLV',
  `seq` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `reset_policy` enum('monthly','never') NOT NULL DEFAULT 'monthly',
  `last_reset_month` char(6) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_counters`
--

INSERT INTO `invoice_counters` (`id`, `branch_id`, `prefix`, `seq`, `reset_policy`, `last_reset_month`, `created_at`, `updated_at`) VALUES
('019d8f4d-265c-72fa-9a86-acc7ed7a345e', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'SLV', 1, 'monthly', '202607', '2026-04-15 04:01:31', '2026-07-08 13:11:30');

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
-- Table structure for table `loyalty_accounts`
--

CREATE TABLE `loyalty_accounts` (
  `id` char(36) NOT NULL,
  `customer_id` char(36) NOT NULL,
  `branch_id` char(36) NOT NULL,
  `stamps` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `lifetime` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `loyalty_accounts`
--

INSERT INTO `loyalty_accounts` (`id`, `customer_id`, `branch_id`, `stamps`, `lifetime`, `created_at`, `updated_at`) VALUES
('019d8f4c-b8a3-70f7-acda-ecc6771681e2', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '9617c6c0-85c2-463e-9885-31e0698f5d77', 9, 26, '2026-04-15 04:01:03', '2026-07-08 13:11:30'),
('019d9fe9-1ccf-71e0-94c2-6845b579559e', '3730104d-ef9b-41cd-b89b-94061d32b82b', '9617c6c0-85c2-463e-9885-31e0698f5d77', 9, 10, '2026-04-18 09:25:47', '2026-05-24 13:26:09');

-- --------------------------------------------------------

--
-- Table structure for table `loyalty_logs`
--

CREATE TABLE `loyalty_logs` (
  `id` char(36) NOT NULL,
  `order_id` char(36) DEFAULT NULL,
  `customer_id` char(36) NOT NULL,
  `branch_id` char(36) NOT NULL,
  `action` varchar(20) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `before` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `after` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `loyalty_logs`
--

INSERT INTO `loyalty_logs` (`id`, `order_id`, `customer_id`, `branch_id`, `action`, `note`, `before`, `after`, `created_at`, `updated_at`) VALUES
('019d8f4d-26db-71e7-a452-991a18efb0bd', '019d8f4d-2661-7388-9e93-6f3b0ada9fa9', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'EARN', NULL, 0, 1, '2026-04-15 04:01:31', '2026-04-15 04:01:31'),
('019d8f5a-5519-71a2-859e-c443c10f2239', '019d8f5a-5443-73d7-a0a0-1aa85285a363', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'EARN', NULL, 1, 2, '2026-04-15 04:15:55', '2026-04-15 04:15:55'),
('019d8ff4-332e-713e-83b8-611c7c91aab0', '019d8ff4-316b-70ec-932d-bd6dadb26769', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'EARN', NULL, 2, 3, '2026-04-15 07:03:59', '2026-04-15 07:03:59'),
('019d900b-66d8-71e7-adf9-445fc7afa8c1', '019d900b-6642-73ba-95be-d5c525edcbc3', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'EARN', NULL, 3, 4, '2026-04-15 07:29:19', '2026-04-15 07:29:19'),
('019d900d-2630-72fe-ba87-08da807827ca', NULL, '7e470177-a56f-4309-8bd3-48be37a4c5b9', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'REWARD25', NULL, 4, 4, '2026-04-15 07:31:14', '2026-04-15 07:31:14'),
('019d900d-2632-731b-93c3-13a9b08e6da2', '019d900d-25ce-71ca-9b71-466a0ef5810d', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'EARN', NULL, 4, 5, '2026-04-15 07:31:14', '2026-04-15 07:31:14'),
('019d9014-a181-7090-90f5-2909f9d94d29', '019d9014-a0ff-71fc-9377-66e3f7b2803e', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'EARN', NULL, 5, 6, '2026-04-15 07:39:24', '2026-04-15 07:39:24'),
('019d901c-8894-714d-ae07-cadc8761337f', '019d901c-8823-73a1-a365-e702f1f851b5', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'EARN', NULL, 6, 7, '2026-04-15 07:48:02', '2026-04-15 07:48:02'),
('019d996c-67f9-70c5-b8a7-4385d70f17a8', '019d996c-6759-72a6-80f5-b7a96613fba9', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'EARN', NULL, 7, 8, '2026-04-17 03:11:51', '2026-04-17 03:11:51'),
('0a8d210d-8408-44c1-a378-fad1c80cc75d', '019e5e6c-688b-72bc-98a5-6986f4c72c93', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'EARN', NULL, 6, 7, '2026-05-25 09:17:03', '2026-05-25 09:17:03'),
('0b22b0d2-8127-41fc-94da-2d817d6d5a17', '019e5e76-6529-73fa-9605-511e5352ba8e', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'EARN', NULL, 4, 5, '2026-05-25 09:27:58', '2026-05-25 09:27:58'),
('0ca65a1c-fadc-4931-93fb-3b3e5a2449cb', '019de857-ce74-7069-a058-1e6b38214e5a', '3730104d-ef9b-41cd-b89b-94061d32b82b', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'EARN', NULL, 7, 8, '2026-05-02 10:59:21', '2026-05-02 10:59:21'),
('19102dd2-ef28-4abe-9ce2-7d7d60e667be', NULL, '7e470177-a56f-4309-8bd3-48be37a4c5b9', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'MANUAL_SUB', NULL, 3, 2, '2026-05-10 08:20:47', '2026-05-10 08:20:47'),
('19624f5c-3338-4772-9fcc-c9669f22b604', NULL, '7e470177-a56f-4309-8bd3-48be37a4c5b9', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'RESET', NULL, 10, 0, '2026-04-21 04:12:37', '2026-04-21 04:12:37'),
('27bc2a05-575a-46b6-a0ff-e284fcf8fd94', '019e5e67-d337-7279-b33c-ebd731ae61b6', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'EARN', NULL, 4, 5, '2026-05-25 09:12:03', '2026-05-25 09:12:03'),
('2a3a2368-9466-47ce-84b2-993fa40ef0a0', '019e5e68-a273-70b0-951e-1f66f1c65534', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'EARN', NULL, 5, 6, '2026-05-25 09:12:56', '2026-05-25 09:12:56'),
('30c7d25c-7c9b-4e29-bf4b-47470320efd7', '019e5f2b-1964-7061-83ce-29cbca5fc54f', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'EARN', NULL, 6, 7, '2026-05-25 12:45:20', '2026-05-25 12:45:20'),
('33828c70-c424-4fcd-81c0-aea3693d59a2', '019de858-3a3b-73ae-8734-5516b49decd3', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'EARN', NULL, 2, 3, '2026-05-02 10:59:49', '2026-05-02 10:59:49'),
('3c7624de-071f-4736-80f5-a7545d55a76f', '019e5e5d-6b84-70db-a52f-4036ce523327', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'EARN', NULL, 2, 3, '2026-05-25 09:00:41', '2026-05-25 09:00:41'),
('3eff5738-6ee6-41a6-a805-adee9381ff8e', NULL, '7e470177-a56f-4309-8bd3-48be37a4c5b9', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'REWARD25', NULL, 4, 4, '2026-05-25 09:12:03', '2026-05-25 09:12:03'),
('4db8bc04-ca8a-4098-8380-a8fd8df8920f', '019dae3d-7967-7289-a506-daa31a50a6f6', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'EARN', NULL, 9, 0, '2026-04-21 04:12:37', '2026-04-21 04:12:37'),
('4fced752-7440-42d4-a1b8-65220a895384', '019e5f40-dd37-7221-bb02-d0929965bee6', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'EARN', NULL, 7, 8, '2026-05-25 13:09:07', '2026-05-25 13:09:07'),
('54a13350-1bbe-4c68-b58f-017e6e2c6b4a', '019da0a8-9036-70dd-92c0-ee053a9c5967', '3730104d-ef9b-41cd-b89b-94061d32b82b', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'EARN', NULL, 4, 5, '2026-04-18 12:54:54', '2026-04-18 12:54:54'),
('5684ee29-c327-466d-ba2d-d14e275e720a', NULL, '3730104d-ef9b-41cd-b89b-94061d32b82b', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'MANUAL_ADD', NULL, 0, 3, '2026-04-18 10:32:13', '2026-04-18 10:32:13'),
('5fed74ba-43cf-4785-a6e3-f76939ee4f49', NULL, '7e470177-a56f-4309-8bd3-48be37a4c5b9', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'MANUAL_SUB', NULL, 2, 1, '2026-05-10 08:20:48', '2026-05-10 08:20:48'),
('66c484aa-e02f-43e1-aabc-45e932f24ad4', '019dc309-b997-7022-aaa1-0acf2093144d', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'EARN', NULL, 1, 2, '2026-04-25 05:08:07', '2026-04-25 05:08:07'),
('721791a4-e1b5-43d5-8653-8a0cf7b15aa0', '019e5e58-f51a-7108-b3d7-c66897a8ebea', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'EARN', NULL, 1, 2, '2026-05-25 08:55:48', '2026-05-25 08:55:48'),
('7b1abd00-d579-4e05-ac73-c16a13cb90cb', '019f41da-de5e-7092-af35-39222437f538', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'EARN', NULL, 8, 9, '2026-07-08 13:11:30', '2026-07-08 13:11:30'),
('7d11fa59-0e3e-4bbe-85fd-3d9b919ef4ed', '019e5e67-1025-719f-9a34-5d652f6e2430', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'EARN', NULL, 3, 4, '2026-05-25 09:11:13', '2026-05-25 09:11:13'),
('83afc1de-2b6c-4979-8ceb-460fbfd9d85d', NULL, '7e470177-a56f-4309-8bd3-48be37a4c5b9', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'REWARD100', NULL, 9, 9, '2026-04-21 04:12:37', '2026-04-21 04:12:37'),
('888ef657-5318-4529-bd8a-6be746ba59d4', '019da08f-3673-72f9-8b78-6a30e1f19204', '3730104d-ef9b-41cd-b89b-94061d32b82b', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'EARN', NULL, 2, 3, '2026-04-18 12:27:13', '2026-04-18 12:27:13'),
('8c000cea-edc2-45e9-89ce-fd6be2c14a17', '019e5a2a-1cbe-714b-b7fb-befaa585aa3c', '3730104d-ef9b-41cd-b89b-94061d32b82b', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'EARN', NULL, 8, 9, '2026-05-24 13:26:09', '2026-05-24 13:26:09'),
('9476d4b9-f80a-4d56-9409-fb41d404dd3f', NULL, '7e470177-a56f-4309-8bd3-48be37a4c5b9', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'MANUAL_REWARD100', 'koreksi menyesuaikan stamp fisik | Order: INV-02-05-0003 | Koreksi oleh: Superadmin', 0, 0, '2026-05-10 08:08:00', '2026-05-10 08:08:00'),
('97584c36-1044-4510-aebf-553a50b0b681', '019da09e-0edb-737a-a86e-9cfccde6da76', '3730104d-ef9b-41cd-b89b-94061d32b82b', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'EARN', NULL, 3, 4, '2026-04-18 12:43:26', '2026-04-18 12:43:26'),
('ca60d548-f826-4a41-999b-e35d1c3593ec', '019da0a3-df16-71b5-a937-8c5e250df40f', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'EARN', NULL, 8, 9, '2026-04-18 12:49:47', '2026-04-18 12:49:47'),
('cea9b9a1-04f7-47ba-9829-3fba75f51bd2', '019e5a31-64c9-7261-b20c-32e2180a69b0', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'EARN', NULL, 0, 1, '2026-05-24 13:34:07', '2026-05-24 13:34:07'),
('cf687a4f-a652-4faf-a020-2f02d3bd61fc', NULL, '7e470177-a56f-4309-8bd3-48be37a4c5b9', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'MANUAL_SUB', NULL, 4, 3, '2026-05-10 08:20:44', '2026-05-10 08:20:44'),
('dc706733-6093-4985-ba94-8890e7e36643', '019da64e-d490-73d8-b46c-2b266b758872', '3730104d-ef9b-41cd-b89b-94061d32b82b', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'EARN', NULL, 5, 6, '2026-04-19 15:14:37', '2026-04-19 15:14:37'),
('dc987e3b-055e-4bc8-b786-b40af97b9ee5', NULL, '7e470177-a56f-4309-8bd3-48be37a4c5b9', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'MANUAL_SUB', NULL, 1, 0, '2026-05-10 08:20:50', '2026-05-10 08:20:50'),
('de07e2d5-da8e-42be-9877-e6c37020e709', '019e5ef4-d62e-718c-8352-f7f9be855513', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'EARN', NULL, 5, 6, '2026-05-25 11:46:04', '2026-05-25 11:46:04'),
('dfa57254-5c49-494f-b0e4-181dbe5e2e8a', NULL, '3730104d-ef9b-41cd-b89b-94061d32b82b', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'MANUAL_SUB', NULL, 3, 2, '2026-04-18 10:34:13', '2026-04-18 10:34:13'),
('e26c4c9b-3a06-4c41-9713-9b680650d78d', NULL, '3730104d-ef9b-41cd-b89b-94061d32b82b', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'REWARD25', NULL, 4, 4, '2026-04-18 12:54:54', '2026-04-18 12:54:54'),
('e81ec2c6-6210-41c8-8c60-71d3b784fd92', '019de858-9545-73a5-bb75-c60f555a0f4e', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'EARN', NULL, 3, 4, '2026-05-02 11:00:12', '2026-05-02 11:00:12'),
('e86ac93a-e2f0-4912-8ea2-caeac01ff24e', '019db9c0-4045-705b-a279-9c6596324665', '3730104d-ef9b-41cd-b89b-94061d32b82b', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'EARN', NULL, 6, 7, '2026-04-23 09:51:17', '2026-04-23 09:51:17'),
('ebcfee2c-70ce-4af4-bcf8-84f85635c307', NULL, '7e470177-a56f-4309-8bd3-48be37a4c5b9', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'REWARD25', NULL, 4, 4, '2026-05-25 09:27:58', '2026-05-25 09:27:58'),
('ed1bbdb5-cb34-4546-8df0-9444d7bb8114', '019db999-fa8e-71d9-803e-88989fe0cf90', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'EARN', NULL, 0, 1, '2026-04-23 09:09:29', '2026-04-23 09:09:29'),
('ef309ffc-ae22-4f0e-8cfd-3b3a0f7e37ca', NULL, '7e470177-a56f-4309-8bd3-48be37a4c5b9', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'MANUAL_SUB', NULL, 7, 4, '2026-05-25 09:24:53', '2026-05-25 09:24:53');

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
(4, '2025_10_21_115731_create_personal_access_tokens_table', 1),
(5, '2025_10_21_115843_create_permission_tables', 1),
(6, '2025_10_21_210811_add_branch_id_and_is_active_to_users_table', 1),
(7, '2025_10_22_174101_create_branches_table', 1),
(8, '2025_10_22_174120_alter_users_branch_to_uuid', 1),
(9, '2025_10_22_174133_create_invoice_counters_table', 1),
(10, '2025_10_22_191246_create_service_categories_table', 1),
(11, '2025_10_22_191311_create_services_table', 1),
(12, '2025_10_22_191325_create_service_prices_table', 1),
(13, '2025_10_23_001451_create_customers_table', 1),
(14, '2025_10_30_001505_create_orders_table', 1),
(15, '2025_10_30_001918_create_order_items_table', 1),
(16, '2025_10_30_003346_create_order_photos_table', 1),
(17, '2025_10_30_171044_fix_orders_unique_number_per_branch', 1),
(18, '2025_10_30_172859_alter_orders_add_payment_columns', 1),
(19, '2025_10_30_172919_create_payments_table', 1),
(20, '2025_10_30_172932_create_receivables_table', 1),
(21, '2025_11_05_184536_create_deliveries_table', 1),
(22, '2025_11_05_184557_create_delivery_events_table', 1),
(23, '2025_11_08_220927_create_vouchers_table', 1),
(24, '2025_11_08_223242_create_order_vouchers_table', 1),
(25, '2025_11_09_162010_create_receivables_table', 1),
(26, '2025_11_14_201006_create_expenses_table', 1),
(27, '2025_11_17_013822_add_dashboard_helper_indexes', 1),
(28, '2025_11_21_174811_make_invoice_no_unique_on_orders', 1),
(29, '2025_11_25_145010_fix_orders_created_by_to_bigint', 1),
(30, '2025_12_04_224859_add_dates_to_orders_table', 1),
(31, '2025_12_05_004843_add_username_to_users_table', 1),
(32, '2025_12_05_134121_create_loyalty_accounts_table', 1),
(33, '2025_12_05_134150_create_loyalty_logs_table', 1),
(34, '2025_12_05_134214_alter_orders_add_loyalty_columns', 1),
(35, '2025_12_07_160309_create_wash_notes_tables', 1),
(36, '2025_12_09_154416_add_unique_order_id_to_wash_note_items', 1),
(37, '2026_04_14_180613_create_whatsapp_templates_table', 2),
(38, '2026_04_16_124152_add_tags_to_customers_table', 3),
(39, '2026_04_16_165207_create_cash_sessions_table', 4),
(40, '2026_04_16_165416_create_cash_mutations_table', 4),
(41, '2026_04_16_165440_alter_expenses_add_payment_source', 4),
(42, '2026_04_18_165521_add_note_to_loyalty_logs_table', 5),
(43, '2026_04_24_202649_alter_orders_dates', 6),
(44, '2026_05_02_140915_create_production_tasks_table', 7),
(46, '2026_05_02_140938_create_production_task_log_table', 8),
(47, '2026_05_02_171421_create_production_task_correction_requests_table', 8),
(48, '2026_05_25_142513_create_accounting_accounts_table', 9),
(49, '2026_05_25_142517_create_accounting_accounts_table', 9),
(50, '2026_05_25_145347_create_accounting_journal_entries_table', 10),
(51, '2026_05_25_145402_create_accounting_journal_lines_table', 10),
(52, '2026_08_03_192029_add_craft_access_to_users_table', 11),
(53, '2026_08_03_192134_backfill_user_modules_from_roles', 11);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(3, 'App\\Models\\User', 3),
(4, 'App\\Models\\User', 4),
(4, 'App\\Models\\User', 6),
(5, 'App\\Models\\User', 5),
(6, 'App\\Models\\User', 7);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` char(36) NOT NULL,
  `branch_id` char(36) NOT NULL,
  `customer_id` char(36) DEFAULT NULL,
  `received_at` date DEFAULT NULL,
  `ready_at` date DEFAULT NULL,
  `number` varchar(40) NOT NULL,
  `invoice_no` varchar(40) DEFAULT NULL,
  `status` varchar(20) NOT NULL,
  `payment_status` varchar(20) NOT NULL DEFAULT 'PENDING',
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `dp_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `grand_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `paid_at` timestamp NULL DEFAULT NULL,
  `due_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `loyalty_reward` varchar(16) NOT NULL DEFAULT 'NONE',
  `loyalty_discount` decimal(12,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `branch_id`, `customer_id`, `received_at`, `ready_at`, `number`, `invoice_no`, `status`, `payment_status`, `subtotal`, `discount`, `dp_amount`, `grand_total`, `paid_amount`, `paid_at`, `due_amount`, `notes`, `created_at`, `updated_at`, `created_by`, `loyalty_reward`, `loyalty_discount`) VALUES
('019d8f4d-2661-7388-9e93-6f3b0ada9fa9', '9617c6c0-85c2-463e-9885-31e0698f5d77', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '2026-04-15', NULL, 'SLV-202604-000001', 'INV-15-04-0001', 'DRYING', 'PENDING', 50000.00, 0.00, 0.00, 50000.00, 0.00, NULL, 50000.00, NULL, '2026-04-15 04:01:31', '2026-05-02 10:54:48', 3, 'NONE', 0.00),
('019d8f5a-5443-73d7-a0a0-1aa85285a363', '9617c6c0-85c2-463e-9885-31e0698f5d77', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '2026-04-15', NULL, 'SLV-202604-000002', 'INV-15-04-0002', 'IRONING', 'PENDING', 100000.00, 0.00, 0.00, 100000.00, 0.00, NULL, 100000.00, '1. converse\n2. nb', '2026-04-15 04:15:54', '2026-05-02 08:04:34', 3, 'NONE', 0.00),
('019d8ff4-316b-70ec-932d-bd6dadb26769', '9617c6c0-85c2-463e-9885-31e0698f5d77', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '2026-04-15', '2026-04-18', 'SLV-202604-000003', 'INV-15-04-0003', 'READY', 'PENDING', 175000.00, 0.00, 0.00, 175000.00, 0.00, NULL, 175000.00, '1. kids\n2. lv', '2026-04-15 07:03:58', '2026-05-02 08:04:38', 3, 'NONE', 0.00),
('019d900b-6642-73ba-95be-d5c525edcbc3', '9617c6c0-85c2-463e-9885-31e0698f5d77', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '2026-04-15', NULL, 'SLV-202604-000004', 'INV-15-04-0004', 'READY', 'PENDING', 220000.00, 0.00, 0.00, 220000.00, 0.00, NULL, 220000.00, '1. conv\n2. nb\n3. adven', '2026-04-15 07:29:19', '2026-05-02 08:03:16', 3, 'NONE', 0.00),
('019d900d-25ce-71ca-9b71-466a0ef5810d', '9617c6c0-85c2-463e-9885-31e0698f5d77', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '2026-04-15', '2026-04-18', 'SLV-202604-000005', 'INV-15-04-0005', 'DRYING', 'PENDING', 125000.00, 31250.00, 0.00, 93750.00, 0.00, NULL, 93750.00, '1. adven', '2026-04-15 07:31:13', '2026-05-02 10:56:55', 3, 'NONE', 0.00),
('019d9014-a0ff-71fc-9377-66e3f7b2803e', '9617c6c0-85c2-463e-9885-31e0698f5d77', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '2026-04-15', '2026-04-18', 'SLV-202604-000006', 'INV-15-04-0006', 'WASHING', 'PAID', 250000.00, 0.00, 0.00, 250000.00, 250000.00, '2026-04-15 21:16:00', 0.00, '1. advenn', '2026-04-15 07:39:24', '2026-05-02 10:56:28', 3, 'NONE', 0.00),
('019d901c-8823-73a1-a365-e702f1f851b5', '9617c6c0-85c2-463e-9885-31e0698f5d77', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '2026-04-15', '2026-04-22', 'SLV-202604-000007', 'INV-15-04-0007', 'IRONING', 'PAID', 625000.00, 0.00, 0.00, 625000.00, 625000.00, '2026-04-15 17:08:00', 0.00, '1. nyoba foto', '2026-04-15 07:48:02', '2026-04-16 08:11:47', 3, 'NONE', 0.00),
('019d996c-6759-72a6-80f5-b7a96613fba9', '9617c6c0-85c2-463e-9885-31e0698f5d77', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '2026-04-17', '2026-04-21', 'SLV-202604-000009', 'INV-17-04-0009', 'DELIVERING', 'PAID', 100000.00, 0.00, 0.00, 100000.00, 100000.00, '2026-04-17 03:11:00', 0.00, '1. nb 530\n2. converse', '2026-04-17 03:11:51', '2026-04-17 03:44:31', 3, 'NONE', 0.00),
('019da08f-3673-72f9-8b78-6a30e1f19204', '9617c6c0-85c2-463e-9885-31e0698f5d77', '3730104d-ef9b-41cd-b89b-94061d32b82b', '2026-04-18', '2026-04-21', 'SLV-202604-000010', 'INV-18-04-0010', 'WASHING', 'PENDING', 50000.00, 0.00, 0.00, 50000.00, 0.00, NULL, 50000.00, '1. nyoba piutang', '2026-04-18 12:27:13', '2026-05-02 07:41:08', 3, 'NONE', 0.00),
('019da09e-0edb-737a-a86e-9cfccde6da76', '9617c6c0-85c2-463e-9885-31e0698f5d77', '3730104d-ef9b-41cd-b89b-94061d32b82b', '2026-04-18', '2026-04-23', 'SLV-202604-000011', 'INV-18-04-0011', 'WASHING', 'PENDING', 125000.00, 0.00, 0.00, 125000.00, 0.00, NULL, 125000.00, '1. converse', '2026-04-18 12:43:26', '2026-05-02 07:41:10', 3, 'NONE', 0.00),
('019da0a3-df16-71b5-a937-8c5e250df40f', '9617c6c0-85c2-463e-9885-31e0698f5d77', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '2026-04-18', '2026-04-22', 'SLV-202604-000012', 'INV-18-04-0012', 'IRONING', 'PENDING', 45000.00, 0.00, 0.00, 45000.00, 0.00, NULL, 45000.00, '1. nyoba', '2026-04-18 12:49:47', '2026-05-02 08:38:19', 3, 'NONE', 0.00),
('019da0a8-9036-70dd-92c0-ee053a9c5967', '9617c6c0-85c2-463e-9885-31e0698f5d77', '3730104d-ef9b-41cd-b89b-94061d32b82b', '2026-04-18', '2026-04-24', 'SLV-202604-000013', 'INV-18-04-0013', 'IRONING', 'PAID', 125000.00, 31250.00, 0.00, 93750.00, 93750.00, '2026-05-25 09:20:00', 0.00, '1. nyoba2', '2026-04-18 12:54:54', '2026-05-25 09:20:07', 3, 'DISC25', 31250.00),
('019da64e-d490-73d8-b46c-2b266b758872', '9617c6c0-85c2-463e-9885-31e0698f5d77', '3730104d-ef9b-41cd-b89b-94061d32b82b', '2026-04-19', '2026-04-23', 'SLV-202604-000014', 'INV-19-04-0014', 'DRYING', 'PAID', 45000.00, 0.00, 0.00, 45000.00, 45000.00, '2026-05-25 08:56:00', 0.00, '1. nyoba wa', '2026-04-19 15:14:37', '2026-05-25 08:56:44', 2, 'NONE', 0.00),
('019dae3d-7967-7289-a506-daa31a50a6f6', '9617c6c0-85c2-463e-9885-31e0698f5d77', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '2026-04-21', '2026-04-23', 'SLV-202604-000015', 'INV-21-04-0015', 'DRYING', 'PENDING', 50000.00, 50000.00, 0.00, 0.00, 0.00, NULL, 0.00, '1. nyoba wa', '2026-04-21 04:12:37', '2026-05-02 10:54:50', 3, 'FREE100', 50000.00),
('019db999-fa8e-71d9-803e-88989fe0cf90', '9617c6c0-85c2-463e-9885-31e0698f5d77', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '2026-04-24', '2026-04-27', 'SLV-202604-000016', 'INV-23-04-0016', 'DRYING', 'PENDING', 125000.00, 0.00, 0.00, 125000.00, 0.00, NULL, 125000.00, '1. nyoba wa 2', '2026-04-23 09:09:29', '2026-05-08 05:50:05', 3, 'NONE', 0.00),
('019db9c0-4045-705b-a279-9c6596324665', '9617c6c0-85c2-463e-9885-31e0698f5d77', '3730104d-ef9b-41cd-b89b-94061d32b82b', '2026-04-24', '2026-04-25', 'SLV-202604-000017', 'INV-23-04-0017', 'DRYING', 'PENDING', 50000.00, 0.00, 0.00, 50000.00, 0.00, NULL, 50000.00, '1. nyoba pesanan', '2026-04-23 09:51:17', '2026-05-02 10:54:53', 3, 'NONE', 0.00),
('019dc309-b997-7022-aaa1-0acf2093144d', '9617c6c0-85c2-463e-9885-31e0698f5d77', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '2026-04-25', '2026-04-29', 'SLV-202604-000018', 'INV-25-04-0018', 'WASHING', 'PENDING', 45000.00, 0.00, 0.00, 45000.00, 0.00, NULL, 45000.00, '1. nobaar', '2026-04-25 05:08:07', '2026-05-02 11:01:31', 3, 'NONE', 0.00),
('019de858-3a3b-73ae-8734-5516b49decd3', '9617c6c0-85c2-463e-9885-31e0698f5d77', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '2026-05-02', '2026-05-07', 'SLV-202605-000002', 'INV-02-05-0002', 'WASHING', 'PENDING', 45000.00, 0.00, 0.00, 45000.00, 0.00, NULL, 45000.00, '1. coba cuci', '2026-05-02 10:59:49', '2026-05-02 11:00:37', 3, 'NONE', 0.00),
('019de858-9545-73a5-bb75-c60f555a0f4e', '9617c6c0-85c2-463e-9885-31e0698f5d77', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '2026-05-02', '2026-05-06', 'SLV-202605-000003', 'INV-02-05-0003', 'QUEUE', 'PAID', 125000.00, 125000.00, 0.00, 0.00, 0.00, NULL, 0.00, '1. coba cuci', '2026-05-02 11:00:12', '2026-05-10 08:08:00', 3, 'FREE100', 125000.00),
('019e5a2a-1cbe-714b-b7fb-befaa585aa3c', '9617c6c0-85c2-463e-9885-31e0698f5d77', '3730104d-ef9b-41cd-b89b-94061d32b82b', '2026-05-24', '2026-05-24', 'SLV-202605-000004', 'INV-24-05-0004', 'READY', 'PENDING', 45000.00, 0.00, 0.00, 45000.00, 0.00, NULL, 45000.00, '1. nyoba notif', '2026-05-24 13:26:09', '2026-05-24 13:26:45', 3, 'NONE', 0.00),
('019e5a31-64c9-7261-b20c-32e2180a69b0', '9617c6c0-85c2-463e-9885-31e0698f5d77', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '2026-05-24', '2026-05-24', 'SLV-202605-000005', 'INV-24-05-0005', 'QUEUE', 'PENDING', 50000.00, 0.00, 0.00, 50000.00, 0.00, NULL, 50000.00, '1. oneday', '2026-05-24 13:34:07', '2026-05-24 13:34:07', 3, 'NONE', 0.00),
('019e5e58-f51a-7108-b3d7-c66897a8ebea', '9617c6c0-85c2-463e-9885-31e0698f5d77', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '2026-05-25', '2026-05-25', 'SLV-202605-000006', 'INV-25-05-0006', 'QUEUE', 'PAID', 45000.00, 0.00, 0.00, 45000.00, 45000.00, '2026-05-25 08:58:00', 0.00, '1. nyoba akuntansi', '2026-05-25 08:55:48', '2026-05-25 08:58:03', 2, 'NONE', 0.00),
('019e5e5d-6b84-70db-a52f-4036ce523327', '9617c6c0-85c2-463e-9885-31e0698f5d77', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '2026-05-25', '2026-05-25', 'SLV-202605-000007', 'INV-25-05-0007', 'QUEUE', 'PAID', 50000.00, 0.00, 0.00, 50000.00, 50000.00, '2026-05-25 09:00:00', 0.00, '1. akuntansi 2', '2026-05-25 09:00:41', '2026-05-25 09:00:41', 2, 'NONE', 0.00),
('019e5e67-1025-719f-9a34-5d652f6e2430', '9617c6c0-85c2-463e-9885-31e0698f5d77', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '2026-05-25', '2026-05-25', 'SLV-202605-000008', 'INV-25-05-0008', 'QUEUE', 'PAID', 125000.00, 0.00, 0.00, 125000.00, 125000.00, '2026-05-25 09:11:00', 0.00, '1. akuntansi 3', '2026-05-25 09:11:13', '2026-05-25 09:11:13', 2, 'NONE', 0.00),
('019e5e67-d337-7279-b33c-ebd731ae61b6', '9617c6c0-85c2-463e-9885-31e0698f5d77', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '2026-05-25', '2026-05-25', 'SLV-202605-000009', 'INV-25-05-0009', 'QUEUE', 'DP', 125000.00, 31250.00, 20000.00, 93750.00, 20000.00, NULL, 73750.00, '1. akuntansi 4', '2026-05-25 09:12:03', '2026-05-25 09:12:03', 2, 'DISC25', 31250.00),
('019e5e68-a273-70b0-951e-1f66f1c65534', '9617c6c0-85c2-463e-9885-31e0698f5d77', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '2026-05-25', '2026-05-25', 'SLV-202605-000010', 'INV-25-05-0010', 'QUEUE', 'PAID', 50000.00, 0.00, 0.00, 50000.00, 50000.00, '2026-05-25 09:12:00', 0.00, '1. akuntansi 5', '2026-05-25 09:12:56', '2026-05-25 09:12:56', 2, 'NONE', 0.00),
('019e5e6c-688b-72bc-98a5-6986f4c72c93', '9617c6c0-85c2-463e-9885-31e0698f5d77', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '2026-05-25', '2026-05-25', 'SLV-202605-000011', 'INV-25-05-0011', 'QUEUE', 'PAID', 45000.00, 0.00, 20000.00, 45000.00, 45000.00, '2026-05-25 09:17:00', 0.00, '1. dp akuntansi', '2026-05-25 09:17:03', '2026-05-25 09:17:44', 2, 'NONE', 0.00),
('019e5e76-6529-73fa-9605-511e5352ba8e', '9617c6c0-85c2-463e-9885-31e0698f5d77', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '2026-05-25', '2026-05-25', 'SLV-202605-000012', 'INV-25-05-0012', 'QUEUE', 'PAID', 125000.00, 31250.00, 25000.00, 93750.00, 93750.00, '2026-05-25 09:28:00', 0.00, '1. nyoba2', '2026-05-25 09:27:57', '2026-05-25 09:28:52', 2, 'DISC25', 31250.00),
('019e5ef4-d62e-718c-8352-f7f9be855513', '9617c6c0-85c2-463e-9885-31e0698f5d77', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '2026-05-25', '2026-05-26', 'SLV-202605-000013', 'INV-25-05-0013', 'QUEUE', 'PENDING', 125000.00, 0.00, 0.00, 125000.00, 0.00, NULL, 125000.00, '1. tes wa', '2026-05-25 11:46:04', '2026-05-25 11:46:04', 2, 'NONE', 0.00),
('019e5f2b-1964-7061-83ce-29cbca5fc54f', '9617c6c0-85c2-463e-9885-31e0698f5d77', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '2026-05-25', '2026-05-26', 'SLV-202605-000014', 'INV-25-05-0014', 'QUEUE', 'PENDING', 100000.00, 10000.00, 0.00, 90000.00, 0.00, NULL, 90000.00, '1. nyoba wa', '2026-05-25 12:45:20', '2026-07-08 13:38:35', 2, 'NONE', 0.00),
('019e5f40-dd37-7221-bb02-d0929965bee6', '9617c6c0-85c2-463e-9885-31e0698f5d77', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '2026-05-25', '2026-05-26', 'SLV-202605-000015', 'INV-25-05-0017', 'QUEUE', 'PENDING', 125000.00, 25000.00, 0.00, 100000.00, 0.00, NULL, 100000.00, '1. last nyoba', '2026-05-25 13:09:06', '2026-07-08 13:31:55', 2, 'NONE', 0.00),
('019f41da-de5e-7092-af35-39222437f538', '9617c6c0-85c2-463e-9885-31e0698f5d77', '7e470177-a56f-4309-8bd3-48be37a4c5b9', '2026-07-08', '2026-07-10', 'SLV-202607-000001', 'INV-08-07-0001', 'QUEUE', 'PAID', 125000.00, 145000.00, 0.00, 0.00, 110000.00, '2026-07-08 13:11:00', 0.00, '1. nyoba diskon', '2026-07-08 13:11:30', '2026-07-08 13:23:33', 3, 'FREE100', 125000.00);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` char(36) NOT NULL,
  `order_id` char(36) NOT NULL,
  `service_id` char(36) NOT NULL,
  `qty` decimal(10,2) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `note` varchar(200) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `service_id`, `qty`, `price`, `total`, `note`, `created_at`, `updated_at`) VALUES
('019d8f4d-26af-71e4-8b61-638ced8055ce', '019d8f4d-2661-7388-9e93-6f3b0ada9fa9', 'c6e6746b-2833-4e78-ac5a-928973788576', 1.00, 50000.00, 50000.00, NULL, '2026-04-15 04:01:31', '2026-04-15 04:01:31'),
('019d8f5a-544b-73af-8f6f-ff124ec6641c', '019d8f5a-5443-73d7-a0a0-1aa85285a363', 'c6e6746b-2833-4e78-ac5a-928973788576', 2.00, 50000.00, 100000.00, NULL, '2026-04-15 04:15:54', '2026-04-15 04:15:54'),
('019d8ff4-326b-736e-9815-64bc9f8d6bc7', '019d8ff4-316b-70ec-932d-bd6dadb26769', 'c6e6746b-2833-4e78-ac5a-928973788576', 1.00, 50000.00, 50000.00, NULL, '2026-04-15 07:03:58', '2026-04-15 07:03:58'),
('019d8ff4-32a7-705b-bc3a-5ef02d5b884e', '019d8ff4-316b-70ec-932d-bd6dadb26769', '57c68862-c326-4352-af54-c19496b74493', 1.00, 125000.00, 125000.00, NULL, '2026-04-15 07:03:58', '2026-04-15 07:03:58'),
('019d900b-66bc-707c-8d9a-5de2a71e8279', '019d900b-6642-73ba-95be-d5c525edcbc3', '57c68862-c326-4352-af54-c19496b74493', 1.00, 125000.00, 125000.00, NULL, '2026-04-15 07:29:19', '2026-04-15 07:29:19'),
('019d900b-66be-7053-924b-998d0cc4af87', '019d900b-6642-73ba-95be-d5c525edcbc3', 'af86c4c7-2bb3-4c57-88d2-517a56312f64', 1.00, 45000.00, 45000.00, NULL, '2026-04-15 07:29:19', '2026-04-15 07:29:19'),
('019d900b-66c8-7002-a130-e163994b1c9b', '019d900b-6642-73ba-95be-d5c525edcbc3', 'c6e6746b-2833-4e78-ac5a-928973788576', 1.00, 50000.00, 50000.00, NULL, '2026-04-15 07:29:19', '2026-04-15 07:29:19'),
('019d9014-a16d-725d-9661-15fb00c22c7a', '019d9014-a0ff-71fc-9377-66e3f7b2803e', 'c6e6746b-2833-4e78-ac5a-928973788576', 5.00, 50000.00, 250000.00, NULL, '2026-04-15 07:39:24', '2026-04-15 07:39:24'),
('019d901c-888b-7378-8a78-baa070b3bc09', '019d901c-8823-73a1-a365-e702f1f851b5', '57c68862-c326-4352-af54-c19496b74493', 5.00, 125000.00, 625000.00, NULL, '2026-04-15 07:48:02', '2026-04-15 07:48:02'),
('019d957d-7f98-7228-9ea8-6aa2b67fc140', '019d900d-25ce-71ca-9b71-466a0ef5810d', '57c68862-c326-4352-af54-c19496b74493', 1.00, 125000.00, 125000.00, NULL, '2026-04-16 08:52:03', '2026-04-16 08:52:03'),
('019d996c-676e-72c0-8f5e-0902468c3d30', '019d996c-6759-72a6-80f5-b7a96613fba9', 'c6e6746b-2833-4e78-ac5a-928973788576', 2.00, 50000.00, 100000.00, NULL, '2026-04-17 03:11:51', '2026-04-17 03:11:51'),
('019da08f-36b4-7296-96b6-2c7438e8116f', '019da08f-3673-72f9-8b78-6a30e1f19204', 'c6e6746b-2833-4e78-ac5a-928973788576', 1.00, 50000.00, 50000.00, NULL, '2026-04-18 12:27:13', '2026-04-18 12:27:13'),
('019da09e-0f06-7367-b842-b79df718a1e5', '019da09e-0edb-737a-a86e-9cfccde6da76', '57c68862-c326-4352-af54-c19496b74493', 1.00, 125000.00, 125000.00, NULL, '2026-04-18 12:43:26', '2026-04-18 12:43:26'),
('019da0a3-df44-73f7-9941-0192bd70b93b', '019da0a3-df16-71b5-a937-8c5e250df40f', 'af86c4c7-2bb3-4c57-88d2-517a56312f64', 1.00, 45000.00, 45000.00, NULL, '2026-04-18 12:49:47', '2026-04-18 12:49:47'),
('019da0a8-903b-715c-8cda-291adeb104b4', '019da0a8-9036-70dd-92c0-ee053a9c5967', '57c68862-c326-4352-af54-c19496b74493', 1.00, 125000.00, 125000.00, NULL, '2026-04-18 12:54:54', '2026-04-18 12:54:54'),
('019da64e-d4c5-71ee-b320-afbb6c0ea981', '019da64e-d490-73d8-b46c-2b266b758872', 'af86c4c7-2bb3-4c57-88d2-517a56312f64', 1.00, 45000.00, 45000.00, NULL, '2026-04-19 15:14:37', '2026-04-19 15:14:37'),
('019dae3d-79bf-710e-8bce-74b07056de02', '019dae3d-7967-7289-a506-daa31a50a6f6', 'c6e6746b-2833-4e78-ac5a-928973788576', 1.00, 50000.00, 50000.00, NULL, '2026-04-21 04:12:37', '2026-04-21 04:12:37'),
('019dbfbf-170a-7395-bd48-29a080db8b65', '019db9c0-4045-705b-a279-9c6596324665', 'c6e6746b-2833-4e78-ac5a-928973788576', 1.00, 50000.00, 50000.00, NULL, '2026-04-24 13:47:44', '2026-04-24 13:47:44'),
('019dbfc4-8b8e-733c-bc6d-bdfac1732445', '019db999-fa8e-71d9-803e-88989fe0cf90', '57c68862-c326-4352-af54-c19496b74493', 1.00, 125000.00, 125000.00, NULL, '2026-04-24 13:53:42', '2026-04-24 13:53:42'),
('019dc309-b99f-73e1-a5fb-52a32b2f02ea', '019dc309-b997-7022-aaa1-0acf2093144d', 'af86c4c7-2bb3-4c57-88d2-517a56312f64', 1.00, 45000.00, 45000.00, NULL, '2026-04-25 05:08:07', '2026-04-25 05:08:07'),
('019de858-3a40-719a-a214-a2a468518815', '019de858-3a3b-73ae-8734-5516b49decd3', 'af86c4c7-2bb3-4c57-88d2-517a56312f64', 1.00, 45000.00, 45000.00, NULL, '2026-05-02 10:59:49', '2026-05-02 10:59:49'),
('019de858-9564-7297-aad2-db75a35af7d7', '019de858-9545-73a5-bb75-c60f555a0f4e', '57c68862-c326-4352-af54-c19496b74493', 1.00, 125000.00, 125000.00, NULL, '2026-05-02 11:00:12', '2026-05-02 11:00:12'),
('019e5a2a-1cc6-70b2-b825-2711840e4ce1', '019e5a2a-1cbe-714b-b7fb-befaa585aa3c', 'af86c4c7-2bb3-4c57-88d2-517a56312f64', 1.00, 45000.00, 45000.00, NULL, '2026-05-24 13:26:09', '2026-05-24 13:26:09'),
('019e5a31-64da-7276-ac47-30ed361c1aad', '019e5a31-64c9-7261-b20c-32e2180a69b0', 'c6e6746b-2833-4e78-ac5a-928973788576', 1.00, 50000.00, 50000.00, NULL, '2026-05-24 13:34:07', '2026-05-24 13:34:07'),
('019e5e58-f521-7384-9d6b-d90cc926928e', '019e5e58-f51a-7108-b3d7-c66897a8ebea', 'af86c4c7-2bb3-4c57-88d2-517a56312f64', 1.00, 45000.00, 45000.00, NULL, '2026-05-25 08:55:48', '2026-05-25 08:55:48'),
('019e5e5d-6b8b-71dc-8671-891122e7fd9a', '019e5e5d-6b84-70db-a52f-4036ce523327', 'c6e6746b-2833-4e78-ac5a-928973788576', 1.00, 50000.00, 50000.00, NULL, '2026-05-25 09:00:41', '2026-05-25 09:00:41'),
('019e5e67-103a-724c-8593-9ce69036c92f', '019e5e67-1025-719f-9a34-5d652f6e2430', '57c68862-c326-4352-af54-c19496b74493', 1.00, 125000.00, 125000.00, NULL, '2026-05-25 09:11:13', '2026-05-25 09:11:13'),
('019e5e67-d33d-7395-b90e-640804763b39', '019e5e67-d337-7279-b33c-ebd731ae61b6', '57c68862-c326-4352-af54-c19496b74493', 1.00, 125000.00, 125000.00, NULL, '2026-05-25 09:12:03', '2026-05-25 09:12:03'),
('019e5e68-a277-7391-acc6-deb2de36a12d', '019e5e68-a273-70b0-951e-1f66f1c65534', 'c6e6746b-2833-4e78-ac5a-928973788576', 1.00, 50000.00, 50000.00, NULL, '2026-05-25 09:12:56', '2026-05-25 09:12:56'),
('019e5e6c-689f-7226-bd7e-cdbf63a0af36', '019e5e6c-688b-72bc-98a5-6986f4c72c93', 'af86c4c7-2bb3-4c57-88d2-517a56312f64', 1.00, 45000.00, 45000.00, NULL, '2026-05-25 09:17:03', '2026-05-25 09:17:03'),
('019e5e76-6530-720e-90da-d335e947a034', '019e5e76-6529-73fa-9605-511e5352ba8e', '57c68862-c326-4352-af54-c19496b74493', 1.00, 125000.00, 125000.00, NULL, '2026-05-25 09:27:58', '2026-05-25 09:27:58'),
('019e5ef4-d636-733d-89f5-2bc77bcf9b67', '019e5ef4-d62e-718c-8352-f7f9be855513', '57c68862-c326-4352-af54-c19496b74493', 1.00, 125000.00, 125000.00, NULL, '2026-05-25 11:46:04', '2026-05-25 11:46:04'),
('019f41e5-e704-71d7-9936-51aa1b127197', '019f41da-de5e-7092-af35-39222437f538', '57c68862-c326-4352-af54-c19496b74493', 1.00, 125000.00, 125000.00, NULL, '2026-07-08 13:23:33', '2026-07-08 13:23:33'),
('019f41ed-8f6a-7180-88bd-f48877dedba2', '019e5f40-dd37-7221-bb02-d0929965bee6', '57c68862-c326-4352-af54-c19496b74493', 1.00, 125000.00, 125000.00, NULL, '2026-07-08 13:31:55', '2026-07-08 13:31:55'),
('019f41f3-aa9a-7352-a065-379ee638122c', '019e5f2b-1964-7061-83ce-29cbca5fc54f', 'c6e6746b-2833-4e78-ac5a-928973788576', 2.00, 50000.00, 100000.00, NULL, '2026-07-08 13:38:35', '2026-07-08 13:38:35');

-- --------------------------------------------------------

--
-- Table structure for table `order_photos`
--

CREATE TABLE `order_photos` (
  `id` char(36) NOT NULL,
  `order_id` char(36) NOT NULL,
  `kind` enum('before','after') NOT NULL,
  `path` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_photos`
--

INSERT INTO `order_photos` (`id`, `order_id`, `kind`, `path`, `created_at`, `updated_at`) VALUES
('019d9017-0d51-72f6-9197-142b2aa64469', '019d9014-a0ff-71fc-9377-66e3f7b2803e', 'before', 'storage/uploads/orders/019d9014-a0ff-71fc-9377-66e3f7b2803e/before/qeLuTm8DZKV3OLTpaFgAyPL5XdUZvSK1syQmMVio.png', '2026-04-15 07:42:03', '2026-04-15 07:42:03'),
('019d901c-8cfb-7085-a6c7-bd5c4a59f0e3', '019d901c-8823-73a1-a365-e702f1f851b5', 'before', 'storage/uploads/orders/019d901c-8823-73a1-a365-e702f1f851b5/before/iyh8prOZvKufRUQ4iVm3dJH70E4dziEj1HrZTSWj.png', '2026-04-15 07:48:03', '2026-04-15 07:48:03'),
('019d996c-70d1-73ba-a074-049c4904f08e', '019d996c-6759-72a6-80f5-b7a96613fba9', 'before', 'storage/uploads/orders/019d996c-6759-72a6-80f5-b7a96613fba9/before/1KdUXTKA9sschPs8oBv5VTZ6j5qXxPNSaK9JrX5y.png', '2026-04-17 03:11:54', '2026-04-17 03:11:54'),
('019da052-9b3a-71cd-8ed4-8a3e682f1b12', '019d900d-25ce-71ca-9b71-466a0ef5810d', 'before', 'storage/uploads/orders/019d900d-25ce-71ca-9b71-466a0ef5810d/before/a1ou0RZSHXXhNmjHE2BP51cLkxUe3GHZFl2zjHKv.png', '2026-04-18 11:21:01', '2026-04-18 11:21:01'),
('019dae3d-83b1-70cd-a630-0a2ae66341d9', '019dae3d-7967-7289-a506-daa31a50a6f6', 'before', 'storage/uploads/orders/019dae3d-7967-7289-a506-daa31a50a6f6/before/a00eBCM2vl4lQ9KLm8ohqRtl31Y6pTfLEks9U1LJ.png', '2026-04-21 04:12:40', '2026-04-21 04:12:40'),
('019db999-fd8a-729b-838a-1a1e8b48ec2e', '019db999-fa8e-71d9-803e-88989fe0cf90', 'before', 'storage/uploads/orders/019db999-fa8e-71d9-803e-88989fe0cf90/before/ysLQumu4UlA8PKdKAKn3cBZnnkEmpNfTFZfQJyGQ.png', '2026-04-23 09:09:30', '2026-04-23 09:09:30'),
('019db9c0-4370-7048-b289-91efa11c7c2b', '019db9c0-4045-705b-a279-9c6596324665', 'before', 'storage/uploads/orders/019db9c0-4045-705b-a279-9c6596324665/before/iR7ejgFQeKdMQ4307nNKDRAQA11CWimJEn1B6x6R.png', '2026-04-23 09:51:18', '2026-04-23 09:51:18'),
('019dc309-bce6-72a5-925d-ff74ae9352f7', '019dc309-b997-7022-aaa1-0acf2093144d', 'before', 'storage/uploads/orders/019dc309-b997-7022-aaa1-0acf2093144d/before/xDo0eCBpzmw9HZQHGq6jHmFSrUQ705ZgeJpQv07N.png', '2026-04-25 05:08:08', '2026-04-25 05:08:08'),
('019de858-986e-7145-a4fb-dbb2c063fb7e', '019de858-9545-73a5-bb75-c60f555a0f4e', 'before', 'storage/uploads/orders/019de858-9545-73a5-bb75-c60f555a0f4e/before/k3WNqHUw3Owp7VLsAdR7gEid0F7LaLppets8QZXs.png', '2026-05-02 11:00:13', '2026-05-02 11:00:13'),
('019dfc2c-fd30-72e1-9305-57b6171a8c01', '019de858-9545-73a5-bb75-c60f555a0f4e', 'before', 'storage/uploads/orders/019de858-9545-73a5-bb75-c60f555a0f4e/before/mzxauiajuIKCxdwDjJa6ol60303TOUANxPL5c1CD.png', '2026-05-06 07:25:00', '2026-05-06 07:25:00'),
('019dfc2d-59ff-70cd-82c8-5cf2e742133f', '019de858-9545-73a5-bb75-c60f555a0f4e', 'before', 'storage/uploads/orders/019de858-9545-73a5-bb75-c60f555a0f4e/before/MMBvefAMQt47PKb7Gkxf1wWVvGyNRKBRFGg0KlNd.png', '2026-05-06 07:25:23', '2026-05-06 07:25:23'),
('019dfc34-1145-7150-a67c-a54192ab4951', '019de858-3a3b-73ae-8734-5516b49decd3', 'before', 'storage/uploads/orders/019de858-3a3b-73ae-8734-5516b49decd3/before/0CfPFBIShtcLPaQS0nKvrnAsdEn7SlYJmVPccE51.png', '2026-05-06 07:32:43', '2026-05-06 07:32:43'),
('019e5a2a-1f99-70c7-a9ac-e042d2ae394a', '019e5a2a-1cbe-714b-b7fb-befaa585aa3c', 'before', 'storage/uploads/orders/019e5a2a-1cbe-714b-b7fb-befaa585aa3c/before/SbFNI9PGjZ0ejzNByYxCCrmkV9sRSFsP4hHyyv3U.png', '2026-05-24 13:26:10', '2026-05-24 13:26:10'),
('019e5a31-66e9-73bf-9b9e-e8cc0aac75e5', '019e5a31-64c9-7261-b20c-32e2180a69b0', 'before', 'storage/uploads/orders/019e5a31-64c9-7261-b20c-32e2180a69b0/before/N8bGxWNnJAhcLQlUSKf02WCQ2GGws9QGIlZzCFER.png', '2026-05-24 13:34:07', '2026-05-24 13:34:07'),
('019e5e58-f849-73a4-a779-117287290f17', '019e5e58-f51a-7108-b3d7-c66897a8ebea', 'before', 'storage/uploads/orders/019e5e58-f51a-7108-b3d7-c66897a8ebea/before/jLI5MhbOLzmDwgTUbP4zpSQMmp1kHCnh98HOP4sz.png', '2026-05-25 08:55:49', '2026-05-25 08:55:49'),
('019e5e5d-7062-73ab-b9e4-915d3331fe33', '019e5e5d-6b84-70db-a52f-4036ce523327', 'before', 'storage/uploads/orders/019e5e5d-6b84-70db-a52f-4036ce523327/before/0oxO2keePHNyOF2vTsqKqp1ABVG2tEapvOeQhfaV.png', '2026-05-25 09:00:42', '2026-05-25 09:00:42'),
('019e5e67-166f-709c-8d92-e2afb84ab8e1', '019e5e67-1025-719f-9a34-5d652f6e2430', 'before', 'storage/uploads/orders/019e5e67-1025-719f-9a34-5d652f6e2430/before/Nlw1UAL59boBWVCWyqA2TAn7rDMbbuSrNlWJFFDT.png', '2026-05-25 09:11:14', '2026-05-25 09:11:14'),
('019e5e67-d787-729b-9779-1176f8d39ca6', '019e5e67-d337-7279-b33c-ebd731ae61b6', 'before', 'storage/uploads/orders/019e5e67-d337-7279-b33c-ebd731ae61b6/before/7XFyblf10b8VjN68VinHQmiocJvtAIwflr4mgFjN.png', '2026-05-25 09:12:04', '2026-05-25 09:12:04'),
('019e5e68-a6e5-7382-8fcd-f288fdea010a', '019e5e68-a273-70b0-951e-1f66f1c65534', 'before', 'storage/uploads/orders/019e5e68-a273-70b0-951e-1f66f1c65534/before/PTdSEc2Ocz1Jk3s5vJ374yIE1fNIsUJlvxE1o692.png', '2026-05-25 09:12:57', '2026-05-25 09:12:57'),
('019e5e6c-6d02-7314-ab17-bae63f12f85c', '019e5e6c-688b-72bc-98a5-6986f4c72c93', 'before', 'storage/uploads/orders/019e5e6c-688b-72bc-98a5-6986f4c72c93/before/RsUXdfK1lwx3MCu8kQW9WnopbqZp6wJPseJiFnRV.png', '2026-05-25 09:17:04', '2026-05-25 09:17:04'),
('019e5e76-69bf-72c5-87ec-2730e0b20d84', '019e5e76-6529-73fa-9605-511e5352ba8e', 'before', 'storage/uploads/orders/019e5e76-6529-73fa-9605-511e5352ba8e/before/K3ZtB0RbyTkYK2ImYVipcRrkkOKK0OSw2YtDAMqq.png', '2026-05-25 09:27:59', '2026-05-25 09:27:59'),
('019e5ef4-d879-7275-ba0b-f42dee0c02e8', '019e5ef4-d62e-718c-8352-f7f9be855513', 'before', 'storage/uploads/orders/019e5ef4-d62e-718c-8352-f7f9be855513/before/RsH5Hpik7JHW1b9rH529L9ID9irWpLUrLzCJe1vF.png', '2026-05-25 11:46:05', '2026-05-25 11:46:05'),
('019e5f2b-1b94-723d-b2a4-2ba2df009f5d', '019e5f2b-1964-7061-83ce-29cbca5fc54f', 'before', 'storage/uploads/orders/019e5f2b-1964-7061-83ce-29cbca5fc54f/before/JEEzVtpG5wYEX2zMykRThwfQ0BclNqMxdnqnRzr1.png', '2026-05-25 12:45:21', '2026-05-25 12:45:21'),
('019e5f40-df74-70a5-919a-1918c2d56205', '019e5f40-dd37-7221-bb02-d0929965bee6', 'before', 'storage/uploads/orders/019e5f40-dd37-7221-bb02-d0929965bee6/before/4QjQlyMJpF2JHSWztgIF9yTKiG8TLJwsO4N7SyHT.png', '2026-05-25 13:09:07', '2026-05-25 13:09:07'),
('019f41da-e4d1-7320-a9cd-39aca43b2c8c', '019f41da-de5e-7092-af35-39222437f538', 'before', 'storage/uploads/orders/019f41da-de5e-7092-af35-39222437f538/before/x3Qt0c1MvycM2ehhMMYOufrXuJDc5sPXYorv8cjR.png', '2026-07-08 13:11:32', '2026-07-08 13:11:32');

-- --------------------------------------------------------

--
-- Table structure for table `order_vouchers`
--

CREATE TABLE `order_vouchers` (
  `id` char(36) NOT NULL,
  `order_id` char(36) NOT NULL,
  `voucher_id` char(36) NOT NULL,
  `applied_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `applied_by` bigint(20) UNSIGNED DEFAULT NULL,
  `applied_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` char(36) NOT NULL,
  `order_id` char(36) NOT NULL,
  `method` enum('PENDING','DP','CASH','QRIS','TRANSFER') NOT NULL DEFAULT 'PENDING',
  `amount` decimal(12,2) NOT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `note` varchar(200) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `method`, `amount`, `paid_at`, `note`, `created_at`, `updated_at`) VALUES
('019d9601-86b5-73f5-9df8-0689561b59e1', '019d9014-a0ff-71fc-9377-66e3f7b2803e', 'CASH', 150000.00, '2026-04-15 21:16:00', NULL, '2026-04-16 11:16:15', '2026-04-16 11:16:15'),
('019d996c-6b66-7334-989e-f5f3a8ff11d0', '019d996c-6759-72a6-80f5-b7a96613fba9', 'CASH', 100000.00, '2026-04-17 03:11:00', NULL, '2026-04-17 03:11:52', '2026-04-17 03:11:52'),
('019e5e59-ce64-71e8-a6be-e39f58cae540', '019da64e-d490-73d8-b46c-2b266b758872', 'CASH', 45000.00, '2026-05-25 08:56:00', NULL, '2026-05-25 08:56:44', '2026-05-25 08:56:44'),
('019e5e5b-0258-73ff-8970-8f5d24dfb397', '019e5e58-f51a-7108-b3d7-c66897a8ebea', 'CASH', 45000.00, '2026-05-25 08:58:00', NULL, '2026-05-25 08:58:03', '2026-05-25 08:58:03'),
('019e5e5d-6d9b-73c4-abf6-cd746dda9c91', '019e5e5d-6b84-70db-a52f-4036ce523327', 'CASH', 50000.00, '2026-05-25 09:00:00', NULL, '2026-05-25 09:00:41', '2026-05-25 09:00:41'),
('019e5e67-1267-738c-a6ca-21044ee70e36', '019e5e67-1025-719f-9a34-5d652f6e2430', 'CASH', 125000.00, '2026-05-25 09:11:00', NULL, '2026-05-25 09:11:13', '2026-05-25 09:11:13'),
('019e5e67-d568-702a-9f59-c457311c8eae', '019e5e67-d337-7279-b33c-ebd731ae61b6', 'DP', 20000.00, '2026-05-25 09:12:00', NULL, '2026-05-25 09:12:03', '2026-05-25 09:12:03'),
('019e5e68-a48c-7322-8f93-4767212c3f6f', '019e5e68-a273-70b0-951e-1f66f1c65534', 'QRIS', 50000.00, '2026-05-25 09:12:00', NULL, '2026-05-25 09:12:56', '2026-05-25 09:12:56'),
('019e5e6c-6ab7-70cb-925f-15609b28ab0e', '019e5e6c-688b-72bc-98a5-6986f4c72c93', 'DP', 20000.00, '2026-05-25 09:17:00', NULL, '2026-05-25 09:17:04', '2026-05-25 09:17:04'),
('019e5e6d-08e3-7210-b3a2-78cc4fbb69fe', '019e5e6c-688b-72bc-98a5-6986f4c72c93', 'CASH', 25000.00, '2026-05-25 09:17:00', NULL, '2026-05-25 09:17:44', '2026-05-25 09:17:44'),
('019e5e6f-36df-712b-805f-7d1051742cb4', '019da0a8-9036-70dd-92c0-ee053a9c5967', 'CASH', 93750.00, '2026-05-25 09:20:00', NULL, '2026-05-25 09:20:07', '2026-05-25 09:20:07'),
('019e5e76-6776-7106-adb1-f9b3a09ddc22', '019e5e76-6529-73fa-9605-511e5352ba8e', 'DP', 25000.00, '2026-05-25 09:27:00', NULL, '2026-05-25 09:27:58', '2026-05-25 09:27:58'),
('019e5e77-3979-7088-9a50-4f763fca6b75', '019e5e76-6529-73fa-9605-511e5352ba8e', 'CASH', 68750.00, '2026-05-25 09:28:00', NULL, '2026-05-25 09:28:52', '2026-05-25 09:28:52'),
('019f41da-e0d1-704d-8b63-c0234dcdef88', '019f41da-de5e-7092-af35-39222437f538', 'CASH', 110000.00, '2026-07-08 13:11:00', NULL, '2026-07-08 13:11:31', '2026-07-08 13:11:31'),
('3691aa78-f27d-4f8d-82fd-b46116abd242', '019d901c-8823-73a1-a365-e702f1f851b5', 'CASH', 625000.00, '2026-04-15 17:08:00', NULL, '2026-04-16 07:08:49', '2026-04-16 07:08:49'),
('5f15d732-820e-49ae-b9ce-53ddaf8c27af', '019d9014-a0ff-71fc-9377-66e3f7b2803e', 'CASH', 100000.00, '2026-04-15 17:09:00', NULL, '2026-04-16 07:09:26', '2026-04-16 07:09:26');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(26, 'App\\Models\\User', 1, 'auth-token', '369f7770ea99829b1dfa1f8cc42c0bb5b34020e58e08ea89c459f16a789676ed', '[\"*\"]', '2026-04-18 06:23:03', NULL, '2026-04-17 04:14:09', '2026-04-18 06:23:03'),
(54, 'App\\Models\\User', 1, 'auth-token', '7c988dd0d0cada4848945c2ee96237258957a0a9241bc3d11184a3b1306f3ace', '[\"*\"]', '2026-05-02 08:32:31', NULL, '2026-05-02 08:21:07', '2026-05-02 08:32:31'),
(82, 'App\\Models\\User', 3, 'auth-token', '557010a03f813cffcd220d0c03ad760be616a7d3bda8a519458fca4c576fd020', '[\"*\"]', '2026-05-06 07:32:44', NULL, '2026-05-06 07:26:17', '2026-05-06 07:32:44'),
(93, 'App\\Models\\User', 1, 'auth-token', '14f97cbc2767a677e1aec01f32b4e7791a5536c2a54d9af44bf4752261c41185', '[\"*\"]', '2026-05-24 13:30:21', NULL, '2026-05-24 13:25:33', '2026-05-24 13:30:21'),
(113, 'App\\Models\\User', 1, 'auth-token', '76a59826535c707f14d538e94ff82762c4f0fb20b723ad42d9c9b36f60bd2d7d', '[\"*\"]', '2026-05-26 10:38:51', NULL, '2026-05-26 10:38:50', '2026-05-26 10:38:51'),
(122, 'App\\Models\\User', 1, 'auth-token', '2deb332061bfe897bde42422bed3a2e59ad4fc7c7bba8d43add3a06476e97f16', '[\"*\"]', '2026-07-08 13:11:23', NULL, '2026-07-08 13:10:13', '2026-07-08 13:11:23'),
(126, 'App\\Models\\User', 1, 'auth-token', '5f64fe6ae211518aa0aff153e0b000b7a80fa5f00217a5e72f3450323eb12e5e', '[\"*\"]', '2026-08-01 11:37:31', NULL, '2026-08-01 11:37:26', '2026-08-01 11:37:31'),
(132, 'App\\Models\\User', 1, 'auth-token', '2f933a55b1c601c054a21c8e447ce0dc395619f0e2e4663bf1951c7dac499c9d', '[\"*\"]', '2026-08-02 13:11:02', NULL, '2026-08-02 12:48:41', '2026-08-02 13:11:02');

-- --------------------------------------------------------

--
-- Table structure for table `production_tasks`
--

CREATE TABLE `production_tasks` (
  `id` char(36) NOT NULL,
  `order_id` char(36) NOT NULL,
  `branch_id` char(36) NOT NULL,
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `current_status` varchar(20) NOT NULL DEFAULT 'QUEUE',
  `qty` decimal(12,2) NOT NULL DEFAULT 0.00,
  `started_date` date DEFAULT NULL,
  `finished_date` date DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `production_tasks`
--

INSERT INTO `production_tasks` (`id`, `order_id`, `branch_id`, `assigned_to`, `current_status`, `qty`, `started_date`, `finished_date`, `note`, `created_at`, `updated_at`) VALUES
('019de78e-42c9-73c1-899e-8b63a1207fab', '019d8f4d-2661-7388-9e93-6f3b0ada9fa9', '9617c6c0-85c2-463e-9885-31e0698f5d77', 4, 'DRYING', 1.00, '2026-05-02', NULL, NULL, '2026-05-02 07:19:13', '2026-05-02 10:54:48'),
('019de78e-42d3-7387-bf41-4f18c7cb7132', '019d8f5a-5443-73d7-a0a0-1aa85285a363', '9617c6c0-85c2-463e-9885-31e0698f5d77', 4, 'IRONING', 2.00, '2026-05-02', NULL, NULL, '2026-05-02 07:19:13', '2026-05-02 08:04:34'),
('019de78e-42da-71db-819c-4bebef8ab744', '019d8ff4-316b-70ec-932d-bd6dadb26769', '9617c6c0-85c2-463e-9885-31e0698f5d77', 4, 'READY', 2.00, '2026-05-02', '2026-05-02', NULL, '2026-05-02 07:19:13', '2026-05-02 08:04:38'),
('019de78e-42e6-70f9-baca-c4eefe8e3698', '019d900b-6642-73ba-95be-d5c525edcbc3', '9617c6c0-85c2-463e-9885-31e0698f5d77', 6, 'READY', 3.00, '2026-05-02', '2026-05-02', NULL, '2026-05-02 07:19:13', '2026-05-02 08:03:16'),
('019de78e-42eb-7026-8754-3786f334dcfd', '019d900d-25ce-71ca-9b71-466a0ef5810d', '9617c6c0-85c2-463e-9885-31e0698f5d77', 6, 'DRYING', 1.00, '2026-05-02', NULL, NULL, '2026-05-02 07:19:13', '2026-05-02 10:56:55'),
('019de78e-42f1-7215-995e-ae36c0e9e2f9', '019d9014-a0ff-71fc-9377-66e3f7b2803e', '9617c6c0-85c2-463e-9885-31e0698f5d77', 6, 'WASHING', 5.00, '2026-05-02', NULL, NULL, '2026-05-02 07:19:13', '2026-05-02 10:56:28'),
('019de78e-42f6-7027-b6a5-bd6207d93ae8', '019d901c-8823-73a1-a365-e702f1f851b5', '9617c6c0-85c2-463e-9885-31e0698f5d77', NULL, 'IRONING', 5.00, NULL, NULL, NULL, '2026-05-02 07:19:13', '2026-05-02 07:19:13'),
('019de78e-42fb-717c-bb6e-d17f436c8f2a', '019da08f-3673-72f9-8b78-6a30e1f19204', '9617c6c0-85c2-463e-9885-31e0698f5d77', 6, 'WASHING', 1.00, '2026-05-02', NULL, NULL, '2026-05-02 07:19:13', '2026-05-02 07:41:08'),
('019de78e-4300-7329-b05f-5d762a180938', '019da09e-0edb-737a-a86e-9cfccde6da76', '9617c6c0-85c2-463e-9885-31e0698f5d77', 6, 'WASHING', 1.00, '2026-05-02', NULL, NULL, '2026-05-02 07:19:13', '2026-05-02 07:41:10'),
('019de78e-4304-7129-ae81-1b3c9ab26b69', '019da0a3-df16-71b5-a937-8c5e250df40f', '9617c6c0-85c2-463e-9885-31e0698f5d77', 4, 'IRONING', 1.00, '2026-05-02', NULL, NULL, '2026-05-02 07:19:13', '2026-05-02 08:38:19'),
('019de78e-4308-71fa-af5d-3b13a74914d9', '019da0a8-9036-70dd-92c0-ee053a9c5967', '9617c6c0-85c2-463e-9885-31e0698f5d77', 4, 'IRONING', 1.00, '2026-05-02', NULL, NULL, '2026-05-02 07:19:13', '2026-05-02 08:38:21'),
('019de78e-430d-7266-a975-94c976cacb0c', '019da64e-d490-73d8-b46c-2b266b758872', '9617c6c0-85c2-463e-9885-31e0698f5d77', 4, 'DRYING', 1.00, '2026-05-02', NULL, NULL, '2026-05-02 07:19:13', '2026-05-02 10:33:37'),
('019de78e-4311-7200-a4ac-97d68fe95b2f', '019dae3d-7967-7289-a506-daa31a50a6f6', '9617c6c0-85c2-463e-9885-31e0698f5d77', 4, 'DRYING', 1.00, '2026-05-02', NULL, NULL, '2026-05-02 07:19:13', '2026-05-02 10:54:50'),
('019de78e-4315-7009-a719-39b60049f9d3', '019db999-fa8e-71d9-803e-88989fe0cf90', '9617c6c0-85c2-463e-9885-31e0698f5d77', 4, 'DRYING', 1.00, '2026-05-02', NULL, NULL, '2026-05-02 07:19:13', '2026-05-02 10:54:51'),
('019de78e-4319-7281-870c-9ed12ff978ee', '019db9c0-4045-705b-a279-9c6596324665', '9617c6c0-85c2-463e-9885-31e0698f5d77', 4, 'DRYING', 1.00, '2026-05-02', NULL, NULL, '2026-05-02 07:19:13', '2026-05-02 10:54:53'),
('019de78e-431d-71bc-b28e-faa65e9339e4', '019dc309-b997-7022-aaa1-0acf2093144d', '9617c6c0-85c2-463e-9885-31e0698f5d77', 4, 'WASHING', 1.00, '2026-05-02', NULL, NULL, '2026-05-02 07:19:13', '2026-05-02 11:01:31'),
('019de858-ddd4-738b-a444-f5c91084ead8', '019de858-3a3b-73ae-8734-5516b49decd3', '9617c6c0-85c2-463e-9885-31e0698f5d77', 4, 'WASHING', 1.00, '2026-05-02', NULL, NULL, '2026-05-02 11:00:31', '2026-05-02 11:00:37'),
('019de858-ddd9-70cf-a230-29400353cf99', '019de858-9545-73a5-bb75-c60f555a0f4e', '9617c6c0-85c2-463e-9885-31e0698f5d77', NULL, 'QUEUE', 1.00, NULL, NULL, NULL, '2026-05-02 11:00:31', '2026-05-02 11:00:31'),
('019fc285-867b-7082-889e-326e28df9ff9', '019e5a2a-1cbe-714b-b7fb-befaa585aa3c', '9617c6c0-85c2-463e-9885-31e0698f5d77', NULL, 'READY', 1.00, NULL, '2026-08-02', NULL, '2026-08-02 12:49:18', '2026-08-02 12:49:18'),
('019fc285-8688-73bd-8a3b-2a05bb7c7f2b', '019e5a31-64c9-7261-b20c-32e2180a69b0', '9617c6c0-85c2-463e-9885-31e0698f5d77', NULL, 'QUEUE', 1.00, NULL, NULL, NULL, '2026-08-02 12:49:18', '2026-08-02 12:49:18'),
('019fc285-868c-70a6-aa80-1e51abd7b7bb', '019e5e58-f51a-7108-b3d7-c66897a8ebea', '9617c6c0-85c2-463e-9885-31e0698f5d77', NULL, 'QUEUE', 1.00, NULL, NULL, NULL, '2026-08-02 12:49:18', '2026-08-02 12:49:18'),
('019fc285-8691-719d-9a0f-83922f8e17de', '019e5e5d-6b84-70db-a52f-4036ce523327', '9617c6c0-85c2-463e-9885-31e0698f5d77', NULL, 'QUEUE', 1.00, NULL, NULL, NULL, '2026-08-02 12:49:18', '2026-08-02 12:49:18'),
('019fc285-8697-7123-960d-a92153f50aa1', '019e5e67-1025-719f-9a34-5d652f6e2430', '9617c6c0-85c2-463e-9885-31e0698f5d77', NULL, 'QUEUE', 1.00, NULL, NULL, NULL, '2026-08-02 12:49:18', '2026-08-02 12:49:18'),
('019fc285-86a6-70ae-a2ac-2ed5a52fe8c9', '019e5e67-d337-7279-b33c-ebd731ae61b6', '9617c6c0-85c2-463e-9885-31e0698f5d77', NULL, 'QUEUE', 1.00, NULL, NULL, NULL, '2026-08-02 12:49:18', '2026-08-02 12:49:18'),
('019fc285-86ab-734e-b646-ef87153c28f1', '019e5e68-a273-70b0-951e-1f66f1c65534', '9617c6c0-85c2-463e-9885-31e0698f5d77', NULL, 'QUEUE', 1.00, NULL, NULL, NULL, '2026-08-02 12:49:18', '2026-08-02 12:49:18'),
('019fc285-86af-72f3-a9c6-dba9ef25e206', '019e5e6c-688b-72bc-98a5-6986f4c72c93', '9617c6c0-85c2-463e-9885-31e0698f5d77', NULL, 'QUEUE', 1.00, NULL, NULL, NULL, '2026-08-02 12:49:18', '2026-08-02 12:49:18'),
('019fc285-86b3-7304-b510-1f72005ec845', '019e5e76-6529-73fa-9605-511e5352ba8e', '9617c6c0-85c2-463e-9885-31e0698f5d77', NULL, 'QUEUE', 1.00, NULL, NULL, NULL, '2026-08-02 12:49:18', '2026-08-02 12:49:18'),
('019fc285-86b7-72bf-9beb-8d60eee2b48c', '019e5ef4-d62e-718c-8352-f7f9be855513', '9617c6c0-85c2-463e-9885-31e0698f5d77', NULL, 'QUEUE', 1.00, NULL, NULL, NULL, '2026-08-02 12:49:18', '2026-08-02 12:49:18'),
('019fc285-86bc-7066-8ca4-9a434c73dba3', '019e5f2b-1964-7061-83ce-29cbca5fc54f', '9617c6c0-85c2-463e-9885-31e0698f5d77', NULL, 'QUEUE', 2.00, NULL, NULL, NULL, '2026-08-02 12:49:18', '2026-08-02 12:49:18'),
('019fc285-86c0-7290-98b4-9a1c35dd9c1b', '019e5f40-dd37-7221-bb02-d0929965bee6', '9617c6c0-85c2-463e-9885-31e0698f5d77', NULL, 'QUEUE', 1.00, NULL, NULL, NULL, '2026-08-02 12:49:18', '2026-08-02 12:49:18'),
('019fc285-86c4-7095-a2e1-4673829691aa', '019f41da-de5e-7092-af35-39222437f538', '9617c6c0-85c2-463e-9885-31e0698f5d77', NULL, 'QUEUE', 1.00, NULL, NULL, NULL, '2026-08-02 12:49:18', '2026-08-02 12:49:18');

-- --------------------------------------------------------

--
-- Table structure for table `production_task_correction_requests`
--

CREATE TABLE `production_task_correction_requests` (
  `id` char(36) NOT NULL,
  `production_task_id` char(36) NOT NULL,
  `order_id` char(36) NOT NULL,
  `branch_id` char(36) NOT NULL,
  `requested_by` bigint(20) UNSIGNED NOT NULL,
  `reviewed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `type` varchar(20) NOT NULL,
  `from_status` varchar(20) NOT NULL,
  `to_status` varchar(20) NOT NULL,
  `reason` text NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'PENDING',
  `review_note` text DEFAULT NULL,
  `requested_date` date NOT NULL,
  `reviewed_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `production_task_correction_requests`
--

INSERT INTO `production_task_correction_requests` (`id`, `production_task_id`, `order_id`, `branch_id`, `requested_by`, `reviewed_by`, `type`, `from_status`, `to_status`, `reason`, `status`, `review_note`, `requested_date`, `reviewed_date`, `created_at`, `updated_at`) VALUES
('019de83d-64be-71e2-a644-2f39f8460b4b', '019de78e-430d-7266-a975-94c976cacb0c', '019da64e-d490-73d8-b46c-2b266b758872', '9617c6c0-85c2-463e-9885-31e0698f5d77', 4, 2, 'ROLLBACK', 'WASHING', 'QUEUE', 'contoh', 'REJECTED', 'sudah aman', '2026-05-02', '2026-05-02', '2026-05-02 10:30:30', '2026-05-02 10:31:30'),
('019de83d-c707-7148-9429-e4a3166d9e9c', '019de78e-42c9-73c1-899e-8b63a1207fab', '019d8f4d-2661-7388-9e93-6f3b0ada9fa9', '9617c6c0-85c2-463e-9885-31e0698f5d77', 4, 2, 'REWASH', 'DRYING', 'WASHING', 'contoh cuci ulang', 'APPROVED', NULL, '2026-05-02', '2026-05-02', '2026-05-02 10:30:56', '2026-05-02 10:31:18'),
('019de854-1e77-7262-9013-4d96a29dca41', '019de78e-431d-71bc-b28e-faa65e9339e4', '019dc309-b997-7022-aaa1-0acf2093144d', '9617c6c0-85c2-463e-9885-31e0698f5d77', 4, 1, 'ROLLBACK', 'WASHING', 'QUEUE', 'nyoba', 'APPROVED', NULL, '2026-05-02', '2026-05-02', '2026-05-02 10:55:20', '2026-05-02 10:55:37');

-- --------------------------------------------------------

--
-- Table structure for table `production_task_logs`
--

CREATE TABLE `production_task_logs` (
  `id` char(36) NOT NULL,
  `production_task_id` char(36) NOT NULL,
  `order_id` char(36) NOT NULL,
  `branch_id` char(36) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `from_status` varchar(20) DEFAULT NULL,
  `to_status` varchar(20) NOT NULL,
  `qty` decimal(12,2) NOT NULL DEFAULT 0.00,
  `process_date` date NOT NULL,
  `started_date` date DEFAULT NULL,
  `finished_date` date DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `production_task_logs`
--

INSERT INTO `production_task_logs` (`id`, `production_task_id`, `order_id`, `branch_id`, `user_id`, `from_status`, `to_status`, `qty`, `process_date`, `started_date`, `finished_date`, `note`, `created_at`, `updated_at`) VALUES
('019de83e-1d13-7377-a42f-978ed244cdd8', '019de78e-42c9-73c1-899e-8b63a1207fab', '019d8f4d-2661-7388-9e93-6f3b0ada9fa9', '9617c6c0-85c2-463e-9885-31e0698f5d77', 2, 'DRYING', 'WASHING', 1.00, '2026-05-02', '2026-05-02', NULL, '[APPROVED REWASH] contoh cuci ulang', '2026-05-02 10:31:18', '2026-05-02 10:31:18'),
('019de840-3c9e-7345-832b-cbc76855f675', '019de78e-430d-7266-a975-94c976cacb0c', '019da64e-d490-73d8-b46c-2b266b758872', '9617c6c0-85c2-463e-9885-31e0698f5d77', 4, 'WASHING', 'DRYING', 1.00, '2026-05-02', '2026-05-02', NULL, NULL, '2026-05-02 10:33:37', '2026-05-02 10:33:37'),
('019de840-8dcd-707c-9580-27cc3dd218d8', '019de78e-4311-7200-a4ac-97d68fe95b2f', '019dae3d-7967-7289-a506-daa31a50a6f6', '9617c6c0-85c2-463e-9885-31e0698f5d77', 4, 'QUEUE', 'WASHING', 1.00, '2026-05-02', '2026-05-02', NULL, NULL, '2026-05-02 10:33:57', '2026-05-02 10:33:57'),
('019de840-9389-71b9-9bf5-5a95486bfdd0', '019de78e-4315-7009-a719-39b60049f9d3', '019db999-fa8e-71d9-803e-88989fe0cf90', '9617c6c0-85c2-463e-9885-31e0698f5d77', 4, 'QUEUE', 'WASHING', 1.00, '2026-05-02', '2026-05-02', NULL, NULL, '2026-05-02 10:33:59', '2026-05-02 10:33:59'),
('019de840-99a3-72c8-9fc7-59323ff9f9bb', '019de78e-4319-7281-870c-9ed12ff978ee', '019db9c0-4045-705b-a279-9c6596324665', '9617c6c0-85c2-463e-9885-31e0698f5d77', 4, 'QUEUE', 'WASHING', 1.00, '2026-05-02', '2026-05-02', NULL, NULL, '2026-05-02 10:34:00', '2026-05-02 10:34:00'),
('019de853-a1bc-72ae-b707-372b2ce7e0f7', '019de78e-42c9-73c1-899e-8b63a1207fab', '019d8f4d-2661-7388-9e93-6f3b0ada9fa9', '9617c6c0-85c2-463e-9885-31e0698f5d77', 4, 'WASHING', 'DRYING', 1.00, '2026-05-02', '2026-05-02', NULL, NULL, '2026-05-02 10:54:48', '2026-05-02 10:54:48'),
('019de853-aa0e-7163-bf0b-699f7a5a3b6d', '019de78e-4311-7200-a4ac-97d68fe95b2f', '019dae3d-7967-7289-a506-daa31a50a6f6', '9617c6c0-85c2-463e-9885-31e0698f5d77', 4, 'WASHING', 'DRYING', 1.00, '2026-05-02', '2026-05-02', NULL, NULL, '2026-05-02 10:54:50', '2026-05-02 10:54:50'),
('019de853-af4f-7125-ad4c-8899106c0f91', '019de78e-4315-7009-a719-39b60049f9d3', '019db999-fa8e-71d9-803e-88989fe0cf90', '9617c6c0-85c2-463e-9885-31e0698f5d77', 4, 'WASHING', 'DRYING', 1.00, '2026-05-02', '2026-05-02', NULL, NULL, '2026-05-02 10:54:51', '2026-05-02 10:54:51'),
('019de853-b4e0-7010-847c-aee11bbbf501', '019de78e-4319-7281-870c-9ed12ff978ee', '019db9c0-4045-705b-a279-9c6596324665', '9617c6c0-85c2-463e-9885-31e0698f5d77', 4, 'WASHING', 'DRYING', 1.00, '2026-05-02', '2026-05-02', NULL, NULL, '2026-05-02 10:54:53', '2026-05-02 10:54:53'),
('019de853-c916-7242-8781-b758982fd0f3', '019de78e-431d-71bc-b28e-faa65e9339e4', '019dc309-b997-7022-aaa1-0acf2093144d', '9617c6c0-85c2-463e-9885-31e0698f5d77', 4, 'QUEUE', 'WASHING', 1.00, '2026-05-02', '2026-05-02', NULL, NULL, '2026-05-02 10:54:58', '2026-05-02 10:54:58'),
('019de854-6055-7099-aa7e-43cbbbb7d684', '019de78e-431d-71bc-b28e-faa65e9339e4', '019dc309-b997-7022-aaa1-0acf2093144d', '9617c6c0-85c2-463e-9885-31e0698f5d77', 1, 'WASHING', 'QUEUE', 1.00, '2026-05-02', '2026-05-02', NULL, '[APPROVED ROLLBACK] nyoba', '2026-05-02 10:55:37', '2026-05-02 10:55:37'),
('019de854-82f4-7277-b915-63043b7825aa', '019de78e-42eb-7026-8754-3786f334dcfd', '019d900d-25ce-71ca-9b71-466a0ef5810d', '9617c6c0-85c2-463e-9885-31e0698f5d77', 1, 'WASHING', 'QUEUE', 1.00, '2026-05-02', '2026-05-02', NULL, '[ROLLBACK] nyoba', '2026-05-02 10:55:45', '2026-05-02 10:55:45'),
('019de854-96a1-7117-b747-63e921caeeac', '019de78e-42f1-7215-995e-ae36c0e9e2f9', '019d9014-a0ff-71fc-9377-66e3f7b2803e', '9617c6c0-85c2-463e-9885-31e0698f5d77', 1, 'WASHING', 'QUEUE', 5.00, '2026-05-02', '2026-05-02', NULL, '[ROLLBACK] nyoba', '2026-05-02 10:55:50', '2026-05-02 10:55:50'),
('019de855-2523-72b6-a5a6-00c691dbf74e', '019de78e-42eb-7026-8754-3786f334dcfd', '019d900d-25ce-71ca-9b71-466a0ef5810d', '9617c6c0-85c2-463e-9885-31e0698f5d77', 6, 'QUEUE', 'WASHING', 1.00, '2026-05-02', '2026-05-02', NULL, NULL, '2026-05-02 10:56:27', '2026-05-02 10:56:27'),
('019de855-2b40-7101-b2a1-523a8f53ce33', '019de78e-42f1-7215-995e-ae36c0e9e2f9', '019d9014-a0ff-71fc-9377-66e3f7b2803e', '9617c6c0-85c2-463e-9885-31e0698f5d77', 6, 'QUEUE', 'WASHING', 5.00, '2026-05-02', '2026-05-02', NULL, NULL, '2026-05-02 10:56:28', '2026-05-02 10:56:28'),
('019de855-913f-70ff-89bb-e69cd5942408', '019de78e-42eb-7026-8754-3786f334dcfd', '019d900d-25ce-71ca-9b71-466a0ef5810d', '9617c6c0-85c2-463e-9885-31e0698f5d77', 6, 'WASHING', 'DRYING', 1.00, '2026-05-02', '2026-05-02', NULL, NULL, '2026-05-02 10:56:55', '2026-05-02 10:56:55'),
('019de858-f725-7071-81ef-4de714a86288', '019de858-ddd4-738b-a444-f5c91084ead8', '019de858-3a3b-73ae-8734-5516b49decd3', '9617c6c0-85c2-463e-9885-31e0698f5d77', 4, 'QUEUE', 'WASHING', 1.00, '2026-05-02', '2026-05-02', NULL, NULL, '2026-05-02 11:00:37', '2026-05-02 11:00:37'),
('019de859-cae5-7368-806d-93e15e9cf10b', '019de78e-431d-71bc-b28e-faa65e9339e4', '019dc309-b997-7022-aaa1-0acf2093144d', '9617c6c0-85c2-463e-9885-31e0698f5d77', 4, 'QUEUE', 'WASHING', 1.00, '2026-05-02', '2026-05-02', NULL, NULL, '2026-05-02 11:01:32', '2026-05-02 11:01:32');

-- --------------------------------------------------------

--
-- Table structure for table `receivables`
--

CREATE TABLE `receivables` (
  `id` char(36) NOT NULL,
  `order_id` char(36) NOT NULL,
  `remaining_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` enum('OPEN','PARTIAL','SETTLED') NOT NULL DEFAULT 'OPEN',
  `due_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `receivables`
--

INSERT INTO `receivables` (`id`, `order_id`, `remaining_amount`, `status`, `due_date`, `created_at`, `updated_at`) VALUES
('073eb95a-3ef4-46cc-89e2-b9d0b1a7f053', '019e5e6c-688b-72bc-98a5-6986f4c72c93', 0.00, 'SETTLED', '2026-05-25', '2026-05-25 09:17:03', '2026-05-25 09:17:44'),
('0b1e6075-1ceb-4a97-852b-611e6ae98060', '019da0a8-9036-70dd-92c0-ee053a9c5967', 0.00, 'SETTLED', '2026-04-24', '2026-04-18 12:54:54', '2026-05-25 09:20:07'),
('1f91457e-98eb-48ad-a23d-55c2fe341bf6', '019db9c0-4045-705b-a279-9c6596324665', 50000.00, 'OPEN', '2026-04-25', '2026-04-23 09:51:17', '2026-04-24 13:47:44'),
('204843a9-5133-448b-8745-2cac21236e1b', '019e5e67-1025-719f-9a34-5d652f6e2430', 0.00, 'SETTLED', '2026-05-25', '2026-05-25 09:11:13', '2026-05-25 09:11:13'),
('2a17c636-5ab2-47ba-aaca-9686492707d8', '019e5a31-64c9-7261-b20c-32e2180a69b0', 50000.00, 'OPEN', '2026-05-24', '2026-05-24 13:34:07', '2026-05-24 13:34:07'),
('33b54b3c-6bbd-4da6-8b9b-6b40a00d3553', '019db999-fa8e-71d9-803e-88989fe0cf90', 125000.00, 'OPEN', '2026-04-27', '2026-04-23 09:09:29', '2026-05-08 05:50:05'),
('38252dc8-c112-4eec-b171-6a313984dc64', '019f41da-de5e-7092-af35-39222437f538', 0.00, 'SETTLED', '2026-07-10', '2026-07-08 13:11:30', '2026-07-08 13:23:33'),
('38c096fb-8b85-478d-9987-a6e09035bfa6', '019d8f5a-5443-73d7-a0a0-1aa85285a363', 100000.00, 'OPEN', NULL, '2026-04-15 04:15:55', '2026-04-15 04:15:55'),
('40b44402-b468-443c-a0f0-845b66345762', '019e5e76-6529-73fa-9605-511e5352ba8e', 0.00, 'SETTLED', '2026-05-25', '2026-05-25 09:27:58', '2026-05-25 09:28:52'),
('40b558b4-b151-4600-814e-3082015eb8fd', '019e5a2a-1cbe-714b-b7fb-befaa585aa3c', 45000.00, 'OPEN', '2026-05-24', '2026-05-24 13:26:09', '2026-05-24 13:26:45'),
('4ff0e2fd-e771-4d73-8253-f392d516f806', '019e5e58-f51a-7108-b3d7-c66897a8ebea', 0.00, 'SETTLED', '2026-05-25', '2026-05-25 08:55:48', '2026-05-25 08:58:03'),
('688b444f-76dd-4407-b2f0-404bd62870d5', '019d901c-8823-73a1-a365-e702f1f851b5', 0.00, 'SETTLED', NULL, '2026-04-15 07:48:02', '2026-04-16 07:08:49'),
('6bc4d037-c21b-419a-9532-baa89ce9b97b', '019d9014-a0ff-71fc-9377-66e3f7b2803e', 0.00, 'SETTLED', NULL, '2026-04-15 07:39:24', '2026-04-16 11:16:15'),
('71437b94-5e04-4c93-92cf-61d709500ce9', '019d8ff4-316b-70ec-932d-bd6dadb26769', 175000.00, 'OPEN', NULL, '2026-04-15 07:03:59', '2026-04-15 07:03:59'),
('72b06a02-9b91-48bb-8d4e-dd4510d3f48e', '019e5f40-dd37-7221-bb02-d0929965bee6', 100000.00, 'OPEN', '2026-05-26', '2026-05-25 13:09:07', '2026-07-08 13:31:55'),
('8c864d02-36d4-4071-965a-2f4570cfeee4', '019d900d-25ce-71ca-9b71-466a0ef5810d', 93750.00, 'OPEN', NULL, '2026-04-15 07:31:14', '2026-04-16 08:52:03'),
('8d2c9715-a184-4562-8c33-d43f6b747ae3', '019da0a3-df16-71b5-a937-8c5e250df40f', 45000.00, 'OPEN', NULL, '2026-04-18 12:49:47', '2026-04-18 12:49:47'),
('8f43a3ab-6b99-4d0e-9384-490838b54b8a', '019d996c-6759-72a6-80f5-b7a96613fba9', 0.00, 'SETTLED', NULL, '2026-04-17 03:11:52', '2026-04-17 03:11:52'),
('91a3ffdb-b94c-42e2-9845-9112900075b2', '019e5e5d-6b84-70db-a52f-4036ce523327', 0.00, 'SETTLED', '2026-05-25', '2026-05-25 09:00:41', '2026-05-25 09:00:41'),
('93e8e1c6-fa7b-43ed-9307-24c70d094a8b', '019e5e67-d337-7279-b33c-ebd731ae61b6', 73750.00, 'PARTIAL', '2026-05-25', '2026-05-25 09:12:03', '2026-05-25 09:12:03'),
('9759c0b3-ddbe-4746-8585-e79a602cb672', '019da08f-3673-72f9-8b78-6a30e1f19204', 50000.00, 'OPEN', NULL, '2026-04-18 12:27:13', '2026-04-18 12:27:13'),
('a8273b9f-d4b0-4aa5-b504-f7a4f34cbb66', '019e5ef4-d62e-718c-8352-f7f9be855513', 125000.00, 'OPEN', '2026-05-26', '2026-05-25 11:46:04', '2026-05-25 11:46:04'),
('ade65517-e816-4116-9710-535d67ee1aa0', '019e5f2b-1964-7061-83ce-29cbca5fc54f', 90000.00, 'OPEN', '2026-05-26', '2026-05-25 12:45:20', '2026-07-08 13:38:35'),
('be0c0b06-97ad-489e-ae1e-ba29d24d9483', '019dc309-b997-7022-aaa1-0acf2093144d', 45000.00, 'OPEN', '2026-04-29', '2026-04-25 05:08:07', '2026-04-25 05:08:07'),
('cd755383-defa-4b72-8c42-80d0bdb8a216', '019de858-9545-73a5-bb75-c60f555a0f4e', 0.00, 'SETTLED', '2026-05-06', '2026-05-02 11:00:12', '2026-05-10 08:08:00'),
('d0586f53-a484-4c00-8d84-8cab86f268e9', '019d8f4d-2661-7388-9e93-6f3b0ada9fa9', 50000.00, 'OPEN', NULL, '2026-04-15 04:01:31', '2026-04-15 04:01:31'),
('d6216cbf-5d73-469c-aeee-df8630bef728', '019d900b-6642-73ba-95be-d5c525edcbc3', 220000.00, 'OPEN', NULL, '2026-04-15 07:29:19', '2026-04-15 07:29:19'),
('d7225b0c-7fac-42c1-9ead-20a79c4bc456', '019da64e-d490-73d8-b46c-2b266b758872', 0.00, 'SETTLED', '2026-04-23', '2026-04-19 15:14:37', '2026-05-25 08:56:44'),
('dfb6fd8c-153e-405b-89b2-3fb29d8fdef0', '019e5e68-a273-70b0-951e-1f66f1c65534', 0.00, 'SETTLED', '2026-05-25', '2026-05-25 09:12:56', '2026-05-25 09:12:56'),
('e28e36d0-e298-40bf-a270-c7aa052adcee', '019de858-3a3b-73ae-8734-5516b49decd3', 45000.00, 'OPEN', '2026-05-07', '2026-05-02 10:59:49', '2026-05-02 10:59:49'),
('ec5c64e9-2229-4584-b68a-f0e112af8986', '019da09e-0edb-737a-a86e-9cfccde6da76', 125000.00, 'OPEN', NULL, '2026-04-18 12:43:26', '2026-04-18 12:43:26');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Superadmin', 'web', '2026-03-29 00:44:53', '2026-03-29 00:44:53'),
(2, 'Admin Cabang', 'web', '2026-03-29 00:44:53', '2026-03-29 00:44:53'),
(3, 'Kasir', 'web', '2026-03-29 00:44:53', '2026-03-29 00:44:53'),
(4, 'Petugas Cuci', 'web', '2026-03-29 00:44:53', '2026-03-29 00:44:53'),
(5, 'Kurir', 'web', '2026-03-29 00:44:53', '2026-03-29 00:44:53'),
(6, 'Akuntansi', 'web', '2026-06-09 09:12:58', '2026-06-09 09:12:58');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` char(36) NOT NULL,
  `category_id` char(36) NOT NULL,
  `name` varchar(150) NOT NULL,
  `unit` varchar(32) NOT NULL,
  `price_default` decimal(14,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `category_id`, `name`, `unit`, `price_default`, `is_active`, `created_at`, `updated_at`) VALUES
('57c68862-c326-4352-af54-c19496b74493', 'fe99f333-e352-4ee4-a8bf-4605cc636835', 'Deep Clean + Repair', 'ITEM', 125000.00, 1, '2026-04-15 04:52:11', '2026-04-15 04:55:45'),
('af86c4c7-2bb3-4c57-88d2-517a56312f64', 'fe99f333-e352-4ee4-a8bf-4605cc636835', 'Basic Clean', 'ITEM', 45000.00, 1, '2026-04-15 04:47:06', '2026-04-15 04:47:06'),
('c6e6746b-2833-4e78-ac5a-928973788576', 'fe99f333-e352-4ee4-a8bf-4605cc636835', 'deep clean', 'ITEM', 50000.00, 1, '2026-04-15 03:59:41', '2026-04-15 03:59:41');

-- --------------------------------------------------------

--
-- Table structure for table `service_categories`
--

CREATE TABLE `service_categories` (
  `id` char(36) NOT NULL,
  `name` varchar(120) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_categories`
--

INSERT INTO `service_categories` (`id`, `name`, `is_active`, `created_at`, `updated_at`) VALUES
('b89305f0-0413-4f4b-8411-632c19f89764', 'bag', 1, '2026-04-15 06:12:02', '2026-04-15 06:12:08'),
('fe99f333-e352-4ee4-a8bf-4605cc636835', 'sepatu', 1, '2026-04-15 03:59:19', '2026-04-15 03:59:19');

-- --------------------------------------------------------

--
-- Table structure for table `service_prices`
--

CREATE TABLE `service_prices` (
  `id` char(36) NOT NULL,
  `service_id` char(36) NOT NULL,
  `branch_id` char(36) NOT NULL,
  `price` decimal(14,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
('7h8UVYMqZaFMbEdEaWeVBDK047dCpCvnkUKlGvcj', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoialN3SWUydUl3a0k4dUh5Qjd1cHZJVE85MkN2ZEJDZTdvWDZkMUpPWCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776935427);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` char(36) DEFAULT NULL,
  `role_label` varchar(100) DEFAULT NULL,
  `modules` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`modules`)),
  `manager` tinyint(1) NOT NULL DEFAULT 0,
  `show_balance` tinyint(1) NOT NULL DEFAULT 0,
  `custom_price` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `username`, `email_verified_at`, `password`, `is_active`, `remember_token`, `created_at`, `updated_at`, `branch_id`, `role_label`, `modules`, `manager`, `show_balance`, `custom_price`) VALUES
(1, 'Superadmin', 'superadmin@gmail.com', 'superadmin', NULL, '$2y$12$tkLiWidGDJVjEpa2Rc3TCOWbhnU72id4kUMgB9utmncsriJBWuZiy', 1, NULL, '2026-03-29 00:44:54', '2026-07-08 13:23:06', NULL, 'Superadmin', '[\"dashboard\",\"kasir-pos\",\"kasir-receipt\",\"kasir-customer\",\"kasir-promo\",\"ops-sorting\",\"ops-proses\",\"ops-kirim\",\"ops-tracker\",\"fin-kas\",\"fin-transaksi\",\"fin-kontak\",\"laporan\",\"set-user\",\"set-master\",\"set-outlet\",\"set-coa\",\"set-jurnal\",\"set-labels\",\"set-paymethod\",\"set-num\",\"set-wa\"]', 1, 1, 1),
(2, 'Admin PB', 'adminpb@gmail.com', 'adminpb', NULL, '$2y$12$2fOL3FpRv4q2T0LoGA.hqe5ksNW.d/wD9vZFnIQiIYzHQx89khDrK', 1, NULL, '2026-03-29 00:44:54', '2026-04-15 05:08:02', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'Admin Cabang', '[\"dashboard\",\"kasir-pos\",\"kasir-receipt\",\"kasir-customer\",\"kasir-promo\",\"ops-proses\",\"ops-kirim\",\"fin-transaksi\",\"laporan\",\"set-user\",\"set-master\",\"set-wa\"]', 1, 1, 1),
(3, 'Kasir PB', 'kasirpb@gmail.com', 'kasirpb', NULL, '$2y$12$OGpCm22CqyMStKcyaW3hn.MgoWra6BGLqf9FeqnXCG9PecUcy914m', 1, NULL, '2026-03-29 00:44:55', '2026-04-15 04:00:32', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'Kasir', '[\"dashboard\",\"kasir-pos\",\"kasir-receipt\",\"kasir-customer\",\"kasir-promo\",\"laporan\"]', 0, 0, 0),
(4, 'Petugas Cuci PB', 'petugascucipb@gmail.com', 'petugascucipb', NULL, '$2y$12$FBUEerr7Zub7PGCRab86dOwQryvO3V01AWikBalO7SfsoDXJrd15e', 1, NULL, '2026-03-29 00:44:55', '2026-05-02 07:20:18', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'Petugas Cuci', '[\"dashboard\",\"ops-proses\"]', 0, 0, 0),
(5, 'Kurir PB', 'kurirpb@gmail.com', 'kurirpb', NULL, '$2y$12$5Ec6uYd9lxv.ogL1gud0O.68.4aM0IAWW0XqD2/i176zHsDrQIuY6', 1, NULL, '2026-03-29 00:44:55', '2026-04-17 04:12:50', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'Kurir', '[\"dashboard\",\"ops-kirim\"]', 0, 0, 0),
(6, 'petigascucipb2', 'petugascucipb2@gmail.com', 'petugascucipb2', NULL, '$2y$12$QXYKJyxzzfk3ueLPIpbybOfD.XfBvlwzu9e0.pXrlloDzkEt0U.RO', 1, NULL, '2026-05-02 07:24:35', '2026-05-02 07:24:35', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'Petugas Cuci', '[\"dashboard\",\"ops-proses\"]', 0, 0, 0),
(7, 'akuntansi', 'akuntansi@gmail.com', 'akuntansi', NULL, '$2y$12$SVwMoC5I1b9z5GswjX9./uGcuLB46COhb18kyT0Z8DqCwBQPFXRGq', 1, NULL, '2026-06-09 09:14:42', '2026-06-09 09:14:42', NULL, 'Akuntansi', '[\"dashboard\",\"laporan\",\"set-coa\",\"set-jurnal\"]', 0, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `id` char(36) NOT NULL,
  `branch_id` char(36) DEFAULT NULL,
  `code` varchar(40) NOT NULL,
  `type` enum('PERCENT','NOMINAL') NOT NULL,
  `value` decimal(12,2) NOT NULL,
  `start_at` timestamp NULL DEFAULT NULL,
  `end_at` timestamp NULL DEFAULT NULL,
  `min_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `usage_limit` int(11) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vouchers`
--

INSERT INTO `vouchers` (`id`, `branch_id`, `code`, `type`, `value`, `start_at`, `end_at`, `min_total`, `usage_limit`, `active`, `created_at`, `updated_at`) VALUES
('019d8fa0-68fe-7309-994f-830d9d681438', NULL, 'COBA', 'NOMINAL', 40000.00, '2026-04-15 05:33:00', '2026-04-17 05:33:00', 500000.00, NULL, 1, '2026-04-15 05:32:27', '2026-04-15 05:32:27');

-- --------------------------------------------------------

--
-- Table structure for table `wash_notes`
--

CREATE TABLE `wash_notes` (
  `id` char(36) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` char(36) DEFAULT NULL,
  `note_date` date NOT NULL,
  `orders_count` int(11) NOT NULL DEFAULT 0,
  `total_qty` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wash_note_items`
--

CREATE TABLE `wash_note_items` (
  `id` char(36) NOT NULL,
  `wash_note_id` char(36) NOT NULL,
  `order_id` char(36) NOT NULL,
  `qty` decimal(10,2) NOT NULL DEFAULT 0.00,
  `process_status` varchar(20) DEFAULT NULL,
  `started_at` time DEFAULT NULL,
  `finished_at` time DEFAULT NULL,
  `note` varchar(200) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `whatsapp_templates`
--

CREATE TABLE `whatsapp_templates` (
  `id` char(36) NOT NULL,
  `branch_id` char(36) DEFAULT NULL,
  `key` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `whatsapp_templates`
--

INSERT INTO `whatsapp_templates` (`id`, `branch_id`, `key`, `name`, `content`, `is_active`, `created_at`, `updated_at`) VALUES
('03d41e64-203c-4857-87fa-8355ef1c3a4d', NULL, 'receipt_paid', 'Receipt Paid', 'Halo Ka {{customer_name}},\nTerima kasih atas pembayarannya.\nKwitansi: {{share_url}}\nNo: {{invoice_no}}\nTotal: {{grand_total}}\nTerima kasih sudah menggunakan layanan kami.\n{{app_name}}', 1, '2026-04-16 05:05:46', '2026-04-16 05:05:46'),
('283dfb5c-2bdb-4202-b5c0-f5c493ba4f8a', NULL, 'receipt_pending', 'Receipt Pending', 'Halo Ka {{customer_name}},\nBerikut tagihan laundry Anda.\nKwitansi: {{share_url}}\nNo: {{invoice_no}}\nTotal: {{grand_total}}\nMohon melakukan pembayaran, terima kasih.\n{{app_name}}', 1, '2026-04-16 05:05:34', '2026-04-19 12:52:23'),
('2a644859-f9aa-47dd-b4f4-044f10e8d75d', '9617c6c0-85c2-463e-9885-31e0698f5d77', 'receipt_pending', 'Receipt Pending', 'Halo ka {{customer_name}},\nBerikut kami kirimkan invoicenya silahkan di klik ya ka: \n{{share_url}}\nNo: {{invoice_no}}\nTotal: {{grand_total}}\nJika ingin melakukan pembayaran saat ini, bisa langsung melalui QRIS yg tersedia dan kirimkan bukti tfnya kepada wa kami ya ka, terima kasih 🙏\n{{app_name}}', 1, '2026-05-25 13:08:24', '2026-05-25 13:08:24'),
('7dbedaf0-cfb6-44a4-8eee-7850ac86ed82', NULL, 'receipt_pending', 'Receipt Pending', 'Halo ka {{customer_name}},\nBerikut kami kirimkan invoicenya silahkan di klik ya ka: \n{{share_url}}\nNo: {{invoice_no}}\nTotal: {{grand_total}}\nJika ingin melakukan pembayaran saat ini, bisa langsung melalui QRIS yg tersedia dan kirimkan bukti tfnya kepada wa kami ya ka, terima kasih 🙏\n{{app_name}}', 1, '2026-05-25 12:42:40', '2026-05-25 13:08:09'),
('e1b52175-6fb9-4e1e-9ed8-95bd5e536180', NULL, 'receipt_pending', 'Receipt Pending', 'Halo ka {{customer_name}},\nBerikut tagihan laundry Anda.\nKwitansi: {{share_url}}\nNo: {{invoice_no}}\nTotal: {{grand_total}}\nJika ingin melakukan pembayaran saat ini, bisa langsung melalui QRIS yg tersedia dan kirimkan bukti tfnya kepada wa kami ya ka, terima kasih 🙏\n{{app_name}}', 1, '2026-04-19 14:18:30', '2026-05-25 12:07:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounting_accounts`
--
ALTER TABLE `accounting_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `accounting_accounts_branch_code_unique` (`branch_id`,`code`),
  ADD KEY `accounting_accounts_parent_id_index` (`parent_id`),
  ADD KEY `accounting_accounts_type_index` (`type`),
  ADD KEY `accounting_accounts_is_active_index` (`is_active`);

--
-- Indexes for table `accounting_account_mappings`
--
ALTER TABLE `accounting_account_mappings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `accounting_mappings_unique` (`branch_id`,`event_key`,`payment_method`,`expense_category`),
  ADD KEY `accounting_mappings_event_key_index` (`event_key`),
  ADD KEY `accounting_mappings_debit_account_id_index` (`debit_account_id`),
  ADD KEY `accounting_mappings_credit_account_id_index` (`credit_account_id`),
  ADD KEY `accounting_mappings_is_active_index` (`is_active`);

--
-- Indexes for table `accounting_journal_entries`
--
ALTER TABLE `accounting_journal_entries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `accounting_journal_entries_journal_no_unique` (`journal_no`),
  ADD UNIQUE KEY `accounting_journal_source_unique` (`source_type`,`source_id`),
  ADD KEY `accounting_journal_entries_created_by_foreign` (`created_by`),
  ADD KEY `accounting_journal_entries_posted_by_foreign` (`posted_by`),
  ADD KEY `accounting_journal_entries_voided_by_foreign` (`voided_by`),
  ADD KEY `accounting_journal_entries_branch_date_index` (`branch_id`,`journal_date`),
  ADD KEY `accounting_journal_entries_status_index` (`status`),
  ADD KEY `accounting_journal_entries_mapping_id_index` (`mapping_id`);

--
-- Indexes for table `accounting_journal_lines`
--
ALTER TABLE `accounting_journal_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accounting_journal_lines_entry_id_index` (`journal_entry_id`),
  ADD KEY `accounting_journal_lines_account_id_index` (`account_id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `branches_code_unique` (`code`);

--
-- Indexes for table `branch_user`
--
ALTER TABLE `branch_user`
  ADD PRIMARY KEY (`user_id`,`branch_id`),
  ADD KEY `branch_user_branch_id_foreign` (`branch_id`);

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
-- Indexes for table `cash_mutations`
--
ALTER TABLE `cash_mutations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cash_mutations_source_unique` (`source_type`,`source_id`,`type`),
  ADD KEY `cash_mutations_branch_id_effective_at_index` (`branch_id`,`effective_at`),
  ADD KEY `cash_mutations_cash_session_id_type_index` (`cash_session_id`,`type`),
  ADD KEY `cash_mutations_created_by_foreign` (`created_by`);

--
-- Indexes for table `cash_sessions`
--
ALTER TABLE `cash_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cash_sessions_branch_business_date_unique` (`branch_id`,`business_date`),
  ADD KEY `cash_sessions_branch_id_status_index` (`branch_id`,`status`),
  ADD KEY `cash_sessions_opened_by_foreign` (`opened_by`),
  ADD KEY `cash_sessions_closed_by_foreign` (`closed_by`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customers_branch_wa_unique` (`branch_id`,`whatsapp`),
  ADD KEY `customers_whatsapp_index` (`whatsapp`);

--
-- Indexes for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deliveries_order_id_index` (`order_id`),
  ADD KEY `deliveries_assigned_to_index` (`assigned_to`),
  ADD KEY `idx_deliveries_order` (`order_id`),
  ADD KEY `idx_deliveries_created` (`created_at`);

--
-- Indexes for table `delivery_events`
--
ALTER TABLE `delivery_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `delivery_events_delivery_id_index` (`delivery_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expenses_branch_id_index` (`branch_id`),
  ADD KEY `idx_expenses_branch_created` (`branch_id`,`created_at`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `invoice_counters`
--
ALTER TABLE `invoice_counters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_counters_branch_id_prefix_unique` (`branch_id`,`prefix`);

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
-- Indexes for table `loyalty_accounts`
--
ALTER TABLE `loyalty_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `loyalty_accounts_customer_id_branch_id_unique` (`customer_id`,`branch_id`),
  ADD KEY `loyalty_accounts_customer_id_index` (`customer_id`),
  ADD KEY `loyalty_accounts_branch_id_index` (`branch_id`);

--
-- Indexes for table `loyalty_logs`
--
ALTER TABLE `loyalty_logs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `loyalty_logs_order_id_unique` (`order_id`),
  ADD KEY `loyalty_logs_customer_id_index` (`customer_id`),
  ADD KEY `loyalty_logs_branch_id_index` (`branch_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_branch_number_unique` (`branch_id`,`number`),
  ADD UNIQUE KEY `orders_invoice_no_unique` (`invoice_no`),
  ADD KEY `orders_customer_id_foreign` (`customer_id`),
  ADD KEY `orders_status_index` (`status`),
  ADD KEY `orders_payment_status_index` (`payment_status`),
  ADD KEY `orders_paid_at_index` (`paid_at`),
  ADD KEY `idx_orders_branch_created` (`branch_id`,`created_at`),
  ADD KEY `idx_orders_paid_at` (`paid_at`),
  ADD KEY `orders_created_by_index` (`created_by`),
  ADD KEY `orders_received_at_index` (`received_at`),
  ADD KEY `orders_ready_at_index` (`ready_at`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_index` (`order_id`),
  ADD KEY `idx_order_items_order` (`order_id`),
  ADD KEY `idx_order_items_service` (`service_id`);

--
-- Indexes for table `order_photos`
--
ALTER TABLE `order_photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_photos_order_id_index` (`order_id`),
  ADD KEY `order_photos_kind_index` (`kind`);

--
-- Indexes for table `order_vouchers`
--
ALTER TABLE `order_vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_vouchers_order_id_unique` (`order_id`),
  ADD KEY `order_vouchers_applied_by_foreign` (`applied_by`),
  ADD KEY `order_vouchers_voucher_id_index` (`voucher_id`),
  ADD KEY `idx_order_vouchers_applied_at` (`applied_at`),
  ADD KEY `idx_order_vouchers_order` (`order_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payments_order_id_method_amount_paid_at_unique` (`order_id`,`method`,`amount`,`paid_at`),
  ADD KEY `payments_order_id_method_index` (`order_id`,`method`),
  ADD KEY `idx_payments_paid_at` (`paid_at`),
  ADD KEY `idx_payments_order` (`order_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `production_tasks`
--
ALTER TABLE `production_tasks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `production_tasks_order_id_unique` (`order_id`),
  ADD KEY `production_tasks_branch_id_current_status_index` (`branch_id`,`current_status`),
  ADD KEY `production_tasks_assigned_to_finished_date_index` (`assigned_to`,`finished_date`),
  ADD KEY `production_tasks_started_date_index` (`started_date`);

--
-- Indexes for table `production_task_correction_requests`
--
ALTER TABLE `production_task_correction_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `production_task_correction_requests_reviewed_by_foreign` (`reviewed_by`),
  ADD KEY `ptcr_branch_status_idx` (`branch_id`,`status`),
  ADD KEY `ptcr_order_status_idx` (`order_id`,`status`),
  ADD KEY `ptcr_requested_status_idx` (`requested_by`,`status`),
  ADD KEY `ptcr_task_status_idx` (`production_task_id`,`status`);

--
-- Indexes for table `production_task_logs`
--
ALTER TABLE `production_task_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `production_task_logs_production_task_id_foreign` (`production_task_id`),
  ADD KEY `production_task_logs_branch_id_process_date_index` (`branch_id`,`process_date`),
  ADD KEY `production_task_logs_user_id_process_date_index` (`user_id`,`process_date`),
  ADD KEY `production_task_logs_order_id_index` (`order_id`);

--
-- Indexes for table `receivables`
--
ALTER TABLE `receivables`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receivables_order_id_unique` (`order_id`),
  ADD KEY `receivables_status_index` (`status`),
  ADD KEY `idx_receivables_status_due` (`status`,`due_date`),
  ADD KEY `idx_receivables_order` (`order_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `services_category_id_name_unique` (`category_id`,`name`);

--
-- Indexes for table `service_categories`
--
ALTER TABLE `service_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_prices`
--
ALTER TABLE `service_prices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `service_prices_service_id_branch_id_unique` (`service_id`,`branch_id`),
  ADD KEY `service_prices_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD KEY `users_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vouchers_code_unique` (`code`),
  ADD KEY `vouchers_branch_id_index` (`branch_id`);

--
-- Indexes for table `wash_notes`
--
ALTER TABLE `wash_notes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `wash_notes_user_id_note_date_unique` (`user_id`,`note_date`),
  ADD KEY `wash_notes_note_date_index` (`note_date`),
  ADD KEY `wash_notes_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `wash_note_items`
--
ALTER TABLE `wash_note_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `wash_note_items_wash_note_id_order_id_unique` (`wash_note_id`,`order_id`),
  ADD UNIQUE KEY `wash_note_items_order_id_unique_global` (`order_id`),
  ADD KEY `wash_note_items_order_id_index` (`order_id`);

--
-- Indexes for table `whatsapp_templates`
--
ALTER TABLE `whatsapp_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `wa_templates_branch_key_unique` (`branch_id`,`key`),
  ADD KEY `wa_templates_key_active_idx` (`key`,`is_active`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounting_accounts`
--
ALTER TABLE `accounting_accounts`
  ADD CONSTRAINT `accounting_accounts_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `accounting_accounts_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `accounting_accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `accounting_account_mappings`
--
ALTER TABLE `accounting_account_mappings`
  ADD CONSTRAINT `accounting_account_mappings_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `accounting_account_mappings_credit_account_id_foreign` FOREIGN KEY (`credit_account_id`) REFERENCES `accounting_accounts` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `accounting_account_mappings_debit_account_id_foreign` FOREIGN KEY (`debit_account_id`) REFERENCES `accounting_accounts` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `accounting_journal_entries`
--
ALTER TABLE `accounting_journal_entries`
  ADD CONSTRAINT `accounting_journal_entries_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `accounting_journal_entries_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `accounting_journal_entries_mapping_id_foreign` FOREIGN KEY (`mapping_id`) REFERENCES `accounting_account_mappings` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `accounting_journal_entries_posted_by_foreign` FOREIGN KEY (`posted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `accounting_journal_entries_voided_by_foreign` FOREIGN KEY (`voided_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `accounting_journal_lines`
--
ALTER TABLE `accounting_journal_lines`
  ADD CONSTRAINT `accounting_journal_lines_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounting_accounts` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `accounting_journal_lines_journal_entry_id_foreign` FOREIGN KEY (`journal_entry_id`) REFERENCES `accounting_journal_entries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `branch_user`
--
ALTER TABLE `branch_user`
  ADD CONSTRAINT `branch_user_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `branch_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cash_mutations`
--
ALTER TABLE `cash_mutations`
  ADD CONSTRAINT `cash_mutations_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_mutations_cash_session_id_foreign` FOREIGN KEY (`cash_session_id`) REFERENCES `cash_sessions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_mutations_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `cash_sessions`
--
ALTER TABLE `cash_sessions`
  ADD CONSTRAINT `cash_sessions_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_sessions_closed_by_foreign` FOREIGN KEY (`closed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `cash_sessions_opened_by_foreign` FOREIGN KEY (`opened_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD CONSTRAINT `deliveries_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `deliveries_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `delivery_events`
--
ALTER TABLE `delivery_events`
  ADD CONSTRAINT `delivery_events_delivery_id_foreign` FOREIGN KEY (`delivery_id`) REFERENCES `deliveries` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `invoice_counters`
--
ALTER TABLE `invoice_counters`
  ADD CONSTRAINT `invoice_counters_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_items_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `order_photos`
--
ALTER TABLE `order_photos`
  ADD CONSTRAINT `order_photos_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_vouchers`
--
ALTER TABLE `order_vouchers`
  ADD CONSTRAINT `order_vouchers_applied_by_foreign` FOREIGN KEY (`applied_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `order_vouchers_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_vouchers_voucher_id_foreign` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `production_tasks`
--
ALTER TABLE `production_tasks`
  ADD CONSTRAINT `production_tasks_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `production_tasks_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `production_tasks_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `production_task_correction_requests`
--
ALTER TABLE `production_task_correction_requests`
  ADD CONSTRAINT `production_task_correction_requests_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `production_task_correction_requests_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `production_task_correction_requests_production_task_id_foreign` FOREIGN KEY (`production_task_id`) REFERENCES `production_tasks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `production_task_correction_requests_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `production_task_correction_requests_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `production_task_logs`
--
ALTER TABLE `production_task_logs`
  ADD CONSTRAINT `production_task_logs_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `production_task_logs_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `production_task_logs_production_task_id_foreign` FOREIGN KEY (`production_task_id`) REFERENCES `production_tasks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `production_task_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `receivables`
--
ALTER TABLE `receivables`
  ADD CONSTRAINT `receivables_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `service_categories` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `service_prices`
--
ALTER TABLE `service_prices`
  ADD CONSTRAINT `service_prices_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `service_prices_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD CONSTRAINT `vouchers_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `wash_notes`
--
ALTER TABLE `wash_notes`
  ADD CONSTRAINT `wash_notes_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `wash_notes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `wash_note_items`
--
ALTER TABLE `wash_note_items`
  ADD CONSTRAINT `wash_note_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `wash_note_items_wash_note_id_foreign` FOREIGN KEY (`wash_note_id`) REFERENCES `wash_notes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `whatsapp_templates`
--
ALTER TABLE `whatsapp_templates`
  ADD CONSTRAINT `whatsapp_templates_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
