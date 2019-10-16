<?php

if(!isset($_GET['verify'])){
    if($_GET['verify'] != 'kg1'){
        die();
    }

}


require 'config.php';


echo '<h1>auth token</h1>';
$url = prophet_url."api/Token?userName=".prophet_user."&password=".prophet_pass;
echo '<h3>'.$url.'</h3>';

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



/*
echo '<h1>get all dropdowns</h1>';


$curl = curl_init("https://www.prophetondemand.com/prophet/prophetwebservices/AvtProphetApi/odata/CustomFieldValueDropdowns");
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json',"Authorization:Token $token"));
$response = curl_exec($curl);
if (!$response) {
    die("Connection Failure.n");
}
print_r($response);


//request list of contacts

//$url = $ProphetOdataAPIBaseUrl."Contacts";
echo '<h1>get contact by id</h1>';
$url = prophet_url."/odata/Contacts"."(guid'e28c7cb0-dc3e-4d8c-a0dd-42caa27d26ad')";
echo '<h3>'.$url.'</h3>';

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json',"Authorization:Token $token"));
$response = curl_exec($curl);
if (!$response) {
    die("Connection Failure.n");
}
print_r($response);


//request list of custom field ids


echo '<h1>get MY contact </h1>';
$url = prophet_url."/odata/Contacts"."(guid'044f0c19-741e-6f52-222d-0c8a62fcf81a')";
echo '<h3>'.$url.'</h3>';

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json',"Authorization:Token $token"));
$response = curl_exec($curl);
if (!$response) {
    die("Connection Failure.n");
}
print_r($response);





echo '<h1>Gennerate a guid</h1>';
function guidv4()
{
    if (function_exists('com_create_guid') === true)
        return trim(com_create_guid(), '{}');

    $data = openssl_random_pseudo_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

echo guidv4();








echo '<h1>Update My contact </h1>';
$url = "https://www.prophetondemand.com/prophet/prophetwebservices/AvtProphetApi/odata/CustomFieldValueTexts";
echo '<h3>'.$url.'</h3>';

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode([
    'CustomFieldDefinitionId' => '318dcc64-6170-40e3-a05e-c0ad9104ac7f',
    'EntityId' => '044f0c19-741e-6f52-222d-0c8a62fcf81a',
    'Text' => 'test custom'
]));
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json',"Authorization:Token $token"));
$response = curl_exec($curl);
print_r($response);
if (!$response) {
    die("Connection Failure.n");
}


 */


/*


echo '<h1>get all custom fields by entity id</h1>';
echo '<h3>'."https://www.prophetondemand.com/prophet/prophetwebservices/AvtProphetApi/odata/CustomFieldValueTexts?".urlencode('$')."filter=EntityId eq (guid'e28c7cb0-dc3e-4d8c-a0dd-42caa27d26ad')".'</h3>';
//,CustomFieldDefinitionId='d8a63672-218b-47c4-8fbd-3f6b8f7f3b0a',Id='5c8a23ae-6291-4b4b-973f-0028a65268d4'
$curl = curl_init("https://www.prophetondemand.com/prophet/prophetwebservices/AvtProphetApi/odata/CustomFieldValueTexts?".urlencode('$')."filter=".urlencode("EntityId eq (guid'e28c7cb0-dc3e-4d8c-a0dd-42caa27d26ad')"));
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json',"Authorization:Token $token"));
$response = curl_exec($curl);
if (!$response) {
    die("Connection Failure.n");
}
print_r($response);


echo '<h1>get all custom fields by MY entity id</h1>';
echo '<h3>'."https://www.prophetondemand.com/prophet/prophetwebservices/AvtProphetApi/odata/CustomFieldValueTexts?".urlencode('$')."filter=EntityId eq (guid'044f0c19-741e-6f52-222d-0c8a62fcf81a')".'</h3>';
//,CustomFieldDefinitionId='d8a63672-218b-47c4-8fbd-3f6b8f7f3b0a',Id='5c8a23ae-6291-4b4b-973f-0028a65268d4'
$curl = curl_init("https://www.prophetondemand.com/prophet/prophetwebservices/AvtProphetApi/odata/CustomFieldValueTexts?".urlencode('$')."filter=".urlencode("EntityId eq (guid'044f0c19-741e-6f52-222d-0c8a62fcf81a')"));
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json',"Authorization:Token $token"));
$response = curl_exec($curl);
if (!$response) {
    die("Connection Failure.n");
}
print_r($response);



//https://www.prophetondemand.com/prophet/prophetwebservices/AvtProphetApi/odata/CustomFieldValueTexts?$filter=EntityId%20eq%20(guid'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx')%20and%20CustomFieldDefinitionId%20eq%20(guid'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx')
echo '<h1>get custom field by entity id and field id</h1>';
echo '<h3>'."https://www.prophetondemand.com/prophet/prophetwebservices/AvtProphetApi/odata/CustomFieldValueTexts?".urlencode('$')."filter=CustomFieldDefinitionId eq (guid'4a54a93c-f755-4a5f-b2c9-002401cf28af')".'</h3>';
//,CustomFieldDefinitionId='d8a63672-218b-47c4-8fbd-3f6b8f7f3b0a',Id='5c8a23ae-6291-4b4b-973f-0028a65268d4'
$curl = curl_init("https://www.prophetondemand.com/prophet/prophetwebservices/AvtProphetApi/odata/CustomFieldValueTexts?".urlencode('$')."filter=".urlencode("EntityId eq (guid'e28c7cb0-dc3e-4d8c-a0dd-42caa27d26ad') and CustomFieldDefinitionId eq (guid'318dcc64-6170-40e3-a05e-c0ad9104ac7f')"));
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json',"Authorization:Token $token"));
$response = curl_exec($curl);
if (!$response) {
    die("Connection Failure.n");
}
print_r((array)json_decode($response));


echo '<h1>get custom field by field id (lead source 1)</h1>';
$curl = curl_init("https://www.prophetondemand.com/prophet/prophetwebservices/AvtProphetApi/odata/CustomFieldValueTexts?".urlencode('$')."filter=".urlencode("CustomFieldDefinitionId eq (guid'302428b8-93d3-4418-8b1c-9b0a4c9190d5')"));
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json',"Authorization:Token $token"));
$response = curl_exec($curl);
if (!$response) {
    die("Connection Failure.n");
}
print_r((array)json_decode($response));



echo '<h1>get custom field by field id (lead source 2)</h1>';
$curl = curl_init("https://www.prophetondemand.com/prophet/prophetwebservices/AvtProphetApi/odata/CustomFieldValueTexts?".urlencode('$')."filter=".urlencode("CustomFieldDefinitionId eq (guid'5ea714fc-8036-4300-a4e2-b21fbdeb47c2')"));
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json',"Authorization:Token $token"));
$response = curl_exec($curl);
if (!$response) {
    die("Connection Failure.n");
}
print_r($response);




echo '<h1>get contact by update date</h1>';
//request list of contacts created on 06-09-2017
//UpdatedDate":"2017-06-09T11:59:56.023
//$filter=SDateTime gt datetime'2014-06-26T03:30:00.000' and SDateTime lt datetime'2014-06-23T03:30:00.000'
//$filter=UpdatedDate gt datetime'2017-06-09T00:00:00.000' and UpdatedDate lt datetime'2017-06-09T23:30:00.000'
$start_date = date('Y-m-d').'T'.date("H", strtotime("-30 minutes")).':'.date("i", strtotime("-30 minutes")).':'.date("s", strtotime("-30 minutes")).'.000';
$end_date = date('Y-m-d').'T'.date('H').':'.date('i').':'.date('s').'.000';
$url = prophet_url."/odata/Contacts?%24filter=".urlencode("UpdatedDate gt datetime'".$start_date."' and UpdatedDate lt datetime'".$end_date."' and UpdatedDate ne null");

//$url = prophet_url."/odata/Contacts?%24filter=".urlencode("UpdatedDate gt datetime'2018-09-21T00:00:00.000' and UpdatedDate lt datetime'2018-09-21T23:30:00.000' and UpdatedDate ne null");
//$url = $ProphetOdataAPIBaseUrl."Companies"."(guid'61448ea6-e386-425b-99d4-1182695ea368')"."";
//$url = $ProphetOdataAPIBaseUrl."Contacts?%24filter=".urlencode("UpdatedDate ne null");
echo '<h3>'.$url.'</h3>';

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

if(array_key_exists('odata.error',$result_json_d)){
echo 'error';
//mail('support@tizinc.com','Prophet Company Name Update Error',print_r($result_json_d,true));
}else{
print_r($result_json_d['Name']);
}

*/


/*
echo '<h1>get contact by create date</h1>';
//request list of contacts created on 06-09-2017
//UpdatedDate":"2017-06-09T11:59:56.023
//$filter=SDateTime gt datetime'2014-06-26T03:30:00.000' and SDateTime lt datetime'2014-06-23T03:30:00.000'
//$filter=UpdatedDate gt datetime'2017-06-09T00:00:00.000' and UpdatedDate lt datetime'2017-06-09T23:30:00.000'
$start_date = date('Y-m-d').'T'.date("H", strtotime("-30 minutes")).':'.date("i", strtotime("-30 minutes")).':'.date("s", strtotime("-30 minutes")).'.000';
$end_date = date('Y-m-d').'T'.date('H').':'.date('i').':'.date('s').'.000';
$url = prophet_url."/odata/Contacts?%24filter=".urlencode("CreatedDate gt datetime'".$start_date."' and CreatedDate lt datetime'".$end_date."' and CreatedDate ne null");

//$url = prophet_url."/odata/Contacts?%24filter=".urlencode("UpdatedDate gt datetime'2018-09-21T00:00:00.000' and UpdatedDate lt datetime'2018-09-21T23:30:00.000' and UpdatedDate ne null");
//$url = $ProphetOdataAPIBaseUrl."Companies"."(guid'61448ea6-e386-425b-99d4-1182695ea368')"."";
//$url = $ProphetOdataAPIBaseUrl."Contacts?%24filter=".urlencode("UpdatedDate ne null");
echo '<h3>'.$url.'</h3>';

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

if(array_key_exists('odata.error',$result_json_d)){
echo 'error';
//mail('support@tizinc.com','Prophet Company Name Update Error',print_r($result_json_d,true));
}else{
print_r($result_json_d['Name']);
}
*/











//
//

//request list of custom field ids
/*

echo '<h1>get contact by guid</h1>';
$url = prophet_url."/odata/Contacts"."(guid'fe136d0d-b2a7-99d4-0ba6-36bc762f5cdf')";
echo '<h3>'.$url.'</h3>';

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json',"Authorization:Token $token"));
$response = curl_exec($curl);
if (!$response) {
    die("Connection Failure.n");
}
print_r($response);

*/
/*
echo '<h1>DELTE CONTACT- COMMENT OUT! </h1>';
$url = prophet_url."/odata/Contacts"."(guid'd7ee4577-622d-0383-f152-01352fdcbaa9')";
echo '<h3>'.$url.'</h3>';

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json',"Authorization:Token $token"));
$response = curl_exec($curl);
print_r($response);

*/

//
//
//
//
//
//
//
//

/*
echo '<h1>Update My contact </h1>';
$url = prophet_url."odata/Contacts"."(guid'4cd6e6be-94c1-a27d-a6e6-753213ef63ae')";
echo '<h3>'.$url.'</h3>';

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
$fields_array = array();
$fields_array['Email3'] = '4something@somewhere.com';
$fields_array['Email2'] = '4XXXX4cd6e6be-94c1-@a6e6-753213ef63ae.com';
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($fields_array));
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json',"Authorization:Token $token"));
$response = curl_exec($curl);
print_r($response);

*/



/*

echo '<h1>DELTE CUSTOM FIELD DROPDOWN - COMMENT OUT! </h1>';
$url = prophet_url."/odata/CustomFieldValueDropdowns"."(guid'69d0f02b-0d1c-4efb-a842-4793842dce69')";
echo '<h3>'.$url.'</h3>';

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json',"Authorization:Token $token"));
$response = curl_exec($curl);
print_r($response);

*/

/*
echo '<h1>get company</h1>';
echo '<h3>'."https://www.prophetondemand.com/prophet/prophetwebservices/AvtProphetApi/odata/Companies?".urlencode('$')."filter=EntityId eq (guid'18497942-c859-c396-d221-a5a73ba1d93d')".'</h3>';
//,CustomFieldDefinitionId='d8a63672-218b-47c4-8fbd-3f6b8f7f3b0a',Id='5c8a23ae-6291-4b4b-973f-0028a65268d4'
$curl = curl_init("https://www.prophetondemand.com/prophet/prophetwebservices/AvtProphetApi/odata/Companies");
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json',"Authorization:Token $token"));
$response = curl_exec($curl);
if (!$response) {
    die("Connection Failure.n");
}
print_r($response);

*/

echo '<h1>get contact by email</h1>';

$url = prophet_url."/odata/Contacts?%24filter=".urlencode("Email eq 'fergc@foodsci.umass.edu'");

echo '<h3>'.$url.'</h3>';

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

if(array_key_exists('odata.error',$result_json_d)){
echo 'error';
  mail('support@tizinc.com','Prophet Company Name Update Error',print_r($result_json_d,true));
}else{
print_r($result_json_d['Name']);
}







echo '<h1>get all custom fields by MY entity id</h1>';
echo '<h3>'."https://www.prophetondemand.com/prophet/prophetwebservices/AvtProphetApi/odata/CustomFieldValueDropdowns?".urlencode('$')."filter=EntityId eq (guid'18497942-c859-c396-d221-a5a73ba1d93d')".'</h3>';
//,CustomFieldDefinitionId='d8a63672-218b-47c4-8fbd-3f6b8f7f3b0a',Id='5c8a23ae-6291-4b4b-973f-0028a65268d4'
$curl = curl_init("https://www.prophetondemand.com/prophet/prophetwebservices/AvtProphetApi/odata/CustomFieldValueDropdowns?".urlencode('$')."filter=".urlencode("EntityId eq (guid'18497942-c859-c396-d221-a5a73ba1d93d')"));
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json',"Authorization:Token $token"));
$response = curl_exec($curl);
if (!$response) {
    die("Connection Failure.n");
}
print_r($response);
