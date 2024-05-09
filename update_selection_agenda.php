<?php
require_once 'core/init.php';

$action_taken = "none";

if (isset($_POST['agenda_id'])) {
    $agenda_id = $_POST['agenda_id'];
    $member_id = $_POST['member_id'];
    $vote = $_POST['vote'];

    // check if already set previously
    $temp_id = MyDb::select_one( 'temp_id', 
    						   'tbl_agenda_temp_vote',
    						   'member_id = ' . $_SESSION['user_no'] . ' AND '.
    								'agenda_id = ' . $agenda_id);
    if ($temp_id) {
    	// already exists; update
	    $data = array('agenda_id' => $agenda_id,
					  'vote' => $vote);
	    MyDb::update(	'tbl_agenda_temp_vote', 
	 				'temp_id',
	 				$temp_id,
	 				$data);
        $action_taken = "updated";
    }
    else {
    	// not existing; insert
	    $data = array('member_id' => $member_id,
	    			  'agenda_id' => $agenda_id,
					  'vote' => $vote);
	    MyDb::insert('tbl_agenda_temp_vote', $data);
        $action_taken = "inserted";
    }
}
echo $action_taken;
?>