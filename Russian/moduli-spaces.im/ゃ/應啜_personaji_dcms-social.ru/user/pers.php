<?
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';

if (!isset($user)){header("Location: /index.php?".SID);exit;}

// Если не листаем, то выводим 1 объекты
if (!isset($_GET['head']) || mysql_result(mysql_query("SELECT COUNT(*) FROM `gala_person` WHERE `id` = '" . intval($_GET['head']) . "' AND `type` = 'head'"),0) == 0)
$_GET['head'] = 1;

if (!isset($_GET['foot']) || mysql_result(mysql_query("SELECT COUNT(*) FROM `gala_person` WHERE `id` = '" . intval($_GET['foot']) . "' AND `type` = 'foot'"),0) == 0)
$_GET['foot'] = 1;

if ($user['pers_time'] > time() - 60 * 60 * 24 * 7)
{
	$arr = explode(':', $user['pers']);
	$_GET['head'] = $arr[0];
	$_GET['foot'] = $arr[1];
}

// Голова
$HEAD = mysql_fetch_assoc(mysql_query("SELECT * FROM `gala_person` WHERE `id` = '" . intval($_GET['head']) . "' AND `type` = 'head' LIMIT 1"));

// Тело
$FOOT = mysql_fetch_assoc(mysql_query("SELECT * FROM `gala_person` WHERE `id` = '" . intval($_GET['foot']) . "' AND `type` = 'foot' LIMIT 1"));

/*----------------------листинг-------------------*/
$HEAD_listr = mysql_fetch_assoc(mysql_query("SELECT * FROM `gala_person` WHERE `type` = 'head' AND `id` < '$HEAD[id]' ORDER BY `id` DESC LIMIT 1"));
$HEAD_list = mysql_fetch_assoc(mysql_query("SELECT * FROM `gala_person` WHERE `type` = 'head' AND `id` > '$HEAD[id]' ORDER BY `id`  ASC LIMIT 1"));

$FOOT_listr = mysql_fetch_assoc(mysql_query("SELECT * FROM `gala_person` WHERE `type` = 'foot' AND `id` < '$FOOT[id]' ORDER BY `id` DESC LIMIT 1"));
$FOOT_list = mysql_fetch_assoc(mysql_query("SELECT * FROM `gala_person` WHERE `type` = 'foot' AND `id` > '$FOOT[id]' ORDER BY `id`  ASC LIMIT 1"));
/*----------------------alex-borisi---------------*/


// Колличество монет за 7 дней
$summa = 5;

// Покупаем персонаж
if (isset($_GET['buy']))
{
	if ($summa > $user['money'])
	$err[] = 'У вас не достаточно монет, пополните счет';
	
	if (!$err)
	{
		mysql_query("UPDATE `user` SET `pers` = '$HEAD[id]:$FOOT[id]', `pers_time` = '".time()."' WHERE `id` = '$user[id]'");
		mysql_query("UPDATE `user` SET `money` = '" . ($user['money'] - $summa) . "' WHERE `id` = '$user[id]'");
		$_SESSION['message'] = 'Вы успешно купили персонажа';
		header('Location: /id' . $user['id']);
		exit;
	}
}

// Удаляем
if (isset($_GET['delete']))
{
	mysql_query("UPDATE `user` SET `pers` = '1:1', `pers_time` = '' WHERE `id` = '$user[id]'");
	$_SESSION['message'] = 'Вы успешно удалили свого персонажа';
	header('Location: ?');
	exit;
}


$set['title'] = 'Купить персонаж'; // заголовок страницы

include_once '../sys/inc/thead.php';
title();
aut();
err();

echo '<div class="foot">';
echo '<img src="/style/icons/str2.gif" alt="*" /> <a href="/id' . $user['id'] . '">' . user::nick($user['id']) . '</a> | <b>Персонаж</b>';
echo '</div>';

?>
<style>
.link_ a
{
	background-color: #ec2058;
	padding: 2px;
	display: block;
	color:#ffffff;
	border-radius: 2px 2 2 2px;
	-moz-border-radius: 2px 2 2 2px;
	margin: auto;
	text-align: center;
}

.link_ 
{
	padding-top: 6px;
}

.input_auth
{
	background-color: #ec2058;
	padding: 8px;
	display: inline-block;
	color:#ffffff;
}
</style>

<?
// Шаг 2 - завершение покупки
if ((isset($_GET['reg']) && $_GET['reg'] == 'pers') || ($user['pers_time'] > time() - 60 * 60 * 24 * 7))
{
	if ($user['pers_time'] > time() - 60 * 60 * 24 * 7)
	{
		?>
		<div style="padding: 6px; margin: 4px; opacity: 0.6; border-radius: 5px 5 5 5px; -moz-border-radius: 5px 5 5 5px; background: #f5e496;">
		Ваш персонаж еще активен, вы можете Удалить/Изменить своего персонажа.<br />
		</div>	
		<?	
	}
	else
	{
		?>
		<div style="padding: 6px; margin: 4px; opacity: 0.6; border-radius: 5px 5 5 5px; -moz-border-radius: 5px 5 5 5px; background: #f5e496;">
		Стоимость персонажа: <b><?=$summa?> монет</b><br />
		Ваш персонаж будет отображаться ровно 7 дней, с момента покупки.<br />
		</div>	
		<?
	}
	?>
	<div style="text-align: center; min-width: 128px; margin: 4px;">
	<img src="/style/person/head/h (<?=$HEAD['id']?>).png"  style="z-index: 2; position: relative;" />
	<br />
	<img src="/style/person/foot/f (<?=$FOOT['id']?>).png"  style="<?=$HEAD['style']?> position: relative;" />
	<br />
	</div>
	<?
	if ($user['pers_time'] > time() - 60 * 60 * 24 * 7)
	{
		?>
		<center>
		<a href="?reg=pers&amp;head=<?=$HEAD['id']?>&amp;foot=<?=$FOOT['id']?>&amp;delete">
		<div class="input_auth" style="border-radius: 5px 5 5 5px; -moz-border-radius: 5px 5 5 5px;">
		Удалить/Изменить
		</div>
		</a>
		</center>
		<?	
	}
	else
	{
		?>
		<center>
		<a href="?reg=pers&amp;head=<?=$HEAD['id']?>&amp;foot=<?=$FOOT['id']?>&amp;buy">
		<div class="input_auth" style="border-radius: 5px 5 5 5px; -moz-border-radius: 5px 5 5 5px;">
		Купить
		</div>
		</a>
		</center>
		<?		
	}

}
else
{
	?>
	<div class="ct_body">
	<table cellpadding="0" cellspacing="0" style="margin: auto; max-width: 220px; padding-top:6px; padding-bottom:6px;">
	<tr>
	<td style="display: block; vertical-align: top; height: 50px; " class="link_">
	<?=($HEAD_listr['id'] ? '<a href="?reg&amp;head=' . $HEAD_listr['id'] . '&amp;foot=' . $FOOT['id'] . '">&laquo;</a>' : 
	'<a href="?reg&amp;head=25&amp;foot=' . $FOOT['id'] . '">&laquo;</a>')?>
	</td>

	<td style="display: block; vertical-align: top;  height: 60px;" class="link_">
	<?=($FOOT_listr['id'] ? '<a href="?reg&amp;foot=' . $FOOT_listr['id'] . '&amp;head=' . $HEAD['id'] . '">&laquo;</a>' : 
	'<a href="?reg&amp;foot=22&amp;head=' . $HEAD['id'] . '">&laquo;</a>')?>
	</td>

	<td style="vertical-align: top;">
	<div style="text-align: center; min-width: 128px; min-height: 150px;">

	<img src="/style/person/head/h (<?=$HEAD['id']?>).png"  style="z-index: 2; position: relative;" />
	<br />
	<?echo '<img src="/style/person/foot/f (' . $FOOT['id'] . ').png"  style="' . $HEAD['style'] . ' position: relative;" />';?>
	<br />

	<a href="?reg=pers&amp;head=<?=$HEAD['id']?>&amp;foot=<?=$FOOT['id']?>">
	<div class="input_auth" style="border-radius: 5px 5 5 5px; -moz-border-radius: 5px 5 5 5px;">
	Продолжить
	</div>
	</a>

	</div>
	</td>

	<td style="display: block; vertical-align: top;  height: 50px;" class="link_">
	<?=($HEAD_list['id'] ? '<a href="?reg&amp;head=' . $HEAD_list['id'] . '&amp;foot=' . $FOOT['id'] . '">&raquo;</a>' :
	 '<a href="?reg&amp;head=1&amp;foot=' . $FOOT['id'] . '">&raquo;</a>')?>
	</td>

	<td style="display: block; vertical-align: top;  height: 60px;" class="link_">
	<?=($FOOT_list['id'] ? '<a href="?reg&amp;foot=' . $FOOT_list['id'] . '&amp;head=' . $HEAD['id'] . '">&raquo;</a>' :
	 '<a href="?reg&amp;foot=1&amp;head=' . $HEAD['id'] . '">&raquo;</a>')?>
	</td>
	</tr>
		
	<div style="padding: 6px; margin: 4px; opacity: 0.6; border-radius: 5px 5 5 5px; -moz-border-radius: 5px 5 5 5px; background: #f5e496;">
	Для начала, создаейте/измените своего персонажа, затем нажмите "Продолжить" для завершения покупки.<br />
	</div>	
		

	</table>
	</div>
	<?		
}


echo '<div class="foot">';
echo '<img src="/style/icons/str2.gif" alt="*" /> <a href="/id' . $user['id'] . '">' . user::nick($user['id']) . '</a> | <b>Персонаж</b>';
echo '</div>';

include_once '../sys/inc/tfoot.php';
?>