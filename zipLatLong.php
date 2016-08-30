<?php 
	 $val = getLnt('741222');
	 echo "<pre>";print_r($val);

	function getLnt($zip){
		$url = "http://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($zip)."&sensor=false";
		$result_string = file_get_contents($url);
		$result = json_decode($result_string, true);
		//$result1[]=$result['results'][0];
		//$result2[]=$result1[0]['geometry'];
		//$result3[]=$result2[0]['location'];
		//return $result3[0];
		return $result;
	}





?>
