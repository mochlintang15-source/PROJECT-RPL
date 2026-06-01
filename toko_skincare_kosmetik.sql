-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 01 Jun 2026 pada 17.47
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
(1, 2, '2026-06-01 22:40:51', 1672000.00, 'dibayar', 'Admin Toko', 'admin@test.com', 'Jakarta', 22000, 'J&T', 'YES', '2026-06-01 22:40:51');

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
(1, 1, 1, 11, 150000.00);

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
(46, 1, 'OVO', 'lunas', '2026-06-01 22:41:05', '0772724', 'bukti_order_1_20260601174105_b08f8ed1.jpg');

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
(1, 'Serum', 'Bagus', 150000.00, 0, NULL, NULL, 'img.jpg', '2026-05-04 08:56:36', '2026-06-01 15:41:22'),
(2, 'Serum Wajah', 'Bagus', 150000.00, 10, NULL, NULL, 'img.jpg', '2026-05-04 09:07:41', NULL),
(3, 'bedak', 'Mencerahkan kulit', 150000.00, 10, NULL, NULL, 'img.jpg', '2026-05-04 09:37:59', '2026-06-01 15:39:30'),
(4, 'Facial Wash', 'Membersihkan wajah', 50000.00, 20, NULL, NULL, 'img.jpg', '2026-05-04 09:37:59', '2026-06-01 15:39:08');

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
(1, 'User Test', 'user@test.com', '$2y$10$9fHXFubUQhsOGYdzSzXQFeG0i4JEQvGt2oK6rLTIUHnritjCa2BHy', 'user', 'Jakarta', '08123', '2026-05-04 08:56:36', NULL),
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
  MODIFY `id_order` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id_order_item` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `payments`
--
ALTER TABLE `payments`
  MODIFY `id_payment` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

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
