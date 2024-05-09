<?php
require_once 'core/init.php';
$str = "
<table class='table table-hover'>
    <thead>
      <tr>
        <th style='text-align: center;'>Candidate</th>
        <th style='text-align: center;'>Votes</th>
      </tr>
    </thead>
    <tbody>";

$member_id = $_POST['member_id'];

$sum_points = 0;
$ctr = 0;

$all = MyDb::select_all("tbl_temp_vote.candidate_id, display_name, points", "tbl_temp_vote INNER JOIN tbl_candidate ON tbl_temp_vote.candidate_id = tbl_candidate.candidate_id", "tbl_temp_vote.member_id = $member_id ORDER BY display_name");
while ($data = $all->fetchAll(PDO::FETCH_ASSOC)) {
	// var_dump($data);
	foreach ($data as $value) {
		$sum_points += $value['points'];
		$ctr++;
		$str .= " <tr>
			        <td>".$ctr.". ".$value['display_name']."</td>
			        <td style='text-align: center;'>".$value['points']."</td>
			      </tr>";
	}
}
$str .= "	<tr>
				<td style='background-color: rgb(207,200,234);'>TOTAL</td>
				<td style='background-color: rgb(207,200,234); text-align: center;'>$sum_points</td>
			</tr>
			</tbody>
		</table>";
echo $str;
?>