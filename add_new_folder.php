<?php
include "access.php";

$javascript = ' ';
$error = ' ';
if (isset($_COOKIE['curdir'])){
        $storeFolder_tmp = $_COOKIE['curdir'];
        $decrypte_blow = new Blowfish($SESSION_UNIQ_KEY);
        $storeFolder = "/".trim($decrypte_blow->Decrypt($storeFolder_tmp));
	$filepath = trim($decrypte_blow->Decrypt($storeFolder_tmp));
}

if (isset($_POST['button'])){
        include "read.php";
	$newname = trim($_POST['name']);
        if (!preg_match('/[\$|<|>|\^|\&|\*|\@|~|\/]/', $newname)){
		if (!file_exists($filepath."/".$newname)){
			$get_size = true;
		}else{
			$get_size = false;
		}
        	if ($get_size){
			mkdir($filepath."/".$newname,0777);
		}
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
        parent.parent.window.frames["menuframe"].location.reload();
        parent.$.fancybox.close();
}
</script>

</head>
<body <?php echo $javascript; ?> class="actionframe">
<center>
<?php
        echo $error;
?>
<p><b>New Folder in 
<?php

echo str_replace('/data', '', $storeFolder);

?>
</b></p>
<form action="add_new_folder.php" method="post">
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
<input type="submit" value="Creat" class="buttonstyle" name="button">
</form>

</center>
</body>
</html>

