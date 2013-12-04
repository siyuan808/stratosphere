<?php
if (!class_exists('S3')) require_once 'S3.php';

if (!isset($_COOKIE['userid'])) {
    header('Location: login.php');
}

$urlid = $_POST['urlid'];
$uid = $_COOKIE['userid'];

$dbhandle = mysql_connect("stratosinstance.cq9eo0agv4tp.us-west-2.rds.amazonaws.com", "stratos", "stratoscloud") or die("Unable to connect to MySQL");
mysql_select_db('stratosphere') or die('Could not select database');

if($_POST['action'] == 'Favorite'){
	$fa = "UPDATE Store SET favorite = 1 WHERE uid = '$uid' AND urlid = '$urlid'";
	$r_fa = mysql_query($fa) or die('Query failed:'.mysql_error());

}else if($_POST['action'] == 'Delete'){
	$detshare = "DELETE FROM Share_Url where urlid = '$urlid' and from_id = '$uid'";
	$r_detshare = mysql_query($detshare) or die('Query failed nima:'.mysql_error());
	
	$dets = "DELETE FROM Store WHERE uid = '$uid' AND urlid = '$urlid'";
	$r_dets = mysql_query($dets) or die('Query failed youcuo:'.mysql_error());
	
	$query="SELECT * FROM Store WHERE urlid = '$urlid'";
	$r_query = mysql_query($query) or die('Query failed bukexue:'.mysql_error());
	
	if(mysql_num_rows($r_query)==0){
		$detu = "DELETE FROM Url WHERE urlid = '$urlid'";
		$r_detu = mysql_query($detu) or die('Query failed nimei:'.mysql_error());
	}
	
	if (!defined('awsAccessKey')) define('awsAccessKey', 'AKIAIXSCBXNJH42EW6PQ');
	if (!defined('awsSecretKey')) define('awsSecretKey', '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');
	$fileToDelete = "$urlid.html"; // File to delete
	$bucketName = "ec2-67-202-55-42-stratos-userid-$uid"; // Bucket name

	$s3 = new S3(awsAccessKey, awsSecretKey);
	$s3->deleteObject($bucketName, baseName($fileToDelete));

}else if($_POST['action'] == 'Public'){
	$pub = "UPDATE Store SET is_public = 1 WHERE uid = '$uid' AND urlid = '$urlid'";
	$r_pub = mysql_query($pub) or die('Query failed:'.mysql_error());
}

header("location:user.php");
?>
