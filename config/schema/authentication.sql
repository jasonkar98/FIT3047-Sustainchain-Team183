-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 13, 2026 at 09:59 PM
-- Server version: 11.8.6-MariaDB
-- PHP Version: 8.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sustainchain`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE users (
  id int(11) NOT NULL,
  first_name varchar(50) NOT NULL,
  last_name varchar(50) NOT NULL,
  email varchar(255) NOT NULL,
  password varchar(255) NOT NULL,
  role ENUM('user','admin','buyer','seller','manufacturer','farmer') NOT NULL DEFAULT 'user',
  goals TEXT NULL,
  business_values TEXT NULL,
  profile varchar(500) DEFAULT NULL,
  nonce varchar(255) DEFAULT NULL,
  nonce_expiry datetime DEFAULT NULL,
  created datetime DEFAULT NULL,
  modified datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO users (id, first_name, last_name, email, password, nonce, nonce_expiry, created, modified) VALUES
(1, 'Test', 'Example', 'test@example.com', 'secret-password', NULL, NULL, '2026-03-26 18:19:43', '2026-03-26 18:19:43'),
(2, 'Bob', 'Jones', 'bJones@gmail.com', '$2y$12$W1I4N13DjBPQMr7PEwfF9eVCq8x.m2TgVBi6D02b1j6S0GxFLV4Gi', NULL, NULL, '2026-03-28 11:10:21', '2026-03-28 11:10:21'),
(3, 'Bob', 'Jones', 'bobjones@gmail.com', '$2y$12$0gKVmn6xiseEIC048fLrselyXpylLHv7pIFMXbU0TFdxjeyt89ZGi', NULL, NULL, '2026-03-28 11:10:36', '2026-03-28 11:10:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE users
  ADD PRIMARY KEY (id);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE users
  MODIFY id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;