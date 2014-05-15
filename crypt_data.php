<?php
include "access.php";

$javascript = " ";
if (isset($_POST['button'])){
	include "read.php";
	$filepath = trim($_POST['path']);
	$key = trim($_POST['key']);
	$data = file_get_contents($filepath);
	$coded_string = base64_encode($data);
	$file_handle = fopen($filepath.".lock", 'w');
	$blowfish2 = new Blowfish($key);
	$ausgabe = $blowfish2->Encrypt($coded_string);
	fwrite($file_handle, $ausgabe);
	fclose($file_handle);
	$res = mysql_query('select * from user where id="'.$id.'"');
	while ($entry = mysql_fetch_array($res, MYSQL_ASSOC)) {
		$quota_cur_usd = $entry['usage_quota'];
	}
	$filesize = filesize($filepath.".lock");
	$filesize = $filesize + $quota_cur_usd;
	mysql_query('update user set usage_quota="'.$filesize.'" where id="'.$id.'"');
	$javascript = 'onload="loadMainFrame()"';
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
	parent.$.fancybox.close();
}
</script>

</head>
<body <?php echo $javascript;?> class='actionframe'>
<center>
<p><b>Enter your Key</b></p>
<form action="crypt_data.php" method="post">
<input type="password" class="textfield" name="key">
<br>
<br>
<?php
if (isset($_GET['dwload'])){
	echo "<input type='hidden' name='path' value='".$_GET['dwload']."'>";
}
?>
<input type="submit" value="Encrypt" class="buttonstyle" name="button">
</form>

</center>
</body>
</html>
