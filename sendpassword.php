<?php
session_start();
include("includes/connection.php");
include("includes/functions.php");
if($_SESSION["sadmin_username"]!="")
{
	print "<META http-equiv='refresh' content=0;URL=home.php>";
	exit;
}
if($_SESSION["6_letters_code"]!=$_POST["verify"])
{
	$_SESSION["slogin_Error"]="Invalid Verification Code";
	$_SESSION["rand_code"] = "";
	print "<META http-equiv='refresh' content=0;URL=forgetpass.php>";	
	exit;
}
else
{
	$password = generateStrongPassword();
	$secpass = sha1($password);

	$_SESSION["rand_code"] = "";
	$sql="select user_id,user_name,user_password,user_email from user_info where user_name='".StringRepair($_POST["username"])."'";
	$rs=mysqli_query($db,$sql)or die("cannot select ".mysqli_error($db));
	if(mysqli_num_rows($rs)>0)
	{
		$rsw=mysqli_fetch_array($rs);


		$sql="update user_info set user_password='".$password."' where user_id='".mysqli_real_escape_string($rsw["user_id"])."'";
		mysqli_query($db,$sql)or die("cannot select ".mysqli_error($db));

		$mailbody = '<table width="100%" border="0" cellpadding="2"><tr><td height="25" colspan="2" align="left" valign="top" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;">Dear Administrator,</td></tr><tr><td height="25" colspan="2" align="left" valign="top" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;"><strong>Your requested login details are below :<br>We Recommend You to change the password Once you are LogIn</strong></td></tr><tr><td width="14%" align="left" valign="top" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;">Login URL</td><td width="86%" align="left" valign="top" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;"><a href="'.$sitepath.'" target="_blank">'.$sitepath.'</a></td></tr><tr><td align="left" valign="top" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;">Username</td><td align="left" valign="top" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;">'.$rsw["user_name"].'</td></tr><tr><td align="left" valign="top" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;">Password</td><td align="left" valign="top" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;">'.$password.'</td></tr><tr><td colspan="2" align="left" valign="top" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;">Regards,<br>URS Technologies Group</td></tr></table>';
		$subject = "Requested password from ".$sitepath;
		$headers = "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\n";
		$headers .= "From: Site Panel \r\n";
		mail($rsw["user_email"], $subject, $mailbody, $headers);
		$_SESSION["slogin_Error"]="Login details sent successfully. <br> Please check your mail account.";
		print "<META http-equiv='refresh' content=0;URL=index.php>";
		exit;
	}
	else
	{
		$sql="select user_id,user_login,user_fname,user_password,user_email from teammembers where user_login='".StringRepair($_POST["username"])."'";
		$rs=mysqli_query($db,$sql)or die("cannot select ".mysqli_error($db));
		if(mysqli_num_rows($rs)>0)
		{
			$rsw=mysqli_fetch_array($rs);

			$sql="update teammembers set user_password='".$newpass."' where sub_id='".mysqli_real_escape_string($rsw["sub_id"])."'";
			mysqli_query($db,$sql)or die("cannot select ".mysqli_error($db));

			$mailbody = '<table width="100%" border="0" cellpadding="2"><tr><td height="25" colspan="2" align="left" valign="top" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;">Dear '.$rsw["user_fname"].',</td></tr><tr><td height="25" colspan="2" align="left" valign="top" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;"><strong>Your requested login details are below :<br> We Recommended You to change the Password Once You LogIn</strong></td></tr><tr><td width="14%" align="left" valign="top" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;">Login URL</td><td width="86%" align="left" valign="top" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;"><a href="'.$sitepath.'" target="_blank">'.$sitepath.'</a></td></tr><tr><td align="left" valign="top" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;">Username</td><td align="left" valign="top" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;">'.$rsw["user_login"].'</td></tr><tr><td align="left" valign="top" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;">Password</td><td align="left" valign="top" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;">'.$password.'</td></tr><tr><td colspan="2" align="left" valign="top" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;">Regards,<br>URS Technologies Group</td></tr></table>';
			$subject = "Requested password from ".$sitepath;
			$headers = "MIME-Version: 1.0\n";
			$headers .= "Content-type: text/html; charset=iso-8859-1\n";
			$headers .= "From: Site Panel \r\n";
			mail($rsw["user_email"], $subject, $mailbody, $headers);
			$_SESSION["slogin_Error"]="Login details sent successfully.<br> Please check your mail account.";
			print "<META http-equiv='refresh' content=0;URL=index.php>";
			exit;
		}
		else
		{
			$_SESSION["slogin_Error"] = "Sorry! No such user exist(s).";
		}
	}
	print "<META http-equiv='refresh' content=0;URL=forgetpass.php>";
	exit;
}

function generateStrongPassword($length = 9, $add_dashes = false, $available_sets = 'luds')
{
	$sets = array();
	if(strpos($available_sets, 'l') !== false)
		$sets[] = 'abcdefghjkmnpqrstuvwxyz';
	if(strpos($available_sets, 'u') !== false)
		$sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
	if(strpos($available_sets, 'd') !== false)
		$sets[] = '23456789';
	if(strpos($available_sets, 's') !== false)
		$sets[] = '!@#$%&*?';

	$all = '';
	$password = '';
	foreach($sets as $set)
	{
		$password .= $set[array_rand(str_split($set))];
		$all .= $set;
	}

	$all = str_split($all);
	for($i = 0; $i < $length - count($sets); $i++)
		$password .= $all[array_rand($all)];

	$password = str_shuffle($password);

	if(!$add_dashes)
		return $password;

	$dash_len = floor(sqrt($length));
	$dash_str = '';
	while(strlen($password) > $dash_len)
	{
		$dash_str .= substr($password, 0, $dash_len) . '-';
		$password = substr($password, $dash_len);
	}
	$dash_str .= $password;
	return $dash_str;
}

?>