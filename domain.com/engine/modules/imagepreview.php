<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/

$_GET['image'] = @htmlspecialchars ($_GET['image'], ENT_QUOTES);

if( preg_match( "/[?&;%<\[\]]/", $_GET['image'] ) ) $_GET['image'] = "";

$_GET['image'] = str_replace( "document.cookie", "", $_GET['image'] );
$_GET['image'] = str_replace( "javascript", "", $_GET['image'] );


if ( preg_match( "/[?&;%<\[\]]/", $_GET['image']) ) {

	$_GET['image'] = "";

}

$preview = <<<HTML
<HTML>
<HEAD>
<TITLE>Image Preview</TITLE>
<script language='javascript'>
     var NS = (navigator.appName=="Netscape")?true:false;

            function fitPic() {
            iWidth = (NS)?window.innerWidth:document.body.clientWidth;
            iHeight = (NS)?window.innerHeight:document.body.clientHeight;
            iWidth = document.images[0].width - iWidth;
            iHeight = document.images[0].height - iHeight;
            window.resizeBy(iWidth, iHeight);
            self.focus();
            };
</script>
</HEAD>
<BODY bgcolor="#FFFFFF" onload='fitPic();' topmargin="0" marginheight="0" leftmargin="0" marginwidth="0">
<script language='javascript'>
document.write( "<img src='{$_GET['image']}' border=0>" );
</script>
</BODY>
</HTML>
HTML;

echo $preview;
?>