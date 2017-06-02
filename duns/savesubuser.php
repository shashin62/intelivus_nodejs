<?php
session_start();
include("includes/connection.php");
include("includes/functions.php");
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
	$glogin=StringRepair($_POST["glogin"]);
	if($glogin!="")
	{
		if($_POST["opt"]=="Add")
		{
			$sql="select sub_id from teammembers where user_id=".$_SESSION["sadmin_id"]." and user_login='".$glogin."'";
		}
		elseif($_POST["opt"]=="Edit")
		{
			$result_id=preg_replace ('/[^\d]/', '', $_POST['cid']);
			if($result_id == "")
			{
				print "<META http-equiv='refresh' content=0;URL=managesubuser.php>";
				exit;
			}
			$sql="select sub_id from teammembers where user_id=".$_SESSION["sadmin_id"]." and user_login='".$glogin."' and sub_id!=".$result_id;
		}
		$rs=mysqli_query($db,$sql)or die("cannot select ".mysqli_error($db));
		if(mysqli_num_rows($rs)>0)	
		{
			if($_POST["opt"]=="Add")
			{
				$_SESSION["sadmin_changeImage_Delete"]="Sub User Already exists";
				print "<META http-equiv='refresh' content=0;URL=addsubuser.php>";
				exit;
			}
			elseif($_POST["opt"]=="Edit")
			{
				$_SESSION["sadmin_changeImage_Delete"]="Sub User Already exists";
				print "<META http-equiv='refresh' content=0;URL=addsubuser.php?id=".$result_id.">";
				exit;
			}
			else
			{
				print "<META http-equiv='refresh' content=0;URL=managesubuser.php>";
				exit;
			}
		}
		
		$gemail=StringRepair($_POST["gemail"]);
		if($_POST["opt"]=="Add")
		{
			$sql="select sub_id from teammembers where user_id=".$_SESSION["sadmin_id"]." and user_email='".$gemail."'";
		}
		elseif($_POST["opt"]=="Edit")
		{
			$result_id=preg_replace ('/[^\d]/', '', $_POST['cid']);
			if($result_id == "")
			{
				print "<META http-equiv='refresh' content=0;URL=managesubuser.php>";
				exit;
			}
			$sql="select sub_id from teammembers where user_id=".$_SESSION["sadmin_id"]." and user_email='".$gemail."' and sub_id!=".$result_id;
		}
		$rs=mysqli_query($db,$sql)or die("cannot select ".mysqli_error($db));
		if(mysqli_num_rows($rs)>0)	
		{
			if($_POST["opt"]=="Add")
			{
				$_SESSION["sadmin_changeImage_Delete"]="Email Address Already used.";
				print "<META http-equiv='refresh' content=0;URL=addsubuser.php>";
				exit;
			}
			elseif($_POST["opt"]=="Edit")
			{
				$_SESSION["sadmin_changeImage_Delete"]="Email Address Already used.";
				print "<META http-equiv='refresh' content=0;URL=addsubuser.php?id=".$result_id.">";
				exit;
			}
			else
			{
				print "<META http-equiv='refresh' content=0;URL=managesubuser.php>";
				exit;
			}
		}
		$password = StringRepair($_POST["gpassword"]);
		$gpassword=sha1(StringRepair($_POST["gpassword"]));
		$gfname=StringRepair($_POST["gfname"]);
		$glname=StringRepair($_POST["glname"]);
		$gemail=StringRepair($_POST["gemail"]);
		$gecode=StringRepair($_POST["ecode"]);
		$cadd=StringRepair($_POST["cadd"]);
		$gzipcode=StringRepair($_POST["gzipcode"]);
		$gphone=StringRepair($_POST["gphone"]);
		$gsections=StringRepair($_POST["gsections"]);
		$user_image="albums/profile_3162_profile8118administratoricon.png";
		$bu = 0;
		if($_POST["block"] == 1)
		{
			$bu = 1;
		}
		
		$sp = 0;
		if($_POST["csend"] == 1)
		{
			$sp = 1;
		}
		
		if($_POST["opt"]=="Add")
		{
			$qry="INSERT INTO `teammembers` (`user_id` , `user_email` , `user_login` ,`user_password`,`user_fname`,`user_lname`,`user_add`,`empcode`,`zipcode`,`user_phone`,`assign_section`,`user_block`, `user_date`,`user_image`,`lastlogin`)";
			$qry.=" VALUES ('".$_SESSION["sadmin_id"]."','".$gemail."','".$glogin."','".$gpassword."','".$gfname."','".$glname."','".$cadd."','".$gecode."','".$gzipcode."','".$gphone."','".$gsections."','".$bu."','".$today."','".$user_image."','".$today."')";
			mysqli_query($db,$qry)or die("cannot insert into teammembers ".mysqli_error($db)." ".$qry);
			$sub_id=mysql_insert_id();
			
			for($i=1;$i<=$_POST["ploop"];$i++)
			{
				$ncode=preg_replace ('/[^\d]/', '', $_POST['rid'.$i]);
				if($ncode != "")
				{
					$rv=0;
					if($_POST["selright".$i]!="")
					{
						$rv=1;
					}
					$sql = "INSERT INTO `teammembers_rights` (`tid`, `rid`, `having_right`, `right_date`) VALUES ('".$sub_id."','".$ncode."','".$rv."','".$today."')";
					mysqli_query($db,$sql)or die("cannot insert teammembers_rights ".mysqli_error($db)." ".$sql);
				}
			}
			
			$bd = '<table border="0" cellpadding="0" cellspacing="0" style=border-collapse: collapse bordercolor=#111111 width="100%">';
			$bd .= '<tr>';
			$bd .= '<td width="100%" colspan="2" align="left"><font face="verdana" size="2" >Dear '.$gfname.'</font>  </td>';
			$bd .= '</tr>';
			$bd .= '<tr>';
			$bd .= '<td width="100%" colspan="2" align="left">&nbsp;</td>';
			$bd .= '</tr>';
			$bd .= '<tr>';
			$bd .= '<td width="100%" colspan="2" align="left"><font face="verdana" size="2" ><strong>Your Account details are below</strong></font></td>';
			$bd .= '</tr>';
			$bd .= '<tr>';
			$bd .= '<td width="100%" colspan="2" align="left"><font face="verdana" size="2" ><strong>Control Panel :</strong> <a href="'.$sitepath.'" target="_blank">'.$sitepath.'</a></td>';
			$bd .= '</tr>';
			$bd .= '<tr>';
			$bd .= '<td width="100%" colspan="2" align="left"><font face="verdana" size="2" ><b>Login ID :&nbsp;</b>'.$glogin.'</font></td>';
			$bd .= '</tr>';
			$bd .= '<tr>';
			$bd .= '<td width="100%" colspan="2" align="left"><font face="verdana" size="2" ><b>Password &nbsp;:&nbsp;</b>'.$password.'</font></td>';
			$bd .= '</tr>';
			$bd .= '<tr>';
			$bd .= '<td width="100%" colspan="2" align="left">&nbsp;</td>';
			$bd .= '</tr>';
			$bd .= '</table>';
			
			$subject = "Login Details From URS Techonologies Solution";
			$headers = "MIME-Version: 1.0\n";
			$headers .= "Content-type: text/html; charset=iso-8859-1\n";
			$headers .= "From: Site Panel \r\n";
			@mail($gemail, $subject, $bd, $headers);
			
			$_SESSION["sadmin_changeHeader_Delete"]="Sub User Added Successfully.";
		}
		elseif($_POST["opt"]=="Edit")
		{
			$result_id=preg_replace ('/[^\d]/', '', $_POST['cid']);
			if($result_id == "")
			{
				print "<META http-equiv='refresh' content=0;URL=managesubuser.php>";
				exit;
			}
			$sub_id=$result_id;

			if($gpassword != ""){
				$qrv = ",user_password='".$gpassword."'";
			}

			$qry="update `teammembers` set user_email='".$gemail."',user_login='".$glogin."'".$qrv.",user_fname='".$gfname."',user_lname='".$glname."',empcode='".$gecode."',user_add='".$cadd."',zipcode='".$gzipcode."',user_phone='".$gphone."',user_mobile='".$gmobile."',user_fax='".$gfax."',assign_section='".$gsections."',user_block='".$bu."' where user_id=".$_SESSION["sadmin_id"]." and sub_id=".$result_id;
			mysqli_query($db,$qry)or die("cannot update teammembers ".mysqli_error($db)." ".$qry);
			
			for($i=1;$i<=$_POST["ploop"];$i++)
			{
				$ncode=preg_replace ('/[^\d]/', '', $_POST['rid'.$i]);
				if($ncode != "")
				{
					$rv=0;
					if($_POST["selright".$i]!="")
					{
						$rv=1;
					}
					$sql="select cid from `teammembers_rights` where tid='".$sub_id."' and rid='".$ncode."'";
					$result5=mysqli_query($db,$sql) or die("cannot select teammembers_rights ".mysqli_error($db));
					if($row5=mysqli_fetch_array($result5))
					{
						$sql = "update `teammembers_rights` set `having_right`='".$rv."' where cid='".$row5["cid"]."'";
						mysqli_query($db,$sql)or die("cannot update teammembers_rights ".mysqli_error($db)." ".$sql);
					}
					else
					{
						$sql = "INSERT INTO `teammembers_rights` (`tid`, `rid`, `having_right`, `right_date`) VALUES ('".$sub_id."','".$ncode."','".$rv."','".$today."')";
						mysqli_query($db,$sql)or die("cannot insert teammembers_rights ".mysqli_error($db)." ".$sql);
					}
				}
			}

			$bd = '<table border="0" cellpadding="0" cellspacing="0" style=border-collapse: collapse bordercolor=#111111 width="100%">';
			$bd .= '<tr>';
			$bd .= '<td width="100%" colspan="2" align="left"><font face="verdana" size="2" >Dear '.$gfname.'</font>  </td>';
			$bd .= '</tr>';
			$bd .= '<tr>';
			$bd .= '<td width="100%" colspan="2" align="left">&nbsp;</td>';
			$bd .= '</tr>';
			$bd .= '<tr>';
			$bd .= '<td width="100%" colspan="2" align="left"><font face="verdana" size="2" ><strong>Your Account details are Updated as  below</strong></font></td>';
			$bd .= '</tr>';
			$bd .= '<tr>';
			$bd .= '<td width="100%" colspan="2" align="left"><font face="verdana" size="2" ><strong>Control Panel :</strong> <a href="'.$sitepath.'" target="_blank">'.$sitepath.'</a></td>';
			$bd .= '</tr>';
			$bd .= '<tr>';
			$bd .= '<td width="100%" colspan="2" align="left"><font face="verdana" size="2" ><b>Login ID :&nbsp;</b>'.$glogin.'</font></td>';
			$bd .= '</tr>';
			$bd .= '<tr>';
			$bd .= '<td width="100%" colspan="2" align="left"><font face="verdana" size="2" ><b>Password &nbsp;:&nbsp;</b>'.$password.'</font></td>';
			$bd .= '</tr>';
			$bd .= '<tr>';
			$bd .= '<td width="100%" colspan="2" align="left">&nbsp;</td>';
			$bd .= '</tr>';
			$bd .= '</table>';

			$subject = "Updated Login Details From URS Techonologies Solution";
			$headers = "MIME-Version: 1.0\n";
			$headers .= "Content-type: text/html; charset=iso-8859-1\n";
			$headers .= "From: Site Panel \r\n";
			@mail($gemail, $subject, $bd, $headers);


			$_SESSION["sadmin_changeImage_Delete"]="Sub User Updated Successfully.";
		}
		
		if($_POST["page"]=="")
		{	
			$page=1;
		}
		else
		{
			$page=ereg_replace( '[^0-9]+', '', $_POST["page"] );
		}
		if($page == "")
		{
			$page = 1;
		}
	}	
	print "<META http-equiv='refresh' content=0;URL=managesubuser.php?page=".$page.">";
}
else
{
	print "<META http-equiv='refresh' content=0;URL=index.php>";
}
?>		
		