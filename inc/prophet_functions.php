<?php
/*****
* Function used to pull data from a Prophet account and compare it to data from a SharpSpring account
*****/

class Prophet {
  public $prophet_url;
  public $prophet_user;
  public $prophet_pass;
  public $token;
  public $prophet_guid;
  public $prophet_data;

  public function __construct() {
    $this -> prophet_url = $prophet_url;
    $this -> prophet_user = $prophet_user;
    $this -> prophet_pass = $prophet_pass;
    $this -> token = $token;
    $this -> prophet_guid = $prophet_guid;
    $this -> prophet_data = $prophet_data;
  }

  public function getProphetToken($prophet_url, $prophet_user, $prophet_pass) {
    $url = $prophet_url."api/Token?userName=".$prophet_user."&password=".$prophet_pass;

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    $response = curl_exec($curl);
    if(!$response) {
      die("Prophet API Connection Failed.");
    } else {
      $token = json_decode($response);
      return $token;
    }
    curl_close($curl);
  }

  public function getProphetContactData($prophet_url, $token, $prophet_guid, $prophet_data) {
    $url = prophet_url."/odata/Contacts"."(guid'".$prophet_guid."')";
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', "Authorization:Token $token"));

    $response = curl_exec($curl);
    $response = (array)json_decode($response);
    $prophet_data = $response;
    return $prophet_data;

  }

  public function parseData($prophet_data) {
    $data = $prophet_data;

    $data_display .= '<div class="prophet-contact-data__wrap>"'.
                        '<h2>Prophet Contact Data</h2>'.
                            '<ul class="data-list">'.
                              '<li data-id="Full Name"><strong>Full Name: </strong> '.$data['FullName'].'</li>'.
                              '<li data-id="First Name"><strong>First Name: </strong> '.$data['FirstName'].'</li>'.
                              '<li data-id="Last Name"><strong>Last Name: </strong> '.$data['LastName'].'</li>'.
                              '<li data-id="Middle Name"><strong>Middle Name: </strong> '.$data['MiddleName'].'</li>'.
                              '<li data-id="Job Title"><strong>Job Title: </strong> '.$data['JobTitle'].'</li>'.
                              '<li data-id="Title"><strong>Title: </strong> '.$data['Title'].'</li>'.
                              '<li data-id="Email"><strong>Email: </strong> '.$data['Email'].'</li>'.
                              '<li data-id="Email2"><strong>Email 2: </strong> '.$data['Email2'].'</li>'.
                              '<li data-id="Email3"><strong>Email 3: </strong> '.$data['Email3'].'</li>'.
                              '<li data-id="Main Company ID"><strong>Main Company ID: </strong>'.$data['MainCompanyId'].'</li>'.
                              '<li data-id="Business Phone"><strong>Business Phone: </strong>'.$data['BusinessPhone'].'</li>'.
                              '<li data-id="Home Phone"><strong>Home Phone: </strong> '.$data['HomePhone'].'</li>'.
                              '<li data-id="Cell Phone"><strong>Cell Phone: </strong> '.$data['CellPhone'].'</li>'.
                              '<li data-id="Fax"><strong>Fax: </strong> '.$data['Fax'].'</li>'.
                              '<li data-id="Website"><strong>Website: </strong> '.$data['Website'].'</li>'.
                              '<li data-id="Suffix"><strong>Suffix: </strong> '.$data['Suffix'].'</li>'.
                              '<li data-id="Department"><strong>Department: </strong> '.$data['Department'].'</li>'.
                              '<li data-id="CreatedDate"><strong>Created Date: </strong> '.$data['CreatedDate'].'</li>'.
                              '<li data-id="UpdatedDate"><strong>Updated Date: </strong> '.$data['UpdatedDate'].'</li>'.
                              '<li data-id="ExternalId"><strong>External Id: </strong> '.$data['ExternalId'].'</li>'.
                              '<li data-id="Id"><strong>Prophet GUID: </strong> '.$data['Id'].'</li>'.
                            '</ul>'.
                              '</div>';

    return $data_display;
  }

  public function getProphetCustomFields($token, $prophet_guid, $data) {

  }



}
?>
