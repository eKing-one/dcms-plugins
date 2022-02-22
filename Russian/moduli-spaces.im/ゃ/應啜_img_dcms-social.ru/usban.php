<?php
////САМАЯ ВЕСЁЛАЯ СТРАНИЧКА
$title = "Акаунт забанен!";
include_once("ini.php");
include_once("header.php");
echo diz($title, "header");
echo '<center>Ваш акаунт заблокирован за нарушение правил! Если Вы считаете себя невиновным,то свяжитесь с администрацией и докажите это.
В свою очередь администрация предъявит вам доказательства или причину бана. <br>[<a href="http://volkes.ru/info.php?id=1">написать админу</a>]</center>';
echo $hr . url();
// //////////////
include_once("footer.php");
?>

