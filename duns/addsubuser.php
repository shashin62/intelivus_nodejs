<?php
session_start();
include("includes/connection.php");
include("includes/layout.php");
include("includes/checkmainaccess.php");
if($_SESSION["sadmin_username"]!="")
{
	if($page_user_management != 1)
	{
		$_SESSION["sadmin_siteAccessMessage"] = $access_message;
		print "<META http-equiv='refresh' content=0;URL=home.php>";
		exit;
	}
	
	$operation="Add";
	if($_GET["page"]=="")
	{	
		$page=1;
	}
	else
	{
		$page=preg_replace ('/[^\d]/', '', $_GET["page"]);
	}
	if($page == "")
	{
		$page = 1;
	}
	
	$t_email = "";
	$t_login = "";
	$t_password = generateStrongPassword();
	$t_fname = "";
	$t_lname = "";
	$t_ecode = "";
	$t_add = "";
	$t_zipcode = "";
	$t_phone = "";
	$t_mobile = "";
	$t_fax = "";
	$t_assign_section = "";
	$t_block = 0;
	$id=$_GET["id"];
	if($id!="" and is_numeric($id))
	{
		$qry="select * from teammembers where user_id=".$_SESSION["sadmin_id"]." and sub_id=".$id;
		$result=mysqli_query($db,$qry) or die("cannot select teammembers ".mysqli_error($db));
		if($row=mysqli_fetch_array($result))
		{
			$operation="Edit";
			$t_email=$row["user_email"];
			$t_login=$row["user_login"];
			$t_fname = $row["user_fname"];
			$t_lname = $row["user_lname"];
			$t_password = "";
			$t_ecode = $row["empcode"];
			$t_add = $row["user_add"];
			$t_zipcode = $row["zipcode"];
			$t_phone = $row["user_phone"];
			$t_mobile = $row["user_mobile"];
			$t_fax = $row["user_fax"];
			$t_assign_section = $row["assign_section"];
			$t_assign_section_arr = preg_split("/,/",$t_assign_section);
			$t_block = $row["user_block"];			
		}
		else
		{
			print "<META http-equiv='refresh' content=0;URL=managesubuser.php>";
			exit;
		}
	}
	else
	{
		$operation="Add";
		$id=0;
	}
	
	$lookup = '<i class="fa fa-user"></i> '.$operation.' User';
	
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
          <h4 class="widgettitle"><?php echo $operation; ?> User</h4>
             <div class="widgetcontent">
            <form class="stdform" name="login" id="login" action="savesubuser.php" method="post" onSubmit="return checkform2()" enctype="multipart/form-data">
               <input type="hidden" name="cid" value="<?php echo $id;?>" />
              <input type="hidden" name="opt" value="<?php echo $operation; ?>" />
              <input type="hidden" name="gsections" id="gsections" value="<?php echo $t_assign_section;?>" />
            
              <p>
                <label>login ID *</label>
                <span class="field">
                <input type="text" name="glogin" class="input-xxlarge" id="glogin"  value="<?php echo $t_login; ?>" />
                </span> </p>
              <p>
                <label>Email Address *</label>
                <span class="field">
                <input type="text" name="gemail" class="input-xxlarge" id="gemail"  value="<?php echo $t_email; ?>" />
                </span> </p>
              <p>
                <label>Password *</label>
                <span class="field">
                <input type="text" name="gpassword" class="input-xxlarge" id="gpassword"  value="<?php echo $t_password; ?>" />
                </span> </p>
              <p>
                <label>First Name *</label>
                <span class="field">
                <input type="text" name="gfname" class="input-xxlarge" id="gfname"  value="<?php echo $t_fname; ?>" />
                </span> </p>
              <p>
                <label>Last Name</label>
                <span class="field">
                <input type="text" name="glname" class="input-xxlarge" id="glname"  value="<?php echo $t_fname; ?>" />
                </span> </p>
              
                 <p>
                <label>Employee Code </label>
                <span class="field">
                <input type="text" name="ecode" class="input-xxlarge" id="ecode"  value="<?php echo $t_ecode; ?>" />
                </span> </p>
                 <p>
                <label>Address</label>
                <span class="field">
                <input type="text" name="cadd" class="input-xxlarge" id="cadd"  value="<?php echo $t_add; ?>" />
                </span> </p>
                 <p>
                <label>Zipcode</label>
                <span class="field">
                <input type="text" name="gzipcode" class="input-xxlarge" id="gzipcode"  value="<?php echo $t_zipcode; ?>" />
                </span> </p>
                <p>
                <label>Phone no.</label>
                <span class="field">
                <input type="text" name="gphone" class="input-xxlarge" id="gphone"  value="<?php echo $t_phone; ?>" />
                </span> </p>
                <p>
                                      <p>
              <label>Block</label>
              <span class="formwrapper">
              <input type="checkbox" name="block" id="block" value="1" <?php if($t_block==1) { echo "checked"; } ?> />
              </span> </p>

           
            
            <table id="dyntable" class="table table-bordered responsive" style="width:470px; margin-left:172px;" >
    <colgroup>
    <col class="con1" />
    <col class="con0" style="text-align: center; width: 4%" />
    </colgroup>
    <thead>
      <tr>
           <th>Sub User Access Areas</th>
           <th class="head0 nosort">Enabled</th>
       </tr>
    </thead>
    <tbody>
    <?php
		   
					$p = 0;
					$q = 0;
					$sql="select * from `sitesections` order by cid";
				    $result2=mysqli_query($db,$sql) or die("cannot select sitesections ".mysqli_error($db));
				    while($row2=mysqli_fetch_array($result2))
				    {
						$p = $p + 1;
						$q = $q + 1;
						$selright="";
						if($id != "")
						{
							$sql="select having_right from `teammembers_rights` where tid='".$id."' and rid='".$row2["cid"]."'";
							$result5=mysqli_query($db,$sql) or die("cannot select teammembers_rights ".mysqli_error($db));
							if($row5=mysqli_fetch_array($result5))
							{
								if($row5["having_right"] == 1)
								{
									$selright="checked";
								}
							}
						}
					?>
        <tr>
                      <td><input name="rid<?php echo $q;?>" type="hidden" id="rid<?php echo $q;?>" value="<?php echo $row2["cid"];?>"  /><b><?php echo $p."."; ?> <?php echo $row2["right_line"];?><?php if($subitems == 0) { ?><input type="hidden" name="rtype<?php echo $q;?>" value="main" /><?php } ?></b></td>
                      
                      <td><?php if($subitems == 0) { ?><input name="selright<?php echo $q;?>" type="checkbox" id="selright<?php echo $q;?>" value="1" <?php echo $selright; ?>  /><?php } ?></td>
                    </tr>
                    <?php
					}
					?>
       
      </tbody>
      </table>
          <input type="hidden" name="ploop" value="<?php echo $q; ?>" />
                
        <p class="stdformbutton">
                <button name="submit" type="submit" class="btn btn-primary">Save</button>
                <button name="submit2" type="button"  onClick="javascript:location.replace('managesubuser.php');"  class="btn btn-primary">Cancel</button>
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

		$("#login").validate({
			rules: {
				"glogin": {
					required: true,
					rangelength: [7, 15]
				},
				"gemail":{
					required: true,
					email: true
				},
				<?php if($operation == "Add"){?>
				"gpassword":{
					required: true,
					rangelength: [8, 15],
					pwlow:true,
					pwupp:true,
					pwdigit:true,
					pwspec:true
				},<?php } ?>
				"gfname":{
					required: true,
					letteronly:true
				},
				"glname":{
					required: true
				},
				"ecode":{
					required: true
				},
				"gphone":{
					required: true,
					number: true
				}
			},
			messages: {
				"user_name": {
					required: "You must Enter a Username",
					rangelength: "Username Must be Between 7-15 Characters"
				},
				"gemail":{
					required:"Your must Enter a Email",
					email:"Enter a Valid Email"
				},
	<?php if($operation == "Add"){?>

				"gpassword":{
					required: "You must enter a Password",
					rangelength: "Password Must be Between 8-15",
					pwlow: "Enter Atleast 1 Lowercase Character",
					pwupp: "Enter Atleast 1 Uppercase Character",
					pwdigit: "Enter Atleast 1 Numeric Digit",
					pwspec: "Enter Atleast 1 Special Character from ( !\-@._* )"
				},
	<?php } ?>
				"gfname":{
					required:"Your must Enter a First Name",
					letteronly:"Please Enter Text Only"
				},
				"glname":{
					required:"Your must Enter a Last Name",
				},
				"ecode":{
					required:"Your must Enter an Employee Code",
				},
				"gphone":{
					required:"Your must Enter a Phone No.",
					number:"The Phone no. should be in Numeric Digits"
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
function generateStrongPassword($length = 9, $add_dashes = false, $available_sets = 'luds')
{
	$sets = array();
	if(strpos($available_sets, 'l') !== false)
		$sets[] = 'abcdefghjkmnpqrstuvwxyz';
	if(strpos($available_sets, 'u') !== false)
		$sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
	if(strpos($available_sets, 'd') !== false)
		$sets[] = '23456789';
	if(strpos($available_sets, 's') !== false)
		$sets[] = '!@#$%&*?';

	$all = '';
	$password = '';
	foreach($sets as $set)
	{
		$password .= $set[array_rand(str_split($set))];
		$all .= $set;
	}

	$all = str_split($all);
	for($i = 0; $i < $length - count($sets); $i++)
		$password .= $all[array_rand($all)];

	$password = str_shuffle($password);

	if(!$add_dashes)
		return $password;

	$dash_len = floor(sqrt($length));
	$dash_str = '';
	while(strlen($password) > $dash_len)
	{
		$dash_str .= substr($password, 0, $dash_len) . '-';
		$password = substr($password, $dash_len);
	}
	$dash_str .= $password;
	return $dash_str;
}
?>