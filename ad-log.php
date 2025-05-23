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
       session_start();
       $_SESSION['name'] = $sqlResult['display_name'];
       $_SESSION['email'] = $sqlResult['user_email'];
       $_SESSION['user_no'] = $sqlResult['ID'];
       echo "<script>alert('Login successful for ".$sqlResult['ID']."!');</script>";
        
        $msg = "Login successful for ". $_SESSION['user_no'] ."!";
        Redirect::to("admin.php");  
    } 
    else {
        $msg = "Invalid username and/or password.\nPlease try again.";  
    }
    
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="_imgs/mainlogo.png" type="image/png" sizes="16x16">
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<title>APMC - Aklan Inc. ASM Portal | Administrator's Login</title>
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
    <div id="login">
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12">

                        <form id="login-form" class="form login100-form validate-form" action="ad-log.php" method="post">
                            <div class="text-center">                            
								<img class="" src="_imgs/apmc_logo.png" alt="APMC-logo" height="100px">
							</div>

                            <h2 class="text-center text-info">Administrator's Login</h2>
                            <p class="text-center text-info"><?=date('Y')?> Annual Stockholders Meeting Portal</p>

                            <div class="form-group wrap-input100 validate-input" data-validate = "Valid username is required. Please contact your administrator if you have none.">
                                <label for="user_login" class="text-info">Username:</label><br>
                                <input type="text" name="user_login" placeholder="Username" class="input100 form-control">
                            </div>
                            <div class="form-group wrap-input100 validate-input" data-validate = "Password is required">
                                <label for="password" class="text-info">Password:</label><br>
                                <input type="password" name="password" placeholder="Password" id="password" class="input100 form-control">
                            </div>

                            <div class="form-group">

                                <input type="submit" name="submit" class="login100-form-btn btn btn-info btn-md" value="Login">&nbsp;&nbsp;&nbsp;
                                <a href="index.php" class="btn btn-light">Voter's Login</a>
                            </div>                            

                            </div>
                        </form>
                    </div>
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