<?php
session_start();
require("includes/connection.php");
$_SESSION["sadmin_username"];
if($_SESSION["sadmin_username"]=="")
{
	print "<META http-equiv='refresh' content=0;URL=index.php>";
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
    <script type="text/javascript" src="js/cufon/cufon-yui.js"></script>
    <script type="text/javascript" src="js/cufon/TitilliumText14L_300-TitilliumText14L_800.font.js"></script>

    <script type="text/javascript">
        jQuery(function(){
            Cufon.replace('h4')
        });

    </script>
    <style>
        label.error{
            position: relative;
            font-size: 12px;
            top:-5px;
            color: #dd0000;
        }
    </style>
    <?php include("includes/headerscripts.php"); ?>
</head>

<body class="loginpage">
<?php include("topmenu.php"); ?>
<div class="loginpanel2">
	<h3>Business Research Portal</h3>
</div>
<div class="loginpanel">
    <div class="loginpanelinner">
    <h5>Change Your Account Password</h5>
        <form id="passlogin" action="submitpass.php" method="post">
        <div class="inputwrapper login-alert">
                <div class="alert alert-error">Please Enter Password </div>
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
                <input type="password" name="newpass" id="newpass" class="boxed" placeholder="New Password" />
                <label><i class="fa fa-lock"></i></label>
            </div>
            <div class="inputwrapper animate2 bounceIn">
                <input type="password" name="confimpass" id="confirmpass" class="boxed" placeholder="Confirm Password" />
                <label><i class="fa fa-lock"></i></label>
            </div>
            <div class="inputwrapper animate1 bounceIn">
                <input type="text" name="verify" id="user01" placeholder="Enter Verification Code" />
            </div>
            <div class="inputwrapper animate2 bounceIn" style="width:100%;">
                <h4 style="margin-top:0px; padding:5px; margin-bottom:7px; width:230px; border:1px solid #CCCCCC; text-align:center;" ><?php echo $code; ?> </h4>
            </div>
            <div class="inputwrapper animate3 bounceIn" style="margin-top: -38px;">
                <button name="submit">Submit</button>
            </div>
        </form>
    </div><!--loginpanelinner-->
</div>
<!--loginpanel-->
<div class="clearboth"></div>
<div class="loginfooter">
    <p>Terms and Conditions | U.S. Online Privacy Statement  <span>Version 1.0</span></p>
</div>
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


        $("#passlogin").validate({
            rules: {
                "newpass":{
                    required: true,
                    rangelength: [8, 15],
                    pwlow:true,
                    pwupp:true,
                    pwdigit:true,
                    pwspec:true
                },
                "confimpass":{
                    required: true,
                    equalTo: "#newpass"
                },
                "verify":{
                    required:true
                }
            },
            messages: {
                "newpass":{
                    required: "You must enter a New Password",
                    rangelength: "Password Must be Between 8-15",
                    pwlow: "Enter Atleast 1 Lowercase Character",
                    pwupp: "Enter Atleast 1 Uppercase Character",
                    pwdigit: "Enter Atleast 1 Numeric Digit",
                    pwspec: "Enter Atleast 1 Special Character from ( !\-@._* )"
                },
                "confimpass":{
                    required: "You must Enter a Confirm Password",
                    equalTo: "New Password and Confirm Password Doesn't Match"
                },
                "verify":{
                    required: "You must Enter Verification Code"
                }
            }
        });
    });
</script>
</body>
</html>
