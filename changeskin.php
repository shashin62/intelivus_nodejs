<div class="rightpanel" style="min-height:450px;">
<ul class="breadcrumbs">
            <li><?php /*?><a href="home.php"><i class="fa fa-dashboard"></i> &nbsp; Dashboard</a> &nbsp; <?php */?> <?php echo $lookup; ?></li>
            
   
  <li style="float:right">
  </li>
    <li style="float:right; <?= $sic_page; ?>">
 <form name="frm12" action="home.php" method="get">
  <select name="proid" class="uniformselect" onchange='javascript:getProject(this.value)'>
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
  </form>
  </li>
        </ul>