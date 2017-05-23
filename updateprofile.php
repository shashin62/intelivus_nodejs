<?php
session_start();
include("includes/connection.php");
include("includes/functions.php");
if($_SESSION["sadmin_username"]!="")
{
	
	if($user_type_admin == "Main Rights")
	{
		$qry1="update user_info set user_fname='".StringRepair($_POST["fname"])."',user_lname='".StringRepair($_POST["lname"])."',user_image='".$newname_90."',user_add='".StringRepair($_POST["address"])."',user_pin='".StringRepair($_POST["pinno"])."',user_phone='".StringRepair($_POST["phone"])."' where user_id=".mysqli_real_escape_string($db,$_SESSION["sadmin_id"]);
		mysqli_query($db,$qry1)or die("cannot update teammembers".mysqli_error($db));
		
		$_SESSION["sadmin_changeImage_Delete"]="Profile Updated successfully.";
		$_SESSION["sadmin_changePass_Type"]="1";
		print "<META http-equiv='refresh' content=0;URL=editprofile.php>";
		exit;
	}
	if($_POST["fname"]!="")
	{	
		$qry1="update teammembers set user_fname='".StringRepair($_POST["fname"])."',user_lname='".StringRepair($_POST["lname"])."',user_image='".$newname_90."',user_add='".StringRepair($_POST["address"])."',zipcode='".StringRepair($_POST["pinno"])."',user_phone='".StringRepair($_POST["phone"])."' where user_id='".mysqli_real_escape_string($db,$_SESSION["sadmin_id"])."' and sub_id='".mysqli_real_escape_string($db,$_SESSION["sadmin_subid"])."'";
		mysqli_query($db,$qry1)or die("cannot update teammembers".mysqli_error($db));
		
		$_SESSION["sadmin_changeImage_Delete"]="Profile Updated successfully.";
		$_SESSION["sadmin_changePass_Type"]="1";
		print "<META http-equiv='refresh' content=0;URL=editprofile.php>";
		exit;
	}
	else
	{
		print "<META http-equiv='refresh' content=0;URL=editprofile.php>";
		exit;
	}
	
}
else
{
	print "<META http-equiv='refresh' content=0;URL=index.php>";
	exit;
}
?>