<?php
require_once 'ad-meta.php';

if (Input::get('mySubmit')) {
    $candidate_id = (int)Input::get('id');
    $display_name = Input::get('display_name');
    $candidate_info = Input::get('candidate_info');
    $imgData = "";
    $imageType = "";

    $validate = new Validate();
    $err = "";

    if (count($_FILES) > 0) {
        if (is_uploaded_file($_FILES['filename']['tmp_name'])) {
            $imgData = addslashes(file_get_contents($_FILES['filename']['tmp_name']));
            $imageProperties = getimageSize($_FILES['filename']['tmp_name']);
            $imageType = $imageProperties['mime'];
        }
    }

    $data = array(  'display_name' => $display_name, 
                    'candidate_info' => $candidate_info,
                    'position_id' => 0,
                    'photo' => $imgData,
                    'imageType' => $imageType);
    if ($candidate_id > 0) {
        if (Input::get('filename') == "") {
            // for editing and photo is to be updated
            unset($data['photo']);
            unset($data['imageType']);
        }
        $validation = $validate->check($_POST, array(
          'display_name' => array(
            'tag_name' => 'Candidate\'s Name',
            'required' => true
            )
        ));

        if($validation->passed()){
            if (MyDb::update("tbl_candidate", "candidate_id", $candidate_id, $data)) {
                $msg = "Successfully updated candidate's details.";
            }
            else {
                $err = "Failed to update candidate's details.";
            }
        } else {
            foreach ($validation->errors() as $errorKey => $errorVal) {
                $err .= $errorVal . "<br>";
            }
        }
    }
    elseif ($candidate_id == 0) {
        $validation = $validate->check($_POST, array(
          'display_name' => array(
            'tag_name' => 'Candidate\'s Name',
            'required' => true
            )
        ));

        if($validation->passed()){
            if (MyDb::insert("tbl_candidate", $data)) {
                $msg = "Successfully added the new candidate.";
            }
            else {
                $err = "Failed to add the new candidate.";
            }
        } else {
            foreach ($validation->errors() as $errorKey => $errorVal) {
                $err .= $errorVal . "<br>";
            }
        }
    }
    unset($candidate_id);
}
elseif (Input::get('action') == 'edit') {
    $candidate_id = Input::get('id');
    $rec = MyDb::select('*', "tbl_candidate", "candidate_id = $candidate_id");
    $display_name = $rec['display_name'];
    $candidate_info = $rec['candidate_info'];
}
elseif (Input::get('action') == 'delete') {
    $candidate_id = Input::get('id');
    // verify: must not be in voting population
    if (MyDb::delete("tbl_candidate", "candidate_id", $candidate_id)) {
        $msg = "Successfully deleted the selected candidate.";
    }
    else {
        $err = "Failed to delete the selected candidate.";
    }
    unset($candidate_id);
}

require_once 'ad-header.php';
?>


<!-- Modal HTML -->
<div id="myModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="ad-candidates.php" enctype="multipart/form-data">
                <div class="modal-header">              
                    <h4 class="modal-title">Candidate Details</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">    
                      <div class="form-group">
                        <label for="member_id">Candidate's Name:</label>
                        <input type="text" class="form-control" name="display_name" id="display_name" value="<?=isset($candidate_id) ? $display_name : ''?>"/>
                      </div>
                      <div class="form-group">
                        <label for="candidate_info">Information:</label>
                        <textarea class="form-control" rows="5" placeholder="Information about the candidate" name="candidate_info" ><?=isset($candidate_id) ? "$candidate_info" : ""?></textarea>
                      </div>            
                      <div class="form-group">
                        <label for="filename">Candidate's Photo:</label>
                        <div class="custom-file mb-3">
                          <input <?=!isset($candidate_id) ? "required='required'" : ""?> type="file" class="custom-file-input" id="filename" name="filename" accept="image/*">
                          <label class="custom-file-label" for="filename">Choose file</label>
                        </div>
                      </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id" value="<?=isset($candidate_id) ? $candidate_id : 0?>">
                    <input type="submit" name="mySubmit" name="mySubmit" class="btn btn-primary pull-right" value="Submit">
                </div>
            </form>
        </div>
    </div>
</div> 

  <div class="card" style="margin-top: 30px;">
    <div class="card-header bg-success">
      <div>
          <h3 class="float-left" style="color: white;">List of Candidates</h3>
          <div class="float-right">
            <a href="#" class="btn btn-light" data-toggle="modal" data-target="#myModal">Add New</a>
          </div>
      </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">

        <div class="input-wrapper">
          <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for names...">
          <label for="myInput" class="fa fa-search input-icon"></label>
        </div>
 
		<table id="myTable" style="width:96%; align-self: center;">
	        <thead>
	            <tr>
	                <th>Candidate's Name</th>
                    <th>Candidate's Information</th>
                    <th>Photo</th>
	                <th>Action</th>
	            </tr>
	        </thead>
        <tbody>
        	<?php
			$all = MyDb::select_all("candidate_id, display_name, candidate_info", "tbl_candidate", "1 ORDER BY display_name ASC");
			while ($data = $all->fetchAll(PDO::FETCH_ASSOC)) {
			  foreach ($data as $value) {
                $id = $value['candidate_id'];
        	?>
            <tr>
                <td><?=$value['display_name'];?></td>
                <td><?=nl2br($value['candidate_info']);?></td>
                <td class="c"><img class="img-fluid img-thumbnail mr-3" src="imageView.php?image_id=<?=$value["candidate_id"]?>" alt="<?=$value['display_name']?>" style="height:100px"></td>
                <td class="c">
                    <a href="ad-candidates.php?action=edit&id=<?=$id?>" title="Edit">
                        <i class="fa fa-pencil-square-o" aria-hidden="true" ></i>
                    </a> | 
                    <a href="ad-candidates.php?action=delete&id=<?=$id?>" onclick="return confirm('Are you sure you want to delete this candidate?');" title="Delete">
                        <i class="fa fa-trash-o" aria-hidden="true" style="color:red;"></i>
                    </a></td>
            </tr>
        	<?php
        		} 
        	} ?>
        </tbody>
	    </table>
	</div>
  </div>

</div>

<script type="text/javascript">
$(document).ready(function() {
    <?php if (isset($msg) || isset($err)) { ?>
        $('#msgModal').modal('show');
    <?php } elseif ($candidate_id > 0) { ?>
        $('#myModal').modal('show');
    <?php } ?>
} );

// show the name of the file on select
$(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  console.log(fileName);
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});
</script>
<?php include_once('ad-footer.php'); ?>