<?
function medal($user=NULL)
{
$ank=mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id` = $user LIMIT 1"));

if ($ank['ank_d_r']>=19 && $ank['ank_m_r']==1){echo "<img src='/style/zadiak/11.gif' alt=''>";}
elseif ($ank['ank_d_r']<=19 && $ank['ank_m_r']==2){echo "<img src='/style/zadiak/11.gif' alt=''>";}
elseif ($ank['ank_d_r']>=18 && $ank['ank_m_r']==2){echo "<img src='/style/zadiak/12.gif' alt=''>";}
elseif ($ank['ank_d_r']<=21 && $ank['ank_m_r']==3){echo "<img src='/style/zadiak/12.gif' alt=''>";}
elseif ($ank['ank_d_r']>=20 && $ank['ank_m_r']==3){echo "<img src='/style/zadiak/1.gif' alt=''>";}
elseif ($ank['ank_d_r']<=21 && $ank['ank_m_r']==4){echo "<img src='/style/zadiak/1.gif' alt=''>";}
elseif ($ank['ank_d_r']>=20 && $ank['ank_m_r']==4){echo "<img src='/style/zadiak/2.gif' alt=''>";}
elseif ($ank['ank_d_r']<=21 && $ank['ank_m_r']==5){echo "<img src='/style/zadiak/2.gif' alt=''>";}
elseif ($ank['ank_d_r']>=20 && $ank['ank_m_r']==5){echo "<img src='/style/zadiak/3.gif' alt=''>";}
elseif ($ank['ank_d_r']<=22 && $ank['ank_m_r']==6){echo "<img src='/style/zadiak/3.gif' alt=''>";}
elseif ($ank['ank_d_r']>=21 && $ank['ank_m_r']==6){echo "<img src='/style/zadiak/4.gif' alt=''>";}
elseif ($ank['ank_d_r']<=22 && $ank['ank_m_r']==7){echo "<img src='/style/zadiak/4.gif' alt=''>";}
elseif ($ank['ank_d_r']>=23 && $ank['ank_m_r']==7){echo "<img src='/style/zadiak/5.gif' alt=''>";}
elseif ($ank['ank_d_r']<=22 && $ank['ank_m_r']==8){echo "<img src='/style/zadiak/5.gif' alt=''>";}
elseif ($ank['ank_d_r']>=22 && $ank['ank_m_r']==8){echo "<img src='/style/zadiak/6.gif' alt=''>";}
elseif ($ank['ank_d_r']<=23 && $ank['ank_m_r']==9){echo "<img src='/style/zadiak/6.gif' alt=''>";}
elseif ($ank['ank_d_r']>=22 && $ank['ank_m_r']==9){echo "<img src='/style/zadiak/7.gif' alt=''>";}
elseif ($ank['ank_d_r']<=23 && $ank['ank_m_r']==10){echo "<img src='/style/zadiak/7.gif' alt=''>";}
elseif ($ank['ank_d_r']>=22 && $ank['ank_m_r']==10){echo "<img src='/style/zadiak/8.gif' alt=''>";}
elseif ($ank['ank_d_r']<=22 && $ank['ank_m_r']==11){echo "<img src='/style/zadiak/8.gif' alt=''>";}
elseif ($ank['ank_d_r']>=21 && $ank['ank_m_r']==11){echo "<img src='/style/zadiak/9.gif' alt=''>";}
elseif ($ank['ank_d_r']<=22 && $ank['ank_m_r']==12){echo "<img src='/style/zadiak/9.gif' alt=''>";}
elseif ($ank['ank_d_r']>=21 && $ank['ank_m_r']==12){echo "<img src='/style/zadiak/10.gif' alt=''>";}
elseif ($ank['ank_d_r']<=20 && $ank['ank_m_r']==1){echo "<img src='/style/zadiak/10.gif' alt=''>";}


if ($ank['rating']>=6 && $ank['rating']<=11){echo " <img src='/style/medal/1.png' alt=''/>";}

if ($ank['rating']>=12 && $ank['rating']<=19){echo " <img src='/style/medal/2.png' alt=''/>";}

if ($ank['rating']>=20 && $ank['rating']<=27){echo " <img src='/style/medal/3.png' alt=''/>";}

if ($ank['rating']>=28 && $ank['rating']<=37){echo " <img src='/style/medal/4.png' alt=''/>";}

if ($ank['rating']>=38 && $ank['rating']<=47){echo " <img src='/style/medal/5.png' alt=''/>";}

if ($ank['rating']>=48 && $ank['rating']<=59){echo " <img src='/style/medal/6.png' alt=''/>";}

if ($ank['rating']>=60 && $ank['rating']<=9999999){echo " <img src='/style/medal/7.png' alt=''/>";}

}
?>





