<?php
$title="Гром | Партнерская программа";////ЗАГОЛОВОК
///////ПОДКЛЮЧАЕМ ФАЙЛЫ
include_once("ini.php");
include_once("header.php");
include_once("referer.php");
////////////////////////
echo diz("...::ГРОМ::...", "header");
////////////////////////
$a=(isset($_GET['a']))?$_GET['a']:"";
if(empty($a)){
$datenew=zapros("select date from news order by id_new desc");
if(!$datenew){$datenew="пока нет";}
///
echo $div["menu"];
include("adv.php");
echo $div["end"];
///
echo $div["menu"];
echo '<div style="text-align: center;"><br/>';
echo '<b>Продавай свой трафик у нас!</b><br/>';
echo 'Низкая минималка<br/>Вывод средств на мобильный<br/>Полная статистика переходов<br/>Неограниченное количество ваших сайтов</div>';
echo $div["end"];

echo $div["menu"];
echo '<form action="login.php" method="get">
<div>ID:<br/>
<input type="text" name="id" value=""/><br/>
Пароль:<br/>
<input type="text" name="pass" value=""/><br/>
<input type="submit" value="Вход" />
</div>
</form>';
echo $div["end"];
echo $hr;
///////////
echo $div["menu"];
echo '<a href="reg.php">Регистрация</a><br/>';
echo '<a href="news.php">Новости ('.$datenew.')</a><br/>';
echo '<a href="price.php">Расценки</a><br/>';
echo '<a href="file.php?view=tarif">Тарифы</a><br/>';
echo '<a href="file.php?view=info">Информация</a><br/>';
echo '<a href="file.php?view=faq">FAQ</a><br/>';
echo '<a href="file.php?view=rules">Правила</a><br/>';
echo '<a href="index.php?a=stat">Статистика</a><br/>';
echo '<a href="file.php?view=cont">Контакты</a>';
echo $div["end"];
///////////
}
////////////////СТАТИСТИКА
elseif($a=="stat"){
echo $div["menu"];
echo "<b>Пользователей:</b><br/>";
echo "Обычных: ".zapros("select count(id_zver) from zveri where type='user'")."<br/>";
echo "Premium: ".zapros("select count(id_zver) from zveri where type='premium'")."<br/>";
echo "Gold: ".zapros("select count(id_zver) from zveri where type='gold'")."<br/>";
echo "Рефералов: ".zapros("select count(id_zver) from zveri where id_parent!=0")."<br/>";
echo "<b>Сайтов:</b><br/>";
echo "Активных: ".zapros("select count(id_url) from sites where status='activ'")."<br/>";
echo "Всего: ".zapros("select count(id_url) from sites")."<br/>";
echo "<b>Деньги:</b><br/>";
echo "Сегодня: ".zapros("select round((sum(monS)),2) from zveri")." WMR<br/>";
echo "Всего: ".zapros("select round((sum(monV)),2) from zveri")." WMR<br/>";
echo "Реферальских: ".round((zapros("select sum(monV) from zveri where id_parent!=0")*$ref/100),2)." WMR<br/>";
echo "<b>Клики:</b><br/>";
echo "Сегодня: ".zapros("select sum(clickS) from zveri")."<br/>";
echo "Всего: ".zapros("select sum(clickV) from zveri")."<br/>";
echo $div["end"];
echo $hr;
echo url();
}
else{
Header("location: /index.php");
}
////////////////
include_once("footer.php");
?>