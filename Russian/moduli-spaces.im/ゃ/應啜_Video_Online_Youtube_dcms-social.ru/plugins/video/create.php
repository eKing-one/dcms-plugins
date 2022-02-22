<?
/*
=======================================
Видео для Dcms-Social
Автор: Искатель
---------------------------------------
Этот скрипт является лицензионным
---------------------------------------
Контакты
ICQ: 587863132
http://dcms-social.ru
=======================================
*/
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

if (isset($_GET['cat']) && isset($_GET['id']) && $_GET['cat'] == 'delete')
{
	if (!isset($user) || $category == 0 || $user['level'] < 3){
			
			header("location: index.php?");
			
	}
	
	$ID = intval($_GET['id']); // id категории
	
	$q = mysql_query("SELECT * FROM `video`  WHERE `id_category` = '$ID'");
	
	while ($post = mysql_fetch_assoc($q))
	{
		mysql_query("DELETE FROM `video_komm` WHERE `id_video` = '$post[id]'");
		mysql_query("DELETE FROM `video_like` WHERE `id_video` = '$post[id]'");
	}
	
	mysql_query("DELETE FROM `video_category` WHERE `id` = '$ID' LIMIT 1");
	mysql_query("DELETE FROM `video` WHERE `id_category` = '$ID'");
	
	header("location: index.php?");
	$_SESSION['message'] = 'Категория успешно удалена';
	
	
}
elseif (isset($_GET['edit']) && isset($_GET['id']) && $_GET['edit'] == 'category')
{

	$ID = intval($_GET['id']); // id категории

	$category = mysql_fetch_assoc(mysql_query("SELECT * FROM `video_category` WHERE `id` = '$ID' LIMIT 1"));

	$set['title']='Редактирование категории';

	include_once '../../sys/inc/thead.php';

	title();

	if (!isset($user) || $category == 0 && $user['level'] < 3){
			
			header("location: index.php?");
			
	}
	
	

	if (isset($_POST['name']))
	{


		$name = my_esc($_POST['name']);

		if (strlen2($name)>250){ $err[] = 'Название содержит более 250 символов'; }

		if (strlen2($name)<2){ $err[] = 'Название содержит менее 2 символов'; }




		if (!isset($err)){


			mysql_query("UPDATE `video_category` set `name` = '$name' WHERE `id` = '$ID'");
			
			$_SESSION['message'] = 'Категория успешно переименована';

			header('Location: category.php?id=' . $ID . '');

			exit;
		}

	}

	err();
	aut();

	echo '<div class="foot">';
	echo '<img src="/style/icons/str2.gif" alt="*"> <a href="category.php?id=' . $ID . '">В категорию</a><br />';
	echo '</div>';

		echo '<form method="post" name="message" action="create.php?id=' . $ID . '&edit=category">';

		echo '<input type="text"  style="margin:2px;" value="' . htmlspecialchars($category['name']) . '" name="name" maxlength="250" placeholder=""/><br />';

		echo '<input value="Готово" style="margin:2px;" type="submit" />';

		echo '</form>';

	echo '<div class="foot">';
	echo '<img src="/style/icons/str2.gif" alt="*"> <a href="category.php?id=' . $ID . '">В категорию</a><br />';
	echo '</div>';

}
elseif (isset($_GET['new']) && $_GET['new'] == 'category')
{

	$set['title']='Создание категории';

	include_once '../../sys/inc/thead.php';

	title();

	if (!isset($user) && $user['level'] < 3){
			
			header("location: index.php?");
			
	}


	if (isset($_POST['name']))
	{


		$name = my_esc($_POST['name']);

		if (strlen2($name)>250){ $err[] = 'Название содержит более 250 символов'; }

		if (strlen2($name)<2){ $err[] = 'Название содержит менее 2 символов'; }




		if (!isset($err)){


			mysql_query("INSERT INTO `video_category` (`name`) values('$name')");
			
			$_SESSION['message'] = 'Категория успешно создана';

			header('Location: index.php');

			exit;
		}

	}

	err();
	aut();

	echo '<div class="foot">';
	echo '<img src="/style/icons/str2.gif" alt="*"> <a href="index.php">Лучшее видео Youtube</a><br />';
	echo '</div>';



	echo '<form method="post" name="message" action="create.php?new=category">';

	echo '<input type="text"  style="margin:2px;" value="" name="name" maxlength="250" placeholder="Название категории..."/><br />';

	echo '<input value="Готово" style="margin:2px;" type="submit" />';

	echo '</form>';


	echo '<div class="foot">';
	echo '<img src="/style/icons/str2.gif" alt="*"> <a href="index.php">Лучшее видео Youtube</a><br />';
	echo '</div>';

}elseif (isset($_GET['new']) && $_GET['new'] == 'video'){


	$ID = intval($_GET['category']); // id категории

	$category = mysql_result(mysql_query("SELECT COUNT(id) FROM `video_category` WHERE `id` = '$ID'"),0);

	$set['title']='Добавление видео';

	include_once '../../sys/inc/thead.php';

	title();

	if (!isset($user) || $category == 0){
			
			header("location: index.php?");
			
	}


if (isset($_POST['url']))
{

	$youtube = $_POST['url'];
	
	
	// Если ввели ссылку на видео
	
	if (strlen2($youtube) != 11)
	{
		$youtube = preg_replace('#(.*)(v=)#isU', '', $youtube); 
		$youtube = preg_replace('/\&.*/', '', $youtube); 
		
	}
	
	// Если ввели HTML код видео
	
	if (strlen2($youtube) != 11)
	{
	
		$youtube = preg_replace('#(<)(.*)(embed/)#isU', '', $youtube); 
		$youtube = preg_replace('/\?.*/', '', $youtube);
	
	}
	
	// Если не смогли выбрать ключ то выдаем ошибку
	
	if (strlen2($youtube) != 11)
	$err[] = 'Ссылка или HTML код имеют неправильный формат';
	

	$url = my_esc($youtube);

	$opis = my_esc($_POST['msg']);
	
	if (strlen2($opis)>1024){ $err[] = 'Описание содержит более 1024 символов'; }
	
	if (mysql_result(mysql_query("SELECT COUNT(url) FROM `video` WHERE `url` = '$url' AND `id_user` = '$user[id]'"),0) != 0)
	$err[] = 'Это видео вы уже добавили';

	/*
	* Если не писали название видео
	* то берем его с Youtube
	*/

	if (!isset($err))
	{
		$file = file_get_contents('http://m.youtube.com/watch?v=' . $youtube);

		$name = preg_replace('#(<)(.*)(<title>)#isU', '', $file); 

		$name = preg_replace('#(</title>)(.*)(</html>)#isU', '', $name); 

		$name = preg_replace('#( - YouTube)#isU', '', $name); 

		$name = my_esc($name);
		
		if (!$name)$err[] = 'Видео не найдено';
	}


	if (!isset($err)){

		mysql_query("INSERT INTO `video` (`name`,`url`,`opis`,`time`,`id_category`,`id_user`) values('$name', '$url', '$opis', '$time', '$ID', '$user[id]')");
		
		$video = mysql_insert_id();
		
		$_SESSION['message'] = 'Видео успешно добавлено';

		header('Location: video.php?id=' . $video);

		exit;
	}

}

err();
aut();

echo '<div class="foot">';
echo '<img src="/style/icons/str2.gif" alt="*"> <a href="category.php?id=' . $ID . '">В категорию</a><br />';
echo '</div>';


echo '<form method="post" name="message" action="create.php?category=' . $ID . '&amp;new=video">';

echo 'URL:<br />';
echo '<input type="text"  style="margin:2px;" value="" name="url" maxlength="350" placeholder="Ссылка на видео..."/><br />';

echo 'Описание:<br />';
echo '<textarea name="msg" style="margin:2px;" placeholder="Описание..."></textarea><br />';

echo '<input value="Добавить" style="margin:2px;" type="submit" />';

echo '</form>';


echo '<div class="foot">';
echo '<img src="/style/icons/str2.gif" alt="*"> <a href="category.php?id=' . $ID . '">В категорию</a><br />';
echo '</div>';

}


include_once '../../sys/inc/tfoot.php';
?>