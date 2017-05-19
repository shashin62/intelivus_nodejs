<?php
session_start();
include("includes/connection.php");
include("includes/functions.php");

if($_SESSION["sadmin_username"]!="")
{
	date_default_timezone_set('Asia/Kolkata');
	$today=date("Y-m-d H:i:s");
	$cdate = date("Y-m-d");
	$oldpass = sha1(mysqli_real_escape_string($db,$_POST["oldpass"]));
	$newpass = sha1(mysqli_real_escape_string($db,$_POST["newpass"]));
	if($user_type_admin == "Main Rights")
	{
		$sql="select * from user_info where user_id='".mysqli_real_escape_string($db,$_SESSION["sadmin_id"])."' and user_password='".$oldpass."'";
			$rs=mysqli_query($db,$sql)or die("cannot select ".mysqli_error($db));
			if(mysqli_num_rows($rs)>0)
			{

				$newquery = "select * from passmain where usertype='Main Right' and userid='".mysqli_real_escape_string($db,$_SESSION["sadmin_id"])."' and DATE_FORMAT(cdtime, '%Y-%m-%d')='".$cdate."' limit 10";
				$newresult = mysqli_query($db,$newquery) or die("cannot Select".mysqli_error($db));
				$numrows = mysqli_num_rows($newresult);

				if($numrows > 7){
					$_SESSION["sadmin_changeImage_Delete"]="Sorry You had changed the Password to a Maximum of 8 times in a day. Please Try again Tomorrow to change the Password";
					print "<META http-equiv='refresh' content=0;URL=editprofile.php>";
					exit;
				}

				$nq = "select * from passmain where usertype='Main Right' and userid='".mysqli_real_escape_string($db,$_SESSION["sadmin_id"])."' limit 12";
				$nrs = mysqli_query($db,$nq) or die("cannot select Sub User".mysqli_error($db));
				$nrnum_rows=mysqli_num_rows($nrs);
				$existing = 0;
				while($ros=mysqli_fetch_array($nrs)){
					if($newpass == $ros["userpass"]){
						$existing = 1;
					}
				}

				if($existing == 1){
					$_SESSION["sadmin_changeImage_Delete"]="Sorry the New Password You Enter is similar to Your Previous Password Enter a New Unique Password";
					print "<META http-equiv='refresh' content=0;URL=editprofile.php>";
				}else{

					if($nrnum_rows > 11){
						$query = "delete from passmain where usertype='Main Right' and userid='".mysqli_real_escape_string($db,$_SESSION["sadmin_id"])."' limit 1";
						mysqli_query($db,$query);
					}
					$rsw=mysqli_fetch_array($rs);
					$sql="update user_info set user_password='".$newpass."' where user_id='".mysqli_real_escape_string($db,$_SESSION["sadmin_id"])."' and user_password='".$oldpass."'";
					mysqli_query($db,$sql)or die("cannot select ".mysqli_error($db));

					$query = "INSERT INTO `passmain` (`userid`, `usertype`, `userpass`, `cdtime`) VALUES ('".mysqli_real_escape_string($db,$_SESSION["sadmin_id"])."','Main Right','".$newpass."','".$today."')";
					mysqli_query($db,$query)or die("cannot select ".mysqli_error($db));

					$_SESSION["sadmin_changeImage_Delete"]="Old Password changed successfully.";
					$_SESSION["sadmin_changePass_Type"]="1";
					print "<META http-equiv='refresh' content=0;URL=editprofile.php>";
				}
			}
			else
			{
				$_SESSION["sadmin_changeImage_Delete"]="Old Password doesn't match.";
				$_SESSION["sadmin_changePass_Type"]="0";
				print "<META http-equiv='refresh' content=0;URL=editprofile.php>";
			}
	exit;
	}
	else
	{

			$sql="select user_id from teammembers where user_id='".mysqli_real_escape_string($db,$_SESSION["sadmin_id"])."' and user_password='".$oldpass."' and sub_id='".mysqli_real_escape_string($db,$_SESSION["sadmin_subid"])."'";
			$rs=mysqli_query($db,$sql)or die("cannot select ".mysqli_error($db));
			if(mysqli_num_rows($rs)>0)
			{
				$newquery = "select * from passmain where usertype='Sub Right' and userid='".mysqli_real_escape_string($db,$_SESSION["sadmin_subid"])."' and DATE_FORMAT(cdtime, '%Y-%m-%d')='".$cdate."' limit 10";
				$newresult = mysqli_query($db,$newquery) or die("cannot Select".mysqli_error($db));
				$numrows = mysqli_num_rows($newresult);

				if($numrows > 7){
					$_SESSION["sadmin_changeImage_Delete"]="Sorry You had changed the Password to a Maximum of 8 times in a day. Please Try again Tomorrow to change the Password";
					print "<META http-equiv='refresh' content=0;URL=editprofile.php>";
					exit;
				}
				$nq = "select * from passmain where usertype='Sub Right' and userid='".mysqli_real_escape_string($db,$_SESSION["sadmin_subid"])."' limit 12";
				$nrs = mysqli_query($db,$nq) or die("cannot select Sub User".mysqli_error($db));
				$nrnum_rows=mysqli_num_rows($nrs);
				$existing = 0;
				while($ros=mysqli_fetch_array($nrs)){
					if($newpass == $ros["userpass"]){
						$existing = 1;
					}
				}

				if($existing == 1){
					$_SESSION["sadmin_changeImage_Delete"]="Sorry the New Password You Enter is similar to Your Previous Password Enter a New Unique Password";
					print "<META http-equiv='refresh' content=0;URL=editprofile.php>";
				}else{

					if($nrnum_rows > 11){
						$query = "delete from passmain where usertype='Sub Right' and userid='".mysqli_real_escape_string($db,$_SESSION["sadmin_subid"])."' limit 1";
						mysqli_query($db,$query);
					}

					$rsw=mysqli_fetch_array($rs);
					$sql="update teammembers set user_password='".$newpass."' where  sub_id='".mysqli_real_escape_string($db,$_SESSION["sadmin_subid"])."' and user_password='".$oldpass."'";
					mysqli_query($db,$sql)or die("cannot select ".mysqli_error($db));
					$query = "INSERT INTO `passmain` (`userid`, `usertype`, `userpass`, `cdtime`) VALUES ('".mysqli_real_escape_string($db,$_SESSION["sadmin_subid"])."','Sub Right','".$newpass."','".$today."')";
					mysqli_query($db,$query)or die("cannot select ".mysqli_error($db));

					$_SESSION["sadmin_changeImage_Delete"]="Old Password changed successfully.";
					$_SESSION["sadmin_changePass_Type"]="1";
					print "<META http-equiv='refresh' content=0;URL=editprofile.php>";
				}
			}
			else
			{
				$_SESSION["sadmin_changeImage_Delete"]="Old Password doesn't match.";
				$_SESSION["sadmin_changePass_Type"]="0";
				print "<META http-equiv='refresh' content=0;URL=editprofile.php>";
			}
	exit;
	}
}
else
{
	print "<META http-equiv='refresh' content=0;URL=index.php>";
	exit;
}
?>
