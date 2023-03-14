<?php
include_once '../../sys/inc/start.php';
include_once '../../sys/inc/compress.php';
include_once '../../sys/inc/sess.php';
include_once '../../sys/inc/home.php';
include_once '../../sys/inc/settings.php';
include_once '../../sys/inc/db_connect.php';
include_once '../../sys/inc/ipua.php';
include_once '../../sys/inc/fnc.php';
//$banpage=true;
include_once '../../sys/inc/user.php';
/***************************/
if(mysql_query("
CREATE TABLE `million_q` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`text` VARCHAR(1024) NOT NULL ,
`step` VARCHAR(16) NOT NULL 
) ENGINE = MYISAM
")) echo 'million_q INSTALLED!<br/>';
else echo mysql_error();
if(mysql_query("
CREATE TABLE `million_ans` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`ans` VARCHAR(1024) NOT NULL ,
`write` VARCHAR(2) NOT NULL ,
`id_q` INT NOT NULL 
) ENGINE = MYISAM
")) echo 'million_ans INSTALLED!<br/>';
else echo mysql_error();


function damp_q($step){
global $qc;

$qs=file("installer/$step.SVP");

for($i=0;$qst=$qs[$i];$i++){
	$qst=trim($qst);
	$qst=iconv('cp1251','utf-8',$qst);
	if(substr($qst,0,1)=='^'){
		$qc++;
		$vop=trim(htmlspecialchars(substr($qst,1,strlen($qst)-1)));
		//echo "Вопрос:$vop<br/>";
		mysql_query("INSERT INTO `million_q` (`text`, `step`) VALUES('$vop', '$step')");
		$id_q=mysql_insert_id();
		$i++;
		$qst=trim($qs[$i]);$qst=iconv('cp1251','utf-8',$qst);
		$wr=substr($qst,0,1);
		$qst=substr($qst,1,strlen($qst)-1);
		mysql_query("INSERT INTO `million_ans` (`ans`, `id_q`, `write`) VALUES('$qst', '$id_q', '$wr')");
		$i++;
		$qst=trim($qs[$i]);$qst=iconv('cp1251','utf-8',$qst);
		$wr=substr($qst,0,1);
		$qst=substr($qst,1,strlen($qst)-1);
		mysql_query("INSERT INTO `million_ans` (`ans`, `id_q`, `write`) VALUES('$qst', '$id_q', '$wr')");
		$i++;
		$qst=trim($qs[$i]);$qst=iconv('cp1251','utf-8',$qst);
		$wr=substr($qst,0,1);
		$qst=substr($qst,1,strlen($qst)-1);
		mysql_query("INSERT INTO `million_ans` (`ans`, `id_q`, `write`) VALUES('$qst', '$id_q', '$wr')");
		$i++;
		$qst=trim($qs[$i]);$qst=iconv('cp1251','utf-8',$qst);
		$wr=substr($qst,0,1);
		$qst=substr($qst,1,strlen($qst)-1);
		mysql_query("INSERT INTO `million_ans` (`ans`, `id_q`, `write`) VALUES('$qst', '$id_q', '$wr')");
		}
	}
}
$qc=0;
damp_q('100');
damp_q('1000');
damp_q('1000000');
damp_q('125000');
damp_q('16000');
damp_q('200');
damp_q('2000');
damp_q('250000');
damp_q('300');
damp_q('32000');
damp_q('4000');
damp_q('500');
damp_q('500000');
damp_q('64000');
damp_q('8000');
echo "Записано вопросов $qc";
/*************************/
exit();
?>