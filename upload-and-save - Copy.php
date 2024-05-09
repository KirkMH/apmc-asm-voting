<?php
require_once 'metaheader.php';

// validate if taken the correct steps
$s_code = (int) MyDb::select_one("security_code", "96h3PJ3_users", "ID = " . $_SESSION['user_no']);
if ($s_code != -1) {
	Redirect::to("security-code.php");
}
require_once 'header.php';

?>


<div class="fullcontainer" style="text-align: center; padding-top: 180px;">
	<h3>Uploading. Please wait...</h3>
	<br>
	<img src="_imgs/upload.gif">
</div>

<?php 
include_once('footer.php'); 


if (Input::get('mySubmit')) {	
	// uploading
	if (count($_FILES) > 0) {
		// upload selected photo
	    if (is_uploaded_file($_FILES['filename']['tmp_name'])) {
	        $imgData = addslashes(file_get_contents($_FILES['filename']['tmp_name']));
	        $imageProperties = getimageSize($_FILES['filename']['tmp_name']);

		  	$arr = array(
		  		'prc_card' => $imgData,
		  		'img_type' => $imageProperties['mime']
		  	);
		  	// upload to 96h3PJ3_users
		  	MyDb::update('96h3PJ3_users', "ID", $_SESSION['user_no'], $arr);
	    }
	}
	// transfer from temp to tbl_vote
	$votes = array();
	$tvote = MyDb::select_all("temp_id, candidate_id, points", 
		"tbl_temp_vote", 'member_id = '.$_SESSION['user_no']);
	while ($data = $tvote->fetchAll(PDO::FETCH_ASSOC)) {
	  foreach ($data as $value) {
	  	$temp_id = $value['temp_id'];
	  	$arr = array(
	  		'member_id' => $_SESSION['user_no'],
	  		'candidate_id' => $value['candidate_id'],
	  		'points' => $value['points']
	  	);
	  	// add to actual vote
	  	MyDb::insert('tbl_vote', $arr);
	  	// delete after inserting
	  	MyDb::delete('tbl_temp_vote', 'temp_id', $temp_id);
	  }
	}
	// update that this member has already casted his vote
  	$arr = array('voted_on' => date("Y-m-d H:i:s"));
  	MyDb::update("96h3PJ3_users", "ID", $_SESSION['user_no'], $arr);

	Redirect::to('thank-you.php');
}
else {
	Redirect::to('vote-bod.php');
}	
?>