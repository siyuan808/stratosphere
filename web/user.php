<?php
if (!isset($_COOKIE['userid'])) {
    header('Location: login.php');
}

$uid = $_COOKIE['userid'];

$dbhandle = mysql_connect("stratosinstance.cq9eo0agv4tp.us-west-2.rds.amazonaws.com", "stratos", "stratoscloud") or die("Unable to connect to MySQL");
mysql_select_db('stratosphere') or die('Could not select database');

?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>My Stratosphere - Stratosphere</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600" rel="stylesheet" type="text/css" />
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
									<h2><a href="user.php">Hello, <?php echo $_COOKIE['name'] ?>!</a></h2>
								</header>
							</article>
						</div>
					</div>
                    <hr/>
                    
                    
                         <?php
                            $q1 = "SELECT * FROM Store WHERE uid = '$uid'";
							$st1 = mysql_query($q1) or die('Query failed:'.mysql_error());
							if(mysql_num_rows($st1)==0){
								echo "<header>
								<h2 style=\"font-size=20px;\"><a href=\"user.php\">No content saved yet! Come on and make your own library!</a></h2>	
								</header>";
							}
							$k = 0;
							while($row1 = mysql_fetch_row($st1)){
								if($k%3==0){	
						?>          
                    <div class="row">
                    	 
						<article class="4u special">
							
							<header>
                            	<?php
									
									$urlid = $row1[1];
									$is_public = $row1[3];
									$is_favourite = $row1[4];
											
									$q2 = "SELECT * FROM Url WHERE urlid = '$urlid'";
									$st2 = mysql_query($q2) or die('Query failed:'.mysql_error());
									$row2 = mysql_fetch_row($st2);
									$title = $row2[3];
								
								echo "<h3><a href=display.php?urlid=$urlid&uid=$uid>$title</a></h3>";
								?>
                                
							</header>
                            <footer>
                            
                            	<form style="display:inline;" action="userfunction.php" method="POST">
                            	<?php
                                 
								 echo "<input type=\"hidden\" name=\"urlid\" value=\"$urlid\" />";
                                ?>
                                
                                 
                                 <?php
								 if($is_favourite==0){
									 echo "
                                 <input  style=\"padding: 1px 4px; font-size: 0.8em; color: #FFF8DC;\" class=\"button\" type=\"submit\" name=\"action\" value=\"Favorite\" />"; 
								 }?>
                                
                                 <input  style="padding: 1px 4px; font-size: 0.8em; color: #FFF8DC;" class="button" type="submit" name="action" value="Delete" />
                                 
                                 <?php
								 if($is_public==0){
                                 echo "<input  style=\"padding: 1px 4px; font-size: 0.8em; color: #FFF8DC;\" class=\"button\" type=\"submit\" name=\"action\" value=\"Public\" /> ";
								 }?>
                                 
                                 </form>
                                
                                 <form style="display:inline;" action="share.php" method="POST">
                                
                                 <input type="hidden" name="urlid" value="<?php echo $urlid ?>" />
                                 
                                 <input  style="padding: 1px 4px; font-size: 0.8em; color: #FFF8DC;" class="button" type="submit" name="action" value="Share" />
                                 </form>
                                 
							</footer>
						</article>
                         
                    <?php
					$k = $k +1;
					}
						while($k%3 != 0 && $row1 = mysql_fetch_row($st1)){
						
					?>
                    <article class="4u special">
							
							<header>
                            	<?php
									
									$urlid = $row1[1];
									$is_public = $row1[3];
									$is_favourite = $row1[4];
											
									$q2 = "SELECT * FROM Url WHERE urlid = '$urlid'";
									$st2 = mysql_query($q2) or die('Query failed:'.mysql_error());
									$row2 = mysql_fetch_row($st2);
									$title = $row2[3];
								
								echo "<h3><a href=display.php?urlid=$urlid&uid=$uid>$title</a></h3>";
								?>
                                
							</header>
                            <footer>
                            
                            	<form style="display:inline;" action="userfunction.php" method="POST">
                            	<?php
                                 
								 echo "<input type=\"hidden\" name=\"urlid\" value=\"$urlid\" />";
                                ?>
                                
                                 
                                 <?php
								 if($is_favourite==0){
								echo"
                                 <input  style=\"padding: 1px 4px; font-size: 0.8em; color: #FFF8DC;\" class=\"button\" type=\"submit\" name=\"action\" value=\"Favorite\" /> ";
								 }?>
                                
                                 <input  style="padding: 1px 4px; font-size: 0.8em; color: #FFF8DC;" class="button" type="submit" name="action" value="Delete" />
                                 
                                 <?php
								 if($is_public==0){
									 echo"
                                 <input  style=\"padding: 1px 4px; font-size: 0.8em; color: #FFF8DC;\" class=\"button\" type=\"submit\" name=\"action\" value=\"Public\" />";
								 }
								 ?>
                                 </form>
                                
                                 <form style="display:inline;" action="share.php" method="POST">
                                
                                 <input type="hidden" name="urlid" value="<?php echo $urlid ?>" />
                                 
                                 <input  style="padding: 1px 4px; font-size: 0.8em; color: #FFF8DC;" class="button" type="submit" name="action" value="Share" />
                                 </form>
                                 
							</footer>
						</article>
                        <?php
						$k = $k +1;
					}
							?>
                            </div>
                            <?php
							}
							?>
                    
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
										<h3><a href=contact.php>Contact Us</a></h3>
									</header>
									<p>Follow Us</p>
									<ul class="icons">

										<li><a href="https://twitter.com/StratosphereUFL" class="fa fa-twitter solo"><span>Twitter</span></a></li>
										<li><a href="https://www.facebook.com/profile.php?id=100007212336523" class="fa fa-facebook solo"><span>Facebook</span></a></li>
										<li><a href="https://plus.google.com/u/0/112539309182150323730/posts" class="fa fa-google-plus solo"><span>Google+</span></a></li>
									</ul>
								</section>
							
							<!-- Copyright -->
								<div class="copyright">
									<ul class="menu">
										<li>&copy; 2013 Stratosphere. All Rights Reserved.</li>
																			</ul>
								</div>
							
						</div>
					
					</div>
				</div>
			</div>

	</body>
</html>