#!/opt/local/bin/php
<?php


if (count($argv) > 1) {
    reset($argv);
    $files = array_slice($argv,1);    
    foreach ($files as $i=>&$file) {
	$file = basename($file);
	if (substr($file,-3) != 'xml') {
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
    $files = glob('*.xml');
}


foreach( $files as  $file ) {
    if (substr($file,0,5) == 'soap_') {
	continue;
    }
    $base = basename($file,'.xml');
    $xml = file_get_contents($file);    
    $cmd = 'basex -i ' . $file . '  \'import module namespace json = "http://basex.org/modules/json";  json:serialize(/.,<json:options><json:format>jsonml</json:format><json:indent>yes</json:indent></json:options>)\' |  python -m json.tool'; 
    $json_file = $base . '.json';
    echo "converting: $base\n";
    exec ($cmd . ' > ' . $json_file);

}