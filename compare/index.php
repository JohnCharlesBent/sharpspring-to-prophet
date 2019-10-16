<?php
require('../inc/config.php');
require('../inc/ss_functions.php');
require('../inc/prophet_functions.php');

if($_SERVER['REQUEST_METHOD'] === 'POST'):

  $ss_email = $_POST['ss_email'];
  $ss = new SharpSpring();
  $data = $ss->getData( $ss_email, sharpspring_requestid, sharpspring_account_number, sharpspring_key );

  $displayData = $ss->parseData($data);

  $prophet_guid= $data['prophet_contact_id_5b58dc0543653'];

  $prophet_data;
  $pr = new Prophet();
  $pr_token = $pr->getProphetToken(prophet_url, prophet_user, prophet_pass);
  $pr_contact_data = $pr->getProphetContactData(prophet_url, $pr_token, $prophet_guid, $prophet_data);
  $prophetDisplayData = $pr->parseData($pr_contact_data);

endif;

include('../inc/head.php');
?>

<div id="ss_data" class="account_data">
  <h2>SharpSpring Account Data</h2>
  <?php echo $displayData; ?>
</div>

<div id="prophet_data" class="account_data">
  <h2>Prophet Account Data</h2>
  <?php echo $prophetDisplayData; ?>
</div>

<?php
include('../inc/footer.php');
?>
