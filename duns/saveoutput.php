<?php
session_start();
include("includes/connection.php");
include("includes/con2.php");
include("includes/functions.php");
require("includes/funcstuffs.php");

// Page Variable Declaration
$addpage= $sitepath."manageduns.php";
$urlsite = $_SESSION["producturl"];
$msgadd = "Data Added Successfully";
$msgupdate = "Data Updated Successfully";
$msgerr = "Data Not Submitted";
$id = $_POST['id'];
$tabname ="OUTPUT";

// Check Login Session
if($_SESSION["sadmin_username"]!="")
{


	date_default_timezone_set('Asia/Kolkata');
	$today=date("Y-m-d H:i:s");

			for($i=1;$i<=$_POST["dloop"];$i++)
			{
				$id = $_POST["del_".$i];
				if($_POST["del_".$i] != "")
				{
					$qry = "select INPUT_ID from OUTPUT where id='".$id."'";
					$srs = mysqli_query($db2,$qry) or die("cannot select Records".mysqli_error($db2));
					$fr = mysqli_fetch_array($srs);
					
					$qry1 = "select * from OUTPUT where INPUT_ID='".$fr["INPUT_ID"]."'";
					$srs1 = mysqli_query($db2,$qry1) or die("cannot select Records".mysqli_error($db2));
					while($row=mysqli_fetch_array($srs1)){
						if($id == $row["ID"]){
							$form_data = array(
								'is_true' => 1
							);
						}else{
							$form_data = array(
								'is_true' => 0
							);
						}

					dbRowUpdate($db2,$tabname,$form_data,"where id=".$row["ID"]);
					}
					
					$form_data = array(
					'status'=>"COMPLETE"
					);
					dbRowUpdate($db2,"INPUT",$form_data,"where id=".$fr["INPUT_ID"]);
				}

			}	
		
	
		$_SESSION["sadmin_changeImage_Delete"]=$msgupdate;

		print "<META http-equiv='refresh' content=0;URL='".$urlsite."'>";
		exit;
}
else
{
	print "<META http-equiv='refresh' content=0;URL=".$sitepath.">";
}
?>