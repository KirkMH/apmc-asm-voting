<?php
require_once 'metaheader.php';

// validate if taken the correct steps
// $s_code = (int) MyDb::select_one("security_code", "tbl_shareholder", "id = " . $_SESSION['user_no']);
// if (is_null($s_code)) {
// 	Redirect::to("security-code.php");
// }

if (Input::get('hiddenSubmit')) {	
	// transfer from temp to tbl_agenda_vote
	$votes = array();
	$tvote = MyDb::select_all("temp_id, agenda_id, vote", 
		"tbl_agenda_temp_vote", 'member_id = '.$_SESSION['user_no']);
	while ($data = $tvote->fetchAll(PDO::FETCH_ASSOC)) {
	  foreach ($data as $value) {
	  	$temp_id = $value['temp_id'];
	  	$arr = array(
	  		'member_id' => $_SESSION['user_no'],
	  		'agenda_id' => $value['agenda_id'],
	  		'vote' => $value['vote']
	  	);
	  	// add to actual vote
	  	MyDb::insert('tbl_agenda_vote', $arr);
	  	// delete after inserting
	  	MyDb::delete('tbl_agenda_temp_vote', 'temp_id', $temp_id);
	  }
	}
	// mark the rest as yes
	// - get list of unselected agenda of the member
	$tvote = MyDb::select_all("agenda_id", 
		"tbl_agenda", 'agenda_id NOT IN (SELECT agenda_id FROM tbl_agenda_vote WHERE member_id = '.$_SESSION['user_no'].')');
	while ($data = $tvote->fetchAll(PDO::FETCH_ASSOC)) {
	  foreach ($data as $value) {
	  	$agenda_id = $value['agenda_id'];
	  	$arr = array(
	  		'member_id' => $_SESSION['user_no'],
	  		'agenda_id' => $agenda_id,
	  		'vote' => 'Y' // yes
	  	);
	  	// add to vote
	  	MyDb::insert('tbl_agenda_vote', $arr);
	  }
	}

	// update that this member has already casted his vote
  	$arr = array('voted_on2' => date("Y-m-d H:i:s"));
  	MyDb::update("tbl_shareholder", "id", $_SESSION['user_no'], $arr);

	Redirect::to('thank-you2.php');
}
else {
	MyDb::delete("tbl_agenda_temp_vote", "member_id", $_SESSION['user_no']);
}	

require_once 'header.php';

?>


<link rel="stylesheet" href="css/vote.css?v=<?=time()?>">
<meta charset="utf-8">
<script src="js/showHide.js" type="text/javascript"></script> 
<script src="js/makeVote.js?v=<?=time()?>" type="text/javascript"></script> 

<style type="text/css">

</style>

<div class="fullcontainer">
	
	<form method="post" id="voting_form" action="vote-agenda.php">
		<div id="container" align="left">
  <div id="top" align="center"> <span class="h1w">Voting for the Agenda</span><br>
	  <div id="q0" class="toggleDiv" style="display: block;" align="center">
	    <div id="box"> <span style="color:red"><b>Instructions </b></span>
	        <p class="lead" align="left" style="padding: 30px 30px;">Hello, <?=$_SESSION['name']?>!<br><br>
	        Please cast your vote for the agenda that follows. To vote, simply click on the dropdown list and select your choice.</p>
	      <p> <input type="button" class="show_hide btn btn-info btn-lg btn-block mt-auto" rel="#q1" value="Start"> </p>
		  <p><a class="btn btn-secondary btn-lg btn-block mt-auto" href="index2.php">Back to Selection</a></p>
	    </div>
	  </div>

	  <div id="q1" class="toggleDiv" style="display: hide;">
	    <div id="box" align="center"> 
	      <p class="h2 ml-2" style="text-align: center;"> Please cast your vote.</p>
	      <p style="text-align: left; font-size: 13pt; font-weight: normal;">Click on the dropdown list and select your choice.</p>

	      <ol style="list-style: none;">
	      <?php
	      // list all the agenda
	      $agenda = MyDb::select_all("*", "tbl_agenda", "1");
	      $ctr = 0;

		  while ($data = $agenda->fetchAll(PDO::FETCH_ASSOC)) {
		    foreach ($data as $value) {
	      ?>

	      	<div class="row no-gutters bg-light position-relative m-3" style="padding:30px; margin-bottom:10px">
			  		<div class="col-md-12">
			  			<li>
			    		<p class="lead" style="text-align:left;"><?=$value['agenda_item']?></p>
							<select onchange="saveUpdate(<?=$value['agenda_id'];?>);" style="width:100%" id="vote_<?=$value['agenda_id'];?>">
								<option value="Y">Yes</option>
							    <option value="N">No</option>
							    <option value="A">Abstain</option>
							</select>
						</li>
					</div>
			</div>

	      <?php
	      	$ctr++;
	      	}
	      }
	      ?>
	  </ol>
	      <input type="hidden" name="nvote" id="nvote" value="<?=$ctr;?>">
	      <input type="hidden" name="selected" id="selected" value="0">

	      <div align="center"> 
	      	<input type="button" style="width: 47%;" class="show_hide btn btn-dark btn-lg mt-auto " rel="#q0" value="Back">
      		<input type="button" style="width: 47%;" class="show_hide btn btn-info btn-lg mt-auto " rel="#q2" value="Next" id="next">
	      </div>
	    </div>
	  </div>

	  <div id="q2" class="toggleDiv" style="display: none;" align="center">
	    <div id="box"> <span style="color:red"><b>Summary </b></span>
	        <p class="lead" align="left" style="padding: 30px 30px;"><?=$_SESSION['name']?>, you have the following for the Agenda.<br><br></p>

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

});


$('body').on('click', '#sumSubmit', function () {
	$('#hiddenSubmit').click();
});


$("#promptOK").on("click", function() {
	$("#myPrompt").modal('hide');
	$('#hiddenSubmit').click();
});

function saveUpdate(id) {
	var e = document.getElementById("vote_" + id);
	var sel = e.options[e.selectedIndex].text;
	var vote = sel.toUpperCase().charAt(0);
	var sel = parseInt($('#selected').val());

	var dataString = 'member_id=<?=$_SESSION['user_no']?>&agenda_id=' + id + '&vote=' + vote;
	  $.ajax({
	    type: "POST",
	    url: "update_selection_agenda.php",
	    data: dataString,
	    success: function(data){
	     if (data == 'inserted')
	     	sel++;
	     	$('#selected').val(sel);
	    }
	  });
}

$("#next").on("click", function() {
	var dataString = 'member_id=' + <?=$_SESSION['user_no']?>;
	  $.ajax({
	    type: "POST",
	    url: "get_selection_agenda.php",
	    data: dataString,
	    success: function(data){
	      $('#selection').html(data);
	    }
	  });
});
</script>

<?php include_once('footer.php'); ?>