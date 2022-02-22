CREATE TABLE `meta_tags` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` text NOT NULL,
  `meta_keywords` text DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `title` (`title`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;