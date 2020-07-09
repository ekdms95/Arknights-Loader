-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Host: 10.0.0.171:3306
-- Generation Time: Jul 03, 2020 at 03:33 AM
-- Server version: 10.1.40-MariaDB
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `akula-cybersport_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `aes_keys`
--

CREATE TABLE `aes_keys` (
  `id` bigint(200) NOT NULL,
  `cipher` text COLLATE utf8_unicode_ci NOT NULL,
  `iv` text COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime DEFAULT NULL,
  `ip` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cheats`
--

CREATE TABLE `cheats` (
  `id` bigint(200) NOT NULL,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `secure` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 's'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `global_bans`
--

CREATE TABLE `global_bans` (
  `id` bigint(200) NOT NULL,
  `hwid` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `launch_log`
--

CREATE TABLE `launch_log` (
  `id` bigint(200) NOT NULL,
  `license_key` text COLLATE utf8_unicode_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `license_keys`
--

CREATE TABLE `license_keys` (
  `id` bigint(200) NOT NULL,
  `license_key` text NOT NULL,
  `cheat` text NOT NULL,
  `time` time NOT NULL,
  `seller` text NOT NULL,
  `status` int(11) NOT NULL,
  `banned` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `loader_settings`
--

CREATE TABLE `loader_settings` (
  `id` bigint(200) NOT NULL,
  `version` text COLLATE utf8_unicode_ci NOT NULL,
  `status` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `loader_settings`
--

INSERT INTO `loader_settings` (`id`, `version`, `status`) VALUES
(1, '0.01', 'Enabled');

-- --------------------------------------------------------

--
-- Table structure for table `panel`
--

CREATE TABLE `panel` (
  `id` bigint(200) NOT NULL,
  `login` text COLLATE utf8_unicode_ci NOT NULL,
  `password` text COLLATE utf8_unicode_ci NOT NULL,
  `access_key` text COLLATE utf8_unicode_ci NOT NULL,
  `job` text COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `banned` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `panel`
--

INSERT INTO `panel` (`id`, `login`, `password`, `access_key`, `job`, `status`, `banned`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'rcjmztnspmatmdeclevipzkhruvvewwoknpjzhxyycbboqadbawqbaxqxtxa', 'admin', 1, 0),
(2, 'reseller', '9efc4ac970619de711752d818c29884a', 'pfkadutsioucbahahlphxvyyknnitjevzkmtrpwfnrvdhityaazbsrxainlh', 'reseller', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` bigint(200) NOT NULL,
  `license_key` text COLLATE utf8_unicode_ci,
  `cheat` text COLLATE utf8_unicode_ci NOT NULL,
  `hwid` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `end` datetime NOT NULL,
  `banned` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aes_keys`
--
ALTER TABLE `aes_keys`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cheats`
--
ALTER TABLE `cheats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `global_bans`
--
ALTER TABLE `global_bans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `launch_log`
--
ALTER TABLE `launch_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `license_keys`
--
ALTER TABLE `license_keys`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loader_settings`
--
ALTER TABLE `loader_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `panel`
--
ALTER TABLE `panel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aes_keys`
--
ALTER TABLE `aes_keys`
  MODIFY `id` bigint(200) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cheats`
--
ALTER TABLE `cheats`
  MODIFY `id` bigint(200) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `global_bans`
--
ALTER TABLE `global_bans`
  MODIFY `id` bigint(200) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `launch_log`
--
ALTER TABLE `launch_log`
  MODIFY `id` bigint(200) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `license_keys`
--
ALTER TABLE `license_keys`
  MODIFY `id` bigint(200) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loader_settings`
--
ALTER TABLE `loader_settings`
  MODIFY `id` bigint(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `panel`
--
ALTER TABLE `panel`
  MODIFY `id` bigint(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` bigint(200) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
