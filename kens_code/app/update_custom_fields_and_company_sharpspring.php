<?php
/*
*
* Used in Zappier "ADVION Prophet New Contacts to Sharpspring"
* Updates synced prophet contact in sharpspring adding custom field and company GUID and data.
*
*/

error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'config.php';
require 'prophet_functions.php';
require 'agnostic_functions.php';
require 'sharpspring_functions.php';


if(isset($_POST['verify']) && $_POST['verify'] == 'W23-s1!p%ps34-0pcx#40XZ@3weK05?dx#me-2'){
	$update = '';
	$return = '';
	$token = get_token_prophet();

	if(isset($_POST['prophet_id']) && $_POST['prophet_id'] != ''){
		$p_contact_base = get_contact_prophet($_POST['prophet_id'],$token);
	}

	$company = get_company_prophet($p_contact_base,$token);
	$custom_fields = get_custom_fields_prophet($p_contact_base,$token);
	$prophet_contact_array = build_prophet_contact_array($p_contact_base, $company, $custom_fields);
	print_r($prophet_contact_array);

	$sharpspring_contact = get_sharpspring_contact_by_ssid($_POST['sharpspring_id']);
	print_r($sharpspring_contact);

	$diff_value = diff_contacts($prophet_contact_array,$sharpspring_contact,'Sharpspring');
	echo $diff_value;



	if($diff_value == "Update"){
		$update = update_sharpspring_record($sharpspring_contact, $prophet_contact_array);
	}else{
		$return = $return.' '.$diff_value;
	}

	if($update == 'Updated'){$return = 'Success';}else{$return = $return.' '.$update;}

	if($return != 'Success'){$return = $return.' '.print_r($_POST,true);}

	echo $return;

}
