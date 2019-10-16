<?php
/******
* Functions used to pull data from a Sharpspring account and compare it to data from Prophet
******/
require 'config.php';


//error_reporting(E_ALL);
//ini_set('display_errors', 1);

$sharpspring_data = array();
$sharpspring_data_display = '';
$prophet_data = array();
$prophet_data_display = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'):
  if(isset($_POST['ss_id'])):
    $ss_id = $_POST['ss_id'];

    $limit = 500;
    $offset = 0;

    $method = 'getLeads';
    $params = array( 'where'=> array('id' => $ss_id), 'limit' => $limit, 'offset' => $offset);

    $requestID = sharpspring_requestid;
    $accountID = urlencode(sharpspring_account);
    $ssKey = urlencode(sharpspring_key);

    $data = array(
      'method' => $method,
      'params' => $params,
      'id' => $requestID,
    );

    $queryString = http_build_query(array('accountID' => $accountID, 'secretKey' => $ssKey));
    $url = "http://api.sharpspring.com/pubapi/v1/?$queryString";


    $data = json_encode($data);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type:application/json',
      'Content-Length:'.strlen($data)
    ));

    $result = curl_exec($ch);
    curl_close($ch);

    $ss_json_data = json_decode($result, true);
    //print_r($ss_json_data);

    $ss_data = $ss_json_data['result']['lead'][0];
    $prophet_contact_id = $ss_json_data['result']['lead'][0]['prophet_contact_id_5b58dc0543653'];
    //$prophet_contact_id = '52d31dba-2753-0080-5206-7c263e5be6ca';
    //var_dump($prophet_contact_id);
    foreach($ss_data as $key => $value) {

      $sharpspring_data[$key] = $value;
    }


    // get contact data from Prophet
    $url = prophet_url."api/Token?userName=".prophet_user."&password=".prophet_pass;

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER,false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    $response = curl_exec($curl);
    if(!$response) {
      die("connection failed");
    } else {
      $token = json_decode($response);
        //var_dump($token);
      $url = prophet_url."/odata/Contacts"."(guid'".$prophet_contact_id."')";
  		$curl = curl_init($url);
  		curl_setopt($curl, CURLOPT_HEADER, false);
  		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json',"Authorization:Token $token"));
  		$response = curl_exec($curl);
      $response = (array)json_decode($response);
      $prophet_data['contact_data'] = $response;
      //var_dump($response);
    }


    // get text custom field data from prophet
    $curl = curl_init(prophet_url."/odata/CustomFieldValueTexts?".urlencode('$')."filter=".urlencode("EntityId eq (guid'".$prophet_contact_id."')"));
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', "Authorization:Token $token"));
    $response = curl_exec($curl);
    if(!$response) {
      //die("connection failure");
    } else {
      $response = (array)json_decode($response);
      $prophet_data['custom_fields__text'] = $response;
    }

    // get dropdown text fields from prophet
    $curl = curl_init(prophet_url."/odata/CustomFieldValueDropdowns?".urlencode('$')."filter=".urlencode("EntityId eq (guid'".$prophet_contact_id."')"));
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', "Authorization:Token $token"));
    $response = curl_exec($curl);
    if(!$response) {
      //die("Connection Failure");
    } else {
      $reponse = (array)json_decode($response);
      $prophet_data['custom_fields__dropdown'] = $response;
    }
    // get date custom fields from prophet
    $curl = curl_init(prophet_url."/odata/CustomFieldValueDates?".urlencode('$')."filter=".urlencode("EntityId eq (guid'".$prophet_contact_id."')"));
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', "Authorization:Token $token"));
    $response = curl_exec($curl);
    if(!$response) {
      //die("Connection Failure");
    } else {
      $response = (array)json_decode($response);
      $prophet_data['custom_fields__date'] = $response;
    }


  endif;
  echo '<pre>';
  var_dump($prophet_data);
  echo '</pre>';
  echo '<hr>';
  $sharpspring_data_display .= '<div class="sharpspring_data__wrap">'.
                                  '<ul class="data-list">'.
                                    '<li data-id="ss_id"><strong>Sharpspring ID:</strong> '.$sharpspring_data['id'].'</li>'.
                                    '<li data-id="ss_accountID"><strong>Sharpspring Account ID:</strong> '.$sharpspring_data['accountID'].'</li>'.
                                    '<li data-id="ss_company-name"><strong>Company Name:</strong> '.$sharpspring_data['companyName'].'</li>'.
                                    '<li data-id="ss_title"><strong>Title:</strong> '.$sharpspring_data['title'].'</li>'.
                                    '<li data-id="ss_first-name"><strong>First Name:</strong> '.$sharpspring_data['firstName'].'</li>'.
                                    '<li data-id="ss_last-name"><strong>Last Name:</strong> '.$sharpspring_data['lastName'].'</li>'.
                                    '<li data-id="ss_street"><strong>Street:</strong> '.$sharpspring_data['street'].'</li>'.
                                    '<li data-id="ss_city"><strong>City:</strong> '.$sharpspring_data['city'].'</li>'.
                                    '<li data-id="ss_country"><strong>Country:</strong> '.$sharpspring_data['country'].'</li>'.
                                    '<li data-id="ss_state"><strong>State:</strong> '.$sharpspring_data['state'].'</li>'.
                                    '<li data-id="ss_zipcode"><strong>Zipcode:</strong> '.$sharpspring_data['zipcode'].'</li>'.
                                    '<li data-id="ss_emailAddress"><strong>Email Address:</strong> '.$sharpspring_data['emailAddress'].'</li>'.
                                    '<li data-id="ss_website"><strong>Website:</strong> '.$sharpspring_data['website'].'</li>'.
                                    '<li data-id="ss_phone"><strong>Phone Number:</strong> '.$sharpspring_data['phoneNumber'].'</li>'.
                                    '<li data-id="ss_office-phone"><strong>Office Phone:</strong> '.$sharpspring_data['officePhoneNumber'].'</li>'.
                                    '<li data-id="ss_mobile-phone"><strong>Mobile Phone:</strong> '.$sharpspring_data['mobilePhoneNumber'].'</li>'.
                                    '<li data-id="ss_fax-number"><strong>Fax Number:</strong> '.$sharpspring_data['faxNumber'].'</li>'.
                                    '<li data-id="ss_description"><strong>Description:</strong> '.$sharpspring_data['description'].'</li>'.
                                    '<li data-id="ss_campaign-id"><strong>Campaign ID:</strong> '.$sharpspring_data['campaignID'].'</li>'.
                                    '<li data-id="ss_tracking-id"><strong>Tracking ID:</strong> '.$sharpspring_data['trackingID'].'</li>'.
                                    '<li data-id="ss_industry"><strong>Industry:</strong> '.$sharpspring_data['industry'].'</li>'.
                                    '<li data-id="ss_active"><strong>Active:</strong> '.$sharpspring_data['active'].'</li>'.
                                    '<li data-id="ss_last-updated"><strong>Account Last Updated:</strong> '.$sharpspring_data['updateTimestamp'].'</li>'.
                                    '<li data-id="ss_time-created"><strong>Date/Time Created:</strong> '.$sharpspring_data['createTimestamp'].'</li>'.
                                    '<li data-id="ss_lead-score-weighted"><strong>Lead Score (Weighted):</strong> '.$sharpspring_data['leadScoreWeighted'].'</li>'.
                                    '<li data-id="ss_lead-score"><strong>Lead Score:</strong> '.$sharpspring_data['leadScore'].'</li>'.
                                    '<li data-id="ss_unsubscsribed"><strong>Unsubscribed:</strong> '.$sharpspring_data['isUnsubscribed'].'</li>'.
                                    '<li data-id="ss_lead-status"><strong>Lead Status:</strong> '.$sharpspring_data['leadStatus'].'</li>'.
                                    '<li data-id="ss_persona"><strong>Persona:</strong> '.$sharpspring_data['persona'].'</li>'.
                                    '<li data-id="ss_prophet-company-id"><strong>prophet_company_id_5a53c851764d2:</strong> '.$sharpspring_data['prophet_company_id_5a53c851764d2'].'</li>'.
                                    '<li data-id="ss_prophet-contact-id"><strong>prophet_contact_id_5b58dc0543653:</strong> '.$sharpspring_data['prophet_contact_id_5b58dc0543653'].'</li>'.
                                    '<li data-id="ss_deliverable-type"><strong>deliverable_type_5baa8049240db:</strong> '.$sharpspring_data['deliverable_type_5baa8049240db'].'</li>'.
                                    '<li data-id="ss_deliverable-one"><strong>deliverable_1_5baa805cbf232:</strong> '.$sharpspring_data['deliverable_1_5baa805cbf232'].'</li>'.
                                    '<li data-id="ss_deliverable-two"><strong>deliverable_2_5baa806851577:</strong> '.$sharpspring_data['deliverable_2_5baa806851577'].'</li>'.
                                    '<li data-id="ss_source-id"><strong>source_id_5baa8ca455679:</strong> '.$sharpspring_data['source_id_5baa8ca455679'].'</li>'.
                                    '<li data-id="ss_last-form-activity"><strong>last_form_activity_5bad18d35f9b8:</strong> '.$sharpspring_data['last_form_activity_5bad18d35f9b8'].'</li>'.
                                    '<li data-id="ss_double_opt_in"><strong>double_opt_in_5bb528866df3e:</strong> '.$sharpspring_data['double_opt_in_5bb528866df3e'].'</li>'.
                                    '<li data-id="ss_triversa_contact"><strong>triversa_contact_5bb528b957a68:</strong> '.$sharpspring_data['triversa_contact_5bb528b957a68'].'</li>'.
                                    '<li data-id="ss_subscribed"><strong>subscribed_5bb528de86132:</strong> '.$sharpspring_data['subscribed_5bb528de86132'].'</li>'.
                                    '<li data-id="ss_subscribed"><strong>subscribed_5bb528de86132:</strong> '.$sharpspring_data['subscribed_5bb528de86132'].'</li>'.
                                    '<li data-id="ss_lead-source-one"><strong>lead_source_1_5bb5290b5f7a6:</strong> '.$sharpspring_data['lead_source_1_5bb5290b5f7a6'].'</li>'.
                                    '<li data-id="ss_lead-source-two"><strong>lead_source_2_5bb5291d8bac1:</strong> '.$sharpspring_data['lead_source_2_5bb5291d8bac1'].'</li>'.
                                    '<li data-id="ss_bounced"><strong>bounced_5bb52944e4df0:</strong> '.$sharpspring_data['bounced_5bb52944e4df0'].'</li>'.
                                    '<li data-id="ss_expression-contact"><strong>expression_contact_5bb5296882b76:</strong> '.$sharpspring_data['expression_contact_5bb5296882b76'].'</li>'.
                                    '<li data-id="ss_bounced_field_guid"><strong>bounced_field_guid_5bb7973d283ac:</strong> '.$sharpspring_data['bounced_field_guid_5bb7973d283ac'].'</li>'.
                                    '<li data-id="ss_double_opt_in_field_guid"><strong>double_opt_in_field_guid_5bb79750dc525:</strong> '.$sharpspring_data['double_opt_in_field_guid_5bb79750dc525'].'</li>'.
                                    '<li data-id="ss_expression_contact_field_guid"><strong>expression_contact_field_guid_5bb79766d6412:</strong> '.$sharpspring_data['expression_contact_field_guid_5bb79766d6412'].'</li>'.
                                    '<li data-id="ss_lead_source_1_field_guid"><strong>lead_source_1_field_guid_5bb7977f90ae5:</strong> '.$sharpspring_data['lead_source_1_field_guid_5bb7977f90ae5'].'</li>'.
                                    '<li data-id="ss_lead_source_2_field_guid"><strong>lead_source_2_field_guid_5bb7990013c94:</strong> '.$sharpspring_data['lead_source_2_field_guid_5bb7990013c94'].'</li>'.
                                    '<li data-id="ss_subscribed_field_guid"><strong>subscribed_field_guid_5bb79922bcc53:</strong> '.$sharpspring_data['subscribed_field_guid_5bb79922bcc53'].'</li>'.
                                    '<li data-id="ss_subscribed_field_guid"><strong>subscribed_field_guid_5bb79922bcc53:</strong> '.$sharpspring_data['subscribed_field_guid_5bb79922bcc53'].'</li>'.
                                    '<li data-id="ss_triversa_contact_field_guid"><strong>triversa_contact_field_guid_5bb79967b4b2e:</strong> '.$sharpspring_data['triversa_contact_field_guid_5bb79967b4b2e'].'</li>'.
                                    '<li data-id="ss_prophet_unsubscribed"><strong>prophet_unsubscribed_5bb7c727860e6:</strong> '.$sharpspring_data['prophet_unsubscribed_5bb7c727860e6'].'</li>'.
                                    '<li data-id="ss_prophet_unsubscribed_guid"><strong>prophet_unsubscribed_guid_5bb7c74544bf6:</strong> '.$sharpspring_data['prophet_unsubscribed_guid_5bb7c74544bf6'].'</li>'.
                                    '<li data-id="ss_lead_owner"><strong>lead_owner_5d14d13cd207f:</strong> '.$sharpspring_data['lead_owner_5d14d13cd207f'].'</li>'.
                                    '<li data-id="ss_qualified"><strong>qualified_5d14d401114b6:</strong> '.$sharpspring_data['qualified_5d14d401114b6'].'</li>'.
                                    '<li data-id="ss_solation_icp_ms_contact"><strong>solation_icp_ms_contact_5d14d47bca5a9:</strong> '.$sharpspring_data['solation_icp_ms_contact_5d14d47bca5a9'].'</li>'.
                                  '</ul>'.
                                '</div>';




  $prophet_data_display .= '<div class="prophet-data__wrap>"';
                            if(!empty($prophet_data['contact_data'])):
  $prophet_data_display .= '<h2>Prophet Account Data</h2>'.
                              '<ul class="data-list">'.
                                '<li data-id="Full Name"><strong>Full Name: </strong> '.$prophet_data['contact_data']['FullName'].'</li>'.
                                '<li data-id="First Name"><strong>First Name: </strong> '.$prophet_data['contact_data']['FirstName'].'</li>'.
                                '<li data-id="Last Name"><strong>Last Name: </strong> '.$prophet_data['contact_data']['LastName'].'</li>'.
                                '<li data-id="Middle Name"><strong>Middle Name: </strong> '.$prophet_data['contact_data']['MiddleName'].'</li>'.
                                '<li data-id="Job Title"><strong>Job Title: </strong> '.$prophet_data['contact_data']['JobTitle'].'</li>'.
                                '<li data-id="Title"><strong>Title: </strong> '.$prophet_data['contact_data']['Title'].'</li>'.
                                '<li data-id="Email"><strong>Email: </strong> '.$prophet_data['contact_data']['Email'].'</li>'.
                                '<li data-id="Email2"><strong>Email 2: </strong> '.$prophet_data['contact_data']['Email2'].'</li>'.
                                '<li data-id="Email3"><strong>Email 3: </strong> '.$prophet_data['contact_data']['Email3'].'</li>'.
                                '<li data-id="Main Company ID"><strong>Main Company ID: </strong> '.$prophet_data['contact_data']['MainCompanyId'].'</li>'.
                                '<li data-id="Business Phone"><strong>Business Phone: </strong> '.$prophet_data['contact_data']['BusinessPhone'].'</li>'.
                                '<li data-id="Home Phone"><strong>Home Phone: </strong> '.$prophet_data['contact_data']['HomePhone'].'</li>'.
                                '<li data-id="Cell Phone"><strong>Cell Phone: </strong> '.$prophet_data['contact_data']['CellPhone'].'</li>'.
                                '<li data-id="Fax"><strong>Fax: </strong> '.$prophet_data['contact_data']['Fax'].'</li>'.
                                '<li data-id="Website"><strong>Website: </strong> '.$prophet_data['contact_data']['Website'].'</li>'.
                                '<li data-id="Suffix"><strong>Suffix: </strong> '.$prophet_data['contact_data']['Suffix'].'</li>'.
                                '<li data-id="Department"><strong>Department: </strong> '.$prophet_data['contact_data']['Department'].'</li>'.
                                '<li data-id="CreatedDate"><strong>Created Date: </strong> '.$prophet_data['contact_data']['CreatedDate'].'</li>'.
                                '<li data-id="UpdatedDate"><strong>Updated Date: </strong> '.$prophet_data['contact_data']['UpdatedDate'].'</li>'.
                                '<li data-id="ExternalId"><strong>External Id: </strong> '.$prophet_data['contact_data']['ExternalId'].'</li>'.
                                '<li data-id="Id"><strong>Prophet GUID: </strong> '.$prophet_data['contact_data']['Id'].'</li>'.
                              '</ul>';
                            endif;

                              if(!empty($prophet_data['custom_fields__text']['value'])):
    $prophet_data_display .= '<h3>Prophet Custom Text Fields</h3>';
                              endif;

                              if(!empty($prophet_data['custom_field__dropdown'])):
    $prophet_data_display .= '<h3>Prophet Custom Dropdown Fields</h3>';
                              endif;
                            '</div>';
endif;


?>

<section>
  <?php echo $sharpspring_data_display; ?>
  <?php echo $prophet_data_display; ?>
</section>
