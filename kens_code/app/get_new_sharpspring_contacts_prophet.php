<?php
/*
*
* Triggered by cron every 25 minutes
* Takes a queue item from the sharpspring pool and syncs it to prophet if it has changed data.
*
*/
require 'config.php';
require 'prophet_functions.php';
require 'agnostic_functions.php';
require 'sharpspring_functions.php';

if(isset($_GET['verify']) && $_GET['verify'] == 'SSw9xX2!$sdp0DI3O4U7HTY$3$2wQqw3p$2WzX09x'){

	$queue_item = get_sharpspring_create_queue_item();

if(is_array($queue_item) && isset($queue_item['entity_id'])){
	$sharpspring_contact_array = get_sharpspring_contact_by_ssid($queue_item['entity_id']);
	$sharpspring_contact_array['queue_id'] = $queue_item['id'];
	header('Content-type: application/json');
	echo json_encode($sharpspring_contact_array);
		
	}else{
		$sharpspring_contact_array = array('id' => '0');
		header('Content-type: application/json');
		echo json_encode($sharpspring_contact_array);
	}
	
}