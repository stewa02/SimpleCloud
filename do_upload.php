<?php
include "access.php";

$ds = DIRECTORY_SEPARATOR;
if (isset($_COOKIE['curdir'])){
	$storeFolder_tmp = $_COOKIE['curdir'];
	$decrypte_blow = new Blowfish($SESSION_UNIQ_KEY);
	$storeFolder = $decrypte_blow->Decrypt($storeFolder_tmp); 
}else{
	$storeFolder = "testdir";
}
if (!empty($_FILES)) {
    $tempFile = $_FILES['file']['tmp_name'];             
    $targetPath = trim(dirname( __FILE__ )) . $ds. trim($storeFolder) . $ds; 
    $targetFile =  $targetPath. $_FILES['file']['name'];
    $remove_chars = array(')','(','#','@','{','}','[',']','!','?','$','+',',','%','&','=',"'",'`','^',';');
    for ($p=0;$p<count($remove_chars);$p++){
	 $targetFile = str_replace($remove_chars["$p"], '', $targetFile);
    }
    $targetFile = str_replace(' ', '_', $targetFile);
    if (!file_exists($targetFile)){
        $get_size = true;
    }else{
        $get_size = false;
    }
    $res = mysql_query('select * from user where id="'.$id.'"');
    while ($entry = mysql_fetch_array($res, MYSQL_ASSOC)) {
        $quota_cur_usd = $entry['usage_quota'];
	$max_size = $entry['quota'];
    }
    $upload_size = $_FILES["file"]["size"];
    $total_stored_size = $upload_size + $quota_cur_usd;
    if ($max_size > $total_stored_size){
    	move_uploaded_file($tempFile,$targetFile);
    	if ($get_size){
    		$filesize = $_FILES["file"]["size"];
    		$filesize = $filesize + $quota_cur_usd;
    		mysql_query('update user set usage_quota="'.$filesize.'" where id="'.$id.'"');
   	}
    }
}
?>
