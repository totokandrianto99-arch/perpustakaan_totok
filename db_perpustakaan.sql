-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 28, 2026 at 02:48 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_perpustakaan`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` bigint UNSIGNED NOT NULL,
  `id_user` bigint UNSIGNED DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_id` bigint UNSIGNED DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `id_user`, `action`, `model`, `model_id`, `description`, `ip_address`, `created_at`, `updated_at`) VALUES
(1, 1, 'approve_peminjaman', 'Peminjaman', 1, 'Approve pinjam: Negeri 5 Menara', '127.0.0.1', '2026-05-25 22:43:55', '2026-05-25 22:43:55'),
(2, 1, 'approve_peminjaman', 'Peminjaman', 2, 'Approve pinjam: The World Book Encyclopedia', '127.0.0.1', '2026-05-25 22:44:59', '2026-05-25 22:44:59'),
(3, 1, 'approve_perpanjang', 'Peminjaman', 2, 'Approve perpanjang 3 hari', '127.0.0.1', '2026-05-25 22:45:43', '2026-05-25 22:45:43'),
(4, 1, 'approve_peminjaman', 'Peminjaman', 3, 'Approve pinjam: Laskar Pelangi', '127.0.0.1', '2026-05-25 22:46:52', '2026-05-25 22:46:52'),
(5, 1, 'reject_peminjaman', 'Peminjaman', 4, 'Reject pinjam: Bumi Manusia', '127.0.0.1', '2026-05-25 22:47:00', '2026-05-25 22:47:00'),
(6, 1, 'approve_peminjaman', 'Peminjaman', 5, 'Approve pinjam: The World Book Encyclopedia', '127.0.0.1', '2026-05-25 23:05:14', '2026-05-25 23:05:14'),
(7, 1, 'approve_perpanjang', 'Peminjaman', 5, 'Approve perpanjang 3 hari', '127.0.0.1', '2026-05-25 23:05:46', '2026-05-25 23:05:46'),
(8, 1, 'reject_peminjaman', 'Peminjaman', 6, 'Reject pinjam: The World Book Encyclopedia', '127.0.0.1', '2026-05-25 23:06:39', '2026-05-25 23:06:39'),
(9, 1, 'approve_peminjaman', 'Peminjaman', 9, 'Approve pinjam: Detective Conan', '127.0.0.1', '2026-05-25 23:29:09', '2026-05-25 23:29:09'),
(10, 1, 'approve_perpanjang', 'Peminjaman', 9, 'Approve perpanjang 3 hari', '127.0.0.1', '2026-05-25 23:29:36', '2026-05-25 23:29:36'),
(11, 1, 'approve_peminjaman', 'Peminjaman', 10, 'Approve pinjam: KBBI', '127.0.0.1', '2026-05-26 00:37:13', '2026-05-26 00:37:13'),
(12, 1, 'approve_peminjaman', 'Peminjaman', 11, 'Approve pinjam: KBBI', '127.0.0.1', '2026-05-26 01:00:20', '2026-05-26 01:00:20'),
(13, 1, 'konfirmasi_kembalikan', 'Peminjaman', 11, 'Konfirmasi kembalikan: KBBI', '127.0.0.1', '2026-05-26 01:08:51', '2026-05-26 01:08:51'),
(14, 1, 'create_peminjaman', 'Peminjaman', 12, 'Request pinjam buku: Detective Conan', '127.0.0.1', '2026-05-26 01:15:39', '2026-05-26 01:15:39'),
(15, 1, 'approve_peminjaman', 'Peminjaman', 12, 'Approve pinjam: Detective Conan', '127.0.0.1', '2026-05-26 01:15:45', '2026-05-26 01:15:45'),
(16, 1, 'kembalikan', 'Peminjaman', 12, 'Kembalikan: Detective Conan', '127.0.0.1', '2026-05-26 01:15:52', '2026-05-26 01:15:52'),
(17, 1, 'create_peminjaman', 'Peminjaman', 13, 'Request pinjam buku: Bumi Manusia', '127.0.0.1', '2026-05-26 01:19:47', '2026-05-26 01:19:47'),
(18, 1, 'approve_peminjaman', 'Peminjaman', 13, 'Approve pinjam: Bumi Manusia', '127.0.0.1', '2026-05-26 01:19:53', '2026-05-26 01:19:53'),
(19, 1, 'kembalikan', 'Peminjaman', 13, 'Kembalikan: Bumi Manusia', '127.0.0.1', '2026-05-26 01:20:11', '2026-05-26 01:20:11'),
(20, 1, 'approve_peminjaman', 'Peminjaman', 14, 'Approve pinjam: The World Book Encyclopedia', '127.0.0.1', '2026-05-26 01:23:35', '2026-05-26 01:23:35'),
(21, 1, 'konfirmasi_kembalikan', 'Peminjaman', 14, 'Konfirmasi kembalikan: The World Book Encyclopedia', '127.0.0.1', '2026-05-26 01:23:58', '2026-05-26 01:23:58');

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `id_buku` bigint UNSIGNED NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pengarang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun_terbit` year NOT NULL,
  `stok` int NOT NULL DEFAULT '0',
  `cover` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `penerbit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kategori` enum('Novel','Pendidikan','Referensi','Komik','Ensiklopedia','Majalah','Lainnya') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Lainnya',
  `sinopsis` text COLLATE utf8mb4_unicode_ci,
  `lokasi_rak` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_dipinjam` int NOT NULL DEFAULT '0',
  `status` enum('tersedia','tidak_tersedia') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tersedia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id_buku`, `judul`, `pengarang`, `tahun_terbit`, `stok`, `cover`, `created_at`, `updated_at`, `penerbit`, `kategori`, `sinopsis`, `lokasi_rak`, `total_dipinjam`, `status`) VALUES
(1, 'Laskar Pelangi', 'Andrea Hirata', '2005', 5, 'covers/DFPbjPfMIU635iou6ZcC9FautPLH5NlWDaWd8De6.webp', '2026-05-25 22:09:08', '2026-05-26 01:18:40', NULL, 'Novel', 'Berlatar belakang di Pulau Belitong yang kaya akan timah namun miskin secara ekonomi masyarakatnya, novel ini mengisahkan tentang perjuangan hidup 10 anak daerah pedalaman yang bersekolah di sebuah sekolah Islam (Muhammadiyah) yang kondisinya sangat memprihatinkan dan nyaris digusur. Di bawah bimbingan dua guru yang luar biasa ikhlas, Bu Muslimah dan Pak Harfan, anak-anak yang menjuluki diri mereka \"Laskar Pelangi\" ini merajut mimpi-mimpi besar. Kisah ini dipenuhi dengan keindahan persahabatan, kepolosan cinta monyet, keteguhan hati dalam melawan kemiskinan, serta kekuatan magis dari sebuah pendidikan dan harapan.', NULL, 2, 'tersedia'),
(2, 'Bumi Manusia', 'Pramoedya Ananta Toer', '1980', 3, 'covers/8hYenwmfCZU7VtJsEtUJSu1wHmjkYL3zjfbkVM7k.webp', '2026-05-25 22:09:08', '2026-05-26 01:20:11', 'Hasta Mitra', 'Referensi', 'Berlatar belakang di akhir masa kolonialisme Hindia Belanda abad ke-19, novel ini mengisahkan tentang Minke, seorang pemuda pribumi yang cerdas, modern, dan berbakat menulis. Sebagai satu-satunya anak pribumi yang berhasil masuk ke sekolah elite HBS Surabaya, Minke sangat mengagumi kebudayaan Eropa. Namun, pandangannya mulai berubah ketika ia mengenal Nyai Ontosoroh, seorang perempuan pribumi yang dijadikan gundik oleh pria Belanda. Nyai Ontosoroh membuktikan dirinya jauh lebih mandiri, cerdas, dan bermartabat daripada kaum kolonial yang menindasnya.\r\n\r\nMelalui kisah cintanya yang tragis dengan Annelies (putri Nyai Ontosoroh), Minke mulai menyadari ketidakadilan, rasisme, dan kejamnya hukum kolonial yang menempatkan kaum pribumi di kasta terendah. Novel ini merupakan kisah perjuangan harga diri, cinta, dan awal mula lahirnya kesadaran nasionalisme di atas tanah air yang terjajah.', NULL, 1, 'tersedia'),
(3, 'Negeri 5 Menara', 'Ahmad Fuadi', '2009', 4, 'covers/gN36ifWvg0THlkCYFuXOvWywKfwfWmsnBi2ixhuL.jpg', '2026-05-25 22:09:08', '2026-05-26 01:19:08', 'Gramedia Pustaka Utama', 'Pendidikan', 'Alif adalah seorang anak dari desa terpencil di Bayur, dekat Danau Maninjau, Sumatra Barat. Ia memiliki impian besar untuk melanjutkan sekolah ke SMA umum dan kuliah di ITB agar bisa menjadi seperti BJ Habibie. Namun, ibunya menginginkan Alif masuk ke sekolah agama untuk menjadi pemuka agama seperti Buya Hamka. Dengan setengah hati, Alif akhirnya merantau ke Jawa untuk bersekolah di Pondok Madani, sebuah pesantren modern di Ponorogo, Jawa Timur.\r\n\r\nDi sana, keterpaksaan Alif perlahan memudar setelah ia bersahabat dengan lima anak dari berbagai daerah di Indonesia: Raja dari Medan, Said dari Surabaya, Dulmajid dari Sumenep, Atang dari Bandung, dan Baso dari Gowa. Di bawah menara masjid pondok yang megah, keenam sahabat ini sering berkumpul dan melayangkan mimpi-mimpi mereka ke langit. Berbekal mantra sakti yang mereka pelajari dari kiai mereka, \"Man jadda wajada\" (Siapa yang bersungguh-sungguh, pasti akan berhasil), mereka berjuang melewati kerasnya disiplin pesantren demi menggapai cita-cita dan menaklukkan benua-benua di dunia di bawah menara impian masing-masing.', NULL, 2, 'tersedia'),
(4, 'Detective Conan', 'Gosho Aoyama', '1994', 1, 'covers/tu4DiaSLgpsYe4OOk2shlweIOc1u5BY1ty2bOMEn.webp', '2026-05-25 22:31:03', '2026-05-26 01:18:25', 'Shogakukan', 'Komik', 'Shinichi Kudo adalah seorang detektif SMA jenius yang gemar membantu polisi memecahkan berbagai kasus kriminal sulit. Suatu hari, saat sedang mengintai sebuah organisasi misterius, ia disergap dan dicekoki sebuah racun eksperimental bernama APTX 4869. Bukannya mati, tubuh Shinichi justru menyusut menjadi anak kecil berusia 7 tahun. Untuk menyembunyikan identitasnya dan melindungi orang-orang di sekitarnya, ia menggunakan nama samaran Conan Edogawa. Sembari menumpang hidup di rumah detektif swasta payah, Kogoro Mouri, Conan diam-diam terus memecahkan kasus-kasus rumit menggunakan alat-alat canggih buatan Profesor Agasa, sembari terus memburu Organisasi Hitam yang telah mengecilkan tubuhnya.', NULL, 2, 'tersedia'),
(5, 'TIME Magazine', 'Henry Luce dan Briton Hadden', '1923', 10, 'covers/srTa1yK2gDJnIRLD0KGehYlfEXSBIXxp9uQ3lS0V.webp', '2026-05-25 22:33:43', '2026-05-26 01:18:01', 'Time USA, LLC', 'Majalah', 'TIME adalah salah satu majalah berita mingguan paling berpengaruh dan terkenal di dunia asal Amerika Serikat. Majalah ini menyajikan laporan mendalam, analisis tajam, serta opini mengenai peristiwa-peristiwa global terkini yang mencakup bidang politik, bisnis, teknologi, sains, budaya, dan hiburan. Selain laporan jurnalistiknya yang kredibel, TIME sangat ikonik dengan bingkai merah di sampulnya serta tradisi tahunan memilih \"Person of the Year\"—sebuah pengakuan terhadap tokoh, kelompok, atau gagasan yang dinilai paling memengaruhi jalannya sejarah dan pemberitaan di dunia, baik dalam arti positif maupun negatif.', NULL, 0, 'tersedia'),
(6, 'The World Book Encyclopedia', 'World Book, Inc.', '1917', 12, 'covers/3zvvQlkvlgK7M6pi4CPdcHOvVo0evarIEXotR3oY.jpg', '2026-05-25 22:34:49', '2026-05-26 01:23:58', 'World Book, Inc.', 'Ensiklopedia', 'The World Book Encyclopedia adalah rangkaian multi-volume ensiklopedia umum yang dirancang khusus untuk memenuhi kebutuhan referensi bagi pelajar, keluarga, serta pustakawan. Ensiklopedia ini mencakup hampir seluruh spektrum pengetahuan manusia, mulai dari sejarah, sains, geografi, seni, hingga biografi tokoh-tokoh penting dunia. Keunggulan utama dari ensiklopedia ini terletak pada gaya bahasanya yang jernih, objektif, dan mudah dipahami, serta dilengkapi dengan ribuan ilustrasi, peta, dan foto berwarna yang menarik. Setiap artikel ditulis dan ditinjau oleh para ahli di bidangnya untuk memastikan akurasi informasi yang disajikan.', NULL, 4, 'tersedia'),
(8, 'KBBI', 'Badan Riset Bahasa', '1988', 2, 'covers/MoBIgwgZo6fwbjHXVHjlJqHr6aePkLPlY9XQslew.webp', '2026-05-25 23:03:21', '2026-05-26 01:08:51', 'Badan Riset Bahasa', 'Novel', 'buku kbbi', NULL, 2, 'tersedia');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_01_01_000010_create_perpustakaan_tables', 2),
(5, '2026_05_21_065818_add_cover_to_buku_table', 2),
(6, '2026_05_22_015327_upgrade_perpustakaan_system', 2),
(7, '2026_05_25_020522_add_perpanjang_to_peminjaman', 2),
(8, '2026_05_25_024849_add_plain_password_to_user_table', 2),
(9, '2026_05_25_052128_create_wishlists_table', 2),
(12, '2026_05_26_000001_add_return_pending_to_peminjaman_status', 3);

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id` bigint UNSIGNED NOT NULL,
  `id_user` bigint UNSIGNED NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pesan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe` enum('info','warning','danger','success') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'info',
  `dibaca` tinyint(1) NOT NULL DEFAULT '0',
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifikasi`
--

INSERT INTO `notifikasi` (`id`, `id_user`, `judul`, `pesan`, `tipe`, `dibaca`, `url`, `created_at`, `updated_at`) VALUES
(1, 2, 'Peminjaman Disetujui', 'Peminjaman buku \"Negeri 5 Menara\" telah disetujui. Harap dikembalikan sebelum 02 Jun 2026.', 'success', 0, 'http://127.0.0.1:8000/member/riwayat', '2026-05-25 22:43:55', '2026-05-25 22:43:55'),
(2, 2, 'Peminjaman Disetujui', 'Peminjaman buku \"The World Book Encyclopedia\" telah disetujui. Harap dikembalikan sebelum 02 Jun 2026.', 'success', 0, 'http://127.0.0.1:8000/member/riwayat', '2026-05-25 22:44:59', '2026-05-25 22:44:59'),
(3, 1, 'Request Perpanjangan', 'Abu Nawas meminta perpanjangan 3 hari untuk buku \"The World Book Encyclopedia\".', 'warning', 0, 'http://127.0.0.1:8000/peminjaman?status=perpanjang_pending', '2026-05-25 22:45:19', '2026-05-25 22:45:19'),
(4, 2, 'Perpanjangan Disetujui', 'Perpanjangan 3 hari untuk buku \"The World Book Encyclopedia\" disetujui. Jatuh tempo baru: 05 Jun 2026.', 'success', 0, 'http://127.0.0.1:8000/member/riwayat', '2026-05-25 22:45:43', '2026-05-25 22:45:43'),
(5, 2, 'Peminjaman Disetujui', 'Peminjaman buku \"Laskar Pelangi\" telah disetujui. Harap dikembalikan sebelum 09 Jun 2026.', 'success', 0, 'http://127.0.0.1:8000/member/riwayat', '2026-05-25 22:46:52', '2026-05-25 22:46:52'),
(6, 2, 'Peminjaman Ditolak', 'Peminjaman buku \"Bumi Manusia\" ditolak. Alasan: mls', 'danger', 0, NULL, '2026-05-25 22:47:00', '2026-05-25 22:47:00'),
(7, 2, 'Peminjaman Disetujui', 'Peminjaman buku \"The World Book Encyclopedia\" telah disetujui. Harap dikembalikan sebelum 29 May 2026.', 'success', 0, 'http://127.0.0.1:8000/member/riwayat', '2026-05-25 23:05:14', '2026-05-25 23:05:14'),
(8, 1, 'Request Perpanjangan', 'Abu Nawas meminta perpanjangan 3 hari untuk buku \"The World Book Encyclopedia\".', 'warning', 0, 'http://127.0.0.1:8000/peminjaman?status=perpanjang_pending', '2026-05-25 23:05:38', '2026-05-25 23:05:38'),
(9, 2, 'Perpanjangan Disetujui', 'Perpanjangan 3 hari untuk buku \"The World Book Encyclopedia\" disetujui. Jatuh tempo baru: 01 Jun 2026.', 'success', 0, 'http://127.0.0.1:8000/member/riwayat', '2026-05-25 23:05:46', '2026-05-25 23:05:46'),
(10, 2, 'Peminjaman Ditolak', 'Peminjaman buku \"The World Book Encyclopedia\" ditolak. Alasan: tidak boleh', 'danger', 0, NULL, '2026-05-25 23:06:39', '2026-05-25 23:06:39'),
(11, 1, 'Request Peminjaman', 'Abu Nawas meminta peminjaman buku \"Detective Conan\" sebanyak 1 eksemplar.', 'warning', 0, 'http://127.0.0.1:8000/peminjaman?status=pending', '2026-05-25 23:27:01', '2026-05-25 23:27:01'),
(12, 1, 'Request Peminjaman Dibatalkan', 'Abu Nawas membatalkan request peminjaman buku \"Detective Conan\".', 'danger', 0, 'http://127.0.0.1:8000/peminjaman?status=pending', '2026-05-25 23:29:00', '2026-05-25 23:29:00'),
(13, 2, 'Peminjaman Disetujui', 'Peminjaman buku \"Detective Conan\" telah disetujui. Harap dikembalikan sebelum 29 May 2026.', 'success', 0, 'http://127.0.0.1:8000/member/riwayat', '2026-05-25 23:29:09', '2026-05-25 23:29:09'),
(14, 1, 'Request Perpanjangan', 'Abu Nawas meminta perpanjangan 3 hari untuk buku \"Detective Conan\".', 'warning', 0, 'http://127.0.0.1:8000/peminjaman?status=perpanjang_pending', '2026-05-25 23:29:25', '2026-05-25 23:29:25'),
(15, 2, 'Perpanjangan Disetujui', 'Perpanjangan 3 hari untuk buku \"Detective Conan\" disetujui. Jatuh tempo baru: 01 Jun 2026.', 'success', 0, 'http://127.0.0.1:8000/member/riwayat', '2026-05-25 23:29:36', '2026-05-25 23:29:36'),
(16, 2, 'Peminjaman Disetujui', 'Peminjaman buku \"KBBI\" telah disetujui. Harap dikembalikan sebelum 29 May 2026.', 'success', 0, 'http://127.0.0.1:8000/member/riwayat', '2026-05-26 00:37:13', '2026-05-26 00:37:13'),
(17, 2, 'Peminjaman Disetujui', 'Peminjaman buku \"KBBI\" telah disetujui. Harap dikembalikan sebelum 29 May 2026.', 'success', 0, 'http://127.0.0.1:8000/member/riwayat', '2026-05-26 01:00:20', '2026-05-26 01:00:20'),
(18, 2, 'Pengembalian Disetujui', 'Peminjaman buku \"KBBI\" telah dikonfirmasi sebagai dikembalikan.', 'success', 0, 'http://127.0.0.1:8000/member/riwayat', '2026-05-26 01:08:51', '2026-05-26 01:08:51'),
(19, 4, 'Peminjaman Disetujui', 'Peminjaman buku \"Detective Conan\" telah disetujui. Harap dikembalikan sebelum 29 May 2026.', 'success', 0, 'http://127.0.0.1:8000/member/riwayat', '2026-05-26 01:15:45', '2026-05-26 01:15:45'),
(20, 3, 'Peminjaman Disetujui', 'Peminjaman buku \"Bumi Manusia\" telah disetujui. Harap dikembalikan sebelum 29 May 2026.', 'success', 0, 'http://127.0.0.1:8000/member/riwayat', '2026-05-26 01:19:53', '2026-05-26 01:19:53'),
(21, 2, 'Peminjaman Disetujui', 'Peminjaman buku \"The World Book Encyclopedia\" telah disetujui. Harap dikembalikan sebelum 29 May 2026.', 'success', 0, 'http://127.0.0.1:8000/member/riwayat', '2026-05-26 01:23:35', '2026-05-26 01:23:35'),
(22, 1, 'Request Pengembalian', 'Abu Nawas meminta konfirmasi pengembalian buku \"The World Book Encyclopedia\".', 'warning', 0, 'http://127.0.0.1:8000/peminjaman?status=return_pending', '2026-05-26 01:23:45', '2026-05-26 01:23:45'),
(23, 2, 'Pengembalian Disetujui', 'Peminjaman buku \"The World Book Encyclopedia\" telah dikonfirmasi sebagai dikembalikan.', 'success', 0, 'http://127.0.0.1:8000/member/riwayat', '2026-05-26 01:23:58', '2026-05-26 01:23:58');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id_pinjam` bigint UNSIGNED NOT NULL,
  `id_user` bigint UNSIGNED DEFAULT NULL,
  `nama_peminjam` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `id_buku` bigint UNSIGNED NOT NULL,
  `jumlah_pinjam` int NOT NULL,
  `status` enum('pending','approved','rejected','borrowed','returned','overdue','suspended','perpanjang_pending','return_pending') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `durasi_pinjam` int NOT NULL DEFAULT '7',
  `late_days` int NOT NULL DEFAULT '0',
  `perpanjang_durasi` int DEFAULT NULL,
  `perpanjang_catatan` text COLLATE utf8mb4_unicode_ci,
  `perpanjang_prev_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `penalty` int NOT NULL DEFAULT '0',
  `catatan_admin` text COLLATE utf8mb4_unicode_ci,
  `catatan_user` text COLLATE utf8mb4_unicode_ci,
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`id_pinjam`, `id_user`, `nama_peminjam`, `tanggal_pinjam`, `id_buku`, `jumlah_pinjam`, `status`, `created_at`, `updated_at`, `due_date`, `return_date`, `durasi_pinjam`, `late_days`, `perpanjang_durasi`, `perpanjang_catatan`, `perpanjang_prev_status`, `penalty`, `catatan_admin`, `catatan_user`, `approved_by`, `approved_at`) VALUES
(1, 2, 'Abu Nawas', '2026-05-26', 3, 2, 'returned', '2026-05-25 22:43:27', '2026-05-25 22:44:08', '2026-06-02', '2026-05-26', 7, 0, NULL, NULL, NULL, 0, 'yayaya', 'saya pingin pinjam ini', 1, '2026-05-25 22:43:55'),
(2, 2, 'Abu Nawas', '2026-05-26', 6, 1, 'returned', '2026-05-25 22:44:21', '2026-05-25 22:46:00', '2026-06-05', '2026-05-26', 7, 0, NULL, NULL, NULL, 0, 'ok', NULL, 1, '2026-05-25 22:44:59'),
(3, 2, 'Abu Nawas', '2026-05-26', 1, 2, 'returned', '2026-05-25 22:46:13', '2026-05-25 23:10:55', '2026-06-09', '2026-05-26', 14, 0, NULL, NULL, NULL, 0, NULL, NULL, 1, '2026-05-25 22:46:52'),
(4, 2, 'Abu Nawas', '2026-05-26', 2, 1, 'rejected', '2026-05-25 22:46:35', '2026-05-25 22:47:00', NULL, NULL, 7, 0, NULL, NULL, NULL, 0, 'mls', 'tolong', 1, '2026-05-25 22:47:00'),
(6, 2, 'Abu Nawas', '2026-05-26', 6, 1, 'rejected', '2026-05-25 23:06:27', '2026-05-25 23:06:39', NULL, NULL, 3, 0, NULL, NULL, NULL, 0, 'tidak boleh', NULL, 1, '2026-05-25 23:06:39'),
(9, 2, 'Abu Nawas', '2026-05-26', 4, 1, 'returned', '2026-05-25 23:27:01', '2026-05-25 23:29:44', '2026-06-01', '2026-05-26', 3, 0, NULL, NULL, NULL, 0, NULL, NULL, 1, '2026-05-25 23:29:09'),
(11, 2, 'Abu Nawas', '2026-05-26', 8, 1, 'returned', '2026-05-26 01:00:09', '2026-05-26 01:08:51', '2026-05-29', '2026-05-26', 3, 0, NULL, NULL, NULL, 0, NULL, NULL, 1, '2026-05-26 01:08:51'),
(13, 3, 'Bradon Jonathans', '2026-05-26', 2, 1, 'returned', '2026-05-26 01:19:47', '2026-05-26 01:20:11', '2026-05-29', '2026-05-26', 3, 0, NULL, NULL, NULL, 0, NULL, NULL, 1, '2026-05-26 01:19:53'),
(14, 2, 'Abu Nawas', '2026-05-26', 6, 1, 'returned', '2026-05-26 01:23:27', '2026-05-26 01:23:58', '2026-05-29', '2026-05-26', 3, 0, NULL, NULL, NULL, 0, NULL, NULL, 1, '2026-05-26 01:23:58');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `label`, `created_at`, `updated_at`) VALUES
(1, 'durasi_pinjam_default', '3', 'Durasi Pinjam Default (hari)', '2026-05-25 22:06:30', '2026-05-25 23:03:57'),
(2, 'durasi_pinjam_max', '30', 'Durasi Pinjam Maksimal (hari)', '2026-05-25 22:06:30', '2026-05-25 22:06:30'),
(3, 'denda_aktif', '0', 'Aktifkan Sistem Denda', '2026-05-25 22:06:30', '2026-05-25 22:06:30'),
(4, 'denda_per_hari', '1000', 'Denda Per Hari (Rp)', '2026-05-25 22:06:30', '2026-05-25 22:06:30'),
(5, 'max_pinjam_aktif', '3', 'Maks Buku Dipinjam Sekaligus', '2026-05-25 22:06:30', '2026-05-25 22:06:30'),
(6, 'nama_perpustakaan', 'Perpustakaan', 'Nama Perpustakaan', '2026-05-25 22:06:30', '2026-05-25 22:06:30');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` bigint UNSIGNED NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_lengkap` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `plain_password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `level` enum('admin','petugas','member') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'member',
  `can_borrow` tinyint(1) NOT NULL DEFAULT '1',
  `need_admin_approval` tinyint(1) NOT NULL DEFAULT '0',
  `violation_points` int NOT NULL DEFAULT '0',
  `status` enum('active','suspended','banned') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `suspended_at` timestamp NULL DEFAULT NULL,
  `suspend_reason` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `nama_lengkap`, `email`, `password`, `plain_password`, `level`, `can_borrow`, `need_admin_approval`, `violation_points`, `status`, `created_at`, `updated_at`, `phone`, `address`, `avatar`, `suspended_at`, `suspend_reason`) VALUES
(1, 'admin', 'Administrator', 'admin@perpustakaan.com', '$2y$12$eNqh7xZNBPR8XpTC.ejh6uBc8KcHFdHbO8dG0MIgZLtxVnSvLdOb.', NULL, 'admin', 0, 0, 0, 'active', '2026-05-25 22:06:31', '2026-05-25 22:06:31', NULL, NULL, NULL, NULL, NULL),
(2, 'Ana', 'Abu Nawas', 'ana@gmail.com', '$2y$12$/Z02s9orarNd.USvf8ga1u71HRK/Fixm7wMMqhTmE3fOI7P4a3fbi', '123456', 'member', 1, 0, 0, 'active', '2026-05-25 22:25:01', '2026-05-25 22:25:01', NULL, NULL, NULL, NULL, NULL),
(3, 'Brado', 'Bradon Jonathans', 'bradpitt@gmail.com', '$2y$12$R3XmOwjgZQ11lV/p/v1SZeCMHYhMSlB9P7Qj63SgX3hkWLq8DRC2S', '654321', 'member', 1, 0, 0, 'active', '2026-05-25 22:25:37', '2026-05-25 22:25:37', NULL, NULL, NULL, NULL, NULL),
(4, 'Cou', 'Chou Liy Maakay', 'ch@gmail.com', '$2y$12$fEHm1HnKd2KEbY9OM51yvuXygjgCYV5h3vnK09Bf3QgZ5Gjrd3GtK', '09876543', 'member', 1, 0, 0, 'active', '2026-05-25 22:26:25', '2026-05-25 22:26:25', NULL, NULL, NULL, NULL, NULL),
(5, 'Dapa', 'Daffa Alfarazi', 'dappp@gmail.com', '$2y$12$aEruAb4q6yb.6P/ZfaG3vedsg5nzMggTXHawk5rqhVN75QNrvLgey', '123456', 'member', 1, 0, 0, 'active', '2026-05-25 22:27:07', '2026-05-25 22:27:07', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `id` bigint UNSIGNED NOT NULL,
  `id_user` bigint UNSIGNED NOT NULL,
  `id_buku` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wishlists`
--

INSERT INTO `wishlists` (`id`, `id_user`, `id_buku`, `created_at`, `updated_at`) VALUES
(2, 2, 2, '2026-05-26 00:36:20', '2026-05-26 00:36:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id_buku`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifikasi_id_user_foreign` (`id_user`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id_pinjam`),
  ADD KEY `peminjaman_id_user_foreign` (`id_user`),
  ADD KEY `peminjaman_id_buku_foreign` (`id_buku`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `user_username_unique` (`username`),
  ADD UNIQUE KEY `user_email_unique` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `wishlists_id_user_id_buku_unique` (`id_user`,`id_buku`),
  ADD KEY `wishlists_id_buku_foreign` (`id_buku`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `id_buku` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id_pinjam` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD CONSTRAINT `notifikasi_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_id_buku_foreign` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE,
  ADD CONSTRAINT `peminjaman_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE SET NULL;

--
-- Constraints for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD CONSTRAINT `wishlists_id_buku_foreign` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlists_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
