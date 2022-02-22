<HTML>
<HEAD>
<TITLE>Гостевая книга</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
</HEAD>
<BODY>
<?

// +-------------------------------+
// |          MyPHP GuestBook          |
// +-------------------------------+
// |        http://myphp.dem.ru        |
// +-------------------------------+

require("config.php");

function show_form()
{
?>
    <form method="post" action="">
     Имя<br>
    <input type="text" name="name" maxlength="22">
    <br>
    E-mail<br>
    <input type="text" name="email" maxlength="21">
    <br>
    Сообщение<br>
    <textarea cols="70" rows="20" name="mess"></textarea><br>
    <input type="submit" value="Послать">
    <input type="reset" value="Очистить">
    </form>
<?
}
function save_mess()
{
      global  $name, $email, $mess, $base;

      $date = date("d.m.y - H:i:s");

      $text = $name."^^".$email."^^".$date."^^".$mess."\n";
      $fp = fopen($base,"a");
      fputs($fp, $text);
      fclose($fp);
}
function show_mess()
{
    global $base, $MessOnScreen;

    $file = file($base);
    $file = array_reverse($file);

    echo "<table>";

    if(sizeof($file) < $MessOnScreen) $MessOnScreen = sizeof($file);

    for ($i = 0; $i < $MessOnScreen; $i++)
    {
        $mess = explode("^^",$file[$i]);

        ?>
        <tr>
          <td>
           <p>Написал:
           <? echo "<a href='mailto:".$mess[1]."'>".$mess[0]."</a>";
           echo "<br>";
           echo $mess[2]; ?>
          </td>
          <td>
          <p><?=$mess[3];?></td>
          </tr>
        <?
    }
    echo "</table></td>";
}
function check_mess()
{
    global  $name, $email, $mess;

    $mess=trim($mess);
    $email=trim($email);
    $name=trim($name);

    $name=htmlspecialchars($name);
    $email=htmlspecialchars($email);
    $mess=htmlspecialchars($mess);

    $mess = str_replace("\n","<br>",$mess);

    check_for_length();        //добавили

    if (empty($name)) output_err(2);
    if (!preg_match("/[0-9a-z_]+@[0-9a-z_^\.]+\.[a-z]{2,3}/i", $email)) output_err(1);
    if (preg_match("/[^(\w)|(\x7F-\xFF)|(\s)]/",$name)) output_err(2);

    check_mess_for_flud();     //добавили
    del_mess_from_file();      //добавили
}
function check_for_length()
{
    global $mess, $email, $name, $MessLength;

    if (strlen($mess)>$MessLength) output_err(3);
    $email=substr($email, 0, 21);
    $name=substr($name, 0, 22);
}
function check_mess_for_flud()
{
    global $mess,$base;

    $file=file($base);
    $file=implode("",$file);
    $mess=preg_quote($mess);
    if (eregi($mess, $file)) output_err(4);
    $mess = stripslashes($mess);
}
function del_mess_from_file()
{
    global $base, $MessInFile;

    $file = file($base);
    $k = 0;
    if($MessInFile<sizeof($file))
    {

        for($i=sizeof($file)-$MessInFile; $i<sizeof($file); $i++)
        {
           $ResFile[$k]=$file[$i];
           $k++;
        }
        $fp=fopen($base,"w");
        for($i=0; $i<sizeof($ResFile); $i++)
        {
              fputs($fp, $ResFile[$i]);
        }
        fclose($fp);
    }
}
function output_err($num)
{
    global $err;
    ?>
    <center><h1>Oшибка!</h1></center>
    <p><?=$err[$num];?>

    <?
    exit();
}
if ($mess) {
  check_mess();
  save_mess();
}
show_mess();
show_form();
?>
</BODY>
</HTML>
