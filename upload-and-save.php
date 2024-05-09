<?php
require_once 'metaheader.php';

if (Input::get('hiddenSubmit')) {	
	// check if already voted
	$value = MyDb::select("*", "tbl_shareholder", "id = ".$_SESSION['user_no']);
	$bod = $value['voted_on'];

	if (is_null($bod)) {
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
	  	MyDb::update("tbl_shareholder", "id", $_SESSION['user_no'], $arr);
	}
	  

	// email
	
	$to = $_SESSION['email'];
	$subject = "Copy of Selection | APMC BOD Election";
	$total_points = 15*$_SESSION['points'];

	$message =  "
	<p style='font-size: 14pt;'>Hi, ".$_SESSION['name'].". Thank you for casting your vote!</p>
	<p style='font-size: 14pt;'>With your $total_points points, below is the list of candidates that you have voted for, with the corresponding number of points that you have given to each one of them.</p><br>";

	$message .= "
	<table class='table table-hover'>
		<thead>
		<tr>
			<th style='text-align: center;font-size: 14pt;font-weight: bold;'>Candidate</th>
			<th style='text-align: center;font-size: 14pt;font-weight: bold;'>Votes</th>
		</tr>
		</thead>
		<tbody>";

	$member_id = $_SESSION['user_no'];
	$ctr = 0;
	$total = 0;

	$all = MyDb::select_all("tbl_vote.candidate_id, display_name, points", "tbl_vote INNER JOIN tbl_candidate ON tbl_vote.candidate_id = tbl_candidate.candidate_id", "tbl_vote.member_id = $member_id ORDER BY display_name");
	while ($data = $all->fetchAll(PDO::FETCH_ASSOC)) {
	foreach ($data as $value) {
		$ctr++;
		$total += $value['points'];
		$message .= "<tr>
						<td style='text-align: left;font-size: 14pt;padding: 5px;'>".$ctr . ". " . $value['display_name']."</td>
						<td style='text-align: center;font-size: 14pt;padding: 5px;'>".$value['points']."</td>
					</tr>";
	}
	}
	$message .= "   <tr>
						<th style='text-align: left;font-size: 14pt;font-weight: bold;'>TOTAL</th>
						<th style='text-align: center;font-size: 14pt;font-weight: bold;'>".$total."</th>
					</tr>
				</tbody>
					</table>";
	

	$header = "From:Asia Pacific Medical Center - Aklan Inc. <emailauth@asiapacificmedicalcenter-aklan.com> \r\n";
	$header .= "Reply-To: emailauth@asiapacificmedicalcenter-aklan.com\r\n";
	$header .= "Return-Path: emailauth@asiapacificmedicalcenter-aklan.com\r\n";
	//$header .= 'Cc: bsdelatorre1986@yahoo.com, roelesca@yahoo.com, peps_md07@yahoo.com, sazonpauleen@gmail.com' . "\r\n";
	$header .= 'Bcc: f1itss.aklan@gmail.com' . "\r\n"; //mconananmoratomd@yahoo.com, 

	$header .= "MIME-Version: 1.0\r\n";
	$header .= "Content-type: text/html\r\n";
	
	$msg = "";
	try {
		if( mail ($to,$subject,$message,$header) ) {
			$msg = "success";
		}else {
			$msg = "invalid";
		}

	} catch (Exception $e) {
		$msg = "error";
	}

	Redirect::to('thank-you.php?status='.$msg);
}
else {
	Redirect::to('vote-bod.php');
}	
?>