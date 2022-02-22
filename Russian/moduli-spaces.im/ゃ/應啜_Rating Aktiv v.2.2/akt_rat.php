<?
////Функция зачисления рейтинга
$number = rand(0, 1)/1000;//генирируем случайное число
mysql_query("UPDATE `user` SET `akt_rating` = '".($user['akt_rating']+$number)."', `aktiv_minus` = '1'  WHERE `id` = '$user[id]' LIMIT 1");
//////////////////////////
?>