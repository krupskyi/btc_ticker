<?php
require('config.php');

//in case of source not available not to display errors
error_reporting(0);

//init data
$rates['BTC'] = [];
$rates['USD'] = [];

$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'GET'
    )
);

$context  = stream_context_create($options);
//processing BTC feeds
foreach($urls['BTC'] as $source){
	$resultJson = file_get_contents($source['url'], false, $context);
	$result=json_decode($resultJson, true);
	$rates['BTC'][] = jsonPath($result, $source['jsonPath'])[0];
	
};
//processing USD feeds
foreach($urls['USD'] as $source){
	$resultJson = file_get_contents($source['url'], false, $context);
	$result=json_decode($resultJson, true);
	$rates['USD'][] = jsonPath($result, $source['jsonPath'])[0];	
};
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