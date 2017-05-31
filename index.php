<?php

$min = (!empty($_POST['min'])) ? $_POST['min'] : 0;
$max = (!empty($_POST['max'])) ? $_POST['max'] : 1;
$speed = (!empty($_POST['speed'])) ? $_POST['speed'] : 0;

$text = (string) mt_rand($min, $max);

function save_file($text = "1", $speed = 0)
{
	$apikey = "34b06ef0ba220c09a817fe7924575123";

	$url = sprintf('https://api.ispeech.org/api/rest?apikey=%s&action=convert&voice=euritalianfemale&speed=%d&pitch=100&text=%s', 
		$apikey,
		$speed,
		$text
	);

	$headers = array(
		'Pragma: no-cache', 
		'Accept-Encoding: gzip, deflate, sdch, br',
		'Accept-Language: en-US,en;q=0.8,ru;q=0.6',
		'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36',
		'Accept: */*',
		'Referer: https://www.ispeech.org/create.text.to.speech.audio',
		'X-Requested-With: ShockwaveFlash/25.0.0.171',
		'Connection: keep-alive',
		'Cache-Control: no-cache'
	);

	$path = sprintf('/var/www/nb/itlang/cache/%s_%s.mp3', $text, $speed); 

	if (file_exists($path)) {
		return str_replace('/var/www/nb/itlang', '', $path);
	}

	$fp = fopen($path, 'wb+');

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FILE, $fp);

	curl_exec($ch);
	fclose($fp);

	return str_replace('/var/www/nb/itlang', '', $path);

}


$path = save_file($text, $speed);


?>
<html>
<body>


	<form>
		<table>
		<?php if (!empty($path)) { ?>
		<tr><td colspan="2">
			<audio controls>
  				<source src="<?php echo $path; ?>" type="audio/mpeg">
				Your browser does not support the audio element.
			</audio>
		</td></tr>
		<?php } ?>

		<tr><td>answer:</td><td><input name="answer"></td></tr>
		<tr><td colspan="2"><input type="submit"></td></tr>
		</table>
	</form>


</body>
</html>
