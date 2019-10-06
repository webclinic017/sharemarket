-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 30, 2019 at 02:23 AM
-- Server version: 5.7.23
-- PHP Version: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `share`
--

-- --------------------------------------------------------

--
-- Table structure for table `dateinsert_report`
--

DROP TABLE IF EXISTS `dateinsert_report`;
CREATE TABLE IF NOT EXISTS `dateinsert_report` (
  `report` smallint(191) NOT NULL,
  `date` date NOT NULL,
  KEY `report` (`report`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oi_data`
--

DROP TABLE IF EXISTS `oi_data`;
CREATE TABLE IF NOT EXISTS `oi_data` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `symbol` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mwpl` bigint(20) DEFAULT NULL,
  `open_interest` bigint(20) DEFAULT NULL,
  `limitNextDay` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `watchlist` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oi_spurt`
--

DROP TABLE IF EXISTS `oi_spurt`;
CREATE TABLE IF NOT EXISTS `oi_spurt` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `symbol` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `instrument` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expiry` date DEFAULT NULL,
  `strike` bigint(20) DEFAULT NULL,
  `optionType` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ltp` decimal(20,2) DEFAULT NULL,
  `prevClose` decimal(20,2) DEFAULT NULL,
  `percLtpChange` decimal(20,2) DEFAULT NULL,
  `latestOI` bigint(20) DEFAULT NULL,
  `previousOI` bigint(20) DEFAULT NULL,
  `oiChange` bigint(20) DEFAULT NULL,
  `volume` bigint(20) DEFAULT NULL,
  `valueInCrores` bigint(20) DEFAULT NULL,
  `premValueInCrores` decimal(20,2) DEFAULT NULL,
  `underlyValue` decimal(20,2) DEFAULT NULL,
  `type` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `option_chain`
--

DROP TABLE IF EXISTS `option_chain`;
CREATE TABLE IF NOT EXISTS `option_chain` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `oce_id` bigint(20) UNSIGNED NOT NULL,
  `calloi` bigint(20) DEFAULT NULL,
  `callchnginoi` bigint(20) DEFAULT NULL,
  `callvolume` bigint(20) DEFAULT NULL,
  `calliv` smallint(6) DEFAULT NULL,
  `callltp` decimal(20,2) DEFAULT NULL,
  `callnetchng` decimal(20,2) DEFAULT NULL,
  `callbidqty` bigint(20) DEFAULT NULL,
  `callbidprice` decimal(20,2) DEFAULT NULL,
  `callaskprice` decimal(20,2) DEFAULT NULL,
  `callaskqty` bigint(20) DEFAULT NULL,
  `strikeprice` decimal(20,2) DEFAULT NULL,
  `putoi` bigint(20) DEFAULT NULL,
  `putchnginoi` bigint(20) DEFAULT NULL,
  `putvolume` bigint(20) DEFAULT NULL,
  `putiv` smallint(6) DEFAULT NULL,
  `putltp` decimal(20,2) DEFAULT NULL,
  `putnetchng` decimal(20,2) DEFAULT NULL,
  `putbidqty` bigint(20) DEFAULT NULL,
  `putbidprice` decimal(20,2) DEFAULT NULL,
  `putaskprice` decimal(20,2) DEFAULT NULL,
  `putaskqty` bigint(20) DEFAULT NULL,
  `ivratio` decimal(10,2) NOT NULL,
  `expiry` smallint(6) DEFAULT NULL,
  `watchlist` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `option_chain_expiry`
--

DROP TABLE IF EXISTS `option_chain_expiry`;
CREATE TABLE IF NOT EXISTS `option_chain_expiry` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `symbol` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expirydate` date NOT NULL,
  `expiry_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `participant_oi`
--

DROP TABLE IF EXISTS `participant_oi`;
CREATE TABLE IF NOT EXISTS `participant_oi` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `client_type` char(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `future_index_long` bigint(20) NOT NULL,
  `future_index_short` bigint(20) NOT NULL,
  `option_index_call_long` bigint(20) NOT NULL,
  `option_index_put_long` bigint(20) NOT NULL,
  `option_index_call_short` bigint(20) NOT NULL,
  `option_index_put_short` bigint(20) NOT NULL,
  `future_stock_long` bigint(20) NOT NULL,
  `future_stock_short` bigint(20) NOT NULL,
  `option_stock_call_long` bigint(20) NOT NULL,
  `option_stock_put_long` bigint(20) NOT NULL,
  `option_stock_call_short` bigint(20) NOT NULL,
  `option_stock_put_short` bigint(20) NOT NULL,
  `index_long_per` smallint(6) NOT NULL,
  `index_short_per` smallint(6) NOT NULL,
  `stock_long_per` decimal(5,2) NOT NULL,
  `stock_short_per` decimal(5,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pcr`
--

DROP TABLE IF EXISTS `pcr`;
CREATE TABLE IF NOT EXISTS `pcr` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `oce_id` bigint(20) UNSIGNED NOT NULL,
  `totalcalloi` bigint(20) DEFAULT NULL,
  `totalcallvolume` bigint(20) DEFAULT NULL,
  `totalputoi` bigint(20) DEFAULT NULL,
  `totalputvolume` bigint(20) DEFAULT NULL,
  `pcr` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `share_detail`
--

DROP TABLE IF EXISTS `share_detail`;
CREATE TABLE IF NOT EXISTS `share_detail` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `symbol` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isin` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sector_index` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stock_pe` double(8,2) DEFAULT NULL,
  `index_pe` double(8,2) DEFAULT NULL,
  `fno` char(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'n',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `symbol` (`symbol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_data`
--

DROP TABLE IF EXISTS `stock_data`;
CREATE TABLE IF NOT EXISTS `stock_data` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `symbol` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `series` char(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `prev_close` double(8,2) DEFAULT NULL,
  `open` double(8,2) DEFAULT NULL,
  `high` double(8,2) DEFAULT NULL,
  `low` double(8,2) DEFAULT NULL,
  `close` double(8,2) DEFAULT NULL,
  `last_price` double(8,2) DEFAULT NULL,
  `vwap` double(8,2) DEFAULT NULL,
  `total_traded_qty` decimal(30,2) UNSIGNED NOT NULL,
  `turnover` decimal(10,2) UNSIGNED DEFAULT NULL,
  `no_of_trades` decimal(10,2) UNSIGNED DEFAULT NULL,
  `deliverable_qty` decimal(30,2) UNSIGNED NOT NULL,
  `per_delqty_to_trdqty` decimal(10,2) UNSIGNED NOT NULL,
  `combine_oi` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `series` (`series`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
