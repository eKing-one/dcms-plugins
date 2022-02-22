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
 $set['title']='Покупка монет через смс';
  include_once '../sys/inc/thead.php';
  title();
err();
aut();

echo "<div class='nav1'>\n";



echo "<b>Сейчас у вас:</b><br />



- <b><font color='red'>$user[money]  монет.</font></b> <br />";







echo "</div>\n";





switch ($_GET['act'])
{
default:
  {
  include_once 'smsbill.class.php';
 echo '
 

  <div class="fon2">
  Выберите желаемое количество Монет:
  </div>
  <div class="fon3" style="vertical-align:middle;"><img src="/style/icons/money.png"> <a href="smsmoney.php?act=5">20 монет &#187;</div></a>
  <div class="fon3" style="vertical-align:middle;"><img src="/style/icons/money.png"> <a href="smsmoney.php?act=10">40 монет &#187;</div></a>
  <div class="fon3" style="vertical-align:middle;"><img src="/style/icons/money.png"> <a href="smsmoney.php?act=30">75 монет &#187;</div></a>
  ';
  break;
  }

case 5:
  {
  $set['title']='Купить 20 монет';
  include_once '../sys/inc/thead.php';
  include_once 'smsbill.class.php';


 echo '<div class="fon3">Выберите страну и оператора, затем отправьте смс, в ответ вам придет пароль, введите его в поле ввода пароля и нажмите ВВЕСТИ и вам зачислятся монеты!</div>';
  $smsbill = new SMSBill_getpassword();
  $smsbill->setServiceId(8887);
  $smsbill->useEncoding('utf-8');
  $smsbill->useHeader('no');
  $smsbill->useJQuery('yes');
  $smsbill->useLang('ru');
  if (isset($_REQUEST['smsbill_password'])) 
    {
    if (!$smsbill->checkPassword($_REQUEST['smsbill_password'])) 
      {
      echo '<div class="fon3" style="vertical-align:middle;">Введенный пароль не верный вернитесь назад и попробуйте еще раз!<br/><a href="smsmoney.php?act=5">Продолжить</a></div>';
      }
      else
      { 
 			mysql_query("UPDATE `user` SET `money`=`money`+20 WHERE `id`=$user[id]");
 			echo '<div class="infosm" style="vertical-align:middle;">Вам успешно зачислено 20 монет!<br/><a href="../info.php">Продолжить</a></div>';
 		  }
    }
    else
    {
      //показать платежную форму//
 		echo '<div class="fon3" style="vertical-align:middle;">';
 		echo $smsbill->getForm();
 		echo '</div>';
    }
  
  break;
  }

case 10:
  {
  $set['title']='Купить 40 монет';
  include_once '../sys/inc/thead.php';
  include_once 'smsbill.class.php';

 echo '<div class="fon3">Выберите страну и оператора, затем отправьте смс, в ответ вам придет пароль, введите его в поле ввода пароля и нажмите ВВЕСТИ и вам зачислятся монеты!</div>';
  $smsbill = new SMSBill_getpassword();
  $smsbill->setServiceId(10172);
  $smsbill->useEncoding('utf-8');
  $smsbill->useHeader('no');
  $smsbill->useJQuery('yes');
  $smsbill->useLang('ru');
  if (isset($_REQUEST['smsbill_password'])) 
    {
    if (!$smsbill->checkPassword($_REQUEST['smsbill_password'])) 
      {
      echo '<div class="fon3" style="vertical-align:middle;">Введенный пароль не верный вернитесь назад и попробуйте еще раз!<br/><a href="smsmoney.php?act=10">Продолжить</a></div>';
      }
      else
      { 
 			mysql_query("UPDATE `user` SET `money`=`money`+40 WHERE `id`=$user[id]");
 			echo '<div class="fon3" style="vertical-align:middle;">Вам успешно зачислено 40 монет!<br/><a href="../info.php">Продолжить</a></div>';
 		  }
    }
    else
    {
      //показать платежную форму//
 		echo '<div class="fon3" style="vertical-align:middle;">';
 		echo $smsbill->getForm();
 		echo '</div>';
    }
    
    
  break;
  }
  
case 30:
  {
  $set['title']='Купить 75 монет';
  include_once '../sys/inc/thead.php';
  include_once 'smsbill.class.php';

 echo '<div class="fon3">Выберите страну и оператора, затем отправьте смс, в ответ вам придет пароль, введите его в поле ввода пароля и нажмите ВВЕСТИ и вам зачислятся монеты!При пополнении 75 монет  вам зачисляеться бонус в размере 5 монет.</div>';
  $smsbill = new SMSBill_getpassword();
  $smsbill->setServiceId(10173);
  $smsbill->useEncoding('utf-8');
  $smsbill->useHeader('no');
  $smsbill->useJQuery('yes');
  $smsbill->useLang('ru');
  if (isset($_REQUEST['smsbill_password'])) 
    {
    if (!$smsbill->checkPassword($_REQUEST['smsbill_password'])) 
      {
      echo '<div class="fon3" style="vertical-align:middle;">Введенный пароль не верный вернитесь назад и попробуйте еще раз!<br/><a href="smsmoney.php?act=30">Продолжить</a></div>';
      }
      else
      { 
 			mysql_query("UPDATE `user` SET `money`=`money`+80 WHERE `id`=$user[id]");
 			echo '<div class="fon3" style="vertical-align:middle;">Вам успешно зачислено 75 монет + 5  (Бонусных)  !<br/><a href="../info.php">Продолжить</a></div>';
 		  }
    }
    else
    {
      //показать платежную форму//
 		echo '<div class="fon3" style="vertical-align:middle;">';
 		echo $smsbill->getForm();
 		echo '</div>';
    }
    
  break;
  }
}
include_once '../sys/inc/tfoot.php';
?>