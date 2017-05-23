<?php
session_start();
include("includes/connection.php");
include("includes/functions.php");

if($_SESSION["sadmin_username"]!="")
{
	date_default_timezone_set('Asia/Kolkata');
	$today=date("Y-m-d H:i:s");
	$cdate = date("Y-m-d");
	$newpass = sha1(mysqli_real_escape_string($db,$_POST["newpass"]));

if($_SESSION["6_letters_code"]!=$_POST["verify"])
{
	$_SESSION["slogin_Error"]="Invalid Verification Code";
	$_SESSION["rand_code"] = "";
	print "<META http-equiv='refresh' content=0;URL=newpass.php>";
	exit;
}else {
		$sql = "select user_id from teammembers where user_id='" . mysqli_real_escape_string($db, $_SESSION["sadmin_id"]) . "' and sub_id='" . mysqli_real_escape_string($db, $_SESSION["sadmin_subid"]) . "'";
		$rs = mysqli_query($db, $sql) or die("cannot select " . mysqli_error($db));
		if (mysqli_num_rows($rs) > 0) {

			$sql = "update teammembers set user_password='" . $newpass . "' , flog=1 where  sub_id='" . mysqli_real_escape_string($db, $_SESSION["sadmin_subid"]) . "'";
			mysqli_query($db, $sql) or die("cannot select " . mysqli_error($db));
			$query = "INSERT INTO `passmain` (`userid`, `usertype`, `userpass`, `cdtime`) VALUES ('" . mysqli_real_escape_string($db, $_SESSION["sadmin_subid"]) . "','Sub Right','" . $newpass . "','" . $today . "')";
			mysqli_query($db, $query) or die("cannot select " . mysqli_error($db));

			$_SESSION["sadmin_changeImage_Delete"] = "New Password Setup successfully.";
			$_SESSION["sadmin_changePass_Type"] = "1";
			$_SESSION["sadmin_flog"] = "1";
			print "<META http-equiv='refresh' content=0;URL=home.php>";

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
