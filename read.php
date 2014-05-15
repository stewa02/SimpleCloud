<?php
function check_path($cur_dir){
	if (preg_match('/\.\./', $cur_dir) or preg_match('/\./', $cur_dir) or preg_match('/^\//', $cur_dir)){
		return FALSE;
	}else{
		if (preg_match('/[\$|<|>|\^|\&|\*|\@|~]/', $cur_dir) or !file_exists($cur_dir)){
			return FALSE;
		}else{
			if (!preg_match('/^\//', $cur_dir)){
				return TRUE;
			}else{
				return FALSE;
			}
		}
	}
}
function del_data($path){
	if (is_dir($path)){
		SureRemoveDir($path,true);
	}elseif(is_file($path)){
                unlink($path);
	}

}
function SureRemoveDir($dir, $DeleteMe) {
    if(!$dh = @opendir($dir)) return;
    while (false !== ($obj = readdir($dh))) {
        if($obj=='.' || $obj=='..') continue;
        if (!@unlink($dir.'/'.$obj)) SureRemoveDir($dir.'/'.$obj, true);
    }

    closedir($dh);
    if ($DeleteMe){
        @rmdir($dir);
    }
}
function foldersize($directory)
{
    $dirSize=0;
     
    if(!$dh=opendir($directory))
    {
        return false;
    }
     
    while($file = readdir($dh))
    {
        if($file == "." || $file == "..")
        {
            continue;
        }
         
        if(is_file($directory."/".$file))
        {
            $dirSize += filesize($directory."/".$file);
        }
         
        if(is_dir($directory."/".$file))
        {
            $dirSize += foldersize($directory."/".$file);
        }
    }
     
    closedir($dh);
     
    return $dirSize;
}
function rename_data($path,$newpath){
	$nameparts = preg_split('/\./', basename($path));
	$newnameend = str_replace(trim($nameparts[0]), '', basename($path));
	rename($path,$newpath.$newnameend);
}
function download_file($filename){
	$finfo = finfo_open(FILEINFO_MIME_TYPE);
	$header_mine_type = finfo_file($finfo, $filename);
	finfo_close($finfo);
	header('Content-Description: File Transfer');
	header('Content-Type: '.$header_mine_type);
	header('Content-Disposition: attachment; filename='.basename(trim($filename)));
	header('Content-Transfer-Encoding: binary');
	header('Expires: 0');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Pragma: public');
	header('Content-Length: ' . filesize(trim($filename)));
	ob_clean();
	flush();
	readfile(trim($filename));
}
function dir_is_empty($cur_dir){
	$counter = 0;
	$handle = opendir($cur_dir);
	while ($file = readdir($handle)){
		if (($file !== '.' and $file !== '..') and !preg_match('/^\./', $file)){
			$counter++;
		}
	}
	closedir($handle);
	if ($counter == 0){
		return TRUE;
	}else{
		return FALSE;
	}
}
function getDirectory($path,$file_tree,$level=0){ 
    $ignore = array('cgi-bin', '.', '..','.svn');
    $all_dirs = array();
    $dh = @opendir( $path );
    while( false !== ( $file = readdir( $dh ) ) ){
        if( !in_array( $file, $ignore ) and is_dir("$path/$file") ){
            $spaces = str_repeat( '-', ($level) );
            if(is_dir("$path/$file")){
		writefile($level.' '.$path.'/'.$file,$file_tree);
                getDirectory("$path/$file", $file_tree,($level+1));
            }else{
		writefile($level.' '.$path.'/'.$file,$file_tree);
            }
        }
    }
    closedir( $dh );
}
function writefile($value,$file_tree){
        $file_handle = fopen($file_tree, 'a');
	fwrite($file_handle, $value."\n");
	fclose($file_handle);
}
function get_file_list($cur_dir, $ftyp="folder_file",$sort_flag="name", $reverse=false){
	require_once("icon_list.php");
	require_once("formate_premissions.php");
	$file_list = array();
	$counter = 0;
	$handle = opendir($cur_dir);
	while ($file = readdir($handle)){
		$access = false;
		if ($ftyp == "file"){
			if (!is_file($curdir."/".$file)){
				$access = true;
			}
		}elseif ($ftyp == "folder"){
			if (!is_dir($curdir."/".$file)){
                                $access = true;
                        }
		}
		if ((($file !== '.' and $file !== '..') and !preg_match('/^\./', $file)) and $access != true){
			// Filename
			$file_list["$counter"]["name"] = $file;
			$filename_parts = preg_split('/\./', $file);
			$filepath = $cur_dir."/".$file;
			$last_format = count($filename_parts)-1;
			$file_list["$counter"]["fileend"] = strtolower(trim($filename_parts["$last_format"]));
			//Filetyp [dir|file|link|unknow]
			if (is_dir($filepath)){
				$file_list["$counter"]["typ"] = "dir";
				$file_list["$counter"]["icon_path"] = "icons/dir.png";
				$file_list["$counter"]["fileinfo"] = "Directory";
                        	$file_list["$counter"]["size"] = 0;

			}elseif (is_link($filepath)){
				$file_list["$counter"]["typ"] = "link";
				$file_list["$counter"]["icon_path"] = "icons/link.png";
				$file_list["$counter"]["fileinfo"] = "Symbolic Link";
				$file_list["$counter"]["size"] = 0;
			}elseif(is_file($filepath)){
				$file_list["$counter"]["typ"] = "file";
				$file_list["$counter"]["icon_path"] = get_icon_path($filename_parts["$last_format"],$filepath);
				$file_list["$counter"]["fileinfo"] = get_icon_path($filename_parts["$last_format"], $filepath,false);
	                        $file_list["$counter"]["size"] = filesize($filepath);

			}else{
				$file_list["$counter"]["typ"] = "unknow";
				$file_list["$counter"]["icon_path"] = get_icon_path($filename_parts["$last_format"], $filepath);
				$file_list["$counter"]["fileinfo"] = get_icon_path($filename_parts["$last_format"], $filepath,false);
				$file_list["$counter"]["size"] = filesize($filepath);
			}
			
			// Get the changetime of the file
			$file_list["$counter"]["ctime"] = filectime($filepath);

			// Get Path
			$file_list["$counter"]["path"] = $filepath;	
		
			// Get premissions of the file
			$file_list["$counter"]["perm"] = substr(sprintf('%o', fileperms($filepath)), -4);
			$file_list["$counter"]["symbolic_perm"] = symbolic_perm(substr(sprintf('%o', fileperms($filepath)), -4));	
			$counter++;
		}
	}
	closedir($handle);
	foreach ($file_list as $key => $row){
		$sort_handle[$key] = $row["$sort_flag"];
	}
	switch ($sort_flag){
		case 'name':
		case 'typ':
		case 'fileinfo':
			array_multisort($sort_handle, SORT_STRING, $file_list);
		break;
		case 'perm':
		case 'ctime':
		case 'size':
			array_multisort($sort_handle, SORT_NUMERIC, $file_list);
		break;
	}
	if ($reverse == true){
		$file_list = array_reverse($file_list);
	}
	return $file_list;
}
function get_file_info($filepath){
        require_once("icon_list.php");
        require_once("formate_premissions.php");
	// Filename
	$file_list["name"] = basename($filepath);
	$filename_parts = preg_split('/\./', basename($filepath));
	$last_format = count($filename_parts)-1;
	$file_list["fileend"] = strtolower(trim($filename_parts["$last_format"]));
	//Filetyp [dir|file|link|unknow]
	if (is_dir($filepath)){
		$file_list["typ"] = "dir";
	        $file_list["icon_path"] = "icons/dir.png";
	        $file_list["fileinfo"] = "Directory";
	        $file_list["size"] = 0;
	}elseif (is_link($filepath)){
	        $file_list["typ"] = "link";
	        $file_list["icon_path"] = "icons/link.png";
	        $file_list["fileinfo"] = "Symbolic Link";
	        $file_list["size"] = 0;
	}elseif(is_file($filepath)){
	        $file_list["typ"] = "file";
	        $file_list["icon_path"] = get_icon_path($filename_parts["$last_format"],$filepath);
	        $file_list["fileinfo"] = get_icon_path($filename_parts["$last_format"], $filepath,false);
	        $file_list["size"] = filesize($filepath);
	}else{
	        $file_list["typ"] = "unknow";
	        $file_list["icon_path"] = get_icon_path($filename_parts["$last_format"], $filepath);
	        $file_list["fileinfo"] = get_icon_path($filename_parts["$last_format"], $filepath,false);
	        $file_list["size"] = filesize($filepath);
	}

	// Get the changetime of the file

	// Get Path
	$file_list["path"] = $filepath;
	return $file_list;

}
//$Main_Directory = "testdir";
//if (file_exists("foldertree.txt")){
//	unlink("foldertree.txt");
//}
//getDirectory($Main_Directory);
//if(check_path($Main_Directory)){
//	if (dir_is_empty($Main_Directory)){
//		echo "Folder is empty";
//	}else{
	//Funktion: get_file_list( <<Directory>>, <<List only [file|folder|file_folder]>>,<<Zu sortierende Spalte>>, <<Reverse Array>>);
//		print_r(get_file_list($Main_Directory));
//	}
//}
?>
