-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Мар 21 2013 г., 10:11
-- Версия сервера: 5.5.25
-- Версия PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `tester`
--

-- --------------------------------------------------------

--
-- Структура таблицы `videoyou`
--

CREATE TABLE IF NOT EXISTS `videoyou` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_category` int(11) NOT NULL DEFAULT '0',
  `id_user` int(11) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL,
  `video` varchar(99) NOT NULL,
  `opis` varchar(10024) NOT NULL,
  `name` varchar(99) NOT NULL,
  `klass` int(11) NOT NULL DEFAULT '0',
  `lakes` int(11) NOT NULL DEFAULT '0',
  `rating` int(11) NOT NULL DEFAULT '0',
  `msg` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `time` (`time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `videoyou`
--

INSERT INTO `videoyou` (`id`, `id_category`, `id_user`, `time`, `video`, `opis`, `name`, `klass`, `lakes`, `rating`, `msg`) VALUES
(1, 2, 1, 1363841998, 'Нойз мс', 'Вселенная бесконечна', '', 0, 0, 0, '45J3niCoRMw'),
(2, 2, 1, 1363844072, 'Гуф - Сегодня - Завтра', 'Гуф - Сегодня - Завтра', '', 0, 0, 0, 'JmNr3sPyuzs'),
(3, 2, 1, 1363844127, 'треть интересные рекомендации.     Войти ›             ГУФ. 2012. НОВЫЙ КЛИП!', 'Участник Hip-Hop All Stars 2012', '', 0, 0, 0, 'tFMxLnJ0xdE');

-- --------------------------------------------------------

--
-- Структура таблицы `videoyou_category`
--

CREATE TABLE IF NOT EXISTS `videoyou_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(99) NOT NULL,
  `icon` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Дамп данных таблицы `videoyou_category`
--

INSERT INTO `videoyou_category` (`id`, `name`, `icon`) VALUES
(1, 'Юмор/Приколы', '4.gif'),
(2, 'Муз. клипы', '10.gif'),
(3, 'Аниме', '28.gif'),
(4, 'Мульты', '9.png'),
(5, 'Происшествия', '19.png'),
(6, 'Новости', '29.gif'),
(7, 'Спорт', '21.png'),
(8, 'Это интересно', '11.gif'),
(9, 'Для взрослых (ХХХ 18 +)', '3.gif'),
(10, 'Фильмы', '8.gif');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
