<?php
if($gruppy['admid']==$user['id'] && isset($_GET['act']) && $_GET['act']=='set'){	echo '<form action="?s='.htmlspecialchars($gruppy['id']).'&amp;act=set&amp;ok&amp;d='.htmlspecialchars($dir_id['dir']).'&amp;page='.$page.'" method="post">';
echo 'Название папки:<br/><input type="text" name="name" value="'.esc(stripcslashes(htmlspecialchars($dir_id['name']))).'"/><br/>';

if($dir_id['upload']==1){	$check=' checked="checked"';
}else{		$check=NULL;
}

echo '<label><input type="checkbox"'.$check.' name="upload" value="1"/> Выгрузка</label><br/>';
echo 'Расширения через ";":<br/><input type="text" name="ras" value="'.esc(stripcslashes(htmlspecialchars($dir_id['ras']))).'"/><br/>';
echo 'Максимальный размер файлов:<br/>';

if($dir_id['maxfilesize']<1024){	$size=$dir_id['maxfilesize'];
}else if($dir_id['maxfilesize']>=1024 && $dir_id['maxfilesize']<1048576){		$size=intval($dir_id['maxfilesize']/1024);
}else if($dir_id['maxfilesize']>=1048576){			$size=intval($dir_id['maxfilesize']/1048576);
}

echo '<input type="text" name="size" size="4" value="'.intval($size).'"/>';
echo '<select name="mn">';

if($dir_id['maxfilesize']<1024){	$sel=' selected="selected"';
}else{		$sel=NULL;
}

echo '<option value="1"'.$sel.'>b</option>';

if($dir_id['maxfilesize']>=1024 && $dir_id['maxfilesize']<1048576){	$sel=' selected="selected"';
}else{		$sel=NULL;
}

echo '<option value="1024"'.$sel.'>kb</option>';

if($dir_id['maxfilesize']>=1048576){	$sel=' selected="selected"';
}else{		$sel=NULL;
}

echo '<option value="1048576"'.$sel.'>mb</option>';
echo '</select><br/>';
echo '*настройки сервера не позволяют выгружать файлы объемом не более: '.size_file($upload_max_filesize).'<br/>';
echo '<input class="submit" type="submit" value="Принять изменения"/><br/>';
echo '&#187;&nbsp;<a href="?s='.$gruppy['id'].'">Отмена</a></form>';
}


if($gruppy['admid']==$user['id'] && isset($_GET['act']) && $_GET['act']=='mkdir'){	echo '<form action="?s='.$gruppy['id'].'&amp;act=mkdir&amp;ok&amp;d='.@$dir_id['dir'].'&amp;page='.$page.'" method="post">';
echo 'Название папки:<br/><input type="text" name="name" value=""/><br/>';
echo '<label><input type="checkbox" name="upload" value="1"/> Выгрузка файлов в эту папку</label><br/>';
echo 'Расширения через ";":<br/>';
echo '<input type="text" name="ras" value=""/><br/>';
echo 'Максимальный размер файлов:<br/><input type="text" name="size" size="4" value="500"/>';
echo '<select name="mn"><option value="1">b</option><option value="1024" selected="selected">kb</option><option value="1048576">mb</option>';
echo '</select><br/>';
echo '*настройки сервера не позволяют выгружать файлы объемом более: '.size_file($upload_max_filesize).'<br/>';
echo '<input class="submit" type="submit" value="Создать папку"/><br/>';
echo ' - <a href="?s='.$gruppy['id'].'">Отмена</a></form>';
}

if($gruppy['admid']==$user['id'] && isset($_GET['act']) && $_GET['act']=='rename' && $l!='/'){	echo '<form class="foot" action="?s='.$gruppy['id'].'&amp;act=rename&amp;ok&amp;d='.$dir_id['dir'].'&amp;page='.$page.'" method="post">';
echo 'Название папки:<br/><input type="text" name="name" value="'.$dir_id['name'].'"/><br/>';
echo '<input class="submit" type="submit" value="Переименовать"/><br/>';
echo '&#187;&nbsp;<a href="?s='.$gruppy['id'].'">Отмена</a></form>';
}

if($gruppy['admid']==$user['id'] && isset($_GET['act']) && $_GET['act']=='mesto' && $l!='/'){	echo '<form action="?s='.$gruppy['id'].'&amp;act=mesto&amp;ok&amp;d='.$dir_id['dir'].'&amp;page='.$page.'" method="post">';
echo 'Новый путь:<br/><select class="submit" name="dir_osn"><option value="/">[в корень]</option>';

$q=mysql_query("SELECT DISTINCT `dir` FROM `gruppy_obmen_dir` WHERE `dir` not like '$l%' AND `id_gruppy` = '$gruppy[id]' ORDER BY 'dir' ASC");
while($post = mysql_fetch_array($q)){	echo '<option value="'.$post['dir'].'">'.htmlspecialchars($post['dir']).'</option>';
}

echo '</select><br/>';
echo '<input class="submit" type="submit" value="Переместить"/><br/>';
echo '&#187;&nbsp;<a href="?s='.$gruppy['id'].'&amp;d='.$dir_id['dir'].'&amp;page='.$page.'">Отмена</a></form>';
}

if($gruppy['admid']==$user['id'] && isset($_GET['act']) && $_GET['act']=='delete' && $l!='/'){	echo '<div class="err">Удалить текущую папку ('.esc(stripcslashes(htmlspecialchars($dir_id['name']))).')?<br/>';
echo '<a href="?s='.$gruppy['id'].'&amp;act=delete&amp;ok&amp;d='.@$dir_id['dir'].'&amp;page='.$page.'">Да</a> | ';
echo '<a href="?s='.$gruppy['id'].'&amp;d='.$dir_id['dir'].'&amp;page='.$page.'">Нет</a></div>';
}


if($gruppy['admid']==$user['id']){
echo "<div class='foot'>\n";
echo '&#187;&nbsp; <a href="?s='.$gruppy['id'].'&amp;act=mkdir&amp;d='.@$dir_id['dir'].'&amp;page='.$page.'">Создать папку</a><br/>';

if($l!='/'){	echo '&#187;&nbsp; <a href="?s='.$gruppy['id'].'&amp;act=rename&amp;d='.$dir_id['dir'].'&amp;page='.$page.'">Переименовать папку</a><br/>';
echo '&#187;&nbsp; <a href="?s='.$gruppy['id'].'&amp;act=set&amp;d='.$dir_id['dir'].'&amp;page='.$page.'">Параметры папки</a><br/>';
echo '&#187;&nbsp; <a href="?s='.$gruppy['id'].'&amp;act=mesto&amp;d='.$dir_id['dir'].'&amp;page='.$page.'">Переместить папку</a><br/>';
echo '&#187;&nbsp; <a href="?s='.$gruppy['id'].'&amp;act=delete&amp;d='.$dir_id['dir'].'&amp;page='.$page.'">Удалить папку</a><br/>';
}
echo "</div>\n";
}
?>
