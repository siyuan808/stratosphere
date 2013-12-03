<!DOCTYPE HTML>

<?php
if (isset($_COOKIE['userid'])) {
	//echo 'Welcome ' . $_COOKIE['name'];
} else {
    header('Location: login.php');
}

$uid = $_COOKIE['userid'];
$urlid = $_POST['URL_ID'];
$friend_name = $_POST['fname'];
								
$dbhandle = mysql_connect("stratosinstance.cq9eo0agv4tp.us-west-2.rds.amazonaws.com", "stratos", "stratoscloud") or die("Unable to connect to MySQL");
mysql_select_db('stratosphere') or die('Could not select database');

if($friend_name && $urlid){
	$q_fid = "select * from user where username = '$friend_nam'e";
	$result_q_fid = mysql_query($q_fid) or die('Query failed:'.mysql_error());	
	$rowfid = mysql_fetch_row($result_q_findfriend);
	$fid = $rowfid[1];
	
	$q_insert = "insert into Share_Url (from_id, to_id, urlid) values ('$uid', '$fid', '$urlid')";
	mysql_query($q_insert) or die('Query failed:'.mysql_error());
}
?>

<html>
	<head>
		<title>My Stratosphere - Stratosphere</title>
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
							<h1><a href="index.html" id="logo">Stratosphere</a></h1>
						</header>
					</div>
				
				<!-- Nav -->
					<nav id="nav">
						<ul>
							<!-- <li><a href="index.php">Home</a></li> -->
                            
							<li>
								<span>Preferences</span>
								<ul>
									<li><a href="#">Add URL</a></li>
									<li><a href="#">Favourites</a></li>
                                    
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
									<h2><a href="user.php">Hello, <?php echo $_COOKIE['name'] ?>!</a></h2>
									
								</header>
							</article>
						</div>
					</div>
                    <hr/>
                    
                    
                                    
                    <div class="row">
						<article class="4u special">
							
							<header>
                            	<?php
                            		$q1 = "SELECT * FROM Store WHERE uid = '$uid'";
									$st1 = mysql_query($q1) or die('Query failed:'.mysql_error());
									while($row1 = mysql_fetch_row($st1)){
									$urlid = $row1[1];
											
									$q2 = "SELECT * FROM Url WHERE urlid = '$urlid'";
									$st2 = mysql_query($q2) or die('Query failed:'.mysql_error());
									$row2 = mysql_fetch_row($st2);
									$title = $row2[3];
								
								echo "<h3><a href=#>$title</a></h3>";
								?>
							</header>
                            <footer>
                            	<?php
                                 echo "<form action=\"favorite.php?URL_ID=$urlid\" \"method=POST\">";
                                ?>
                                   
                                 <input  style="padding: 1px 4px; font-size: 0.8em; color: #FFF8DC;" class="button" type="submit" name="favorite" value="Favorite" />
                                
                                 <input  style="padding: 1px 4px; font-size: 0.8em; color: #FFF8DC;" class="button" type="submit" name="delete" value="Delete" />
                                 
                                 <input  style="padding: 1px 4px; font-size: 0.8em; color: #FFF8DC;" class="button" type="submit" name="public" value="Public" />
                                 
                                 <input  style="padding: 1px 4px; font-size: 0.8em; color: #FFF8DC;" class="button" type="submit" name="share" value="Share" />
                                 </form>
                                 <?php
									}
								?>
							</footer>
						</article>
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