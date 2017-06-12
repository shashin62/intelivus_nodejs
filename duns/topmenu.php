<?php
if($_SESSION["sadmin_username"]!="")
{

	if($user_type_admin == "Main Rights")
	{
	$qz="select * from user_info where user_id=".$_SESSION["sadmin_id"];
	$rs=mysqli_query($db,$qz) or die("cannot select form content ".mysqli_error($db));
	$rsw=mysqli_fetch_array($rs);
	$tpemail=$rsw["user_email"];
	$tplname=$rsw["user_fname"];
	$profile_image=$rsw["user_image"];
	}
	else
	{
	$qz="select * from teammembers where user_id=".$_SESSION["sadmin_id"]." and sub_id='".mysqli_real_escape_string($db,$_SESSION["sadmin_subid"])."'";
	$rs=mysqli_query($db,$qz) or die("cannot select form content ".mysqli_error($db));
	$rsw=mysqli_fetch_array($rs);
	$tpemail=$rsw["user_email"];
	$tplname=$rsw["user_fname"];
	$profile_image=$rsw["user_image"];
	}
}
 	
?>
<div class="header boxed">
<div class="logo" style="margin-top:10px; float:left;">
	<a href="home.php" style="color:#FFF; font-size:24px; margin-top:0px; height:80px!imporatant; text-decoration:none"><img src="img/logo.png" alt="" style="width:28%;"></a>
        </div>
       <?php if($_SESSION["sadmin_username"]!=""){?>
		  

		   <div class="topstatus" style="float:right">
	<h4><i class="fa fa-user"></i> &nbsp;Welcome <?php echo $tplname; ?><br />
	<div id="clock" style="text-align: right"></div>
    </h4>
    </div>
       <?php } ?>  
    </div>
