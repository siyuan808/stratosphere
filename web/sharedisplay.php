<?php
if (isset($_COOKIE['userid'])) {
	//echo 'Welcome ' . $_COOKIE['name'];
} else {
    header('Location: login.php');
}

$uid = $_COOKIE['userid'];
$flag = $_POST['flag'];
$friend_name = $_POST['fname'];

session_start();
$urlid = $_SESSION['urlid'];

$dbhandle = mysql_connect("stratosinstance.cq9eo0agv4tp.us-west-2.rds.amazonaws.com", "stratos", "stratoscloud") or die("Unable to connect to MySQL");
mysql_select_db('stratosphere') or die('Could not select database');

if(isset($flag)){
	
	if($friend_name != "select your friend" && $urlid){
	$q_check = "select * from User where username = '$friend_name'";
	$r_check = mysql_query($q_check) or die('Query failed:'.mysql_error());
	$rowcheck = mysql_fetch_row($r_check);
	$fuid = $rowcheck[0];
	
	$q_checkdup = "select * from Share_Url where from_id = '$uid' and to_id = '$fuid' and urlid = '$urlid'";
	$r_checkdup = mysql_query($q_checkdup) or die('Query failed:'.mysql_error());
	if(mysql_num_rows($r_checkdup)!=0){
		echo "
		<script>
		alert(\"You have shared this article with this friend! Please share another one!\");
		window.location = \"sharedisplay.php\";
		</script>
		";
	}
	
	$q_insert = "insert into Share_Url (from_id, to_id, urlid) values ('$uid', '$fuid', '$urlid')";
	mysql_query($q_insert) or die('Query failed:'.mysql_error());
	header("location:user.php");
	}else{
		echo "
		<script>
		alert(\"No friend added! Please select again!\");
		window.location = \"sharedisplay.php\";
		</script>
		";
	}
}
?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>Stratosphere - Friend Result</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600" rel="stylesheet" type="text/css" />
		<!--[if lte IE 8]><script src="js/html5shiv.js"></script><![endif]-->
		<script src="js/jquery.min.js"></script>
		<script src="js/jquery.dropotron.min.js"></script>
		<script src="js/skel.min.js"></script>
		<script src="js/skel-panels.min.js"></script>
		<script src="js/init.js"></script>
		<noscript>
			<link rel="stylesheet" href="css/skel-noscript.css" />
			<link rel="stylesheet" href="css/style.css" />
			<link rel="stylesheet" href="css/style-desktop.css" />
			<link rel="stylesheet" href="css/style-noscript.css" />
		</noscript>
		<!--[if lte IE 8]><link rel="stylesheet" href="css/ie8.css" /><![endif]-->
	</head>
	<body class="no-sidebar">

		<!-- Header -->
			<div id="header">

				<!-- Inner -->
					<div class="inner">
						<header>
							<h1><a href="index.php" id="logo">Stratosphere</a></h1>
						</header>
					</div>
				
				<!-- Nav -->
					<nav id="nav">
						<ul>
						<!--	<li><a href="index.php">Home</a></li>  -->
                            
							<li>
								<span>Action</span>
								<ul>
									<li><a href="add_url.php">Add URL</a></li>
									<li><a href="favorites.php">Favourites</a></li>
									<li><a href="sharedwithme.php">Shared with me</a></li>
									
								</ul>
							</li>
							<li><a href="friend.php">Friends</a></li>
							<li><a href="user.php">My Stratosphere</a></li>
							<li><a href="logout.php">Log Out</a></li>
							
						</ul>
					</nav>

			</div>
			
		<!-- Main -->
			<div class="wrapper style1">

				<div class="container">
					<div class="row">
						<div class="12u skel-cell-important" id="content">
							<article id="main" class="special">
								<header>
									<h2 style="font-size:2.2em">Share With Friend:</h2>
                                  
								</header>
                                </br>
                                <form style="text-align:center; font-size:1.5em" name="input" action="sharedisplay.php" method="post">
                                
                                <input type="hidden" name="flag" value=1 />
                                <?php
									$q_f = "select * from Add_Friend where from_id = '$uid' and is_friend = 1";
									$r_f = mysql_query($q_f) or die('Query failed:'.mysql_error());
									
									$q_t = "select * from Add_Friend where to_id = '$uid' and is_friend = 1";
									$r_t = mysql_query($q_t) or die('Query failed:'.mysql_error());
									
                                    echo "<select style=\"width:230px; margin-left:auto; margin-right:auto;\" name=\"fname\">";
									echo "<option value=\"select your friend\">Please Select Your Friend</option>";
                                    while($rowf = mysql_fetch_row($r_f)) {
										$toid = $rowf[1];
										$q_fn = "select * from User where uid = '$toid'";
										$r_fn = mysql_query($q_fn) or die('Query failed:'.mysql_error());
										$rowfn = mysql_fetch_row($r_fn);
										$fn = $rowfn[1];
                                        echo "<option value=$fn>$fn</option>"; 
                                    }
									while($rowt = mysql_fetch_row($r_t)) {
										$fromid = $rowt[0];
										$q_tn = "select * from User where uid = '$fromid'";
										$r_tn = mysql_query($q_tn) or die('Query failed:'.mysql_error());
										$rowtn = mysql_fetch_row($r_tn);
										$tn = $rowtn[1];
                                        echo "<option value=$tn>$tn</option>"; 
                                    }
                                    echo "</select>";
								?>
                                </br>
								<input style="padding: 2px 20px; font-size: 0.8em; color: #FFF8DC;" class="button" type="submit" value="Share">
								</form>
                                
								
							</article>
						</div>
					</div>
					
					
				</div>

			</div>

		<!-- Footer -->
			<div id="footer">
				<div class="container">
					<div class="row">
						
						<!-- Tweets -->
							

						<!-- Posts -->
							

						<!-- Photos -->
							

					</div>
					
					<div class="row">
						<div class="12u">
							
							<!-- Contact -->
								<section class="contact">
									<header>
										<h3>Want to contact us?</h3>
									</header>
									<p>You can following us.</p>
									<ul class="icons">
										<li><a href="#" class="fa fa-twitter solo"><span>Twitter</span></a></li>
										<li><a href="#" class="fa fa-facebook solo"><span>Facebook</span></a></li>
										<li><a href="#" class="fa fa-google-plus solo"><span>Google+</span></a></li>
									</ul>
								</section>
							
							<!-- Copyright -->
								<div class="copyright">
									<ul class="menu">
										<li>&copy; 2013 Stratosphere. All rights reserved.</li>
										<li>Design: <a href="http://html5up.net/">HTML5 UP</a></li>
									</ul>
								</div>
							
						</div>
					
					</div>
				</div>
			</div>

	</body>
</html>