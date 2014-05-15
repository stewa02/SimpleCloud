<?php
if (isset($_POST['button'])){
        include "kdf2funktion.php";
        include "mysql_conf.php";
	include "blowfish/blowfish.class.php";
        $Password = trim($_POST['passi']);
        $Benutzer = trim($_POST['benu']);
        $connection = mysql_connect($MySQL_Host, $MySQL_Benutzer, $MySQL_Password)
              OR die ("Keine Verbindung zu der Datenbank moeglich.");
        $db = mysql_select_db($MySQL_DB, $connection)
              OR die ("Auswahl der Datenbank nicht moeglich.");

        $result = mysql_query("SELECT * From user Where email='".$Benutzer."'");
        $rows = mysql_num_rows($result);
        if ($rows == 0){
                $stop = "true";
                $meldung = "<span style='color:red'><b>Falscher Benutzernamen</b></span><br><br>";
        }else{
                $stop = "false";
        }
	if ($stop == "false"){
        	$res = mysql_query('select * from user where email="'.$Benutzer.'"');
        	while ($entry = mysql_fetch_array($res, MYSQL_ASSOC)) {
        	        $Password_hash = $entry['password'];
			$session_id = $entry['session'];
        	}
		$Passwd_input = trim($session_id.':'.pbkdf2($Password, $session_id));
        	$Passwd_db = trim("$session_id:$Password_hash");
        	if ($Passwd_input == $Passwd_db){
			$uniq_key = $session_id.":".md5(microtime().rand());
			$crypt_blow = new Blowfish($uniq_key);
			$crypted_Benutzer = $crypt_blow->Encrypt($Benutzer);
			setcookie("erspu2435",$crypted_Benutzer);
			session_start();
        		$_SESSION['session_id'] = $session_id;
			$_SESSION['session_key'] = $uniq_key;
        	        Header("Location: index.php");
        	}else{
        	       $meldung = "<span style='color:red'><b>Falsches Passwort</b></span><br><br>";
        	}
	}
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
			<div class='login_div' align="left">
			<form action="login.php" method="post">
			<?php
				if (isset($meldung)){
					echo $meldung;
				}
			?>
				<b>E-mail:</b><br>
				<input type="text" name="benu" class="textfieldlogin" size="400" maxlength="100000"><br>
				<b style='margin-top:20px;'>Password:</b><br>
                                <input type="password" name="passi" class="textfieldlogin" size="400" maxlength="100000"><br>
				<center>
					<br>
					<input type='submit' value='login' name='button' class="buttonstyle">
				</center>
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
