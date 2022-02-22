<?php
// ///////ПОДКЛЮЧАЕМ ФАЙЛИКИ
include_once("ini.php");
include_once("ip.php");
// /////////ПИРИХОДИМ..... :-)
$a=0;
if($oper==50)$a++;
if($oper==1)$a++;
if($oper==5)$a++;
if($oper==9)$a++;
if($oper==14)$a++;
if($oper==10)$a++;
if($oper==13)$a++;
if($oper==8)$a++;
if($oper==7)$a++;
if($oper==44)$a++;
if($oper==2)$a++;
if($oper==4)$a++;
if($oper==6)$a++;
if($oper==15)$a++;
if($a){
header("location: http://volkes.ru");
}
else{
header("location: http://volkes.ru/index.php");
}
?>

