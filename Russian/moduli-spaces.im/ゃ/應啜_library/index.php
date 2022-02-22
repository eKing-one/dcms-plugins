<?php
/**
 * @author Banito
 * @ICQ 555561009
 * @copyright 2012
 */
#Делаю грабберы на заказ#
##################
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';
$set['title']='Библиотека';
include_once '../sys/inc/thead.php';
title();
aut();
#######################
$file = file_get_contents('http://lib.siza.us/library/'.$_GET['id']); //подключяемся к сайту
$file = str_replace('href="/library/','href="'.$_SERVER["SCRIPT_NAME"].'?id=', $file);
#######################
$file=str_replace('<?xml version="1.0" encoding="utf-8"?>', '',$file);
$file=preg_replace('|<!DOCTYPE(.*?)<body>|is', '',$file);
$file=preg_replace('|<div class="header">(.*?)</div>|is', '',$file);
$file=preg_replace('|<div class="tmn">(.*?)</div>|is', '',$file);
$file=str_replace('&bull;', '',$file);
$file=str_replace('<a href="../index.php">На главную</a>', '',$file);
$file=preg_replace('|<div class="fmenu">(.*?)</div>|is', '',$file);
$file=preg_replace('|<div class="footer">(.*?)</html>|is', '',$file);
$file=str_replace('menu_green_a','main_menu',$file);
$file=str_replace('menu_green','menu_razd',$file);
$file=str_replace('com2','main_menu',$file);
$file=str_replace('com','main_menu',$file);
$file=str_replace('book_list','main_menu',$file);
$file=str_replace('/library/img/','http://films.siza.us/img/',$file);
$file=str_replace('http://films.siza.us/img/page.png','http://lib.siza.us/library/img/page.png',$file);
$file=str_replace('<div class="green_line"><img src="http://films.siza.us/img/book.png" class="ico" alt="." /> Java книги</div>','',$file);
$file=str_replace('ss="load"','ss="main_menu"',$file);
$file=str_replace('<b style="color:black">','',$file);
$file=str_replace('/library/index.php?id=load','http://lib.mobik.ru/library/load',$file);
$file=str_replace('<a href="/library/index.php?id=shelf','<a href="http://'.$_SERVER['HTTP_HOST'].'/library/index.php?id=shelf',$file);
$file=str_replace('&#187; <a href="http://'.$_SERVER['HTTP_HOST'].'/library/index.php?id=shelf">Сохранить на полку</a><br />','',$file);
$file=str_replace('<fileldset>','</div>',$file);
$file=str_replace('</fieldset>','',$file);
$file=preg_replace('#<input type=(.*)/div>#isU','',$file);
$file=preg_replace('#Перейти" />(.*)/div>#isU','',$file);
$file=str_replace('<a href="http://'.$_SERVER['HTTP_HOST'].'/library/index.php?id=shelf">Книжная полка</a>','',$file);
$file=str_replace('/library/index.php?id=search','http://'.$_SERVER['HTTP_HOST'].'/library/index.php?id=search',$file);
$file=str_replace('&#187; <a href="http://'.$_SERVER['HTTP_HOST'].'/library/index.php?id=search">Поиск</a><br />','',$file);
##################
#####
echo $file;
include_once '../sys/inc/tfoot.php';
?>