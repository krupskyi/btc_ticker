<?php
function writeLog($type,$message)
{
	$file = fopen(APP_PATH.LOG_PATH.'/'.date('Ymd').'.log','a');
	fwrite($file,'['.date('Y-m-d H:i:s').']['.strtoupper($type).'] '.$message.PHP_EOL);
	fclose($file);
};
?>