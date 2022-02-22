-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Сен 09 2019 г., 23:57
-- Версия сервера: 5.6.38-log
-- Версия PHP: 5.6.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `ds`
--

-- --------------------------------------------------------

--
-- Структура таблицы `photo_tender`
--

CREATE TABLE `photo_tender` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `name` varchar(500) NOT NULL,
  `message` varchar(1000) NOT NULL,
  `image` varchar(500) NOT NULL,
  `level` int(11) NOT NULL,
  `max` int(11) NOT NULL,
  `sex` int(11) NOT NULL,
  `balls1` int(11) NOT NULL,
  `balls2` int(11) NOT NULL,
  `balls3` int(11) NOT NULL,
  `balls4` int(11) NOT NULL,
  `balls5` int(11) NOT NULL,
  `money1` int(11) NOT NULL,
  `money2` int(11) NOT NULL,
  `money3` int(11) NOT NULL,
  `money4` int(11) NOT NULL,
  `money5` int(11) NOT NULL,
  `rating1` int(11) NOT NULL,
  `rating2` int(11) NOT NULL,
  `rating3` int(11) NOT NULL,
  `rating4` int(11) NOT NULL,
  `rating5` int(11) NOT NULL,
  `lider1` int(11) NOT NULL,
  `lider2` int(11) NOT NULL,
  `lider3` int(11) NOT NULL,
  `lider4` int(11) NOT NULL,
  `lider5` int(11) NOT NULL,
  `plus1` int(11) NOT NULL,
  `plus2` int(11) NOT NULL,
  `plus3` int(11) NOT NULL,
  `plus4` int(11) NOT NULL,
  `plus5` int(11) NOT NULL,
  `golos` int(11) NOT NULL,
  `time_end` int(11) NOT NULL,
  `time_end_key` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `closed` int(11) NOT NULL,
  `mod` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `photo_tender_dlike`
--

CREATE TABLE `photo_tender_dlike` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `image` int(11) NOT NULL,
  `tender` int(11) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `photo_tender_golos`
--

CREATE TABLE `photo_tender_golos` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `image` int(11) NOT NULL,
  `tender` int(11) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `photo_tender_like`
--

CREATE TABLE `photo_tender_like` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `image` int(11) NOT NULL,
  `tender` int(11) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `photo_tender_sys`
--

CREATE TABLE `photo_tender_sys` (
  `id` int(11) NOT NULL,
  `data` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `messages` varchar(1000) NOT NULL,
  `news` int(11) NOT NULL,
  `title` varchar(32) NOT NULL,
  `msg` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `photo_tender_sys`
--

INSERT INTO `photo_tender_sys` (`id`, `data`, `type`, `messages`, `news`, `title`, `msg`) VALUES
(1, 0, 0, 'Все гоу в конкурс', 0, 'Новый конкурс', 'Всем привет стартует новый конкурс');

-- --------------------------------------------------------

--
-- Структура таблицы `photo_tender_user`
--

CREATE TABLE `photo_tender_user` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `tender` int(11) NOT NULL,
  `image` varchar(500) NOT NULL,
  `time` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  `like` int(11) NOT NULL,
  `dlike` int(11) NOT NULL,
  `closed` int(11) NOT NULL,
  `lider` int(11) NOT NULL,
  `mod` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `photo_tender`
--
ALTER TABLE `photo_tender`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `photo_tender_dlike`
--
ALTER TABLE `photo_tender_dlike`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `photo_tender_golos`
--
ALTER TABLE `photo_tender_golos`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `photo_tender_like`
--
ALTER TABLE `photo_tender_like`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `photo_tender_sys`
--
ALTER TABLE `photo_tender_sys`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `photo_tender_user`
--
ALTER TABLE `photo_tender_user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `photo_tender`
--
ALTER TABLE `photo_tender`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `photo_tender_dlike`
--
ALTER TABLE `photo_tender_dlike`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `photo_tender_golos`
--
ALTER TABLE `photo_tender_golos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `photo_tender_like`
--
ALTER TABLE `photo_tender_like`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `photo_tender_sys`
--
ALTER TABLE `photo_tender_sys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `photo_tender_user`
--
ALTER TABLE `photo_tender_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
