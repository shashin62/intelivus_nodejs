<?php
function fileextension($filename)
{
	$length_of_filename = strlen($file_name);
    $last_char = substr($file_name, $length_of_filename - 1, 1);
    for($i_parse_name = 0; $i_parse_name < $length_of_filename; $i_parse_name++)
    {
        $last_char = substr($file_name, $length_of_filename - $i_parse_name + 2, 1);
        if($last_char == ".")
        {
            $filename_suffix = substr($file_name, $length_of_filename - $i_parse_name + 2, $i_parse_name);
            $filename_prefix = substr($file_name, 0, $length_of_filename - strlen($filename_suffix));
            $i_parse_name = $length_of_filename;
        }
    }
	
	return $filename_suffix;
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
// operateImage(SrcFile,Size,DestFile);
// operateImage('t.jpg',60,'a.jpg');
//
function operateImage($filename,$maxwidth,$newfilename="",$maxheight='',$withSampling=true) 
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
	
	// Load Image
	$thumb = imagecreatetruecolor($newwidth, $newheight); 
	$ext = strtolower(substr($filename,strlen($filename)-3)); 
	
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
?>