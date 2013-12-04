<?php
$uid = $_GET['userid'];

$dbhandle = mysql_connect("stratosinstance.cq9eo0agv4tp.us-west-2.rds.amazonaws.com", "stratos", "stratoscloud") or die("Unable to connect to MySQL");
mysql_select_db('stratosphere') or die('Could not select database');

$q1 = "SELECT User.uid, User.name, Url.urlid, Url.url, Url.is_readable, Url.title 
	FROM User , Store, Url 
	WHERE User.uid = '$uid' AND
		User.uid = Store.uid AND
		Store.urlid = Url.urlid
		";
$result = mysql_query($q1) or die('Query failed:'.mysql_error());

echo "[";

$row = mysql_fetch_row($result);

while($row){
	echo "{\"urlid\": $row[2],\"url\":\"$row[3]\",\"is_readable\":$row[4],\"title\":\"$row[5]\"}";
	if($row = mysql_fetch_row($result))
		echo ",";
}
echo "]";
?>
