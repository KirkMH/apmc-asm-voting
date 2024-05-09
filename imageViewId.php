<?php
    require_once "core/init.php";
    if(Input::get('image_id')) {
    	$id = Input::get('image_id');
    	$row = MyDb::select("prc_card, img_type", "96h3PJ3_users", "ID = $id");
		header("Content-type: " . $row["img_type"]);
        echo $row["prc_card"];
	}
?>