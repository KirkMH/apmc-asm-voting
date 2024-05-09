<?php
require_once 'core/init.php';

// $action_taken = "none";

if (isset($_POST['candidate_id'])) {
    $candidate_id = $_POST['candidate_id'];
    $points = (int) $_POST['points'];

    // delete existing vote of this user to this candidate
    MyDb::delete("tbl_temp_vote", "member_id", $_SESSION['user_no'] . ' AND candidate_id = ' . $candidate_id);
    // insert new record
    if ($points > 0) {
        $data = array('member_id' => $_SESSION['user_no'],
                        'candidate_id' => $candidate_id,
                        'points' => $points);
        MyDb::insert('tbl_temp_vote', $data);
    }

    // // check if already set previously
    // $temp_id = MyDb::select_one( 'temp_id', 
    // 						   'tbl_temp_vote',
    // 						   'member_id = ' . $_SESSION['user_no'] . ' AND '.
    // 								'candidate_id = ' . $candidate_id);
    // if ($temp_id) {
    // 	// already exists; delete
	//     $data = array('candidate_id' => $candidate_id,
	// 				  'points' => $points);
	//     MyDb::update(	'tbl_temp_vote', 
	//  				'temp_id',
	//  				$temp_id,
	//  				$data);
    //     $action_taken = "updated";
    // }
    // else if (!$temp_id && $points > 0) {
    // 	// not existing; insert
	//     $data = array('member_id' => $_SESSION['user_no'],
	//     			  'candidate_id' => $candidate_id,
	// 				  'points' => $points);
	//     MyDb::insert('tbl_temp_vote', $data);
    //     $action_taken = "inserted";
    // }
}
echo "ok"; // $action_taken;
?>