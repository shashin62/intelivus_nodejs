<?php
session_start();
include("includes/connection.php");
if($_SESSION["sadmin_username"]!="")
{
	if($page_upload_management != 1)
	{
		$_SESSION["sadmin_siteAccessMessage"] = $access_message;
		print "<META http-equiv='refresh' content=0;URL=home.php>";
		exit;
	}
	$operation="Add";
	$id=$_GET["id"];

	if($id!="" and is_numeric($id))
	{
		$cname = "";
		$company = "";
		$address = "";
		
		$qry="select * from project_data where cid=".$id;
		$result=mysqli_query($db,$qry) or die("cannot select Data ".mysqli_error($db));
		if($row=mysqli_fetch_array($result))
		{
			$operation="Edit";
			$cname=$row["proname"];
			$company=$row["company"];
			$address=$row["comp_address"];
            $activate=$row["activate"];
		}
		else
		{
			print "<META http-equiv='refresh' content=0;URL=home.php>";
			exit;
		}
	}
	else
	{
		$operation="Add";
		$cname = "";
		$company = "";
		$address = "";
	}
	
	$lookup = '<i class="fa fa-cloud-upload"></i> '.$operation.' Uploads';
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title><?php echo $title; ?></title>
<?php include('includes/headercss.php'); ?>
<?php include('includes/headerscripts.php'); ?>
<script>
function clearRecord(){
	
	var r = confirm("You are about to clear all records for this project!");
	if (r == true) {
		document.getElementById("frmclear").submit();
	} else {
		alert("Records Are Safe!");
	}
	
}
</script>
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
          <h4 class="widgettitle">Upload Data</h4>
          <div class="widgetcontent">
            <form class="stdform" name="frm1" id="login" action="importdata.php" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $id; ?>" />
              <input type="hidden" name="opt" value="<?php echo $operation; ?>" />
              <p>
                <label>Project Name </label>
                <span class="field">
                <input type="text" name="cname" class="input-xxlarge" id="cname"  value="<?php echo $cname; ?>" />
                </span> </p>
                 <p>
                <label>Company Name </label>
                <span class="field">
                <input type="text" name="company" class="input-xxlarge" id="company"  value="<?php echo $company; ?>" />
                </span> </p>
                 <p>
                <label>Company Address</label>
                <span class="field">
                <input type="text" name="address" class="input-xxlarge" id="address"  value="<?php echo $address; ?>" />
                </span> </p>
              <p>
                <label>Upload Excel File</label>
                <span class="field">
                <input type="file" name="flname1" <?php if($opr=="Add"){ echo "required"; } ?> class="btn btn-rounded" accept=".xlsx">
                </span> </p>

                <p>
               <label>Activated</label>
              <span class="formwrapper">
              <input type="checkbox" name="act" id="act" value="1" <?php if($activate==1) { echo "checked"; } ?> />
              </span> </p>

                <p class="stdformbutton">
                <button name="clearbtn" type="button" onClick="javascript:clearRecord();" class="btn btn-primary">Clear Records</button>
                </p>
                
              <p class="stdformbutton">
                <button name="submit" type="submit" class="btn btn-primary">Upload</button>
                <button name="submit2" type="button"  onClick="javascript:location.replace('home.php');"  class="btn btn-primary">Cancel</button>
              </p>
            </form>
          </div>
        </div>
        <?php include("includes/tb_footer.php"); ?>
        <form name="frmclear" id="frmclear" action="clearrecords.php" method="post">
        	<input type="hidden" name="proid" value="<?php echo $id; ?>" />
        </form>
      </div>
    </div>
  </div>
</div>
<?php require('includes/tb_footerscript.php'); ?>
<script>
    $(document).ready(function(){

        $("#login").validate({
            rules: {
                "cname": {
                    required: true
                },
                "company":{
                    required: true
                },
                "address":{
                    required: true,
                }
            },
            messages: {
                "cname": {
                    required: "You must Enter a Project Name"
                },
                "company":{
                    required:"Your must Enter a Company Name"
                },
                "address":{
                    required: "You must enter a Address"
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