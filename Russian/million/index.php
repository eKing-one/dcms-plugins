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
only_reg();
function million_next($step){
global $user;
if($step==100) return '200';
if($step==200) return '300';
if($step==300) return '500';
if($step==500) return '1000';
if($step==1000) return '2000';
if($step==2000) return '4000';
if($step==4000) return '8000';
if($step==8000) return '16000';
if($step==16000) return '32000';
if($step==32000) return '64000';
if($step==64000) return '125000';
if($step==125000) return '250000';
if($step==250000) return '500000';
if($step==500000) return '1000000';
if($step==1000000) {
unset($_SESSION['million_step']);
unset($_SESSION['million_left']);
unset($_SESSION['million_que']);
unset($_SESSION['million_pz']);
unset($_SESSION['million_zd']);
unset($_SESSION['million_55']);

$reit=mysql_fetch_assoc(mysql_query("SELECT * FROM `million_reit` WHERE `id_user` = '$user[id]' LIMIT 1"));

mysql_query("DELETE FROM `million_reit` WHERE `id` = '$reit[id]' LIMIT 1");
mysql_query("INSERT INTO `million_reit` (`id_user`, `balls`, `ask`, `balls_score`) values('$user[id]', '".($reit['balls']+1000000)."', '".($reit['ask']+15)."', '".($reit['balls_score']+1000000)."')");		
		
include_once '../../sys/inc/thead.php';
title();
aut();

echo "<div style='text-align:center;font-size:x-large;color=#00ee00'><b>Поздравляем!!!!<br/>
Теперь Вы настоящий миллионер))))))))))))))
</b></div>
<a href='?begin=true'>Сыграть еще</a>";
include '../../sys/inc/tfoot.php';
}
}
$set['title']='Кто хочет стать миллионером';
/*********************/
if(!empty($_SESSION['million_step'])){
	$step=$_SESSION['million_step'];
	if(isset($_GET['ask']) && empty($_SESSION['million_left'])){
		$_SESSION['million_left']=time();
		$ques=@mysql_result(mysql_query("SELECT COUNT(*) FROM `million_q` WHERE `step`='$step'"),0)+0;
		$rnd=rand(0,$ques-1);
		$_SESSION['million_que']=@mysql_result(mysql_query("SELECT `id` FROM `million_q` WHERE `step`='$step' LIMIT $rnd, 1"),0);
		header('Location:../million/');
		exit();
		}
	if(!empty($_SESSION['million_left'])){
		if($_SESSION['million_left']<time()-60){
			unset($_SESSION['million_step']);
			unset($_SESSION['million_left']);
			unset($_SESSION['million_que']);
			unset($_SESSION['million_pz']);
				unset($_SESSION['million_zd']);
				unset($_SESSION['million_55']);
				include '../../sys/inc/thead.php';
			title();
			aut();
			echo "
			<font color='#ff0000'><b>К сожалению время вышло((</b></font><br/>
			<a href='?'>Сыграть еще</a>
			";
			
			include '../../sys/inc/tfoot.php';
			}
		/**************/
		if(isset($_GET['ans'])){
			if(@mysql_result(mysql_query("SELECT * FROM `million_ans` WHERE `id_q`='$_SESSION[million_que]' AND `id`='$_GET[ans]' AND `write`='+'"),0)){
				/***WIN****/
				$next=million_next($step);
				$_SESSION['million_step']=$next;
				unset($_SESSION['million_left']);
				unset($_SESSION['million_que']);
				include '../../sys/inc/thead.php';
				title();
				aut();
				
				echo "<div class='p_m'>";
				echo "<span class='on'>ПРАВИЛЬНО</span><br/>\n";
				echo "Ваш текущий выйграш составляет $step рублей\n";
				echo "</div>";
				
				echo "<div class='menu'>";
				echo "<a href='?'>Продолжить</a>\n";
				echo "</div>";
				include '../../sys/inc/tfoot.php'; exit();
				/************/
				}else{
				/***LOSE***/
				
				if($step==100) {
				$ask=0;
				$cash=0;
				}
				if($step==200) {
				$ask=1;
				$cash=100;
				}
				if($step==300) {
				$ask=2;
				$cash=200;
				}
				if($step==500) {
				$ask=3;
				$cash=300;
				}
				if($step==1000) {
				$ask=4;
				$cash=500;
				}
				if($step==2000) {
				$ask=5;
				$cash=1000;
				}
				if($step==4000) {
				$ask=6;
				$cash=2000;
				}
				if($step==8000) {
				$ask=7;
				$cash=4000;
				}
				if($step==16000) {
				$ask=8;
				$cash=8000;
				}
				if($step==32000) {
				$ask=9;
				$cash=16000;
				}
				if($step==64000) {
				$ask=10;
				$cash=32000;
				}
				if($step==125000) {
				$ask=11;
				$cash=64000;
				}
				if($step==250000) {
				$ask=12;
				$cash=125000;
				}
				if($step==500000) {
				$ask=13;
				$cash=250000;
				}
				
				
				$reit=mysql_fetch_assoc(mysql_query("SELECT * FROM `million_reit` WHERE `id_user` = '$user[id]' LIMIT 1"));
				
				mysql_query("DELETE FROM `million_reit` WHERE `id` = '$reit[id]' LIMIT 1");
				mysql_query("INSERT INTO `million_reit` (`id_user`, `balls`, `ask`, `balls_score`) values('$user[id]', '".($reit['balls']+$cash)."', '".($reit['ask']+$ask)."', '".($reit['balls_score']+$cash)."')");

				unset($_SESSION['million_step']);
				unset($_SESSION['million_left']);
				unset($_SESSION['million_que']);
				
				unset($_SESSION['million_pz']);
				unset($_SESSION['million_zd']);
				unset($_SESSION['million_55']);
				
				include '../../sys/inc/thead.php';
				title();
				aut();
				echo "<span class='off'>НЕВЕРНО</span><br/>\n";
				echo "Ваш текущий выйграш составляет $cash рублей.<br/>
				<a href='?'>Сыграть еще</a>";
				include '../../sys/inc/tfoot.php'; exit();
				/***********/
				}
			}
		/***************/
		if(isset($_GET['help'])){
			$help=$_GET['help'];
			if($help=='zd' && empty($_SESSION['million_zd'])){
				$_SESSION['million_zd']='done';
				$_SESSION['million_left']+=300;
				$_SESSION['million_do_zd']='yes';
				}
			if($help=='pz' && empty($_SESSION['million_pz'])){
				$_SESSION['million_pz']='done';
				$q=mysql_query("SELECT * FROM `million_ans` WHERE `id_q`='$_SESSION[million_que]' ORDER BY `write` LIMIT 0, 4");
				echo mysql_error();
				while($an=mysql_fetch_array($q)){
					$nx[]=$an['ans'];
					}
					//echo $nx[0];
				$ob=100;
				$nxp[0]=rand(40,100);
				$ob-=$nxp[0];
				$nxp[1]=rand(0,$ob);
				$ob-=$nxp[1];
				$nxp[2]=rand(0,$ob);
				$ob-=$nxp[2];
				$nxp[3]=rand(0,$ob);
				while(!($nxp[0]>=$nxp[1] && $nxp[1]>=$nxp[2] && $nxp[2]>=$nxp[3])){
					if($nxp[0]<$nxp[1]){
						$nt=$nx[0];
						$nx[0]=$nx[1];
						$nx[1]=$nt;
						$nt=$nxp[0];
						$nxp[0]=$nxp[1];
						$nxp[1]=$nt;
						}
					if($nxp[1]<$nxp[2]){
						$nt=$nx[1];
						$nx[1]=$nx[2];
						$nx[2]=$nt;
						$nt=$nxp[1];
						$nxp[1]=$nxp[2];
						$nxp[2]=$nt;
						}
					if($nxp[2]<$nxp[3]){
						$nt=$nx[2];
						$nx[0+2]=$nx[3];
						$nx[3]=$nt;
						$nt=$nxp[2];
						$nxp[2]=$nxp[3];
						$nxp[3]=$nt;
						}
					}
				$_SESSION['pzz']="<b>Помощь зала:</b><br/>
				1. $nx[0] - $nxp[0] %<br/>
				2. $nx[1] - $nxp[1] %<br/>
				3. $nx[2] - $nxp[2] %<br/>
				4. $nx[3] - $nxp[3] %<br/>
				";
				$_SESSION['million_do_pz']='yes';
				}
			if($help=='55' && empty($_SESSION['million_55'])){
				$_SESSION['million_55']='done';
				$q=mysql_query("SELECT * FROM `million_ans` WHERE `id_q`='$_SESSION[million_que]' AND `write` = '-' LIMIT 3");
				
				while($an=mysql_fetch_array($q)){
				 $rand1[]=$an['id'];
				}
				 $rand1=$rand1[rand(1,3)];

				$q=mysql_query("SELECT * FROM `million_ans` WHERE `id_q`='$_SESSION[million_que]' LIMIT 4");

				$h50='<table><tr>';
				while($an=mysql_fetch_array($q)){
					$v++;
					$h50.='<td>';
					     if ($an['write']='-') $i++;
					if($an['write']=='-' && $x<2 && $rand1!=$an['id']){
						$h50.="<font color='#660000'>$an[ans]</font><br/>";
						$x++;
						} else
					$h50.="<a href='?ans=$an[id]'>$an[ans]</a><br/>";
					$h50.='</td>';
					if($v==2) $h50.='</tr><tr>';
					}
				$h50.='</tr></table>';
				
				$_SESSION['h50']=$h50;
				$_SESSION['million_do_55']='yes';
				}
			}
		/**************/
		include '../../sys/inc/thead.php';
		title();
		aut();
		$left=$_SESSION['million_left']-(time()-60);
		$ddd=rand(10000,99999);
		$question=mysql_result(mysql_query("SELECT `text` FROM `million_q` WHERE `id`='$_SESSION[million_que]'"),0);
		echo "<style text='text/css'>
		.help{border:1px #00dd00 solid;margin:2px;padding:2px;color:#00dd00;}
		</style>";
		if(!empty($_SESSION['million_do_zd'])){
			echo "<div class='help'>
			<b>Звонок другу:</b><br/>
			Подсказка заключается в том, что вместо 60 сек. Вам дается 5 минут на то чтоб найти правильный ответ в любом доступном для Вас источнике.
			</div>";
			}
		if(!empty($_SESSION['million_do_pz'])){
			echo "<div class='help'>$_SESSION[pzz]</div>";
			}
		echo "Осталось времени: $left (<a href='?rnd=$ddd'>Обновить</a>)<br/>
		<b>$question</b><br/><br/>";
		if(!empty($_SESSION['million_do_55'])) echo $_SESSION['h50'];
		else{
		$q=mysql_query("SELECT * FROM `million_ans` WHERE `id_q`='$_SESSION[million_que]'");
		echo "<table><tr>";
		$v=0;
		while($an=mysql_fetch_array($q)){
			$v++;
			if ($user['id']==1 && $an['write']=='+') echo "<td><a href='?ans=$an[id]'><font color='green'>$an[ans]</font></a>(+)<br/></td>";
			else echo "<td><a href='?ans=$an[id]'>$an[ans]</a><br/></td>";
			if($v==2) echo '</tr><tr>';
			}
		echo '</tr></table>';
		}
		
		echo "<div class='foot'>";
		if(empty($_SESSION['million_zd'])) echo "<a href='?help=zd'>Звонок другу</a>";
		else echo "<font color='#990000'>Звонок другу</font>";
		echo "\n|\n";
		if(empty($_SESSION['million_55'])) echo "<a href='?help=55'>50 x 50</a>"; else echo "<font color='#990000'>50 x 50</font>";
		echo "\n|\n";
		if(empty($_SESSION['million_pz'])) echo "<a href='?help=pz'>Помощь зала</a>";
		else echo "<font color='#990000'>Помощь зала</font>";
		echo "</div>";
		include '../../sys/inc/tfoot.php';		
		}
	unset($_SESSION['million_do_zd']);
	unset($_SESSION['million_do_pz']);
	unset($_SESSION['million_do_55']);
	
	include '../../sys/inc/thead.php';
	title();
	aut();
	
	echo "<div class='p_m'>"; 
	echo "Итак, вопрос на $step рублей. Время на раздумие 60 секунд.<br/>
	Вы готовы?\n";
	echo "</div>";
	
	echo "<div class='menu'>";
	echo "<a href='?ask=true'>Перейти к вопросу</a>\n";
	echo "</div>";
	include '../../sys/inc/tfoot.php';
	}
if(isset($_GET['begin'])){
	$_SESSION['million_step']='100';
	header('Location:../million/');
	exit();
	}
/*********************/
$set['title']='Кто хочет стать миллионером';
include '../../sys/inc/thead.php';
title();
aut();
echo "<div style='text-align:center;font-size:large;'><b>Добро пожаловать в игру!</b></div>";

echo "<div class='menu'>";
echo "<a href='?begin=true'>Начать игру</a><br/>\n";
echo "<a href='reit.php'>Рейтинг игроков</a><br/>\n";
echo "<a href='balls.php'>Обналичить деньги</a>\n";
echo "</div>";
include '../../sys/inc/tfoot.php';
?>