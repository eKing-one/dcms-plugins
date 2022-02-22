<?php
require_once('functions.php');
$url = 'http://o5wap.ru/LENOVO05:6387628';
$tttt = koecurl($url , $post, array
( 'referer' => 'http://
o5wap.ru', 'ssl' => 0, 'headers' => 1));
$kmess = 10;
$start = (isset($_GET['page'])?intval($_GET['page'])*$kmess-$kmess:0);
$act = htmlspecialchars($_GET['act']);
switch($act){
	default:
		$title = 'Поиск Музыки';
		require('head.php');
		//echo '<div class="foot">Поиск Музыки</div>';
		echo '<div class="nav1">
		<form action="" method="get">
		Запрос:<br />
		<input type="hidden" name="act" value="search" />
		<input type="text" name="Text" value="" /><br />
		<input type="submit" value="Поиск" />
		</form>
		</div>';
		require_once('foot.php');
	break;
case 'search':
	if(!empty($_GET['Text'])){
		$text = htmlspecialchars($_GET['Text']);
		$purl = isset($_GET['page'])?'&Page='.intval($_GET['page']):'';
		$url = 'http://o5wap.ru/mp3/allsearch/?Text='.$text.$purl;
		$texts = koecurl($url, $post, array('referer' => 'http://o5wap.ru', 'ssl' => 0, 'headers' => 1));
		$title = 'Поиск Музыки &raquo; Поиск:'.$text;
		require('head.php');
		/*echo '<div class="foot">
		<a href="?">Поиск Музыки</a> &raquo; Поиск: '.$text.'
		</div>';*/
		preg_match_all('#href="/mp3/allsearch/file(.*?)/">(.*?)</a> \(([0-9:]+)\)<hr/>#si', $texts, $song);
		$to = count($song[1]);
		if($to >= 1) {
			for($i=0; $i<$to; $i++){
				echo '<a class="main_menu" href="?act=file&amp;id='.$song[1][$i].'&amp;query='.$text.'&amp;name='.$song[2][$i].'"><img class="middle" src="ico.png" /> '.$song[2][$i].' ('.$song[3][$i].')</a>';
			}
			$total = (check_page($texts) > 10 ? 10*$kmess : check_page($str)*$kmess);
			if($total > 0 && $to >= 10){
				echo '<div class="c2">';
				echo pagenav('?act='.$act.'&amp;Text='.$text.'&amp;page=', $start, $total, $kmess);
				echo '</div>';
			}
		} else {
			echo '<div class="err">Извините! Но по вашему запросу ничего не найдено!<br />
			<a href="?">&larr; Назад</a></div>';
		}
		require_once('foot.php');
	} else {
		header('Location: /');
	}
break;
case 'file':
if(!empty($_GET['id'])) {
	$id = htmlspecialchars($_GET['id']);
	$new1 = explode('/', $id);
	$new = $_SERVER['HTTP_HOST'].'_'.end($new1);
	$new = str_replace(end($new1), $new, $id);
	$nm = (isset($_GET['new'])?'?new='.$_GET['new']:'');
	$url = 'http://o5wap.ru/mp3/allsearch/file'.$new.$nm;
	$texts = koecurl($url, $post, array('referer' => 'http://o5wap.ru', 'ssl' => 0, 'headers' => 1));
	$title = 'Поиск Музыки &raquo; '.urldecode($_GET['name']);
	require('head.php');
	/*echo '<div class="foot">
	<a href="?">Поиск Музыки</a> &raquo; <a href="?act=search&amp;Text='.$_GET['query'].'">'.$_GET['query'].'</a> &raquo; '.urldecode($_GET['name']).'
	</div>';*/
	preg_match('#Файл подготавливается<br/><big>\[(.*?)\]</big>#si', $texts, $update);
	if(!empty($update[1])){
		echo '<div class="nav1"><b>Подготовка файла</b><br />
		<font color="green">Файл подготовливается к скачиванию</font><br />
		пожалуйста <a href="?act=file&amp;id='.$id.'&amp;query='.$_GET['query'].'&amp;name='.$_GET['name'].'&amp;'.rand(0, 99).'">обновите</a> страницу через <b>5-10 секунд!</b><br />
		файл подготовлен на '.$update[1].'
		</div>';
	} else {
		echo '<div class="nav1"><b>Информация</b><br />
		Имя: <font color="blue">'.urldecode($_GET['name']).'</font>
		<br />';
		preg_match('#<big>(.*?)</big>#si', $texts, $pro);
		preg_match('#<b>Создаётся (.*?)</b>#si', $texts, $kbs);
		echo '<b>Файлы:</b>';
		echo '</div>';
	if(isset($_GET['new']) && !empty($pro[1]) && !empty($kbs[1])){
			echo '<div class="nav1"><b>Конвертирования</b><br />
			Процесс может занять некоторое время<br /><b>Создаётся '.$kbs[1].'</b><br /> 
			<big>'.$pro[1].'</big> <a href="?act=file&amp;id='.$id.'&amp;query='.$_GET['query'].'&amp;name='.$_GET['name'].'&amp;new='.$_GET['new'].'">Обновить</a>
			</div>';
		}
	preg_match_all('#<a href="/content/mp3/search/file/(.*?).mp3">(.*?)</a></b> \(<b>(.*?)</b>\)<br/>#si', $texts, $mp3);
	for($i=0; $i<count($mp3[1]); $i++){
		echo '<a class="main_menu" href="?act=download&amp;url='.base64_encode($mp3[1][$i]).'">'.$mp3[2][$i].' ('.$mp3[3][$i].')</a>';
	}
		preg_match_all('#href="\?new=([0-9]+)">(.*?)</a>#si', $texts, $n);
		if($n[1][0]) {
			echo '<div class="nav1"><b>Создать:</b></div>';
		}
		for($i=0; $i<count($n[1]); $i++){
			echo '<a class="main_menu" href="?act=file&amp;id='.$id.'&amp;query='.$_GET['query'].'&amp;name='.$_GET['name'].'&amp;new='.$n[1][$i].'">'.$n[2][$i].'</a>';
		}
	}
	require_once('foot.php');
} else {
	header('Location: /');
}
break;
case 'download':
	$url = base64_decode($_GET['url']);
	$u = 'http://o5wap.ru/content/mp3/search/file/'.$url.'.mp3';
	header('Location: '.$u);
	break;
}
?>