<?php
include "access.php";
$free_space = $quota-$quota_usd;
$free_space = ($free_space/1024)/1024;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>simpleCloud</title>
<link rel="stylesheet" href="css/main.css" type="text/css" />
<link href="dropzone/css/dropzone.css" type="text/css" rel="stylesheet" />
<script src="dropzone/dropzone.min.js"></script>
<script type="text/javascript">
function loadMainFrame(){
        parent.parent.window.frames["mainframe"].location.reload();
	parent.showHint();
}
</script>
<script type="text/javascript">
Dropzone.options.dropzone = {
maxFilesize: <?php echo $free_space; ?>,
};
</script>
</head>
<body onload="setInterval(loadMainFrame, 4000);" class='actionframe'>
<?php
if (isset($_COOKIE['curdir'])){
        $storeFolder_tmp = $_COOKIE['curdir'];
        $decrypte_blow = new Blowfish($SESSION_UNIQ_KEY);
        $storeFolder = $decrypte_blow->Decrypt($storeFolder_tmp);
}else{
        $storeFolder = $Homedir;
}
?>
<form action="do_upload.php" class="dropzone" id="dropzone">
</form> 
<p style='color:#FFFFFF;'>The files are saved in <b><?php echo trim(preg_replace('/^data/','',$storeFolder));?> </b></p>

</body>
</html>
