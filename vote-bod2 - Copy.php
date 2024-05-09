<?php
require_once 'metaheader.php';

// validate if taken the correct steps
$s_code = (int) MyDb::select_one("security_code", "96h3PJ3_users", "ID = " . $_SESSION['user_no']);
if ($s_code != -1) {
	Redirect::to("security-code.php");
}

MyDb::delete("tbl_temp_vote", "member_id", $_SESSION['user_no']);

require_once 'header.php';
?>


	<link rel="stylesheet" href="css/vote.css?v=<?=time()?>">
	<meta charset="utf-8">
	<script src="js/showHide.js" type="text/javascript"></script> 
	<script src="js/makeVote.js?v=<?=time()?>" type="text/javascript"></script> 


<!-- Modal HTML -->
<div id="myModal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="upload-and-save.php" method="post" enctype="multipart/form-data">
				<div class="modal-header">				
					<h4 class="modal-title">Verify Your Identity</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body">				
					<p>Please upload a photo of your government-released ID, preferably PRC license.</p>
					<div class="custom-file mb-3">
				      <input type="file" class="custom-file-input" id="filename" name="filename" accept="image/*">
				      <label class="custom-file-label" for="filename">Choose file</label>
				    </div>
				</div>
				<div class="modal-footer">
					<button name="proxSubmit" id="proxSubmit" class="btn btn-primary pull-right" ><i class=""></i> Submit</button>
					<input type="submit" name="mySubmit" id="mySubmit" style="display: none;" value="Submit">
				</div>
			</form>
		</div>
	</div>
</div> 

<!-- sticky side, for point monitoring -->
<div class="sticky-container">
    <div class="card">
	  <div class="card-header">
	    Information
	  </div>
	  <div class="card-body">
	    <p class="card-text">With the current setting, your shares will be equally divided to the number of selected candidates. If you want to assign specific number of shares to a candidate, please <a href="vote-bod.php">click here.</a></p>
		<p class="card-text rcorners">Remaining selections: <br>
			<span id="remaining" style="font-size: 21pt; background-color: #f0ad4e; color: blue;">15</span></p>
	  </div>
	</div>
</div>

<div class="fullcontainer">
	
	<form method="post" id="voting_form" action="vote-bod2.php">
		<div id="container" align="left">
  <div id="top" align="center"> <span class="h1w">Election of Board of Directors</span><br>
	  <div id="q0" class="toggleDiv" style="display: block;" align="center">
	    <div id="box"> <span style="color:red"><b>Instructions </b></span>
	        <p class="lead" align="left" style="padding: 30px 30px;">Hello, <?=$_SESSION['name']?>!<br><br>
	        You can choose up to 15 candidates. To vote a candidate, simply click on the <kbd style="background-color: rgb(40,167,69);">Vote</kbd> button. It will then change to <kbd style="background-color: rgb(255,193,7); color: black">Selected</kbd>. The system will notify you if you exceed the maximum number of candidates. If you want to change your selection, just click the <kbd style="background-color: rgb(255,193,7); color: black">Selected</kbd> button to deselect the candidate.<br><br>
			Please note that your <?=$_SESSION['points']?> shares will be equally divided into all of your selected candidates.</p>
	      <p> <input type="button" class="show_hide btn btn-info btn-lg btn-block mt-auto" rel="#q1" value="Start"> </p>
	    </div>
	  </div>

	  <div id="q1" class="toggleDiv" style="display: hide;">
	    <div id="box" align="center"> 
	      <p class="h2 ml-2" style="text-align: center;"> Please choose up to 15 directors.</p>

	      <?php
	      // list all the candidates under this position
	      $candidates = MyDb::select_all("*", "candidate_list", "1");

		  while ($data = $candidates->fetchAll(PDO::FETCH_ASSOC)) {
		    foreach ($data as $value) {
	      ?>

	      	<div class="row no-gutters bg-light position-relative m-3">
			  <div class="col-md-4 mb-md-0 p-md-4">
			    <img class="img-fluid img-thumbnail mr-3" src="imageView.php?image_id=<?=$value["candidate_id"]?>" alt="<?=$value['display_name']?>" style="height:200px">
			  </div>
			  <div class="col-md-8 position-static p-4 pl-md-0 d-flex flex-column">
			    <h3 class="mt-0" style="text-align: left;"><?=$value['display_name']?></h3>
			    <p style="text-align: left; font-size: 10pt; line-height: 1.3; font-weight: normal;"><?=nl2br($value['candidate_info'])?></p>
			    <input align="left" type="button" id="<?='cand_'.$value['candidate_id']?>" data-pid="<?=$value['candidate_id']?>" class="make_vote btn btn-success btn-lg btn-block mt-auto stretched-link" href="#" value="Vote">
			  </div>
			</div>

	      <?php
	      	}
	      }
	      ?>
	      <input type="hidden" name="nvote" id="nvote" value="15">
	      <input type="hidden" name="selected" id="selected" value="0">

	      <div align="center"> 
	      	<input type="button" style="width: 47%;" class="show_hide btn btn-dark btn-lg mt-auto " rel="#q0" value="Back">
      		<input type="button" style="width: 47%;" class="show_hide btn btn-info btn-lg mt-auto " rel="#q2" value="Next" id="next">
	      </div>
	    </div>
	  </div>

	  <div id="q2" class="toggleDiv" style="display: none;" align="center">
	    <div id="box"> <span style="color:red"><b>Summary </b></span>
	        <p class="lead" align="left" style="padding: 30px 30px;"><?=$_SESSION['name']?>, you have selected the following candidates as your directors, and we have divided your <?=$_SESSION['points']?> shares accordingly. Please click on <kbd style="background-color: rgb(23, 162, 184); color: white;">Submit</kbd> to confirm your vote.<br><br></p>

	        <span id="selection" style="padding-bottom: 30px;"></span>

	      <div align="center"> 
	      	<input type="button" style="width: 47%;" class="show_hide btn btn-dark btn-lg mt-auto " rel="#q1" value="Back">
  			<input type="button" style="width: 47%;" id="sumSubmit" name="sumSubmit" class="btn btn-info btn-lg mt-auto " value="Submit">
      		<input type="submit" name="hiddenSubmit" id="hiddenSubmit" value="submit" style="display: none;">
	      </div>
	    </div>
	  </div>
	</div>
</div>

	</form>
</div>
<script type="text/javascript">

$(document).ready(function(){

   $('.show_hide').showHide({			 
		speed: 'fast',
		easing: 'linear',
		changeText: 0
	}); 

    $('.make_vote').on("click", function() {

        var caption = $(this).val();
        var cnd_id = $(this).data('pid');
        var points = (caption == "Vote") ? 1 : 0;
        // change text and color
        var nname = '#nvote';
        var sname = '#selected';
        var nvote = parseInt($(nname).val());
        var sel = parseInt($(sname).val());
        var perform_save = false;

        if (points == 1) {
            if (sel < nvote) {
                $(this).addClass('btn-warning').removeClass('btn-success ');
                $(this).val("Selected");
                $(sname).val(++sel);
                perform_save = true;
            }
            else {
				$('#prompt_text').html("You have already selected " + sel + 
                    " candidate(s). If you want to change your vote, please deselect a candidate first.");
				$("#myPrompt").modal('show');
            }
        }
        else {
            $(this).removeClass('btn-warning').addClass('btn-success ');
            $(this).val("Vote");
            $(sname).val(--sel);
            perform_save = true;
        }
        $("#remaining").html(nvote-sel);

        if (perform_save) {
	        var dataString = 'candidate_id=' + cnd_id + '&points=' + points;
	        $.ajax({
	          type: "POST",
	          url: "update-vote.php",
	          data: dataString,
	          done: function(){
	          }
	        });
        }

      return false;
  });

});

$('body').on('click', '#proxSubmit', function () {
	if ($('#filename').val() == "") {
		$('#myModal').modal('hide');
		$('#prompt_text').html("Please select a file first.");
		$("#myPrompt").modal('show');
		return false;
	}
	else {
		$(this).prop("disabled", true);
		$(this).html(
			'<span class="fa fa-spinner fa-spin" role="status" aria-hidden="true"></span> Uploading...'
			);
		$('#mySubmit').click();
	}

});


$('body').on('click', '#sumSubmit', function () {
	var nvote = parseInt($('#nvote').val());
	var sel = parseInt($('#selected').val());

	if (sel == nvote) {
		$("#myModal").modal('show');
	}
	else {
		$('#prompt_text').html("You can still give " + (nvote-sel) + " vote(s). Do you want to continue submitting your votes? Click OK to continue, Close to review your votes.");
		$("#promptOK").show();
		$("#myPrompt").modal('show');
		return false;
	}
});


$("#promptOK").on("click", function() {
	$("#myPrompt").modal('hide');
	$("#myModal").modal('show');
});

$("#next").on("click", function() {
	var dataString = 'member_id=' + <?=$_SESSION['user_no']?>;
	  $.ajax({
	    type: "POST",
	    url: "get_selection2.php",
	    data: dataString,
	    success: function(data){
	      $('#selection').html(data);
	    }
	  });
});

// show the name of the file on select
$(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});
</script>

<?php include_once('footer.php'); ?>