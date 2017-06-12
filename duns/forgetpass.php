<?php
session_start();
require("includes/connection.php");
if($_SESSION["sadmin_username"]!="")
{
	print "<META http-equiv='refresh' content=0;URL=home.php>";
	exit;
}

$possible_letters = '23456789bcdfghjkmnpqrstvwxyz';
$characters_on_image = 6;
$i = 0;
while ($i < $characters_on_image) { 
$code .= substr($possible_letters, mt_rand(0, strlen($possible_letters)-1), 1);
$i++;
}

$_SESSION['6_letters_code'] = $code;

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title><?php echo $title ?></title>
<link rel="stylesheet" href="css/style.default.css" type="text/css" />
<link rel="stylesheet" href="css/style.shinyblue.css" type="text/css" />

<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.1.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.10.3.min.js"></script>
<script type="text/javascript" src="js/modernizr.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<script language="javascript">
function checkit()
{
	var sname=document.f1.username.value;
	if(sname=="")
	{
		alert("Please Enter Username");
		document.f1.username.focus();
		return false;
	}
	var sname=document.f1.user01.value;
	if(sname=="")
	{
		alert("Please Enter Verification Code");
		document.f1.user01.focus();
		return false;
	}
	return true;
}
</script>

<script type="text/javascript" src="js/cufon/cufon-yui.js"></script>
<script type="text/javascript" src="js/cufon/TitilliumText14L_300-TitilliumText14L_800.font.js"></script>

<script type="text/javascript">
	jQuery(function(){
		Cufon.replace('h4')
	});

</script>
</head>

<body class="loginpage">

<?php include("topmenu.php"); ?>
<div class="loginpanel2">
	<h3>Business Research Portal</h3>
</div>
<div class="loginpanel">
    <div class="loginpanelinner">
     <h5>Enter Your Account Details</h5>
        <form id="f1" action="sendpassword.php" method="post">
        <div class="inputwrapper login-alert">
                <div class="alert alert-error"><?php echo $_SESSION["slogin_Error"]; ?> </div>
            </div>
           <?php if ($_SESSION["slogin_Error"]!="")
  	{
   ?>
            <div class="inputwrapper login-alert" style="display:block">
                <div class="alert alert-error"><?php echo $_SESSION["slogin_Error"]; ?> </div>
            </div>
     
      <?php 
     $_SESSION["slogin_Error"]="";
   }
   ?>       <div class="inputwrapper animate1 bounceIn">
                <input type="text" name="username" id="username" placeholder="Enter Username" />
            </div>
            <div class="inputwrapper animate1 bounceIn">
                <input type="text" name="verify" id="user01" placeholder="Enter Verification Code" />
            </div>
            <div class="inputwrapper animate2 bounceIn" style="width:100%;">
               <h4 style="margin-top:0px; padding:5px; margin-bottom:7px; width:100%; border:1px solid #CCCCCC; text-align:center;" ><?php echo $code; ?> </h4>
            </div>
            <div class="inputwrapper animate3 bounceIn">
                <button name="submit">Send Password</button>
            </div>
            
            
        </form>
    </div><!--loginpanelinner-->
</div><!--loginpanel-->
<!--loginpanel-->
<div class="clearboth"></div>
<div class="loginfooter">
    <p>Terms and Conditions | U.S. Online Privacy Statement  <span>Version 1.0</span></p>
</div>

</body>
</html>
