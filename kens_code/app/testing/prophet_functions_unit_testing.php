<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../config.php';
require '../prophet_functions.php';
require '../agnostic_functions.php';
require '../sharpspring_functions.php';

$token = get_token_prophet();

$queue_item = get_prophet_queue_item();
print_r($queue_item);
echo "<br><br>";
$prophet_contect_base = get_contact_prophet($queue_item,$token);
print_r($prophet_contect_base);
echo "<br><br>";

$sharpspring_contact = get_sharpspring_contact_by_email($prophet_contect_base ['Email']);
print_r($sharpspring_contact);
echo "<br><br>";
$company = get_company_prophet($prophet_contect_base,$token);
print_r($company);


echo "<br><br>";
$custom_fields = get_custom_fields_prophet($prophet_contect_base,$token);
print_r($custom_fields);
echo "<br><br>";

$prophet_contact_array = build_prophet_contact_array($prophet_contect_base, $company, $custom_fields);
print_r($prophet_contact_array);

echo "<br><br>";
$diff_value = diff_contacts($prophet_contact_array,$sharpspring_contact,'Sharpspring');
print_r($diff_value);

echo "<br><br>";


update_sharpspring_record($sharpspring_contact, $prophet_contact_array);
































//print_r(get_contact_by_email_prophet('dschedle@bsc.edu',$token));
