<?php
require('config.php');

//in case of source not available not to display errors
error_reporting(0);

//init data
$rates['BTC'] = [];
$rates['USD'] = [];
$curlArr = [];
$curlNodes=['BTC'=>0,'USD'=>0];
$master = curl_multi_init();

//collecting nodes
foreach($urls as $source) {    
	$curlNodes[$source['type']]++;
	$curlNode['url'] = $source['url'];
	$curlNode['type'] = $source['type'];
	$curlNode['jsonPath'] = $source['jsonPath'];
    $curlNode['data'] = curl_init($source['url']);
    curl_setopt($curlNode['data'], CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curlNode['data'], CURLOPT_SSL_VERIFYPEER, false);
	$curlArr[] = $curlNode;
    curl_multi_add_handle($master, $curlNode['data']);
}

//processing requests
do {
    curl_multi_exec($master,$running);
} while($running > 0);

//retrieving data 
foreach($curlArr as $curlNode) {
   $resultJson = curl_multi_getcontent($curlNode['data']);
   if(!$resultJson) {
		writeLog('Warning', $curlNode['type'].' source '.$curlNode['url'].' not available');
	} else {
		if(!is_string($resultJson)) {
			writeLog('Warning', $curlNode['type'].' source '.$source['url'].' bad response');
		} else {
			$result=json_decode($resultJson, true);
			if(!json_last_error === JSON_ERROR_NONE) {
				writeLog('Warning', $curlNode['type'].' source '.$source['url'].' bad JSON format');
			} else {
				//success
				$rates[$curlNode['type']][] = jsonPath($result, $curlNode['jsonPath'])[0];
			}
		}					
	}
}

//checking if BTC data is available
$ratesCount['BTC'] = count($rates['BTC']);
if($ratesCount['BTC'] == 0){
	writeLog('Error', 'No BTC sources');
	die('service not available');
}

//checking if USD data is available
$ratesCount['USD'] = count($rates['USD']);
if($ratesCount['USD'] == 0){
	writeLog('Error', 'No USD sources');
	die('service not available');
}

//calculating
$maxBTC = max($rates['BTC']);
$minUSD = min($rates['USD']);

//displaying results
printf("BTC/USD: %.4f EUR/USD: %.4f BTC/EUR: %.4f", $maxBTC, $minUSD, $maxBTC/$minUSD);
echo '<br>';// cli \n creates a new line in PHP, thus in HTML code, but not in HTML output
printf("Active sources: BTC/USD (%d of %d)  BTC/EUR (%d of %d)", 
	$ratesCount['BTC'], //successful BTC feeds responses
	$curlNodes['BTC'], //total BTC feeds
	$ratesCount['USD'], //successful USD feeds responses`
	$curlNodes['USD']	//total USD feeds
);
?>