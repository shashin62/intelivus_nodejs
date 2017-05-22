<?php
session_start();
include("includes/connection.php");
include("includes/layout.php");
include("includes/paging.php");
include("includes/functions.php");


$_SESSION["producturl"]=full_url();

if($_SESSION["sadmin_username"]!="")
{
		$result_seach = mysqli_real_escape_string($db,StringRepair3($_GET["q"]));
		$result_type = mysqli_real_escape_string($db,StringRepair3($_GET["qt"]));
		
		if($result_seach == "")
		{
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
		if($page == "")
		{
			$page=1;
		}
		$pageurl = "&qt=".$result_type."&st=".$result_status."&q=".$result_seach;
		$colname = "legal_name";
		if($result_type==1){
			$colname = "serial";
		}elseif($result_type==2){
			$colname = "legal_name";
		}elseif($result_type==3){
			$colname = "dba_name";
		}
		
		$qy="select * from data where (".$colname." like '%".$result_seach."%' ) order by cid";
		$srh=mysqli_query($db,$qy) or die("cannot select product".mysqli_error($db));
		$num_rows=mysqli_num_rows($srh);
		$page_para = "&amp;q=".$result_seach;
		$per_page=10;
		
		$lookup = '<i class="fa fa-search"></i> Manage Search Data';
			
	//$rndstring=base64_encode(rand(11111111,99999999)."/deleteHoneymoon");
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
<?php 
			if($num_rows<11)
			{
				$cheight = "height:550px;";
			}
		  ?>
<div class="maincontent" style="min-height:550px;">
<div class="maincontentinner" >
 <div class="row-fluid">
 <div id="dashboard-left" class="span12">
 <h5>Manage Search</h5>
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
		  <th>Legal Name</th>
		  <th>DBA Name</th>
        <th>Address</th>
        <th>City</th>
        <th>State</th>
        <th>Action Code</th>
        <th>Edit</th>
      </tr>
    </thead>
    <tbody>
      <?php
				  while($row=mysqli_fetch_array($rowrs))
				  {
				  	$c=$c+1;
					if($c%2==1){
						$cls = "con1";
					}else{
						$cls = "con0";
					}
					if($row["status"]== 0){
						$stat = "Hold";
					}elseif($row["status"]== 1){
						$stat = "Save";
					}elseif($row["status"]== 2){
						$stat = "Submit To QA";
					}elseif($row["status"]== 3){
						$stat = "Complete";
					}
				?>
      <tr class="<?php echo $cls;?>">
        <td><?php echo $row["legal_name"]; ?></td>
        <td><?php echo $row["dba_name"]; ?></td>
        <td><?php echo $row["b_address"]; ?></td>
        <td><?php echo $row["b_city"]; ?></td>
        <td><?php echo $row["b_state"]; ?></td>
        <td><?php echo $row["a_code"]; ?></td>
        <td class="center" ><a href="updatedata.php?id=<?=$row['cid'];?>" title="edit"><img src="img/icons/edit_icon.png" alt="edit" width="16" height="16" /></a></td>
      </tr>
      <?php
				  }
			  ?>
    </tbody>
  </table>
  <div id="dyntable_info" class="dataTables_info">&nbsp;
	  <?php echo $num_rows; ?> Records Found Using keyword '<?php echo $result_seach; ?>'
    <div id="dyntable_paginate" class="dataTables_paginate paging_full_numbers" style="float:left;"> </div>
    <div class="dataTables_paginate paging_full_numbers">
      <?php
						if($InfoArray["PREV_PAGE"]) 
					    {
						?>
      <a href=<?php echo $PHP_SELF; ?>?page=1<?php echo $pageurl; ?> id="dyntable_first" class="first paginate_button " tabindex="0">First</a> <a href="<?php echo $PHP_SELF."?page=".$InfoArray["PREV_PAGE"].$pageurl; ?>" id="dyntable_previous" class="previous paginate_button " tabindex="0">Previous</a>
      <?php
						}
						if (count($InfoArray["PAGE_NUMBERS"])>1)
	 					{
							for($i=0; $i<count($InfoArray["PAGE_NUMBERS"]); $i++) 
							{
								if($InfoArray["CURRENT_PAGE"] == $InfoArray["PAGE_NUMBERS"][$i])
								{
								?>
      <a href="#" class="paginate_active" tabindex="0">
      <?=$InfoArray["PAGE_NUMBERS"][$i];?>
      </a>
      <?php							
								}
								else
								{
								?>
      <a href="<?php echo $PHP_SELF."?page=".$InfoArray["PAGE_NUMBERS"][$i].$pageurl; ?>" class="paginate_button" tabindex="0"> <?php echo $InfoArray["PAGE_NUMBERS"][$i]; ?> </a>
      <?php							
								}
							}
						}
						if($InfoArray["NEXT_PAGE"]) 
						{
						?>
      &nbsp;<a href="<?php echo $PHP_SELF."?page=".$InfoArray["NEXT_PAGE"].$pageurl; ?>" id="dyntable_next" class="next paginate_button" tabindex="0">Next</a> <a href="<?php echo $PHP_SELF."?page=".$InfoArray["TOTAL_PAGES"].$pageurl; ?>" id="dyntable_last" class="last paginate_button" tabindex="0">Last</a>
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
    <p class="clearfix"> <strong>No Record(s) Found.
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