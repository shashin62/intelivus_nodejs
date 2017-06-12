<?php
session_start();
include("includes/connection.php");
include("includes/functions.php");
require("includes/funcstuffs.php");

// Page Variable Declaration
$url = "home.php";
$msg = "Data Reset Successfully";
$userid = $_SESSION['sadmin_subid'];
$tabname ="resetdata";

// Check Login Session
if($_SESSION["sadmin_username"]!="")
{


	if($page_data_management != 1)
	{
		$_SESSION["sadmin_siteAccessMessage"] = $access_message;
		print "<META http-equiv='refresh' content=0;URL=home.php>";
		exit;
	}
	date_default_timezone_set('Asia/Kolkata');
	$today=date("Y-m-d H:i:s");
	
	if($userid!="")
	{	
				$qy = "select rsave,rqacount from teammembers where sub_id='".$userid."'";
				$rs = mysqli_query($db,$qy) or die("Cannot select teammembers save");
				$row = mysqli_fetch_array($rs);
		
				$form_data = array('userid' => $userid,'save' => $row["rsave"],'qacount' => $row["rqacount"],'cdtime' => $today);
				dbRowInsert($tabname,$form_data);
				
				$form_data = array('rqacount' => "0");
				dbRowUpdate("teammembers",$form_data,"where sub_id=".$userid);
				
				$_SESSION["sadmin_changeImage_Delete"]=$msgupdate;

	}
	
	print "<META http-equiv='refresh' content=0;URL='".$url."'>";
	exit;
	
}
else
{
	print "<META http-equiv='refresh' content=0;URL=".$sitepath.">";
}
?>