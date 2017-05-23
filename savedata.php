<?php
session_start();
include("includes/connection.php");
include("includes/functions.php");
require("includes/funcstuffs.php");

// Page Variable Declaration
$addpage= $sitepath."updatedata.php";
$url = $_SESSION["producturl"];
$msgcomp = "Data Completed Successfully";
$msgsave = "Data Saved Successfully";
$msgqa = "Data Submitted to QA Successfully";
$msgreject = "Data Rejected Successfully";
$id = $_POST['id'];
$tabname ="data";

// Check Login Session
if($_SESSION["sadmin_username"]!="")
{


	if($page_data_management != 1)
	{
		$_SESSION["sadmin_siteAccessMessage"] = $access_message;
		print "<META http-equiv='refresh' content=0;URL=home.php>";
		exit;
	}
	date_default_timezone_set('Asia/Kolkata');
	$today=date("Y-m-d H:i:s");
	
	$proid=StringRepair($_POST["proid"]);
	$allocid=StringRepair($_POST["allocid"]);
	$rtype=StringRepair($_POST["rtype"]);
	$cname=StringRepair($_POST["serial"]);
	if($cname!="")
	{	
		$status = 0;
		if (isset($_POST['save'])) {
			$status = 1;
			$user = "userid";
			$userid = $_SESSION["sadmin_subid"];
			$msg = $msgsave;

			$arcolname = "rsave";
			$prcolname = "rsave";
			$trcolname = "rsave";

		}elseif(isset($_POST['submitqa'])){
			$status = 2;
			$user = "userid";
			$userid = $_SESSION["sadmin_subid"];
			$msg = $msgqa;

			$arcolname = "rqacount";
			$prcolname = "submitqa";
			$trcolname = "rqacount";

		}elseif(isset($_POST['complete'])){
			$status = 3;
			$user = "adminid";
			$userid = $_SESSION["sadmin_id"];
			$msg = $msgcomp;

			$arcolname = "complete";
			$prcolname = "complete";
			$trcolname = "complete";

		}elseif(isset($_POST['reject'])){
			$status = 4;
			$user = "adminid";
			$userid = $_SESSION["sadmin_id"];
			$subid = $_POST["wid"];
			$msg = $msgreject;

			$arcolname = "qareject";
			$prcolname = "reject";
			$trcolname = "qareject";

		}

		$atname="";
		$ptname="";
		$ttname="";
		$nodo = 0;
		if ($rtype == 1){
			$atname = "rsave";
			$ptname = "rsave";
			$ttname = "rsave";
			if($status != 1){
				$nodo = 1;
			}
		}elseif($rtype == 2){
			$atname = "rqacount";
			$ptname = "submitqa";
			$ttname = "rqacount";
			if($status != 2){
				$nodo = 1;
			}
		}elseif($rtype == 3){
			$atname = "complete";
			$ptname = "complete";
			$ttname = "complete";
			if($status != 3){
				$nodo = 1;
			}
		}elseif($rtype == 4){
			$atname = "qareject";
			$ptname = "reject";
			$ttname = "qareject";
			if($status != 4){
				$nodo = 1;
			}
		}


				$newstan = $_POST["stan_comments"];
                $oldstan = $_POST["oldstan_comments"];

                if($oldstan == "" && $newstan == "RECORD NOT FOUND"){
                    $ct = 1;
                }else if($oldstan != "RECORD NOT FOUND" && $newstan == "RECORD NOT FOUND"){
                    $ct = 1;
                }else if($oldstan == "RECORD NOT FOUND" && $newstan != "RECORD NOT FOUND"){
                    $ct = 2;
                }else if($oldstan == "RECORD NOT FOUND" && $newstan == "RECORD NOT FOUND"){
                    $ct = 0;
                }


		$statcount = 0;
		$qy = "select * from teammembers where sub_id='".$userid."'";
		$rs = mysqli_query($db,$qy) or die("Cannot select teammembers");
		$row = mysqli_fetch_array($rs);

		$statcount = $row[$trcolname];

		if($rtype != $status) {
			$statcount = $row[$trcolname] + 1;
		}

		$tstatcount = $row[$ttname];
		if($tstatcount>0 && $nodo == 1){
			$tstatcount = $row[$ttname] - 1;

			$form_data = array($ttname => $tstatcount);
			dbRowUpdate($db,"teammembers",$form_data,"where sub_id=".$userid);

		}

		$nrfcount = $row["nrf"];
		if($ct == 1){
			$nrfcount = $row["nrf"]+1;
		}elseif($ct == 2){
			$nrfcount = $row["nrf"]-1;
		}

		$form_data = array($trcolname => $statcount,'nrf' => $nrfcount);
		dbRowUpdate($db,"teammembers",$form_data,"where sub_id=".$userid);

		$statcount = 0;
		$qy = "select * from allocat where userid='".$userid."' and id=".$allocid." and proid='".$proid."'";
		$rs = mysqli_query($db,$qy) or die("Cannot select Allocat save".mysqli_error($db));
		$row = mysqli_fetch_array($rs);

		$statcount = $row[$arcolname];
		if($rtype != $status) {
			$statcount = $row[$arcolname] + 1;
		}
		$astatcount = $row[$atname];
		if($astatcount>0 && $nodo == 1){
			$astatcount = $row[$atname] - 1;

			$form_data = array($atname => $astatcount);
			dbRowUpdate($db,"allocat",$form_data,"where userid=".$userid." and id=".$allocid." and proid='".$proid."'");
		}

		$nrfcount = $row["nrf"];
		if($ct == 1){
			$nrfcount = $row["nrf"]+1;
		}elseif($ct == 2){
			$nrfcount = $row["nrf"]-1;
		}

		$form_data = array($arcolname => $statcount,'nrf' => $nrfcount);
		dbRowUpdate($db,"allocat",$form_data,"where userid=".$userid." and id=".$allocid."  and proid='".$proid."'");

		$statcount = 0;
		$qy = "select * from project_data where cid='".$proid."'";
		$rs = mysqli_query($db,$qy) or die("Cannot select project Data");
		$row = mysqli_fetch_array($rs);

		$statcount = $row[$prcolname];
		if($rtype != $status) {
			$statcount = $row[$prcolname] + 1;
		}
		$pstatcount = $row[$ptname];
		if($pstatcount>0  && $nodo == 1){
			$pstatcount = $row[$ptname] - 1;

			$form_data = array($ptname => $pstatcount);
			dbRowUpdate($db,"project_data",$form_data,"where cid=".$proid);

		}

		$nrfcount = $row["nrf"];
		if($ct == 1){
			$nrfcount = $row["nrf"]+1;
		}elseif($ct == 2){
			$nrfcount = $row["nrf"]-1;
		}

		$form_data = array($prcolname => $statcount,'nrf' => $nrfcount);
		dbRowUpdate($db,"project_data",$form_data,"where cid=".$proid);


		$colold=StringRepair($_POST["colold"]);
		
		$establish=StringRepair($_POST["establish"]);
		$apname1=StringRepair($_POST["apname1"]);
		$designation1=StringRepair($_POST["designation1"]);
		$apname2=StringRepair($_POST["apname2"]);
		$designation2=StringRepair($_POST["designation2"]);
		$apname3=StringRepair($_POST["apname3"]);
		$designation3=StringRepair($_POST["designation3"]);
		$soslink=StringRepair($_POST["soslink"]);
		$soscompany=StringRepair($_POST["soscompany"]);
		$sosaddress=StringRepair($_POST["sosaddress"]);
		$apname_match=StringRepair($_POST["apname_match"]);
		$confmetric=StringRepair($_POST["confmetric"]);
		$stan_comments=StringRepair($_POST["stan_comments"]);
		$qa_comments=StringRepair($_POST["qa_comments"]);
		$a_signer = $_POST["a_signer"];

		if($establish==""){$establish = "-"; }
		if($apname1==""){$apname1 = "-"; }
		if($designation1==""){$designation1 = "-"; }
		if($apname2==""){$apname2 = "-"; }
		if($designation2==""){$designation2 = "-"; }
		if($apname3==""){$apname3 = "-"; }
		if($designation3==""){$designation3 = "-"; }
		if($soslink==""){$soslink = "-"; }
		if($soscompany==""){$soscompany = "-"; }
		if($sosaddress==""){$sosaddress = "-"; }
		if($apname_match==""){$apname_match = "-"; }
		if($confmetric==""){$confmetric = "-"; }


		if($a_signer == ""){$apname_match = "NO INPUT INFO"; }

		if($stan_comments == "RECORD NOT FOUND"){
			$establish = "-"; $apname1 = "-"; $designation1 = "-"; $apname2 = "-";  $designation2 = "-";
			$apname3 = "-"; $designation3 = "-"; $soslink = "-"; $sosaddress = "-"; $soscompany = "-";
			$apname_match = "-"; $confmetric = "-";
			
		}
		
		
			$form_data = array(
			$user => $userid,
			'establish' => $establish,
			'apname1' => strtoupper($apname1),
			'designation1' => strtoupper($designation1),
			'apname2' => strtoupper($apname2),
			'designation2' => strtoupper($designation2),
			'apname3' => strtoupper($apname3),
			'designation3' => strtoupper($designation3),
			'soslink' => $soslink,
			'soscompany' => strtoupper($soscompany),
			'sosaddress' => strtoupper($sosaddress),
			'apname_match' => $apname_match,
			'confmetric' => $confmetric,
			'stan_comments' => $stan_comments,
			'qa_comments' => $qa_comments,
			'status' => $status,
			'cdtime' => $today
			);
			
			// Updation of Data
			dbRowUpdate($db,$tabname,$form_data,"where cid=".$id);

				
			$_SESSION["sadmin_changeImage_Delete"]=$msg;
	}
	
	print "<META http-equiv='refresh' content=0;URL='".$url."'>";
	exit;
	
}
else
{
	print "<META http-equiv='refresh' content=0;URL=".$sitepath.">";
}
?>