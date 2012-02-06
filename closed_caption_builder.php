<?php
//if(!stristr($_SERVER['PHP_SELF'], 'cc/'))
	//header('Location: http://localhost/cc/closed_caption_builder.php');
//	echo $_SERVER['PHP_SELF'];
function formatTime($secs) {
	//formats time to mm:ss.xx
	$times = array(3600, 60, 1);
	$time = '';
	$tmp = '';
	for($i = 0; $i < 3; $i++) {
    	$tmp = floor($secs / $times[$i]);
		if($tmp < 1) {
		   $tmp = '00';
		}
		elseif($tmp < 10) {
		   $tmp = '0' . $tmp;
		}
			
		$time .= $tmp;
		if($i < 2) {
		   $time .= ':';
		}
		$secs = $secs % $times[$i];
	}
	return $time;
}
function proper_modulus($n1, $n2){
	return ($n1%$n2)+$n1-floor($n1);
}	
$i = 0;
$begin_times = array();
foreach($_POST as $k => $v){
	if(preg_match('/^begin_time/', $k, $matches))
		$begin_times[] = $_POST[$k];
}

$uploaded = false;
if(!empty($_FILES['xml_file'])){
	$tmp_name = $_FILES['xml_file']['tmp_name'];
	if(move_uploaded_file($tmp_name, 'cc.xml')) {
		$uploaded = true;
	} else {
		echo 'File was not uploaded';
	}
}
if(!empty($_POST)){
	
	if($_POST['format'] == 'DFXP'){
		$xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
	<tt xmlns="http://www.w3.org/ns/ttml" xmlns:tts="http://www.w3.org/ns/ttml#styling" xml:lang="en">
		<head>
			<styling>
			</styling>
		</head>
		<body>
			<div xml:lang="en" style="b1">
		';

		foreach	($begin_times as $k => $v){
			$time = explode(':', formatTime($v));
			$secs = proper_modulus($v, 3600);
			$secs = proper_modulus($secs, 60);
			$secs = str_pad(sprintf("%.2f",$secs), 5, 0, STR_PAD_LEFT);
			$hours = $time[0];
			$mins = $time[1];
			//$begin_time = $hours.':'.$mins.':'.$secs;
			$begin_time = $mins.':'.$secs;
			
			$duration = str_pad(sprintf("%.2f",$_POST['duration'.$k]), 5, 0, STR_PAD_LEFT);
			
			$end_time = $_POST['end_time'.$k];
			$time = explode(':', formatTime($end_time));
			$secs = proper_modulus($end_time, 3600);
			$secs = proper_modulus($secs, 60);
			$secs = str_pad(sprintf("%.2f",$secs), 5, 0, STR_PAD_LEFT);
			$mins = $time[1];
			$end_time = $mins.':'.$secs;
			
			$caption = $_POST['caption_field'.$k];
		//	$xml .= '		<p begin="'.$begin_time.'" dur="'.$duration.'" style="1">'.$caption.'</p>
			$xml .= '		<p begin="'.$begin_time.'" end="'.$end_time.'" style="1">'.$caption.'</p>
			';
		}
		          $xml .= '	</div>
		</body>'."\n\t".'</tt>';
		file_put_contents('cc.xml', $xml);
		$uploaded = true;
	}
}

if(!empty($_POST['download'])){
	header("Content-type: text/plain");
	header("Content-Disposition: attachment; filename=closed_caption.xml");
	echo $xml;
	exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Closed Caption Builder</title>
	<script type="text/javascript">AC_FL_RunContent = 0;</script>
	<script src="AC_RunActiveContent.js" type="text/javascript"></script>
	<script src="jquery-1.6.1.min.js" type="text/javascript"></script>
	
	<style type="text/css">
		html{color:#000;background:#FFF;}body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,code,form,fieldset,legend,input,button,textarea,p,blockquote,th,td{margin:0;padding:0;}table{border-collapse:collapse;border-spacing:0;}fieldset,img{border:0;}address,caption,cite,code,dfn,em,strong,th,var,optgroup{font-style:inherit;font-weight:inherit;}del,ins{text-decoration:none;}li{list-style:none;}caption,th{text-align:left;}h1,h2,h3,h4,h5,h6{font-size:100%;font-weight:normal;}q:before,q:after{content:'';}abbr,acronym{border:0;font-variant:normal;}sup{vertical-align:baseline;}sub{vertical-align:baseline;}legend{color:#000;}input,button,textarea,select,optgroup,option{font-family:inherit;font-size:inherit;font-style:inherit;font-weight:inherit;}input,button,textarea,select{*font-size:100%;}
		body{font:13px/1.231 arial,helvetica,clean,sans-serif;*font-size:small;*font:x-small;}select,input,button,textarea,button{font:99% arial,helvetica,clean,sans-serif;}table{font-size:inherit;font:100%;}pre,code,kbd,samp,tt{font-family:monospace;*font-size:108%;line-height:100%;}
		body { margin:30px;}
		a {color:#900;}
		li {margin:0 0 10px 35px; list-style:disc;}
		.cleared:after {
		     visibility: hidden;
		     display: block;
		     font-size: 0;
		     content: ' ';
		     clear: both;
		     height: 0;
		 }
		.cleared {
			min-height:1px;
		}
		#flashContent {width:440px;height:365px; border:1px solid #333; margin:0 0 20px; }
		.hide { position:absolute; left:-9999px; top:0; }
		.field { padding:0 0 10px;}
		label { float:left; margin:0 10px 0 0; width:100px; }
		.caption { padding:0 0 10px; margin:0 0 10px; border-bottom:1px solid #666; position:relative;}
		.text, textarea { border:1px solid #666; padding:5px; }
		.col { float:left; width:350px; margin:0 20px 0 0;}
		.col2 { width:450px; }
		.col3 { width:450px; }
		pre { background:#EEE; border:1px solid #666; margin:0 0 20px; padding:20px; }
		.l_checkbox { float:none; width:auto;}
		.checkbox { margin:0 10px 0 0;}
		#captions { height:300px; overflow-y:scroll; padding-right:10px; }
		.btn_close { position:absolute; right:10px; top:10px; }
		.btns { position:absolute; right:10px; top:0; }
		.btns a { display:block;}
		.code { position:relative; }
		pre { overflow-x:scroll;}
	</style>
</head>
<body>
	<div class="col col1">
		<form method="post" action="">
			<fieldset>
				<div class="field cleared">
					<button class="btn_record">Start recording</button>
					<button class="btn_generate_xml" type="submit">Generate XML</button>
				</div>
				
				<div class="field cleared">
					<label for="download" class="l_checkbox"><input type="checkbox" class="checkbox" id="download" name="download" value="1" />Download XML</label>
				</div>
				<input type="hidden" name="format" value="DFXP" />
				<div id="captions"></div>
				<div id="caption_template" class="hide">
					<div class="caption">
						<div class="btns">
							<a href="#" class="btn_close_caption">Delete</a>
							<a href="#" class="btn_up" title="Move up">Up</a>
							<a href="#" class="btn_down" title="Move down">Down</a>
						</div>
						<div class="field cleared">
							<label class="l_begin_time">Begin time</label>
							<input type="text" class="text begin_time" value="" />
						</div>
						<div class="field cleared">
							<label class="l_end_time">End time</label>
							<input type="text" class="text end_time" value="" />
						</div>
						<div class="field cleared hide">
							<label class="l_duration">Duration</label>
							<input type="text" class="text duration" value="" />
						</div>
						<div class="field cleared">
							<label class="l_caption_field">Caption</label>
							<textarea class="caption_field"></textarea>
						</div>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
	<div class="col col2">
		<div id="flashContent">
			<script type="text/javascript">
				if (AC_FL_RunContent == 0) {
					alert("This page requires AC_RunActiveContent.js.");
				} else {
					AC_FL_RunContent('codebase', 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0', 'width', '440', 'height', '365', 'src', 'videoplayerprt1', 'quality', 'high', 'pluginspage', 'http://www.macromedia.com/go/getflashplayer', 'align', 'middle', 'play', 'true', 'loop', 'true', 'scale', 'showall', 'wmode', 'window', 'devicefont', 'false', 'id', 'videoplayerprt1', 'bgcolor', '#000000', 'name', 'videoplayerprt1', 'menu', 'true', 'allowFullScreen', 'false', 'allowScriptAccess', 'sameDomain', 'movie', 'videoplayerprt1', 'salign', ''); //end AC code
				}
			</script>
			<noscript>
				<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="440" height="365" id="videoplayerprt1" align="middle">
				<param name="allowScriptAccess" value="sameDomain" />
				<param name="allowFullScreen" value="false" />
				<param name="movie" value="videoplayerprt1.swf" /><param name="quality" value="high" /><param name="bgcolor" value="#000000" />	<embed src="videoplayerprt1.swf" quality="high" bgcolor="#000000" width="440" height="365" name="videoplayerprt1" align="middle" allowScriptAccess="sameDomain" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
				</object>
			</noscript>
		</div>
		<form method="post" action="" id="get_movie">
			<fieldset>
				<div class="field cleared">
					<label for="url">Movie URL (e.g. FLV or MP4)</label>
					<input type="text" id="url" name="url" class="text" value="" />
				</div>
				<div>or</div>
				<div class="field cleared">
					<label for="bcid">Brightcove ID</label>
					<input type="text" id="bcid" name="bcid" class="text" value="" />
				</div>
				<div class="field cleared">
					<button class="btn_load_movie" type="submit">Load movie</button>
				</div>
			</fieldset>
		</form>
		<form method="post" action="" id="get_xml" enctype="multipart/form-data">
			<fieldset>
				<div class="field cleared">
					<label for="xml_file">XML URL</label>
					<input type="file" id="xml_file" name="xml_file" class="text" value="" />
				</div>
				<div class="field cleared">
					<button class="btn_load_xml" type="submit">Load XML</button>
				</div>
			</fieldset>
		</form>
		<ul>
			<li>If you use the player to start the video, then stop it with the player</li>
		</ul>
	</div>
	<div class="col col3">
	<? 
	if(!empty($xml))
		echo '<div class="code"><a href="#" class="btn_close">Close</a><pre>'.htmlentities($xml).'</pre></div>';
	?>
	</div>
		<script type="text/javascript">
		function getFlashMovie(movieName) {   
			var isIE = navigator.appName.indexOf("Microsoft") != -1;   
			return (isIE) ? window[movieName] : document[movieName];  
		}
		function convert_to_secs(colon_format){
			time_arr = colon_format.split(':');
			time_m = time_arr[0];
			time_s = time_arr[1];
			time = (time_m*60)+parseFloat(time_s);
			return time;
		}
		$(function(){
			var caption_template = $('#caption_template').html(),
			caption_i = 0,
			is_recording = false,
			xml_content = <?php echo ($uploaded ? 'true' : 'false'); ?>;
			
			if(xml_content){
				$.post('cc.xml',
					function(xml){
						$('#captions').text('');
						//loop through the captions
						$(xml).find('p').each(function(i, el){
							var begin_time = $(el).attr('begin'),
							end_time = $(el).attr('end'),
							caption_txt = $(el).text(),
							dur = $(el).attr('dur');
							
							begin_time = convert_to_secs(begin_time);
							end_time = convert_to_secs(end_time);
							
							add_caption();
							$('#captions .caption[rel="'+caption_i+'"] .begin_time').val(begin_time);
						//	$('#captions .caption[rel="'+caption_i+'"] .duration').val(dur);
							$('#captions .caption[rel="'+caption_i+'"] .end_time').val(end_time);
							$('#captions .caption[rel="'+caption_i+'"] .caption_field').text(caption_txt);
							caption_i++;
							update_move_buttons();
						});
					}, 'xml'
				);
			}			
			function add_caption(){
				$('#captions').append(caption_template);
				var caption = $('#captions .caption:last'),
				begin_time = caption.find('.begin_time'),
				end_time = caption.find('.end_time'),
				duration = caption.find('.duration'),
				btn_close_caption = caption.find('.btn_close_caption'),
				caption_field = caption.find('.caption_field');

				caption.find('label.l_begin_time').attr('for', 'begin_time'+caption_i);
				caption.find('label.l_end_time').attr('for', 'end_time'+caption_i);
				caption.find('label.l_duration').attr('for', 'duration'+caption_i);
				caption.find('label.l_caption_field').attr('for', 'caption_field'+caption_i);
				caption.attr('rel', caption_i);
				begin_time.attr({'name':'begin_time'+caption_i, 'id':'begin_time'+caption_i});
				end_time.attr({'name':'end_time'+caption_i, 'id':'end_time'+caption_i});
				duration.attr({'name':'duration'+caption_i, 'id':'duration'+caption_i});
				caption_field.attr({'name':'caption_field'+caption_i, 'id':'caption_field'+caption_i});				
			}
			$('#captions').click(function(e){
				var el = $(e.target);
				
				if(el.hasClass('btn_up')){
					var caption = el.parents('.caption'),
					adj_caption = caption.prev('.caption');
					caption.after(adj_caption);
					update_move_buttons();
				}
				if(el.hasClass('btn_down')){
					var caption = el.parents('.caption'),
					adj_caption = caption.next('.caption');
					caption.before(adj_caption);
					update_move_buttons();
				}
				if(el.hasClass('btn_close_caption')){
					el.parents('.caption').remove();
					update_move_buttons();
				}
				return false;
			});
			function update_move_buttons(){
				$('.btn_up, .btn_down').removeClass('hide');
				$('#captions .caption:first-child').find('.btn_up').addClass('hide');
				$('#captions .caption:last-child').find('.btn_down').addClass('hide');
			}
			
			$('.btn_record').click(function(){
				if(is_recording){
					var duration = getFlashMovie('videoplayerprt1').js_get_duration(),
					end_time = getFlashMovie('videoplayerprt1').js_get_end_time();
					
					$('#captions .caption[rel="'+caption_i+'"] .end_time').val(end_time);
					$('#captions .caption[rel="'+caption_i+'"] .duration').val(duration);
					is_recording = false;
					$('#caption_field'+caption_i).focus();
					caption_i++;
					$(this).text('Start recording');				
				}else{
					add_caption();
					update_move_buttons();
					var begin_time = getFlashMovie('videoplayerprt1').js_get_begin_time();
					$('#captions .caption[rel="'+caption_i+'"] .begin_time').val(begin_time);
					is_recording = true;
					$(this).text('Stop recording');
					$('#caption_field'+caption_i).focus();			
				}
				return false;
			});
			$('#captions').css('height', $(window).height()-200);
			$('.btn_close').click(function(){
				$(this).parents('.code').remove();
				return false;
			});
			$('#get_movie').submit(function(){
				var bcid = $('#bcid').val();
				if(bcid != ''){
					$.get('http://api.brightcove.com/services/library',
						{
							command:'find_video_by_id',
							video_id:bcid,
							video_fields:'FLVURL',
							media_delivery:'http',
							token:'AgH8MUSmnBCjckglT9OGOEV3EYJoiHhnTfZkdhxKgWw.'
						},
						function(data){
							getFlashMovie('videoplayerprt1').js_load_movie(data.FLVURL);
						}, 'jsonp'
					);
				}
				if($('#url').val() != ''){
					getFlashMovie('videoplayerprt1').js_load_movie($('#url').val());
				}
				return false;
			});
		});
		//http://api.brightcove.com/services/library?command=find_video_by_id&video_id=986928433001&video_fields=FLVURL&media_delivery=http&token=AgH8MUSmnBCjckglT9OGOEV3EYJoiHhnTfZkdhxKgWw.
		
		</script>
<?/*
<?xml version="1.0" encoding="UTF-8"?>
<tt xml:lang="en" xmlns="http://www.w3.org/2006/10/ttaf1"  xmlns:tts="http://www.w3.org/2006/10/ttaf1#styling">
      <head>
          <styling>
              <style id="1" tts:fontFamily="Arial" tts:fontSize="14" tts:color="#FFFFFF" tts:textAlign="left" tts:fontStyle="Bold" />
              <style id="2" tts:fontSize="10" tts:color="#000000" />
          </styling>
      </head>
      <body>
           <div xml:lang="en" >

              <p begin = "00:00:00.01" dur="04.00">First caption with default style coming from the Content plugin config</p>
              <p begin = "00:00:04.19" dur="04.00" style="1">2nd caption with timed text styling to make the text white</p>
              <p begin = "8s" dur="04.00" style="2">3rd caption using a small black font</p>
          </div> 
      </body>
 </tt>


<tt xmlns="http://www.w3.org/2006/10/ttaf1">
  <body>
    <div xml:id="captions">
      <p begin="00:08" end="00:10">- Nothing is going on.</p>
      <p begin="00:10" end="00:12.5">You liar!</p>
      <p begin="00:13" end="00:15">Are you?</p>
      <p begin="00:17" end="00:20">Violet, please!<br/>- I am not your babe!</p>
      <p begin="00:24" end="00:29">You stupid cow,<br/>look what you gone and done now, ay.</p>
      <p begin="00:34" end="00:36">Vi, please.<br/>- Leave me alone!</p>
      <p begin="00:36" end="00:38.5">- We need to talk.<br/>- Jason, are you deaf?!</p>
      <p begin="00:41" end="00:43">What's going on?</p>
      <p begin="00:43" end="00:45">Get out there and try to salvage this!</p>
    </div>
  </body>
</tt>


*/?>
</body>
</html>

<?/*

$id = trim($_REQUEST['id']);
$url = "http://www.youtube.com/watch?v=" . $id;
$url = $url . "&fmt=18"; //Gets the movie in High Quality
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$info = curl_exec($ch);
echo $info.'
';
if (!preg_match('#\'SWF_ARGS\': (\{.*?\})#is', $info, $matches)){
    echo "Check the YouTube URL : {$url} <br/>\n";
    die("Couldnt detect swfArgs");
}
if (function_exists(json_decode)){ # >= PHP 5.2.0
	echo 'beep';
	$swfArgs = json_decode($matches[1]);
    $video_id = $swfArgs->video_id;
    $t = $swfArgs->t;
}
else{
    preg_match('#"video_id":.*?"(.*?)"#is', $matches[1], $submatches);
    $video_id = $submatches[1];
    preg_match('#"t":.*?"(.*?)"#is', $matches[1], $submatches);
    $t = $submatches[1];
}
curl_close($ch);
$fullPath = "http://www.youtube.com/get_video.php?video_id=" . $video_id . "&t=" . $t; 
// construct the path to retreive the video from
$headers = get_headers($fullPath); // get all headers from the url
foreach($headers as $header){ //search the headers for the location url of youtube video
	if(preg_match("/Location:/i",$header)){
	    $location = $header;			  
	}
}
//header($location); // go to the location specified in the header and get the video
*/?>

<?/*<div class="field cleared">
	<label for="format" class="l_format">Format</label>
	<select name="format" id="format">
		<option selected="selected" value="DFXP">DFXP</option>
		<option value="TTXML">TTXML</option>
	</select>
</div>*/?>