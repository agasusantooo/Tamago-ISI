-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 03, 2025 at 02:12 AM
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
-- Table structure for table `bimbingan`
--

CREATE TABLE `bimbingan` (
  `id_bimbingan` bigint UNSIGNED NOT NULL,
  `id_proyek_akhir` bigint UNSIGNED NOT NULL,
  `nidn` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date NOT NULL,
  `catatan_bimbingan` text COLLATE utf8mb4_unicode_ci,
  `pencapaian` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_persetujuan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
(4, '2025_10_06_030744_create_mahasiswa_table', 1),
(5, '2025_10_06_030748_create_dosen_table', 1),
(6, '2025_10_06_030751_create_projek_akhir_table', 1),
(7, '2025_10_06_030756_create_bimbingan_table', 1),
(8, '2025_10_06_030800_create_tim_produksi_table', 1),
(9, '2025_10_06_030805_create_tefa_fair_table', 1),
(10, '2025_10_06_030809_create_luaran_table', 1),
(11, '2025_10_06_030812_create_story_conference_table', 1),
(12, '2025_10_06_030816_create_ujian_tugas_akhir_table', 1),
(13, '2025_10_06_030819_create_dosen_penguji_table', 1),
(14, '2025_10_06_030824_create_revisi_table', 1),
(15, '2025_10_28_171420_create_proposals_table', 1),
(16, '2025_10_28_173204_create_roles_and_update_users_table', 1),
(17, '2025_10_29_161329_create_ta_progress_stages_table', 1),
(18, '2025_10_29_161345_create_student_progress_table', 1),
(19, '2025_10_31_062835_add_status_to_dosen_table', 2);

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
  `file_pitch_deck` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_story_bible` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proposals`
--

CREATE TABLE `proposals` (
  `id` bigint UNSIGNED NOT NULL,
  `mahasiswa_id` bigint UNSIGNED NOT NULL,
  `dosen_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_proposal` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_pitch_deck` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `versi` int NOT NULL DEFAULT '1',
  `status` enum('draft','diajukan','review','revisi','disetujui','ditolak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `tanggal_pengajuan` timestamp NULL DEFAULT NULL,
  `tanggal_review` timestamp NULL DEFAULT NULL,
  `feedback` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `proposals`
--

INSERT INTO `proposals` (`id`, `mahasiswa_id`, `dosen_id`, `judul`, `deskripsi`, `file_proposal`, `file_pitch_deck`, `versi`, `status`, `tanggal_pengajuan`, `tanggal_review`, `feedback`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'Pengembangan Sistem', 'dajhdouahodihaoidhwaihidajhdouahodihaoidhwaihidajhdouahodihaoidhwaihidajhdouahodihaoidhwaihidajhdouahodihaoidhwaihidajhdouahodihaoidhwaihidajhdouahodihaoidhwaihidajhdouahodihaoidhwaihidajhdouahodihaoidhwaihidajhdouahodihaoidhwaihidajhdouahodihaoidhwaihidajhdouahodihaoidhwaihidajhdouahodihaoidhwaihidajhdouahodihaoidhwaihidajhdouahodihaoidhwaihidajhdouahodihaoidhwaihidajhdouahodihaoidhwaihidajhdouahodihaoidhwaihidajhdouahodihaoidhwaihidajhdouahodihaoidhwaihidajhdouahodihaoidhwaihidajhdouahodihaoidhwaihidajhdouahodihaoidhwaihidajhdouahodihaoidhwaihidajhdouahodihaoidhwaihidajhdouahodihaoidhwaihidajhdouahodihaoidhwaihidajhdouahodihaoidhwaihidajhdouahodihaoidhwaihidajhdouahodihaoidhwaihidajhdouahodihaoidhwaihidajhdouahodihaoidhwaihidajhdouahodihaoidhwaihidajhdouahodihaoidhwaihidajhdouahodihaoidhwaihidajhdouahodihaoidhwaihidajhdouahodihaoidhwaihidajhdouahodihaoidhwaihidajhdouahodihaoidhwaihidajhdouahodihaoidhwaihidajhdouahodihaoidhwaihidajhdouahodihaoidhwaihidajhdouahodihaoidhwaihi', 'proposals/1/proposal_v1_1761895887.pdf', 'pitch-decks/1/pitchdeck_v1_1761895887.pdf', 1, 'diajukan', '2025-10-31 00:31:27', NULL, NULL, '2025-10-31 00:31:27', '2025-10-31 00:31:27');

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
(1, 'mahasiswa', 'Mahasiswa', 'Mahasiswa yang sedang mengerjakan TA', '2025-10-29 23:55:34', '2025-10-29 23:55:34'),
(2, 'dospem', 'Dosen Pembimbing', 'Dosen pembimbing tugas akhir', '2025-10-29 23:55:34', '2025-10-29 23:55:34'),
(3, 'kaprodi', 'Koordinator Prodi', 'Koordinator program studi', '2025-10-29 23:55:34', '2025-10-29 23:55:34'),
(4, 'koordinator_ta', 'Koordinator TA', 'Koordinator tugas akhir fakultas', '2025-10-29 23:55:34', '2025-10-29 23:55:34'),
(5, 'admin', 'Admin', 'Administrator sistem', '2025-10-29 23:55:34', '2025-10-29 23:55:34'),
(6, 'dosen_penguji', 'Dosen Penguji', 'Dosen penguji ujian TA', '2025-10-29 23:55:34', '2025-10-29 23:55:34');

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
('AFyuwUjcZ6uHnQjyAwG1ScPFrANEHye8RSsjsCdM', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMTdtSnViY1RibERlNXBnazhWSUgwOE85bm81Z0kyQ1JuZkp4QVRkNiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYWhhc2lzd2EvcHJvcG9zYWwiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1761896392),
('MJYQS3874dJ08PLzGBoMsAVSaTF8ANAr05uVCUR3', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNmx6S3RhVEZnUTV1a2l1ZzRFcmJrQzYyNWxhWHFTRXZZMkF2d1RKTyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fX0=', 1762135858);

-- --------------------------------------------------------

--
-- Table structure for table `story_conference`
--

CREATE TABLE `story_conference` (
  `id_conference` bigint UNSIGNED NOT NULL,
  `id_proyek_akhir` bigint UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `catatan_evaluasi` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 'proposal_submission', 'Pengajuan Proposal', 'Mahasiswa mengajukan proposal TA', 15.00, 1, 1, '2025-10-29 23:55:34', '2025-10-29 23:55:34'),
(2, 'proposal_approved', 'Proposal Disetujui', 'Proposal telah disetujui oleh pembimbing/kaprodi', 10.00, 2, 1, '2025-10-29 23:55:34', '2025-10-29 23:55:34'),
(3, 'bimbingan_progress', 'Bimbingan (Min. 8x)', 'Melakukan bimbingan minimal 8 kali', 25.00, 3, 1, '2025-10-29 23:55:34', '2025-10-29 23:55:34'),
(4, 'story_conference', 'Story Conference', 'Mengikuti story conference', 10.00, 4, 1, '2025-10-29 23:55:34', '2025-10-29 23:55:34'),
(5, 'production_upload', 'Upload Produksi', 'Mengunggah hasil produksi/karya', 15.00, 5, 1, '2025-10-29 23:55:34', '2025-10-29 23:55:34'),
(6, 'exam_registration', 'Pendaftaran Ujian TA', 'Mendaftar ujian tugas akhir', 10.00, 6, 1, '2025-10-29 23:55:34', '2025-10-29 23:55:34'),
(7, 'exam_completed', 'Ujian TA Selesai', 'Telah melaksanakan ujian TA', 10.00, 7, 1, '2025-10-29 23:55:34', '2025-10-29 23:55:34'),
(8, 'final_submission', 'Submit Naskah & Karya Final', 'Mengunggah naskah dan karya final', 5.00, 8, 1, '2025-10-29 23:55:34', '2025-10-29 23:55:34');

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
  `id_anggota_tim` bigint UNSIGNED NOT NULL,
  `id_proyek_akhir` bigint UNSIGNED NOT NULL,
  `nim` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `peran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ujian_tugas_akhir`
--

CREATE TABLE `ujian_tugas_akhir` (
  `id_ujian` bigint UNSIGNED NOT NULL,
  `id_proyek_akhir` bigint UNSIGNED NOT NULL,
  `tanggal_ujian` date NOT NULL,
  `hasil_akhir` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan_penguj` text COLLATE utf8mb4_unicode_ci,
  `status_kelayakan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role_id`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'debora', 'debora@gmail.com', 1, NULL, '$2y$12$8ZsJQnue7bzKIYZzwnCsS.oZsXbRXxvMKlgnIjr/u4kUsfZIvqXqe', NULL, '2025-10-29 23:56:34', '2025-10-29 23:56:34'),
(2, 'lintang', 'lintang@staff.com', 2, NULL, '$2y$12$CCJ9hIUiEeRgurN6VAyOgeewJoSWWj8b9eiU3VdCZq33tKmOEDVcW', NULL, '2025-10-30 00:27:24', '2025-10-30 00:27:24'),
(3, 'adminnn', 'admin@gmail.com', 1, NULL, '$2y$12$yG8iHKZVvrT.2m7pgYNgEeHnPms5ZV4uDpqD2Fu3UJhmUOmD2/jjm', NULL, '2025-11-02 19:08:21', '2025-11-02 19:08:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bimbingan`
--
ALTER TABLE `bimbingan`
  ADD PRIMARY KEY (`id_bimbingan`),
  ADD KEY `bimbingan_id_proyek_akhir_foreign` (`id_proyek_akhir`),
  ADD KEY `bimbingan_nidn_foreign` (`nidn`);

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
  ADD UNIQUE KEY `mahasiswa_email_unique` (`email`);

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
  ADD KEY `projek_akhir_nidn2_foreign` (`nidn2`);

--
-- Indexes for table `proposals`
--
ALTER TABLE `proposals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proposals_mahasiswa_id_index` (`mahasiswa_id`),
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
  ADD KEY `story_conference_id_proyek_akhir_foreign` (`id_proyek_akhir`);

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
  ADD PRIMARY KEY (`id_anggota_tim`),
  ADD KEY `tim_produksi_id_proyek_akhir_foreign` (`id_proyek_akhir`),
  ADD KEY `tim_produksi_nim_foreign` (`nim`);

--
-- Indexes for table `ujian_tugas_akhir`
--
ALTER TABLE `ujian_tugas_akhir`
  ADD PRIMARY KEY (`id_ujian`),
  ADD KEY `ujian_tugas_akhir_id_proyek_akhir_foreign` (`id_proyek_akhir`);

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
-- AUTO_INCREMENT for table `bimbingan`
--
ALTER TABLE `bimbingan`
  MODIFY `id_bimbingan` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `projek_akhir`
--
ALTER TABLE `projek_akhir`
  MODIFY `id_proyek_akhir` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proposals`
--
ALTER TABLE `proposals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `id_conference` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `id_anggota_tim` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ujian_tugas_akhir`
--
ALTER TABLE `ujian_tugas_akhir`
  MODIFY `id_ujian` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bimbingan`
--
ALTER TABLE `bimbingan`
  ADD CONSTRAINT `bimbingan_id_proyek_akhir_foreign` FOREIGN KEY (`id_proyek_akhir`) REFERENCES `projek_akhir` (`id_proyek_akhir`) ON DELETE CASCADE,
  ADD CONSTRAINT `bimbingan_nidn_foreign` FOREIGN KEY (`nidn`) REFERENCES `dosen` (`nidn`) ON DELETE CASCADE;

--
-- Constraints for table `dosen_penguji`
--
ALTER TABLE `dosen_penguji`
  ADD CONSTRAINT `dosen_penguji_id_ujian_foreign` FOREIGN KEY (`id_ujian`) REFERENCES `ujian_tugas_akhir` (`id_ujian`) ON DELETE CASCADE,
  ADD CONSTRAINT `dosen_penguji_nidn_foreign` FOREIGN KEY (`nidn`) REFERENCES `dosen` (`nidn`) ON DELETE CASCADE;

--
-- Constraints for table `luaran`
--
ALTER TABLE `luaran`
  ADD CONSTRAINT `luaran_id_proyek_akhir_foreign` FOREIGN KEY (`id_proyek_akhir`) REFERENCES `projek_akhir` (`id_proyek_akhir`) ON DELETE CASCADE;

--
-- Constraints for table `projek_akhir`
--
ALTER TABLE `projek_akhir`
  ADD CONSTRAINT `projek_akhir_nidn1_foreign` FOREIGN KEY (`nidn1`) REFERENCES `dosen` (`nidn`) ON DELETE SET NULL,
  ADD CONSTRAINT `projek_akhir_nidn2_foreign` FOREIGN KEY (`nidn2`) REFERENCES `dosen` (`nidn`) ON DELETE SET NULL,
  ADD CONSTRAINT `projek_akhir_nim_foreign` FOREIGN KEY (`nim`) REFERENCES `mahasiswa` (`nim`) ON DELETE CASCADE;

--
-- Constraints for table `proposals`
--
ALTER TABLE `proposals`
  ADD CONSTRAINT `proposals_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosen` (`nidn`) ON DELETE SET NULL,
  ADD CONSTRAINT `proposals_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `revisi`
--
ALTER TABLE `revisi`
  ADD CONSTRAINT `revisi_id_ujian_foreign` FOREIGN KEY (`id_ujian`) REFERENCES `ujian_tugas_akhir` (`id_ujian`) ON DELETE CASCADE;

--
-- Constraints for table `story_conference`
--
ALTER TABLE `story_conference`
  ADD CONSTRAINT `story_conference_id_proyek_akhir_foreign` FOREIGN KEY (`id_proyek_akhir`) REFERENCES `projek_akhir` (`id_proyek_akhir`) ON DELETE CASCADE;

--
-- Constraints for table `tefa_fair`
--
ALTER TABLE `tefa_fair`
  ADD CONSTRAINT `tefa_fair_id_proyek_akhir_foreign` FOREIGN KEY (`id_proyek_akhir`) REFERENCES `projek_akhir` (`id_proyek_akhir`) ON DELETE CASCADE;

--
-- Constraints for table `tim_produksi`
--
ALTER TABLE `tim_produksi`
  ADD CONSTRAINT `tim_produksi_id_proyek_akhir_foreign` FOREIGN KEY (`id_proyek_akhir`) REFERENCES `projek_akhir` (`id_proyek_akhir`) ON DELETE CASCADE,
  ADD CONSTRAINT `tim_produksi_nim_foreign` FOREIGN KEY (`nim`) REFERENCES `mahasiswa` (`nim`) ON DELETE CASCADE;

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
