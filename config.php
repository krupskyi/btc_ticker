<?php
//environment
define('APP_PATH','');
echo base_url();
//includes
require('lib/jsonpath-0.8.1.php');
require('lib/log.php');
//sources
$urls['BTC']=array(
	['url'=>'http://api.coindesk.com/v1/bpi/currentprice.json','jsonPath'=>'$.bpi.USD.rate'],
	['url'=>'https://blockchain.info/ticker','jsonPath'=>'$.USD.last'],
	['url'=>'https://bitpay.com/api/rates/eur','jsonPath'=>'$.rate']
);
$urls['USD']=array(
	['url'=>'http://www.floatrates.com/daily/eur.json','jsonPath'=>'$.usd.rate'],
	['url'=>'http://api.fixer.io/latest?base=eur','jsonPath'=>'$.rates.USD']
);
?>