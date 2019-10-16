<?php

//print_r($_POST);

if(isset($_POST['Subject'])){

	mail('support@tizinc.com',$_POST['Subject'],print_r($_POST,true));
}
//
