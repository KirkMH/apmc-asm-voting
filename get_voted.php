<?php
require_once 'core/init.php';
$id = $_POST['id'];

$voter = MyDb::select_one("full_name", "tbl_shareholder", "id = $id");

$str = "<h3>$voter</h3><br>";
$str .= "
<table class='table table-hover'>
    <thead>
      <tr>
        <th style='text-align: center;'>L/N</th>
        <th style='text-align: center;'>Candidate</th>
        <th style='text-align: center;'>Points Given</th>
      </tr>
    </thead>
    <tbody>";


$ctr = 1;
$all = MyDb::select_all("*", "tbl_vote", "member_id = $id ORDER BY points DESC");
while ($data = $all->fetchAll(PDO::FETCH_ASSOC)) {
	foreach ($data as $value) {
    $candidate = MyDb::select_one("display_name", "tbl_candidate", "candidate_id = ".$value['candidate_id']);
		$str .= " <tr>
			        <td>".$ctr."</td>
			        <td>".$candidate."</td>
			        <td style='text-align: center;'>".$value['points']."</td>
			      </tr>";
    $ctr++;
	}
}
$str .= "	</tbody>
		</table>";
echo $str;
?>