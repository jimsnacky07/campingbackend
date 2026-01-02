/*
SQLyog Ultimate v12.5.1 (64 bit)
MySQL - 10.4.32-MariaDB : Database - db_camping
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`db_camping` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `db_camping`;

/*Table structure for table `barang` */

DROP TABLE IF EXISTS `barang`;

CREATE TABLE `barang` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_kategori` bigint(20) unsigned NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga_sewa` int(10) unsigned NOT NULL,
  `stok` int(10) unsigned NOT NULL DEFAULT 0,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `barang_id_kategori_foreign` (`id_kategori`),
  CONSTRAINT `barang_id_kategori_foreign` FOREIGN KEY (`id_kategori`) REFERENCES `kategori_barang` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `barang` */

insert  into `barang`(`id`,`id_kategori`,`nama_barang`,`deskripsi`,`harga_sewa`,`stok`,`foto`,`created_at`,`updated_at`) values 
(1,1,'Tas Kerel','Tas Kerel untuk menyimpan barang bawaan anda',600000,100,NULL,'2025-12-04 08:43:26','2025-12-04 08:43:26'),
(2,1,'Tenda Kapasitas 2 Orang','Tenda dome untuk 2 orang, waterproof, mudah dipasang',50000,10,NULL,'2025-12-09 05:51:13','2025-12-09 05:51:13'),
(3,1,'Tenda Kapasitas 4 Orang','Tenda keluarga untuk 4 orang, double layer, anti UV',100000,5,NULL,'2025-12-09 05:51:13','2025-12-09 05:51:13'),
(4,1,'Tenda Kapasitas 6 Orang','Tenda besar untuk 6 orang, cocok untuk keluarga besar',150000,3,NULL,'2025-12-09 05:51:13','2025-12-09 05:51:13'),
(5,2,'Sleeping Bag Musim Panas','Sleeping bag ringan untuk cuaca hangat',25000,15,NULL,'2025-12-09 05:51:13','2025-12-09 05:51:13'),
(6,2,'Sleeping Bag Musim Dingin','Sleeping bag tebal untuk suhu dingin hingga 5°C',40000,10,NULL,'2025-12-09 05:51:13','2025-12-09 05:51:13'),
(7,2,'Sleeping Bag Premium','Sleeping bag premium dengan bahan berkualitas tinggi',60000,8,NULL,'2025-12-09 05:51:13','2025-12-09 05:51:13'),
(8,3,'Carrier 40L','Carrier kapasitas 40 liter untuk hiking 1-2 hari',35000,9,NULL,'2025-12-09 05:51:13','2025-12-30 20:44:26'),
(9,3,'Carrier 60L','Carrier kapasitas 60 liter untuk hiking 3-4 hari',50000,7,NULL,'2025-12-09 05:51:13','2025-12-30 19:19:02'),
(10,3,'Carrier 80L','Carrier besar 80 liter untuk ekspedisi panjang',75000,5,NULL,'2025-12-09 05:51:13','2025-12-09 05:51:13'),
(11,4,'Kompor Gas Mini','Kompor gas portable ukuran mini, hemat dan praktis',20000,19,NULL,'2025-12-09 05:51:13','2025-12-30 16:26:10'),
(12,4,'Kompor Gas Double Burner','Kompor gas 2 tungku untuk memasak lebih cepat',35000,10,NULL,'2025-12-09 05:51:13','2025-12-30 20:23:03'),
(13,4,'Kompor Spiritus','Kompor spiritus portable, aman dan mudah digunakan',15000,15,NULL,'2025-12-09 05:51:13','2025-12-09 05:51:13'),
(14,5,'Lampu LED Gantung','Lampu LED gantung dengan 3 mode pencahayaan',15000,22,NULL,'2025-12-09 05:51:13','2025-12-30 20:36:37'),
(15,5,'Headlamp LED','Headlamp LED untuk aktivitas malam hari',20000,18,NULL,'2025-12-09 05:51:13','2025-12-30 20:50:47'),
(16,5,'Senter LED Rechargeable','Senter LED bisa dicharge ulang, tahan air',25000,15,NULL,'2025-12-09 05:51:13','2025-12-23 05:21:39'),
(17,5,'Lampu Emergency Solar','Lampu emergency dengan panel solar dan powerbank',30000,10,NULL,'2025-12-09 05:51:13','2025-12-23 05:21:39');

/*Table structure for table `cache` */

DROP TABLE IF EXISTS `cache`;

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cache` */

/*Table structure for table `cache_locks` */

DROP TABLE IF EXISTS `cache_locks`;

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cache_locks` */

/*Table structure for table `detail_pengembalian` */

DROP TABLE IF EXISTS `detail_pengembalian`;

CREATE TABLE `detail_pengembalian` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_pengembalian` bigint(20) unsigned NOT NULL,
  `id_barang` bigint(20) unsigned NOT NULL,
  `kondisi` enum('baik','rusak ringan','rusak berat','hilang') NOT NULL,
  `denda` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `detail_pengembalian_id_pengembalian_foreign` (`id_pengembalian`),
  KEY `detail_pengembalian_id_barang_foreign` (`id_barang`),
  CONSTRAINT `detail_pengembalian_id_barang_foreign` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `detail_pengembalian_id_pengembalian_foreign` FOREIGN KEY (`id_pengembalian`) REFERENCES `pengembalian` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `detail_pengembalian` */

insert  into `detail_pengembalian`(`id`,`id_pengembalian`,`id_barang`,`kondisi`,`denda`,`created_at`,`updated_at`) values 
(1,1,11,'baik',0,'2025-12-23 05:13:26','2025-12-23 05:13:26'),
(2,1,9,'baik',0,'2025-12-23 05:13:26','2025-12-23 05:13:26'),
(3,2,8,'baik',0,'2025-12-23 05:21:39','2025-12-23 05:21:39'),
(4,2,15,'baik',0,'2025-12-23 05:21:39','2025-12-23 05:21:39'),
(5,2,11,'rusak berat',10000,'2025-12-23 05:21:39','2025-12-23 05:21:39'),
(6,2,17,'baik',0,'2025-12-23 05:21:39','2025-12-23 05:21:39'),
(7,2,14,'hilang',15000,'2025-12-23 05:21:39','2025-12-23 05:21:39'),
(8,2,16,'baik',0,'2025-12-23 05:21:39','2025-12-23 05:21:39'),
(9,3,8,'baik',0,'2025-12-30 19:27:02','2025-12-30 19:27:02'),
(10,4,12,'rusak ringan',7000,'2025-12-30 20:23:03','2025-12-30 20:23:03');

/*Table structure for table `detail_sewa` */

DROP TABLE IF EXISTS `detail_sewa`;

CREATE TABLE `detail_sewa` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_sewa` bigint(20) unsigned NOT NULL,
  `id_barang` bigint(20) unsigned NOT NULL,
  `qty` int(10) unsigned NOT NULL,
  `harga` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `detail_sewa_id_sewa_foreign` (`id_sewa`),
  KEY `detail_sewa_id_barang_foreign` (`id_barang`),
  CONSTRAINT `detail_sewa_id_barang_foreign` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `detail_sewa_id_sewa_foreign` FOREIGN KEY (`id_sewa`) REFERENCES `sewa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `detail_sewa` */

insert  into `detail_sewa`(`id`,`id_sewa`,`id_barang`,`qty`,`harga`,`created_at`,`updated_at`) values 
(1,1,11,1,20000,'2025-12-23 05:09:28','2025-12-23 05:09:28'),
(2,1,9,1,50000,'2025-12-23 05:09:28','2025-12-23 05:09:28'),
(3,2,8,1,35000,'2025-12-23 05:19:35','2025-12-23 05:19:35'),
(4,2,15,1,20000,'2025-12-23 05:19:35','2025-12-23 05:19:35'),
(5,2,11,1,20000,'2025-12-23 05:19:35','2025-12-23 05:19:35'),
(6,2,17,1,30000,'2025-12-23 05:19:35','2025-12-23 05:19:35'),
(7,2,14,1,15000,'2025-12-23 05:19:35','2025-12-23 05:19:35'),
(8,2,16,1,25000,'2025-12-23 05:19:35','2025-12-23 05:19:35'),
(9,3,8,1,35000,'2025-12-30 16:26:11','2025-12-30 16:26:11'),
(10,3,15,1,20000,'2025-12-30 16:26:11','2025-12-30 16:26:11'),
(11,3,11,1,20000,'2025-12-30 16:26:11','2025-12-30 16:26:11'),
(12,3,14,1,15000,'2025-12-30 16:26:11','2025-12-30 16:26:11'),
(13,4,8,1,35000,'2025-12-30 16:48:09','2025-12-30 16:48:09'),
(14,5,8,1,35000,'2025-12-30 19:03:01','2025-12-30 19:03:01'),
(15,6,9,1,50000,'2025-12-30 19:19:02','2025-12-30 19:19:02'),
(16,7,12,1,35000,'2025-12-30 20:20:45','2025-12-30 20:20:45'),
(17,8,14,1,15000,'2025-12-30 20:36:37','2025-12-30 20:36:37'),
(19,10,15,1,20000,'2025-12-30 20:50:47','2025-12-30 20:50:47');

/*Table structure for table `failed_jobs` */

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `failed_jobs` */

/*Table structure for table `job_batches` */

DROP TABLE IF EXISTS `job_batches`;

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `job_batches` */

/*Table structure for table `jobs` */

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `jobs` */

/*Table structure for table `kategori_barang` */

DROP TABLE IF EXISTS `kategori_barang`;

CREATE TABLE `kategori_barang` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `kategori_barang` */

insert  into `kategori_barang`(`id`,`nama_kategori`,`created_at`,`updated_at`) values 
(1,'Tas','2025-12-04 08:42:47','2025-12-04 08:42:47'),
(2,'Tenda','2025-12-09 05:51:13','2025-12-09 05:51:13'),
(3,'Sleeping Bag','2025-12-09 05:51:13','2025-12-09 05:51:13'),
(4,'Carrier','2025-12-09 05:51:13','2025-12-09 05:51:13'),
(5,'Kompor Portable','2025-12-09 05:51:13','2025-12-09 05:51:13'),
(6,'Lampu Camping','2025-12-09 05:51:13','2025-12-09 05:51:13');

/*Table structure for table `keranjang` */

DROP TABLE IF EXISTS `keranjang`;

CREATE TABLE `keranjang` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_pelanggan` bigint(20) unsigned NOT NULL,
  `id_barang` bigint(20) unsigned NOT NULL,
  `qty` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `keranjang_id_pelanggan_foreign` (`id_pelanggan`),
  KEY `keranjang_id_barang_foreign` (`id_barang`),
  CONSTRAINT `keranjang_id_barang_foreign` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id`),
  CONSTRAINT `keranjang_id_pelanggan_foreign` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `keranjang` */

insert  into `keranjang`(`id`,`id_pelanggan`,`id_barang`,`qty`,`created_at`,`updated_at`) values 
(8,2,8,1,'2025-12-30 19:36:44','2025-12-30 19:36:44');

/*Table structure for table `metode_pembayaran` */

DROP TABLE IF EXISTS `metode_pembayaran`;

CREATE TABLE `metode_pembayaran` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_bank` varchar(255) NOT NULL,
  `no_rek` varchar(255) NOT NULL,
  `nama_pemilik` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `metode_pembayaran` */

insert  into `metode_pembayaran`(`id`,`nama_bank`,`no_rek`,`nama_pemilik`,`created_at`,`updated_at`) values 
(1,'BCA Virtual Account','8808012345678901','PT Camping Rental Indonesia','2025-12-09 05:51:13','2025-12-09 05:51:13'),
(2,'Mandiri Virtual Account','8808098765432109','PT Camping Rental Indonesia','2025-12-09 05:51:13','2025-12-09 05:51:13'),
(3,'BRI Virtual Account','8808055556666777','PT Camping Rental Indonesia','2025-12-09 05:51:13','2025-12-09 05:51:13'),
(4,'QRIS','ID1234567890QRIS','PT Camping Rental Indonesia','2025-12-09 05:51:13','2025-12-09 05:51:13');

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`migration`,`batch`) values 
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2025_12_04_075713_create_personal_access_tokens_table',1),
(5,'2025_12_04_090000_update_users_table_for_roles_and_profile',1),
(6,'2025_12_04_091000_create_kategori_barang_table',1),
(7,'2025_12_04_091100_create_barang_table',1),
(8,'2025_12_04_091200_create_pelanggan_table',1),
(9,'2025_12_04_091300_create_metode_pembayaran_table',1),
(10,'2025_12_04_091400_create_sewa_table',1),
(11,'2025_12_04_091500_create_detail_sewa_table',1),
(12,'2025_12_04_091600_create_pengembalian_table',1),
(13,'2025_12_04_091700_create_detail_pengembalian_table',1),
(14,'2025_12_09_053953_add_foto_ktp_to_sewa_table',2),
(15,'2025_12_30_070000_add_midtrans_fields_to_sewa_table',3),
(16,'2025_12_30_080000_create_keranjang_table',3);

/*Table structure for table `password_reset_tokens` */

DROP TABLE IF EXISTS `password_reset_tokens`;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `password_reset_tokens` */

/*Table structure for table `pelanggan` */

DROP TABLE IF EXISTS `pelanggan`;

CREATE TABLE `pelanggan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` bigint(20) unsigned NOT NULL,
  `nik` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `telp` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pelanggan_nik_unique` (`nik`),
  KEY `pelanggan_id_user_foreign` (`id_user`),
  CONSTRAINT `pelanggan_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `pelanggan` */

insert  into `pelanggan`(`id`,`id_user`,`nik`,`alamat`,`telp`,`created_at`,`updated_at`) values 
(1,5,'1371091002000007','Padang','081234567890','2025-12-23 05:00:29','2025-12-23 05:00:29'),
(2,6,'1371091002000000','Padang','081234567890','2025-12-23 05:19:14','2025-12-23 05:19:14');

/*Table structure for table `pengembalian` */

DROP TABLE IF EXISTS `pengembalian`;

CREATE TABLE `pengembalian` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_sewa` bigint(20) unsigned NOT NULL,
  `tanggal_pengembalian` date NOT NULL,
  `total_denda` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pengembalian_id_sewa_foreign` (`id_sewa`),
  CONSTRAINT `pengembalian_id_sewa_foreign` FOREIGN KEY (`id_sewa`) REFERENCES `sewa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `pengembalian` */

insert  into `pengembalian`(`id`,`id_sewa`,`tanggal_pengembalian`,`total_denda`,`created_at`,`updated_at`) values 
(1,1,'2026-01-02',0,'2025-12-23 05:13:26','2025-12-23 05:13:26'),
(2,2,'2025-12-31',25000,'2025-12-23 05:21:39','2025-12-23 05:21:39'),
(3,5,'2025-12-30',0,'2025-12-30 19:27:02','2025-12-30 19:27:02'),
(4,7,'2026-01-10',7000,'2025-12-30 20:23:03','2025-12-30 20:23:03');

/*Table structure for table `personal_access_tokens` */

DROP TABLE IF EXISTS `personal_access_tokens`;

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `personal_access_tokens` */

insert  into `personal_access_tokens`(`id`,`tokenable_type`,`tokenable_id`,`name`,`token`,`abilities`,`last_used_at`,`expires_at`,`created_at`,`updated_at`) values 
(1,'App\\Models\\User',2,'user_token','bf518d9649944a7875cffc31cbf400088a9ab4545e0cd5db5c361eafa95ced0b','[\"*\"]',NULL,NULL,'2025-12-04 08:56:42','2025-12-04 08:56:42'),
(2,'App\\Models\\User',2,'user_token','ccc512617878a173c77ac414cc9b497a9ebc7f8ecf289a8afc8d4b76986ed129','[\"*\"]',NULL,NULL,'2025-12-04 08:57:55','2025-12-04 08:57:55'),
(9,'App\\Models\\User',6,'user_token','0205eed43ee4a71fb62ba5503bb8bb3621ea39ebb4e29e5f7e6f00657b23c0f7','[\"*\"]','2025-12-30 16:48:15',NULL,'2025-12-30 16:47:33','2025-12-30 16:48:15'),
(10,'App\\Models\\User',6,'user_token','29f6969e4438521a9c86c79ebf2e8ba1b232482b4cef62ee6d17f9dbe15668cc','[\"*\"]','2025-12-30 18:49:47',NULL,'2025-12-30 18:49:33','2025-12-30 18:49:47'),
(12,'App\\Models\\User',5,'user_token','52f90e9c8e637f0994f2bd52c13eb600821072b823d21a3d5d2dfca6d8f0829a','[\"*\"]','2025-12-30 21:11:59',NULL,'2025-12-30 20:19:44','2025-12-30 21:11:59');

/*Table structure for table `sessions` */

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `sessions` */

insert  into `sessions`(`id`,`user_id`,`ip_address`,`user_agent`,`payload`,`last_activity`) values 
('w8NQMuYabqq3paeOU7FLCvkp0Zyl3Ri3vEiAzSiF',1,'192.168.100.48','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYUxPUUpIZDJZV0tyM3NqdFpHZDlnV1lPbGlVcGQzUWFiNjlJT0Z3NCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDI6Imh0dHA6Ly8xOTIuMTY4LjEwMC40ODo4MDAwL2FkbWluL2Rhc2hib2FyZCI7czo1OiJyb3V0ZSI7czoxNToiYWRtaW4uZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9',1767129332);

/*Table structure for table `sewa` */

DROP TABLE IF EXISTS `sewa`;

CREATE TABLE `sewa` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_pelanggan` bigint(20) unsigned NOT NULL,
  `tanggal_sewa` date NOT NULL,
  `tanggal_kembali` date NOT NULL,
  `total_harga` int(10) unsigned NOT NULL,
  `status` enum('pending','dibayar','dipinjam','dikembalikan','batal') NOT NULL DEFAULT 'pending',
  `bukti_bayar` varchar(255) DEFAULT NULL,
  `foto_ktp` varchar(255) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `midtrans_order_id` varchar(255) DEFAULT NULL,
  `midtrans_transaction_id` varchar(255) DEFAULT NULL,
  `payment_type` varchar(255) DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sewa_id_pelanggan_foreign` (`id_pelanggan`),
  CONSTRAINT `sewa_id_pelanggan_foreign` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `sewa` */

insert  into `sewa`(`id`,`id_pelanggan`,`tanggal_sewa`,`tanggal_kembali`,`total_harga`,`status`,`bukti_bayar`,`foto_ktp`,`catatan`,`midtrans_order_id`,`midtrans_transaction_id`,`payment_type`,`paid_at`,`created_at`,`updated_at`) values 
(1,1,'2025-12-23','2025-12-24',70000,'dikembalikan','bukti-bayar/G0FJFZg62G875KbbDnhZSOcuGvQYFYLiZipgHeRS.png',NULL,NULL,NULL,NULL,NULL,NULL,'2025-12-23 05:09:28','2025-12-23 05:13:26'),
(2,2,'2025-12-23','2025-12-31',1160000,'dikembalikan','bukti-bayar/7lyhls8eaJPD2CMqgadgSM61v2wyYrX2EQSdvANO.png',NULL,'Coba coba 2',NULL,NULL,NULL,NULL,'2025-12-23 05:19:35','2025-12-23 05:21:39'),
(3,1,'2025-12-30','2026-01-03',360000,'pending',NULL,NULL,'Test Midtrans 1',NULL,NULL,NULL,NULL,'2025-12-30 16:26:11','2025-12-30 16:26:11'),
(4,2,'2025-12-30','2025-12-31',35000,'pending',NULL,NULL,'Test Midtrans 2','RENT-4-1767113295',NULL,NULL,NULL,'2025-12-30 16:48:09','2025-12-30 16:48:15'),
(5,2,'2025-12-30','2025-12-31',35000,'dikembalikan',NULL,NULL,'Yy','RENT-5-1767121392','9679f2ba-3fc9-4acb-8948-ef5092138491','bank_transfer','2025-12-30 19:17:54','2025-12-30 19:03:01','2025-12-30 19:27:02'),
(6,2,'2025-12-30','2025-12-31',50000,'batal',NULL,NULL,'Uu','RENT-6-1767122346',NULL,NULL,NULL,'2025-12-30 19:19:02','2025-12-30 19:21:06'),
(7,1,'2025-12-30','2025-12-31',35000,'dipinjam',NULL,NULL,'Test Midtrans 3','RENT-7-1767126051','983f5df7-ca15-4a03-b97f-6f57a4002787','bank_transfer','2025-12-30 20:22:07','2025-12-30 20:20:45','2025-12-30 20:24:03'),
(8,1,'2025-12-30','2025-12-31',15000,'dibayar','bukti-bayar/2An8397fjCI5mKvuCxvz4qInsjxQyPAyGAnlVMgU.png',NULL,'Test upload KTP',NULL,NULL,NULL,NULL,'2025-12-30 20:36:37','2025-12-30 20:36:53'),
(10,1,'2025-12-30','2025-12-31',20000,'dibayar','bukti-bayar/OXZ0BJb84lV3r5sgvgQyewUqaZBuhgSfCRKhEHRd.png','ktp/PJumwGQfJOUfvFkJUtqQufixWnr5YS9n9xei8Gna.jpg','Test KTP Final',NULL,NULL,NULL,NULL,'2025-12-30 20:50:47','2025-12-30 20:51:03');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `alamat` text DEFAULT NULL,
  `telp` varchar(255) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`nama`,`username`,`email`,`email_verified_at`,`password`,`role`,`alamat`,`telp`,`foto`,`remember_token`,`created_at`,`updated_at`) values 
(1,'Admin','Admin','admin@gmail.com',NULL,'$2y$12$N3dJBMETGHrBPiluPz4QieoLAvQae/wL/wmKyaszfHgQhVk5YtlUq','admin',NULL,NULL,NULL,NULL,'2025-12-04 08:42:17','2025-12-04 08:42:17'),
(3,'Administrator','admin','admin@camping.com',NULL,'$2y$12$gmEFBoSr2Ugfh77CkKYvY.9fHjkVIwmXDLv03kqe3zsfCHELPsi/6','admin','Jl. Admin No. 1, Jakarta','081234567890',NULL,NULL,'2025-12-09 05:51:13','2025-12-09 05:51:13'),
(5,'Anton Sabu','anton','anton@gmail.com',NULL,'$2y$12$dOuY0tr2Iy3xN7aqLIK7SuH7lDwWhZAT1mz0mXSuyyikeSZFS77dW','user','Padang','081234567890','profiles/1tLZS6WJ0UtIunlteDtCIELgM8EAo32TnWZVB9yK.jpg',NULL,'2025-12-23 05:00:29','2025-12-30 21:11:57'),
(6,'Yudha Bima Sakti','yudhabimasakti787','yudhabimasakti787@gmail.com',NULL,'$2y$12$s.Ex/04m2eHP7lM6jsByGON64y/0eZTAnpGYsM9wfpsa2ugfFO/ye','user','Padang','081234567890',NULL,NULL,'2025-12-23 05:19:14','2025-12-23 05:19:14');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
