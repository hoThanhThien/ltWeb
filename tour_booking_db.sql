-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th7 10, 2025 lúc 03:32 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `tour_booking_db`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `auth_tokens`
--

CREATE TABLE `auth_tokens` (
  `id` int(11) NOT NULL,
  `selector` varchar(255) NOT NULL,
  `hashed_validator` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `expires` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `tour_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  `paid_at` datetime DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `tour_id`, `quantity`, `total_price`, `start_date`, `end_date`, `status`, `paid_at`, `payment_method`, `created_at`) VALUES
(102, 10, 19, 1, 2000.00, '2025-07-05', '2025-07-12', 'confirmed', '2025-07-02 16:35:48', 'sepay', '2025-07-02 08:51:50'),
(104, 10, 19, 1, 2000.00, '2025-07-05', '2025-07-12', 'confirmed', NULL, NULL, '2025-07-04 00:46:51'),
(105, 10, 19, 1, 2000.00, '2025-07-05', '2025-07-12', 'confirmed', NULL, NULL, '2025-07-04 01:32:39'),
(106, 10, 19, 1, 2000.00, '2025-07-05', '2025-07-12', 'confirmed', '2025-07-04 10:03:23', 'sepay', '2025-07-04 03:02:24'),
(107, 10, 19, 1, 2000.00, '2025-07-05', '2025-07-12', 'confirmed', '2025-07-04 10:48:14', 'sepay', '2025-07-04 03:46:56'),
(108, 10, 21, 1, 18000000.00, '2025-07-25', '2025-07-30', 'confirmed', NULL, NULL, '2025-07-04 09:24:47'),
(109, 10, 19, 1, 2000.00, '2025-07-05', '2025-07-12', 'confirmed', NULL, NULL, '2025-07-05 14:13:01'),
(111, 10, 19, 1, 2000.00, '2025-07-01', '2025-07-06', 'confirmed', NULL, NULL, '2025-07-06 19:02:04'),
(112, 10, 19, 1, 2000.00, '2025-07-23', '2025-07-30', 'confirmed', NULL, NULL, '2025-07-07 10:56:09'),
(113, 10, 19, 1, 2000.00, '2025-07-23', '2025-07-30', 'confirmed', '2025-07-07 18:58:22', 'sepay', '2025-07-07 11:56:19'),
(114, 10, 12, 1, 3500000.00, '2025-07-29', '2025-07-31', 'confirmed', NULL, NULL, '2025-07-08 12:19:13'),
(115, 10, 28, 1, 40000.00, '2025-07-31', '2025-08-05', 'confirmed', NULL, NULL, '2025-07-08 12:47:22'),
(116, 10, 12, 1, 3500000.00, '2025-07-29', '2025-07-31', 'confirmed', NULL, NULL, '2025-07-08 13:05:47'),
(117, 10, 12, 2, 7000000.00, '2025-07-29', '2025-07-31', 'confirmed', NULL, NULL, '2025-07-08 13:06:15'),
(118, 10, 13, 1, 4200000.00, '2025-08-27', '2025-07-31', 'confirmed', NULL, NULL, '2025-07-08 13:10:07');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'Admin'),
(3, 'Customer');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tb_transactions`
--

CREATE TABLE `tb_transactions` (
  `id` int(11) NOT NULL,
  `gateway` varchar(100) NOT NULL,
  `transaction_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `account_number` varchar(100) DEFAULT NULL,
  `sub_account` varchar(250) DEFAULT NULL,
  `amount_in` decimal(20,2) NOT NULL DEFAULT 0.00,
  `amount_out` decimal(20,2) NOT NULL DEFAULT 0.00,
  `accumulated` decimal(20,2) NOT NULL DEFAULT 0.00,
  `code` varchar(250) DEFAULT NULL,
  `transaction_content` text DEFAULT NULL,
  `reference_number` varchar(255) DEFAULT NULL,
  `body` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tb_transactions`
--

INSERT INTO `tb_transactions` (`id`, `gateway`, `transaction_date`, `account_number`, `sub_account`, `amount_in`, `amount_out`, `accumulated`, `code`, `transaction_content`, `reference_number`, `body`, `created_at`) VALUES
(1, 'MBBank', '2025-07-02 08:52:00', '0375227764', NULL, 2000.00, 0.00, 0.00, 'DH102', 'DH102 FT25183502456710   Ma giao dich  Trace892046 Trace 892046', 'FT25183000521350', '{\"gateway\":\"MBBank\",\"transactionDate\":\"2025-07-02 15:52:00\",\"accountNumber\":\"0375227764\",\"subAccount\":null,\"code\":\"DH102\",\"content\":\"DH102 FT25183502456710   Ma giao dich  Trace892046 Trace 892046\",\"transferType\":\"in\",\"description\":\"BankAPINotify DH102 FT25183502456710   Ma giao dich  Trace892046 Trace 892046\",\"transferAmount\":2000,\"referenceCode\":\"FT25183000521350\",\"accumulated\":0,\"id\":16563315}', '2025-07-02 09:24:02'),
(2, 'MBBank', '2025-07-02 08:52:00', '0375227764', NULL, 2000.00, 0.00, 0.00, 'DH102', 'DH102 FT25183502456710   Ma giao dich  Trace892046 Trace 892046', 'FT25183000521350', '{\"gateway\":\"MBBank\",\"transactionDate\":\"2025-07-02 15:52:00\",\"accountNumber\":\"0375227764\",\"subAccount\":null,\"code\":\"DH102\",\"content\":\"DH102 FT25183502456710   Ma giao dich  Trace892046 Trace 892046\",\"transferType\":\"in\",\"description\":\"BankAPINotify DH102 FT25183502456710   Ma giao dich  Trace892046 Trace 892046\",\"transferAmount\":2000,\"referenceCode\":\"FT25183000521350\",\"accumulated\":0,\"id\":16563315}', '2025-07-02 09:30:10'),
(3, 'MBBank', '2025-07-02 08:52:00', '0375227764', NULL, 2000.00, 0.00, 0.00, 'DH102', 'DH102 FT25183502456710   Ma giao dich  Trace892046 Trace 892046', 'FT25183000521350', '{\"gateway\":\"MBBank\",\"transactionDate\":\"2025-07-02 15:52:00\",\"accountNumber\":\"0375227764\",\"subAccount\":null,\"code\":\"DH102\",\"content\":\"DH102 FT25183502456710   Ma giao dich  Trace892046 Trace 892046\",\"transferType\":\"in\",\"description\":\"BankAPINotify DH102 FT25183502456710   Ma giao dich  Trace892046 Trace 892046\",\"transferAmount\":2000,\"referenceCode\":\"FT25183000521350\",\"accumulated\":0,\"id\":16563315}', '2025-07-02 09:30:48'),
(4, 'MBBank', '2025-07-02 08:52:00', '0375227764', NULL, 2000.00, 0.00, 0.00, 'DH102', 'DH102 FT25183502456710   Ma giao dich  Trace892046 Trace 892046', 'FT25183000521350', '{\"gateway\":\"MBBank\",\"transactionDate\":\"2025-07-02 15:52:00\",\"accountNumber\":\"0375227764\",\"subAccount\":null,\"code\":\"DH102\",\"content\":\"DH102 FT25183502456710   Ma giao dich  Trace892046 Trace 892046\",\"transferType\":\"in\",\"description\":\"BankAPINotify DH102 FT25183502456710   Ma giao dich  Trace892046 Trace 892046\",\"transferAmount\":2000,\"referenceCode\":\"FT25183000521350\",\"accumulated\":0,\"id\":16563315}', '2025-07-02 09:33:50'),
(5, 'MBBank', '2025-07-02 08:52:00', '0375227764', NULL, 2000.00, 0.00, 0.00, 'DH102', 'DH102 FT25183502456710   Ma giao dich  Trace892046 Trace 892046', 'FT25183000521350', '{\"gateway\":\"MBBank\",\"transactionDate\":\"2025-07-02 15:52:00\",\"accountNumber\":\"0375227764\",\"subAccount\":null,\"code\":\"DH102\",\"content\":\"DH102 FT25183502456710   Ma giao dich  Trace892046 Trace 892046\",\"transferType\":\"in\",\"description\":\"BankAPINotify DH102 FT25183502456710   Ma giao dich  Trace892046 Trace 892046\",\"transferAmount\":2000,\"referenceCode\":\"FT25183000521350\",\"accumulated\":0,\"id\":16563315}', '2025-07-02 09:33:57'),
(6, 'MBBank', '2025-07-02 08:52:00', '0375227764', NULL, 2000.00, 0.00, 0.00, 'DH102', 'DH102 FT25183502456710   Ma giao dich  Trace892046 Trace 892046', 'FT25183000521350', '{\"gateway\":\"MBBank\",\"transactionDate\":\"2025-07-02 15:52:00\",\"accountNumber\":\"0375227764\",\"subAccount\":null,\"code\":\"DH102\",\"content\":\"DH102 FT25183502456710   Ma giao dich  Trace892046 Trace 892046\",\"transferType\":\"in\",\"description\":\"BankAPINotify DH102 FT25183502456710   Ma giao dich  Trace892046 Trace 892046\",\"transferAmount\":2000,\"referenceCode\":\"FT25183000521350\",\"accumulated\":0,\"id\":16563315}', '2025-07-02 09:34:01'),
(7, 'MBBank', '2025-07-02 08:52:00', '0375227764', NULL, 2000.00, 0.00, 0.00, 'DH102', 'DH102 FT25183502456710   Ma giao dich  Trace892046 Trace 892046', 'FT25183000521350', '{\"gateway\":\"MBBank\",\"transactionDate\":\"2025-07-02 15:52:00\",\"accountNumber\":\"0375227764\",\"subAccount\":null,\"code\":\"DH102\",\"content\":\"DH102 FT25183502456710   Ma giao dich  Trace892046 Trace 892046\",\"transferType\":\"in\",\"description\":\"BankAPINotify DH102 FT25183502456710   Ma giao dich  Trace892046 Trace 892046\",\"transferAmount\":2000,\"referenceCode\":\"FT25183000521350\",\"accumulated\":0,\"id\":16563315}', '2025-07-02 09:35:48'),
(8, 'MBBank', '2025-07-04 03:03:00', '0375227764', NULL, 2000.00, 0.00, 0.00, 'DH106', 'DH106 FT25185951120749   Ma giao dich  Trace476873 Trace 476873', 'FT25185492336800', '{\"gateway\":\"MBBank\",\"transactionDate\":\"2025-07-04 10:03:00\",\"accountNumber\":\"0375227764\",\"subAccount\":null,\"code\":\"DH106\",\"content\":\"DH106 FT25185951120749   Ma giao dich  Trace476873 Trace 476873\",\"transferType\":\"in\",\"description\":\"BankAPINotify DH106 FT25185951120749   Ma giao dich  Trace476873 Trace 476873\",\"transferAmount\":2000,\"referenceCode\":\"FT25185492336800\",\"accumulated\":0,\"id\":16730423}', '2025-07-04 03:03:23'),
(9, 'MBBank', '2025-07-04 03:48:00', '0375227764', NULL, 2000.00, 0.00, 0.00, 'DH107', 'DH107 FT25185063170441   Ma giao dich  Trace720070 Trace 720070', 'FT25185084204265', '{\"gateway\":\"MBBank\",\"transactionDate\":\"2025-07-04 10:48:00\",\"accountNumber\":\"0375227764\",\"subAccount\":null,\"code\":\"DH107\",\"content\":\"DH107 FT25185063170441   Ma giao dich  Trace720070 Trace 720070\",\"transferType\":\"in\",\"description\":\"BankAPINotify DH107 FT25185063170441   Ma giao dich  Trace720070 Trace 720070\",\"transferAmount\":2000,\"referenceCode\":\"FT25185084204265\",\"accumulated\":0,\"id\":16733683}', '2025-07-04 03:48:14'),
(10, 'MBBank', '2025-07-07 11:58:00', '0375227764', NULL, 2000.00, 0.00, 0.00, 'DH113', 'DH113 FT25188456369393   Ma giao dich  Trace778228 Trace 778228', 'FT25188013265907', '{\"gateway\":\"MBBank\",\"transactionDate\":\"2025-07-07 18:58:00\",\"accountNumber\":\"0375227764\",\"subAccount\":null,\"code\":\"DH113\",\"content\":\"DH113 FT25188456369393   Ma giao dich  Trace778228 Trace 778228\",\"transferType\":\"in\",\"description\":\"BankAPINotify DH113 FT25188456369393   Ma giao dich  Trace778228 Trace 778228\",\"transferAmount\":2000,\"referenceCode\":\"FT25188013265907\",\"accumulated\":0,\"id\":17039284}', '2025-07-07 11:58:22');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tours`
--

CREATE TABLE `tours` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration_days` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `available_slots` int(11) NOT NULL,
  `created_at` date DEFAULT curdate(),
  `discount_percent` decimal(5,2) DEFAULT 0.00,
  `discount_price` decimal(10,2) DEFAULT 0.00,
  `promotion_start` date DEFAULT NULL,
  `promotion_end` date DEFAULT NULL,
  `stars` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `loai_tour` enum('trongnuoc','nuocngoai') NOT NULL DEFAULT 'trongnuoc'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tours`
--

INSERT INTO `tours` (`id`, `title`, `location`, `description`, `price`, `duration_days`, `start_date`, `end_date`, `available_slots`, `created_at`, `discount_percent`, `discount_price`, `promotion_start`, `promotion_end`, `stars`, `image`, `loai_tour`) VALUES
(12, 'Tour Hà Nội - Hạ Long', 'Hạ Long', '<p>Khám phá vịnh Hạ Long</p>', 3500000.00, 3, '2025-07-29', '2025-07-31', 0, '2025-06-29', 0.00, 0.00, NULL, NULL, 4, 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/42/Ha_Long_2019_taken_by_DJI_FC220.jpg/330px-Ha_Long_2019_taken_by_DJI_FC220.jpg', 'trongnuoc'),
(13, 'Tour Sài Gòn - Phú Quốc', 'Phú Quốc', 'Thưởng thức biển đảo Phú Quốc', 4200000.00, 4, '2025-08-27', '2025-07-31', 19, '2025-06-29', 0.00, 0.00, NULL, NULL, 5, 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/ec/H%C3%B2n_Th%C6%A1m_cable_car_above_the_An_Th%E1%BB%9Bi_township.jpg/250px-H%C3%B2n_Th%C6%A1m_cable_car_above_the_An_Th%E1%BB%9Bi_township.jpg', 'trongnuoc'),
(14, 'Tour Đà Nẵng - Hội An', 'Hội An', 'Phố cổ Hội An lung linh', 2800000.00, 2, '2025-07-29', '2025-07-30', 9, '2025-06-29', 0.00, 0.00, NULL, NULL, 4, 'https://encrypted-tbn3.gstatic.com/licensed-image?q=tbn:ANd9GcRdj1k4tweDqVtVLYm06CnEJUxklmrCoCYpi7T99_UQJtzrzHzOoK_Tlbe-HFOXXhNPIdQV-kTpeQyDkjQ2j8lCgsricA12vu_G1U4-6w', 'trongnuoc'),
(15, 'Tour Huế - Đà Nẵng', 'Huế', 'Khám phá kinh thành Huế', 2600000.00, 3, '2025-07-28', '2025-07-30', 11, '2025-06-29', 0.00, 0.00, NULL, NULL, 3, 'https://encrypted-tbn2.gstatic.com/licensed-image?q=tbn:ANd9GcTWgxDkIj7en_Y1t4yKa7Q2fCFajEJkJgJzHOawmYh4qE-9cMWtY2MLkbUyTCEdGOMZVoX4T63qPGr15TSZtH1g3MC-YjmXLuvVO7xEBQ', 'trongnuoc'),
(16, 'Tour Sapa - Fansipan', 'Sapa', 'Chinh phục nóc nhà Đông Dương', 3900000.00, 4, '2025-07-15', '2025-07-19', 10, '2025-06-29', 0.00, 0.00, NULL, NULL, 5, 'https://encrypted-tbn0.gstatic.com/licensed-image?q=tbn:ANd9GcR-c5GicqaigwosIFDVb-zV0kymslTs0phMONpsTgNXx60TJtTNU2ZpJXw3Q_oHYRGl_2uBH5joA01Q7ccQjGwBQrjaXUd3x1zSyQ_VLA', 'trongnuoc'),
(17, 'Tour Nha Trang - Đảo Bình Ba', 'Nha Trang', 'Biển xanh và hải sản Bình Ba', 3100000.00, 3, '2025-07-23', '2025-07-30', 12, '2025-06-29', 0.00, 0.00, NULL, NULL, 4, 'https://happytour.com.vn/public/userfiles/tour/40/binhba1.jpg', 'trongnuoc'),
(18, 'Tour Đà Lạt - Langbiang', 'Đà Lạt', 'Khám phá thành phố sương mù', 3200000.00, 3, '2025-07-20', '2025-07-23', 14, '2025-06-29', 0.00, 0.00, NULL, NULL, 4, 'https://encrypted-tbn3.gstatic.com/licensed-image?q=tbn:ANd9GcQBK8_IbRQdPMM218MzghxyzYhzOdM2OvcE_JpKwfdkiFak9AikCnUHXRzvd3j2V88m_ij2g5J7UGO6lpwM2hbb3cC3_zZFb69vdXbMwQ', 'trongnuoc'),
(19, 'Tour Hà Nội - Paris', 'Paris', 'Thành phố tình yêu', 4000.00, 7, '2025-07-23', '2025-07-30', 55, '2025-07-01', 50.00, 0.00, NULL, NULL, 5, 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4b/La_Tour_Eiffel_vue_de_la_Tour_Saint-Jacques%2C_Paris_ao%C3%BBt_2014_%282%29.jpg/330px-La_Tour_Eiffel_vue_de_la_Tour_Saint-Jacques%2C_Paris_ao%C3%BBt_2014_%282%29.jpg', 'nuocngoai'),
(20, 'Tour Sài Gòn - Singapore', 'Singapore', 'Đảo quốc sư tử', 12000000.00, 4, '2025-07-18', '2025-07-22', 0, '2025-06-29', 0.00, 0.00, NULL, NULL, 4, 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/10/A_Night_Perspective_on_the_Singapore_Merlion_%288347645113%29.jpg/250px-A_Night_Perspective_on_the_Singapore_Merlion_%288347645113%29.jpg', 'nuocngoai'),
(21, 'Tour Đà Nẵng - Seoul', 'Seoul', 'Trải nghiệm Hàn Quốc', 18000000.00, 5, '2025-07-25', '2025-07-30', 1, '2025-06-29', 0.00, 0.00, NULL, NULL, 5, 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f5/%EA%B2%BD%EB%B3%B5%EA%B6%81_%EA%B4%91%ED%99%94%EB%AC%B8_%28cropped%29.jpg/330px-%EA%B2%BD%EB%B3%B5%EA%B6%81_%EA%B4%91%ED%99%94%EB%AC%B8_%28cropped%29.jpg', 'nuocngoai'),
(27, 'Tour Nhật Bản ', 'Tokyo', '<p>Xứ sở của hoa anh đào</p>', 30000000.00, 0, '2025-07-28', '2025-08-03', 0, '2025-06-30', 0.00, 28000000.00, NULL, NULL, 5, 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b2/Skyscrapers_of_Shinjuku_2009_January.jpg/1920px-Skyscrapers_of_Shinjuku_2009_January.jpg', 'nuocngoai'),
(28, 'Tour Huế - Mỹ', 'Hoa Kỳ', 'Xứ sở cờ hoa, nhiều cảnh đẹp', 2000000.00, 5, '2025-07-31', '2025-08-05', 29, '2025-07-07', 98.00, 0.00, NULL, NULL, 5, 'https://hanoitourist.vn/sites/default/files/inline-images/phan-duoc-tuong-Nu-than-Tu-do-3.jpg', 'nuocngoai'),
(29, 'Tour Campuchia', 'Phnôm Pênh', '<p>Đi Cam gì kh&ocirc;ng người đẹp</p>\r\n', 2000000.00, 5, '2025-07-31', '2025-08-04', 84, '2025-07-09', 0.00, 0.00, NULL, NULL, 2, 'unnamed.jpg', 'nuocngoai'),
(50, 'Khám Phá Hà Giang', 'Hà Giang', 'Chinh phục Mã Pí Lèng và cột cờ Lũng Cú', 4890000.00, 4, '2025-10-03', '2025-10-06', 20, '2025-07-10', 0.00, 0.00, NULL, NULL, 5, 'https://images.pexels.com/photos/10784919/pexels-photo-10784919.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1', 'trongnuoc'),
(51, 'Du Thuyền Hạ Long', 'Hạ Long', '<p>Nghỉ dưỡng 5 sao tr&ecirc;n vịnh di sản UNESCO</p>\r\n', 3550000.00, 2, '2025-09-15', '2025-09-16', 18, '2025-07-10', 0.00, 0.00, NULL, NULL, 5, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQnukWf3KN9fHsW-y8qKxJbLJsTUysrgnm2SA&s', 'trongnuoc'),
(53, 'Hành Trình Di Sản Miền Trung', 'Huế - Đà Nẵng - Hội An', '<p>Kh&aacute;m ph&aacute; Cố Đ&ocirc; Huế, Phố Cổ Hội An v&agrave; th&agrave;nh phố Đ&agrave; Nẵng</p>\r\n', 5600000.00, 5, '2025-09-25', '2025-09-29', 22, '2025-07-10', 0.00, 0.00, NULL, NULL, 5, 'https://static.hotdeal.vn/images/1722/1721729/60x60/369560-tour-hanh-trinh-di-san-mien-trung-6n5d-di-xe-ve-may-bay.jpg', 'trongnuoc'),
(54, 'Thiên Đường Biển Phú Quốc', 'Phú Quốc', 'Nghỉ dưỡng tại đảo ngọc, lặn ngắm san hô', 4200000.00, 4, '2025-10-10', '2025-10-13', 30, '2025-07-10', 10.00, 420000.00, '2025-07-15', '2025-08-15', 5, 'https://images.pexels.com/photos/10329712/pexels-photo-10329712.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1', 'trongnuoc'),
(55, 'Về Miền Tây Sông Nước', 'Cần Thơ - Mỹ Tho', '<p>Trải nghiệm chợ nổi C&aacute;i Răng v&agrave; miệt vườn trĩu quả</p>\r\n', 2500000.00, 3, '2025-10-17', '2025-10-19', 28, '2025-07-10', 0.00, 0.00, NULL, NULL, 4, 'https://lh3.googleusercontent.com/gps-cs-s/AC9h4nobGanwbwZv73bSxuzLNIXkw1blfT9qM2f2dTfog7HY8fjG2OcSAowWc5EPzxFT-OVSHFjqtOHXvT3HTFbLi3Ial2ZCPhEC13OFcaKmMDlyWGIaVobBXAtsMyo0dZvJOFhYE23I=w675-h390-n-k-no', 'trongnuoc'),
(56, 'Kỳ Vĩ Phong Nha - Kẻ Bàng', 'Quảng Bình', 'Thám hiểm hệ thống hang động độc đáo nhất thế giới', 3900000.00, 3, '2025-09-28', '2025-09-30', 15, '2025-07-10', 0.00, 0.00, NULL, NULL, 5, 'https://images.pexels.com/photos/974314/pexels-photo-974314.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1', 'trongnuoc'),
(57, 'Sắc Màu Tây Bắc', 'Mộc Châu - Sơn La', '<p>Dạo chơi tr&ecirc;n đồi ch&egrave; tr&aacute;i tim, săn m&acirc;y T&agrave; X&ugrave;a</p>\r\n', 3150000.00, 3, '2025-11-07', '2025-11-09', 20, '2025-07-10', 0.00, 0.00, NULL, NULL, 4, 'https://lh3.googleusercontent.com/gps-cs-s/AC9h4npL0L-YfaTNL1otURx53lSRc9u-xuKPwlbq5l5y68bfbmPj5HaIT-m-LnW9hwAqgjTYA0oKRgvHEHzYD1wMiGvxyxSm1I8e-ILDudwze3ZSNEPW6UAQbLZLu5oFKo8qppEoUh8l=w675-h390-n-k-no', 'trongnuoc'),
(58, 'Biển Gọi Quy Nhơn', 'Quy Nhơn - Phú Yên', '<p>Kh&aacute;m ph&aacute; Kỳ Co, Eo Gi&oacute; v&agrave; Ghềnh Đ&aacute; Dĩa</p>\r\n', 3400000.00, 4, '2025-09-12', '2025-09-15', 24, '2025-07-10', 0.00, 0.00, NULL, NULL, 4, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTSy79srjuy8z7wlujiZ062SFDJ9U-yJJIIOg&s', 'trongnuoc'),
(59, 'Cao Nguyên Đà Lạt', 'Đà Lạt', '<p>Th&agrave;nh phố ng&agrave;n hoa mộng mơ v&agrave; l&atilde;ng mạn</p>\r\n', 3200000.00, 3, '2025-11-14', '2025-11-16', 30, '2025-07-10', 0.00, 0.00, NULL, NULL, 4, 'https://lh3.googleusercontent.com/gps-cs-s/AC9h4nrQmC3hRPph0CrUhkHnEMS_LkLk16Ch07tUIyu-Ly8yeuJfLf2UhmipMM1Nnm-Kpm1l-vYx6IsKkrNtrTcFBo8rRq4h9vWnUsKdRFn_yE-YfCurt3_xHwmjr8CmhNDPqGTG74MQ=w675-h390-n-k-no', 'trongnuoc'),
(60, 'Chùa Vàng Thái Lan', 'Bangkok - Pattaya', '<p>Thi&ecirc;n đường mua sắm v&agrave; văn h&oacute;a t&acirc;m linh đặc sắc</p>\r\n', 7990000.00, 5, '2025-10-05', '2025-10-09', 15, '2025-07-10', 0.00, 0.00, NULL, NULL, 4, 'https://www.tsttourist.com/vnt_upload/news/03_2024/TSTtourist_6_ngoi_chua_vang_thai_lan_duoc_nhieu_du_khach_yeu_thich_2.jpg', 'nuocngoai'),
(61, 'Đảo Thần Bali', 'Bali, Indonesia', 'Hòn đảo của những ngôi đền, bãi biển và ruộng bậc thang', 16500000.00, 5, '2025-10-20', '2025-10-24', 12, '2025-07-10', 0.00, 0.00, NULL, NULL, 5, 'https://images.pexels.com/photos/3225517/pexels-photo-3225517.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1', 'nuocngoai'),
(62, 'Mùa Thu Hàn Quốc', 'Seoul - Nami - Everland', '<p>L&atilde;ng mạn đảo Nami v&agrave; vui chơi tại c&ocirc;ng vi&ecirc;n Everland</p>\r\n', 18900000.00, 5, '2025-10-26', '2025-10-30', 10, '2025-07-10', 0.00, 0.00, NULL, NULL, 5, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRxYwKe3um0pYtChvV3VfwoKQkC_h62dj6BDQ&s', 'nuocngoai'),
(63, 'Cung Đường Vàng Nhật Bản', 'Tokyo - Kyoto - Osaka', '<p>Trải nghiệm t&agrave;u Shinkansen, n&uacute;i Ph&uacute; Sĩ v&agrave; di sản Kyoto</p>\r\n', 35800000.00, 6, '2025-11-02', '2025-11-07', 8, '2025-07-10', 0.00, 0.00, NULL, NULL, 5, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRFWTidJUL282tL6a9zi3KwhYLTUOnBh4L7ZQ&s', 'nuocngoai'),
(64, 'Khám Phá Singapore', 'Singapore', '<p>Đảo quốc sư tử hiện đại với Gardens by the Bay</p>\r\n', 9300000.00, 4, '2025-11-10', '2025-11-13', 20, '2025-07-10', 0.00, 0.00, NULL, NULL, 4, 'https://www.startravel.vn/upload/tintuc/SINGA_29_7_2024_13_48_37.jpg', 'nuocngoai'),
(65, 'Kinh Đô Ánh Sáng Paris', 'Paris, Pháp', '<p>Tham quan th&aacute;p Eiffel, bảo t&agrave;ng Louvre v&agrave; đi du thuyền s&ocirc;ng Seine</p>\r\n', 55000000.00, 7, '2025-09-30', '2025-10-06', 10, '2025-07-10', 5.00, 2750000.00, '2025-07-15', '2025-08-30', 5, 'https://baodongnai.com.vn/file/e7837c02876411cd0187645a2551379f/dataimages/201801/original/images2100904_13_Thay.jpg', 'nuocngoai'),
(66, 'Thành Rome Vĩnh Hằng', 'Rome, Ý', 'Khám phá Đấu trường La Mã Colosseum và Vatican', 62000000.00, 7, '2025-10-12', '2025-10-18', 9, '2025-07-10', 0.00, 0.00, NULL, NULL, 5, 'https://images.pexels.com/photos/532263/pexels-photo-532263.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1', 'nuocngoai'),
(67, 'Hiện Đại và Xa Hoa Dubai', 'Dubai, UAE', 'Ngắm nhìn tòa tháp Burj Khalifa và trải nghiệm sa mạc Safari', 33500000.00, 5, '2025-11-20', '2025-11-24', 14, '2025-07-10', 0.00, 0.00, NULL, NULL, 5, 'https://images.pexels.com/photos/2044434/pexels-photo-2044434.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1', 'nuocngoai'),
(68, 'Khám Phá Sydney', 'Sydney, Úc', 'Tham quan Nhà hát Opera Con Sò và cầu cảng Sydney', 42000000.00, 6, '2025-11-25', '2025-11-30', 11, '2025-07-10', 0.00, 0.00, NULL, NULL, 5, 'https://images.pexels.com/photos/1878293/pexels-photo-1878293.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1', 'nuocngoai'),
(69, 'Bờ Tây Hoa Kỳ', 'Los Angeles - Las Vegas', '<p>Kh&aacute;m ph&aacute; Hollywood, Universal Studios v&agrave; th&agrave;nh phố kh&ocirc;ng ngủ Las Vegas</p>\r\n', 69000000.00, 8, '2025-12-01', '2025-12-08', 10, '2025-07-10', 0.00, 0.00, NULL, NULL, 5, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTyMEv0gkq8BGWop2ddm8sWkrQdqtqijQPTuQ&s', 'nuocngoai'),
(70, 'Tour du dịch điện quang', 'Campuchia', '<p>Đi cam đi vui lắm á</p>\r\n', 2000000.00, 5, '2026-04-09', '2026-04-14', 38, '2025-07-10', 0.00, 0.00, NULL, NULL, 5, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT-BrrNZ3BcvbO3UCsZEdr19K2y9jednyzi2g&s', 'nuocngoai');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `isVerified` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `isVerified`, `created_at`, `role_id`) VALUES
(9, 'Admin', 'hothanhthien119@gmail.com', '0000000000', '$2y$10$A5P/QHER1KeNmiXozQpSAOKIfKRV.GIsdYh3ZXeF.y85fvSELEE2e', 0, '2025-06-14 16:13:57', 1),
(10, 'test', 'test@gmail.com', '', '$2y$10$b.pg6HR3FMgtnu4QWMC2Zuvwcn5mnFjB/VvKYuBCdfscDgIXBTu36', 0, '2025-06-14 17:02:59', 3),
(14, 'a', 'ak@gmail.com', '0375227764', '$2y$10$WCdr/ZxXMr4xHP4KguRnW.T61CW0/AHCZjTCdUBs12OrdcQ9FHtRy', 0, '2025-06-15 16:09:53', 3),
(15, 'test@gmail.com', 'hothanhthien2k5@gmail.com', 'test@gmail.com', '$2y$10$2yo1oJWrKRVRoi4gfmdeu./dG4nhoTLP7td2MWnpIpAy/iEQ9gaye', 0, '2025-06-30 15:44:13', 3),
(16, 'User One', 'user1@example.com', '0910000001', '$2y$10$E.mK2F5d8v3q8Y9zJ7k4n.wG2R6x.3B9Q1I7sR0j.4X5cM8l.1kG', 0, '2025-07-10 10:24:03', 3),
(17, 'User Two', 'user2@example.com', '0910000002', '$2y$10$E.mK2F5d8v3q8Y9zJ7k4n.wG2R6x.3B9Q1I7sR0j.4X5cM8l.1kG', 0, '2025-07-10 10:24:03', 3),
(18, 'User Three', 'user3@example.com', '0910000003', '$2y$10$E.mK2F5d8v3q8Y9zJ7k4n.wG2R6x.3B9Q1I7sR0j.4X5cM8l.1kG', 0, '2025-07-10 10:24:03', 3),
(19, 'User Four', 'user4@example.com', '0910000004', '$2y$10$E.mK2F5d8v3q8Y9zJ7k4n.wG2R6x.3B9Q1I7sR0j.4X5cM8l.1kG', 0, '2025-07-10 10:24:03', 3),
(20, 'User Five', 'user5@example.com', '0910000005', '$2y$10$E.mK2F5d8v3q8Y9zJ7k4n.wG2R6x.3B9Q1I7sR0j.4X5cM8l.1kG', 0, '2025-07-10 10:24:03', 3),
(21, 'User Six', 'user6@example.com', '0910000006', '$2y$10$E.mK2F5d8v3q8Y9zJ7k4n.wG2R6x.3B9Q1I7sR0j.4X5cM8l.1kG', 0, '2025-07-10 10:24:03', 3),
(22, 'User Seven', 'user7@example.com', '0910000007', '$2y$10$E.mK2F5d8v3q8Y9zJ7k4n.wG2R6x.3B9Q1I7sR0j.4X5cM8l.1kG', 0, '2025-07-10 10:24:03', 3),
(23, 'User Eight', 'user8@example.com', '0910000008', '$2y$10$E.mK2F5d8v3q8Y9zJ7k4n.wG2R6x.3B9Q1I7sR0j.4X5cM8l.1kG', 0, '2025-07-10 10:24:03', 3),
(24, 'User Nine', 'user9@example.com', '0910000009', '$2y$10$E.mK2F5d8v3q8Y9zJ7k4n.wG2R6x.3B9Q1I7sR0j.4X5cM8l.1kG', 0, '2025-07-10 10:24:03', 3),
(25, 'User Ten', 'user10@example.com', '0910000010', '$2y$10$E.mK2F5d8v3q8Y9zJ7k4n.wG2R6x.3B9Q1I7sR0j.4X5cM8l.1kG', 0, '2025-07-10 10:24:03', 3),
(26, 'User Eleven', 'user11@example.com', '0910000011', '$2y$10$E.mK2F5d8v3q8Y9zJ7k4n.wG2R6x.3B9Q1I7sR0j.4X5cM8l.1kG', 0, '2025-07-10 12:32:15', 3),
(27, 'User Twelve', 'user12@example.com', '0910000012', '$2y$10$E.mK2F5d8v3q8Y9zJ7k4n.wG2R6x.3B9Q1I7sR0j.4X5cM8l.1kG', 0, '2025-07-10 12:32:15', 3),
(28, 'User Thirteen', 'user13@example.com', '0910000013', '$2y$10$E.mK2F5d8v3q8Y9zJ7k4n.wG2R6x.3B9Q1I7sR0j.4X5cM8l.1kG', 0, '2025-07-10 12:32:15', 3),
(29, 'User Fourteen', 'user14@example.com', '0910000014', '$2y$10$E.mK2F5d8v3q8Y9zJ7k4n.wG2R6x.3B9Q1I7sR0j.4X5cM8l.1kG', 0, '2025-07-10 12:32:15', 3),
(30, 'User Fifteen', 'user15@example.com', '0910000015', '$2y$10$E.mK2F5d8v3q8Y9zJ7k4n.wG2R6x.3B9Q1I7sR0j.4X5cM8l.1kG', 0, '2025-07-10 12:32:15', 3),
(31, 'User Sixteen', 'user16@example.com', '0910000016', '$2y$10$E.mK2F5d8v3q8Y9zJ7k4n.wG2R6x.3B9Q1I7sR0j.4X5cM8l.1kG', 0, '2025-07-10 12:32:15', 3),
(32, 'User Seventeen', 'user17@example.com', '0910000017', '$2y$10$E.mK2F5d8v3q8Y9zJ7k4n.wG2R6x.3B9Q1I7sR0j.4X5cM8l.1kG', 0, '2025-07-10 12:32:15', 3),
(33, 'User Eighteen', 'user18@example.com', '0910000018', '$2y$10$E.mK2F5d8v3q8Y9zJ7k4n.wG2R6x.3B9Q1I7sR0j.4X5cM8l.1kG', 0, '2025-07-10 12:32:15', 3),
(34, 'User Nineteen', 'user19@example.com', '0910000019', '$2y$10$E.mK2F5d8v3q8Y9zJ7k4n.wG2R6x.3B9Q1I7sR0j.4X5cM8l.1kG', 0, '2025-07-10 12:32:15', 3),
(35, 'User Twenty', 'user20@example.com', '0910000020', '$2y$10$E.mK2F5d8v3q8Y9zJ7k4n.wG2R6x.3B9Q1I7sR0j.4X5cM8l.1kG', 0, '2025-07-10 12:32:15', 3),
(36, 'User Twenty-one', 'user21@example.com', '0910000021', '$2y$10$E.mK2F5d8v3q8Y9zJ7k4n.wG2R6x.3B9Q1I7sR0j.4X5cM8l.1kG', 0, '2025-07-10 12:35:17', 3),
(37, 'User Twenty-two', 'user22@example.com', '0910000022', '$2y$10$E.mK2F5d8v3q8Y9zJ7k4n.wG2R6x.3B9Q1I7sR0j.4X5cM8l.1kG', 0, '2025-07-10 12:35:17', 3),
(38, 'User Twenty-three', 'user23@example.com', '0910000023', '$2y$10$E.mK2F5d8v3q8Y9zJ7k4n.wG2R6x.3B9Q1I7sR0j.4X5cM8l.1kG', 0, '2025-07-10 12:35:17', 3),
(39, 'User Twenty-four', 'user24@example.com', '0910000024', '$2y$10$E.mK2F5d8v3q8Y9zJ7k4n.wG2R6x.3B9Q1I7sR0j.4X5cM8l.1kG', 0, '2025-07-10 12:35:17', 3),
(40, 'User Twenty-five', 'user25@example.com', '0910000025', '$2y$10$E.mK2F5d8v3q8Y9zJ7k4n.wG2R6x.3B9Q1I7sR0j.4X5cM8l.1kG', 0, '2025-07-10 12:35:17', 3);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `auth_tokens`
--
ALTER TABLE `auth_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Chỉ mục cho bảng `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Chỉ mục cho bảng `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `tb_transactions`
--
ALTER TABLE `tb_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `tours`
--
ALTER TABLE `tours`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `auth_tokens`
--
ALTER TABLE `auth_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT cho bảng `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `tb_transactions`
--
ALTER TABLE `tb_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `tours`
--
ALTER TABLE `tours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `auth_tokens`
--
ALTER TABLE `auth_tokens`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
