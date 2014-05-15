<?php
include "access.php";
if ($id != 1){
        echo "Only for Admin";
        exit;
}

include "kdf2funktion.php";
if (isset($_POST['buttonpasswd'])){
	if (!isset($_POST['newpassi']) or !isset($_POST['newpassiconf'])){
		$error = "Both password fields have to set.";
	}elseif ($_POST['newpassi'] !== $_POST['newpassiconf']){
		$error = "The passwords are not correct";
	}else{
		$change_id = trim($_POST['change_id']);
                $res = mysql_query('select * from user where id="'.$change_id.'"');
                while ($entry = mysql_fetch_array($res, MYSQL_ASSOC)) {
                        $session_id = $entry['session'];
                }
		$newpassi = trim($_POST['newpassi']);
		$passi_hash = pbkdf2($newpassi, $session_id);
		$SQL_query = "Update user set password='".$passi_hash."' where id='".$change_id."';";
                mysql_query($SQL_query);		
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
        parent.$.fancybox.close();
}
</script>

</head>
<body  class="actionframe">
<br>
<br>
<a href="admin.php" style='margin-left:20px;padding-top:30px;'><img src='icons/left.png'></a>
<center>
<b style='color:red'>
<?php
if (isset($error)){ echo $error;}
?>
</b>
<form action="settings.php" method="post">
<p><b>New Password</b></p>
<input type="password" class="textfield" name="newpassi">
<br>
<p><b>Confirm New Password</b></p>
<input type="password" class="textfield" name="newpassiconf">
<br>
<br>
<?php
if (isset($_GET['change_id'])){
        echo "<input type='hidden' name='change_id' value='".$_GET['change_id']."'>";
}
if (isset($_POST['change_id'])){
        echo "<input type='hidden' name='change_id' value='".$_POST['change_id']."'>";
}
?>

<input type="submit" value="Modify" class="buttonstyle" name="buttonpasswd">
</form>
<br>
</center>
</body>
</html>
