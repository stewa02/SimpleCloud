<?php
//include "access.php";
include "read.php";
$Directory = "testdir";
$typ = "dir";
$name = "unknown";

if (isset($_GET['typ'])){
	$typ = $_GET['typ'];
}
if (isset($_GET['name'])){
	$name = $_GET['name'];
	$name = htmlentities($name);
}
if (isset($_GET['path'])){
	$Directory = $_GET['path'];
}	


//Functions
if ($typ == "download"){
	download_file($Directory);
}

if (isset($_POST['button'])){
        include "kdf2funktion.php";
        include "mysql_conf.php";
	include("blowfish/blowfish.class.php");
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
<html>
<head>
<title>simpleCloud</title>
<link rel="stylesheet" href="jq/jquery.mobile-1.3.1.min.css" />
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,700">
<script src="jq/jquery-1.9.1.min.js"></script>
<script src="jq/jquery.mobile-1.3.1.min.js"></script>
</head>
<body>
<div data-role="header" data-theme="b">
    <h1>simpleCloud</h1>
	<?php
	//echo "<a href=\"?typ=more&path=".$Directory."\" data-corners=\"false\" class=\"ui-btn-left\">Back</a>";
	if ($typ == "dir"){
	echo "<a href=\"?typ=more&path=".$Directory."\" data-corners=\"false\" class=\"ui-btn-right\">More</a>";
	}
	?>
</div>
<?php
if ($typ == "login"){
?>
	<form method="POST" action="index_mobile.php">
    <input name="benu" id="text-6" value="" placeholder="Login" type="text">
	<input data-clear-btn="true" placeholder="Password" name="passi" id="password-2" value="" autocomplete="off" type="password">
	<input name="123button" value="Login" type="submit" data-corners="false">
	</form>
<?php
}elseif ($typ == "dir"){
	$files = array();
	if(check_path($Directory)){
		if (dir_is_empty($Directory)){
			echo "Folder is empty";
		}else{
			$files = get_file_list($Directory);
			echo "<ul data-role=\"listview\">";
			foreach ($files as $file) {
				echo "<li><a data-transition=\"slide\" href=\"?path=".$file['path']."&typ=".$file['typ']."&name=".$file['name']."\"><img src=\"".$file['icon_path']."\" alt=\"".$file['name']."\" class=\"ui-li-icon ui-corner-none\">".$file['name']."</a></li>";
			}
			echo "</ul>";
		}
	}
}elseif ($typ == "file"){
	?>
	<ul data-role="listview">
	<li>File: <?php echo $name;?></li>
	<li><a href="?typ=download&<?php echo "$path"?>"><img src="icons/download.png" alt="Download" class="ui-li-icon ui-corner-none">Download</a></li>
	<li><a href="#"><img src="icons/rename.png" alt="Rename" class="ui-li-icon ui-corner-none">Rename</a></li>
	<li><a href="#"><img src="icons/del.png" alt="Delete" class="ui-li-icon ui-corner-none">Delete</a></li>
	</ul>
	<?php
}elseif ($typ == "more"){
	?>
	<ul data-role="listview">
	<li>Path: <?php echo $Directory;?></li>
	<li><a href="#"><img src="icons/upload.png" alt="Upload" class="ui-li-icon ui-corner-none">Upload</a></li>
	<li><a href="#"><img src="icons/new_dir.png" alt="Mkdir" class="ui-li-icon ui-corner-none">New Directory</a></li>
	<li><a href="#"><img src="icons/rename.png" alt="Rename" class="ui-li-icon ui-corner-none">Rename</a></li>
	</ul>
	<?php
}else{
	echo "Error";
}

?>

</body>
</html>