<?php
require_once 'metaheader.php';

MyDb::delete("tbl_temp_vote", "member_id", $_SESSION['user_no']);

require_once 'header.php';
?>


	<link rel="stylesheet" href="css/vote.css?v=<?=time()?>">
	<meta charset="utf-8">
	<script src="js/showHide.js" type="text/javascript"></script> 
	<script src="js/makeVote.js?v=<?=time()?>" type="text/javascript"></script> 

<!-- sticky side, for point monitoring -->
<<!-- div class="sticky-container">
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
</div> -->

<?php
$shares = number_format($_SESSION['points']);
$votes = number_format($_SESSION['points'] * 15);
?>

<div class="fullcontainer">
	
	<form method="post" id="voting_form" action="upload-and-save.php">
		<div id="container" align="left">
  <div id="top" align="center"> <span class="h1w">Election of Board of Directors</span><br>
	  <div id="q0" class="toggleDiv" style="display: block;" align="center">
	    <div id="box"> <span style="color:red"><b>Instructions </b></span>
	        <p class="lead" align="left" style="padding: 30px 30px;">Hello, <?=$_SESSION['name']?>!<br><br>
	        	
	        	There will be twelve (12) directors and three (3) independent directors to be elected. You have a total cumulative votes of <?=$votes;?> votes for your <?=$shares;?> shares. You may cast such votes for all the nominees, or one nominee or some of the nominees only, in such number of votes as you prefer or none at all provided that the total number of votes cast shall not exceed the number of shares owned multiplied by the number of directors to be elected.<br><br>

	        	To vote a candidate, simply click on the <kbd style="background-color: rgb(40,167,69);">Vote</kbd> button. It will then change to <kbd style="background-color: rgb(255,193,7); color: black">Selected</kbd>. The system will notify you if you exceed the maximum number of candidates. If you want to change your selection, just click the <kbd style="background-color: rgb(255,193,7); color: black">Selected</kbd> button to deselect the candidate.<br><br>

				Please note that your <?=$_SESSION['points']?> shares will be granted to all of your selected candidates. If you want to assign specific number of shares to a candidate, please <a href="vote-bod.php">click here</a>.</p>
	      <p> <input type="button" class="show_hide btn btn-info btn-lg btn-block mt-auto" rel="#q1" value="Start"> </p>
		  <p><a class="btn btn-secondary btn-lg btn-block mt-auto" href="index2.php">Back to Selection</a></p>
	    </div>
	  </div>

	  <div id="q1" class="toggleDiv" style="display: hide;">
	    <div id="box" align="center"> 
	      <p class="h2 ml-2" style="text-align: center;"> Please choose up to 15 directors.</p>
	      <p style="text-align: left; font-size: 13pt; font-weight: normal;">Please note that your <?=$_SESSION['points']?> shares will be granted to all of your selected candidates. If you want to assign specific number of shares to a candidate, please <a href="vote-bod.php">click here</a>.</p>

	      <?php
	      // list all the candidates under this position
	      $candidates = MyDb::select_all("*", "tbl_candidate", "1 ORDER BY candidate_id ASC");

		  while ($data = $candidates->fetchAll(PDO::FETCH_ASSOC)) {
		    foreach ($data as $value) {
	      ?>

	      	<div class="row no-gutters bg-light position-relative m-3">
			  <div class="col-md-4 mb-md-0 p-md-4">
			    <img class="img-fluid img-thumbnail mr-3" src="imageView.php?image_id=<?=$value["candidate_id"]?>" alt="<?=$value['display_name']?>" style="height:200px; width:auto;">
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
	        <p class="lead" align="left" style="padding: 30px 30px;"><?=$_SESSION['name']?>, you have selected the following candidates as your directors, and your <?=$_SESSION['points']?> shares are granted to each of them accordingly. Please click on <kbd style="background-color: rgb(23, 162, 184); color: white;">Submit</kbd> to confirm your vote.<br><br></p>

	        <span id="selection" style="padding-bottom: 30px;"></span>

	      <div align="center"> 
	      	<input type="button" style="width: 47%;" class="show_hide btn btn-dark btn-lg mt-auto " rel="#q1" value="Back">
  			<input type="button" style="width: 47%;" id="sumSubmit" name="sumSubmit" class="btn btn-info btn-lg mt-auto " value="Submit">
      		<input type="submit" name="hiddenSubmit" id="hiddenSubmit" value="Submit" style="display: none;">
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
        var action = "";

        if (points == 1) {
            if (sel < nvote) {
                $(this).addClass('btn-warning').removeClass('btn-success ');
                $(this).val("Selected");
                $(sname).val(++sel);
                perform_save = true;
                action = "add"
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
            action = "rem"
        }
        $("#remaining").html(nvote-sel);

        if (perform_save) {
	        var dataString = 'candidate_id=' + cnd_id + '&points=' + points + '&action=' + action;
	        $.ajax({
	          type: "POST",
	          url: "update-vote2.php",
	          data: dataString,
	          done: function(){
	          }
	        });
        }

      return false;
  });

});


$('body').on('click', '#sumSubmit', function () {
	var nvote = parseInt($('#nvote').val());
	var sel = parseInt($('#selected').val());

	if (sel == nvote) {
		$('#hiddenSubmit').click();
	}
	else {
		$('#prompt_text').html("You can still select " + (nvote-sel) + " candidate(s). Do you want to continue submitting your votes? Click OK to continue, Close to review your votes.");
		$("#promptOK").show();
		$("#myPrompt").modal('show');
		return false;
	}
});


$("#promptOK").on("click", function() {
	$("#myPrompt").modal('hide');
	$('#hiddenSubmit').click();
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