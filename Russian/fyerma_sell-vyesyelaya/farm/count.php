<?
/*
Автор скрипта: MoD
ICQ: 3536335
脚本不能
向公众投放，
卖给他们
给朋友！
*/
echo dbresult(dbquery("SELECT COUNT(*) FROM `user` WHERE `date_last` > '".(time()-600)."' AND `url` like '/fermer/%'"), 0).' человек';
echo '<br />';?>