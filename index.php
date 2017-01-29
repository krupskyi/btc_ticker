<?php
require('config.php');

//in case of source not available not to display errors
error_reporting(0);

//init data
$urlsCount['BTC'] = count($urls['BTC']);
$urlsCount['USD'] = count($urls['USD']);
if($urlsCount['BTC'] == 0 || $urlsCount['USD'] == 0) {
	$writeLog('Error','No source specified: '.json_encode($urlsCount));
	die('no source');
}

$rates['BTC'] = [];
$rates['USD'] = [];

//setup
$options = array(
    'http' => array(
        'method'  => 'GET'
    )
);
$context  = stream_context_create($options);

//processing BTC feeds
foreach($urls['BTC'] as $source){
	$resultJson = @file_get_contents($source['url'], false, $context);
	if(!$resultJson) {
		writeLog('Warning', 'BTC source '.$source['url'].' not available');
	} else {
		if(!is_string($resultJson)) {
			writeLog('Warning', 'BTC source '.$source['url'].' bad response');
		} else {
			$result=json_decode($resultJson, true);
			if(!json_last_error === JSON_ERROR_NONE) {
				writeLog('Warning', 'BTC source '.$source['url'].' bad JSON format');
			} else {
				//success
				$rates['BTC'][] = jsonPath($result, $source['jsonPath'])[0];
			}
		}					
	}  	
};
$ratesCount['BTC'] = count($rates['BTC']);
if($ratesCount['BTC'] == 0){
	writeLog('Error', 'No BTC sources');
	die('service not available');
}

//processing USD feeds
foreach($urls['USD'] as $source){
	$resultJson = @file_get_contents($source['url'], false, $context);
	if(!$resultJson) {
		writeLog('Warning', 'USD source '.$source['url'].' not available');
	} else {
		if(!is_string($resultJson)) {
			writeLog('Warning', 'USD source '.$source['url'].' bad response');
		} else {
			$result=json_decode($resultJson, true);
			if(!json_last_error === JSON_ERROR_NONE) {
				writeLog('Warning', 'USD source '.$source['url'].' bad JSON format');
			} else {
				//success
				$rates['USD'][] = jsonPath($result, $source['jsonPath'])[0];
			}
		}					
	}  	
};
$ratesCount['USD'] = count($rates['USD']);
if($ratesCount['USD'] == 0){
	writeLog('Error', 'No USD sources');
	die('service not available');
}
$maxBTC = max($rates['BTC']);
$minUSD = min($rates['USD']);
//displaying results
printf("BTC/USD: %.4f EUR/USD: %.4f BTC/EUR: %.4f", $maxBTC, $minUSD, $maxBTC/$minUSD);
echo '<br>';// cli \n creates a new line in PHP, thus in HTML code, but not in HTML output
printf("Active sources: BTC/USD (%d of %d)  BTC/EUR (%d of %d)", 
	$ratesCount['BTC'], //successful BTC feeds responses
	$urlsCount['BTC'], //total BTC feeds
	$ratesCount['USD'], //successful USD feeds responses`
	$urlsCount['USD']	//total USD feeds
);

?>