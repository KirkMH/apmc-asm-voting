<?php
require_once 'core/init.php';
$str = "
<table class='table table-hover'>
    <thead>
      <tr>
        <th style='text-align: center;'>Agenda</th>
        <th style='text-align: center;'>Vote</th>
      </tr>
    </thead>
    <tbody>";

$member_id = $_POST['member_id'];
$ctr = 0;

$all = MyDb::run("SELECT tbl_agenda.agenda_id, agenda_item, tbl_agenda_vote.vote AS vote FROM tbl_agenda LEFT JOIN (SELECT agenda_id, vote FROM tbl_agenda_temp_vote WHERE tbl_agenda_temp_vote.member_id = $member_id) AS tbl_agenda_vote ON tbl_agenda.agenda_id = tbl_agenda_vote.agenda_id ORDER BY tbl_agenda.agenda_id");
while ($data = $all->fetchAll(PDO::FETCH_ASSOC)) {
	foreach ($data as $value) {
		$ctr++;
		$vote = $value['vote'];
		$vote_desc = "";
		if ($vote == "N")
			$vote_desc = 'No';
		elseif ($vote == "A")
			$vote_desc = 'Abstain';
		else
			$vote_desc = 'Yes';
		$str .= " <tr>
			        <td>".$value['agenda_item']."</td>
			        <td style='text-align: center;'>".$vote_desc."</td>
			      </tr>";
	}
}
$str .= "</tbody>
		</table>";
echo $str;
?>