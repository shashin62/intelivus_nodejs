<?php
session_start();
include("includes/connection.php");
//include("includes/layout.php");
include("includes/paging.php");

$_SESSION["producturl"]=full_url();
$tabtitle = "Manage Data";
if($_SESSION["sadmin_username"]!="")
{
	if( $_SESSION["sadmin_subid"]!=0 && $_SESSION["sadmin_flog"] == 0){
		print "<META http-equiv='refresh' content=0;URL=newpass.php>";
		exit;
	}
	if( $_SESSION["sadmin_subid"]!=0 && $bvduns_mgmt_rights == 1){
		print "<META http-equiv='refresh' content=0;URL=addduns.php>";
		exit;
	}
	$proid = $_GET["proid"];
	$rtype = $_GET["type"];
	$code = $_GET["code"];
	
	if($proid == ""){
		$sq = "select * from batch order by id limit 1";
		$rs = mysqli_query($db,$sq) or die ("cannot select Projects".mysqli_error($db));
		$arr = mysqli_fetch_array($rs);
		$proid = $arr["id"];
	}

	$psql = "&proid=".$proid;

	if($_GET["page"]=="")
	{	
		$page=1;
	}
	else
	{
		$page=preg_replace ('/[^\d]/', '', $_GET['page']);
	}
	$lookup = '<i class="fa fa-dashboard"></i> &nbsp;Dashboard';
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
          <h4>Work Queue Status</h4>

          <?php
	   
	  	$i = 0;
		$qr = "select * from batch where id=".$proid;
		$rs = mysqli_query($db,$qr) or die ("cannot select projects".mysqli_error($db));
		$row=mysqli_fetch_array($rs);

?>

<div class="smallboxy">
    <a href="<?php echo 'home.php?type=1'.$psql;?>"><div class="fbox" style="background:#009BBA; margin-left:5px;"><span class="absbox"><i class="fa fa-save"></i></span> <h2><?php echo $row["rsave"]; ?></h2><div class="tip" >Saved</div></div></a>
	<a href="<?php echo 'home.php?type=2'.$psql;?>"><div class="fbox" style="background:#78216F;"><span class="absbox"><i class="fa fa-paste"></i></span><h2><?php echo $row["submitqa"]; ?></h2><div class="tip">Submit To QA</div></div></a>
	<a href="<?php if($user_type_admin == "Main Rights"){echo 'home.php?type=3'.$psql;}else{echo "#";}?>"><div class="fbox" style="background:#36C4B6;"><span class="absbox"><i class="fa fa-thumbs-up"></i></span><h2><?php echo $row["complete"]; ?></h2><div class="tip">Complete</div></div></a>
	
    <a href="<?php echo 'home.php?type=4'.$psql;?>"><div class="fbox" style="background:#1D9E75;"><span class="absbox"><i class="fa fa-remove"></i></span><h2><?php echo $row["reject"]; ?></h2><div class="tip">Rejected</div></div></a>
    <a href="<?php if($user_type_admin == "Main Rights"){echo 'home.php?type=0'.$psql;}else{echo "#";}?>"><div class="fbox" style="background:#3F9C34; margin-right:0px;"><span class="absbox"><i class="fa fa-copy"></i></span><h2><?php echo $row["onhold"]; ?></h2><div class="tip">WIP</div></div></a>
</div>

<div class="clearboth"></div>
 <?php if($user_type_admin == "Main Rights" && $proid != ""){ ?>
<div class="maincontentinner boxpad" style="margin-top:20px;">
<div class="piebox">
		<div id="container1" class="margin" role="group"></div>
		<div id="container2" class="margin" role="group"></div>
		<div id="container3" class="margin" role="group"></div>
		<div id="container5" class="margin" role="group"></div>
		<div id="container4" class="margin" role="group"></div>
</div>
<script>
	$(window).load(function() {
		doughnutWidget.options = {
			container: $('#container1'),
			width: 100,
			height: 100,
			class: 'myClass',
			cutout: 60
		};
		doughnutWidget.render(data1());
	});

	function init() {
		doughnutWidget.render(data1());
	}

	function data1() {
		var data = {
			Saved: {
				val: <?php echo round(($row["rsave"]/$row["records"])*100); ?>,
				color: '#009BBA',
				link: "<?php echo 'home.php?type=1'.$psql; ?>"
			}
		};
		return data;
	}

	$(window).load(function() {
		doughnutWidget.options = {
			container: $('#container2'),
			width: 100,
			height: 100,
			class: 'myClass',
			cutout: 60
		};
		doughnutWidget.render(data2());
	});

	function init() {
		doughnutWidget.render(data2());
	}

	function data2() {
		var data = {
			Sent: {
				val: <?php echo round(($row["submitqa"]/$row["records"])*100); ?>,
				color: '#78216F',
				link: "<?php echo 'home.php?type=2'.$psql; ?>"
			}
		};
		return data;
	}

	$(window).load(function() {
		doughnutWidget.options = {
			container: $('#container3'),
			width: 100,
			height: 100,
			class: 'myClass',
			cutout: 60
		};
		doughnutWidget.render(data3());
	});

	function init() {
		doughnutWidget.render(data3());
	}

	function data3() {
		var data = {
			Complete: {
				val: <?php echo round(($row["complete"]/$row["records"])*100); ?>,
				color: '#36C4B6',
				link: "<?php echo 'home.php?type=3'.$psql; ?>"
			}
		};
		return data;
	}

	$(window).load(function() {
		doughnutWidget.options = {
			container: $('#container4'),
			width: 100,
			height: 100,
			class: 'myClass',
			cutout: 60
		};
		doughnutWidget.render(data4());
	});

	function init() {
		doughnutWidget.render(data4());
	}

	function data4() {
		var data = {
			WIP: {
				val: <?php echo round(($row["onhold"]/$row["records"])*100); ?>,
				color: '#3F9C34',
				link: "<?php echo 'home.php?type=0'.$psql; ?>"
			}
		};
		return data;
	}
	
	$(window).load(function() {
		doughnutWidget.options = {
			container: $('#container5'),
			width: 100,
			height: 100,
			class: 'myClass',
			cutout: 60
		};
		doughnutWidget.render(data5());
	});

	function init() {
		doughnutWidget.render(data5());
	}

	function data5() {
		var data = {
			Rejected: {
				val: <?php echo round(($row["reject"]/$row["records"])*100); ?>,
				color: '#1D9E75',
				link: "<?php echo 'home.php?type=4'.$psql; ?>"
			}
		};
		return data;
	}
</script>
     
</div>
<?php
}

				if($rtype == 0){
					$typehead = " - WIP";
				}elseif($rtype == 1){
					$typehead = " - Saved";
				}elseif($rtype == 2){
					$typehead = " - Submit To QA";
				}else if($rtype == 3){
					$typehead = " - Complete";
				}else if($rtype == 4){
					$typehead = " - Rejected By QA";
				}	
?>

<div class="maincontent" style="padding:0px 0px; margin-top:20px; border-top:1px solid #EAEAEA;">
<h4>Manage Data<?php echo $typehead; ?></h4>
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
			$sql = "where";
			$pageurl="";
			if($rtype!=""){
				$sql=$sql." RECORD_STATUS='".$rtype."'";
				$pageurl = "&type=".$rtype;
				if($rtype == 0){
					$typename = "WIP";
				}elseif($rtype == 1){
					$typename = "Saved";
				}elseif($rtype == 2){
					$typename = "Submit To QA";
				}else if($rtype == 3){
					$typename = "Complete";
				}else if($rtype == 4){
					$typename = "Rejected By QA";
				}	
				
			}elseif($user_qa_rights == 1){
					$sql = $sql." RECORD_STATUS=2 ";
			}else{
				$sql = $sql." RECORD_STATUS=0 ";
				$typename = "WIP";
			}
			
			if($proid!=""){
				$sql =$sql."and batch_id='".$proid."'";
				$pageurl = $pageurl."&proid=".$proid;
			}
			
			if($rtype==1 && $user_type_admin == "Sub Rights"){
				$sql = $sql." and userid='".$_SESSION["sadmin_subid"]."' ";
			}
			
			$c=0;
			$qy = "select * from input ".$sql." order by id";
			$per_page=10;
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
		  <th>DBA Name</th>
		  <th>Legal Name</th>
		  <th>Address 1</th>
		  <th>Address 2</th>
		  <th style="width:40px;">State 1</th>
		  <th style="width:40px;">State 2</th>
		  <th>City 1</th>
		  <th>City 2</th>
		  <th>Status</th>
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
					}elseif($row["status"]== 4){
						$stat = "Reject By QA";
					}
				?>
				<tr class="<?php echo $cls;?>">
				  <td><?php echo $row["COMPANY_NAME1"]; ?></td>
				  <td><?php echo $row["COMPANY_NAME2"]; ?></td>
				  <td><?php echo strrev($row["ADDRESS1"]); ?></td>
				  <td><?php echo strrev($row["ADDRESS2"]); ?></td>
				  <td><?php echo $row["STATE1"]; ?></td>
				  <td><?php echo $row["STATE2"]; ?></td>
				  <td><?php echo $row["CITY1"]; ?></td>
				  <td><?php echo $row["CITY2"]; ?></td>
				  <td><?php echo str_replace("_"," ",$row["STATUS"]); ?></td>
				  <td class="center"  ><a href="updatedata.php?id=<?=$row['ID'];?>" title="edit"><img src="img/icons/edit_icon.png" alt="edit" width="16" height="16" /></a></td>
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