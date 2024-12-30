-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- 생성 시간: 24-12-21 09:43
-- 서버 버전: 10.4.32-MariaDB
-- PHP 버전: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 데이터베이스: `quantumcode`
--

-- --------------------------------------------------------

--
-- 테이블 구조 `lecture_watch`
--

CREATE TABLE `lecture_watch` (
  `id` int(11) NOT NULL,
  `mid` varchar(255) NOT NULL,
  `lid` int(20) NOT NULL,
  `lvid` int(20) NOT NULL,
  `event_type` varchar(255) NOT NULL,
  `timestamp` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 테이블의 덤프 데이터 `lecture_watch`
--

INSERT INTO `lecture_watch` (`id`, `mid`, `lid`, `lvid`, `event_type`, `timestamp`) VALUES
(17, 'gwaja97@naver.com', 130, 120, 'start', '2024-12-21 08:43:03'),
(18, 'gwaja97@naver.com', 130, 120, 'completed', '2024-12-21 08:43:20');

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `lecture_watch`
--
ALTER TABLE `lecture_watch`
  ADD PRIMARY KEY (`id`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `lecture_watch`
--
ALTER TABLE `lecture_watch`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
