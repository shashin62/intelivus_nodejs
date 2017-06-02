<?php
session_start();
include("includes/connection.php");
include("includes/con2.php");
include("includes/functions.php");
require("includes/funcstuffs.php");
/*
$qry = "select * from INPUT";
$srs = mysqli_query($db,$qry) or die("cannot select Records".mysqli_error($db));
while($fr = mysqli_fetch_array($srs)){
	$form_data = array(
		'COMPANY_NAME1' => strrev($fr["COMPANY_NAME1"]),
        'COMPANY_NAME2' => strrev($fr["COMPANY_NAME2"])
	);
	dbRowUpdate($db,"input",$form_data,'where id='.$fr["ID"]);
}




/*

// Generates a strong password of N length containing at least one lower case letter,
// one uppercase letter, one digit, and one special character. The remaining characters
// in the password are chosen at random from those four sets.
//
// The available characters in each set are user friendly - there are no ambiguous
// characters such as i, l, 1, o, 0, etc. This, coupled with the $add_dashes option,
// makes it much easier for users to manually type or speak their passwords.
//
// Note: the $add_dashes option will increase the length of the password by
// floor(sqrt(N)) characters.

echo $pass = generateStrongPassword();

function generateStrongPassword($length = 9, $add_dashes = false, $available_sets = 'luds')
{
	$sets = array();
	if(strpos($available_sets, 'l') !== false)
		$sets[] = 'abcdefghjkmnpqrstuvwxyz';
	if(strpos($available_sets, 'u') !== false)
		$sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
	if(strpos($available_sets, 'd') !== false)
		$sets[] = '23456789';
	if(strpos($available_sets, 's') !== false)
		$sets[] = '!@#$%&*?';

	$all = '';
	$password = '';
	foreach($sets as $set)
	{
		$password .= $set[array_rand(str_split($set))];
		$all .= $set;
	}

	$all = str_split($all);
	for($i = 0; $i < $length - count($sets); $i++)
		$password .= $all[array_rand($all)];

	$password = str_shuffle($password);

	if(!$add_dashes)
		return $password;

	$dash_len = floor(sqrt($length));
	$dash_str = '';
	while(strlen($password) > $dash_len)
	{
		$dash_str .= substr($password, 0, $dash_len) . '-';
		$password = substr($password, $dash_len);
	}
	$dash_str .= $password;
	return $dash_str;
}

if (extension_loaded('mcrypt') === true)
    {
        echo "Mcrypt exist !";
    } 
	
// The password
$passphrase = 'My secret';
 
// Turn a human readable passphrase
// into a reproducible iv/key pair
 
$iv = substr(md5("\x1B\x3C\x58".$passphrase, true), 0, 8);
$key = substr(md5("\x2D\xFC\xD8".$passphrase, true) .
md5("\x2D\xFC\xD9".$passphrase, true), 0, 24);
$opts = array('iv' => $iv, 'key' => $key, 'mode' => 'stream');
 
// Open the file
$fp = fopen('secret-file.enc', 'wb');
 
// Add the Mcrypt stream filter
// We use Triple DES here, but you
// can use other encryption algorithm here
stream_filter_append($fp, 'mcrypt.tripledes', STREAM_FILTER_WRITE, $opts);
 
// Wrote some contents to the file
fwrite($fp, 'Secret secret secret data');
 
// Close the file
fclose($fp);
*/
?>