-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 01 Jun 2026 pada 15.34
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Database siap import untuk project tokoskincare
CREATE DATABASE IF NOT EXISTS `toko_skincare_kosmetik` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `toko_skincare_kosmetik`;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `reviews`;
DROP TABLE IF EXISTS `shipments`;
DROP TABLE IF EXISTS `payments`;
DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `cart_items`;
DROP TABLE IF EXISTS `carts`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `promos`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `brands`;
DROP TABLE IF EXISTS `users`;
SET FOREIGN_KEY_CHECKS = 1;


--
-- Database: `toko_skincare_kosmetik`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `brands`
--

CREATE TABLE `brands` (
  `id_brand` bigint(20) UNSIGNED NOT NULL,
  `nama_brand` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `carts`
--

CREATE TABLE `carts` (
  `id_cart` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `carts`
--

INSERT INTO `carts` (`id_cart`, `id_user`, `created_at`) VALUES
(1, 1, '2026-05-04 08:56:36');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cart_items`
--

CREATE TABLE `cart_items` (
  `id_cart_item` bigint(20) UNSIGNED NOT NULL,
  `id_cart` bigint(20) UNSIGNED NOT NULL,
  `id_product` bigint(20) UNSIGNED NOT NULL,
  `jumlah` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `categories`
--

CREATE TABLE `categories` (
  `id_kategori` bigint(20) UNSIGNED NOT NULL,
  `nama_kategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `id_order` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `tanggal_order` datetime NOT NULL DEFAULT current_timestamp(),
  `total_harga` decimal(12,2) NOT NULL,
  `status_order` enum('pending','dibayar','dikirim','selesai','batal') NOT NULL DEFAULT 'pending',
  `nama` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `ongkir` int(11) DEFAULT NULL,
  `kurir` varchar(50) DEFAULT NULL,
  `layanan` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `orders`
--

INSERT INTO `orders` (`id_order`, `id_user`, `tanggal_order`, `total_harga`, `status_order`, `nama`, `email`, `alamat`, `ongkir`, `kurir`, `layanan`, `created_at`) VALUES
(26, 1, '2026-05-05 00:47:04', 162000.00, 'dibayar', '23-051 MOCH LINTANG AKBAR PERDANA', 'mochlintang15@gmail.com', 'jakarta', 12000, 'J&T', 'REG', '2026-05-04 19:47:04'),
(34, 1, '2026-06-01 00:36:21', 610000.00, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-01 00:36:21'),
(35, 1, '2026-06-01 01:08:24', 610000.00, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-01 01:08:24'),
(36, 1, '2026-06-01 12:50:04', 60000.00, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-01 12:50:04'),
(37, 1, '2026-06-01 13:23:00', 72000.00, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-01 13:23:00'),
(38, 1, '2026-06-01 14:45:48', 60000.00, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-01 14:45:48'),
(39, 1, '2026-06-01 14:52:03', 71000.00, 'pending', 'lintang', 'mochlintang15@gmail.com', 'jakarta', 21000, 'SiCepat', 'BEST', '2026-06-01 09:52:03'),
(40, 1, '2026-06-01 14:57:20', 222000.00, 'pending', 'suga', 'mochlintang15@gmail.com', 'jln kusuma bangsa nomer 3', 22000, 'J&T', 'YES', '2026-06-01 09:57:20'),
(41, 1, '2026-06-01 15:30:43', 160000.00, 'pending', '23-051 MOCH LINTANG AKBAR PERDANA', 'mochlintang15@gmail.com', 'jakarta', 10000, 'JNE', 'REG', '2026-06-01 10:30:43'),
(42, 1, '2026-06-01 15:32:07', 320000.00, 'pending', 'radik', 'mochlintang15@gmail.com', 'jakarta', 20000, 'JNE', 'YES', '2026-06-01 10:32:07'),
(43, 1, '2026-06-01 20:07:21', 170000.00, 'pending', '23-051 MOCH LINTANG AKBAR PERDANA', 'mochlintang15@gmail.com', 'jakarta', 20000, 'JNE', 'YES', '2026-06-01 15:07:21'),
(44, 1, '2026-06-01 20:17:29', 161000.00, 'pending', '23-051 MOCH LINTANG AKBAR PERDANA', 'mochlintang15@gmail.com', 'kamal', 11000, 'SiCepat', 'REG', '2026-06-01 15:17:29');

-- --------------------------------------------------------

--
-- Struktur dari tabel `order_items`
--

CREATE TABLE `order_items` (
  `id_order_item` bigint(20) UNSIGNED NOT NULL,
  `id_order` bigint(20) UNSIGNED NOT NULL,
  `id_product` bigint(20) UNSIGNED NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_satuan` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `order_items`
--

INSERT INTO `order_items` (`id_order_item`, `id_order`, `id_product`, `jumlah`, `harga_satuan`) VALUES
(31, 26, 1, 1, 150000.00),
(39, 34, 1, 4, 150000.00),
(40, 35, 1, 4, 150000.00),
(41, 36, 4, 1, 50000.00),
(42, 37, 4, 1, 50000.00),
(43, 38, 4, 1, 50000.00),
(44, 39, 4, 1, 50000.00),
(45, 40, 4, 4, 50000.00),
(46, 41, 1, 1, 150000.00),
(47, 42, 1, 2, 150000.00),
(48, 43, 1, 1, 150000.00),
(49, 44, 1, 1, 150000.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `payments`
--

CREATE TABLE `payments` (
  `id_payment` bigint(20) UNSIGNED NOT NULL,
  `id_order` bigint(20) UNSIGNED NOT NULL,
  `metode_pembayaran` varchar(50) NOT NULL,
  `status_pembayaran` enum('pending','lunas','gagal') NOT NULL DEFAULT 'pending',
  `tanggal_bayar` datetime NOT NULL,
  `nomor_pembayaran` varchar(50) DEFAULT NULL,
  `bukti_transfer` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `payments`
--

INSERT INTO `payments` (`id_payment`, `id_order`, `metode_pembayaran`, `status_pembayaran`, `tanggal_bayar`, `nomor_pembayaran`, `bukti_transfer`) VALUES
(24, 26, 'Transfer', 'lunas', '2026-05-04 19:47:04', '5260 1234 5678 9012', NULL),
(32, 34, 'Transfer', 'pending', '2026-06-01 00:36:21', '087757295631', '1780250209_Cuplikan layar 2023-10-25 210806.png'),
(33, 35, 'Gopay', 'pending', '2026-06-01 01:08:24', '0877757295631', '1780250910_BBBBB.png'),
(34, 36, 'Gopay', 'pending', '2026-06-01 12:50:04', '087757295631', '1780293011_BBBBB.png'),
(35, 37, 'Dana', 'pending', '2026-06-01 13:23:00', '087757295631', NULL),
(36, 38, 'Dana', 'pending', '2026-06-01 14:45:48', '087757295631', NULL),
(37, 39, 'Gopay', 'pending', '2026-06-01 14:52:03', '082345678321', '1780300543_Screenshot 2026-06-01 144614.png'),
(38, 40, 'OVO', 'pending', '2026-06-01 14:57:20', '087757295631', '1780300647_BBBBB.png'),
(39, 41, 'Gopay', 'pending', '2026-06-01 15:30:43', '087757295631', '1780302648_BBBBB.png'),
(40, 42, 'OVO', 'pending', '2026-06-01 15:32:07', '087757295631', '1780302744_BBBBB.png'),
(41, 43, 'Gopay', 'pending', '2026-06-01 20:07:21', '087757295631', '1780319252_BBBBB.png'),
(42, 44, 'Transfer', 'pending', '2026-06-01 20:17:29', '087757295631', '1780319875_TTTTT.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id_product` bigint(20) UNSIGNED NOT NULL,
  `nama_produk` varchar(150) NOT NULL,
  `deskripsi` text NOT NULL,
  `harga` decimal(12,2) NOT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `id_kategori` bigint(20) UNSIGNED DEFAULT NULL,
  `id_brand` bigint(20) UNSIGNED DEFAULT NULL,
  `gambar` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id_product`, `nama_produk`, `deskripsi`, `harga`, `stok`, `id_kategori`, `id_brand`, `gambar`, `created_at`, `updated_at`) VALUES
(1, 'Serum', 'Bagus', 150000.00, 10, NULL, NULL, 'img.jpg', '2026-05-04 08:56:36', NULL),
(2, 'Serum Wajah', 'Bagus', 150000.00, 10, NULL, NULL, 'img.jpg', '2026-05-04 09:07:41', NULL),
(3, 'Serum Wajah', 'Mencerahkan kulit', 150000.00, 10, NULL, NULL, 'img.jpg', '2026-05-04 09:37:59', NULL),
(4, 'Facial Wash', 'Membersihkan wajah', 50000.00, 20, NULL, NULL, 'img.jpg', '2026-05-04 09:37:59', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `promos`
--

CREATE TABLE `promos` (
  `id_promo` bigint(20) UNSIGNED NOT NULL,
  `nama_promo` varchar(100) NOT NULL,
  `diskon` decimal(5,2) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `reviews`
--

CREATE TABLE `reviews` (
  `id_review` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `id_product` bigint(20) UNSIGNED NOT NULL,
  `komentar` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `rating` int(11) DEFAULT NULL CHECK (`rating` >= 1 and `rating` <= 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `shipments`
--

CREATE TABLE `shipments` (
  `id_shipment` bigint(20) UNSIGNED NOT NULL,
  `id_order` bigint(20) UNSIGNED NOT NULL,
  `alamat_pengiriman` text NOT NULL,
  `status_pengiriman` enum('diproses','dikirim','sampai') NOT NULL DEFAULT 'diproses',
  `tanggal_kirim` datetime NOT NULL,
  `tanggal_terima` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` bigint(11) UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `alamat` text NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `nama`, `email`, `password`, `role`, `alamat`, `no_hp`, `created_at`, `updated_at`) VALUES
(1, 'User Test', 'user@test.com', '123', 'user', 'Jakarta', '08123', '2026-05-04 08:56:36', NULL),
(2, 'Admin Toko', 'admin@test.com', '$2y$12$UDsSQ25KwJicMrt2zUoG6.ci9ZqvD7Oy8w27FWojwugVWL30Ghnma', 'admin', 'Jakarta', '081234567890', '2026-06-01 15:00:00', NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id_brand`);

--
-- Indeks untuk tabel `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id_cart`),
  ADD KEY `fk_carts_user` (`id_user`);

--
-- Indeks untuk tabel `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id_cart_item`),
  ADD KEY `fk_cart_items_cart` (`id_cart`),
  ADD KEY `fk_cart_items_product` (`id_product`);

--
-- Indeks untuk tabel `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id_order`),
  ADD KEY `fk_orders_user` (`id_user`);

--
-- Indeks untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id_order_item`),
  ADD KEY `fk_order_items_order` (`id_order`),
  ADD KEY `fk_order_items_product` (`id_product`);

--
-- Indeks untuk tabel `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id_payment`),
  ADD KEY `fk_payments_order` (`id_order`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id_product`),
  ADD KEY `fk_products_kategori` (`id_kategori`),
  ADD KEY `fk_products_brand` (`id_brand`);

--
-- Indeks untuk tabel `promos`
--
ALTER TABLE `promos`
  ADD PRIMARY KEY (`id_promo`);

--
-- Indeks untuk tabel `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id_review`),
  ADD KEY `fk_reviews_user` (`id_user`),
  ADD KEY `fk_reviews_product` (`id_product`);

--
-- Indeks untuk tabel `shipments`
--
ALTER TABLE `shipments`
  ADD PRIMARY KEY (`id_shipment`),
  ADD KEY `fk_shipments_order` (`id_order`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `brands`
--
ALTER TABLE `brands`
  MODIFY `id_brand` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `carts`
--
ALTER TABLE `carts`
  MODIFY `id_cart` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id_cart_item` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id_kategori` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id_order` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT untuk tabel `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id_order_item` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT untuk tabel `payments`
--
ALTER TABLE `payments`
  MODIFY `id_payment` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id_product` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `promos`
--
ALTER TABLE `promos`
  MODIFY `id_promo` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id_review` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `shipments`
--
ALTER TABLE `shipments`
  MODIFY `id_shipment` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `fk_carts_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `fk_cart_items_cart` FOREIGN KEY (`id_cart`) REFERENCES `carts` (`id_cart`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cart_items_product` FOREIGN KEY (`id_product`) REFERENCES `products` (`id_product`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_order` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_order_items_product` FOREIGN KEY (`id_product`) REFERENCES `products` (`id_product`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payments_order` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_brand` FOREIGN KEY (`id_brand`) REFERENCES `brands` (`id_brand`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_products_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `categories` (`id_kategori`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_product` FOREIGN KEY (`id_product`) REFERENCES `products` (`id_product`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reviews_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `shipments`
--
ALTER TABLE `shipments`
  ADD CONSTRAINT `fk_shipments_order` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
