<html>
<head>
	<link rel="icon" href="_imgs/mainlogo.png" type="image/png" sizes="16x16">
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<style type="text/css">
		/* Style the header with a grey background and some padding */
		.header, .footer {
		  overflow: hidden;
		  background-color: #ebebeb;
		  padding: 10px 5px;
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
		  padding-top: 150px;
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
	      padding-top: 50px;
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

	<title>APMC - Aklan Inc. Voting System | Administrator's Page</title>
</head>
<body>


<style type="text/css">
.input-icon{
  position: absolute;
  left: 15px;
  top: 16px; /* Keep icon in center of input, regardless of the input height */
}
.input-wrapper{
  position: relative;
}

#myInput {
  background-position: 10px 12px; /* Position the search icon */
  background-repeat: no-repeat; /* Do not repeat the icon image */
  width: 100%; /* Full-width */
  font-size: 16px; /* Increase font-size */
  padding: 12px 20px 12px 40px; /* Add some padding */
  border: 1px solid #ddd; /* Add a grey border */
  margin-bottom: 12px; /* Add some space below the input */
}

#myTable {
  border-collapse: collapse; /* Collapse borders */
  width: 100%; /* Full-width */
  border: 1px solid #ddd; /* Add a grey border */
  font-size: 18px; /* Increase font-size */
}

#myTable th {
  padding: 12px; /* Add padding */
}

#myTable td {
	padding: 5px;
}

#myTable tr {
  /* Add a bottom border to all table rows */
  border-bottom: 1px solid #ddd;
}

#myTable tr.header, #myTable tr:hover {
  /* Add a grey background color to the table header and on hover */
  background-color: #f1f1f1;
}

	.c {
		text-align: center;
	}
</style>

<!-- Navigation -->
<nav class="header navbar navbar-expand-lg navbar-light bg-light static-top">
  <div class="container">
    <a class="navbar-brand" href="#">
      <img src="_imgs/apmc_logo.png" height="50px" alt="">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item <?=($url=='admin.php') ? 'active' : '';?>">
          <a class="nav-link" href="admin.php">Elections
          </a>
        </li>
        <li class="nav-item <?=($url=='ad-voters.php') ? 'active' : '';?>">
          <a class="nav-link" href="ad-voters.php">Voters</a>
        </li>
        <!-- <li class="nav-item <?=($url=='ad-candidates.php') ? 'active' : '';?>">
          <a class="nav-link" href="ad-candidates.php">Candidates</a>
        </li> -->
        <li class="nav-item ">
          <a class="nav-link " style="color: red;" href="ad-log.php?action=logout">Logout <?=$_SESSION['name']?></a>
        </li>
      </ul>
    </div>
  </div>
</nav>


<div class="fullcontainer" style="text-align: left; padding-top: 90px;">