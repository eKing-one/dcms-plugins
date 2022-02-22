<?
/*
Запрещено распространять скрипт в любом виде и под любым предлогом!
*/
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';
$set['title']='Доктор';
include_once '../sys/inc/thead.php';
title();
aut();
include_once 'inc/user.php';
$b=mysql_fetch_assoc(mysql_query("SELECT * FROM `baby` WHERE `mama` = '".$user['id']."' OR `papa` = '".$user['id']."' LIMIT 1"));
if (!$b)
{
echo "<div class='err'>";
echo "У вас нет ребёнка!";
echo "</div>";
echo "<a href='index.php'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
echo "<table style='width:100%' cellspacing='1' cellpadding='1'><tr>";
echo "<td class='icon14'><center>";
echo "<img src='img/doktor.png' width='90' alt='Simptom'>";
echo "</center></td>";
echo "<td class='main'>";
echo "<center><b>Добро пожаловать!</b></center><hr />";
echo "Вот что я могу сказать вам по поводу вашего ребёнка:<hr />";
echo "<img src='img/health.png' alt='Simptom'> <b>Здоровье:</b> ";
if ($b['health']>=0 && $b['health']<=10)
{
echo "Очень плохое<br />";
echo "Если вы не будете следить за своим ребёнком, то у вас его отнимут!<br />";
}
else if ($b['health']>=11 && $b['health']<=40)
{
echo "Плохое<br />";
echo "Если вы не будете следить за своим ребёнком, то у вас его отнимут!<br />";
}
else if ($b['health']>=41 && $b['health']<=60)
{
echo "Нормальное<br />";
echo "Ваш ребёнок активный и стремится познать мир. Не отказывайте ему в этом!<br />";
}
else if ($b['health']>=61 && $b['health']<=80)
{
echo "Хорошее<br />";
echo "Вы хорошо справляетесь с обязоностями родителя!<br />";
}
else if ($b['health']>=81)
{
echo "Замечательное<br />";
echo "Вы хорошо справляетесь с обязоностями родителя! Но не стоит забывать что ваш ребёнок желает открывать для себя что-то новое!<br />";
}
echo "<img src='img/eda.png' alt='Simptom'> <b>Сытость:</b> ";
if ($b['eda']>=0 && $b['eda']<=10)
{
echo "Умирает от голода!<br />";
echo "Если вы не будете следить за своим ребёнком, то у вас его отнимут!<br />";
}
else if ($b['eda']>=11 && $b['eda']<=40)
{
echo "Испытывает голод<br />";
echo "Если вы не будете следить за своим ребёнком, то у вас его отнимут!<br />";
}
else if ($b['eda']>=41 && $b['eda']<=60)
{
echo "В норме<br />";
echo "Не смотря на это советую вам покормить своего ребёнка!<br />";
}
else if ($b['eda']>=61 && $b['eda']<=80)
{
echo "Сыт<br />";
echo "Вы хорошо справляетесь с обязоностями родителя!<br />";
}
else if ($b['eda']>=81)
{
echo "Еще немного и было бы переедание)<br />";
echo "Вы хорошо справляетесь с обязоностями родителя! Но не стоит забывать что ваш ребёнок может скоро захотеть кушать!<br />";
}
echo "<img src='img/happy.png' alt='Simptom'> <b>Энергия:</b> ";
if ($b['happy']>=0 && $b['happy']<=10)
{
echo "Переутомление!<br />";
echo "Если вы не будете следить за своим ребёнком, то у вас его отнимут!<br />";
}
else if ($b['happy']>=11 && $b['happy']<=40)
{
echo "Чувствует усталость<br />";
echo "Если вы не будете следить за своим ребёнком, то у вас его отнимут!<br />";
}
else if ($b['happy']>=41 && $b['happy']<=60)
{
echo "В норме<br />";
echo "Не смотря на это советую вам не перетруждать своего ребёнка!<br />";
}
else if ($b['happy']>=61 && $b['happy']<=80)
{
echo "Готов к приключениям<br />";
echo "Вы хорошо справляетесь с обязоностями родителя!<br />";
}
else if ($b['happy']>=81)
{
echo "Полон сил<br />";
echo "Вы хорошо справляетесь с обязоностями родителя! Но не стоит забывать что ваш ребёнок может скоро почувствовать усталость!<br />";
}
echo "</td>";
echo "</tr></table>";
echo "<a href='my_baby.php'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
?>