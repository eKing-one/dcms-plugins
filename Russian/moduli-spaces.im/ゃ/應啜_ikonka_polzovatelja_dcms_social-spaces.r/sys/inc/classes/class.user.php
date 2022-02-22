<?
/**
* / Основные пользовательские функции
* / nick() - выводит ник и значок онлайна
* / avatar - выводит аватар и иконку пользователя
* / у всех функций есть параметры что выводить а что нет
*/

class user
{
	
	/**
	* / Ссылка и Ник юзера
	*/

	public static function nick($user = 0, $url = 1, $on = 0, $medal = 0)
	{
		/*
		* $url == 0		Выводит только ник
		* $url == 1		Выводит ник с ссылкой на страницу юзера
		* $on  == 1		Выводит рядом с ником значок онлайн
		* $medal == 1	Выводит медальку рядом со значком онлайн
		*/
		
		$ank = mysql_fetch_assoc(mysql_query('SELECT `nick`, `date_last`, `rating`, `browser` FROM `user` WHERE `id` = "' . $user . '" LIMIT 1 '));
		
		$nick = null;
		$online = null;
		$icon_medal = null;
		
		// Вывод ника 
		if ($user == 0)$ank = array('id' => '0', 'nick' => 'Cистема', 'pol' => '1', 'rating' => '0', 'browser' => 'wap', 'date_last' => time());
		elseif (!$ank)$ank = array('id' => '0', 'nick' => '[Удален]', 'pol' => '1', 'rating' => '0', 'browser' => 'wap', 'date_last' => time());
		
		if ($url == true)
			$nick = ' <a href="/id' . $user . '">' . text($ank['nick']) . '</a> ';
		else
			$nick = text($ank['nick']);
		
		// Вывод значка онлайн
		if ($user != 0 && $ank['date_last'] > time()-600 && $on == true)
		{
			if ($ank['browser'] == 'wap')
				$online = ' <img src="/style/icons/online.gif" alt="WAP" /> ';
			else
				$online = ' <img src="/style/icons/online_web.gif" alt="WEB" /> ';
		}
		
		// Вывод медали
		$R = $ank['rating'];
		
		if ($medal == 1 && $R >= 6)
		{
			if ($R >= 6 && $R <= 11)		{$img = 1;}
			elseif ($R >= 12 && $R <= 19)	{$img = 2;}
			elseif ($R >= 20 && $R <= 27)	{$img = 3;}
			elseif ($R >= 28 && $R <= 37)	{$img = 4;}
			elseif ($R >= 38 && $R <= 47)	{$img = 5;}
			elseif ($R >= 48 && $R <= 59)	{$img = 6;}
			elseif ($R >= 60)				{$img = 7;}
			$icon_medal = ' <img src="/style/medal/' . $img . '.png" alt="*" /> ';
		}
		
		return $nick . $icon_medal . $online;
	}

	/**
	* / Аватар, иконка группы пользователя
	*/

	public static function avatar($user = 0, $type = 0)
	{
		/*
		* $type == 0 - Выводит аватар и иконку вместе
		* $type == 1 - Выводит только аватар
		* $type == 2 - Выводит только иконку
		*/
		global $time, $set;
		
		$AVATAR = null;
		$icon = null;

		$ank = mysql_fetch_assoc(mysql_query('SELECT `pol`, `id`, `group_access` FROM `user` WHERE `id` = "' . $user . '" LIMIT 1 '));
		
		if ($user == 0)$ank = array('id' => '0', 'pol' => '1');
		elseif (!$ank)$ank = array('id' => '0', 'pol' => '1');
		
		// Аватар
		if ($type == 0 || $type == 1)
		{
			$avatar = mysql_fetch_array(mysql_query("SELECT id,ras FROM `gallery_foto` WHERE `id_user` = '$user' AND `avatar` = '1' LIMIT 1"));
			
			if (is_file(H.'sys/gallery/50/' . $avatar['id'] . '.' . $avatar['ras']))
				$AVATAR = ' <img class="avatar" src="/foto/foto50/' . $avatar['id'] . '.' . $avatar['ras'] . '" alt="Avatar" /> ';
			else
				$AVATAR = '<img class="avatar" src="/style/user/avatar.gif" width="50" alt="No Avatar" />';			
		}
		
		
		// Иконка пользователя
		if ($type == 0 || $type == 2)
		{
			if (mysql_result(mysql_query("SELECT COUNT(*) FROM `ban` WHERE `id_user` = '$user' AND (`time` > '$time' OR `navsegda` = '1')"), 0) != 0)
			{
				$icon = ' <img src="/style/user/ban.png" alt="*" class="icon" id="icon_group" /> ';
			}
			else 
			{
        
		if (mysql_result(mysql_query("SELECT COUNT(id_user) FROM `us_icons` WHERE `id_user` = '".$ank['id']."' and `time` > '".$time."' "), 0) != 0)
	    {
		$icon = mysql_fetch_assoc(mysql_query("SELECT id_icon FROM `us_icons` WHERE `id_user` = '".$ank['id']."' LIMIT 1"));

			$icon = '<img src="/user/icons/png/'.$icon['id_icon'].'.png" alt="" class="icon"/> ';
		}
		
				elseif ($ank['group_access'] > 7 && ($ank['group_access'] < 10 || $ank['group_access'] > 14))
				{
					if ($ank['pol'] == 1) 
					$icon = '<img src="/style/user/1.png" alt="*" class="icon" id="icon_group" /> ';
					else
					$icon = '<img src="/style/user/2.png" alt="" class="icon"/> ';
				}
				elseif (($ank['group_access'] > 1 && $ank['group_access'] <= 7) || ($ank['group_access'] > 10 && $ank['group_access'] <= 14))
				{
					if ($ank['pol'] == 1)
						$icon = '<img src="/style/user/3.png" alt="*" class="icon" id="icon_group" /> ';
					else
						$icon = '<img src="/style/user/4.png" alt="*" class="icon" id="icon_group" /> ';
				}
				elseif (isset ($ank['status']) == 0)
				{
					if ($ank['pol'] == 1) 
						$icon = '<img src="/style/user/5.png" alt="" class="icon" id="icon_group" /> ';
					else
						$icon = '<img src="/style/user/6.png" alt="" class="icon" id="icon_group" /> ';
				}
			}
		}
		
		return $AVATAR . $icon;
		
	}
}
?>