<?php
/*
*
* Triggered by cron every 25 minutes
* Checks for updated leads in sharpspirng in the last 30 minutes and adds them to the queue.
*
*/
date_default_timezone_set('America/New_York');
require 'config.php';
require 'prophet_functions.php';
require 'agnostic_functions.php';
require 'sharpspring_functions.php';

if(isset($_GET['verify']) && $_GET['verify'] == 'sSwx2!sdp08PxZ873$2wQq4fd37p'){
	$token = get_token_prophet();

	$updated_list = get_sharpspring_updated_contact_list();
	save_to_sharpspring_updated_queue($updated_list);
	//print_r($updated_list);

}

?>
