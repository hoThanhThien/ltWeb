-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th6 30, 2025 lúc 06:10 AM
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
-- Cấu trúc bảng cho bảng `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `tour_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `otp_verifications`
--

CREATE TABLE `otp_verifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `otp_code` varchar(10) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `verified` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `method` varchar(50) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `qr_code_url` varchar(500) DEFAULT NULL,
  `is_paid` tinyint(1) DEFAULT 0,
  `paid_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(2, 'Staff'),
(3, 'Customer');

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
(12, 'Tour Hà Nội - Hạ Long', 'Hạ Long', 'Khám phá vịnh Hạ Long', 3500000.00, 3, '2025-07-01', '2025-07-03', 20, '2025-06-29', 0.00, 0.00, NULL, NULL, 4, 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/42/Ha_Long_2019_taken_by_DJI_FC220.jpg/330px-Ha_Long_2019_taken_by_DJI_FC220.jpg', 'trongnuoc'),
(13, 'Tour Sài Gòn - Phú Quốc', 'Phú Quốc', 'Thưởng thức biển đảo Phú Quốc', 4200000.00, 4, '2025-07-05', '2025-07-09', 25, '2025-06-29', 0.00, 0.00, NULL, NULL, 5, 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/ec/H%C3%B2n_Th%C6%A1m_cable_car_above_the_An_Th%E1%BB%9Bi_township.jpg/250px-H%C3%B2n_Th%C6%A1m_cable_car_above_the_An_Th%E1%BB%9Bi_township.jpg', 'trongnuoc'),
(14, 'Tour Đà Nẵng - Hội An', 'Hội An', 'Phố cổ Hội An lung linh', 2800000.00, 2, '2025-07-02', '2025-07-04', 15, '2025-06-29', 0.00, 0.00, NULL, NULL, 4, 'hoian.jpg', 'trongnuoc'),
(15, 'Tour Huế - Đà Nẵng', 'Huế', 'Khám phá kinh thành Huế', 2600000.00, 3, '2025-07-10', '2025-07-12', 18, '2025-06-29', 0.00, 0.00, NULL, NULL, 3, 'hue.jpg', 'trongnuoc'),
(16, 'Tour Sapa - Fansipan', 'Sapa', 'Chinh phục nóc nhà Đông Dương', 3900000.00, 4, '2025-07-15', '2025-07-19', 10, '2025-06-29', 0.00, 0.00, NULL, NULL, 5, 'sapa.jpg', 'trongnuoc'),
(17, 'Tour Nha Trang - Đảo Bình Ba', 'Nha Trang', 'Biển xanh và hải sản Bình Ba', 3100000.00, 3, '2025-07-08', '2025-07-11', 12, '2025-06-29', 0.00, 0.00, NULL, NULL, 4, 'nhatrang.jpg', 'trongnuoc'),
(18, 'Tour Đà Lạt - Langbiang', 'Đà Lạt', 'Khám phá thành phố sương mù', 3200000.00, 3, '2025-07-20', '2025-07-23', 15, '2025-06-29', 0.00, 0.00, NULL, NULL, 4, 'dalat.jpg', 'trongnuoc'),
(19, 'Tour Hà Nội - Paris', 'Paris', 'Thành phố tình yêu', 25000000.00, 7, '2025-07-05', '2025-07-12', 8, '2025-06-29', 0.00, 0.00, NULL, NULL, 5, 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4b/La_Tour_Eiffel_vue_de_la_Tour_Saint-Jacques%2C_Paris_ao%C3%BBt_2014_%282%29.jpg/330px-La_Tour_Eiffel_vue_de_la_Tour_Saint-Jacques%2C_Paris_ao%C3%BBt_2014_%282%29.jpg', 'nuocngoai'),
(20, 'Tour Sài Gòn - Singapore', 'Singapore', 'Đảo quốc sư tử', 12000000.00, 4, '2025-07-18', '2025-07-22', 10, '2025-06-29', 0.00, 0.00, NULL, NULL, 4, 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/10/A_Night_Perspective_on_the_Singapore_Merlion_%288347645113%29.jpg/250px-A_Night_Perspective_on_the_Singapore_Merlion_%288347645113%29.jpg', 'nuocngoai'),
(21, 'Tour Đà Nẵng - Seoul', 'Seoul', 'Trải nghiệm Hàn Quốc', 18000000.00, 5, '2025-07-25', '2025-07-30', 6, '2025-06-29', 0.00, 0.00, NULL, NULL, 5, 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f5/%EA%B2%BD%EB%B3%B5%EA%B6%81_%EA%B4%91%ED%99%94%EB%AC%B8_%28cropped%29.jpg/330px-%EA%B2%BD%EB%B3%B5%EA%B6%81_%EA%B4%91%ED%99%94%EB%AC%B8_%28cropped%29.jpg', 'nuocngoai'),
(27, 'Tour Nhật Bản 7 ngày', 'Tokyo', NULL, 30000000.00, 0, '2025-07-28', '2025-08-03', 0, '2025-06-30', 0.00, 28000000.00, NULL, NULL, 5, 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b2/Skyscrapers_of_Shinjuku_2009_January.jpg/1920px-Skyscrapers_of_Shinjuku_2009_January.jpg', 'nuocngoai');

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
(9, 'Admin', 'hothanhthien119@gmail.com', '0000000000', '$2y$10$INWoyvIejFBapYy05CXUeuz3/rhHijDlmcBUKIzkNqIlCxi3o.r9m', 0, '2025-06-14 16:13:57', 1),
(10, 'test', 'test@gmail.com', '', '$2y$10$b.pg6HR3FMgtnu4QWMC2Zuvwcn5mnFjB/VvKYuBCdfscDgIXBTu36', 0, '2025-06-14 17:02:59', NULL),
(14, 'a', 'ak@gmail.com', '0375227764', '$2y$10$WCdr/ZxXMr4xHP4KguRnW.T61CW0/AHCZjTCdUBs12OrdcQ9FHtRy', 0, '2025-06-15 16:09:53', 2);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Chỉ mục cho bảng `otp_verifications`
--
ALTER TABLE `otp_verifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Chỉ mục cho bảng `roles`
--
ALTER TABLE `roles`
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
-- AUTO_INCREMENT cho bảng `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `otp_verifications`
--
ALTER TABLE `otp_verifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `tours`
--
ALTER TABLE `tours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `otp_verifications`
--
ALTER TABLE `otp_verifications`
  ADD CONSTRAINT `otp_verifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
