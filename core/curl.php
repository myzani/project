<?php

$ch = curl_init();

// set some cURL options
$ret = curl_setopt($ch, CURLOPT_URL, "www.yahoo.com");
$ret = curl_setopt($ch, CURLOPT_HEADER,         1);
$ret = curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
$ret = curl_setopt($ch, CURLOPT_TIMEOUT,        30);

// execute
$ret = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch); // close cURL handler
//echo "<pre>";
//print_r($info);
//echo "</pre>";
?>
