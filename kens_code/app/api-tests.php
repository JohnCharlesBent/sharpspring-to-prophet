<?php
require 'config.php';

$url = prophet_url . 'api/Token?userName='.prophet_user.'&password='.prophet_pass;

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

$response = curl_exec($curl);

if(!$response) {
  die("connection failure");
}

$token = json_decode($response);

//var_dump($token);

$guid = 'ec125560-1a61-41b3-bcc0-0fd1dfc56b67';

$curl = curl_init(prophet_url.'/odata/CustomFieldValueTexts?'.urlencode('$').'filter='.urlencode("EntityId eq (guid'".$guid."')"));
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', "Authorization:Token $token"));
$response = curl_exec($curl);

if(!$response) {
  die("connection failure");
}
$response = (array)json_decode($response);
var_dump($response);

?>
