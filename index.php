<?php
error_reporting(0);
$rates['BTC'] = [];
$rates['USD'] = [];

$urls = array(
	'BTC'=>array(
		'http://api.coindesk.com/v1/bpi/currentprice.json',
		'https://blockchain.info/ticker'
	),
	'USD'=>array(
	)
);

$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'GET'
    )
);
foreach($urls['BTC'] as $key=>$url){
	$context  = stream_context_create($options);
	$result = file_get_contents($url, false, $context);
	if($result){
		switch($key){
			case 0: $rates['BTC'][] = json_decode($result)->bpi->USD->rate; break;
			case 1: $rates['BTC'][] = json_decode($result)->USD->last; break;
		}
	}	
}

printf('BTC/USD: %s BTC/EUR: %s<br>',max($rates['BTC']), max($rates['EUR']));
printf('Active sources: BTC/USD (%s of %s)  BTC/EUR (%s of %s)', 
	count($rates['BTC']),
	count($urls['BTC']),
	count($rates['USD']),
	count($urls['USD'])	
);

?>