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
$upload_page = "";
if(strpos($_SERVER["PHP_SELF"],"manageuploads.php") || strpos($_SERVER["PHP_SELF"],"uploaddata.php"))
{
	$upload_page = 'class="active"';
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
      <li class="nav-header">URS Technologies Solutions LLC</li>
       
      <li <?php echo $home_page; ?>><a href="home.php"> <i class="fa fa-dashboard"></i> &nbsp; Dashboard</a></li>
      <?php if($page_user_management == 1){ ?>
      <li <?php echo $user_page; ?>><a href="managesubuser.php"> <i class="fa fa-user"></i> &nbsp; Manage User</a></li>
      <?php }if($page_upload_management == 1){ ?>
      <li <?php echo $upload_page; ?>><a href="manageuploads.php"> <i class="fa fa-cloud-upload"></i> &nbsp;Manage Uploads</a></li>
      <?php }
        if($sic_mgmt_rights == 1)
        { ?>
        <li <?php echo $sic_page; ?> >
        <div id="custom-show-hide-example1">
        <h3>  <img src="img/icons/dunsapp_2.png" alt="" style="width: 16px; height: 16px;"> &nbsp;SIDE Engine  <span style="float:right;"> <i id="searchicon" class="fa fa-caret-left"></i></span></h3>
        <div style="padding-left:20px; padding-bottom:5px; padding-top:5px;">
            <ul>
            <li style="margin-bottom:10px;"><a href="addsic.php"> <i class="fa fa-minus"></i> &nbsp;Get SIC</a></li>
            <li><a href="managesic.php"> <i class="fa fa-minus"></i> &nbsp;Manage SIC</a></li>
         	</ul>
        </div>

    </div>
    </li>
        <?php } ?>
        <?php if($bvduns_mgmt_rights == 1){ ?>
            <li <?php echo $engine_page; ?>>
                <div id="custom-show-hide-example3">
                    <h3>  <img src="img/icons/bv_2.png" alt="" style="width: 16px; height: 16px;"> &nbsp;DUNS Engine <span style="float:right;"> <i id="searchicon" class="fa fa-caret-left"></i></span></h3>
                    <div style="padding-left:20px; padding-bottom:5px; padding-top:5px;">
                        <ul>
                            <li style="margin-bottom:10px;"><a href="addduns.php"> <i class="fa fa-minus"></i> &nbsp;Get DUNS Data</a></li>
                            <li><a href="manageduns.php"> <i class="fa fa-minus"></i> &nbsp;Manage DUNS Data</a></li>
                        </ul>
                    </div>

                </div>
            </li>
        <?php } ?>
       <li <?php echo $editprofile; ?>><a href="editprofile.php"> <i class="fa fa-user"></i> &nbsp; Edit Profile</a></li>
      <li><a href="javascript:log_out()"> <i class="fa fa-lock"></i> &nbsp; Logout</a></li>
        <?php if($user_type_admin == "Main Rights"){ ?>
      <li><div id="custom-show-hide-example">
        <h3> <i class="fa fa-search"></i> &nbsp; Search Data <span style="float:right;"> <i id="searchicon" class="fa fa-caret-left"></i></span></h3>
        <div>
            <form name="frmSearch" action="searchdata.php" class="sideform widget" method="get">
                <select name="qt" class="uniformselect">
                    <?php
                    if($result_type == 1){$jun = "selected";}
                    elseif($result_type == 2){$may = "selected";}
                    elseif($result_type == 3){$legal = "selected";}
                    elseif($result_type == 4){$dba = "selected";}
                    ?>
                    <option value="1" <?php echo $jun; ?>>DUNS</option>
                    <option value="3" <?php echo $legal; ?>>Legal Name</option>
                    <option value="4" <?php echo $dba; ?>>DBA Name</option>
                </select>
                <input type="text" value="<?php echo $result_seach; ?>" name="q" />
                <input type="submit" value="Search" class="btn btn-primary" />
            </form>
        </div>

    </div>
    </li>
        <?php } ?>
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

        new jQueryCollapse($("#custom-show-hide-example3"), {
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
