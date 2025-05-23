<?php
require_once 'core/init.php';

if (Input::get('action') == "logout") {
	session_destroy();
}
else if (Input::get('submit')) {

   $user_login = addslashes(Input::get('user_login'));
   $psswd = addslashes(Input::get('password'));

   $sqlResult = MyDb::select('*', "feo_users", "user_login = '$user_login' AND user_pass = '$psswd'");
//    $sqlResult =  MyDb::check_password($psswd, "user_pass", "feo_users", "user_login = '$user_login'");

    if($sqlResult){
       $_SESSION['name'] = $sqlResult['display_name'];
       $_SESSION['email'] = $sqlResult['user_email'];
       $_SESSION['user_no'] = $sqlResult['ID'];

        if ((int) $sqlResult['points'] == 0) {
        	Redirect::to("admin.php");
        }
        else {
        	Redirect::to("index.php");
        }
    } 
    else {
        $msg = "Invalid username and/or password.\nPlease try again.";  
  	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="icon" href="_imgs/mainlogo.png" type="image/png" sizes="16x16">
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<title>ACE Medical Center Kalibo BOD Election | Admin</title>
	<link rel="stylesheet" href="css/index.css">
</head>
<body>

	<?php
	if (isset($msg)) {
	?>
	    <div class="alert alert-primary" role="alert">
	      <?=$msg?>
	    </div>
	<?php } ?>

	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt" data-tilt>
					<img src="_imgs/ACELOGO_aklan.png" alt="ACE-MC-logo" height="100px">
					<div class="txt3 p-t-30">ACE Medical Center is now electing its Board of Directors to serve for its Service Year 2020-2021. Cast your vote by first logging in to the system.</div>
				</div>

				<div class="bg-light" style="padding: 25px 25px; margin-bottom: 25px">
					<form class="login100-form validate-form" method="post" action="ad-log.php">
						<span class="login100-form-title">
							<img src="_imgs/ProfilePic2.png" height="100px" align="center"> <br/><br/>
							Admin Login
						</span>

						<div class="wrap-input100 validate-input" data-validate = "Username is required. Please ask from your administrator.">
							<input class="input100" type="text" name="user_login" placeholder="Username">
							<span class="focus-input100"></span>
							<span class="symbol-input100">
								<i class="fa fa-envelope" aria-hidden="true"></i>
							</span>
						</div>

						<div class="wrap-input100 validate-input" data-validate = "Password is required">
							<input class="input100" type="password" name="password" placeholder="Password">
							<span class="focus-input100"></span>
							<span class="symbol-input100">
								<i class="fa fa-lock" aria-hidden="true"></i>
							</span>
						</div>

						<div class="container-login100-form-btn">
							<button class="login100-form-btn" type="submit" name="submit" value="submit">
								Login
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</body>
<script type="text/javascript">
$('.js-tilt').tilt({
		scale: 1.1
	})

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