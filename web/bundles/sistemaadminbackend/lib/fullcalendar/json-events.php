<?php

	$year = date('Y');
	$month = date('m');

	echo json_encode(array(
	
		array(
			'id' => 111,
			'title' => "Event1",
			'start' => "$year-$month-10"
		),
		
		array(
			'id' => 222,
			'title' => "Event2",
			'start' => "$year-$month-20",
			'end' => "$year-$month-22"
		),

		array(
			'id' => 333,
			'title' => "Event3",
			'start' => "$year-$month-26",
			'end' => "$year-$month-27",
			'color' => '#cea97e',
			'textColor' => '#5e4223'
		)
	
	));

?>
