<?php
require_once 'core/init.php';
?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="_imgs/APMC_logo.jpg" type="image/jpg" sizes="16x16">
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
	<title>APMC-Aklan Inc. Election | Selection</title>
	<link rel="stylesheet" href="css/index.css?v=<?=time();?>">

    <style type="text/css">
        form button {
            width:100%;
            margin-top: 10px;
        }
    </style>

</head>





<body>


    <div id="myModal" class="modal fade">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">        
              <h4 class="modal-title" id="modal_title">Zoom Link</h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">        
                <p>Topic: APMC-Aklan <?=date('Y')?> Annual Stockholders' Meeting<br>
                Time: Aug 31, 2023 09:00 AM Asia/Manila</p>
                <p>Join Zoom Meeting<br>
                <a target="_blank" href="https://us02web.zoom.us/j/83065471136?pwd=wf5sbFOAXcRP4ARbEOfAGGM3pswFbI.1">https://us02web.zoom.us/j/83065471136?pwd=wf5sbFOAXcRP4ARbEOfAGGM3pswFbI.1</a></p>
                <p>Meeting ID: 83065471136<br>
                Passcode: 273528</p>

            </div>
            <div class="modal-footer">
              <input type="button" name="closeBtn" id="closeBtn" class="btn btn-danger pull-right" value="Close" data-dismiss="modal">
            </div>
        </div>
      </div>
    </div> 


	<?php
	if (isset($msg)) {
	?>
	    <div class="alert alert-primary" role="alert">
	      <?=$msg?>
	    </div>
	<?php } ?>	

    <div id="dialog" style="display: none">
    </div>

    <div id="login">
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12 card">

                        <form id="login-form" class="form login100-form validate-form" action="index.php" method="post">
                            <div class="text-center">                            
								<img class="" src="_imgs/apmc_logo.png" alt="ACE-MC-logo" height="100px">
							</div>
                            <br>
                            <h4 class="text-center text-info">Please select an action:</h4>
                            <button type="button" class="btn btn-primary" id="btnZoom">Zoom Link</button><br>
                            <button type="button" class="btn btn-success" id="btnProc" >Voting Procedures</button><br>
                            <button type="button" class="btn btn-info" id="btnNotice">Notice of Meeting</button><br>
                            <button type="button" class="btn btn-warning" id="btnMinutes">Minutes of the Last ASM</button><br>
                            <button type="button" onclick="location.href='security-code.php?resend=1';" class="btn btn-danger" >Vote</button><br>
                            <button type="button" onclick="location.href='meeting-info.php?logout=1';" class="btn btn-light" >Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script type="text/javascript">
$('#btnZoom').on("click", event => {
    $('#myModal').modal('show');
});

var w         = (screen.width/3) * 2;
var h         = 600;
var left      = (screen.width/2)-(w/2);
var top       = (screen.height/2)-(h/2);
$("#btnProc").click(function () {
    window.open('_files/VotingProcedure.pdf', 'APMC Voting Procedure', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
});
$("#btnNotice").click(function () {
    window.open('_files/NoticeOfMeeting.pdf', 'APMC Notice of Meeting', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
});
$("#btnMinutes").click(function () {
    window.open('_files/Minutes.pdf', 'APMC Minutes of the Last ASM', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
});
</script>
</html>