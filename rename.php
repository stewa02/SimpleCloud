<?php
include "access.php";

$javascript = ' ';
$error = ' ';
if (isset($_POST['button'])){
	include "read.php";
	$filename = trim($_POST['path']);
	$newname = trim($_POST['name']);
	$onlyname = basename($filename);
	$filename_new = str_replace($onlyname, '', $filename).$newname;
	if (!preg_match('/[\$|<|>|\^|\&|\*|\@|~|\/]/', $newname) or !preg_match('/^\./', $newname)){
		rename_data($filename, $filename_new);
		$javascript = 'onload="loadMainFrame()"';
	}else{
		$error = "<b style='color:red;'>The filename includes forbidden special chars</b><br>";
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>simpleCloud</title>
<link rel="stylesheet" href="css/main.css" type="text/css" />
<script type="text/javascript">
function loadMainFrame(){
        parent.parent.window.frames["mainframe"].location.reload();
	parent.window.frames["menuframe"].location.reload();
        parent.$.fancybox.close();
}
</script>

</head>
<body <?php echo $javascript; ?> class="actionframe">
<center>
<?php
	echo $error;
?>
<p><b>New filename</b></p>
<form action="rename.php" method="post">
<input type="text" class="textfield" name="name">
<br>
<br>
<?php
if (isset($_GET['mvpath'])){
	echo "<input type='hidden' name='path' value='".$_GET['mvpath']."'>";
}
if (isset($_POST['path'])){
        echo "<input type='hidden' name='path' value='".$_POST['path']."'>";
}
?>
<input type="submit" value="Rename" class="buttonstyle" name="button">
</form>

</center>
</body>
</html>
