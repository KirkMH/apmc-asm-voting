<?php
require_once 'core/init.php';

if (Input::get('logout')){
    unset($_SESSION['user_no']);
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
     
    } else {
        $msg = "Invalid username and/or password.\nPlease try again.";  
  }
         
}

// check if election is still open
$now = new DateTime('now', new DateTimeZone('Asia/Manila'));

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
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
	<title>APMC-Aklan Inc. | Annual Stockholders' Meeting</title>
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

    <!-- for confirmation before opening the election portal -->
    <div id="myModal" class="modal fade">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">        
              <h4 class="modal-title" id="modal_title">ASM Election Portal</h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">        
                <p>By clicking Proceed, you hereby authorize Asia Pacific Medical Center (APMC) - Aklan, Inc. to use, collect and process information for legitimate purposes specifically for the <?php echo date("Y"); ?> Annual Stockholders Meeting including the Election of the <?php echo date("Y"); ?> Board of Directors and allow authorized personnel to process the information.</p>
            </div>
            <div class="modal-footer">
              <input type="button" name="proceedBtn" id="proceedBtn" class="btn btn-success pull-right" value="Proceed">
              <input type="button" name="closeBtn" id="closeBtn" class="btn btn-danger pull-right" value="Close" data-dismiss="modal">
            </div>
        </div>
      </div>
    </div> 

    <?php if (!isset($_SESSION['user_no'])) { ?>
    <div id="login">
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12 card">

                        <form id="login-form" class="form login100-form validate-form" action="meeting-info.php" method="post">
                            <div class="text-center">                            
								<img class="" src="_imgs/apmc_logo.png" alt="ACE-MC-logo" height="150px">
							</div>

                            <h6 class="text-left text-info" style="font-size: 12pt; font-weight: normal;"><br>Dear valued shareholder,
                                <br><br>
                                Welcome to Asia Pacific Medical Center (APMC) - Aklan Inc.'s Annual Stockholders' Meeting Portal. 
                                To view the meeting details including ZOOM credentials, please log-in using your provided credentials.
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
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php } else { ?>

    <div class="container p-4" style="margin-top: 10px;">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-10 card m-4 p-4">
                <div class="text-center">
                    <img class="" src="_imgs/apmc_logo.png" alt="ACE-MC-logo" height="150px">
                </div>
                <br><h3 class="text-center"><?=date('Y')?> Annual Stockholders' Meeting</h3><br>
                <p>We are pleased to inform you that our <?=date('Y')?> Annual Stockholders' Meeting ("ASM") is scheduled this coming <b>Thursday, August 31, 2023 at 9 ‘o clock in the morning</b>.</p>
                <p>Below is the link for the Zoom Teleconference:</p>
                <p class="ml-4">
                    APMC-AKLAN is inviting you to a scheduled Zoom meeting.<br>
                    Topic: <?=date('Y')?> APMC-Aklan Annual Stockholders' Meeting<br>
                    <b>Time: Aug 31, 2023 09:00 AM Asia/Manila<br>
                    Meeting ID: 84437646730<br>
                    Passcode: 2023ASM</b><br><br>
                    Join  Meeting<br>
                    <a href="https://us02web.zoom.us/j/84437646730?pwd=RzFxbmhxVDkzeGhQcGMzZjRsUjlUZz09">https://us02web.zoom.us/j/84437646730?pwd=RzFxbmhxVDkzeGhQcGMzZjRsUjlUZz09</a>
                </p>
                <p></p>
                <p>Please be guided that only those Stockholders who have successfully registered in the Online Voting Portal and those who have notified the Company thru email of their intent to participate in the ASM by remote communication on or before 15 August 2023 will be allowed to join in the Meeting and will be included in determining quorum.</p>
                <p>Stockholders may send questions or remarks days prior to the ASM through the Company's email compliance@apmcaklan.com or during the Meeting through the <b><?=date('Y')?> Zoom Meeting Chatbox</b>.</p>
                <p>The proceedings during the <?=date('Y')?> ASM will be recorded. A link to the recorded webcast of the Meeting will be posted on the Company's website and social media within 5 days from the date of the meeting. Stockholders shall have two (2) weeks from posting to raise to the Company any issues, clarifications and concerns on the Meeting conducted.</p>
                <p><i>For more questions and clarifications, stockholders may contact:</i></p>
                <p>The Office of the Corporate Secretary: +639178146042/ +639190963082/ 036-2632320</p>
                <p align="center"><br>
                <?php if ($status == "OPEN") { ?>
                    <button type="button" class="btn btn-lg btn-block btn-info" id="openPortal">Open Election Portal</button>
                <?php } ?>
                    <a href="meeting-info.php?logout=1" class="btn btn-lg btn-block btn-danger">Logout</a>
                </p>

            </div>
        </div>
    </div>

    <?php } ?>
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

    
    $('#openPortal').on("click", event => {
        console.log('clicked');
        $('#myModal').modal('show');
    });
    
    $('#proceedBtn').click(function() {
        location.href = "index1.php";
    });

})(jQuery);
</script>
</html>