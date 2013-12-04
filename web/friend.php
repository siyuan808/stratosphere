<?php
if (isset($_COOKIE['userid'])) {
	//echo 'Welcome ' . $_COOKIE['name'];
} else {
    header('Location: login.php');
}
?>

<!DOCTYPE HTML>
<!--
	Helios 1.5 by HTML5 UP
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>My Stratosphere - Friends</title>
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
	<body class="left-sidebar">

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
									<li><a href="#">Help</a></li> -->
                                    
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
						<div class="4u" id="sidebar">
							<hr class="first" />
							<section>
								<header>
									<h3>Notification center</h3>
                                    Hello, <?php echo $_COOKIE['name'] ?></h2>
								</header>
								<?php
								//check if there's notification for the user.
								//connection
								$dbhandle = mysql_connect("stratosinstance.cq9eo0agv4tp.us-west-2.rds.amazonaws.com", "stratos", "stratoscloud") or die("Unable to connect to MySQL");
	mysql_select_db('stratosphere') or die('Could not select database');
	
	$queryid = $_COOKIE['userid'];
	//is_friend = 0 means pending request.
	//fetch the requests others sent to you.	
	$query = "select * from User where uid in (select from_id from Add_Friend where to_id = '$queryid' and is_friend = 0);";
	$result = mysql_query($query) or die('Query failed:'.mysql_error());
	
	if(mysql_num_rows($result)==0){
		echo "<p> no new notifications</p>";
		 
    }else{
		echo "<p> new friend requests!</p>";
		//list up the friend requests the user have
		echo "<table border='1'>
		<tr align=\"left\">
		<th>Name</th>
		<th colspan=\"2\"> Actions </th> 
		</tr>";
		
		$i = 0;
		while($rowrequests = mysql_fetch_row($result)and $i < mysql_num_rows($result)){
			$requstid = $rowrequests[0];
			$requestname = $rowrequests[3];
			echo "<form action=confirm_or_rejectfriend.php method=\"post\" id=\"comfirmrejectform\"> <tr>";
  			echo "<td> <input type=\"hidden\" name=\"requestid\" value=\"$requstid\"> " . $requestname. "</td>";
			echo "<td> <input type=\"submit\" value=\"confirm\" name=\"action\" class=\"button\" style=\"padding: 1px 10px; \"> </td>";
			echo "<td> <input type=\"submit\" value=\"reject\" name=\"action\" class=\"button\" style=\"padding: 1px 10px; \"> </td>";
  			echo "</form> </tr>";
			$i++;
			}
		echo "</table> ";
	}
	
?>

								<footer>
                                </footer>
							</section>
							<hr />
                            
                            <section>
								<header>
									<h3>Find Friends</h3>
								</header>
								<p>
									<form action="find_friend.php" method="post" id="searchfiendform">								<ul class="link-list">
                                        <li>Find your friend: <input type="text" name="friendname"/> <input style="padding: 1px 10px; font-size: 1em;" class="button" type="submit" value="search"/></li>  			
                                    </ul>
                                </form>
								</p>
								
							</section>
                            
                            
							
						</div>
						<div class="8u skel-cell-important" id="content">
							<article id="main">
								<header>
									<h2>Your Friends</h2>
									
								</header>
								
								
									<?php 
									
									$dbhandle = mysql_connect("stratosinstance.cq9eo0agv4tp.us-west-2.rds.amazonaws.com", "stratos", "stratoscloud") or die("Unable to connect to MySQL");
	mysql_select_db('stratosphere') or die('Could not select database');
	
									//chech if the user have friends
									$queryid1 = $_COOKIE['userid'];
									//is_friend = 1 means confirmed friends.	
	$query1 = "select * from User where uid in (select from_id from Add_Friend where to_id = '$queryid1' and is_friend = 1) 
	union 
	select * from User where uid in (select to_id from Add_Friend where from_id = '$queryid1' and is_friend = 1);";
	$result1 = mysql_query($query1) or die('Query failed:'.mysql_error());
    
	
	
	if(mysql_num_rows($result1)==0){
		echo "<p> Sorry, You don't have friends now.";
		 
    }else{
			//list up the friend requests the user have
			
			echo "<table border=1> <tr align=left>
                        <th width=25>No.</th>
                        <th width=100>Friend Name</th> ";			
			$i = 1;
		while($rowfriends = mysql_fetch_row($result1)and $i <= mysql_num_rows($result1)){
			
			//$rowfriends = mysql_fetch_row($result1);
			//$friendname = mysql_result($result1,0,'NAME');
			//$send = mysql_result($result1,0,'UID');
			$friendname = $rowfriends[3];
			$send = $rowfriends[0];
			echo "<tr>
			<td>$i</td>";
			echo "<td><a href=frienddisplay.php?UID=$send>$friendname</a></td>";
  			echo "</tr>";
			$i++;
			}
		echo "</table>";
			}
			
			mysql_close($dbhandle);
									
									?>
								
								
							</article>
						</div>
					</div>
					<hr />
					
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