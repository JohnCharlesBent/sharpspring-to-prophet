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

if(isset($_POST['verify']) && $_POST['verify'] == 'wx2!Wsdp08PxZ873$2wQq-p'){
	$update = '';
	$return = '';

remove_sharpspring_create_queue_item($_POST['queue_id']);
echo 'done';
}