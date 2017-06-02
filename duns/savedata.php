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
	$rtype=StringRepair($_POST["rtype"]);
	$cname=StringRepair($_POST["a_code"]);
	if($cname!="")
	{	
		$status = 0;
		if (isset($_POST['save'])) {
			$status = 1;
			$user = "userid";
			$userid = $_SESSION["sadmin_subid"];
			$msg = $msgsave;
		
			if($rtype == 0){
				
				$qy = "select rsave from teammembers where sub_id='".$userid."'";
				$rs = mysqli_query($db,$qy) or die("Cannot select teammembers save");
				$row = mysqli_fetch_array($rs);
				$statcount = $row["rsave"] + 1;
				$form_data = array('rsave' => $statcount);
				dbRowUpdate($db,"teammembers",$form_data,"where sub_id=".$userid);

				
				$qy = "select * from project_data where cid='".$proid."'";
				$rs = mysqli_query($db,$qy) or die("Cannot select project Data");
				$row = mysqli_fetch_array($rs);
				$rsave = $row["rsave"] + 1;
				if($row["onhold"]>0){
					$countonhold = $row["onhold"] - 1;
				}
				$form_data = array('rsave' => $rsave,'onhold' => $countonhold);
				dbRowUpdate($db,"project_data",$form_data,"where cid=".$proid);
				
			}elseif($rtype == 4){
				
				$qy = "select rsave,qareject from teammembers where sub_id='".$userid."'";
				$rs = mysqli_query($db,$qy) or die("Cannot select teammembers qa");
				$row = mysqli_fetch_array($rs);
				$countsave = $row["rsave"] + 1;
				if($row["qareject"]>0){
					$countreject = $row["qareject"] - 1;
				}
				$form_data = array('qareject' => $countreject,'rsave' => $countsave );
				dbRowUpdate($db,"teammembers",$form_data,"where sub_id=".$userid);

				$qy = "select * from project_data where cid='".$proid."'";
				$rs = mysqli_query($db,$qy) or die("Cannot select project Data");
				$row = mysqli_fetch_array($rs);
				if($row["reject"]>0){
					$countreject = $row["reject"] - 1;
				}
				$countsave = $row["rsave"] + 1;
				$form_data = array('reject' => $countreject,'rsave' => $countsave);
				dbRowUpdate($db,"project_data",$form_data,"where cid=".$proid);
			
			}
		
		}elseif(isset($_POST['submitqa'])){
			$status = 2;
			$user = "userid";
			$userid = $_SESSION["sadmin_subid"];
			$msg = $msgqa;
			
			if($rtype == 0){
				$qy = "select rqacount from teammembers where sub_id='".$userid."'";
				$rs = mysqli_query($db,$qy) or die("Cannot select teammembers qa");
				$row = mysqli_fetch_array($rs);
				$statcount = $row["rqacount"] + 1;
				$form_data = array('rqacount' => $statcount);
				dbRowUpdate($db,"teammembers",$form_data,"where sub_id=".$userid);

				$qy = "select * from project_data where cid='".$proid."'";
				$rs = mysqli_query($db,$qy) or die("Cannot select project Data");
				$row = mysqli_fetch_array($rs);
				if($row["onhold"]>0){
					$counthold = $row["onhold"] - 1;
				}
				$countqa = $row["submitqa"] + 1;
				$form_data = array('submitqa' => $countqa,'onhold' => $counthold);
				dbRowUpdate($db,"project_data",$form_data,"where cid=".$proid);
				
			}elseif($rtype == 1){
				
				$qy = "select rqacount,rsave from teammembers where sub_id='".$userid."'";
				$rs = mysqli_query($db,$qy) or die("Cannot select teammembers qa");
				$row = mysqli_fetch_array($rs);
				$countqa = $row["rqacount"] + 1;
				if($row["rsave"]>0){
					$countsave = $row["rsave"] - 1;
				}
				$form_data = array('rsave' => $countsave,'rqacount' => $countqa );
				dbRowUpdate($db,"teammembers",$form_data,"where sub_id=".$userid);

				$qy = "select * from project_data where cid='".$proid."'";
				$rs = mysqli_query($db,$qy) or die("Cannot select project Data");
				$row = mysqli_fetch_array($rs);
				if($row["rsave"]>0){
					$countsave = $row["rsave"] - 1;
				}
				$countqa = $row["submitqa"] + 1;
				$form_data = array('rsave' => $countsave,'submitqa' => $countqa);
				dbRowUpdate($db,"project_data",$form_data,"where cid=".$proid);
			
			}elseif($rtype == 4){
				
				$qy = "select rqacount,qareject from teammembers where sub_id='".$userid."'";
				$rs = mysqli_query($db,$qy) or die("Cannot select teammembers qa");
				$row = mysqli_fetch_array($rs);
				$countqa = $row["rqacount"] + 1;
				if($row["qareject"]>0){
					$countreject = $row["qareject"] - 1;
				}
				$form_data = array('qareject' => $countreject,'rqacount' => $countqa );
				dbRowUpdate($db,"teammembers",$form_data,"where sub_id=".$userid);

				$qy = "select * from project_data where cid='".$proid."'";
				$rs = mysqli_query($db,$qy) or die("Cannot select project Data");
				$row = mysqli_fetch_array($rs);
				if($row["reject"]>0){
					$countsave = $row["reject"] - 1;
				}
				$countqa = $row["submitqa"] + 1;
				$form_data = array('reject' => $countsave,'submitqa' => $countqa);
				dbRowUpdate($db,"project_data",$form_data,"where cid=".$proid);
			
			}
			
		}elseif(isset($_POST['complete'])){
			$status = 3;
			$user = "adminid";
			$userid = $_SESSION["sadmin_id"];
			$msg = $msgcomp;
			$qy = "select * from project_data where cid='".$proid."'";
			$rs = mysqli_query($db,$qy) or die("Cannot select project Data");
			$row = mysqli_fetch_array($rs);
			if($row["submitqa"]>0){
				$countqa = $row["submitqa"] - 1;
			}
			$countcomplete = $row["complete"] + 1;
			$form_data = array('submitqa' => $countqa,'complete' => $countcomplete);
			dbRowUpdate($db,"project_data",$form_data,"where cid=".$proid);

		}elseif(isset($_POST['reject'])){
			$status = 4;
			$user = "adminid";
			$userid = $_SESSION["sadmin_id"];
			$msg = $msgreject;
			$qy = "select * from project_data where cid='".$proid."'";
			$rs = mysqli_query($db,$qy) or die("Cannot select project Data");
			$row = mysqli_fetch_array($rs);
			if($row["submitqa"]>0){
				$countqa = $row["submitqa"] - 1;
			}
			$countreject = $row["reject"] + 1;
			$form_data = array('submitqa' => $countqa,'reject' => $countreject);
			dbRowUpdate($db,"project_data",$form_data,"where cid=".$proid);
			
			$qy = "select rqacount,qareject from teammembers where sub_id='".$userid."'";
				$rs = mysqli_query($db,$qy) or die("Cannot select teammembers qa");
				$row = mysqli_fetch_array($rs);
				$countreject = $row["qareject"] + 1;
				if($row["rqacount"]>0){
					$countqa = $row["rqacount"] - 1;
				}
				$form_data = array('qareject' => $countreject,'rqacount' => $countqa );
				dbRowUpdate($db,"teammembers",$form_data,"where sub_id=".$userid);

		}
		
				
		$colold=StringRepair($_POST["colold"]);
		$serial=StringRepair($_POST["serial"]);
		$jun_dun=StringRepair($_POST["jun_dun"]);
		$may_dun=StringRepair($_POST["may_dun"]);
		$legal_name=StringRepair($_POST["legal_name"]);
		$dba_name=StringRepair($_POST["dba_name"]);
		$b_address=StringRepair($_POST["b_address"]);
		$b_city=StringRepair($_POST["b_city"]);
		$b_state=StringRepair($_POST["b_state"]);
		$a_code=StringRepair($_POST["a_code"]);
		$a_details=StringRepair($_POST["a_details"]);
		$final_duns=StringRepair($_POST["final_duns"]);
		$comments=StringRepair($_POST["comments"]);
		$weblinks=StringRepair($_POST["weblinks"]);
		$website=StringRepair($_POST["website"]);
		$duns_month=StringRepair($_POST["duns_month"]);
		$location=StringRepair($_POST["location"]);
		$company=StringRepair($_POST["company"]);
		$address=StringRepair($_POST["address"]);
		$tel_no=StringRepair($_POST["tel_no"]);
		$duns_as_qa=StringRepair($_POST["duns_as_qa"]);
		$headquarters=StringRepair($_POST["headquarters"]);
		$qa_findings=StringRepair($_POST["qa_findings"]);
		$qa_comments=StringRepair($_POST["qa_comments"]);
		
		
			$form_data = array(
			$user => $userid,
			'serial' => $serial,
			'jun_dun' => $jun_dun,
			'may_dun' => $may_dun,
			'legal_name' => $legal_name,
			'dba_name' => $dba_name,
			'b_address' => $b_address,
			'b_city' => $b_city,
			'b_state' => $b_state,
			'a_code' => $a_code,
			'a_details' => $a_details,
			'final_duns' => $final_duns,
			'comments' => $comments,
			'weblinks' => $weblinks,
			'website' => $website,
			'duns_month' => $duns_month,
			'location' => $location,
			'company' => $company,
			'address' => $address,
			'tel_no' => $tel_no,
			'duns_as_qa' => $duns_as_qa,
			'headquarters' => $headquarters,
			'qa_findings' => $qa_findings,
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