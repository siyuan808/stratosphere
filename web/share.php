<?php
if (isset($_COOKIE['userid'])) {
	//echo 'Welcome ' . $_COOKIE['name'];
} else {
    header('Location: login.php');
}

$urlid = $_POST['urlid'];

session_start();
// store session data
$_SESSION['urlid'] = $urlid;

header("location:sharedisplay.php");
?>