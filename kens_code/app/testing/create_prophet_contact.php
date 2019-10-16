<?php
/*
* Create a prophet contact if needed change contact in array and uncomment function before running. Comment after to avoid duplicates
*/
error_reporting(E_ALL);
ini_set('display_errors', 1);


echo $_GET['verify'];
if(isset($_GET['verify']) && $_GET['verify']=="iuerfg3ih98ur9f8yfr8yf"){
	echo "yo";
require '../config.php';
require '../prophet_functions.php';
$token = get_token_prophet();

$company_guid = create_guid_prophet();
$contact_guid = create_guid_prophet();
echo " comp:".$company_guid;
echo " cont:".$contact_guid;

$url = "https://www.prophetondemand.com/prophet/prophetwebservices/AvtProphetApi/odata/Contacts";

$fields = array();

$company_name = '';

$fields['FullName']='George Antonious';
$fields['FirstName']='George';
$fields['LastName']='Antonious';
$fields['MiddleName']='';
$fields['JobTitle']='Dr.';
$fields['Title']='';
$fields['Email']='eric.turley@kysu.edu';
$fields['Email2']='';
$fields['Email3']='';
if($company_name != ''){
	$fields['MainCompanyId']=$company_guid;
}
$fields['BusinessPhone']='';
$fields['HomePhone']='';
$fields['CellPhone']='';
$fields['Fax']='';
$fields['Website']='';
$fields['Suffix']='';
$fields['Department']='';
$fields['Id'] = $contact_guid;

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($fields));
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json',"Authorization:Token $token"));
$response = curl_exec($curl);
print_r($response);

if($company_name != ''){

$url = "https://www.prophetondemand.com/prophet/prophetwebservices/AvtProphetApi/odata/Companies";

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
$fields = array();
$fields['Name'] = $company_name;
$fields['Id'] = $company_guid;
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($fields));
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json',"Authorization:Token $token"));
$response = curl_exec($curl);
print_r($response);

}


}
