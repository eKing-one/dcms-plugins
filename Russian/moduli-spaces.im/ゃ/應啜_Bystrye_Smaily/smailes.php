<script language="JavaScript" type="text/javascript">

                function tag(text1, text2) {

                if ((document.selection)) {

                document.message.msg.focus();

                document.message.document.selection.createRange().text = text1+document.message.document.selection.createRange().text+text2;

                } else if(document.forms['message'].elements['msg'].selectionStart!=undefined) {

                var element = document.forms['message'].elements['msg'];

                var str = element.value;

                var start = element.selectionStart;

                var length = element.selectionEnd - element.selectionStart;

                element.value = str.substr(0, start) + text1 + str.substr(start, length) + text2 + str.substr(start + length);

				document.forms['message'].elements['msg'].focus();

                } else document.message.msg.value += text1+text2;

				document.forms['message'].elements['msg'].focus();}</script>

				<?				

echo "<table width='100%'>\n";

echo "<tr>\n";



if (!isset($msg2))$msg2=NULL;

echo "<div id='comments' class='tpanel'><div class='tmenu'><a href='/plugins/smiles'>Смайлы</a></div><div class='tmenu'><a href='/plugins/rules/bb-code.php'>Теги</a></div></div>";

?>

<div style="margin:4px;">

<a href="javascript:tag('.ч', 'е.')"><img src="/style/smiles/1168.gif" alt="*" title="*"/></a>

<a href="javascript:tag('.:', '(.')"><img src="/style/smiles/1169.gif" alt="*" title="*"/></a>

<a href="javascript:tag('.:', 'D.')"><img src="/style/smiles/1170.gif" alt="*" title="*"/></a>

<a href="javascript:tag('.ми', 'г.')"><img src="/style/smiles/1173.gif" alt="*" title="*"/></a>

<a href="javascript:tag('.кр', 'ут.')"><img src="/style/smiles/1174.gif" alt="*" title="*" /></a>

<a href="javascript:tag('.сек', 'рет.')"><img src="/style/smiles/1175.gif" alt="*" title="*"/></a>

<a href="javascript:tag('.а', 'х.')"><img src="/style/smiles/1182.gif" alt="*" title="*"/></a>

<a href="javascript:tag('.сер', 'дит.')"><img src="/style/smiles/1184.gif" alt="*" title="*"/></a>

<a href="javascript:tag('=', ').')"><img src="/style/smiles/331.gif" alt="*" title="*"/></a>

</div>

<?

echo '<textarea name="msg" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);">'.$otvet.''.$msg2.'</textarea><br />';

?>  <script>    $.fx.speeds._default = 1000;    $( "#dialog" ).dialog({      autoOpen: false,      show: "blind",      hide: "explode"    });	    $( "#opener" ).click(function(){      $( "#dialog" ).dialog( "open" );	  showContent2('/ajax/php/smiles.php');      return false;    });  </script>
