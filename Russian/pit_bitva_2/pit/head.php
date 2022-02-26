<?php
$cena_sila=20; //Цена силы защиты и силы
$time_sila=3; //перезарядка после покупки силы и защиты, в часах
$time_boii=3; //Перезарядка после боя , в часах
$max_sila=80; //Максимальная покупки силы или защиты

/////То что внизу лучьше не трогать
$qqq_user=mysql_fetch_array(mysql_query("SELECT * FROM `pit` WHERE `id_user`='".$user['id']."'"));
echo '<div class=p_t>> <img src="icon/bitva/str.png" alt="" class="icon">'.$qqq_user[sila].' <img src="icon/bitva/vit.png" alt="" class="icon"> '.$qqq_user[zdorov].' <img src="icon/bitva/def.png" alt="" class="icon"> '.$qqq_user[zashita].'</div>';

?>