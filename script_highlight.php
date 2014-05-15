<?php
include "access.php";
if (isset($_GET['end'])){
	$Language = trim($_GET['end']);
}
if (isset($_GET['sppath'])){
	$filepath = trim($_GET['sppath']);
}
include "set_hightlight.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>simpleCloud</title>
<link rel="stylesheet" href="css/main.css" type="text/css" />

<script type="text/javascript" src="synhighlight/scripts/shCore.js"></script>
 
<script type="text/javascript" src="synhighlight/scripts/<?php echo $Script_brush; ?>"></script>
 
<link href="synhighlight/styles/shCore.css" rel="stylesheet" type="text/css" />
<link href="synhighlight/styles/shThemeDefault.css" rel="stylesheet" type="text/css" />

</head>
<body>
	<pre class="brush: <?php echo $balias; ?>" >
<?php
echo file_get_contents($filepath);

?>
	</pre>
 
<script type="text/javascript">
     SyntaxHighlighter.all()
</script>
</body>
</html>
