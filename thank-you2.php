<?php
require_once 'metaheader.php';

$to = $_SESSION['email'];
$subject = "Copy of Selection | APMC Agenda";
 
$message =  "<p style='font-size: 14pt;'>Hi, ".$_SESSION['name'].". Thank you for casting your vote!</p>".
			"<p style='font-size: 14pt;'>Below is a copy of your selections for the agenda.</p><br>";


$message .= "
<table class='table table-hover'>
    <thead>
      <tr>
        <th style='text-align: center;font-size: 14pt;font-weight: bold;'>Agenda</th>
        <th style='text-align: center;font-size: 14pt;font-weight: bold;'>Votes</th>
      </tr>
    </thead>
    <tbody>";

$member_id = $_SESSION['user_no'];
$ctr = 0;
$total = 0;

$all = MyDb::select_all("tbl_agenda_vote.agenda_id, agenda_item, vote", "tbl_agenda_vote INNER JOIN tbl_agenda ON tbl_agenda_vote.agenda_id = tbl_agenda.agenda_id", "tbl_agenda_vote.member_id = $member_id ORDER BY agenda_item");
while ($data = $all->fetchAll(PDO::FETCH_ASSOC)) {
  foreach ($data as $value) {
    $ctr++;
    $vote = $value['vote'];
    $vote_desc = "";
    if ($vote == "N")
      $vote_desc = 'No';
    elseif ($vote == "A")
      $vote_desc = 'Abstain';
    else
      $vote_desc = 'Yes';
    $message .= " <tr>
                    <td style='text-align: left;font-size: 14pt;padding: 5px;'>".$value['agenda_item']."</td>
                    <td style='text-align: center;font-size: 14pt;padding: 5px;'>".$vote_desc."</td>
                  </tr>";
  }
}
$message .= "   </tbody>
              </table>";
 

$header = "From:Asia Pacific Medical Center - Aklan Inc. <emailauth@asiapacificmedicalcenter-aklan.com> \r\n";
$header .= "Reply-To: emailauth@asiapacificmedicalcenter-aklan.com\r\n";
$header .= "Return-Path: emailauth@asiapacificmedicalcenter-aklan.com\r\n";
//$header .= 'Cc: bsdelatorre1986@yahoo.com, roelesca@yahoo.com, peps_md07@yahoo.com, sazonpauleen@gmail.com' . "\r\n";
$header .= 'Bcc: f1itss.aklan@gmail.com' . "\r\n"; //mconananmoratomd@yahoo.com, 

$header .= "MIME-Version: 1.0\r\n";
$header .= "Content-type: text/html\r\n";
 
 
try {
	if( mail ($to,$subject,$message,$header) ) {
     	$msg = "A copy of your choices has been sent to your email";
     }else {
        $msg = "We are unable to send you an email. Please confirm that you are using a valid email.";
     }

} catch (Exception $e) {
	$msg = "We are unable to send you an email. Please confirm that you are using a valid email and that your internet connection is stable.";
}

require_once 'header.php';

?>

<div class="fullcontainer" style="text-align: center; padding-top: 180px;">
	<h3>Thank you, <?=$_SESSION['name']?> for casting your vote!</h3>
	<h5><?=$msg?>.</h5>
	<br>
	<a href="index2.php" class="btn btn-success btn-lg">Back to Selection Page</a>
</div>

<?php include_once('footer.php'); ?>