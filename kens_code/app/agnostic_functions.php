<?php
//require 'config.php';
//date_default_timezone_set('America/New_York');


/*
* diff two leads going. return update, ignore or error.
*/
function diff_contacts($prophet_contact_array,$sharpspring_contact_array,$destination){

	$sync_event_type = '';
	$sync_difference_log = '';

	if( (!is_array($prophet_contact_array) || count($prophet_contact_array) == 0) && $destination == 'Prophet'){
		//missing from prophet
		$sync_event_type = 'Ignore -1';
	}
	if( (!is_array($sharpspring_contact_array) || count($sharpspring_contact_array) == 0) && $destination == 'Sharpspring'){
		//missing from sharpspring
		$sync_event_type = 'Ignore -2';
	}
	if( (!is_array($prophet_contact_array) || count($prophet_contact_array) == 0) && $destination == 'Sharpspring'){
		//missing from prophet
		$sync_event_type = 'Ignore -3';
	}
	if( (!is_array($sharpspring_contact_array) || count($sharpspring_contact_array) == 0) && $destination == 'Prophet'){
		//missing from sharpspring
		$sync_event_type = 'Ignore -4';
	}
	if( isset($prophet_contact_array['updateddate']) && isset($prophet_contact_array['createddate']) ){
		if($prophet_contact_array['updateddate'] == $prophet_contact_array['createddate']){
			$sync_event_type = 'Ignore -5';
		}
	}


	//if($sync_event_type == ''){
/*
		//make sure we are not overwriting a previous sync to another record
		if($sharpspring_contact_array['prophet_contact_id_5b58dc0543653'] != '' && $prophet_contact_array['contact_guid'] != '' ){
			if($sharpspring_contact_array['prophet_contact_id_5b58dc0543653'] != $prophet_contact_array['contact_guid']){
				$sync_event_type = 'Error -1';
			}
		}

		if($sharpspring_contact_array['id'] != '' && $prophet_contact_array['external_id'] != '' ){
			if($sharpspring_contact_array['id'] != $prophet_contact_array['external_id']){
				$sync_event_type = 'Error -2';
			}
		}
*/

		//if($sync_event_type == ''){


				//test company name
				if(isset($prophet_contact_array['company_name']) && isset($sharpspring_contact_array['companyName'])){
					if($sharpspring_contact_array['companyName'] != $prophet_contact_array['company_name']){
						$sync_event_type = 'Update';
						$sync_difference_log = $sync_difference_log.': Company Name';
					}
				}


				//test first name
				if(isset($prophet_contact_array['contact_first_name']) && isset($sharpspring_contact_array['firstName'])){
					if($prophet_contact_array['contact_first_name'] != $sharpspring_contact_array['firstName']){
						$sync_event_type = 'Update';
						$sync_difference_log = $sync_difference_log.': First Name';
					}
				}

				//test last name
				if(isset($prophet_contact_array['contact_last_name']) && isset($sharpspring_contact_array['lastName'])){
					if($prophet_contact_array['contact_last_name'] != $sharpspring_contact_array['lastName']){
						$sync_event_type = 'Update';
						$sync_difference_log = $sync_difference_log.': Last Name';
					}
				}

				//test job title
				if(isset($prophet_contact_array['contact_job_title']) && isset($sharpspring_contact_array['title'])){
					if($prophet_contact_array['contact_job_title'] != $sharpspring_contact_array['title']){
						$sync_event_type = 'Update';
						$sync_difference_log = $sync_difference_log.': Title';
					}
				}

				//test email
				if(isset($prophet_contact_array['contact_email']) && isset($sharpspring_contact_array['emailAddress'])){
					if($prophet_contact_array['contact_email'] != $sharpspring_contact_array['emailAddress']){
						$sync_event_type = 'Update';
						$sync_difference_log = $sync_difference_log.': Email';
					}
				}

				//test company guid
				if(isset($prophet_contact_array['contact_company_guid']) && isset($sharpspring_contact_array['prophet_company_id_5a53c851764d2'])){
					if($prophet_contact_array['contact_company_guid']!= $sharpspring_contact_array['prophet_company_id_5a53c851764d2']){
						$sync_event_type = 'Update';
						$sync_difference_log = $sync_difference_log.': Company GUID';
					}
				}

				//test 
				if(isset($prophet_contact_array['contact_business_phone']) && isset($sharpspring_contact_array['phoneNumber'])){
					if($prophet_contact_array['contact_business_phone']!= $sharpspring_contact_array['phoneNumber']){
						$sync_event_type = 'Update';
						$sync_difference_log = $sync_difference_log.': Phone';
					}
				}

				//test 
				if(isset($prophet_contact_array['contact_website']) && isset($sharpspring_contact_array['website'])){
					if($prophet_contact_array['contact_website']!= $sharpspring_contact_array['website']){
						$sync_event_type = 'Update';
						$sync_difference_log = $sync_difference_log.': Website';
					}
				}

				//test 
				if(isset($prophet_contact_array['contact_fax']) && isset($sharpspring_contact_array['faxNumber'])){
					if($prophet_contact_array['contact_fax']!= $sharpspring_contact_array['faxNumber']){
						$sync_event_type = 'Update';
						$sync_difference_log = $sync_difference_log.': Fax';
					}
				}

				//test 
				if(isset($prophet_contact_array['contact_cell_phone']) && isset($sharpspring_contact_array['mobilePhoneNumber'])){
					if($prophet_contact_array['contact_cell_phone']!= $sharpspring_contact_array['mobilePhoneNumber']){
						$sync_event_type = 'Update';
						$sync_difference_log = $sync_difference_log.': Cell';
					}
				}

				//test 
				if(isset($prophet_contact_array['custom_double_opt_in']) && isset($sharpspring_contact_array['double_opt_in_5bb528866df3e'])){
					if($prophet_contact_array['custom_double_opt_in_text_value_for_sharpspring']!= $sharpspring_contact_array['double_opt_in_5bb528866df3e']){
						$sync_event_type = 'Update';
						$sync_difference_log = $sync_difference_log.': Double opt in';
					}
				}

				//test 
				if(isset($prophet_contact_array['custom_double_opt_in_field_id']) && isset($sharpspring_contact_array['double_opt_in_field_guid_5bb79750dc525'])){
					if($prophet_contact_array['custom_double_opt_in_field_id']!= $sharpspring_contact_array['double_opt_in_field_guid_5bb79750dc525']){
						$sync_event_type = 'Update';
						$sync_difference_log = $sync_difference_log.': Double opt in GUID';
					}
				}


				//test 
				if(isset($prophet_contact_array['custom_triversa_contact']) && isset($sharpspring_contact_array['triversa_contact_5bb528b957a68'])){
					if($prophet_contact_array['custom_triversa_contact_text_value_for_sharpspring']!= $sharpspring_contact_array['triversa_contact_5bb528b957a68']){
						$sync_event_type = 'Update';
						$sync_difference_log = $sync_difference_log.': custom_triversa_contact';
					}
				}

				//test 
				if(isset($prophet_contact_array['custom_triversa_contact_field_id']) && isset($sharpspring_contact_array['triversa_contact_field_guid_5bb79967b4b2e'])){
					if($prophet_contact_array['custom_triversa_contact_field_id']!= $sharpspring_contact_array['triversa_contact_field_guid_5bb79967b4b2e']){
						$sync_event_type = 'Update';
						$sync_difference_log = $sync_difference_log.': custom_triversa_contact GUID';
					}
				}


				//test 
				if(isset($prophet_contact_array['custom_subscribed']) && isset($sharpspring_contact_array['subscribed_5bb528de86132'])){
					if($prophet_contact_array['custom_subscribed_text_value_for_sharpspring']!= $sharpspring_contact_array['subscribed_5bb528de86132']){
						$sync_event_type = 'Update';
						$sync_difference_log = $sync_difference_log.': custom_subscribed';
					}
				}

				//test 
				if(isset($prophet_contact_array['custom_subscribed_field_id']) && isset($sharpspring_contact_array['subscribed_field_guid_5bb79922bcc53'])){
					if($prophet_contact_array['custom_subscribed_field_id']!= $sharpspring_contact_array['subscribed_field_guid_5bb79922bcc53']){
						$sync_event_type = 'Update';
						$sync_difference_log = $sync_difference_log.': custom_subscribed GUID';
					}
				}


				//test 
				if(isset($prophet_contact_array['custom_unsubscribed']) && isset($sharpspring_contact_array['prophet_unsubscribed_5bb7c727860e6'])){
					if($prophet_contact_array['custom_unsubscribed_text_value_for_sharpspring']!= $sharpspring_contact_array['prophet_unsubscribed_5bb7c727860e6']){
						$sync_event_type = 'Update';
						$sync_difference_log = $sync_difference_log.': custom_unscribed';
					}
				}

				//test 
				if(isset($prophet_contact_array['custom_unsubscribed_field_id']) && isset($sharpspring_contact_array['prophet_unsubscribed_guid_5bb7c74544bf6'])){
					if($prophet_contact_array['custom_unsubscribed_field_id']!= $sharpspring_contact_array['prophet_unsubscribed_guid_5bb7c74544bf6']){
						$sync_event_type = 'Update';
						$sync_difference_log = $sync_difference_log.': custom_unscribed GUID';
					}
				}


				//test 
				if(isset($prophet_contact_array['custom_lead_source1']) && isset($sharpspring_contact_array['lead_source_1_5bb5290b5f7a6'])){
					if($prophet_contact_array['custom_lead_source1']!= $sharpspring_contact_array['lead_source_1_5bb5290b5f7a6']){
						$sync_event_type = 'Update';
						$sync_difference_log = $sync_difference_log.': custom_lead_source1';
					}
				}

				//test 
				if(isset($prophet_contact_array['custom_lead_source1_field_id']) && isset($sharpspring_contact_array['lead_source_1_field_guid_5bb7977f90ae5'])){
					if($prophet_contact_array['custom_lead_source1_field_id']!= $sharpspring_contact_array['lead_source_1_field_guid_5bb7977f90ae5']){
						$sync_event_type = 'Update';
						$sync_difference_log = $sync_difference_log.': custom_lead_source1 GUID';
					}
				}


				//test 
				if(isset($prophet_contact_array['custom_lead_source2']) && isset($sharpspring_contact_array['lead_source_2_5bb5291d8bac1'])){
					if($prophet_contact_array['custom_lead_source2']!= $sharpspring_contact_array['lead_source_2_5bb5291d8bac1']){
						$sync_event_type = 'Update';
						$sync_difference_log = $sync_difference_log.': custom_lead_source2';
					}
				}

				//test 
				if(isset($prophet_contact_array['custom_lead_source2_field_id']) && isset($sharpspring_contact_array['lead_source_2_field_guid_5bb7990013c94'])){
					if($prophet_contact_array['custom_lead_source2_field_id']!= $sharpspring_contact_array['lead_source_2_field_guid_5bb7990013c94']){
						$sync_event_type = 'Update';
						$sync_difference_log = $sync_difference_log.': custom_lead_source2 GUID';
					}
				}


				//test 
				if(isset($prophet_contact_array['custom_bounced']) && isset($sharpspring_contact_array['bounced_5bb52944e4df0'])){
					if($prophet_contact_array['custom_bounced_text_value_for_sharpspring']!= $sharpspring_contact_array['bounced_5bb52944e4df0']){
						$sync_event_type = 'Update';
						$sync_difference_log = $sync_difference_log.': custom_bounced';
					}
				}

				//test 
				if(isset($prophet_contact_array['custom_bounced_field_id']) && isset($sharpspring_contact_array['bounced_field_guid_5bb7973d283ac'])){
					if($prophet_contact_array['custom_bounced_field_id']!= $sharpspring_contact_array['bounced_field_guid_5bb7973d283ac']){
						$sync_event_type = 'Update';
						$sync_difference_log = $sync_difference_log.': custom_bounced GUID';
					}
				}


				//test 
				if(isset($prophet_contact_array['custom_expression_contact']) && isset($sharpspring_contact_array['expression_contact_5bb5296882b76'])){
					if($prophet_contact_array['custom_expression_contact_text_value_for_sharpspring']!= $sharpspring_contact_array['expression_contact_5bb5296882b76']){
						$sync_event_type = 'Update';
						$sync_difference_log = $sync_difference_log.': custom_expression_contact';
					}
				}

				//test 
				if(isset($prophet_contact_array['custom_expression_contact_field_id']) && isset($sharpspring_contact_array['expression_contact_field_guid_5bb79766d6412'])){
					if($prophet_contact_array['custom_expression_contact_field_id']!= $sharpspring_contact_array['expression_contact_field_guid_5bb79766d6412']){
						$sync_event_type = 'Update';
						$sync_difference_log = $sync_difference_log.': custom_expression_contact GUID';
					}
				}
	
		//}

	//}

	if($sync_event_type == ''){$sync_event_type = 'Ignore -6';}
	
	//print_r($sync_difference_log);

	return $sync_event_type;

}

