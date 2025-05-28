<?php

require_once 'metaheader.php';

require_once 'header.php';



$status = Input::get("status");

if ($status == "success") {

	$msg = "A copy of your choices has been sent to your email.";

} else if ($status == "invalid") {

	$msg = "We are unable to send you an email. Please confirm that you are using a valid email.";

} else {

	$msg = "We are unable to send you an email. Please confirm that you are using a valid email and that your internet connection is stable.";

}

?>



<div class="fullcontainer" style="text-align: center; padding-top: 180px;">

	<h3>Thank you, <?=$_SESSION['name']?> for casting your vote!</h3>

	<h5><?=$msg?></h5>

	<br>

	<a href="index2.php" class="btn btn-success btn-lg">Back to Selection Page</a>

</div>



<?php include_once('footer.php'); ?>