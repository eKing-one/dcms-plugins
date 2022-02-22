<?php
if(is_file(H."sys/gruppy/obmen/screens/128/$file_id[id].gif")){	echo '<img src="/sys/gruppy/obmen/screens/128/'.$file_id['id'].'.gif" alt="Скрин..."/><br/>';
	}

if($file_id['opis']!=NULL){	echo '<font color=\"#009900\" size=\"3\"><u>Описание</u></font>: <b>'.trim(br(links($file_id['opis']))).'</b><br/>';
	}

echo '<u><font color=\"#009900\" size=\"3\">Добавлен</font></u>: <b>'.vremja($file_id['time']).'</b><br/>';
echo '<u><font color=\"#009900\" size=\"3\">Размер</font></u>: <b>'.size_file($file_id['size']).'</b><br/>';
?>