<?php
session_start();
include("includes/connection.php");
include("includes/con2.php");
include("includes/functions.php");
require("includes/funcstuffs.php");

ini_set('max_execution_time', 6000);
ini_set('memory_limit', '-1');

// Page Variable Declaration
$addpage= $sitepath."manageduns.php";
$urlsite = $sitepath."manageduns.php";
$msgadd = "Data Added Successfully";
$msgupdate = "Data Updated Successfully";
$msgerr = "Data Not Submitted";
$id = $_POST['id'];
$tabname ="input";
$taboutput = "output";

// Check Login Session
if($_SESSION["sadmin_username"]!="")
{


	date_default_timezone_set('Asia/Kolkata');
	$today=date("Y-m-d H:i:s");

	if($_POST["opt"]=="Add"){

		$company1=StringRepair($_POST["company1"]);
		$company2=StringRepair($_POST["company2"]);
		$gduns=StringRepair($_POST["gduns"]);
		$address1=StringRepair($_POST["address1"]);
		$address2=StringRepair($_POST["address2"]);

		$phone1=StringRepair($_POST["phone1"]);
		$phone2=StringRepair($_POST["phone2"]);

		$pincode1=StringRepair($_POST["pincode1"]);
		$pincode2=StringRepair($_POST["pincode2"]);

		$city1=StringRepair($_POST["city1"]);
		$city2=StringRepair($_POST["city2"]);

		$state1=StringRepair($_POST["state1"]);
		$state2=StringRepair($_POST["state2"]);

		if($company1 == ""){
			$company1 = null;
		}
		if($company2 == ""){
			$company2 = null;
		}
		if($gduns == ""){
			$gduns = null;
		}
		if($address1 == ""){
			$address1 = null;
		}
		if($address2 == ""){
			$address2 = null;
		}
		if($phone1 == ""){
			$phone1 = null;
		}
		if($phone2 == ""){
			$phone2 = null;
		}
		if($pincode1 == ""){
			$pincode1 = null;
		}
		if($pincode2 == ""){
			$pincode2 = null;
		}
		if($city1 == ""){
			$city1 = null;
		}
		if($city2 == ""){
			$city2 = null;
		}
		if($state1 == ""){
			$state1 = null;
		}
		if($state2 == ""){
			$state2 = null;
		}


		if($company1 != "" ) {

			$array = array("id" => null, "companyName1" => $company1, "address1" => $address1, "city1" => $city1, "state1" => $state1,
				"zipcode1" => $pincode1, "phone1" => $phone1, "companyName2" => $company2, "address2" => $address2, "city2" => $city2, "state2" => $state2,
				"zipcode2" => $pincode2, "phone2" => $phone2, "contactName" => null, "duns" => $gduns);

			$data_string = json_encode($array);
			$url = 'http://54.255.136.38:8080/dunsreader/reader/read';

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 0);
			curl_setopt ($ch, CURLOPT_TIMEOUT  , 30);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data_string)));
			echo $result = curl_exec($ch);
			curl_close($ch);

			$_SESSION["sadmin_changeImage_Delete"]=$msgadd;

			print "<META http-equiv='refresh' content=0;URL='".$urlsite."'>";
			exit;
		}


	}

}
else
{
	print "<META http-equiv='refresh' content=0;URL=".$sitepath.">";
}
?>