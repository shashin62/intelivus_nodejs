<?php
  if($user_type_admin != "Main Rights")
  {
  	$_SESSION["sadmin_changeImage_Delete"] = $access_message;
	print "<META http-equiv='refresh' content=0;URL=home.php>";
	exit;
  }
?>