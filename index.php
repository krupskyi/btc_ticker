<?php
error_reporting(0);
$rates['EUR'] = [];
$rates['USD'] = [];
$sources['EUR'] = 1;
$sources['USD'] = 1;

$url = 'http://api.1coindesk.com/v1/bpi/currentprice.json';
$data = array('key1' => 'value1', 'key2' => 'value2');

$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'GET',
        'content' => http_build_query($data)
    )
);

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
if($result){
	$rates['EUR'][] = json_decode($result)->bpi->EUR->rate;
	$rates['USD'][] = json_decode($result)->bpi->USD->rate;	
}
printf('BTC/USD: %s BTC/EUR: %s<br>',max($rates['USD']), max($rates['EUR']));
printf('Active sources: BTC/USD (%s of %s)  BTC/EUR (%s of %s)', 
	count($rates['USD']),
	$sources['USD'],
	count($rates['EUR']),
	$sources['EUR'] 	
);
?>