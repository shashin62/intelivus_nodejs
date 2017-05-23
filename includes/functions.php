<?php
/*function isValidEmail($email){
	return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email);
}*/

function isValidEmail($value){
	$pattern = "/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)+/";
	return preg_match($pattern, $value);
}
function ClearString($tempdata)
{
	$tempdata=str_replace("\n"," ",$tempdata);
	$tempdata=str_replace("\t"," ",$tempdata);
	if($tempdata=="")
	{
		$tempdata="-";
	}
	return $tempdata;
}
function displayDate2($tempdate,$tempformat = "Y-m-d")
{
	$tempdarr = split(" ",$tempdate);
	$tempdarr2 = split("-",$tempdarr[0]);
	$tempdarr3 = split(":",$tempdarr[1]);
	if($tempdarr2[0]>1970)
	{
		$dateretun = date($tempformat,mktime($tempdarr3[0],$tempdarr3[1],$tempdarr3[3],$tempdarr2[1],$tempdarr2[2],$tempdarr2[0]));
		return $dateretun;
	}
}
function displayDate($tempdate,$tempformat = "Y-m-d")
{
	$tempdarr = split("-",$tempdate);
	if($tempdarr[0]>1970)
	{
		$dateretun = date($tempformat,mktime(0,0,0,$tempdarr[1],$tempdarr[2],$tempdarr[0]));
		return $dateretun;
	}
}

function displayDatedmy($tempdate,$tempformat = "Y-m-d")
{
	$tempdarr = split("-",$tempdate);
	if($tempdarr[2]>1970)
	{
		$dateretun = date($tempformat,mktime(0,0,0,$tempdarr[1],$tempdarr[0],$tempdarr[2]));
		return $dateretun;
	}
}

function displayDatemdy($tempdate,$tempformat = "Y-m-d")
{
	$tempdarr = split("-",$tempdate);
	if($tempdarr[2]>1970)
	{
		$dateretun = date($tempformat,mktime(0,0,0,$tempdarr[0],$tempdarr[1],$tempdarr[2]));
		return $dateretun;
	}
}

function addDate($tempdate,$nday,$tempformat = "Y-m-d")
{
	$tempdarr = split("-",$tempdate);
	$dateretun = date($tempformat,mktime(0,0,0,$tempdarr[1],$tempdarr[2]-$nday,$tempdarr[0]));
	return $dateretun;
}

function StringRepair($temptext)
{
	$temptext = trim($temptext);
	$temptext=str_replace("'","&#39;",$temptext);
	$temptext=str_replace("\"","&#34;",$temptext);
	$temptext = mb_convert_encoding($temptext,"UTF-8");
	return $temptext;
}
function StringRepair3($temptext)
{
	$temptext=trim($temptext);
	$temptext=str_replace("&#39;","'",$temptext);
	$temptext=str_replace("&#34;","\"",$temptext);
	return $temptext;
}

function GetFileName($filename)
{
	return rand(100000,999999)."_".rand(100000,999999)."_".rand(100000,999999).".".strtolower(substr($filename,strlen($filename)-3));
}
function UploadImage($file,$newname)
{
	if(move_uploaded_file($_FILES[$file]['tmp_name'],$newname))
	{
	}
	else
	{
		$err_msg="Image Uplaod Problem";
		echo "<table width='100%' border=0>";
		echo "<tr>";
		echo "<td align='center' valgin=top height=230>&nbsp;</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td align='center' valgin=top>".$err_msg."</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td align='center' valgin=top><a href='#' onclick='javascript:history.back(-1);'>back</a></td>";
		echo "</tr>";
		echo "</table>";
		exit;
	}
}
function CheckPhotoDimension($file,$w,$h)
{
	list($width, $height) = getimagesize($file);
	if($w>$width or $h>$height)
	{
		$err_msg="File Dimension should be ".$w." x ".$h;
		echo "<table width='100%' border=0>";
		echo "<tr>";
		echo "<td align='center' valgin=top height=230>&nbsp;</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td align='center' valgin=top>".$err_msg."</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td align='center' valgin=top><a href='#' onclick='javascript:history.back(-1);'>back</a></td>";
		echo "</tr>";
		echo "</table>";
		exit;
	}
}

function GetPhotoDimension($file)
{
	list($width, $height) = getimagesize($file);
		$iarr[0]=$width;
		$iarr[1]=$height;
		
	return $iarr;
}

// USE
// setImageDimention(SrcFile,Size,DestFile);
// setImageDimention('t.jpg',60,'a.jpg');
//
function setImageDimention($filename,$maxwidth) 
{ 
	if($newfilename=="") 
		$newfilename=$filename; 

	// Count Dimention For Image
	list($width, $height) = getimagesize($filename); 
	
	if($width>=$maxwidth and $height>=$maxwidth)
	{
		if($height==$width)
		{
			$ar=$maxwidth*100/$width;
			$newwidth=$width*$ar/100;
			$newheight=$height*$ar/100;						
		}
		elseif($height>$width)
		{
			$ar=$maxwidth*100/$height;
			$newwidth=$width*$ar/100;
			$newheight=$height*$ar/100;
		}
		elseif($height<$width)
		{
			$ar=$maxwidth*100/$width;
			$newwidth=$width*$ar/100;
			$newheight=$height*$ar/100;
		}
	}
	else
	{
		$newwidth=$width;
		$newheight=$height;
	}
		
	$newwidth =round($newwidth);
	$newheight =round($newheight);
	
	return " width='".$newwidth."' height='".$newheight."'";
}

// USE
// operateImage(SrcFile,Size,DestFile);
// operateImage('t.jpg',60,'a.jpg');
//
function operateImage($filename,$maxwidth,$newfilename="",$maxheight='',$withSampling=true) 
{ 
	if($newfilename=="") 
		$newfilename=$filename; 

	// Count Dimention For Image
	list($width, $height) = getimagesize($filename); 
	
	if($width>=$maxwidth or $height>=$maxwidth)
	{
		if($height==$width)
		{
			$ar=$maxwidth*100/$width;
			$newwidth=$width*$ar/100;
			$newheight=$height*$ar/100;						
		}
		elseif($height>$width)
		{
			$ar=$maxwidth*100/$height;
			$newwidth=$width*$ar/100;
			$newheight=$height*$ar/100;
		}
		elseif($height<$width)
		{
			$ar=$maxwidth*100/$width;
			$newwidth=$width*$ar/100;
			$newheight=$height*$ar/100;
		}
	}
	else
	{
		$newwidth=$width;
		$newheight=$height;
	}
		
	$newwidth =round($newwidth);
	$newheight =round($newheight);
	
	
	// Load Image
	$thumb = imagecreatetruecolor($newwidth, $newheight); 
	$ext = strtolower(substr($filename,strlen($filename)-3)); 
	$path_info = pathinfo($filename);
	$ext = $path_info['extension'];
	
	if($ext=='jpg' || $ext=='jpeg')
	{
		$source = imagecreatefromjpeg($filename); 
	}
	if($ext=='gif') 
	{
		$source = imagecreatefromgif($filename); 
	}
	if($ext=='png') 
	{
		$source = imagecreatefrompng($filename); 
	}
		
	// Resize Image
	if($withSampling) 
	{
		imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height); 
	}
	else    
	{
		imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height); 
	}
		
	// Output 
	if($ext=='jpg' || $ext=='jpeg') 
	{
		return imagejpeg($thumb,$newfilename,100); 
	}
	if($ext=='gif') 
	{
		return imagegif($thumb,$newfilename,100);
	}
	if($ext=='png') 
	{
		return imagepng($thumb,$newfilename,100); 
	}
}
function operateImageReal($filename,$maxwidth,$newfilename="",$maxheight='',$withSampling=true) 
{ 
	if($newfilename=="") 
		$newfilename=$filename; 

	// Count Dimention For Image
	list($width, $height) = getimagesize($filename); 
	
	if ($width > $maxwidth || $height > $maxheight){
		if ($width>$maxwidth && $height<=$maxheight){//too wide, height is OK
			$proportion=($maxwidth*100)/$width;
			$neww=$maxwidth;
			$newh=ceil(($height*$proportion)/100);
		}		
		else if ($width<=$maxwidth && $height>$maxheight){//too high, width is OK
			$proportion=($maxheight*100)/$height;
			$newh=$maxheight;
			$neww=ceil(($width*$proportion)/100);
		}            
		else {//too high and too wide
			if ($width/$maxwidth > $height/$maxheight){//width is the bigger problem
				$proportion=($maxwidth*100)/$width;
				$neww=$maxwidth;
				$newh=ceil(($height*$proportion)/100);
			}                
			else {//height is the bigger problem
				$proportion=($maxheight*100)/$height;
				$newh=$maxheight;
				$neww=ceil(($width*$proportion)/100);
			}
		}
	}        
	else {//copy image even if not resizing
		$neww=$width;
		$newh=$height;
	}
		
	$newwidth =round($neww);
	$newheight =round($newh);
	
	// Load Image
	$thumb = imagecreatetruecolor($newwidth, $newheight); 
	$ext = strtolower(substr($filename,strlen($filename)-3));
	$path_info = pathinfo($filename);
	$ext = $path_info['extension']; 
	
	if($ext=='jpg' || $ext=='jpeg')
	{
		$source = imagecreatefromjpeg($filename); 
	}
	if($ext=='gif') 
	{
		$source = imagecreatefromgif($filename); 
	}
	if($ext=='png') 
	{
		$source = imagecreatefrompng($filename); 
	}
		
	// Resize Image
	if($withSampling) 
	{
		imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height); 
	}
	else    
	{
		imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height); 
	}
		
	// Output 
	if($ext=='jpg' || $ext=='jpeg') 
	{
		return imagejpeg($thumb,$newfilename,100); 
	}
	if($ext=='gif') 
	{
		return imagegif($thumb,$newfilename,100);
	}
	if($ext=='png') 
	{
		return imagepng($thumb,$newfilename,100); 
	}
}
// USE
// operateImage(SrcFile,Size,DestFile);
// operateImage('t.jpg',60,'a.jpg');
//
function operateImage2($filename,$maxwidth,$newfilename="",$maxheight='',$withSampling=true) 
{ 
	if($newfilename=="") 
		$newfilename=$filename;
		
	// Count Dimention For Image
	list($width, $height) = getimagesize($filename); 
	
	$newwidth =round($maxwidth);
	$newheight =round($maxheight);
	
	// Load Image
	$thumb = imagecreatetruecolor($newwidth, $newheight); 
	$ext = strtolower(substr($filename,strlen($filename)-3)); 
	$path_info = pathinfo($filename);
	$ext = $path_info['extension'];
	
	if($ext=='jpg' || $ext=='jpeg')
	{
		$source = imagecreatefromjpeg($filename); 
	}
	if($ext=='gif') 
	{
		$source = imagecreatefromgif($filename); 
	}
	if($ext=='png') 
	{
		$source = imagecreatefrompng($filename); 
	}
		
	// Resize Image
	if($withSampling) 
	{
		imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height); 
	}
	else    
	{
		imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height); 
	}
	
	// Output 
	if($ext=='jpg' || $ext=='jpeg') 
	{
		return imagejpeg($thumb,$newfilename); 
	}
	if($ext=='gif') 
	{
		return imagegif($thumb,$newfilename); 
	}
	if($ext=='png') 
	{
		return imagepng($thumb,$newfilename); 
	}
}
function pager_delete($num_rows = 0,$per_page = 1,$page =1)
{
	if ($num_rows <= $per_page) { 
		$num_pages = 1; 
	} else if (($num_rows % $per_page) == 0) { 
		$num_pages = ($num_rows / $per_page); 
	} else { 
		$num_pages = ($num_rows / $per_page) + 1; 
	} 
	$num_pages = (int) $num_pages;
	if($num_pages < $page)
	{
		$page = $page - 1;
	}
	if($page <= 0)
	{
		$page = 1;
	}
	return $page;
}
?>