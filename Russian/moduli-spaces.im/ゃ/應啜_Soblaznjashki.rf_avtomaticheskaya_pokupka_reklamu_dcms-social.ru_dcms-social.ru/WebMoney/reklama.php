<?
##############################
#  Модуль покупки рекламы    #
#Автор Sakamsky99 (Sakamsky) #
#Скрипт запрещено продавать, #
#дарить, кидать в пабл!      #
##############################
define('H', $_SERVER['DOCUMENT_ROOT'].'/');
require_once H.'WebMoney/core.php';
$res = $dbb->query("SELECT * FROM `webmoney_rek` where `date_last`>'".time()."' ORDER BY RAND() DESC LIMIT 6");

while ($row = $res->fetch(PDO::FETCH_BOTH)){	
        if($row['color']==null){$name='-'.htmlspecialchars($row['name']);}else{$name='<font color="'.htmlspecialchars($row['color']).'">- '.htmlspecialchars($row['name']).'</font>';}
       if(abs(intval($row['bold']))==1){$nam='<b>'.$name.'</b>';}else{$nam=$name;}
       echo '<a href='.htmlspecialchars($row['url']).'>'.$nam.'</a><br/>';
}
?>
