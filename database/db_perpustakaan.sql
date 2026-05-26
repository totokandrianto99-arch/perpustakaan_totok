-- ============================================================
-- DATABASE: db_perpustakaan
-- Sistem Peminjaman Buku Sederhana
-- ============================================================

CREATE DATABASE IF NOT EXISTS `db_perpustakaan`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `db_perpustakaan`;

-- -------------------------------------------------------
-- Tabel: user
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS `user` (
  `id_user`    BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username`   VARCHAR(255)    NOT NULL,
  `password`   VARCHAR(255)    NOT NULL,
  `level`      ENUM('admin','petugas') NOT NULL DEFAULT 'admin',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `user_username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- Tabel: buku
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS `buku` (
  `id_buku`      BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `judul`        VARCHAR(255)    NOT NULL,
  `pengarang`    VARCHAR(255)    NOT NULL,
  `tahun_terbit` YEAR            NOT NULL,
  `stok`         INT             NOT NULL DEFAULT 0,
  `created_at`   TIMESTAMP NULL DEFAULT NULL,
  `updated_at`   TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id_buku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- Tabel: peminjaman
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS `peminjaman` (
  `id_pinjam`      BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_peminjam`  VARCHAR(255)    NOT NULL,
  `tanggal_pinjam` DATE            NOT NULL,
  `id_buku`        BIGINT UNSIGNED NOT NULL,
  `jumlah_pinjam`  INT             NOT NULL,
  `status`         ENUM('dipinjam','dikembalikan') NOT NULL DEFAULT 'dipinjam',
  `created_at`     TIMESTAMP NULL DEFAULT NULL,
  `updated_at`     TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id_pinjam`),
  KEY `peminjaman_id_buku_foreign` (`id_buku`),
  CONSTRAINT `peminjaman_id_buku_foreign`
    FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- Data awal: user admin
-- Password: admin123
-- -------------------------------------------------------
INSERT INTO `user` (`username`, `password`, `level`, `created_at`, `updated_at`) VALUES
('admin', '$2y$12$exampleHashReplaceWithActual', 'admin', NOW(), NOW());

-- -------------------------------------------------------
-- Data awal: buku contoh
-- -------------------------------------------------------
INSERT INTO `buku` (`judul`, `pengarang`, `tahun_terbit`, `stok`, `created_at`, `updated_at`) VALUES
('Laskar Pelangi',   'Andrea Hirata',          2005, 5, NOW(), NOW()),
('Bumi Manusia',     'Pramoedya Ananta Toer',  1980, 3, NOW(), NOW()),
('Negeri 5 Menara',  'Ahmad Fuadi',            2009, 4, NOW(), NOW());
