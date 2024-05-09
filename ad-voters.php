<?php
require_once 'ad-meta.php';

if (Input::get('mySubmit')) {
	$member_id = (int)Input::get('id');
	$full_name = Input::get('full_name');
	$shares = (int) Input::get('shares');
	$username = Input::get('username');
	$tin = Input::get('tin');
	$email = Input::get('email');
	$contact = Input::get('contact');

	$validate = new Validate();

	$data = array(	'username' => $username, 
					'email' => $email,
					'full_name' => $full_name,
					'shares' => $shares,
					'tin' => $tin,
					'contact' => $contact);

	if ($member_id > 0) {
		if (MyDb::update("tbl_shareholder", "id", $member_id, $data)) {
			$msg = "Successfully updated member $full_name.";
		}
		else {
			$err = "Failed to update member $full_name.";
		}
	}
	if ($member_id == 0) {
		// insert
		$validation = $validate->check($_POST, array(
	      	'email' => array(
		        'tag_name' => 'Email',
		        'required' => true,
		        'email' => true,
		        'unique' => "tbl_shareholder"
		        ),
	      	'username' => array(
		        'tag_name' => 'Username',
		        'required' => true,
		        'unique' => "tbl_shareholder"
		        ),
	      	'tin' => array(
		        'tag_name' => 'TIN',
		        'required' => true,
		        'min' => 6,
		        'max' => 20,
		        'unique' => "tbl_shareholder"
		        ),
	      	'full_name' => array(
		        'tag_name' => 'Complete Name',
		        'required' => true
		        ),
	      	'shares' => array(
		        'tag_name' => 'Number of Shares',
		        'numeric' => true
		        ),
	    ));

		if($validation->passed()){
			if (MyDb::insert("tbl_shareholder", $data)) {
				$msg = "Successfully added member $full_name.";
			}
			else {
				$err = "Failed to add member $full_name.";
			}
		} else {
			$err = "";
			foreach ($validation->errors() as $errorKey => $errorVal) {
	        	$err .= $errorVal . "<br>";
	      	}
		}
	}
}
elseif (Input::get('action') == 'edit') {
	$member_id = Input::get('id');
	$rec = MyDb::select('*', "tbl_shareholder", "id = $member_id");
	$username = $rec['username'];
	$email = $rec['email'];
	$full_name =  $rec['full_name'];
	$shares = $rec['shares'];
	$contact = $rec['contact'];
	$tin = $rec['tin'];
}
elseif (Input::get('action') == 'delete') {
	$member_id = Input::get('id');
	$deleted_name = MyDb::select_one("full_name", "tbl_shareholder", "id = $member_id");
	// verify: must not be in voting population
	if (MyDb::delete("tbl_shareholder", "id", $member_id)) {
		$msg = "Successfully deleted member $deleted_name.";
	}
	else {
		$err = "Failed to delete member $deleted_name.";
	}
}

require_once 'ad-header.php';

?>


<!-- Modal HTML -->
<div id="myModal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="post" action="ad-voters.php">
				<div class="modal-header">				
					<h4 class="modal-title">Member-Voter's Details</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body">	
					  <div class="form-group">
					    <label for="full_name">Complete Name:</label>
					    <input type="text" class="form-control" placeholder="Lastname, Firstname MI" name="full_name" required="required" value="<?=isset($full_name) ? $full_name : ''?>">
					  </div>
					  <div class="form-group">
					    <label for="shares">Number of Shares:</label>
					    <input type="number" class="form-control" placeholder="e.g. 10" name="shares" min="0" step="1" required="required" value="<?=isset($shares) ? $shares : ''?>">
					  </div>
					  <div class="form-group">
					    <label for="username">Username:</label>
					    <input type="text" class="form-control" placeholder="Login username" name="username" required="required" value="<?=isset($username) ? $username : ''?>">
					  </div>
					  <div class="form-group">
					    <label for="tin">TIN (without dashes):</label>
					    <input type="number" class="form-control" placeholder="000000000" name="tin" required="required" value="<?=isset($tin) ? $tin : ''?>">
					  </div>			
					  <div class="form-group">
					    <label for="email">Email address:</label>
					    <input type="email" class="form-control" placeholder="abc@def.com" name="email" required="required" value="<?=isset($email) ? $email : ''?>">
					  </div>	
					  <div class="form-group">
					    <label for="contact">Contact number:</label>
					    <input type="text" class="form-control" placeholder="+639" name="contact" value="<?=isset($contact) ? $contact : ''?>">
					  </div>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="id" value="<?=isset($member_id) ? $member_id : 0?>">
					<input type="submit" name="mySubmit" name="mySubmit" class="btn btn-primary pull-right" value="Submit">
				</div>
			</form>
		</div>
	</div>
</div> 

<?php if (isset($msg) || isset($err)) { ?>
<div id="msgModal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
				<div class="modal-header <?=(isset($msg) ? 'bg-success' : 'bg-warning');?>">				
					<h4 class="modal-title">Information</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body">	
					  <p><?=(isset($msg) ? $msg : $err);?></p>
				</div>
				<div class="modal-footer">
					<button type="button" class="close btn btn-info" data-dismiss="modal" aria-hidden="true">Close</button>
				</div>
			</form>
		</div>
	</div>
</div> 
<?php } ?>

  <div class="card" style="margin-top: 30px;">
    <div class="card-header bg-success">
      <div>
      	  <h3 class="float-left" style="color: white;">List of Voters</h3>
	      <div class="float-right">
	      	<a href="#" class="btn btn-light" data-toggle="modal" data-target="#myModal">Add New</a>
	      </div>
	  </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body table-responsive">
    	<div class="input-wrapper">
		  <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for names...">
		  <label for="myInput" class="fa fa-search input-icon"></label>
		</div>
 
		<table id="myTable" style="width:96%; align-self: center;">
	        <thead>
	            <tr>
	                <th width="15%">Name</th>
	                <th width="10%">Username</th>
	                <th width="15%">TIN</th>
	                <th width="15%">Email</th>
	                <th width="10%">Shares</th>
	                <th width="20%">Voted on</th>
	                <th width="15%">Action</th>
	            </tr>
	        </thead>
	        <tbody>
	        	<?php
				$all = MyDb::select_all("*", "tbl_shareholder", "1");
				while ($data = $all->fetchAll(PDO::FETCH_ASSOC)) {
				  foreach ($data as $value) {
				  	$id = $value['id'];
				  	if (!(is_null($value['voted_on'])  || $value['voted_on'] == '0000-00-00 00:00:00')) {
			  			$date = date_create($value['voted_on']);
			  			$d_out = date_format($date, 'M d,  g:i A');	
				  	}
				  	else {
				  		unset($d_out);
				  	}
	        	?>
	            <tr>
	                <td width="15%"><?=$value['full_name'];?></td>
	                <td width="15%"><?=$value['username'];?></td>
	                <td width="10%" class="c"><?=$value['tin'];?></td>
	                <td width="15%"><?=$value['email'];?></td>
	                <td width="10%" class="c"><?=$value['shares'];?></td>
	                <td width="15%" class="c"><?=(isset($d_out) ? $value['voted_on'] : "");?></td>
	                <td class="c" width="15%">
	                	<a href="ad-voters.php?action=edit&id=<?=$id?>" title="Edit">
	                		<i class="fa fa-pencil-square-o" aria-hidden="true" ></i>
	                	</a> | 
	                	<a href="ad-voters.php?action=delete&id=<?=$id?>" onclick="return confirm('Are you sure you want to delete this member?');" title="Delete">
	                		<i class="fa fa-trash-o" aria-hidden="true" style="color:red;"></i>
	                	</a>
	                </td>
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
	<?php } elseif ($member_id > 0) { ?>
		$('#myModal').modal('show');
	<?php } ?>
} );
</script>
<?php include_once('ad-footer.php'); ?>