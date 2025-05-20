<?php
require_once 'core/init.php';

if (Input::get('logout')){
    session_destroy();
}


if (Input::get('submit')) {

   $username = addslashes(Input::get('username'));
   $psswd = addslashes(Input::get('password'));

   $sqlResult = MyDb::select('*', "tbl_shareholder", "username = '$username' AND tin = '$psswd'");
    if($sqlResult){
       $_SESSION['name'] = $sqlResult['full_name'];
       $_SESSION['email'] = $sqlResult['email'];
       $_SESSION['user_no'] = $sqlResult['id'];
       $_SESSION['points'] = ((int)$sqlResult['shares']);
       $_SESSION['shares'] = $sqlResult['shares'];

        // $code = F1ITSS::randomNum(4);

           
        // $to = $_SESSION['email'];
        // $subject = "Verification Code for APMC-Aklan Inc. Online Voting System";
         
        // $message = "<p style='font-size: 14pt;'>Thank you for taking you time to vote for our Annual Meeting of Stockholders. To complete your login process, kindly enter the verification code below to the website.</p>";
        // $message .= "<p style='font-size: 32pt; text-align: center;'>$code</p>"; // add spacing to code here
         
        // $header = "From:APMC-Aklan Inc. <emailauth@acemc-aklan.com> \r\n";
        // $header .= "Reply-To: emailauth@acemc-aklan.com\r\n";
        // $header .= "Return-Path: emailauth@acemc-aklan.com\r\n";
        // $header .= "MIME-Version: 1.0\r\n";
        // $header .= "Content-type: text/html\r\n";
         
         
        // $res = mail ($to,$subject,$message,$header);
        // if( $res == true ) {
        //     // save security code here
        //     $data = array('security_code' => implode(explode(" ", $code)));
        //     MyDb::update( 'tbl_shareholder', 
        //                 'ID',
        //                 $_SESSION['user_no'],
        //                 $data);

            Redirect::to("index1.php");
         // }else {
         //    $msg = "We are unable to send you an email. Please confirm that you are using a valid email.";
         // }
         
     
    } else {
        $msg = "Invalid username and/or password.\nPlease try again.";  
  }
         
}

// check if election is still open
$now = new DateTime(null, new DateTimeZone('Asia/Manila'));

$value = MyDb::select("*", "tbl_election", "elect_id = 1");
$from_date = new DateTime($value['duration_from']);
$to_date = new DateTime($value['duration_to']);

$status = "CLOSED";

if (($now >= $from_date) && ($now <= $to_date)){
$status = "OPEN";
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="_imgs/APMC_logo.jpg" type="image/jpg" sizes="16x16">
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<title>APMC-Aklan Inc. ASM <?=date('Y')?> Portal | Login</title>
	<link rel="stylesheet" href="css/index.css?v=<?=time();?>">
</head>



<body>
	<?php
	if (isset($msg)) {
	?>
	    <div class="alert alert-primary" role="alert">
	      <?=$msg?>
	    </div>
	<?php } ?>	
    <div id="login">
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12 card">

                        <form id="login-form" class="form login100-form validate-form" action="index.php" method="post">
                            <div class="text-center">                            
								<img class="" src="_imgs/apmc_logo.png" alt="ACE-MC-logo" height="150px">
                                <h2><?=date('Y')?> Annual Stockholders Meeting Portal</h2>
							</div>

                        <?php if (($now >= $from_date) && ($now <= $to_date)){ ?>
                            <h6 class="text-left text-info" style="font-size: 12pt; font-weight: normal;"><br>Dear valued shareholder,
                                <br><br>
                                Welcome to Asia Pacific Medical Center (APMC) - Aklan Inc.'s Online Voting System. 
                                By logging in, you hereby authorize Asia Pacific Medical Center (APMC) - Aklan, Inc. to use, collect and process information for legitimate purposes specifically for the <?=date('Y')?> Annual Stockholders Meeting including the Election of the <?=date('Y')?> Board of Directors and allow authorized personnel to process the information.
                                <br><br>
                                If you cannot login using your credentials, or you do not have your credentials yet, please contact APMC-Aklan's corporate secretary's office.
                            </h6>

                            <div class="form-group wrap-input100 validate-input mt-4" data-validate = "Valid username is required. Please contact your administrator if you have none.">
                                <label for="username" class="text-info">Username:</label><br>
                                <input type="text" name="username" placeholder="Username" class="input100 form-control">
                            </div>
                            <div class="form-group wrap-input100 validate-input" data-validate = "Password is required">
                                <label for="password" class="text-info">Password:</label><br>
                                <input type="password" name="password" placeholder="Password" id="password" class="input100 form-control">
                            </div>

                            <div class="form-group">

                                <input type="submit" name="submit" class="login100-form-btn btn btn-info btn-md" value="Login">
                            </div>
                        <?php } elseif ($now < $from_date) { ?>
                            <br><h3>Notice to Our Valued Shareholders:</h3>
                            <p>The voting system is not yet available. Voting will start on <?=$from_date->format('d M Y \a\t h:i A');?> We will notify you once it is ready. Thank you for your patience and understanding.</p>
                            <p><br><a href="meeting-info.php" class="btn btn-lg btn-block btn-info">Open Meeting Information</a></p>

                        <?php } else { ?>
                            <p style="text-align: center"><br><h3>Voting has been closed.<br><br>The result of the election will be announced during Annual Stockholder's Meeting.<br><br>Thank you.</h3></p>
                            <p align="center"><br><a href="meeting-info.php" class="btn btn-lg btn-block btn-info">Open Meeting Information</a></p>
                        <?php } ?>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

















<script type="text/javascript">


(function ($) {
    "use strict";

    
    /*==================================================================
    [ Validate ]*/
    var input = $('.validate-input .input100');

    $('.validate-form').on('submit',function(){
        var check = true;

        for(var i=0; i<input.length; i++) {
            if(validate(input[i]) == false){
                showValidate(input[i]);
                check=false;
            }
        }

        return check;
    });


    $('.validate-form .input100').each(function(){
        $(this).focus(function(){
           hideValidate(this);
        });
    });

    function validate (input) {
        if($(input).attr('type') == 'email' || $(input).attr('name') == 'email') {
            if($(input).val().trim().match(/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{1,5}|[0-9]{1,3})(\]?)$/) == null) {
                return false;
            }
        }
        else {
            if($(input).val().trim() == ''){
                return false;
            }
        }
    }

    function showValidate(input) {
        var thisAlert = $(input).parent();

        $(thisAlert).addClass('alert-validate');
    }

    function hideValidate(input) {
        var thisAlert = $(input).parent();

        $(thisAlert).removeClass('alert-validate');
    }
    
    

})(jQuery);
</script>
</html>