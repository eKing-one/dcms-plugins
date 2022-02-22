
-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Сен 08 2014 г., 13:56
-- Версия сервера: 10.0.12-MariaDB
-- Версия PHP: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `u133474124_wapfa`
--

-- --------------------------------------------------------

--
-- Структура таблицы `clan_adm_chat`
--

CREATE TABLE IF NOT EXISTS `clan_adm_chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_clan` int(11) NOT NULL,
  `msg` varchar(1024) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `clan_adm_chat`
--

INSERT INTO `clan_adm_chat` (`id`, `id_user`, `id_clan`, `msg`, `time`) VALUES
(1, 1, 1, 'echo \\"  </td>n\\";\r\necho \\"   </tr>n\\";', 1362271096);

-- --------------------------------------------------------

--
-- Структура таблицы `clan_chat`
--

CREATE TABLE IF NOT EXISTS `clan_chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_clan` int(11) NOT NULL,
  `msg` varchar(1024) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `clan_chat`
--

INSERT INTO `clan_chat` (`id`, `id_user`, `id_clan`, `msg`, `time`) VALUES
(4, 1, 2, 'test', 1365286093);

-- --------------------------------------------------------

--
-- Структура таблицы `clan_jurnal`
--

CREATE TABLE IF NOT EXISTS `clan_jurnal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_clan` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `msg` varchar(1024) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_clan`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `clan_jurnal`
--

INSERT INTO `clan_jurnal` (`id`, `id_clan`, `time`, `msg`) VALUES
(1, 2, 1410170118, 'Банк был распределён на всех пользователей! Каждому пользователю клана досталось по 25 баллов!');

-- --------------------------------------------------------

--
-- Структура таблицы `clan_news`
--

CREATE TABLE IF NOT EXISTS `clan_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_clan` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `msg` varchar(1024) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `clan_news`
--

INSERT INTO `clan_news` (`id`, `id_clan`, `title`, `msg`, `time`) VALUES
(1, 1, 'Графическая Обнова', 'AddDefaultCharset UTF-8', 1362153259);

-- --------------------------------------------------------

--
-- Структура таблицы `clan_prig`
--

CREATE TABLE IF NOT EXISTS `clan_prig` (
  `id_user` int(11) NOT NULL DEFAULT '0',
  `id_kont` int(11) NOT NULL DEFAULT '0',
  `id_clan` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `clan_user`
--

CREATE TABLE IF NOT EXISTS `clan_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_clan` int(11) NOT NULL,
  `level` set('0','1','2') NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL,
  `activaty` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `clan_user`
--

INSERT INTO `clan_user` (`id`, `id_user`, `id_clan`, `level`, `time`, `activaty`) VALUES
(1, 1, 2, '2', 1365286007, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `duels`
--

CREATE TABLE IF NOT EXISTS `duels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user1` int(11) DEFAULT NULL,
  `id_user2` int(11) DEFAULT NULL,
  `foto1` varchar(111) NOT NULL,
  `foto2` varchar(111) NOT NULL,
  `golos1` int(11) NOT NULL,
  `golos2` int(11) NOT NULL,
  `active` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `duels`
--

INSERT INTO `duels` (`id`, `id_user1`, `id_user2`, `foto1`, `foto2`, `golos1`, `golos2`, `active`, `time`) VALUES
(1, 1, NULL, '14082026401.jpg', '', 0, 0, 2, 1408202640);

-- --------------------------------------------------------

--
-- Структура таблицы `duels_golos`
--

CREATE TABLE IF NOT EXISTS `duels_golos` (
  `id_duels` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `duels_invite`
--

CREATE TABLE IF NOT EXISTS `duels_invite` (
  `id_duels` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `duels_settings`
--

CREATE TABLE IF NOT EXISTS `duels_settings` (
  `golos` int(11) NOT NULL,
  `pobeda` int(11) NOT NULL,
  `pobeda2` int(11) NOT NULL,
  `golosov` int(11) NOT NULL,
  `invite` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `duels_settings`
--

INSERT INTO `duels_settings` (`golos`, `pobeda`, `pobeda2`, `golosov`, `invite`) VALUES
(0, 0, 0, 5, 5);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
