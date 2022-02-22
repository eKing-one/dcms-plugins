<?
include_once 'sys/inc/start.php';
include_once 'sys/inc/compress.php';
include_once 'sys/inc/sess.php';
include_once 'sys/inc/home.php';
include_once 'sys/inc/settings.php';
include_once 'sys/inc/db_connect.php';
include_once 'sys/inc/ipua.php';
include_once 'sys/inc/fnc.php';
include_once 'sys/inc/user.php';

$set['title']='Рейтинг активности';
include_once 'sys/inc/thead.php';
title();
err();
aut();

echo'<div class="menu_razd">1) Что такое рейтинг активности?</div>';
echo'<div class="main_menu">Рейтинг активности - эта уникальная система подсчета вашей активности на сайте,
основанная на многочисленных факторах... Рейтинг может повышаться и понижаться.</div>';

echo'<div class="menu_razd">2) Понижение рейтинга</div>';
echo'<div class="main_menu">Чаще всего рейтинг понижается из-за нарушения, выкладывания вами в обменник "плохих" файлов.
Возможно вы стали проводить меньше времени на сайте, вам стали чаще ставить минусы в обычный рейтинг.</div>';

echo'<div class="menu_razd">3) Повышение рейтинга</div>';
echo'<div class="main_menu">рейтинг повышается за посты в форуме, чате, гостевой, создание актуальных тем.
А так же создание ценных комментариев к файлам, частого нахождения на сайте, времени проведения на сайте.</div>';

echo'<div class="menu_razd">4) Я создал 10 постов в форуме, но рейтинг не повысился!</div>';
echo'<div class="main_menu">Рейтинг повышается не заметно, а так же считается не каждый пост.</div>';
echo '<div class="foot"><a href="/info.php">Назад</a></div>';
include_once 'sys/inc/tfoot.php';
?>
