<?php
/*
ПРОСМОТРЩИК ФАЙЛОВ....
ФАЙЛЫ ЛОЖИМ В ПАПКУ see В ФОРМАТЕ *.txt
ССЫЛКА НА ФАЙЛ БУДЕТ ИМЕТЬ ВИД file.php?view=НАЗВАНИЕ_ФАЙЛА_БЕЗ_РАСШИРЕНИЯ
*/
include_once("ini.php");
$user=info();
if($user){$b="cabinet";} else {$b="index";}
//////////
if($_GET["view"]){
$file=@file_get_contents("see/".$_GET["view"].".txt");
if(!$file){$title="Ошибка";
include_once("header.php");
echo diz($title, "header");
echo "К сожалению, данный файл не найден.";
} else {
$file=str_replace("\$min\$",$min,$file);
$file=str_replace("\$ref\$",$ref,$file);
$file=str_replace("\$hr\$",$hr,$file);
$file=str_replace("\$premium\$",$tarif["premium"],$file);
$file=str_replace("\$gold\$",$tarif["gold"],$file);
$file=split("\|",$file);
/////////////////
$title=$file[0];
include_once("header.php");
echo diz($title, "header");
/////////////////
echo $div["menu"];
echo $file[1];
echo $div["end"];
}
} else {
Header("location: /index.php");
}
echo $hr.url($b);
////////////////
include_once("footer.php");
?>

