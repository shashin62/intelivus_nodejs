<?php
session_start();
include("includes/connection.php");
include("includes/layout.php");
include("includes/paging.php");
include("includes/checkmainaccess.php");

$_SESSION["producturl"]=full_url();
$rtype = "";
if($_SESSION["sadmin_username"]!="")
{
	
	if($page_user_management != 1)
	{
		$_SESSION["sadmin_siteAccessMessage"] = $access_message;
		print "<META http-equiv='refresh' content=0;URL=home.php>";
		exit;
	}
	
	
	if($_GET["page"]=="")
	{	
		$page=1;
	}
	else
	{
		$page=preg_replace ('/[^\d]/', '', $_GET['page']);
	}
	$rndstring=base64_encode(rand(11111111,99999999)."/deleteAllocation");
	
	$lookup = '<i class="fa fa-user"></i> Manage Allocation';
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title><?php echo $title; ?></title>
<?php include('includes/headercss.php'); ?>
<?php include('includes/headerscripts.php'); ?>
<script type="text/javascript" language="JavaScript">
function deleteIt()
{
	var tc=0;
	for(i=1;i<=document.login.dloop.value;i++)
	{
		e=eval("document.login.del" +i);
		if(e.checked)
		{
			tc=tc+1;
		}
	}
	if(tc>0)
	{
		if(!confirm("Are You sure You want to delete selected Allocation(s)?"))
		{
			return false;
		}
		else
		{
			document.login.submit();
		}
	}
	else
	{
		alert("Please Check Atleast One Checkbox To Delete");
	}
}
function openAttach(url)
{
	window.open(url,'','scrollbars=yes,status=yes,width=1024,height=720,top=10,left=10')
}
</script>
</head>
<body>
<div id="mainwrapper" class="mainwrapper">
<?php require('topmenu.php'); ?>
<?php require('leftpanel.php'); ?>
<?php require('changeskin.php'); ?>
<?php

date_default_timezone_set('Asia/Kolkata');
$today=date("Y-m-d H:i:s");

			$c=0;
			$qy = "select * from allocat order by proid,rstart ";
			$per_page=100;
			$rs=mysqli_query($db,$qy) or die(mysqli_error($db)."<br>".$qy);
			$num_rows = mysqli_num_rows($rs);
			$cheight = "";
			if($num_rows<11)
			{
				$cheight = "height:550px;";
			}
		  ?>
<div class="maincontent" style="min-height:550px;">
<div class="maincontentinner" >
 <div class="row-fluid">
 <div id="dashboard-left" class="span12">
 <h5>Manage Allocation</h5>
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
                <div class="widget">
<?php
				$page = $page; 
				
				$prev_page = $page - 1; 
				$next_page = $page + 1;
				$page_start = ($per_page * $page) - $per_page;
				$pageend=0;
				
				if ($num_rows <= $per_page) { 
					$num_pages = 1; 
				} else if (($num_rows % $per_page) == 0) { 
					$num_pages = ($num_rows / $per_page); 
				} else { 
					$num_pages = ($num_rows / $per_page) + 1; 
				} 
				$num_pages = (int) $num_pages; 
				if (($page > $num_pages) || ($page < 0))
				{ 
					echo("You have specified an invalid page number"); 
					exit;
				}
				/* Instantiate the paging class! */ 
			   $Paging = new PagedResults(); 
			   
			   /* This is required in order for the whole thing to work! */ 
			   $Paging->TotalResults = $num_pages; 
			
			   /* If you want to change options, do so like this INSTEAD of changing them directly in the class! */ 
			   $Paging->ResultsPerPage = $per_page; 
			   $Paging->LinksPerPage = 10; 
			   $Paging->PageVarName = "page";
			   $Paging->TotalResults = $num_rows; 
			
			   /* Get our array of valuable paging information! */ 
			   $InfoArray = $Paging->InfoArray();
			   $qy1=$qy." LIMIT $page_start, $per_page ";
			   $rowrs=mysqli_query($db,$qy1) or die(mysqli_error($db)."<br>".$qy1);
			   
			   if($num_rows>0)
			   {
			  ?>
              
<form id="login" name="login" method="post" action="delete_process.php" onSubmit="return checkit();">
<input type="hidden" name="deleteKey" value="<?=$rndstring;?>" />
 <table id="dyntable" class="table table-bordered responsive"  >
   
    <thead>
      <tr>
        <th class="head0 nosort"><input type="checkbox" class="checkall" /></th>
        <th>Project Name</th>
        <th>User Name</th>
		<th>Start &amp; Stop</th>
		<th>Complete</th>
        <th>Saved</th>
        <th>Submit To QA</th>        
        <th>Reject</th>
		<th>Pending</th>
		<th>Record Not Found</th>
		<th>Total</th>
		<th>Status</th>
		<th>Edit</th>
      </tr>
    </thead>
    <tbody>
      <?php
	  
	  	$i=1;
		$c=0;
				  while($row=mysqli_fetch_array($rowrs))
				  {
				  	$c=$c+1;
					if($c%2==1){
						$cls = "con1";
					}else{
						$cls = "con0";
					}
					  if($row["activate"]){
						  $act = '<span style="background:#4285F4;color:#FFFFFF; padding:5px 15px;">Working</span>';
					  }else{
						  $act = '<span style="background:#EA4335;color:#FFFFFF; padding:5px 15px;">Completed</span>';
					  }

					  $qr1 = "select * from project_data where cid =".$row["proid"];
					  $rs1 = mysqli_query($db,$qr1) or die ("cannot select projects".mysqli_error($db));
					  while($row1=mysqli_fetch_array($rs1)){
						  	  $project = $row1["proname"]." - ".$row1["records"];
					  }

					  $qr1 = "select * from teammembers where sub_id=".$row["userid"];
					  $rs1 = mysqli_query($db,$qr1) or die ("cannot select projects".mysqli_error($db));
					  while($row1=mysqli_fetch_array($rs1)){
							  $username = $row1["user_fname"]." ".$row1["user_lname"];
					  }

					  $pending = $row["tot"] - $row["qareject"] - $row["rqacount"] - $row["rsave"] - $row["complete"];

					  ?>
      <tr class="<?php echo $cls;?>">
        <td class="aligncenter"><span class="center">
          <input type="checkbox" name="del<?php echo $c; ?>" value="<?php echo $row['id']; ?>" />
          </span></td>
          <td><?php echo $project; ?></td>
        <td><?php echo $username; ?></td>
		<td><?php echo $row["rstart"]." - ".$row["rend"]; ?></td>
		  <td><?php echo $row["complete"]; ?></td>
		  <td><?php echo $row["rsave"]; ?></td>
		<td><?php echo $row["rqacount"]; ?></td>
		<td><?php echo $row["qareject"]; ?></td>
		  <td><?php echo $pending; ?></td>
		  <td><?php echo $row["nrf"]; ?></td>
		  <td><?php echo $row["tot"]; ?></td>
		  <td ><?php echo $act; ?></td>

		  <td class="center"  ><a href="addallocation.php?id=<?=$row['id'];?>" title="edit"><img src="img/icons/edit_icon.png" alt="edit" width="16" height="16" /></a></td>
      </tr>
      <?php
				  }
			  ?>
    </tbody>
  </table>
  <div id="dyntable_info" class="dataTables_info">&nbsp;
    <div id="dyntable_paginate" class="dataTables_paginate paging_full_numbers" style="float:left;"> <a href="javascript:location.replace('addallocation.php');" class="first paginate_button btn-primary">Add</a> <a onClick="javascript:deleteIt();" class="first paginate_button btn-primary">Delete</a></div>
    <div class="dataTables_paginate paging_full_numbers">
      <?php
						if($InfoArray["PREV_PAGE"]) 
					    {
						?>
      <a href=<?php echo $PHP_SELF; ?>?page=1 id="dyntable_first" class="first paginate_button btn-primary" tabindex="0">First</a> <a href="<?php echo $PHP_SELF."?page=".$InfoArray["PREV_PAGE"]; ?>" id="dyntable_previous" class="previous paginate_button btn-primary" tabindex="0">Previous</a>
      <?php
						}
						if (count($InfoArray["PAGE_NUMBERS"])>1)
	 					{
							for($i=0; $i<count($InfoArray["PAGE_NUMBERS"]); $i++) 
							{
								if($InfoArray["CURRENT_PAGE"] == $InfoArray["PAGE_NUMBERS"][$i])
								{
								?>
      <a href="#" class="paginate_active btn-primary" style="font-weight:bold;" tabindex="0">
      <?=$InfoArray["PAGE_NUMBERS"][$i];?>
      </a>
      <?php							
								}
								else
								{
								?>
      <a href="<?php echo $PHP_SELF."?page=".$InfoArray["PAGE_NUMBERS"][$i]; ?>" class="paginate_button btn-primary" tabindex="0"> <?php echo $InfoArray["PAGE_NUMBERS"][$i]; ?> </a>
      <?php							
								}
							}
						}
						if($InfoArray["NEXT_PAGE"]) 
						{
						?>
      &nbsp;<a href="<?php echo $PHP_SELF."?page=".$InfoArray["NEXT_PAGE"]; ?>" id="dyntable_next" class="next paginate_button btn-primary" tabindex="0">Next</a> <a href="<?php echo $PHP_SELF."?page=".$InfoArray["TOTAL_PAGES"]; ?>" id="dyntable_last" class="last paginate_button btn-primary" tabindex="0">Last</a>
      <?php
						}
						?>
    </div>
  </div>
  <input type="hidden" name="dloop" value="<?php echo $c; ?>" />
  </form>
  </div>
  <?php
			  }
			  else
			  {
			  ?>
  <div class="toolTip tpYellow" >
    <p class="clearfix"> <img src="img/icons/light-bulb-off.png" alt="Tip!" /> <strong>No Service(s) added yet.
      <input type="button" name="sub2" value="Add New" class="formbuttonInner btn-primary" onClick="javascript:location.replace('addallocation.php');" style="width:100px;" />
      </strong></p>
  </div>
  <?php
			 }
			 ?>
</div>
</div>
  <?php require('includes/tb_footer.php'); ?>
</div>
</div>
<?php require('includes/tb_footerscript.php'); ?>
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