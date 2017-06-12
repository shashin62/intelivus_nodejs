<?php
session_start();
include("includes/connection.php");
include("includes/functions.php");
require("includes/paging.php");
/// Check Login Session
if($_SESSION["sadmin_username"]!="")
{
	if($_POST["deleteKey"]!="")
	{
		$delkey=preg_split("/\//",base64_decode($_POST["deleteKey"]));
		if($_POST["page"]=="")
		{	
			$page=1;
		}
		else
		{
			$page=preg_replace ('/[^\d]/', '', $_POST["page"]);
		}
		if($page == "")
		{
			$page = 1;
		}
		if($_POST["perpage"]=="")
		{	
			$perpage=1;
		}
		else
		{
			$perpage=preg_replace ('/[^\d]/', '', $_POST['perpage']);
		}
		if($delkey[1]=="deletePagebanner")
		{
		if($page_banner_management != 1)
	{
		$_SESSION["sadmin_siteAccessMessage"] = $access_message;
		print "<META http-equiv='refresh' content=0;URL=home.php>";
		exit;
	}

			for($i=1;$i<=$_POST["dloop"];$i++)
			{
				if($_POST["del".$i] != "")
				{
					$qry="select image_id,user_id,image_name,image_line1,filename_c1 from banners where image_id=" .$_POST["del".$i];
					$result=mysqli_query($db,$qry) or die("cannot select banners ".mysqli_error($db));
					while($row=mysqli_fetch_array($result))
					{
						$filename[] = $row["filename_c1"];
						$filename[] = $row["filename_c1_60"];
						$sql="delete from banners where image_id=" .$row["image_id"];
						mysqli_query($db,$sql) or die(mysqli_error($db)."<br>".$sql);
					}
				}
			}
			if(is_array($filename))
			{
				$postarr["operation"]="delete_file_all";
				for($j=0;$j<count($filename);$j++)
				{
					$postarr["file_".$j]=$filename[$j];;						
				}
				$postarr["total"]=count($filename);
				$ch = curl_init($sitepath.'includes/upload.php?rand='.rand(100000,999999));  
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postarr);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$postResult = curl_exec($ch);
				curl_close($ch);
			}
			$qy = "select image_id from banners where user_id='".$_SESSION["sadmin_id"]."' order by image_id desc";
			$rs=mysqli_query($db,$qy) or die(mysqli_error($db)."<br>".$qy);
			$num_rows = mysqli_num_rows($rs);
			$page = pager_delete($num_rows,$perpage,$page);
			$_SESSION["sadmin_changeImage_Delete"]="Banner(s) Deleted Successfully.";	
			print "<META http-equiv='refresh' content=0;URL=managebanners.php?page=".$page.">";
			exit;
		}
		if($delkey[1]=="deleteSocial")
		{
		if($page_elements_management != 1)
	{
		$_SESSION["sadmin_siteAccessMessage"] = $access_message;
		print "<META http-equiv='refresh' content=0;URL=home.php>";
		exit;
	}

			for($i=1;$i<=$_POST["dloop"];$i++)
			{
				if($_POST["del".$i] != "")
				{
					$qry="select image_id,user_id,image_name,image_line1,filename_c1 from as_social where image_id=" .$_POST["del".$i];
					$result=mysqli_query($db,$qry) or die("cannot select as_social ".mysqli_error($db));
					while($row=mysqli_fetch_array($result))
					{
						$filename[] = $row["filename_c1"];
						$filename[] = $row["filename_c1_60"];
						$sql="delete from as_social where image_id=" .$row["image_id"];
						mysqli_query($db,$sql) or die(mysqli_error($db)."<br>".$sql);
					}
				}
			}
			if(is_array($filename))
			{
				$postarr["operation"]="delete_file_all";
				for($j=0;$j<count($filename); $j++)
				{
					$postarr["file_".$j]=$filename[$j];;						
				}
				$postarr["total"]=count($filename);
				$ch = curl_init($sitepath.'includes/upload.php?rand='.rand(100000,999999));  
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postarr);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$postResult = curl_exec($ch);
				curl_close($ch);
			}
			$qy = "select image_id from as_social where user_id='".$_SESSION["sadmin_id"]."' order by image_id desc";
			$rs=mysqli_query($db,$qy) or die(mysqli_error($db)."<br>".$qy);
			$num_rows = mysqli_num_rows($rs);
			$page = pager_delete($num_rows,$perpage,$page);
			$_SESSION["sadmin_changeImage_Delete"]="Social Link(s) Deleted Successfully.";	
			print "<META http-equiv='refresh' content=0;URL=managesocial.php?page=".$page.">";
			exit;
		}
		if($delkey[1]=="deleteProductGallery")
		{
			for($i=1;$i<=$_POST["dloop"];$i++)
			{
				if($_POST["del".$i] != "")
				{
					$qry="select image_id,user_id,image_name,filename_c1,filename_c1_90 from product_image where image_id=" .$_POST["del".$i];
					$result=mysqli_query($db,$qry) or die("cannot select Product_images ".mysqli_error($db));
					while($row=mysqli_fetch_array($result))
					{
						$filename[] = $row["filename_c1"];
						$filename[] = $row["filename_c1_90"];
						$sql="delete from product_image where image_id=" .$row["image_id"];
						mysqli_query($db,$sql) or die(mysqli_error($db)."<br>".$sql);
					}
				}
			}
			if(is_array($filename))
			{
				$postarr["operation"]="delete_file_all";
				for($j=0;$j<count($filename); $j++)
				{
					$postarr["file_".$j]=$filename[$j];;						
				}
				$postarr["total"]=count($filename);
				$ch = curl_init($sitepath.'includes/upload.php?rand='.rand(100000,999999));  
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postarr);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$postResult = curl_exec($ch);
				curl_close($ch);
			}
			$qy = "select image_id from product_image where user_id='".$_SESSION["sadmin_id"]."' order by image_id desc";
			$rs=mysqli_query($db,$qy) or die(mysqli_error($db)."<br>".$qy);
			$num_rows = mysqli_num_rows($rs);
			$page = pager_delete($num_rows,$perpage,$page);
			$_SESSION["sadmin_changeImage_Delete"]="Product Images(s) Deleted Successfully.";	
			print "<META http-equiv='refresh' content=0;URL=manage-productimages.php?page=".$page.">";
			exit;
		}
		if($delkey[1]=="deleteGallery")
		{
			if($page_elements_management != 1)
	{
		$_SESSION["sadmin_siteAccessMessage"] = $access_message;
		print "<META http-equiv='refresh' content=0;URL=home.php>";
		exit;
	}

			for($i=1;$i<=$_POST["dloop"];$i++)
			{
				if($_POST["del".$i] != "")
				{
					$qry="select image_id,user_id,image_name,filename_c1 from image_gal where image_id=" .$_POST["del".$i];
					$result=mysqli_query($db,$qry) or die("cannot select image_gal ".mysqli_error($db));
					while($row=mysqli_fetch_array($result))
					{
						$filename[] = $row["filename_c1"];
						$sql="delete from image_gal where image_id=" .$row["image_id"];
						mysqli_query($db,$sql) or die(mysqli_error($db)."<br>".$sql);
					}
				}
			}
			if(is_array($filename))
			{
				$postarr["operation"]="delete_file_all";
				for($j=0;$j<count($filename); $j++)
				{
					$postarr["file_".$j]=$filename[$j];;						
				}
				$postarr["total"]=count($filename);
				$ch = curl_init($sitepath.'includes/upload.php?rand='.rand(100000,999999));  
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postarr);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$postResult = curl_exec($ch);
				curl_close($ch);
			}
			$qy = "select image_id from image_gal where user_id='".$_SESSION["sadmin_id"]."' order by image_id desc";
			$rs=mysqli_query($db,$qy) or die(mysqli_error($db)."<br>".$qy);
			$num_rows = mysqli_num_rows($rs);
			$page = pager_delete($num_rows,$perpage,$page);
			$_SESSION["sadmin_changeImage_Delete"]="Images(s) Deleted Successfully.";	
			print "<META http-equiv='refresh' content=0;URL=manageimages.php?page=".$page.">";
			exit;
		}
		if($delkey[1]=="deleteFiles")
		{
			if($page_elements_management != 1)
	{
		$_SESSION["sadmin_siteAccessMessage"] = $access_message;
		print "<META http-equiv='refresh' content=0;URL=home.php>";
		exit;
	}

			for($i=1;$i<=$_POST["dloop"];$i++)
			{
				if($_POST["del".$i] != "")
				{
					$qry="select image_id,user_id,image_name,filename_c1 from up_files where image_id=" .$_POST["del".$i];
					$result=mysqli_query($db,$qry) or die("cannot select image_gal ".mysqli_error($db));
					while($row=mysqli_fetch_array($result))
					{
						$filename[] = $row["filename_c1"];
						$sql="delete from up_files where image_id=" .$row["image_id"];
						mysqli_query($db,$sql) or die(mysqli_error($db)."<br>".$sql);
					}
				}
			}
			if(is_array($filename))
			{
				$postarr["operation"]="delete_file_all";
				for($j=0;$j<count($filename); $j++)
				{
					$postarr["file_".$j]=$filename[$j];;						
				}
				$postarr["total"]=count($filename);
				$ch = curl_init($sitepath.'includes/upload.php?rand='.rand(100000,999999));  
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postarr);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$postResult = curl_exec($ch);
				curl_close($ch);
			}
			$qy = "select image_id from up_files where user_id='".$_SESSION["sadmin_id"]."' order by image_id desc";
			$rs=mysqli_query($db,$qy) or die(mysqli_error($db)."<br>".$qy);
			$num_rows = mysqli_num_rows($rs);
			$page = pager_delete($num_rows,$perpage,$page);
			$_SESSION["sadmin_changeImage_Delete"]="Images(s) Deleted Successfully.";	
			print "<META http-equiv='refresh' content=0;URL=managefiles.php?page=".$page.">";
			exit;
		}
		if($delkey[1]=="deleteSubuser")
		{
			for($i=1;$i<=$_POST["dloop"];$i++)
			{
				if($_POST["del".$i] != "")
				{
					$qry="select sub_id,user_id,user_email from teammembers where sub_id=" .$_POST["del".$i];
					$result=mysqli_query($db,$qry) or die("cannot select teammembers ".mysqli_error($db));
					while($row=mysqli_fetch_array($result))
					{
						$sql="delete from teammembers where sub_id=" .$row["sub_id"];
						mysqli_query($db,$sql) or die(mysqli_error($db)."<br>".$sql);
					}
				}
			}
			$qy = "select sub_id from teammembers where user_id='".$_SESSION["sadmin_id"]."' order by sub_id desc";
			$rs=mysqli_query($db,$qy) or die(mysqli_error($db)."<br>".$qy);
			$num_rows = mysqli_num_rows($rs);
			$page = pager_delete($num_rows,$perpage,$page);
			$_SESSION["sadmin_changeImage_Delete"]="User(s) Deleted Successfully.";	
			print "<META http-equiv='refresh' content=0;URL=managesubuser.php?page=".$page.">";
			exit;
		}
		if($delkey[1]=="deleteCountries")
		{
			for($i=1;$i<=$_POST["dloop"];$i++)
			{
				if($_POST["del".$i] != "")
				{
					$qry="select cid,user_id,country_name,cont_id from as_country where cont_id=" .$_POST["del".$i];
					$result=mysqli_query($db,$qry) or die("cannot select as_country ".mysqli_error($db));
					while($row=mysqli_fetch_array($result))
					{
						$sql="delete from as_country where cont_id=".$row["cont_id"];
						mysqli_query($db,$sql) or die(mysqli_error($db)."<br>".$sql);
					}
					$qry="select cid,country_name from country where cid=" .$_POST["del".$i];
					$result=mysqli_query($db,$qry) or die("cannot select country ".mysqli_error($db));
					while($row=mysqli_fetch_array($result))
					{
						$sql="delete from country where cid=" .$row["cid"];
						mysqli_query($db,$sql) or die(mysqli_error($db)."<br>".$sql);
					}
				}
			}
			$qy = "select cid from as_country where user_id='".$_SESSION["sadmin_id"]."' order by cid desc";
			$rs=mysqli_query($db,$qy) or die(mysqli_error($db)."<br>".$qy);
			$num_rows = mysqli_num_rows($rs);
			$page = pager_delete($num_rows,$perpage,$page);
			$_SESSION["sadmin_changeImage_Delete"]="Country(s) Deleted Successfully.";	
			print "<META http-equiv='refresh' content=0;URL=managecountry.php?page=".$page.">";
			exit;
		}
		if($delkey[1]=="deleteState")
		{
			for($i=1;$i<=$_POST["dloop"];$i++)
			{
				if($_POST["del".$i] != "")
				{
					
					$qry="select * from as_state where cont_id=" .$_POST["del".$i];
					$result=mysqli_query($db,$qry) or die("cannot select as_country ".mysqli_error($db));
					while($row=mysqli_fetch_array($result))
					{
						$sql="delete from as_state where cont_id=" .$row["cont_id"];
						mysqli_query($db,$sql) or die(mysqli_error($db)."<br>".$sql);
					}
					$qry="select cid,pid,state_name from states where cid=" .$_POST["del".$i];
					$result=mysqli_query($db,$qry) or die("cannot select as_country ".mysqli_error($db));
					while($row=mysqli_fetch_array($result))
					{
						$sql="delete from states where cid=" .$row["cid"];
						mysqli_query($db,$sql) or die(mysqli_error($db)."<br>".$sql);
					}
				}
			}
			$qy = "select cid from as_state where user_id='".$_SESSION["sadmin_id"]."' order by cid desc";
			$rs=mysqli_query($db,$qy) or die(mysqli_error($db)."<br>".$qy);
			$num_rows = mysqli_num_rows($rs);
			$page = pager_delete($num_rows,$perpage,$page);
			$_SESSION["sadmin_changeImage_Delete"]="State(s) Deleted Successfully.";	
			print "<META http-equiv='refresh' content=0;URL=managestate.php?page=".$page.">";
			exit;
		}
		if($delkey[1]=="deleteCity")
		{
			for($i=1;$i<=$_POST["dloop"];$i++)
			{
				if($_POST["del".$i] != "")
				{
					$qry="select * from as_city where cont_id=" .$_POST["del".$i];
					$result=mysqli_query($db,$qry) or die("cannot select as_city ".mysqli_error($db));
					while($row=mysqli_fetch_array($result))
					{
						$sql="delete from as_city where cont_id=" .$row["cont_id"];
						mysqli_query($db,$sql) or die(mysqli_error($db)."<br>".$sql);
					}
					$qry="select * from city where cid=" .$_POST["del".$i];
					$result=mysqli_query($db,$qry) or die("cannot select as_city ".mysqli_error($db));
					while($row=mysqli_fetch_array($result))
					{
						$sql="delete from city where cid=" .$row["cid"];
						mysqli_query($db,$sql) or die(mysqli_error($db)."<br>".$sql);
					}
				}
			}
			$qy = "select cid from as_city where user_id='".$_SESSION["sadmin_id"]."' order by cid desc";
			$rs=mysqli_query($db,$qy) or die(mysqli_error($db)."<br>".$qy);
			$num_rows = mysqli_num_rows($rs);
			$page = pager_delete($num_rows,$perpage,$page);
			$_SESSION["sadmin_changeImage_Delete"]="City(s) Deleted Successfully.";	
			print "<META http-equiv='refresh' content=0;URL=managecity.php?page=".$page.">";
			exit;
		}
		if($delkey[1]=="deleteCouches")
		{
			if($page_elements_management != 1)
	{
		$_SESSION["sadmin_siteAccessMessage"] = $access_message;
		print "<META http-equiv='refresh' content=0;URL=home.php>";
		exit;
	}

			for($i=1;$i<=$_POST["dloop"];$i++)
			{
				if($_POST["del".$i] != "")
				{
					$qry="select cid,user_id,dest_name,sdate,edate,pax from as_couches where cid=" .$_POST["del".$i];
					$result=mysqli_query($db,$qry) or die("cannot select as_couches ".mysqli_error($db));
					while($row=mysqli_fetch_array($result))
					{
						$sql="delete from as_couches where cid=" .$row["cid"];
						mysqli_query($db,$sql) or die(mysqli_error($db)."<br>".$sql);
					}
				}
			}
			$qy = "select cid from as_couches where user_id='".$_SESSION["sadmin_id"]."' order by cid desc";
			$rs=mysqli_query($db,$qy) or die(mysqli_error($db)."<br>".$qy);
			$num_rows = mysqli_num_rows($rs);
			$page = pager_delete($num_rows,$perpage,$page);
			$_SESSION["sadmin_changeImage_Delete"]="Couch(es) Deleted Successfully.";	
			print "<META http-equiv='refresh' content=0;URL=managecouches.php?page=".$page.">";
			exit;
		}
		if($delkey[1]=="deleteTourism")
		{
			for($i=1;$i<=$_POST["dloop"];$i++)
			{
				if($_POST["del".$i] != "")
				{
					$qry="select cid,user_id,cname,page_title,metakey,metakeyphrases,metadesc from tourism where cid=" .$_POST["del".$i];
					$result=mysqli_query($db,$qry) or die("cannot select tourism ".mysqli_error($db));
					while($row=mysqli_fetch_array($result))
					{
						$sql="delete from tourism where cid=" .$row["cid"];
						mysqli_query($db,$sql) or die(mysqli_error($db)."<br>".$sql);
					}
				}
			}
			$qy = "select cid from tourism where user_id='".$_SESSION["sadmin_id"]."' order by cid desc";
			$rs=mysqli_query($db,$qy) or die(mysqli_error($db)."<br>".$qy);
			$num_rows = mysqli_num_rows($rs);
			$page = pager_delete($num_rows,$perpage,$page);
			$_SESSION["sadmin_changeImage_Delete"]="Tourism(s) Deleted Successfully.";	
			print "<META http-equiv='refresh' content=0;URL=managetourism.php?page=".$page.">";
			exit;
		}
		if($delkey[1]=="deleteCategory")
		{
		if($page_tours_management != 1)
	{
		$_SESSION["sadmin_siteAccessMessage"] = $access_message;
		print "<META http-equiv='refresh' content=0;URL=home.php>";
		exit;
	}


			for($i=1;$i<=$_POST["dloop"];$i++)
			{
				if($_POST["del".$i] != "")
				{
					$qry="select cid,user_id,cname,page_title,metakey,metakeyphrases,metadesc from as_category where cid=" .$_POST["del".$i];
					$result=mysqli_query($db,$qry) or die("cannot select as_category ".mysqli_error($db));
					while($row=mysqli_fetch_array($result))
					{
						$sql = "select cid from as_tours where gid='".$row['cid']."'";
					    $rowfind=mysqli_query($db,$sql) or die(mysqli_error($db)."<br>".$sql);
					    $total_tours = mysqli_num_rows($rowfind);
						if($total_tours == 0)
						{
							$sql="delete from as_category where cid=" .$row["cid"];
							mysqli_query($db,$sql) or die(mysqli_error($db)."<br>".$sql);
						}
					}
				}
			}
			$qy = "select cid from as_category where user_id='".$_SESSION["sadmin_id"]."' order by cid desc";
			$rs=mysqli_query($db,$qy) or die(mysqli_error($db)."<br>".$qy);
			$num_rows = mysqli_num_rows($rs);
			$page = pager_delete($num_rows,$perpage,$page);
			$_SESSION["sadmin_changeImage_Delete"]="Category(ies) Deleted Successfully.";	
			print "<META http-equiv='refresh' content=0;URL=managecategory.php?page=".$page.">";
			exit;
		}
		if($delkey[1]=="deleteHoneymoon")
		{
		if($page_honeymoon_management != 1)
	{
		$_SESSION["sadmin_siteAccessMessage"] = $access_message;
		print "<META http-equiv='refresh' content=0;URL=home.php>";
		exit;
	}

			for($i=1;$i<=$_POST["dloop"];$i++)
			{
				if($_POST["del".$i] != "")
				{
					$qry="select cid,user_id,cname,page_title,metakey,metakeyphrases,metadesc from as_honeymoon where cid=" .$_POST["del".$i];
					$result=mysqli_query($db,$qry) or die("cannot select as_honeymoon ".mysqli_error($db));
					while($row=mysqli_fetch_array($result))
					{
						$sql = "select cid from honeymoonpack where gid='".$row['cid']."'";
					    $rowfind=mysqli_query($db,$sql) or die(mysqli_error($db)."<br>".$sql);
					    $total_tours = mysqli_num_rows($rowfind);
						if($total_tours == 0)
						{
							$sql="delete from honeymoonpack where cid=" .$row["cid"];
							mysqli_query($db,$sql) or die(mysqli_error($db)."<br>".$sql);
						}
						$sql="delete from as_honeymoon where cid=" .$row["cid"];
						mysqli_query($db,$sql) or die(mysqli_error($db)."<br>".$sql);
					}
				}
			}
			$qy = "select cid from as_honeymoon where user_id='".$_SESSION["sadmin_id"]."' order by cid desc";
			$rs=mysqli_query($db,$qy) or die(mysqli_error($db)."<br>".$qy);
			$num_rows = mysqli_num_rows($rs);
			$page = pager_delete($num_rows,$perpage,$page);
			$_SESSION["sadmin_changeImage_Delete"]="Category(ies) Deleted Successfully.";	
			print "<META http-equiv='refresh' content=0;URL=managehoneymoon.php?page=".$page.">";
			exit;
		}
		if($delkey[1]=="deleteNewsLetter")
		{
		
			for($i=1;$i<=$_POST["dloop"];$i++)
			{
				if($_POST["del".$i] != "")
				{
					$qry="select cid,user_id,user_name,user_email,cdtime from as_newsletter where cid=" .$_POST["del".$i];
					$result=mysqli_query($db,$qry) or die("cannot select as_newsletter ".mysqli_error($db));
					while($row=mysqli_fetch_array($result))
					{
						$sql="delete from as_newsletter where cid=" .$row["cid"];
						mysqli_query($db,$sql) or die(mysqli_error($db)."<br>".$sql);
					}
				}
			}
			$qy = "select cid from as_newsletter where user_id='".$_SESSION["sadmin_id"]."' order by cid desc";
			$rs=mysqli_query($db,$qy) or die(mysqli_error($db)."<br>".$qy);
			$num_rows = mysqli_num_rows($rs);
			$page = pager_delete($num_rows,$perpage,$page);
			$_SESSION["sadmin_changeImage_Delete"]="NewsLetter(s) Deleted Successfully.";	
			print "<META http-equiv='refresh' content=0;URL=newsletter.php?page=".$page.">";
			exit;
		}
		if($delkey[1]=="deleteReviews")
		{
		if($page_traveller_management != 1)
	{
		$_SESSION["sadmin_siteAccessMessage"] = $access_message;
		print "<META http-equiv='refresh' content=0;URL=home.php>";
		exit;
	}

			for($i=1;$i<=$_POST["dloop"];$i++)
			{
				if($_POST["del".$i] != "")
				{
					$qry="select cid,user_id,user_name,user_addr,user_msg,cdtime from as_reviews where cid=" .$_POST["del".$i];
					$result=mysqli_query($db,$qry) or die("cannot select as_reviews ".mysqli_error($db));
					while($row=mysqli_fetch_array($result))
					{
						$sql="delete from as_reviews where cid=" .$row["cid"];
						mysqli_query($db,$sql) or die(mysqli_error($db)."<br>".$sql);
					}
				}
			}
			$qy = "select cid from as_reviews where user_id='".$_SESSION["sadmin_id"]."' order by cid desc";
			$rs=mysqli_query($db,$qy) or die(mysqli_error($db)."<br>".$qy);
			$num_rows = mysqli_num_rows($rs);
			$page = pager_delete($num_rows,$perpage,$page);
			$_SESSION["sadmin_changeImage_Delete"]="Review(s) Deleted Successfully.";	
			print "<META http-equiv='refresh' content=0;URL=managereviews.php?page=".$page.">";
			exit;
		}
		if($delkey[1]=="deleteTours")
		{
		if($page_tours_management != 1)
	{
		$_SESSION["sadmin_siteAccessMessage"] = $access_message;
		print "<META http-equiv='refresh' content=0;URL=home.php>";
		exit;
	}


			for($i=1;$i<=$_POST["dloop"];$i++)
			{
				if($_POST["del".$i] != "")
				{
					$qry="select cid,user_id,mid,cname,filename_c1 from as_tours where cid=" .$_POST["del".$i];
					$result=mysqli_query($db,$qry) or die("cannot select Tours ".mysqli_error($db));
					while($row=mysqli_fetch_array($result))
					{
						$filename[] = $row["filename_c1"];
						$qry1="select image_id,filename_c1,filename_c1_90 from rel_gallery where pid=" .$row["cid"];
						$result1=mysqli_query($db,$qry1) or die("cannot select Rel_gallery ".mysqli_error($db));
						while($row1=mysqli_fetch_array($result1))
						{
							$filename[] = $row1["filename_c1"];
							$filename[] = $row1["filename_c1_90"];
							$sql1="delete from rel_gallery where image_id=" .$row1["image_id"];
							mysqli_query($db,$sql1) or die(mysqli_error($db)."<br>".$sql1);
						}
						$sql="delete from as_tours where cid=" .$row["cid"];
						mysqli_query($db,$sql) or die(mysqli_error($db)."<br>".$sql);						
					}
				}
			}
			if(is_array($filename))
			{
				$postarr["operation"]="delete_file_all";
				for($j=0; $j<count($filename); $j++)
				{
					$postarr["file_".$j]=$filename[$j];;						
				}
				$postarr["total"]=count($filename);
				$ch = curl_init($sitepath.'includes/upload.php?rand='.rand(100000,999999));  
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postarr);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$postResult = curl_exec($ch);
				curl_close($ch);
			}
			$qy = "select cid from as_tours where user_id='".$_SESSION["sadmin_id"]."' order by cid desc";
			$rs=mysqli_query($db,$qy) or die(mysqli_error($db)."<br>".$qy);
			$num_rows = mysqli_num_rows($rs);
			$page = pager_delete($num_rows,$perpage,$page);
			
			
			
			
			$_SESSION["sadmin_changeImage_Delete"]="Tour(s) Deleted Successfully.";	
			print "<META http-equiv='refresh' content=0;URL=managetours.php?page=".$page.">";
			exit;
		}
		if($delkey[1]=="deleteHoneymoonPack")
		{
		if($page_honeymoon_management != 1)
	{
		$_SESSION["sadmin_siteAccessMessage"] = $access_message;
		print "<META http-equiv='refresh' content=0;URL=home.php>";
		exit;
	}

			for($i=1;$i<=$_POST["dloop"];$i++)
			{
				if($_POST["del".$i] != "")
				{
					$qry="select cid,user_id,mid,cname,filename_c1 from honeymoonpack where cid=" .$_POST["del".$i];
					$result=mysqli_query($db,$qry) or die("cannot select Tours ".mysqli_error($db));
					while($row=mysqli_fetch_array($result))
					{
						$filename[] = $row["filename_c1"];
						$qry1="select image_id,filename_c1,filename_c1_90 from dest_gallery where pid=" .$row["cid"];
						$result1=mysqli_query($db,$qry1) or die("cannot select dest_gallery ".mysqli_error($db));
						while($row1=mysqli_fetch_array($result1))
						{
							$filename[] = $row1["filename_c1"];
							$filename[] = $row1["filename_c1_90"];
							$sql1="delete from dest_gallery where image_id=" .$row1["image_id"];
							mysqli_query($db,$sql1) or die(mysqli_error($db)."<br>".$sql1);
						}
						$sql="delete from honeymoonpack where cid=" .$row["cid"];
						mysqli_query($db,$sql) or die(mysqli_error($db)."<br>".$sql);
					}
				}
			}
			if(is_array($filename))
			{
				$postarr["operation"]="delete_file_all";
				for($j=0;$j<count($filename); $j++)
				{
					$postarr["file_".$j]=$filename[$j];;						
				}
				$postarr["total"]=count($filename);
				$ch = curl_init($sitepath.'includes/upload.php?rand='.rand(100000,999999));  
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postarr);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$postResult = curl_exec($ch);
				curl_close($ch);
			}
			$qy = "select cid from honeymoonpack where user_id='".$_SESSION["sadmin_id"]."' order by cid desc";
			$rs=mysqli_query($db,$qy) or die(mysqli_error($db)."<br>".$qy);
			$num_rows = mysqli_num_rows($rs);
			$page = pager_delete($num_rows,$perpage,$page);
			$_SESSION["sadmin_changeImage_Delete"]="Pack(s) Deleted Successfully.";	
			print "<META http-equiv='refresh' content=0;URL=managehoneymoonpack.php?page=".$page.">";
			exit;
		}
		if($delkey[1]=="deleteCard")
		{
		if($page_elements_management != 1)
	{
		$_SESSION["sadmin_siteAccessMessage"] = $access_message;
		print "<META http-equiv='refresh' content=0;URL=home.php>";
		exit;
	}

			for($i=1;$i<=$_POST["dloop"];$i++)
			{
				if($_POST["del".$i] != "")
				{
					$qry="select image_id,user_id,image_name,filename_c1 from card_gal where image_id=" .$_POST["del".$i];
					$result=mysqli_query($db,$qry) or die("cannot select Card ".mysqli_error($db));
					while($row=mysqli_fetch_array($result))
					{
						$filename[] = $row["filename_c1"];
						$sql="delete from card_gal where image_id=" .$row["image_id"];
						mysqli_query($db,$sql) or die(mysqli_error($db)."<br>".$sql);
					}
				}
			}
			if(is_array($filename))
			{
				$postarr["operation"]="delete_file_all";
				for($j=0;$j<count($filename); $j++)
				{
					$postarr["file_".$j]=$filename[$j];;						
				}
				$postarr["total"]=count($filename);
				$ch = curl_init($sitepath.'includes/upload.php?rand='.rand(100000,999999));  
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postarr);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$postResult = curl_exec($ch);
				curl_close($ch);
			}
			$qy = "select image_id from card_gal where user_id='".$_SESSION["sadmin_id"]."' order by image_id desc";
			$rs=mysqli_query($db,$qy) or die(mysqli_error($db)."<br>".$qy);
			$num_rows = mysqli_num_rows($rs);
			$page = pager_delete($num_rows,$perpage,$page);
			$_SESSION["sadmin_changeImage_Delete"]="Card(s) Deleted Successfully.";	
			print "<META http-equiv='refresh' content=0;URL=managecard.php?page=".$page.">";
			exit;
		}
		if($delkey[1]=="deleteRelatedGallery")
		{
			if($page_tours_management != 1)
		{
		$_SESSION["sadmin_siteAccessMessage"] = $access_message;
		print "<META http-equiv='refresh' content=0;URL=home.php>";
		exit;
		}


			for($i=1;$i<=$_POST["dloop"];$i++)
			{
				if($_POST["del".$i] != "")
				{
					$qry="select image_id,user_id,image_name,filename_c1,filename_c1_90 from rel_gallery where image_id=" .$_POST["del".$i];
					$result=mysqli_query($db,$qry) or die("cannot select Rel_gallery ".mysqli_error($db));
					while($row=mysqli_fetch_array($result))
					{
						$filename[] = $row["filename_c1"];
						$filename[] = $row["filename_c1_90"];
						$sql="delete from rel_gallery where image_id=" .$row["image_id"];
						mysqli_query($db,$sql) or die(mysqli_error($db)."<br>".$sql);
					}
				}
			}
			if(is_array($filename))
			{
				$postarr["operation"]="delete_file_all";
				for($j=0;$j<count($filename); $j++)
				{
					$postarr["file_".$j]=$filename[$j];;						
				}
				$postarr["total"]=count($filename);
				$ch = curl_init($sitepath.'includes/upload.php?rand='.rand(100000,999999));  
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postarr);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$postResult = curl_exec($ch);
				curl_close($ch);
			}
			$qy = "select image_id from rel_gallery where user_id='".$_SESSION["sadmin_id"]."' order by image_id desc";
			$rs=mysqli_query($db,$qy) or die(mysqli_error($db)."<br>".$qy);
			$num_rows = mysqli_num_rows($rs);
			$page = pager_delete($num_rows,$perpage,$page);
			$_SESSION["sadmin_changeImage_Delete"]="Related Photos(s) Deleted Successfully.";	
			print "<META http-equiv='refresh' content=0;URL=managerelgallery.php?page=".$page.">";
			exit;
		}
		if($delkey[1]=="deleteGalleryHoneymoon")
		{
			if($page_honeymoon_management != 1)
	{
		$_SESSION["sadmin_siteAccessMessage"] = $access_message;
		print "<META http-equiv='refresh' content=0;URL=home.php>";
		exit;
	}

			for($i=1;$i<=$_POST["dloop"];$i++)
			{
				if($_POST["del".$i] != "")
				{
					$qry="select image_id,user_id,image_name,filename_c1,filename_c1_90 from dest_gallery where image_id=" .$_POST["del".$i];
					$result=mysqli_query($db,$qry) or die("cannot select dest_gallery ".mysqli_error($db));
					while($row=mysqli_fetch_array($result))
					{
						$filename[] = $row["filename_c1"];
						$filename[] = $row["filename_c1_90"];
						$sql="delete from dest_gallery where image_id=" .$row["image_id"];
						mysqli_query($db,$sql) or die(mysqli_error($db)."<br>".$sql);
					}
				}
			}
			if(is_array($filename))
			{
				$postarr["operation"]="delete_file_all";
				for($j=0;$j<count($filename); $j++)
				{
					$postarr["file_".$j]=$filename[$j];;						
				}
				$postarr["total"]=count($filename);
				$ch = curl_init($sitepath.'includes/upload.php?rand='.rand(100000,999999));  
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postarr);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$postResult = curl_exec($ch);
				curl_close($ch);
			}
			$qy = "select image_id from dest_gallery where user_id='".$_SESSION["sadmin_id"]."' order by image_id desc";
			$rs=mysqli_query($db,$qy) or die(mysqli_error($db)."<br>".$qy);
			$num_rows = mysqli_num_rows($rs);
			$page = pager_delete($num_rows,$perpage,$page);
			$_SESSION["sadmin_changeImage_Delete"]="Related Photos(s) Deleted Successfully.";	
			print "<META http-equiv='refresh' content=0;URL=managegalleryHoneymoon.php?page=".$page.">";
			exit;
		}
		if($delkey[1]=="deleteLogoScroller")
		{
			if($page_elements_management != 1)
	{
		$_SESSION["sadmin_siteAccessMessage"] = $access_message;
		print "<META http-equiv='refresh' content=0;URL=home.php>";
		exit;
	}

for($i=1;$i<=$_POST["dloop"];$i++)
			{
				if($_POST["del".$i] != "")
				{
					$qry="select image_id,user_id,image_name,filename_c1 from logo_gal where image_id=" .$_POST["del".$i];
					$result=mysqli_query($db,$qry) or die("cannot select logo ".mysqli_error($db));
					while($row=mysqli_fetch_array($result))
					{
						$filename[] = $row["filename_c1"];
						$sql="delete from logo_gal where image_id=" .$row["image_id"];
						mysqli_query($db,$sql) or die(mysqli_error($db)."<br>".$sql);
					}
				}
			}
			if(is_array($filename))
			{
				$postarr["operation"]="delete_file_all";
				for($j=0;$j<count($filename); $j++)
				{
					$postarr["file_".$j]=$filename[$j];;						
				}
				$postarr["total"]=count($filename);
				$ch = curl_init($sitepath.'includes/upload.php?rand='.rand(100000,999999));  
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postarr);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$postResult = curl_exec($ch);
				curl_close($ch);
			}
			$qy = "select image_id from logo_gal where user_id='".$_SESSION["sadmin_id"]."' order by image_id desc";
			$rs=mysqli_query($db,$qy) or die(mysqli_error($db)."<br>".$qy);
			$num_rows = mysqli_num_rows($rs);
			$page = pager_delete($num_rows,$perpage,$page);
			$_SESSION["sadmin_changeImage_Delete"]="Logo(s) Deleted Successfully.";	
			print "<META http-equiv='refresh' content=0;URL=managelogoscroller.php?page=".$page.">";
			exit;
		}
		if($delkey[1]=="deleteProduct")
		{
			for($i=1;$i<=$_POST["dloop"];$i++)
			{
				if($_POST["del".$i] != "")
				{
					$qry="select cid,pid,spid,user_id,cname,ctext,cimage,cimage_small from products where cid=" .$_POST["del".$i];
					$result=mysqli_query($db,$qry) or die("cannot select categories ".mysqli_error($db));
					while($row=mysqli_fetch_array($result))
					{
						$filename[] = $row["cimage"];
						$filename[] = $row["cimage_small"];
						$sql="delete from products where cid=" .$row["cid"];
						mysqli_query($db,$sql) or die(mysqli_error($db)."<br>".$sql);
					}
				}
			}
			if(is_array($filename))
			{
				$postarr["operation"]="delete_file_all";
				for($j=0;$j<count($filename); $j++)
				{
					$postarr["file_".$j]=$filename[$j];;						
				}
				$postarr["total"]=count($filename);
				$ch = curl_init($sitepath.'includes/upload.php?rand='.rand(100000,999999));  
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postarr);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$postResult = curl_exec($ch);
				curl_close($ch);
			}
			$qy = "select cid from products where user_id='".$_SESSION["sadmin_id"]."' order by cid desc";
			$rs=mysqli_query($db,$qy) or die(mysqli_error($db)."<br>".$qy);
			$num_rows = mysqli_num_rows($rs);
			$page = pager_delete($num_rows,$perpage,$page);
			$_SESSION["sadmin_changeImage_Delete"]="Product(s) Deleted Successfully.";	
			print "<META http-equiv='refresh' content=0;URL=manageproducts.php?page=".$page.">";
			exit;
		}
		
	}
	else
	{
		print "<META http-equiv='refresh' content=0;URL=home.php>";
		exit;
	}
}
else
{
	print "<META http-equiv='refresh' content=0;URL=index.php>";
}