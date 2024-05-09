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
  .l {
    text-align: left;
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


<div class="fullcontainer" style="text-align: left; padding-top: 90px;">
  <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">


  <div class="card" style="margin-top: 30px;">
    <div class="card-header bg-success">
      <div>
          <h3 class="float-center" style="color: white;">BOD Election Voters' Selection</h3>
          <div class="float-right">
            <a href="#" id="btn_print" class="btn btn-light inline" data-toggle="modal" data-target="#myModal" onclick="print_result();">Print</a>
          </div>
      </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">

        <?php
        $voters = MyDb::select_all("ID, display_name, shares", "feo_users", "user_status >= 0 ORDER BY display_name ASC");
        while ($voter = $voters->fetchAll(PDO::FETCH_ASSOC)) {
          foreach ($voter as $vote) {
            $id = $vote['ID'];
            $shares = $vote['shares'];
        ?>

        <div class="bg-primary" style="padding: 10px;">
          <h3><?=$vote['display_name']?></h3>
        </div>
 
        <table id="myTable" style="width:100%; align-self: center;">
        <thead>
            <tr>
                <th class="c">L/N</th>
                <th>Candidate</th>
                <th class="c">Value</th>
            </tr>
        </thead>
        <tbody>
        	<?php
          $ctr = 0;
          $used = 0;
			$all = MyDb::select_all("*", "tbl_vote", "member_id = $id");
			while ($data = $all->fetchAll(PDO::FETCH_ASSOC)) {
			  foreach ($data as $value) {
          $ctr++;
          $used += $value['points'];
          $cid = $value['candidate_id'];
          $mid = MyDb::select_one("member_id", "tbl_candidate", "candidate_id=$cid");
          $cname = MyDb::select_one("display_name", "feo_users", "ID=$mid");
          $shares = MyDb::select_one("shares", "feo_users", "ID=$mid");
        	?>
            <tr>
                <td class="c"><?=$ctr;?></td>
                <td><?=$cname;?></td>
                <td class="c"><?=$value['points'];?></td>
            </tr>
        	<?php
        		} 
        	} 
          $max = 15 * $shares;?>

            <tr class="bg-light">
                <td colspan="2">Shares:</td>
                <td class="l"><?=$shares;?></td>
            </tr>
            <tr class="bg-light">
                <td colspan="2">Max Points:</td>
                <td class="l"><?=$max;?></td>
            </tr>
            <tr class="bg-light">
                <td colspan="2">Total Used:</td>
                <td class="l"><?=$used;?></td>
            </tr>
            <tr class="bg-light">
                <td colspan="2">Total Unused:</td>
                <td class="l"><?=($max - $used);?></td>
            </tr>
        </tbody>
    </table>
    <br><br>

    <?php }
    } ?>
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


$(".view_detail").on("click", function() {
  var id = $(this).data("pid");
  var dataString = 'candidate_id=' + id;
    $.ajax({
      type: "POST",
      url: "get_details.php",
      data: dataString,
      success: function(data){
        $('#vote_details').html(data);
      }
    });
});
</script>
<?php include_once('ad-footer.php'); ?>