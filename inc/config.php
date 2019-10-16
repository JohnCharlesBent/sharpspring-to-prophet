<?php
/**
* Configuration - contstants to use throughout the app
**/

/**** Base URL ****/

$url = sprintf(
  "%s://%s%s",
  isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
  $_SERVER['SERVER_NAME'],
  $_SERVER['REQUEST_URI']
);

$url = explode('/', $url);
$scheme = $url[0];
$root_dir = $url[3];
$host = $url[2];


$base_url = $scheme.'//'.$host.'/'.$root_dir;
define('dist_dir', $base_url);


/***** Prophet Credentials - pass to API call ******/
define('prophet_url', 'https://www.prophetondemand.com/prophet/prophetwebservices/AvtProphetApi/');
define('prophet_user', rawurlencode("support@tizinc.com"));
define('prophet_pass', rawurlencode("r3W11p2r09p3mB"));

/***** SharpSpring Credentials - pass to API call *****/
define('sharpspring_requestid', session_id());
define('sharpspring_account_number', '329023B8902CDD5B9FD8D4223C53D1AC');
define('sharpspring_key', 'B1165B8A2ACD262E2DB76B137C7905CE');

/***** Prophet Custom Field Information *****/
//double opt in
define('custom_double_opt_in_template_id', 'bd1528ea-4b69-423e-bd10-70d6783f5e6d');
define('custom_double_opt_in_field_definition_id', 'e4e9fe54-ca00-43d5-9d4a-cd559f808050');
define('custom_double_opt_in_type', 'Dropdown');
$options = array();
$options[0] = array();
$options[0][0] = 'TRUE';
$options[0][1] = 'd5d91b06-7e1a-4efd-86e4-433af4a33fb3';
$options[1][0] = 'FALSE';
$options[1][1] = '73da37c3-ad15-41ea-8220-0f74d9ddab91';
define('custom_double_opt_in_options', $options);


//triversa contact
define('custom_triversa_contact_template_id', 'bd1528ea-4b69-423e-bd10-70d6783f5e6d');
define('custom_triversa_contact_field_definition_id', '4255d086-f152-48f9-bd45-1797b0c8a869');
define('custom_triversa_contact_type', 'Dropdown');
$options = array();
$options[0] = array();
$options[0][0] = 'Yes';
$options[0][1] = 'b02887bc-3fa7-46b7-9da7-675626ed24d8';
$options[1][0] = 'No';
$options[1][1] = 'e7354f83-48dc-4785-ab02-6270e4657b5d';
define('custom_triversa_contact_options', $options);

//subscribed
define('custom_subscribed_template_id', 'bd1528ea-4b69-423e-bd10-70d6783f5e6d');
define('custom_subscribed_field_definition_id', '9103414e-3cf1-4b0d-acd2-25fe5419e45d');
define('custom_subscribed_type', 'Dropdown');
$options = array();
$options[0] = array();
$options[0][0] = 'TRUE';
$options[0][1] = '7234596c-0536-4f5c-93c4-f644bb1c251a';
$options[1][0] = 'FALSE';
$options[1][1] = '1667e4b0-7ae6-48e2-9c35-d0201caab944';
define('custom_subscribed_options', $options);

//unsubscribed
define('custom_unsubscribed_template_id', 'bd1528ea-4b69-423e-bd10-70d6783f5e6d');
define('custom_unsubscribed_field_definition_id', '514a4809-4889-48b9-854e-2ebc6de0ff51');
define('custom_unsubscribed_type', 'Dropdown');
$options = array();
$options[0] = array();
$options[0][0] = 'TRUE';
$options[0][1] = 'b8835fd7-be48-4d39-ac42-d1923d913777';
$options[1][0] = 'FALSE';
$options[1][1] = '9e847a35-6304-440c-ad8a-039814dc236f';
define('custom_unsubscribed_options', $options);

//bounced
define('custom_bounced_template_id', 'bd1528ea-4b69-423e-bd10-70d6783f5e6d');
define('custom_bounced_field_definition_id', 'd4324297-975e-490e-914b-ae562765ec5b');
define('custom_bounced_type', 'Dropdown');
$options = array();
$options[0] = array();
$options[0][0] = 'TRUE';
$options[0][1] = 'cbafc652-6872-4978-a082-2dbc6e921d39';
$options[1][0] = 'FALSE';
$options[1][1] = 'f80ded00-2cf8-4766-9454-acd46ca7d5e7';
define('custom_bounced_options', $options);

//expression contact
define('custom_expression_contact_template_id', 'bd1528ea-4b69-423e-bd10-70d6783f5e6d');
define('custom_expression_contact_field_definition_id', '88bd7066-1c46-408e-aa1b-bc7923f0c5e2');
define('custom_expression_contact_type', 'Dropdown');
$options = array();
$options[0] = array();
$options[0][0] = 'No';
$options[0][1] = '911c4693-4ad4-4ec0-9de6-47729186d27c';
$options[1][0] = 'Yes';
$options[1][1] = 'e0adfe00-a9be-49e0-98c4-1fd52414074b';
define('custom_expression_contact_options', $options);

//lead_source 1
define('custom_lead_source1_template_id', 'bd1528ea-4b69-423e-bd10-70d6783f5e6d');
define('custom_lead_source1_field_definition_id', '302428b8-93d3-4418-8b1c-9b0a4c9190d5');
define('custom_lead_source1_type', 'Text');

//lead_source 2
define('custom_lead_source2_template_id', 'bd1528ea-4b69-423e-bd10-70d6783f5e6d');
define('custom_lead_source2_field_definition_id', '5ea714fc-8036-4300-a4e2-b21fbdeb47c2');
define('custom_lead_source2_type', 'Text');

?>
