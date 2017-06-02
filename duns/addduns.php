<?php
session_start();
include("includes/connection.php");

if($_SESSION["sadmin_username"]!="")
{
  $operation="Add";

  $lookup = '<img src="img/icons/bv.png" alt="" style="width: 18px; height: 18px;">  DUNS Engine';
  ?>
  <!DOCTYPE html>
  <html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $title; ?></title>
    <?php include('includes/headercss.php'); ?>
    <?php include('includes/headerscripts.php'); ?>
    <style>
      .stdform label.inputCssLabel
      {
        float: left; !important;
        margin-bottom:15px;
        width: 20% !important;
        padding-right: 43px;
      }
      .stdform input.inputCssInnerhead
      {
        width:83% !important;
        margin-bottom:15px;
      }
      .stdform label.error{
        margin-left: 24%;
        margin-top: -10px;
        margin-bottom: 10px;
      }
	  .stdform .stdformbutton{
	  	margin-left:24%;
		float:left;
	  }
    </style>

    

    <script type='text/javascript'>
      var isCtrl = false;
      document.onkeyup=function(e)
      {
        if(e.which == 17)
          isCtrl=false;
      }
      document.onkeydown=function(e) {
        if (e.which == 17)
          isCtrl = true;
        if((e.which == 85)  && (isCtrl == true)){
          return false;
        }
        if((e.which == 88) && (isCtrl == true)){
          return true;
        }
        if((e.which == 86) && (isCtrl == true) ){
          return true;
        }
        if((e.which == 67) && (isCtrl == true)){
          return true;
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
          <h4 class="widgettitle">GET DUNS Data</h4>
          <div class="widgetcontent">
            <form class="stdform" name="frm1" id="login"  action="saveduns.php" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $id; ?>" />
              <input type="hidden" name="opt" value="<?php echo $operation; ?>" />
              <p>
                <label>Company Name</label>
                <span class="field">
                <input type="text" name="company1" class="inputCssInnerhead" id="company1" style="width: 40% !important; float:left;" oninput="this.title = this.value" value="<?php echo $company1; ?>" placeholder="Enter DBA Name" required />
                 <input type="text" name="company2" class="inputCssInnerhead" id="company2" style="width: 40% !important; float:left; margin-left:15px;" oninput="this.title = this.value"  value="<?php echo $company2; ?>"  placeholder="Enter Legal Name"  />
                </span> </p>


              <p>
                <label>Street Address</label>
                <span class="field">
                <input type="text" name="address1" class="inputCssInnerhead" id="address1" style="width: 40% !important; float:left;"  value="<?php echo $address1; ?>"  placeholder="Enter Street Address"  />
                <input type="text" name="address2" class="inputCssInnerhead" id="address2" style="width: 40% !important; float:left; margin-left:15px;"  value="<?php echo $address2; ?>"  placeholder="Enter Street Address"  />
                </span> </p>
              <p>
                <label>City</label>
                <span class="field">
                <input type="text" name="city1" class="inputCssInnerhead" id="city1" style="width: 40% !important; float:left;"  value="<?php echo $city1; ?>" placeholder="Enter City 1"  />
                <input type="text" name="city2" class="inputCssInnerhead" id="city2" style="width: 40% !important; float:left; margin-left:15px;" value="<?php echo $city2; ?>" placeholder="Enter City 2"  />
                </span> </p>

              <p>
                <label>State</label>
                <span class="field">
                <input type="text" name="state1" class="inputCssInnerhead" id="state1" style="width: 40% !important; float:left;"  value="<?php echo $state1; ?>" placeholder="Enter State 1"  />
                <input type="text" name="state2" class="inputCssInnerhead" id="state2" style="width: 40% !important; float:left; margin-left:15px;" value="<?php echo $state2; ?>" placeholder=" Enter State 2"  />
                </span> </p>
              <p>
                <label>Phone</label>
                <span class="field">
                <input type="text" name="phone1" class="inputCssInnerhead" id="phone1" style="width: 40% !important; float:left;"  value="<?php echo $phone1; ?>"  placeholder="Enter Phone 1"  />
                <input type="text" name="phone2" class="inputCssInnerhead" id="phone2" style="width: 40% !important; float:left; margin-left:15px;" value="<?php echo $phone2; ?>"  placeholder="Enter Phone 2"  />
                </span> </p>

              <p>
                <label>Zip Code</label>
                <span class="field">
                <input type="text" name="pincode1" class="inputCssInnerhead" id="pincode1" style="width: 40% !important; float:left;"  value="<?php echo $pincode1; ?>" placeholder="Enter Zip Code 1"  />
                <input type="text" name="pincode2" class="inputCssInnerhead" id="pincode2" style="width: 40% !important; float:left; margin-left:15px;" value="<?php echo $pincode2; ?>" placeholder="Enter Zip Code 2"  />
                </span> </p>

              <p>
                <label>Given Duns</label>
                <span class="field">
                <input type="text" name="gduns" class="inputCssInnerhead" id="gduns"  value="<?php echo $gduns; ?>" style="width: 25% !important;"   placeholder="Enter DUNS"  />
                </span> </p>

              <input type="hidden" name="dloop" value="3" />
              <p class="stdformbutton">
                <button name="submit" type="submit" style="padding:10px 15px; color: #0e0e0e;" class="btn btn-primary">Get DUNS</button>
                <!-- <button name="submit2" type="button"  onClick="javascript:location.replace('managesic.php');"  class="btn btn-primary">GO BACK</button>-->
              </p>
              
            </form>

              <div class="clearfix"></div>

        </div>
          </div>

        <?php include("includes/tb_footer.php"); ?>
      </div>
    </div>
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