<?php
session_start();
include("includes/connection.php");

$usertype = base64_decode($_SESSION["sadmin_site_rights"]);
if($usertype == "Main Rights"){
    $qry1="update user_info set user_session='' where user_id=".$_SESSION["sadmin_id"];
    mysqli_query($db,$qry1)or die("cannot update user_info ".mysqli_error($db));
}elseif($usertype == "Sub Rights"){
    $qry1="update teammembers set  user_session='' where sub_id=".$_SESSION["sadmin_subid"];
    mysqli_query($db,$qry1)or die("cannot update sub user_info".mysqli_error($db));
}

$_SESSION["sadmin_username"]="";
$_SESSION["sadmin_id"]="";
$_SESSION["sadmin_subid"]="";
$_SESSION["sadmin_sessionid"]="";
$_SESSION["sadmin_city"]="";
$_SESSION["sadmin_email"]="";
$_SESSION["sadmin_login"]="";
$_SESSION["sadmin_loginfor"]="";
$_SESSION["sadmin_site_rights"]="";
print "<META http-equiv='refresh' content=0;URL=index.php>";
?>