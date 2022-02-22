<?
// Начисление рейтинга и баллов за активность
query("UPDATE `user` SET `balls` = '" . ($active_ank['balls'] + 1) . "', `rating_tmp` = '" . ($active_ank['rating_tmp'] + 1) . "' WHERE `id` = '$active_ank[id]' LIMIT 1");
mysql_query("UPDATE `user` SET `ikey_reit` = '".($user['ikey_reit']+0.1)."' WHERE `id` = '$user[id]' LIMIT 1",$db);
?>
