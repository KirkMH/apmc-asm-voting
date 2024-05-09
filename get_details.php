<?php
require_once 'core/init.php';
$candidate_id = $_POST['candidate_id'];

$candidate = MyDb::select_one("full_name", "tbl_shareholder", "candidate_id = $candidate_id");

$str = "<h3>$candidate</h3><br>";
$str .= "
<table class='table table-hover'>
    <thead>
      <tr>
        <th style='text-align: center;'>Voter</th>
        <th style='text-align: center;'>Points Given</th>
      </tr>
    </thead>
    <tbody>";



$all = MyDb::select_all("tbl_shareholder.full_name, points", "tbl_shareholder INNER JOIN tbl_vote ON tbl_shareholder.id = tbl_vote.member_id", "tbl_vote.candidate_id = $candidate_id ORDER BY full_name");
while ($data = $all->fetchAll(PDO::FETCH_ASSOC)) {
	foreach ($data as $value) {
		$str .= " <tr>
			        <td>".$value['full_name']."</td>
			        <td style='text-align: center;'>".$value['points']."</td>
			      </tr>";
	}
}
$str .= "	</tbody>
		</table>";
echo $str;
?>