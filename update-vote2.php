<?php
require_once 'core/init.php';

$action_taken = "none";

if (isset($_POST['candidate_id'])) {
    $candidate_id = $_POST['candidate_id'];
    $action = $_POST['action'];

    // check if already set previously
    $temp_id = MyDb::select_one( 'temp_id', 
    						   'tbl_temp_vote',
    						   'member_id = ' . $_SESSION['user_no'] . ' AND '.
    								'candidate_id = ' . $candidate_id);
    if ($temp_id && $action == "rem") {
        MyDb::delete("tbl_temp_vote", "temp_id", $temp_id);
        $action_taken = "deleted";
    }
    else if ($temp_id && $action == "add") {
    	// already exists; update
	    $data = array('candidate_id' => $candidate_id,
					  'points' => $_SESSION['points']);
	    MyDb::update(	'tbl_temp_vote', 
	 				'temp_id',
	 				$temp_id,
	 				$data);
        $action_taken = "updated";
    }
    else if (!$temp_id && $action == "add") {
    	// not existing; insert
	    $data = array('member_id' => $_SESSION['user_no'],
	    			  'candidate_id' => $candidate_id,
					  'points' => $_SESSION['points']);
	    MyDb::insert('tbl_temp_vote', $data);
        $action_taken = "inserted";
    }
}
echo $action_taken;
?>