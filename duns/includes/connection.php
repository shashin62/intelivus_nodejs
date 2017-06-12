<?php
//$db_username = "duns_user";
//$db_password = "E23asdflj;ljdsf";
$db_username = "root";
$db_password = "root123";
$db_name = "duns";
$db_host = "localhost";
$sitepath="http://107.170.73.31/duns/";
$title="Business Research Portal" ;
set_time_limit(0);
ini_set('display_errors', 0); 

$db=mysqli_connect($db_host, $db_username, $db_password,$db_name);
if (!$db) {
	echo "Error: Unable to connect to MySQL." . PHP_EOL;
	echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
	echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
	exit;
}
$access_message = "Sorry! You don't have permission.";
$access_message2 = "Sorry! You don't have permission to access this section.";
if($_SESSION["sadmin_username"]!="")
{
	$user_type_admin = base64_decode($_SESSION["sadmin_site_rights"]);
	if($_SESSION["sadmin_loginfor"]=="")
	{
		$_SESSION["sadmin_username"]="";
		$_SESSION["sadmin_id"]="";
		$_SESSION["sadmin_subid"]="";
		$_SESSION["sadmin_city"]="";
		$_SESSION["sadmin_email"]="";
		$_SESSION["sadmin_login"]="";
		$_SESSION["sadmin_lastlogin"]="";
		$_SESSION["sadmin_loginfor"]="";
		$_SESSION["sadmin_site_rights"]="";
		print "<META http-equiv='refresh' content=0;URL=index.php>";
		exit;
	}
	else
	{
		if($_SESSION["sadmin_loginfor"]!="cmscorporate_vistaplus")
		{
			$_SESSION["sadmin_username"]="";
			$_SESSION["sadmin_id"]="";
			$_SESSION["sadmin_subid"]="";
			$_SESSION["sadmin_city"]="";
			$_SESSION["sadmin_email"]="";
			$_SESSION["sadmin_login"]="";
			$_SESSION["sadmin_lastlogin"]="";
			$_SESSION["sadmin_loginfor"]="";
			$_SESSION["sadmin_site_rights"]="";
			print "<META http-equiv='refresh' content=0;URL=index.php>";
			exit;
		}
	}
	$page_section_list = "";
	if($user_type_admin == "Main Rights")
	{
		$page_user_management = 1;
		$page_upload_management = 1;
		$page_data_management = 1;
		$user_qa_rights = 1;
		$bvduns_mgmt_rights = 1;
		$sic_mgmt_rights = 1;

		$sql="select user_session from `user_info` where user_id='".$_SESSION["sadmin_id"]."'";
		$result_userright=mysqli_query($db,$sql) or die("cannot select teammembers ".mysqli_error($db));
		if($row_userright=mysqli_fetch_array($result_userright)) {
			if ($row_userright["user_session"] != $_SESSION["sadmin_sessionid"]) {

				$_SESSION["slogin_Error"] = "Your account had been Logged out. <br> There might be some other device logged In";
				$_SESSION["sadmin_username"] = "";
				$_SESSION["sadmin_id"] = "";
				$_SESSION["sadmin_subid"] = "";
				$_SESSION["sadmin_city"] = "";
				$_SESSION["sadmin_sessionid"] = "";
				$_SESSION["sadmin_email"] = "";
				$_SESSION["sadmin_login"] = "";
				$_SESSION["sadmin_lastlogin"] = "";
				$_SESSION["sadmin_loginfor"] = "";
				$_SESSION["sadmin_site_rights"] = "";
				print "<META http-equiv='refresh' content=0;URL=index.php>";
				exit;

			}
		}
	}
	if($user_type_admin == "Sub Rights")
	{
		$sql="select user_block,user_session from `teammembers` where sub_id='".$_SESSION["sadmin_subid"]."'";
		$result_userright=mysqli_query($db,$sql) or die("cannot select teammembers ".mysqli_error($db));
		if($row_userright=mysqli_fetch_array($result_userright))
		{
			if($row_userright["user_session"] != $_SESSION["sadmin_sessionid"]){

				$_SESSION["slogin_Error"] = "Your account had been Logged out. <br> There might be some other device logged In";
				$_SESSION["sadmin_username"]="";
				$_SESSION["sadmin_id"]="";
				$_SESSION["sadmin_subid"]="";
				$_SESSION["sadmin_city"]="";
				$_SESSION["sadmin_sessionid"]="";
				$_SESSION["sadmin_email"]="";
				$_SESSION["sadmin_login"]="";
				$_SESSION["sadmin_lastlogin"]="";
				$_SESSION["sadmin_loginfor"]="";
				$_SESSION["sadmin_site_rights"]="";
				print "<META http-equiv='refresh' content=0;URL=index.php>";
				exit;

			}elseif($row_userright["user_block"] == 1)
			{
				$_SESSION["slogin_Error"] = "Your account had been blocked. <br> Please contact administrator for the detail.";
				$_SESSION["sadmin_username"]="";
				$_SESSION["sadmin_id"]="";
				$_SESSION["sadmin_subid"]="";
				$_SESSION["sadmin_city"]="";
				$_SESSION["sadmin_sessionid"]="";
				$_SESSION["sadmin_email"]="";
				$_SESSION["sadmin_login"]="";
				$_SESSION["sadmin_lastlogin"]="";
				$_SESSION["sadmin_loginfor"]="";
				$_SESSION["sadmin_site_rights"]="";
				print "<META http-equiv='refresh' content=0;URL=index.php>";
				exit;
			}
		}
		$page_user_management = 0;
		$page_upload_management = 0;
		$page_data_management = 0;
		$user_qa_rights = 0;
		$bvduns_mgmt_rights = 0;
		$sic_mgmt_rights = 0;
		$sql="select rid,having_right from `teammembers_rights` where tid='".$_SESSION["sadmin_subid"]."' order by rid";
		$result_userright=mysqli_query($db,$sql) or die("cannot select teammembers_rights ".mysqli_error($db));
		while($row_userright=mysqli_fetch_array($result_userright))
		{
			switch ($row_userright["rid"]) {
				case 1:
					$page_user_management = $row_userright["having_right"];
					break;
				case 2:
					$page_upload_management = $row_userright["having_right"];
					break;
				case 3:
					$page_data_management = $row_userright["having_right"];
					break;
				case 4:
					$user_qa_rights = $row_userright["having_right"];
					break;
				case 5:
					$bvduns_mgmt_rights = $row_userright["having_right"];
					break;
				case 6:
					$sic_mgmt_rights = $row_userright["having_right"];
					break;
				
			}
		}
	}
}
