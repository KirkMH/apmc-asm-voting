<?php
require_once 'ad-meta.php';

if (Input::get('mySubmit')) {
    $agenda_id = (int)Input::get('id');
    $agenda_item = Input::get('agenda_item');

    $validate = new Validate();

    $data = array(  'agenda_item' => $agenda_item);
    if ($agenda_id > 0) {
        $validation = $validate->check($_POST, array(
          'agenda_item' => array(
            'tag_name' => 'Agenda Item',
            'required' => true
            )
        ));

        if($validation->passed()){
            if (MyDb::update("tbl_agenda", "agenda_id", $agenda_id, $data)) {
                $msg = "Successfully updated the agenda item.";
            }
            else {
                $err = "Failed to update the agenda item.";
            }
        } else {
            foreach ($validation->errors() as $errorKey => $errorVal) {
                $err .= $errorVal . "<br>";
            }
        }
    }
    elseif ($agenda_id == 0) {
        $validation = $validate->check($_POST, array(
          'agenda_item' => array(
            'tag_name' => 'Agenda Item',
            'required' => true
            )
        ));

        if($validation->passed()){
            if (MyDb::insert("tbl_agenda", $data)) {
                $msg = "Successfully added the agenda item.";
            }
            else {
                $err = "Failed to add the agenda item.";
            }
        } else {
            foreach ($validation->errors() as $errorKey => $errorVal) {
                $err .= $errorVal . "<br>";
            }
        }
    }
    unset($agenda_id);
}
elseif (Input::get('action') == 'edit') {
    $agenda_id = Input::get('id');
    $rec = MyDb::select('*', "tbl_agenda", "agenda_id = $agenda_id");
    $agenda_item = $rec['agenda_item'];
}
elseif (Input::get('action') == 'delete') {
    $agenda_id = Input::get('id');
    // verify: must not be in voting population
    if (MyDb::delete("tbl_agenda", "agenda_id", $agenda_id)) {
        $msg = "Successfully deleted the selected agenda.";
    }
    else {
        $err = "Failed to delete the selected agenda.";
    }
    unset($agenda_id);
}

require_once 'ad-header.php';

?>


<!-- Modal HTML -->
<div id="myModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="ad-items.php">
                <div class="modal-header">              
                    <h4 class="modal-title">Add an Agenda</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                      <div class="form-group">
                        <label for="agenda_item">Agenda Item:</label>
                        <textarea class="form-control" rows="5" placeholder="Agenda" name="agenda_item" ><?=isset($agenda_id) ? "$agenda_item" : ""?></textarea>
                      </div> 
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id" value="<?=isset($agenda_id) ? $agenda_id : 0?>">
                    <input type="submit" name="mySubmit" name="mySubmit" class="btn btn-primary pull-right" value="Submit">
                </div>
            </form>
        </div>
    </div>
</div> 

  <div class="card" style="margin-top: 30px;">
    <div class="card-header bg-success">
      <div>
          <h3 class="float-left" style="color: white;">List of Agenda</h3>
          <div class="float-right">
            <a href="#" class="btn btn-light" data-toggle="modal" data-target="#myModal">Add New</a>
          </div>
      </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">

        <div class="input-wrapper">
          <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search...">
          <label for="myInput" class="fa fa-search input-icon"></label>
        </div>
 
		<table id="myTable" style="width:96%; align-self: center;">
	        <thead>
	            <tr>
	                <th>Agenda</th>
	                <th>Action</th>
	            </tr>
	        </thead>
        <tbody>
        	<?php
			$all = MyDb::select_all("agenda_id, agenda_item", "tbl_agenda", "1 ORDER BY agenda_item");
			while ($data = $all->fetchAll(PDO::FETCH_ASSOC)) {
			  foreach ($data as $value) {
                $id = $value['agenda_id'];
        	?>
            <tr>
                <td><?=nl2br($value['agenda_item']);?></td>
                <td class="c">
                    <a href="ad-items.php?action=edit&id=<?=$id?>" title="Edit">
                        <i class="fa fa-pencil-square-o" aria-hidden="true" ></i>
                    </a> | 
                    <a href="ad-items.php?action=delete&id=<?=$id?>" onclick="return confirm('Are you sure you want to delete this agenda?');" title="Delete">
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
    <?php } elseif ($agenda_id > 0) { ?>
        $('#myModal').modal('show');
    <?php } ?>
} );
</script>
<?php include_once('ad-footer.php'); ?>