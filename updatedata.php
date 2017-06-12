<?php
session_start();
include("includes/connection.php");
if($_SESSION["sadmin_username"]!="")
{

	if($page_data_management != 1)
	{
		$_SESSION["sadmin_siteAccessMessage"] = $access_message;
		print "<META http-equiv='refresh' content=0;URL=home.php>";
		exit;
	}

	$operation="Add";
	$id=$_GET["id"];

	if($id!="" and is_numeric($id))
	{
		$serial="";
		$legal_name="";
		$dba_name="";
		$b_address="";
		$b_city="";
		$b_state="";
		$a_code="";
		$a_details="";
		$final_duns="";
		$comments="";
		$weblinks="";
		$website="";
		$duns_month="";
		$location="";
		$company="";
		$tel_no="";
		$duns_as_qa="";
		$headquarters="";
		$qa_findings="";
		
		$qry="select * from data where cid=".$id;
		$result=mysqli_query($db,$qry) or die("cannot select Data ".mysqli_error($db));
		if($row=mysqli_fetch_array($result))
		{
			$operation="Edit";
			$proid=$row["proid"];
			$allocid=$row["allocid"];
            $wid=$row["userid"];
			$serial=$row["serial"];
			$legal_name=$row["legal_name"];
			$dba_name=$row["dba_name"];
			$b_address=$row["b_address"];
			$b_city=$row["b_city"];
			$b_state=$row["b_state"];
			$a_code=$row["a_code"];
			$a_signer=$row["a_signer"];
            $weblinking=$row["weblink"];
			$establish=$row["establish"];
			$apname1=$row["apname1"];
			$designation1=$row["designation1"];
			$apname2=$row["apname2"];
			$designation2=$row["designation2"];
			$apname3=$row["apname3"];
			$designation3=$row["designation3"];
			$address=$row["address"];
			$soslink=$row["soslink"];
			$soscompany=$row["soscompany"];
			$sosaddress=$row["sosaddress"];
			$apname_match=$row["apname_match"];
            $confmetric=$row["confmetric"];
            $stan_comments=$row["stan_comments"];
            $apname_match=$row["apname_match"];
			$qa_comments=$row["qa_comments"];
            $col=$row["status"];
			
			if($qa_comments == ""){
				$qa_comments = "No Comments";
			}
			$rtype = $row["status"];

		}
		else
		{
			print "<META http-equiv='refresh' content=0;URL=home.php>";
			exit;
		}
	}
	else
	{
		$operation="Add";
		$srno="";
		$jun_dun="";
		$may_dun="";
		$legal_name="";
		$dba_name="";
		$b_address="";
		$b_city="";
		$b_state="";
		$a_code="";
		$a_details="";
		$final_duns="";
		$comments="";
		$weblinks="";
		$website="";
		$duns_month="";
		$location="";
		$company="";
		$tel_no="";
		$duns_as_qa="";
		$headquarters="";
		$qa_findings="";
		$rtype = 0;
	}
	$lookup = '<i class="fa fa-pencil"></i> '.$operation.' Data';
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
        .input-xxlarge{
            text-transform: uppercase;
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
      
 <div class="row-fluid">
 <div id="dashboard-left" class="span12">
 <h5>Edit Data</h5>
        <div class="widget">
        <h4  class="widgettitle"><span style="width:30%; text-align:center; border-right:1px solid #FFFFFF; ">Input Data</span>
        <span style="text-align:center; width:65%;">Output Data</span></h4>
          <div class="widgetcontent">
            <form class="stdform" name="frm1" action="savedata.php" method="post" onSubmit="return checkform()" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $id; ?>" />
              <input type="hidden" name="opt" value="<?php echo $operation; ?>" />
              <input type="hidden" name="proid" value="<?php echo $proid; ?>" />
              <input type="hidden" name="rtype" value="<?php echo $rtype; ?>" />
              <input type="hidden" name="colold" value="<?php echo $col; ?>" />
              <input type="hidden" name="allocid" value="<?php echo $allocid; ?>" />
              <input type="hidden" name="wid" value="<?php echo $wid; ?>" />
              <input type="hidden" name="oldstan_comments" value="<?php echo $stan_comments; ?>" />
              <div style="width:32%; float:left; border-right:1px solid #CCCCCC;">
              <p>
                <label>Se 10 : </label>
                <span class="field">
                <label><?php echo $serial; ?></label>
                <input type="hidden" name="serial" class="input-xxlarge" id="serial"  value="<?php echo $serial; ?>" />
                </span> </p>

                 <p>
                <label>Legal Name :</label>
                <span class="field">
                 <label><?php echo $legal_name; ?></label>
                <input type="hidden" name="legal_name" class="input-xxlarge" id="legal_name"  value="<?php echo $legal_name; ?>" />
                </span> </p>
                
                 <p>
                <label>DBA Name :</label>
                <span class="field">
                 <label><?php echo $dba_name; ?></label>
                <input type="hidden" name="dba_name" class="input-xxlarge" id="dba_name"  value="<?php echo $dba_name; ?>" />
                </span> </p>
                
                 <p>
                <label>Address :</label>
                <span class="field">
                 <label><?php echo $b_address; ?></label>
                <input type="hidden" name="b_address" class="input-xxlarge" id="b_address"  value="<?php echo $b_address; ?>" />
                </span> </p>
                
                 <p>
                <label>City :</label>
                <span class="field">
                 <label><?php echo $b_city; ?></label>
                <input type="hidden" name="b_city" class="input-xxlarge" id="b_city"  value="<?php echo $b_city; ?>" />
                </span> </p>
                
                 <p>
                <label>State :</label>
                <span class="field">
                 <label><?php echo $b_state; ?></label>
                <input type="hidden" name="b_state" class="input-xxlarge" id="b_state"  value="<?php echo $b_state; ?>" />
                </span> </p>

                  <p>
                      <label>Pincode :</label>
                <span class="field">
                 <label><?php echo $a_code; ?></label>
                <input type="hidden" name="a_code" class="input-xxlarge" id="a_code"  value="<?php echo $a_code; ?>" />
                </span> </p>

                  <p>
                      <label>Weblink :</label>
                <span class="field" style="padding-top: 5px;">
                 <a href="<?= $weblinking; ?>" style="padding-top: 15px; font-size: 14px; font-weight: bold;" target="_blank">Open Link</a>
				</span> </p>

                  <p>
                      <label>Author Signer :</label>
                <span class="field">
                 <label><?php echo $a_signer; ?></label>
                <input type="hidden" name="a_signer" class="input-xxlarge" id="a_signer"  value="<?php echo $a_signer; ?>" />
                </span> </p>

               </div>
               <div style="width:32%;float:left;">


                   <p>
                       <label>SOS Links :</label>
                <span class="field">
                <input type="text" name="soslink" class="input-xxlarge" style="text-transform: lowercase" id="soslink"  value="<?php echo $soslink; ?>" />
                </span> </p>
                   <p>
                       <label>SOS Company :</label>
                <span class="field">
                <input type="text" name="soscompany" class="input-xxlarge" id="soscompany"  value="<?php echo $soscompany; ?>" />
                </span> </p>


                   <p>
                       <label>Established : </label>
                <span class="field">
                <input type="text" name="establish" class="input-xxlarge" id="establish"  value="<?php echo $establish; ?>" />
                </span> </p>

                   <p>
                       <label>SOS Address :</label>
                <span class="field">
                <input type="text" name="sosaddress" class="input-xxlarge" id="sosaddress"  value="<?php echo $sosaddress; ?>" />
                </span> </p>


                
                 <p>
                <label>Apname 1 :</label>
                <span class="field">
                <input type="text" name="apname1" class="input-xxlarge" id="apname1"  value="<?php echo $apname1; ?>" />
                </span> </p>
                
                 <p>
                <label>Designation 1 :</label>
                <span class="field">
                <input type="text" name="designation1" class="input-xxlarge" id="designation1"  value="<?php echo $designation1; ?>" />
                </span> </p>

                   <p>
                       <label>Apname 2 :</label>
                <span class="field">
                <input type="text" name="apname2" class="input-xxlarge" id="apname2"  value="<?php echo $apname2; ?>" />
                </span> </p>

                </div>
               <div style="width:32%;float:left;">



                   <p>
                       <label>Designation 2 :</label>
                <span class="field">
                <input type="text" name="designation2" class="input-xxlarge" id="designation2"  value="<?php echo $designation2; ?>" />
                </span> </p>

                   <p>
                       <label>Apname 3 :</label>
                <span class="field">
                <input type="text" name="apname3" class="input-xxlarge" id="apname3"  value="<?php echo $apname3; ?>" />
                </span> </p>

                   <p>
                       <label> Designation 3 :</label>
                <span class="field">
                <input type="text" name="designation3" class="input-xxlarge" id="designation3"  value="<?php echo $designation3; ?>" />
                </span> </p>

                   <p>
                <label>Apname Match :</label>
                <span class="field">
                <select name="apname_match" id="apname_match" class="uniformselect" style="width:80%;">
                <option value="">- Select -</option>
                <?php 
				if($apname_match == 'NO INPUT INFO'){
                	$noinput = 'selected';
				}elseif($apname_match == 'YES'){
					$yes = 'selected';
				}elseif($apname_match == 'NO'){
					$no = 'selected';
				}elseif($apname_match == 'PARTIAL'){
                    $partial = 'selected';
                }elseif($apname_match == '-'){
                    $dash = 'selected';
                }
				
				?><option value="NO INPUT INFO" <?php echo $noinput; ?>>NO INPUT INFO</option>
                  <option value="YES" <?php echo $yes; ?>>YES</option>
                  <option value="NO" <?php echo $no; ?>>NO</option>
                  <option value="PARTIAL" <?php echo $partial; ?>>PARTIAL</option>
                  <option value="-" <?php echo $dash; ?>>-</option>
                </select>
                </span> </p>
                
                <p>
                <label>Confidence :</label>
                <span class="field">
                <?php
                $confidence = '';
                if($apname_match=='YES'){
                    $n = (float) $confmetric;
                    
                    if($n < 0.34){
                        $confidence = 'HIGH';
                    } else if($n > 0.35 && $n < 0.7){
                        $confidence = 'MEDIUM';
                    } else if($n > 0.7){
                        $confidence = 'LOW';
                    }
                }
                ?>
                <input type="text" name="confmetric" class="input-xxlarge" id="confmetric"  value="<?php echo $confidence; ?>" />
                </span> </p>

                <p>
                <label>Comments :</label>
                <span class="field">
                <select name="stan_comments" id="stan_comments" class="uniformselect" style="width:80%;">
                <option value="">- Select -</option>
                <?php 
				if($stan_comments == 'ADDRESS NO EXACT MATCH'){
                	$am = 'selected';
				}elseif($stan_comments == 'RECORD NOT FOUND'){
					$rnf = 'selected';
				}elseif($stan_comments == 'COMP NAME NO EXACT MATCH'){
					$cnem = 'selected';
				}elseif($stan_comments == 'COMP NAME AND ADDRESS NO EXACT MATCH'){
					$caddr = 'selected';
				}elseif($stan_comments == '-'){
					$co = 'selected';
				}
				
				?><option value="ADDRESS NO EXACT MATCH" <?php echo $am; ?>>ADDRESS NO EXACT MATCH</option>
                  <option value="RECORD NOT FOUND" <?php echo $rnf; ?>>RECORD NOT FOUND</option>
                  <option value="COMP NAME NO EXACT MATCH" <?php echo $cnem; ?>>COMP NAME NO EXACT MATCH</option>
                  <option value="COMP NAME AND ADDRESS NO EXACT MATCH" <?php echo $caddr; ?>>COMP NAME AND ADDRESS NO EXACT MATCH</option>
                  <option value="-" <?php echo $co; ?>>-</option>
                </select>
                </span> </p>
                
            <?php 
			if($user_qa_rights == 1)
			{?>    
                    <p>
                <label>QA Comments :</label>
                <span class="field">
                <input type="text" name="qa_comments" class="input-xxlarge" id="qa_comments"  value="<?php echo $qa_comments; ?>" />
                </span> </p>
               <?php
			   }else{
			   ?> 
                  <p>
                <label>QA Comments :</label>
                <span class="field">
                <label style="width:100%;"><?php echo $qa_comments; ?></label>
                </span> </p>
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
                <button name="submitqa" type="submit" class="btn  btn-primary">Submit To QA</button>
             <?php
			 }
			 ?>
              </p>
               </div>
                     
           
              
            </form>
          </div>
        </div>
      </div>
    </div>
    <?php include("includes/tb_footer.php"); ?>
  </div>
</div>

<?php require('includes/tb_footerscript.php'); ?>
    <script  type="text/javascript">
        function checkform()
        {


            var sname=document.frm1.stan_comments.value;
            if(sname=="")
            {
                alert("Please Select Comments");
                document.frm1.stan_comments.focus();
                return false;
            }else if(sname == "ADDRESS NO EXACT MATCH" || sname == "COMP NAME NO EXACT MATCH" || sname == "-"){

                var data=document.frm1.soslink.value;
                if(data=="" || data == "-")
                {
                    alert("Please Enter Sos Link");
                    document.frm1.soslink.focus();
                    return false;
                }else if(data != "" && data != "-"){
                    var vurl = validUrl();
                    if(vurl == true){}else{
                        document.frm1.soslink.focus();
                        return false;
                    }
                }
                var data=document.frm1.soscompany.value;
                if(data=="")
                {
                    alert("Please Enter Sos Company Name");
                    document.frm1.soscompany.focus();
                    return false;
                }
                var data=document.frm1.establish.value;
                if(data=="")
                {
                    alert("Please Enter Established Date");
                    document.frm1.establish.focus();
                    return false;
                }else if(data != "-"){
                    var vdate = isValidDate(data);
                    if(vdate == true){}else{
                        alert("Please Enter Proper Established Date");
                        document.frm1.establish.focus();
                        return false;
                    }
                }
                var data=document.frm1.sosaddress.value;
                if(data=="")
                {
                    alert("Please Enter Sos Address");
                    document.frm1.sosaddress.focus();
                    return false;
                }
                var data=document.frm1.apname1.value;
                if(data=="")
                {
                    alert("Please Enter Apname 1");
                    document.frm1.apname1.focus();
                    return false;
                }
                var data=document.frm1.designation1.value;
                if(data=="")
                {
                    alert("Please Enter Designation 1");
                    document.frm1.designation1.focus();
                    return false;
                }
                var data=document.frm1.a_signer.value;
                if(data=="" || data == "-")
                {
                    var data=document.frm1.apname_match.value;
                    if(data=="NO INPUT INFO") {
                        document.frm1.apname_match.focus();
                        return true;
                    }
					
                }else{
                    var data=document.frm1.apname_match.value;
                    if(data!="YES" && data != "NO" && data != "PARTIAL" && data != "-") {
                        alert("Please Select Other Option As There is Authority Name Present");
                        document.frm1.apname_match.focus();
                        return false;
                    }
                }
                var data=document.frm1.confmetric.value;
                if(data=="" || data == "-")
                {
                    alert("Please Enter Proper Confidence Metric");
                    document.frm1.confmetric.focus();
                    return false;
                }



            }else if(sname == "RECORD NOT FOUND"){
                return true;
            }
        }

        function validUrl() {
            var url = document.frm1.soslink.value;
            var pattern = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
            if (pattern.test(url)) {
                return true;
            }
            alert("Sos Link is not valid!");
            return false;

        }
        // Validates that the input string is a valid date formatted as "mm/dd/yyyy"
        function isValidDate(dateString)
        {
            // First check for the pattern
            if(!/^\d{1,2}\/\d{1,2}\/\d{4}$/.test(dateString))
                return false;

            // Parse the date parts to integers
            var parts = dateString.split("/");
            var day = parseInt(parts[1], 10);
            var month = parseInt(parts[0], 10);
            var year = parseInt(parts[2], 10);

            // Check the ranges of month and year
            if(year < 1000 || year > 3000 || month == 0 || month > 12)
                return false;

            var monthLength = [ 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 ];

            // Adjust for leap years
            if(year % 400 == 0 || (year % 100 != 0 && year % 4 == 0))
                monthLength[1] = 29;

            // Check the range of the day
            if(day > 0 && day <= monthLength[month - 1]){
                return true;
            }

        };
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