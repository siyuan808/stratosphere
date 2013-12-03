
<?php
if (isset($_COOKIE['userid'])) {
	//echo 'Welcome ' . $_COOKIE['name'];
} else {
    header('Location: login.php');
}

$id1 = $_POST["fromid"];
$id2 = $_POST["toid"];

$dbhandle = mysql_connect("stratosinstance.cq9eo0agv4tp.us-west-2.rds.amazonaws.com", "stratos", "stratoscloud") or die("Unable to connect to MySQL");
	mysql_select_db('stratosphere') or die('Could not select database');
	

$q_addfriend = "insert into Add_Friend values('$id1', '$id2', 0)";
$result_addfriend = mysql_query($q_addfriend) or die('Query failed:'.mysql_error());

header("location:frienddisplay.php?UID=$id2");
?>