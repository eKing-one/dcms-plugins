<?
/*
Автор скрипта: Simptom
Сайт поддержки: http://s-klub.ru
Запрещено распространять скрипт в любом виде и под любым предлогом!
*/
$flirt=mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `date_last` > '".(time()-600)."' AND `url` like '/flirt/%'"), 0);
echo "".$flirt." чел.";
?>