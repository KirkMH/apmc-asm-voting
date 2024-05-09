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

<!-- sticky side, for point monitoring -->
<!-- <div class="sticky-container">
    <div class="card">
	  <div class="card-header">
	    Information
	  </div>
	  <div class="card-body">
	    <p class="card-text">With the current setting, you can assign specific number of shares to a candidate. If you want your shares to be equally divided among your selected candidates, please <a href="vote-bod2.php">click here.</a></p>
		<p class="card-text rcorners">Remaining Shares<br>
			<span id="remaining" style="font-size: 21pt; background-color: #f0ad4e; color: blue;"><?//=$_SESSION['points']?></span></p>
		<p class="card-text rcorners">Remaining Candidates<br>
			<span id="rem_cand" style="font-size: 21pt; background-color: #f0ad4e; color: blue;">15</span> </p>
	  </div>
	</div>
</div> -->

<div class="fullcontainer">
	
	<form method="post" id="voting_form" action="upload-and-save.php">
		<div id="container" align="left">
  <div id="top" align="center"> <span class="h1w">Election of Board of Directors</span><br>
	  <div id="q0" class="toggleDiv" style="display: block;" align="center">
	    <div id="box"> <span style="color:red"><b>Instructions</b></span>
	        <p class="lead" align="left" style="padding: 30px 30px;">Hello, <?=$_SESSION['name']?>!<br><br>
	        You can give up to a maximum of <?=$_SESSION['points']?> points to any candidate of your choice. To vote a candidate, simply click <kbd style="background-color: #17a2b8;">+</kbd> button to add shares, or <kbd style="background-color: #6c757d;">-</kbd> to reduce it. You may also directly enter the number of points that you want to give to the candidate. The system will notify you if you exceed the maximum selection.<br><br>
	    	If you want to grant your full shares to your selected candidates, please <a href="vote-bod2.php">click here</a>.</p>
	      <p> <input type="button" class="show_hide btn btn-info btn-lg btn-block mt-auto" rel="#q1" value="Start"> </p>
	    </div>
	  </div>

	  <div id="q1" class="toggleDiv" style="display: hide;">
	    <div id="box" align="center"> 
	      <p class="h2 ml-2" style="text-align: center;">Please vote for your chosen candidates.</p>
	      <p style="text-align: left; font-size: 13pt; font-weight: normal;">You can give up to a maximum of <?=$_SESSION['points']?> points to any candidate of your choice. If you want to grant your full shares to your selected candidates, please <a href="vote-bod2.php">click here</a>.</p>

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
			    <p style="position: absolute; bottom: 0;">
				    <input type="button" style="display: inline; width: 75px; height: 40px;" class="vless btn btn-secondary" value="-" data-pid="<?=$value["candidate_id"]?>">
				    <input class="vote" type="text" style="display: inline; width: 150px; height: 40px; text-align: center;" value="0" id="vote_<?=$value["candidate_id"]?>"  data-pid="<?=$value["candidate_id"]?>">
				    <input type="button" style="display: inline; width: 75px; height: 40px;" class="vmore btn btn-info" value="+" data-pid="<?=$value["candidate_id"]?>">
				</p>
			  </div>
			</div>

	      <?php
	      	}
	      }
	      ?>
	      <input type="hidden" name="selected" id="selected" value="0">

	      <div align="center"> 
	      	<input type="button" style="width: 47%;" class="show_hide btn btn-dark btn-lg mt-auto " rel="#q0" value="Back">
      		<input type="button" style="width: 47%;" class="show_hide btn btn-info btn-lg mt-auto " rel="#q2" value="Next" id="next">
	      </div>
	    </div>
	  </div>

	  <div id="q2" class="toggleDiv" style="display: none;" align="center">
	    <div id="box"> <span style="color:red"><b>Summary </b></span>
	        <p class="lead" align="left" style="padding: 30px 30px 10px; "><?=$_SESSION['name']?>, you have selected the following candidates as your directors. Note that you can give up to <?=$_SESSION['points']?> share(s). Please click on <kbd style="background-color: rgb(23, 162, 184); color: white;">Submit</kbd> to confirm your vote.<br><br></p>

	        <span id="selection" style="padding-bottom: 50px;"></span>

	      <div align="center"> 
	      	<input type="button" style="width: 47%;" class="show_hide btn btn-dark btn-lg mt-auto " rel="#q1" value="Back">
  			<input type="button" style="width: 47%;" id="submitBtn" name="submitBtn" class="btn btn-info btn-lg mt-auto " value="Submit">
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

	$('.vote').on("keyup", function() {
		var vote = $(this).val();
		var sel = parseInt($("#selected").val());
		var cand = $(this).data('pid');
		var cdt = get_total_candidates();
		var max = <?=$_SESSION['points']?>;

		if (cdt == 16) {
			$('#prompt_text').html("You have already selected 15 candidates. You may still adjust your selection by zeroing the shares of other candidates.");
			$("#myPrompt").modal('show');
			$(this).val(0);
		}
		else if (vote <= max)
			save_progress(cand, vote);
		else{
			$('#prompt_text').html("Sorry, the assigned point exceeds your maximum number of shares.");
			$("#myPrompt").modal('show');
			$(this).val(max);
			update_selected();
		}
	});

	$('.vless').on("click", function() {
		var cand = $(this).data('pid');
        var name = "#vote_" + cand;
		var vote = parseInt($(name).val());
		if (vote > 0) {
			vote = vote - 1;
			$(name).val(vote);
			if (update_selected())
				save_progress(cand, vote);
		}
	});   

	$('.vmore').on("click", function() {
		var sel = get_total_votes();
		var cdt = get_total_candidates();
		var cand = $(this).data('pid');
        var name = "#vote_" + cand;
		var vote = parseInt($(name).val());

		if (sel == <?=$_SESSION['points']?>) {
			$('#prompt_text').html("You have already given all your allowable shares. You may still adjust your shares by deducting your shares from other candidates.");
			$("#myPrompt").modal('show');
		}
		else if (cdt == 15 && vote == 0) {
			$('#prompt_text').html("You have already selected 15 candidates. You may still adjust your selection by zeroing the shares of other candidates.");
			$("#myPrompt").modal('show');
		}
		else {
			vote = vote+1;
			$(name).val(vote);

			if (update_selected())
				save_progress(cand, vote);
			else {
				$(name).val(vote-1);
			}
		}
	});   

	function update_selected() {
		var sum = get_total_votes();
		if (sum > <?=$_SESSION['points']?>) {
			return false;
		}
		else {
			$(".selected").val(sum);
			$("#remaining").html(<?=$_SESSION['points']?>-sum);
			$("#rem_cand").html(15-get_total_candidates());
			return true;
		}
	}

	function get_total_votes() {
		var sum = 0;
		$(".vote").each(function(){
		    sum += +$(this).val();
		});
		return sum;
	}

	function get_total_candidates() {
		var ctr = 0;
		$(".vote").each(function(){
		    ctr += (+$(this).val() > 0) ? 1 : 0;
		});
		return ctr;
	}

    function save_progress(candidate_id, votes) {
    	var points = votes;
        var dataString = 'candidate_id=' + candidate_id + '&points=' + points;
        $.ajax({
          type: "POST",
          url: "update-vote.php",
          data: dataString,
          done: function(){
          }
        });
  	}

	$('#submitBtn').on("click", function() {
		var sel = get_total_votes();
		var nvote = <?=$_SESSION['points']?>;

		if (sel == nvote) {
			$("#hiddenSubmit").click();
		}
		else {

			$('#prompt_text').html("You can still give " + (nvote-sel) + " vote(s). Do you want to continue submitting your votes? Click OK to continue, Close to review your votes.");
			$("#promptOK").show();
			$("#myPrompt").modal('show');

			return false;
		}
  	});
});


$("#promptOK").on("click", function() {
	$("#hiddenSubmit").click();
});


$("#next").on("click", function() {
	var dataString = 'member_id=' + <?=$_SESSION['user_no']?>;
	  $.ajax({
	    type: "POST",
	    url: "get_selection.php",
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