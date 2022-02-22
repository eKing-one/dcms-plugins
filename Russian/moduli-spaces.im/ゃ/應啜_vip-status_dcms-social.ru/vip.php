<?
include_once '../../sys/inc/start.php';
include_once '../../sys/inc/compress.php';
include_once '../../sys/inc/sess.php';
include_once '../../sys/inc/home.php';
include_once '../../sys/inc/settings.php';
include_once '../../sys/inc/db_connect.php';
include_once '../../sys/inc/ipua.php';
include_once '../../sys/inc/fnc.php';
include_once '../../sys/inc/adm_check.php';
include_once '../../sys/inc/user.php';
if (!isset($user)) {header("location: /index.php?");exit();}
$set['title'] = 'VIP статус';
include_once '../../sys/inc/thead.php';
title();
$money_need = 10;
echo '<div class="foot">';
echo '<img src="/style/icons/str2.gif" alt="S"/> <a href="/user/money/">Дополнительные услуги</a> | <b>VIP статус</b>';
echo '</div>';
echo '<div class="mess">';
echo 'Стоимость VIP статуса составляет <b style="color: green;">'.$money_need.'</b> монет.<br>';
echo 'Услуга предоставляется на одну неделю.<br>';
echo 'Во время ее действия, возле Вашего ника будет светиться выбранная Вами VIP иконка, а Вашая анкета будет ротироваться на главной странице сайта!<br>';
echo '</div>';
$vip = mysql_result(mysql_query("SELECT COUNT(*) FROM `vip_users` WHERE `id_user` = '$user[id]'"), 0);
if ($vip) {
	$timediff = mysql_result(mysql_query("SELECT `time` FROM `vip_users` WHERE `id_user` = '$user[id]' LIMIT 1"), 0) - time();





$oneMinute=60; 


$oneHour=60*60; 


$hourfield=floor(($timediff)/$oneHour); 


$minutefield=floor(($timediff-$hourfield*$oneHour)/$oneMinute); 


$secondfield=floor(($timediff-$hourfield*$oneHour-$minutefield*$oneMinute)); 





$sHoursLeft=$hourfield; 


$sHoursText = "часов"; 


$nHoursLeftLength = strlen($sHoursLeft); 


$h_1=substr($sHoursLeft,-1,1); 


if (substr($sHoursLeft,-2,1) != 1 && $nHoursLeftLength>1) 


{ 


    if ($h_1== 2 || $h_1== 3 || $h_1== 4) 


    { 


        $sHoursText = "часа"; 


    } 


    elseif ($h_1== 1) 


    { 


        $sHoursText = "час"; 


    } 


} 





if ($nHoursLeftLength==1) 


{ 


    if ($h_1== 2 || $h_1== 3 || $h_1== 4) 


    { 


        $sHoursText = "часа"; 


    } 


    elseif ($h_1== 1) 


    { 


        $sHoursText = "час"; 


    } 


} 





$sMinsLeft =$minutefield; 


$sMinsText = "минут"; 


$nMinsLeftLength = strlen($sMinsLeft); 


$m_1=substr($sMinsLeft,-1,1); 





if ($nMinsLeftLength>1 && substr($sMinsLeft,-2,1) != 1) 


{ 


    if ($m_1== 2 || $m_1== 3 || $m_1== 4) 


    { 


        $sMinsText = "минуты"; 


    } 


    else if ($m_1== 1) 


    { 


        $sMinsText = "минута"; 


    } 


} 





if ($nMinsLeftLength==1) 


{ 


    if ($m_1== 2 || $m_1==3 || $m_1== 4) 


    { 


        $sMinsText = "минуты"; 


    } 


    elseif ($m_1== "1") 


    { 


        $sMinsText = "минута"; 


    } 


} 








$displaystring="". 


$sHoursLeft." ". 


$sHoursText." ". 


$sMinsLeft." ". 


$sMinsText." ";








if ($timediff<0) $displaystring='дата уже наступила'; 
	echo "<div class='main'>\n";
	echo "Вы уже преобрели VIP статус ранее! Дождитесь истечения срока его действия.<br />\n";
	echo "Осталось: $displaystring<br />\n";
	echo "</div>\n";
} else {
	$array_vip_icons = array(
		1 => 'Голубая', 
		2 => 'Зеленая', 
		3 => 'Розовая', 
		4 => 'Фиолетовая', 
		5 => 'Красная', 
		6 => 'Желтая', 
	);
	if (isset($_POST['buy_vip']) && isset($_POST['icon'])) {
		$icon = intval($_POST['icon']);
		if (!@$array_vip_icons[$icon])$err[] = 'Иконка не найдена.';
		elseif ($user['money'] < $money_need)$err[] = 'Вам не хватает монет.';
		else {
			mysql_query("INSERT INTO `vip_users` SET `id_user` = '$user[id]', `time` = '".(time() + 3600 * 24 * 7)."', `icon` = '$icon'");
			mysql_query("UPDATE `user` SET `money` = '".($user['money'] - $money_need)."' WHERE `id` = '$user[id]'");
			header("Location: ?");
			$_SESSION['message'] = 'VIP статус успешно приобретен!';
			exit(); // больше нечего здесь делать
		}
	}
	err();
	echo "<form method='POST'>\n";
	echo "Выберите VIP иконку:<br />";
	foreach ($array_vip_icons as $viid => $name) {
		echo "<input type='radio' name='icon' id='{$viid}' value='{$viid}'".($viid == 1?" CHECKED":null)."> <label for='{$viid}'><img src='/style/vip_icons/{$viid}.gif' /> {$name}</label><br />\n";
	}
	echo "<input type='submit' name='buy_vip' value='Купить VIP статус!'><br />\n";
	echo "</form>\n";
}
echo '<div class="foot">';
echo '<img src="/style/icons/str2.gif" alt="S"/> <a href="/user/money/">Дополнительные услуги</a> | <b>VIP статус</b>';
echo '</div>';
echo "<form method='POST' action=''>\n";
include_once '../../sys/inc/tfoot.php';
?>