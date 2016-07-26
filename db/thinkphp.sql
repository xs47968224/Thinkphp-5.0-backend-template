-- phpMyAdmin SQL Dump
-- version 4.5.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2016-07-26 11:39:20
-- 服务器版本： 5.6.31
-- PHP Version: 5.6.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `thinkphp`
--

-- --------------------------------------------------------

--
-- 表的结构 `think_administrator`
--

CREATE TABLE `think_administrator` (
  `id` int(11) NOT NULL,
  `nickname` varchar(100) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `salt` char(3) DEFAULT NULL,
  `mobile` varchar(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `last_login_ip` varchar(100) DEFAULT NULL,
  `last_login_time` int(11) DEFAULT NULL,
  `expire_time` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `think_administrator`
--

INSERT INTO `think_administrator` (`id`, `nickname`, `username`, `password`, `salt`, `mobile`, `status`, `avatar`, `last_login_ip`, `last_login_time`, `expire_time`, `create_time`, `update_time`) VALUES
(1, 'Admin', 'admin', '7c99b344032cd02cb1c1ae958174fd82', '112', '13888888888', 1, NULL, '127.0.0.1', 1469530998, 1469534598, 1463362516, 1469511520),
(2, 'Editor', 'editor', 'df620c97d6c8a15b672191fe11b9a886', '519', '13888888888', 1, NULL, '127.0.0.1', 1469508726, 1469513500, 1463363564, 1469513500),
(3, '刘晨', 'liuchen', 'bc9ab605e3046a23a80a679612a6b77b', '321', '15088888888', 1, '57971ff092983_thumb.jpg', NULL, 1469509428, 1469511172, 1469164654, 1469521904),
(6, '流年2', 'liunian2', '111111', '111', '13888888888', 1, NULL, NULL, 1469509554, 1469531035, 1469165729, 1469531035),
(7, '流年3', 'liunian3', '111111', '111', '13888888888', 1, NULL, NULL, NULL, 1469511483, 1469166592, 1469511483),
(8, '刘晨123', 'liuchen123', '386933bc9fc02c9cd6a5396d25afaaf0', '322', '13888888888', 1, NULL, NULL, NULL, NULL, 1469166690, 1469511765),
(9, 'Kevin', 'root', '', '0', '13888888888', 1, NULL, NULL, NULL, NULL, 1469171170, 1469444188),
(10, '123123123', '13123123', '123123', '123', '13888888888', 1, NULL, NULL, NULL, NULL, 1469495815, 1469495815),
(20, '3213123', '321123123', '4297f44b13955235245b2497399d7a93', '123', '13888888888', 1, NULL, NULL, NULL, NULL, 1469501012, 1469501498),
(14, '123321', 'r1r32r', '5f4dcc3b5aa765d61d8327deb882cf99', '222', '13888888888', -1, NULL, NULL, NULL, NULL, 1469500375, 1469513370),
(15, '3213123', '3212313', 'f668bd04d1a6cfc29378e24829cddba9', '333', '13888888888', 1, NULL, NULL, NULL, NULL, 1469496441, 1469501288),
(18, 'd123', 'sdfasdfasdf', '4297f44b13955235245b2497399d7a93', '323', '13888888888', 1, NULL, NULL, NULL, NULL, 1469496682, 1469496682),
(19, 'testtest1', 'testtest1', '4297f44b13955235245b2497399d7a93', '123', '13888888888', 1, NULL, NULL, NULL, NULL, 1469497668, 1469501304),
(21, 'test123123', 'test123123', '912b7bc95fb9e6885a4685746433f39a', '123', '15088888888', 1, '579720776d0c1_thumb.jpg', NULL, NULL, NULL, 1469522039, 1469522039);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `think_administrator`
--
ALTER TABLE `think_administrator`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `think_administrator`
--
ALTER TABLE `think_administrator`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
