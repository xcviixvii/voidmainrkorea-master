<?php

if (! function_exists('itemclass')) {
	    function itemclass() {
			$itemclass = array(
			'Permanent',				//1
			'Event',					//2
			'Unlimited 7 days',			//3
			'Unlimited 15 days',		//4
			'Unlimited 30 days',		//5
			'Number of times limit',	//6
			'Unlimited',				//7
			'One time'					//8
		);
		return $itemclass;
    }
}

if (! function_exists('ItemDisc')){
    function ItemDisc($value){
            if($value == 1) { $output = 'Permanent'; }
            elseif($value == 2) { $output = 'Event'; }
            elseif($value == 3) { $output ='Unlimited 7 days'; }
			elseif($value == 4) { $output ='Unlimited 15 days'; }
			elseif($value == 5) { $output ='Unlimited 30 days'; }
			elseif($value == 6) { $output ='Number of times limit'; }
			elseif($value == 7) { $output ='Unlimited'; }
			elseif($value == 8) { $output ='One time'; }
        return $output;
    }
}

if (! function_exists('ItemConfig')) {
	    function ItemConfig() {
			$ItemConfig = array();
			array_push($ItemConfig, array("Value" => 1, "Name" => "New Item"));
			array_push($ItemConfig, array("Value" => 2, "Name" => "GM Pick"));
			array_push($ItemConfig, array("Value" => 3, "Name" => "Item Mall"));
			return $ItemConfig;
    }
}

if (! function_exists('newscategory')) {
	    function newscategory() {
			$newscategory = array(
			'Free Board',					//1
			'Test server bulletin board',	//2
			'Tips Board',					//3
			'Screenshot',					//4
			'Development Notes',			//5
			'Club',							//6
			'Club mark',					//7
		);
		return $newscategory;
    }
}


if (! function_exists('NewsConfig')) {
	    function NewsConfig() {
			$NewsConfig = array();
			array_push($NewsConfig, array("value" => NULL,'Name'=>'Normal'));
			array_push($NewsConfig, array("value" => 1,'Name'=>'Highlights'));
			return $NewsConfig;
    }
}