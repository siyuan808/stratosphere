<?php
$dbusername = "stratos";
$dbpassword = "stratoscloud";
$dbhostname = "stratospheredbinstance.cclr2a2v3sia.us-west-2.rds.amazonaws.com"; 

//connection to the database
$dbhandle = mysql_connect($dbhostname, $dbusername, $dbpassword) 
  or die("Unable to connect to MySQL");

    mysql_select_db('test') or die('Could not select database'); 

    $name=$_POST['username'];
    $pass=$_POST['password'];
    if($name) {
        $query = 'select username from users where username=\''.$name.'\' and password=\''.$pass.'\';';

        $result = mysql_query($query) or die('Query failed:'.mysql_error());

        $arr=mysql_fetch_array($result);
        if($arr && $arr[0])
            //echo $name." log in successfully";
	     header("Location:user.html?uname=$name");
	     //echo "<meta http-equiv=refresh content=10;URL=http://ec2-67-202-55-42.compute-1.amazonaws.com/user.html>";

        else{
            echo "username and password does not match!";
        }
    }
    else    echo "invalid";

?>
