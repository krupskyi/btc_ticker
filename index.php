<?php
error_reporting(0);
$rates['EUR'] = [];
$rates['USD'] = [];
$sources['EUR'] = 1;
$sources['USD'] = 1;

$url = 'http://api.coindesk.com/v1/bpi/currentprice.json';

$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'GET'
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