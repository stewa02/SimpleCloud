<?php
include "access.php";
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="chrome=1" />
<link href="visualize/common.css" rel="stylesheet" type="text/css">
<style>
html, body {
  overflow: hidden;
  margin: 0;
  padding: 0;
}
body > section {
  display: -webkit-flex;
  -webkit-flex-direction: column;
  -webkit-justify-content: center;
  -webkit-align-content: center;
  -webkit-align-items: center;
  box-sizing: border-box;
  height: 100%;
  -webkit-perspective: 800;
  -webkit-transform-style: preserve-3d;
}
section > * {
  display: -webkit-flex;
  -webkit-align-items: center;
}
.fft {
  position: absolute;
  -webkit-box-reflect: below 5px -webkit-linear-gradient(top, transparent, transparent 50%, rgba(255,255,255,0.2));
}
#fft {
  -webkit-transform: translateX(-350px) rotateX(-30deg) rotateY(30deg);
}
#fft2 {
  -webkit-transform: translateX(-530px) rotateX(-30deg) rotateY(-30deg);
}
aside {
  position: absolute;
  left: 1em;
  top: 3em;
  z-index: 10;
}
label {
  cursor: pointer;
}
#myaudio {
  opacity: 0;
  -webkit-transition: all 0.3s ease-in-out;
}
#myaudio.show {
  opacity: 1;
}
#playbutton {
  margin-top:60px;
  cursor: pointer;
}
#playbutton.playing {
  opacity: 0.1;
}
#current-time {
  font-size: 100px;
  position: absolute;
  z-index: -1;
  right: 1em;
  opacity: 0.5;
  top: 0.25em;
  text-shadow: 0px 1px 0px rgba(255, 255, 255, 0.9), 0px -1px 0px rgba(0, 0, 0, 0.7);
  color: transparent;
  font-weight: 400;
}
</style>
</head>
<body>

<aside>
  <div id="myaudio"></div>
<?php
if(isset($_GET['filepath'])){
	include "read.php";
        require_once('getid3/getid3.php');
	$filepath = trim($_GET['filepath']);
        $getID3 = new getID3;
        $ThisFileInfo = $getID3->analyze($filepath);
	$filepath = trim($_GET['filepath']);
	$onlypath = str_replace(basename($filepath),'',$filepath);
	$onlypath_rel = str_replace("/".basename($filepath),'',$filepath);
        $Audio_Files = get_file_list($onlypath_rel);
        $base_filename = trim(basename($filepath));
        for ($i=0;$i<count($Audio_Files);$i++){
                if ($base_filename == $Audio_Files["$i"]['name']){
                	$Hot_key = $i;
        	}
        }
	$NEXT = " ";
	$ne_key = $Hot_key-1;
	$pl_key = $Hot_key+1;
	$space = " ";
	$readable_files2 = array(
	'wav','ogg','mp3'
	);
	if (isset($Audio_Files["$ne_key"]['path']) and in_array($Audio_Files["$ne_key"]['fileend'],$readable_files2)){
		$NEXT = $NEXT."<a href='file_view.php?f=".$Audio_Files["$ne_key"]['path']."&end=".$Audio_Files["$ne_key"]['fileend']."'><img src='icons/left.png'></a>";
	$space = "style='padding-left:10px'";
	}
        if (isset($Audio_Files["$pl_key"]['path']) and in_array($Audio_Files["$pl_key"]['fileend'],$readable_files2)){
                $NEXT = $NEXT."<a $space href='file_view.php?f=".$Audio_Files["$pl_key"]['path']."&end=".$Audio_Files["$pl_key"]['fileend']."'><img src='icons/right.png'></a>";
        	$jump_to_next = ' onended="self.location.href=\'file_view.php?f='.$Audio_Files["$pl_key"]['path']."&end=".$Audio_Files["$pl_key"]['fileend'].'&play=auto\'"';
	}
        if (preg_match('/jpeg/', $ThisFileInfo['id3v2']['APIC'][0]['image_mime'])){
        	$img_base64 = "data:image/jpg;base64,".base64_encode($ThisFileInfo['id3v2']['APIC'][0]['data']);
        }elseif (preg_match('/png/', $ThisFileInfo['id3v2']['APIC'][0]['image_mime'])){
        	$img_base64 = "data:image/png;base64,".base64_encode($ThisFileInfo['id3v2']['APIC'][0]['data']);
        }elseif (preg_match('/gif/', $ThisFileInfo['id3v2']['APIC'][0]['image_mime'])){
        	$img_base64 = "data:image/gif;base64,".base64_encode($ThisFileInfo['id3v2']['APIC'][0]['data']);
        }else{
        	$img_base64 = "icons/mp3.png";
        }

	echo "<img src='".$img_base64."' width='50px'>";
	if (isset($ThisFileInfo['tags']['id3v2']['title'][0])){
		echo "<span style='font-size:8pt;padding-left:10px;'>".$ThisFileInfo['tags']['id3v2']['title'][0].
		"<span style='padding-left:20px;'>".$NEXT."</span></span>";
	}

}
?>
</aside>

<section>
  <div style="margin-top:-350px;"><canvas id="playbutton" width="125" height="125"></canvas></div>
  <div>
    <canvas id="fft" class="fft" width="512" height="200"></canvas>
    <canvas id="fft2" class="fft" width="512" height="200"></canvas>
  </div>

  <h2 id="current-time"></h2>
</section>
<script>
(function() {
CanvasRenderingContext2D.prototype.line = function(x1, y1, x2, y2) {
  this.lineCap = 'round';
  this.beginPath();
  this.moveTo(x1, y1);
  this.lineTo(x2, y2);
  this.closePath();
  this.stroke();
}
CanvasRenderingContext2D.prototype.circle = function(x, y, r, fill_opt) {
  this.beginPath();
  this.arc(x, y, r, 0, Math.PI * 2, true);
  this.closePath();
  if (fill_opt) {
    this.fillStyle = 'rgba(0,0,0,1)';
    this.fill();
    this.stroke();
  } else {
    this.stroke();
  }
}
CanvasRenderingContext2D.prototype.rectangle = function(x, y, w, h, fill_opt) {
  this.beginPath();
  this.rect(x, y, w, h);
  this.closePath();
  if (fill_opt) {
    this.fillStyle = 'rgba(0,0,0,1)';
    this.fill();
  } else {
    this.stroke();
  }
}
CanvasRenderingContext2D.prototype.triangle = function(p1, p2, p3, fill_opt) {
  // Stroked triangle.
  this.beginPath();
  this.moveTo(p1.x, p1.y);
  this.lineTo(p2.x, p2.y);
  this.lineTo(p3.x, p3.y);
  this.closePath();
  if (fill_opt) {
    this.fillStyle = 'rgba(0,0,0,1)';
    this.fill();
  } else {
    this.stroke();
  }
}
CanvasRenderingContext2D.prototype.clear = function() {
  this.clearRect(0, 0, this.canvas.clientWidth, this.canvas.clientHeight);
}

var canvas = document.getElementById('playbutton');
var ctx = canvas.getContext('2d');
ctx.lineWidth = 4;

var R = canvas.width / 2;
var STROKE_AND_FILL = false;

canvas.addEventListener('mouseover', function(e) {
  if (this.classList.contains('playing')) {
    drawPauseButton(STROKE_AND_FILL);
  } else {
    drawPlayButton(STROKE_AND_FILL);
  }
  ctx.save();
  ctx.lineWidth += 3;
  ctx.circle(R, R, R - ctx.lineWidth + 1);
  ctx.restore();
}, true);

canvas.addEventListener('mouseout', function(e) {
  if (this.classList.contains('playing')) {
    drawPauseButton(STROKE_AND_FILL);
  } else {
    drawPlayButton(STROKE_AND_FILL);
  }
}, true);

canvas.addEventListener('click', function(e) {
  this.classList.toggle('playing');
  if (this.classList.contains('playing')) {
    drawPauseButton(STROKE_AND_FILL);
    audio.play();
<?php
if (isset($Audio_Files["$pl_key"]['path']) and in_array($Audio_Files["$pl_key"]['fileend'],$readable_files2)){
echo '
    audio.addEventListener(\'ended\',function(){
    self.location.href=\'file_view.php?f='.$Audio_Files["$pl_key"]['path']."&end=".$Audio_Files["$pl_key"]['fileend'].'&play=auto'."';";
echo '});';
}
?>
  } else {
    drawPlayButton(STROKE_AND_FILL);
    audio.pause();
  }
}, true);

function drawPlayButton(opt_fill) {
  ctx.clear();
  ctx.circle(R, R, R - ctx.lineWidth + 1, opt_fill);
  ctx.triangle({x: R*0.8, y: R*0.56}, {x: R*1.45, y: R}, {x: R*0.8, y: R*1.45}, true);
}

function drawPauseButton(opt_fill) {
  ctx.clear();
  ctx.circle(R, R, R - ctx.lineWidth + 1, opt_fill);
  ctx.save();
  ctx.lineWidth += 4;
  ctx.line(R*0.8, R/2, R*0.8, R*1.5);
  ctx.line(R+(R/5), R/2, R+(R/5), R*1.5);
  ctx.restore();
}
drawPlayButton(STROKE_AND_FILL);

window.playButton = canvas;
})();

(function() {
var canvas = document.getElementById('fft');
var ctx = canvas.getContext('2d');
canvas.width = document.body.clientWidth / 1.4;

var canvas2 = document.getElementById('fft2');
var ctx2 = canvas2.getContext('2d');
canvas2.width = canvas.width;

const CANVAS_HEIGHT = canvas.height;
const CANVAS_WIDTH = canvas.width;

window.audio = new Audio();
audio.src = '<?php 
if (isset($_GET['filepath'])){
	echo trim($_GET['filepath']);
}
?>';
audio.controls = true;
//audio.autoplay = true;
audio.loop = false;

var currenTimeNode = document.querySelector('#current-time');
audio.addEventListener('timeupdate', function(e) {
  var currTime = audio.currentTime;
  currenTimeNode.textContent = parseInt(currTime / 60) + ':' + parseInt(currTime % 60);
}, false);

document.querySelector('#myaudio').appendChild(audio);

// Check for non Web Audio API browsers.
if (!window.webkitAudioContext) {
  alert("Web Audio isn't available in your browser. But...you can still play the HTML5 audio :)");
  document.querySelector('#myaudio').classList.toggle('show');
  document.querySelector('aside').style.marginTop = '7em';
  return;
}

var context = new webkitAudioContext();
var analyser = context.createAnalyser();

function rafCallback(time) {
  window.webkitRequestAnimationFrame(rafCallback, canvas);

  var freqByteData = new Uint8Array(analyser.frequencyBinCount);
  analyser.getByteFrequencyData(freqByteData); //analyser.getByteTimeDomainData(freqByteData);

  var SPACER_WIDTH = 10;
  var BAR_WIDTH = 5;
  var OFFSET = 100;
  var CUTOFF = 23;
  var numBars = Math.round(CANVAS_WIDTH / SPACER_WIDTH);

  ctx.clearRect(0, 0, CANVAS_WIDTH, CANVAS_HEIGHT);
  ctx.fillStyle = '#F6D565';
  ctx.lineCap = 'round';

  ctx2.clearRect(0, 0, CANVAS_WIDTH, CANVAS_HEIGHT);
  ctx2.fillStyle = '#3A5E8C';
  ctx2.lineCap = 'round';

  // Draw rectangle for each frequency bin.
  /*for (var i = 0; i < numBars / 2 - CUTOFF; ++i) {
    var magnitude = freqByteData[i + OFFSET];
    ctx.fillRect(i * SPACER_WIDTH, CANVAS_HEIGHT, BAR_WIDTH, -magnitude);
  }
  for (var i = numBars / 2 + CUTOFF; i < numBars; ++i) {
    var magnitude = freqByteData[i + OFFSET];
    ctx2.fillRect(i * SPACER_WIDTH, CANVAS_HEIGHT, BAR_WIDTH, -magnitude);
  }*/
  for (var i = 0; i < numBars; ++i) {
    var magnitude = freqByteData[i + OFFSET];
    ctx.fillRect(i * SPACER_WIDTH, CANVAS_HEIGHT, BAR_WIDTH, -magnitude);
    ctx2.fillRect(i * SPACER_WIDTH, CANVAS_HEIGHT, BAR_WIDTH, -magnitude);
  }
}

function onLoad(e) {
  var source = context.createMediaElementSource(audio);
  source.connect(analyser);
  analyser.connect(context.destination);

  rafCallback();
}

// Need window.onload to fire first. See crbug.com/112368.
window.addEventListener('load', onLoad, false);
})();

window.addEventListener('keydown', function(e) {
  if (e.keyCode == 32) { // space
    // Simulate link click on an element.
    var evt = document.createEvent('Event');
    evt.initEvent('click', false, false);
    window.playButton.dispatchEvent(evt);
  }
}, false);
</script>
<!--[if IE]>
<script src="http://ajax.googleapis.com/ajax/libs/chrome-frame/1/CFInstall.min.js"></script>
<script>CFInstall.check({mode: 'overlay'});</script>
<![endif]-->
</body>
</html>
