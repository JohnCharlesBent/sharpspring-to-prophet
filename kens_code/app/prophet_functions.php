<?php

/*
* function gets a oAuth token.
*/
function get_token_prophet(){
	$url = prophet_url."api/Token?userName=".prophet_user."&password=".prophet_pass;
	//auth into prophet
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	$response = curl_exec($curl);
	if (!$response) {
	    die("Connection Failure.n");
	}
	$token = json_decode($response);
	return $token;
}

//token for unit testing keep commented
//$token = get_token_prophet();

/*
* function gets a oAuth token.
*/
function create_guid_prophet(){
    if (function_exists('com_create_guid') === true)
        return trim(com_create_guid(), '{}');

    $data = openssl_random_pseudo_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

//unit testing
//print_r(create_guid_prophet());

/*
* function takes a GUID and returns an array of base contact fields including contact and company GUID.
*/
function get_custom_fields_prophet($prophet_contact_array,$token){
	$return_response = array();

	if(is_array($prophet_contact_array) && isset($prophet_contact_array) && isset($prophet_contact_array['Id']) && $prophet_contact_array['Id'] != '' ){
		$guid = $prophet_contact_array['Id'];

		//get text custom fields
		$curl = curl_init(prophet_url."/odata/CustomFieldValueTexts?".urlencode('$')."filter=".urlencode("EntityId eq (guid'".$guid."')"));
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json',"Authorization:Token $token"));
		$response = curl_exec($curl);
		if (!$response) {
		    die("Connection Failure.n");
		}
		$response = (array)json_decode($response);
		if(isset($response[value]) && is_array($response[value]) && count($response[value]) != 0){
			$return_response = array_merge((array)$response[value],$return_response);
		}

		//get dropdown custom fields
		$curl = curl_init(prophet_url."/odata/CustomFieldValueDropdowns?".urlencode('$')."filter=".urlencode("EntityId eq (guid'".$guid."')"));
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json',"Authorization:Token $token"));
		$response = curl_exec($curl);
		if (!$response) {
		    //die("Connection Failure.n");
		}
		$response = (array)json_decode($response);
		if(isset($response[value]) && is_array($response[value]) && count($response[value]) != 0){
			$return_response = array_merge((array)$response[value],$return_response);
		}


		//get date custom fields
		$curl = curl_init(prophet_url."/odata/CustomFieldValueDates?".urlencode('$')."filter=".urlencode("EntityId eq (guid'".$guid."')"));
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json',"Authorization:Token $token"));
		$response = curl_exec($curl);
		if (!$response) {
		    //die("Connection Failure.n");
		}
		$response = (array)json_decode($response);
		if(isset($response[value]) && is_array($response[value]) && count($response[value]) != 0){
			$return_response = array_merge((array)$response[value],$return_response);
		}




	}
	return $return_response;


}

//unit testing
//$custom_fields = get_custom_fields_prophet('2fb7fbab-afca-4f84-9bac-2f4d43458009',$token);
//print_r($custom_fields);

/*
* function takes a company GUID and returns an array of company fields.
*/
function get_company_prophet($prophet_contact_array,$token){
	if(is_array($prophet_contact_array) && isset($prophet_contact_array) && isset($prophet_contact_array['MainCompanyId']) && $prophet_contact_array['MainCompanyId'] != '' ){
		$guid = $prophet_contact_array['MainCompanyId'];
		$url = prophet_url."/odata/Companies"."(guid'".$guid."')";
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json',"Authorization:Token $token"));
		$response = curl_exec($curl);
		if (!$response) {
		    die("Connection Failure.n");
		}
		$response = (array)json_decode($response);
	}else{
		$response = array();
	}
	return $response;

}

//unit testing
//$company = get_company_prophet('15df120f-7aa4-473f-aaa5-12a243bc4faf',$token);
//print_r($company);

/*
* Takes an email and returns an array of contacts with that email including contact GUID.
*/
function get_contact_by_email_prophet($email,$token){
	$url = prophet_url."/odata/Contacts?%24filter=".urlencode("Email eq '".$email."'");
	//print_r($url);
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json',"Authorization:Token $token"));
	$response = curl_exec($curl);
	if (!$response) {
	    //die("Connection Failure.n");
	}
	$response = (array)json_decode($response);


	//only take one if multiple exist
	if(isset($response[value]) && is_array($response[value]) && count($response[value]) != 0){
		$response = (array)$response[value][0];
	}else{
		$response = array();
	}
	return $response;
}

//unit testing
//print_r(get_contact_by_email_prophet('ken.grondell@tizinc.com',$token));


/*
* Takes the contact GUID and returns an array of contact fields.
*/
function get_contact_prophet($prophet_contact_guid,$token){


	if($prophet_contact_guid != '' ){
		$url = prophet_url."/odata/Contacts"."(guid'".$prophet_contact_guid."')";
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json',"Authorization:Token $token"));
		$response = curl_exec($curl);
		if (!$response) {
		    //die("Connection Failure.n");
		}
		$response = (array)json_decode($response);

	}else{
		$response = array();
	}

	return $response;

}

//unit test
//$contact = get_contact_prophet('2fb7fbab-afca-4f84-9bac-2f4d43458009',$token);
//print_r($contact);



/*
* Get list of prophet contacts updated in the last 30 minutes
*/
function get_updated_list_prophet($token){
	$start_date = date('Y-m-d').'T'.date("H", strtotime("-30 minutes")).':'.date("i", strtotime("-30 minutes")).':'.date("s", strtotime("-30 minutes")).'.000';
	$end_date = date('Y-m-d').'T'.date('H').':'.date('i').':'.date('s').'.000';
	$url = prophet_url."/odata/Contacts?%24filter=".urlencode("UpdatedDate gt datetime'".$start_date."' and UpdatedDate lt datetime'".$end_date."' and UpdatedDate ne null");
	//test url
	//$url = prophet_url."/odata/Contacts?%24filter=".urlencode("UpdatedDate gt datetime'2018-09-21T00:00:00.000' and UpdatedDate lt datetime'2018-09-21T23:30:00.000' and UpdatedDate ne null");
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json',"Authorization:Token $token"));
	$response = curl_exec($curl);
	if (!$response) {
	    die("Connection Failure.n");
	}
	$response = (array)json_decode($response);
	if(isset($response['value']) && count($response['value']) != 0){
		$response = $response['value'];
	}else{
		$response = '';
	}
	return $response;
}

//unit test
$updated_contacts_list = get_updated_list_prophet($token);
print_r($update_contacts_list);


/*
* Save list of recently updated contacts from prophet
*/
function save_to_prophet_updated_queue($updated_contacts_array){

	$dbhost = db_host;
	$dbname = db_name;
	$dbusername = db_user;
	$dbpassword = db_pass;

	//print_r($updated_contacts_array[0]->Id."<br>");
	if(is_array($updated_contacts_array) && count($updated_contacts_array) != 0){
		$connection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbusername, $dbpassword);

		for ($i = 0; $i < count($updated_contacts_array); $i++) {
			$result = array();
			$query = $connection->query("SELECT id FROM update_queue WHERE entity_origin ='Prophet' AND entity_id = '".$updated_contacts_array[$i]->Id."'");
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if(!isset($result['id'])){

					$query = $connection->prepare('INSERT INTO update_queue (entity_id, entity_origin)
					    VALUES (:entity_id, :entity_origin)');

					$query->execute([
					    'entity_id' => $updated_contacts_array[$i]->Id,
					    'entity_origin' => 'Prophet',
					]);


			}

		}
	}
}
//unit test
save_to_prophet_updated_queue($updated_contacts_list);


/*
* Save single entity to queue *fix for newly created leads returning a blank company name
*/
function save_one_contact_to_prophet_updated_queue($giud){

	$dbhost = db_host;
	$dbname = db_name;
	$dbusername = db_user;
	$dbpassword = db_pass;

	//print_r($updated_contacts_array[0]->Id."<br>");
	if($giud != ''){
		$connection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbusername, $dbpassword);

			$result = array();
			$query = $connection->query("SELECT id FROM update_queue WHERE entity_origin ='Prophet' AND entity_id = '".$giud."'");
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if(!isset($result['id'])){

					$query = $connection->prepare('INSERT INTO update_queue (entity_id, entity_origin)
					    VALUES (:entity_id, :entity_origin)');

					$query->execute([
					    'entity_id' => $giud,
					    'entity_origin' => 'Prophet',
					]);

			}

	}
}





/*
* Get a contact saved to the queue from prophet
*/
function get_prophet_queue_item(){

	$dbhost = db_host;
	$dbname = db_name;
	$dbusername = db_user;
	$dbpassword = db_pass;
	$connection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbusername, $dbpassword);
	$query = $connection->query("SELECT * FROM update_queue WHERE entity_origin ='Prophet' LIMIT 1");
	$result = $query->fetch(PDO::FETCH_ASSOC);
	return $result;

}
//unit test
//$queue_item = get_prophet_queue_item();
//print_r($queue_item);



/*
* remove contact from the queue from prophet
*/
function remove_prophet_queue_item($prophet_contact_array){
	if(is_array($prophet_contact_array) && isset($prophet_contact_array['queue_id'])){
		$dbhost = db_host;
		$dbname = db_name;
		$dbusername = db_user;
		$dbpassword = db_pass;
		$connection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbusername, $dbpassword);
		$query = $connection->query("DELETE FROM update_queue WHERE id = ".$prophet_contact_array['queue_id']);
		$query->execute();
	}
}
//unit test
//$prophet_contact_array['queue_id'] = 1;
//remove_prophet_queue_item($prophet_contact_array);


/*
*
*/
function build_prophet_contact_array($array_contact, $array_company, $array_custom_fields){

	$contact = array();
	//company data
	$contact['company_name'] = '';
	//contact data
	$contact['contact_guid'] = '';
	$contact['contact_first_name'] = '';
	$contact['contact_last_name'] = '';
	$contact['contact_job_title'] = '';
	$contact['contact_email'] = '';
	$contact['contact_company_guid'] = '';
	$contact['contact_business_phone'] = '';
	$contact['contact_website'] = '';
	$contact['contact_fax'] = '';
	$contact['contact_cell_phone'] = '';
	$contact['contact_guid'] = '';
	$contact['external_id'] = '';
	$contact['queue_id'] = '';
	$contact['createddate'] = '';
	$contact['updateddate'] = '';
	//custom fields
	//double opt in
	$contact['custom_double_opt_in'] = '';
	$contact['custom_double_opt_in_field_id'] = '';
	$contact['custom_double_opt_in_template_id'] = 'bd1528ea-4b69-423e-bd10-70d6783f5e6d';
	$contact['custom_double_opt_in_field_definition_id'] = 'e4e9fe54-ca00-43d5-9d4a-cd559f808050';
	$contact['custom_double_opt_in_type'] = 'Dropdown';
	$contact['custom_double_opt_in_text_value_for_sharpspring'] = '';
	//[DisplayName] = Double Opt-In
	//[EntityType] = Contact
	//[FieldType] = Dropdown
	//[TemplateId] = bd1528ea-4b69-423e-bd10-70d6783f5e6d
	//[Id] = e4e9fe54-ca00-43d5-9d4a-cd559f808050

	//triversa contact
	$contact['custom_triversa_contact'] = '';
	$contact['custom_triversa_contact_field_id'] = '';
	$contact['custom_triversa_contact_template_id'] = 'bd1528ea-4b69-423e-bd10-70d6783f5e6d';
	$contact['custom_triversa_contact_field_definition_id'] = '4255d086-f152-48f9-bd45-1797b0c8a869';
	$contact['custom_triversa_contact_type'] = 'Dropdown';
	$contact['custom_triversa_contact_text_value_for_sharpspring'] = '';
	//[DisplayName] = TriVersa Contact
	//[EntityType] = Contact
	//[FieldType] = Dropdown
	//[TemplateId] = bd1528ea-4b69-423e-bd10-70d6783f5e6d
	//[Id] = 4255d086-f152-48f9-bd45-1797b0c8a869

	//subscribed
	$contact['custom_subscribed'] = '';
	$contact['custom_subscribed_field_id'] = '';
	$contact['custom_subscribed_template_id'] = 'bd1528ea-4b69-423e-bd10-70d6783f5e6d';
	$contact['custom_subscribed_field_definition_id'] = '9103414e-3cf1-4b0d-acd2-25fe5419e45d';
	$contact['custom_subscribed_type'] = 'Dropdown';
	$contact['custom_subscribed_text_value_for_sharpspring'] = '';
	//[DisplayName] = Subscribed
	//[EntityType] = Contact
	//[FieldType] = Dropdown
	//[TemplateId] = bd1528ea-4b69-423e-bd10-70d6783f5e6d
	//[Id] = 9103414e-3cf1-4b0d-acd2-25fe5419e45d

	//unsubscribed
	$contact['custom_unsubscribed'] = '';
	$contact['custom_unsubscribed_field_id'] = '';
	$contact['custom_unsubscribed_template_id'] = 'bd1528ea-4b69-423e-bd10-70d6783f5e6d';
	$contact['custom_unsubscribed_field_definition_id'] = '514a4809-4889-48b9-854e-2ebc6de0ff51';
	$contact['custom_unsubscribed_type'] = 'Dropdown';
	$contact['custom_unsubscribed_text_value_for_sharpspring'] = '';
	//[DisplayName] = Unsubscribed
	//[EntityType] = Contact
	//[FieldType] = Dropdown
	//[TemplateId] = bd1528ea-4b69-423e-bd10-70d6783f5e6d
	//[Id] = 514a4809-4889-48b9-854e-2ebc6de0ff51

	//lead_source 1
	$contact['custom_lead_source1'] = '';
	$contact['custom_lead_source1_field_id'] = '';
	$contact['custom_lead_source1_template_id'] = 'bd1528ea-4b69-423e-bd10-70d6783f5e6d';
	$contact['custom_lead_source1_field_definition_id'] = '302428b8-93d3-4418-8b1c-9b0a4c9190d5';
	$contact['custom_lead_source1_type'] = 'Text';
	$contact['custom_lead_source1_text_value_for_sharpspring'] = '';
	//[DisplayName] = Lead Source
	//[EntityType] = Contact
	//[FieldType] = Text
	//[TemplateId] = bd1528ea-4b69-423e-bd10-70d6783f5e6d
	//[Id] = 302428b8-93d3-4418-8b1c-9b0a4c9190d5

	//lead_source 2
	$contact['custom_lead_source2'] = '';
	$contact['custom_lead_source2_field_id'] = '';
	$contact['custom_lead_source2_template_id'] = 'bd1528ea-4b69-423e-bd10-70d6783f5e6d';
	$contact['custom_lead_source2_field_definition_id'] = '5ea714fc-8036-4300-a4e2-b21fbdeb47c2';
	$contact['custom_lead_source2_type'] = 'Text';
	$contact['custom_lead_source2_text_value_for_sharpspring'] = '';
	//[DisplayName] = Lead Source
	//[EntityType] = Contact
	//[FieldType] = Text
	//[TemplateId] = bd1528ea-4b69-423e-bd10-70d6783f5e6d
	//[Id] = 5ea714fc-8036-4300-a4e2-b21fbdeb47c2

	//bounced
	$contact['custom_bounced'] = '';
	$contact['custom_bounced_field_id'] = '';
	$contact['custom_bounced_template_id'] = 'bd1528ea-4b69-423e-bd10-70d6783f5e6d';
	$contact['custom_bounced_field_definition_id'] = 'd4324297-975e-490e-914b-ae562765ec5b';
	$contact['custom_bounced_type'] = 'Dropdown';
	$contact['custom_bounced_text_value_for_sharpspring'] = '';
	//[DisplayName] = Bounced
	//[EntityType] = Contact
	//[FieldType] = Dropdown
	//[TemplateId] = bd1528ea-4b69-423e-bd10-70d6783f5e6d
	//[Id] = d4324297-975e-490e-914b-ae562765ec5b

	//expression contact
	$contact['custom_expression_contact'] = '';
	$contact['custom_expression_contact_field_id'] = '';
	$contact['custom_expression_contact_template_id'] = 'bd1528ea-4b69-423e-bd10-70d6783f5e6d';
	$contact['custom_expression_contact_field_definition_id'] = '88bd7066-1c46-408e-aa1b-bc7923f0c5e2';
	$contact['custom_expression_contact_type'] = 'Dropdown';
	$contact['custom_expression_contact_text_value_for_sharpspring'] = '';
	//[DisplayName] = expression contact
	//[EntityType] = Contact
	//[FieldType] = Dropdown
	//[TemplateId] = bd1528ea-4b69-423e-bd10-70d6783f5e6d
	//[Id] = 88bd7066-1c46-408e-aa1b-bc7923f0c5e2

	//START assign the contact values if there are any
	if(is_array($array_contact) && count($array_contact) != 0){


		if(isset($array_contact['CreatedDate']) && $array_contact['CreatedDate']!=''){
			$contact['createddate'] = $array_contact['CreatedDate'];
		}

		if(isset($array_contact['UpdatedDate']) && $array_contact['UpdatedDate']!=''){
			$contact['updateddate'] = $array_contact['UpdatedDate'];
		}

		if(isset($array_contact['Id']) && $array_contact['Id']!=''){
			$contact['contact_guid'] = $array_contact['Id'];
		}

		if(isset($array_contact['queue_id']) && $array_contact['queue_id']!=''){
			$contact['queue_id'] = $array_contact['queue_id'];
		}

		if(isset($array_contact['FirstName'])){
			$contact['contact_first_name'] = $array_contact['FirstName'];
		}
		if(isset($array_contact['LastName'])){
			$contact['contact_last_name'] = $array_contact['LastName'];
		}
		if(isset($array_contact['JobTitle'])){
			$contact['contact_job_title'] = $array_contact['JobTitle'];
		}
		if(isset($array_contact['Email'])){
			$contact['contact_email'] = $array_contact['Email'];
		}
		if(isset($array_contact['MainCompanyId'])){
			$contact['contact_company_guid'] = $array_contact['MainCompanyId'];
		}
		if(isset($array_contact['BusinessPhone'])){
			$contact['contact_business_phone'] = $array_contact['BusinessPhone'];
		}
		if(isset($array_contact['CellPhone'])){
			$contact['contact_cell_phone'] = $array_contact['CellPhone'];
		}
		if(isset($array_contact['Fax'])){
			$contact['contact_fax'] = $array_contact['Fax'];
		}
		if(isset($array_contact['Website'])){
			$contact['contact_website'] = $array_contact['Website'];
		}
		if(isset($array_contact['Id'])){
			$contact['contact_guid'] = $array_contact['Id'];
		}
		if(isset($array_contact['ExternalId'])){
			$contact['external_id'] = $array_contact['ExternalId'];
		}
	}
	//END assign the contact values if there are any

	//START assign the company values if there are any
	if(is_array($array_company) && count($array_company) != 0){
		if(isset($array_company['Name'])){
			$contact['company_name'] = $array_company['Name'];
		}
	}
	//END assign the company values if there are any

	//print_r($array_custom_fields);

	//START loop the custom field values if there are any
	if(is_array($array_custom_fields) && count($array_custom_fields) != 0){
		for ($i = 0; $i < count($array_custom_fields); $i++) {
			//double opt in
			if($array_custom_fields[$i]->CustomFieldDefinitionId == $contact['custom_double_opt_in_field_definition_id']){
				$contact['custom_double_opt_in'] = $array_custom_fields[$i]->DropDownValueId;
				$contact['custom_double_opt_in_field_id'] = $array_custom_fields[$i]->Id;
				if(custom_double_opt_in_options[0][1] == $array_custom_fields[$i]->DropDownValueId){
					$contact['custom_double_opt_in_text_value_for_sharpspring'] = custom_double_opt_in_options[0][0];
				}elseif(custom_double_opt_in_options[1][1] == $array_custom_fields[$i]->DropDownValueId){
					$contact['custom_triversa_contact_text_value_for_sharpspring'] = custom_double_opt_in_options[1][0];
				}
			}
			//triversa contact
			if($array_custom_fields[$i]->CustomFieldDefinitionId == $contact['custom_triversa_contact_field_definition_id']){
				$contact['custom_triversa_contact'] = $array_custom_fields[$i]->DropDownValueId;
				$contact['custom_triversa_contact_field_id'] = $array_custom_fields[$i]->Id;
				if(custom_triversa_contact_options[0][1] == $array_custom_fields[$i]->DropDownValueId){
					$contact['custom_triversa_contact_text_value_for_sharpspring'] = custom_triversa_contact_options[0][0];
				}elseif(custom_triversa_contact_options[1][1] == $array_custom_fields[$i]->DropDownValueId){
					$contact['custom_triversa_contact_text_value_for_sharpspring'] = custom_triversa_contact_options[1][0];
				}
			}
			//subscribed
			if($array_custom_fields[$i]->CustomFieldDefinitionId == $contact['custom_subscribed_field_definition_id']){
				$contact['custom_subscribed'] = $array_custom_fields[$i]->DropDownValueId;
				$contact['custom_subscribed_field_id'] = $array_custom_fields[$i]->Id;
				if(custom_subscribed_options[0][1] == $array_custom_fields[$i]->DropDownValueId){
					$contact['custom_subscribed_text_value_for_sharpspring'] = custom_subscribed_options[0][0];
				}elseif(custom_subscribed_options[1][1] == $array_custom_fields[$i]->DropDownValueId){
					$contact['custom_subscribed_text_value_for_sharpspring'] = custom_subscribed_options[1][0];
				}
			}
			//unsubscribed
			if($array_custom_fields[$i]->CustomFieldDefinitionId == $contact['custom_unsubscribed_field_definition_id']){
				$contact['custom_unsubscribed'] = $array_custom_fields[$i]->DropDownValueId;
				$contact['custom_unsubscribed_field_id'] = $array_custom_fields[$i]->Id;
				if(custom_unsubscribed_options[0][1] == $array_custom_fields[$i]->DropDownValueId){
					$contact['custom_unsubscribed_text_value_for_sharpspring'] = custom_unsubscribed_options[0][0];
				}elseif(custom_unsubscribed_options[1][1] == $array_custom_fields[$i]->DropDownValueId){
					$contact['custom_unsubscribed_text_value_for_sharpspring'] = custom_unsubscribed_options[1][0];
				}
			}
			//lead_source 1
			if($array_custom_fields[$i]->CustomFieldDefinitionId == $contact['custom_lead_source1_field_definition_id']){
				$contact['custom_lead_source1'] = $array_custom_fields[$i]->Text;
				$contact['custom_lead_source1_field_id'] = $array_custom_fields[$i]->Id;
				$contact['custom_lead_source1_text_value_for_sharpspring'] = $array_custom_fields[$i]->Text;
			}
			//lead_source 2
			if($array_custom_fields[$i]->CustomFieldDefinitionId == $contact['custom_lead_source2_field_definition_id']){
				$contact['custom_lead_source2'] = $array_custom_fields[$i]->Text;
				$contact['custom_lead_source2_field_id'] = $array_custom_fields[$i]->Id;
				$contact['custom_lead_source2_text_value_for_sharpspring'] = $array_custom_fields[$i]->Text;
			}
			//bounced

			if($array_custom_fields[$i]->CustomFieldDefinitionId == $contact['custom_bounced_field_definition_id']){
				$contact['custom_bounced'] = $array_custom_fields[$i]->DropDownValueId;
				$contact['custom_bounced_field_id'] = $array_custom_fields[$i]->Id;
				if(custom_bounced_options[0][1] == $array_custom_fields[$i]->DropDownValueId){
					$contact['custom_bounced_text_value_for_sharpspring'] = custom_bounced_options[0][0];
				}elseif(custom_bounced_options[1][1] == $array_custom_fields[$i]->DropDownValueId){
					$contact['custom_bounced_text_value_for_sharpspring'] = custom_bounced_options[1][0];
				}
			}
			//expression contact
			if($array_custom_fields[$i]->CustomFieldDefinitionId == $contact['custom_expression_contact_field_definition_id']){
				$contact['custom_expression_contact'] = $array_custom_fields[$i]->DropDownValueId;
				$contact['custom_expression_contact_field_id'] = $array_custom_fields[$i]->Id;
				if(custom_expression_contact_options[0][1] == $array_custom_fields[$i]->DropDownValueId){
					$contact['custom_expression_contact_text_value_for_sharpspring'] = custom_expression_contact_options[0][0];
				}elseif(custom_expression_contact_options[1][1] == $array_custom_fields[$i]->DropDownValueId){
					$contact['custom_expression_contact_text_value_for_sharpspring'] = custom_expression_contact_options[1][0];
				}
			}
		}

	}
	//END loop the custom field values if there are any

return $contact;

}
//unit test
//$prophet_contact_array = build_prophet_contact_array($contact, $company, $custom_fields,$queue_array);
//print_r($prophet_contact_array);



/*
* Update a prophet contact
*/

function update_contact_prophet($sharpspring_contact_array,$prophet_contact_array,$token){


	if(isset($prophet_contact_array['contact_guid']) && $prophet_contact_array['contact_guid'] != ''){
		$prophet_contact_guid = $prophet_contact_array['contact_guid'];

		$url = prophet_url."odata/Contacts"."(guid'".$prophet_contact_guid."')";
		//echo $url;
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
		$fields_array = array();
		if(isset($sharpspring_contact_array['firstName']) && $sharpspring_contact_array['firstName'] != ''){
			$fields_array['FirstName'] = $sharpspring_contact_array['firstName'];
		}
		if(isset($sharpspring_contact_array['lastName']) && $sharpspring_contact_array['lastName'] != ''){
			$fields_array['LastName'] = $sharpspring_contact_array['lastName'];
		}
		if(isset($sharpspring_contact_array['title']) && $sharpspring_contact_array['title'] != ''){
			$fields_array['JobTitle'] = $sharpspring_contact_array['title'];
		}
		if(isset($sharpspring_contact_array['emailAddress']) && $sharpspring_contact_array['emailAddress'] != ''){
			$fields_array['Email'] = $sharpspring_contact_array['emailAddress'];
		}
		if(isset($sharpspring_contact_array['phoneNumber']) && $sharpspring_contact_array['phoneNumber'] != ''){
			$fields_array['BusinessPhone'] = $sharpspring_contact_array['phoneNumber'];
		}
		if(isset($sharpspring_contact_array['website']) && $sharpspring_contact_array['website'] != ''){
			$fields_array['Website'] = $sharpspring_contact_array['website'];
		}
		if(isset($sharpspring_contact_array['mobilePhoneNumber']) && $sharpspring_contact_array['mobilePhoneNumber'] != ''){
			$fields_array['CellPhone'] = $sharpspring_contact_array['mobilePhoneNumber'];
		}
		if(isset($sharpspring_contact_array['faxNumber']) && $sharpspring_contact_array['faxNumber'] != ''){
			$fields_array['Fax'] = $sharpspring_contact_array['faxNumber'];
		}
		//print_r($fields_array);
		if(count($fields_array) > 0){
			curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($fields_array));
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json',"Authorization:Token $token"));
			$response = curl_exec($curl);
			$return_value = $response;
		}else{
			$return_value = "no updates"."Prophet guid:".$prophet_contact_guid." sharpspring id:".$sharpspring_contact_array['id'];
		}

		//sync custom fields
		if($return_value == ''){

			//START triversa custom field update
			if(isset($sharpspring_contact_array['triversa_contact_5bb528b957a68']) && $sharpspring_contact_array['triversa_contact_5bb528b957a68'] != ''){
				if($sharpspring_contact_array['triversa_contact_5bb528b957a68'] != $prophet_contact_array['custom_triversa_contact_text_value_for_sharpspring']){
					//update
					if($prophet_contact_array['custom_triversa_contact_field_id'] != ''){
					//use existing guid
						//echo "prophet_contact_array['custom_triversa_contact_field_id']:".$prophet_contact_array['custom_triversa_contact_field_id'];

						$url = "https://www.prophetondemand.com/prophet/prophetwebservices/AvtProphetApi/odata/CustomFieldValueDropdowns"."(guid'".$prophet_contact_array['custom_triversa_contact_field_id']."')";
						$curl = curl_init($url);
						curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
						$tri_custom_field_fields = array();
						//echo $sharpspring_contact_array['triversa_contact_5bb528b957a68'];
						//yes
						if($sharpspring_contact_array['triversa_contact_5bb528b957a68'] == custom_triversa_contact_options[0][0]){
						    $tri_custom_field_fields['CustomFieldDefinitionId'] = custom_triversa_contact_field_definition_id;
						    $tri_custom_field_fields['EntityId'] = $prophet_contact_guid;
						    //$tri_custom_field_fields['Id'] = $prophet_contact_array['custom_triversa_contact_field_id'];
						    $tri_custom_field_fields['DropDownValueId'] = custom_triversa_contact_options[0][1];
						    echo "TRI_YES";
						}
						//no
						if($sharpspring_contact_array['triversa_contact_5bb528b957a68'] == custom_triversa_contact_options[1][0]){
						    $tri_custom_field_fields['CustomFieldDefinitionId'] = custom_triversa_contact_field_definition_id;
						    $tri_custom_field_fields['EntityId'] = $prophet_contact_guid;
						    //$tri_custom_field_fields['Id'] = $prophet_contact_array['custom_triversa_contact_field_id'];
						    $tri_custom_field_fields['DropDownValueId'] = custom_triversa_contact_options[1][1];
						    echo "TRI_NO";
						}
						//print_r($tri_custom_field_fields);echo count($tri_custom_field_fields);
						echo "<br><br>";
						print_r($tri_custom_field_fields);
						echo "<br><br>";

						if(isset($tri_custom_field_fields) && count($tri_custom_field_fields) >= 3){
						curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($tri_custom_field_fields));
						curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json',"Authorization:Token $token"));
						$response = curl_exec($curl);
						echo "cf -u tri";
						}

					}else{
					//create new guid and field entity in prophet

						$new_giud = create_guid_prophet();
						$url = "https://www.prophetondemand.com/prophet/prophetwebservices/AvtProphetApi/odata/CustomFieldValueDropdowns";
						$curl = curl_init($url);
						curl_setopt($curl, CURLOPT_HEADER, false);
						curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($curl, CURLOPT_POST, true);
						$tri_custom_field_fields = array();
						//echo $sharpspring_contact_array['triversa_contact_5bb528b957a68'];
						//yes
						if($sharpspring_contact_array['triversa_contact_5bb528b957a68'] == custom_triversa_contact_options[0][0]){
						    $tri_custom_field_fields['CustomFieldDefinitionId'] = custom_triversa_contact_field_definition_id;
						    $tri_custom_field_fields['EntityId'] = $prophet_contact_guid;
						    $tri_custom_field_fields['Id'] = $new_giud;
						    $tri_custom_field_fields['DropDownValueId'] = custom_triversa_contact_options[0][1];
						    echo "TRI_YES";
						}
						//no
						if($sharpspring_contact_array['triversa_contact_5bb528b957a68'] == custom_triversa_contact_options[1][0]){
						    $tri_custom_field_fields['CustomFieldDefinitionId'] = custom_triversa_contact_field_definition_id;
						    $tri_custom_field_fields['EntityId'] = $prophet_contact_guid;
						    $tri_custom_field_fields['Id'] = $new_giud;
						    $tri_custom_field_fields['DropDownValueId'] = custom_triversa_contact_options[1][1];
						    echo "TRI_NO";
						}
						//print_r($tri_custom_field_fields);echo count($tri_custom_field_fields);

						echo "<br><br>";
						print_r($tri_custom_field_fields);
						echo "<br><br>";

						if(isset($tri_custom_field_fields) && count($tri_custom_field_fields) >= 3){
						curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($tri_custom_field_fields));
						curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json',"Authorization:Token $token"));
						$response = curl_exec($curl);
						echo "cf -c tri";
						}

					}
				}
			}
			//END triversa custom field update

			//START expression custom field update
			if(isset($sharpspring_contact_array['expression_contact_5bb5296882b76']) && $sharpspring_contact_array['expression_contact_5bb5296882b76'] != ''){
				if($sharpspring_contact_array['expression_contact_5bb5296882b76'] != $prophet_contact_array['custom_expression_contact_text_value_for_sharpspring']){
					//update
					if($prophet_contact_array['custom_expression_contact_field_id'] != ''){
					//use existing guid
						//echo "prophet_contact_array['custom_expression_contact_field_id']:".$prophet_contact_array['custom_expression_contact_field_id'];

						$url = "https://www.prophetondemand.com/prophet/prophetwebservices/AvtProphetApi/odata/CustomFieldValueDropdowns"."(guid'".$prophet_contact_array['custom_expression_contact_field_id']."')";
						$curl = curl_init($url);
						curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
						$exp_custom_field_fields = array();
						echo $sharpspring_contact_array['expression_contact_5bb5296882b76'];
						//yes
						if($sharpspring_contact_array['expression_contact_5bb5296882b76'] == custom_expression_contact_options[1][0]){
						    $exp_custom_field_fields['CustomFieldDefinitionId'] = custom_expression_contact_field_definition_id;
						    $exp_custom_field_fields['EntityId'] = $prophet_contact_guid;
						    //$exp_custom_field_fields['Id'] = $prophet_contact_array['custom_expression_contact_field_id'];
						    $exp_custom_field_fields['DropDownValueId'] = custom_expression_contact_options[1][1];
						    echo "EXP_YES";
						}
						//no
						if($sharpspring_contact_array['expression_contact_5bb5296882b76'] == custom_expression_contact_options[0][0]){
						    $exp_custom_field_fields['CustomFieldDefinitionId'] = custom_expression_contact_field_definition_id;
						    $exp_custom_field_fields['EntityId'] = $prophet_contact_guid;
						    //$exp_custom_field_fields['Id'] = $prophet_contact_array['custom_expression_contact_field_id'];
						    $exp_custom_field_fields['DropDownValueId'] = custom_expression_contact_options[0][1];
						    echo "EXP_NO";
						}
						print_r($exp_custom_field_fields);echo count($exp_custom_field_fields);
						if(isset($exp_custom_field_fields) && count($exp_custom_field_fields) >= 3){
						curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($exp_custom_field_fields));
						curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json',"Authorization:Token $token"));
						$response = curl_exec($curl);
						echo "cf -u exp";
						}

					}else{
					//create new guid and field entity in prophet

						$new_giud = create_guid_prophet();
						$url = "https://www.prophetondemand.com/prophet/prophetwebservices/AvtProphetApi/odata/CustomFieldValueDropdowns";
						$curl = curl_init($url);
						curl_setopt($curl, CURLOPT_HEADER, false);
						curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($curl, CURLOPT_POST, true);
						$exp_custom_field_fields = array();
						echo $sharpspring_contact_array['expression_contact_5bb5296882b76'];
						//yes
						if($sharpspring_contact_array['expression_contact_5bb5296882b76'] == custom_expression_contact_options[1][0]){
						    $exp_custom_field_fields['CustomFieldDefinitionId'] = custom_expression_contact_field_definition_id;
						    $exp_custom_field_fields['EntityId'] = $prophet_contact_guid;
						    $exp_custom_field_fields['Id'] = $new_giud;
						    $exp_custom_field_fields['DropDownValueId'] = custom_expression_contact_options[1][1];
						    echo "EXP_YES";
						}
						//no
						if($sharpspring_contact_array['expression_contact_5bb5296882b76'] == custom_expression_contact_options[0][0]){
						    $exp_custom_field_fields['CustomFieldDefinitionId'] = custom_expression_contact_field_definition_id;
						    $exp_custom_field_fields['EntityId'] = $prophet_contact_guid;
						    $exp_custom_field_fields['Id'] = $new_giud;
						    $exp_custom_field_fields['DropDownValueId'] = custom_expression_contact_options[0][1];
						    echo "EXP_NO";
						}
						echo "<br><br>";
						print_r($exp_custom_field_fields);
						echo "<br><br>";

						//echo count($exp_custom_field_fields);
						if(isset($exp_custom_field_fields) && count($exp_custom_field_fields) >= 3){
						curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($exp_custom_field_fields));
						curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json',"Authorization:Token $token"));
						$response = curl_exec($curl);
						echo "cf -c exp";
						}

					}
				}
			}
			//END expression custom field update



		}

	}
}



/*
* Create a prophet contact
*/
function create_contact_prophet($array){


}







/*
* get custom field definitions.

function get_custom_def_prophet($token){
	$url = prophet_url."/odata/CustomFieldDefinitions"."";
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json',"Authorization:Token $token"));
	$response = curl_exec($curl);
	if (!$response) {
	    die("Connection Failure.n");
	}
	$response = (array)json_decode($response);
	return $response;
}
print_r(get_custom_def_prophet($token));


List of custom fields:
==============================================

[Name] = contLabel10
[DisplayName] = Double Opt-In
[EntityType] = Contact
[FieldType] = Dropdown
[TemplateId] = bd1528ea-4b69-423e-bd10-70d6783f5e6d
[Id] = e4e9fe54-ca00-43d5-9d4a-cd559f808050


[DisplayName] = TriVersa Contact
[EntityType] = Contact
[FieldType] = Dropdown
[TemplateId] = bd1528ea-4b69-423e-bd10-70d6783f5e6d
[Id] = 4255d086-f152-48f9-bd45-1797b0c8a869


[DisplayName] = Subscribed
[EntityType] = Contact
[FieldType] = Dropdown
[TemplateId] = bd1528ea-4b69-423e-bd10-70d6783f5e6d
[Id] = 9103414e-3cf1-4b0d-acd2-25fe5419e45d


[DisplayName] = TriVersa MS
[EntityType] = Contact
[FieldType] = Text
[TemplateId] = bd1528ea-4b69-423e-bd10-70d6783f5e6d
[Id] = c00b0c54-3afb-4886-b161-286e0110f9f8


[DisplayName] = Unsubscribed
[EntityType] = Contact
[FieldType] = Dropdown
[TemplateId] = bd1528ea-4b69-423e-bd10-70d6783f5e6d
[Id] = 514a4809-4889-48b9-854e-2ebc6de0ff51


[DisplayName] = RePlay Contact
[EntityType] = Contact
[FieldType] = Dropdown
[TemplateId] = bd1528ea-4b69-423e-bd10-70d6783f5e6d
[Id] = 98ec8ca0-c784-48dc-94ff-3117192237d3


[DisplayName] = First Contact Date
[EntityType] = Contact
[FieldType] = Date
[TemplateId] = bd1528ea-4b69-423e-bd10-70d6783f5e6d
[Id] = ea9814fb-5fa0-4fe8-aa68-39fb427eb88a


[DisplayName] = Primary Language
[EntityType] = Contact
[FieldType] = Text
[TemplateId] = bd1528ea-4b69-423e-bd10-70d6783f5e6d
[Id] = d8a63672-218b-47c4-8fbd-3f6b8f7f3b0a


[DisplayName] = Area of Research
[EntityType] = Contact
[FieldType] = Text
[TemplateId] = bd1528ea-4b69-423e-bd10-70d6783f5e6d
[Id] = 3c68f9cf-e5b1-48f9-90ec-422aa61474e0


[DisplayName] = CMS Interface Technique
[EntityType] = Contact
[FieldType] = Dropdown
[TemplateId] = bd1528ea-4b69-423e-bd10-70d6783f5e6d
[Id] = b885563f-c31b-4e0e-8700-61e84160ff24


[DisplayName] = Lead Source
[EntityType] = Contact
[FieldType] = Text
[TemplateId] = bd1528ea-4b69-423e-bd10-70d6783f5e6d
[Id] = 302428b8-93d3-4418-8b1c-9b0a4c9190d5


[DisplayName] = Review Date
[EntityType] = Contact
[FieldType] = Date
[TemplateId] = bd1528ea-4b69-423e-bd10-70d6783f5e6d
[Id] = 1b255e27-bf1b-4d55-997b-8554eeccd556


[DisplayName] = expression application
[EntityType] = Contact
[FieldType] = Text
[TemplateId] = bd1528ea-4b69-423e-bd10-70d6783f5e6d
[Id] = c7e498a5-ef15-4392-9ee7-a5d8b2ee7f47


[DisplayName] = Bounced
[EntityType] = Contact
[FieldType] = Dropdown
[TemplateId] = bd1528ea-4b69-423e-bd10-70d6783f5e6d
[Id] = d4324297-975e-490e-914b-ae562765ec5b


[DisplayName] = Lead Source
[EntityType] = Contact
[FieldType] = Text
[TemplateId] = bd1528ea-4b69-423e-bd10-70d6783f5e6d
[Id] = 5ea714fc-8036-4300-a4e2-b21fbdeb47c2


[DisplayName] = expression contact
[EntityType] = Contact
[FieldType] = Dropdown
[TemplateId] = bd1528ea-4b69-423e-bd10-70d6783f5e6d
[Id] = 88bd7066-1c46-408e-aa1b-bc7923f0c5e2


[DisplayName] = NanoTek Contact
[EntityType] = Contact
[FieldType] = Dropdown
[TemplateId] = bd1528ea-4b69-423e-bd10-70d6783f5e6d
[Id] = f0a4cde5-9e13-40e2-b39b-be54f4453fed


[DisplayName] = City (Native Language)
[EntityType] = Contact
[FieldType] = Text
[TemplateId] = bd1528ea-4b69-423e-bd10-70d6783f5e6d
[Id] = 318dcc64-6170-40e3-a05e-c0ad9104ac7f

[Name] = contLabel20
[DisplayName] = Company (Native Language)
[EntityType] = Contact
[FieldType] = Text
[TemplateId] = bd1528ea-4b69-423e-bd10-70d6783f5e6d
[Id] = b3d6508c-b749-4e0a-927d-cc1be7aee90f























*/
