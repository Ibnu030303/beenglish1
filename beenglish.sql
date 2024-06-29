-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 28, 2024 at 07:36 PM
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
-- Database: `beenglish`
--

-- --------------------------------------------------------

--
-- Table structure for table `articel`
--

CREATE TABLE `articel` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `content` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `articel`
--

INSERT INTO `articel` (`id`, `title`, `content`, `image`) VALUES
(15, 'Articel Post ', 'Articel post add ', '0f881b68f806a21532aeefb86e9e1090_cmcbpude878c73cka310_image.png'),
(17, 'asdawdasd', 'adsadasd', '7754440.jpg'),
(18, 'Articel Post Dua', 'hallo world im post the article two', 'english.png'),
(19, 'Articel post  There', 'hallo world im post the article two', 'bimbel.png');

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `id` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `deskripsi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`id`, `nama`, `deskripsi`) VALUES
(1, 'Komputer', 'Belajar Komputer dengan Mudah dan cepat sesuai dengan perkembangan jaman'),
(2, 'Bahasa Inggris', 'Belajar Bahasa Inggris Dengan Mudah dan Baik Untuk Melatih Public Speaking '),
(3, 'Bimbingan Belajar (MTK&IPA)', 'Belajar Bimbel dengan Mudah dan cepat sesuai dengan perkembangan jaman');

-- --------------------------------------------------------

--
-- Table structure for table `instruktur`
--

CREATE TABLE `instruktur` (
  `id` int(11) NOT NULL,
  `nip` int(25) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `tempat_lahir` char(50) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jk` enum('L','P') NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `telepon` int(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `course_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `instruktur`
--

INSERT INTO `instruktur` (`id`, `nip`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jk`, `alamat`, `telepon`, `email`, `course_id`) VALUES
(3, 20241, 'Ibnu Nuridiyansa', 'Tangerang', '2003-03-03', 'L', 'Kp. Blok Kelapa, RT03/02, Desa Serdang Wetan, Legok', 2147483647, 'ibnu@test.com', 3);

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `id` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `title` varchar(100) NOT NULL,
  `deskripsi` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `telepon` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id`, `nama`, `title`, `deskripsi`, `alamat`, `telepon`, `email`, `logo`) VALUES
(2, 'BEE', 'Title', 'dawdadsa', 'Kp. Blok Kelapa, RT03/02, Desa Serdang Wetan, Legok', '08921313231', 'bee@gmail.com', 'logo-removebg-preview.png');

-- --------------------------------------------------------

--
-- Table structure for table `program`
--

CREATE TABLE `program` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `deskripsi` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `program`
--

INSERT INTO `program` (`id`, `course_id`, `nama`, `deskripsi`, `image`) VALUES
(9, 2, 'TOEFL', 'asdawdada', 'bimbel.png'),
(10, 2, 'TOEIC', 'Program TOEIC ini adlaah merupakan program belajar dengan bentarandar internasional', 'kelompok.png'),
(11, 2, 'English At Home', 'Program English At Home merupakan program belajar di rumah ', 'english.png'),
(12, 2, 'English At Company', 'Program ini di rancang untuk orang company yang inign belajar bahsa inggris lebih lanjut', 'blended.png.png');

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `id` int(11) NOT NULL,
  `title` char(50) NOT NULL,
  `desktipsi` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `status` enum('active','off') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (`id`, `title`, `desktipsi`, `icon`, `status`) VALUES
(1, 'Murah dan Berkulitas', '', 'https://img.icons8.com/dusk/64/sell.png', 'active'),
(2, 'Flexible', '', 'https://img.icons8.com/external-flaticons-lineal-color-flat-icons/64/external-working-hours-digital-nomad-flaticons-lineal-color-flat-icons.png', 'active'),
(3, 'Berstandar Internasional', '', 'https://img.icons8.com/external-flaticons-lineal-color-flat-icons/64/external-international-university-flaticons-lineal-color-flat-icons.png', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id` int(11) NOT NULL,
  `no_siswa` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `tanggal_daftar` date NOT NULL,
  `tempat_lahir` char(50) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jk` enum('L','P') NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `telepon` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `program_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `status` enum('Wait','Active','Off') NOT NULL DEFAULT 'Wait'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id`, `no_siswa`, `nama`, `tanggal_daftar`, `tempat_lahir`, `tanggal_lahir`, `jk`, `alamat`, `telepon`, `email`, `program_id`, `course_id`, `status`) VALUES
(8, 202410001, 'Ibnu Nuridiyansas', '2024-06-28', 'Tangerang', '2003-03-03', 'L', 'Kp. Blok Kelapa, RT03/02, Desa Serdang Wetan, Legok', '08921313231', 'ibnu@test.com', 1, 1, 'Active'),
(10, 202410002, 'Nurdiyansa', '2024-06-28', 'Tangerang', '2042-03-03', 'L', 'Kp. Blok Kelapa, RT03/02, Desa Serdang Wetan, Legok', '1312121', 'penul@test.com', 3, 2, 'Active'),
(11, 202410003, 'Test Coba Daftar', '2024-06-28', 'Tangerang', '2004-03-03', 'P', 'Kp. Blok Kelapa, RT03/02, Desa Serdang Wetan, Legok', '0892131323112', 'test@test.com', 3, 2, 'Wait'),
(12, 202410004, 'coba', '2024-06-28', 'Tangerang', '2003-02-12', 'P', 'Kp. Blok Kelapa, RT03/02, Desa Serdang Wetan, Legok', '1312312', 'guest@test.com', 1, 1, 'Wait');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `role`) VALUES
(4, 'Admin', 'admin@test.com', '$2y$10$IHxqNUE5cI8.FU4gXrgQOuBbW1mhzZjA2b3nZzEY9tt4HQSGlqO3a', 'admin'),
(5, 'user', 'user@test.com', '$2y$10$g01uV/oa2TMVYStL3SZljOByZyVIaBFbf9AEK34fFLJpM9anG68BK', 'user'),
(6, 'test', 'test@test.com', '$2y$10$nLC.R3g4uh.nUvS1s/GcdOE6Opgl/l31kkIGhSycHIpEXm8Y3CZuu', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articel`
--
ALTER TABLE `articel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `instruktur`
--
ALTER TABLE `instruktur`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `program`
--
ALTER TABLE `program`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `no_siswa` (`no_siswa`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articel`
--
ALTER TABLE `articel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `instruktur`
--
ALTER TABLE `instruktur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `program`
--
ALTER TABLE `program`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `program`
--
ALTER TABLE `program`
  ADD CONSTRAINT `program_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
