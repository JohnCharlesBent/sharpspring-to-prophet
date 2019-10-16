<?php


/*
* get contact in sharpspring by email
*/
function get_sharpspring_contact_by_email($email){

		$method = 'getLeads';
		$limit = 1;
		$offset = 0;
		$params = array('where' => array('emailAddress' => $email), 'limit' => $limit, 'offset' => $offset);

		$data = array(
		 'method' => $method,
		 'params' => $params,
		 'id' => sharpspring_requestid,
		);


		$queryString = http_build_query(array('accountID' => sharpspring_account, 'secretKey' => sharpspring_key));
		$url = "http://api.sharpspring.com/pubapi/v1/?$queryString";

		$data = json_encode($data);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		 'Content-Type: application/json',
		 'Content-Length: ' . strlen($data)
		));

		$result = curl_exec($ch);
		curl_close($ch);
		$result = json_decode( $result, true );
		$return_value = array();

		if(count($result['error']) === 0){

		//results were returned
		if(count($result['result']['lead']) != 0){
		    //lead exists record Sharpspring ID
		    $return_value = $result['result']['lead'][0];

		}

		}
return $return_value;


}
//unit test
//print_r(get_sharpspring_contact_by_email('jturner@qcc.mass.edu'));



/*
* get contact from sharpspring queue
*/
function get_sharpspring_queue_item(){

	$dbhost = db_host;
	$dbname = db_name;
	$dbusername = db_user;
	$dbpassword = db_pass;
	$connection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbusername, $dbpassword);
	$query = $connection->query("SELECT * FROM update_queue WHERE entity_origin ='Sharpspring' LIMIT 1");
	$result = $query->fetch(PDO::FETCH_ASSOC);
	return $result;

}
//unit test
//$queue_item = get_sharpspring_queue_item();
//print_r($queue_item);

/*
* get contact from sharpspring queue
*/
function get_sharpspring_create_queue_item(){

	$dbhost = db_host;
	$dbname = db_name;
	$dbusername = db_user;
	$dbpassword = db_pass;
	$connection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbusername, $dbpassword);
	$query = $connection->query("SELECT * FROM create_queue WHERE entity_origin ='Sharpspring' LIMIT 1");
	$result = $query->fetch(PDO::FETCH_ASSOC);
	return $result;

}
//unit test
//$queue_item = get_sharpspring_queue_item();
//print_r($queue_item);


/*
* get contact in sharpspring by sharpspring id
*/
function get_sharpspring_contact_by_ssid($ss_id){


	if( $ss_id != '' ){
			$method = 'getLeads';
			$limit = 1;
			$offset = 0;
			$params = array('where' => array('id' => $ss_id), 'limit' => $limit, 'offset' => $offset);

			$data = array(
			 'method' => $method,
			 'params' => $params,
			 'id' => sharpspring_requestid,
			);


			$queryString = http_build_query(array('accountID' => sharpspring_account, 'secretKey' => sharpspring_key));
			$url = "http://api.sharpspring.com/pubapi/v1/?$queryString";

			$data = json_encode($data);
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			 'Content-Type: application/json',
			 'Content-Length: ' . strlen($data)
			));

			$result = curl_exec($ch);
			curl_close($ch);
			$result = json_decode( $result, true );
			//print_r($result);
			$return_value = array();

			if(count($result['error']) === 0){

			//results were returned
			if(count($result['result']['lead']) != 0){
			    //lead exists record Sharpspring ID
			    $return_value = $result['result']['lead'][0];
			    if(isset($ss_queue_array['id'])){$return_value['queue_id'] = $ss_queue_array['id'];}

			}

			}

	return $return_value;
	}

}

//print_r(get_sharpspring_contact_by_ssid($queue_item));



/*
* get list of update contact in sharpspring
*/

function get_sharpspring_updated_contact_list(){

		$method = 'getLeadsDateRange';
		$limit = 5000;
		$offset = 0;
		$startDate = date('Y-m-d H:i:s', strtotime('-30 minutes'));//7
		$endDate = date('Y-m-d H:i:s');
		$params = array('startDate' => $startDate,'endDate' => $endDate,'timestamp' => 'update', 'limit' => $limit, 'offset' => $offset);
		//print_r($params);
		$data = array(
		 'method' => $method,
		 'params' => $params,
		 'id' => sharpspring_requestid,
		);


		$queryString = http_build_query(array('accountID' => sharpspring_account, 'secretKey' => sharpspring_key));
		$url = "http://api.sharpspring.com/pubapi/v1/?$queryString";

		$data = json_encode($data);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		 'Content-Type: application/json',
		 'Content-Length: ' . strlen($data)
		));

		$result = curl_exec($ch);
		curl_close($ch);
		$result = json_decode( $result, true );
		$return_value = '';

		if(count($result['error']) === 0){

		//results were returned
		if(count($result['result']['lead']) != 0){
		    //lead exists record Sharpspring ID
		    $return_value = $result['result']['lead'];
		}

		}
return $return_value;


}
//unit test
//$updated_contacts_list = get_sharpspring_updated_contact_list();
//print_r($updated_contacts_list);



/*
* Save contacts in sharpspring update queue
*/
function save_to_sharpspring_updated_queue($updated_contacts_array){

	$dbhost = db_host;
	$dbname = db_name;
	$dbusername = db_user;
	$dbpassword = db_pass;
	if(is_array($updated_contacts_array) && count($updated_contacts_array) != 0){
		$connection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbusername, $dbpassword);

		for ($i = 0; $i < count($updated_contacts_array); $i++) {
			$query = $connection->query("SELECT id FROM update_queue WHERE entity_origin ='Sharpspring' AND entity_id = '".$updated_contacts_array[$i]['id']."'");
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if(!isset($result['id'])){

				$query = $connection->prepare('INSERT INTO update_queue (entity_id, entity_origin)
				    VALUES (:entity_id, :entity_origin)');

				$query->execute([
				    'entity_id' => $updated_contacts_array[$i]['id'],
				    'entity_origin' => 'Sharpspring',
				]);
			}

		}
	}
}
//unit test
//save_to_sharpspring_updated_queue($updated_contacts_list);


/*
* Save contacts in sharpspring create queue
*/
function save_to_sharpspring_create_queue($updated_contacts_id){

	$dbhost = db_host;
	$dbname = db_name;
	$dbusername = db_user;
	$dbpassword = db_pass;
	if($updated_contacts_id != ''){

		$connection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbusername, $dbpassword);

			$query = $connection->query("SELECT id FROM create_queue WHERE entity_origin ='Sharpspring' AND entity_id = '".$updated_contacts_id."'");
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if(!isset($result['id'])){
				echo $updated_contacts_id;
				$query = $connection->prepare('INSERT INTO create_queue (entity_id, entity_origin)
				    VALUES (:entity_id, :entity_origin)');

				$query->execute([
				    'entity_id' => $updated_contacts_id,
				    'entity_origin' => 'Sharpspring',
				]);
			}

	}
}
//unit test
//save_to_sharpspring_updated_queue($updated_contacts_list);


/*
*  remove contact from sharpspring queue
*/
function remove_sharpspring_queue_item($sharpspring_contact_array){
	if(is_array($sharpspring_contact_array) && isset($sharpspring_contact_array['queue_id'])){
		$dbhost = db_host;
		$dbname = db_name;
		$dbusername = db_user;
		$dbpassword = db_pass;
		$connection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbusername, $dbpassword);
		$query = $connection->query("DELETE FROM update_queue WHERE id = ".$sharpspring_contact_array['queue_id']);
		$query->execute();
	}
}
//unit test
//$sharpspring_contact_array['queue_id'] = 1;
//remove_sharpspring_queue_item($sharpspring_contact_array);


/*
*  remove contact from sharpspring queue
*/
function remove_sharpspring_create_queue_item($sharpspring_contact_id){
	if($sharpspring_contact_id != ''){
		$dbhost = db_host;
		$dbname = db_name;
		$dbusername = db_user;
		$dbpassword = db_pass;
		$connection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbusername, $dbpassword);
		$query = $connection->query("DELETE FROM create_queue WHERE id = ".$sharpspring_contact_id);
		$query->execute();
	}
}
//unit test
//$sharpspring_contact_array['queue_id'] = 1;
//remove_sharpspring_queue_item($sharpspring_contact_array);

/*
*  Update sharpspring contact
*/
function update_sharpspring_record($sharpspring_contact_array, $prophet_contact_array){

	if(isset($sharpspring_contact_array['id']) && $sharpspring_contact_array['id'] != ''){
				$method = 'updateLeads';
				 $limit = 1;
				 $offset = 0;
				 $lead_field_array = array(
				 	'id' => $sharpspring_contact_array['id']
				 	);
					$lead_field_array['companyName'] = $prophet_contact_array['company_name'];
					$lead_field_array['title'] = $prophet_contact_array['contact_job_title'];
					$lead_field_array['firstName'] = $prophet_contact_array['contact_first_name'];
					$lead_field_array['lastName'] = $prophet_contact_array['contact_last_name'];
					$lead_field_array['emailAddress'] = $prophet_contact_array['contact_email'];
					$lead_field_array['website'] = $prophet_contact_array['contact_website'];
					$lead_field_array['phoneNumber'] = $prophet_contact_array['contact_business_phone'];
					$lead_field_array['mobilePhoneNumber'] = $prophet_contact_array['contact_cell_phone'];
					$lead_field_array['faxNumber'] = $prophet_contact_array['contact_fax'];
					$lead_field_array['prophet_company_id_5a53c851764d2'] = $prophet_contact_array['contact_company_guid'];
					$lead_field_array['prophet_contact_id_5b58dc0543653'] = $prophet_contact_array['contact_guid'];
					$lead_field_array['lead_source_1_5bb5290b5f7a6'] = $prophet_contact_array['custom_lead_source1_text_value_for_sharpspring'];
					$lead_field_array['lead_source_2_5bb5291d8bac1'] = $prophet_contact_array['custom_lead_source2_text_value_for_sharpspring'];
					//start dropdowns
					$lead_field_array['double_opt_in_5bb528866df3e'] = $prophet_contact_array['custom_double_opt_in_text_value_for_sharpspring'];
					$lead_field_array['triversa_contact_5bb528b957a68'] = $prophet_contact_array['custom_triversa_contact_text_value_for_sharpspring'];
					$lead_field_array['subscribed_5bb528de86132'] = $prophet_contact_array['custom_subscribed_text_value_for_sharpspring'];
					$lead_field_array['bounced_5bb52944e4df0'] = $prophet_contact_array['custom_bounced_text_value_for_sharpspring'];
					$lead_field_array['expression_contact_5bb5296882b76'] = $prophet_contact_array['custom_expression_contact_text_value_for_sharpspring'];
					$lead_field_array['bounced_field_guid_5bb7973d283ac'] = $prophet_contact_array['custom_bounced_field_id'];
					$lead_field_array['double_opt_in_field_guid_5bb79750dc525'] = $prophet_contact_array['custom_double_opt_in_field_id'];
					$lead_field_array['expression_contact_field_guid_5bb79766d6412'] = $prophet_contact_array['custom_expression_contact_field_id'];
					$lead_field_array['prophet_unsubscribed_5bb7c727860e6'] = $prophet_contact_array['custom_unsubscribed_text_value_for_sharpspring'];
					$lead_field_array['triversa_contact_field_guid_5bb79967b4b2e'] = $prophet_contact_array['custom_triversa_contact_field_id'];
					$lead_field_array['prophet_unsubscribed_guid_5bb7c74544bf6'] = $prophet_contact_array['custom_unsubscribed_field_id'];
					$lead_field_array['subscribed_field_guid_5bb79922bcc53'] = $prophet_contact_array['custom_subscribed_field_id'];
					//end dropdowns
				 $params = array('objects' => array($lead_field_array));
				 $data = array(
				     'method' => $method,
				     'params' => $params,
				     'id' => sharpspring_requestid
				 );



				 $queryString = http_build_query(array('accountID' => sharpspring_account, 'secretKey' => sharpspring_key));
				 $url = "http://api.sharpspring.com/pubapi/v1/?$queryString";

				 $data = json_encode($data);
				 $ch = curl_init($url);
				 curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				 curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				 curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				     'Content-Type: application/json',
				     'Content-Length: ' . strlen($data)
				 ));

				 $result = curl_exec($ch);
				 curl_close($ch);
				 $result = json_decode( $result, true );
				 //print_r($result);
				 $api_lead_update = '';
				 //api connection response check
				  if(count($result['error']) === 0){
				        if($result['result']['updates'][0]['success'] === true){
				            //lead created record Sharpspring id
				          $api_lead_update = 'Updated';
				        }else{
				            //lead not created get error
				            $leadUpdateError = $result['result']['updates'][0]['error'];
				            $api_lead_update = 'Lead Update Error:'.$leadUpdateError;
				        }
				  }else{
				    //record error
				           $api_lead_update = 'Lead Update Error:'.$result['error']['message'];

				  }
	}
	return $api_lead_update;

}




/*
*  Create sharpspring contact
*/
function create_sharpspring_record($prophet_contact_array){

	if(isset($prophet_contact_array['contact_email']) && $prophet_contact_array['contact_email'] != ''){
				$method = 'createLeads';
				 $limit = 1;
				 $offset = 0;
					$lead_field_array['companyName'] = $prophet_contact_array['company_name'];
					$lead_field_array['title'] = $prophet_contact_array['contact_job_title'];
					$lead_field_array['firstName'] = $prophet_contact_array['contact_first_name'];
					$lead_field_array['lastName'] = $prophet_contact_array['contact_last_name'];
					$lead_field_array['emailAddress'] = $prophet_contact_array['contact_email'];
					$lead_field_array['website'] = $prophet_contact_array['contact_website'];
					$lead_field_array['phoneNumber'] = $prophet_contact_array['contact_business_phone'];
					$lead_field_array['mobilePhoneNumber'] = $prophet_contact_array['contact_cell_phone'];
					$lead_field_array['faxNumber'] = $prophet_contact_array['contact_fax'];
					$lead_field_array['prophet_company_id_5a53c851764d2'] = $prophet_contact_array['contact_company_guid'];
					$lead_field_array['prophet_contact_id_5b58dc0543653'] = $prophet_contact_array['contact_guid'];
					$lead_field_array['lead_source_1_5bb5290b5f7a6'] = $prophet_contact_array['custom_lead_source1_text_value_for_sharpspring'];
					$lead_field_array['lead_source_2_5bb5291d8bac1'] = $prophet_contact_array['custom_lead_source2_text_value_for_sharpspring'];
					//start dropdowns
					$lead_field_array['double_opt_in_5bb528866df3e'] = $prophet_contact_array['custom_double_opt_in_text_value_for_sharpspring'];
					$lead_field_array['triversa_contact_5bb528b957a68'] = $prophet_contact_array['custom_triversa_contact_text_value_for_sharpspring'];
					$lead_field_array['subscribed_5bb528de86132'] = $prophet_contact_array['custom_subscribed_text_value_for_sharpspring'];
					$lead_field_array['bounced_5bb52944e4df0'] = $prophet_contact_array['custom_bounced_text_value_for_sharpspring'];
					$lead_field_array['expression_contact_5bb5296882b76'] = $prophet_contact_array['custom_expression_contact_text_value_for_sharpspring'];
					$lead_field_array['bounced_field_guid_5bb7973d283ac'] = $prophet_contact_array['custom_bounced_field_id'];
					$lead_field_array['double_opt_in_field_guid_5bb79750dc525'] = $prophet_contact_array['custom_double_opt_in_field_id'];
					$lead_field_array['expression_contact_field_guid_5bb79766d6412'] = $prophet_contact_array['custom_expression_contact_field_id'];
					$lead_field_array['prophet_unsubscribed_5bb7c727860e6'] = $prophet_contact_array['custom_unsubscribed_text_value_for_sharpspring'];
					$lead_field_array['triversa_contact_field_guid_5bb79967b4b2e'] = $prophet_contact_array['custom_triversa_contact_field_id'];
					$lead_field_array['prophet_unsubscribed_guid_5bb7c74544bf6'] = $prophet_contact_array['custom_unsubscribed_field_id'];
					$lead_field_array['subscribed_field_guid_5bb79922bcc53'] = $prophet_contact_array['custom_subscribed_field_id'];
					//end dropdowns
				 $params = array('objects' => array($lead_field_array));
				 $data = array(
				     'method' => $method,
				     'params' => $params,
				     'id' => sharpspring_requestid
				 );



				 $queryString = http_build_query(array('accountID' => sharpspring_account, 'secretKey' => sharpspring_key));
				 $url = "http://api.sharpspring.com/pubapi/v1/?$queryString";

				 $data = json_encode($data);
				 $ch = curl_init($url);
				 curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				 curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				 curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				     'Content-Type: application/json',
				     'Content-Length: ' . strlen($data)
				 ));

				 $result = curl_exec($ch);
				 curl_close($ch);
				 $result = json_decode( $result, true );
				 //print_r($result);
				 $api_lead_update = '';
				 //api connection response check
				 //Array ( [result] => Array ( [creates] => Array ( [0] => Array ( [success] => 1 [error] => [id] => 614437518339 ) ) ) [error] => Array ( ) [id] => )
				  if(count($result['error']) === 0){
				        if($result['result']['creates'][0]['success'] === true){
				            //lead created record Sharpspring id
				          $api_lead_update = 'Created';
				        }else{
				            //lead not created get error
				            $leadUpdateError = $result['result']['updates'][0]['error'];
				            $api_lead_update = 'Lead Update Error:'.$leadUpdateError;
				        }
				  }else{
				    //record error
				           $api_lead_update = 'Lead Update Error:'.$result['error']['message'];

				  }
	}
	return $api_lead_update;

}
