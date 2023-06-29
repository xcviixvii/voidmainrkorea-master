[
<?php
$data = $this->llllllllz_model->getevents($_POST['nowDate']);
foreach ($data as $row) {
	?>
	
		{
			"Idx":"<?=$row['newsid']?>",
			"ListImageUrl":"<?=base_url()?>Uploadfiles/News/<?=$row['banner']?>",
			"Title":"<?=$row['newstitle']?>",
			"EventStartDate":"<?=$row['evefrom']?>",
			"EventEndDate":"<?=$row['eveto']?>"
		}
		,
	<?php
}
?>
]
