<?php
if (isset($_COOKIE['userid'])) {
	//echo 'Welcome ' . $_COOKIE['name'];
} else {
    header('Location: login.php');
}
?>

<!DOCTYPE HTML>

<html>
	<head>
		<title>My Stratosphere - Friend's Page</title>
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
						<!--	<li><a href="index.php">Home</a></li> -->
                            
							<li>
								<span>Action</span>
								<ul>
									<li><a href="add_url.php">Add URL</a></li>
									<li><a href="favorites.php">Favourites</a></li>
                                   				<li><a href="sharedwithme.php">Shared with me</a></li>
									<!--
									<li>
										<span>Options</span>
										<ul>
											<li><a href="#">Option 1</a></li>
											<li><a href="#">Option 2</a></li>
											<li><a href="#">Option 3</a></li>
											<li><a href="#">Option 4</a></li>
										</ul>
									</li>
									<li><a href="#">Help</a></li>
                                    -->
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
                                <?php 
								$userid = $_COOKIE['userid'];
								
								if($_GET["UID"]){
									
									//friend id
									$toid = $_GET["UID"];
									
									if ($toid != $queryid){
										//database connection
										$dbhandle = mysql_connect("stratosinstance.cq9eo0agv4tp.us-west-2.rds.amazonaws.com", "stratos", "stratoscloud") or die("Unable to connect to MySQL");
	mysql_select_db('stratosphere') or die('Could not select database');
									
									$q_check = "select * from User where uid='$toid'";
									
									$q_checkfriend = "select * from Add_Friend where from_id = '$userid' and to_id = '$toid' and is_friend = 1
									union 
									select * from Add_Friend where from_id = '$toid' and to_id = '$userid' and is_friend = 1";
									$q_friendpending1 = "select * from Add_Friend where from_id = '$userid' and to_id = '$toid' and is_friend = 0";
									$q_friendpending2 = "select * from Add_Friend where from_id = '$toid' and to_id = '$userid' and is_friend = 0";
	
									$result_check = mysql_query($q_check) or die('Query failed:'.mysql_error());
									$result1 = mysql_fetch_row($result_check);
									
									if($result1){
										echo "<h2> This is home of " . $result1[3] . "</h2> </br> ";
										
										$result_checkfriend = mysql_query($q_checkfriend) or die('Query failed:'.mysql_error());
									
										
										if(mysql_num_rows($result_checkfriend)==0){
											
											//you've sent a request
										$result_pending1 = mysql_query($q_friendpending1) or die('Query failed:'.mysql_error());
									if(mysql_num_rows($result_pending1)!=0){
										echo "<p>You've sent the friend request.</p>";
										echo "</header></article></div></div><hr/>";
										}else{
											//waiting your response
											$result_pending2 = mysql_query($q_friendpending2) or die('Query failed:'.mysql_error());
											if(mysql_num_rows($result_pending2)!=0){
										echo "<p>User is waiting for your response to his/her request.</p>";
										echo "</header></article></div></div><hr/>";
										}else{
											echo "<p> not friend yet! </p>";
											echo "<form action=\"addfriend.php\" method=\"post\" id=\"addfiendform\">
	
	<input type=\"hidden\" value=\"$userid\" name=\"fromid\"/>
	<input type=\"hidden\" value=\"$toid\" name=\"toid\"/>
	
	<input style=\"padding: 5px 42px; \" class=\"button\" type=\"submit\" value=\"Add friend\"/>
	</form>";
	echo "</header></article></div></div><hr/>";
											}
											
										}
										
    									}else{
											echo "<p> you are friends. </p>";
											
											echo "</header></article></div></div><hr/>";
					
											//display friend's hoomepage.
											$q1 = "SELECT * FROM Store WHERE uid = '$toid' and is_public = 1";
							$st1 = mysql_query($q1) or die('Query failed:'.mysql_error());
							
							/*
							echo $q1;
							while($row1 = mysql_fetch_row($st1)){
								$urlid = $row1[1];
								$q2 = "SELECT * FROM Url WHERE urlid = '$urlid'";
								$st2 = mysql_query($q2) or die('Query failed:'.mysql_error());
								echo $q2;
								$row2 = mysql_fetch_row($st2);
								$title = $row2[3];
								
								echo $title;
								}*/
							if(mysql_num_rows($st1)==0){
								echo "<header>
								<h2 style=\"font-size=20px;\"><a href=\"friend.php\">Your friend has no public contents yet.</a></h2>	
								</header>";
							}
	
							
							$k = 0;
							while($row1 = mysql_fetch_row($st1)){
								if($k%3==0){
									echo "<div class=\"row\">
                    	 
						<article class=\"4u special\">
							
							<header>";
								$urlid = $row1[1];
								$q2 = "SELECT * FROM Url WHERE urlid = '$urlid'";
								$st2 = mysql_query($q2) or die('Query failed:'.mysql_error());
								$row2 = mysql_fetch_row($st2);
								$title = $row2[3];
								
								echo "<h3><a href=display.php?urlid=$urlid&uid=$toid>$title</a></h3>";
								echo "</header>
								<footer></footer>";
								echo "</article>";
								$k = $k +1;
							}
								while($k%3 != 0 && $row1 = mysql_fetch_row($st1)){
									echo "<article class=\"4u special\">
							
							<header>";
									$urlid = $row1[1];
											
									$q2 = "SELECT * FROM Url WHERE urlid = '$urlid'";
									$st2 = mysql_query($q2) or die('Query failed:'.mysql_error());
									$row2 = mysql_fetch_row($st2);
									$title = $row2[3];
								
								echo "<h3><a href=display.php?urlid=$urlid&uid=$toid>$title</a></h3>";
								echo "</header>
                            <footer></footer>
						</article>";	
						$k = $k +1;
					}
					echo "</div>";
					
					}
					
	
						}
										
											}
										else{
											echo"<h3>Sorry! No such ID matches!
        										<p>
           									 	<a href=login.php>Please try again</a> or 
            									 <a href=signup.php>Register</a>!
        										</p>
            									</h3>";
											echo "</header></article></div></div><hr/>";
											}
										}
									else{
										header("location:user.php");
									}
																
									
								}
								else{
									echo "No UID sent!";
									echo "</header></article></div></div><hr/>";
									}
								?>
									
									
								
                    
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
										<li><a href="https://twitter.com/StratosphereUFL" class="fa fa-twitter solo"><span>Twitter</span></a></li>
										<li><a href="https://www.facebook.com/profile.php?id=100007212336523" class="fa fa-facebook solo"><span>Facebook</span></a></li>
										<li><a href="https://plus.google.com/u/0/112539309182150323730/posts" class="fa fa-google-plus solo"><span>Google+</span></a></li>
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