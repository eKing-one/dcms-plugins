<?
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';

$temp_set = $set;

if (!isset($temp_set['roller_days'])) {
	$temp_set['roller_days'] = '3';
}
if (!isset($temp_set['roller_balls'])) {
	$temp_set['roller_balls'] = '3000';
}
if (!isset($temp_set['roller_money'])) {
	$temp_set['roller_money'] = '3';
}
if (!isset($temp_set['roller_rating'])) {
	$temp_set['roller_rating'] = '5';
}
if (!isset($temp_set['roller_status'])) {
	$temp_set['roller_status'] = '0';
}

include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/adm_check.php';
include_once '../sys/inc/user.php';
user_access('adm_set_sys',null,'index.php?'.SID);
adm_check();

if (isset($_POST['save']))
{
	$temp_set['roller_days'] = intval($_POST['roller_days']);
	$temp_set['roller_balls'] = intval($_POST['roller_balls']);
	$temp_set['roller_money'] = intval($_POST['roller_money']);
	$temp_set['roller_rating'] = intval($_POST['roller_rating']);
	$temp_set['roller_status'] = intval($_POST['roller_status']);
	
	if (!isset($err)) {
		if (save_settings($temp_set)) {
			if (isset($_POST['truncate'])) {
				mysql_query("TRUNCATE `roller`");
			}
			
			admin_log('Настройки','Система','Изменение параметров переклички');
			msg('Настройки успешно приняты');
		} else {
			$err = 'Нет прав для изменения файла настроек';
		}		
	}
}

$set['title'] = 'Настройки переклички';
include_once '../sys/inc/thead.php';
title();
aut();
err();

?>
<form class="nav2" method="post" action="?">

 Сколько дней идет перекличка?:<br />
 <select name="roller_days">
  <option value="3" <?= ($temp_set['roller_days'] == 3 ? ' selected="selected"' : '')?>>3 дня</option>
  <option value="5" <?= ($temp_set['roller_days'] == 5 ? ' selected="selected"' : '')?>>5 дней</option>
  <option value="7" <?= ($temp_set['roller_days'] == 7 ? ' selected="selected"' : '')?>>7 дней</option>
 </select><br />
 
 Вознаграждение (баллы):<br /><input type='text' name='roller_balls' value='<?= $temp_set['roller_balls']?>' /><br />
 Вознаграждение (монеты):<br /><input type='text' name='roller_money' value='<?= $temp_set['roller_money']?>' /><br />
 Вознаграждение (рейтинг):<br /><input type='text' name='roller_rating' value='<?= $temp_set['roller_rating']?>' /><br />

 Режим:<br />
 <select name="roller_status">
  <option value="1" <?= ($temp_set['roller_status'] == 1 ? ' selected="selected"' : '')?>>Включен</option>
  <option value="0" <?= ($temp_set['roller_status'] == 0 ? ' selected="selected"' : '')?>>Отключен</option>
 </select><br />
 
 <label><input type="checkbox" name="truncate" /> удалить старые переклички</label><br />

<input value="Сохранить" name='save' type="submit" />
</form>

<div class="mess">
 * Если поставить 0 для баллов, монет, рейтинга, то это вознаграждение не будет выводится пользователю.<br />
 ** Ставьте больше или равно дней проведения акции, относительно дней переклички.<br />
</div>

<div class="foot">
<?
if (user_access('adm_panel_show')) {
	?><img src="/style/icons/str2.gif" /> <a href='/adm_panel/'>В админку</a><br /><?
}
?>
</div>
<?
include_once '../sys/inc/tfoot.php';
?>