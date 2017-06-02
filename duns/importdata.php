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
				$sql = "update project_data set `proname`='".$proname."',`company`='".$company."',`comp_address`='".$address."' where cid=".$id;
					 //we are using mysql_query function. it returns a resource on true else False on error
					  mysqli_query($db,$sql)or die("cannot Update into project_data ".mysqli_error($db));

					  $current_id = $id;
	}

		 if($_FILES["flname1"]["size"] > 0)
		 {

			// If you need to parse csv files

			 $csv_file = $newname_30;

			$x=0;

			if (($handle = fopen($csv_file, "r")) !== FALSE) {
				fgetcsv($handle);
				while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
					$x++;
					$num = count($data);
					for ($c = 0; $c < $num; $c++) {
						$Row[$c] = $data[$c];
					}
					
					if ($x < 1){} else {
						$serial = StringRepair($Row[0]);
						$jun_dun = StringRepair($Row[1]);
						$may_dun = StringRepair($Row[2]);
						$legal_name = StringRepair($Row[3]);
						$dba_name = StringRepair($Row[4]);
						$b_address = StringRepair($Row[6]);
						$b_city = StringRepair($Row[8]);
						$b_state = StringRepair($Row[9]);
						$a_code = StringRepair($Row[10]);
						$a_details = StringRepair($Row[11]);
						$final_duns = StringRepair($Row[12]);
						$comments = StringRepair($Row[13]);
						$weblinks = StringRepair($Row[14]);
						$website = "";
						$dun_month = StringRepair($Row[15]);
						$location = StringRepair($Row[16]);
						$company = StringRepair($Row[17]);
						$address = StringRepair($Row[18]);
						$contact = StringRepair($Row[19]);
						$dunsasper = StringRepair($Row[20]);
						$headquarter = StringRepair($Row[21]);

						if ($jun_dun != "") {
							//It wiil insert a row to our subject table from our csv file`
							$sql = "INSERT into orgdata (`proid`,`serial`, `jun_dun`, `may_dun`, `legal_name`,`dba_name`, `b_address`, `b_city`,`b_state`, `a_code`,`a_details`, `final_duns`, `comments`, `weblinks`, `website`,`duns_month`,`location`,`company`,`address`,`tel_no`,`duns_as_qa`,`headquarters`) values ('$current_id','$serial','$jun_dun','$may_dun','$legal_name','$dba_name', '$b_address', '$b_city', '$b_state', '$a_code', '$a_details', '$final_duns', '$comments','$weblinks','$website','$dun_month','$location','$company','$address','$contact','$dunsasper','$headquarter')";
							//we are using mysql_query function. it returns a resource on true else False on error
							mysqli_query($db, $sql) or die("cannot Upload into database " . mysqli_error($db));

							//It wiil insert a row to our subject table from our csv file`
							$sql = "INSERT into data (`proid`,`serial`, `jun_dun`, `may_dun`, `legal_name`,`dba_name`, `b_address`, `b_city`,`b_state`, `a_code`,`a_details`, `final_duns`, `comments`, `weblinks`, `website`,`duns_month`,`location`,`company`,`address`,`tel_no`,`duns_as_qa`,`headquarters`) values ('$current_id','$serial','$jun_dun','$may_dun','$legal_name','$dba_name', '$b_address', '$b_city', '$b_state', '$a_code', '$a_details', '$final_duns', '$comments','$weblinks','$website','$dun_month','$location','$company','$address','$contact','$dunsasper','$headquarter')";
							//we are using mysql_query function. it returns a resource on true else False on error
							mysqli_query($db, $sql) or die("cannot Upload into database " . mysqli_error($db));
						}

					}
				}
			}
				fclose($handle);

				$qupdate = "update project_data set `records`='".$x."',`onhold`='".$x."' where cid=".$current_id;
				mysqli_query($db,$qupdate) or die ("cannot update the record count..");

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