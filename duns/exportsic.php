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

    $sql = "select site_name,site_home,sic2,site_links,sic4 from site_data order by id";
    $result = mysqli_query($db, $sql) or die("Selection Error " . mysqli_error($db));

    $filename="export/projectsic-".$today.'.csv';
    $fp = fopen($filename, 'w');

  /*  $list = array('aaa', 'bbb', 'ccc', 'dddd');

        fputcsv($fp, $list);*/

    while ($row = mysqli_fetch_assoc($result)) {

        fputcsv($fp, $row);
    }

    fclose($fp);

    header("Location: ".$sitepath.$filename."");
    print "<META http-equiv='refresh' content=0;URL=managesic.php>";

}else
{
    print "<META http-equiv='refresh' content=0;URL=index.php>";
}
?>