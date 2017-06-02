<?php

ini_set('max_execution_time', 6000);
ini_set('memory_limit', '-1');

$company1 = "SCS DIRECT INC";
$company2 = NULL;
$address1 = "8972 DARROW RD STE 202242 RANDOM RD";
$address2 = "SCS DIRECT INC100 RATON RD";
$phone1 = null;
$phone2 = null;
$pincode1 = "68251408";
$pincode2 = "64611726";
$city1 = "FAIRFIELD";
$city2 = "MILFORD";
$state1 = "CT";
$state2 = "CT";
$gduns = "64611726";

 

  $array = array("id"=>null,"companyName1"=>$company1,"address1"=>$address1,"city1"=>$city1,"state1"=>$state1,
				"zipcode1"=>$pincode1,"phone1"=>$phone1,"companyName2"=>$company2,"address2"=>$address2,"city2"=>$city2,"state2"=>$state2,
				"zipcode2"=>$pincode2,"phone2"=>$phone2,"contactName"=>null,"duns"=>$gduns);

		//	echo $data_string = json_encode($array);
		echo $data_string = '{"id":null,"companyName1":"LVMH INC","address1":"19 E 57TH ST FL 16","city1":"NEW YORK","state1":"NY","zipcode1":null,"phone1":null,"companyName2":"LVMH INC","address2":null,"city2":null,"state2":null,"zipcode2":null,"phone2":null,"contactName":null,"duns":"121565709"}';
			echo "<br/>";
			echo $url = 'http://54.255.136.38:8080/dunsreader/reader/read';
			echo "<br/>";
			$ch = curl_init($url);
			curl_setopt ($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 0);
			curl_setopt ($ch, CURLOPT_TIMEOUT  , 30);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data_string)));
			$result = curl_exec($ch);
			if(curl_errno($ch)){
				echo 'Curl error: ' . curl_error($ch);
			}
			curl_close($ch);
			echo $result;
			exit;
?>