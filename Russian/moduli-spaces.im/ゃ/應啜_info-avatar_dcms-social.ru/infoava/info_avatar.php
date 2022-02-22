<?php
echo"<img src='/style/icons/id1.png'/><b>ID:<font color='green'> $ank[id]</b></font><br />\n";
if ($ank['ank_name']!=NULL)echo"<img src='/style/icons/imya.png'/><b>Имя:</b><font color='green'>$ank[ank_name]</font><br />\n";
else
echo"<img src='/style/icons/imya.png'/><b>Имя:</b><font color='red'>не указана</font><br />\n";
if ($ank['ank_city']!=NULL)echo"<img src='/style/icons/dom.png'/><b>Город:</b><font color='green'>$ank[ank_city]</font><br />\n";
else
echo"<img src='/style/icons/dom.png'/><b>Город:</b><font color='red'>не указан</font><br />\n";
echo"<img src='/style/icons/bals.png' / ><b>Баллы: </b>";
echo"<font color='green'>$ank[balls]</font><br />";
echo '<img src="/style/icons/xclock.png"/><b>Был(а):</b>';
echo"<font color='green'>".vremja($ank['date_last'])."</font><br />\n";
include 'avtoritet.php';
echo "</div>";
echo  '</span>';
?>