<?php
if(isset($_GET['del_post']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_obmen_komm` WHERE `id` = '".intval($_GET['del_post'])."' AND `id_file` = '$file_id[id]' AND `id_gruppy` = '$gruppy[id]'"),0)){	mysql_query("DELETE FROM `gruppy_obmen_komm` WHERE `id` = '".intval($_GET['del_post'])."' AND `id_gruppy` = '$gruppy[id]' LIMIT 1");
	msg ('Комментарий успешно удален');
	}
?>