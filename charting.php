<?php
include "access.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>simpleCloud</title>
<link rel="stylesheet" href="css/main.css" type="text/css" />
<!--[if lt IE 9]><script language="javascript" type="text/javascript" src="dist/excanvas.js"></script><![endif]-->
<script language="javascript" type="text/javascript" src="dist/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="dist/jquery.jqplot.min.js"></script>
<link rel="stylesheet" type="text/css" href="dist/jquery.jqplot.css" />
<script type="text/javascript" src="dist/plugins/jqplot.pieRenderer.min.js"></script>
<script type="text/javascript" src="dist/plugins/jqplot.donutRenderer.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
  var data = [
<?php
$quota_usd_get = $_GET['quota_usd'];
$quota_get = $_GET['quota'];
$proz_usd = round((100/$quota_get)*$quota_usd_get,0);
$proz_free = 100-$proz_usd
?>

    ['Free Space', <?php echo $proz_free; ?>],['Used Space', <?php echo $proz_usd; ?>]
  ];
  var plot1 = jQuery.jqplot ('chart1', [data],
    {
      seriesDefaults: {
        // Make this a pie chart.
        renderer: jQuery.jqplot.PieRenderer,
        rendererOptions: {
          // Put data labels on the pie slices.
          // By default, labels show the percentage of the slice.
          showDataLabels: true
        }
      },
      legend: { show:true, location: 'e' }
    }
  );
});
</script>
</head>
<body class="actionframe">
<br>
<br>
<a href="admin.php" style='margin-left:20px;padding-top:30px;'><img src='icons/left.png'></a>
<center>
<p><b>Used space of quota</b></p>
<br>
<div id="chart1" name="chart1"  style="height:400px;width:500px;">
</div>
</center>
</body>
</html>
