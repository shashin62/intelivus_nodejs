<?php
session_start();
include("includes/connection.php");
if($_SESSION["sadmin_username"]!="")
{
	$operation="Edit";
    $id=$_GET["id"];
    if($id!="" and is_numeric($id))
    {
        $qry="select * from site_data where id=".$id;
        $result=mysqli_query($db,$qry) or die("cannot select SIC Data ".mysqli_error($db));
        if($row=mysqli_fetch_array($result))
        {
            $operation="Edit";
           $sitename= $row["site_name"];
           $address= $row["address"];
            $sicdata = $row["sic_data"];
           $sitelinks = $row["site_links"];
        }
        else
        {
            print "<META http-equiv='refresh' content=0;URL=managesic.php>";
            exit;
        }
    }
    $cls = "con1";
    $cls1 = "con0";
	$lookup = '<img src="img/icons/dunsapp.png" alt="" style="width: 18px; height: 18px;"> '.$operation.' - SIDE Engine';
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

    function addPhoto()
    {
        var c = eval("document.frm1.dloop");
        c.value = parseInt(c.value)+1;
        var pt = document.getElementById("photos")
        var txtEle = document.createElement("label")
        txtEle.textContent ="SIC 4 Value "+c.value;
        txtEle.className="inputCssLabel";
        pt.appendChild (txtEle);
        var txtEle = document.createElement("input")
        txtEle.name="text_"+c.value;
        txtEle.id="text_"+c.value;
        txtEle.className="inputCssInnerhead";
        txtEle.type="url";
        txtEle.required = true
        pt.appendChild (txtEle);
    }
    </script>
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
            display: block;
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
            <form class="stdform" name="frm1" id="login" action="savesic.php" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $id; ?>" />
                <input type="hidden" name="opt" value="<?php echo $operation; ?>" />
                <p>
                    <label>Company Name </label>
                <span class="field">
                <input type="text" name="company" disabled class="inputCssInnerhead" id="company"  value="<?php echo $sitename; ?>" />
                </span> </p>
                <p>
                    <label>Company Address</label>
                <span class="field">
                <input type="text" name="address" disabled class="inputCssInnerhead" id="address"  value="<?php echo $address; ?>" />
                </span> </p>
                <?php
                $i=0;
                $sitelinks = trim($sitelinks,"[,]");
                $arr = explode( ',', $sitelinks );

                foreach ($arr as $key => $value) {
                    $i++;
                    $value = trim($value,"\"");
                    ?>
                    <p>
                        <label>Weblink <?= $i; ?></label>
                        <span class="field">
                       <input type="url" name="text_<?= $i; ?>" disabled class="inputCssInnerhead" id="text_<?= $i; ?>" required value="<?= $value; ?>"/>
                        </span> </p>
                    <p>

                    <?php
                }
                ?>


                        <input type="hidden" name="dloop" value="<?= $i; ?>" />



                <div class="clearfix"></div>
                <div class="tabid" id="tabid">
                    <div id="dashboard-left">
                    <h5>SIC Mappings</h5>
                        </div>

                    <?php
                    $json = json_decode($sicdata,true);

                    $i = 0;
                    $j = 0;
                    echo '<table class="table table-bordered responsive">
                        <thead>
                        <tr >
                        <th><input type="checkbox" style="min-width: 15px;"> </th>
                            <th>SIC 2 Code</th>
                            <th>SIC 2 Division</th>
                            <th>SIC 2 Description</th>
                            <th>SIC 4 Code</th>
                            <th>SIC Code Description</th>
                        </tr>
                        </thead>
                        <tbody>';

				$si = count($json['sicDisplay']);
				$mj=0;
				for($i=0;$i<$si;$i++){
					$sicdata = $json['sicDisplay'][$i]['code'];
					$sic2 = count($json['sicDisplay'][$i]['children']);
					for($j=0;$j<$sic2;$j++){
						$sicdesc2 = count($json['sicDisplay'][$i]['children'][$j]['children']);
						//echo $sicdata = $json['sicDisplay'][$i]['children'][$j]['code'];
						//echo $sicdata = $json['sicDisplay'][$i]['children'][$j]['name'];
						for($k=0;$k<$sicdesc2;$k++){
							$mj++;
							if($mj % 2 == 0){
								echo '<tr class="con0">';
							}else{
								echo '<tr class="con1">';
							}
							echo "<td>".$json['sicDisplay'][$i]['name']."</td>";
							echo "<td>".$json['sicDisplay'][$i]['children'][$j]['code']."</td>";
							echo "<td>".$json['sicDisplay'][$i]['children'][$j]['name']."</td>";
							echo "<td>".$json['sicDisplay'][$i]['children'][$j]['children'][$k]['code']."</td>";
							echo "<td>".$json['sicDisplay'][$i]['children'][$j]['children'][$k]['name']."</td>";
							echo "</tr>";
				
						}
					}
					
	
				}
                    echo '   </tbody>
             </table>';

                    ?>

                </div>

                <p class="stdformbutton">
                    <button name="submit" type="submit" class="btn btn-primary">Submit</button>
                    <!--<button name="submit2" type="button"  onClick="javascript:location.replace('managesic.php');"  class="btn btn-primary">Cancel</button>-->
                </p>

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
    $(document).ready(function(){

        $("#login").validate({
            rules: {
                "company":{
                    required: true
                },
                "address":{
                    required: true
                }
            },
            messages: {
                "company":{
                    required:"Your must Enter a Company Name"
                },
                "address":{
                    required: "You must enter a Address",
                }
            }
        });
    });
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