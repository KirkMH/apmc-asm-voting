<?php
require_once 'metaheader.php';

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

	$code = F1ITSS::randomNum(4);

	$to = $_SESSION['email'];
    $subject = "Verification Code for APMC-Aklan Inc. Online Voting System";
    
    $message = "<p style='font-size: 14pt;'>Thank you for taking you time to vote for our Annual Meeting of Stockholders. To complete your login process, kindly enter the verification code below to the website.</p>";
    $message .= "<p style='font-size: 32pt; text-align: center;'>$code</p>"; // add spacing to code here
    
    $header = "From:APMC-Aklan Inc. <emailauth@asiapacificmedicalcenter-aklan.com> \r\n";
    $header .= "Reply-To: emailauth@asiapacificmedicalcenter-aklan.com\r\n";
    $header .= "Return-Path: emailauth@asiapacificmedicalcenter-aklan.com\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-type: text/html\r\n";

	try {
		if( mail ($to,$subject,$message,$header) ) {
			// save security code here
			$data = array('security_code' => implode(explode(" ", $code)));
			MyDb::update(	'tbl_shareholder', 
						'id',
						$_SESSION['user_no'],
						$data);

			if (Input::get('resend') != "1") {
				$msg = "We have resent your verification code. Please check your email and enter the 4 digit code below.";
			}
		}else {
			$msg = "We are unable to send you an email. Please confirm that you are using a valid email.";
		}
	} catch (Exception $e) {
		$msg = "We are unable to send you an email. Please confirm that you are using a valid email and that your internet connection is stable.";
	}

}

require_once 'header.php';
?>


	<link rel="stylesheet" href="css/sc.css">

 <div class="fullcontainer" id="wrapper">
  <div id="dialog">
	<?php
	if (isset($msg))
		echo $msg;
	else { ?>
		<h4>We <span style="color: red;">sent a verification code to your email</span> <?=F1ITSS::crop_email($_SESSION['email'])?> as part of your security.<br>
		It may take about <span style="color: red;">5 minutes</span> for you to receive the verification code.<br><br>
		Kindly <span style="color: red;">check your spam folder if it is not in your inbox</span>. If you do not receive the email <br>
		after 5 minutes, you may try again by clicking Resend Email.</h4><br>
		<h1>Please enter the 4 digit code below.</h1>
	<?php } ?>
	<div id="form">
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
	</div>
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