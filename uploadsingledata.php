<?php
session_start();
include("includes/connection.php");
if ($_SESSION["sadmin_username"] != "") {
    if ($page_upload_management != 1) {
        $_SESSION["sadmin_siteAccessMessage"] = $access_message;
        print "<META http-equiv='refresh' content=0;URL=home.php>";
        exit;
    }
    $operation = "Add";
    $id = $_GET["id"];

    if ($id != "" and is_numeric($id)) {
        $cname = "";
        $company = "";
        $address = "";

        $qry = "select * from project_data where cid=" . $id;
        $result = mysqli_query($db, $qry) or die("cannot select Data " . mysqli_error($db));
        if ($row = mysqli_fetch_array($result)) {
            $operation = "Edit";
            $cname = $row["proname"];
            $company = $row["company"];
            $address = $row["comp_address"];
            $activate = $row["activate"];
        } else {
            print "<META http-equiv='refresh' content=0;URL=home.php>";
            exit;
        }
    } else {
        $operation = "Add";
        $cname = "";
        $company = "";
        $address = "";
    }

    $lookup = '<i class="fa fa-cogs"></i> SOS Engine';
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <title><?php echo $title; ?></title>
    <?php include('includes/headercss.php'); ?>
    <?php include('includes/headerscripts.php'); ?>
            <script>
                function clearRecord() {

                    var r = confirm("You are about to clear all records for this project!");
                    if (r == true) {
                        document.getElementById("frmclear").submit();
                    } else {
                        alert("Records Are Safe!");
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
    if ($_SESSION["sadmin_changeImage_Delete"] != "") {
        ?>
                        <div class="toolTip " >
                            <p class="clearfix"> <?php echo $_SESSION["sadmin_changeImage_Delete"]; ?> </p>
                        </div>
                    <?php
                    $_SESSION["sadmin_changeImage_Delete"] = "";
                }
                ?>
                    <div class="maincontentinner">
                        <div class="widget">
                            <h4 class="widgettitle">SOS Engine</h4>
                            <div class="widgetcontent">
                                <form class="stdform" name="frm1" id="login" action="importsingledata.php" method="post" enctype="multipart/form-data">
                                <p>
                                        <label>Project</label>
                                        <span class="field">
                                            <select name="proid" class="uniformselect" >
                                                <option value=""> - Select Project -</option>
                                                <?php

                                                        if($user_type_admin == "Main Rights" || $user_qa_rights == 1){
                                                            $qr = "select * from project_data";
                                                            $rs = mysqli_query($db,$qr) or die ("cannot select projects".mysqli_error($db));
                                                            while($row=mysqli_fetch_array($rs)){
                                                                if($row["cid"]==$proid){
                                                                    echo '<option value="'.$row["cid"].'" selected>'.$row["proname"].'</option>';
                                                                }else{
                                                                    echo '<option value="'.$row["cid"].'">'.$row["proname"].'</option>';
                                                                }
                                                            }
                                                        }else if($user_type_admin == "Sub Rights" || $user_qa_rights != 1){
                                                        
                                                            
                                                            $qr = "select * from allocat where activate=1 and userid='" . $_SESSION["sadmin_subid"] . "' group by proid";
                                                            $rs = mysqli_query($db,$qr) or die ("cannot select projects".mysqli_error($db));
                                                            while($row=mysqli_fetch_array($rs)){
                                                            
                                                            $qr1 = "select * from project_data where activate=1 and cid=".$row["proid"];
                                                            $rs1 = mysqli_query($db,$qr1) or die ("cannot select projects".mysqli_error($db));
                                                            $row1=mysqli_fetch_array($rs1);
                                                                if($row["proid"]==$proid){
                                                                    echo '<option value="'.$row1["cid"].'" selected>'.$row1["proname"].'</option>';
                                                                }else{
                                                                    echo '<option value="'.$row1["cid"].'">'.$row1["proname"].'</option>';
                                                                }
                                                            }
                                                        
                                                        }
                                                        ?>
                                                </select>
                                            </span>
                                    </p>
                                    
                                    <input type="hidden" name="id" value="<?php echo $id; ?>" />
                                    <input type="hidden" name="opt" value="<?php echo $operation; ?>" />
<!--                                    <p>
                                        <label>Serial</label>
                                        <span class="field">
                                            <input type="text" name="serial" class="input-xxlarge" id="dserial"  value="<?php echo $serial; ?>" />
                                        </span> </p>-->
                                    <p>
                                        <label>Legal Name</label>
                                        <span class="field">
                                            <input type="text" name="legal_name" class="input-xxlarge" id="dlegal_name"  value="<?php echo $legal_name; ?>" />
                                        </span> </p>
                                    <p>
                                        <label>DBA Name</label>
                                        <span class="field">
                                            <input type="text" name="dba_name" class="input-xxlarge" id="ddba_name"  value="<?php echo $dba_name; ?>" />
                                        </span> </p>
                                    <p>
                                        <label>Address</label>
                                        <span class="field">
                                            <input type="text" name="b_address" class="input-xxlarge" id="db_address"  value="<?php echo $b_address; ?>" />
                                        </span> </p>

                                    <p>
                                        <label>City</label>
                                        <span class="field">
                                            <input type="text" name="b_city" class="input-xxlarge" id="db_city"  value="<?php echo $b_city; ?>" />
                                        </span> </p>

                                    <p>
                                        <label>State</label>
                                        <span class="field">
                                            <input type="text" name="b_state" class="input-xxlarge" id="db_state"  value="<?php echo $b_state; ?>" />
                                        </span> </p>

                                    <p>
                                        <label>Zip Code</label>
                                        <span class="field">
                                            <input type="text" name="a_code" class="input-xxlarge" id="da_code"  value="<?php echo $a_code; ?>" />
                                        </span> </p>

<!--                                    <p>
                                        <label>Signer</label>
                                        <span class="field">
                                            <input type="text" name="a_signer" class="input-xxlarge" id="da_signer"  value="<?php echo $a_signer; ?>" />
                                        </span> </p>
                                        
                                        <p>
                                        <label>Weblink</label>
                                        <span class="field">
                                            <input type="text" name="weblink" class="input-xxlarge" id="dweblink"  value="<?php echo $weblink; ?>" />
                                        </span> </p>-->



    <!--                <p>
                   <label>Activated</label>
                  <span class="formwrapper">
                  <input type="checkbox" name="act" id="act" value="1" <?php if ($activate == 1) {
                    echo "checked";
                } ?> />
                  </span> </p>-->

    <!--                <p class="stdformbutton">
                    <button name="clearbtn" type="button" onClick="javascript:clearRecord();" class="btn btn-primary">Clear Records</button>
                    </p>-->

                                    <p class="">
                                        <label></label>
                                        <button name="submit" type="submit" class="btn btn-primary">Get SOS Data</button>
<!--                                        <button name="submit2" type="button"  onClick="javascript:location.replace('home.php');"  class="btn btn-primary">Cancel</button>-->
                                    </p>
                                </form>
                            </div>
                        </div>
    <?php include("includes/tb_footer.php"); ?>
                        <form name="frmclear" id="frmclear" action="clearrecords.php" method="post">
                            <input type="hidden" name="proid" value="<?php echo $id; ?>" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php require('includes/tb_footerscript.php'); ?>
        <script>
            $(document).ready(function () {

                $("#login").validate({
                    rules: {
                        "serial": {
                            required: true
                        },
                        "legal_name": {
                            required: true
                        },
                        "dba_name": {
                            required: true
                        },
                        "b_state": {
                            required: true
                        }
                    },
                    messages: {
                        "serial": {
                            required: "You must Enter a Serial"
                        },
                        "legal_name": {
                            required: "Your must Enter a Legal Name"
                        },
                        "dba_name": {
                            required: "You must enter a DBA Name"
                        },
                        "b_state": {
                            required: "You must enter a State"
                        }
                    }
                });
            });
        </script>
    </body>
    </html>
    <?php
} else {
    print "<META http-equiv='refresh' content=0;URL=index.php>";
    exit;
}
?>