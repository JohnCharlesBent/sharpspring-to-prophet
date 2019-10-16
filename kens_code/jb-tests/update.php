<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../app/config.php';

$dbhost = db_host;
$dbname = db_name;
$dbusername = db_user;
$dbpassword = db_pass;
$connection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbusername, $dbpassword);
$query = $connection->query("SELECT * FROM update_queue WHERE entity_origin = 'Sharpspring' LIMIT 1");
$result = $query->fetch(PDO::FETCH_ASSOC);
//var_dump($result);

// get SS id number from queue
$ss_id = $result['entity_id'];

$method = 'getLeads';
$limit = 1;
$offset = 0;
$params = array('where' => array('id' => $ss_id), 'limit' => $limit, 'offset' => $offset);

$data = array(
  'method' => $method,
  'params' => $params,
  'id' => sharpspring_requestid,
);

$queryString = http_build_query(array('accountID' => sharpspring_account, 'secretKey' => sharpspring_key));
$url = "http://api.sharpspring.com/pubapi/v1/?$queryString";

$data = json_encode($data);
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
 'Content-Type: application/json',
 'Content-Length: ' . strlen($data)
));

$result = curl_exec($ch);
curl_close($ch);

$result = json_decode( $result, true );
print_r($result);
