<?php
session_start();
include("includes/connection.php");
//include("includes/layout.php");
include("includes/paging.php");

$tabtitle = "Manage Data";
if($_SESSION["sadmin_username"]!="")
{
	$proid = $_GET["proid"];

	if($proid == ""){
		$sq = "select * from project_data order by cid limit 1";
		$rs = mysqli_query($db,$sq) or die ("cannot select Projects".mysqli_error($db));
		$arr = mysqli_fetch_array($rs);
		$proid = $arr["cid"];
		$proname = $arr["proname"];
	}
	if($_GET["page"]=="")
	{	
		$page=1;
	}
	else
	{
		$page=preg_replace ('/[^\d]/', '', $_GET['page']);
	}
	$lookup = '<i class="fa fa-dashboard"></i> &nbsp;Original Data for '.$proname;
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title><?php echo $title; ?></title>
<?php include('includes/headercss.php'); ?>
<?php include('includes/headerscripts.php'); ?>
<script src="js/piechart.js"></script>
</head>
<body>

<div id="mainwrapper" class="mainwrapper">
  <?php require('topmenu.php'); ?>
  <?php require('leftpanel.php'); ?>
  <?php require('changeskin.php'); ?>
    <div class="maincontent">
      <div class="maincontentinner" style="min-height:450px;">
        <div class="row-fluid">
          <div id="dashboard-left2" class="span12">
          <?php
	   
	  	$i = 0;
		$qr = "select * from project_data where cid=".$proid;
		$rs = mysqli_query($db,$qr) or die ("cannot select projects".mysqli_error($db));
		$row=mysqli_fetch_array($rs);

?>

<div class="clearboth"></div>

<div class="maincontent" style="padding:0px 0px; ">
<h4>Manage Original Data - <?php echo $proname; ?><?php echo $typehead; ?></h4>
</div>
<div class="maincontent">
<div class="maincontentinner boxpad widget">
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
                <?php
			  if($_SESSION["sadmin_siteAccessMessage"]!="")
			  {
			  ?>
      <div class="toolTip " >
        <p class="clearfix"> <?php echo $_SESSION["sadmin_siteAccessMessage"]; ?> </p>
      </div>
      <?php
				$_SESSION["sadmin_siteAccessMessage"]="";
				}
				?>
                
                <?php 
			$c=0;
			$qy = "select * from orgdata where proid=".$proid." order by cid";
			$per_page=50;
			$rs=mysqli_query($db,$qy) or die(mysqli_error($db)."<br>".$qy);
			$num_rows = mysqli_num_rows($rs);
			$cheight = "";
			if($num_rows<11)
			{
				$cheight = "height:550px;";
			}
		  ?>
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
					}elseif($row["status"]== 4){
						$stat = "Reject By QA";
					}
				?>
      <tr class="<?php echo $cls;?>">
        <td><?php echo $row["legal_name"]; ?></td>
        <td><?php echo $row["dba_name"]; ?></td>
        <td><?php echo $row["b_address"]; ?></td>
        <td><?php echo $row["b_city"]; ?></td>
        <td><?php echo $row["b_state"]; ?></td>
      </tr>
      <?php
				  }
			  ?>
    </tbody>
  </table>
  <div id="dyntable_info" class="dataTables_info">&nbsp;
    <div id="dyntable_paginate" class="dataTables_paginate paging_full_numbers" style="float:left;"> </div>
    <div class="dataTables_paginate paging_full_numbers">
      <?php
						if($InfoArray["PREV_PAGE"]) 
					    {
						?>
      <a href=<?php echo $PHP_SELF; ?>?page=1<?php echo $pageurl;?> id="dyntable_first" class="first paginate_button btn-primary" tabindex="0">First</a> <a href="<?php echo $PHP_SELF."?page=".$InfoArray["PREV_PAGE"].$pageurl; ?>" id="dyntable_previous" class="previous paginate_button btn-primary" tabindex="0">Previous</a>
      <?php
						}
						if (count($InfoArray["PAGE_NUMBERS"])>1)
	 					{
							for($i=0; $i<count($InfoArray["PAGE_NUMBERS"]); $i++) 
							{
								if($InfoArray["CURRENT_PAGE"] == $InfoArray["PAGE_NUMBERS"][$i])
								{
								?>
      <a href="#" class="paginate_active btn-primary" tabindex="0">
      <?=$InfoArray["PAGE_NUMBERS"][$i];?>
      </a>
      <?php							
								}
								else
								{
								?>
      <a href="<?php echo $PHP_SELF."?page=".$InfoArray["PAGE_NUMBERS"][$i].$pageurl; ?>" class="paginate_button btn-primary" tabindex="0"> <?php echo $InfoArray["PAGE_NUMBERS"][$i]; ?> </a>
      <?php							
								}
							}
						}
						if($InfoArray["NEXT_PAGE"]) 
						{
						?>
      &nbsp;<a href="<?php echo $PHP_SELF."?page=".$InfoArray["NEXT_PAGE"].$pageurl; ?>" id="dyntable_next" class="next paginate_button btn-primary" tabindex="0">Next</a> <a href="<?php echo $PHP_SELF."?page=".$InfoArray["TOTAL_PAGES"].$pageurl; ?>" id="dyntable_last" class="last paginate_button btn-primary" tabindex="0">Last</a>
      <?php
						}
						?>
    </div>
  </div>
  <input type="hidden" name="dloop" value="<?php echo $c; ?>" />
  </form>
  <?php
			  }
			  else
			  {
			  ?>
  <div class="toolTip tpYellow" >
    <p class="clearfix"> <strong>No Record(s).
      </strong></p>
  </div>
  <?php
			 }
			 ?>
          </div>
        </div>
        
      </div>
    </div>
    <?php include('includes/tb_footer.php'); ?>
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