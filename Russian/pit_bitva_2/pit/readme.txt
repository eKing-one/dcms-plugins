Мод Битва питомцев
Это типа мой питомец или питомцы в анкету 
1) У вас в анкете появица изображение вашого питомца и его имя
2) Вы можете пакупать своему питомцу жызнь здорове и силу чтобы сражаца з другими питомцами
3) Своему питомцу можете купить различные игрущки дом и другое
Обращяйтесь к ADMIN на сайте http://vent.besaba.com! 
/********************************/
Установка:
1.Залить таблицы sql.sql
2.Поставьте этот код в info.php
$q_pit=mysql_fetch_array(mysql_query("SELECT * FROM `pit` WHERE `id_user`='$ank[id]'"));
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `pit` WHERE `id_user` = '$ank[id]'"),0) != '0')echo'<img src="pit/img/'.$q_pit[pit].'.png" alt="" class="icon"/>Имя: <a href="pit/index.php?id='.$ank[id].'">'.$q_pit[name].'</a><br />';
3. В файле head.php можете всё настроить как вы хотите