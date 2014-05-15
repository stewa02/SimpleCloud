<?php
include "access.php";
include "read.php";
include "bytes_formate.php";
$script_types = array(
'sh','cf','css',
'c','js','diff',
'path','java',
'pl','php','ps',
'py','rb','sql',
'vba','vbs','vb',
'xml','html','hrm',
'xhtml'
);

$readable_files = array(
'txt','doc','xls',
'png','gif','jpeg',
'jpg','bmp','mp3',
'wav','ogg','ogv',
'mp4','webm'
);

if (isset($_GET['dwload'])){
        download_file(trim($_GET['dwload']));
}

if (isset($_GET['del_share'])){
	$del_share = trim($_GET['id']);
	$res = mysql_query('select * from filesharing where id='.$del_share);
	$del_it = false;
        while ($entry3 = mysql_fetch_array($res, MYSQL_ASSOC)) {
        	if ($entry3['sender'] == $id){
			$del_it = true;
		}
		if ($entry3['empfaenger'] == $id){
			$del_it = true;
		}
	}
	if ($del_it){
		mysql_query('delete from filesharing where id='.$del_share);
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>simpleCloud</title>
<link rel="stylesheet" href="css/main.css" type="text/css" />
<script src="jquery.min.js"></script>
<script type="text/javascript">
function loadMainFrame(){
        parent.parent.window.frames["mainframe"].location.reload();
        parent.parent.window.frames["menuframe"].location.reload();
        parent.$.fancybox.close();
}
</script>
<script type="text/javascript">
$(document).ready(function(){
	$("#myshare").hide();
	$("#exshare_bu").click(function(){
		$("#exshare").fadeIn();
		$("#myshare").hide();
	});
	$("#myshare_bu").click(function(){
                $("#myshare").fadeIn();
                $("#exshare").hide();
        });

}); 
</script>
</head>
<body class="actionframe">
<center>
<br>
<br>
<button type="button" id='exshare_bu' class='sharebutton'>External Share</button>
<button type="button" id='myshare_bu' class='sharebutton' >My Share</button>
<div id="exshare">
<p><b>External Shares</b></p><br>
<form action="share.php" method="post">
<?php
$res = mysql_query('select * from filesharing where empfaenger='.$id);
$rows = mysql_num_rows($res);
if ($rows == 0){
	echo "<b style='color:red'>There are no shares</b>";
}else{
	echo '
                <table class="admintable_share">
                <tr class="admintr">
			<th class="adminth" style="width:26px;"><img src="icons/dir.png" width="25"></th>
                        <th class="adminth">Name</th>
			<th class="adminth">Fileinfo</th>
                        <th class="adminth">Size</th>
			<th class="adminth">Shared from</th>
			<th class="adminth"> </th>
                </tr>

	';
	while ($entry = mysql_fetch_array($res, MYSQL_ASSOC)) {
		$DATA = get_file_info($entry['abspath']);
		echo "<tr>\n";
		echo "<td class='sharetd' style='width:26px;'>
		<a href='share.php?dwload=".$entry['abspath']."'>
			<img src='".$DATA['icon_path']."' width='25'>
		</a>
		</td>\n";
		echo "<td class='sharetd'>\n";
                if (in_array($DATA['fileend'], $script_types)){
                        echo "<a href='script_highlight.php?sppath=".$entry['abspath']."&end=".$DATA['fileend']."' target='_self' class='link_list_share'>
                        ".$DATA['name']."</a>\n";
                }elseif (in_array($DATA['fileend'], $readable_files)){
                        echo "<a href='file_view.php?f=".$entry['abspath']."&end=".$DATA['fileend']."' class='link_list_share'>";
                        echo $DATA['name']."</a>\n";
                }else{
                        if (!preg_match('/lock/', $DATA['name'])){
                                echo '<a href="share.php?dwload='.$entry['abspath'].'" class="link_list_share">';
                                echo $DATA['name']."</a>\n";
                        }else{
                                echo '<a href="encryp_file.php?dwload='.$entry['abspath'].'" target="_self" class="link_list_share">';
                                echo $DATA['name']."</a>\n";
                        }
                }
		echo "</td>\n";
		echo "<td class='sharetd'>".$DATA['fileinfo']."</td>\n";
		echo "<td class='sharetd'>".formate_bytes($DATA['size'])."</td>\n";
		$res_user = mysql_query('select * from user where id='.$entry['sender']);
		while ($entry_user = mysql_fetch_array($res_user, MYSQL_ASSOC)) {
			 echo "<td class='sharetd'>".$entry_user['vorname']." ".$entry_user['nachname']."</td>\n";
		}
		echo "<td class='sharetd' style='width:26px;'>
		<a href='share.php?del_share=true&id=".$entry['id']."'>
			<img src='icons/del.png' width='25'>
		</a>
		</td>\n";
		echo "</tr>\n";
		unset($DATA);
	}
echo "</table>\n";
}
?>
</form>
</div>
<div id="myshare">
<p><b>My Shares</b></p><br>
<form action="share.php" method="post">
<?php
$res2 = mysql_query('select * from filesharing where sender='.$id);
$rows = mysql_num_rows($res2);
if ($rows == 0){
        echo "<b style='color:red'>There are no shares</b>";
}else{
        echo '
                <table class="admintable_share">
                <tr class="admintr">
			<th class="adminth" style="width:26px;"><img src="icons/dir.png" width="25"></th>
                        <th class="adminth">Name</th>
			<th class="adminth">Fileinfo</th>
                        <th class="adminth">Size</th>
			<th class="adminth">Shared to</th>
			<th class="adminth"> </th>
                </tr>

        ';
        while ($entry2 = mysql_fetch_array($res2, MYSQL_ASSOC)) {
                $DATA2 = get_file_info($entry2['abspath']);
                echo "<tr>\n";
		echo "<td class='sharetd' style='width:26px;'><img src='".$DATA2['icon_path']."' width='25'></td>\n";
                echo "<td class='sharetd'>".$DATA2['name']."</td>\n";
		echo "<td class='sharetd'>".$DATA2['fileinfo']."</td>\n";
                echo "<td class='sharetd'>".formate_bytes($DATA2['size'])."</td>\n";
                $res_user2 = mysql_query('select * from user where id='.$entry2['empfaenger']);
                while ($entry_user2 = mysql_fetch_array($res_user2, MYSQL_ASSOC)) {
                         echo "<td class='sharetd'>".$entry_user2['vorname']." ".$entry_user2['nachname']."</td>\n";
                }
		echo "<td class='sharetd' style='width:26px;'>
                <a href='share.php?del_share=true&id=".$entry2['id']."'>
                        <img src='icons/del.png' width='25'>
                </a>
		</td>\n";
                echo "</tr>\n";
                unset($DATA2);
        }
echo "</table>\n";
}
?>
</form>
</div>
</center>
</body>
</html>

