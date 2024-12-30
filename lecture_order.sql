-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- 생성 시간: 24-12-22 13:31
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
-- 테이블 구조 `lecture_order`
--

CREATE TABLE `lecture_order` (
  `odid` int(11) NOT NULL COMMENT '주문번호',
  `mid` varchar(100) NOT NULL COMMENT '회원 고유번호',
  `lid` varchar(20) NOT NULL COMMENT '강의 고유번호',
  `cid` int(11) DEFAULT NULL COMMENT '쿠폰 고유 번호',
  `total_price` decimal(10,0) NOT NULL COMMENT '최종 결제 가격',
  `status` tinyint(4) NOT NULL COMMENT '주문 상태',
  `createdate` datetime NOT NULL DEFAULT current_timestamp() COMMENT '주문 시각'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 테이블의 덤프 데이터 `lecture_order`
--

INSERT INTO `lecture_order` (`odid`, `mid`, `lid`, `cid`, `total_price`, `status`, `createdate`) VALUES
(41, 'user1@example.com', '121', 0, 116000, 1, '2024-12-22 19:20:37'),
(42, 'user2@example.com', '121', 0, 116000, 1, '2024-09-02 19:21:07'),
(43, 'user3@example.com', '121', 0, 116000, 1, '2024-08-06 19:21:07'),
(44, 'user4@example.com', '121', 0, 116000, 1, '2024-10-08 19:21:07'),
(45, 'user5@example.com', '121', 0, 116000, 1, '2024-07-08 19:21:07'),
(46, 'user7@example.com', '121', 0, 116000, 1, '2024-10-07 19:22:33'),
(47, 'user6@example.com', '121', 0, 116000, 1, '2024-08-06 19:22:33'),
(48, 'user8@example.com', '121', 0, 116000, 1, '2024-09-09 19:22:33'),
(49, 'user9@example.com', '121', 0, 116000, 1, '2024-05-01 19:22:33'),
(50, 'user10@example.com', '121', 0, 116000, 1, '2024-06-30 19:22:33'),
(51, 'user1@example.com', '143', 0, 77000, 1, '2024-11-13 20:01:59'),
(52, 'user2@example.com', '143', 0, 77000, 1, '2024-10-17 20:02:04'),
(53, 'user3@example.com', '143', 0, 77000, 1, '2024-12-22 20:02:04'),
(54, 'user4@example.com', '143', 0, 77000, 1, '2024-10-07 20:02:04'),
(55, 'user5@example.com', '143', 0, 77000, 1, '2024-12-22 20:02:04'),
(56, 'user1@example.com', '119', 0, 33000, 1, '2024-12-22 20:33:57'),
(57, 'user2@example.com', '119', 0, 33000, 1, '2024-11-04 20:34:26'),
(58, 'user3@example.com', '119', 0, 33000, 1, '2024-10-06 20:34:26'),
(59, 'user4@example.com', '119', 0, 33000, 1, '2024-10-09 20:34:26'),
(60, 'user5@example.com', '119', 0, 33000, 1, '2024-09-05 20:34:26'),
(61, 'user6@example.com', '119', 0, 33000, 1, '2024-12-22 20:34:26'),
(62, 'user1@example.com', '120', 0, 22000, 1, '2024-12-22 20:35:57'),
(63, 'user2@example.com', '120', 0, 22000, 1, '2024-12-22 20:36:13'),
(64, 'user3@example.com', '120', 0, 22000, 1, '2024-12-22 20:36:13'),
(65, 'user4@example.com', '120', 0, 22000, 1, '2024-06-11 20:36:13'),
(66, 'user9@example.com', '120', 0, 22000, 1, '2024-07-09 20:36:13'),
(67, 'user10@example.com', '120', 0, 22000, 1, '2024-08-06 20:36:13'),
(68, 'user11@example.com', '120', 0, 22000, 1, '2024-08-12 20:36:13'),
(69, 'user12@example.com', '120', 0, 22000, 1, '2024-09-12 20:36:13'),
(70, 'user13@example.com', '120', 0, 22000, 1, '2024-09-06 20:36:13'),
(71, 'user14@example.com', '120', 0, 22000, 1, '2024-10-18 20:36:13'),
(72, 'user15@example.com', '120', 0, 22000, 1, '2024-10-18 20:36:13'),
(73, 'user16@example.com', '120', 0, 22000, 1, '2024-10-11 20:36:13'),
(74, 'user17@example.com', '120', 0, 22000, 1, '2024-10-03 20:36:13'),
(75, 'user18@example.com', '120', 0, 22000, 1, '2024-11-08 20:36:13'),
(76, 'user19@example.com', '120', 0, 22000, 1, '2024-11-13 20:36:13'),
(77, 'user20@example.com', '120', 0, 22000, 1, '2024-11-20 20:36:13'),
(78, 'user21@example.com', '120', 0, 22000, 1, '2024-12-22 20:41:03'),
(79, 'user22@example.com', '120', 0, 22000, 1, '2024-01-19 20:41:03'),
(80, 'user23@example.com', '120', 0, 22000, 1, '2024-01-15 20:41:03'),
(81, 'user24@example.com', '120', 0, 22000, 1, '2024-02-06 20:41:03'),
(82, 'user25@example.com', '120', 0, 22000, 1, '2024-03-06 20:41:03'),
(83, 'user26@example.com', '120', 0, 22000, 1, '2024-04-25 20:41:03'),
(84, 'user27@example.com', '120', 0, 22000, 1, '2024-04-08 20:41:03'),
(85, 'user28@example.com', '120', 0, 22000, 1, '2024-05-15 20:41:03'),
(86, 'user29@example.com', '120', 0, 22000, 1, '2024-05-07 20:41:03'),
(87, 'user30@example.com', '120', 0, 22000, 1, '2024-06-13 20:41:03'),
(88, 'user21@example.com', '121', 0, 121000, 1, '2024-11-08 20:44:03'),
(89, 'user22@example.com', '121', 0, 121000, 1, '2024-11-14 20:44:03'),
(90, 'user23@example.com', '121', 0, 121000, 1, '2024-12-22 20:44:03'),
(91, 'user11@example.com', '121', 0, 121000, 1, '2024-12-22 20:44:03'),
(92, 'gwaja97@naver.com', '132', 0, 33000, 1, '2024-12-22 21:04:06');

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `lecture_order`
--
ALTER TABLE `lecture_order`
  ADD PRIMARY KEY (`odid`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `lecture_order`
--
ALTER TABLE `lecture_order`
  MODIFY `odid` int(11) NOT NULL AUTO_INCREMENT COMMENT '주문번호', AUTO_INCREMENT=93;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
