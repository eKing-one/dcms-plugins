<?

include_once 'sys/inc/start.php';
include_once 'sys/inc/compress.php';
include_once 'sys/inc/sess.php';
include_once 'sys/inc/home.php';
include_once 'sys/inc/settings.php';
include_once 'sys/inc/db_connect.php';
include_once 'sys/inc/ipua.php';
include_once 'sys/inc/fnc.php';
include_once 'sys/inc/user.php';
if(!isset($user))
{
}

if (isset($user))$ank['id']=$user['id'];
if (isset($_GET['id']))$ank['id']=intval($_GET['id']);






?>
<script>
 $(
  function(){
    $("body").addClass("js");
  
    $(".link_nav").click(
      function(){
        $("body").toggleClass("mobile_nav");
		
	}
    );
  }
)



$(document).ready(function()
{
$("#contentbox").keyup(function()
{
var box=$(this).val();
var main = box.length *100;
var value= (main / 145);
var count= 145 - box.length;

if(box.length <= 145)
{
$('#count').html(count);
$('#bar').animate(
{
"width": value+'%',
}, 1);
}
else
{
alert(' Full ');
}
return false;
});

});
  </script>
  
 
<div class="jsonly">
<header class="header clearfix">
<nav class="menu_main">
<?
echo '<a class="link_nav" title="Развернуть/Свернуть"></a>';
?>
<ul id="go_nav">

<li>
<a href="/" title="На главную"><img src="/panell/img/logo.png"></a>
</li>


<?
include_once 'panell/incpanel.php';
?>



</nav>
</header>
</div>
<noscript>
<div class="warning">Боковая панель не может работать когда отключен JavaScript</div>
</noscript>
<?