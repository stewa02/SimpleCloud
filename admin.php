<?php
include "access.php";
include "bytes_formate.php";
include "read.php";
if ($id != 1){
        echo "Only for Admin";
        exit;
}

if (isset($_POST['save'])){
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
		$id = trim($_POST['user_id']);
		$SQL_query = "Update user set vorname='".$vorname."',nachname='".$name."',email='".$email."',quota='".$quota."' where id='".$id."';";
		mysql_query($SQL_query);
	}
	if (!isset($error)){
		$erfolg = "Saveing successful!";
	}
}
if (isset($_POST['remove'])){
	$id = trim($_POST['user_id']);
	$SQL_query = "delete from user where id=".$id;
        $res = mysql_query("SELECT * From user Where id='".$id."';");
	while ($entry = mysql_fetch_array($res, MYSQL_ASSOC)) {
		$name = $entry['nachname'];
	}
	$path_to_rm = "data/".$name;
	if (file_exists($path_to_rm)){
		SureRemoveDir($path_to_rm,true);
		mysql_query($SQL_query);
		$SQL_query_share = "delete from filesharing where sender=".$id." or empfaenger=".$id;
		mysql_query($SQL_query_share);
	}
	if (!isset($error)){
		$erfolg = "Remove successful!";
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

<script type="text/javascript">

function calc_bytes(bytes,einheit,id){
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
	document.getElementById('bytes' + id).value = result;
}
</script>


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
		<td style="background-color:#49a5bf;border:0px;height:100%;" align="center">
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
		<a href="user_add.php" ><button type="button" class="buttonstyle" name="button"><img src="icons/new_user.png" width="20"> Add New User</button></a>
		<br>
		<br>
		<table class="admintable">
		<tr class="admintr">
			<th class='adminth'>Name</th>
			<th class='adminth'>Prename</th>
			<th class='adminth'>E-mail</th>
			<th class='adminth'>Quota</th>
			<th></th>
		</tr>
<?php

$res = mysql_query('select * from user');
$count = 0;
while ($entry = mysql_fetch_array($res, MYSQL_ASSOC)) {
	echo '<form action="admin.php" method="post">';
	echo "<tr class='admintr'>\n";
	echo "<td class='admintd'><input class='adminfeld' type='text' size='30' name='name' value='".$entry['nachname']."'></td>\n";
	echo "<td class='admintd'><input class='adminfeld' type='text' size='30' name='vorname' value='".$entry['vorname']."'></td>\n";
	echo "<td class='admintd'><input class='adminfeld' type='text' size='30' name='email' value='".$entry['email']."'></td>\n";
	echo '<td class="admintd"><input type="hidden" name="quota" id="bytes'.$count.'" value="'.$entry['quota'].'">
	<input type="hidden" name="user_id" value="'.$entry['id'].'">
	';
	$einheit = preg_split('/ /', formate_bytes($entry['quota']));
	$option_bytes = array(' ',' ',' ',' ');
	switch (trim($einheit[1])){
		case 'B':
			$option_bytes[0] = " selected";
		break;
                case 'kB':
                        $option_bytes[1] = " selected";
                break;
                case 'MB':
                        $option_bytes[2] = " selected";
                break;
                case 'GB':
                        $option_bytes[3] = " selected";
                break;
	}
	echo '<input class="adminfeld" type="text" id="calc'.$count.'" size="20" value="'.$einheit[0].'"';
	echo '
	onchange="calc_bytes(this.value,document.getElementById('."'einheit".$count."'".').value,'.$count.')"
	onmouseout="calc_bytes(this.value,document.getElementById('."'einheit".$count."'".').value,'.$count.')">
	<select class="adminselect" id="einheit'.$count.'" size="1" onmouseup="calc_bytes(document.getElementById('."'calc".$count."'".').value,this.value,'.$count.')">
		<option'.$option_bytes[0].'>B</option>
		<option'.$option_bytes[1].'>kB</option>
		<option'.$option_bytes[2].'>MB</option>
		<option'.$option_bytes[3].'>GB</option>
	</select>
	</td>';
	echo "<td>
		<a href='settings.php?change_id=".$entry['id']."'><img src='icons/lock.png' width='16px'></a>
		<button type='submit' name='save' class='nobutton'><img src='icons/save.png' width='16px'></button>
		<a href='charting.php?quota_usd=".$entry['usage_quota']."&quota=".$entry['quota']."'><img src='icons/chart.png' width='16px'></a>
		<button type='submit' name='remove' class='nobutton'><img src='icons/del_user.png' width='16px'></button>
	</td>";	
	echo "</tr>\n";
	echo "</form>";
	$count++;
}


?>
	</table>
		</td>
	</tr>
</table>
</body>
</html>
