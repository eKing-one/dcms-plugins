-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Июн 17 2013 г., 09:53
-- Версия сервера: 5.5.25
-- Версия PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `wapka`
--

-- --------------------------------------------------------

--
-- Структура таблицы `diary`
--
-- Создание: Июн 01 2013 г., 11:56
-- Последнее обновление: Июн 17 2013 г., 05:45
-- Последняя проверка: Июн 09 2013 г., 11:50
--

CREATE TABLE IF NOT EXISTS `diary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cat` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `msg` varchar(10000) NOT NULL,
  `readers` set('0','1','2') NOT NULL DEFAULT '0',
  `viewings` int(11) NOT NULL DEFAULT '0',
  `rating` int(11) NOT NULL DEFAULT '0',
  `tags` varchar(128) DEFAULT NULL,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1888 ;

-- --------------------------------------------------------

--
-- Структура таблицы `diary_cat`
--
-- Создание: Июн 01 2013 г., 11:56
-- Последнее обновление: Июн 01 2013 г., 11:56
-- Последняя проверка: Июн 01 2013 г., 13:46
--

CREATE TABLE IF NOT EXISTS `diary_cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `desc` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

--
-- Структура таблицы `diary_images`
--
-- Создание: Июн 01 2013 г., 11:56
-- Последнее обновление: Июн 01 2013 г., 11:56
-- Последняя проверка: Июн 01 2013 г., 13:46
--

CREATE TABLE IF NOT EXISTS `diary_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_diary` int(11) NOT NULL,
  `position` set('up','down') NOT NULL DEFAULT 'up',
  `ras` varchar(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=96 ;

-- --------------------------------------------------------

--
-- Структура таблицы `diary_komm`
--
-- Создание: Июн 01 2013 г., 11:56
-- Последнее обновление: Июн 17 2013 г., 05:41
-- Последняя проверка: Июн 01 2013 г., 13:46
--

CREATE TABLE IF NOT EXISTS `diary_komm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_diary` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `msg` varchar(1024) NOT NULL,
  `reply` varchar(1024) NOT NULL,
  `who_reply` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=607 ;

-- --------------------------------------------------------

--
-- Структура таблицы `diary_rating`
--
-- Создание: Июн 01 2013 г., 11:56
-- Последнее обновление: Июн 17 2013 г., 05:28
-- Последняя проверка: Июн 01 2013 г., 13:46
--

CREATE TABLE IF NOT EXISTS `diary_rating` (
  `id_diary` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `rating` int(11) NOT NULL DEFAULT '0',
  KEY `id_diary` (`id_diary`,`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
