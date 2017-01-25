<?php
//in case of source not available not to display errors
error_reporting(0);
$rates['BTC'] = [];
$rates['USD'] = [];
//sources
$urls = array(
	// BTC/USD feeds
	'BTC'=>array(
		'http://api.coindesk.com/v1/bpi/currentprice.json',
		'https://blockchain.info/ticker',
		'https://bitpay.com/api/rates/eur'
	),
	// USD/EUR feeds
	'USD'=>array(
		'http://www.floatrates.com/daily/eur.json',
		'http://api.fixer.io/latest?base=eur'
	)
);

$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'GET'
    )
);
//processing BTC feeds
foreach($urls['BTC'] as $key=>$url){
	$context  = stream_context_create($options);
	$resultJson = file_get_contents($url, false, $context);
	if($resultJson){
		$result=json_decode($resultJson);
		switch($key){
			case 0: if(isset($result->bpi->USD->rate)){$rates['BTC'][] = $result->bpi->USD->rate;} break;
			case 1: if(isset($result->USD->last)){$rates['BTC'][] = $result->USD->last;} break;
			case 2: if(isset($result->rate)){$rates['BTC'][] = $result->rate;} break;			
		}				
	}	
}
//processing USD feeds
foreach($urls['USD'] as $key=>$url){
	$context  = stream_context_create($options);
	$resultJson = file_get_contents($url, false, $context);
	if($resultJson){
		$result=json_decode($resultJson);
		switch($key){
			case 0: if(isset($result->usd->rate)){$rates['USD'][] = $result->usd->rate;} break;
			case 1: if(isset($result->rates->USD)){$rates['USD'][] = $result->rates->USD;} break;
		}
	}	
}
//displaying results
printf('BTC/USD: %.4f EUR/USD: %.4f BTC/EUR: %.4f<br>',
	max($rates['BTC']),
	min($rates['USD']),
	max($rates['BTC'])/min($rates['USD'])	
);
printf('Active sources: BTC/USD (%s of %s)  BTC/EUR (%s of %s)', 
	count($rates['BTC']), //successful BTC feeds responses
	count($urls['BTC']), //total BTC feeds
	count($rates['USD']), //successful USD feeds responses`
	count($urls['USD'])	//total USD feeds
);

?>