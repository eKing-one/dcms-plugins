<?
function group($user = NULL)
{
	global $set,$time;

		if (mysql_result(mysql_query("SELECT COUNT(*) FROM `ban` WHERE `id_user` = '$user' AND (`time` > '$time' OR `navsegda` = '1')"), 0)!=0)
		{

			$ban = ' <img src="/style/user/ban.png" alt="" class="icon"/> ';
			return $ban;
		}
		else 
		{

		$ank = mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id` = $user LIMIT 1"));
		
		if (mysql_result(mysql_query("SELECT COUNT(*) FROM `user_icons` WHERE `id_user` = '$user' AND `time` > '".(time()-604800)."'"), 0) != 0)
		{
			$icon = mysql_fetch_assoc(mysql_query("SELECT * FROM `user_icons` WHERE `id_user` = '$user' AND `time` > '".(time()-604800)."' LIMIT 1"));
			return '<img src="/style/personal_icons/i (' . $icon['id'] . ').png" alt="" class="icon"/> ';
		}
		elseif ($ank['group_access'] > 7 && ($ank['group_access'] < 10 || $ank['group_access'] > 14))
		{

			if ($ank['pol']==1) $adm = '<img src="/style/user/1.png" alt="" class="icon"/> ';
			else
			$adm = '<img src="/style/user/2.png" alt="" class="icon"/> ';
			return $adm;
		}

		else

		if (($ank['group_access'] > 1 && $ank['group_access'] <= 7) || ($ank['group_access'] > 10 && $ank['group_access'] <= 14))
		{
			if ($ank['pol']==1)
			$mod='<img src="/style/user/3.png" alt="" class="icon"/> ';
			else
			$mod='<img src="/style/user/4.png" alt="" class="icon"/> ';
			return $mod;
		}

		else
		{
			if ($ank['pol'] == 1) $user='<img src="/style/user/5.png" alt="" class="icon"/> ';
			else
			$user = '<img src="/style/user/6.png" alt="" class="icon"/> ';
			return $user;
		}
	}
}
?>