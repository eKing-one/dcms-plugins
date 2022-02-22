	/*
	
	Добавляем комментарии к аватару
	запрос ниже
	выкладывать аватарпхп и инфопхп не стал т.к у всех они уже проредаченные
	если аватар незагружен комментить нельзя
	когда юзер изменяет аватар старые комменты очищаются
	
	
	
	
	CREATE TABLE IF NOT EXISTS `avkom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL,
  `msg` varchar(1024) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_av` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `time` (`time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;





1. В аватар.пхп добавляем после каждой строки можно перед каждой строки)
msg("Аватар успешно установлен");

вот это добавляем
mysql_query("DELETE FROM `avkom` WHERE `id_av`= '$user[id]' ");

там три раза надо добавить

2. в инфо добавляем

if (is_file(H."/sys/avatar/$ank[id].gif") || is_file(H."/sys/avatar/$ank[id].jpg") || is_file(H."/sys/avatar/$ank[id].png")){
echo '<a href="/avkom.php?id='.$ank['id'].'">Комммменннтариии к аватару</a>';
}

3. выполняем скул

4. почему не работает? 
возможно потому что ты не прочитал этот файл, 
или руки не там где надо, или у меня руки не там где надо, 
или у тебя не 6.6.4, 
или у тебя вообще не дцмс,




автор: Дикий

*/
