<?php
if($_SESSION["sadmin_username"]!="")
{

	if($user_type_admin == "Main Rights")
	{
	$qz="select * from user_info where user_id=".$_SESSION["sadmin_id"];
	$rs=mysqli_query($db,$qz) or die("cannot select form content ".mysqli_error($db));
	$rsw=mysqli_fetch_array($rs);
	$tpemail=$rsw["user_email"];
	$tpfname=$rsw["user_name"];
	$profile_image=$rsw["user_image"];
	}
	else
	{
	$qz="select * from teammembers where user_id=".$_SESSION["sadmin_id"]." and sub_id='".mysqli_real_escape_string($db,$_SESSION["sadmin_subid"])."'";
	$rs=mysqli_query($db,$qz) or die("cannot select form content ".mysqli_error($db));
	$rsw=mysqli_fetch_array($rs);
	$tpemail=$rsw["user_email"];
	$tpfname=$rsw["user_fname"];
	$profile_image=$rsw["user_image"];
	$mrsave = $rsw["rsave"];
	$mqasubmit = $rsw["rqacount"];
	$mqareject = $rsw["qareject"];
	$rnrf = $rsw["nrf"];

	}
}
 	
?>
<div class="header boxed">
	<?php
	if($user_type_admin == "Sub Rights" && $user_qa_rights != 1)
	{?>
	<div class="topstatus" style="float:left;">
		<h4><i class="fa fa-save"></i> &nbsp;Saved - <?= $mrsave; ?> | <i class="fa fa-paste"></i> &nbsp;Submit To QA - <?= $mqasubmit; ?> | <i class="fa fa-copy"></i> &nbsp; Reject - <?= $mqareject; ?> | <i class="fa fa-copy"></i> &nbsp; Record Not Found - <?= $rnrf; ?> </h4>
    </div>
     <?php } ?>
       <?php if($_SESSION["sadmin_username"]!=""){?>
		  

		   <div class="topstatus" style="float:right">
	<h4><i class="fa fa-user"></i> &nbsp;Welcome <?php echo $tpfname; ?><br />
	<div id="clock" style="text-align: right"></div>
    </h4>
    </div>
       <?php } ?>  
    </div>
