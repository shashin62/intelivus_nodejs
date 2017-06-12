<?php

$sic_page = "";
if(strpos($_SERVER["PHP_SELF"],"managesic.php") ||  strpos($_SERVER["PHP_SELF"],"addsic.php") || strpos($_SERVER["PHP_SELF"],"editsic.php") || strpos($_SERVER["PHP_SELF"],"manageduns.php") ||  strpos($_SERVER["PHP_SELF"],"addduns.php") || strpos($_SERVER["PHP_SELF"],"editduns.php"))
{
	$sic_page = 'display:none';
}
?>
<div class="rightpanel" style="min-height:450px;">
<ul class="breadcrumbs">
            <li><?php echo $lookup; ?></li>
            
   

 <li style="float:right; <?= $sic_page; ?>">
 <form name="frm12" action="home.php" method="get">
  <select name="proid" class="uniformselect" onchange='javascript:getProject(this.value)'>
  <option value=""> - Select Project -</option>
  <?php
  if($proid==""){ $proid = 1; }

 		 $qr = "select * from batch";
		$rs = mysqli_query($db,$qr) or die ("cannot select projects".mysqli_error($db));
		while($row=mysqli_fetch_array($rs)){
			if($row["id"]==$proid){
				echo '<option value="'.$row["id"].'" selected>'.$row["proname"].'</option>';
			}else{
				echo '<option value="'.$row["id"].'">'.$row["proname"].'</option>';
			}
		}
		?>
  </select>
  </form>
  </li>
        </ul>