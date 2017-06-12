<?php
session_start();
include("includes/connection.php");
include("includes/functions.php");
require("includes/funcstuffs.php");

// Page Variable Declaration
$addpage= $sitepath."managesic.php";
$urlsite = $_SESSION["producturl"];
$msgadd = "Data Added Successfully";
$msgupdate = "Data Updated Successfully";
$msgerr = "Data Not Submitted";
$id = $_POST['id'];
$tabname ="site_data";

// Check Login Session
if($_SESSION["sadmin_username"]!="")
{


	date_default_timezone_set('Asia/Kolkata');
	$today=date("Y-m-d H:i:s");

	if($_POST["opt"]=="Add"){

		$company=StringRepair($_POST["company"]);
		$address=StringRepair($_POST["address"]);
		$home = StringRepair($_POST["text_1"]);
		$dloop = $_POST["dloop"];

		$url=array();
		for($i=1;$i<=$dloop;$i++) {
			$url[] = $_POST["text_".$i];
		}

		$array = array("home_url"=>$home,"urls"=>$url);
		$data_string = json_encode($array);
		$str = json_encode($url);

		$url = 'http://localhost:8080/mapsite';

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Content-Length: ' . strlen($data_string))
		);
		$result = curl_exec($ch);
		curl_close($ch);
		$json_result = json_decode($result, true);
		$m = 0;
		$site_id ="";
		$stat = 0;
		foreach ($json_result as $key => $dj){
			$arr[$m] = $dj;
			$m++;
		}
		$site_id = $arr[0];
		$stat = $arr[1];

		if($stat == 1 ){

			$qry = "select * from sic_mapper.site_data where site_id='".$site_id."'";
			$rs = mysqli_query($db,$qry) or die("cannot Connect".mysqli_error($db));
			$row= mysqli_fetch_array($rs);

			$updated = $row["updated_at"];
			$created = $row["created_at"];
			$sicdata = $row["sic_data"];
			$is_processed = $row["is_processed"];

			$json = json_decode($row["sic_data"],true);


			$form_data = array(
				'site_id' => $site_id,
				'site_name' => $company,
				'address' => $address,
				'site_home' => $home,
				'site_links' => $str,
				'is_processed' => $is_processed,
				'sic_data' => $sicdata,
				'updated_at' => $updated,
				'created_at' => $created
			);

			// Insert of Data
			dbRowInsert($db,$tabname,$form_data);

			//$_SESSION["sadmin_changeImage_Delete"]=$msgadd;

			$i = 0;
			$j = 0;
			echo '<table class="table table-bordered responsive">
                        <thead>
                        <tr >
                            <th>SIC 2 Code</th>
                            <th>SIC 2 Division</th>
                            <th>SIC 2 Description</th>
                            <th>SIC 4 Code</th>
                            <th>SIC Code Description</th>
                        </tr>
                        </thead>
                        <tbody>';

				$si = count($json['sicDisplay']);
				$mj=0;
				for($i=0;$i<$si;$i++){
					$sicdata = $json['sicDisplay'][$i]['code'];
					$sic2 = count($json['sicDisplay'][$i]['children']);
					for($j=0;$j<$sic2;$j++){
						$sicdesc2 = count($json['sicDisplay'][$i]['children'][$j]['children']);
						//echo $sicdata = $json['sicDisplay'][$i]['children'][$j]['code'];
						//echo $sicdata = $json['sicDisplay'][$i]['children'][$j]['name'];
						for($k=0;$k<$sicdesc2;$k++){
							$mj++;
							if($mj % 2 == 0){
								echo '<tr class="con0">';
							}else{
								echo '<tr class="con1">';
							}
							echo "<td>".$json['sicDisplay'][$i]['name']."</td>";
							echo "<td>".$json['sicDisplay'][$i]['children'][$j]['code']."</td>";
							echo "<td>".$json['sicDisplay'][$i]['children'][$j]['name']."</td>";
							echo "<td>".$json['sicDisplay'][$i]['children'][$j]['children'][$k]['code']."</td>";
							echo "<td>".$json['sicDisplay'][$i]['children'][$j]['children'][$k]['name']."</td>";
							echo "</tr>";
				
						}
					}
				}

			echo '   </tbody>
             </table>';

		}else{
			echo '<div style="width:80%; margin:0 auto; padding:10px; text-align:center;">There is an error processing the data from the server.</div>';
		}
		exit;

	}elseif ($_POST["opt"]=="Edit"){

		$id=StringRepair($_POST["id"]);
		$company=StringRepair($_POST["company"]);
		$address=StringRepair($_POST["address"]);
		$sic2=StringRepair($_POST["sic2"]);
		$sic4=StringRepair($_POST["sic4"]);
		$dloop = $_POST["dloop"];

		$url=array();
		for($i=1;$i<=$dloop;$i++) {
			$url[] = $_POST["text_".$i];
		}

		$array = array("home_url"=>$home,"urls"=>$url);
		$json = json_encode($array);
		$str = json_encode($url);

		$form_data = array(
			'site_name' => $company,
			'site_links' => $str,
			'address' => $address,
			'sic2' => $sic2,
			'sic4' => $sic4,
			'updated_at' => $today
		);

		// Insert of Data
		//dbRowUpdate($db,$tabname,$form_data,"where id=".$id);

		$_SESSION["sadmin_changeImage_Delete"]=$msgupdate;

		print "<META http-equiv='refresh' content=0;URL='".$urlsite."'>";
		exit;
	}

}
else
{
	print "<META http-equiv='refresh' content=0;URL=".$sitepath.">";
}
?>