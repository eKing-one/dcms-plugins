<?php
///ВЫВОДИМ РАСЦЕНКИ
$title="Расценки";
include_once("ini.php");
include_once("header.php");
echo diz($title, "header");
$user=info();
if($user!=0){$b="cabinet";} else {$b="index";}
//////////
echo "<center><b>Цена за 1000 переходов</b></center>".$hr;
echo $div["menu"];
for($i=1;$i<119;$i++){
$oper=$i;
include("opname.php");
if($money["".$i.""]!=0)echo $oper_name." - ".$money["".$i.""]." WMR<br/>";
echo $hr;
}
echo $div["end"];
echo $hr.url($b);
////////////////
include_once("footer.php");
?>

