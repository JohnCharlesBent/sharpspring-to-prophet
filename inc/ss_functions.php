<?php
/******
* Functions used to pull data from a Sharpspring account and compare it to data from Prophet
******/

class SharpSpring {
  public $ss_data;
  public $ss_data_display;
  public $ss_email;
  public $requestID;
  public $accountID;
  public $ss_key;
  public $method;

  public function __contstruct() {
    $this -> $ss_data = $ss_data;
    $this -> ss_email = $ss_email;
    $this -> requestID = $requestID;
    $this -> accountID - $accountID;
    $this -> ss_key = $ss_key;
    $this -> requestId = $reqestID;
    $this -> method = $method;
  }

  // pull data from Sharpspring Account
  public function getData($ss_email, $requestID, $accountID, $ss_key) {
    $limit = 500;
    $offset = 0;
    $method = 'getLeads';
    $accountID = urlencode($accountID);
    $ss_key = urlencode($ss_key);
    $account_data;

    $params = array(
      'where' => array(
        'emailAddress' => $ss_email,
      ),
      'limit' => $limit,
      'offset' => $offset,
    );

    $data = array(
      'method' => $method,
      'params' => $params,
      'id'  => $requestID,
    );

    $queryString = http_build_query(array('accountID' => $accountID, 'secretKey' => $ss_key ));

    $url = "http://api.sharpspring.com/pubapi/v1/?$queryString";

    $data = json_encode($data);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type:applications/json',
      'Content-Length:'.strlen($data)
    ));

    $result = curl_exec($ch);
    curl_close($ch);

    $ss_data = json_decode($result, true);

    $ss_data = $ss_data['result']['lead'][0];

    foreach($ss_data as $key => $value) {
      $account_data[$key] = $value;
    }

    return $account_data;

  }

  // parse data and set-up for display
  public function parseData($data) {
    $data_display = '<ul class="data-list">'.
                      '<li data-id="ss_id"><strong>Sharpspring ID:</strong> '.$data['id'].'</li>'.
                      '<li data-id="ss_accountID"><strong>Sharpspring Account ID:</strong> '.$data['accountID'].'</li>'.
                      '<li data-id="ss_company-name"><strong>Company Name:</strong> '.$data['companyName'].'</li>'.
                      '<li data-id="ss_title"><strong>Title:</strong> '.$data['title'].'</li>'.
                      '<li data-id="ss_first-name"><strong>First Name:</strong> '.$data['firstName'].'</li>'.
                      '<li data-id="ss_last-name"><strong>Last Name:</strong> '.$data['lastName'].'</li>'.
                      '<li data-id="ss_street"><strong>Street:</strong> '.$data['street'].'</li>'.
                      '<li data-id="ss_city"><strong>City:</strong> '.$data['city'].'</li>'.
                      '<li data-id="ss_country"><strong>Country:</strong> '.$data['country'].'</li>'.
                      '<li data-id="ss_state"><strong>State:</strong> '.$data['state'].'</li>'.
                      '<li data-id="ss_zipcode"><strong>Zipcode:</strong> '.$data['zipcode'].'</li>'.
                      '<li data-id="ss_emailAddress"><strong>Email Address:</strong> '.$data['emailAddress'].'</li>'.
                      '<li data-id="ss_website"><strong>Website:</strong> '.$data['website'].'</li>'.
                      '<li data-id="ss_phone"><strong>Phone Number:</strong> '.$data['phoneNumber'].'</li>'.
                      '<li data-id="ss_office-phone"><strong>Office Phone:</strong> '.$data['officePhoneNumber'].'</li>'.
                      '<li data-id="ss_mobile-phone"><strong>Mobile Phone:</strong> '.$data['mobilePhoneNumber'].'</li>'.
                      '<li data-id="ss_fax-number"><strong>Fax Number:</strong> '.$data['faxNumber'].'</li>'.
                      '<li data-id="ss_description"><strong>Description:</strong> '.$data['description'].'</li>'.
                      '<li data-id="ss_campaign-id"><strong>Campaign ID:</strong> '.$data['campaignID'].'</li>'.
                      '<li data-id="ss_tracking-id"><strong>Tracking ID:</strong> '.$data['trackingID'].'</li>'.
                      '<li data-id="ss_industry"><strong>Industry:</strong> '.$data['industry'].'</li>'.
                      '<li data-id="ss_active"><strong>Active:</strong> '.$data['active'].'</li>'.
                      '<li data-id="ss_last-updated"><strong>Account Last Updated:</strong> '.$data['updateTimestamp'].'</li>'.
                      '<li data-id="ss_time-created"><strong>Date/Time Created:</strong> '.$data['createTimestamp'].'</li>'.
                      '<li data-id="ss_lead-score-weighted"><strong>Lead Score (Weighted):</strong> '.$data['leadScoreWeighted'].'</li>'.
                      '<li data-id="ss_lead-score"><strong>Lead Score:</strong> '.$data['leadScore'].'</li>'.
                      '<li data-id="ss_unsubscsribed"><strong>Unsubscribed:</strong> '.$data['isUnsubscribed'].'</li>'.
                      '<li data-id="ss_lead-status"><strong>Lead Status:</strong> '.$data['leadStatus'].'</li>'.
                      '<li data-id="ss_persona"><strong>Persona:</strong> '.$data['persona'].'</li>'.
                      '<li data-id="ss_prophet-company-id"><strong>prophet_company_id_5a53c851764d2:</strong> '.$data['prophet_company_id_5a53c851764d2'].'</li>'.
                      '<li data-id="ss_prophet-contact-id"><strong>prophet_contact_id_5b58dc0543653:</strong> '.$data['prophet_contact_id_5b58dc0543653'].'</li>'.
                      '<li data-id="ss_deliverable-type"><strong>deliverable_type_5baa8049240db:</strong> '.$data['deliverable_type_5baa8049240db'].'</li>'.
                      '<li data-id="ss_deliverable-one"><strong>deliverable_1_5baa805cbf232:</strong> '.$data['deliverable_1_5baa805cbf232'].'</li>'.
                      '<li data-id="ss_deliverable-two"><strong>deliverable_2_5baa806851577:</strong> '.$data['deliverable_2_5baa806851577'].'</li>'.
                      '<li data-id="ss_source-id"><strong>source_id_5baa8ca455679:</strong> '.$data['source_id_5baa8ca455679'].'</li>'.
                      '<li data-id="ss_last-form-activity"><strong>last_form_activity_5bad18d35f9b8:</strong> '.$data['last_form_activity_5bad18d35f9b8'].'</li>'.
                      '<li data-id="ss_double_opt_in"><strong>double_opt_in_5bb528866df3e:</strong> '.$data['double_opt_in_5bb528866df3e'].'</li>'.
                      '<li data-id="ss_triversa_contact"><strong>triversa_contact_5bb528b957a68:</strong> '.$data['triversa_contact_5bb528b957a68'].'</li>'.
                      '<li data-id="ss_subscribed"><strong>subscribed_5bb528de86132:</strong> '.$data['subscribed_5bb528de86132'].'</li>'.
                      '<li data-id="ss_subscribed"><strong>subscribed_5bb528de86132:</strong> '.$data['subscribed_5bb528de86132'].'</li>'.
                      '<li data-id="ss_lead-source-one"><strong>lead_source_1_5bb5290b5f7a6:</strong> '.$data['lead_source_1_5bb5290b5f7a6'].'</li>'.
                      '<li data-id="ss_lead-source-two"><strong>lead_source_2_5bb5291d8bac1:</strong> '.$data['lead_source_2_5bb5291d8bac1'].'</li>'.
                      '<li data-id="ss_bounced"><strong>bounced_5bb52944e4df0:</strong> '.$data['bounced_5bb52944e4df0'].'</li>'.
                      '<li data-id="ss_expression-contact"><strong>expression_contact_5bb5296882b76:</strong> '.$data['expression_contact_5bb5296882b76'].'</li>'.
                      '<li data-id="ss_bounced_field_guid"><strong>bounced_field_guid_5bb7973d283ac:</strong> '.$data['bounced_field_guid_5bb7973d283ac'].'</li>'.
                      '<li data-id="ss_double_opt_in_field_guid"><strong>double_opt_in_field_guid_5bb79750dc525:</strong> '.$data['double_opt_in_field_guid_5bb79750dc525'].'</li>'.
                      '<li data-id="ss_expression_contact_field_guid"><strong>expression_contact_field_guid_5bb79766d6412:</strong> '.$data['expression_contact_field_guid_5bb79766d6412'].'</li>'.
                      '<li data-id="ss_lead_source_1_field_guid"><strong>lead_source_1_field_guid_5bb7977f90ae5:</strong> '.$data['lead_source_1_field_guid_5bb7977f90ae5'].'</li>'.
                      '<li data-id="ss_lead_source_2_field_guid"><strong>lead_source_2_field_guid_5bb7990013c94:</strong> '.$data['lead_source_2_field_guid_5bb7990013c94'].'</li>'.
                      '<li data-id="ss_subscribed_field_guid"><strong>subscribed_field_guid_5bb79922bcc53:</strong> '.$data['subscribed_field_guid_5bb79922bcc53'].'</li>'.
                      '<li data-id="ss_subscribed_field_guid"><strong>subscribed_field_guid_5bb79922bcc53:</strong> '.$data['subscribed_field_guid_5bb79922bcc53'].'</li>'.
                      '<li data-id="ss_triversa_contact_field_guid"><strong>triversa_contact_field_guid_5bb79967b4b2e:</strong> '.$data['triversa_contact_field_guid_5bb79967b4b2e'].'</li>'.
                      '<li data-id="ss_prophet_unsubscribed"><strong>prophet_unsubscribed_5bb7c727860e6:</strong> '.$data['prophet_unsubscribed_5bb7c727860e6'].'</li>'.
                      '<li data-id="ss_prophet_unsubscribed_guid"><strong>prophet_unsubscribed_guid_5bb7c74544bf6:</strong> '.$data['prophet_unsubscribed_guid_5bb7c74544bf6'].'</li>'.
                      '<li data-id="ss_lead_owner"><strong>lead_owner_5d14d13cd207f:</strong> '.$data['lead_owner_5d14d13cd207f'].'</li>'.
                      '<li data-id="ss_qualified"><strong>qualified_5d14d401114b6:</strong> '.$data['qualified_5d14d401114b6'].'</li>'.
                      '<li data-id="ss_solation_icp_ms_contact"><strong>solation_icp_ms_contact_5d14d47bca5a9:</strong> '.$data['solation_icp_ms_contact_5d14d47bca5a9'].'</li>'.
                      '</ul>';
    return $data_display;
  }

}

?>
