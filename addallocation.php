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
	
	$id=$_GET["id"];
	if($id!="" and is_numeric($id))
	{
		$qry="select * from allocat where id=".$id;
		$result=mysqli_query($db,$qry) or die("cannot select allocation ".mysqli_error($db));
		if($row=mysqli_fetch_array($result))
		{
			$operation="Edit";
			$proid=$row["proid"];
			$userid=$row["userid"];
			$rstart = $row["rstart"];
			$rend = $row["rend"];
			$activate = $row["activate"];
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
	
	$lookup = '<i class="fa fa-user"></i> '.$operation.' Allocation';
	
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
          <h4 class="widgettitle"><?php echo $operation; ?> Allocation</h4>
             <div class="widgetcontent">
            <form class="stdform" name="login" id="login" action="saveallocation.php" method="post" onSubmit="return checkform2()" enctype="multipart/form-data">
               <input type="hidden" name="cid" value="<?php echo $id;?>" />
              <input type="hidden" name="opt" value="<?php echo $operation; ?>" />

              <p>
                <label>Project </label>
                <span class="field">
					<select name="proid" id="proid" class="uniformselect">
						<option value=""> - Select Project -</option>
						<?php
						$qr = "select * from project_data";
						$rs = mysqli_query($db,$qr) or die ("cannot select projects".mysqli_error($db));
						while($row=mysqli_fetch_array($rs)){
							if($row["cid"]==$proid){
								echo '<option value="'.$row["cid"].'" selected>'.$row["proname"]." - ".$row["records"].'</option>';
							}else{
								echo '<option value="'.$row["cid"].'">'.$row["proname"]." - ".$row["records"].'</option>';
							}
						}
						?>
					</select>
                </span> </p>

              <p>
                <label>Name *</label>
                <span class="field">
                <select name="userid" id="userid" class="uniformselect">
					<option value=""> - Select User -</option>
					<?php
					$qr = "select * from teammembers";
					$rs = mysqli_query($db,$qr) or die ("cannot select projects".mysqli_error($db));
					while($row=mysqli_fetch_array($rs)){
						$sql = "select cid from teammembers_rights where tid=".$row["sub_id"]." and rid=4 and having_right=1 order by cid";
						$qry = mysqli_query($db,$sql)or die("cannot select team Members".mysqli_error($db));
						$num_rows = mysqli_num_rows($qry);
						if($num_rows == 1){}else{
							if($row["sub_id"]==$userid){
								echo '<option value="'.$row["sub_id"].'" selected>'.$row["user_fname"]." ".$row["user_lname"].'</option>';
							}else{
								echo '<option value="'.$row["sub_id"].'">'.$row["user_fname"]." - ".$row["user_lname"].'</option>';
							}
						}
						
					}
					?>
				</select>
                </span> </p>
              <p>
                <label>Records Starting </label>
                <span class="field">
                <input type="text" name="rstart" class="input-xxlarge" id="rstart"  value="<?php echo $rstart; ?>" />
                </span> </p>
                <p>
                <label>Records Ending</label>
                <span class="field">
                <input type="text" name="rend" class="input-xxlarge" id="rend"  value="<?php echo $rend; ?>" />
                </span> </p>

				<p>
					<label>Activated</label>
              <span class="formwrapper">
              <input type="checkbox" name="act" id="act" value="1" <?php if($activate==1) { echo "checked"; } ?> />
              </span> </p>


        <p class="stdformbutton">
                <button name="submit" type="submit" class="btn btn-primary">Save</button>
                <button name="submit2" type="button"  onClick="javascript:location.replace('manageallocation.php');"  class="btn btn-primary">Cancel</button>
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
					rangelength: [3, 15]
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