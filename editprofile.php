<?php
session_start();
include("includes/connection.php");
include("includes/layout.php");
if($_SESSION["sadmin_username"]!="")
{
	if($user_type_admin == "Main Rights")
	{
		$qy="select * from user_info where user_id=".mysqli_real_escape_string($db,$_SESSION["sadmin_id"]);
		$rs=mysqli_query($db,$qy) or die("cannot select form user_info ".mysqli_error($db));
		$rsw=mysqli_fetch_array($rs);
		$tplogin=$rsw["user_name"];
		$tpemail=$rsw["user_email"];
		$tpfname=$rsw["user_fname"];
		$tplname=$rsw["user_lname"];
		$tpaddress=$rsw["user_add"];
		$tppin=$rsw["user_pin"];
		$tpphone=$rsw["user_phone"];
		$tpempcode=$rsw["user_ecode"];
		if($tpempcode == "")
		{
			$tpempcode = "-None-";
		}
	}
	else
	{
	$qy="select * from teammembers where user_id='".mysqli_real_escape_string($db,$_SESSION["sadmin_id"])."' and sub_id='".mysqli_real_escape_string($db,$_SESSION["sadmin_subid"])."'";
	$rs=mysqli_query($db,$qy) or die("cannot select form teammembers ".mysqli_error($db));
	$rsw=mysqli_fetch_array($rs);
	$tplogin=$rsw["user_login"];
	$tpemail=$rsw["user_email"];
	$tpfname=$rsw["user_fname"];
	$tplname=$rsw["user_lname"];
	$tpaddress=$rsw["user_add"];
	$tpcity=$rsw["user_city"];
	$tppin=$rsw["zipcode"];
	$tpphone=$rsw["user_phone"];
	$tpempcode=$rsw["empcode"];
	if($tpempcode == "")
	{
		$tpempcode = "-None-";
	}
	}
	
	$lookup = '<i class="fa fa-user"></i> Edit Profile';
	
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title><?php echo $title; ?></title>
<?php include('includes/headercss.php'); ?>
<?php include('includes/headerscripts.php'); ?>

</head>
<body>
<div id="mainwrapper" class="mainwrapper">
  <?php require('topmenu.php'); ?>
  <?php require('leftpanel.php'); ?>
  <?php require('changeskin.php'); ?>
    <div class="maincontent" style="min-height:550px;">
      <?php
			  if($_SESSION["sadmin_changeImage_Delete"]!="")
			  {
			  ?>
      <div class="toolTip " >
        <p class="clearfix"> <?php echo $_SESSION["sadmin_changeImage_Delete"]; ?> </p>
      </div>
      <?php
				$_SESSION["sadmin_changeImage_Delete"]="";
				}
				?>
      <div class="maincontentinner">
        <div class="widget">
          <h4 class="widgettitle">Change Password</h4>
          <div class="widgetcontent">
            <form class="stdform" name="frm1" id="passlogin" action="updatepass.php" method="post" onSubmit="return checkform()" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $id; ?>" />
              <input type="hidden" name="opt" value="<?php echo $operation; ?>" />
              <p>
                <label>Old Password</label>
                <span class="field">
                <input type="password" name="oldpass" class="input-xxlarge" id="oldpass"  value="" />
                </span> </p>
              <p>
                <label>New Password</label>
                <span class="field">
                <input type="password" name="newpass" class="input-xxlarge" id="newpass"  value="" />
                </span> </p>
              <p>
              <p>
                <label>Retype Password</label>
                <span class="field">
                <input type="password" name="repass" class="input-xxlarge" id="repass"  value="" />
                </span> </p>
            <p class="stdformbutton">
                <button name="submit" type="submit" class="btn btn-primary">Save</button>
                <button name="submit2" type="button"  onClick="javascript:location.replace('home.php');"  class="btn btn-primary">Cancel</button>
              </p>
              
            </form>
            </div>
            </div>
            <div class="widget">
          <h4 class="widgettitle">Manage Profile</h4>
             <div class="widgetcontent">
            <form class="stdform" name="frm12" id="proflogin" action="updateprofile.php" method="post" onSubmit="return checkform2()" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $id; ?>" />
              <input type="hidden" name="opt" value="<?php echo $operation; ?>" />
              <p>
                <label>login ID</label>
                <label class="fixpro"><?php echo $tplogin; ?></label>
              </p>
              <p>
                <label>Employee Code</label>
                <label class="fixpro"><?php echo $tpempcode; ?></label>
              </p>
              <p>
                <label>Email Address *</label>
                <label class="fixpro"><?php echo $tpemail;?></label>
          		</p>
              <p>
              <p>
                <label>First Name</label>
                <span class="field">
                <input type="text" name="fname" class="input-xxlarge" id="fname"  value="<?php echo $tpfname;?>" />
                </span> </p>
                 <p>
                <label>Last Name</label>
                <span class="field">
                <input type="text" name="lname" class="input-xxlarge" id="lname"  value="<?php echo $tplname;?>" />
                </span> </p>
                 <p>
                <label>Address</label>
                <span class="field">
                <input type="text" name="address" class="input-xxlarge" id="address"  value="<?php echo $tpaddress;?>" />
                </span> </p>
                 <p>
                <label>Zipcode</label>
                <span class="field">
                <input type="text" name="pinno" class="input-xxlarge" id="pinno"  value="<?php echo $tppin;?>" />
                </span> </p>
                <p>
                <label>Phone no.</label>
                <span class="field">
                <input type="text" name="phone" class="input-xxlarge" id="phone"  value="<?php echo $tpphone;?>" />
                </span> </p>
               <?php /*?> <p>
                <label>Profile Image *<br> (Size 100px X 100px)</label>
                <span class="field">
                <input type="file" name="flname1"  class="btn btn-rounded"> 
                </span> </p><?php */?>
              <p>
            <p class="stdformbutton">
                <button name="submit" type="submit" class="btn btn-primary">Save</button>
                <button name="submit2" type="button"  onClick="javascript:location.replace('home.php');"  class="btn btn-primary">Cancel</button>
              </p>
              
            </form>
            </div>
        </div>
        <?php include("includes/tb_footer.php"); ?>
      </div>
    </div>
  </div>
</div>
<?php require('includes/tb_footerscript.php'); ?>
<script>
	$(document).ready(function(){

		$.validator.addMethod("letteronly",
			function(value, element) {
				return /^[A-Za-z*]+$/.test(value);
			});

		$.validator.addMethod("pwlow",
			function(value, element) {
				return /^[A-Za-z0-9\d=!\-@._*]+$/.test(value)
					&& /[a-z]/.test(value) // has a lowercase letter
			});
		$.validator.addMethod("pwupp",
			function(value, element) {
				return /^[A-Za-z0-9\d=!\-@._*]+$/.test(value)
					&& /[A-Z]/.test(value) // has a Uppercase letter
			});

		$.validator.addMethod("pwdigit",
			function(value, element) {
				return /^[A-Za-z0-9\d=!\-@._*]+$/.test(value)
					&& /\d/.test(value) // has a digit;
			});

		$.validator.addMethod("pwspec",
			function(value, element) {
				return /^[A-Za-z0-9\d=!\-@._*]+$/.test(value)
					&& /[!\-@._*]/.test(value); // For Special Charaters
			});


		$("#proflogin").validate({
			rules: {
				"fname": {
					required: true,
					letteronly: true
				},
				"lname":{
					required: true,
					letteronly: true
				},
				"address":{
					required: true,
				},
				"pinno":{
					required: true
				},
				"phone":{
					required: true,
					number: true,
					rangelength: [10, 15]
				}
			},
			messages: {
				"fname": {
					required: "You must Enter a First Name",
					letteronly: "Please Enter Text Only"
				},
				"lname":{
					required:"Your must Enter a Last Name",
					letteronly: "Please Enter Text Only"
				},
				"address":{
					required: "You must enter a Address"
				},
				"pinno":{
					required:"Your must Enter a Zipcode"
				},
				"phone":{
					required:"Your must Enter a Phone No.",
					number:"You must Enter Numeric Only",
					rangelength:"Phone No. Must be between 10-15 Digits"
				}
			}
		});

		$("#passlogin").validate({
			rules: {
				"oldpass": {
					required: true
				},
				"newpass":{
					required: true,
					rangelength: [8, 15],
					pwlow:true,
					pwupp:true,
					pwdigit:true,
					pwspec:true
				},
				"repass":{
					required: true,
					equalTo: "#newpass"
				}
			},
			messages: {
				"oldpass": {
					required: "You must Enter a Old Password"
				},
				"newpass":{
					required: "You must enter a New Password",
					rangelength: "Password Must be Between 8-15",
					pwlow: "Enter Atleast 1 Lowercase Character",
					pwupp: "Enter Atleast 1 Uppercase Character",
					pwdigit: "Enter Atleast 1 Numeric Digit",
					pwspec: "Enter Atleast 1 Special Character from ( !\-@._* )"
				},
				"repass":{
					required: "You must Enter a Confirm Password",
					equalTo: "New Password and Confirm Password Doesn't Match"
				}
			}
		});
	});
</script>
</body>
</html>
<?php
}
else
{
	print "<META http-equiv='refresh' content=0;URL=index.php>";
	exit;
}
?>