-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 23, 2025 at 11:34 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `toko_baju`
--

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int(11) NOT NULL,
  `kategori` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `kategori`) VALUES
(2, 'Celana'),
(3, 'Rok'),
(4, 'Hijab'),
(7, 'Baju');

-- --------------------------------------------------------

--
-- Table structure for table `log_produk_hapus`
--

CREATE TABLE `log_produk_hapus` (
  `log_id` int(11) NOT NULL,
  `produk_id` int(11) DEFAULT NULL,
  `nama_produk` varchar(255) DEFAULT NULL,
  `tanggal_hapus` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `log_produk_hapus`
--

INSERT INTO `log_produk_hapus` (`log_id`, `produk_id`, `nama_produk`, `tanggal_hapus`) VALUES
(1, 17, 'Sweater Putih Rajut', '2025-06-23 09:23:24');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` int(11) NOT NULL,
  `kategori_id` int(11) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `harga` double DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `detail` text DEFAULT NULL,
  `stok_produk` enum('habis','tersedia') DEFAULT 'tersedia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `kategori_id`, `nama`, `harga`, `foto`, `detail`, `stok_produk`) VALUES
(2, 7, 'Kemeja Hitam Polos', 250000, '', 'Warna: Hitam pekat\r\nModel: Polos (tanpa motif atau ornamen)\r\nBahan: Umumnya katun, linen, atau campuran poliester, menawarkan kenyamanan dan sirkulasi udara yang baik.\r\n', 'tersedia'),
(3, 7, 'Baju Biru Pita', 350000, 'produk_68591ee0a8593.jpg', 'Warna: Biru muda\r\nModel: Atasan/blus peplum berjenjang (tiered) dengan lengan lonceng (bell sleeve) yang melebar dan aksen ruffle di ujungnya. \r\nBahan:  katun', 'tersedia'),
(4, 2, 'Celana Cutbray Retro', 350000, 'produk_68591f294a358.jpg', 'Warna: Hitam pekat\r\n\r\nModel: Polos (tanpa motif atau ornamen)\r\n\r\nBahan: Umumnya katun, linen, atau campuran poliester, menawarkan kenyamanan dan sirkulasi udara yang baik.', 'tersedia'),
(5, 2, 'Celana Jeans High Waist Hitam', 500000, 'produk_68591f3e117c1.jpg', 'pilihan celana denim yang menonjolkan gaya klasik dengan sentuhan modern. Dibuat dari denim kaku 100% katun yang tidak melar, jeans ini menawarkan siluet lurus dari pinggang tinggi hingga pergelangan kaki, menciptakan tampilan yang rapi dan memanjangkan kaki.', 'tersedia'),
(6, 2, 'Celana Kulot Abu', 300000, '', 'pilihan denim yang berani dan nyaman, menampilkan siluet kaki lebar yang sedang tren dan pinggang tinggi untuk tampilan yang modern dan santai. Dibuat dari denim kaku yang tidak melar, jeans ini memberikan struktur yang indah dan sentuhan vintage. Dengan warna abu-abu pudar yang unik, jeans ini menawarkan fleksibilitas gaya, sempurna untuk dipadukan dengan berbagai atasan, mulai dari kasual hingga lebih formal, menjadikannya pilihan ideal untuk gaya sehari-hari', 'tersedia'),
(7, 2, 'Celana Kulot Putih', 300000, '', 'pilihan celana panjang yang elegan dan nyaman, sempurna untuk gaya yang santai namun tetap terlihat rapi. Terbuat dari campuran katun, celana ini menawarkan tekstur yang lembut dan ringan, ideal untuk cuaca hangat. Dengan potongan kaki lebar yang modern dan pinggang yang dirancang apik, celana ini memberikan siluet yang mengalir dan sophisticated, sementara warna krem yang netral membuatnya mudah dipadukan dengan berbagai atasan untuk tampilan kasual yang cerdas atau lebih formal.', 'tersedia'),
(8, 4, 'Pashmina Biru Muda', 40000, '', 'jilbab pashmina berwarna biru muda ini merupakan aksesori yang ringan dan elegan, sempurna untuk menambah sentuhan keanggunan pada setiap busana. Terbuat dari bahan sifon yang lembut dan mengalir, jilbab ini menawarkan tekstur yang halus dan sedikit transparan, ideal untuk memberikan kesan yang ringan dan anggun. Warnanya yang biru muda memberikan kesan yang menenangkan dan mudah dipadukan, cocok untuk digunakan sehari-hari maupun untuk acara khusus, memungkinkan berbagai gaya lilitan yang indah dan modis.', 'tersedia'),
(9, 4, 'Pashmina Cream', 50000, '', 'jilbab ini merupakan aksesori mewah yang ideal untuk menambah kehangatan dan keanggunan pada penampilan. Terbuat dari bahan cashmere yang tipis dan lembut, jilbab ini menawarkan sentuhan yang halus di kulit dan kenyamanan maksimal. Dengan warna cream yang netral dan serbaguna, pashmina ini sangat cocok untuk dipadukan dengan berbagai warna busana, menjadikannya pilihan sempurna untuk melengkapi gaya formal maupun kasual dengan sentuhan kemewahan dan kehangatan yang ringan', 'tersedia'),
(10, 4, 'Pashmina Hijau Gelap', 30000, '', 'Pashmina Sifon berwarna hijau gelap ini adalah aksesori serbaguna dan bergaya yang dapat mempercantik tampilan apa pun. Dibuat dari bahan sifon ringan, pashmina ini memiliki tekstur halus dan mengalir yang anggun saat dikenakan. Warna hijau gelap yang kuat dan chic menambahkan sentuhan gaya militer namun tetap elegan, menjadikannya pilihan yang sempurna untuk dipadukan dengan berbagai warna, mulai dari netral hingga cerah, dan dapat ditata dengan berbagai cara untuk melengkapi busana kasual maupun formal.', 'tersedia'),
(11, 4, 'Pashmina Maroon', 50000, '', 'Pashmina Georgette Chiffon berwarna maroon ini adalah aksesori yang anggun dan serbaguna, mampu memperkaya penampilan dengan sentuhan warna yang mewah. Terbuat dari bahan georgette sifon yang ringan dan mengalir, pashmina ini menawarkan tekstur yang lembut dan drape yang indah, cocok untuk berbagai gaya lilitan.', 'tersedia'),
(12, 7, 'Kemeja Pink Garis', 200000, '', 'kemeja ini menghadirkan gaya klasik yang segar dengan sentuhan ceria. Terbuat dari bahan poplin yang ringan dan renyah, kemeja ini nyaman dipakai dan memberikan tampilan yang rapi. Dengan motif garis-garis tipis berwarna pink dan putih, kemeja ini menawarkan desain kerah kemeja standar, kancing depan penuh, dan potongan oversized yang santai dengan hem melengkung, menjadikannya pilihan serbaguna untuk gaya kasual sehari-hari maupun semi-formal.', 'tersedia'),
(13, 3, 'Rok Hitam Polos', 250000, '', 'Rok penuh berwarna hitam ini memancarkan kesan elegan dan dramatis, menjadikannya pilihan sempurna untuk acara formal maupun gaya kasual yang chic. Dengan potongan A-line yang lebar dan mengembang dari pinggang, rok ini menciptakan siluet yang anggun dan bervolume. Detail pinggangnya yang dirancang rapi, kemungkinan dengan penutup tersembunyi atau kancing unik di bagian depan, menambah sentuhan kemewahan pada desain minimalisnya, membuatnya serbaguna untuk dipadukan dengan blus atau atasan yang pas badan.', 'tersedia'),
(14, 3, 'Rok Jeans Panjang', 350000, '', 'rok ini menawarkan tampilan retro yang trendi dengan sentuhan modern. Dibuat dari bahan denim biru wash yang kokoh, rok ini menampilkan siluet lurus atau sedikit A-line yang memanjang hingga betis, menciptakan gaya yang unik dan santai. Dengan desain pinggang tinggi klasik, loop ikat pinggang, dan detail jahitan tengah yang menonjol, rok ini sangat serbaguna untuk dipadukan dengan kaus kasual atau blus yang lebih formal.', 'tersedia'),
(15, 3, 'Rok Polos Batta', 350000, '', 'Rok bertingkat berwarna batta ini menghadirkan gaya bohemian yang feminin dan elegan. Terbuat dari bahan yang tampak ringan dan mengalir, rok ini memiliki beberapa tingkatan kain yang ditumpuk dan berkerut, menciptakan volume dan gerakan yang indah. Potongan panjang maksi dan warna batta yang kaya memberikan kesan mewah dan serbaguna, menjadikannya pilihan yang sempurna untuk dipadukan dengan atasan simpel untuk tampilan kasual yang chic atau blus elegan untuk acara yang lebih formal.', 'tersedia'),
(16, 3, 'Rok Putih Satin', 300000, '', 'Rok satin berwarna putih bersih ini menampilkan desain minimalis dan elegan, cocok untuk gaya yang rapi dan serbaguna. Terbuat dari bahan yang tampak jatuh dan sedikit bervolume, rok ini memiliki potongan A-line atau flare yang memberikan gerakan lembut saat dikenakan. Dengan warna putih yang klasik, rok ini menawarkan kemudahan untuk dipadukan dengan berbagai atasan, mulai dari yang kasual hingga formal, menjadikannya pilihan ideal untuk tampilan yang ringan dan chic di berbagai kesempatan.', 'tersedia');

--
-- Triggers `produk`
--
DELIMITER $$
CREATE TRIGGER `after_delete_produk` AFTER DELETE ON `produk` FOR EACH ROW BEGIN
    INSERT INTO `log_produk_hapus` (produk_id, nama_produk)
    VALUES (OLD.id, OLD.nama);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_produk_kategori`
-- (See below for the actual view)
--
CREATE TABLE `view_produk_kategori` (
`produk_id` int(11)
,`nama_produk` varchar(255)
,`nama_kategori` varchar(100)
,`harga` double
,`foto` varchar(255)
,`detail` text
,`stok_produk` enum('habis','tersedia')
);

-- --------------------------------------------------------

--
-- Structure for view `view_produk_kategori`
--
DROP TABLE IF EXISTS `view_produk_kategori`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_produk_kategori`  AS SELECT `p`.`id` AS `produk_id`, `p`.`nama` AS `nama_produk`, `k`.`kategori` AS `nama_kategori`, `p`.`harga` AS `harga`, `p`.`foto` AS `foto`, `p`.`detail` AS `detail`, `p`.`stok_produk` AS `stok_produk` FROM (`produk` `p` join `kategori` `k` on(`p`.`kategori_id` = `k`.`id`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log_produk_hapus`
--
ALTER TABLE `log_produk_hapus`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nama` (`nama`),
  ADD KEY `kategori_produk` (`kategori_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `log_produk_hapus`
--
ALTER TABLE `log_produk_hapus`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `kategori_produk` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
