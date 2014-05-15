<?php
include "access.php";
include "read.php";
include "bytes_formate.php";
$crypt_blow = new Blowfish($SESSION_UNIQ_KEY);


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
'txt',
'png','gif','jpeg',
'jpg','bmp','mp3',
'wav','ogg','ogv',
'mp4','webm'
);

if (isset($_GET['dwload'])){
	$file_to_download = trim($_GET['dwload']);
	download_file($file_to_download);
}
if (isset($_GET['rm'])){
        $path = $_GET['rmpath'];
	$path = trim($path);
	$res = mysql_query('select * from user where id="'.$id.'"');
	while ($entry = mysql_fetch_array($res, MYSQL_ASSOC)) {
		$quota_cur_usd = $entry['usage_quota'];
	}
	if (file_exists($path)){
		if(is_dir($path)){
			$filesize = foldersize($path);	
			$javascript = '<script>parent.window.frames["menuframe"].location.reload();</script>';
		}else{
			$filesize = filesize($path);
			$javascript = '<script>parent.showHint();</script>';
			mysql_query('delete from filesharing where abspath="'.$path.'" and sender='.$id);
		}
		$filesize = $quota_cur_usd-$filesize;
		mysql_query('update user set usage_quota="'.$filesize.'" where id='.$id);
	}
	del_data($path);
}
if (!isset($_GET['d'])){
	if (!isset($_GET['d_de'])){
        	$Main_Directory = $Homedir;
	}else{
		$Main_Directory = trim($_GET['d_de']);
	}
}else{
	$Main_Directory_decrypt = $_GET['d'];
        $Main_Directory = trim($Main_Directory_decrypt);
}
$crypted_path = $crypt_blow->Encrypt($Main_Directory);
setcookie("curdir",$crypted_path);
$rep_homedir = preg_split('/\//', $Homedir);
$rep_Main_Directory = preg_split('/\//', $Main_Directory);
if(check_path($Main_Directory) and ($rep_Main_Directory[1] == $rep_homedir[1])){
      if (dir_is_empty($Main_Directory)){
              $error = "<tr style='border-top:1px solid #000;'><td colspan='5' style='border-top:1px solid #000;'>
		<center><br><br><b style='color:red;font-size:80px'>No Data</b></center></td></tr></table>";
      }else{
        //Funktion: get_file_list( <<Directory>>, <<List only [file|folder|file_folder]>>,<<Zu sortierende Spalte>>, <<Reverse Array>>);
                $DATA = array();
		if (!isset($_GET['sort'])){
                	$DATA = get_file_list($Main_Directory);
		}else{
			$sort_option = $_GET['sopt'];
			$sort_reverse = $_GET['srev'];
			if ($sort_reverse == "true"){
				$DATA = get_file_list($Main_Directory,"file_folder",$sort_option,true);
			}else{
				$DATA = get_file_list($Main_Directory,"file_folder",$sort_option);
			}
		}
      }
}
if ($Main_Directory == $Homedir){
	$backdir = $Homedir;
}else{
	$url_parts = preg_split('/\//', $Main_Directory);
	$cur_num = count($url_parts)-1;
	$cur = "/".$url_parts["$cur_num"];
	$backdir = str_replace($cur,'',$Main_Directory);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>simpleCloud</title>
<link rel="stylesheet" href="css/main.css" type="text/css" />
<!-- Add jQuery library -->
<script type="text/javascript" src="fancybox/jquery.min.js"></script>

<!-- Add mousewheel plugin (this is optional) -->
<script type="text/javascript" src="fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>

<!-- Add fancyBox -->
<link rel="stylesheet" href="fancybox/source/jquery.fancybox.css?v=2.1.4" type="text/css" media="screen" />
<script type="text/javascript" src="fancybox/source/jquery.fancybox.pack.js?v=2.1.4"></script>

<!-- Optionally add helpers - button, thumbnail and/or media -->
<link rel="stylesheet" href="fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
<script type="text/javascript" src="fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
<script type="text/javascript" src="fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.5"></script>

<link rel="stylesheet" href="fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
<script type="text/javascript" src="fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>
<script type='text/javascript'>
$(document).ready(function() {
	$(".various").fancybox({
		maxWidth	: 900,
		maxHeight	: 700,
		fitToView	: false,
		width		: '80%',
		height		: '80%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'none'
	});
        $(".script_view").fancybox({
                fitToView       : false,
                width           : '98%',
                height          : '98%',
		scrolling	: 'no',
                autoSize        : false,
                closeClick      : false,
                openEffect      : 'none',
                closeEffect     : 'none'
        });
});
</script>

</head>

<body>
<?php
if (isset($javascript)){
	echo $javascript;
}
$Home_Directory = $Homedir;
?>
<table class="browsetable">
<tr style='height:16px;'>
	<th class='browsethmenu' align='left' colspan='6' style='color:#FFF;'>
	<a href='main_browser.php?d=<?php echo $backdir; ?>' class='icon_link'>
		<img src='icons/back.png' width='16'>
	</a>
	<a href='main_browser.php?d=<?php echo $Home_Directory; ?>' class='icon_link'>
		<img src='icons/home.png' width='16'>
	</a>
	Directory: <?php echo preg_replace('/^data/','',$Main_Directory); ?></th>
</tr>
<tr style='height:16px;'>
	<th class='browseth' style="padding-left:10px;">
<?php
	if (isset($_GET['srev'])){
		if ($_GET['srev'] == "false"){
			echo '<a href="main_browser.php?sort=true&sopt=typ&srev=true" class="tlink">';
		}else{
			echo '<a href="main_browser.php?sort=true&sopt=typ&srev=false" class="tlink">';
		}
	}else{
		echo '<a href="main_browser.php?sort=true&sopt=typ&srev=true" class="tlink">';
	}
?>
		<img src="icons/dir.png" width="16">
		 <img src="icons/sort.png">
		</a>
	</th>
	<th class='browseth'>
<?php
        if (isset($_GET['srev'])){
                if ($_GET['srev'] == "false"){
                        echo '<a href="main_browser.php?sort=true&sopt=name&srev=true" class="tlink">';
                }else{
                        echo '<a href="main_browser.php?sort=true&sopt=name&srev=false" class="tlink">';
                }
        }else{
                echo '<a href="main_browser.php?sort=true&sopt=name&srev=true" class="tlink">';
        }
?> 		Name 
		<img src="icons/sort.png">
		</a>
	</th>
	<th class='browseth'>
<?php
        if (isset($_GET['srev'])){
                if ($_GET['srev'] == "false"){
                        echo '<a href="main_browser.php?sort=true&sopt=ctime&srev=true" class="tlink">';
                }else{
                        echo '<a href="main_browser.php?sort=true&sopt=ctime&srev=false" class="tlink">';
                }
        }else{
                echo '<a href="main_browser.php?sort=true&sopt=ctime&srev=true" class="tlink">';
        }
?>  
		Timestamp 
		<img src="icons/sort.png">
		</a>
	</th>
	<th class='browseth'>
<?php
        if (isset($_GET['srev'])){
                if ($_GET['srev'] == "false"){
                        echo '<a href="main_browser.php?sort=true&sopt=filename&srev=true" class="tlink">';
                }else{
                        echo '<a href="main_browser.php?sort=true&sopt=fileinfo&srev=false" class="tlink">';
                }
        }else{
                echo '<a href="main_browser.php?sort=true&sopt=fileinfo&srev=true" class="tlink">';
        }
?>  
		Fileinfo 
		<img src="icons/sort.png">
		</a>
	</th>
	<th class='browseth'>
<?php
        if (isset($_GET['srev'])){
                if ($_GET['srev'] == "false"){
                        echo '<a href="main_browser.php?sort=true&sopt=size&srev=true" class="tlink">';
                }else{
                        echo '<a href="main_browser.php?sort=true&sopt=size&srev=false" class="tlink">';
                }
        }else{
                echo '<a href="main_browser.php?sort=true&sopt=size&srev=true" class="tlink">';
        }
?>  
		Size 
		<img src="icons/sort.png">
		</a>
	</th>
	<th class="browseth"> </th>
</tr>
<?php
if (isset($error)){
	echo $error;
	exit;
}
for ($i=0;$i<count($DATA);$i++){
	echo "<tr class='browsetr'>\n";
	echo "<td class='browsetd' style='padding-left:10px;'>\n";
	if ($DATA["$i"]['typ'] == "dir"){
		$next_directory = $DATA["$i"]['path'];
		echo "<img src='".$DATA["$i"]['icon_path']."' width='16'></td>\n";
		echo "<td class='browsetd'>
		<a href='main_browser.php?d=".$next_directory."' target='_self' class='link_list'>
		".htmlentities($DATA["$i"]['name'])."</a></td>\n";
	}else{
		if (!preg_match('/lock/', $DATA["$i"]['name'])){
			$dwload = $DATA["$i"]['path'];
			echo '<a href="main_browser.php?dwload='.$dwload.'">';
		}else{
			echo '<a class="various" data-fancybox-type="iframe" href="encryp_file.php?dwload='.$DATA["$i"]['path'].'">';
		}
		echo "<img src='".$DATA["$i"]['icon_path']."' width='16'></td>\n";
		echo "</a>\n";
		if (in_array($DATA["$i"]['fileend'], $script_types)){
			echo "<td class='browsetd' style='min-width:150px;'>
			<a class='script_view link_list' data-fancybox-type='iframe'
			id='link_scp' 
			href='script_highlight.php?sppath=".$DATA["$i"]['path']."&end=".$DATA["$i"]['fileend']."'>
			".$DATA["$i"]['name']."</span></a></td>\n";	
		}elseif (in_array($DATA["$i"]['fileend'], $readable_files)){
                        echo "<td class='browsetd' style='min-width:150px;'>";
                        echo "<a class='script_view link_list' data-fancybox-type='iframe'
                        id='link_scp'
                        href='file_view.php?f=".$DATA["$i"]['path']."&end=".$DATA["$i"]['fileend']."'>";
                        echo $DATA["$i"]['name']."</a></td>\n";
		}else{
			echo "<td class='browsetd' style='min-width:150px;'>";
	                if (!preg_match('/lock/', $DATA["$i"]['name'])){
	                        echo '<a href="main_browser.php?dwload='.$DATA["$i"]['path'].'" class="link_list">';
				echo $DATA["$i"]['name']."</a></td>\n";
	                }else{
	                        echo '<a class="various link_list" data-fancybox-type="iframe" href="encryp_file.php?dwload='.$DATA["$i"]['path'].'">';
	                	echo $DATA["$i"]['name']."</a></td>\n";
			}
		}
	}
        echo "<td class='browsetd' style='min-width:150px;'>".date("d.m.Y H:i:s", $DATA["$i"]['ctime'])."</td>\n";
        echo "<td class='browsetd' style='min-width:150px;'>".$DATA["$i"]['fileinfo']."</td>\n";
	if ($DATA["$i"]['typ'] == "dir"){
		echo "<td class='browsetd' style='min-width:150px;'>-</td>\n";
	}else{
        	echo "<td class='browsetd' style='min-width:150px;'>".formate_bytes($DATA["$i"]['size'])."</td>\n";
	}
	echo '<td align="right" style="padding-right:10px;">';
	if ($DATA["$i"]['typ'] == "file" and !preg_match('/lock/', $DATA["$i"]['name'])){
		echo '<a class="various" data-fancybox-type="iframe" href="crypt_data.php?dwload='.$DATA["$i"]['path'].'">';
		echo '<img src="icons/lock.png" width="16">';
		echo '</a>'."\n";
	}
        if ($DATA["$i"]['typ'] == "file"){
                echo '<a class="various" data-fancybox-type="iframe" href="fileshare.php?sharepath='.$DATA["$i"]['path'].'">';
                echo '<img src="icons/share.png" width="16">';
                echo '</a>'."\n";
        }
	echo '<a class="various" data-fancybox-type="iframe" href="rename.php?mvpath='.$DATA["$i"]['path'].'">';
	echo '
		<img src="icons/rename.png" width="16"></a>';
	$Main_Directory_dec = $Main_Directory;
	$rm_path = trim($DATA["$i"]['path']);
	echo '<a href="main_browser.php?rm=true&rmpath='.$rm_path.'&d='.$Main_Directory_dec.'" target="_self" style="text-decoration:none;border:0px;">';
	echo '
		<img src="icons/del.png" width="16"></a>
	</td>'."\n";
	echo "</tr>\n";
}
?>
</table>
</body>
</html>
