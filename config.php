<?php
//environment
define('APP_PATH',dirname(__FILE__));
//includes
require('lib/jsonpath-0.8.1.php');
define('LOG_PATH','/log');
require('lib/log.php');
//sources
$urls=array(
	['type'=>'BTC','url'=>'http://api.coindesk.com/v1/bpi/currentprice.json','jsonPath'=>'$.bpi.USD.rate'],
	['type'=>'BTC','url'=>'https://blockchain.info/ticker','jsonPath'=>'$.USD.last'],
	['type'=>'BTC','url'=>'https://bitpay.com/api/rates/eur','jsonPath'=>'$.rate'],
	
	['type'=>'USD','url'=>'http://www.floatrates.com/daily/eur.json','jsonPath'=>'$.usd.rate'],
	['type'=>'USD','url'=>'http://api.fixer.io/latest?base=eur','jsonPath'=>'$.rates.USD']
);
?>