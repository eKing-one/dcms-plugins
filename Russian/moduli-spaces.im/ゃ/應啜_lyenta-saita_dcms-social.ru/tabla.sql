
CREATE TABLE IF NOT EXISTS `lenta_site` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `opis` varchar(250) CHARACTER SET utf8 NOT NULL,
  `title` varchar(250) CHARACTER SET utf8 NOT NULL,
  `link` varchar(250) CHARACTER SET utf8 NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `lenta_site_set` (
  `id` int(11) NOT NULL,
  `reg` enum('0','1') NOT NULL,
  `forum_komm` enum('0','1') NOT NULL,
  `guest` enum('0','1') NOT NULL,
  `news_komm` enum('0','1') NOT NULL,
  `note` enum('0','1') NOT NULL,
  `note_komm` enum('0','1') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- Dumping data for table `lenta_site_set`
--

INSERT INTO `lenta_site_set` (`id`, `reg`, `forum_komm`, `guest`, `news_komm`, `note`, `note_komm`) VALUES
(1, '1', '1', '1', '1', '1', '1');
