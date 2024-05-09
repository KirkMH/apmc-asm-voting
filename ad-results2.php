<?php
require_once 'ad-meta.php';

?>


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

  <title>APMC-Aklan | BOD Election</title>
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
  </div>
</nav>


<!-- Modal HTML -->
<div class="modal fade bannerformmodal" tabindex="-1" role="dialog" aria-labelledby="bannerformmodal" aria-hidden="true" id="bannerformmodal">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="vote-bod.php" method="post" enctype="multipart/form-data" action="vote-bod.php">
        <div class="modal-header">        
          <h4 class="modal-title">Vote Details</h4>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="modal-body" id="vote_details">   
        </div>

        <div class="modal-footer">
          <input type="button" class="btn btn-default pull-right" data-dismiss="modal" value="Close">
        </div>
      </form>
    </div>
  </div>
</div> 


<div class="fullcontainer" style="text-align: left; padding-top: 90px;">
  <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">


  <div class="card" style="margin-top: 30px;">
    <div class="card-header bg-success">
      <div>
          <h3 class="float-center" style="color: white;">Agenda Results</h3>
          <div class="float-right">
            <a href="#" id="btn_print" class="btn btn-light inline" data-toggle="modal" data-target="#myModal" onclick="print_result();">Print</a>
          </div>
      </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="input-wrapper" id="search_bar">
          <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for names...">
          <label for="myInput" class="fa fa-search input-icon"></label>
        </div>
 
        <table id="myTable" style="width:100%; align-self: center;">
        <thead>
            <tr>
                <th>Agenda</th>
                <th class="c">Yes Votes</th>
                <th class="c">No Votes</th>
                <th class="c">Abstain Votes</th>
            </tr>
        </thead>
        <tbody>
        	<?php
          $ctr = 0;
			$all = MyDb::select_all("agenda_id, agenda_item", "tbl_agenda", "1 ORDER BY agenda_item ASC");
			while ($data = $all->fetchAll(PDO::FETCH_ASSOC)) {
			  foreach ($data as $value) {
          $ctr++;
          $id = $value['agenda_id'];

          $yes = MyDb::select_one('COUNT(*) AS yes_votes', 'tbl_agenda_vote', "agenda_id = $id AND vote='Y'");
          $no = MyDb::select_one('COUNT(*) AS no_votes', 'tbl_agenda_vote', "agenda_id = $id AND vote='N'");
          $abstain = MyDb::select_one('COUNT(*) AS abstain_votes', 'tbl_agenda_vote', "agenda_id = $id AND vote='A'");
        	?>
            <tr>
                <td><?=$value['agenda_item'];?></td>
                <td class="c"><?=$yes;?></td>
                <td class="c"><?=$no;?></td>
                <td class="c"><?=$abstain;?></td>
            </tr>
        	<?php
        		} 
        	} ?>
        </tbody>
    </table>
  </div>
</div>

<script type="text/javascript">
  function print_result() {
    document.getElementById('btn_print').style.visibility = 'hidden';
    document.getElementById('search_bar').style.visibility = 'hidden';
    window.print();
    document.getElementById('btn_print').style.visibility = 'visible';
    document.getElementById('search_bar').style.visibility = 'visible';
  }
</script>
<?php include_once('ad-footer.php'); ?>