<?php
session_start();
include("includes/connection.php");
include("includes/functions.php");
ini_set('max_execution_time', 6000);
ini_set('memory_limit', '-1');

$id = $_GET["id"];
date_default_timezone_set('Asia/Kolkata');
$today=date("Y-m-d");
//include("Excel/reader.php");
/// Check Login Session
if($_SESSION["sadmin_username"]!="") {

    if ($page_upload_management != 1) {
        $_SESSION["sadmin_siteAccessMessage"] = $access_message;
        print "<META http-equiv='refresh' content=0;URL=home.php>";
        exit;
    }

    $sql = "select serial, dba_name, legal_name, b_address, b_city, b_state, a_code, a_signer, establish, apname1, designation1, apname2, designation2, apname3, designation3, soslink, soscompany, sosaddress, apname_match, confmetric, stan_comments from data where status=3 order by cid";
    $result = mysqli_query($db, $sql) or die("Selection Error " . mysqli_error($db));

    $filename="export/project-".$today.'.csv';
    $fp = fopen($filename, 'w');

  
    while ($row = mysqli_fetch_assoc($result)) {

        fputcsv($fp, $row);
    }

    fclose($fp);

    header("Location: ".$sitepath.$filename."");
    print "<META http-equiv='refresh' content=0;URL=manageuploads.php>";

}else
{
    print "<META http-equiv='refresh' content=0;URL=index.php>";
}
?>