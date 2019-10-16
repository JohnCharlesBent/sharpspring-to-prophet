<?php
/*
*
* Used in Zappier "ADVION New Sharpspring Lead to Prophet"
* Checks to see if lead already exists in prophet and if so adds it to the update Queue as opposed to creating a new contact in prophet
*
*/
require 'config.php';
require 'prophet_functions.php';
require 'agnostic_functions.php';
require 'sharpspring_functions.php';

if(isset($_POST['verify']) && $_POST['verify'] == 'wx2!%sdp08PxZ873$2wQq-p'){
	$update = '';
	$return = '';
	$token = get_token_prophet();



	$return = 'error: '.print_r($_POST,true);
	$p_contact_base = array();


	if(isset($_POST['sharpspring_prophet_id']) && $_POST['sharpspring_prophet_id'] != ''){
		$return = 'Update: '.print_r($_POST,true);
		save_to_sharpspring_updated_queue($_POST['sharpspring_id']);
		print_r($return);
		die();
	}elseif(isset($_POST['sharpspring_email']) && $_POST['sharpspring_email'] != ''){
		$p_contact_base = get_contact_by_email_prophet($_POST['sharpspring_email'],$token);
	}

	if(is_array($p_contact_base) && count($p_contact_base) == 0){
		$return = 'create';
	}
	if(is_array($p_contact_base) && count($p_contact_base) > 0){
		$return = 'Update: '.print_r($_POST,true);
		save_to_sharpspring_updated_queue($_POST['sharpspring_id']);
	}

	print_r($return);

}