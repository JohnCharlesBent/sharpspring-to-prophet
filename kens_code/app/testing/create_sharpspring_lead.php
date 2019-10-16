<?php
require '../config.php';
				$method = 'createLeads';
				 $limit = 1;    
				 $offset = 0; 
					$lead_field_array['companyName'] = 'test company';
					$lead_field_array['title'] = 'test title';
					$lead_field_array['firstName'] = 'test';
					$lead_field_array['lastName'] = 'testerson';
					$lead_field_array['emailAddress'] = 'test2@mctest.com';
					$lead_field_array['website'] = '';
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
				 print_r($result);                                                                                      
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