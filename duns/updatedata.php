<?php
session_start();
include("includes/connection.php");
include("includes/paging.php");
if($_SESSION["sadmin_username"]!="")
{

    if($_GET["page"]=="")
    {
        $page=1;
    }
    else
    {
        $page=preg_replace ('/[^\d]/', '', $_GET['page']);
    }
    $operation="Edit";
    $id=$_GET["id"];
    if($id!="" and is_numeric($id))
    {
        $qry="select * from input where id=".$id;
        $result=mysqli_query($db,$qry) or die("cannot select DUNS Data ".mysqli_error($db));
        if($row=mysqli_fetch_array($result))
        {
            $operation="Edit";
            $company1= $row["COMPANY_NAME1"];
            $company2= $row["COMPANY_NAME2"];
            $gduns= $row["DUNS"];
            $address1= strrev($row["ADDRESS1"]);
            $address2= strrev($row["ADDRESS2"]);
            $phone1= $row["PHONE1"];
            $phone2= $row["PHONE2"];
            $pincode1= $row["ZIPCODE1"];
            $pincode2= $row["ZIPCODE2"];
            $city1= $row["CITY1"];
            $city2= $row["CITY2"];
            $state1= $row["STATE1"];
            $state2= $row["STATE2"];
            $state2= $row["STATE2"];
            $rtype= $row["RECORD_STATUS"];
            $cid = $row["CID"];
            $proid = $row["BATCH_ID"];

        }
        else
        {
            print "<META http-equiv='refresh' content=0;URL=manageduns.php>";
            exit;
        }
    }
    $cls = "con1";
    $cls1 = "con0";
    $lookup = '<img src="img/icons/bv.png" alt="" style="width: 18px; height: 18px;">   DUNS Engine';
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
        </style>
        <style>


            #tabid{
                padding: 20px;
                display: block;
            }
            .tabid table{
                width: 80%;
                margin: 0 auto;
            }
            .stdform .stdformbutton{
                margin-right: 11%;
                float: right;
            }
            h5{
                width: 78%;
                margin: 0 auto;
                padding: 10px;
                font-size: 14px !important;
                font-weight: bold;
                margin-bottom: 10px;
            }
            .dataTables_info{
                width: 78%;
                margin: 0 auto;
                height: 21px;
            }
            .toolTip{
                width: 78%;
                margin: 0 auto;
                height: 21px;
            }
        </style>

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
                        <form class="stdform" name="frm1" id="login" action="saverecords.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?php echo $id; ?>" />
                            <input type="hidden" name="opt" value="<?php echo $operation; ?>" />
                            <input type="hidden" name="proid" value="<?php echo $proid; ?>" />
                            <input type="hidden" name="rtype" value="<?php echo $rtype; ?>" />
                            <p>
                                <label>Company Name</label>
                <span class="field">
                <input type="text" name="company1" disabled class="inputCssInnerhead" oninput="this.title = this.value" id="company" style="width: 40% !important; float:left;"    value="<?php echo $company1; ?>" placeholder="Enter DBA Name" required />
                <input type="text" name="company2" disabled class="inputCssInnerhead" oninput="this.title = this.value" id="company2" style="width: 40% !important; float:left; margin-left:15px;"   value="<?php echo $company2; ?>"  placeholder="Enter Legal Name"  />
                </span> </p>


                            <p>
                                <label>Street Address</label>
                <span class="field">
                <input type="text" name="address1" disabled class="inputCssInnerhead" id="address1" style="width: 40% !important; float:left;"  value="<?php echo $address1; ?>"  placeholder="Enter Street Address 1"  />
                <input type="text" name="address2" disabled class="inputCssInnerhead" id="address2" style="width: 40% !important; float:left; margin-left:15px;"  value="<?php echo $address2; ?>"  placeholder="Enter Street Address 2"  />
                </span> </p>
                            <p>
                                <label>City</label>
                <span class="field">
                <input type="text" name="city1" disabled class="inputCssInnerhead" id="city1" style="width: 40% !important; float:left;"  value="<?php echo $city1; ?>" placeholder="Enter City  1"  />
                <input type="text" name="city2" disabled class="inputCssInnerhead" id="city2" style="width: 40% !important; float:left; margin-left:15px;" value="<?php echo $city2; ?>" placeholder="Enter City  2"  />
                </span> </p>

                            <p>
                                <label>State</label>
                <span class="field">
                <input type="text" name="state1" disabled class="inputCssInnerhead" id="state1" style="width: 40% !important; float:left;"  value="<?php echo $state1; ?>" placeholder="Enter State  1"  />
                <input type="text" name="state2" disabled class="inputCssInnerhead" id="state2" style="width: 40% !important; float:left; margin-left:15px;" value="<?php echo $state2; ?>" placeholder=" Enter State  2"  />
                </span> </p>

                            <p>
                                <label>Phone</label>
                <span class="field">
                <input type="text" name="phone1" disabled class="inputCssInnerhead" id="phone1" style="width: 40% !important; float:left;"  value="<?php echo $phone1; ?>"  placeholder="Enter Phone  1"  />
                <input type="text" name="phone2" disabled class="inputCssInnerhead" id="phone2" style="width: 40% !important; float:left; margin-left:15px;" value="<?php echo $phone2; ?>"  placeholder="Enter Phone  2"  />
                </span> </p>

                            <p>
                                <label>Zip Code</label>
                <span class="field">
                <input type="text" name="pincode1" disabled class="inputCssInnerhead" id="pincode1" style="width: 40% !important; float:left;"  value="<?php echo $pincode1; ?>" placeholder="Enter Zip Code 1"  />
                <input type="text" name="pincode2" disabled class="inputCssInnerhead" id="pincode2" style="width: 40% !important; float:left; margin-left:15px;" value="<?php echo $pincode2; ?>" placeholder="Enter Zip Code 2"  />
                </span> </p>

                            <p>
                                <label>Given Duns</label>
                <span class="field">
                <input type="text" name="gduns" disabled class="inputCssInnerhead" id="gduns"  value="<?php echo $gduns; ?>" style="width: 25% !important;"  placeholder="Enter  DUNS"  />
                </span> </p>



                            <div class="clearfix"></div>

                            <?php

                            $c=0;
                            $qy = "select * from output where input_id=".$cid." order by id desc ";
                            $per_page=10;
                            $rs=mysqli_query($db,$qy) or die(mysqli_error($db));
                            $num_rows = mysqli_num_rows($rs);

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

                                <div class="tabid" id="tabid">
                                    <div id="dashboard-left">
                                        <h5>D&B Verified Data</h5>
                                    </div>
                                    <?php
                                    $i = 0;
                                    $j = 0;echo '<table class="table table-bordered responsive">
                        <thead>
                        <tr >
                            <th>&nbsp;</th>
                            <th>Comp Name</th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th>Zip Code</th>
                            <th>City</th>
                            <th>State</th>
                            <th>D&B DUNS</th>
                            <th>Immediate Parent</th>
                            <th>Location Type</th>
                            <th>Matching Code</th>
                        </tr>
                        </thead>
                        <tbody>';

                                    $mj=0;


                                    $i=1;
                                    $c=0;
                                    while($row=mysqli_fetch_array($rowrs))
                                    {
                                        $mj++;
                                        if($mj % 2 == 0){
                                            echo '<tr class="con0">';
                                        }else{
                                            echo '<tr class="con1">';
                                        }
                                        if($row["IS_TRUE"]==1){
                                            $check = "checked";
                                        }else{ $check=""; }
                                        echo '<td><input type="checkbox" name="del_'.$mj.'" value="'.$row["ID"].'" '.$check.' /></td>';
                                        echo "<td>".$row["COMPANY_NAME"]."</td>";
                                        echo "<td>".strrev($row["ADDRESS"])."</td>";
                                        echo "<td> &nbsp; </td>";
                                        echo "<td>".$row["ZIPCODE"]."</td>";
                                        echo "<td>".$row["CITY"]."</td>";
                                        echo "<td>".$row["STATE"]."</td>";
                                        echo "<td>".$row["HOOVERS_DUNS"]."</td>";
                                        echo "<td>".$row["IMMEDIATE_PARENT"]."</td>";
                                        echo "<td>".$row["LOCATION_TYPE"]."</td>";
                                        echo "<td>".$row["MATCHING_CODE"]."</td>";
                                        echo "</tr>";

                                    }

                                    echo '   </tbody>
             </table>
			 <input type="hidden" name="dloop" value="'.$mj.'" />
			 
             ';

                                    $startpos = (($page-1)*$per_page+1);
                                    $endpos = (($page-1)*$per_page+$per_page);
                                    if($endpos > $num_rows){
                                        $endpos = $num_rows;
                                    }
                                    if($num_rows > 10) {

                                        ?>

                                        <div id="dyntable_info" class="dataTables_info">&nbsp;
                                            <div id="dyntable_paginate" class="dataTables_paginate paging_full_numbers"
                                                 style="float:left;">
                                                <?php echo 'Showing ' . $startpos . ' to ' . $endpos . ' of ' . $num_rows . '  Records'; ?></div>
                                            <div class="dataTables_paginate paging_full_numbers">
                                                <?php
                                                if ($InfoArray["PREV_PAGE"]) {
                                                    ?>
                                                    <a href=<?php echo $PHP_SELF; ?>?id=<?= $id; ?>&page=1 id="dyntable_first"
                                                       class="first paginate_button btn-primary" tabindex="0">First</a> <a
                                                        href="<?php echo $PHP_SELF . "?id=" . $id . "&page=" . $InfoArray["PREV_PAGE"]; ?>"
                                                        id="dyntable_previous" class="previous paginate_button btn-primary"
                                                        tabindex="0">Previous</a>
                                                    <?php
                                                }
                                                if (count($InfoArray["PAGE_NUMBERS"]) > 1) {
                                                    for ($i = 0; $i < count($InfoArray["PAGE_NUMBERS"]); $i++) {
                                                        if ($InfoArray["CURRENT_PAGE"] == $InfoArray["PAGE_NUMBERS"][$i]) {
                                                            ?>
                                                            <a href="#" class="paginate_active btn-primary" tabindex="0">
                                                                <?= $InfoArray["PAGE_NUMBERS"][$i]; ?>
                                                            </a>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <a href="<?php echo $PHP_SELF . "?id=" . $id . "&page=" . $InfoArray["PAGE_NUMBERS"][$i]; ?>"
                                                               class="paginate_button btn-primary"
                                                               tabindex="0"> <?php echo $InfoArray["PAGE_NUMBERS"][$i]; ?> </a>
                                                            <?php
                                                        }
                                                    }
                                                }
                                                if ($InfoArray["NEXT_PAGE"]) {
                                                    ?>
                                                    &nbsp;<a
                                                        href="<?php echo $PHP_SELF . "?id=" . $id . "&page=" . $InfoArray["NEXT_PAGE"]; ?>"
                                                        id="dyntable_next" class="next paginate_button btn-primary"
                                                        tabindex="0">Next</a> <a
                                                        href="<?php echo $PHP_SELF . "?id=" . $id . "&page=" . $InfoArray["TOTAL_PAGES"]; ?>"
                                                        id="dyntable_last" class="last paginate_button btn-primary"
                                                        tabindex="0">Last</a>
                                                    <?php
                                                }
                                                ?>

                                            </div>


                                        </div>
                                        <?php
                                    }
                                    ?>
                                    <p class="stdformbutton">
                                        <?php
                                        if($user_qa_rights == 1)
                                        {?>
                                            <button name="complete" type="submit" class="btn btn-primary">Complete</button>
                                            <button name="reject" type="submit" class="btn btn-primary">Rejected</button>
                                            <?php
                                        }else{
                                            ?>
                                            <button name="save" type="submit" class="btn btn-primary">Save</button>
                                            <button name="submitqa" type="submit" class="btn">Submit To QA</button>
                                            <?php
                                        }
                                        ?>
                                    </p>

                                </div>
                                <?php
                            }
                            else
                            {
                                ?>
                                <div class="tabid" id="tabid">
                                    <div id="dashboard-left">
                                        <h5>D&B Verified Data</h5>

                                        <div class="toolTip tpYellow" >
                                            <strong>No Records Found </strong>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>



                        </form>
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