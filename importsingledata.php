<?php

session_start();
include("includes/connection.php");
include("includes/functions.php");
ini_set('max_execution_time', 6000);
ini_set('memory_limit', '-1');

//include("Excel/reader.php");
/// Check Login Session
if ($_SESSION["sadmin_username"] != "") {

    if ($page_upload_management != 1) {
        $_SESSION["sadmin_siteAccessMessage"] = $access_message;
        print "<META http-equiv='refresh' content=0;URL=home.php>";
        exit;
    }

    $id = $_POST["id"];
    $opr = $_POST["opt"];
    $serial = '';//StringRepair($_POST['serial']);
    $legal_name = StringRepair($_POST['legal_name']);
    $dba_name = StringRepair($_POST['dba_name']);
    $b_address = StringRepair($_POST['b_address']);
    $b_city = StringRepair($_POST['b_city']);
    $b_state = StringRepair($_POST['b_state']);
    $a_code = StringRepair($_POST['a_code']);
    $a_signer = '';//StringRepair($_POST['a_signer']);    
    $weblink = '';//StringRepair($_POST['weblink']);


    $act = 0;
    if ($_POST["act"] == 1) {
        $act = 1;
    }

    if ($opr == "Add") {

        
        $qupdate = "select * from project_data where cid= 14" ;
        $result = mysqli_query($db, $qupdate) or die("cannot select the record..");
        $row = mysqli_fetch_assoc($result);

        $x = $row['records']+1;
        
        if ($legal_name != "") {

            $sql = "INSERT into data (`rcal`,`proid`,`serial`, `legal_name`,`dba_name`, `b_address`, `b_city`,`b_state`, `a_code`,`a_signer`,`weblink`) values ('$x','".$row['cid']."','$serial','$legal_name','$dba_name', '$b_address', '$b_city', '$b_state', '$a_code', '$a_signer', '$weblink')";
            mysqli_query($db, $sql) or die("cannot Upload into database " . mysqli_error($db));
        }


        $qupdate = "update project_data set `records`='" . $x . "' where cid=" . $row['cid'];
        mysqli_query($db, $qupdate) or die("cannot update the record count..");


        //call matching API
        //next example will insert new conversation
        $service_url = 'http://localhost:8081/start-scrap?proid=' . $row['cid'] . '&state=' . $b_state;
        $curl = curl_init($service_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $curl_response = curl_exec($curl);
        if ($curl_response === false) {
            $info = curl_getinfo($curl);
            curl_close($curl);
            die('error occured during curl exec. Additioanl info: ' . var_export($info));
        }
        curl_close($curl);

        //throws a message if data successfully imported to mysql database from excel file
        $_SESSION["sadmin_changeImage_Delete"] = "Uploaded Successfully.";
    }
    print "<META http-equiv='refresh' content=0;URL=manageuploads.php>";
} else {
    print "<META http-equiv='refresh' content=0;URL=index.php>";
}
?>		 