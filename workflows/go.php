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
    curl_close($ch);
    $json = json_decode($response);
    return "http://www.websequencediagrams.com/" . $json->img;

  }

foreach( glob('*.wsd') as  $file ) {
    $wsd = file_get_contents($file);    
    $img_url = get_sequence_diagram(
	array(
	    "apiVersion" => "1",
	    "message" => $wsd,
	    "style" => "modern-blue",
	    "format" => "png"
	    ));
    $img = file_get_contents($img_url);
    $img_file = basename($file,'.wsd') . '.png';
    file_put_contents($img_file,$img);
  }