<?php
require '../config.php';
require '../sharpspring_functions.php';
$foo = array();
$foo['entity_id'] = '614439612419';
$foo['entity_origin'] = 'Sharpspring';
$sharpspring_contact_array = get_sharpspring_contact_by_ssid('614439612419');

print_r($sharpspring_contact_array);
