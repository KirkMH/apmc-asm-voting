<?php
    require_once "core/init.php";
    if(Input::get('image_id')) {
    	$id = Input::get('image_id');
    	$row = MyDb::select("photo, imageType", "tbl_candidate", "candidate_id = $id");
		header("Content-type: " . $row["imageType"]);
        echo $row["photo"];
	}
?>