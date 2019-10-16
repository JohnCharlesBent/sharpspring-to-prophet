<?php
/*
*
* Used in Zappier "ADVION New Sharpspring Lead to Prophet"
* After contact is created in prophet this updates the sharpsring contact with the new GUID data from prophet
*
*/
require 'config.php';
require 'prophet_functions.php';
require 'agnostic_functions.php';
require 'sharpspring_functions.php';

if(isset($_POST['verify']) && $_POST['verify'] == '3wx2!%sdp08PxZ873$2wQq-pd3@dPx4$l-+dsRPx'){
	$update = '';
	$return = '';
	$token = get_token_prophet();



	$return = print_r($_POST,true);
	$p_contact_base = array();


	if(isset($_POST['contact_guid']) && $_POST['contact_guid'] != ''){
		save_one_contact_to_prophet_updated_queue($_POST['contact_guid']);
	}
	$return = "saved to queue";

}