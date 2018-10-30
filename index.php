<?php
session_start();
require("includes/connection.php");
$_SESSION["sadmin_username"];
if($_SESSION["sadmin_username"]!="")
{
	print "<META http-equiv='refresh' content=0;URL=home.php>";
	exit;
}

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title><?php echo $title ?></title>
<link rel="stylesheet" href="/var/www/html/css/style.default.css" type="text/css" />
<link rel="stylesheet" href="/var/www/html/css/style.shinyblue.css" type="text/css" />

<script type="text/javascript" src="./js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="./js/jquery-migrate-1.1.1.min.js"></script>
<script type="text/javascript" src="./js/jquery-ui-1.10.3.min.js"></script>
<script type="text/javascript" src="./js/modernizr.min.js"></script>
<script type="text/javascript" src="./js/bootstrap.min.js"></script>
<script type="text/javascript" src="./js/jquery.cookie.js"></script>
<script type="text/javascript" src="./js/custom.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('#login').submit(function(){
            var u = jQuery('#username').val();
            var p = jQuery('#password').val();
            if(u == '' && p == '') {
                jQuery('.login-alert').fadeIn();
                return false;
            }
        });
    });
</script>
    <?php include("includes/headerscripts.php"); ?>
</head>

<body class="loginpage">
<?php include("topmenu.php"); ?>
<div class="loginpanel2">
	<h3>Personalization <br>Initiative DA Work</h3>
</div>
<div class="loginpanel">
    <div class="loginpanelinner">
    <h5>Login to your Account</h5>
        <form id="login" action="validatelogin.php" method="post">
        <div class="inputwrapper login-alert">
                <div class="alert alert-error">Please Enter Username / Password </div>
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
                <input type="text" name="username" id="username" class="boxed" placeholder="Username" />
                <label><i class="fa fa-envelope"></i></label>
            </div>
            <div class="inputwrapper animate2 bounceIn">
                <input type="password" name="password" id="password" class="boxed" placeholder="Password" />
                <label><i class="fa fa-lock"></i></label>
            </div>
            
            <div class="inputwrapper animate3 bounceIn" >
                <button name="submit">Sign In</button>
            </div>
            <div class="clearfix"></div>
            <div class="inputwrapper animate4 bounceIn" style="margin-top:-30px;">
                <div class="pull-right"><a href="forgetpass.php">Forgot My Password</a></div>
            </div>
        </form>
    </div><!--loginpanelinner-->
</div>
<!--loginpanel-->
<div class="clearboth"></div>
<div class="loginfooter">
    <p>Terms and Conditions | U.S. Online Privacy Statement  <span>Version 1.0</span></p>
</div>

</body>
</html>
