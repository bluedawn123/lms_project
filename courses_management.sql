-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- 생성 시간: 24-11-28 09:59
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
-- 테이블 구조 `courses_management`
--

CREATE TABLE `courses_management` (
  `eid` int(11) NOT NULL COMMENT '수강 고유번호',
  `mid` int(15) NOT NULL COMMENT '회원 ID (members.mid)',
  `lid` int(11) NOT NULL COMMENT '강의 ID (lecture_list.lid)',
  `start_date` date NOT NULL COMMENT '수강 시작일',
  `end_date` date DEFAULT NULL COMMENT '수강 종료일',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '수강 상태 (1: 진행 중, 2: 완료, 3: 취소)',
  `progress` double DEFAULT 0 COMMENT '진도율 (%)',
  `reg_date` datetime NOT NULL DEFAULT current_timestamp() COMMENT '등록 시간'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='수강 관리 테이블';

--
-- 테이블의 덤프 데이터 `courses_management`
--

INSERT INTO `courses_management` (`eid`, `mid`, `lid`, `start_date`, `end_date`, `status`, `progress`, `reg_date`) VALUES
(1, 24, 120, '2024-11-28', '2025-01-07', 1, 50, '2024-11-28 17:56:53');

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `courses_management`
--
ALTER TABLE `courses_management`
  ADD PRIMARY KEY (`eid`),
  ADD KEY `mid` (`mid`),
  ADD KEY `lid` (`lid`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `courses_management`
--
ALTER TABLE `courses_management`
  MODIFY `eid` int(11) NOT NULL AUTO_INCREMENT COMMENT '수강 고유번호', AUTO_INCREMENT=3;

--
-- 덤프된 테이블의 제약사항
--

--
-- 테이블의 제약사항 `courses_management`
--
ALTER TABLE `courses_management`
  ADD CONSTRAINT `courses_management_ibfk_1` FOREIGN KEY (`mid`) REFERENCES `members` (`mid`) ON DELETE CASCADE,
  ADD CONSTRAINT `courses_management_ibfk_2` FOREIGN KEY (`lid`) REFERENCES `lecture_list` (`lid`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
