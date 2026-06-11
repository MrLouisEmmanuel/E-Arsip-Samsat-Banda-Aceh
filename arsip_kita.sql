-- phpMyAdmin SQL Dump
-- Database: `arsip_kita`

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_nama` varchar(255) NOT NULL,
  `user_username` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_foto` varchar(255) DEFAULT NULL,
  `user_role` enum('admin','petugas','user') NOT NULL DEFAULT 'user',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_username` (`user_username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `users`
-- Note: Passwords are in MD5 for old users. The new system will automatically 
-- upgrade them to Bcrypt upon first login.
--

INSERT INTO `users` (`user_id`, `user_nama`, `user_username`, `user_password`, `user_foto`, `user_role`) VALUES
(1, 'Administrator', 'admin', '0192023a7bbd73250516f069df18b500', '1471275613_Screen Shot 2019-10-11 at 16.26.42.png', 'admin'),
(2, 'Jhony Andrean', 'petugas1', 'b53fe7751b37e40ff34d012c7774d65f', '', 'petugas'),
(3, 'Junaidi Mus', 'petugas2', 'ac5604a8b8504d4ff5b842480df02e91', '', 'petugas'),
(4, 'Jamilah Suanda', 'petugas3', '6f7dc121bccfd778744109cac9593474', '', 'petugas'),
(5, 'Samsul Bahri', 'user1', '24c9e15e52afc47c225b757e7bee1f9d', '', 'user'),
(6, 'Reza Yuzanni', 'user2', '7e58d63b60197ceb55a1c487989a3720', '', 'user'),
(7, 'Ajir Muhajier', 'user3', '92877af70a45fd6a2ed7fe81e1236b78', '', 'user'),
(8, 'Cut Nanda Somay', 'user4', '3f02ebe3d7929b091e3d8ccfde2f3bc6', '', 'user');


-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `kategori_id` int(11) NOT NULL AUTO_INCREMENT,
  `kategori_nama` varchar(255) NOT NULL,
  `kategori_keterangan` text NOT NULL,
  PRIMARY KEY (`kategori_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`kategori_id`, `kategori_nama`, `kategori_keterangan`) VALUES
(1, 'Tidak berkategori', 'Semua yang tidak memiliki kategori'),
(3, 'Surat Keputusan', 'Format arsip untuk surat keputusan\r\n'),
(4, 'Surat Izin Pelaksanaan', 'Contoh format surat izin pelaksaan pekerjaan'),
(5, 'Surat Perintah Kerja Proyek jalan', 'Contoh format surat perintah untuk pekerjaan proyek jalan'),
(6, 'Surat Perintah Kerja Proyek Jembatan', 'Contoh format untuk surat perintah kerja proyek jembatan'),
(7, 'Surat Kesehatan Pegawai', 'Surat kesehatan untuk pegawai'),
(8, 'Surat Lampiran Skripsi', 'Surat contoh lampiran untuk skripsi'),
(10, 'Curiculum Vitae', 'Contoh format surat curiculum vitae untuk kenaikan jabatan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `arsip`
--

CREATE TABLE `arsip` (
  `arsip_id` int(11) NOT NULL AUTO_INCREMENT,
  `arsip_waktu_upload` datetime NOT NULL,
  `arsip_petugas` int(11) NOT NULL,
  `arsip_kode` varchar(255) NOT NULL,
  `arsip_nama` varchar(255) NOT NULL,
  `arsip_jenis` varchar(255) NOT NULL,
  `arsip_kategori` int(11) NOT NULL,
  `arsip_keterangan` text NOT NULL,
  `arsip_file` varchar(255) NOT NULL,
  `arsip_status` enum('Diterima Petugas','Sedang Diverifikasi','Menunggu Pembayaran','Arsip Tersimpan') NOT NULL DEFAULT 'Diterima Petugas',
  PRIMARY KEY (`arsip_id`),
  KEY `arsip_petugas` (`arsip_petugas`),
  KEY `arsip_kategori` (`arsip_kategori`),
  CONSTRAINT `fk_arsip_petugas` FOREIGN KEY (`arsip_petugas`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_arsip_kategori` FOREIGN KEY (`arsip_kategori`) REFERENCES `kategori` (`kategori_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `arsip`
--

INSERT INTO `arsip` (`arsip_id`, `arsip_waktu_upload`, `arsip_petugas`, `arsip_kode`, `arsip_nama`, `arsip_jenis`, `arsip_kategori`, `arsip_keterangan`, `arsip_file`, `arsip_status`) VALUES
(2, '2019-10-10 15:09:59', 2, 'ARSIP-MN-0002', 'File keberngkatan', 'png', 4, 'tes ttead', '1162363338_Screen Shot 2019-10-10 at 13.22.15.png', 'Arsip Tersimpan'),
(3, '2019-10-10 16:02:44', 2, 'asd', 'asdasd 2x', 'pdf', 3, 'asdasd', '432536246_mamunur.pdf', 'Arsip Tersimpan'),
(4, '2019-10-12 17:02:16', 3, 'MN-002', 'Contoh Surat Izin Pelaksanaan', 'pdf', 4, 'Ini Contoh Surat Izin Pelaksanaan', '1352467019_c4611_sample_explain.pdf', 'Arsip Tersimpan'),
(5, '2019-10-12 17:03:01', 3, 'MN-003', 'Contoh Keputusan Kerja', 'pdf', 3, 'Contoh Keputusan Kerja pegawai', '1765932248_Contoh-surat-lamaran-kerja-pdf (1).pdf', 'Arsip Tersimpan'),
(6, '2019-10-12 17:03:37', 3, 'MN-004', 'Contoh Surat Izin Pegawai', 'pdf', 7, 'berikut Contoh Surat Izin Pegawai untuk pelaksana kerja', '1651167980_instructions-for-adding-your-logo.pdf', 'Arsip Tersimpan'),
(7, '2019-10-12 17:04:30', 3, 'MN-005', 'Contoh SPK Proyek Kontraktor', 'pdf', 5, 'Contoh SPK Proyek Kontraktor adalah contoh surat SPK KONTRAK', '142845393_OoPdfFormExample.pdf', 'Arsip Tersimpan'),
(8, '2019-10-12 17:05:22', 3, 'MN-006', 'SPK Kontrak Jembatan', 'pdf', 6, 'Surat SPK Kontrak Jembatan Layang', '106615077_sample-link_1.pdf', 'Arsip Tersimpan'),
(9, '2019-10-12 17:06:55', 4, 'MN-008', 'Contoh Curiculum Vitae Untuk Lamaran Kerja', 'pdf', 10, 'Contoh Curiculum Vitae Untuk Lamaran Kerja untuk pegawai baru', '927990343_pdf-sample(1).pdf', 'Arsip Tersimpan'),
(10, '2019-10-12 17:07:30', 4, 'MN-009', 'Surat Cuti Sakit Pegawai', 'pdf', 7, 'Contoh Surat Cuti Sakit Pegawai baru', '2071946811_PEMBUATAN FILE PDF_FNH_tamim (1).pdf', 'Arsip Tersimpan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `riwayat`
--

CREATE TABLE `riwayat` (
  `riwayat_id` int(11) NOT NULL AUTO_INCREMENT,
  `riwayat_waktu` datetime NOT NULL,
  `riwayat_user` int(11) NOT NULL,
  `riwayat_arsip` int(11) NOT NULL,
  PRIMARY KEY (`riwayat_id`),
  KEY `riwayat_user` (`riwayat_user`),
  KEY `riwayat_arsip` (`riwayat_arsip`),
  CONSTRAINT `fk_riwayat_user` FOREIGN KEY (`riwayat_user`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_riwayat_arsip` FOREIGN KEY (`riwayat_arsip`) REFERENCES `arsip` (`arsip_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `riwayat`
--

INSERT INTO `riwayat` (`riwayat_id`, `riwayat_waktu`, `riwayat_user`, `riwayat_arsip`) VALUES
(1, '2019-10-11 15:32:29', 5, 3),
(2, '2019-10-12 17:09:31', 5, 10),
(3, '2019-10-12 17:09:45', 5, 9),
(4, '2019-10-12 17:09:50', 5, 8),
(5, '2019-10-12 17:09:53', 5, 3),
(6, '2019-10-12 17:10:07', 6, 10),
(7, '2019-10-12 17:10:16', 6, 9),
(8, '2019-10-12 17:10:19', 6, 8),
(9, '2019-10-12 17:10:22', 6, 6),
(10, '2019-10-12 17:10:26', 6, 2);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
