<?
define(H, $_SERVER['DOCUMENT_ROOT'] . '/');
include_once H.'sys/inc/home.php';
include_once H.'sys/inc/start.php';
include_once H.'sys/inc/compress.php';
include_once H.'sys/inc/sess.php';
include_once H.'sys/inc/settings.php';
include_once H.'sys/inc/db_connect.php';
include_once H.'sys/inc/ipua.php';
include_once H.'sys/inc/fnc.php';
include_once H.'sys/inc/user.php';

if (isset($_POST['search']))
{
	$_SESSION['search_'] = $_POST['search'];
}

// Если ничего не искали
if (!isset($_SESSION['search_']))
$_SESSION['search_'] = NULL;
else
{
	$search_text = text($_SESSION['search_']);
}

// Конфигурационный файл
include 'inc/config.php';

// Заголовок
$set['title'] = $search_name;

include_once H.'sys/inc/thead.php';
err();
title();
aut();

?>
<div class="foot">
<img src="/style/icons/str.gif"> <?=(isset($_GET['search']) ? '<a href="?">' . $search_name . '</a>' : $search_name)?>
</div>

<?
if (isset($_GET['search']) && isset($search_text))
{
	$count['people'] = mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE 
	`id` <=> '" . $search_text . "' OR  
	`nick` like '%" . $search_text . "%' OR
	(`ank_icq` <=> '" . $search_text . "' AND `ank_icq` != '0') OR 
	(`ank_city` = '" . $search_text . "') OR 
	(`ip` <=> '" . ip2long($search_text) . "' AND `ip` != NULL) OR 
	`ank_mail` <=> '" . $search_text . "'
	"), 0);
	
	$count['obmen'] = mysql_result(mysql_query("SELECT COUNT(*) FROM `obmennik_files` WHERE 
	`name` like '%" . $search_text . "%' OR
	`name` like '%" . translit($search_text) . "%' OR
	`name` like '%" . retranslit($search_text) . "%' OR
	`opis` like '%" . $search_text . "%' OR
	`opis` like '%" . translit($search_text) . "%' OR
	`opis` like '%" . retranslit($search_text) . "%'
	"), 0);
	
	$count['notes'] = mysql_result(mysql_query("SELECT COUNT(*) FROM `notes` WHERE 
	`name` like '%" . $search_text . "%' OR
	`name` like '%" . translit($search_text) . "%' OR
	`name` like '%" . retranslit($search_text) . "%' OR
	`msg` like '%" . $search_text . "%' OR
	`msg` like '%" . translit($search_text) . "%' OR
	`msg` like '%" . retranslit($search_text) . "%'
	"), 0);
	
	$count['forum'] = mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_t` WHERE 
	`name` like '%" . $search_text . "%' OR
	`text` like '%" . $search_text . "%'
	"), 0);
	
	if ($_GET['search'] == 'people')
	include 'inc/people.php';
	
	elseif ($_GET['search'] == 'obmen')
	include 'inc/obmen.php';
	
	elseif ($_GET['search'] == 'notes')
	include 'inc/notes.php';
	
	elseif ($_GET['search'] == 'forum')
	include 'inc/forum.php';
	
	else
	{
		$i = 0;
		
		if ($count['people'] != 0)
		{
			echo '<div class="main_menu">';
			echo '<img src="' . $icon_people . '" /> <a href="?search=people">Люди</a> (' . $count['people'] . ')';
			echo '</div>';	
			++$i;			
		}
		
		if ($count['obmen'] != 0)
		{
			echo '<div class="main_menu">';
			echo '<img src="' . $icon_obmen . '" /> <a href="?search=obmen">Файлы</a> (' . $count['obmen'] . ')';
			echo '</div>';	
			++$i;			
		}
		
		if ($count['notes'] != 0)
		{
			echo '<div class="main_menu">';
			echo '<img src="' . $icon_notes . '" /> <a href="?search=notes">Дневники</a> (' . $count['notes'] . ')';
			echo '</div>';	
			++$i;			
		}
		
		if ($count['forum'] != 0)
		{
			echo '<div class="main_menu">';
			echo '<img src="' . $icon_forum . '" /> <a href="?search=forum">Форум</a> (' . $count['forum'] . ')';
			echo '</div>';	
			++$i;			
		}
		
		if ($i == 0)
		{
			?>
			<form action="?search" method="post">
			<?=$search_opis?><br />
			<input type="text" name="search" value="<?=text($_SESSION['search_'])?>" placeholder="<?=$placeholder?>"/><br />
			<input type="submit" value="<?=$submit?>"/>
			</form>
			<?
			
			echo '<div class="mess">';
			echo 'По запросу <b>' . $search_text . '</b> ничего не найдено =(';
			echo '</div>';
		}
	
	}

}
else
{
	?>
	<form action="?search" method="post">
	<?=$search_opis?><br />
	<input type="text" name="search" value="<?=text($_SESSION['search_'])?>" placeholder="<?=$placeholder?>"/><br />
	<input type="submit" value="<?=$submit?>"/>
	</form>
	<?
}
?>
<div class="foot">
<img src="/style/icons/str.gif"> <?=(isset($_GET['search']) ? '<a href="?">' . $search_name . '</a>' : $search_name)?>
</div>
<?
include_once H.'sys/inc/tfoot.php';
?>