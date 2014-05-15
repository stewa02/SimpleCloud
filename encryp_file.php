<?php
include "access.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>simpleCloud</title>
<link rel="stylesheet" href="css/main.css" type="text/css" />
</head>
<body class='actionframe'>
<center>
<p><b>Enter your Key</b></p>
<form action="encryp_file.php" method="post">
<input type="password" class="textfield" name="key">
<br>
<br>
<?php
if (isset($_GET['dwload'])){
	echo "<input type='hidden' name='path' value='".trim($_GET['dwload'])."'>";
}
if (isset($_POST['path'])){
        echo "<input type='hidden' name='path' value='".trim($_POST['path'])."'>";
}
?>
<input type="submit" value="Download" class="buttonstyle" name="button">
</form>

</center>
</body>
</html>
<?php
if (isset($_POST['button'])){
        include "read.php";
        $key = trim($_POST['key']);
        $ausgabe = file_get_contents(trim($_POST['path']));
        $blowfish = new Blowfish($key);
        $coded_string = $blowfish->Decrypt($ausgabe);
        $fullpath = trim($_POST['path']);
        $fullpath_2 = str_replace('.lock', '', basename($fullpath));
        $file_handle = fopen("tmp/$fullpath_2", 'w');
        fwrite($file_handle, base64_decode($coded_string));
        fclose($file_handle);
        download_file("tmp/$fullpath_2");
        unlink("tmp/$fullpath_2");
}
?>
