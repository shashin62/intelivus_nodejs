<?php
session_start();
include("includes/connection.php");
include("includes/functions.php");
include("includes/funcstuffs.php");
include("includes/checkmainaccess.php");
/// Check Login Session
if($_SESSION["sadmin_username"]!="")
{
	if($page_user_management != 1)
	{
		$_SESSION["sadmin_siteAccessMessage"] = $access_message;
		print "<META http-equiv='refresh' content=0;URL=home.php>";
		exit;
	}
	
	$today=date("Y-m-d H:i:s");
	$proid=StringRepair($_POST["proid"]);
	if($proid!="")
	{



		$proid=StringRepair($_POST["proid"]);
		$userid=StringRepair($_POST["userid"]);
		$rstart=StringRepair($_POST["rstart"]);
		$rend=StringRepair($_POST["rend"]);
		$act = 0;
		if($_POST["act"] == 1)
		{
			$act = 1;
		}

		$itot = $rend - $rstart + 1;

		if($_POST["opt"]=="Add")
		{
			$form_data = array('proid' => $proid,'userid' => $userid,'rstart' => $rstart,'rend' => $rend,'tot' => $itot );
			dbRowInsert($db,"allocat",$form_data);
			$current_id = mysqli_insert_id($db);
			
			$sql = "update data  set allocid=".$current_id." where proid=".$proid." and rcal >= ".$rstart." and rcal <= ".$rend." order by cid";
			mysqli_query($db,$sql) or die("Cannot update Records ".mysqli_error($db));
			$_SESSION["sadmin_changeHeader_Delete"]="Allocation Added Successfully.";
		}
		elseif($_POST["opt"]=="Edit")
		{

			$id=StringRepair($_POST["cid"]);
			$current_id = $id;
			
			$form_data = array('proid' => $proid,'userid' => $userid,'rstart' => $rstart,'rend' => $rend,'tot' => $itot,'activate' => $act );
			dbRowUpdate($db,"allocat",$form_data,"where id=".$id);
			
			$sql = "update data  set allocid=".$current_id." where proid=".$proid." and rcal>=".$rstart." and rcal <= ".$rend." order by cid";
			mysqli_query($db,$sql) or die("Cannot update Records ".mysqli_error($db));

			$_SESSION["sadmin_changeImage_Delete"]="Allocation Updated Successfully.";
		}
		

	}	
	print "<META http-equiv='refresh' content=0;URL=manageallocation.php>";
}
else
{
	print "<META http-equiv='refresh' content=0;URL=index.php>";
}
?>		
		