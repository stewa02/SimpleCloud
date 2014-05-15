<?php
require_once("access.php");
echo '<div style="padding-left:8px;color:#FFF;">';
include "bytes_formate.php";
$total_space = $quota;
$prozentual = round(((100/$total_space)*$quota_usd),0);
if ($prozentual <= 50){
	$balke = "balken1";
}elseif($prozentual > 50 and $prozentual < 75){
	$balke = "balken2";
}else{
	$balke = "balken3";
}
$px = $prozentual*2;
echo "
<div style='width:200px; border:1px solid black; height:100%;float:left;' class='mainbalken'>
<div style='width:".$px."px; height:15px; text-align:center;' id='usage_balke' 
class='".$balke."'> 
</div></div>\n";
echo "<div style='float:right;padding-right:8px;font-size:10pt;'> ".$prozentual."% Used of ".formate_bytes($total_space)."</div>";
echo "</div>";
?>
