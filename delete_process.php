<?php
session_start();
include("includes/connection.php");
include("includes/functions.php");
require("includes/paging.php");
/// Check Login Session
if($_SESSION["sadmin_username"]!="")
{
	if($_POST["deleteKey"]!="")
	{
		$delkey=preg_split("/\//",base64_decode($_POST["deleteKey"]));
		if($_POST["page"]=="")
		{	
			$page=1;
		}
		else
		{
			$page=preg_replace ('/[^\d]/', '', $_POST["page"]);
		}
		if($page == "")
		{
			$page = 1;
		}
		if($_POST["perpage"]=="")
		{	
			$perpage=1;
		}
		else
		{
			$perpage=preg_replace ('/[^\d]/', '', $_POST['perpage']);
		}
		if($delkey[1]=="deleteSubuser")
		{
			for($i=1;$i<=$_POST["dloop"];$i++)
			{
				if($_POST["del".$i] != "")
				{
					$qry="select sub_id,user_id,user_email from teammembers where sub_id=" .$_POST["del".$i];
					$result=mysqli_query($db,$qry) or die("cannot select teammembers ".mysqli_error($db));
					while($row=mysqli_fetch_array($result))
					{
						$sql="delete from teammembers where sub_id=" .$row["sub_id"];
						mysqli_query($db,$sql) or die(mysqli_error($db)."<br>".$sql);
					}
				}
			}
			$qy = "select sub_id from teammembers where user_id='".$_SESSION["sadmin_id"]."' order by sub_id desc";
			$rs=mysqli_query($db,$qy) or die(mysqli_error($db)."<br>".$qy);
			$num_rows = mysqli_num_rows($rs);
			$page = pager_delete($num_rows,$perpage,$page);
			$_SESSION["sadmin_changeImage_Delete"]="User(s) Deleted Successfully.";	
			print "<META http-equiv='refresh' content=0;URL=managesubuser.php?page=".$page.">";
			exit;
		}
		if($delkey[1]=="deleteAllocation")
		{
		
			for($i=1;$i<=$_POST["dloop"];$i++)
			{
				if($_POST["del".$i] != "")
				{
					$qry="select * from allocat where id=" .$_POST["del".$i];
					$result=mysqli_query($db,$qry) or die("cannot select Allocation ".mysqli_error($db));
					while($row=mysqli_fetch_array($result))
					{
						$sql="delete from allocat where id=" .$row["id"];
						mysqli_query($db,$sql) or die(mysqli_error($db)."<br>".$sql);
					}
				}
			}
			$qy = "select id from allocat  order by id desc";
			$rs=mysqli_query($db,$qy) or die(mysqli_error($db)."<br>".$qy);
			$num_rows = mysqli_num_rows($rs);
			$page = pager_delete($num_rows,$perpage,$page);
			$_SESSION["sadmin_changeImage_Delete"]="Allocation(s) Deleted Successfully.";	
			print "<META http-equiv='refresh' content=0;URL=manageallocation.php?page=".$page.">";
			exit;
		}
		
	}
	else
	{
		print "<META http-equiv='refresh' content=0;URL=home.php>";
		exit;
	}
}
else
{
	print "<META http-equiv='refresh' content=0;URL=index.php>";
}