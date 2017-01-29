<?php
$multiRequests = array(
  new HttpRequest('http://www.google.com', HttpRequest::METH_GET),
  new HttpRequest('http://www.yahoo.com', HttpRequest::METH_GET),
  new HttpRequest('http://www.bing.com', HttpRequest::METH_GET)
);

$pool = new HttpRequestPool();
foreach ($multiRequests as $request)
{
  $pool->attach($request);
}

$pool->send();

foreach($pool as $request) 
{
  echo $request->getResponseBody();
}
?>