-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 27, 2025 at 04:36 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tamago`
--

-- --------------------------------------------------------

--
-- Table structure for table `bimbingans`
--

CREATE TABLE `bimbingans` (
  `id_bimbingan` bigint UNSIGNED NOT NULL,
  `topik` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan_mahasiswa` text COLLATE utf8mb4_unicode_ci,
  `catatan_dosen` text COLLATE utf8mb4_unicode_ci,
  `file_pendukung` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `id_proyek_akhir` bigint UNSIGNED DEFAULT NULL,
  `mahasiswa_id` bigint UNSIGNED DEFAULT NULL,
  `nim` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nidn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal` date NOT NULL,
  `waktu_mulai` time DEFAULT NULL,
  `waktu_selesai` time DEFAULT NULL,
  `ruang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan_bimbingan` text COLLATE utf8mb4_unicode_ci,
  `pencapaian` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `dosen_nidn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bimbingans`
--

INSERT INTO `bimbingans` (`id_bimbingan`, `topik`, `catatan_mahasiswa`, `catatan_dosen`, `file_pendukung`, `status`, `id_proyek_akhir`, `mahasiswa_id`, `nim`, `nidn`, `tanggal`, `waktu_mulai`, `waktu_selesai`, `ruang`, `catatan_bimbingan`, `pencapaian`, `created_at`, `updated_at`, `dosen_nidn`) VALUES
(1, 'bab 1', 'pak bab 1 gmn?', NULL, 'bimbingan_files/qo8ayLhvIP9BFew8uQFWtpYttCwcIiQaK7AzJOCk.pdf', 'disetujui', NULL, NULL, NULL, NULL, '2025-11-27', '09:00:00', NULL, NULL, NULL, NULL, '2025-11-26 20:22:53', '2025-11-27 06:17:18', NULL),
(2, 'Diskusi Proposal', NULL, NULL, NULL, 'pending', NULL, NULL, '71220022', NULL, '2025-11-30', '14:00:00', '15:00:00', NULL, NULL, NULL, '2025-11-26 20:43:53', '2025-11-26 20:43:53', 'NIDN001'),
(3, 'Revisi Bab 3', NULL, NULL, NULL, 'approved', NULL, NULL, '71220022', NULL, '2025-12-04', '15:30:00', '16:30:00', NULL, NULL, NULL, '2025-11-26 20:43:53', '2025-11-26 20:43:53', 'NIDN001'),
(4, 'bab 1', 'bab 1', 'oke aman', 'bimbingan_files/Uaeeijow2ARW6YA0WCOgXlohTYbsGbHsmhuK3HK2.pdf', 'disetujui', NULL, NULL, NULL, NULL, '2025-11-29', '09:00:00', NULL, NULL, NULL, NULL, '2025-11-27 06:12:59', '2025-11-27 06:28:54', NULL),
(5, 'latar belakang', 'latar belakang gmn pak?', NULL, 'bimbingan_files/5zFmx2IRwAVpWBAFkGPYK8SH6XUEpeTkVUeErwIf.pdf', 'ditolak', NULL, NULL, NULL, NULL, '2025-11-28', '09:00:00', NULL, NULL, NULL, NULL, '2025-11-27 06:30:49', '2025-11-27 07:24:40', NULL),
(6, 'BAB 1', 'aaadadadadada', NULL, 'bimbingan_files/WepNnGFlS7E6jkYr64JHLke1OP3m9hhHsNS6n5Pg.pdf', 'disetujui', NULL, NULL, NULL, NULL, '2025-11-28', '11:00:00', NULL, NULL, NULL, NULL, '2025-11-27 08:04:20', '2025-11-27 08:20:24', NULL),
(7, 'bab 2', 'gimana?', NULL, 'bimbingan_files/ZD6JccH1BpfxkcEYAZ9DZtZZcjN24GXzYXXvQZfo.pdf', 'disetujui', NULL, NULL, '71240099', NULL, '2025-11-28', '13:00:00', NULL, NULL, NULL, NULL, '2025-11-27 08:30:03', '2025-11-27 08:31:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dosen`
--

CREATE TABLE `dosen` (
  `nidn` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jabatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rumpun_ilmu` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dosen`
--

INSERT INTO `dosen` (`nidn`, `nama`, `jabatan`, `rumpun_ilmu`, `created_at`, `updated_at`, `status`) VALUES
('12345', 'Dosen 1', 'Dosen', 'Informatika', '2025-11-25 09:21:18', '2025-11-25 09:21:18', 'aktif'),
('54321', 'Dosen 2', 'Dosen', 'Informatika', '2025-11-25 09:21:18', '2025-11-25 09:21:18', 'aktif');

-- --------------------------------------------------------

--
-- Table structure for table `dosen_penguji`
--

CREATE TABLE `dosen_penguji` (
  `id_penguji` bigint UNSIGNED NOT NULL,
  `id_ujian` bigint UNSIGNED NOT NULL,
  `nidn` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nilai` int DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `peran_penguji` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` bigint UNSIGNED NOT NULL,
  `uploaded_by` bigint UNSIGNED NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `id` bigint UNSIGNED NOT NULL,
  `mahasiswa_id` bigint UNSIGNED NOT NULL,
  `nidn` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `tempat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `topik` text COLLATE utf8mb4_unicode_ci,
  `status` enum('menunggu','disetujui','ditolak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `approved_at` timestamp NULL DEFAULT NULL,
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `rejected_by` bigint UNSIGNED DEFAULT NULL,
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `nim` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jadwals`
--

CREATE TABLE `jadwals` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `mahasiswa_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_acaras`
--

CREATE TABLE `jadwal_acaras` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime DEFAULT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
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
-- Table structure for table `luaran`
--

CREATE TABLE `luaran` (
  `id_luaran` bigint UNSIGNED NOT NULL,
  `id_proyek_akhir` bigint UNSIGNED NOT NULL,
  `jenis_luaran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_unggah` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `nim` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `dosen_pembimbing_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`nim`, `user_id`, `nama`, `email`, `status`, `created_at`, `updated_at`, `dosen_pembimbing_id`) VALUES
('0149787380', 84, 'Citlalli Mayert', 'chesley32@example.net', 'aktif', '2025-11-25 09:21:19', '2025-11-25 09:21:19', '54321'),
('0331442343', 56, 'Lew Metz', 'ebernhard@example.org', 'non-aktif', '2025-11-25 09:21:19', '2025-11-25 09:21:19', '12345'),
('0844173530', 73, 'Ocie Stehr', 'walsh.felipa@example.com', 'non-aktif', '2025-11-25 09:21:19', '2025-11-25 09:21:19', '12345'),
('0860212229', 54, 'Miss Petra Upton IV', 'aschoen@example.net', 'non-aktif', '2025-11-25 09:21:19', '2025-11-25 09:21:19', '12345'),
('12345678', NULL, 'adminnn', 'admin@gmail.com', 'aktif', '2025-11-25 09:21:18', '2025-11-25 09:21:18', NULL),
('1615421633', 63, 'Ofelia Nicolas III', 'bartholome.kuhn@example.org', 'lulus', '2025-11-25 09:21:19', '2025-11-25 09:21:19', '12345'),
('1649076719', 64, 'Jordon Reynolds', 'xschmitt@example.org', 'non-aktif', '2025-11-25 09:21:19', '2025-11-25 09:21:19', '54321'),
('1737327631', 77, 'Colby Jast', 'rudy09@example.com', 'non-aktif', '2025-11-25 09:21:19', '2025-11-25 09:21:19', '12345'),
('1838971706', 78, 'Mr. Murphy Douglas IV', 'marques70@example.org', 'lulus', '2025-11-25 09:21:19', '2025-11-25 09:21:19', '54321'),
('1892386437', 81, 'Kari Rau V', 'emard.pietro@example.net', 'aktif', '2025-11-25 09:21:19', '2025-11-25 09:21:19', '54321'),
('1991376814', 69, 'Vergie Quitzon I', 'gracie27@example.net', 'lulus', '2025-11-25 09:21:19', '2025-11-25 09:21:19', '12345'),
('2013779494', 60, 'Adan Considine', 'rogelio79@example.org', 'lulus', '2025-11-25 09:21:19', '2025-11-25 09:21:19', '12345'),
('2059163438', 86, 'Amya Olson', 'auer.breanna@example.org', 'lulus', '2025-11-25 09:21:19', '2025-11-25 09:21:19', '54321'),
('2073960187', 91, 'Cornell Green', 'sthiel@example.com', 'aktif', '2025-11-25 09:21:19', '2025-11-25 09:21:19', '54321'),
('2133296988', 83, 'Orpha Rolfson', 'lehner.misty@example.org', 'lulus', '2025-11-25 09:21:19', '2025-11-25 09:21:19', '12345'),
('2141795059', 68, 'Andreane Mertz', 'bauch.presley@example.net', 'lulus', '2025-11-25 09:21:19', '2025-11-25 09:21:19', '54321'),
('2249586871', 79, 'Berenice Hamill DVM', 'schimmel.genevieve@example.net', 'non-aktif', '2025-11-25 09:21:19', '2025-11-25 09:21:19', '12345'),
('2408392810', 74, 'Lauretta Stokes', 'bogan.derick@example.org', 'non-aktif', '2025-11-25 09:21:19', '2025-11-25 09:21:19', '12345'),
('2844017017', 57, 'Jonathan Lesch MD', 'kessler.elnora@example.net', 'aktif', '2025-11-25 09:21:19', '2025-11-25 09:21:19', '12345'),
('3572715514', 75, 'Carlee Roberts', 'hank38@example.org', 'lulus', '2025-11-25 09:21:19', '2025-11-25 09:21:19', '54321'),
('3718023757', 62, 'Mrs. Amy Graham DDS', 'bins.delores@example.net', 'aktif', '2025-11-25 09:21:19', '2025-11-25 09:21:19', '12345'),
('3767504410', 59, 'Prof. Kailee Reinger', 'kunde.mae@example.com', 'non-aktif', '2025-11-25 09:21:19', '2025-11-25 09:21:19', '54321'),
('5475940608', 70, 'Anderson Mills', 'brock90@example.com', 'non-aktif', '2025-11-25 09:21:19', '2025-11-25 09:21:19', '12345'),
('5617826146', 55, 'David Graham V', 'iheller@example.net', 'non-aktif', '2025-11-25 09:21:19', '2025-11-25 09:21:19', '54321'),
('5639867582', 65, 'Jett Roberts V', 'macey.frami@example.org', 'aktif', '2025-11-25 09:21:19', '2025-11-25 09:21:19', '54321'),
('6017540100', 90, 'Earl Swift', 'shanon.lynch@example.com', 'non-aktif', '2025-11-25 09:21:19', '2025-11-25 09:21:19', '54321'),
('6292418046', 85, 'Bennie Ryan', 'era.krajcik@example.com', 'non-aktif', '2025-11-25 09:21:19', '2025-11-25 09:21:19', '12345'),
('6694400903', 72, 'Mr. Antone Ankunding', 'shania25@example.net', 'lulus', '2025-11-25 09:21:19', '2025-11-25 09:21:19', '12345'),
('6853332047', 80, 'Alvah Klocko', 'bogisich.danny@example.net', 'aktif', '2025-11-25 09:21:19', '2025-11-25 09:21:19', '12345'),
('6855477000', 89, 'Prof. Melvin Walker II', 'dion.smitham@example.net', 'aktif', '2025-11-25 09:21:19', '2025-11-25 09:21:19', '54321'),
('7025021252', 71, 'Connor Green', 'hickle.leonor@example.org', 'lulus', '2025-11-25 09:21:19', '2025-11-25 09:21:19', '54321'),
('7092588317', 87, 'Danielle Barton', 'irving.tremblay@example.com', 'non-aktif', '2025-11-25 09:21:19', '2025-11-25 09:21:19', '12345'),
('71220022', 114, 'debora', 'debora@student.isi.ac.id', 'Aktif', '2025-11-26 20:15:20', '2025-11-26 20:43:53', 'NIDN001'),
('71220099', 116, 'ananda', 'ananda@student.isi.ac.id', 'aktif', '2025-11-27 05:23:02', '2025-11-27 05:23:02', NULL),
('71240099', 117, 'angga', 'angga@student.isi.ac.id', 'bimbingan_disetujui', '2025-11-27 06:12:03', '2025-11-27 08:31:23', NULL),
('7606305174', 61, 'Dejon Breitenberg', 'jgutmann@example.net', 'lulus', '2025-11-25 09:21:19', '2025-11-25 09:21:19', '12345'),
('7848003305', 67, 'Kariane Hill', 'akunze@example.com', 'aktif', '2025-11-25 09:21:19', '2025-11-25 09:21:19', '54321'),
('7874057510', 82, 'Carmela Hudson', 'kessler.antonette@example.org', 'aktif', '2025-11-25 09:21:19', '2025-11-25 09:21:19', '12345'),
('8025606168', 58, 'Darien Hills', 'alexandria79@example.net', 'lulus', '2025-11-25 09:21:19', '2025-11-25 09:21:19', '54321'),
('8489503276', 88, 'Iliana Dare', 'reynolds.albina@example.org', 'non-aktif', '2025-11-25 09:21:19', '2025-11-25 09:21:19', '12345'),
('8659847564', 66, 'Daphne Boyle DDS', 'chaya.weimann@example.com', 'non-aktif', '2025-11-25 09:21:19', '2025-11-25 09:21:19', '12345'),
('8779612017', 76, 'Haylie Hettinger', 'kbrown@example.net', 'lulus', '2025-11-25 09:21:19', '2025-11-25 09:21:19', '12345');

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
(4, '2025_10_06_030744_create_mahasiswa_table', 1),
(5, '2025_10_06_030748_create_dosen_table', 1),
(6, '2025_10_06_030750_create_proposals_table', 1),
(7, '2025_10_06_030751_create_projek_akhir_table', 1),
(8, '2025_10_06_030756_create_bimbingan_table', 1),
(9, '2025_10_06_030800_create_tim_produksi_table', 1),
(10, '2025_10_06_030805_create_tefa_fair_table', 1),
(11, '2025_10_06_030809_create_luaran_table', 1),
(12, '2025_10_06_030812_create_story_conference_table', 1),
(13, '2025_10_06_030816_create_ujian_tugas_akhir_table', 1),
(14, '2025_10_06_030819_create_dosen_penguji_table', 1),
(15, '2025_10_06_030824_create_revisi_table', 1),
(16, '2025_10_28_171420_create_proposals_table', 1),
(17, '2025_10_28_173204_create_roles_and_update_users_table', 1),
(18, '2025_10_29_161329_create_ta_progress_stages_table', 1),
(19, '2025_10_29_161345_create_student_progress_table', 1),
(20, '2025_10_31_062835_add_status_to_dosen_table', 1),
(21, '2025_11_03_025155_add_role_id_to_users_table', 1),
(22, '2025_11_04_060253_add_mahasiswa_id_to_bimbingans_table', 1),
(23, '2025_11_04_063456_add_topik_to_bimbingans_table', 1),
(24, '2025_11_04_064138_add_missing_fields_to_bimbingans_table', 1),
(25, '2025_11_05_164642_add_mahasiswa_and_proposal_to_story_conference_table', 1),
(26, '2025_11_06_031910_add_waktu_and_file_to_story_conference_table', 1),
(27, '2025_11_10_165642_add_user_id_to_mahasiswa_table', 1),
(28, '2025_11_11_041852_update_bimbingans_table_add_missing_columns', 1),
(29, '2025_11_11_042246_cleanup_and_complete_bimbingans_table', 1),
(30, '2025_11_11_050000_add_user_id_to_mahasiswa_table', 1),
(31, '2025_11_11_064500_fix_story_conference_mahasiswa_id_constraint', 1),
(32, '2025_11_11_070000_make_story_conference_columns_nullable', 1),
(33, '2025_11_11_165613_make_id_proyek_akhir_nullable_in_bimbingans_table', 1),
(34, '2025_11_12_120000_add_naskah_fields_to_projek_akhir', 1),
(35, '2025_11_12_155000_add_ujian_tugas_akhir_columns', 1),
(36, '2025_11_12_160000_add_dosen_pembimbing_to_ujian_tugas_akhir', 1),
(37, '2025_11_13_000001_add_theme_and_avatar_to_users', 1),
(38, '2025_11_13_010000_make_tanggal_ujian_nullable', 1),
(39, '2025_11_13_062151_create_jadwals_table', 1),
(40, '2025_11_13_064658_create_jadwal_acaras_table', 1),
(41, '2025_11_18_000001_add_dosen_pembimbing_id_to_mahasiswa_table', 1),
(42, '2025_11_18_000001_add_mahasiswa_id_to_projek_akhir_table', 1),
(43, '2025_11_18_000002_add_dosen_nidn_to_bimbingans_table', 1),
(44, '2025_11_18_000002_add_primary_key_to_projek_akhir_table', 1),
(45, '2025_11_18_000003_add_proposal_id_to_projek_akhir_table', 1),
(46, '2025_11_20_071540_add_mahasiswa_id_to_bimbingans_table', 1),
(47, '2025_11_20_072214_create_files_table', 1),
(48, '2025_11_25_164600_add_prodi_angkatan_to_mahasiswa_table', 2),
(49, '2025_11_26_000000_drop_prodi_angkatan_from_mahasiswa', 3),
(50, '2025_11_27_121004_add_status_to_mahasiswa_table', 4),
(51, '2025_11_27_121943_add_default_value_to_status_in_mahasiswa_table', 5),
(52, '2025_11_27_180000_create_jadwal_bimbingan_table', 6);

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
-- Table structure for table `projek_akhir`
--

CREATE TABLE `projek_akhir` (
  `id_proyek_akhir` bigint UNSIGNED NOT NULL,
  `nim` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nidn1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nidn2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_proposal` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_naskah_publikasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_jurnal` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_upload_naskah` timestamp NULL DEFAULT NULL,
  `file_pitch_deck` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_story_bible` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `mahasiswa_id` bigint UNSIGNED DEFAULT NULL,
  `proposal_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projek_akhir`
--

INSERT INTO `projek_akhir` (`id_proyek_akhir`, `nim`, `nidn1`, `nidn2`, `judul`, `file_proposal`, `file_naskah_publikasi`, `link_jurnal`, `tanggal_upload_naskah`, `file_pitch_deck`, `file_story_bible`, `status`, `created_at`, `updated_at`, `mahasiswa_id`, `proposal_id`) VALUES
(1, '12345678', '12345', '54321', 'Test Judul', NULL, NULL, NULL, NULL, NULL, NULL, 'berjalan', '2025-11-25 09:21:18', '2025-11-25 09:21:18', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `proposals`
--

CREATE TABLE `proposals` (
  `id` bigint UNSIGNED NOT NULL,
  `mahasiswa_nim` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dosen_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_proposal` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_pitch_deck` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `versi` int NOT NULL DEFAULT '1',
  `status` enum('draft','diajukan','review','revisi','disetujui','ditolak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `tanggal_pengajuan` timestamp NULL DEFAULT NULL,
  `tanggal_review` timestamp NULL DEFAULT NULL,
  `feedback` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `proposals`
--

INSERT INTO `proposals` (`id`, `mahasiswa_nim`, `dosen_id`, `judul`, `deskripsi`, `file_proposal`, `file_pitch_deck`, `versi`, `status`, `tanggal_pengajuan`, `tanggal_review`, `feedback`, `created_at`, `updated_at`) VALUES
(1, '71220022', NULL, 'apapap', 'apapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapapap', 'proposals/71220022/proposal_v1_1764213733.pdf', 'pitch-decks/71220022/pitchdeck_v1_1764213733.pdf', 1, 'diajukan', '2025-11-26 20:22:13', NULL, '', '2025-11-26 20:22:13', '2025-11-26 20:22:13'),
(2, '71240099', NULL, 'yayaya', 'yayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayayaya', 'proposals/71240099/proposal_v1_1764249149.pdf', 'pitch-decks/71240099/pitchdeck_v1_1764249149.pdf', 1, 'disetujui', '2025-11-27 06:12:29', '2025-11-27 08:03:29', 'oke sudah bagus', '2025-11-27 06:12:29', '2025-11-27 08:03:29');

-- --------------------------------------------------------

--
-- Table structure for table `revisi`
--

CREATE TABLE `revisi` (
  `id_revisi` bigint UNSIGNED NOT NULL,
  `id_ujian` bigint UNSIGNED NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'mahasiswa', 'Mahasiswa', 'Mahasiswa yang sedang mengerjakan TA', '2025-11-25 09:21:15', '2025-11-25 09:21:15'),
(2, 'dospem', 'Dosen Pembimbing', 'Dosen pembimbing tugas akhir', '2025-11-25 09:21:15', '2025-11-25 09:21:15'),
(3, 'kaprodi', 'Koordinator Prodi', 'Koordinator program studi', '2025-11-25 09:21:15', '2025-11-25 09:21:15'),
(4, 'koordinator_ta', 'Koordinator TA', 'Koordinator tugas akhir fakultas', '2025-11-25 09:21:15', '2025-11-25 09:21:15'),
(5, 'admin', 'Admin', 'Administrator sistem', '2025-11-25 09:21:15', '2025-11-25 09:21:15'),
(6, 'dosen_penguji', 'Dosen Penguji', 'Dosen penguji ujian TA', '2025-11-25 09:21:15', '2025-11-25 09:21:15');

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

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('dib35kVYPevc74NAm45GkyLGN6luGd2Gcf0snGmj', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.106.3 Chrome/138.0.7204.251 Electron/37.7.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRDBPUGNzOFJYbFo3MnpSdTM4bnJiWFR4alMxUHVuSDJNaHl2YkdnYiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoxMzE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9kb3NwZW0vbWFoYXNpc3dhLWJpbWJpbmdhbi8yMzEwMTAwMDE/aWQ9ZjFkMDU5YTItMmRlNi00NWIzLTgzYTAtZDhjOTIzZGMyM2ViJnZzY29kZUJyb3dzZXJSZXFJZD0xNzY0MjU1MDg5MTA2Ijt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTMxOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvZG9zcGVtL21haGFzaXN3YS1iaW1iaW5nYW4vMjMxMDEwMDAxP2lkPWYxZDA1OWEyLTJkZTYtNDViMy04M2EwLWQ4YzkyM2RjMjNlYiZ2c2NvZGVCcm93c2VyUmVxSWQ9MTc2NDI1NTA4OTEwNiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1764255089),
('hxglXvcLGS3bBwljClXuuSlXk69OWoT4ipRTsIhJ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.106.3 Chrome/138.0.7204.251 Electron/37.7.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQW1wZDllQkxzbzVCeUlGaUF1eTg1SGhIQktVcE5XVGRzdFZqNW9iSyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1764255090),
('oG8Njns3YE0r3njjTkcAmwpHINeOyXn18SFEBTfC', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.106.3 Chrome/138.0.7204.251 Electron/37.7.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiemlhNHU5UjhvazM5OXdCWU1kYUY5eDd5ZjVCUk1Rd01rYzU4Yjg3dSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1764255386),
('PzuSha2DSAeCmGUA7bV16ouUVs5IabHcj3jYWW6A', 115, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRkxER2ZMU3FDdThweUE3dTFQenhxa2dSOExDVEp2S3VDSlBFZlV4MCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kb3NwZW0vbWFoYXNpc3dhLWJpbWJpbmdhbi83MTI0MDA5OSI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjExNTt9', 1764261235),
('QkL1q02es5vo3tjQTXzxJ4QKNu6hyIcuPUxQrRSY', 117, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUW4xeXc4c1BoNFQ3ME5FSk9UazBJdlhiQnRaZ09oeWZMUFdGRWFISSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYWhhc2lzd2EvcHJvZHVrc2kiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxMTc7fQ==', 1764257549),
('zds6vv8WjVyy4w9f2ErbewGlUelHiCzT5cRy33R4', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.106.3 Chrome/138.0.7204.251 Electron/37.7.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoidTFlYmk2S3psbGE1clFiQ213UkZtV2IzV3pQY3E4MmJPbWx2NXdRZyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoxMjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9kb3NwZW0vbWFoYXNpc3dhLWJpbWJpbmdhbj9pZD0wNmU4YWVmNi1mMjkzLTQ1NDktODBmYS1lZGVhNGRkNzUzZmEmdnNjb2RlQnJvd3NlclJlcUlkPTE3NjQyNTUzODU3MjMiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoxMjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9kb3NwZW0vbWFoYXNpc3dhLWJpbWJpbmdhbj9pZD0wNmU4YWVmNi1mMjkzLTQ1NDktODBmYS1lZGVhNGRkNzUzZmEmdnNjb2RlQnJvd3NlclJlcUlkPTE3NjQyNTUzODU3MjMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1764255386);

-- --------------------------------------------------------

--
-- Table structure for table `story_conference`
--

CREATE TABLE `story_conference` (
  `id_conference` bigint UNSIGNED NOT NULL,
  `mahasiswa_id` bigint UNSIGNED DEFAULT NULL,
  `proposal_id` bigint UNSIGNED NOT NULL,
  `dosen_id` bigint UNSIGNED DEFAULT NULL,
  `mahasiswa_nim` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `proposals_id` bigint UNSIGNED DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `catatan_evaluasi` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `judul_karya` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slot_waktu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_presentasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_daftar` timestamp NULL DEFAULT NULL,
  `tanggal_review` timestamp NULL DEFAULT NULL,
  `catatan_panitia` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ruang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `waktu_presentasi` timestamp NULL DEFAULT NULL,
  `waktu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `story_conference`
--

INSERT INTO `story_conference` (`id_conference`, `mahasiswa_id`, `proposal_id`, `dosen_id`, `mahasiswa_nim`, `proposals_id`, `tanggal`, `catatan_evaluasi`, `status`, `created_at`, `updated_at`, `judul_karya`, `slot_waktu`, `file_presentasi`, `tanggal_daftar`, `tanggal_review`, `catatan_panitia`, `ruang`, `waktu_presentasi`, `waktu`, `file`) VALUES
(1, NULL, 2, NULL, '71240099', 2, NULL, NULL, 'menunggu_persetujuan', '2025-11-27 08:13:18', '2025-11-27 08:13:18', 'yayaya', '15 Desember 2024 - 08:00-17:00', 'story-conference/71240099/storyconf_1764256398.pdf', '2025-11-27 08:13:18', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student_progress`
--

CREATE TABLE `student_progress` (
  `id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ta_progress_stages`
--

CREATE TABLE `ta_progress_stages` (
  `id` bigint UNSIGNED NOT NULL,
  `stage_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stage_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `weight` decimal(5,2) NOT NULL,
  `sequence` int NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ta_progress_stages`
--

INSERT INTO `ta_progress_stages` (`id`, `stage_code`, `stage_name`, `description`, `weight`, `sequence`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'proposal_submission', 'Pengajuan Proposal', 'Mahasiswa mengajukan proposal TA', 15.00, 1, 1, '2025-11-25 09:21:15', '2025-11-25 09:21:15'),
(2, 'proposal_approved', 'Proposal Disetujui', 'Proposal telah disetujui oleh pembimbing/kaprodi', 10.00, 2, 1, '2025-11-25 09:21:15', '2025-11-25 09:21:15'),
(3, 'bimbingan_progress', 'Bimbingan (Min. 8x)', 'Melakukan bimbingan minimal 8 kali', 25.00, 3, 1, '2025-11-25 09:21:15', '2025-11-25 09:21:15'),
(4, 'story_conference', 'Story Conference', 'Mengikuti story conference', 10.00, 4, 1, '2025-11-25 09:21:15', '2025-11-25 09:21:15'),
(5, 'production_upload', 'Upload Produksi', 'Mengunggah hasil produksi/karya', 15.00, 5, 1, '2025-11-25 09:21:15', '2025-11-25 09:21:15'),
(6, 'exam_registration', 'Pendaftaran Ujian TA', 'Mendaftar ujian tugas akhir', 10.00, 6, 1, '2025-11-25 09:21:15', '2025-11-25 09:21:15'),
(7, 'exam_completed', 'Ujian TA Selesai', 'Telah melaksanakan ujian TA', 10.00, 7, 1, '2025-11-25 09:21:15', '2025-11-25 09:21:15'),
(8, 'final_submission', 'Submit Naskah & Karya Final', 'Mengunggah naskah dan karya final', 5.00, 8, 1, '2025-11-25 09:21:15', '2025-11-25 09:21:15');

-- --------------------------------------------------------

--
-- Table structure for table `tefa_fair`
--

CREATE TABLE `tefa_fair` (
  `id_tefa` bigint UNSIGNED NOT NULL,
  `id_proyek_akhir` bigint UNSIGNED NOT NULL,
  `semester` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_presentasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `daftar_kebutuhan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tim_produksi`
--

CREATE TABLE `tim_produksi` (
  `id` bigint UNSIGNED NOT NULL,
  `mahasiswa_id` bigint UNSIGNED NOT NULL,
  `proposal_id` bigint UNSIGNED NOT NULL,
  `dosen_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_skenario` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_storyboard` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_dokumen_pendukung` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_produksi_akhir` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_luaran_tambahan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan_produksi` text COLLATE utf8mb4_unicode_ci,
  `status_pra_produksi` enum('belum_upload','menunggu_review','disetujui','revisi','ditolak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum_upload',
  `status_produksi_akhir` enum('belum_upload','menunggu_review','disetujui','revisi','ditolak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum_upload',
  `tanggal_upload_pra` timestamp NULL DEFAULT NULL,
  `tanggal_upload_akhir` timestamp NULL DEFAULT NULL,
  `tanggal_review_pra` timestamp NULL DEFAULT NULL,
  `tanggal_review_akhir` timestamp NULL DEFAULT NULL,
  `feedback_pra_produksi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `feedback_produksi_akhir` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tim_produksi`
--

INSERT INTO `tim_produksi` (`id`, `mahasiswa_id`, `proposal_id`, `dosen_id`, `file_skenario`, `file_storyboard`, `file_dokumen_pendukung`, `file_produksi_akhir`, `file_luaran_tambahan`, `catatan_produksi`, `status_pra_produksi`, `status_produksi_akhir`, `tanggal_upload_pra`, `tanggal_upload_akhir`, `tanggal_review_pra`, `tanggal_review_akhir`, `feedback_pra_produksi`, `feedback_produksi_akhir`, `created_at`, `updated_at`) VALUES
(1, 117, 2, NULL, 'produksi/117/skenario_1764257549.pdf', 'produksi/117/storyboard_1764257549.pdf', 'produksi/117/dokumen_1764257549.pdf', NULL, NULL, NULL, 'menunggu_review', 'belum_upload', '2025-11-27 08:32:29', NULL, NULL, NULL, '', '', '2025-11-27 08:32:29', '2025-11-27 08:32:29');

-- --------------------------------------------------------

--
-- Table structure for table `ujian_tugas_akhir`
--

CREATE TABLE `ujian_tugas_akhir` (
  `id_ujian` bigint UNSIGNED NOT NULL,
  `id_proyek_akhir` bigint UNSIGNED NOT NULL,
  `file_surat_pengantar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_transkrip_nilai` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_revisi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_pendaftaran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pengajuan_ujian',
  `status_ujian` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum_ujian',
  `status_revisi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum_revisi',
  `tanggal_daftar` timestamp NULL DEFAULT NULL,
  `tanggal_submit_revisi` timestamp NULL DEFAULT NULL,
  `tanggal_ujian` date DEFAULT NULL,
  `hasil_akhir` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan_penguj` text COLLATE utf8mb4_unicode_ci,
  `status_kelayakan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `dosen_pembimbing_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` bigint UNSIGNED DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `theme` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'light',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role_id`, `email_verified_at`, `password`, `remember_token`, `theme`, `avatar`, `created_at`, `updated_at`) VALUES
(2, 'Dr. Sarah Wijaya', 'dospem@test.com', 2, NULL, '$2y$12$n7GuOzEPNn12bLq9GSvVHOT7ijfzOvKpZUZHCxfwQG6oAXrdF7JcS', NULL, 'light', NULL, '2025-11-25 09:21:18', '2025-11-25 09:21:18'),
(3, 'Prof. Ahmad Rahman', 'kaprodi@test.com', 3, NULL, '$2y$12$Mkb0avq..sAuuM89s0Rs7.q3dWzIqeRjPF9zc7.6WTBoQEt4nuXRK', NULL, 'light', NULL, '2025-11-25 09:21:18', '2025-11-25 09:21:18'),
(4, 'Dr. Siti Aminah', 'koordinator_ta@test.com', 4, NULL, '$2y$12$9ghxPVh4q4aL58o7gFyIIOPS3Q5.XYME6cKA/4m6olcpdMGaCjIQO', NULL, 'light', NULL, '2025-11-25 09:21:18', '2025-11-25 09:21:18'),
(5, 'Dr. Indah Permata', 'dosen_penguji@test.com', 6, NULL, '$2y$12$4QRFFO33WQZZ0QE1RmqqOOzvHaoltX41WydBUjADjPINvZalhGErm', NULL, 'light', NULL, '2025-11-25 09:21:18', '2025-11-25 09:21:18'),
(6, 'Admin System', 'admin@test.com', 5, NULL, '$2y$12$lj2DkI1DFy4/LLa9MMm98uLFTxQq9zV.ZGIBHhHB.V1G1kYWOTnC6', NULL, 'light', NULL, '2025-11-25 09:21:18', '2025-11-25 09:21:18'),
(54, 'Liliane Dickens II', 'herzog.oliver@example.org', 1, NULL, '$2y$12$jncWqZCiNlLDymgAfpRPReqJvVrt1BEFBl9cgNUopNzRVYb2d3bUy', NULL, 'light', NULL, '2025-11-25 09:21:19', '2025-11-25 09:21:19'),
(55, 'Dr. Trever Hackett', 'yankunding@example.org', 1, NULL, '$2y$12$jncWqZCiNlLDymgAfpRPReqJvVrt1BEFBl9cgNUopNzRVYb2d3bUy', NULL, 'light', NULL, '2025-11-25 09:21:19', '2025-11-25 09:21:19'),
(56, 'Florencio Herman', 'feil.bonnie@example.net', 1, NULL, '$2y$12$jncWqZCiNlLDymgAfpRPReqJvVrt1BEFBl9cgNUopNzRVYb2d3bUy', NULL, 'light', NULL, '2025-11-25 09:21:19', '2025-11-25 09:21:19'),
(57, 'Adela Deckow', 'durgan.aric@example.net', 1, NULL, '$2y$12$jncWqZCiNlLDymgAfpRPReqJvVrt1BEFBl9cgNUopNzRVYb2d3bUy', NULL, 'light', NULL, '2025-11-25 09:21:19', '2025-11-25 09:21:19'),
(58, 'Kolby Leannon', 'russel.abel@example.org', 1, NULL, '$2y$12$jncWqZCiNlLDymgAfpRPReqJvVrt1BEFBl9cgNUopNzRVYb2d3bUy', NULL, 'light', NULL, '2025-11-25 09:21:19', '2025-11-25 09:21:19'),
(59, 'Larry Durgan', 'freida.lakin@example.com', 1, NULL, '$2y$12$jncWqZCiNlLDymgAfpRPReqJvVrt1BEFBl9cgNUopNzRVYb2d3bUy', NULL, 'light', NULL, '2025-11-25 09:21:19', '2025-11-25 09:21:19'),
(60, 'Laura Collins', 'ceasar.fay@example.com', 1, NULL, '$2y$12$jncWqZCiNlLDymgAfpRPReqJvVrt1BEFBl9cgNUopNzRVYb2d3bUy', NULL, 'light', NULL, '2025-11-25 09:21:19', '2025-11-25 09:21:19'),
(61, 'Ebony Steuber', 'elliott04@example.org', 1, NULL, '$2y$12$jncWqZCiNlLDymgAfpRPReqJvVrt1BEFBl9cgNUopNzRVYb2d3bUy', NULL, 'light', NULL, '2025-11-25 09:21:19', '2025-11-25 09:21:19'),
(62, 'Maximilian Hane', 'littel.alanis@example.net', 1, NULL, '$2y$12$jncWqZCiNlLDymgAfpRPReqJvVrt1BEFBl9cgNUopNzRVYb2d3bUy', NULL, 'light', NULL, '2025-11-25 09:21:19', '2025-11-25 09:21:19'),
(63, 'Meghan Schulist Jr.', 'jailyn.marquardt@example.com', 1, NULL, '$2y$12$jncWqZCiNlLDymgAfpRPReqJvVrt1BEFBl9cgNUopNzRVYb2d3bUy', NULL, 'light', NULL, '2025-11-25 09:21:19', '2025-11-25 09:21:19'),
(64, 'Derick O\'Keefe', 'bernhard.renee@example.org', 1, NULL, '$2y$12$jncWqZCiNlLDymgAfpRPReqJvVrt1BEFBl9cgNUopNzRVYb2d3bUy', NULL, 'light', NULL, '2025-11-25 09:21:19', '2025-11-25 09:21:19'),
(65, 'Javon Kshlerin', 'daryl.blick@example.net', 1, NULL, '$2y$12$jncWqZCiNlLDymgAfpRPReqJvVrt1BEFBl9cgNUopNzRVYb2d3bUy', NULL, 'light', NULL, '2025-11-25 09:21:19', '2025-11-25 09:21:19'),
(66, 'Mr. Braulio Gutkowski', 'qmedhurst@example.net', 1, NULL, '$2y$12$jncWqZCiNlLDymgAfpRPReqJvVrt1BEFBl9cgNUopNzRVYb2d3bUy', NULL, 'light', NULL, '2025-11-25 09:21:19', '2025-11-25 09:21:19'),
(67, 'Michaela Little', 'izaiah69@example.com', 1, NULL, '$2y$12$jncWqZCiNlLDymgAfpRPReqJvVrt1BEFBl9cgNUopNzRVYb2d3bUy', NULL, 'light', NULL, '2025-11-25 09:21:19', '2025-11-25 09:21:19'),
(68, 'Reed Nitzsche Jr.', 'reinger.myrna@example.org', 1, NULL, '$2y$12$jncWqZCiNlLDymgAfpRPReqJvVrt1BEFBl9cgNUopNzRVYb2d3bUy', NULL, 'light', NULL, '2025-11-25 09:21:19', '2025-11-25 09:21:19'),
(69, 'Shaun Von', 'mccullough.zechariah@example.org', 1, NULL, '$2y$12$jncWqZCiNlLDymgAfpRPReqJvVrt1BEFBl9cgNUopNzRVYb2d3bUy', NULL, 'light', NULL, '2025-11-25 09:21:19', '2025-11-25 09:21:19'),
(70, 'Rosa McDermott', 'mariane.hill@example.com', 1, NULL, '$2y$12$jncWqZCiNlLDymgAfpRPReqJvVrt1BEFBl9cgNUopNzRVYb2d3bUy', NULL, 'light', NULL, '2025-11-25 09:21:19', '2025-11-25 09:21:19'),
(71, 'Jonatan Kertzmann', 'rdaniel@example.net', 1, NULL, '$2y$12$jncWqZCiNlLDymgAfpRPReqJvVrt1BEFBl9cgNUopNzRVYb2d3bUy', NULL, 'light', NULL, '2025-11-25 09:21:19', '2025-11-25 09:21:19'),
(72, 'Louie Hagenes', 'lonny.rath@example.com', 1, NULL, '$2y$12$jncWqZCiNlLDymgAfpRPReqJvVrt1BEFBl9cgNUopNzRVYb2d3bUy', NULL, 'light', NULL, '2025-11-25 09:21:19', '2025-11-25 09:21:19'),
(73, 'Dr. Jaleel Durgan DDS', 'yasmine.hodkiewicz@example.com', 1, NULL, '$2y$12$jncWqZCiNlLDymgAfpRPReqJvVrt1BEFBl9cgNUopNzRVYb2d3bUy', NULL, 'light', NULL, '2025-11-25 09:21:19', '2025-11-25 09:21:19'),
(74, 'Onie Will', 'wyost@example.org', 1, NULL, '$2y$12$jncWqZCiNlLDymgAfpRPReqJvVrt1BEFBl9cgNUopNzRVYb2d3bUy', NULL, 'light', NULL, '2025-11-25 09:21:19', '2025-11-25 09:21:19'),
(75, 'Charles Huels', 'angela.tremblay@example.org', 1, NULL, '$2y$12$jncWqZCiNlLDymgAfpRPReqJvVrt1BEFBl9cgNUopNzRVYb2d3bUy', NULL, 'light', NULL, '2025-11-25 09:21:19', '2025-11-25 09:21:19'),
(76, 'Libbie Pagac', 'wyman.herminia@example.org', 1, NULL, '$2y$12$jncWqZCiNlLDymgAfpRPReqJvVrt1BEFBl9cgNUopNzRVYb2d3bUy', NULL, 'light', NULL, '2025-11-25 09:21:19', '2025-11-25 09:21:19'),
(77, 'Prof. Brent Hyatt', 'kris02@example.com', 1, NULL, '$2y$12$jncWqZCiNlLDymgAfpRPReqJvVrt1BEFBl9cgNUopNzRVYb2d3bUy', NULL, 'light', NULL, '2025-11-25 09:21:19', '2025-11-25 09:21:19'),
(78, 'Dr. Cassidy Beahan DVM', 'bogan.russel@example.org', 1, NULL, '$2y$12$jncWqZCiNlLDymgAfpRPReqJvVrt1BEFBl9cgNUopNzRVYb2d3bUy', NULL, 'light', NULL, '2025-11-25 09:21:19', '2025-11-25 09:21:19'),
(79, 'Parker Bartoletti', 'sipes.deshaun@example.org', 1, NULL, '$2y$12$jncWqZCiNlLDymgAfpRPReqJvVrt1BEFBl9cgNUopNzRVYb2d3bUy', NULL, 'light', NULL, '2025-11-25 09:21:19', '2025-11-25 09:21:19'),
(80, 'Harold Reilly', 'taya00@example.net', 1, NULL, '$2y$12$jncWqZCiNlLDymgAfpRPReqJvVrt1BEFBl9cgNUopNzRVYb2d3bUy', NULL, 'light', NULL, '2025-11-25 09:21:19', '2025-11-25 09:21:19'),
(81, 'Dr. Mariela Collier', 'langosh.viviane@example.org', 1, NULL, '$2y$12$jncWqZCiNlLDymgAfpRPReqJvVrt1BEFBl9cgNUopNzRVYb2d3bUy', NULL, 'light', NULL, '2025-11-25 09:21:19', '2025-11-25 09:21:19'),
(82, 'Prof. Paolo Little DVM', 'nicolas.brice@example.net', 1, NULL, '$2y$12$jncWqZCiNlLDymgAfpRPReqJvVrt1BEFBl9cgNUopNzRVYb2d3bUy', NULL, 'light', NULL, '2025-11-25 09:21:19', '2025-11-25 09:21:19'),
(83, 'Sydney Rolfson II', 'pagac.hazel@example.com', 1, NULL, '$2y$12$jncWqZCiNlLDymgAfpRPReqJvVrt1BEFBl9cgNUopNzRVYb2d3bUy', NULL, 'light', NULL, '2025-11-25 09:21:19', '2025-11-25 09:21:19'),
(84, 'Prof. Korey Stracke', 'terry.maureen@example.net', 1, NULL, '$2y$12$jncWqZCiNlLDymgAfpRPReqJvVrt1BEFBl9cgNUopNzRVYb2d3bUy', NULL, 'light', NULL, '2025-11-25 09:21:19', '2025-11-25 09:21:19'),
(85, 'Flavie Stokes DVM', 'llangworth@example.net', 1, NULL, '$2y$12$jncWqZCiNlLDymgAfpRPReqJvVrt1BEFBl9cgNUopNzRVYb2d3bUy', NULL, 'light', NULL, '2025-11-25 09:21:19', '2025-11-25 09:21:19'),
(86, 'Remington Schoen', 'freida.tillman@example.org', 1, NULL, '$2y$12$jncWqZCiNlLDymgAfpRPReqJvVrt1BEFBl9cgNUopNzRVYb2d3bUy', NULL, 'light', NULL, '2025-11-25 09:21:19', '2025-11-25 09:21:19'),
(87, 'Reynold Thiel', 'mkozey@example.com', 1, NULL, '$2y$12$jncWqZCiNlLDymgAfpRPReqJvVrt1BEFBl9cgNUopNzRVYb2d3bUy', NULL, 'light', NULL, '2025-11-25 09:21:19', '2025-11-25 09:21:19'),
(88, 'Marc Ortiz', 'haleigh.roob@example.net', 1, NULL, '$2y$12$jncWqZCiNlLDymgAfpRPReqJvVrt1BEFBl9cgNUopNzRVYb2d3bUy', NULL, 'light', NULL, '2025-11-25 09:21:19', '2025-11-25 09:21:19'),
(89, 'Mr. Johnson Breitenberg III', 'gavin14@example.com', 1, NULL, '$2y$12$jncWqZCiNlLDymgAfpRPReqJvVrt1BEFBl9cgNUopNzRVYb2d3bUy', NULL, 'light', NULL, '2025-11-25 09:21:19', '2025-11-25 09:21:19'),
(90, 'Oma Larkin', 'ogoyette@example.com', 1, NULL, '$2y$12$jncWqZCiNlLDymgAfpRPReqJvVrt1BEFBl9cgNUopNzRVYb2d3bUy', NULL, 'light', NULL, '2025-11-25 09:21:19', '2025-11-25 09:21:19'),
(91, 'Mr. Quinton Franecki II', 'emmerich.meagan@example.com', 1, NULL, '$2y$12$jncWqZCiNlLDymgAfpRPReqJvVrt1BEFBl9cgNUopNzRVYb2d3bUy', NULL, 'light', NULL, '2025-11-25 09:21:19', '2025-11-25 09:21:19'),
(110, 'lintang', 'lintang@staff.com', 2, NULL, '$2y$12$O0sljjuSSBQFkZwVTxAJCuvnnm.LPmc1k2zkT43NVqUQ7TTqkQCU6', NULL, 'light', NULL, '2025-11-25 18:11:29', '2025-11-25 18:11:29'),
(111, 'lintang', 'lintang@lecture.isi.ac.id', 3, NULL, '$2y$12$OsD/9WD8siMzJXUfTxbBoO1MdrKRDGtTSN/mbpMzR.eo4FI2yH2CC', NULL, 'light', NULL, '2025-11-25 18:12:37', '2025-11-25 18:12:37'),
(112, 'adminn', 'admin@admin.com', 5, NULL, '$2y$12$dkxp3P4i8EXtnk3B781d6ejO42HMQ2XWFBvguJocEdjT/GnUJKKy.', NULL, 'light', NULL, '2025-11-25 18:18:50', '2025-11-25 18:18:50'),
(113, 'surya', 'surya@kaprodi.com', 4, NULL, '$2y$12$5eH/rbq0aUTeB.LP5Hs4c.T5E1hEdv59y6wwgx78HfCHjBBHWYD2.', NULL, 'light', NULL, '2025-11-25 18:19:40', '2025-11-25 18:19:40'),
(114, 'debora', 'debora@student.isi.ac.id', 1, NULL, '$2y$12$LpfJJjBqKZZPh2AlLyArD.FK29FhAUgixkDVJqhHly2f8IALbYnfi', NULL, 'light', NULL, '2025-11-26 20:19:57', '2025-11-26 20:19:57'),
(115, 'Dosen Pembimbing 1', 'dosen1@lecturer.isi.ac.id', 2, NULL, '$2y$12$cxlPx.dAlR5lV7/ecDAOyOkVQXU/zIenGVknfzWXu/to.BBtjqjHu', NULL, 'light', NULL, '2025-11-26 20:43:15', '2025-11-26 20:43:15'),
(116, 'ananda', 'ananda@student.isi.ac.id', 1, NULL, '$2y$12$IJFx1LcqP5n3fUt29aAhquWSpTU70Qh.ys7FA./Oyv1UtxZqcbGWO', NULL, 'light', NULL, '2025-11-27 05:23:02', '2025-11-27 05:23:02'),
(117, 'angga', 'angga@student.isi.ac.id', 1, NULL, '$2y$12$p7.cDWSGeeKS33.hDtB2iuDagc0V9cEnaVyz.nPbe9CuLxzixpyxu', NULL, 'light', NULL, '2025-11-27 06:12:03', '2025-11-27 06:12:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bimbingans`
--
ALTER TABLE `bimbingans`
  ADD PRIMARY KEY (`id_bimbingan`),
  ADD KEY `bimbingans_id_proyek_akhir_foreign` (`id_proyek_akhir`),
  ADD KEY `bimbingans_nidn_foreign` (`nidn`),
  ADD KEY `bimbingans_nim_foreign` (`nim`),
  ADD KEY `bimbingans_mahasiswa_id_foreign` (`mahasiswa_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `dosen`
--
ALTER TABLE `dosen`
  ADD PRIMARY KEY (`nidn`);

--
-- Indexes for table `dosen_penguji`
--
ALTER TABLE `dosen_penguji`
  ADD PRIMARY KEY (`id_penguji`),
  ADD KEY `dosen_penguji_id_ujian_foreign` (`id_ujian`),
  ADD KEY `dosen_penguji_nidn_foreign` (`nidn`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jadwals`
--
ALTER TABLE `jadwals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jadwals_user_id_foreign` (`user_id`);

--
-- Indexes for table `jadwal_acaras`
--
ALTER TABLE `jadwal_acaras`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `luaran`
--
ALTER TABLE `luaran`
  ADD PRIMARY KEY (`id_luaran`),
  ADD KEY `luaran_id_proyek_akhir_foreign` (`id_proyek_akhir`);

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`nim`),
  ADD UNIQUE KEY `mahasiswa_email_unique` (`email`),
  ADD KEY `mahasiswa_user_id_foreign` (`user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `projek_akhir`
--
ALTER TABLE `projek_akhir`
  ADD PRIMARY KEY (`id_proyek_akhir`),
  ADD KEY `projek_akhir_nim_foreign` (`nim`),
  ADD KEY `projek_akhir_nidn1_foreign` (`nidn1`),
  ADD KEY `projek_akhir_nidn2_foreign` (`nidn2`),
  ADD KEY `projek_akhir_mahasiswa_id_foreign` (`mahasiswa_id`),
  ADD KEY `projek_akhir_proposal_id_foreign` (`proposal_id`);

--
-- Indexes for table `proposals`
--
ALTER TABLE `proposals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proposals_mahasiswa_nim_index` (`mahasiswa_nim`),
  ADD KEY `proposals_dosen_id_index` (`dosen_id`),
  ADD KEY `proposals_status_index` (`status`);

--
-- Indexes for table `revisi`
--
ALTER TABLE `revisi`
  ADD PRIMARY KEY (`id_revisi`),
  ADD KEY `revisi_id_ujian_foreign` (`id_ujian`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `story_conference`
--
ALTER TABLE `story_conference`
  ADD PRIMARY KEY (`id_conference`),
  ADD KEY `story_conference_mahasiswa_nim_foreign` (`mahasiswa_nim`),
  ADD KEY `story_conference_proposals_id_foreign` (`proposals_id`);

--
-- Indexes for table `student_progress`
--
ALTER TABLE `student_progress`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ta_progress_stages`
--
ALTER TABLE `ta_progress_stages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ta_progress_stages_stage_code_unique` (`stage_code`);

--
-- Indexes for table `tefa_fair`
--
ALTER TABLE `tefa_fair`
  ADD PRIMARY KEY (`id_tefa`),
  ADD KEY `tefa_fair_id_proyek_akhir_foreign` (`id_proyek_akhir`);

--
-- Indexes for table `tim_produksi`
--
ALTER TABLE `tim_produksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tim_produksi_mahasiswa_id_index` (`mahasiswa_id`),
  ADD KEY `tim_produksi_proposal_id_index` (`proposal_id`),
  ADD KEY `tim_produksi_dosen_id_index` (`dosen_id`),
  ADD KEY `tim_produksi_status_pra_produksi_index` (`status_pra_produksi`),
  ADD KEY `tim_produksi_status_produksi_akhir_index` (`status_produksi_akhir`);

--
-- Indexes for table `ujian_tugas_akhir`
--
ALTER TABLE `ujian_tugas_akhir`
  ADD PRIMARY KEY (`id_ujian`),
  ADD KEY `ujian_tugas_akhir_id_proyek_akhir_foreign` (`id_proyek_akhir`),
  ADD KEY `ujian_tugas_akhir_dosen_pembimbing_id_index` (`dosen_pembimbing_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bimbingans`
--
ALTER TABLE `bimbingans`
  MODIFY `id_bimbingan` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `dosen_penguji`
--
ALTER TABLE `dosen_penguji`
  MODIFY `id_penguji` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jadwals`
--
ALTER TABLE `jadwals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jadwal_acaras`
--
ALTER TABLE `jadwal_acaras`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `luaran`
--
ALTER TABLE `luaran`
  MODIFY `id_luaran` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `projek_akhir`
--
ALTER TABLE `projek_akhir`
  MODIFY `id_proyek_akhir` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `proposals`
--
ALTER TABLE `proposals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `revisi`
--
ALTER TABLE `revisi`
  MODIFY `id_revisi` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `story_conference`
--
ALTER TABLE `story_conference`
  MODIFY `id_conference` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `student_progress`
--
ALTER TABLE `student_progress`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ta_progress_stages`
--
ALTER TABLE `ta_progress_stages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tefa_fair`
--
ALTER TABLE `tefa_fair`
  MODIFY `id_tefa` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tim_produksi`
--
ALTER TABLE `tim_produksi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ujian_tugas_akhir`
--
ALTER TABLE `ujian_tugas_akhir`
  MODIFY `id_ujian` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bimbingans`
--
ALTER TABLE `bimbingans`
  ADD CONSTRAINT `bimbingans_id_proyek_akhir_foreign` FOREIGN KEY (`id_proyek_akhir`) REFERENCES `projek_akhir` (`id_proyek_akhir`) ON DELETE CASCADE,
  ADD CONSTRAINT `bimbingans_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bimbingans_nidn_foreign` FOREIGN KEY (`nidn`) REFERENCES `dosen` (`nidn`) ON DELETE CASCADE,
  ADD CONSTRAINT `bimbingans_nim_foreign` FOREIGN KEY (`nim`) REFERENCES `mahasiswa` (`nim`) ON DELETE CASCADE;

--
-- Constraints for table `dosen_penguji`
--
ALTER TABLE `dosen_penguji`
  ADD CONSTRAINT `dosen_penguji_id_ujian_foreign` FOREIGN KEY (`id_ujian`) REFERENCES `ujian_tugas_akhir` (`id_ujian`) ON DELETE CASCADE,
  ADD CONSTRAINT `dosen_penguji_nidn_foreign` FOREIGN KEY (`nidn`) REFERENCES `dosen` (`nidn`) ON DELETE CASCADE;

--
-- Constraints for table `jadwals`
--
ALTER TABLE `jadwals`
  ADD CONSTRAINT `jadwals_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `luaran`
--
ALTER TABLE `luaran`
  ADD CONSTRAINT `luaran_id_proyek_akhir_foreign` FOREIGN KEY (`id_proyek_akhir`) REFERENCES `projek_akhir` (`id_proyek_akhir`) ON DELETE CASCADE;

--
-- Constraints for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD CONSTRAINT `mahasiswa_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `projek_akhir`
--
ALTER TABLE `projek_akhir`
  ADD CONSTRAINT `projek_akhir_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `projek_akhir_nidn1_foreign` FOREIGN KEY (`nidn1`) REFERENCES `dosen` (`nidn`) ON DELETE SET NULL,
  ADD CONSTRAINT `projek_akhir_nidn2_foreign` FOREIGN KEY (`nidn2`) REFERENCES `dosen` (`nidn`) ON DELETE SET NULL,
  ADD CONSTRAINT `projek_akhir_nim_foreign` FOREIGN KEY (`nim`) REFERENCES `mahasiswa` (`nim`) ON DELETE CASCADE,
  ADD CONSTRAINT `projek_akhir_proposal_id_foreign` FOREIGN KEY (`proposal_id`) REFERENCES `proposals` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `proposals`
--
ALTER TABLE `proposals`
  ADD CONSTRAINT `proposals_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosen` (`nidn`) ON DELETE SET NULL,
  ADD CONSTRAINT `proposals_mahasiswa_nim_foreign` FOREIGN KEY (`mahasiswa_nim`) REFERENCES `mahasiswa` (`nim`) ON DELETE CASCADE;

--
-- Constraints for table `revisi`
--
ALTER TABLE `revisi`
  ADD CONSTRAINT `revisi_id_ujian_foreign` FOREIGN KEY (`id_ujian`) REFERENCES `ujian_tugas_akhir` (`id_ujian`) ON DELETE CASCADE;

--
-- Constraints for table `story_conference`
--
ALTER TABLE `story_conference`
  ADD CONSTRAINT `story_conference_mahasiswa_nim_foreign` FOREIGN KEY (`mahasiswa_nim`) REFERENCES `mahasiswa` (`nim`) ON DELETE CASCADE,
  ADD CONSTRAINT `story_conference_proposals_id_foreign` FOREIGN KEY (`proposals_id`) REFERENCES `proposals` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tefa_fair`
--
ALTER TABLE `tefa_fair`
  ADD CONSTRAINT `tefa_fair_id_proyek_akhir_foreign` FOREIGN KEY (`id_proyek_akhir`) REFERENCES `projek_akhir` (`id_proyek_akhir`) ON DELETE CASCADE;

--
-- Constraints for table `tim_produksi`
--
ALTER TABLE `tim_produksi`
  ADD CONSTRAINT `tim_produksi_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosen` (`nidn`) ON DELETE SET NULL,
  ADD CONSTRAINT `tim_produksi_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tim_produksi_proposal_id_foreign` FOREIGN KEY (`proposal_id`) REFERENCES `proposals` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ujian_tugas_akhir`
--
ALTER TABLE `ujian_tugas_akhir`
  ADD CONSTRAINT `ujian_tugas_akhir_id_proyek_akhir_foreign` FOREIGN KEY (`id_proyek_akhir`) REFERENCES `projek_akhir` (`id_proyek_akhir`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
