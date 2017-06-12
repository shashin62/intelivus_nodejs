<?php
$db_username = "hoover";
$db_password = "ktabtky";
$db_name = "hoover";
$db_host = "54.255.136.38";

set_time_limit(0);
ini_set('display_errors', 0);

$db2=mysqli_connect($db_host, $db_username, $db_password,$db_name);
if (!$db) {
	echo "Error: Unable to connect to MySQL." . PHP_EOL;
	echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
	echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
	exit;
}
?>