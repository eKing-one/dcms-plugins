        /////////////////////////////////////////////////////
       //////////////панелька как в вк//////////////////////
      /////////////////////////////////////////////////////
     ////////////////автор master/////////////////////////
    /////////////////////////////////////////////////////
   /////////////////цена товара 50р/////////////////////
  /////////////////////////////////////////////////////
 ////////////////сайт scriptwm.ru/////////////////////
/////////////////////////////////////////////////////




/////////////////////Установка///////////////////////////

1) Выполнить запрос :

ALTER TABLE `user` ADD `paneljs` set('0','1') DEFAULT '0' ;


2) Все Файлы c папки panel кинуть в корень .

3) В папке с темой где вы хотите видеть панель, в файле head.php (Кто не в курсе /style/themes/название_темы/heads.php)  вставить этот код :

в конце  
//////////////////////////
if($user['paneljs']==1){
include_once 'panel.php'; 
};
//////////////////////////

 И

ПОСЛЕ - <link rel="stylesheet" href="/style/themes/<?echo $set['set_them'];?>/style.css" type="text/css" />
////////////////////////////
<link rel="stylesheet" href="/panell/css/decor_web_def.css" type="text/css" />
<script src="/panell/js/paneljs.js"></script>
	

////////////////////////////////


4) В  Файле settings.php прописать :


ПОСЛЕ - if (isset($_POST['save'])){
////////////////////////////////////
if (isset($_POST['paneljs']) && ($_POST['paneljs']==2 || $_POST['paneljs']==1 || $_POST['paneljs']==0))
{
$user['paneljs']=intval($_POST['paneljs']);
mysql_query("UPDATE `user` SET `paneljs` = '$user[paneljs]' WHERE `id` = '$user[id]' LIMIT 1");
}
else $err='Ошибка режима показа панели';
///////////////////////////////////


 и 
 
ПОСЛЕ - echo "<form method='post' action='?$passgen'>\n";
//////////////////////////////////
echo "панель:<br />\n<select name='paneljs'>\n";
echo "<option value='1'".($user['paneljs']==1?" selected='selected'":null).">вкл</option>\n";
echo "<option value='0'".($user['paneljs']==0?" selected='selected'":null).">выкл</option>\n";
echo "</select><br />\n";
/////////////////////////////////




готово ) скрипт работает ^^
если есть проблемы пишите - ttdris@spaces.ru
