#!/opt/local/bin/php
<?php

function get_sequence_diagram($args) {
    $params = array();
    foreach ($args as $key => $value ) {
        $params[] = urlencode($key) . "=" . urlencode($value);
    }

    $ch = curl_init("http://www.websequencediagrams.com");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, implode("&", $params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $json = json_decode($response);
    if (!$json) {
	echo "error:no connection?\n"; 
	$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	echo "errorno:" .  curl_errno($ch) ."\n";
	echo curl_error($ch);
	    //print_r($args);
	curl_close($ch);
	return false;
    } else  {
	curl_close($ch);
	return "http://www.websequencediagrams.com/" . $json->img;
    }

  }


var_dump($argv);
if (count($argv) > 1) {
    reset($argv);
    $files = array_slice($argv,1);    
    foreach ($files as $i=>&$file) {
	$file = basename($file);
	if (substr($file,-3) != 'wsd') {
	    unset($files[$i]);
	    continue;
	} 
	if ($file[0] == '.') {
	    unset($files[$i]);
	    continue;
	}
    }
    unset($file);
} else {
    $files = glob('*.wsd');
}


foreach( $files as  $file ) {
    $wsd = file_get_contents($file);    
    $img_url = get_sequence_diagram(
	array(
	    "apiVersion" => "1",
	    "message" => $wsd,
	    "style" => "modern-blue",
	    "format" => "png"
	    ));
    if ($img_url) {
	$img = file_get_contents($img_url);
	$img_file = basename($file,'.wsd') . '.png';
	file_put_contents($img_file,$img);
    }
  }