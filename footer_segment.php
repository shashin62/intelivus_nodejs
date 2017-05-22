<?php
session_start();
include("includes/connection.php");
if($_SESSION["sadmin_username"]!="")
{
		if($page_segment_management != 1)
	{
		$_SESSION["sadmin_siteAccessMessage"] = $access_message;
		print "<META http-equiv='refresh' content=0;URL=home.php>";
		exit;
	}
	
		$ptitle="";
		$phead="";
		$ckey="";
		$cphrases="";
		$cmeta="";
		$dmeta=0;
		$ctext="";
		$qry="select * from homesegment where cid=3";
		$result=mysqli_query($db,$qry) or die("cannot select content ".mysqli_error($db));
		if($row=mysqli_fetch_array($result))
		{
			$phead=$row["page_innerhead"];
			$ctext=$row["ctext"];
		}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title><?php echo $title; ?></title>
<?php include('includes/headercss.php'); ?>
<?php include('includes/headerscripts.php'); ?>
<script type="text/javascript">
function checkform()
{
	var sname=document.frm1.ptitle.value;
	if(sname=="")
	{
		alert("Please Enter Page Title");
		document.frm1.ptitle.focus();
		return false;
	}
	var sname=document.frm1.phead.value;
	if(sname=="")
	{
		alert("Please Enter Paragraph Heading");
		document.frm1.phead.focus();
		return false;
	}
	var sname=document.frm1.ctext.value;
	if(sname=="")
	{
		alert("Please Enter Pragraph Text");
		document.frm1.ctext.focus();
		return false;
	}
	var sname=document.frm1.ctext2.value;
	if(sname=="")
	{
		alert("Please Enter Paragraph Text 2");
		document.frm1.ctext2.focus();
		return false;
	}
	
}
</script>
</head>
<body>
<!-- Header  -->
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
        <h4 class="widgettitle">Manage Home Footer Segment</h4>
        <div class="widgetcontent">
          <form class="stdform" name="frm1" action="savefootersegment.php" method="post" onSubmit="return checkform()" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $id; ?>" />
            <p>
              <label>Inner Heading *</label>
              <span class="field">
              <input type="text" name="phead" class="input-xxlarge" id="phead"  value="<?php echo $phead; ?>" />
              </span> </p>
            <p>
              <label>Page Text *</label>
              <span class="field">
              <textarea cols="100" rows="5" class="span5 ctext" name="ctext" id="ctext"><?php echo $ctext; ?></textarea>
              </span> </p>
              <p>
                <label>Image (250px X 200px)</label>
                <span class="field">
                <input type="file" name="flname1"  class="btn btn-rounded">
                </span> </p>
            <p class="stdformbutton">
              <button name="submit" type="submit" class="btn btn-primary">Save</button>
              <button name="submit2" type="button"  onClick="javascript:location.replace('home.php');" type="reset" class="btn">Cancel</button>
            </p>
          </form>
        </div>
      </div>
      <?php include("includes/tb_footer.php"); ?>
    </div>
  </div>
</div>
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