<?php
$title = "Аукцион";
include_once("ini.php");
include_once("header.php");
if((isset($_GET['pwd']))&&($_GET['pwd']==$apanel)){$admin=1;} else {$admin=0;};
$user = info();
if((!$user)&&(!$admin)){
header("location: index.php");
}
$stav=(isset($_POST['stav']))?$_POST['stav']:"0";

$maxstav=zapros("select summa from auction order by summa desc");
//////
echo diz($title, "header");
echo $div['menu'];
if(isset($_POST['action'])){
$stav=(float)$stav;
if(!$stav){
echo 'Ставка введена не верно!<br/>';
echo $hr;
}
elseif($stav<$shag+$maxstav){
echo 'Ставка ниже минимума!<br/>';
echo $hr;
}
elseif($user['balans']<$stav){
echo 'Не хватает средств!<br/>';
echo $hr;
}
elseif($user['id_zver']==zapros("select id_zver from winner")){
echo 'Победитель последней игры не может участвовать в следующей игре!<br/>';
echo $hr;
}
else
{
$st=zapros("select summa from auction where id_zver=".$user['id_zver']);
if($st){
mysql_query("update auction set summa=summa+".$stav." where id_zver=".$user['id_zver']."");
}
else
{
mysql_query("insert into auction set id_zver=".$user['id_zver'].", summa=".$stav."");
}
mysql_query("update zveri set balans=balans-".$stav." where id_zver=".$user['id_zver']);
echo 'Ставка успешно поставлена!<br/>';
echo $hr;
}
}
$query=mysql_query("select * from auction order by summa desc");
$auc=mysql_fetch_array($query);
$bank=zapros("select round(sum(summa),2) from auction");
$count=zapros("select count(id_zver) from auction");
if(!$bank){$bank="нет";}
echo "Минимальный \"шаг\" аукциона: ".$shag."<br/>";
echo "Банк аукциона: ".$bank."<br/>";
echo "Максимальная ставка: ".$maxstav."<br/>";
echo "Всего ставок: ".$count."<br/>";
echo "Текущий лидер: ".(($user['id_zver']==$auc['id_zver'])?"Вы":"Не Вы")."<br/>";
$t1=23-date('H');
$t2=59-date('i');
$t3=59-date('s');
echo 'До конца аукциона: <b>';
if(strlen($t1)==1)$t1='0'.$t1;
if(strlen($t2)==1)$t2='0'.$t2;
if(strlen($t3)==1)$t3='0'.$t3;
echo $t1.":".$t2.":".$t3."</b><br/>";
echo $hr;
echo '<form method="post">
Ставка:<br/><input type="text" name="stav" value="'.(zapros("select summa from auction order by summa desc")+$shag).'"/><br/>
<input type="hidden" name="action" value="1"/>
<input type="submit" value="Поставить"/><br/>
</form>';
echo $div['end'];
echo $hr;
echo $div['menu'];
$query=mysql_query("select * from winner");
$winner=mysql_fetch_array($query);
if(!$winner[0]){
echo 'Победители отсутствуют...';
}
else
{
echo 'ID: '.$winner['id_zver'].'<br/>';
echo 'Сумма: '.$winner['summa'].'<br/>';	
}
echo $div['end'];
echo $hr;
echo url('cabinet');
// //////////////
include_once("footer.php");

?>