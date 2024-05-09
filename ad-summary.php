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
          <h3 class="float-center" style="color: white;">BOD Election Voters' Summary</h3>
          <div class="float-right">
            <a href="#" id="btn_print" class="btn btn-light inline" onclick="print_result();">Print</a>
          </div>
      </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <!-- <div class="input-wrapper" id="search_bar">
          <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for names...">
          <label for="myInput" class="fa fa-search input-icon"></label>
        </div> -->
 
        <table id="myTable" style="width:100%; align-self: center;">
        <thead>
            <tr>
                <th class="c">L/N</th>
                <th>Voter</th>
                <th class="c">Number of Shares</th>
                <th class="c">Maximum Points</th>
                <th class="c">Used Points</th>
                <th class="c">Unused Points</th>
            </tr>
        </thead>
        <tbody>
        	<?php
          $ctr = 0;
          $voted_shares = 0;
          $shares_total = 0;
          $max_total = 0;
          $used_total = 0;
          $unused_total = 0;
          $voted = 0;
          $not_voted = 0;
			$all = MyDb::select_all("*", "summary", "1 ORDER BY shares DESC, full_name ASC");
			while ($data = $all->fetchAll(PDO::FETCH_ASSOC)) {
			  foreach ($data as $value) {
          $ctr++;
          $unused = $value['max_points'] - $value['used'];

          $shares_total += $value['shares'];
          $max_total += $value['max_points'];
          $used_total += is_null($value['used']) ? 0 : $value['used'];
          $unused_total += $unused;
          if (is_null($value['used']) || $value['used'] == 0)
            $not_voted++;
          else {
            $voted++;
            $voted_shares += $value['max_points'];
          }
        	?>
            <tr>
                <td class="c"><?=$ctr;?></td>
                <td><a href="#bannerformmodal" data-toggle="modal" data-target="#bannerformmodal" title="View details" class="view_detail" data-pid="<?=$value['id']?>"><?=$value['full_name'];?></a></td>
                <td class="c"><?=number_format($value['shares']);?></td>
                <td class="c"><?=number_format($value['max_points']);?></td>
                <td class="c"><?=is_null($value['used']) ? '-' : number_format($value['used']);?></td>
                <td class="c"><?=number_format($unused);?></td>
            </tr>
        	<?php
        		} 
        	} ?>
            <tr style="font-weight: bold;">
                <td class="c text-left" colspan="2">TOTAL</td>
                <td class="c"><?=number_format($shares_total);?></td>
                <td class="c"><?=number_format($max_total);?></td>
                <td class="c"><?=number_format($used_total);?></td>
                <td class="c"><?=number_format($unused_total);?></td>
            </tr>
            
            <tr><td colspan="6">&nbsp;</td></tr>
            <tr style="font-weight: bold;">
              <td class="c text-left" colspan="2">Voted Shares:</td>
              <td class="c text-right" colspan="4"><?=number_format($voted_shares);?></td>
            </tr>
            <tr style="font-weight: bold;">
              <td class="c text-left" colspan="2">Total Shares:</td>
              <td class="c text-right" colspan="4"><?=number_format($max_total);?></td>
            </tr>
            <?php
            $perc = round(($voted_shares / $max_total) * 10000);
            $perc /= 100;
            ?>
            <tr style="font-weight: bold;">
              <td class="c text-left" colspan="2">Percentage:</td>
              <td class="c text-right" colspan="4"><?=$perc;?>%</td>
            </tr>
            
            <tr><td colspan="6">&nbsp;</td></tr>
            <tr style="font-weight: bold;">
              <td class="c text-left" colspan="2">Total Voters:</td>
              <td class="c text-right" colspan="4"><?=number_format($voted + $not_voted);?></td>
            </tr>
            <tr style="font-weight: bold;">
              <td class="c text-left" colspan="2">Voted:</td>
              <td class="c text-right" colspan="4"><?=$voted;?></td>
            </tr>
            <tr style="font-weight: bold;">
              <td class="c text-left" colspan="2">Not Voted:</td>
              <td class="c text-right" colspan="4"><?=$not_voted;?></td>
            </tr>
        </tbody>
    </table>
  </div>
</div>

<script type="text/javascript">
  function print_result() {
    document.getElementById('btn_print').style.visibility = 'hidden';
    // document.getElementById('search_bar').style.visibility = 'hidden';
    window.print();
    document.getElementById('btn_print').style.visibility = 'visible';
    // document.getElementById('search_bar').style.visibility = 'visible';
  }


$(".view_detail").on("click", function() {
  var id = $(this).data("pid");
  var dataString = 'id=' + id;
    $.ajax({
      type: "POST",
      url: "get_voted.php",
      data: dataString,
      success: function(data){
        $('#vote_details').html(data);
      }
    });
});
</script>
<?php include_once('ad-footer.php'); ?>