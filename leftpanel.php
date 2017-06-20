<style>
ul{
	list-style:none;
}
ul.leftmenu li{
border:1px solid #CCCCCC; 
margin-left:-20px;
padding:5px 10px;
}
</style>
<?php

$home_page = "";
if(strpos($_SERVER["PHP_SELF"],"home.php"))
{
	$home_page = 'class="active"';
}
$user_page = "";
if(strpos($_SERVER["PHP_SELF"],"managesubuser.php") || strpos($_SERVER["PHP_SELF"],"addsubuser.php"))
{
	$user_page = 'class="active"';
}
$alloc_page = "";
if(strpos($_SERVER["PHP_SELF"],"manageallocation.php") || strpos($_SERVER["PHP_SELF"],"addallocation.php"))
{
    $alloc_page = 'class="active"';
}
$upload_page = "";
if(strpos($_SERVER["PHP_SELF"],"manageuploads.php") || strpos($_SERVER["PHP_SELF"],"uploaddata.php"))
{
	$upload_page = 'class="active"';
}
$singleupload_page = "";
if(strpos($_SERVER["PHP_SELF"],"uploadsingledata.php"))
{
	$singleupload_page = 'class="active"';
}
$sic_page = "";
if(strpos($_SERVER["PHP_SELF"],"managesic.php") || strpos($_SERVER["PHP_SELF"],"addsic.php"))
{
    $sic_page =  'class="active"';
}
$editprofile = "";
if(strpos($_SERVER["PHP_SELF"],"editprofile.php"))
{
	$editprofile = 'class="active"';
}

		?>
<div class="leftpanel">
  <div class="leftmenu">
    <ul class="nav nav-tabs nav-stacked">
      <li class="nav-header">Personalization Initiative</li>
      <li <?php echo $home_page; ?>><a href="home.php"> <i class="fa fa-dashboard"></i> &nbsp; Dashboard</a></li>
      <?php if($page_user_management == 1){ ?>
      <li <?php echo $user_page; ?>><a href="managesubuser.php"> <i class="fa fa-user"></i> &nbsp; Manage User</a></li>
      <li <?php echo $alloc_page; ?>><a href="manageallocation.php"> <i class="fa fa-user"></i> &nbsp; Manage Allocation</a></li>
      <?php }if($page_upload_management == 1){ ?>
      <li <?php echo $upload_page; ?>><a href="manageuploads.php"> <i class="fa fa-cloud-upload"></i> &nbsp;Manage Uploads</a></li>
      <li <?php echo $singleupload_page; ?>><a href="uploadsingledata.php"> <i class="fa fa-cogs"></i> &nbsp;SOS Engine</a></li>
        <?php } ?>
       <li <?php echo $editprofile; ?>><a href="editprofile.php"> <i class="fa fa-user"></i> &nbsp; Edit Profile</a></li>
      <li><a href="javascript:log_out()"> <i class="fa fa-lock"></i> &nbsp; Logout</a></li>
      <li><div id="custom-show-hide-example">
        <h3> <i class="fa fa-search"></i> &nbsp; Search Data <span style="float:right;"> <i id="searchicon" class="fa fa-caret-left"></i></span></h3>
        <div>
            <form name="frmSearch" action="searchdata.php" class="sideform widget" method="get">
                <select name="qt" class="uniformselect">
                    <?php
                    if($result_type == 1){$se10 = "selected";}
                    elseif($result_type == 2){$legal = "selected";}
                    elseif($result_type == 3){$dba = "selected";}
                    ?>
                    <option value="1" <?php echo $se10; ?>>SE 10</option>
                    <option value="2" <?php echo $legal; ?>>Legal Name</option>
                    <option value="3" <?php echo $dba; ?>>DBA Name</option>
                </select>
                <input type="text" value="<?php echo $result_seach; ?>" name="q" />
                <input type="submit" value="Search" class="btn btn-primary" />
            </form>
        </div>

    </div>
    </li>
    </ul>
  </div>
    
    <script>
        new jQueryCollapse($("#custom-show-hide-example"), {
            open: function() {
                this.slideDown(150);
				 $("#searchicon").removeClass('fa-caret-left');
    			 $("#searchicon").addClass('fa-caret-down');
            },
            close: function() {
                this.slideUp(150);
				 $("#searchicon").removeClass('fa-caret-down');
    			 $("#searchicon").addClass('fa-caret-left');
            }
        });
		new jQueryCollapse($("#custom-show-hide-example1"), {
            open: function() {
                this.slideDown(150);
				 $("#searchicon").removeClass('fa-caret-left');
    			 $("#searchicon").addClass('fa-caret-down');
            },
            close: function() {
                this.slideUp(150);
				 $("#searchicon").removeClass('fa-caret-down');
    			 $("#searchicon").addClass('fa-caret-left');
            }
        });
    </script>

  <!--leftmenu-->
  
</div>
<!-- leftpanel -->
