<?php
require_once 'core/init.php';

// check which ones have been voted already
$value = MyDb::select("*", "tbl_shareholder", "id = ".$_SESSION['user_no']);
$bod = $value['voted_on'];
$agenda = $value['voted_on2'];

?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="_imgs/APMC_logo.jpg" type="image/jpg" sizes="16x16">
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<title>APMC-Aklan Inc. Election | Selection</title>
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
								<img class="" src="_imgs/apmc_logo.png" alt="APMC-logo" height="100px">
							</div>
                            
                            <p class="text-center text-info">Please cast your votes on the items below. An item with a <span class="badge badge-danger">New</span></a> badge indicates that you haven't casted your vote yet. This <span class="bg-secondary" style="padding: 2px; color: white;">button</span> indicates that it has been filled. Please click the <span style="color:black;">Back to Selection</span> button if you have casted your votes on all the items.</p>
                            <br>

                            <?php if (is_null($bod)) { ?>
                                <a href="vote-bod2.php" class="button pull-left">BOD Election
                                <span class="badge badge-danger">New</span></a>
                            <?php } else { ?>
                                <a href="#" class="button bg-secondary pull-left">BOD Election</a>
                            <?php } ?>


                            <?php if (is_null($agenda)) { ?>
                                <a href="vote-agenda.php" class="button pull-right">Agenda
                                <span class="badge badge-danger">New</span></a>
                            <?php } else { ?>
                                <a href="#" class="button bg-secondary pull-right">Agenda</a>
                            <?php } ?>

                            <a href="index1.php" class="btn btn-light" style="width:100%;margin-top: 30px;">Back to Selection</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>