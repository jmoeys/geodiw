-- phpMyAdmin SQL Dump
-- version 3.3.2deb1ubuntu1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 01, 2012 at 01:19 PM
-- Server version: 5.1.41
-- PHP Version: 5.3.2-1ubuntu4.11

-- SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ssld_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `ssld_users`
--

CREATE TABLE IF NOT EXISTS `ssld_users` (
  `USER_ID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'User ID (auto)',
  `USER_PWD` char(255) NOT NULL COMMENT 'User password (encrypted)',
  `USER_MAIL` char(255) DEFAULT NULL COMMENT 'User e-mail',
  `USER_LOGIN` char(255) NOT NULL COMMENT 'User Login',
  `USER_NAME` char(255) NOT NULL COMMENT 'User Name (full text)',
  `USER_LEVEL` int(10) NOT NULL COMMENT 'User level (0 = admin, 1=editor)',
  PRIMARY KEY (`USER_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

