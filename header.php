<html>
<head>
	<link rel="icon" href="_imgs/APMC_logo.jpg" type="image/jpg" sizes="16x16">
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<style type="text/css">
		/* Style the header with a grey background and some padding */
		.header, .footer {
		  overflow: hidden;
		  background-color: #ebebeb;
		  padding: 10px 0px 10px 5px;
          position: fixed;  
          z-index: 999;
          width: 100%;
		}

	    .header{
	        top: 0;
	    }
	    .footer{
	        bottom: 0;
	    }  

		.spacer
		{
		    width: 100%;
		    height: 200px;
		}

	    .fullcontainer {
		  padding-top: 100px;
		  padding-bottom: 50px;
		  margin: 0 auto; /* Center the DIV horizontally */
		  width: 80%;
	    }

		/* Style the logo link (notice that we set the same value of line-height and font-size to prevent the header to increase when the font gets bigger */
		.header a.logo {
		  font-size: 25px;
		  font-weight: bold;
		}

		/* Float the link section to the right */
		.header-right {
		  float: right;
	      padding-top: 10px;
		}

		/* Add media queries for responsiveness - when the screen is 500px wide or less, stack the links on top of each other */
		@media screen and (max-width: 500px) {
		  .header a {
		    float: none;
		    display: block;
		    text-align: left;
		  }
		  .header-right {
		    float: none;
	        padding-top: 50px;
		  }
		}

		@font-face {
			font-family:"katay";
			src:url("css/font/Kingthings_Calligraphica_Light.ttf");
		}
	</style>

	<title>APMC-Aklan Inc. | Election</title>
</head>
<body>
	<!--header-->
	<div class="header">
	  <img src="_imgs/apmc_logo.png" height="50px">
	  <div class="header-right" style="display:inline-block;margin-top:20px;text-align:center;color:green;font-size:16px;">Annual Meeting of Stockholders <?=date('Y')?> &nbsp;&nbsp;&nbsp;</div>
	</div>
	<!--/.header-->


<!-- for prompts -->
<div id="myPrompt" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">				
				<h4 class="modal-title">APMC - Aklan Inc.</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body">				
				<p id="prompt_text"></p>
			</div>
			<div class="modal-footer" id="footer">
				<input style="display: none;" type="button" name="promptOK" id="promptOK" data-dismiss="modal" class="btn btn-primary pull-right" value="OK" >
				<input type="button" name="promptClose" id="promptClose" data-dismiss="modal" class="btn btn-secondary pull-right" value="Close" >
			</div>
		</div>
	</div>
</div> 