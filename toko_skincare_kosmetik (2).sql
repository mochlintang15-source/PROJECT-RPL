-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 31 Bulan Mei 2026 pada 14.26
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

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
(1, 1, '2026-05-04 15:57:11', 300000.00, 'dibayar', NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-04 21:37:45'),
(2, 1, '2026-05-04 15:58:47', 0.00, 'dibayar', NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-04 21:37:45'),
(3, 1, '2026-05-04 15:58:58', 0.00, 'dibayar', NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-04 21:37:45'),
(4, 1, '2026-05-04 16:08:44', 150000.00, 'dibayar', NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-04 21:37:45'),
(5, 1, '2026-05-04 16:12:47', 150000.00, 'dibayar', NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-04 21:37:45'),
(8, 1, '2026-05-04 16:20:55', 50000.00, 'dibayar', '23-051 MOCH LINTANG AKBAR PERDANA', 'mochlintang15@gmail.com', 'jalan kamal', NULL, NULL, NULL, '2026-05-04 21:37:45'),
(9, 1, '2026-05-04 16:21:06', 150000.00, 'dibayar', '23-051 MOCH LINTANG AKBAR PERDANA', 'mochlintang15@gmail.com', 'jalan kamal', NULL, NULL, NULL, '2026-05-04 21:37:45'),
(10, 1, '2026-05-04 16:22:27', 150000.00, 'dibayar', 'bagus', 'bagus@gmail.com', 'surabaya', NULL, NULL, NULL, '2026-05-04 21:37:45'),
(11, 1, '2026-05-04 16:38:35', 50000.00, 'dibayar', 'lintangakbar321', 'budi@gmail.com', 'jakarta', NULL, NULL, NULL, '2026-05-04 21:37:45'),
(12, 1, '2026-05-04 16:58:33', 50000.00, 'dibayar', '23-051 MOCH LINTANG AKBAR PERDANA', 'mochlintang15@gmail.com', 'jakarta', NULL, NULL, NULL, '2026-05-04 21:37:45'),
(13, 1, '2026-05-04 17:02:15', 50000.00, 'dibayar', 'bagas', 'mochlintang15@gmail.com', 'jakarta', NULL, NULL, NULL, '2026-05-04 21:37:45'),
(14, 1, '2026-05-04 17:34:15', 50000.00, 'dibayar', 'jokotole', 'joko@gmail.com', 'jakarta', NULL, NULL, NULL, '2026-05-04 21:37:45'),
(15, 1, '2026-05-04 19:56:19', 450000.00, 'dibayar', '23-051 MOCH LINTANG AKBAR PERDANA', 'mochlintang15@gmail.com', 'jalan kamal', NULL, NULL, NULL, '2026-05-04 21:37:45'),
(16, 1, '2026-05-04 20:05:29', 1750000.00, 'dibayar', 'bagas', 'bagas@gmail.com', 'jalan kamal', NULL, NULL, NULL, '2026-05-04 21:37:45'),
(17, 1, '2026-05-04 20:06:28', 150000.00, 'dibayar', 'lintang', 'mochlintang15@gmail.com', 'jalan kamal', NULL, NULL, NULL, '2026-05-04 21:37:45'),
(18, 1, '2026-05-04 21:37:56', 912000.00, 'dibayar', 'gabriel', 'lintangakbar158@gmail.com', 'jln kusuma bangsa nomer 3', 12000, 'J&T', 'REG', '2026-05-04 16:37:56'),
(19, 1, '2026-05-04 21:43:49', 172000.00, 'dibayar', 'lintang', 'mochlintang15@gmail.com', 'jalan kamal', 22000, 'J&T', 'YES', '2026-05-04 16:43:49'),
(20, 1, '2026-05-04 22:01:24', 372000.00, 'dibayar', 'lintang', 'gabriel@gmail.com', 'jakarta', 22000, 'J&T', 'YES', '2026-05-04 17:01:24'),
(21, 1, '2026-05-04 22:08:40', 11000.00, 'dibayar', 'lintangakbar321', 'lintangakbar158@gmail.com', 'jakarta', 11000, 'SiCepat', 'REG', '2026-05-04 17:08:40'),
(22, 1, '2026-05-05 00:24:38', 460000.00, 'dibayar', 'lintang', 'mochlintang15@gmail.com', 'jakarta', 10000, 'JNE', 'REG', '2026-05-04 19:24:38'),
(23, 1, '2026-05-05 00:27:21', 160000.00, 'dibayar', 'lintang', 'mochlintang15@gmail.com', 'jakarta', 10000, 'JNE', 'REG', '2026-05-04 19:27:21'),
(24, 1, '2026-05-05 00:30:59', 460000.00, 'dibayar', 'lintang', 'mochlintang15@gmail.com', 'jalan kamal', 10000, 'JNE', 'REG', '2026-05-04 19:30:59'),
(25, 1, '2026-05-05 00:40:29', 621000.00, 'dibayar', '23-051 MOCH LINTANG AKBAR PERDANA', 'mochlintang15@gmail.com', 'kamal', 21000, 'SiCepat', 'BEST', '2026-05-04 19:40:29'),
(26, 1, '2026-05-05 00:47:04', 162000.00, 'dibayar', '23-051 MOCH LINTANG AKBAR PERDANA', 'mochlintang15@gmail.com', 'jakarta', 12000, 'J&T', 'REG', '2026-05-04 19:47:04'),
(27, 1, '2026-05-05 00:48:21', 160000.00, 'dibayar', 'bagas', 'mochlintang15@gmail.com', 'jakarta', 10000, 'JNE', 'REG', '2026-05-04 19:48:21'),
(28, 1, '2026-05-05 00:56:18', 160000.00, 'dibayar', 'lintang', 'mochlintang15@gmail.com', 'jakarta', 10000, 'JNE', 'REG', '2026-05-04 19:56:18'),
(29, 1, '2026-05-05 01:07:14', 160000.00, 'dibayar', 'lintang', 'mochlintang15@gmail.com', 'jakarta', 10000, 'JNE', 'REG', '2026-05-04 20:07:14'),
(30, 1, '2026-05-09 21:47:46', 162000.00, 'dibayar', 'lintang', 'mochlintang15@gmail.com', 'jakarta', 12000, 'J&T', 'REG', '2026-05-09 16:47:46');

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
(1, 1, 1, 2, 150000.00),
(2, 4, 2, 1, 150000.00),
(3, 5, 2, 1, 150000.00),
(6, 9, 2, 1, 150000.00),
(7, 10, 2, 1, 150000.00),
(8, 11, 4, 1, 50000.00),
(9, 12, 4, 1, 50000.00),
(10, 13, 4, 1, 50000.00),
(11, 14, 4, 1, 50000.00),
(12, 15, 4, 3, 50000.00),
(13, 15, 1, 2, 150000.00),
(14, 16, 2, 7, 150000.00),
(15, 16, 4, 11, 50000.00),
(16, 16, 1, 1, 150000.00),
(17, 17, 1, 1, 150000.00),
(18, 18, 1, 6, 150000.00),
(19, 19, 1, 1, 150000.00),
(20, 20, 1, 1, 150000.00),
(21, 20, 2, 1, 150000.00),
(22, 20, 4, 1, 50000.00),
(23, 22, 1, 2, 150000.00),
(24, 22, 2, 1, 150000.00),
(25, 23, 1, 1, 150000.00),
(26, 24, 1, 1, 150000.00),
(27, 24, 2, 1, 150000.00),
(28, 24, 3, 1, 150000.00),
(29, 25, 1, 2, 150000.00),
(30, 25, 2, 2, 150000.00),
(31, 26, 1, 1, 150000.00),
(32, 27, 1, 1, 150000.00),
(33, 28, 1, 1, 150000.00),
(34, 29, 1, 1, 150000.00),
(35, 30, 1, 1, 150000.00);

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
  `nomor_pembayaran` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `payments`
--

INSERT INTO `payments` (`id_payment`, `id_order`, `metode_pembayaran`, `status_pembayaran`, `tanggal_bayar`, `nomor_pembayaran`) VALUES
(1, 1, 'Kartu Kredit', 'lunas', '2026-05-04 15:57:11', NULL),
(2, 2, 'Kartu Kredit', 'lunas', '2026-05-04 15:58:47', NULL),
(3, 3, 'Kartu Kredit', 'lunas', '2026-05-04 15:58:58', NULL),
(4, 4, 'Gopay', 'lunas', '2026-05-04 16:08:44', NULL),
(5, 5, 'Gopay', 'lunas', '2026-05-04 16:12:47', NULL),
(8, 9, 'Gopay', 'lunas', '2026-05-04 16:21:06', NULL),
(9, 10, 'OVO', 'lunas', '2026-05-04 16:22:27', NULL),
(10, 11, 'Gopay', 'lunas', '2026-05-04 16:38:35', NULL),
(11, 13, 'Gopay', 'lunas', '2026-05-04 17:02:15', '0877757295631'),
(12, 14, 'OVO', 'lunas', '2026-05-04 17:34:15', '0823456731421'),
(13, 15, 'Gopay', 'lunas', '2026-05-04 19:56:19', '087757295631'),
(14, 16, 'Gopay', 'lunas', '2026-05-04 20:05:29', '082345678321'),
(15, 17, 'Gopay', 'lunas', '2026-05-04 20:06:28', '087757295631'),
(16, 18, 'Gopay', 'lunas', '2026-05-04 16:37:56', '087757295631'),
(17, 19, 'OVO', 'lunas', '2026-05-04 16:43:49', '087757295631'),
(18, 20, 'Gopay', 'lunas', '2026-05-04 17:01:24', '12342313131'),
(19, 21, 'Gopay', 'lunas', '2026-05-04 17:08:40', '888878686868'),
(20, 22, 'Gopay', 'lunas', '2026-05-04 19:24:38', '087757295631'),
(21, 23, 'Gopay', 'lunas', '2026-05-04 19:27:21', '098199183918'),
(22, 24, 'Dana', 'lunas', '2026-05-04 19:30:59', '087757295631'),
(23, 25, 'Transfer', 'lunas', '2026-05-04 19:40:29', '5260 1234 5678 9012'),
(24, 26, 'Transfer', 'lunas', '2026-05-04 19:47:04', '5260 1234 5678 9012'),
(25, 27, 'Dana', 'lunas', '2026-05-04 19:48:21', '087757295631'),
(26, 28, 'Dana', 'lunas', '2026-05-04 19:56:18', '087757295631'),
(27, 29, 'Dana', 'lunas', '2026-05-04 20:07:14', '087757295631'),
(28, 30, 'Gopay', 'lunas', '2026-05-09 16:47:46', '087757295631');

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
(1, 'User Test', 'user@test.com', '123', 'user', 'Jakarta', '08123', '2026-05-04 08:56:36', NULL);

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
  MODIFY `id_order` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT untuk tabel `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id_order_item` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT untuk tabel `payments`
--
ALTER TABLE `payments`
  MODIFY `id_payment` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

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
  MODIFY `id_user` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
