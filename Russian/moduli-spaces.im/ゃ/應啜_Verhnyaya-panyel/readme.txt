###Скрипт###
Верхняя панель навигации от Killer

###Описание###
Вверху появляется панелька, которая имеет четыре ссылки: старт (моя страничка), почта, журнал и лента.
Пользователь может настроить эту панельку на свой вкус. Он может:
- Изменять фон панельки
- Изменять цвет ссылок
- Изменять цвет разделителей
- Изменять цвет активной ссылки
- Изменять цвет фона активной ссылки
- Изменять цвет уведомлений
- Изменять размер шрифта (большой/маленький)
- Изменять отображение пунктов панели (иконки/слова/буквы)
- Выбирать набор иконок (серые/синие/зеленые/розовые/фиолетовые/красные/желтые)
[+] В базе имеется 139 цветов
[+] Всего юзер может выбрать набор иконок, который состоит из 56 иконок! (вэб+вап)
[+] Для тех, кто с телефона, будут отображаться маленькие иконки (16х16)

1) Файлы settings_panel.php, settings_panel_style.php и settings_panel_icons.php закинуть в корень сайта

2) Залить таблы
CREATE TABLE `colors_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `color` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=142 DEFAULT CHARSET=utf8;
INSERT INTO `colors_list` (`id`, `color`, `name`) VALUES (3,'800000','maroon'),(4,'8B0000','darkred'),(5,'B22222','firebrick'),(6,'ff0000','red'),(7,'FA8072','salmon'),(8,'ff6347','tomato'),(9,'ff7f50','coral'),(10,'ff4500','orangered'),(11,'d2691e','chocolate'),(12,'f4a460','sandybrown'),(13,'ff8c00','darkorange'),(14,'ffa500','orange'),(15,'b8860b','darkgoldenron'),(16,'daa520','goldenrod'),(17,'ffd700','gold'),(18,'808000','olive'),(19,'ffff00','yellow'),(20,'9acd32','yellowgreen'),(21,'adff2f','greenyellow'),(22,'7fff00','chartreuse'),(23,'7cfc00','lawngreen'),(24,'008000','green'),(25,'00ff00','lime'),(26,'32cd32','limegreen'),(27,'00ff7f','springgreen'),(28,'00fa9a','mediumspringgreen'),(29,'40e0d0','turquiose'),(30,'20B2AA','lightseagreen'),(31,'48d1cc','mediumturquoise'),(32,'008080','teal'),(33,'008B8B','darkcyan'),(34,'00FFFF','aqua'),(35,'00ffff','cyan'),(36,'00ced1','darkturquoise'),(37,'00bfff','deepskyblue'),(38,'1e90ff','dodgerblue'),(39,'4169e1','royalblue'),(40,'000080','navy'),(41,'00008b','darkblue'),(42,'0000cd','mediumblue'),(43,'0000ff','blue'),(44,'8a2be2','blueviolet'),(45,'9932cc','darkorchid'),(46,'9400d3','darkviolet'),(47,'800080','purple'),(48,'8b008b','darkmagenta'),(49,'ff00ff','fuchsia'),(50,'c71585','mediumvioletred'),(51,'ff1493','deeppink'),(52,'ff69b4','hotpink'),(53,'dc143c','crimson'),(54,'a52a2a','brown'),(55,'cd5c5c','indianred'),(56,'bc8f8f','rosybrown'),(57,'f08080','lightcoral'),(58,'fffafa','snow'),(59,'ffe4e1','mistyrose'),(60,'e9967a','darksalmon'),(61,'ffa07a','lightsalom'),(62,'a0552d','sienna'),(63,'FFF5EE','seashell'),(64,'8b4513','saddlebrown'),(65,'ffda89','peachpuff'),(66,'cd853f','peru'),(67,'faf0e6','linen'),(68,'ffe4c4','bisque'),(69,'DEB887','burlywood'),(70,'D2B48C','tan'),(71,'FAEBD7','antiquewhite'),(72,'FFDEAD','navajowhite'),(73,'FFEBCD','blanchedalmond'),(74,'FFEFD5','papayawhip'),(75,'FFE4B5','moccasin'),(76,'F5DEB3','wheat'),(77,'FDF5E6','oldlace'),(78,'FFFAF0','floralwhite'),(79,'FFF8DC','cornsilk'),(80,'F0E68C','khaki'),(81,'FFFACD','lemonchiffon'),(82,'EEE8AA','palegoldenrod'),(83,'BDB76B','darkkhaki'),(84,'F5F5DC','beige'),(85,'FAFAD2','lightgoldenrodyellow'),(86,'FFFFE0','lightyellow'),(87,'FFFFF0','ivory'),(88,'6B8E23','olivedrab'),(89,'556B2F','darkolivegreen'),(90,'8FBC8F','darkseagreen'),(91,'006400','darkgreen'),(92,'228B22','forestgreen'),(93,'90EE90','lightgreen'),(94,'98FB98','palegreen'),(95,'F0FFF0','honeydew'),(96,'2E8B57','seagreen'),(97,'3CB371','mediumseagreen'),(98,'F5FFFA','mintcream'),(99,'66CDAA','mediumaquamarine'),(100,'7FFFD4','aquamarine'),(101,'2F4F4F','darkslategray'),(102,'AFEEEE','paleturquoise'),(103,'E0FFFF','lightcyan'),(104,'F0FFFF','azure'),(105,'5F9EA0','cadetblue'),(106,'B0E0E6','powderblue'),(107,'ADD8E6','lightblue'),(108,'87CEEB','skyblue'),(109,'87CEFA','lightskyblue'),(110,'4682B4','steelblue'),(111,'F0F8FF','aliceblue'),(112,'708090','slategray'),(113,'778899','lightslategray'),(114,'B0C4DE','lightsteelblue'),(115,'6495ED','cornflowerblue'),(116,'E6E6FA','lavender'),(117,'F8F8FF','ghostwhite'),(118,'191970','midnightblue'),(119,'6A5ACD','slateblue'),(120,'483D8B','darkslateblue'),(121,'7B68EE','mediumslateblue'),(122,'9370DB','mediumpurple'),(123,'4B0082','indigo'),(124,'BA55D3','mediumorchid'),(125,'DDA0DD','plum'),(126,'EE82EE','violet'),(127,'D8BFD8','thistle'),(128,'DA70D6','orchid'),(129,'FFF0F5','lavenderblush'),(130,'DB7093','palevioletred'),(131,'FFC0CB','pink'),(132,'FFB6C1','lightpink'),(133,'000000','black'),(134,'696969','dimgray'),(135,'808080','gray'),(136,'A9A9A9','darkgray'),(137,'C0C0C0','silver'),(138,'D3D3D3','LightGrey'),(139,'DCDCDC','gainsboro'),(140,'F5F5F5','whitesmoke'),(141,'FFFFFF','white');
ALTER TABLE `user`
  ADD `panel_newevent` varchar(6) NOT NULL DEFAULT 'ffff00',
  ADD `panel_fon` varchar(6) NOT NULL DEFAULT '677fb3',
  ADD `panel_link` varchar(6) NOT NULL DEFAULT 'ffffff',
  ADD `panel_focus_link` varchar(6) NOT NULL DEFAULT '3b5998',
  ADD `panel_focus_fon` varchar(6) NOT NULL DEFAULT 'ffffff',
  ADD `panel_border` varchar(6) NOT NULL DEFAULT '445988',
  ADD `panel` enum('1','2','3') NOT NULL DEFAULT '1',
  ADD `panel_font_size` enum('small','medium') NOT NULL DEFAULT 'small',
  ADD `panel_icons_list` varchar(128) NOT NULL DEFAULT 'none';

  if (isset($_SERVER["HTTP_USER_AGENT"]) && preg_match('#up-browser|blackberry|windows ce|symbian|palm|nokia#i', $_SERVER["HTTP_USER_AGENT"]))
	$webbrowser=false;
	elseif (isset($_SERVER["HTTP_USER_AGENT"]) && (preg_match('#windows#i', $_SERVER["HTTP_USER_AGENT"]) ||preg_match('#linux#i', $_SERVER["HTTP_USER_AGENT"]) ||preg_match('#bsd#i', $_SERVER["HTTP_USER_AGENT"]) ||preg_match('#x11#i', $_SERVER["HTTP_USER_AGENT"]) ||preg_match('#unix#i', $_SERVER["HTTP_USER_AGENT"]) ||preg_match('#macos#i', $_SERVER["HTTP_USER_AGENT"]) ||preg_match('#macintosh#i', $_SERVER["HTTP_USER_AGENT"])))
	$webbrowser=true;else $webbrowser=false; // определение типа браузера
	if ($webbrowser == true)
	{
		$web_panel = true;
	} else $web_panel = false;
	
$color = array();
$color['newevent'] = '#'.$user['panel_newevent'];
$color['fon'] = '#'.$user['panel_fon'];
$color['link'] = '#'.$user['panel_link'];
$color['focus_fon'] = '#'.$user['panel_focus_fon'];
$color['focus_link'] = '#'.$user['panel_focus_link'];
$color['border'] = '#'.$user['panel_border'];
$panel = array();
$panel['font_size'] = $user['panel_font_size'];
if ($user['panel']==1) {
$panel['home']="<img class='icon' src='/style/panel/home_".$user['panel_icons_list'].($web_panel==false?"_16x16":NULL).".png' alt='Старт'>";
$panel['mail']="<img class='icon' src='/style/panel/mail_".$user['panel_icons_list'].($web_panel==false?"_16x16":NULL).".png' alt='Почта'>";
$panel['journal']="<img class='icon' src='/style/panel/journal_".$user['panel_icons_list'].($web_panel==false?"_16x16":NULL).".png' alt='Журнал'>";
$panel['lenta']="<img class='icon' src='/style/panel/lenta_".$user['panel_icons_list'].($web_panel==false?"_16x16":NULL).".png' alt='Лента'>";
} elseif ($user['panel']==2) {
$panel['home']='Старт';
$panel['mail']='Почта';
$panel['journal']='Жур';
$panel['lenta']='Лента';
} else {
$panel['home']='С';
$panel['mail']='Пч';
$panel['journal']='Ж';
$panel['lenta']='Л';
}
?>
<style>
a.top_menu_link {
text-align: center;
display: block;
padding: 3px;
}
tbody {
display: table-row-group;
vertical-align: middle;
border-color: inherit;
}
table {
border-collapse: separate;
border-spacing: 2px;
border-color: gray;
}
#navi {
color: <? echo $color['border'];?>;
font-size: <? echo $panel['font_size'];?>;
padding:4px 2px;
background-color:<? echo $color['fon'];?>;
border:1px solid #abbefb;
}
#navi a:link,#navi a:visited {
color:<? echo $color['link'];?>;
text-decoration:none;
font-size: <? echo $panel['font_size'];?>;
}
#navi a:focus,#navi a:hover,#navi a:active {
color:<? echo $color['focus_link'];?>;
background-color:<? echo $color['focus_fon'];?>;
}
#time {
background-color:#ccc;
color:#000;
font-size:medium;
text-align:right;
padding:0px 10px 0px 0px;
}
.newevent {
color:<? echo $color['newevent'];?>;
font-size: <? echo $panel['font_size'];?>;
vertical-align:bottom;
}
</style>
<div id="navi" >
		
	
			<table style="width:100%" cellspacing="0" cellpadding="0"><tr>
				<td style="vertical-align:top;width:24%;border-right:solid; border-width:1px">
					<a class="top_menu_link user_color_link" href="/info.php" title="Старт">
					
						<? echo $panel['home'];?>
					
					</a>
				</td>
	
				<td style="vertical-align:top;width:25%;border-right:solid;border-width:1px;">
					<a class="top_menu_link user_color_link" href="/mail" title="Почта">
					
						<? echo $panel['mail'];?>
						<? if ($mail!=0)echo "<span class='newevent'>$mail</span>\n";?>
					
					</a>
				</td>
	
				<td style="vertical-align:top;width:25%;border-right:solid;border-width:1px;">
					<a class="top_menu_link user_color_link" href="/jurnal" title="Журнал">
					
						<? echo $panel['journal'];?>
						<? if ($jurnal!=0)echo "<span class='newevent'>$jurnal</span>\n";?>
					
					</a>
				</td>
	
				<td style="vertical-align:top;width:24%;border-right:solid;border-width:0px;">
					<a class="top_menu_link user_color_link" href="/lenta" title="Лента">
					
						<? echo $panel['lenta'];?>
						<? if ($lenta!=0)echo "<span class='newevent'>$lenta</span>\n";?>
					
					</a>
				</td>
	
			</tr></table>
	
		
	
		
		</div>
	<?

4) В файле settings.php прописать код
echo "&raquo; <a href='/settings_panel.php'>Настройки панели</a><br />\n";