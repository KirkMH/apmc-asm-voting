<?php
require_once 'metaheader.php';
require_once 'core/PHPMailer/PHPMailer.php';
require_once 'core/PHPMailer/SMTP.php';
require_once 'core/PHPMailer/Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$to = $_SESSION['email'];
$subject = "Copy of Selection | APMC Agenda";
 
$message =  "<p style='font-size: 14pt;'>Hi, ".$_SESSION['name'].". Thank you for casting your vote!</p>".
			"<p style='font-size: 14pt;'>Below is a copy of your selections for the agenda.</p><br>";
$alt_message = "Hi, ".$_SESSION['name'].". Thank you for casting your vote!\n\nBelow is a copy of your selections for the agenda.\n\n";

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

$all = MyDb::select_all("tbl_agenda_vote.agenda_id, agenda_item, tbl_agenda_vote.vote", "tbl_agenda_vote INNER JOIN tbl_agenda ON tbl_agenda_vote.agenda_id = tbl_agenda.agenda_id", "tbl_agenda_vote.member_id = $member_id ORDER BY tbl_agenda.agenda_id");
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
    $alt_message .= "".$value['agenda_item']." (".$vote_desc.")\n";
  }
}
$message .= "   </tbody>
              </table>";
 

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

  // Email content
  $mail->isHTML(true);
  $mail->Subject = $subject;
  $mail->Body    = $message;
  $mail->AltBody = $alt_message;

  // Send email
  $mail->send();
  $msg = "We have sent you an email with a copy of your selections.";
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