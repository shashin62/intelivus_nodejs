<?php
session_start();
include("includes/connection.php");
include("includes/con2.php");
include("includes/functions.php");
require("includes/funcstuffs.php");

// Page Variable Declaration
$addpage= $sitepath."manageduns.php";
$urlsite = $_SESSION["producturl"];
$msgadd = "Data Added Successfully";
$msgupdate = "Data Updated Successfully";
$msgerr = "Data Not Submitted";
$id = $_POST['id'];
$tabname ="OUTPUT";

// Check Login Session
if($_SESSION["sadmin_username"]!="")
{


	date_default_timezone_set('Asia/Kolkata');
	$today=date("Y-m-d H:i:s");

	$proid=StringRepair($_POST["proid"]);
	$rtype=StringRepair($_POST["rtype"]);
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


			$qy = "select * from batch where id='".$proid."'";
			$rs = mysqli_query($db,$qy) or die("Cannot select project Data");
			$row = mysqli_fetch_array($rs);
			$rsave = $row["rsave"] + 1;
			if($row["onhold"]>0){
				$countonhold = $row["onhold"] - 1;
			}
			$form_data = array('rsave' => $rsave,'onhold' => $countonhold);
			dbRowUpdate($db,"batch",$form_data,"where id=".$proid);

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

			$qy = "select * from batch where id='".$proid."'";
			$rs = mysqli_query($db,$qy) or die("Cannot select project Data");
			$row = mysqli_fetch_array($rs);
			if($row["reject"]>0){
				$countreject = $row["reject"] - 1;
			}
			$countsave = $row["rsave"] + 1;
			$form_data = array('reject' => $countreject,'rsave' => $countsave);
			dbRowUpdate($db,"batch",$form_data,"where id=".$proid);

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

			$qy = "select * from batch where id='".$proid."'";
			$rs = mysqli_query($db,$qy) or die("Cannot select project Data");
			$row = mysqli_fetch_array($rs);
			if($row["onhold"]>0){
				$counthold = $row["onhold"] - 1;
			}
			$countqa = $row["submitqa"] + 1;
			$form_data = array('submitqa' => $countqa,'onhold' => $counthold);
			dbRowUpdate($db,"batch",$form_data,"where id=".$proid);

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

			$qy = "select * from batch where id='".$proid."'";
			$rs = mysqli_query($db,$qy) or die("Cannot select project Data");
			$row = mysqli_fetch_array($rs);
			if($row["rsave"]>0){
				$countsave = $row["rsave"] - 1;
			}
			$countqa = $row["submitqa"] + 1;
			$form_data = array('rsave' => $countsave,'submitqa' => $countqa);
			dbRowUpdate($db,"batch",$form_data,"where id=".$proid);

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

			$qy = "select * from batch where id='".$proid."'";
			$rs = mysqli_query($db,$qy) or die("Cannot select project Data");
			$row = mysqli_fetch_array($rs);
			if($row["reject"]>0){
				$countsave = $row["reject"] - 1;
			}
			$countqa = $row["submitqa"] + 1;
			$form_data = array('reject' => $countsave,'submitqa' => $countqa);
			dbRowUpdate($db,"batch",$form_data,"where id=".$proid);

		}

	}elseif(isset($_POST['complete'])){
		$status = 3;
		$user = "adminid";
		$userid = $_SESSION["sadmin_id"];
		$msg = $msgcomp;
		$qy = "select * from batch where id='".$proid."'";
		$rs = mysqli_query($db,$qy) or die("Cannot select project Data");
		$row = mysqli_fetch_array($rs);
		if($row["submitqa"]>0){
			$countqa = $row["submitqa"] - 1;
		}
		$countcomplete = $row["complete"] + 1;
		$form_data = array('submitqa' => $countqa,'complete' => $countcomplete);
		dbRowUpdate($db,"batch",$form_data,"where id=".$proid);

	}elseif(isset($_POST['reject'])){
		$status = 4;
		$user = "adminid";
		$userid = $_SESSION["sadmin_id"];
		$msg = $msgreject;
		$qy = "select * from batch where id='".$proid."'";
		$rs = mysqli_query($db,$qy) or die("Cannot select project Data");
		$row = mysqli_fetch_array($rs);
		if($row["submitqa"]>0){
			$countqa = $row["submitqa"] - 1;
		}
		$countreject = $row["reject"] + 1;
		$form_data = array('submitqa' => $countqa,'reject' => $countreject);
		dbRowUpdate($db,"batch",$form_data,"where id=".$proid);

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

	$form_data = array('RECORD_STATUS'=>$status);
	dbRowUpdate($db,"input",$form_data,"where id=".$id);

			for($i=1;$i<=$_POST["dloop"];$i++)
			{
				$id = $_POST["del_".$i];
				if($_POST["del_".$i] != "")
				{
					$qry = "select INPUT_ID from OUTPUT where id='".$id."'";
					$srs = mysqli_query($db2,$qry) or die("cannot select Records".mysqli_error($db2));
					$fr = mysqli_fetch_array($srs);
					
					$qry1 = "select * from OUTPUT where INPUT_ID='".$fr["INPUT_ID"]."'";
					$srs1 = mysqli_query($db2,$qry1) or die("cannot select Records".mysqli_error($db2));
					while($row=mysqli_fetch_array($srs1)){
						if($id == $row["ID"]){
							$form_data = array(
								'is_true' => 1
							);
						}else{
							$form_data = array(
								'is_true' => 0
							);
						}

					dbRowUpdate($db2,$tabname,$form_data,"where id=".$row["ID"]);
					}
					
					$form_data = array(
					'status'=>"COMPLETE"
					);
					dbRowUpdate($db2,"INPUT",$form_data,"where id=".$fr["INPUT_ID"]);
				}

			}	
		
	
		$_SESSION["sadmin_changeImage_Delete"]=$msgupdate;

		print "<META http-equiv='refresh' content=0;URL='".$urlsite."'>";
		exit;
}
else
{
	print "<META http-equiv='refresh' content=0;URL=".$sitepath.">";
}
?>