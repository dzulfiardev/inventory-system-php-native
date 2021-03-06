-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 06 Mar 2021 pada 05.18
-- Versi server: 10.1.35-MariaDB
-- Versi PHP: 7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventory_system_test`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `brand`
--

CREATE TABLE `brand` (
  `brand_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `brand_name` varchar(255) NOT NULL,
  `brand_status` enum('active','inactive') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `brand`
--

INSERT INTO `brand` (`brand_id`, `category_id`, `brand_name`, `brand_status`) VALUES
(1, 1, 'IBM', 'active'),
(2, 1, 'Sony', 'active'),
(3, 1, 'HP', 'active'),
(4, 1, 'LG', 'active'),
(5, 1, 'Lenovo', 'active'),
(6, 1, 'Apple', 'inactive'),
(7, 1, 'Macintosh', 'active'),
(8, 1, 'Microsoft', 'active'),
(9, 2, 'Samsung', 'active'),
(10, 2, 'Xiaomi', 'active'),
(11, 2, 'Asus', 'active'),
(12, 2, 'Iphone', 'active'),
(13, 2, 'Lenovo', 'active'),
(14, 2, 'vivo', 'active'),
(15, 4, 'Lenovo', 'active'),
(16, 4, 'Asus', 'active'),
(17, 4, 'Sony', 'active');

-- --------------------------------------------------------

--
-- Struktur dari tabel `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_status` enum('active','inactive') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `category`
--

INSERT INTO `category` (`category_id`, `category_name`, `category_status`) VALUES
(1, 'Pc (Personal Computer)', 'active'),
(2, 'Smartphone', 'active'),
(3, 'Laptop', 'active'),
(4, 'Notebook', 'active'),
(5, 'TV', 'active'),
(6, 'Oven', 'active'),
(7, 'Lamp', 'active'),
(8, 'Speaker', 'active'),
(9, 'Lithium Battery', 'active');

-- --------------------------------------------------------

--
-- Struktur dari tabel `inventory_order`
--

CREATE TABLE `inventory_order` (
  `inventory_order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `inventory_order_total` double(10,2) NOT NULL,
  `inventory_order_date` date NOT NULL,
  `inventory_order_name` varchar(255) NOT NULL,
  `inventory_order_address` text NOT NULL,
  `payment_status` enum('cash','credit') NOT NULL,
  `inventory_order_status` varchar(255) NOT NULL,
  `inventory_order_created_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `inventory_order`
--

INSERT INTO `inventory_order` (`inventory_order_id`, `user_id`, `inventory_order_total`, `inventory_order_date`, `inventory_order_name`, `inventory_order_address`, `payment_status`, `inventory_order_status`, `inventory_order_created_date`) VALUES
(1, 29, 1606.00, '2021-03-05', 'Abdurrahman', 'Jl. Suratmo 223 Kembangarum, Semarang Barat, Semarang, Jawa Tengah, Indonesia ', 'credit', 'active', '2021-03-05');

-- --------------------------------------------------------

--
-- Struktur dari tabel `inventory_order_product`
--

CREATE TABLE `inventory_order_product` (
  `inventory_order_product_id` int(11) NOT NULL,
  `inventory_order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` double(10,2) NOT NULL,
  `tax` double(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `inventory_order_product`
--

INSERT INTO `inventory_order_product` (`inventory_order_product_id`, `inventory_order_id`, `product_id`, `quantity`, `price`, `tax`) VALUES
(4, 1, 4, 3, 130.00, 10.00),
(5, 1, 5, 1, 30.00, 10.00),
(6, 1, 6, 2, 520.00, 10.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `product_name` varchar(300) NOT NULL,
  `product_description` text NOT NULL,
  `product_quantity` int(11) NOT NULL,
  `product_unit` varchar(150) NOT NULL,
  `product_base_price` double(10,2) NOT NULL,
  `product_tax` decimal(4,2) NOT NULL,
  `product_minimum_order` double(10,2) NOT NULL,
  `product_enter_by` int(11) NOT NULL,
  `product_status` enum('active','inactive') NOT NULL,
  `product_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `product`
--

INSERT INTO `product` (`product_id`, `category_id`, `brand_id`, `product_name`, `product_description`, `product_quantity`, `product_unit`, `product_base_price`, `product_tax`, `product_minimum_order`, `product_enter_by`, `product_status`, `product_date`) VALUES
(1, 1, 3, 'PC HP G1101', 'Intel core i7, 4Gb Ram, SSD 500GB', 10, 'Pcs', 300.00, '10.00', 0.00, 2, 'inactive', '0000-00-00'),
(2, 4, 15, 'Lenvo A113-SM', 'AMD Ryzen 2xxx, 8GB Ram, 500GB SSD', 20, 'Pcs', 500.00, '12.00', 0.00, 1, 'active', '0000-00-00'),
(3, 2, 9, 'Samsung A20', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsa dicta, fugit aliquid, officiis. \r\nQuos ullam reiciendis cum libero atque, nulla nemo.', 15, 'Pcs', 140.00, '10.00', 0.00, 1, 'active', '0000-00-00'),
(4, 2, 9, 'Samsung A10', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsa dicta, fugit aliquid, officiis quos ullam reiciendis cum libero atque, nulla nemo.', 12, 'Pcs', 130.00, '10.00', 0.00, 1, 'active', '0000-00-00'),
(5, 2, 9, 'Samsung A20s', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsa dicta, fugit aliquid, officiis quos ullam reiciendis cum libero atque, nulla nemo.', 155, 'Pcs', 30.00, '10.00', 0.00, 1, 'active', '0000-00-00'),
(6, 1, 8, 'Microsoft PC-23131xx', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsa dicta, fugit aliquid, officiis quos ullam reiciendis cum libero atque, nulla nemo.', 20, 'Pcs', 520.00, '10.00', 0.00, 1, 'active', '0000-00-00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_detail`
--

CREATE TABLE `user_detail` (
  `user_id` int(11) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_type` enum('master','user') NOT NULL,
  `user_status` enum('active','inactive') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `user_detail`
--

INSERT INTO `user_detail` (`user_id`, `user_email`, `user_password`, `user_name`, `user_type`, `user_status`) VALUES
(1, 'dzulfikar.sauki@gmail.com', '$2y$10$9frEjCwBW0QUuDamhNbck.D938GiHfozk1gawD9KTujql/rB3jpRS', 'Dzulfikar Sauki', 'master', 'active'),
(2, 'zuni@gmail.com', '$2y$10$DsHKjAp4yvdASd.xLm0woujefFIg9h/0iWDM0NxqKX9wnF9DlyWu.', 'Zuni Anifah', 'master', 'active'),
(3, 'jafar@gmail.com', '$2y$10$9frEjCwBW0QUuDamhNbck.D938GiHfozk1gawD9KTujql/rB3jpRS', 'jafar', 'user', 'active'),
(27, 'wafamaulana@gmail.com', '$2y$10$UAZX/TAIoHNt6V99Z5f5k.gr4Pc2ALNIxPFu0nlsI89lqq3YHcL0m', 'Wafa Maulana', 'user', 'active'),
(28, 'kipli@gmail.com', '$2y$10$ESmGGvOqTfXK/m0gAAH/A.oAT7IlUe9RvRda6OdExK8fvRXQNFGkW', 'Kipli', 'user', 'inactive'),
(29, 'admin@gmail.com', '$2y$10$5cSGom5aRaV3j2bQ2e/4yuo1tVn8wwwLWHha0ptl.l3WbWEYMEuy.', 'Admin Test', 'master', 'active');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`brand_id`),
  ADD KEY `brand_category_id_foreign` (`category_id`);

--
-- Indeks untuk tabel `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indeks untuk tabel `inventory_order`
--
ALTER TABLE `inventory_order`
  ADD PRIMARY KEY (`inventory_order_id`),
  ADD KEY `inventory_order_user_id_cascade` (`user_id`);

--
-- Indeks untuk tabel `inventory_order_product`
--
ALTER TABLE `inventory_order_product`
  ADD PRIMARY KEY (`inventory_order_product_id`),
  ADD KEY `inventory_order_product_inventory_order_id_foreign` (`inventory_order_id`),
  ADD KEY `inventory_order_product_product_id_foreign` (`product_id`);

--
-- Indeks untuk tabel `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `product_brand_id_foreign` (`brand_id`),
  ADD KEY `product_category_id_foreign` (`category_id`);

--
-- Indeks untuk tabel `user_detail`
--
ALTER TABLE `user_detail`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `brand`
--
ALTER TABLE `brand`
  MODIFY `brand_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `inventory_order`
--
ALTER TABLE `inventory_order`
  MODIFY `inventory_order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `inventory_order_product`
--
ALTER TABLE `inventory_order_product`
  MODIFY `inventory_order_product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `user_detail`
--
ALTER TABLE `user_detail`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `brand`
--
ALTER TABLE `brand`
  ADD CONSTRAINT `brand_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `inventory_order`
--
ALTER TABLE `inventory_order`
  ADD CONSTRAINT `inventory_order_user_id_cascade` FOREIGN KEY (`user_id`) REFERENCES `user_detail` (`user_id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `inventory_order_product`
--
ALTER TABLE `inventory_order_product`
  ADD CONSTRAINT `inventory_order_product_inventory_order_id_foreign` FOREIGN KEY (`inventory_order_id`) REFERENCES `inventory_order` (`inventory_order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_order_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brand` (`brand_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
