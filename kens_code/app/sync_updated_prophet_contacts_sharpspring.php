<?php
/*
*
* Triggered by cron every 25 minutes
* Checks for updated leads in prophet in the last 30 minutes and adds them to the queue.
*
*/

error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'config.php';
require 'prophet_functions.php';
require 'agnostic_functions.php';
require 'sharpspring_functions.php';

if(isset($_GET['verify']) && $_GET['verify'] == 'w9xX2!$sdp08PxZ873$2wQqw3p$2WzX0'){
	$token = get_token_prophet();

	$queue_item = get_prophet_queue_item();
if(is_array($queue_item) && count($queue_item) > 0){
	$prophet_contact_array = get_contact_prophet($queue_item['entity_id'],$token);
	if(is_array($prophet_contact_array) && count($prophet_contact_array) > 0){

		$prophet_contact_array['queue_id'] = $queue_item['id'];
		$array_custom_fields = get_custom_fields_prophet($prophet_contact_array,$token);
		$array_company = get_company_prophet($prophet_contact_array,$token);
		$prophet_contact = build_prophet_contact_array($prophet_contact_array, $array_company, $array_custom_fields);

	}else{
			mail('support@tizinc.com','Prophet to Sharpspring Update Error',"prophet contact array error: ".$queue_item['entity_id']."contact array:".print_r($prophet_contact_array,true));
			echo "-e";
			//send error and remove from queue
			die();

	}


		$sharpspring_contact = get_sharpspring_contact_by_email($prophet_contact['contact_email']);


		$contact_diff = diff_contacts($prophet_contact,$sharpspring_contact,'Sharpspring');

	if($contact_diff == 'Ignore -2'){
		//create a new lead
		echo "-1";
		$create_contact = create_sharpspring_record($prophet_contact);
		mail('support@tizinc.com','Prophet to Sharpspring Update Event',"Diff value: ".$contact_diff."\n\n"."ID of queue item: ".$queue_item['entity_id']."\n\n".print_r($create_contact,true));

	}elseif($contact_diff == 'Update'){
		//update sharpspring lead
		$update_response = update_sharpspring_record($sharpspring_contact, $prophet_contact);
		//print_r($update_response);
		echo "-u";
		mail('support@tizinc.com','Prophet to Sharpspring Update Event',"Diff value: ".$contact_diff."\n\n"."ID of queue item: ".$queue_item['entity_id']."\n\n"."Update Response: ".$update_response);
		remove_prophet_queue_item($prophet_contact);

	}else{
		mail('support@tizinc.com','Prophet to Sharpspring Update Event',"Diff value: ".$contact_diff."\n\n"."ID of queue item: ".$queue_item['entity_id']);
		echo "-ok";
		remove_prophet_queue_item($prophet_contact);
		die();
	}

		//print_r($prophet_contact);

	}else{

	echo "-0";
	}
}
