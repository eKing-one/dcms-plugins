<?php
if(isset($_GET['act']) && $_GET['act']=='delete' && $l!='/'){
echo '<div class="err">Удалить файл "'.$file_id['name'].'"?<br/>';
echo '<a href="?s='.$gruppy['id'].'&amp;d='.$dir_id['dir'].'&amp;f='.urlencode($file_id['name']).'.'.$file_id['ras'].'&amp;showinfo&amp;act=delete&amp;ok">Да</a> | ';
echo '<a href="?s='.$gruppy['id'].'&amp;d='.$dir_id['dir'].'&amp;f='.urlencode($file_id['name']).'.'.$file_id['ras'].'&amp;showinfo">Нет</a></div>';
}

echo '<div class="foot"><a href="?s='.$gruppy['id'].'&amp;d='.$dir_id['dir'].'&amp;f='.urlencode($file_id['name']).'.'.$file_id['ras'].'&amp;showinfo&amp;act=delete">&#187;&nbsp;Удалить файл</a></div>';
?>