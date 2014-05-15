<?php
include "access.php";
if ($id != 1){
        echo "Only for Admin";
        exit;
}

if (isset($_POST['add'])){
        if (!isset($_POST['name']) or !isset($_POST['vorname']) or !isset($_POST['email']) or !isset($_POST['quota'])){
                $error = "Please check your input.";
                $go_ahead = false;
        }else{
                $go_ahead = true;
        }
        if ($go_ahead){
                $name = trim($_POST['name']);
                $vorname = trim($_POST['vorname']);
                $email = trim($_POST['email']);
                $quota = trim($_POST['quota']);
		$sessid = rand(1000,99999);
		$result = mysql_query("SELECT * From user Where session='".$sessid."';");
        	$rows = mysql_num_rows($result);
		while ($rows != 0){
                	$sessid = rand(1000,99999);
                	$result = mysql_query("SELECT * From user Where session='".$sessid."';");
                	$rows = mysql_num_rows($result);
		}
                $SQL_query = "INSERT INTO user (vorname,nachname,email,session,quota,usage_quota,homedir) VALUES ('".$vorname."','".$name."','".$email."',".$sessid.",".$quota.",0,'data/".$name."');";
                mysql_query($SQL_query);
        }
	mkdir("data/$name",0777);
	$file_handle = fopen("data/".$name."/.foldertree.txt","w+");
	fclose($file_handle);
	chmod("data/".$name."/.foldertree.txt",0777);
	if (!isset($error)){
		$erfolg = "Add was successful!";
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

function calc_bytes(bytes,einheit){
        var result = "";
        switch (einheit) {
        case "B":
                result = bytes;
        break;
        case "kB":
                result = bytes*1024;
        break;
        case "MB":
                result = bytes*1024*1024;
        break;
        case "GB":
                result = bytes*1024*1024*1024;
        break;
        default:
                result = 0;
        break;
        }
        document.getElementById('bytes').value = result;
}
</script>


</head>
<body  class="actionframe">
<br>
<br>
<a href="admin.php" style='margin-left:20px;padding-top:30px;'><img src='icons/left.png'></a>
<center>
<?php
if (isset($error)){ 
echo "<br><br><b style='color:red'>";
echo $error;
echo "</b>";
}
if (isset($erfolg)){
echo "<br><br><b style='color:green'>";
echo $erfolg;
echo "</b>";
}

?>
<form action="user_add.php" method="post">
<p><b>Name</b></p>
<input type="text" class="textfield" name="name">
<br>
<p><b>Prename</b></p>
<input type="text" class="textfield" name="vorname">
<br>
<p><b>E-mail</b></p>
<input type="text" class="textfield" name="email">
<br>
<p><b>Quota</b></p>
        <input type="hidden" name="quota" id="bytes">
		<input class="textfield_add" type="text" id="calc" size="10"
		onchange="calc_bytes(this.value,document.getElementById('einheit').value)"
		onmouseout="calc_bytes(this.value,document.getElementById('einheit').value)">
        <select class="select_add" id="einheit" size="1" onmouseup="calc_bytes(document.getElementById('calc').value,this.value)">
                <option>B</option>
                <option>kB</option>
                <option>MB</option>
                <option>GB</option>
        </select>

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

<input type="submit" value="Add" class="buttonstyle" name="add">
</form>
<br>
</center>
</body>
</html>
