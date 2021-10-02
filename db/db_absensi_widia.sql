-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 15, 2021 at 08:40 AM
-- Server version: 10.1.26-MariaDB
-- PHP Version: 7.2.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_absensi_widia`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensi`
--

CREATE TABLE `absensi` (
  `id_absen` int(11) NOT NULL,
  `tgl` date NOT NULL,
  `waktu` time NOT NULL,
  `keterangan` enum('Masuk','Pulang') NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `absensi`
--

INSERT INTO `absensi` (`id_absen`, `tgl`, `waktu`, `keterangan`, `id_user`) VALUES
(86, '2021-06-08', '22:42:00', 'Masuk', 16),
(87, '2021-06-08', '22:44:10', 'Pulang', 16),
(88, '2021-06-26', '19:22:05', 'Masuk', 17),
(89, '2021-06-26', '19:22:25', 'Pulang', 17),
(90, '2021-06-26', '19:24:33', 'Masuk', 18),
(91, '2021-06-26', '19:24:52', 'Pulang', 18),
(92, '2021-06-26', '19:32:54', 'Masuk', 19),
(93, '2021-06-26', '23:16:29', 'Masuk', 22),
(94, '2021-06-26', '23:16:57', 'Pulang', 22),
(95, '2021-06-26', '23:20:20', 'Masuk', 23),
(96, '2021-06-26', '23:20:37', 'Pulang', 23),
(97, '2021-06-27', '08:57:25', 'Masuk', 22),
(98, '2021-06-27', '08:57:54', 'Pulang', 22),
(99, '2021-06-27', '09:02:37', 'Masuk', 23),
(100, '2021-06-27', '09:02:48', 'Pulang', 23),
(101, '2021-06-30', '22:38:34', 'Masuk', 24),
(102, '2021-07-01', '12:52:08', 'Masuk', 23),
(103, '2021-07-01', '12:53:00', 'Pulang', 23),
(104, '2021-07-01', '12:53:38', 'Masuk', 24),
(105, '2021-07-01', '12:54:13', 'Pulang', 24),
(106, '2021-07-01', '12:55:10', 'Masuk', 25),
(107, '2021-07-01', '12:55:32', 'Pulang', 25),
(108, '2021-07-01', '13:17:28', 'Masuk', 26),
(109, '2021-07-01', '13:17:46', 'Pulang', 26),
(110, '2021-07-09', '14:06:58', 'Masuk', 27),
(111, '2021-07-09', '17:33:30', 'Pulang', 27),
(112, '2021-07-10', '20:58:30', 'Masuk', 27);

-- --------------------------------------------------------

--
-- Table structure for table `code`
--

CREATE TABLE `code` (
  `id` int(11) NOT NULL,
  `code` text NOT NULL,
  `image` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `code`
--

INSERT INTO `code` (`id`, `code`, `image`) VALUES
(1, 'gG1HJwh07J', 'gG1HJwh07J.png');

-- --------------------------------------------------------

--
-- Table structure for table `divisi`
--

CREATE TABLE `divisi` (
  `id_divisi` smallint(3) NOT NULL,
  `nama_divisi` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `divisi`
--

INSERT INTO `divisi` (`id_divisi`, `nama_divisi`) VALUES
(7, 'front office'),
(8, 'tenaga teknis'),
(9, 'kebersihan'),
(10, 'pengemudi'),
(12, 'pramubakti');

-- --------------------------------------------------------

--
-- Table structure for table `jam`
--

CREATE TABLE `jam` (
  `id_jam` tinyint(1) NOT NULL,
  `id_divisi` int(11) NOT NULL,
  `start` time NOT NULL,
  `finish` time NOT NULL,
  `keterangan` enum('Masuk','Pulang') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jam`
--

INSERT INTO `jam` (`id_jam`, `id_divisi`, `start`, `finish`, `keterangan`) VALUES
(1, 1, '19:00:00', '19:30:00', 'Masuk'),
(2, 1, '19:00:00', '19:30:00', 'Pulang'),
(3, 2, '19:20:00', '19:30:00', 'Masuk'),
(4, 2, '19:20:00', '19:35:00', 'Pulang'),
(5, 3, '08:00:00', '17:00:00', 'Masuk'),
(6, 3, '17:00:00', '08:00:00', 'Pulang'),
(7, 12, '07:30:00', '16:00:00', 'Masuk'),
(8, 12, '15:00:00', '17:00:00', 'Pulang'),
(9, 10, '07:00:00', '09:30:00', 'Masuk'),
(10, 10, '16:00:00', '15:00:00', 'Pulang'),
(11, 7, '07:00:00', '15:00:00', 'Masuk'),
(12, 7, '15:00:00', '16:00:00', 'Pulang');

-- --------------------------------------------------------

--
-- Table structure for table `rekap_absensi`
--

CREATE TABLE `rekap_absensi` (
  `id_rekap` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `tgl` date NOT NULL,
  `jam_masuk` time DEFAULT NULL,
  `jam_keluar` time DEFAULT NULL,
  `keterangan` text,
  `bukti` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rekap_absensi`
--

INSERT INTO `rekap_absensi` (`id_rekap`, `id_user`, `tgl`, `jam_masuk`, `jam_keluar`, `keterangan`, `bukti`) VALUES
(1, 16, '2021-06-08', '22:42:00', '22:44:10', 'Telat', NULL),
(2, 16, '2021-06-07', NULL, NULL, 'Alpha', NULL),
(3, 17, '2021-06-26', '19:22:05', '19:22:25', 'Hadir', NULL),
(4, 18, '2021-06-26', '19:24:33', '19:24:53', 'Telat', NULL),
(5, 19, '2021-06-26', '19:32:54', NULL, 'Telat', NULL),
(6, 22, '2021-06-26', '23:16:29', '23:16:57', 'telat', NULL),
(7, 23, '2021-06-26', '23:20:20', '23:20:37', 'telat', NULL),
(8, 22, '2021-06-27', '08:57:25', '08:57:54', 'Hadir', NULL),
(9, 23, '2021-06-27', '09:02:37', '09:02:48', 'Hadir', NULL),
(10, 24, '2021-06-30', '22:38:34', NULL, 'Telat', NULL),
(11, 23, '2021-07-01', '12:52:08', '12:53:00', 'izin', NULL),
(12, 24, '2021-07-01', '12:53:38', '12:54:13', 'Telat', NULL),
(13, 25, '2021-07-01', '12:55:10', '12:55:32', 'sakit', NULL),
(14, 26, '2021-07-01', '13:17:28', '13:17:46', 'sakit', NULL),
(27, 27, '2021-07-09', '14:06:58', '17:33:30', 'Telat', NULL),
(28, 27, '2021-07-10', NULL, '20:58:30', 'Alpha', NULL),
(29, 28, '2021-07-10', NULL, NULL, 'Alpha', NULL),
(30, 29, '2021-07-10', NULL, NULL, 'Alpha', NULL),
(31, 30, '2021-07-10', NULL, NULL, 'Alpha', NULL),
(32, 31, '2021-07-10', NULL, NULL, 'Alpha', NULL),
(33, 32, '2021-07-10', NULL, NULL, 'Alpha', NULL),
(34, 27, '2021-07-11', NULL, NULL, 'Alpha', NULL),
(35, 28, '2021-07-11', NULL, NULL, 'Alpha', NULL),
(36, 29, '2021-07-11', NULL, NULL, 'Alpha', NULL),
(37, 30, '2021-07-11', NULL, NULL, 'Alpha', NULL),
(38, 31, '2021-07-11', NULL, NULL, 'Alpha', NULL),
(39, 32, '2021-07-11', NULL, NULL, 'Alpha', NULL),
(40, 27, '2021-07-12', NULL, NULL, 'Izin', NULL),
(41, 28, '2021-07-12', NULL, NULL, 'Alpha', NULL),
(42, 29, '2021-07-12', NULL, NULL, 'Alpha', NULL),
(43, 30, '2021-07-12', NULL, NULL, 'Alpha', NULL),
(44, 31, '2021-07-12', NULL, NULL, 'Alpha', NULL),
(45, 32, '2021-07-12', NULL, NULL, 'Alpha', NULL),
(46, 27, '2021-07-13', NULL, NULL, 'Sakit', '7.PNG'),
(47, 28, '2021-07-13', NULL, NULL, 'Alpha', NULL),
(48, 29, '2021-07-13', NULL, NULL, 'Alpha', NULL),
(49, 30, '2021-07-13', NULL, NULL, 'Alpha', NULL),
(50, 31, '2021-07-13', NULL, NULL, 'Alpha', NULL),
(51, 32, '2021-07-13', NULL, NULL, 'Alpha', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` smallint(5) NOT NULL,
  `nik` varchar(20) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `pendidikan_terakhir` enum('SMA','D3','S1') NOT NULL,
  `telp` varchar(15) NOT NULL,
  `email` varchar(50) NOT NULL,
  `foto` varchar(20) DEFAULT 'no-foto.png',
  `divisi` smallint(5) DEFAULT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(60) NOT NULL,
  `level` enum('Manager','Karyawan') NOT NULL DEFAULT 'Karyawan'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `nik`, `nama`, `pendidikan_terakhir`, `telp`, `email`, `foto`, `divisi`, `username`, `password`, `level`) VALUES
(1, '', 'widia', 'D3', '08139212092', 'admin@mail.com', '1624709749.png', NULL, 'admin', '$2y$10$IiIs9NdEwMAnMdAQUD2jOOTwG9qDegeebr3XgBKLJZ6gRrxR1r8ii', 'Manager'),
(27, '00036855268480', 'Widia nurul hidayah', 'SMA', '085863890951', 'wnurulhidayah72@gmail.com', 'no-foto.png', 7, 'Widia', '$2y$10$BYWcTW3.xohH2oWWeDi7GOMEYew8nQEWgqnEeGMKqg5Qhy7SI1gAK', 'Karyawan'),
(28, '32132358429006', 'Kiki taufik hidayat', 'SMA', '0852316798854', 'kiki@gmail.com', 'no-foto.png', 7, 'Kiki', '$2y$10$AUANTui6YbbUs13h3rHz9.aqcHIobi8D7ZedoYYZw8naxs2oPbsEW', 'Karyawan'),
(29, '3213036311950006', 'Nadya rahmarani', 'SMA', '085321458796', 'nadya@gmail.com', 'no-foto.png', 7, 'Nadya', '$2y$10$47ZBFxCP5lQVx.mssJEGdu0GTuCyJdsP3yjolLMyPN/UUYQYkKh/a', 'Karyawan'),
(30, '32130363119500043', 'Sonia pratiwi', 'SMA', '089688005544', 'sonia@gmail.com', 'no-foto.png', 12, 'Sonia', '$2y$10$N94k2aO6JV/vBnWEXeshaOo/nROJMTTPlb9qpkCpd.nW5vOLnh7uC', 'Karyawan'),
(31, '3215036311996005', 'Edi sopyan', 'SMA', '085532145588', 'edi@gmail.com', 'no-foto.png', 10, 'Edi', '$2y$10$Fit4BRgV/.0CLO6zERyubOFgEKN.6ujnNv4taJKzxBhmDV1orkFZu', 'Karyawan'),
(32, '3213032508840014', 'Agus rismanto', 'SMA', '087765435421', 'agus@gmail.com', 'no-foto.png', 9, 'Agus', '$2y$10$Amo3mMPjM.cGKfsGNxLAr.6iMnKWmbWJQ3JZfFk2xQ8aFq5QeVgdm', 'Karyawan');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id_absen`);

--
-- Indexes for table `code`
--
ALTER TABLE `code`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `divisi`
--
ALTER TABLE `divisi`
  ADD PRIMARY KEY (`id_divisi`);

--
-- Indexes for table `jam`
--
ALTER TABLE `jam`
  ADD PRIMARY KEY (`id_jam`);

--
-- Indexes for table `rekap_absensi`
--
ALTER TABLE `rekap_absensi`
  ADD PRIMARY KEY (`id_rekap`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id_absen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `code`
--
ALTER TABLE `code`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `divisi`
--
ALTER TABLE `divisi`
  MODIFY `id_divisi` smallint(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `jam`
--
ALTER TABLE `jam`
  MODIFY `id_jam` tinyint(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `rekap_absensi`
--
ALTER TABLE `rekap_absensi`
  MODIFY `id_rekap` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` smallint(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
