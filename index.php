<?php
/****
* SharpSpring / Prophet Sync and Comparison Application
* A simple web application that allows a user to compare the data from a SharpSpring contact to the data in a Prophet contact. If data needs to be synced in either direction a button will allow the user to sync the contact data
****/

include 'inc/head.php';
?>

<form id="ss_contact_lookup" method="POST" action="compare/">
  <fieldset class="form_fields">
    <label class="form_label">
      Enter A SharpSpring Contact Email Address <span class="req">*</span>
      <input class="form_field" type="email" name="ss_email" required/>
    </label>
  </fieldset>
  <fieldset class="buttons">
    <input type="submit" class="form_field" value="Look up SharpSpring Account" />
  </fieldset>
</form>

<?php
include 'inc/footer.php';
?>
