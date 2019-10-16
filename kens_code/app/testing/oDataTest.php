<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


$prophetusername = rawurlencode("support@tizinc.com");
$prophetuserpassword = rawurlencode("r3W11p2r09p3mB");
$ProphetAPIBaseUrl = "https://www.prophetOnDemand.com/prophet/prophetwebservices/AvtProphetApi/";
$ProphetOdataAPIBaseUrl = "https://www.prophetOnDemand.com/prophet/prophetwebservices/AvtProphetApi/odata/";




$url = $ProphetAPIBaseUrl."api/Token?userName=".$prophetusername."&password=".$prophetuserpassword;
echo '<h1>'.$url.'</h1>';

//auth into prophet
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

// Make the REST call, returning the result
$response = curl_exec($curl);
if (!$response) {
    die("Connection Failure.n");
}
print_r($response);
$token = json_decode($response);
echo $token;
//after you have token

//request list of contacts

//$url = $ProphetOdataAPIBaseUrl."Contacts";
$url = $ProphetOdataAPIBaseUrl."Contacts"."(guid'87b6c5d6-cc46-4d74-b332-0016d446fbb0')"."/Address";
echo '<h1>'.$url.'</h1>';

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json',"Authorization:Token $token"));
$response = curl_exec($curl);
if (!$response) {
    die("Connection Failure.n");
}
print_r($response);

/*
//request list of contacts created on 06-09-2017
//UpdatedDate":"2017-06-09T11:59:56.023
//$filter=SDateTime gt datetime'2014-06-26T03:30:00.000' and SDateTime lt datetime'2014-06-23T03:30:00.000'
//$filter=UpdatedDate gt datetime'2017-06-09T00:00:00.000' and UpdatedDate lt datetime'2017-06-09T23:30:00.000'

//$url = $ProphetOdataAPIBaseUrl."Contacts?%24filter=".urlencode("UpdatedDate gt datetime'2017-06-09T00:00:00.000' and UpdatedDate lt datetime'2017-06-09T23:30:00.000' and UpdatedDate ne null");
$url = $ProphetOdataAPIBaseUrl."Companies"."(guid'61448ea6-e386-425b-99d4-1182695ea368')"."";
//$url = $ProphetOdataAPIBaseUrl."Contacts?%24filter=".urlencode("UpdatedDate ne null");
echo '<h1>'.$url.'</h1>';

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json',"Authorization:Token $token"));
$response = curl_exec($curl);
if (!$response) {
    die("Connection Failure.n");
}
print_r($response);
$result_json_d = (array)json_decode($response);
echo '<h1>decoded</h1>';

if(array_key_exists('odata.error',$result_json_d)){
echo 'error';
mail('support@tizinc.com','Prophet Company Name Update Error',print_r($result_json_d,true));
}else{
print_r($result_json_d['Name']);
}
