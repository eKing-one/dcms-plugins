<table width='100%' cellspacing="0" cellpadding="0">
<tr>
<td width='1%'></td>

<td id="content" width="49%" align="center"><div align="center">
              <div id="content_top_bar">
                <div id="content_top">
                  <div id="content_right_top"></div>
                </div>
              </div>
            </div>
            <table width="80%" border="0" align="center" cellpadding="0" cellspacing="0" class="content">
              <tbody><tr>
                <td width="203" colspan="3"><div id="pathway">
			<span class='table_title_menu'>Новое в темах</span>
        </div>
    <div id="main_content"></div>                             
<?
$adm_add=NULL;
$adm_add2=NULL;
if (!isset($user) || $user['level']==0){
$adm_add2=' WHERE';
$q222=mysql_query("SELECT * FROM `forum_f` WHERE `adm` = '1'");
while ($adm_f = mysql_fetch_array($q222))
{
$adm_add[]="`id_forum` <> '$adm_f[id]'";
}
$adm_add2.=implode(' AND ', $adm_add);
}

$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_t`$adm_add"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
echo "<table class='post'>\n";
$q=mysql_query("SELECT * FROM `forum_t`$adm_add ORDER BY `time` DESC  LIMIT 5");
if (mysql_num_rows($q)==0) {
echo "   <tr>\n";
echo "  <td class='p_t'>\n";
echo "Нет тем\n";
echo "  </td>\n";
echo "   </tr>\n";
}
while ($them = mysql_fetch_array($q))
{


$forum=mysql_fetch_array(mysql_query("SELECT * FROM `forum_f` WHERE `id` = '$them[id_forum]' LIMIT 1"));
$razdel=mysql_fetch_array(mysql_query("SELECT * FROM `forum_r` WHERE `id` = '$them[id_razdel]' LIMIT 1"));
//$them=mysql_fetch_array(mysql_query("SELECT * FROM `forum_t` WHERE `id` = '$post[id_them]' LIMIT 1"));
$ank=mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id` = $them[id_user] LIMIT 1"));


echo "   <tr>\n";





echo "  <td class='p_t'>\n";
echo "<a href='/forum/$forum[id]/$razdel[id]/$them[id]/'>$them[name]</a> <a href='/forum/$forum[id]/$razdel[id]/$them[id]/?page=end'>(".mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_p` WHERE `id_forum` = '$forum[id]' AND `id_razdel` = '$razdel[id]' AND `id_them` = '$them[id]'"),0).")</a>\n";
echo "  </td>\n";
echo "   </tr>\n";


echo "   <tr>\n";
echo "  <td class='p_m'>\n";


echo "<a href='/forum/$forum[id]/'>$forum[name]</a> &gt; <a href='/forum/$forum[id]/$razdel[id]/'>$razdel[name]</a><br />\n";

$post1=mysql_fetch_array(mysql_query("SELECT * FROM `forum_p` WHERE `id_them` = '$them[id]' AND `id_razdel` = '$razdel[id]' AND `id_forum` = '$forum[id]' ORDER BY `time` ASC LIMIT 1"));
$ank=mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id` = $post1[id_user] LIMIT 1"));
echo "Автор: <a href='/info.php?id=$ank[id]' title='Анкета \"$ank[nick]\"'>$ank[nick]</a> (".vremja($them['time_create']).")<br />\n";

$post=mysql_fetch_array(mysql_query("SELECT * FROM `forum_p` WHERE `id_them` = '$them[id]' AND `id_razdel` = '$razdel[id]' AND `id_forum` = '$forum[id]' ORDER BY `time` DESC LIMIT 1"));
$ank2=mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id` = $post[id_user] LIMIT 1"));
echo "Посл.: <a href='/info.php?id=$ank2[id]' title='Анкета \"$ank2[nick]\"'>$ank2[nick]</a> (".vremja($post['time']).")<br />\n";
echo "  </td>\n";
echo "   </tr>\n";

}
echo "</table>\n";
?>
</div>
    </div>		</td>
			</tr>
                <tr>
             <td colspan="3">
</td>
            </tr>
         </tbody>
	</table>
<div align="center">
              <div id="content_bottom_bar">
                <div id="content_bottom">
                  <div id="content_right_bottom"></div>
                </div>
              </div>
            </div>
			</td>
<td id="content" width="47%" align="center">
<div align="center">
              <div id="content_top_bar">
                <div id="content_top">
                  <div id="content_right_top"></div>
                </div>
              </div>
            </div>
            <table class="content" width="80%" align="center" border="0" cellpadding="0" cellspacing="0">
              <tbody>
			  <tr>
                <td colspan="3">
				  <div id="pathway">
    <span class='table_title_menu'>Новые темы</span></div>
    <div id="main_content">
	</div>
<?
$adm_add=NULL;
$adm_add2=NULL;
if (!isset($user) || $user['level']==0){
$adm_add2=' WHERE';
$q222=mysql_query("SELECT * FROM `forum_f` WHERE `adm` = '1'");
while ($adm_f = mysql_fetch_array($q222))
{
$adm_add[]="`id_forum` <> '$adm_f[id]'";
}
$adm_add2.=implode(' AND ', $adm_add);
}

$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_t`$adm_add"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
echo "<table class='post'>\n";
$q=mysql_query("SELECT * FROM `forum_t`$adm_add ORDER BY `time_create` DESC  LIMIT 5");
if (mysql_num_rows($q)==0) {
echo "   <tr>\n";
echo "  <td class='p_t'>\n";
echo "Нет тем\n";
echo "  </td>\n";
echo "   </tr>\n";
}
while ($them = mysql_fetch_array($q))
{


$forum=mysql_fetch_array(mysql_query("SELECT * FROM `forum_f` WHERE `id` = '$them[id_forum]' LIMIT 1"));
$razdel=mysql_fetch_array(mysql_query("SELECT * FROM `forum_r` WHERE `id` = '$them[id_razdel]' LIMIT 1"));
//$them=mysql_fetch_array(mysql_query("SELECT * FROM `forum_t` WHERE `id` = '$post[id_them]' LIMIT 1"));
$ank=mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id` = $them[id_user] LIMIT 1"));


echo "   <tr>\n";




echo "  <td class='p_t'>\n";
echo "<a href='/forum/$forum[id]/$razdel[id]/$them[id]/'>$them[name]</a> <a href='/forum/$forum[id]/$razdel[id]/$them[id]/?page=end'>(".mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_p` WHERE `id_forum` = '$forum[id]' AND `id_razdel` = '$razdel[id]' AND `id_them` = '$them[id]'"),0).")</a>\n";
echo "  </td>\n";
echo "   </tr>\n";


echo "   <tr>\n";
echo "  <td class='p_m'>\n";


echo "<a href='/forum/$forum[id]/'>$forum[name]</a> &gt; <a href='/forum/$forum[id]/$razdel[id]/'>$razdel[name]</a><br />\n";

$post1=mysql_fetch_array(mysql_query("SELECT * FROM `forum_p` WHERE `id_them` = '$them[id]' AND `id_razdel` = '$razdel[id]' AND `id_forum` = '$forum[id]' ORDER BY `time` ASC LIMIT 1"));
$ank=mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id` = $post1[id_user] LIMIT 1"));
echo "Автор: <a href='/info.php?id=$ank[id]' title='Анкета \"$ank[nick]\"'>$ank[nick]</a> (".vremja($them['time_create']).")<br />\n";

$post=mysql_fetch_array(mysql_query("SELECT * FROM `forum_p` WHERE `id_them` = '$them[id]' AND `id_razdel` = '$razdel[id]' AND `id_forum` = '$forum[id]' ORDER BY `time` DESC LIMIT 1"));
$ank2=mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id` = $post[id_user] LIMIT 1"));
echo "Посл.: <a href='/info.php?id=$ank2[id]' title='Анкета \"$ank2[nick]\"'>$ank2[nick]</a> (".vremja($post['time']).")<br />\n";
echo "  </td>\n";
echo "   </tr>\n";

}
echo "</table>\n";
?>
</div>
    </div>		</td>
			</tr>
                <tr>
             <td colspan="3">
			 </td>
            </tr>
         </tbody>
	</table>
<div align="center">
              <div id="content_bottom_bar">
                <div id="content_bottom">
                  <div id="content_right_bottom">
				  </div>
                </div>
              </div>
            </div>
			</td>

<td width='1%'>
</td>
</tr>
</table>
