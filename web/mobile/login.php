<?php
if(isset($_POST['username'])){

	$username = $_POST['username'];
	$password = $_POST['password'];

	$dbhandle = mysql_connect("stratosinstance.cq9eo0agv4tp.us-west-2.rds.amazonaws.com", "stratos", "stratoscloud") or die("Unable to connect to MySQL");
	mysql_select_db('stratosphere') or die('Could not select database');

	$query = "select * from User where username='$username' and password='$password'";
	$result = mysql_query($query) or die('Query failed:'.mysql_error());
       $row=mysql_fetch_row($result);
	if($row){
	echo $row[0];
       }
}else{
	echo '-1';
}
?>
