<?php
session_start();
include("includes/connection.php");
include("includes/functions.php");
ini_set('max_execution_time', 6000);
ini_set('memory_limit', '-1');

//include("Excel/reader.php");
/// Check Login Session
if($_SESSION["sadmin_username"]!="")
{

	if($page_upload_management != 1)
	{
		$_SESSION["sadmin_siteAccessMessage"] = $access_message;
		print "<META http-equiv='refresh' content=0;URL=home.php>";
		exit;
	}
	
	$id = $_POST["id"];
	$opr = $_POST["opt"];
	$proname = StringRepair($_POST["cname"]);
	$company = StringRepair($_POST["company"]);
	$address = StringRepair($_POST["address"]);
	$filename=$_FILES["flname1"]["tmp_name"];
	$act = 0;
	if($_POST["act"] == 1)
	{
		$act = 1;
	}
	
	if($opr == "Add"){
		
		if($_FILES['flname1']['name']!="")
		{		
			$allowed = "/[^a-z0-9.]/i";
			$newname_small=strtolower(preg_replace($allowed,"",$_FILES['flname1']['name']));
			$newfile="docs/excel_".rand(1000,9999)."_".$newname_small;
			$newname_30=$newfile;
			UploadImage('flname1',$newname_30);
		}		
			
			if($opr)
				$sql = "INSERT into project_data (`proname`,`company`,`comp_address`,`excelpath`) values('$proname','$company','$address','$newname_30')";
					 //we are using mysql_query function. it returns a resource on true else False on error
					  mysqli_query($db,$sql)or die("cannot insert into project_data ".mysqli_error($db));
					  
					  $current_id = mysqli_insert_id($db);
		}elseif($opr == "Edit"){
		if($_FILES['flname1']['name']!="")
		{
			$allowed = "/[^a-z0-9.]/i";
			$newname_small=strtolower(preg_replace($allowed,"",$_FILES['flname1']['name']));
			$newfile="docs/excel_".rand(1000,9999)."_".$newname_small;
			$newname_30=$newfile;
			UploadImage('flname1',$newname_30);
			$sql = "update project_data set `excelpath`='".$newname_30."' where cid=".$id;
			mysqli_query($db,$sql) or die("Cannot update Excel filepath".mysqli_error($db));
		}

			if($opr)
				$sql = "update project_data set `proname`='".$proname."',`company`='".$company."',`comp_address`='".$address."',`activate`='".$act."' where cid=".$id;
					 //we are using mysql_query function. it returns a resource on true else False on error
					  mysqli_query($db,$sql)or die("cannot Update into project_data ".mysqli_error($db));

					  $current_id = $id;
	}

		 if($_FILES["flname1"]["size"] > 0)
		 {

			// If you need to parse csv files
			// If you need to parse XLS files, include php-excel-reader
			 //require('php-excel-reader/excel_reader2.php');

			 require('SpreadsheetReader.php');

			 $Reader = new SpreadsheetReader($newname_30);
			 $x=0;
                         $isFirst = 1;
			 $rcal = 0;
			 foreach ($Reader as $Row)
			 {
				 $x++;
				 if($x<2){}
				 else{
                                     
                                     if($isFirst==1){
                                         $state =  StringRepair($Row[5]);
                                         $isFirst = 0;
                                     }
					 $rcal++;
						$serial = StringRepair($Row[0]);
						$dba_name = StringRepair($Row[1]);
						$legal_name = StringRepair($Row[2]);
						$b_address = StringRepair($Row[3]);
						$b_city = StringRepair($Row[4]);
						$b_state = StringRepair($Row[5]);
						$a_code = StringRepair($Row[6]);
						$a_signer = StringRepair($Row[7]);
					 	$weblink = StringRepair($Row[8]);

						if ($serial != "") {
								
							$sql = "INSERT into data (`rcal`,`proid`,`serial`, `legal_name`,`dba_name`, `b_address`, `b_city`,`b_state`, `a_code`,`a_signer`,`weblink`) values ('$rcal','$current_id','$serial','$legal_name','$dba_name', '$b_address', '$b_city', '$b_state', '$a_code', '$a_signer', '$weblink')";
							mysqli_query($db, $sql) or die("cannot Upload into database " . mysqli_error($db));
						}

					}
				}
				fclose($handle);
				$x = $x - 1;
				$qupdate = "update project_data set `records`='".$x."',`onhold`='".$x."' where cid=".$current_id;
				mysqli_query($db,$qupdate) or die ("cannot update the record count..");
                                
                                
                                //call matching API
                                //next example will insert new conversation
                                $service_url = 'http://localhost:8081/start-scrap?proid='.$current_id.'&state='.$state;
                                $curl = curl_init($service_url);
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                $curl_response = curl_exec($curl);
                                if ($curl_response === false) {
                                    $info = curl_getinfo($curl);
                                    curl_close($curl);
                                    die('error occured during curl exec. Additioanl info: ' . var_export($info));
                                }
                                curl_close($curl);

	         //throws a message if data successfully imported to mysql database from excel file
	         $_SESSION["sadmin_changeImage_Delete"] = "Uploaded Successfully.";
		 }
	print "<META http-equiv='refresh' content=0;URL=manageuploads.php>";
}
else
{
	print "<META http-equiv='refresh' content=0;URL=index.php>";
}	 
?>		 