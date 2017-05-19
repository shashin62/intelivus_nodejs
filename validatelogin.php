<?php
session_start();
include("includes/connection.php");
include("includes/functions.php");
$ip = "";
if (!empty($_SERVER["HTTP_CLIENT_IP"]))
{
	//check for ip from share internet
	$ip = $_SERVER["HTTP_CLIENT_IP"];
}
elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
{
	// Check for the Proxy User
	$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
}
else
{
	$ip = $_SERVER["REMOTE_ADDR"];
}

	date_default_timezone_set('Asia/Kolkata');
	$today=date("Y-m-d H:i:s");
	$cdate=date("Y-m-d");
	$random = random(12);

	$password = sha1(mysqli_real_escape_string($db,StringRepair($_POST["password"])));
	$sql="select sub_id,user_block,lastlogin from teammembers where user_login='".mysqli_real_escape_string($db,$_POST["username"])."' limit 1";
	$rs=mysqli_query($db,$sql)or die("cannot select ".mysqli_error($db));
	if(mysqli_num_rows($rs)>0)
	{
		$rsw=mysqli_fetch_array($rs);
		$lastdate = $rsw["lastlogin"];

		$seconds = strtotime($today) - strtotime($lastdate);
		$days    = floor($seconds / 86400);

		if($days > 365 ){

			$qry1="delete from teammembers where sub_id=".$rsw["sub_id"];
			mysqli_query($db,$qry1)or die("cannot update user_info".mysqli_error($db));

			$_SESSION["slogin_Error"] = "Your account had been Deleted. <br>Please contact administrator for the detail.";
			print "<META http-equiv='refresh' content=0;URL=index.php>";
			exit;
		}

		if($days > 180 ){

			$qry1="update teammembers set user_block=1 where sub_id=".$rsw["sub_id"];
			mysqli_query($db,$qry1)or die("cannot update user_info".mysqli_error($db));

			$_SESSION["slogin_Error"] = "Your account had been blocked. <br>Please contact administrator for the detail.";
			print "<META http-equiv='refresh' content=0;URL=index.php>";
			exit;
		}
		if($rsw['user_block'] == 1){
			$_SESSION["slogin_Error"] = "Your account had been blocked. <br>Please contact administrator for the detail.";
			print "<META http-equiv='refresh' content=0;URL=index.php>";
			exit;
		}
	}


	$sql="select user_id,user_name,user_city,user_email,lastlogin from user_info where user_name='".mysqli_real_escape_string($db,StringRepair($_POST["username"]))."' and user_password='".$password."'";
	$rs=mysqli_query($db,$sql)or die("cannot select ".mysqli_error($db));
	if(mysqli_num_rows($rs)>0)
	{

		$rsw=mysqli_fetch_array($rs);

		$sql="INSERT INTO login_log_master (`user_id`, `utype`, `ip_address`,`logdet`, `cdatetime`) VALUES ('".$rsw["user_id"]."','Master','".$ip."',1,'".date("Y-m-d H:i:s")."')";
		mysqli_query($db,$sql) or die("cannot insert into login_log_master ".mysqli_error($db));

		$_SESSION["sadmin_login"]=$rsw["lastlogin"];

		$qry1="update user_info set lastlogin='".$today."',user_session='".$random."' where user_id=".$rsw["user_id"];
		mysqli_query($db,$qry1)or die("cannot update user_info ".mysqli_error($db));
		$_SESSION["sadmin_username"]=mysqli_real_escape_string($db,$_POST["username"]);
		$_SESSION["sadmin_id"]=$rsw["user_id"];
		$_SESSION["sadmin_sessionid"]=$random;
		$_SESSION["sadmin_city"]=$rsw["user_city"];
		$_SESSION["sadmin_email"]=$rsw["user_email"];
		$_SESSION["sadmin_lastlogin"]=$today;
		$_SESSION["sadmin_site_rights"] = base64_encode("Main Rights");
		$_SESSION["sadmin_loginfor"]="cmscorporate_Awesome";

		print "<META http-equiv='refresh' content=0;URL=home.php>";
	}
	else {
		$sql = "select sub_id,user_id,user_email,user_fname,user_block,flog from teammembers where user_login='" . mysqli_real_escape_string($db, $_POST["username"]) . "' and user_password='" . $password . "'";
		$rs = mysqli_query($db, $sql) or die("cannot select " . mysqli_error($db));
		if (mysqli_num_rows($rs) > 0) {
			date_default_timezone_set('Asia/Kolkata');
			$today = date("Y-m-d H:i:s");
			$rsw = mysqli_fetch_array($rs);
			$_SESSION["sadmin_login"] = "";
			$sql = "select cdatetime from login_log_master where user_id='" . $rsw['sub_id'] . "' and utype='Sub User' order by cid desc";
			$resultcity = mysqli_query($db, $sql) or die("cannot select local_city " . mysqli_error($db));
			if ($rswcity = mysqli_fetch_array($resultcity)) {
				$_SESSION["sadmin_login"] = $rswcity["cdatetime"];
			}
			$_SESSION["sadmin_username"] = mysqli_real_escape_string($db, $rsw["user_fname"]);
			$_SESSION["sadmin_id"] = $rsw["user_id"];
			$_SESSION["sadmin_subid"] = $rsw["sub_id"];
			$_SESSION["sadmin_city"] = "";
			$_SESSION["sadmin_sessionid"] = $random;
			$_SESSION["sadmin_email"] = $rsw["user_email"];
			$_SESSION["sadmin_flog"]=$rsw["flog"];
			$_SESSION["sadmin_lastlogin"] = $today;
			$_SESSION["sadmin_site_rights"] = base64_encode("Sub Rights");
			$_SESSION["sadmin_loginfor"] = "cmscorporate_Awesome";

			$qry1 = "update teammembers set lastlogin='" . $today . "' , user_session='" . $random . "' where sub_id=" . $rsw["sub_id"];
			mysqli_query($db, $qry1) or die("cannot update user_info" . mysqli_error($db));

			$sql = "INSERT INTO login_log_master (`user_id`, `utype`, `ip_address`,`logdet`, `cdatetime`) VALUES ('" . $rsw["sub_id"] . "','Sub User','" . $ip . "',1,'" . date("Y-m-d H:i:s") . "')";
			mysqli_query($db, $sql) or die("cannot insert into login_log_master " . mysqli_error($db));
			print "<META http-equiv='refresh' content=0;URL=home.php>";
			exit;

		}

		// Check for Login attempts

		$sql1 = "select user_id from user_info where user_name='" . mysqli_real_escape_string($db, StringRepair($_POST["username"])) . "' limit 1";
		$rs1 = mysqli_query($db, $sql1) or die("cannot select " . mysqli_error($db));
		if (mysqli_num_rows($rs1) > 0) {
			$data = mysqli_fetch_array($rs1);

			$newquery = "select * from login_log_master where utype='Master' and user_id='" . $data["user_id"] . "' and DATE_FORMAT(cdatetime, '%Y-%m-%d')='" . $cdate . "' order by cid desc limit 6";
			$newresult = mysqli_query($db, $newquery) or die("cannot Select Log" . mysqli_error($db));
			$numrows = mysqli_num_rows($newresult);

			$attempt = 0;
			while ($rows = mysqli_fetch_array($newresult)) {
				if ($rows["logdet"] == 0) {
					$attempt = $attempt + 1;
				} else {
					$attempt = 0;
				}
			}

			$sql = "INSERT INTO login_log_master (`user_id`, `utype`, `ip_address`,`cdatetime`) VALUES ('" . $data["user_id"] . "','Master','" . $ip . "','" . date("Y-m-d H:i:s") . "')";
			mysqli_query($db, $sql) or die("cannot insert into login_log_master " . mysqli_error($db));


			if ($attempt > 6) {

				$qry1 = "update user_info set blocked=1 where user_id=" . $data["user_id"];
				mysqli_query($db, $qry1) or die("cannot update user_info" . mysqli_error($db));

				$_SESSION["slogin_Error"] = "Sorry You had Tried to a maximum of Six Times Your Account Had be Deactivated Please Contact the Administrator for More.";
				print "<META http-equiv='refresh' content=0;URL=index.php>";
				exit;
			} else {

				$_SESSION["slogin_Error"] = "Invalid User name/Password";
				print "<META http-equiv='refresh' content=0;URL=index.php>";

			}

		} else {

			$sql1 = "select sub_id,user_id from teammembers where user_login='" . mysqli_real_escape_string($db, $_POST["username"]) . "' limit 1";
			$rs1 = mysqli_query($db, $sql1) or die("cannot select " . mysqli_error($db));
			if (mysqli_num_rows($rs1) > 0) {
				$data = mysqli_fetch_array($rs1);

				$newquery = "select * from login_log_master where utype='Sub User' and user_id='" . $data["sub_id"] . "' and DATE_FORMAT(cdatetime, '%Y-%m-%d')='" . $cdate . "' order by cid desc limit 6";
				$newresult = mysqli_query($db, $newquery) or die("cannot Select" . mysqli_error($db));
				$numrows = mysqli_num_rows($newresult);
				$attempt = 0;
				while ($rows = mysqli_fetch_array($newresult)) {

					if ($rows["logdet"] == 0) {
						$attempt = $attempt + 1;
					} else {
						$attempt = 0;
					}
				}
				$sql = "INSERT INTO login_log_master (`user_id`, `utype`, `ip_address`, `cdatetime`) VALUES ('" . $data["sub_id"] . "','Sub User','" . $ip . "','" . date("Y-m-d H:i:s") . "')";
				//mysqli_query($db,$sql) or die("cannot insert into login_log_master ".mysqli_error($db));

				if ($attempt > 5) {

					$qry1 = "update teammembers set user_block=1 where sub_id=" . $data["sub_id"];
					mysqli_query($db, $qry1) or die("cannot update user_info" . mysqli_error($db));

					$_SESSION["slogin_Error"] = "Sorry You had Tried to a maximum of Six Times Your Account Had be Deactivated Please Contact the Administrator for More.";
					print "<META http-equiv='refresh' content=0;URL=index.php>";
					exit;
				} else {

					$_SESSION["slogin_Error"] = "Invalid User name/Password";
					print "<META http-equiv='refresh' content=0;URL=index.php>";

				}

			}
		}
		$_SESSION["slogin_Error"] = "Invalid User name/Password";
		print "<META http-equiv='refresh' content=0;URL=index.php>";
	}

function random($length)
{
	$result = "";
	$chars = 'BCD1FGH2JKL3MNAP4RS5TVW6XZA7EIO8Ubcd1fgh2jkl3mnap4rs5tvw6xza7eio8u9';
	for ($p = 0; $p < $length; $p++)
	{
		$result .= ($p%2) ? $chars[mt_rand(19, 23)] : $chars[mt_rand(0, 18)];
	}
	return sha1($result);
}
?>