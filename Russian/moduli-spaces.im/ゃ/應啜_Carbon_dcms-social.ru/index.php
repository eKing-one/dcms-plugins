<?php
defined( '_VALID_MOS' ) or die( 'Restricted access' );
// needed to seperate the ISO number from the language file constant _ISO
$iso = explode( '=', _ISO );
// xml prolog
echo '<?xml version="1.0" encoding="'. $iso[1] .'"?' .'>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php mosShowHead(); ?>
<?php
if ( $my->id ) {
	initEditor();
}
$collspan_offset = ( mosCountModules( 'right', 'left' ) + mosCountModules( 'user2' ) ) ? 2 : 1;
//script to determine which div setup for layout to use based on module configuration
$user1 = 0;
$user2 = 0;
$colspan = 0;
$right = 0;
$left = 0;
$banner = 0;
$user3 = 0;
$user4 = 0;
$top = 0;
// banner combos
//user1 combos
if ( mosCountModules( 'user1' ) + mosCountModules( 'user2' ) == 2) {
	$user1 = 2;
	$user2 = 2;
	$colspan = 3;
} elseif ( mosCountModules( 'user1' ) == 1 ) {
	$user1 = 1;
	$colspan = 1;
} elseif ( mosCountModules( 'user2' ) == 1 ) {
	$user2 = 1;
	$colspan = 1;
}
//banner based combos
if ( mosCountModules( 'banner' ) and ( empty( $_REQUEST['task'] ) || $_REQUEST['task'] != 'edit' ) ) {
	$banner = 1;
}
//right based combos
if ( mosCountModules( 'right' ) and ( empty( $_REQUEST['task'] ) || $_REQUEST['task'] != 'edit' ) ) {
	$right = 1;
}
//left based combos
if ( mosCountModules( 'left' ) and ( empty( $_REQUEST['task'] ) || $_REQUEST['task'] != 'edit' ) ) {
      $left = 1;
}
//top based combos
if ( mosCountModules( 'top' ) and ( empty( $_REQUEST['task'] ) || $_REQUEST['task'] != 'edit' ) ) {
      $top = 1;
}
//user4 based combos
if ( mosCountModules( 'user4' ) and ( empty( $_REQUEST['task'] ) || $_REQUEST['task'] != 'edit' ) ) {
      $user4 = 1;
}
//user3 based combos
if ( mosCountModules( 'user3' ) and ( empty( $_REQUEST['task'] ) || $_REQUEST['task'] != 'edit' ) ) {
      $user3 = 1;
}
?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php echo "<link rel=\"stylesheet\" href=\"$GLOBALS[mosConfig_live_site]/templates/$GLOBALS[cur_template]/css/template_css.css\" type=\"text/css\"/>" ; ?><?php echo "<link rel=\"shortcut icon\" href=\"$GLOBALS[mosConfig_live_site]/images/favicon.ico\" />" ; ?>
</head>
<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><div id="header">
        <?php
							if ( $banner > 0 ) {
		  				?>
        <div id="banner">
          <div id="banner_inner">
            <?php mosLoadModules( "banner", -1 ); ?>
          </div>
        </div>
        <?php
		  			}
		  			?>
      </div></td>
  </tr>
  <?php
							if ( $user3 > 0 ) {
		  				?>
  <tr>
    <td><div id="top_menu">
        <div id="top_menu_inner">
          <?php mosLoadModules ( 'user3' ); ?>
        </div>
      </div></td>
  </tr>
  <?php
		  			}
		  			?>
  <?php
							if ( $user4 > 0 ) {
		  				?>
  <tr>
    <td><div id="top_menu_top_two">
        <div id="top_menu_top_two_inner">
          <div id="search_inner">
            <form action="index.php" method="post">
              <div align="center">
                <input class="inputbox" type="text" name="searchword" size="15" value="<?php echo _SEARCH_BOX; ?>"  onblur="if(this.value=='') this.value='<?php echo _SEARCH_BOX; ?>';" onfocus="if(this.value=='<?php echo _SEARCH_BOX; ?>') this.value='';" />
                <input type="hidden" name="option" value="search" />
              </div>
            </form>
          </div>
        </div>
      </div></td>
  </tr>
  <?php
		  			}
		  			?>
  <?php
							if ( $top > 0 ) {
		  				?>
  <tr>
    <td><div id="top_menu_top">
        <div id="top_menu_top_inner">
          <?php mosLoadModules ( 'top' ); ?>
        </div>
      </div></td>
  </tr>
  <?php
		  			}
		  			?>
  <tr>
    <td id="content_outer" valign="top"><table border="0" align="center" cellpadding="0" cellspacing="0" class="content_table">
        <tr valign="top">
          <?php
							if ( $left > 0 ) {
		  				?>
          <td><div id="left_outer">
              <div id="left_top"></div>
              <div id="left_inner_float">
                <div id="left_inner">
                  <?php mosLoadModules ( 'left', -2 ); ?>
                </div>
              </div>
              <div id="left_bottom"></div>
            </div></td>
          <?php
		  			}
		  			?>
          <td align="center" width="100%" id="content"><div align="center">
              <div id="content_top_bar">
                <div id="content_top">
                  <div id="content_right_top"></div>
                </div>
              </div>
            </div>
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="content">
              <tr>
                <td colspan="<?php echo $colspan; ?>"><div id="pathway">
                    <div id="pathway_text">
                      <?php mosPathWay(); ?>
                    </div>
                  </div>
                  <div id="main_content">
                    <?php mosMainBody(); ?>
                  </div></td>
              </tr>
              <?php
								if ($colspan > 0) {
								?>
              <tr valign="top">
                <?php
				  					if ( $user1 > 0 ) {
				  						?>
                <td width="50%"><div id="user1_outer">
                    <div class="user1_inner">
                      <?php mosLoadModules ( 'user1', -2 ); ?>
                    </div>
                  </div></td>
                <?php
				  					}
				  					if ( $colspan == 3) {
										 ?>
                <?php
										}
				  					if ( $user2 > 0 ) {
				  						?>
                <td width="50%"><div id="user2_outer">
                    <div class="user2_inner">
                      <?php mosLoadModules ( 'user2', -2 ); ?>
                    </div>
                  </div></td>
                <?php
				  					}
										?>
              </tr>
              <tr>
                <td colspan="<?php echo $colspan; ?>"></td>
              </tr>
              <?php
									}
								?>
            </table>
            <div align="center">
              <div id="content_bottom_bar">
                <div id="content_bottom">
                  <div id="content_right_bottom"></div>
                </div>
              </div>
            </div></td>
          <?php
							if ( $right > 0 ) {
		  				?>
          <td><div id="right_outer">
              <div id="right_top"></div>
              <div id="right_inner_float">
                <div id="right_inner">
                  <?php mosLoadModules ( 'right', -2 ); ?>
                </div>
              </div>
              <div id="right_bottom"></div>
            </div></td>
          <?php
		  			}
		  			?>
        </tr>
      </table>
      <div align="center">
        <div id="copy">
          <div id="copy_inner" class="copy_inner">Valid <a href="http://validator.w3.org/check?uri=referer" target="_blank">XHTML</a> &amp; <a href="http://jigsaw.w3.org/css-validator/" target="_blank">CSS</a> - Design by <a href="http://ah-68.de" target="_blank">ah-68</a> - Copyright &copy; 2007 by <strong><a href="<?php echo $mosConfig_live_site; ?>" target="_self">Firma</a></strong></div>
        </div>
      </div></td>
  </tr>
</table>
<?php mosLoadModules( 'debug', -1 );?>
</body>
</html>
