<?php

if($dir_id['upload']==1){

if(isset($_GET['act']) && $_GET['act']=='upload' && $l!='/'){

if(!isset($set['obmen_limit_up']) || $set['obmen_limit_up']<=$user['balls']){
	echo '<form enctype="multipart/form-data" action="?s='.$gruppy['id'].'&amp;act=upload&amp;ok&amp;d='.$dir_id['dir'].'&amp;page='.$page.'" method="post">';
	echo 'Файл:<br/><input name="file" type="file" maxlength="'.$dir_id['maxfilesize'].'"/><br/>';
	echo 'Скриншот:<br/><input name="screen" type="file" accept="image/*"/><br/>';
	echo 'Описание:<br/><textarea name="opis"></textarea><br/>';
	echo '<input class="submit" type="submit" value="Выгрузить"/><br/>';
	echo '*Разрешается выгружать файлы форматов: '.$dir_id['ras'].'<br/>';
	echo 'Размером до: '.size_file($dir_id['maxfilesize']).'<br/>';
	echo '&#187;&nbsp;<a href="?s='.$gruppy['id'].'">Отмена</a></form>';
	}else{
		echo 'Выгружать файлы в обменник могут только пользователи, набравшие '.$set['obmen_limit_up'].' и более баллов<br/>';
		}
		}
echo "<div class='nav2'>\n";
echo '<img src="img/plus_16.png" alt="" class="icon"/> <b><a href="?s='.$gruppy['id'].'&amp;act=upload&amp;d='.$dir_id['dir'].'&amp;page='.$page.'">Выгрузить файл</a></b><br/>';
echo '</div>';
}
?>