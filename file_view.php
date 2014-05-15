<?php
include "access.php";

if (isset($_GET['f'])){
	$filepath = trim($_GET['f']);
}
if (isset($_GET['end'])){
	$fileend = trim($_GET['end']);
}
include "read.php";
$main_css = " ";
$ambilight = "#ffffff";
switch ($fileend){
	case 'doc':
	case 'docx':
		$random = rand(1000,9999);
		$name = basename($filepath);
		$tmpfile = "/tmp/".$random."_".$name.".html";
		while (file_exists($tmpfile)){
			$random = rand(1000,9999);
	                $tmpfile = "/tmp/".$random."_".$name.".html";
		}
		shell_exec('abiword --to="html" --to-name="'.$tmpfile.'" '.$filepath);
		$DATA = file_get_contents($tmpfile);
		unlink($tmpfile);
	break;
	case 'xls':
	case 'xlsx':
		$random = rand(1000,9999);
                $name = basename($filepath);
                $tmpfile = "/tmp/".$random."_".$name.".html";
                while (file_exists($tmpfile)){
                        $random = rand(1000,9999);
                        $tmpfile = "/tmp/".$random."_".$name.".html";
                }
                shell_exec('xlhtml '.$filepath.' > '.$tmpfile);
                $DATA = file_get_contents($tmpfile);
                unlink($tmpfile);
	break;
	case 'png':
	case 'jpg':
	case 'jpeg':
	case 'gif':
	case 'bmp':
		$onlypath_rel = str_replace("/".basename($filepath),'',$filepath);
                $Image_Files_all = get_file_list($onlypath_rel);
                $allowed_format = array('png','jpg','jpeg','gif','bmp');
                $count_format = 0;
		$Image_Files = array();
                for ($s=0;$s<count($Image_Files_all);$s++){
                        if (in_array($Image_Files_all["$s"]['fileend'], $allowed_format)){
                                $Image_Files["$count_format"]['name'] = $Image_Files_all["$s"]['name'];
                                $Image_Files["$count_format"]['path'] = $Image_Files_all["$s"]['path'];
                                $Image_Files["$count_format"]['fileend'] = $Image_Files_all["$s"]['fileend'];
                                $count_format++;
                        }
                }
                $base_filename = trim(basename($filepath));
                for ($i=0;$i<count($Image_Files);$i++){
                        if ($base_filename == $Image_Files["$i"]['name']){
                                $Hot_key = $i;
                        }
                }
                $NEXT = " ";
                $ne_key = $Hot_key-1;
                $pl_key = $Hot_key+1;
                $space = " ";
                $readable_files2 = array(
                'png','jpg','jpeg','gif','bmp'
		);

                if (isset($Image_Files["$ne_key"]['path']) and in_array($Image_Files["$ne_key"]['fileend'],$readable_files2)){
                        $NEXT = $NEXT."<a href='file_view.php?f=".$Image_Files["$ne_key"]['path']."&end=".$Image_Files["$ne_key"]['fileend']."'><img src='icons/left.png'></a>";
                $space = "style='padding-left:10px'";
                }
                if (isset($Image_Files["$pl_key"]['path']) and in_array($Image_Files["$pl_key"]['fileend'],$readable_files2)){
                        $NEXT = $NEXT."<a $space href='file_view.php?f=".$Image_Files["$pl_key"]['path']."&end=".$Image_Files["$pl_key"]['fileend']."'><img src='icons/right.png'></a>";
                }

		$DATA = "<center><br><br><img src='$filepath' class='imgview_ambilight'><br>".$NEXT."</center>";
		$main_css = "class='actionframe'";
		list($width, $height, $type) = getimagesize($filepath);

		switch ($fileend) {
			case 'gif':
				$img = imagecreatefromgif($filepath);
				break;
			case 'jpg':
				$img = imagecreatefromjpeg($filepath);
				break;
			case 'png':
				$img = imagecreatefrompng($filepath);
				break;
		}

		$img_r = imagecreatetruecolor(1, 1);
		imagecopyresampled($img_r, $img, 0, 0, 0, 0, 1, 1, $width, $height);

		imagedestroy($img);

		$rgb = imagecolorat($img_r, 0, 0);

		imagedestroy($img_r);

		$r = ($rgb >> 16) & 0xFF;
		$g = ($rgb >> 8) & 0xFF;
		$b = $rgb & 0xFF;
		$ambilight = sprintf('#%02X%02X%02X', $r, $g, $b);

	break;
	case 'mp4':
	case 'webm':
	case 'ogv':
		$file_source = array(
		'mp4' => '<source src="'.$filepath.'" type="video/mp4" />',
		'ogv' => '<source src="'.$filepath.'" type="video/ogg" />',
		'webm' => '<source src="'.$filepath.'" type="video/webm" />'
		);
		$DATA = '
		  <video id="example_video_1" class="video-js vjs-default-skin" controls preload="none" width="100%" height="100%"
		      poster="icons/simpleCloud.png"
			data-setup="{}">
		'.$file_source["$fileend"].'
		  </video>
		';
	break;
	case 'mp3':
	case 'wav':
	case 'ogg':
		$sound_arr = array("mp3" => "audio/mpeg", "wav" => "audio/wav", "ogg" => "audio/ogg");
		require_once('getid3/getid3.php');
		$getID3 = new getID3;
		$ThisFileInfo = $getID3->analyze($filepath);
		if ('image/jpeg' == trim($ThisFileInfo['id3v2']['APIC'][0]['image_mime'])){
			$img_base64 = "data:image/jpeg;base64,".base64_encode($ThisFileInfo['id3v2']['APIC'][0]['data']);
		}elseif (preg_match('/png/', $ThisFileInfo['id3v2']['APIC'][0]['image_mime'])){
			$img_base64 = "data:image/png;base64,".base64_encode($ThisFileInfo['id3v2']['APIC'][0]['data']);
		}elseif (preg_match('/gif/', $ThisFileInfo['id3v2']['APIC'][0]['image_mime'])){
                        $img_base64 = "data:image/gif;base64,".base64_encode($ThisFileInfo['id3v2']['APIC'][0]['data']);
                }else{
			$img_base64 = "icons/mp3.png";
		}
	        $onlypath_rel = str_replace("/".basename($filepath),'',$filepath);
	        $Audio_Files_all = get_file_list($onlypath_rel);
		$allowed_format = array('mp3','wav','ogg');
		$count_format = 0;
		for ($s=0;$s<count($Audio_Files_all);$s++){
			if (in_array($Audio_Files_all["$s"]['fileend'], $allowed_format)){
				$Audio_Files["$count_format"]['name'] = $Audio_Files_all["$s"]['name'];
				$Audio_Files["$count_format"]['path'] = $Audio_Files_all["$s"]['path'];
				$Audio_Files["$count_format"]['fileend'] = $Audio_Files_all["$s"]['fileend'];
				$count_format++;
			}
		}
	        $base_filename = trim(basename($filepath));
	        for ($i=0;$i<count($Audio_Files);$i++){
	                if ($base_filename == $Audio_Files["$i"]['name']){
	                        $Hot_key = $i;
	                }
	        }
	        $NEXT = " ";
	        $ne_key = $Hot_key-1;
	        $pl_key = $Hot_key+1;
		$right_button = false;
		$left_button = false;
	        $space = " ";
	        $readable_files2 = array(
	        'wav','ogg','mp3'
	        );

	        if (isset($Audio_Files["$ne_key"]['path']) and in_array($Audio_Files["$ne_key"]['fileend'],$readable_files2)){
	                $NEXT = $NEXT."<div  style='float:left;'><a href='file_view.php?f=".$Audio_Files["$ne_key"]['path']."&end=".$Audio_Files["$ne_key"]['fileend']."'><img src='icons/left.png' class='nextbutton'></a></div>";
	        	$space = "style='padding-left:10px'";
			$left_button = true;
	        }
		$jump_to_next = " ";
		if ($left_button == false){
			$NEXT = $NEXT."<div  style='float:left;visibility:hidden;'><img src='icons/left.png' class='nextbutton'></div>";
		}
	        if (isset($Audio_Files["$pl_key"]['path']) and in_array($Audio_Files["$pl_key"]['fileend'],$readable_files2)){
	                $NEXT = $NEXT."<div style='float:right;'><a $space href='file_view.php?f=".$Audio_Files["$pl_key"]['path']."&end=".$Audio_Files["$pl_key"]['fileend']."'><img src='icons/right.png' class='nextbutton'></a></div>";
	        	$jump_to_next = ' onended="self.location.href=\'file_view.php?f='.$Audio_Files["$pl_key"]['path']."&end=".$Audio_Files["$pl_key"]['fileend'].'&play=auto\'"';
			$right_button = true;
		}
		if ($right_button == false){
                        $NEXT = $NEXT."<div  style='float:right;visibility:hidden;'><img src='icons/right.png' class='nextbutton'></div>";
                }

		if (isset($_GET['play'])){
			if ($_GET['play'] == "auto"){
				$jump_to_next = " autoplay='autoplay'".$jump_to_next;
			}
		}
		$DATA = "<center><br>";
                if (isset($ThisFileInfo['tags']['id3v2']['title'][0])){
                        $title_info = $ThisFileInfo['tags']['id3v2']['title'][0];
                }else{
                        $title_info = "Unknown";
                }
		if (isset($ThisFileInfo['id3v1']['artist'][0])){
			$artist_info = $ThisFileInfo['id3v1']['artist'];
		}else{
			$artist_info = "Unknown";
		}
                if (isset($ThisFileInfo['playtime_string'])){
                        $playtime_info = $ThisFileInfo['playtime_string'];
                }else{
                        $playtime_info = "Unknown";
                }
                if (isset($ThisFileInfo['audio']['bitrate'])){
                        $bitrate_info = $ThisFileInfo['audio']['bitrate'];
                }else{
                        $bitrate_info = "Unknown";
                }

                if (isset($ThisFileInfo['id3v1']['album'][0])){
                        $album_info = $ThisFileInfo['id3v1']['album'];
                }else{
                        $album_info = "Unknown";
                }
		if (isset($ThisFileInfo['tags']['id3v2']['title'][0])){
			$DATA = $DATA."<b>".$ThisFileInfo['tags']['id3v2']['title'][0]."</b><br>";
		}
		$DATA = $DATA."<br><img src='".$img_base64."' class='audioimg' id='flipimg'><div id='flipbox' style='height:200px;width:300px'>
		<b>Information</b>
		<p style='text-align:left;'>
		<b>Title: </b>".$title_info."<br>
		<b>Artist: </b>".$artist_info."<br>
		<b>Album: </b>".$album_info."<br>
                <b>Playtime: </b>".$playtime_info."<br>
                <b>Bitrate: </b>".$bitrate_info."<br>
		</p>
		</div></center>";
		if (preg_match("/Chrome/", $_SERVER['HTTP_USER_AGENT'])){
			$DATA = 
			'<iframe src="visualize.php?filepath='.$filepath.'" width="100%" height="100%" style="border:0px;"></iframe>
			';
		}else{
			$main_css = "class='actionframe'";
			$DATA = $DATA.
			'<center><br><audio controls class="audiomenu"'.$jump_to_next.' id="audio_spec">
			  <source src="'.$filepath.'" type="'.$sound_arr["$fileend"].'">
			</audio><br>'.$NEXT.'
		<canvas id="canvas_spec" width="400px" height="100px" style="background-color:transparent;"></canvas>
		</center>
            <script type="text/javascript">
              var audio5 = document.getElementById("audio_spec");
              var canvas5 = document.getElementById("canvas_spec");
              var context5 = canvas5.getContext(\'2d\');
              canvas5.strokeStyle = "#ffffff";
              context5.lineWidth = 4;
              audio5.addEventListener("MozAudioAvailable", writeSamples, false);
              audio5.addEventListener("loadedmetadata", getMetadata, false);
              var fbLength, channels, rate;
              function getMetadata() {
                channels = audio5.mozChannels;
                fbLength = audio5.mozFrameBufferLength;
                rate     = audio5.mozSampleRate;
                fft = new FFT(fbLength / channels, rate);
              }
              function writeSamples (event) {
                var data = event.frameBuffer;
                var length = data.length / channels;
                var signal = new Float32Array(length);
                for (var i = 0; i < length; i++ ) {
                  if (channels == 2) {
                    // merge channels into a stereo-mix mono signal
                    signal[i] = (data[2*i] + data[2*i+1]) / 2;
                  } else { // assume no more than 2 channels of data
                    signal[i] = data[i];
                  }
                }
                fft.forward(signal);
                context5.clearRect(0,0, 400, 100);
                for (var i = 0; i < fft.spectrum.length; i++ ) {
                  // multiply spectrum by a zoom value
                  magnitude = fft.spectrum[i] * 4000;
                  // Draw rectangle bars for each frequency bin
                  context5.fillRect(i * 4, canvas5.height, 3, -magnitude);
                }
              }
            </script>

			';
		}
	break;

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>simpleCloud</title>
<link rel="stylesheet" href="css/main.css" type="text/css" />
<link href="video-js/video-js.css" rel="stylesheet" type="text/css">
<script src="video-js/video.js"></script>
<script src="jquery.min.js"></script>
<script src="dsp.js"></script>
<script>
    videojs.options.flash.swf = "video-js/video-js.swf";
</script>

<script type="text/javascript">
function loadMainFrame(){
        parent.window.frames["mainframe"].location.reload();
}
</script>
<style>
.imgview_ambilight {
	max-height:400px;
	-moz-box-shadow:0px 0px 25px 21px<?php echo $ambilight; ?>;
        -webkit-box-shadow:0px 0px 25px 21px <?php echo $ambilight; ?>;
        box-shadow:0px 0px 25px 21px <?php echo $ambilight ?>;
}
</style>
<script type="text/javascript">
$(document).ready(function(){
  $("#flipbox").hide();
  $("#flipimg").click(function(){
	$("#flipimg").hide();
	$("#flipbox").fadeIn();
  });
  $("#flipbox").click(function(){
        $("#flipbox").hide();
        $("#flipimg").fadeIn();
  });
}); 
</script>

</head>
<body <?php echo $main_css; ?> style="overflow:hidden;">
<?php
if (isset($DATA)){
	echo $DATA;
}
?>
</body>
</html>
