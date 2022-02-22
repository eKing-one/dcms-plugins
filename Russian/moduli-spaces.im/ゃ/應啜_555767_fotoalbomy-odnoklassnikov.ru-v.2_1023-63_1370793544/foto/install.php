<?
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';

$set['title'] = 'Установка';
include_once '../sys/inc/thead.php';

title();
aut();

/*-----------------------загрузка таблиц-----------------------*/
if (isset($_GET['install']) && $_GET['install']=='table')
{
mysql_query("CREATE TABLE IF NOT EXISTS `ocenky` (
  `time` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `stav` int(11) NOT NULL DEFAULT '0',
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");
mysql_query("CREATE TABLE IF NOT EXISTS `gallery_frend` (
  `time` int(11) NOT NULL,
  `id_avtor` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_foto` int(11) NOT NULL DEFAULT '0',
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");
mysql_query("ALTER TABLE `gallery` ADD `my` int(11) default '0'");
mysql_query("ALTER TABLE `gallery_foto` ADD `id_user` int(11) default '0'");
mysql_query("ALTER TABLE `gallery_foto` ADD `avatar` enum('0','1') default '0'");

mysql_query("ALTER TABLE `gallery_rating` ADD `like` int(11) default '0'");
mysql_query("ALTER TABLE `gallery_rating` ADD `avtor` int(11) default '0'");
mysql_query("ALTER TABLE `gallery_rating` ADD `ready` int(11) default '1'");
mysql_query("ALTER TABLE `gallery_rating` ADD `time` int(11) default '0'");
echo "<div class=msg>Таблицы успешно созданы</div>";
echo "<center><img width=100 src='ok.jpg' alt=''></center>";
echo "<div class=p_m>";
echo "Далее будут созданые папки необходимые для работы скрипта.<br />";
echo "<a href=\"?install=dir\" title=\"5+\">Продолжить...</a>";
echo "</div>";
include_once '../sys/inc/tfoot.php';
}
/*-------------------------------------------------------------*/



/*------------------------создание папки-----------------------*/
if (isset($_GET['install']) && $_GET['install']=='dir')
{
if (!file_exists(H."sys/gallery/50")) {
mkdir(H."sys/gallery/50", 0777);
}
if (!file_exists(H."sys/gallery/avatar")) {
mkdir(H."sys/gallery/avatar", 0777);
}
if (!file_exists(H."sys/gallery/tmp")) {
mkdir(H."sys/gallery/tmp", 0777);
}
echo "<div class=msg>Папки успешно созданы</div>";
echo "<center><img width=100 src='ok.jpg' alt=''></center>";
echo "<div class=p_m>";
echo "Необходимо обработать все фотографии для дальнейшей установки.<br />";
echo "Шаг первый. <a href=\"?install=avatar&amp;ok\" title=\"5+\">Продолжить...</a>";
echo "</div>";
include_once '../sys/inc/tfoot.php';
}
/*-------------------------------------------------------------*/


/*------------------------создание превью фото-----------------*/

$k=mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery_foto`"),0);
$q=mysql_query("SELECT * FROM `gallery_foto`");
if (isset($_GET['install']) && $_GET['install']=='avatar')
{
if (isset($_GET['ok']))
{
while ($post = mysql_fetch_assoc($q))
{
$id_foto = $post['id'];
crop(H."sys/gallery/640/$id_foto.jpg", H."sys/gallery/avatar/$id_foto.tmp.jpg");
resize(H."sys/gallery/avatar/$id_foto.tmp.jpg", H."sys/gallery/avatar/$id_foto.jpg", 150, 150);

@chmod(H."sys/gallery/avatar/$id_foto.jpg",0777);
@unlink(H."sys/gallery/avatar/$id_foto.tmp.jpg");
}
header("Location: ?install=avatar");
}
echo "<div class=msg>Шаг 1. Обработано ($k) фото</div>";
echo "<center><img width=100 src='ok.jpg' alt=''></center>";
echo "<div class=p_m>";
echo "Созданы копии фотографий для вывода большого аватара.<br />";
echo "Шаг второй. <a href=\"?install=50&amp;ok\" title=\"5+\">Продолжить...</a>";
echo "</div>";
include_once '../sys/inc/tfoot.php';
}
elseif (isset($_GET['install']) && $_GET['install']=='50')
{
if (isset($_GET['ok']))
{
while ($post = mysql_fetch_assoc($q))
{
$id_foto = $post['id'];
crop(H."sys/gallery/avatar/$id_foto.jpg", H."sys/gallery/50/$id_foto.tmp.jpg");
resize(H."sys/gallery/50/$id_foto.tmp.jpg", H."sys/gallery/50/$id_foto.jpg", 50, 50);

@chmod(H."sys/gallery/50/$id_foto.jpg",0777);
@unlink(H."sys/gallery/50/$id_foto.tmp.jpg");
}
header("Location: ?install=50");
}
echo "<div class=msg>Шаг 2. Обработано ($k) фото</div>";
echo "<center><img width=100 src='ok.jpg' alt=''></center>";
echo "<div class=p_m>";
echo "Созданы копии для вывода превью аватара размером 50х50.<br />";
echo "<a href=\"?install=exit\" title=\"5+\">Завершить установку</a>";
echo "</div>";
include_once '../sys/inc/tfoot.php';
}
elseif (isset($_GET['install']) && $_GET['install']=='exit')
{
@unlink(H."foto/ok.jpg");
@unlink(H."foto/install.php");
header("Location: /");
exit;
}
/*--------------------------------------------------------------*/
echo "<center><img width=100 src='ok.jpg' alt=''></center>";
echo "<div class=p_m>";
echo "Добро пожаловать в установщик фотоальбомов Одноклассники.ru v2.<br />";
echo "<a href=\"?install=table\" title=\"5+\">Приступить к установке</a>";
echo "</div>";



include_once '../sys/inc/tfoot.php';
?>