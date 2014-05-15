<?php
include "access.php";
$crypt_blow = new Blowfish($SESSION_UNIQ_KEY);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>simpleCloud</title>
<script type="text/javascript" src="foldertree/_lib/jquery.js"></script>
<script type="text/javascript" src="foldertree/_lib/jquery.cookie.js"></script>
<script type="text/javascript" src="foldertree/_lib/jquery.hotkeys.js"></script>
<script type="text/javascript" src="foldertree/jquery.jstree.js"></script>
<link rel="stylesheet" href="css/main.css" type="text/css" />
<script type="text/javascript">
function loadMainFrame(url){
	url = "main_browser.php?d_de=" + url
	parent.window.frames["mainframe"].location = url
}
function showHint()
{
var xmlhttp;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","home_usage.php");
xmlhttp.send();
}
</script>

</head>

<body onload="showHint();">
<table class="browsetable">
<tr style="height:46px;border-bottom:1px solid #000;">
        <th class='browseth'>
		<div id="txtHint">
		</div>
	</th>
</tr>
</table>
<div id="demo1" class="demo" style="height:100px;">
<?php
include "read.php";
$Main_Directory = $Homedir;
$datatreecache = $Homedir."/.foldertree.txt";


$file_handle = fopen($datatreecache,"w+");
fclose($file_handle);
getDirectory($Main_Directory,$datatreecache);
$data = file($datatreecache);
$counter = 1;
$root_directory = trim($Main_Directory);
echo '<ul><li id="phtml_1"><a href="#" onclick="loadMainFrame(\''.$root_directory.'\');">
'.trim(preg_replace('/^data\//','',$Main_Directory)).'</a><ul>';
for ($i=0;$i<count($data);$i++){
	$splits = preg_split('/ /', $data["$i"]);
	$k = $i+1;
	if (!isset($data["$k"])){
		$next_directory = trim($splits[1]);
                echo '<li id="phtml_'.$counter.'">
                <a href="#" onclick="loadMainFrame(\''.$next_directory.'\');">
                '.basename($splits[1]).
                '</a></li>';
                for ($s=$splits2[0];$s<$splits[0];$s++){
                        echo '</ul>'."\n";
                }
		break;
	}
	$splits2 = preg_split('/ /', $data["$k"]);
	if ($splits2[0] > $splits[0]){
		$next_directory = trim($splits[1]);
		echo '<li id="phtml_'.$counter.'"">
		<a href="#" onclick="loadMainFrame(\''.$next_directory.'\');">
		'.basename($splits[1]).'
		</a><ul>'."\n";
	}elseif($splits2[0] == $splits[0]){
		$next_directory = trim($splits[1]);
		echo '<li id="phtml_'.$counter.'">
		<a href="#" onclick="loadMainFrame(\''.$next_directory.'\');">
		'.basename($splits[1]).'
		</a></li>'."\n";
	}elseif($splits2[0] < $splits[0]){
		$next_directory = trim($splits[1]);
		echo '<li id="phtml_'.$counter.'">
		<a href="#" onclick="loadMainFrame(\''.$next_directory.'\');">
		'.basename($splits[1]).
		'</a></li>';
		for ($s=$splits2[0];$s<$splits[0];$s++){
			echo '</ul>'."\n";
		}
	}
	$counter++;
}
?>
	</li>
</ul>
</div>
<script type="text/javascript" class="source below">
$(function () {
	$("#demo1")
		.jstree({
			"plugins" : ["themes","html_data","ui","crrm","hotkeys"],
			"core" : { "initially_open" : [ "phtml_1" ] }
		})
		.bind("loaded.jstree", function (event, data) {
		});
	setTimeout(function () { $("#demo1").jstree("set_focus"); }, 500);
	setTimeout(function () { $.jstree._focused().select_node("#phtml_1"); }, 1000);
	setTimeout(function () { $.jstree._reference("#phtml_1").close_node("#phtml_1"); }, 1500);
	$("#demo1").bind("open_node.jstree", function (e, data) {
		data.inst.select_node("#phtml_2", true);
	});
	setTimeout(function () { $.jstree._reference("#phtml_1").open_node("#phtml_1"); }, 2500);
});
</script>
</div>
</body>
</html>
