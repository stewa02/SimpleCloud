<?php
include "access.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>simpleCloud</title>

<link rel="icon" href="icons/favicon.ico" type="image/x-icon"/>
<link rel="shortcut icon" href="icons/favicon.ico" type="image/x-icon"/>

<link rel="stylesheet" href="css/main.css" type="text/css" />
<link href='http://fonts.googleapis.com/css?family=Trade+Winds' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Over+the+Rainbow' rel='stylesheet' type='text/css'>
<!-- Add jQuery library -->
<script type="text/javascript" src="fancybox/jquery.min.js"></script>

<!-- Add mousewheel plugin (this is optional) -->
<script type="text/javascript" src="fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>

<!-- Add fancyBox -->
<link rel="stylesheet" href="fancybox/source/jquery.fancybox.css?v=2.1.4" type="text/css" media="screen" />
<script type="text/javascript" src="fancybox/source/jquery.fancybox.pack.js?v=2.1.4"></script>

<!-- Optionally add helpers - button, thumbnail and/or media -->
<link rel="stylesheet" href="fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
<script type="text/javascript" src="fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
<script type="text/javascript" src="fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.5"></script>

<link rel="stylesheet" href="fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
<script type="text/javascript" src="fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>
<script type='text/javascript'>
$(document).ready(function() {
	$(".various").fancybox({
		maxWidth	: 800,
		maxHeight	: 600,
		fitToView	: false,
		width		: '80%',
		height		: '80%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'none'
	});
        $(".share").fancybox({
                maxWidth        : 1000,
                maxHeight       : 600,
                fitToView       : false,
                width           : '90%',
                height          : '80%',
                autoSize        : false,
                closeClick      : false,
                openEffect      : 'none',
                closeEffect     : 'none'
        });

        $(".admin").fancybox({
                fitToView       : false,
                width           : '90%',
                height          : '90%',
                autoSize        : false,
                closeClick      : false,
                openEffect      : 'none',
                closeEffect     : 'none'
        });

});
</script>
<script type="text/javascript">
function showHint()
{
var xmlhttp;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {

    var iframe = document.getElementById('folderframe');
    var innerDoc = iframe.contentDocument || iframe.contentWindow.document;
    
    innerDoc.getElementById("txtHint").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","home_usage.php");
xmlhttp.send();
}
</script>

</head>
 
<body style='overflow:hidden;'>
<table class="main_table" border="1">
	<tr style='border:0px;'>
		<td class="menu" colspan="2" valign="bottom">
			<span class='title'>simpleCloud</span>
			<img src="icons/cloud2.png" class='mainimg'>
			<img src="icons/cloud3.png" class="mainimg2">
			<img src="icons/cloud2.png" class='mainimg3'>
			<img src="icons/cloud2.png" class='mainimg4'>
		</td>
	</tr>
	<tr style='border:0px;'>
		<td class="infobord">
			<?php include "user_info.php"; ?>
                </td>
		<td class="menubord">
			<?php include "menuboard.php"; ?>
		</td>
	</tr>
	<tr>
		<td class="folder_view">
		<iframe src="folder_view.php" width="350" height="100%" class="menuframe" name="menuframe" id="folderframe"></iframe>
		</td>
		<td class="main_view">
		<iframe src="main_browser.php"  class="mainframe" name="mainframe" id="mainframe"></iframe>
		</td>
	</tr>
	<tr>
		<td class="infoliste" colspan="2">
			<div style='float:left;'>&copy; simpleCloud by Stephan Wagner, Simon Kaspar und Yannick Schlatter</div> 
			<div style='float:right;'>&copy; Design by Simon Kaspar</div>
		</td>
	</tr>
</table>
</body>
</html>
