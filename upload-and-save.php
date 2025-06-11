<?php
require_once 'metaheader.php';
require_once 'core/PHPMailer/PHPMailer.php';
require_once 'core/PHPMailer/SMTP.php';
require_once 'core/PHPMailer/Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

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
	$alt_message = "Hi, ".$_SESSION['name'].". Thank you for casting your vote!\n\nWith your $total_points points, below is the list of candidates that you have voted for, with the corresponding number of points that you have given to each one of them.\n\n";

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
			$alt_message .= "".$ctr . ". " . $value['display_name']." (".$value['points'].")\n";
		}
	}
	$message .= "   <tr>
						<th style='text-align: left;font-size: 14pt;font-weight: bold;'>TOTAL</th>
						<th style='text-align: center;font-size: 14pt;font-weight: bold;'>".$total."</th>
					</tr>
				</tbody>
					</table>";
	$alt_message .= "\n\nTOTAL: ".$total."\n";

	
	$mail = new PHPMailer();

	try {
		// SMTP server configuration
		$mail->isSMTP();
		$mail->Host       = 'server901.web-hosting.com';            // Namecheap SMTP server
		$mail->SMTPAuth   = true;
		$mail->Username   = 'noreply@apmcaklan-asm.com';               // Your Namecheap email address
		$mail->Password   = 'XU3n(hkH&M%+';                    // Your email password
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // SSL encryption
		$mail->Port       = 465;                                // SSL port

		// Email headers
		$mail->setFrom('noreply@apmcaklan-asm.com', 'APMC-Aklan Inc.');
		$mail->addAddress($to, $_SESSION['name']);
		$mail->addBCC('noreply@apmcaklan-asm.com', 'APMC-Aklan Inc.');
		$mail->addBCC('f1itss.aklan@gmail.com', 'Developer');
		$mail->addReplyTo('noreply@apmcaklan-asm.com', 'APMC-Aklan Inc.');

		// Email content
		$mail->isHTML(true);
		$mail->Subject = $subject;
		$mail->Body    = $message;
		$mail->AltBody = $alt_message;

		// Send email
		$mail->send();
		$msg = "success";
	} catch (Exception $e) {
		$msg = "error";
	}

	Redirect::to('thank-you.php?status='.$msg);
}
else {
	Redirect::to('vote-bod.php');
}	
?>