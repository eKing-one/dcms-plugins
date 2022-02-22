<?

# ALTER TABLE `user` ADD `horo` INT NOT NULL DEFAULT '1', ADD `lhoro` INT NOT NULL DEFAULT '0'
function horoskop()
{
	global $sethoroskop,$user;

	$server = "http://service.jetdir.ru/horo.php?host=$_SERVER[HTTP_HOST]";
	# Если чего-то нет, то завершаем работу.
if(!isset($user)) return;
if(!isset($user['horo'])|| !isset($user['lhoro'])) return;

 # Изменение настроек
if(isset($_GET['setsend']))
{
	if($user['horo']) $set = 0; else $set = 1;
	mysql_query("UPDATE `user` SET `horo`='$set' where `id`='$user[id]'");
	$user['horo'] = $set;
}
$sethoroskop = '<a href="?setsend"><u>'.($user['horo']?'Hе присылать':'Присылать').'</u></a> гороскоп<br />*<small>Гороскоп будет присылаться если верно заполнено поле "Дата рождения"</small><br />';
if(isset($set)) return;

# Проверка на существование папки
if(!file_exists(H.'/sys/dat/horoskop')) if(!mkdir(H.'/sys/dat/horoskop')) return;

# Если гороскоп существует, то подгружаем его, иначе просим сервер выдать нам его!
$datfile = H.'/sys/dat/horoskop/'.date("d_m_Y").'.dat';
if(!file_exists($datfile))
{
	$f = fopen($datfile,"w");
	fputs($f, base64_decode(file_get_contents($server)));
	fclose($f);
}
$datfile = unserialize(file_get_contents($datfile));
if(count($datfile)<11 || !isset($datfile['date']) || $datfile['date']!=date("dm")){ unlink(H.'/sys/dat/horoskop/'.date("d_m_Y").'.dat'); return; }
# Проверка на то, присылался ли уже гороскоп, а затем на правильность даты рождения
if($user['lhoro'] == date("dm")) return;

if( ($user['ank_d_r']>31 || $user['ank_d_r']<1) || ($user['ank_m_r']>12 || $user['ank_m_r']<1) ) return;
$d = (int)$user['ank_d_r'];
$m = (int)$user['ank_m_r'];
$flag = 0;

# Определяем знак
if(($d>=21 && $m==3) || ($d<=20 && $m==4)) $flag = 1;	// Овен
if(($d>=21 && $m==4) || ($d<=21 && $m==5)) $flag = 2;	// Телец
if(($d>=22 && $m==5) || ($d<=21 && $m==6)) $flag = 3;	// Близнецы
if(($d>=22 && $m==6) || ($d<=22 && $m==7)) $flag = 4;	// Рак
if(($d>=23 && $m==7) || ($d<=23 && $m==8)) $flag = 5;	// Лев
if(($d>=24 && $m==8) || ($d<=23 && $m==9)) $flag = 6;	// Дева
if(($d>=24 && $m==9) || ($d<=23 && $m==10)) $flag = 7;	// Весы
if(($d>=24 && $m==10) || ($d<=22 && $m==11)) $flag = 8;	// Скорпион
if(($d>=23 && $m==11) || ($d<=21 && $m==12)) $flag = 9;	// Стрелец
if(($d>=22 && $m==12) || ($d<=20 && $m==1)) $flag = 10;	// Козерог
if(($d>=21 && $m==1) || ($d<=18 && $m==2)) $flag = 11;	// Водолей
if(($d>=19 && $m==2) || ($d<=20 && $m==3)) $flag = 12;	// Рыбы

$msg = "Гороскоп на сегодня:\r\n".$datfile[$flag];
# Посылаем письмо и делаем отметку о приеме
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$user[id]', '".my_esc($msg)."', '".time()."')");
mysql_query("UPDATE `user` SET `lhoro`='".date("dm")."' where `id`='$user[id]'");
}










?>