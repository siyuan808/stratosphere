
<?php
if (isset($_COOKIE['userid'])) {
	//echo 'Welcome ' . $_COOKIE['name'];
} else {
    header('Location: login.php');
}

$requestid = $_POST['requestid'];
$userid = $_COOKIE['userid'];

$dbhandle = mysql_connect("stratosinstance.cq9eo0agv4tp.us-west-2.rds.amazonaws.com", "stratos", "stratoscloud") or die("Unable to connect to MySQL");
	mysql_select_db('stratosphere') or die('Could not select database');

if($_POST['action'] == 'confirm'){
	echo "confirm!";
	$q_confirm = "update Add_Friend set is_friend=1 where from_id='$requestid' and to_id='$userid'";
	$result_confirm = mysql_query($q_confirm) or die('Query failed:'.mysql_error());
	header("location:frienddisplay.php?UID=$requestid");
	}
	else if ($_POST['action'] == 'reject'){
		echo "reject!";
		$q_reject = "delete from Add_Friend WHERE from_id='$requestid' and to_id='$userid' and is_friend=0";
		$result_reject = mysql_query($q_reject) or die('Query failed:'.mysql_error());
		header("location:friend.php");
		}
?>