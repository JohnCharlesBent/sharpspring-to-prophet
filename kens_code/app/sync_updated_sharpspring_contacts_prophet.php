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

	$queue_item = get_sharpspring_queue_item();



if(is_array($queue_item) && isset($queue_item['entity_id'])){
	$token = get_token_prophet();
	$sharpspring_contact_array = get_sharpspring_contact_by_ssid($queue_item['entity_id']);

	if(is_array($sharpspring_contact_array) && count($sharpspring_contact_array) > 0){
		$sharpspring_contact_array['queue_id'] = $queue_item['id'];

		//print_r($sharpspring_contact_array);
		//echo "<br><br><br><br>";

		if(isset($sharpspring_contact_array['prophet_contact_id_5b58dc0543653']) && $sharpspring_contact_array['prophet_contact_id_5b58dc0543653'] != ''){
			$prophet_contact = get_contact_prophet($sharpspring_contact_array['prophet_contact_id_5b58dc0543653'],$token);
			$prophet_retieved_by = 'guid';
		}
		if(!isset($prophet_contact['Id'])){
			$prophet_contact = get_contact_by_email_prophet($sharpspring_contact_array['emailAddress'],$token);
			$prophet_retieved_by = 'email';
		}
		if(!isset($prophet_contact['Id'])){

			//send error and move to create queue
			save_to_sharpspring_create_queue($queue_item['entity_id']);
			mail('john.bent@tizinc.com','Sharpspring to Prophet Update Event',"Error: Prophet ID not set entity:".$queue_item['entity_id']."\n\n");
			remove_sharpspring_queue_item($sharpspring_contact_array);
			die();

		}else{

			$array_custom_fields = get_custom_fields_prophet($prophet_contact,$token);
			$array_company = get_company_prophet($prophet_contact,$token);
			$prophet_contact = build_prophet_contact_array($prophet_contact, $array_company, $array_custom_fields);

		}
		$sharpspring_contact_array['prophet_retieved_by'] = $prophet_retieved_by;

		//print_r($prophet_contact);
		//echo "<br><br><br><br>";

		$contact_diff = diff_contacts($prophet_contact,$sharpspring_contact_array,'Prophet');
		//echo $contact_diff."<br><br>";
	}else{

			//send error and remove from queue
			mail('john.bent@tizinc.com','Sharpspring to Prophet Update Event',"Error: No Queue Item"."\n\n");
			remove_sharpspring_queue_item($sharpspring_contact_array);
			die();

	}


	if($contact_diff == 'Update'){
		//update prophet contact
		$update_response = update_contact_prophet($sharpspring_contact_array, $prophet_contact,$token);
		//print_r($update_response);
		echo "-u";
		mail('john.bent@tizinc.com','Sharpspring to Prophet Update Event',"Diff value: ".$contact_diff."\n\n"."ID of queue item: ".$queue_item['entity_id']."\n\n"."Update Response: ".$update_response);
		remove_sharpspring_queue_item($sharpspring_contact_array);

	}else{
		mail('john.bent@tizinc.com','Sharpspring to Prophet Update Event',"Diff value: ".$contact_diff."\n\n"."ID of queue item: ".$queue_item['entity_id']);
		echo "-ok";
		remove_sharpspring_queue_item($sharpspring_contact_array);
		die();
	}


	}else{

	echo "-0";
	}

}
