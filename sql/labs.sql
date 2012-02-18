-- phpMyAdmin SQL Dump
-- version 3.3.2deb1ubuntu1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 01, 2012 at 01:35 PM
-- Server version: 5.1.41
-- PHP Version: 5.3.2-1ubuntu4.11

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ssld_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `ssld_labs`
--

CREATE TABLE IF NOT EXISTS `ssld_labs` (
  `LAB_ID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Lab ID',
  `LAB_NAME` char(255) NOT NULL COMMENT 'Lab name',
  `WGS_LAT` double NOT NULL COMMENT 'Lab latitude (WGS Coordinates)',
  `WGS_LONG` double NOT NULL COMMENT 'Lab longitude (WGS Coordinates)',
  `LAB_DESC` char(255) DEFAULT NULL COMMENT 'Lab description',
  `LAB_URL` char(255) DEFAULT NULL COMMENT 'URL of the lab homepage',
  `LAB_INST` char(255) DEFAULT NULL COMMENT 'Lab parent institution',
  `LAB_CITY` char(255) NOT NULL COMMENT 'City in which the lab is located',
  `LAB_COUNTRY` char(255) NOT NULL COMMENT 'Country in which the lab is located',
  PRIMARY KEY (`LAB_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Laboratory coordinates and address' AUTO_INCREMENT=1 ;

