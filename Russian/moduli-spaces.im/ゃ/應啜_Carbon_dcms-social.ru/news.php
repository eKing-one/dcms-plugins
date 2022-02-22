<?
$q=mysql_query("SELECT * FROM `news` WHERE `main_time` > '".time()."' ORDER BY `id` DESC LIMIT 1");
if (mysql_num_rows($q)==1)
{
$news = mysql_fetch_array($q);

?>
<table width='100%' cellspacing="0" cellpadding="0">
<tr>
<td id="content" width="50%" align="center"><div align="center">
              <div id="content_top_bar">
                <div id="content_top">
                  <div id="content_right_top"></div>
                </div>
              </div>
            </div>
            <table width="80%" border="0" align="center" cellpadding="0" cellspacing="0" class="content">
              <tbody><tr>
                <td width="203" colspan="3"><div id="pathway">
                <a href='/news/'><?echo $news['title'];?></a></span>
</div>
    <div id="main_content">
	</div>
<div class='news'>
<?
echo trim(br(bbcode(smiles(links(stripcslashes(htmlspecialchars($news['msg'])))))))."<br />\n";
if ($news['link']!=NULL)echo "<a href='$news[link]'>Подробности</a><br />\n";
echo "<a href='/news/komm.php?id=$news[id]'>Комментарии</a> (".mysql_result(mysql_query("SELECT COUNT(*) FROM `news_komm` WHERE `id_news` = '$news[id]'"),0).")<br />\n";

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
			</tr>
	</table>
<?

}
?>