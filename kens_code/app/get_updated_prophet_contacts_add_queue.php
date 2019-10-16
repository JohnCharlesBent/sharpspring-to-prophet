<?php
/*
*
* Triggered by cron every 25 minutes
* Checks for updated leads in prophet in the last 30 minutes and adds them to the queue.
*
*/
require 'config.php';
require 'prophet_functions.php';
require 'agnostic_functions.php';
require 'sharpspring_functions.php';

if(isset($_GET['verify']) && $_GET['verify'] == 'wx2!%sdp08PxZ873$2wQq-p'){
	$token = get_token_prophet();

	$updated_list = get_updated_list_prophet($token);


	save_to_prophet_updated_queue($updated_list);

}
