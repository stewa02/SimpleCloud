<?php
include "access.php";
include "read.php";
if (isset($_POST['share'])){
	if (!isset($_POST['email'])){
		$error = "Please check the E-mail field.";
		$go_ahead = false;
	}else{
		$go_ahead = true;
		$email = trim($_POST['email']);
		$sharepath = trim($_POST['sharepath']);
	}
	$res = mysql_query("SELECT * From user Where email='".$email."';");
        $rows = mysql_num_rows($res);
        if ($rows == 0){
        	$error = "There is no E-mail to share.";
		$go_ahead = false;
       	}else{
		if ($go_ahead == true){
			$go_ahead = true;
		}
	}
	if ($go_ahead){
        	$res = mysql_query("SELECT * From user Where email='".$email."';");
        	while ($entry = mysql_fetch_array($res, MYSQL_ASSOC)) {
        	        $share_id = $entry['id'];
        	}
	        $res = mysql_query('select * from filesharing where empfaenger='.$share_id.' and abspath="'.$sharepath.'" and sender='.$id);
	        $rows = mysql_num_rows($res);
	        if ($rows != 0){
	                $error = "This file is already shared";
		}else{
			$SQL_query = "INSERT INTO filesharing (sender,empfaenger,abspath) VALUES (".$id.",".$share_id.",'".$sharepath."');";
                	mysql_query($SQL_query);
			$erfolg = "The file has been shared.";
		}
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
<body class="actionframe">
<center>
<p><b>Filesharing</b></p>
<p><b>E-mail</b></p>
<?php
if (isset($error)){
echo "<b style='color:red'>";
echo $error;
echo "</b><br><br>";
}
if (isset($erfolg)){
echo "<b style='color:green'>";
echo $erfolg;
echo "</b><br><br>";
}

?>

<form action="fileshare.php" method="post">
<input type="text" class="textfield" name="email">
<br>
<?php
if (isset($_GET['sharepath'])){
        echo "<input type='hidden' name='sharepath' value='".$_GET['sharepath']."'>";
}
if (isset($_POST['sharepath'])){
        echo "<input type='hidden' name='sharepath' value='".$_POST['sharepath']."'>";
}
?>
<br>
<input type="submit" value="Share" class="buttonstyle" name="share">

</form>

</center>
</body>
</html>
