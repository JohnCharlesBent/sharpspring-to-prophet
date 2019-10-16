<?php
//init vars
$lead_email ='';
$prophet_company_id = '';
$sharpspring_lead_id = '';
$prophet_company_name = '';
$sharpspring_company_name = '';
//sharpspring API init
$requestID = session_id();       
$accountID = '329023B8902CDD5B9FD8D4223C53D1AC';
$secretKey = 'B1165B8A2ACD262E2DB76B137C7905CE'; 
//end sharpspring API init
$_POST['emailsb34xpsplwq'] = 'test2018t1@tizinc.com';


if(isset($_POST['emailsb34xpsplwq']) && $_POST['emailsb34xpsplwq'] != ''){
		//=====================
		//get sharprping leads with empty company name value and value in the prophet company id field
		//=====================                                               
		$method = 'getLeads';
		$limit = 1;    
		$offset = 0;                                                           
		$params = array('where' => array('emailAddress' => $_POST['emailsb34xpsplwq']), 'limit' => $limit, 'offset' => $offset);
		                                                               
		$data = array(                                                                                
		 'method' => $method,                                                                      
		 'params' => $params,                                                                      
		 'id' => $requestID,                                                                       
		);                                                                                            
		                                                                                           
		$queryString = http_build_query(array('accountID' => $accountID, 'secretKey' => $secretKey)); 
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
		//var_dump($result);                                                                               


		if(count($result['error']) === 0){

		//results were returned 
		if(count($result['result']['lead']) != 0){
		    //lead exists record Sharpspring ID
		    $lead_email = $result['result']['lead'][0]['emailAddress'];
		    $sharpspring_lead_id = $result['result']['lead'][0]['id'];
		    $prophet_company_id = $result['result']['lead'][0]['prophet_company_id_5a53c851764d2'];
		    $sharpspring_company_name = $result['result']['lead'][0]['companyName'];
		    
		}
		    //echo $lead_email;
		    //echo $prophet_company_id; 
		}

		//
		//end get sharpspring leads

		//kill if company name already populated (leads from prophet will have name)
		if($sharpspring_company_name != ''){
			//echo "die";
			die();
		}


		if($prophet_company_id != ''){

				//connect to prophet and get company name
				$prophetusername = urlencode("admin@b2b-affiliate-networks.com");
				$prophetuserpassword = urlencode("tw2017");
				$ProphetAPIBaseUrl = "https://www.prophetOnDemand.com/prophet/prophetwebservices/AvtProphetApi/";
				$ProphetOdataAPIBaseUrl = "https://www.prophetOnDemand.com/prophet/prophetwebservices/AvtProphetApi/odata/";


				$url = $ProphetAPIBaseUrl."api/Token?userName=".$prophetusername."&password=".$prophetuserpassword;
				//echo '<h1>'.$url.'</h1>';

				//auth into prophet
				$curl = curl_init($url);
				curl_setopt($curl, CURLOPT_HEADER, false);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

				// Make the REST call, returning the result
				$response = curl_exec($curl);
				if (!$response) {
				    die("Connection Failure.n");
				}
				//print_r($response);
				$token = json_decode($response);
				//echo $token;
				//after you have token



				$url = $ProphetOdataAPIBaseUrl."Companies"."(guid'".$prophet_company_id."')"."";

				//echo '<h1>'.$url.'</h1>';

				$curl = curl_init($url);
				curl_setopt($curl, CURLOPT_HEADER, false);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json',"Authorization:Token $token"));
				$response = curl_exec($curl);

				if (!$response) {
				    die("Connection Failure.n");
				}

				//print_r($response);
				$result_json_d = (array)json_decode($response);
				//echo '<h1>decoded</h1>';

				if(array_key_exists('odata.error',$result_json_d)){
					//echo 'error';
					mail('support@tizinc.com','Prophet Company Name Update Error',print_r($result_json_d,true));
					die();
				}else{
					//print_r($result_json_d['Name']);
					$prophet_compny_name = $result_json_d['Name'];
				}
				//end prophet
		}


            //============
            //Update Lead

		
            if($prophet_compny_name != ''){
				           
				            //============
				            //Update Lead
				            //============
				            $method = 'updateLeads';
				             $limit = 1;    
				             $offset = 0;
				             $lead_field_array = array(
				             	'id' => $sharpspring_lead_id,
								'companyName' => $prophet_compny_name
				             	);                                                          
				             $params = array('objects' => array($lead_field_array));                                                                
				             $data = array(                                                                                
				                 'method' => $method,                                                                      
				                 'params' => $params,                                                                      
				                 'id' => $requestID                                                                     
				             );                                                                                            
				                                                                                                           
				             $queryString = http_build_query(array('accountID' => $accountID, 'secretKey' => $secretKey)); 
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
				             //var_dump($result); 
				             //api connection response check
				              if(count($result['error']) === 0){
				                    if($result['result']['updates'][0]['success'] === true){
				                        //lead created record Sharpspring id 
				                      $api_lead_update = 'Updated!';

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



}