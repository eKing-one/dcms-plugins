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


$set['title'] = 'Музыка';
include_once '../sys/inc/thead.php';

echo '<link rel="stylesheet" href="/ajax/style/style.css" type="text/css"/>
<script type="text/javascript" src="/ajax/jquery.js"></script>
<script type="text/javascript" src="/ajax/facebox.js"></script>';

echo "<script type=\"text/javascript\">
jQuery(document).ready(function($) {
$('a[rel*=facebox]').facebox({
loading_image : '/ajax/style/icons/loading.gif',
close_image   : '/ajax/style/icons/closelabel.gif'
}) 
})
</script>";

title();
aut();

$sort = 'id'; 
$por = 'DESC';

$search_music = NULL;
if (isset($_SESSION['search_music']))$search_music = $_SESSION['search_music'];
if (isset($_POST['search_music']))$search_music = $_POST['search_music'];

if ($search_music == NULL)
unset($_SESSION['search_music']);
else
$_SESSION['search_music'] = $search_music;
$search_music = preg_replace("#( ){1,}#","",$search_music);

echo '<div class="mess">Введите название mp3 или его часть</div>';

echo '<form class="nav2" method="post" action="?go&amp;sort=' . $sort . '&amp;' . $por . '">';
$search_music = stripcslashes(htmlspecialchars($search_music));
echo '<input type="text" name="search_music" maxlength="16" value="' . $search_music . '" /> ';
echo '<input type="submit" value="Искать mp3" />';
echo '</form>';


if (!isset($_GET['go']))
{

	$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `obmennik_files` WHERE `ras` = 'mp3'"),0);
	$k_page = k_page($k_post,$set['p_str']);
	$page = page($k_page);
	$start = $set['p_str']*$page-$set['p_str'];


	echo '<table class="post">';

	if ($k_post == 0)
	{
		echo '<div class="mess">';
		echo 'Нет результатов';
		echo '</div>';
	}
	else
	{
		echo '<div class="foot">';
		switch (@$_GET['sort']) 
		{
			case 'rate':$sql_sort = '`k_loads`'; $sort = 'rate'; // баллы
			echo "Популярные | <a href='?sort=id&amp;DESC&amp;page=$page'>Новые</a>";
		 	break;
		 	default:$sql_sort = '`id`'; $sort = 'id'; // ID
			echo "<a href='?sort=rate&amp;DESC&amp;page=$page'>Популярные</a> | Новые";
		 	break;
		}
		echo '</div>';
	}


	$q = mysql_query("SELECT * FROM `obmennik_files` WHERE `ras` = 'mp3' ORDER BY $sql_sort $por LIMIT $start, $set[p_str];");

	while ($post = mysql_fetch_array($q))
	{
		$ras = $post['ras'];
		$file = H."sys/obmen/files/$post[id].dat";
		$name = $post['name'];
		$size = $post['size'];

		// Лесенка дивов
		if ($num == 0)
		{
			echo '<div class="nav1">';
			$num = 1;
		}
		elseif ($num == 1)
		{
			echo '<div class="nav2">';
			$num = 0;
		}

		// Вывод иконки
		include 'icon14.php';

		// Папка в обмене
		$dir = mysql_fetch_array(mysql_query("SELECT * FROM `obmennik_dir` WHERE `id` = '$post[id_dir]' LIMIT 1"));

		// Название папки и файла.
		echo stripcslashes(htmlspecialchars($dir['name'])) . ' - <a href="/obmen' . $dir['dir'] . $post['id'] . '.' . $post['ras'] . '?showinfo">
			 ' . stripcslashes(htmlspecialchars($post['name'])) . '</a><br />';

		// Кнопки Слушать и Скачать
		echo '<img src="onl.png" alt="Слушать онлайн" /> <a href="#' . $post['id'] . '" rel="facebox">Слушать</a> <img src="off.png" /> <a href="/obmen'.$dir['dir'].''.$post['id'].'.'.$post['ras'].'">Скачать</a> <br />';

		// Окно с плеером
		echo '<div id="' . $post['id'] . '" style="display:none;">';
		?>
		<script type="text/javascript" src="/ajax/js/audio-player.js"></script>
		<script type="text/javascript">
		 AudioPlayer.setup
		 ("/ajax/js/player.swf",{
		 	width:"100%",animation:"yes",encode:"no",initialvolume:"100",
			remaining:"yes",noinfo:"no",buffer:"2",checkpolicy:"no",
			rtl:"no",bg:"064a91",text:"000000",leftbg:"064a91",lefticon:"fee300",
			volslider:"fee300",voltrack:"ffffff",rightbg:"064a91",
			rightbghover:"064a91",righticon:"fee300",righticonhover:"fee300",
			track:"FFFFFF",loader:"fee300",border:"D2F0FF",tracker:"fee300",
			skip:"ff284b",pagebg:"064a91",transparentpagebg:"yes"});
		</script>

		<p id="audioplayer_<?=$post['id']?>">Для отображение плеера необходимо включить Javascript</p>
		<script type="text/javascript">AudioPlayer.embed("audioplayer_<?=$post['id']?>",{soundFile: "/obmen<?=$dir['dir'].$post['id'].'.'.$post['ras']?>",titles: "<?=$post['name']?>",artists: "",autostart: "yes"});</script>
		<?

		// Название
		echo ' <b>' . output_text($post['name']) . '</b><br />';

		// Cкриншот
		if (is_file(H."sys/obmen/screens/128/$post[id].gif"))
		{
			echo '<img src="/sys/obmen/screens/128/' . $post['id'] . '.gif" alt="Скрин..." /><br />';
		}


		// Описание
		if ($post['opis'] != NULL)
		{
			echo 'Описание: ';
			echo output_text($post['opis']);
			echo '<br />';
		}

		// Информация о файле
		if (class_exists('ffmpeg_movie'))
		{
			$media = new ffmpeg_movie($file);

			if (intval($media->getDuration())>3599)
			echo ''.intval($media->getDuration()/3600).":".date('s',fmod($media->getDuration()/60,60)).":".date('s',fmod($media->getDuration(),3600));
			elseif (intval($media->getDuration())>59)
			echo ''.intval($media->getDuration()/60).":".date('s',fmod($media->getDuration(),60));
			else
			echo ''.intval($media->getDuration())." сек\n";
			echo "| ".ceil(($media->getBitRate())/1024)." KBPS\n";
			if($media->getAudioChannels()==1)echo "| Mono\n";else echo "| Stereo\n";
			echo '| '.$media->getAudioSampleRate()." Гц\n";
			if(($media->getArtist())<>""){
			if (function_exists('iconv'))
			echo '| '.iconv('windows-1251', 'utf-8', $media->getArtist());
			else
			echo '| '.$media->getArtist();
			}
			if(($media->getGenre())<>"")echo '| '.$media->getGenre();
		}
		else
		{
			include_once H.'sys/inc/mp3.php';
			$id3 = new MP3_Id(); 
			$result = $id3->read($file); 
			$result = $id3->study();
			
			if(($id3->getTag('length')<>0)){echo ''.$id3->getTag('length');}
			if(($id3->getTag('bitrate'))<>0){echo'| '.$id3->getTag('bitrate').' KBPS';}
			if(($id3->getTag('mode'))<>""){echo '| '.$id3->getTag('mode');}
			if(($id3->getTag('frequency'))<>0){echo '| '.$id3->getTag('frequency').' Гц';}
			if(($id3->getTag('album'))<>"")
			{
				if (function_exists('iconv'))
				echo '| '.iconv('windows-1251', 'utf-8', $id3->getTag('album'));
				else
				echo '| '.$id3->getTag('album');
			}
			if(($id3->getTag('artists'))<>"")
			{
				if (function_exists('iconv'))
				echo '| '.iconv('windows-1251', 'utf-8', $id3->getTag('artists'));
				else
				echo '| '.$id3->getTag('artists');
			}
			if(($id3->getTag('genre'))<>""){echo ', '.$id3->getTag('genre');}
		}




		echo '<div class="mess">';

		// Папка в личных файлах
		$x = mysql_fetch_array(mysql_query("SELECT * FROM `user_files` WHERE `id` = '$post[my_dir]' LIMIT 1"));

		// Автор 
		$avtor = get_user($post['id_user']);

		// Выводим автора
		echo 'Добавил' . ($avtor['pol'] == 0 ? "a" : null) . ': '; 
		echo status($avtor['id']) . ' <a href="/info.php?id=' . $post['id_user'] . '">' . $avtor['nick'] . '</a> ';
		echo online($avtor['id']) . ' <br />в папку <img src="/style/themes/default/loads/14/dir.png" alt="" /> <a href="/user/personalfiles/' . $avtor['id'] . '/' . $x['id'] . '/">' . stripcslashes(htmlspecialchars($x['name'])) . '</a>';
		echo '</div>';

		echo '<div class="foot">';
		echo '<img src="/style/icons/d.gif" /> <a href="/obmen' . $dir['dir'] . $post['id'] . '.' . $post['ras'] . '">Скачать</a> (' . $post['k_loads'] . ')<br />';
		echo '</div>';

		echo '</div>';

		echo '</div>';
	}

	echo '</table>';

	if ($k_page>1)str("?sort=$sort&amp;$por&amp;",$k_page,$page); // Вывод страниц

}


// Поиск
if (isset ($_GET['go']) && $search_music!=NULL)
{

	$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `obmennik_files` WHERE `ras` = 'mp3' AND (`name` like '%".mysql_escape_string(translit($search_music))."%' OR `name` like '%".mysql_escape_string(retranslit($search_music))."%' OR `name` like '%".mysql_escape_string($search_music)."%' OR `opis` = '".mysql_escape_string($search_music)."')"),0);
	$k_page = k_page($k_post,$set['p_str']);
	$page = page($k_page);
	$start = $set['p_str'] * $page - $set['p_str'];

	echo '<table class="post">';
		
	if ($k_post == 0)
	{
		echo '<div class="mess">';
		echo 'Нет результатов';
		echo '</div>';
	}
	else
	{
		echo '<div class="foot">';
		switch (@$_GET['sort']) 
		{
			case 'rate':$sql_sort = '`k_loads`'; $sort = 'rate'; // баллы
			echo "Популярные | <a href='?sort=id&amp;DESC&amp;page=$page'>Новые</a>";
		 	break;
		 	default:$sql_sort = '`id`'; $sort = 'id'; // ID
			echo "<a href='?sort=rate&amp;DESC&amp;page=$page'>Популярные</a> | Новые";
		 	break;
		}
		echo '</div>';
	}
	
	$q = mysql_query("SELECT * FROM `obmennik_files` WHERE `ras` = 'mp3' AND (`name` like '%".mysql_escape_string(translit($search_music))."%' OR `name` like '%".mysql_escape_string(retranslit($search_music))."%' OR `name` like '%".mysql_escape_string($search_music)."%' OR `opis` = '".mysql_escape_string($search_music)."') ORDER BY $sql_sort $por LIMIT $start, $set[p_str];");

	while ($post = mysql_fetch_array($q))
	{
		$ras = $post['ras'];
		$file = H."sys/obmen/files/$post[id].dat";
		$name = $post['name'];
		$size = $post['size'];

		// Лесенка дивов
		if ($num == 0)
		{
			echo '<div class="nav1">';
			$num = 1;
		}
		elseif ($num == 1)
		{
			echo '<div class="nav2">';
			$num = 0;
		}

		// Вывод иконки
		include 'icon14.php';

		// Папка в обмене
		$dir = mysql_fetch_array(mysql_query("SELECT * FROM `obmennik_dir` WHERE `id` = '$post[id_dir]' LIMIT 1"));

		// Название папки и файла.
		echo stripcslashes(htmlspecialchars($dir['name'])) . ' - <a href="/obmen' . $dir['dir'] . $post['id'] . '.' . $post['ras'] . '?showinfo">
			 ' . stripcslashes(htmlspecialchars($post['name'])) . '</a><br />';

		// Кнопки Слушать и Скачать
		echo '<img src="onl.png" alt="Слушать онлайн" /> <a href="#' . $post['id'] . '" rel="facebox">Слушать</a> <img src="off.png" /> <a href="/obmen'.$dir['dir'].''.$post['id'].'.'.$post['ras'].'">Скачать</a> <br />';

		// Окно с плеером
		echo '<div id="' . $post['id'] . '" style="display:none;">';
		?>
		<script type="text/javascript" src="/ajax/js/audio-player.js"></script>
		<script type="text/javascript">
		 AudioPlayer.setup
		 ("/ajax/js/player.swf",{
		 	width:"100%",animation:"yes",encode:"no",initialvolume:"100",
			remaining:"yes",noinfo:"no",buffer:"2",checkpolicy:"no",
			rtl:"no",bg:"064a91",text:"000000",leftbg:"064a91",lefticon:"fee300",
			volslider:"fee300",voltrack:"ffffff",rightbg:"064a91",
			rightbghover:"064a91",righticon:"fee300",righticonhover:"fee300",
			track:"FFFFFF",loader:"fee300",border:"D2F0FF",tracker:"fee300",
			skip:"ff284b",pagebg:"064a91",transparentpagebg:"yes"});
		</script>

		<p id="audioplayer_<?=$post['id']?>">Для отображение плеера необходимо включить Javascript</p>
		<script type="text/javascript">AudioPlayer.embed("audioplayer_<?=$post['id']?>",{soundFile: "/obmen<?=$dir['dir'].$post['id'].'.'.$post['ras']?>",titles: "<?=$post['name']?>",artists: "",autostart: "yes"});</script>
		<?

		// Название
		echo ' <b>' . output_text($post['name']) . '</b><br />';

		// Cкриншот
		if (is_file(H."sys/obmen/screens/128/$post[id].gif"))
		{
			echo '<img src="/sys/obmen/screens/128/' . $post['id'] . '.gif" alt="Скрин..." /><br />';
		}


		// Описание
		if ($post['opis'] != NULL)
		{
			echo 'Описание: ';
			echo output_text($post['opis']);
			echo '<br />';
		}

		// Информация о файле
		if (class_exists('ffmpeg_movie'))
		{
			$media = new ffmpeg_movie($file);

			if (intval($media->getDuration())>3599)
			echo ''.intval($media->getDuration()/3600).":".date('s',fmod($media->getDuration()/60,60)).":".date('s',fmod($media->getDuration(),3600));
			elseif (intval($media->getDuration())>59)
			echo ''.intval($media->getDuration()/60).":".date('s',fmod($media->getDuration(),60));
			else
			echo ''.intval($media->getDuration())." сек\n";
			echo "| ".ceil(($media->getBitRate())/1024)." KBPS\n";
			if($media->getAudioChannels()==1)echo "| Mono\n";else echo "| Stereo\n";
			echo '| '.$media->getAudioSampleRate()." Гц\n";
			if(($media->getArtist())<>""){
			if (function_exists('iconv'))
			echo '| '.iconv('windows-1251', 'utf-8', $media->getArtist());
			else
			echo '| '.$media->getArtist();
			}
			if(($media->getGenre())<>"")echo '| '.$media->getGenre();
		}
		else
		{
			include_once H.'sys/inc/mp3.php';
			$id3 = new MP3_Id(); 
			$result = $id3->read($file); 
			$result = $id3->study();
			
			if(($id3->getTag('length')<>0)){echo ''.$id3->getTag('length');}
			if(($id3->getTag('bitrate'))<>0){echo'| '.$id3->getTag('bitrate').' KBPS';}
			if(($id3->getTag('mode'))<>""){echo '| '.$id3->getTag('mode');}
			if(($id3->getTag('frequency'))<>0){echo '| '.$id3->getTag('frequency').' Гц';}
			if(($id3->getTag('album'))<>"")
			{
				if (function_exists('iconv'))
				echo '| '.iconv('windows-1251', 'utf-8', $id3->getTag('album'));
				else
				echo '| '.$id3->getTag('album');
			}
			if(($id3->getTag('artists'))<>"")
			{
				if (function_exists('iconv'))
				echo '| '.iconv('windows-1251', 'utf-8', $id3->getTag('artists'));
				else
				echo '| '.$id3->getTag('artists');
			}
			if(($id3->getTag('genre'))<>""){echo ', '.$id3->getTag('genre');}
		}




		echo '<div class="mess">';

		// Папка в личных файлах
		$x = mysql_fetch_array(mysql_query("SELECT * FROM `user_files` WHERE `id` = '$post[my_dir]' LIMIT 1"));

		// Автор 
		$avtor = get_user($post['id_user']);

		// Выводим автора
		echo 'Добавил' . ($avtor['pol'] == 0 ? "a" : null) . ': '; 
		echo status($avtor['id']) . ' <a href="/info.php?id=' . $post['id_user'] . '">' . $avtor['nick'] . '</a> ';
		echo online($avtor['id']) . ' <br />в папку <img src="/style/themes/default/loads/14/dir.png" alt="" /> <a href="/user/personalfiles/' . $avtor['id'] . '/' . $x['id'] . '/">' . stripcslashes(htmlspecialchars($x['name'])) . '</a>';
		echo '</div>';

		echo '<div class="foot">';
		echo '<img src="/style/icons/d.gif" /> <a href="/obmen' . $dir['dir'] . $post['id'] . '.' . $post['ras'] . '">Скачать</a> (' . $post['k_loads'] . ')<br />';
		echo '</div>';

		echo '</div>';

		echo '</div>';
	}
	
	echo '</table>';

	if ($k_page > 1)str("?go&amp;sort=$sort&amp;$por&amp;",$k_page,$page); // Вывод страниц

}
include_once '../sys/inc/tfoot.php';
?>