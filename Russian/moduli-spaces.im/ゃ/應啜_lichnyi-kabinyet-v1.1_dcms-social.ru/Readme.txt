ПРивет !!!Даный мод это личный кабинет юзеров вашего сайта.
Модуль : Личный кабинет V1.1
Автор : by wladua 
Связь : wladua2013@yandex.ru


Ну что приступим к установки :
1) Закидаем архив в корень сайта и распаковуем его.Но перед эти удалите файл по пути sys/inc/umenu.php 
2)Потом идем в папку style/themes/ваша тема/style.css и в конец дизайна вставляем вот этот код

.bl2 {
border: 0;
width: 100%;
min-height: auto;
-o-border-radius: 0;
border-radius: 0;
-moz-border-radius: 0;
}

.bl1 {
float:left;
display:inline-block;
}

.bl2 {
border: 1px #bcbcbc solid;
border-right: 0;
width: 60%;
min-height: 80px;
float: right;
margin-right:-4px;
padding: 4px;
background-color: #e9e9e9;
-o-border-radius: 14px 0 0 14px;
border-radius: 14px 0 0 14px;
-moz-border-radius: 14px 0 0 14px;
}

3)Так,что делаем дальше: заливаем таблицы те что в архиве
4)Топаем в user/info/wap.php или web.phр и там примерно в 120 строке или после

echo medal($ank['id']) . " " . online($ank['id']) . " ";

вставляем вот это 
 
if ($ank['sostoyanie']==0)echo "Статуса нету<br/>\n";
else if ($ank['sostoyanie']==1)echo "<img src='/sostoyanie/img/1.png' alt='' class='icon'/><br/>\n";
else if ($ank['sostoyanie']==2)echo "<img src='/sostoyanie/img/2.png' alt='' class='icon'/><br/>\n";
else if ($ank['sostoyanie']==3)echo "<img src='/sostoyanie/img/3.png' alt='' class='icon'/><br/>\n";
else if ($ank['sostoyanie']==4)echo "<img src='/sostoyanie/img/4.png' alt='' class='icon'/><br/>\n";
else if ($ank['sostoyanie']==5)echo "<img src='/sostoyanie/img/5.png' alt='' class='icon'/><br/>\n";
else if ($ank['sostoyanie']==6)echo "<img src='/sostoyanie/img/6.png' alt='' class='icon'/><br/>\n";
else if ($ank['sostoyanie']==7)echo "<img src='/sostoyanie/img/7.png' alt='' class='icon'/><br/>\n";
else if ($ank['sostoyanie']==8)echo "<img src='/sostoyanie/img/8.png' alt='' class='icon'/><br/>\n";
else if ($ank['sostoyanie']==9)echo "<img src='/sostoyanie/img/9.png' alt='' class='icon'/><br/>\n";
else if ($ank['sostoyanie']==10)echo "<img src='/sostoyanie/img/10.png' alt='' class='icon'/><br/>\n";
else if ($ank['sostoyanie']==11)echo "<img src='/sostoyanie/img/11.png' alt='' class='icon'/><br/>\n";
else if ($ank['sostoyanie']==12)echo "<img src='/sostoyanie/img/12.png' alt='' class='icon'/><br/>\n";
else if ($ank['sostoyanie']==13)echo " <img src='/sostoyanie/img/13.png' alt='' class='icon'/><br/>\n";
else if ($ank['sostoyanie']==14)echo "<img src='/sostoyanie/img/14.png' alt='' class='icon'/><br/>\n";
else if ($ank['sostoyanie']==15)echo "<img src='/sostoyanie/img/15.png' alt='' class='icon'/><br/>\n";
else if ($ank['sostoyanie']==16)echo "<img src='/sostoyanie/img/16.png' alt='' class='icon'/><br/>\n";
else if ($ank['sostoyanie']==17)echo "<img src='/sostoyanie/img/17.png' alt='' class='icon'/><br/>\n";
else if ($ank['sostoyanie']==18)echo "<img src='/sostoyanie/img/18.png' alt='' class='icon'/><br/>\n";
else if ($ank['sostoyanie']==19)echo "<img src='/sostoyanie/img/19.png' alt='' class='icon'/><br/>\n";
else if ($ank['sostoyanie']==20)echo "<img src='/sostoyanie/img/20.png' alt='' class='icon'/><br/>\n";
else if ($ank['sostoyanie']==21)echo "<img src='/sostoyanie/img/21.png' alt='' class='icon'/><br/>\n";

 
5)Все кабинет полнустью такой как и на картинке .Спасибо что купили модуль скоро будут обновление ))))))