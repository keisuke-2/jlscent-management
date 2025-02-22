-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 11, 2024 at 04:15 PM
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
-- Database: `finalproject`
--

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`cart_id`, `user_id`, `session_id`, `created_at`, `updated_at`) VALUES
(2, 14, NULL, '2024-12-03 05:52:57', '2024-12-03 05:52:57'),
(4, 12, '7ffb2a38j7lon4081iqmi2ueb2', '2024-12-03 17:15:56', '2024-12-03 17:15:56'),
(5, 19, 'sbss3b656nsm3sf7int5sojm5i', '2024-12-03 21:23:51', '2024-12-03 21:23:51');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `cart_item_id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`cart_item_id`, `cart_id`, `product_id`, `quantity`, `price`) VALUES
(54, 4, 14, 1, 560.00);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','shipped','completed','canceled') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `total_amount`, `status`) VALUES
(2, 12, 99.99, 'completed'),
(21, 12, 168.00, 'completed'),
(52, 12, 99.99, 'completed'),
(53, 12, 99.99, 'shipped'),
(54, 12, 850.00, 'shipped'),
(55, 12, 0.00, 'shipped'),
(56, 12, 99.99, 'pending'),
(57, 12, 290.00, 'pending'),
(58, 12, 580.00, 'pending'),
(59, 12, 659.99, 'pending'),
(60, 19, 267.99, 'pending'),
(61, 12, 336.00, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(3, 2, 1, 1, 99.99),
(13, 21, 12, 1, 168.00),
(21, 52, 1, 1, 99.99),
(22, 53, 1, 1, 99.99),
(23, 54, 14, 1, 560.00),
(24, 54, 15, 1, 290.00),
(25, 56, 1, 1, 99.99),
(26, 57, 15, 1, 290.00),
(27, 58, 15, 2, 290.00),
(28, 59, 1, 1, 99.99),
(29, 59, 14, 1, 560.00),
(30, 60, 1, 1, 99.99),
(31, 60, 12, 1, 168.00),
(32, 61, 12, 2, 168.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `productPic` longblob NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `category` enum('Perfume','Pomade') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `productPic`, `description`, `price`, `stock`, `category`) VALUES
(1, 'VALOR', 0x70726f647563745f7069632e6a706567, 'A gorgeous product.', 99.99, 20, 'Perfume'),
(11, 'SAMPLE', 0x706f6d6164652e6a706567, 'Sample only', 120.12, 6, 'Pomade'),
(12, 'SWI', 0x70726f647563745f7069632e6a706567, 'A woody aroma.', 168.00, 0, 'Perfume'),
(13, 'Sumpremacy', 0x706f6d6164652e6a706567, 'Vanilla with a oud wood scent.', 260.00, 2, 'Pomade'),
(14, 'Spicebomb', 0x70657266756d652e6a7067, 'Lavender and black pepper scent', 560.00, 1, 'Perfume'),
(15, 'Spicebomb 2.0', 0x70657266756d652e6a7067, 'Sample product', 290.00, 7, 'Perfume'),
(16, 'Perfume 1', 0x70657266756d652e6a7067, 'A great perfume.', 256.00, 4, 'Perfume');

-- --------------------------------------------------------

--
-- Table structure for table `revenue`
--

CREATE TABLE `revenue` (
  `revenue_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `recorded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `revenue`
--

INSERT INTO `revenue` (`revenue_id`, `order_id`, `amount`, `recorded_at`) VALUES
(1, 2, 99.99, '2024-12-02 16:25:43'),
(18, 21, 168.00, '2024-12-03 07:12:31'),
(49, 52, 99.99, '2024-12-03 17:16:05'),
(50, 53, 99.99, '2024-12-03 17:16:58'),
(51, 54, 850.00, '2024-12-03 20:18:09'),
(52, 55, 0.00, '2024-12-03 20:24:03'),
(53, 56, 99.99, '2024-12-03 20:28:45'),
(54, 57, 290.00, '2024-12-03 20:31:04'),
(55, 58, 580.00, '2024-12-03 20:37:47'),
(56, 59, 659.99, '2024-12-03 20:55:24'),
(57, 60, 267.99, '2024-12-03 21:24:32'),
(58, 61, 336.00, '2024-12-04 02:36:20');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `userPic` longblob NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_no` varchar(32) NOT NULL,
  `home_address` varchar(64) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','customer') NOT NULL DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `userPic`, `email`, `phone_no`, `home_address`, `password_hash`, `role`) VALUES
(11, 'sample', '', 'sample@email.com', '2147483647', '0', '$2y$10$nOlVE4AvZsWBaeczaLG64OZz/3A.onTR5q3XaDeOu9A97v6v6PQMu', 'customer'),
(12, 'customers', '', 'customer@email.com', '09937158215', 'Calumpit, Bulacan', '$2y$10$K6ZaUKlHhs4E8THiuavuLuVLjiHEiG.meKwDWjw2yBFwOvRE59C1.', 'customer'),
(13, 'admin123', '', 'admin@email.com', '0', '', '$2y$10$06CZSklTGBvsOjPQm5FjXu3KNT6Gf8SbZSmy8KpgV0kNfMJOVQAL.', 'admin'),
(14, 'isaac', '', 'isaac@gmail.com', '09937158215', '', '$2y$10$cxeOonluD9zIGNSrkIckveIMuEB/i88BEhRQ7xjIxjF8lFjG5ckAy', 'admin'),
(18, 'dummy123', '', 'dummy@email.com', '09217464918', 'Calumpit Bulacan', '$2y$10$nUsALOYGRKdjpY5XIhpE6eLou35GvW4/Nx7mpgJxq9lB8x68Wn2Wy', 'customer'),
(19, 'dummycustomer', '', 'dummy1@gmail.com', '09217464918', 'Calumpit Bulacan', '$2y$10$WPPrZOuiBy5Tu6b9O88s4ey2HyUNFk7fcot/z3hwrMtak.CRHXG5K', 'customer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`cart_item_id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `revenue`
--
ALTER TABLE `revenue`
  ADD PRIMARY KEY (`revenue_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `cart_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `revenue`
--
ALTER TABLE `revenue`
  MODIFY `revenue_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`cart_id`),
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `revenue`
--
ALTER TABLE `revenue`
  ADD CONSTRAINT `revenue_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
