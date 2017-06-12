<?php
session_start();
include("includes/connection.php");
include("includes/functions.php");
/// Check Login Session

if($_SESSION["sadmin_username"]!="")
{
	if($_POST["id"]!="")
	{
		$def=0;
		if($_POST["dmeta"]!="")
		{
			$def = 1;
		}
		$qry1="Update `content` set `page_name`='".StringRepair($_POST["pname"])."',`page_linkname`='".StringRepair($_POST["plname"])."',`page_title`='".StringRepair($_POST["ptitle"])."',`page_innerhead`='".StringRepair($_POST["phead"])."',`metakey`='".StringRepair($_POST["ckey"])."',`metakeyphrases`='".StringRepair($_POST["cphrases"])."',`metadesc`='".StringRepair($_POST["cmeta"])."',`metadef`=".$def.",`ctext`='".StringRepair($_POST["ctext"])."',`ctext2`='".StringRepair($_POST["ctext2"])."' where cid=".$_POST["id"];

		$_SESSION["sadmin_changeImage_Delete"] = "Page Updated Successfully.";
		mysqli_query($db,$qry1)or die("cannot Update into content ".mysqli_error($db));
		print "<META http-equiv='refresh' content='0;URL=managepages.php'>";
		exit;
	}
	else
	{
		print "<META http-equiv='refresh' content=0;URL=addpage.php>";
		exit;
	}
}
else
{
	print "<META http-equiv='refresh' content=0;URL=index.php>";
}
?>