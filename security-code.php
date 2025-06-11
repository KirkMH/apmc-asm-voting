<?php
require_once 'metaheader.php';
require_once 'core/PHPMailer/PHPMailer.php';
require_once 'core/PHPMailer/SMTP.php';
require_once 'core/PHPMailer/Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$err = false;

if (Input::get('submit')) {
	$num1 = Input::get('num1');
	$num2 = Input::get('num2');
	$num3 = Input::get('num3');
	$num4 = Input::get('num4');

	$num = $num1 . $num2 . $num3 . $num4;

	// check if they match
	$s_code = MyDb::select_one("security_code", "tbl_shareholder", "security_code = '$num' AND id = " . $_SESSION['user_no']);
	if (!$s_code) {
		$msg = "Sorry, we are unable to confirm your identity. Please check your email and enter the correct verification code.";
		echo $msg;
	}
  else {
		Redirect::to('index2.php');
	}
}
else if (Input::get('resend')) {
	// var_dump($_SESSION);
	$code = F1ITSS::randomNum(4);

	$to = $_SESSION['email'];
    $subject = "Verification Code for APMC-Aklan Inc. Online Voting System";
    
    $message = "<p style='font-size: 14pt;'>Thank you for taking the time to vote in our Annual Stockholders Meeting. To access the voting module, please enter the verification code below on the website.</p>";
    $message .= "<p style='font-size: 32pt; text-align: center;'>$code</p>"; // add spacing to code here
    
    $header = "From:APMC-Aklan Inc. <noreply@apmcaklan-asm.com> \r\n";
    $header .= "Reply-To: noreply@apmcaklan-asm.com\r\n";
    $header .= "Return-Path: noreply@apmcaklan-asm.com\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-type: text/html\r\n";

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
		$mail->AltBody = "Thank you for taking the time to vote in our Annual Stockholders Meeting. To access the voting module, please enter the verification code below on the website.\n\n" . $code;

		// Send email
		$mail->send();
		
		// save security code here
		$data = array('security_code' => implode(explode(" ", $code)));
		MyDb::update(	'tbl_shareholder', 
					'id',
					$_SESSION['user_no'],
					$data);

		if (Input::get('resend') != "1") {
			$msg = "We have resent your verification code. Please check your email and enter the 4 digit code below.";
		}
	} catch (\PHPMailer\PHPMailer\Exception $e) {
		$msg = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		$err = true;
	}
}

require_once 'header.php';
?>


	<link rel="stylesheet" href="css/sc.css">

 <div class="fullcontainer" id="wrapper">
  <div id="dialog" style="width: 50%; margin: auto;">
	<?php
	if (isset($msg) && $err)
		echo $msg;
	else { ?>
		<h4 style="text-align: left;">
			We sent a <span style="color: red;">verification code</span> to your email <?=F1ITSS::crop_email($_SESSION['email'])?> as part of your security. It may take about <span style="color: red;">5 minutes</span> for you to receive the verification code.<br><br>
			Kindly <span style="color: red;">check your spam folder if it is not in your inbox</span>. If you do not receive the email after 5 minutes, you may try again by clicking Resend Email.</h4><br>
		<h2 style="text-align: center;">Please enter the 4 digit code below.</h2>
		<form method="post">
			<div style="margin-top: 30px; margin-bottom: 20px; color: #00b050"><label>Security Code:</label> <br/></div>
			<input class="input-num" type="text" min="0" max="9" name="num1" maxLength="1" size="1" pattern="[0-9]{1}" />
			<input class="input-num" type="text" min="0" max="9" name="num2" maxLength="1" size="1" pattern="[0-9]{1}" />
			<input class="input-num" type="text" min="0" max="9" name="num3" maxLength="1" size="1" pattern="[0-9]{1}" />
			<input class="input-num" type="text" min="0" max="9" name="num4" maxLength="1" size="1" pattern="[0-9]{1}" />
			<br><br><br>
			<button class="btn btn-primary btn-lg" type="submit" name="submit" value="submit">Proceed</button>
			<button class="btn btn-default btn-lg" type="submit" name="resend" value="resend">Resend Email</button>
			<br><br>
			<h5 style="color: #848484;">Not you? Click <a href="index.php?logout=true">here</a> to logout.</h5>
		</form>
	<?php } ?>
	</div>
 </div>

<script type="text/javascript">
$(function() {
  'use strict';

  var body = $('body');

  function goToNextInput(e) {
    var key = e.which,
      t = $(e.target),
      sib = t.next('input');

    if (key != 9 && (key < 48 || key > 57)) {
      e.preventDefault();
      return false;
    }

    if (key === 9) {
      return true;
    }

    if (!sib || !sib.length) {
      sib = body.find('input').eq(0);
    }
    sib.select().focus();
  }

  function onKeyDown(e) {
    var key = e.which;

    if (key === 9 || (key >= 48 && key <= 57)) {
      return true;
    }

    e.preventDefault();
    return false;
  }
  
  function onFocus(e) {
    $(e.target).select();
  }

  body.on('keyup', 'input', goToNextInput);
  body.on('keydown', 'input', onKeyDown);
  body.on('click', 'input', onFocus);

})
</script>

<?php include_once('footer.php'); ?>