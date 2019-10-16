<?php
/*
*
* Triggered by cron every 10 minutes
* Takes a queue item from the sharpspring pool and syncs it to prophet if it has changed data.
*
*/
require 'config.php';
require 'prophet_functions.php';
require 'agnostic_functions.php';
require 'sharpspring_functions.php';

if(isset($_GET['verify']) && $_GET['verify'] == 'SSw9xX2!$sdp0DI3O4U7HTY$3$2wQqw3p$2WzX09x'){
	$token = get_token_prophet();
	$queue_item = get_sharpspring_create_queue_item();

	var_dump($queue_item);

if(is_array($queue_item) && isset($queue_item['entity_id'])){
		$sharpspring_contact_array = get_sharpspring_contact_by_ssid($queue_item['entity_id']);
		$sharpspring_contact_array['queue_id'] = $queue_item['id'];
		print_r($sharpspring_contact_array);

		if(isset($sharpspring_contact_array['prophet_contact_id_5b58dc0543653']) && $sharpspring_contact_array['prophet_contact_id_5b58dc0543653'] != ''){
			save_to_sharpspring_updated_queue($sharpspring_contact_array['id']);
			remove_sharpspring_create_queue_item($sharpspring_contact_array['queue_id']);
			//kill zap
			$sharpspring_contact_array = array();
			$sharpspring_contact_array['id'] = 0;
			$sharpspring_contact_json = json_encode($sharpspring_contact_array);

		}elseif(isset($sharpspring_contact_array['emailAddress']) && $sharpspring_contact_array['emailAddress'] != ''){
			$p_contact_base = get_contact_by_email_prophet($sharpspring_contact_array['emailAddress'],$token);

		}
		//send contat to zap
		if(is_array($p_contact_base) && count($p_contact_base) == 0){
			$sharpspring_contact_json = json_encode($sharpspring_contact_array);
		}else{
			save_to_sharpspring_updated_queue($sharpspring_contact_array['id']);
			remove_sharpspring_create_queue_item($sharpspring_contact_array['queue_id']);
			//kill zap
			$sharpspring_contact_array = array();
			$sharpspring_contact_array['id'] = 0;
			$sharpspring_contact_json = json_encode($sharpspring_contact_array);
		}
	}else{
		//kill zap
		$sharpspring_contact_array = array();
		$sharpspring_contact_array['id'] = 0;
		$sharpspring_contact_json = json_encode($sharpspring_contact_array);

	}

	$curl = curl_init();
	$opts = array(
	    CURLOPT_URL             => 'https://hooks.zapier.com/hooks/catch/798566/e606fo/',
	    CURLOPT_RETURNTRANSFER  => true,
	    CURLOPT_CUSTOMREQUEST   => 'POST',
	    CURLOPT_POST            => 1,
	    CURLOPT_POSTFIELDS      => $sharpspring_contact_json,
	    CURLOPT_HTTPHEADER  => array('Content-Type: application/json','Content-Length: ' . strlen($sharpspring_contact_json))
	);
print_r($sharpspring_contact_json);
  	// Set curl options
    curl_setopt_array($curl, $opts);

    // Get the results
    $result = curl_exec($curl);

    // Close resource
    curl_close($curl);
    echo $result;
    echo "ok";

}
