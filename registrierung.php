<?php
if (isset($_POST['button'])){
	$Password = trim($_POST['benu']);
	$Benutzer = trim($_POST['passi']);
}
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

</head>
 
<body style='overflow:hidden;'>
<table class="main_table" border="1">
	<tr style='border:0px;'>
		<td class="menu" colspan="2" valign="bottom" style="border:0px;">
			<span class='title'>simpleCloud</span>
			<img src="icons/cloud2.png" class='mainimg'>
		</td>
	</tr>
	<tr style='border:0px;'>
		<td style="background-color:#49a5bf;border:0px;height:100%;" valign="middle">
			<center>
			<div class='reg_div' align="left">
			<form action="login.php" method="post">
				<b>E-mail:</b><br>
				<input type="text" name="benu" class="textfieldlogin" size="300" maxlength="100000"><br>
				<b style='margin-top:20px;'>Password:</b><br>
                                <input type="password" name="passi" class="textfieldlogin" size="300" maxlength="100000"><br>
                                <b>Name:</b><br>
                                <input type="text" name="benu" class="textfieldlogin" size="300" maxlength="100000"><br>
                                <b>Prename:</b><br>
                                <input type="text" name="benu" class="textfieldlogin" size="300" maxlength="100000"><br>
				<br>
				<input type='submit' value='Subscribe' name='button' class="buttonstyle">
			</form>
			</div>
			</center>
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
