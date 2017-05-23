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
	
	$id = $_POST["proid"];
	
	$sql = "delete from data where proid=".$id;
	$rs = mysqli_query($db,$sql) or die("couldn't clear Records".mysqli_error($db));

	$sql = "delete from orgdata where proid=".$id;
	$rs = mysqli_query($db,$sql) or die("couldn't clear Records".mysqli_error($db));
	
				$count = mysqli_affected_rows($rs);
				
				
				 $qupdate = "update project_data set `records`='0',`rsave`='0',`complete`='0',`onhold`='0',`submitqa`='0' where cid=".$id;
				mysqli_query($db,$qupdate) or die ("cannot update the record count..");
	
				 $qupdate = "update teammembers set `rsave`='0',`rqacount`='0',`qareject`='0' where flog=1";
				mysqli_query($db,$qupdate) or die ("cannot update the record count..");
	
				
	         //throws a message if data successfully imported to mysql database from excel file
	         $_SESSION["sadmin_changeImage_Delete"] = $count." Records Cleared Successfully.";
			 
	print "<META http-equiv='refresh' content=0;URL=manageuploads.php>";
}
else
{
	print "<META http-equiv='refresh' content=0;URL=index.php>";
}	 
?>		 