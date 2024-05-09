<?php
require_once 'ad-meta.php';

if (Input::get('mySubmit')) {
  $action = Input::get('action');
  $elect_id = Input::get('elect_id');
  $title = Input::get('title');
  $from = Input::get('start_sched');
  $to = Input::get('end_sched');

  $from_d = DateTime::createFromFormat('Y-m-d H:i:s', $from);
  $to_d = DateTime::createFromFormat('Y-m-d H:i:s', $to);

  $data = array('title' => $title, 
                'duration_from' => $from, //_d->format('Y-m-d H:i:s'),
                'duration_to' => $to, //_d->format('Y-m-d H:i:s'),
                'active' => '1');
  if ($action == "edit")
    MyDb::update('tbl_election', 'elect_id', $elect_id, $data);
  else
    MyDb::insert('tbl_election', $data);
}
elseif (Input::get('yesBtn')) {
  $id = Input::get('which');
  $field = $id == 1 ? 'voted_on' : 'voted_on2';
  $table = $id == 1 ? 'tbl_vote' : 'tbl_agenda_vote';
  MyDb::delete($table, '1', '1');
  MyDb::run("UPDATE tbl_shareholder SET $field=NULL;");
}

$value = MyDb::select("*", "tbl_shareholder", "id = ".$_SESSION['user_no']);

require_once 'ad-header.php';

?>

  <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
  <style type="text/css">
    th {
      text-align: center;
    }
    .my-btn {
      background-color: #E4F4E8;
      color: black;
      transition: background-color ease .1s;
    }
    .my-btn:hover {
      background-color: #A7288A;
      color: white;
      text-decoration: none;
    }
    .myCard {
      margin: 10px;
      width: 480px;
      display: inline-block;
      text-align: center;
      align-content: center;
    }
    .top-space {
      margin-top: 5px;
    }
  </style>



<!-- Modal for Election Details -->
<div id="myModal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="admin.php" method="post" >
        <div class="modal-header">        
          <h4 class="modal-title">Election Details</h4>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="modal-body">        
          <div class="form-group">
            <label for="title">Election Title:</label>
            <input type="text" class="form-control" placeholder="" id="title" name="title" required="required">
          </div>
          <div class="form-group">
            <label for="title">Scheduled Start of Election:</label>
            <input type="datetime-local" class="form-control" placeholder="Select date and time" id="start_sched" name="start_sched" required="required">
          </div>
          <div class="form-group">
            <label for="title">Scheduled End of Election:</label>
            <input type="datetime-local" class="form-control" placeholder="Select date and time" id="end_sched" name="end_sched" required="required">
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="elect_id" id="elect_id">
          <input type="hidden" name="action" id="action">
          <input type="submit" name="mySubmit" id="mySubmit" class="btn btn-primary pull-right" value="Submit">
        </div>
      </form>
    </div>
  </div>
</div> 


<!-- Modal for Resetting of Results -->
<div id="confirmModal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="admin.php" method="post" >
        <div class="modal-header">        
          <h4 class="modal-title" id="elect_type">Reset Results?</h4>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="modal-body">        
          <p>Are you sure you want to reset the results of this election? You cannot undo this action.</p>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="which" id="which">
          <input type="submit" name="yesBtn" id="yesBtn" class="btn btn-primary pull-right" value="Yes">
          <input type="button" name="noBtn" id="noBtn" class="btn btn-secondary pull-right" value="No" data-dismiss="modal">
        </div>
      </form>
    </div>
  </div>
</div> 


  <div class="card" style="margin-top: 30px;">
    <div class="card-header bg-success">
      <div>
          <h3 class="float-left" style="color: white;">Election List</h3>
          <!-- <a class="btn-lg my-btn float-right" href="#" id="add_election">Add</a> -->
      </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <!-- <div class="input-wrapper">
          <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for title...">
          <label for="myInput" class="fa fa-search input-icon"></label>
        </div> -->

          <?php
          // get server date/time
          $now = new DateTime(null, new DateTimeZone('Asia/Manila'));

          $all = MyDb::select_all("*", "tbl_election", "1");
          $ctr = 1;
          $status1 = "CLOSED";
          $status2 = "CLOSED";
          while ($data = $all->fetchAll(PDO::FETCH_ASSOC)) {
            foreach ($data as $value) {
              $from_date = new DateTime($value['duration_from']);
              $to_date = new DateTime($value['duration_to']);

              $status = "CLOSED";

              if (($now >= $from_date) && ($now <= $to_date)){
                $status = "OPEN";
              }
              $elect_id = $value['elect_id'];
              $title = $value['title'];
              $from = $from_date->format('d M Y');
              $to = $to_date->format('d M Y');
              $start = $from_date->format('Y-m-d\TH:i');
              $end = $to_date->format('Y-m-d\TH:i');
              ?>

        <div class="card myCard">
          <div class="card-header" style="background-color: #5EBD74; color: white;">
            <?=$title;?>
          </div>
          <div class="card-body">
            From <?=$start;?> to <?=$end;?><br>
            <div style="padding: 10px;background-color: rgb(<?=($status == 'OPEN') ? '192,236,164' : '221,221,221';?>);">Status: <?=$status;?></div><br>
            <?php if ($ctr == 1) { 
              $status1 = $status;
            ?>
              <a href="ad-candidates.php" class="btn btn-info">Candidates</a>
              <input type="button" class="btn<?=($status=='CLOSED' ? ' btn-primary' : '');?>" id="view_result" value="Result">
              <input type="button" class="btn btn-success" id="summary" value="Summary"><br>
              <!-- <input type="button" class="btn btn-info" id="details" value="Individual"> -->
              <input type="button" class="btn btn-warning top-space" id="open_modal" value="Edit" data-id="<?=$elect_id;?>" data-from="<?=$start;?>" data-to="<?=$end;?>" data-pname="<?=$title;?>" >
              <input type="button" class="btn btn-danger top-space" id="reset1" value="Reset" data-id="<?=$elect_id;?>">
            <?php } else {
              $status2 = $status;
            ?>
              <a href="ad-items.php" class="btn btn-info">Items</a>
              <input type="button" class="btn<?=($status=='CLOSED' ? ' btn-primary' : '');?>" id="view_result2" value="Result">
              <input type="button" class="btn btn-success" id="summary2" value="Summary"><br>
              <input type="button" class="btn btn-warning top-space" id="open_modal2" value="Edit" data-id="<?=$elect_id;?>" data-from="<?=$start;?>" data-to="<?=$end;?>" data-pname="<?=$title;?>" >  
              <input type="button" class="btn btn-danger top-space" id="reset2" value="Reset" data-id="<?=$elect_id;?>">            
            <?php } ?>  
            
          </div>
        </div>
 
      	<?php
          $ctr++;
      		} 
      	} ?>
  </div>
</div>

<script type="text/javascript">
  
$("#view_result").on("click", function() {
  var $status = "<?=$status1;?>";
  if ($status == 'OPEN')
    return;

    var w         = (screen.width/3) * 2;
    var h         = 600;
    var left      = (screen.width/2)-(w/2);
    var top       = (screen.height/2)-(h/2);

    window.open('ad-results.php', 'BOD Election Results', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
});

$("#view_result2").on("click", function() {
  var $status = "<?=$status2;?>";
  if ($status == 'OPEN')
    return;

    var w         = (screen.width/3) * 2;
    var h         = 600;
    var left      = (screen.width/2)-(w/2);
    var top       = (screen.height/2)-(h/2);

    window.open('ad-results2.php', 'Agenda Results', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
});


$("#summary").on("click", function() {
    var w         = (screen.width/3) * 2;
    var h         = 600;
    var left      = (screen.width/2)-(w/2);
    var top       = (screen.height/2)-(h/2);

    window.open('ad-summary.php', 'BOD Election Summary', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
});

$("#summary2").on("click", function() {
    var w         = (screen.width/4) * 5;
    var h         = 600;
    var left      = (screen.width/2)-(w/2);
    var top       = (screen.height/2)-(h/2);

    window.open('ad-summary2.php', 'BOD Election Summary', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
});


$("#details").on("click", function() {

    var w         = (screen.width/3) * 2;
    var h         = 600;
    var left      = (screen.width/2)-(w/2);
    var top       = (screen.height/2)-(h/2);

    window.open('ad-details.php', 'BOD Election Individual Selection', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
});


$('#open_modal').on("click", function() {
  var name = $(this).data("pname");
  var id = $(this).data("id");
  var from = $(this).data("from");
  var to = $(this).data("to");

  $("#action").val("edit");
  $("#title").val(name);
  $("#elect_id").val(id);
  $("#start_sched").val(from);
  $("#end_sched").val(to);
  $("#myModal").modal('show');
});

$('#open_modal2').on("click", function() {
  var name = $(this).data("pname");
  var id = $(this).data("id");
  var from = $(this).data("from");
  var to = $(this).data("to");

  $("#action").val("edit");
  $("#title").val(name);
  $("#elect_id").val(id);
  $("#start_sched").val(from);
  $("#end_sched").val(to);
  $("#myModal").modal('show');
});


$('#add_election').on("click", function() {
  $("#action").val("add");
  $("#myModal").modal('show');
});


$('#reset1').on("click", function() {
  var id = $(this).data("id");
  $("#elect_type").val("Reset BOD Election Results");
  $("#which").val(id);
  $("#confirmModal").modal('show');
});

$('#reset2').on("click", function() {
  var id = $(this).data("id");
  $("#which").val(id);
  $("#elect_type").val("Reset Agenda Results");
  $("#confirmModal").modal('show');
});
</script>
<?php include_once('ad-footer.php'); ?>