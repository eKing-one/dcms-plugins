<?php
/*
НАЧИЛЕНИЕ РЕФЕРАЛЬСКИХ И ОБНУЛЕНИЕ НЕОБХОДИМЫХ ДАННЫХ.
ЗАПУСКАТЬ ПО КРОНУ РАЗ В СУТКИ, ЖЕЛАТЕЛЬНО В ПОЛНОЧЬ.
*/
include_once("ini.php");
///////////////////////////
///НАЧИСЛЯЕМ РЕФЕРАЛЬСКИЕ//
///////////////////////////
$query=mysql_query("select id_parent, sum(monS) from zveri where status='activ' group by id_parent");
while($data=mysql_fetch_array($query)){
mysql_query("update zveri set balans=balans+".($data[1]*$ref/100)." where id_zver=".$data[0]."");
}
///////////////////////////
mysql_query("delete from trafick");///ОЧИЩАЕМ СТАТИСТИКУ
mysql_query("update zveri set clickS=0, monS=0");///////ОБНУЛЯЕМ КЛИКИ И ДЕНЬГИ ЗА СЕГОДНЯ
mysql_query("update zveri set type='premium' where clickV>=50 and clickV<150");/////ДАЕМ ДОПОЛНИТЕЛЬНЫЙ СТАТУС
mysql_query("update zveri set type='gold' where clickV>=150");/////ДАЕМ ДОПОЛНИТЕЛЬНЫЙ СТАТУС
mysql_query("update money set clickS=0, clickSM=0, clickSO=0, clickSC=0");
mysql_query("delete from adver where ".time()."-ts>colday*60*60*24");
///////////////////////////аукцион
$id=zapros("select id_zver from auction order by summa desc");
if($id){
$bank=zapros("select round(sum(summa),2) from auction");
$bank=($bank-($bank/10));
mysql_query("update zveri set balans=balans+".$bank." where id_zver=".$id);
mysql_query("delete from auction");
mysql_query("update winner set id_zver=".$id.", summa=".$bank."");
}
else
{
mysql_query("update winner set id_zver=0, summa=0");
}
///////////////////////////
mysql_close($sql);//////ЗАКРЫВАЕМ СОЕДИНЕНИЕ
//////////
echo "Ok";////:-)
?>

