<?php
session_start();
include("includes/connection.php");
if($_SESSION["sadmin_username"]!="")
{
  $operation="Add";
  $cls = "con1";
  $cls1 = "con0";

  $lookup = '<img src="img/icons/dunsapp.png" alt="" style="width: 18px; height: 18px;">  SIDE Engine';
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
        width:61% !important;
        margin-bottom:15px;
      }
      .stdform label.error{
        margin-left: 24%;
        margin-top: -10px;
        margin-bottom: 10px;
      }
    </style>

    <style>

      #tabid{
        padding: 20px;
        display: none;
      }
      .tabid table{
        width: 80%;
        margin: 0 auto;
      }
      .stdform .stdformbutton{
        margin-left: 24%;
        float: left;
      }
      h5{
        width: 78%;
        margin: 0 auto;
        padding: 10px;
        font-size: 14px !important;
        font-weight: bold;
        margin-bottom: 10px;
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
          <h4 class="widgettitle">SIDE Engine</h4>
          <div class="widgetcontent">
            <form class="stdform" name="frm1" id="login"  onsubmit="return submitForm();" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $id; ?>" />
              <input type="hidden" name="opt" value="<?php echo $operation; ?>" />
              <p>
                <label>Company Name </label>
                <span class="field">
                <input type="text" name="company" class="inputCssInnerhead" id="company"  value="<?php echo $company; ?>" required />
                </span> </p>
              <p>
                <label>Company Address</label>
                <span class="field">
                <input type="text" name="address" class="inputCssInnerhead" id="address"  value="<?php echo $address; ?>" required />
                </span> </p>
              <p>
                <label>Weblink 1</label>
                <span class="field">
                <input type="url" name="text_1" class="inputCssInnerhead" id="text_1"  value="<?php echo $txt01; ?>" required />
                </span> </p>
              <p>
                <label>Weblink 2</label>
                <span class="field">
                <input type="url" name="text_2" class="inputCssInnerhead" id="text_2"  value="<?php echo $txt02; ?>" />
                </span> </p>
              <p>
                <label>Weblink 3</label>
                <span class="field">
                <input type="url" name="text_3" class="inputCssInnerhead" id="text_3"  value="<?php echo $txt03; ?>" />
                </span> </p>
              <input type="hidden" name="dloop" value="3" />
              <p class="stdformbutton">
                <button name="submit" type="submit" style="padding:10px 15px; color: #0e0e0e;" class="btn btn-primary">Get SIC</button>
                <!-- <button name="submit2" type="button"  onClick="javascript:location.replace('managesic.php');"  class="btn btn-primary">GO BACK</button>-->
              </p>
              <div class="clearfix"></div>
              <div class="tabid" id="tabid">
                <div id="dashboard-left">
                  <h5>SIC Mappings</h5>
                </div>
                <div class="form_result"></div>
              </div>

            </form>

          </div>
        </div>
        <?php include("includes/tb_footer.php"); ?>
      </div>
    </div>
  </div>
  </div>
  <?php require('includes/tb_footerscript.php'); ?>
  <script>

    function submitForm() {
      $.ajax({type:'POST', url: 'savesic.php', data:$('#login').serialize(), success: function(response) {
        $('#login').find('.form_result').html(response);
        $('#tabid').css({ "display":"block" });
      }});

      return false;
    }

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