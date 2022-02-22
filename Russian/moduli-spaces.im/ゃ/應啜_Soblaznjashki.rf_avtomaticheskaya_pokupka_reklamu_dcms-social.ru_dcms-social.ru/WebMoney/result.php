<?php
##############################
#  Модуль покупки рекламы    #
#Автор Sakamsky99 (Sakamsky) #    
#Скрипт запрещено продавать, #
#дарить, кидать в пабл!      #
##############################
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';
$set['title']='Автоматическая покупка рекламы';
include_once '../sys/inc/thead.php';
title();
aut();
err();
require_once ('../WebMoney/head.php');
?>
Реклама успешно куплена.
<?
require_once ('../WebMoney/foot.php');
include_once '../sys/inc/tfoot.php';
?>