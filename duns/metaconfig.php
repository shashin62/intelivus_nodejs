<?php
session_start();
include("includes/connection.php");
if($_SESSION["sadmin_username"]!="")
{
		$ptitle="";
		$phead="";
		$ckey="";
		$cphrases="";
		$cmeta="";
		$dmeta=0;
		$ctext="";
		$ctext2="";
		$qry="select * from def_meta";
		$result=mysqli_query($db,$qry) or die("cannot select content ".mysqli_error($db));
		if($row=mysqli_fetch_array($result))
		{
			$ptitle=$row["page_title"];
			$ckey=$row["metakey"];
			$cphrases=$row["mphrases"];
			$cmeta=$row["mdescription"];
			
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
<script type="text/javascript" src="js/tinymce/tinymce.min.js"></script>
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
        <h4 class="widgettitle">Manage Default Meta</h4>
        <div class="widgetcontent">
          <form class="stdform" name="frm1" action="updatemetaconfig.php" method="post" onSubmit="return checkform()">
            <input type="hidden" name="id" value="<?php echo $id; ?>" />
            <p>
              <label>Page Title *</label>
              <span class="field">
              <input type="text" name="ptitle" class="input-xxlarge" id="ptitle"  value="<?php echo $ptitle; ?>" />
              </span> </p>
            <p>
              <label>Meta Keywords</label>
              <span class="field">
              <textarea cols="100" rows="5" class="span5" name="ckey" id="ckey"><?php echo $ckey; ?></textarea>
              </span> </p>
            <p>
              <label>Meta Key Phrases</label>
              <span class="field">
              <textarea cols="100" rows="5" class="span5" name="cphrases" id="cphrases"><?php echo $cphrases; ?></textarea>
              </span> </p>
            <p>
              <label>Meta Description</label>
              <span class="field">
              <textarea cols="100" rows="5" class="span5" name="cmeta" id="cmeta"><?php echo $cmeta; ?></textarea>
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